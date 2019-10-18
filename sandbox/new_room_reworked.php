<?php

    use mofodojodino\ProfanityFilter\Check;

    require '/var/www/PHPMailer/vendor/autoload.php';
    require("utils_security.php");
    require("utils_database.php");
    require("utils_json.php");
    require("utils_session.php");

    $sec   = new utils_security();
    $json  = new utils_json();
    $check = new Check();

    /*
     * Input
     */
    $in_session_id  = $sec->rm_inject($_POST["session_id"]);
    $in_description = $sec->rm_inject($_POST["description"]);
    $in_max_players = $sec->rm_inject($_POST["max_players"]);
    $in_min_rating  = $sec->rm_inject($_POST["min_rating"]);
    $in_rated       = $sec->rm_inject($_POST["rated"]) === 'true' ? 1 : 0;
    $in_anonymity   = $sec->rm_inject($_POST["anonymity"]) === 'true' ? 1 : 0;
    $in_goal        = $sec->rm_inject($_POST["goal"]);
    $in_map         = $sec->rm_inject($_POST["map"]);

    /*
     * Constants
     */
    $out_invalid_description = "[new_room] Description contains bad words";
    $out_invalid_input       = "[new_room] Invalid inputs received";
    $out_invalid_session     = "[new_room] Invalid session. Authentication required";
    $out_invalid_min_rating  = "[new_room] Invalid minimum rating";
    $arr_player_count        = [2,3,4,5,6,7,8,9,10];
    $arr_map                 = [0,1,2,3];
    $arr_goal                = [0,1];
    $int_init_player_count   = 1;

    // Begin
    try {

        if( !in_array($in_max_players, $arr_player_count)
            || !in_array($in_map, $arr_map)
            || !in_array($in_goal, $arr_goal)
            || !($in_min_rating >= 0) ) {

            throw new \Exception($out_invalid_input);
        } else if( $check->hasProfanity($in_description) ) {

            throw new \Exception($out_invalid_description);
        } else {

            // Connect to sandbox database
            $db = new utils_database(utils_database::new_connection());
            $func_ses = (new utils_session($db))->reworked_is_session_valid($in_session_id);

            $func_player_id = $func_ses->getPlayerId();

            // Check if game is rated
            if( $in_rated === 1 ) {

                $db->bind_req($func_player_id)
                    ->bind_res($res_player_rating)
                    ->exec_db("
                    SELECT rating
                    FROM sandbox.player_statistics 
                    WHERE player_id=?");

                if( $in_min_rating > $res_player_rating ) {

                    throw new \Exception($out_invalid_min_rating);
                }
            } else {

                $in_min_rating = 0;
            }

            $func_time = time();

            // Insert new open room
            $db->bind_req($func_player_id, $in_rated, $in_max_players, $int_init_player_count,
                $in_min_rating, $in_description, $in_goal, $in_anonymity, $in_map, $func_time)
                ->exec_db("
                INSERT INTO sandbox.open_rooms (creator_id, rated, max_players, player_count, min_rating, description, goal, anonymity, map, seed)
                VALUES (?,?,?,?,?,?,?,?,?,?)");

            $func_insert_id = $db->getInsertId();

            // Insert player associated to new open room
            $db->bind_req($func_player_id, $func_insert_id)
                ->exec_db("
                INSERT INTO sandbox.player_open_room (player_id, room_id)
                VALUES(?,?)");

            $json->success_new_room($func_player_id, $in_rated, $in_max_players, $int_init_player_count,
                $in_min_rating, $in_description, $in_goal, $in_anonymity, $in_map, $func_time);
        }
    } catch (\Exception $e) {

        $json->fail_msg($e->getMessage());
    }