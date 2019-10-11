<?php

    require("utils_security.php");
    require("utils_database.php");
    require("session.php");

    $sec = new utils_security();
    $db = new utils_database();
    $ses = new session();

    $username = $sec->rm_inject(($_POST["username"]));
    $password = $sec->rm_inject($_POST["password"]);

    /*
     * Constants
     */
    $str_incorrect_password= "Incorrect password";
    $str_player_not_found= "Could not find player";
    $str_missing_user_psw = "Missing username or password";

    if(empty($username) || empty($password)) {

        echo $str_missing_user_psw;
    } else {

        $con = $db->new_connection();

        $stmt = $con->prepare("
SELECT password 
FROM sandbox.player_administrative_info 
WHERE playername=?
");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($res_password_hash);

        if($stmt->fetch() && $stmt->num_rows == 1) {

            if(password_verify($password, $res_password_hash)){

                $ses->generate_session_login($con, $username);
            } else {
                echo $str_incorrect_password;
            }
        } else {

            echo $str_player_not_found;
        }
        $stmt->close();
        $con->close();
    }