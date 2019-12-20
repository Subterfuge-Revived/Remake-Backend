<?php

    /*
     * Input
     */
    $in_session_id  = $sec->rm_inject(($_POST["session_id"]));
    $in_room_id     = $sec->rm_inject($_POST["room_id"]);
    $in_occurs_at   = $sec->rm_inject($_POST["occurs_at"]);
    $in_event_msg   = $sec->rm_inject($_POST["event_msg"]);

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

        $func_event_room = "events_room_" . $in_room_id;

        // Insert new command
        $db = new utils_database(utils_database::new_connection_events());
        $db->bind_req(time(), $in_occurs_at, $func_player_id, $in_event_msg)
            ->exec_db("
            INSERT INTO events_ongoing_rooms." . $func_event_room . " (time_issued, occurs_at, player_id, event_msg)
            VALUES (?,?,?,?)");

        $json->success_join_room($in_room_id);

    } catch (\Exception $e) {
        $json->fail_msg($e->getMessage());
    }
