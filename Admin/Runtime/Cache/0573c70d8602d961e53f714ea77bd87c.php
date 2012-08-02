<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
<METAHTTP-EQUIV="Pragma"CONTENT="no-cache">
<METAHTTP-EQUIV="Cache-Control"CONTENT="no-cache">
<METAHTTP-EQUIV="Expires"CONTENT="0">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<title><?php echo (C("sitename")); ?></title>

<link href="__PUBLIC__/dwz/themes/default/style.css" rel="stylesheet" type="text/css" />
<link href="__PUBLIC__/dwz/themes/css/core.css" rel="stylesheet" type="text/css" />
<!--[if IE]>
<link href="__PUBLIC__/dwz/themes/css/ieHack.css" rel="stylesheet" type="text/css" />
<![endif]-->

<script src="__PUBLIC__/dwz/js/speedup.js" type="text/javascript"></script>

<script src="__PUBLIC__/dwz/js/jquery-1.4.4.min.js" type="text/javascript"></script>

<script src="__PUBLIC__/dwz/js/jquery.cookie.js" type="text/javascript"></script>
<script src="__PUBLIC__/dwz/js/jquery.validate.js" type="text/javascript"></script>
<script src="__PUBLIC__/dwz/js/jquery.bgiframe.js" type="text/javascript"></script>
<script src="__PUBLIC__/xheditor/xheditor-1.1.9-zh-cn.min.js" type="text/javascript"></script>

<script src="__PUBLIC__/dwz/js/dwz.min.js" type="text/javascript"></script>
<script src="__PUBLIC__/dwz/js/dwz.regional.zh.js" type="text/javascript"></script>

<link rel="stylesheet" href="__PUBLIC__/css/booking.css" type="text/css" media="screen" />
<link rel="stylesheet" href="__PUBLIC__/css/jquery.datepicker.addon.css" type="text/css" charset="utf-8"/>
<link rel="stylesheet" href="__PUBLIC__/css/jquery.ui.css" type="text/css" media="all" /> 
<script type="text/javascript" src="__PUBLIC__/js/jquery.form.js"></script>
<script type="text/javascript" charset="utf-8" src="__PUBLIC__/js/card.js"></script>

<link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.10/themes/base/jquery-ui.css" type="text/css" media="all" />
<script src="__PUBLIC__/js/jquery.ui.js" type="text/javascript"></script>    
<script src="__PUBLIC__/js/jquery.ui.i18n.js" type="text/javascript"></script>   
<script src="__PUBLIC__/js/jquery.datepicker.addon.js" type="text/javascript"></script>  
<script type="text/javascript">
function fleshVerify(){
	//重载验证码
	$('#verifyImg').attr("src", '__APP__/Public/verify/'+new Date().getTime());
}
function dialogAjaxMenu(json){
	dialogAjaxDone(json);
	if (json.statusCode == DWZ.statusCode.ok){
		$("#sidebar").loadUrl("__APP__/Public/menu");
	}
}
function navTabAjaxMenu(json){
	navTabAjaxDone(json);
	if (json.statusCode == DWZ.statusCode.ok){
		$("#sidebar").loadUrl("__APP__/Public/menu");
	}
}
$(function(){
	DWZ.init("__PUBLIC__/dwz/dwz.frag.xml", {
		loginUrl:"__APP__/Public/login_dialog", loginTitle:"登录",	// 弹出登录对话框
//		loginUrl:"__APP__/Public/login",	//跳到登录页面
		statusCode:{ok:1,error:0},
		pageInfo:{pageNum:"pageNum", numPerPage:"numPerPage", orderField:"_order", orderDirection:"_sort"}, //【可选】
		debug:false,	// 调试模式 【true|false】
		callback:function(){
			initEnv();
			$("#themeList").theme({themeBase:"__PUBLIC__/dwz/themes"});
		}
	});
});
//清理浏览器内存,只对IE起效，FF不需要
if ($.browser.msie) {
	window.setInterval("CollectGarbage();", 10000);
}

</script>
</head>


