drop table if exists `jiihome_admin`;
CREATE TABLE `jiihome_admin` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `username` varchar(30) NOT NULL UNIQUE COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  `phone` varchar(30) NOT NULL default '' COMMENT '手机号',
  `email` varchar(30) NOT NULL default '' COMMENT '邮箱',
  `status` tinyint unsigned not null default 0 comment '是否启用',
  `last_login_ip` int(10) unsigned NOT NULL DEFAULT '0',
  `last_login_time` int(11) unsigned NOT NULL DEFAULT '0',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员';

INSERT INTO `jiihome_admin` (`id`, `username`, `password`,`status`) VALUES
(1, 'root', '5e677af31eddafcdad40cf3ed50d1a25','1');

drop table if exists `jiihome_role`;
CREATE TABLE `jiihome_role` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `name` varchar(30) NOT NULL UNIQUE COMMENT '角色名称',
  `content` varchar(150) NOT NULL default '' COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色表';
INSERT INTO `jiihome_role` (`id`, `name`, `content`) VALUES
(1, '超级管理员', '拥有至高无上的权限');

drop table if exists `jiihome_permission`;
CREATE TABLE `jiihome_permission` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `name` varchar(30) NOT NULL COMMENT '权限名称',
  `module_name` varchar(30) NOT NULL default '' COMMENT '模型名称',
  `controller_name` varchar(30) NOT NULL default '' COMMENT '控制器名称',
  `action_name` varchar(30) NOT NULL default '' COMMENT '方法名称',
  `parent_id` int UNSIGNED NOT NULL COMMENT '上级分类ID',
  `sort_id` int UNSIGNED NOT NULL default 100 COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限表';

