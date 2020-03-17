<?php

    /*
     * Input
     */
    $in_session_id  = $sec->rm_inject(($_POST["session_id"]));
    $in_room_id     = $sec->rm_inject($_POST["room_id"]);

    /*
    * Constants
    */
    $out_invalid_room       = "[leave_room] No room found with id: " . $in_room_id;
    $out_invalid_assignment = "[leave_room] Player not in room: " . $in_room_id;

    // Begin
    try {

        $db = new utils_database(utils_database::new_connection());
        $func_ses = (new utils_session($db))->reworked_is_session_valid($in_session_id);

        $func_player_id = $func_ses->getPlayerId();

        // Get the Room player count
        $db->bind_req($in_room_id)
            ->bind_res($res_player_count)
            ->error_num_row_zero($out_invalid_room)
            ->exec_db("
            SELECT player_count
            FROM sandbox.open_rooms
            WHERE id=?");

        // Check if player is in Room
        $db->bind_req($func_player_id, $in_room_id)
            ->error_num_row_zero($out_invalid_assignment)
            ->exec_db("
            SELECT *
            FROM sandbox.player_open_room
            WHERE player_id=? AND room_id=?");

        // Remove player from Room
        $db->bind_req($func_player_id, $in_room_id)
            ->exec_db("
            DELETE FROM sandbox.player_open_room
            WHERE player_id=? AND room_id=?");

        // If the room is now empty, remove it else update player count
        if($res_player_count === 1) {

            $db->bind_req($in_room_id)
                ->exec_db("
                DELETE FROM sandbox.open_rooms
                WHERE id=?");

        } else {

            $db->bind_req($in_room_id)
                ->exec_db("
                UPDATE sandbox.open_rooms
                SET player_count = player_count - 1
                WHERE id=?");

        }

        $json->success_join_room((int)$in_room_id);

    } catch (\Exception $e) {

        $json->fail_msg($e->getMessage());
    }