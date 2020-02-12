<?php

    /*
     * Input
     */
    $in_session_id  = $sec->rm_inject($_POST["session_id"]);
    $in_message = $sec->rm_inject($_POST["message"]);
    $in_room_id = $sec->rm_inject($_POST["room_id"]);
    $in_other_player_id = $sec->rm_inject($_POST["other_player_id"]);

    $out_profanity = "Diplomacy is not the place for profanity";
    $out_invalid_room = "Player not associated with room";
    $out_invalid_recipient = "Other player not associated with room";


try {
    if( $check->hasProfanity($in_message) ) {

        throw new \Exception($out_profanity);
    } else {
      //Connect to database
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
    // Check if the other player is in the room
              $db->bind_req($in_other_player_id, $in_room_id)
                  ->error_num_row_zero($out_invalid_recipient)
                  ->exec_db("
                  SELECT *
                  FROM sandbox.player_open_room
                  WHERE player_id=? AND room_id=?");
                  
              // Insert new message
              $db = new utils_database(utils_database::new_connection());
              $db->bind_req($in_room_id, time(), $func_player_id, $in_other_player_id, $in_message)
                  ->exec_db("
                  INSERT INTO messages (room_id, time_issued, sender_id, recipient_id, message)
                  VALUES (?,?,?,?,?)");

    }
} catch (\Exception $e) {

    $json->fail_msg($e->getMessage());
}
?>
