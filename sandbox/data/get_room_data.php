<?php

    /*
     * Input
     */

    if(isset($_POST["session_id"])){

        $in_session_id          = $sec->rm_inject(($_POST["session_id"]));

        if(isset($_POST["room_status"])) {
            $in_room_status = $sec->rm_inject(($_POST["room_status"])) === 'ongoing' ? 1 : 0;
        } else {
            $in_room_status = 0;
        }

        if(isset($_POST["filter_by_player"])){
            $in_filter_by_player    = $sec->rm_inject(($_POST["filter_by_player"])) === 'true' ? 1 : 0;
        } else {
            $in_filter_by_player = 0;
        }

    } else {
        $json->fail_msg("Unauthorized.");
    }

    // Begin
    try {

        $db = new utils_database(utils_database::new_connection());
        $func_ses = (new utils_session($db))->reworked_is_session_valid($in_session_id);

        $func_player_id = $func_ses->getPlayerId();

        $func_temp_stmt =
            "SELECT *
             FROM sandbox.open_rooms";

        if( $in_filter_by_player === 1 ) {

            $func_temp_stmt =
                "SELECT room_id, creator_id, rated, max_players, player_count, min_rating, description, goal, anonymity, map, seed
                 FROM sandbox.player_open_room
                 INNER JOIN sandbox.open_rooms o ON player_open_room.room_id = o.id AND player_open_room.player_id=?";
        }
        if( $in_room_status === 1 ) {

            $func_temp_stmt =
                "SELECT room_id, creator_id, started_at, rated, player_count, min_rating, description, goal, anonymity, map, seed
                 FROM sandbox.player_open_room
                 INNER JOIN sandbox.ongoing_rooms o on player_open_room.room_id = o.id AND player_open_room.player_id=?";
        }

        // Get Room Data (either player specific or all)
        $db->bind_req($func_player_id);
        if( $in_room_status === 1 )
            $db->bind_res($res_room_id, $res_creator_id, $res_started_at, $res_rated,
                $res_max_players, $res_min_rating, $res_description, $res_goal, $res_anonymity,
                $res_map, $res_seed);
        else
            $db->bind_res($res_room_id, $res_creator_id, $res_rated, $res_max_players,
                $res_player_count, $res_min_rating, $res_description, $res_goal, $res_anonymity,
                $res_map, $res_seed);

        $db->exec_db($func_temp_stmt);

        $func_players = array();

        foreach( (array)$res_room_id as $i => $el ) {

            // Check if player names should be replaced
            if( ((array)$res_anonymity)[$i] === 1 ) {

                $func_players[$i] = array_fill(0, ((array)$res_player_count)[$i], "Anonymous");
            } else {

                $func_bind = ((array)$res_room_id)[$i];

                // Get all Players of Room
                $db->bind_req($func_bind)
                    ->bind_res($res_player_id, $res_player_name)
                    ->exec_db("
                    SELECT player_administrative_info.id, player_administrative_info.player_name
                    FROM sandbox.player_open_room
                    JOIN sandbox.player_administrative_info ON player_open_room.player_id = player_administrative_info.id AND player_open_room.room_id=?");

                $func_players[$i] = array();

                foreach( (array)$res_player_id as $j => $el2 ) {

                    $func_players[$i][$j] = array(
                        "id" => ((array)$res_player_id)[$j],
                        "name" => ((array)$res_player_name)[$j]
                    );
                }
            }
        }

        $json->success_get_open_rooms($res_room_id, $res_creator_id, $res_started_at, $res_rated, $res_max_players,
            $res_min_rating, $res_description, $res_goal, $res_anonymity,
            $res_map, $res_seed, $func_players, $in_room_status);

    } catch (Exception $e) {

        $json->fail_msg($e->getMessage());
    }
