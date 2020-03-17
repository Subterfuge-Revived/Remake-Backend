<?php

class utils_session
{

    private $db;
    private $player_id;

    /*
    * Constants
    */
    private $str_invalid_session = "Invalid session. Authentication required";

    public function __construct(utils_database $db) {

        $this->db = $db;
        $this->player_id = null;

        return $this;
    }

    public function getPlayerId() {

        return $this->player_id;
    }

    public function generate_session_login(mysqli $con, $player_id)
    {

        try {

            $stmt = $con->prepare("
INSERT INTO sandbox.player_session (player_id, session_id, valid_until) 
VALUES (?,?,DATE_ADD(NOW(), INTERVAL 30 MINUTE )) 
ON DUPLICATE KEY UPDATE player_id=VALUES(player_id),session_id=VALUES(session_id),valid_until=VALUES(valid_until)
");
            // Generate unique utilsSession-ID
            $session_id = bin2hex(openssl_random_pseudo_bytes(40));

            $stmt->bind_param("is", $player_id, $session_id);

            if (!$stmt->execute()) {
                throw new \Exception($stmt->error);
            }

            $stmt->store_result();

            return $session_id;

        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }

    public function reworked_generate_session_login(utils_database $db, $player_id) {

        try {

            // Generate unique session id
            $session_id = bin2hex(openssl_random_pseudo_bytes(40));

            // Insert new session id for player
            $db->bind_req($player_id, $session_id)
                ->exec_db("
                INSERT INTO sandbox.player_session (player_id, session_id, valid_until)
                VALUES (?,?,DATE_ADD(NOW(), INTERVAL 30 MINUTE))
                ON DUPLICATE KEY UPDATE player_id = VALUES(player_id), session_id = VALUES(session_id), valid_until = VALUES(valid_until)");

            return $session_id;

        } catch (\Exception $e) {

            $json = new utils_json();
            $json->fail_msg($e->getMessage());
        }
    }

    public function session_valid(mysqli $con, $session_id) {

        try{

            $stmt = $con->prepare("
SELECT player_id FROM sandbox.player_session
WHERE session_id=? AND NOW() <= valid_until
");
            $stmt->bind_param("s", $session_id);

            if(!$stmt->execute()) {
                throw new \Exception($stmt->error);
            }

            $stmt->store_result();
            $stmt->bind_result($res_player_id);

            if($stmt->fetch() && $stmt->num_rows == 1) {
                return ["valid" => true, "player_id" => $res_player_id];
            } else {
                return ["valid" => false];
            }
        } catch(\Exception $e) {
            $e->getMessage();
        }
    }

    public function reworked_is_session_valid($session_id) {

        $out_session_invalid = "[new_room] Invalid session. Authentication required";

        try {

            $this->db
                ->bind_req($session_id)
                ->bind_res($res_player_id)
                ->error_num_row_zero($out_session_invalid)
                ->exec_db("
                SELECT player_id FROM sandbox.player_session
                WHERE session_id=? AND NOW() <= valid_until");

            $this->player_id = $res_player_id;

            return $this;

        } catch (\Exception $e) {

            $json = new utils_json();
            $json->fail_msg($e->getMessage());
        }
    }
}