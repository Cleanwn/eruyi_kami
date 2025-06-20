<?php
$sql = [
"CREATE TABLE IF NOT EXISTS `{$db_pre}app` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT 'APP名称',
  `appkey` varchar(32) NOT NULL COMMENT 'APPKEY',
  `mode` enum('y','n') DEFAULT 'y' COMMENT '运营模式',
  `state` enum('y','n') DEFAULT 'y' COMMENT 'APP状态',
  `notice` varchar(255) DEFAULT NULL COMMENT '关闭通知',
  `reg_state` enum('y','n') DEFAULT 'y' COMMENT '注册状态',
  `reg_notice` varchar(255) DEFAULT NULL COMMENT '注册关闭通知',
  `reg_ipon` int(10) DEFAULT '24' COMMENT '注册IP限制',
  `reg_inon` int(10) DEFAULT '24' COMMENT '注册信息限制',
  `logon_state` enum('y','n') DEFAULT 'y' COMMENT '登录状态',
  `logon_notice` varchar(255) DEFAULT NULL COMMENT '登录关闭通知',
  `logon_check_in` enum('y','n') DEFAULT 'y' COMMENT '登录校验设备信息',
  `logon_check_t` int(10) DEFAULT '24' COMMENT '登录换绑机器码间隔',
  `logon_num` int(10) DEFAULT '1' COMMENT '多设备登录',
  `logon_way` int(10) DEFAULT '0' COMMENT '登录方式',
  `reg_award` enum('vip','fen') DEFAULT 'vip' COMMENT '注册奖励',
  `reg_award_num` int(10) DEFAULT '30' COMMENT '注册奖励数',
  `inv_award` enum('vip','fen') DEFAULT 'vip' COMMENT '邀请奖励',
  `inv_award_num` int(10) DEFAULT '24' COMMENT '邀请奖励数',
  `diary_award` enum('vip','fen') DEFAULT 'fen' COMMENT '签到奖励',
  `diary_award_num` int(10) DEFAULT '10' COMMENT '签到奖励数',
  `app_bb` varchar(10) DEFAULT '1.0' COMMENT 'APP版本',
  `app_nshow` text COMMENT '更新内容',
  `app_nurl` varchar(255) DEFAULT NULL COMMENT '更新地址',
  `pay_state` enum('y','n') DEFAULT 'n' COMMENT '支付状态',
  `pay_url` varchar(255) DEFAULT 'https://pay.muitc.com' COMMENT '支付地址',
  `pay_id` varchar(200) DEFAULT NULL COMMENT '商户ID',
  `pay_key` varchar(200) DEFAULT NULL COMMENT '商户KEY',
  `pay_ali_state` enum('y','n') DEFAULT 'y' COMMENT '支付宝状态',
  `pay_wx_state` enum('y','n') DEFAULT 'y' COMMENT '微信状态',
  `pay_qq_state` enum('y','n') DEFAULT 'y' COMMENT 'QQ状态',
  `pay_notify` varchar(255) DEFAULT NULL COMMENT '异步通知地址',
  `mi_state` enum('y','n') DEFAULT 'y' COMMENT '加密控制',
  `mi_type` int(10) DEFAULT '0' COMMENT '加密类型',
  `mi_sign` enum('y','n') DEFAULT 'y' COMMENT '是否签名',
  `mi_time` int(10) DEFAULT '100' COMMENT '是否校验时间',
  `mi_rsa_private_key` text COMMENT 'RSA私钥',
  `mi_rsa_public_key` text COMMENT 'RSA公钥',
  `mi_rc4_key` varchar(255) DEFAULT NULL COMMENT 'RC4秘钥',
  `smtp_state` enum('y','n') DEFAULT 'n' COMMENT '邮箱状态',
  `smtp_host` varchar(255) DEFAULT 'smtp.exmail.qq.com' COMMENT '邮箱服务器',
  `smtp_user` varchar(255) DEFAULT NULL COMMENT '邮箱账户',
  `smtp_pass` varchar(255) DEFAULT NULL COMMENT '邮箱密码',
  `smtp_port` int(10) DEFAULT '25' COMMENT '邮箱端口',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`appkey`),
  KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10000 ;"
,
"CREATE TABLE IF NOT EXISTS `{$db_pre}app_exten` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL COMMENT '变量名',
  `data` text NOT NULL COMMENT '数据',
  `appid` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
,
"CREATE TABLE IF NOT EXISTS `{$db_pre}captcha` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `email` varchar(32) DEFAULT NULL COMMENT '邮箱',
  `phone` bigint(11) DEFAULT NULL COMMENT '手机号',
  `code` int(6) NOT NULL COMMENT '验证码',
  `new` enum('y','n') DEFAULT 'y' COMMENT '是否可被使用',
  `appid` int(10) NOT NULL COMMENT '应用ID',
  `time` int(10) NOT NULL COMMENT '时间戳',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
,
"CREATE TABLE IF NOT EXISTS `{$db_pre}fen` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL COMMENT '应用ID',
  `name` varchar(255) NOT NULL COMMENT '积分事件名称',
  `fen_num` int(10) NOT NULL COMMENT '积分数',
  `vip_num` int(10) DEFAULT '0' COMMENT '获得VIP天数',
  `state` enum('y','n') DEFAULT 'y' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
