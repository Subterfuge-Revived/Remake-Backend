<?php

    /*
     * Input
     */
    $in_session_id = $sec->rm_inject(($_POST["session_id"]));
    $in_room_id    = $sec->rm_inject(($_POST["room_id"]));

    /*
     * Constants
     */
    $out_invalid_session     = "[join_room] Invalid session. Authentication required";
    $out_insufficient_rating = "[join_room] Insufficient rating";
    $out_invalid_room        = "[join_room] No room found with id: " . $in_room_id;
    $out_already_joined      = "[join_room] You have already joined this room";

    // Begin
    try  {

        $db = new utils_database(utils_database::new_connection());
        $func_ses = (new utils_session($db))->reworked_is_session_valid($in_session_id);

        $func_player_id = $func_ses->getPlayerId();

        // Check if room open room exists
        $db->bind_req($in_room_id)
            ->bind_res($res_room_id, $res_max_players, $res_player_count)
            ->error_num_row_zero($out_invalid_room)
            ->exec_db("
            SELECT id, max_players, player_count
            FROM sandbox.open_rooms
            WHERE id=?");

        // Check if player is already in room
        $db->bind_req($func_player_id, $in_room_id)
            ->error_num_row_not_zero($out_already_joined)
            ->exec_db("
            SELECT *
            FROM sandbox.player_open_room
            WHERE player_id=? AND room_id=?");

        // Get the player rating
        $db->bind_req($func_player_id)
            ->bind_res($res_player_rating)
            ->exec_db("
            SELECT rating
            FROM sandbox.player_statistics
            WHERE player_id=?");

        // Get the minimum access rating
        $db->bind_req($res_room_id)
            ->bind_res($res_min_rating)
            ->exec_db("
            SELECT min_rating
            FROM sandbox.open_rooms
            WHERE id=?");

        // Check if player rating is less than min access rating
        if( $res_player_rating < $res_min_rating ) {

            throw new \Exception($out_insufficient_rating);
        }

        // Insert player associated to room
        $db->bind_req($func_player_id, $res_room_id)
            ->exec_db("
            INSERT INTO sandbox.player_open_room (player_id, room_id)
            VALUES (?,?)");

        // Check if room is now full after join
        if( ++$res_player_count == $res_max_players ) {

            // Transfer open room to ongoing rooms
            $db->bind_req($res_room_id)
                ->exec_db("
                INSERT INTO sandbox.ongoing_rooms
                SELECT id, creator_id, rated, player_count + 1, min_rating, description, goal, anonymity, map, seed
                FROM sandbox.open_rooms
                WHERE id = ?");

            // Delete room from open rooms
            $db->bind_req($res_room_id)
                ->exec_db("
                DELETE FROM sandbox.open_rooms
                WHERE sandbox.open_rooms.id = ?");

            $func_event_tbl_name = "events_room_" . $res_room_id;
            $func_event_tbl_pk = "room_" . $res_room_id . "_pk";

            $db = new utils_database(utils_database::new_connection_events());

            // Create event table for room
            $db->exec_db("
                create table " . $func_event_tbl_name . "
                (
                    event_id int auto_increment,
                    time_issued int not null,
                    occurs_at int not null,
                    player_id int not null,
                    event_msg varchar(200) not null,
                    constraint " . $func_event_tbl_pk . "
                        primary key (event_id)
                );");
        } else {

            // Update player count
            $db->bind_req($res_room_id)
                ->exec_db("
                UPDATE sandbox.open_rooms 
                SET player_count = player_count + 1
                WHERE id = ?");
        }

        $json->success_join_room($res_room_id);
    } catch (\Exception $e) {

        $json->fail_msg($e->getMessage());
    }