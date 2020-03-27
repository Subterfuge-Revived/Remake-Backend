<?php

    /*
     * Input
     */
    $in_session_id = $sec->rm_inject(($_POST["session_id"]));
    $in_room_id = $sec->rm_inject($_POST["room_id"]);
    $in_event_id = $sec->rm_inject($_POST["event_id"]);
    $in_event_msg   = $sec->rm_inject($_POST["event_msg"]);

    /*
    * Constants
    */
    $out_invalid_room = "[update_events] Player not associated with room";

    // Begin
    try {

        $db = new utils_database(utils_database::new_connection());
        $func_ses = (new utils_session($db))->reworked_is_session_valid($in_session_id);

        $func_player_id = $func_ses->getPlayerId();

        // Check if player is in Room
        $db->bind_req($func_player_id, $in_room_id)
            ->error_num_row_zero($out_invalid_room)
            ->exec_db("
                    SELECT *
                    FROM sandbox.player_open_room
                    WHERE player_id=? AND room_id=?");

        // Check if Room is ongoing
        $db->bind_req($in_room_id)
            ->error_num_row_zero($out_invalid_room)
            ->exec_db("
                    SELECT *
                    FROM sandbox.ongoing_rooms
                    WHERE id=?");

        // Update if event belongs to player
        $db->bind_req($in_event_msg, $in_event_id, $func_player_id)
            ->exec_db("
                    UPDATE sandbox.events
                    SET event_msg=?
                    WHERE event_id=? AND player_id=?");

        $json->success_generic($in_room_id);

    } catch (\Exception $e) {
        $json->fail_msg($e->getMessage());
    }
