<?php
    error_reporting(0);


    /*
     * Global Event Requirements
     */
    require __DIR__ . '/vendor/autoload.php';

    require "utils/utils_security.php";
    require "utils/utils_database.php";
    require "utils/utils_session.php";
    require "utils/utils_json.php";
    require "credentials.php";

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
    $event_cancel_event     = "cancel_event";
    $event_update_event     = "update_event";
    $event_get_events       = "get_events";
    $event_get_room_data    = "get_room_data";
    $event_message          = "message";
    $event_get_message      = "get_message";
    $event_block            = "block";
    $event_unblock          = "unblock";


    if( strcmp($event_type, $event_login) == 0 ) {

        require_once 'auth/login.php';

    } else if( strcmp($event_type, $event_register) == 0 ) {

        require_once 'auth/register.php';

    } else if( strcmp($event_type, $event_create_new_room) == 0 ) {

        require_once 'room/new_room.php';

    } else if( strcmp($event_type, $event_join_room) == 0 ) {

        require_once 'room/join_room.php';

    } else if( strcmp($event_type, $event_leave_room) == 0 ) {

        require_once 'room/leave_room.php';

    } else if( strcmp($event_type, $event_start_early) == 0 ) {

        require_once 'room/start_early.php';

    } else if( strcmp($event_type, $event_submit_event) == 0 ) {

        require_once 'data/submit_event.php';

    } else if( strcmp($event_type, $event_get_events) == 0 ) {

        require_once 'data/get_events.php';

    } else if( strcmp($event_type, $event_cancel_event) == 0 ) {

        require_once 'data/cancel_events.php';

    } else if( strcmp($event_type, $event_update_event) == 0 ) {

        require_once 'data/update_events.php';

    } else if( strcmp($event_type, $event_get_room_data) == 0 ) {

        require_once 'data/get_room_data.php';

    } else if( strcmp($event_type, $event_message) == 0 ) {

        require_once 'messaging/message.php';

    } else if( strcmp($event_type, $event_get_message) == 0 ) {

        require_once 'messaging/get_message.php';

    } else if( strcmp($event_type, $event_block) == 0 ) {

        require_once 'messaging/block.php';

    } else if ( strcmp($event_type, $event_unblock) == 0 ) {

        require_once 'messaging/unblock.php';
    }
