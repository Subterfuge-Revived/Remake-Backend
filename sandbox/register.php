<?php

    use mofodojodino\ProfanityFilter\Check;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require '/var/www/PHPMailer/vendor/autoload.php';

    require("utils_security.php");
    require("utils_database.php");
    require("utils_json.php");
    require("utils_session.php");

    $sec = new utils_security();
    $json = new utils_json();
    $ses = new utils_session();
    $check = new Check();

    $player_name = $sec->rm_inject($_POST["username"]);
    $mail_address = $sec->rm_inject($_POST["email"]);
    $password = $sec->rm_inject($_POST["password"]);

    /*
     * Constants
     */
    $str_username_taken = "This username is already taken";
    $str_mail_taken = "This mail address is already taken";
    $str_registration_successful = "Registration successful";
    $str_invalid_registration_details = "Invalid registration details";
    $str_player_name_bad_words = "Player name content inappropriate";
    $int_initial_rating = 1200;

    // Salted password hashing
    $password = password_hash($password, PASSWORD_DEFAULT);

    try{
        // Validate if fields are populated correctly
        if(!filter_var($mail_address, FILTER_VALIDATE_EMAIL)
            && !empty($player_name)
            && !empty($password)
            && strlen($password) > 5) {

            throw new \Exception($str_invalid_registration_details);
        } else if($check->hasProfanity($player_name)) {

            throw new \Exception($str_player_name_bad_words);
        } else {

            $con = utils_database::new_connection();

            // Check whether username is already registered
            $stmt_usr = $con->prepare("
SELECT player_name 
FROM sandbox.player_administrative_info 
WHERE player_name=?
");
            $stmt_usr->bind_param("s", $player_name);

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
INSERT INTO sandbox.player_administrative_info (player_name, password, mail) 
VALUES (?,?,?)
");
            $stmt->bind_param("sss", $player_name, $password, $mail_address);

            if(!$stmt->execute()) {
                throw new \Exception($stmt->error);
            }

            $stmt->store_result();

            $player_id = $con->insert_id;

            $stmt = $con->prepare("
INSERT INTO sandbox.player_statistics (player_id, rating, last_online)
VALUES (?,?, NOW())
");
            $stmt->bind_param("ii", $player_id, $int_initial_rating);

            if(!$stmt->execute()) {
                throw new \Exception($stmt->error);
            }

            $stmt->store_result();

            $session_id = $ses->generate_session_login($con, $player_id);
            $json->success_login($player_id, $player_name, $session_id);

        }
    } catch(\Exception $e) {
        $json->fail_msg($e->getMessage());
    } finally {

        $stmt->close();
        $con->close();
    }
