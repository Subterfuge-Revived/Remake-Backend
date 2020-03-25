<?php

    /*
     * Input
     */
    $in_session_id  = $sec->rm_inject($_POST["session_id"]);
    $in_other_player_id = $sec->rm_inject($_POST["other_player_id"]);
    $in_room_id =  $sec->rm_inject($_POST["room_id"]);

    $out_invalid_room = "Player not associated with room";
    $out_invalid_recipient = "Other player not associated with room";
    $out_same_recipient = "You can't block yourself";
    $out_already_blocked = "You've already blocked this person";


try {
      //Connect to database
      $db = new utils_database(utils_database::new_connection());

      $func_ses = (new utils_session($db))->reworked_is_session_valid($in_session_id);
      $func_player_id = $func_ses->getPlayerId();

      if($func_player_id == $in_other_player_id){
        throw new \Exception($out_same_recipient);
      } else {

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


                      // Insert block into database
                      $db = new utils_database(utils_database::new_connection());
                      $db->bind_req($in_room_id, time(), $func_player_id, $in_other_player_id)
                          ->exec_db("
                          INSERT INTO blocks (room_id, time_issued, sender_id, recipient_id)
                          VALUES (?,?,?,?)");

                          $json->success_block($in_room_id, $in_other_player_id);

      }
} catch (\Exception $e) {

    $json->fail_msg($e->getMessage());
}
?>
