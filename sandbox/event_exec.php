<?php

    /*
     * Global Event Requirements
     */
    require __DIR__ . '/vendor/autoload.php';

    require "utils_security.php";
    require "utils_database.php";
    require "utils_session.php";
    require "utils_json.php";

    use mofodojodino\ProfanityFilter\Check;

    $sec   = new utils_security();
    $json  = new utils_json();
    $check = new Check();

    /*
     * Input
     */
    $event_type = $sec->rm_inject(($_POST["type"]));

    /*
     * Constants
     */
    $event_login            = "login";
    $event_register         = "register";
    $event_create_new_room  = "new_room";
    $event_join_room        = "join_room";
    $event_leave_room       = "leave_room";
    $event_start_early      = "start_early";
    $event_submit_event     = "submit_event";

    if( strcmp($event_type, $event_login) == 0 ) {

        require_once 'login.php';

    } else if( strcmp($event_type, $event_register) == 0 ) {

        require_once 'register.php';

    } else if( strcmp($event_type, $event_create_new_room) == 0 ) {

        require_once 'new_room.php';

    } else if( strcmp($event_type, $event_join_room) == 0 ) {

        require_once 'join_room.php';

    } else if( strcmp($event_type, $event_leave_room) == 0 ) {

        require_once 'leave_room.php';

    } else if( strcmp($event_type, $event_start_early) == 0 ) {

        require_once 'start_early.php';

    } else if( strcmp($event_type, $event_submit_event) == 0 ) {

        require_once 'submit_event.php';

    }



