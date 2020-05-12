insert into `ongoing_rooms`
values (1, 1, 0, 1, 2, 880, 'Backend Testing', 1, 0, 1, 1571079360),
       (2, 1, 0, 1, 2, 1190, 'New Room 2', 1, 0, 1, 1571134878),
       (3, 4, 0, 1, 2, 1195, 'Room', 1, 0, 2, 1571135043),
       (4, 1, 0, 1, 2, 1200, 'New Room 4', 1, 0, 2, 1571135171),
       (5, 4, 0, 1, 2, 800, 'Test Room 1', 1, 0, 2, 1571154097),
       (6, 1, 0, 0, 2, 0, 'fff', 1, 0, 1, 1571154337),
       (7, 1, 0, 0, 2, 0, 'fff2', 1, 0, 1, 1571154428),
       (8, 1, 0, 0, 2, 0, 'fff22', 1, 0, 1, 1571154455),
       (9, 1, 0, 0, 2, 0, 'fff22', 1, 0, 1, 1571154780),
       (10, 1, 0, 0, 2, 0, 'fff22', 1, 0, 1, 1571155163),
       (11, 1, 0, 0, 2, 0, 'fff22', 1, 0, 1, 1571155331),
       (12, 1, 0, 0, 2, 0, 'fff22', 1, 0, 1, 1571155373),
       (13, 1, 0, 0, 2, 0, 'fff22', 1, 0, 1, 1571156330),
       (14, 1, 0, 0, 2, 0, 'fff22', 1, 0, 1, 1571156613),
       (15, 5, 0, 0, 2, 0, 'new game yo', 1, 0, 0, 1584533035),
       (20, 5, 1585244858, 0, 2, 0, 'New room', 1, 0, 1, 1585244851);

insert into `open_rooms`
values (16, 5, 0, 5, 1, 0, 'New room', 1, 0, 1, 1585242293),
       (19, 5, 0, 2, 1, 0, 'New room', 1, 0, 1, 1585244498);

insert into `player_administrative_info`
values (1, 'XATEV', '$2y$10$J7RYnd.E1Dp3CUUsPifFYe7RAEWrzA1rD1YX9kj85vJFltwdOiKCu', 'xatev@gmail.com'),
       (2, 'Player', '$2y$10$apSZLmaHykzOdTlvczC39eKGpR.tw2/5h.ZZnuBsyQ7ZnmB7.pjRS', 'player@gmail.com'),
       (3, 'User', '$2y$10$yw2zlXZ/.wqhYeqGo9t/WeQB3F.Zm4/zDVRhotYm0woTE0izRK7gm', 'user@gmail.com'),
       (4, 'Test', '$2y$10$gAPTQXr.bwRRgjXoJQM7kOa3hAAAfQngaDVGWcOTJqvfBdgl.uILK', 'test@gmail.com'),
       (5, 'player1', '$2y$10$OSx/zln1tBP67nEbzwvDze4quYHLai3sVRU2P9s1CpL7wwTMf74py', 'p1@gmail.com'),
       (6, 'player2', '$2y$10$TLygONGvpRS6D30olKcbvuImajFkeFbxUpEG.EHD6ywTX201j1mXC', 'p2@gmail.com');

insert into `player_open_room`
values (1, 1),
       (1, 2),
       (1, 3),
       (1, 4),
       (1, 5),
       (1, 6),
       (1, 7),
       (1, 8),
       (1, 9),
       (1, 10),
       (1, 11),
       (1, 12),
       (1, 13),
       (1, 14),
       (4, 1),
       (4, 2),
       (4, 3),
       (4, 4),
       (4, 5),
       (4, 6),
       (4, 7),
       (4, 8),
       (4, 9),
       (4, 10),
       (4, 11),
       (4, 12),
       (4, 13),
       (4, 14),
       (4, 17),
       (4, 18),
       (4, 19),
       (4, 20),
       (5, 15),
       (5, 16),
       (5, 17),
       (5, 18),
       (5, 19),
       (5, 20),
       (6, 15);

insert into `player_session`
values (1, '50b1dcb4084596825872db1e8f6ad356ffd3b22ad24b94574061ccbb6041bf7cd7df4797c4443c36', '2019-10-15 18:48:42'),
       (2, '812c703a9a636f7ddd834d8120e41057337e66b0651d944ab1dcc37e1a4ce1b07f7cce7886d0bdd4', '2019-10-14 21:23:32'),
       (3, '291e579a06e03f6160fcb22df32455b32be7e667fb2a2bbcc232eac4f3cad1a5c6a0cf67f60a4572', '2019-10-14 21:23:43'),
       (4, 'ba6e95fb1f284276ebcfa6557a6b1b929b6566c19104e43c6aa5e8396c87a666c03e0f73e7fb13f9', '2021-10-15 18:49:02'),
       (5, '67cc7311cdbbfc448b33e16ecce363f1a68d24784c0c8f1c1162b8e1d27c40940ff1203e75d7285b', '2021-03-18 13:35:38'),
       (6, '995bd851633feb06ae70420c1ebe747d02908d059215a2ed251aaa778f2a2a966f72cc2908e065e7', '2020-03-18 13:34:32');

insert into `player_statistics`
values (1, 1200, 0, 0, 0, '2019-10-14 20:53:10'),
       (2, 1200, 0, 0, 0, '2019-10-14 20:53:32'),
       (3, 1200, 0, 0, 0, '2019-10-14 20:53:43'),
       (4, 1200, 0, 0, 0, '2019-10-14 20:54:17'),
       (5, 1200, 0, 0, 0, '2020-03-18 13:02:55'),
       (6, 1200, 0, 0, 0, '2020-03-18 13:04:32');
