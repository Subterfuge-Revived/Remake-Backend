<?php

    require("utils_security.php");
    require("utils_database.php");
    require("utils_json.php");
    require("utils_session.php");

    $sec = new utils_security();
    $ses = new utils_session();
    $json = new utils_json();

    $player_name = $sec->rm_inject(($_POST["player_name"]));
    $password = $sec->rm_inject($_POST["password"]);

    /*
     * Constants
     */
    $str_incorrect_password = "Incorrect password";
    $str_player_not_found = "Could not find player";
    $str_missing_user_psw = "Missing username or password";

    if(empty($player_name) || empty($password)) {

        $json->fail_msg($str_missing_user_psw);
    } else {

        $con = utils_database::new_connection();

        $stmt = $con->prepare("
SELECT password 
FROM sandbox.player_administrative_info 
WHERE player_name=?
");
        $stmt->bind_param("s", $player_name);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($res_password_hash);

        if($stmt->fetch() && $stmt->num_rows == 1) {

            if(password_verify($password, $res_password_hash)){

                $stmt = $con->prepare("
SELECT id 
FROM sandbox.player_administrative_info
WHERE player_name=?");

                $stmt->bind_param("s", $player_name);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($player_id);

                if($stmt->fetch()) {

                    $session_id = $ses->generate_session_login($con, $player_id);
                    $json->success_login($player_id, $player_name, $session_id);
                }
            } else {
                $json->fail_msg($str_incorrect_password);
            }
        } else {
            $json->fail_msg($str_player_not_found);
        }
        $stmt->close();
        $con->close();
    }