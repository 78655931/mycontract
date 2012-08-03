<?php
$siteconfig	=	require './siteconfig.inc.php';
$config	= array(
    'URL_MODEL'=>1, // 如果你的环境不支持PATHINFO 请设置为3
	'DB_TYPE'=>'mysql',
	'DB_HOST'=>'117.34.70.20',
	//'DB_HOST'=>'localhost',

	'DB_NAME'=>'test',
	'DB_USER'=>'root',
	'DB_PWD'=>'123.com',
	'DB_PORT'=>'3306',
	'DB_PREFIX'=>'',

	'APP_DEBUG'=>true,	//调试模式开�?

	'VAR_PAGE'=>'pageNum',
	'DB_CRS'=>array(
		'dbms' => 'mysql',
		'username' => 'root',
		'password' => '123.com',
	//	'hostname' => 'localhost',

		'hostname' => '117.34.70.20',
		'hostport' => '3306',
		'database' => 'ry_crsengine_db',
    ),
    'TMPL_ACTION_SUCCESS' =>'Public:success' ,
	'HTML_CACHE_ON'	=> false,
	'TOKEN_ON'=>false,
	'USER_AUTH_ON'=>true,
	'USER_AUTH_TYPE'=>2,		// 默认认证类型 1 登录认证 2 实时认证
	//'USER_AUTH_KEY'=>'authId',	// 用户认证SESSION标记

	'USER_AUTH_KEY'=>'CONTRACT_AUTH',	// 用户认证SESSION标记
    'ADMIN_AUTH_KEY'=>'administrator',
	'USER_AUTH_MODEL'=>'User',	// 默认验证数据表模�?
	'AUTH_PWD_ENCODER'=>'md5',	// 用户认证密码加密方式
	'USER_AUTH_GATEWAY'=>'/Public/login',	// 默认认证网关
	'NOT_AUTH_MODULE'=>'Public,Index',		// 默认无需认证模块
	'REQUIRE_AUTH_MODULE'=>'',		// 默认需要认证模�?
	'NOT_AUTH_ACTION'=>'',		// 默认无需认证操作
	'REQUIRE_AUTH_ACTION'=>'add,edit,index,foreverdelete',		// 默认需要认证操�?
    'GUEST_AUTH_ON'=>false,    // 是否开启游客授权访�?
    'GUEST_AUTH_ID'=>0,     // 游客的用户ID
	'BRANDCODE'=>'GONGSI',
    'DB_LIKE_FIELDS'=>'title|remark',
	'RBAC_ROLE_TABLE'=>'role',
	'RBAC_USER_TABLE'=>'role_user',
	'RBAC_ACCESS_TABLE'=>'access',
	'RBAC_NODE_TABLE'=>'node',
	'BRANDCODE'=>'GONGSI',
	'CAR'=>'http://172.16.100.47/findCar?',
	'DISCOUNT'=>'http://172.16.100.47:8099/crsCarRental/findDiscount?',
	'CARTYPE'=>'http://172.16.100.52:8099/crsCarRental/SelectCarType?paraRequest.sourceCode=W&paraRequest.vendorCode=W-GONGSI&paraRequest.vendorPass=123456&paraRequest.companyCode=GONGSI&paraRequest.brandCode=GONGSI&paraRequest.carTypeCode=',
    'MEMURL'=>'http://172.16.100.121:8080/crsCarRental/regUser?',
    'DEFAULT_AJAX_RETURN'=>'JSON',
);

return array_merge($config,$siteconfig);
?>
