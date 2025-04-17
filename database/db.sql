/*
 Source Server         : LOCALHOST
 Source Server Type    : MySQL
 Source Server Version : 50736
 Source Host           : localhost:3306
 Source Schema         : php_native_starter_kit

 Target Server Type    : MySQL
 Target Server Version : 50736
 File Encoding         : 65001

 Date: 17/04/2025 23:16:24
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for buku_tamu
-- ----------------------------
DROP TABLE IF EXISTS `buku_tamu`;
CREATE TABLE `buku_tamu`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nomor` varchar(30) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `tgl_kunjungan` date NULL DEFAULT NULL,
  `jam_kunjungan` time(0) NULL DEFAULT NULL,
  `nama_tamu` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `no_telp_tamu` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `membawa_kendaraan` varchar(5) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nomor_kendaraan` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `jenis_identitas_id` bigint(20) NULL DEFAULT NULL COMMENT 'md_jenis_identitas.id',
  `jenis_identitas` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `nama_yang_dikunjungi` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `no_tanda_masuk` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'no tanda masuk perumahan',
  `no_tanda_masuk_dikembalikan` int(1) NULL DEFAULT 0 COMMENT 'no tanda masuk perumahan sudah dikembalikan, 0:belum, 1:sudah',
  `keterangan` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `foto_tanda_pengenal` json NULL COMMENT 'SCAN SIM/KTP/ETC',
  `foto_kendaraan` json NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `created_by` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_by_id` bigint(20) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `updated_by` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `updated_by_id` bigint(20) NULL DEFAULT NULL,
  `deleted_at` datetime(0) NULL DEFAULT NULL,
  `deleted_by` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `deleted_by_id` bigint(20) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jenis_kendaraan_foreign`(`jenis_identitas_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of buku_tamu
-- ----------------------------

-- ----------------------------
-- Table structure for md_jenis_identitas
-- ----------------------------
DROP TABLE IF EXISTS `md_jenis_identitas`;
CREATE TABLE `md_jenis_identitas`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `kode` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `keterangan` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0:tidak aktif, 1:aktif',
  `created_at` datetime(0) NULL DEFAULT NULL,
  `created_by` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_by_id` bigint(20) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `updated_by` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `updated_by_id` bigint(20) NULL DEFAULT NULL,
  `deleted_at` datetime(0) NULL DEFAULT NULL,
  `deleted_by` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `deleted_by_id` bigint(20) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of md_jenis_identitas
-- ----------------------------
INSERT INTO `md_jenis_identitas` VALUES (1, 'JI3UTGC', 'KTP', 1, '2025-04-17 23:13:16', 'Admin', 1, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `md_jenis_identitas` VALUES (2, 'JIFGZ53', 'SIM', 1, '2025-04-17 23:13:22', 'Admin', 1, NULL, NULL, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for menus
-- ----------------------------
DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `position` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_separator` smallint(6) NOT NULL,
  `parent_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `menu_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `menu_icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `order_by` bigint(20) NOT NULL,
  `route_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0:tidak aktif, 1:aktif',
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `bg_color` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `menus_unique`(`position`, `is_separator`, `parent_id`, `menu_name`, `route_name`) USING BTREE,
  INDEX `menus_parent_id_foreign`(`parent_id`) USING BTREE,
  CONSTRAINT `menus_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of menus
-- ----------------------------
INSERT INTO `menus` VALUES (1, 'left', 0, NULL, 'Dashboard', 'fa-dashboard', 1, 'dashboard', 1, NULL, NULL, NULL);
INSERT INTO `menus` VALUES (2, 'left', 1, NULL, 'Aktivitas Harian', NULL, 2, NULL, 1, NULL, NULL, NULL);
INSERT INTO `menus` VALUES (3, 'left', 0, NULL, 'Buku Tamu', 'fa-book', 3, 'buku-tamu', 1, NULL, NULL, 'blue');
INSERT INTO `menus` VALUES (4, 'left', 1, NULL, 'Laporan', NULL, 11, NULL, 1, NULL, NULL, NULL);
INSERT INTO `menus` VALUES (5, 'left', 0, NULL, 'Report', 'fa-print', 12, 'report', 1, NULL, NULL, 'maroon');
INSERT INTO `menus` VALUES (6, 'left', 0, 5, 'Buku Tamu', 'fa-circle-o', 15, 'report/buku-tamu', 1, NULL, NULL, 'blue');
INSERT INTO `menus` VALUES (7, 'left', 0, NULL, 'Master Data', 'fa-puzzle-piece', 20, 'master-data', 1, NULL, NULL, 'navy');
INSERT INTO `menus` VALUES (8, 'left', 0, 7, 'Jenis Identitas', 'fa-circle-o', 23, 'master-data/jenis-identitas', 1, NULL, NULL, 'yellow');
INSERT INTO `menus` VALUES (9, 'left', 0, NULL, 'Setup', 'fa-gears', 26, 'setup', 1, NULL, NULL, 'black');
INSERT INTO `menus` VALUES (10, 'left', 0, 9, 'Webs', 'fa-globe', 27, 'setup/webs', 1, NULL, NULL, 'blue');
INSERT INTO `menus` VALUES (11, 'left', 0, 9, 'Running Numbers', 'fa-edit', 28, 'setup/running-numbers', 1, NULL, NULL, 'green');
INSERT INTO `menus` VALUES (12, 'left', 0, 9, 'User Role', 'fa-users', 29, 'setup/user-role', 1, NULL, NULL, 'maroon');
INSERT INTO `menus` VALUES (13, 'left', 0, 9, 'Manage Role', 'fa-lock', 30, 'setup/manage-role', 1, NULL, NULL, 'red');
INSERT INTO `menus` VALUES (14, 'left', 1, NULL, 'Account', NULL, 31, NULL, 1, NULL, NULL, NULL);
INSERT INTO `menus` VALUES (15, 'left', 0, NULL, 'Profile', 'fa-user', 32, 'account/profile', 1, NULL, NULL, 'aqua');
INSERT INTO `menus` VALUES (16, 'left', 0, NULL, 'Change Password', 'fa-lock', 33, 'account/change-password', 1, NULL, NULL, 'orange');
INSERT INTO `menus` VALUES (17, 'left', 0, NULL, 'Sign out', 'fa-power-off', 34, 'javascript:onLogout()', 1, NULL, NULL, 'red');
INSERT INTO `menus` VALUES (18, 'left', 1, NULL, 'Setup', NULL, 19, NULL, 1, NULL, '2025-04-17 22:48:22', NULL);

-- ----------------------------
-- Table structure for permissions
-- ----------------------------
DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `id_menu` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  `short_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `permissions_name_guard_name_unique`(`name`, `guard_name`, `short_name`, `id_menu`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 41 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of permissions
-- ----------------------------
INSERT INTO `permissions` VALUES (1, 1, 'dashboard-show', 'web', NULL, NULL, 'Show');
INSERT INTO `permissions` VALUES (2, 2, 'separator-aktivitas-harian-show', 'web', NULL, NULL, 'Show');
INSERT INTO `permissions` VALUES (3, 3, 'buku-tamu-show', 'web', NULL, NULL, 'Show');
INSERT INTO `permissions` VALUES (4, 3, 'buku-tamu-add', 'web', NULL, NULL, 'Add');
INSERT INTO `permissions` VALUES (5, 3, 'buku-tamu-edit', 'web', NULL, NULL, 'Edit');
INSERT INTO `permissions` VALUES (6, 3, 'buku-tamu-detail', 'web', NULL, NULL, 'Detail');
INSERT INTO `permissions` VALUES (7, 3, 'buku-tamu-delete', 'web', NULL, NULL, 'Delete');
INSERT INTO `permissions` VALUES (8, 4, 'separator-laporan-show', 'web', NULL, NULL, 'Show');
INSERT INTO `permissions` VALUES (9, 5, 'report-show', 'web', NULL, NULL, 'Show');
INSERT INTO `permissions` VALUES (10, 6, 'report-buku-tamu-show', 'web', NULL, NULL, 'Show');
INSERT INTO `permissions` VALUES (11, 6, 'report-buku-tamu-export-pdf', 'web', NULL, NULL, 'Export PDF');
INSERT INTO `permissions` VALUES (12, 6, 'report-buku-tamu-export-excel', 'web', NULL, NULL, 'Export Excel');
INSERT INTO `permissions` VALUES (13, 7, 'master-data-show', 'web', NULL, NULL, 'Show');
INSERT INTO `permissions` VALUES (14, 8, 'master-data-jenis-identitas-show', 'web', NULL, NULL, 'Show');
INSERT INTO `permissions` VALUES (15, 8, 'master-data-jenis-identitas-add', 'web', NULL, NULL, 'Add');
INSERT INTO `permissions` VALUES (16, 8, 'master-data-jenis-identitas-edit', 'web', NULL, NULL, 'Edit');
INSERT INTO `permissions` VALUES (17, 8, 'master-data-jenis-identitas-detail', 'web', NULL, NULL, 'Detail');
INSERT INTO `permissions` VALUES (18, 8, 'master-data-jenis-identitas-active', 'web', NULL, NULL, 'Active');
INSERT INTO `permissions` VALUES (19, 8, 'master-data-jenis-identitas-delete', 'web', NULL, NULL, 'Delete');
INSERT INTO `permissions` VALUES (20, 9, 'setup-show', 'web', NULL, NULL, 'Show');
INSERT INTO `permissions` VALUES (21, 10, 'setup-website-show', 'web', NULL, NULL, 'Show');
INSERT INTO `permissions` VALUES (22, 10, 'setup-website-edit', 'web', NULL, NULL, 'Edit');
INSERT INTO `permissions` VALUES (23, 11, 'setup-running-numbers-show', 'web', NULL, NULL, 'Show');
INSERT INTO `permissions` VALUES (24, 11, 'setup-running-numbers-edit', 'web', NULL, NULL, 'Edit');
INSERT INTO `permissions` VALUES (25, 12, 'setup-user-role-show', 'web', NULL, NULL, 'Show');
INSERT INTO `permissions` VALUES (26, 12, 'setup-user-role-add', 'web', NULL, NULL, 'Add');
INSERT INTO `permissions` VALUES (27, 12, 'setup-user-role-edit', 'web', NULL, NULL, 'Edit');
INSERT INTO `permissions` VALUES (28, 12, 'setup-user-role-active', 'web', NULL, NULL, 'Active');
INSERT INTO `permissions` VALUES (29, 12, 'setup-user-role-delete', 'web', NULL, NULL, 'Delete');
INSERT INTO `permissions` VALUES (30, 13, 'setup-manage-role-show', 'web', NULL, NULL, 'Show');
INSERT INTO `permissions` VALUES (31, 13, 'setup-manage-role-edit', 'web', NULL, NULL, 'Edit');
INSERT INTO `permissions` VALUES (32, 13, 'setup-manage-role-edit-access', 'web', NULL, NULL, 'Edit Role Access');
INSERT INTO `permissions` VALUES (33, 13, 'setup-manage-role-edit-sort', 'web', NULL, NULL, 'Edit Role Sorted');
INSERT INTO `permissions` VALUES (34, 14, 'account-show', 'web', NULL, NULL, 'Show');
INSERT INTO `permissions` VALUES (35, 15, 'account-profile-show', 'web', NULL, NULL, 'Show');
INSERT INTO `permissions` VALUES (36, 15, 'account-profile-edit', 'web', NULL, NULL, 'Edit');
INSERT INTO `permissions` VALUES (37, 16, 'account-change-password-show', 'web', NULL, NULL, 'Show');
INSERT INTO `permissions` VALUES (38, 16, 'account-change-password-edit', 'web', NULL, NULL, 'Edit');
INSERT INTO `permissions` VALUES (39, 17, 'account-sign-out-show', 'web', NULL, NULL, 'Show');
INSERT INTO `permissions` VALUES (40, 18, 'separator-setup-show', 'web', NULL, NULL, 'Show');

-- ----------------------------
-- Table structure for role_has_permissions
-- ----------------------------
DROP TABLE IF EXISTS `role_has_permissions`;
CREATE TABLE `role_has_permissions`  (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `order_by` bigint(20) NULL DEFAULT 0,
  PRIMARY KEY (`permission_id`, `role_id`) USING BTREE,
  INDEX `role_has_permissions_role_id_foreign`(`role_id`) USING BTREE,
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of role_has_permissions
-- ----------------------------
INSERT INTO `role_has_permissions` VALUES (1, 1, 1);
INSERT INTO `role_has_permissions` VALUES (1, 2, 1);
INSERT INTO `role_has_permissions` VALUES (2, 1, 2);
INSERT INTO `role_has_permissions` VALUES (2, 2, 2);
INSERT INTO `role_has_permissions` VALUES (3, 1, 3);
INSERT INTO `role_has_permissions` VALUES (3, 2, 3);
INSERT INTO `role_has_permissions` VALUES (4, 1, 4);
INSERT INTO `role_has_permissions` VALUES (4, 2, 4);
INSERT INTO `role_has_permissions` VALUES (5, 1, 5);
INSERT INTO `role_has_permissions` VALUES (5, 2, 5);
INSERT INTO `role_has_permissions` VALUES (6, 1, 6);
INSERT INTO `role_has_permissions` VALUES (6, 2, 6);
INSERT INTO `role_has_permissions` VALUES (7, 1, 7);
INSERT INTO `role_has_permissions` VALUES (7, 2, 7);
INSERT INTO `role_has_permissions` VALUES (8, 1, 4);
INSERT INTO `role_has_permissions` VALUES (8, 2, 4);
INSERT INTO `role_has_permissions` VALUES (9, 1, 5);
INSERT INTO `role_has_permissions` VALUES (9, 2, 5);
INSERT INTO `role_has_permissions` VALUES (10, 1, 6);
INSERT INTO `role_has_permissions` VALUES (10, 2, 6);
INSERT INTO `role_has_permissions` VALUES (11, 1, 11);
INSERT INTO `role_has_permissions` VALUES (11, 2, 11);
INSERT INTO `role_has_permissions` VALUES (12, 1, 12);
INSERT INTO `role_has_permissions` VALUES (12, 2, 12);
INSERT INTO `role_has_permissions` VALUES (13, 1, 8);
INSERT INTO `role_has_permissions` VALUES (14, 1, 9);
INSERT INTO `role_has_permissions` VALUES (15, 1, 15);
INSERT INTO `role_has_permissions` VALUES (16, 1, 16);
INSERT INTO `role_has_permissions` VALUES (17, 1, 17);
INSERT INTO `role_has_permissions` VALUES (18, 1, 18);
INSERT INTO `role_has_permissions` VALUES (19, 1, 19);
INSERT INTO `role_has_permissions` VALUES (20, 1, 10);
INSERT INTO `role_has_permissions` VALUES (21, 1, 11);
INSERT INTO `role_has_permissions` VALUES (22, 1, 22);
INSERT INTO `role_has_permissions` VALUES (23, 1, 12);
INSERT INTO `role_has_permissions` VALUES (24, 1, 24);
INSERT INTO `role_has_permissions` VALUES (25, 1, 13);
INSERT INTO `role_has_permissions` VALUES (26, 1, 26);
INSERT INTO `role_has_permissions` VALUES (27, 1, 27);
INSERT INTO `role_has_permissions` VALUES (28, 1, 28);
INSERT INTO `role_has_permissions` VALUES (29, 1, 29);
INSERT INTO `role_has_permissions` VALUES (30, 1, 14);
INSERT INTO `role_has_permissions` VALUES (31, 1, 31);
INSERT INTO `role_has_permissions` VALUES (32, 1, 32);
INSERT INTO `role_has_permissions` VALUES (33, 1, 33);
INSERT INTO `role_has_permissions` VALUES (34, 1, 15);
INSERT INTO `role_has_permissions` VALUES (34, 2, 7);
INSERT INTO `role_has_permissions` VALUES (35, 1, 16);
INSERT INTO `role_has_permissions` VALUES (35, 2, 8);
INSERT INTO `role_has_permissions` VALUES (36, 1, 36);
INSERT INTO `role_has_permissions` VALUES (36, 2, 15);
INSERT INTO `role_has_permissions` VALUES (37, 1, 17);
INSERT INTO `role_has_permissions` VALUES (37, 2, 9);
INSERT INTO `role_has_permissions` VALUES (38, 1, 38);
INSERT INTO `role_has_permissions` VALUES (38, 2, 17);
INSERT INTO `role_has_permissions` VALUES (39, 1, 18);
INSERT INTO `role_has_permissions` VALUES (39, 2, 10);
INSERT INTO `role_has_permissions` VALUES (40, 1, 7);

-- ----------------------------
-- Table structure for roles
-- ----------------------------
DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `order_by` int(10) NULL DEFAULT NULL,
  `is_active` int(1) NULL DEFAULT 1 COMMENT '0:tidak aktif, 1:aktif',
  `created_at` datetime(0) NULL DEFAULT NULL,
  `created_by` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_by_id` bigint(20) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `updated_by` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `updated_by_id` bigint(20) NULL DEFAULT NULL,
  `deleted_at` datetime(0) NULL DEFAULT NULL,
  `deleted_by` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `deleted_by_id` bigint(20) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `roles_name_unique`(`name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of roles
-- ----------------------------
INSERT INTO `roles` VALUES (1, 'Super Admin', 1, 1, NULL, NULL, NULL, '2025-04-17 21:44:28', 'Admin', 1, NULL, NULL, NULL);
INSERT INTO `roles` VALUES (2, 'User', 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for setup_running_numbers
-- ----------------------------
DROP TABLE IF EXISTS `setup_running_numbers`;
CREATE TABLE `setup_running_numbers`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `inisial` varchar(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL COMMENT 'Awalan',
  `length` int(10) NULL DEFAULT 5 COMMENT 'Panjang Karakter setelah inisial',
  `type` tinyint(1) NULL DEFAULT 0 COMMENT '0:random, 1:no_urut',
  `random_allow` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789',
  `description` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `created_by` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_by_id` bigint(20) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `updated_by` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `updated_by_id` bigint(20) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of setup_running_numbers
-- ----------------------------
INSERT INTO `setup_running_numbers` VALUES (1, 'buku-tamu', 'BT', 5, 1, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', 'Buku Tamu', NULL, NULL, NULL, '2025-04-17 21:37:57', 'Admin', 1);
INSERT INTO `setup_running_numbers` VALUES (2, 'md-jenis-identitas', 'JI', 5, 0, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789', 'Master Data Jenis Identitas', NULL, NULL, NULL, '2025-04-17 21:38:14', 'Admin', 1);

-- ----------------------------
-- Table structure for setup_webs
-- ----------------------------
DROP TABLE IF EXISTS `setup_webs`;
CREATE TABLE `setup_webs`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `app_name` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `title` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `description` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `website` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `favicon` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `logo` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `updated_by` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `updated_by_id` bigint(20) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of setup_webs
-- ----------------------------
INSERT INTO `setup_webs` VALUES (1, 'PHP Native Starter Kit', 'PHP Native Starter Kit', 'Aplikasi PHP Native Starter Kit', 'anwarsptr.com', 'uploads/webs/VCnO8GBlnciN530977rCh55TpxhF666hjm8vSPQq29e.jpg', 'uploads/webs/Qjqa0oJPShRfNFOnRD8xgz6ENmJkoVqUQxKDQNbjdbi.jpg', '2025-04-17 21:35:24', 'Admin', 1);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `foto` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `name` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `username` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `password` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `role_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT 'setup_roles.id',
  `is_active` tinyint(1) NULL DEFAULT 1 COMMENT '0:tidak aktif, 1:aktif',
  `last_login` datetime(0) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `created_by` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `created_by_id` bigint(20) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `updated_by` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `updated_by_id` bigint(20) NULL DEFAULT NULL,
  `deleted_at` datetime(0) NULL DEFAULT NULL,
  `deleted_by` varchar(100) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  `deleted_by_id` bigint(20) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `users_foreign`(`role_id`) USING BTREE,
  CONSTRAINT `users_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, NULL, 'Admin', 'admin', 'UlD1nsaSriqjzJG91d5tmhA8UNtbYT4iqKoNw3PCzON', 1, 1, '2025-04-17 21:53:19', NULL, NULL, NULL, '2025-04-17 23:02:02', 'Admin', 1, NULL, NULL, NULL);
INSERT INTO `users` VALUES (2, NULL, 'user', 'user', 'JZKF9CGTV2xBj5kVmihysElytSaqZdC9bR7JdkHHvcN', 2, 1, '2025-04-17 23:01:37', '2025-04-17 21:39:03', 'Admin', 1, '2025-04-17 21:43:06', 'Admin', 1, NULL, NULL, NULL);

SET FOREIGN_KEY_CHECKS = 1;
