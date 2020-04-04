grant all privileges on *.* to subterfuge@'%';

create database if not exists `sandbox`;
use sandbox;

create table if not exists `blocks`
(
    `room_id`      int(11) not null auto_increment,
    `time_issued`  int(11) not null,
    `sender_id`    int(11) not null,
    `recipient_id` int(11) not null,
    primary key (`room_id`)
) comment = 'Everyone who is blocking everyone';

create table if not exists `events`
(
    `room_id`     int(11) not null,
    `event_id`    int(11) not null,
    `time_issued` int(11) not null,
    `occurs_at`   int(11) not null,
    `player_id`   int(11) not null,
    `event_msg`   varchar(200) default null
);

create table if not exists `messages`
(
    `room_id`      int(11)      not null auto_increment,
    `time_issued`  int(11)      not null,
    `sender_id`    int(11)      not null,
    `recipient_id` int(11)      not null,
    `message`      varchar(250) not null,
    primary key (`room_id`)
) comment = 'All messages';

create table if not exists `ongoing_rooms`
(
    `id`           int(11)     not null,
    `creator_id`   int(11)     not null,
    `started_at`   int(11)     not null,
    `rated`        tinyint(1)  not null,
    `player_count` int(11)     not null,
    `min_rating`   int(11)     not null,
    `description`  varchar(50) not null,
    `goal`         int(11)     not null,
    `anonymity`    tinyint(1)  not null,
    `map`          int(11)     not null,
    `seed`         int(11)     not null,
    primary key (`id`)
);

create table if not exists `open_rooms`
(
    `id`           int(11)     not null auto_increment,
    `creator_id`   int(11)     not null,
    `rated`        tinyint(1)  not null,
    `max_players`  int(11)     not null,
    `player_count` int(11)     not null,
    `min_rating`   int(11)     not null,
    `description`  varchar(50) not null,
    `goal`         int(11)     not null comment '0 = Mine 200 Neptunium\n1 = Control 40 Outposts',
    `anonymity`    tinyint(1)  not null,
    `map`          int(11)     not null comment '0 = random\n1 = custom generator heavy\n2 = custom balanced\n3 = custom factory heavy',
    `seed`         int(11)     not null default 0 comment 'seed based on ''map''',
    primary key (`id`)
) comment = 'List of open game rooms';

create table if not exists `player_administrative_info`
(
    `id`          int(11)     not null auto_increment,
    `player_name` varchar(15) not null,
    `password`    varchar(64) not null,
    `mail`        varchar(64) default null,
    primary key (`id`),
    unique key `player_administrative_info_mail_uindex` (`mail`)
) comment = 'Stores necessary information for authentication';

create table `player_open_room`
(
    `player_id` int(11) not null,
    `room_id`   int(11) not null,
    unique key `player_open_room_pk` (`player_id`, `room_id`),
    key `player_open_room_open_rooms_id_fk` (`room_id`)
) comment = 'Assign player to a 1..* rooms';

create table `player_session`
(
    `player_id`   int(11) not null,
    `session_id`  varchar(400) default null,
    `valid_until` datetime     default null,
    primary key (`player_id`),
    unique key `player_session_id_uindex` (`session_id`)
);

create table `player_statistics`
(
    `player_id`    int(11)  not null,
    `rating`       int(11)  not null,
    `games_played` int(11)  not null default 0,
    `wins`         int(11)  not null default 0,
    `resigned`     int(11)  not null default 0,
    `last_online`  datetime not null,
    primary key (`player_id`)
)
