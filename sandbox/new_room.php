<?php

    use mofodojodino\ProfanityFilter\Check;

    require '/var/www/PHPMailer/vendor/autoload.php';

    require("utils_security.php");
    require("utils_database.php");
    require("utils_json.php");
    require("utils_session.php");

    $sec = new utils_security();
    $ses = new utils_session();
    $json = new utils_json();
    $check = new Check();

    $session_id = $sec->rm_inject(($_POST["session_id"]));
    $description = $sec->rm_inject($_POST["description"]);
    $max_players = $sec->rm_inject($_POST["max_players"]);
    $min_rating = $sec->rm_inject($_POST["min_rating"]);
    $rated = $sec->rm_inject($_POST["rated"]) === 'true' ? 1 : 0;
    $goal = $sec->rm_inject($_POST["goal"]);
    $anonymity = $sec->rm_inject($_POST["anonymity"]) === 'true' ? 1 : 0;
    $map = $sec->rm_inject($_POST["map"]);

    /*
    * Constants
    */
    $str_invalid_description = "Description contains bad words";
    $str_invalid_max_players = "Invalid max players amount";
    $str_invalid_map = "Invalid map type";
    $str_invalid_rating = "Invalid rating";
    $str_invalid_session = "Invalid session. Authentication required";
    $str_invalid_min_rating = "Invalid min rating";
    $arr_player_count = [2,3,4,5,6,7,8,9,10];
    $arr_goal = [0,1];
    $arr_map = [0,1,2,3];
    $int_initial_player_count = 1;

    try {

        if($check->hasProfanity($description)) {
            throw new \Exception($str_invalid_description);
        } elseif (!in_array($max_players, $arr_player_count)) {
            throw new \Exception($str_invalid_max_players);
        } elseif (!in_array($map, $arr_map)) {
            throw new \Exception($str_invalid_map);
        } elseif (!in_array($goal, $arr_goal)) {
            throw new \Exception($str_invalid_map);
        } elseif(!($min_rating >= 0)) {
            throw new \Exception($str_invalid_rating);
        } else {

            $con = utils_database::new_connection();
            $session_check = $ses->session_valid($con, $session_id);

            if(!$session_check["valid"]) {

                throw new \Exception($str_invalid_session);
            }

            $player_id = $session_check["player_id"];

            if($rated == 1) {

                $stmt = $con->prepare("
SELECT rating
FROM sandbox.player_statistics 
WHERE player_id=?
");
                $stmt->bind_param("i", $player_id);
                if(!$stmt->execute()) {
                    throw new \Exception($stmt->error);
                }
                $stmt->store_result();
                $stmt->bind_result($res_player_rating);

                if(!$stmt->fetch()) {
                    throw new \Exception($stmt->error);
                }

                if($min_rating > $res_player_rating) {

                    throw new \Exception($str_invalid_min_rating);
                }
            } else {
                $min_rating = 0;
            }

            $stmt = $con->prepare("
INSERT INTO sandbox.open_rooms (creator_id, rated, max_players, player_count, min_rating, description, goal, anonymity, map, seed)
VALUES (?,?,?,?,?,?,?,?,?,?)
");
            $time = time();

            $stmt->bind_param("iiiiisiiii", $player_id, $rated, $max_players, $int_initial_player_count,
                $min_rating, $description, $goal, $anonymity, $map, $time);
            if(!$stmt->execute()) {
                throw new \Exception($stmt->error);
            }
            $stmt->store_result();

            $new_id = $con->insert_id;

            $stmt = $con->prepare("
INSERT INTO sandbox.player_open_room (player_id, room_id)
VALUES(?,?)
");

            $stmt->bind_param("ii", $player_id, $new_id);
            if(!$stmt->execute()) {
                throw new \Exception($stmt->error);
            }
            $stmt->store_result();

            $json->success_new_room($player_id, $rated, $max_players, $int_initial_player_count,
                $min_rating, $description, $goal, $anonymity, $map, $time);
        }

    } catch (\Exception $e) {
        $json->fail_msg($e->getMessage());
    } finally {

        $stmt->close();
        $con->close();
    }