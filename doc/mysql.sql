drop table if exists `jiihome_admin`;
CREATE TABLE `jiihome_admin` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `username` varchar(30) NOT NULL UNIQUE COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  `phone` varchar(30) NOT NULL default '' COMMENT '手机号',
  `email` varchar(30) NOT NULL default '' COMMENT '邮箱',
  `status` tinyint unsigned not null default 1 comment '是否启用',
  `last_login_ip` varchar(30) NOT NULL default '' COMMENT '最后登陆Ip',
  `last_login_time` int(12) unsigned NOT NULL DEFAULT '0' COMMENT '最后登陆时间',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
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

DROP TABLE IF EXISTS `jiihome_brand`;
CREATE TABLE `jiihome_brand` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '品牌ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '品牌名',
  `web_url` varchar(150) NOT NULL DEFAULT '' COMMENT '品牌官网',
  `image_url` varchar(150) NOT NULL DEFAULT '' COMMENT '图片logo',
  `sort_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `is_index` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '是否展示',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '品牌表';

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
  `brand_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '对应品牌ID',
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
  KEY `category_id` (`category_id`),
  KEY `brand_id` (`brand_id`)
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
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品specID',
  `goods_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品ID',
  `goods_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商品价格',
  `line_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '划线价格',
  `stock_num` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '库存',
  `goods_weight` double unsigned NOT NULL DEFAULT '0' COMMENT '商品重量',
  `spec_sku_id` varchar(255) NOT NULL DEFAULT '0' COMMENT '对应sku值ID',
  `image_url` varchar(150) NOT NULL default '' COMMENT '图片地址',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `goods_id`(`goods_id`,`spec_sku_id`)
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
  `image_url` varchar(150) NOT NULL DEFAULT '' COMMENT '封面图片路径',
  `content` longtext NOT NULL COMMENT '详情',
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

DROP TABLE IF EXISTS `jiihome_furniture`;
CREATE TABLE `jiihome_furniture` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `fur_name` varchar(30) NOT NULL  COMMENT '家具名称',
  `image_url` varchar(150) NOT NULL DEFAULT '' COMMENT '封面图',
  `cate_id` tinyint(4) UNSIGNED NOT NULL COMMENT '分类ID；1柜体，2门，3饰面',
  `sort_id` int(10) UNSIGNED NOT NULL DEFAULT '100' COMMENT '排序',
  `is_index` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否显示',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
   PRIMARY KEY (`id`),
   KEY `cate_id` (`cate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='家具表';


DROP TABLE IF EXISTS `jiihome_attr`;
CREATE TABLE `jiihome_attr` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '规格组ID',
  `furniture_id` int(11) NOT NULL COMMENT '家具ID',
  `attr_name` varchar(255) NOT NULL DEFAULT '' COMMENT '规格组名称',
  `type_id` tinyint NOT NULL DEFAULT '1' COMMENT '规格组类型，1选择，2增减',
  `create_time` int(11) NOT NULL default '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `attr_name,type_id`(`attr_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '规格组';

DROP TABLE IF EXISTS `jiihome_attr_value`;
CREATE TABLE `jiihome_attr_value` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '规格值ID',
  `attr_value` varchar(255) NOT NULL default '' COMMENT '规格值',
  `attr_id` int(11) NOT NULL COMMENT '规格ID',
  `create_time` int(11) NOT NULL default '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `attr_id`(`attr_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '规格值';

