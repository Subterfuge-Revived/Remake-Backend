<?php

    /*
     * Input
     */
    $in_session_id = $sec->rm_inject(($_POST["session_id"]));
    $in_room_id = $sec->rm_inject($_POST["room_id"]);

    /*
    * Constants
    */
    $out_invalid_room = "Player not associated with room";

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

        $db = new utils_database(utils_database::new_connection_events());

        $func_event_room = "events_room_" . $in_room_id;

        $db->bind_res($res_event_id, $res_time_issued, $res_occurs_at, $res_player_id, $res_event_msg)
            ->exec_db("
            SELECT *
            FROM events_ongoing_rooms." . $func_event_room . "
            ");

        $json->success_get_events($res_event_id, $res_time_issued, $res_occurs_at, $res_player_id, $res_event_msg);

    } catch (\Exception $e) {
        $json->fail_msg($e->getMessage());
    }