,
"CREATE TABLE IF NOT EXISTS `{$db_pre}fen_order` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `fid` int(10) NOT NULL COMMENT '积分事件ID',
  `uid` int(10) NOT NULL COMMENT '用户ID',
  `mark` varchar(255) DEFAULT NULL COMMENT '扣分标记',
  `time` int(10) NOT NULL COMMENT '记录时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
,
"CREATE TABLE IF NOT EXISTS `{$db_pre}goods` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '商品名称',
  `type` enum('fen','vip') NOT NULL COMMENT '商品类型',
  `money` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '金额',
  `amount` int(10) NOT NULL COMMENT '到账数',
  `jie` text COMMENT '商品介绍',
  `appid` int(10) DEFAULT '0' COMMENT '应用',
  `state` enum('y','n') DEFAULT 'y' COMMENT '商品状态',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
,
"CREATE TABLE IF NOT EXISTS `{$db_pre}goods_order` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `order` varchar(64) NOT NULL COMMENT '订单号',
  `uid` int(10) NOT NULL COMMENT '用户ID',
  `gid` int(10) NOT NULL COMMENT '商品ID',
  `name` varchar(255) NOT NULL COMMENT '商品名称',
  `money` float(10,2) NOT NULL DEFAULT '0.00' COMMENT '支付金额',
  `o_time` int(10) NOT NULL COMMENT '订单时间',
  `p_time` int(10) DEFAULT '0' COMMENT '支付时间',
  `p_type` enum('ali','wx','qq') NOT NULL COMMENT '支付类型',
  `state` int(10) DEFAULT '0' COMMENT '订单状态',
  `data` text COMMENT '回调数据',
  PRIMARY KEY (`id`),
  UNIQUE KEY `order` (`order`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
,
"CREATE TABLE IF NOT EXISTS `{$db_pre}kami` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `appid` int(10) NOT NULL COMMENT '应用ID',
  `kami` varchar(32) NOT NULL COMMENT '卡密',
  `type` enum('vip','fen') DEFAULT 'vip' COMMENT '卡密类型',
  `amount` int(10) NOT NULL COMMENT '数量',
  `note` varchar(255) DEFAULT NULL COMMENT '备注',
  `user` varchar(32) DEFAULT NULL COMMENT '使用者',
  `use_time` int(10) DEFAULT '0' COMMENT '使用时间',
  `end_time` int(10) DEFAULT '0' COMMENT '结束时间',
  `new` enum('y','n') DEFAULT 'n' COMMENT '是否导出',
  `state` enum('y','n') DEFAULT 'y' COMMENT '状态',
  PRIMARY KEY (`id`),
  UNIQUE KEY `kami` (`kami`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
,
"CREATE TABLE IF NOT EXISTS `{$db_pre}user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `user` varchar(32) DEFAULT NULL COMMENT '账号',
  `email` varchar(32) DEFAULT NULL COMMENT '邮箱',
  `phone` bigint(11) DEFAULT NULL COMMENT '手机号',
  `pwd` varchar(32) DEFAULT NULL COMMENT '密码',
  `name` varchar(255) DEFAULT '这个人没有名字！' COMMENT '昵称',
  `pic` varchar(255) DEFAULT '0.png' COMMENT '头像',
  `vip` int(10) DEFAULT '0' COMMENT 'VIP时间戳',
  `fen` int(10) DEFAULT '0' COMMENT '积分',
  `inv` int(10) DEFAULT '0' COMMENT '邀请人',
  `reg_time` int(10) NOT NULL COMMENT '注册时间',
  `reg_ip` varchar(15) DEFAULT '127.0.0.1' COMMENT '注册IP',
  `reg_in` varchar(255) DEFAULT NULL COMMENT '注册信息',
  `openid_wx` varchar(128) DEFAULT NULL COMMENT '微信openid',
  `openid_qq` varchar(128) DEFAULT NULL COMMENT 'QQ互联openid',
  `ban` int(10) DEFAULT '0' COMMENT '禁用到期时间戳',
  `ban_notice` varchar(255) DEFAULT NULL COMMENT '禁用通知',
  `appid` int(10) NOT NULL COMMENT '应用ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
,
"CREATE TABLE IF NOT EXISTS `{$db_pre}log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `group` enum('adm','agent','user') DEFAULT 'user' COMMENT '用户组',
  `type` varchar(255) DEFAULT 'no' COMMENT '日志类型',
  `uid` int(10) DEFAULT '0' COMMENT '用户ID，管理员=0',
  `vip` int(10) DEFAULT '0' COMMENT 'VIP变化',
  `fen` int(10) DEFAULT '0' COMMENT '积分变化',
  `ip` varchar(15) DEFAULT '127.0.0.1' COMMENT '操作IP',
  `time` int(10) NOT NULL COMMENT '操作时间',
  `appid` int(10) DEFAULT '0' COMMENT '应用ID',
  `data` text COMMENT '数据',
  `status` int(10) DEFAULT '200' COMMENT '状态码',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
,
"CREATE TABLE IF NOT EXISTS `{$db_pre}user_logon` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL COMMENT '用户ID',
  `token` varchar(32) NOT NULL COMMENT '用户TOKEN',
  `log_time` int(10) NOT NULL COMMENT '登录时间',
  `log_ip` varchar(15) DEFAULT '127.0.0.1' COMMENT '登录IP',
  `log_in` varchar(255) DEFAULT NULL COMMENT '登录信息',
  `last_t` int(10) NOT NULL COMMENT '最后活动时间',
  `appid` int(10) NOT NULL COMMENT 'appid',
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;"
]
?>