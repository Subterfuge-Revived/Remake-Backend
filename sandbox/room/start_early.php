<?php

    /*
     * Input
     */
    $in_session_id  = $sec->rm_inject(($_POST["session_id"]));
    $in_room_id     = $sec->rm_inject($_POST["room_id"]);

    /*
    * Constants
    */
    $out_invalid_request = "[start_early] Invalid request: " . $in_room_id;

    try {

        $db = new utils_database(utils_database::new_connection());
        $func_ses = (new utils_session($db))->reworked_is_session_valid($in_session_id);

        $func_player_id = $func_ses->getPlayerId();

        // Check if Room exists and player requesting is owner
        $db->bind_req($in_room_id, $func_player_id)
            ->bind_res($func_player_count)
            ->error_num_row_zero($out_invalid_request)
            ->exec_db("
            SELECT player_count
            FROM sandbox.open_rooms
            WHERE id=? AND creator_id=?");

        // If Room has more than one player -> start
        if($func_player_count > 1) {

            $func_time = time();

            $db->bind_req($func_time, $in_room_id)
                ->exec_db("
                INSERT INTO sandbox.ongoing_rooms (started_at, id, creator_id, rated, player_count, min_rating, description, goal, anonymity, map, seed) 
                SELECT ?, id, creator_id, rated, player_count, min_rating, description, goal, anonymity, map, seed
                FROM sandbox.open_rooms
                WHERE id=?");

            $db->bind_req($in_room_id)
                ->exec_db("
                DELETE FROM sandbox.open_rooms
                WHERE id=?");

            $json->success_start_early($in_room_id);

        } else {
          $json->failure_start_early($in_room_id, "players must be > 1");
        }


    } catch (\Exception $e) {

        $json->fail_msg($e->getMessage());
    }
