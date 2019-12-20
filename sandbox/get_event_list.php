<?php

    require("utils_security.php");
    require("utils_database.php");
    require("utils_json.php");
    require("utils_session.php");

    $sec = new utils_security();
    $ses = new utils_session();
    $json = new utils_json();

    $session_id = $sec->rm_inject(($_POST["session_id"]));
    $room_id = $sec->rm_inject($_POST["room_id"]);

    /*
    * Constants
    */
    $str_invalid_session = "Invalid session. Authentication required";
    $str_invalid_room = "Player not associated with room";

    try {

        $con = utils_database::new_connection();
        $session_check = $ses->session_valid($con, $session_id);

        $player_id = $session_check["player_id"];

        $stmt = $con->prepare("
    SELECT *
    FROM sandbox.player_open_room
    WHERE player_id=? AND room_id=?
    ");
        $stmt->bind_param("ii", $player_id, $room_id);

        if (!$stmt->execute()) {
            throw new \Exception($stmt->error);
        }

        $stmt->store_result();
        if (!$stmt->num_rows) {
            throw new \Exception($str_invalid_room);
        }

        $stmt = $con->prepare("
    SELECT *
    FROM sandbox.ongoing_rooms
    WHERE id=?
    ");
        $stmt->bind_param("i", $room_id);

        if (!$stmt->execute()) {
            throw new \Exception($stmt->error);
        }

        $stmt->store_result();
        if (!$stmt->num_rows) {
            throw new \Exception($str_invalid_room);
        }

        $con->close();
        $con = utils_database::new_connection_events();

        $event_room = "events_room_" . $room_id;

        $stmt = $con->prepare("
SELECT * FROM events_ongoing_rooms." . $event_room . "
");

        if(!$stmt->execute()) {
            throw new \Exception($stmt->error);
        }

        $stmt->store_result();
        $stmt->bind_result($res_event_id, $res_time_issued, $res_occurs_at, $res_player_id, $res_event_msg);

        $events = array();

        while($stmt->fetch()) {

            $events[count($events)] = array(
                "event_id" => $res_event_id,
                "time_issued" => $res_time_issued,
                "occurs_at" => $res_occurs_at,
                "player_id" => $res_player_id,
                "event_msg" => $res_event_msg
            );
        }

        $json->success_retr_rooms($events);

    } catch (\Exception $e) {
        $json->fail_msg($e->getMessage());
    }
