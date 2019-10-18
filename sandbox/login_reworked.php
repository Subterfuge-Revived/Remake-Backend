<?php

    require ("utils_security.php");
    require ("utils_database.php");
    require ("utils_json.php");
    require ("utils_session.php");

    $sec  = new utils_security();
    $json = new utils_json();

    /*
     * Input
     */
    $in_player_name = $sec->rm_inject($_POST["player_name"]);
    $in_password    = $sec->rm_inject($_POST["password"]);

    /*
     * Constants
     */
    $out_incorrect_password = "[login] Incorrect password";
    $out_missing_player_psw = "[login] Missing player name or password";
    $out_player_not_found   = "[login] Could not find specified player";

    // Begin
    try{

        // Check if player_name or password empty
        if( empty($in_player_name) || empty($in_password) ) {

            throw new \Exception($out_missing_player_psw);
        }

        // Connect to sandbox database
        $db = new utils_database(utils_database::new_connection());

        // Fetch player id and password, if player exits error out
        $db->bind_req($in_player_name)
            ->bind_res($db_player_id, $db_player_password)
            ->error_num_row_zero($out_player_not_found)
            ->exec_db("
            SELECT  player_administrative_info.id, player_administrative_info.password
            FROM    sandbox.player_administrative_info
            WHERE   player_administrative_info.player_name = ?");

        // Check if password matches
        if( !password_verify($in_password, $db_player_password) ) {

            throw new \Exception($out_incorrect_password);
        }

        $session_id = (new utils_session($db))->reworked_generate_session_login($db, $db_player_id);
        $json->success_login($db_player_id, $in_player_name, $session_id);

    } catch (\Exception $e) {

        $json->fail_msg($e->getMessage());
    }