drop table if exists `jiihome_role_permission`;
CREATE TABLE `jiihome_role_permission` (
  `role_id` int(10) UNSIGNED NOT NULL COMMENT '角色ID',
  `per_id` int(10) UNSIGNED NOT NULL COMMENT '权限ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限对应表';

drop table if exists `jiihome_admin_role`;
CREATE TABLE `jiihome_admin_role` (
  `role_id` int(10) UNSIGNED NOT NULL COMMENT '角色ID',
  `admin_id` int(10) UNSIGNED NOT NULL COMMENT '管理员ID'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员角色对应表';
INSERT INTO `jiihome_admin_role` (`role_id`, `admin_id`) VALUES
(1, 1);


DROP TABLE IF EXISTS `jiihome_category`;
CREATE TABLE `jiihome_category` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '分类名',
  `en_name` varchar(50) NOT NULL DEFAULT '' COMMENT '分类英文名',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父级ID',
  `image_url` varchar(150) NOT NULL DEFAULT '' COMMENT '图片路径',
  `sort_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `is_index` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '是否展示',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '分类表';

DROP TABLE IF EXISTS `jiihome_spec`;
CREATE TABLE `jiihome_spec` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '规格组ID',
  `spec_name` varchar(255) NOT NULL DEFAULT '' COMMENT '规格组名称',
  `type_id` tinyint NOT NULL DEFAULT '1' COMMENT '规格组类型，1文字，2图片',
  `create_time` int(11) NOT NULL default '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `spec_name,type_id`(`spec_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '规格组';

DROP TABLE IF EXISTS `jiihome_spec_value`;
CREATE TABLE `jiihome_spec_value` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '规格值ID',
  `spec_value` varchar(255) NOT NULL default '' COMMENT '规格值',
  `spec_value_alt` varchar(255) NOT NULL default '' COMMENT '规格值提示词',
  `spec_id` int(11) NOT NULL COMMENT '规格ID',
  `create_time` int(11) NOT NULL default '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `spec_value_alt,spec_id`(`spec_value_alt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '规格值';

DROP TABLE IF EXISTS `jiihome_delivery`;
CREATE TABLE `jiihome_delivery` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '配送模板ID',
  `delivery_name` varchar(255) NOT NULL DEFAULT '' COMMENT '配送名称',
  `method` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '配送计算方式，10按件，20按重量',
  `sort_id` int(11) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '配送模板';

DROP TABLE IF EXISTS `jiihome_delivery_rule`;
CREATE TABLE `jiihome_delivery_rule` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '配送规则ID',
  `delivery_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '配送模板ID',
  `region` text NOT NULL COMMENT '包含地区',
  `first` double unsigned NOT NULL DEFAULT '0' COMMENT '首件数或者重量',
  `first_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '首件费用',
  `additional` double unsigned NOT NULL DEFAULT '0' COMMENT '续件续重',
  `additional_fee` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '续件续重费',
  `create_time` int(11) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '运费规则表';

DROP TABLE IF EXISTS `jiihome_region`;
CREATE TABLE `jiihome_region` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '地区ID',
  `pid` int(11) DEFAULT NULL COMMENT '上级ID',
  `shortname` varchar(100) DEFAULT NULL COMMENT '简称',
  `name` varchar(100) DEFAULT NULL COMMENT '全称',
  `merger_name` varchar(255) DEFAULT NULL COMMENT '长名称',
  `level` tinyint(4) unsigned DEFAULT '0' COMMENT '级别',
  `pinyin` varchar(100) DEFAULT NULL COMMENT '拼音名',
  `code` varchar(100) DEFAULT NULL COMMENT '区号',
  `zip_code` varchar(100) DEFAULT NULL COMMENT '邮编',
  `first` varchar(50) DEFAULT NULL COMMENT '首字母',
  `lng` varchar(100) DEFAULT NULL COMMENT '经度',
  `lat` varchar(100) DEFAULT NULL COMMENT '纬度',
  PRIMARY KEY (`id`),
  KEY `name,level` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '地区表';

DROP TABLE IF EXISTS `jiihome_goods`;
CREATE TABLE `jiihome_goods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品ID',
  `goods_name` varchar(255) NOT NULL DEFAULT '' COMMENT '商品名称',
  `category_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '对应分类ID',
  `spec_type` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '规格类型',
  `content` longtext NOT NULL COMMENT '详情',
  `sales_actual` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '实际销量',
  `sort_id` int(11) unsigned NOT NULL DEFAULT '100' COMMENT '排序',
  `delivery_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '运费模板ID',
  `goods_status` tinyint(3) unsigned NOT NULL DEFAULT '10' COMMENT '商品状态',
  `is_delete` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否删除',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '商品表';

DROP TABLE IF EXISTS `jiihome_goods_image`;
CREATE TABLE `jiihome_goods_image` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品图片ID',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品Id',
  `image_url` varchar(150) NOT NULL COMMENT '图片地址',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `goods_id`(`goods_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '商品图片表';

DROP TABLE IF EXISTS `jiihome_goods_spec`;
CREATE TABLE `jiihome_goods_spec` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品规格ID',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `goods_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商品价格',
  `line_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '划线价格',
  `stock_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '库存',
  `goods_sales` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '售出量',
  `goods_weight` double unsigned NOT NULL DEFAULT '0' COMMENT '商品重量',
  `spec_sku_id` varchar(255) NOT NULL DEFAULT '' COMMENT '对应sku值ID',
  `image_url` varchar(150) NOT NULL COMMENT '图片地址',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '商品规格库存表';

DROP TABLE IF EXISTS `jiihome_goods_spec_rel`;
CREATE TABLE `jiihome_goods_spec_rel` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品规格关系ID',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品Id',
  `spec_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '规格ID',
  `spec_value_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '规格值Id',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '商品规格关系表';

DROP TABLE IF EXISTS `jiihome_article_cate`;
CREATE TABLE `jiihome_article_cate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '分类ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '分类名',
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父级ID',
  `image_url` varchar(150) NOT NULL DEFAULT '' COMMENT '图片路径',
  `sort_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `is_index` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '是否展示',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '分类表';

DROP TABLE IF EXISTS `jiihome_article`;
CREATE TABLE `jiihome_article` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '文章ID',
  `article_name` varchar(50) NOT NULL DEFAULT '' COMMENT '文章名',
  `article_brief` varchar(150) NOT NULL DEFAULT '' COMMENT '简介',
  `cate_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
  `image_url` varchar(150) NOT NULL DEFAULT '' COMMENT '图片路径',
  `sort_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `is_index` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '是否展示',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '文章表';

DROP TABLE IF EXISTS `jiihome_article_goods_rel`;
CREATE TABLE `jiihome_article_goods_rel` (
  `article_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '文章ID',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  KEY `article_id`(`article_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '分类表';