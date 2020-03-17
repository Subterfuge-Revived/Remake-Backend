<?php

    /*
     * Input
     */
    $in_session_id  = $sec->rm_inject($_POST["session_id"]);
    $in_timestamp = $sec->rm_inject($_POST["timestamp"]);
    $in_room_id = $sec->rm_inject($_POST["room_id"]);

    $out_invalid_room = "Player not associated with room";
    $out_invalid_recipient = "Other player not associated with room";

try {
  //Connect to database
  $db = new utils_database(utils_database::new_connection());

  $func_ses = (new utils_session($db))->reworked_is_session_valid($in_session_id);
  $func_player_id = $func_ses->getPlayerId();

  //Make sure the player is actually in the room
  $db->bind_req($func_player_id, $in_room_id)
      ->error_num_row_zero($out_invalid_room)
      ->exec_db("
      SELECT *
      FROM sandbox.player_open_room
      WHERE player_id=? AND room_id=?");

  $db->bind_res($res_room_id, $res_time_issued, $res_sender_id, $res_recipient_id, $res_message)
      ->exec_db("
      SELECT *
      FROM messages
      ");
  $json->success_get_messages($res_room_id, $res_time_issued, $res_sender_id, $res_recipient_id, $res_message, $in_timestamp);

} catch (\Exception $e) {

    $json->fail_msg($e->getMessage());
}
?>
