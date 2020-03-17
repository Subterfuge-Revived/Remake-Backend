<?php

    /*
     * Input
     */
    $in_player_name = $sec->rm_inject($_POST["username"]);
    $in_mail_addr   = $sec->rm_inject($_POST["email"]);
    $in_password    = $sec->rm_inject($_POST["password"]);

    /*
     * Constants
     */
    $out_invalid_reg_details = "[register] Invalid registration details";
    $out_bad_player_name     = "[register] Player Name contains inappropriate words";
    $out_player_name_taken   = "[register] This player name is already taken";
    $out_mail_address_taken  = "[register] This mail address is already taken";
    $var_str_initial_rating  = 1200;
    $var_name_min_length     = 4;
    $var_psw_min_length      = 2;

    // Begin
    try {

        // Check if valid format for: mail address, player name and password
        if( !filter_var($in_mail_addr, FILTER_VALIDATE_EMAIL)
            || empty($in_player_name)
            || strlen($in_player_name) < $var_name_min_length
            || empty($in_password)
            || strlen($in_password) < $var_psw_min_length ) {

            throw new \Exception($out_invalid_reg_details);
        }
        // Check if player name contains any bad words
        else if( $check->hasProfanity($in_player_name) ) {

            throw new \Exception($out_bad_player_name);
        } else {

            // Password hashing
            $in_password = password_hash($in_password, PASSWORD_DEFAULT);

            // Connect to sandbox database
            $db = new utils_database(utils_database::new_connection());

            // Check is player name is already taken
            $db->bind_req($in_player_name)
                ->error_num_row_not_zero($out_player_name_taken)
                ->exec_db("
                SELECT player_administrative_info.player_name
                FROM sandbox.player_administrative_info
                WHERE LOWER(player_administrative_info.player_name) = LOWER(?)");

            // Check is mail is already taken
            $db->bind_req($in_mail_addr)
                ->error_num_row_not_zero($out_mail_address_taken)
                ->exec_db("
                SELECT player_administrative_info.mail
                FROM sandbox.player_administrative_info
                WHERE LOWER(player_administrative_info.mail) = LOWER(?)");

            // Add new player
            $db->bind_req($in_player_name, $in_password, $in_mail_addr)
                ->exec_db("
                INSERT INTO sandbox.player_administrative_info (player_name, password, mail) 
                VALUES (?,?,?)");

            $func_player_id = $db->getInsertId();

            // Add player statistics
            $db->bind_req($func_player_id, $var_str_initial_rating)
                ->exec_db("
                INSERT INTO sandbox.player_statistics (player_id, rating, last_online)
                VALUES (?,?, NOW())");

            $func_session_id = (new utils_session($db))->reworked_generate_session_login($db, $func_player_id);
            $json->success_login($func_player_id, $in_player_name, $func_session_id);
        }
    } catch (\Exception $e) {

        $json->fail_msg($e->getMessage());
    }