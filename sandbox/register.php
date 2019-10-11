<?php

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require '/var/www/PHPMailer/vendor/autoload.php';

    require("utils_security.php");
    require("utils_database.php");

    $sec = new utils_security();
    $db = new utils_database();

    $username = $sec->rm_inject($_POST["username"]);
    $mail_address = $sec->rm_inject($_POST["email"]);
    $password = $sec->rm_inject($_POST["password"]);

    /*
     * Constants
     */
    $str_username_taken = "This username is already taken";
    $str_mail_taken = "This mail address is already taken";
    $str_registration_successful = "Registration successful";
    $str_invalid_registration_details = "Invalid registration details";

    // Salted password hashing
    $password = password_hash($password, PASSWORD_DEFAULT);

    try{
        // Validate if fields are populated correctly
        if(!filter_var($mail_address, FILTER_VALIDATE_EMAIL)
            && !empty($username)
            && !empty($password)
            && strlen($password) > 5) {

            throw new \Exception($str_invalid_registration_details);
        } else {

            $con = $db->new_connection();

            // Check whether username is already registered
            $stmt_usr = $con->prepare("
SELECT playername 
FROM sandbox.player_administrative_info 
WHERE playername=?
");
            $stmt_usr->bind_param("s", $username);

            if((!$stmt_usr->execute())) {
                throw new \Exception($stmt_usr->error);
            }

            $stmt_usr->store_result();

            //Check whether mail is already taken
            $stmt_mail = $con->prepare("
SELECT mail 
FROM sandbox.player_administrative_info
WHERE mail=?
");

            $stmt_mail->bind_param("s", $mail_address);

            if((!$stmt_mail->execute())) {
                throw new \Exception($stmt_usr->error);
            }

            $stmt_mail->store_result();

            if($stmt_usr->num_rows) {
                throw new \Exception($str_username_taken);
            }

            if($stmt_mail->num_rows) {
                throw new \Exception($str_mail_taken);
            }

            // Add new player to player_administrative_info
            $stmt = $con->prepare("
INSERT INTO sandbox.player_administrative_info (playername, password, mail) 
VALUES (?,?,?)
");
            $stmt->bind_param("sss", $username, $password, $mail_address);

            if(!$stmt->execute()) {
                throw new \Exception($stmt->error);
            }

            echo $str_registration_successful;
        }
    } catch(\Exception $e) {
        echo $e->getMessage();
    } finally {

        $stmt->close();
        $con->close();
    }