DROP TABLE IF EXISTS `jiihome_furniture_attr`;
CREATE TABLE `jiihome_furniture_attr` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '家具规格ID',
  `fur_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '家具ID',
  `model_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '模型ID',
  `attr_sku_id` varchar(255) NOT NULL DEFAULT '' COMMENT '对应sku值ID',
  `image_url` varchar(150) NOT NULL DEFAULT '' COMMENT '图片地址',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `fur_id,attr_sku_id`(`fur_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '家具规格模型对应表';

DROP TABLE IF EXISTS `jiihome_model`;
CREATE TABLE `jiihome_model` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '模型ID',
  `model_name` varchar(255) NOT NULL DEFAULT '' COMMENT '模型名称',
  `project_area` varchar(255) NOT NULL DEFAULT '' COMMENT '投影面积公式',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '计算模型表';

DROP TABLE IF EXISTS `jiihome_model_material`;
CREATE TABLE `jiihome_model_material` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '材料ID',
  `model_id` int(11) unsigned NOT NULL COMMENT '模型ID',
  `material_name` varchar(255) NOT NULL DEFAULT '' COMMENT '材料块名称',
  `material_para` varchar(50) NOT NULL DEFAULT '' COMMENT '材料参数',
  `material_goods` varchar(100) NOT NULL DEFAULT '' COMMENT '材料商品ID组合',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `model_id`(`model_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '模型对应材料表';

DROP TABLE IF EXISTS `jiihome_model_parameter`;
CREATE TABLE `jiihome_model_parameter` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '参数ID',
  `model_id` int(11) unsigned NOT NULL COMMENT '模型ID',
  `parameter` varchar(50) NOT NULL DEFAULT '' COMMENT '参数',
  `min` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '参数最小值',
  `max` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '参数最大值',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `model_id`(`model_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '模型对应参数表';

DROP TABLE IF EXISTS `jiihome_model_formula`;
CREATE TABLE `jiihome_model_formula` (
  `model_id` int(11) unsigned NOT NULL COMMENT '模型ID',
  `formula_name` varchar(50) NOT NULL DEFAULT '' COMMENT '公式名称',
  `number` varchar(300) NOT NULL DEFAULT '' COMMENT '数量计算',
  `price` varchar(300) NOT NULL DEFAULT '' COMMENT '单价计算',
  `unit` varchar(50) NOT NULL DEFAULT '' COMMENT '计量单位',
  `remark` varchar(150) NOT NULL DEFAULT '' COMMENT '备注',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  KEY `model_id`(`model_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '模型对应公式表';

DROP TABLE IF EXISTS `jiihome_model_ext`;
CREATE TABLE `jiihome_model_ext` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '扩展ID',
  `model_id` int(11) unsigned NOT NULL COMMENT '模型ID',
  `type_id` int(11) unsigned NOT NULL COMMENT '类型ID：1单选；2多选；3输入框',
  `ext_name` varchar(50) NOT NULL DEFAULT '' COMMENT '扩展名',
  `ext_para` varchar(150) NOT NULL DEFAULT '' COMMENT '扩展参数',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `model_id`(`model_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '模型对应扩展表';

DROP TABLE IF EXISTS `jiihome_model_ext_val`;
CREATE TABLE `jiihome_model_ext_val` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '扩展值ID',
  `ext_id` int(11) unsigned NOT NULL COMMENT '扩展ID',
  `par_name` varchar(50) NOT NULL DEFAULT '' COMMENT '参数名',
  `ext_val` varchar(150) NOT NULL DEFAULT '' COMMENT '扩展参数值',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `ext_id`(`ext_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '模型对应扩展值表';


DROP TABLE IF EXISTS `jiihome_offer`;
CREATE TABLE `jiihome_offer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '报价ID',
  `role_id` int(11) unsigned NOT NULL COMMENT '10代表管理员；20代表用户',
  `user_id` int(11) unsigned NOT NULL COMMENT '用户或管理员ID',
  `fur_attr_id` int(11) unsigned NOT NULL COMMENT '家具属性ID',
  `space` varchar(50) NOT NULL DEFAULT '' COMMENT '家具所在地址',
  `material` varchar(500) NOT NULL DEFAULT '' COMMENT '材料',
  `parameter` varchar(500) NOT NULL DEFAULT '' COMMENT '参数',
  `ext` varchar(500) NOT NULL DEFAULT '' COMMENT '扩展值',
  `is_delete` tinyint NOT NULL DEFAULT 0 COMMENT '是否删除，1删除，0正常',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `role_id,user_id,is_delete`(`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '报价表';


drop table if exists `jiihome_user`;
CREATE TABLE `jiihome_user` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `username` varchar(30) NOT NULL default '' COMMENT '用户名',
  `password` char(32) NOT NULL default '' COMMENT '密码',
  `phone` varchar(30) NOT NULL default '' COMMENT '手机号',
  `email` varchar(30) NOT NULL default '' COMMENT '邮箱',
  `image_url` varchar(150) NOT NULL default 'http://static.jiihome.com/D9A95F44-C02A-42f4-8D29-BBBF1846053A.png' COMMENT '头像地址',
  `last_login_ip` varchar(30) NOT NULL default '' COMMENT '最后登陆Ip',
  `last_login_time` int(12) unsigned NOT NULL DEFAULT '0' COMMENT '最后登陆时间',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户';

drop table if exists `jiihome_cart`;
CREATE TABLE `jiihome_cart` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `user_id` int UNSIGNED NOT NULL COMMENT '用户Id',
  `product_type` tinyint UNSIGNED NOT NULL COMMENT '商品类型；1商品，2.定制',
  `product_id` int UNSIGNED NOT NULL COMMENT '商品Id',
  `spec_sku_id` varchar(30) NOT NULL COMMENT '商品sku',
  `goods_name` varchar(30) NOT NULL COMMENT '商品名',
  `image_url` varchar(150) NOT NULL COMMENT '商品图',
  `goods_attr` varchar(150) NOT NULL COMMENT '所选商品属性',
  `goods_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商品价格',
  `goods_num` int UNSIGNED NOT NULL COMMENT '商品数量',
  `is_delete` tinyint NOT NULL DEFAULT 0 COMMENT '是否删除，1删除，0正常',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  KEY `user_id`(`user_id`,`is_delete`,`product_type`,`product_id`,`spec_sku_id`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='购物车';

drop table if exists `jiihome_address`;
CREATE TABLE `jiihome_address` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `user_id` int UNSIGNED NOT NULL COMMENT '用户Id',
  `sccept_name` varchar(30) NOT NULL COMMENT '收货人姓名',
  `telphone` varchar(30) NOT NULL COMMENT '手机号',
  `area_id` int UNSIGNED NOT NULL COMMENT '地区Id',
  `area_name` varchar(50) NOT NULL COMMENT '地区长名称',
  `address` varchar(50) NOT NULL COMMENT '详细地址',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  KEY `user_id`(`user_id`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='收货地址';

drop table if exists `jiihome_order`;
CREATE TABLE `jiihome_order` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `order_no` varchar(30) NOT NULL COMMENT '订单编号',
  `parent_no` varchar(30) NOT NULL default '0' COMMENT '上级订单号',
  `user_id` int UNSIGNED NOT NULL COMMENT '用户Id',
  `total_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '总价格',
  `pay_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '支付价格',
  `pay_status` tinyint UNSIGNED NOT NULL DEFAULT '20' COMMENT '支付状态：10支付；20未支付',
  `pay_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '支付时间',
  `order_status` tinyint UNSIGNED NOT NULL DEFAULT '20' COMMENT '订单状态：10已发货；20未发货;30已收货；40申请退货；70已退货',
  `express_no` varchar(30) NOT NULL DEFAULT '' COMMENT '快递单号',
  `wxpay_no` varchar(30) NOT NULL default '' COMMENT '微信支付编号',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  KEY `order_no`(`order_no`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单';

drop table if exists `jiihome_order_address`;
CREATE TABLE `jiihome_order_address` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `order_no` varchar(30) NOT NULL COMMENT '订单编号',
  `sccept_name` varchar(30) NOT NULL COMMENT '收货人姓名',
  `telphone` varchar(30) NOT NULL COMMENT '手机号',
  `area_id` int UNSIGNED NOT NULL COMMENT '地区Id',
  `area_name` varchar(50) NOT NULL COMMENT '地区长名称',
  `address` varchar(50) NOT NULL COMMENT '详细地址',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  KEY `order_no`(`order_no`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单收货地址';

drop table if exists `jiihome_order_goods`;
CREATE TABLE `jiihome_order_goods` (
  `order_no` varchar(30) NOT NULL COMMENT '订单编号',
  `product_type` tinyint UNSIGNED NOT NULL COMMENT '商品类型；1商品，2.定制',
  `product_id` int UNSIGNED NOT NULL COMMENT '商品Id',
  `spec_sku_id` varchar(30) NOT NULL COMMENT '商品sku',
  `goods_name` varchar(30) NOT NULL COMMENT '商品名',
  `image_url` varchar(150) NOT NULL COMMENT '商品图',
  `goods_attr` varchar(150) NOT NULL COMMENT '所选商品属性',
  `goods_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '商品价格',
  `goods_num` int UNSIGNED NOT NULL COMMENT '商品数量',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  KEY `order_no`(`order_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='订单商品';

drop table if exists `jiihome_half_custom`;
CREATE TABLE `jiihome_half_custom` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `halfcust_name` varchar(30) NOT NULL COMMENT '半报价名',
  `goods_id` int UNSIGNED NOT NULL COMMENT '商品Id',
  `sort_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `is_index` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '是否展示',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  primary key (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='半定制主商品表';

drop table if exists `jiihome_half_customdtl`;
CREATE TABLE `jiihome_half_customdtl` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `halfcus_id` int UNSIGNED NOT NULL COMMENT '半定制Id',
  `type_name` varchar(30) NOT NULL COMMENT '类型名',
  `num` tinyint NOT NULL default 1 COMMENT '该类型需要商品数量',
  `goods` varchar(30) NOT NULL COMMENT '类型包含商品集合',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  primary key (`id`),
  key `halfcus_id`(`halfcus_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='半定制详情表';

drop table if exists `jiihome_half_custom_quote`;
CREATE TABLE `jiihome_half_custom_quote` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `user_type` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '用户类型：1用户；2管理员',
  `user_id` int UNSIGNED NOT NULL COMMENT '用户Id',
  `halfcus_id` int UNSIGNED NOT NULL COMMENT '半定制Id',
  `spec_sku_id` varchar(255) NOT NULL DEFAULT '0' COMMENT '框架对应sku值ID',
  `is_delete` tinyint NOT NULL DEFAULT 0 COMMENT '是否删除，1删除，0正常',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  primary key (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='半定制报价表';

drop table if exists `jiihome_half_custom_quotedtl`;
CREATE TABLE `jiihome_half_custom_quotedtl` (
  `hcq_id` int UNSIGNED NOT NULL COMMENT '半定制报价Id',
  `type_name` varchar(30) NOT NULL COMMENT '配置类型名',
  `goods_id` int UNSIGNED NOT NULL COMMENT '商品Id',
  `spec_sku_id` varchar(255) NOT NULL DEFAULT '0' COMMENT '对应sku值ID',
  `num` tinyint NOT NULL default 1 COMMENT '该类型需要商品数量',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  key `hcq_id`(`hcq_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='半定制报价详情表';

drop table if exists `jiihome_custom_quote`;
CREATE TABLE `jiihome_custom_quote` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `user_type` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '用户类型：1用户；2管理员',
  `user_id` int UNSIGNED NOT NULL COMMENT '用户Id',
  `fur_id` int UNSIGNED NOT NULL COMMENT '家具Id',
  `attr_sku_id` varchar(255) NOT NULL DEFAULT '0' COMMENT '对应sku值ID组',
  `is_delete` tinyint NOT NULL DEFAULT 0 COMMENT '是否删除，1删除，0正常',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  primary key (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='定制报价表';


DROP TABLE IF EXISTS `jiihome_custom_parameter`;
CREATE TABLE `jiihome_custom_parameter` (
  `cust_id` int(11) unsigned NOT NULL COMMENT '定制ID',
  `parameter` varchar(50) NOT NULL DEFAULT '' COMMENT '参数',
  `value` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '参数值',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  KEY `model_id`(`cust_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '报价对应参数表';

DROP TABLE IF EXISTS `jiihome_custom_material`;
CREATE TABLE `jiihome_custom_material` (
  `cust_id` int(11) unsigned NOT NULL COMMENT '定制ID',
  `material_id` int(11) unsigned NOT NULL COMMENT '材料Id',
  `goods_id` varchar(100) NOT NULL DEFAULT '' COMMENT '材料商品ID',
  `spec_sku_id` varchar(255) NOT NULL DEFAULT '0' COMMENT '商品对应sku值ID组',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  KEY `cust_id`(`cust_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '报价对应材料表';

DROP TABLE IF EXISTS `jiihome_custom_ext`;
CREATE TABLE `jiihome_custom_ext` (
  `cust_id` int(11) unsigned NOT NULL COMMENT '定制ID',
  `ext_id` int(11) unsigned NOT NULL COMMENT '模型扩展ID',
  `ext_val` varchar(50) NOT NULL DEFAULT '' COMMENT '类型为1、2时为扩展值ID，为3时为扩展值',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  KEY `cust_id`(`cust_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '报价对应扩展表';

drop table if exists `jiihome_wxuser`;
CREATE TABLE `jiihome_wxuser` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `user_id` int(11) unsigned NOT NULL default '0' COMMENT '对应用户表ID',
  `open_id` varchar(50) NOT NULL default '' COMMENT '微信对应唯一ID',
  `nickName` varchar(50) NOT NULL default '' COMMENT '用户名',
  `avatarUrl` varchar(150) NOT NULL default '' COMMENT '头像地址',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `user_id`(`user_id`),
  KEY `open_id`(`open_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信用户表';


drop table if exists `jiihome_carousel`;
CREATE TABLE `jiihome_carousel` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `image_url` varchar(150) NOT NULL COMMENT '轮播图地址',
  `web_url` varchar(150) NOT NULL DEFAULT '' COMMENT '跳转路径',
  `sort_id` smallint(5) UNSIGNED NOT NULL DEFAULT '100' COMMENT '排序',
  `is_index` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '是否展示',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信轮播图';

drop table if exists `jiihome_theme`;
CREATE TABLE `jiihome_theme` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `theme_id` smallint (10) UNSIGNED NOT NULL default '1' COMMENT '展示方式ID',
  `cate_id` int(10) UNSIGNED NOT NULL DEFAULT '1' COMMENT '对应分类ID',
  `sort_id` smallint(5) UNSIGNED NOT NULL DEFAULT '100' COMMENT '排序',
  `is_index` tinyint unsigned NOT NULL DEFAULT '1' COMMENT '是否展示',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '修改时间',
  PRIMARY KEY (`id`),
  KEY `theme_id`(`theme_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='微信首页展示方式';