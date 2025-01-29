/*
 Navicat Premium Dump SQL

 Source Server         : Lokal MySQL
 Source Server Type    : MySQL
 Source Server Version : 80300 (8.3.0)
 Source Host           : localhost:3306
 Source Schema         : registrasi_trailrun

 Target Server Type    : MySQL
 Target Server Version : 80300 (8.3.0)
 File Encoding         : 65001

 Date: 28/01/2025 21:19:47
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for kategori_lomba
-- ----------------------------
DROP TABLE IF EXISTS `kategori_lomba`;
CREATE TABLE `kategori_lomba`
(
    `id`         bigint UNSIGNED                                               NOT NULL AUTO_INCREMENT,
    `nama`       varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `harga`      double                                                        NULL DEFAULT NULL,
    `warna`      varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
    `kategori`   varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
    `deskripsi`  varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
    `created_at` timestamp                                                     NULL DEFAULT NULL,
    `updated_at` timestamp                                                     NULL DEFAULT NULL,
    PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB
  AUTO_INCREMENT = 5
  CHARACTER SET = utf8mb4
  COLLATE = utf8mb4_unicode_ci
  ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of kategori_lomba
-- ----------------------------
INSERT INTO `kategori_lomba`
VALUES (1, 'Early Bird 8K', 200000, '#8ad41e', 'early_bird', 'Kategori Early Bird 8K', '2025-01-28 20:28:55',
        '2025-01-28 20:32:17');
INSERT INTO `kategori_lomba`
VALUES (2, 'Early Bird 15K', 275000, '#8ad41e', 'early_bird', 'Kategori Early Bird 15K', '2025-01-28 20:29:10',
        '2025-01-28 20:32:03');
INSERT INTO `kategori_lomba`
VALUES (3, '8K', 250000, '#e6e857', 'normal', 'Kategori 8K', '2025-01-28 20:29:25', '2025-01-28 20:32:57');
INSERT INTO `kategori_lomba`
VALUES (4, '15K', 350000, '#e6e857', 'normal', 'Kategori 15K', '2025-01-28 20:29:43', '2025-01-28 20:33:01');

SET FOREIGN_KEY_CHECKS = 1;
