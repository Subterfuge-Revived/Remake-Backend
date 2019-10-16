<?php

    require ("utils_security.php");
    require ("utils_database.php");
    require ("utils_json.php");
    require ("utils_session.php");

    $sec  = new utils_security();
    $ses  = new utils_session();
    $json = new utils_json();

    /*
     * Input
     */
    $player_name = $sec->rm_inject($_POST["player_name"]);
    $password    = $sec->rm_inject($_POST["password"]);

    /*
     * Constants
     */
    $out_incorrect_password = "[login] Incorrect password";
    $out_missing_player_psw = "[login] Missing player name or password";
    $out_player_not_found   = "[login] Could not find specified player";

    // Begin
    try{

        // Check if player_name or password empty
        if( empty($player_name) || empty($password) ) {

            throw new \Exception($out_missing_player_psw);
        }

        // Connect to sandbox database
        $db = new utils_database(utils_database::new_connection());

        // Fetch player id and password
        $db->bind_req($player_name)
            ->bind_res($db_player_id, $db_player_password)
            ->exec_db("
            SELECT player_administrative_info.id, player_administrative_info.password
            FROM sandbox.player_administrative_info
            WHERE player_administrative_info.player_name = ?");

        // Check if player exists
        if( !$db->num_rows ) {

            throw new \Exception($out_player_not_found);
        }

        // Check if password matches
        if( !password_verify($password, $db_player_password) ) {

            throw new \Exception($out_incorrect_password);
        }

        $session_id = $ses->reworked_generate_session_login($db, $db_player_id);
        $json->success_login($db_player_id, $player_name, $session_id);

    } catch (\Exception $e) {

        $json->fail_msg($e->getMessage());
    }


