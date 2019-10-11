<?php


class utils_database
{
    public function new_connection() {
        $db_servername = "localhost";
        $db_username = "";
        $db_password = "";
        $db_database = "sandbox";
        return new mysqli($db_servername, $db_username, $db_password, $db_database);
    }
}