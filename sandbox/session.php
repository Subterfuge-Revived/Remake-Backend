<?php

class session
{

    public function generate_session_login($con, $username)
    {

        try {

            $stmt = $con->prepare("
INSERT INTO sandbox.player_session (playername, id, valid_until) 
VALUES (?,?,DATE_ADD(NOW(), INTERVAL 30 MINUTE )) 
ON DUPLICATE KEY UPDATE playername=VALUES(playername),id=VALUES(id),valid_until=VALUES(valid_until)
");
            // Generate unique session-ID
            $session_id = bin2hex(openssl_random_pseudo_bytes(50));

            $stmt->bind_param("ss", $username, $session_id);

            if (!$stmt->execute()) {
                throw new \Exception($stmt->error);
            }

            echo $session_id;

        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}