<body scroll="no">
	<div id="layout">
		<div id="header">
			<div class="headerNav">
				<a class="logo" href="__APP__">Logo</a>
				<ul class="nav">
					<li ><span>你好，</span><font color="red"><?php echo ($_SESSION['loginUserName']); ?></font></li><li><a href="__APP__/Public/logout/">退出</a></li>
				</ul>
				
			</div>
		<!--div id="navMenu">
				<ul>
					<li class="selected"><a href="sidebar_1.html"><span>资讯管理</span></a></li>
					<li><a href="sidebar_2.html"><span>订单管理</span></a></li>
					<li><a href="sidebar_1.html"><span>产品管理</span></a></li>
					<li><a href="sidebar_2.html"><span>会员管理</span></a></li>
					<li><a href="sidebar_1.html"><span>服务管理</span></a></li>
					<li><a href="sidebar_2.html"><span>系统设置</span></a></li>
				</ul>
			</div-->
		</div>
		
		<div id="leftside">
			<div id="sidebar_s">
				<div class="collapse">
					<div class="toggleCollapse"><div></div></div>
				</div>
			</div>
			
			<div id="sidebar" >
				<div class="accordion" fillSpace="sideBar">

	<div class="toggleCollapse">
		<h2><span >主菜单</span></h2>
		<div></div>
	</div>
	<!--<div class="accordionHeader">-->
		<!--<h2><span>Folder</span>应用</h2>-->
	<!--</div>-->
	<div class="accordionContent">
	
		<!--ul class="tree treeFolder">
			<?php if(is_array($menu)): $i = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$item): ++$i;$mod = ($i % 2 )?><?php if((strtolower($item['name']))  !=  "public"): ?><?php if((strtolower($item['name']))  !=  "index"): ?><?php if(($item['access'])  ==  "1"): ?><li><a href="__APP__/<?php echo ($item['name']); ?>/index/" target="navTab" rel="<?php echo ($item['name']); ?>"><?php echo ($item['title']); ?></a></li><?php endif; ?><?php endif; ?><?php endif; ?><?php endforeach; endif; else: echo "" ;endif; ?>
		</ul-->
		<ul class="tree treeFolder">
			
		<li><a href="__APP__/Reservation/index/" target="navTab" fresh="true" rel="订单列表">订单列表</a></li>
		<li><a href="__APP__/Agreement/index/" target="navTab" fresh="true" rel="合同列表">合同列表</a></li>
		<!--<li><a href="__APP__/Driver/index/" target="navTab"  rel="司机管理">司机管理</a></li>-->
	</ul>
	</div>
</div>

			</div>
		</div>

		<div id="container">

			<div id="navTab" class="tabsPage">
				<div class="tabsPageHeader">
					<div class="tabsPageHeaderContent"><!-- 显示左右控制时添加 class="tabsPageHeaderMargin" -->
						<ul class="navTab-tab">
							<li tabid="main" class="main"><a href="javascript:void(0)"><span><span class="home_icon">我的主页</span></span></a></li>
						</ul>
					</div>
					<div class="tabsLeft">left</div><!-- 禁用只需要添加一个样式 class="tabsLeft tabsLeftDisabled" -->
					<div class="tabsRight">right</div><!-- 禁用只需要添加一个样式 class="tabsRight tabsRightDisabled" -->
					<div class="tabsMore">more</div>
				</div>
				<ul class="tabsMoreList">
					<li><a href="javascript:void(0)">我的主页</a></li>
				</ul>
				<div class="navTab-panel tabsPageContent">
					<div>
<table layoutH="150" class="table" width="1100">
	<thead>
		<tr>
			<th width="12%">预订单编号</th>
			<th width="6%">客户姓名</th>
			<th width="12%">身份证号</th>
			<th width="8%">客户电话</th>
			<th width="10%">取车时间</th>
			<th width="10%">还车时间</th>
			<th width="25%">车型</th>
			<th width="5%">状态</th>
			<th width="8%"></th>
		</tr>
	</thead>

	<tbody>
		<?php if(is_array($lists)): $i = 0; $__LIST__ = $lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><tr >
			<td ><?php echo ($vo['confirmation']); ?></td>
			<td ><?php echo ($vo['real_name']); ?></td>
			<td ><?php echo ($vo['identity_code']); ?></td>
			<td ><?php echo ($vo['home_phone']); ?></td>
			<td ><?php echo ($vo['pickup_date']); ?></td>
			<td ><?php echo ($vo['return_date']); ?></td>
			<td ><?php echo ($vo['CAR_MODEL_NAME']); ?></td>
			<?php echo (convert_stat($vo['status'])); ?>

			<td > <ul class="toolBar"><li><a width="15%" href="__APP__/Reservation/edit/id/<?php echo ($vo['reservation_id']); ?>/confirmation/<?php echo ($vo['confirmation']); ?>/navTabId/__MODULE__" target="dialog" max="true" maxable="false" minable="false" resizable="false"  rel="reser2" fresh="true"><span>预订单生成合同</span></a></li></ul>

			</td>
		</tr><?php endforeach; endif; else: echo "" ;endif; ?>

		</tbody>
</table>
								

<div class="panelBar">
		<div class="pages">
			<span>共<?php echo ($totalCount); ?>条</span>
		</div>
		<div class="pagination" targetType="navTab" totalCount="<?php echo ($totalCount); ?>" numPerPage="<?php echo ($numPerPage); ?>" pageNumShown="10" currentPage="<?php echo ($currentPage); ?>"></div>
	</div>


					</div>
				</div>
			</div>
		</div>

	</div>
	


</body>
</html>