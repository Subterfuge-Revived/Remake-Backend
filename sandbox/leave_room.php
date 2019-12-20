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
    $str_invalid_room = "No room found with id: " . $room_id;
    $str_invalid_assignment = "Player not in room: " . $room_id;

    try {

        $con = utils_database::new_connection();
        $session_check = $ses->session_valid($con, $session_id);

        $player_id = $session_check["player_id"];

        if(!$session_check["valid"]) {
            throw new \Exception($str_invalid_session);
        }

        $stmt = $con->prepare("
SELECT player_count
FROM sandbox.open_rooms
WHERE id=?
");
        $stmt->bind_param("i", $room_id);

        if(!$stmt->execute()) {
            throw new \Exception($stmt->error);
        }

        $stmt->store_result();

        if(!$stmt->num_rows) {
            throw new \Exception($str_invalid_room);
        }

        $stmt->bind_result($res_player_count);

        if(!$stmt->fetch()) {
            throw new \Exception($stmt->error);
        }

        echo $res_player_count;

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
            throw new \Exception($str_invalid_assignment);
        }

        $stmt = $con->prepare("
DELETE FROM sandbox.player_open_room
WHERE player_id=? AND room_id=?
");
        $stmt->bind_param("ii", $player_id, $room_id);

        if(!$stmt->execute()) {
            throw new \Exception($stmt->error);
        }


        if($res_player_count === 1) {

            $stmt = $con->prepare("
DELETE FROM sandbox.open_rooms
WHERE sandbox.open_rooms.id = ?
");

            $stmt->bind_param("i", $room_id);

            if(!$stmt->execute()) {
                throw new \Exception($stmt->error);
            }

        } else {

            $stmt = $con->prepare("
UPDATE sandbox.open_rooms 
SET player_count = player_count - 1
WHERE id = ?
");
            $stmt->bind_param("i", $room_id);

            if(!$stmt->execute()) {
                throw new \Exception($stmt->error);
            }

            $stmt->store_result();
        }


        $json->success_join_room($room_id);

    } catch (\Exception $e) {

        $json->fail_msg($e->getMessage());
    } finally {

        $stmt->close();
        $con->close();
    }