<?php

require_once ("httpStatusCode.php");

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
        http_response_code(httpStatusCode::OK);
    }

    public function fail_msg($message) {

        $arr = array(
            'success' => false,
            'message' => $message
        );

        echo json_encode($arr);
        http_response_code(httpStatusCode::BAD_REQUEST);
    }

    public function success_new_room($player_id, $rated, $max_players, $int_initial_player_count,
                                     $min_rating, $description, $goal, $anonymity, $map, $time) {

        $arr = array(
            'success' => true,
            'created_room' => array(
                'creator' => $player_id,
                'description' => $description,
                'rated' => $rated == 1 ? true:false,
                'max_players' => $max_players,
                'player_count' => $int_initial_player_count,
                'min_rating' => $min_rating,
                'goal' => $goal,
                'anonymity' => $anonymity == 1? true:false,
                'map' => $map,
                'seed' => $time
            )
        );

        echo json_encode($arr);
        http_response_code(httpStatusCode::OK);
    }

    public function success_join_room($room_id) {

        $arr = array(
            'success' => true,
            'room' => $room_id
        );

        echo json_encode($arr);
        http_response_code(httpStatusCode::OK);
    }

    public function  success_retr_rooms($arr) {

        echo json_encode($arr);
        http_response_code(httpStatusCode::OK);
    }
}