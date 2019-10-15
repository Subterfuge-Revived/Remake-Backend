<?php

    require '/var/www/PHPMailer/vendor/autoload.php';

    require("utils_security.php");
    require("utils_database.php");
    require("utils_json.php");
    require("utils_session.php");

    $sec = new utils_security();
    $ses = new utilsSession();
    $json = new utils_json();

    $session_id = $sec->rm_inject(($_POST["session_id"]));
    $room_id = $sec->rm_inject($_POST["room_id"]);
    $occurs_at = $sec->rm_inject($_POST["occurs_at"]);
    $event_msg = $sec->rm_inject($_POST["event_msg"]);

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

        if(!$stmt->execute()) {
            throw new \Exception($stmt->error);
        }

        $stmt->store_result();
        if(!$stmt->num_rows) {
            throw new \Exception($str_invalid_room);
        }

        $stmt = $con->prepare("
SELECT *
FROM sandbox.ongoing_rooms
WHERE id=?
");
        $stmt->bind_param("i",  $room_id);

        if(!$stmt->execute()) {
            throw new \Exception($stmt->error);
        }

        $stmt->store_result();
        if(!$stmt->num_rows) {
            throw new \Exception($str_invalid_room);
        }

        $event_room = "events_room_" . $room_id;


        $stmt = $con->prepare("
INSERT INTO events_ongoing_rooms." . $event_room . " (time_issued, occurs_at, player_id, event_msg)
VALUES (?,?,?,?)
");
        $stmt->bind_param("iiis", time(), $occurs_at, $player_id, $event_msg);

        if(!$stmt->execute()) {
            throw new \Exception($stmt->error);
        }

        $json->success_join_room($room_id);

    } catch (\Exception $e) {
        $json->fail_msg($e->getMessage());
    }
