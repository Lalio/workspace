/*
Navicat MySQL Data Transfer

Source Server         : localhost_3306
Source Server Version : 50051
Source Host           : localhost:3306
Source Database       : pay

Target Server Type    : MYSQL
Target Server Version : 50051
File Encoding         : 65001

Date: 2015-03-14 20:29:41
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `dealrecord`
-- ----------------------------
DROP TABLE IF EXISTS `dealrecord`;
CREATE TABLE `dealrecord` (
  `id` int(20) NOT NULL auto_increment,
  `deal_id` varchar(128) NOT NULL default '' COMMENT '交易id',
  `myvid` int(20) NOT NULL COMMENT '我的vid',
  `othervid` int(20) default NULL COMMENT '对方vid',
  `commodity_type` int(20) NOT NULL,
  `commodity_id` varchar(20) default NULL COMMENT '商品id',
  `deal_type` int(20) NOT NULL COMMENT '买还是卖',
  `price` int(20) NOT NULL,
  `cost_voucher` int(20) NOT NULL COMMENT '代金券',
  `cost_change` int(20) NOT NULL COMMENT '现金',
  `cost_payment` int(20) NOT NULL,
  `pay_type` int(20) NOT NULL COMMENT '现金支付方式',
  `balance_voucher` int(20) NOT NULL COMMENT '存商品快照',
  `balance_change` int(20) NOT NULL,
  `create_time` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=299 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for `person_balance`
-- ----------------------------
DROP TABLE IF EXISTS `person_balance`;
CREATE TABLE `person_balance` (
  `id` int(20) NOT NULL auto_increment,
  `person_vid` int(20) default NULL,
  `person_balance_voucher` int(20) default NULL,
  `person_balance_change` int(20) default NULL,
  `person_count_frozen_voucher` int(20) default NULL,
  `person_count_frozen_change` int(20) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
