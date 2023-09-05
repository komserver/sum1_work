/*
Navicat MySQL Data Transfer

Source Server         : 192.168.0.108
Source Server Version : 50505
Source Host           : 192.168.0.108:3311
Source Database       : sum1_test

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2023-09-04 20:00:36
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `objects`
-- ----------------------------
DROP TABLE IF EXISTS `objects`;
CREATE TABLE `objects` (
  `id` int(11) NOT NULL,
  `code` varchar(255) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`,`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of objects
-- ----------------------------
INSERT INTO `objects` VALUES ('1', 'login', 'Petrov');
INSERT INTO `objects` VALUES ('1', 'name', 'Петров');
INSERT INTO `objects` VALUES ('2', 'login', 'Sidorov');
INSERT INTO `objects` VALUES ('2', 'name', 'Сидоров');
INSERT INTO `objects` VALUES ('3', 'login', 'Ivanov');
INSERT INTO `objects` VALUES ('3', 'name', 'Иванов');

-- ----------------------------
-- Table structure for `_users`
-- ----------------------------
DROP TABLE IF EXISTS `_users`;
CREATE TABLE `_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `login` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ----------------------------
-- Records of _users
-- ----------------------------
