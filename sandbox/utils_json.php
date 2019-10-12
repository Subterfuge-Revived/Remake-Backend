<?php


class utils_json
{
    public function success_login($player_id, $playername, $session_id) {

        $arr = array(
            'success' => true,
            'user' => array(
                'id' => $player_id,
                'name' => $playername
            ),
            'token' => $session_id
        );

        echo json_encode($arr);
        http_response_code(new httpStatusCode(httpStatusCode::OK));
    }

    public function fail_msg($message) {

        $arr = array(
            'success' => false,
            'message' => $message
        );

        echo json_encode($arr);
        http_response_code(new httpStatusCode(httpStatusCode::BAD_REQUEST));
    }
}