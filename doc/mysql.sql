CREATE TABLE `jiihome_admin` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `username` varchar(30) NOT NULL UNIQUE COMMENT '用户名',
  `password` char(32) NOT NULL COMMENT '密码',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员';

INSERT INTO `jiihome_admin` (`id`, `username`, `password`) VALUES
(1, 'root', '48601017c9c217061bc9c231f246ca7f');

drop table if exists `jiihome_role`;
CREATE TABLE `jiihome_role` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `role_name` varchar(30) NOT NULL UNIQUE COMMENT '角色名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='角色表';

drop table if exists `jiihome_permission`;
CREATE TABLE `jiihome_permission` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Id',
  `name` varchar(30) NOT NULL COMMENT '权限名称',
  `moudle_name` varchar(30) NOT NULL COMMENT '模型名称',
  `controller_name` varchar(30) NOT NULL COMMENT '控制器名称',
  `action_name` varchar(30) NOT NULL COMMENT '方法名称',
  `parent_id` int UNSIGNED NOT NULL COMMENT '上级分类ID',
  `sort_id` int UNSIGNED NOT NULL default 100 COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='权限表';