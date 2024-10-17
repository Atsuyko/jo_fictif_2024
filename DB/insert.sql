/* Add User */
INSERT INTO `user` (`id`, `email`, `roles`, `password`, `firstname`, `lastname`, `created_at`, `updated_at`, `is_verified`, `google_id`) VALUES
(0x01929aa63a7776c7b4634a6c76c3cf50, 'user@jo.com', '[]', '$2y$13$jBjtTKuQhnzBzoMfWEha6O0eIV1cvyqEPv/HPzF2Xo6Wl2ZmXpwUm', 'User', 'Fictif', '2024-10-17 15:23:57', NULL, 1, NULL),
(0x01929aa6ef7b739294b6b53cc122f513, 'employee@jo.com', '[\"ROLE_USER\",\"ROLE_EMPLOYEE\"]', '$2y$13$CWz4ghF2Y9lkd4BWrPH4pe/3Qh03aUBm4nw8AVQTsas6i3VgOLj0i', 'Employee', 'Fictif', '2024-10-17 15:24:44', NULL, 1, NULL),
(0x01929aa772c179f0b72c7ebffa896119, 'admin@jo.com', '[\"ROLE_USER\",\"ROLE_ADMIN\"]', '$2y$13$iHM9cQYsxOzQ46XEh5NijeUrfgkxSStbsQdtHASMIVETGqHYbKzc6', 'Admin', 'Fictif', '2024-10-17 15:25:17', NULL, 1, NULL);

/* Add Event */
INSERT INTO `event` (`id`, `name`, `place`, `date`, `start_time`, `end_time`, `price`, `picture`, `created_at`, `updated_at`) VALUES
(1, 'Basket', 'Terrain 1', '2024-11-01', '10:00:00', '12:00:00', 100, 'upload_203_phpA59.tmp.png', '2024-10-08 14:05:57', NULL),
(2, 'Athlétisme', 'Stade de France', '2024-11-16', '14:00:00', '18:00:00', 150, 'upload_167_phpA83B.tmp.png', '2024-10-08 14:07:43', NULL),
(3, 'Natation', 'Aquaboulevard', '2024-12-05', '15:00:00', '19:00:00', 120, 'upload_889_phpA346.tmp.png', '2024-10-08 14:08:47', NULL),
(4, 'Cyclisme', 'Vélodrome SQY', '2024-12-24', '09:00:00', '11:30:00', 80, 'upload_853_php2E9F.tmp.png', '2024-10-08 14:09:23', NULL),
(5, 'Boxe', 'Ring 452', '2025-01-09', '12:00:00', '17:00:00', 70, 'upload_984_phpD83E.tmp.png', '2024-10-08 14:10:06', NULL),
(6, 'Equitation', 'Château de Versailles', '2025-02-05', '13:00:00', '16:00:00', 90, 'upload_599_php7CFA.tmp.png', '2024-10-08 14:10:48', NULL),
(7, 'Escalade', 'Mur 18', '2025-02-12', '17:00:00', '19:00:00', 50, 'upload_609_php45D9.tmp.png', '2024-10-08 14:11:40', NULL),
(8, 'Escrime', 'Piste 8', '2024-12-13', '08:00:00', '11:00:00', 70, 'upload_573_phpBF60.tmp.png', '2024-10-08 14:12:11', NULL),
(9, 'Gymnastique', 'Gymnase 7', '2024-11-02', '09:00:00', '17:00:00', 130, 'upload_727_php3C51.tmp.png', '2024-10-08 14:12:43', NULL),
(10, 'Tennis de table', 'Gymnase 56', '2024-11-16', '12:00:00', '15:00:00', 40, 'upload_284_phpB4BE.tmp.png', '2024-10-08 14:13:14', NULL);

/* Add Offer */
INSERT INTO `offer` (`id`, `name`, `discount`, `nb_people`, `picture`, `created_at`, `updated_at`) VALUES
(1, 'Solo', 0, 1, 'upload_608_phpC979.tmp.png', '2024-10-08 13:47:07', NULL),
(2, 'Duo', 5, 2, 'upload_992_phpFA45.tmp.png', '2024-10-08 13:48:25', NULL),
(3, 'Famille', 10, 4, 'upload_496_php35E8.tmp.png', '2024-10-08 13:48:40', NULL);

/* Mapped Event-Offer */
INSERT INTO `event_offer` (`event_id`, `offer_id`) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 1),
(2, 2),
(2, 3),
(3, 1),
(3, 2),
(3, 3),
(4, 1),
(4, 2),
(4, 3),
(5, 1),
(5, 2),
(5, 3),
(6, 1),
(6, 2),
(6, 3),
(7, 1),
(7, 2),
(7, 3),
(8, 1),
(8, 2),
(8, 3),
(9, 1),
(9, 2),
(9, 3),
(10, 1),
(10, 2),
(10, 3);