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

    /*
    * Constants
    */
    $str_invalid_session = "Invalid session. Authentication required";
    $str_insufficient_rating = "Insufficient rating";
    $str_invalid_room = "No room found with id: " . $room_id;

    try {

        $con = utils_database::new_connection();
        $session_check = $ses->session_valid($con, $session_id);

        $player_id = $session_check["player_id"];

        if (!$session_check["valid"]) {
            throw new \Exception($str_invalid_session);
        }

        $stmt = $con->prepare("
SELECT creator_id, player_count
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

        $stmt->bind_result($res_creator_id, $res_player_count);

        if(!$stmt->fetch()) {
            throw new \Exception($stmt->error);
        }

        if($res_creator_id == $player_id && $res_player_count > 1) {

            $stmt = $con->prepare("
INSERT INTO sandbox.ongoing_rooms
SELECT id, creator_id, rated, player_count, min_rating, description, goal, anonymity, map, seed
FROM sandbox.open_rooms
WHERE id = ?
");
            $stmt->bind_param("i", $room_id);

            if(!$stmt->execute()) {
                throw new \Exception($stmt->error);
            }

            $stmt->store_result();

            $stmt = $con->prepare("
DELETE FROM sandbox.open_rooms
WHERE sandbox.open_rooms.id = ?
");

            $stmt->bind_param("i", $room_id);

            if(!$stmt->execute()) {
                throw new \Exception($stmt->error);
            }

            $stmt->store_result();

            // Safe against injections since this part is only reached if the room_id is a valid one
            $event_table_name = "events_room_" . $room_id;
            $event_table_name_pk = "room_" . $room_id . "_pk";

            $con->close();
            $con = utils_database::new_connection_events();

            $stmt = $con->prepare("
create table " . $event_table_name . "
(
    event_id int auto_increment,
	time_issued int not null,
	occurs_at int not null,
	player_id int not null,
	event_msg varchar(200) not null,
	constraint " . $event_table_name_pk . "
		primary key (event_id)
);
");

            if(!$stmt->execute()) {
                throw new \Exception($stmt->error);
            }

            $stmt->store_result();
        }

    } catch (\Exception $e) {
        $json->fail_msg($e->getMessage());
    }
