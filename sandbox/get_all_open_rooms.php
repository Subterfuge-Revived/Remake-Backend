<?php

    require("utils_security.php");
    require("utils_database.php");
    require("utils_json.php");
    require("utils_session.php");

    $sec = new utils_security();
    $ses = new utils_session();
    $json = new utils_json();

    $session_id = $sec->rm_inject(($_POST["session_id"]));

    /*
    * Constants
    */
    $str_invalid_session = "Invalid session. Authentication required";
    $str_insufficient_rating = "Insufficient rating";

    try {
        $con = utils_database::new_connection();
        $session_check = $ses->session_valid($con, $session_id);

        if(!$session_check["valid"]) {
            throw new \Exception($str_invalid_session);
        }

        $stmt = $con->prepare("
SELECT *
FROM sandbox.open_rooms
");

        if(!$stmt->execute()) {
            throw new \Exception($stmt->error);
        }

        $stmt->bind_result($room_id, $creator_id, $rated, $max_players, $player_count,
            $min_rating, $description, $goal, $anonymity, $map, $seed);
        $stmt->store_result();

        $arr = array();

        while($stmt->fetch()) {

            if($anonymity === 1) {

                $players = array_fill(0, $player_count, "Anonymous");
            } else {

                $stmt2 = $con->prepare("
SELECT player_administrative_info.id, player_administrative_info.player_name
FROM sandbox.player_open_room
JOIN sandbox.player_administrative_info ON player_open_room.player_id = player_administrative_info.id AND player_open_room.room_id = ?
");

                $stmt2->bind_param("i", $room_id);

                if(!$stmt2->execute()) {
                    throw new \Exception($stmt2->error);
                }

                $stmt2->store_result();
                $stmt2->bind_result($player_id, $player_name);

                $players = array();

                while($stmt2->fetch()) {

                    $players[count($players)] = array(
                        "id" => $player_id,
                        "name" => $player_name
                    );
                }
            }

            $arr[count($arr)] = array(
                "creator_id" => $creator_id,
                "rated" => $rated == 1 ? true : false,
                "max_players" => $max_players,
                "player_count" => $player_count,
                "min_rating" => $min_rating,
                "description" => $description,
                "goal" => $goal,
                "anonymity" => $anonymity == 1 ? true : false,
                "map" => $map,
                "seed" => $seed,
                "players" => $players
            );
        }

        $json->success_retr_rooms($arr);

    } catch (\Exception $e) {

        $json->fail_msg($e->getMessage());
    } finally {

        $stmt->close();
        $con->close();
    }
