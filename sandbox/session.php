<?php

class session
{

    public function generate_session_login($con, $player_id)
    {

        try {

            $stmt = $con->prepare("
INSERT INTO sandbox.player_session (player_id, session_id, valid_until) 
VALUES (?,?,DATE_ADD(NOW(), INTERVAL 30 MINUTE )) 
ON DUPLICATE KEY UPDATE player_id=VALUES(player_id),session_id=VALUES(session_id),valid_until=VALUES(valid_until)
");
            // Generate unique session-ID
            $session_id = bin2hex(openssl_random_pseudo_bytes(20));

            $stmt->bind_param("is", $player_id, $session_id);

            if (!$stmt->execute()) {
                throw new \Exception($stmt->error);
            }

            return $session_id;
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}