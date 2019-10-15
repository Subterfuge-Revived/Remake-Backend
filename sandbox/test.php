<?php

    require("utils_database.php");


    try {

        $id = 3;

        $db = new utils_database(utils_database::new_connection());

        $db->bind_res($res_rating, $res_games_played)
            ->exec_db("
        SELECT rating, games_played FROM sandbox.player_statistics
        ");

        echo  json_encode($res_rating);

    } catch (\Exception $e) {

    }
