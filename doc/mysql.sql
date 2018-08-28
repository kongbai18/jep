drop table if exists `jiihome_admin`;
CREATE TABLE `jiihome_admin` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `username` varchar(30) NOT NULL UNIQUE COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  `phone` varchar(30) NOT NULL default '' COMMENT '手机号',
  `email` varchar(30) NOT NULL default '' COMMENT '邮箱',
  `is_index` tinyint unsigned not null default 0 comment '是否启用',
  `create_time` int(11) unsigned NOT NULL DEFAULT '0',
  `update_time` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员';

INSERT INTO `jiihome_admin` (`id`, `username`, `password`,`is_index`) VALUES
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
  `moudle_name` varchar(30) NOT NULL default '' COMMENT '模型名称',
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