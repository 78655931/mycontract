<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo (C("sitename")); ?></title>
		<link href="__PUBLIC__/dwz/themes/css/login.css" rel="stylesheet" type="text/css" />
		<script src="__PUBLIC__/dwz/js/jquery-1.4.4.min.js" type="text/javascript"></script>
		<script language="JavaScript">
			<!--
			function fleshVerify(type){ 
				//重载验证码
				var timenow = new Date().getTime();
				if (type){
					$('#verifyImg').attr("src", '__URL__/verify/adv/1/'+timenow);
					}else{
					$('#verifyImg').attr("src", '__URL__/verify/'+timenow);
				}
			}
			//-->
		</script>
	</head>
	<body>

		<div id="login">
			<div id="login_content">
				<div class="loginForm">
					<form method="post" action="__URL__/checkLogin/">
						<p>
						<label>帐号：</label>
						<input type="text" name="account" size="20" class="login_input" />
						</p>
						<p>
						<label>密码：</label>
						<input type="password" name="password" size="20" class="login_input" />
						</p>
						<p>
						<label>门店：</label>
						<select  name="location_code">
							<?php if(is_array($list['brand'])): $i = 0; $__LIST__ = $list['brand'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$brand): ++$i;$mod = ($i % 2 )?><?php if(is_array($brand)): $i = 0; $__LIST__ = $brand;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><option value="<?php echo ($key); ?>"><?php echo ($vo); ?></option><?php endforeach; endif; else: echo "" ;endif; ?><?php endforeach; endif; else: echo "" ;endif; ?>
						</select>
						</p>
						<p>
						<label>验证码：</label>
						<input class="code" name="verify" type="text" size="5" />
						<span><img id="verifyImg" SRC="__URL__/verify/" onClick="fleshVerify()" border="0" alt="点击刷新验证码" style="cursor:pointer" align="absmiddle"></span>
						</p>
						<div class="login_bar">
							<input class="sub" type="submit" value=" " />
						</div>				</div>
			</div>
		</div>
		<!--div id="login">
			<div id="login_header">
				<h1 class="login_logo">
					<a href="__APP__"></a>
				</h1>
				<div class="login_headerContent">
					<div class="navList">
					</div>
					<h2 class="login_title">合同管理平台</h2>
				</div>
			</div>
			<div id="login_content">
				<div class="loginForm">
					<form method="post" action="__URL__/checkLogin/">
						<p>
						<label>帐号：</label>
						<input type="text" name="account" size="20" class="login_input" />
						</p>
						<p>
						<label>密码：</label>
						<input type="password" name="password" size="20" class="login_input" />
						</p>
						<p>
						<label>门店：</label>
						<select  name="location_code">
							<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><option value="<?php echo ($key); ?>"><?php echo ($vo); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
						</select>
						</p>
						<p>
						<label>验证码：</label>
						<input class="code" name="verify" type="text" size="5" />
						<span><img id="verifyImg" SRC="__URL__/verify/" onClick="fleshVerify()" border="0" alt="点击刷新验证码" style="cursor:pointer" align="absmiddle"></span>
						</p>
						<div class="login_bar">
							<input class="sub" type="submit" value=" " />
						</div>
					</form>
				</div>
				
			</div-->
		
	</body>
</html>