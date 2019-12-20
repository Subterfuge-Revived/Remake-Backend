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

    public function success_start_early($room_id) {

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

    public function success_get_events($res_event_id, $res_time_issued, $res_occurs_at,
                                       $res_player_id, $res_event_msg) {

        $arr = array();

        foreach( $res_event_id as $i => $el ) {

            $arr[$i] = array(
                "event_id" => $res_event_id[$i],
                "time_issued" => $res_time_issued[$i],
                "occurs_at" => $res_occurs_at[$i],
                "player_id" => $res_player_id[$i],
                "event_msg" => $res_event_msg[$i]
            );
        }

        echo json_encode($arr);
        http_response_code(httpStatusCode::OK);
    }
}