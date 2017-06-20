<!doctype html>
<html class="no-js">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>微站后台管理系统-首页</title>
  <meta name="keywords" content="index">
  <meta name="description" content="这是一个登陆页面">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <meta name="apple-mobile-web-app-title" content="Amaze UI" />
  <link rel="icon" type="image/png" href="<#$admindomain#>static/images/favicon.png">
  <link rel="apple-touch-icon-precomposed" href="<#$admindomain#>static/images/app-icon72x72@2x.png">
  <link rel="stylesheet" href="<#$admindomain#>static/css/amazeui.min.css"/>
  <link rel="stylesheet" href="<#$admindomain#>static/css/admin.css">
  
  <!--[if lt IE 9]>
	<script src="<#$admindomain#>static/js/jquery1.11.1.min.js"></script>
	<script src="<#$admindomain#>static/js/modernizr.js"></script>
	<script src="<#$admindomain#>static/js/polyfill/rem.min.js"></script>
	<script src="<#$admindomain#>static/js/polyfill/respond.min.js"></script>
	<script src="<#$admindomain#>static/js/amazeui.legacy.js"></script>
	<![endif]-->
	
	<!--[if (gte IE 9)|!(IE)]><!-->
	<script src="<#$admindomain#>static/js/jquery.min.js"></script>
	<script src="<#$admindomain#>static/js/amazeui.min.js"></script>
	<!--<![endif]-->

<style>
    .header {
      text-align: center;
    }
    .header h1 {
      font-size: 200%;
      color: #333;
      margin-top: 30px;
    }
    .header p {
      font-size: 14px;
    }
  </style>
  
</head>
<body>
<!--[if lte IE 9]>
<p class="browsehappy">你正在使用<strong>过时</strong>的浏览器，Amaze UI 暂不支持。 请 <a href="http://www.baidu.com/" target="_blank">升级浏览器</a>
  以获得更好的体验！</p>
<![endif]-->

<div class="header"> 
  <hr />
</div>
<div class="am-g" style="margin-bottom:50px;">
  <div class="am-u-lg-6 am-u-md-8 am-u-sm-centered">
    <h2>微站后台管理-登录</h2>
    <hr>
    <br>

    <form method="post" action="" name="adminlogin" class="am-form" >
      <label for="email">用户名:</label>
      <input type="text" name="c[username]" id="username" value="" maxlength="20" style="width:300px;" />
      <br>
      <label for="password">密码:</label>
      <input type="password" name="c[password]" id="password" value="" maxlength="20" style="width:300px;" />
      <br>
      <!--
      <label for="remember-me">
        <input id="remember-me" type="checkbox" />
        记住密码
      </label>
      -->
      <br />
      <div class="am-cf">
        <input type="button" name="" value="登 录" class="am-btn am-btn-primary am-btn-sm am-fl" onclick="checklogin();">
        <input type="reset" name="" value="重 置" class="am-btn am-btn-primary am-btn-sm am-fl" style="margin-left:50px;">
        <!--
        <input type="submit" name="" value="忘记密码 ^_^? " class="am-btn am-btn-default am-btn-sm am-fr">
        -->
      </div>
    </form>
  </div>
</div>

<script type="text/javascript">
	//通过
	function checklogin(){
		var form = document.adminlogin;
		var url = '<#$admindomain#>admin/master/savelogin';
		var username = document.getElementById('username').value;
		var pwd = document.getElementById('password').value;
		if( !username || username.length>20 ) {
			alert("用户名不能为空或者超过20个字符！");
			return false;
		}
		if( !pwd || pwd.length>20 ) {
			alert("密码不能为空或者超过20个字符！");
			return false;
		}
		form.action = url;
		form.submit();
	}
</script>

<#include file="../common/footer.tpl" #>
