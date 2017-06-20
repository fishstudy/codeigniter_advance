<!doctype html>
<html class="no-js">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>后台管理系统-首页</title>
  <meta name="keywords" content="index">
  <meta name="description" content="后台管理系统-首页">
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

</head>
<body>
<!--[if lte IE 9]>
<p class="browsehappy">你正在使用<strong>过时</strong>的浏览器，Amaze UI 暂不支持。 请 <a href="http://www.baidu.com/" target="_blank">升级浏览器</a>
  以获得更好的体验！</p>
<![endif]-->

<header class="am-topbar admin-header">
  <div class="am-topbar-brand">
    <strong>微 站</strong> <small>后 台 管 理 系 统</small>
  </div>

  <button class="am-topbar-btn am-topbar-toggle am-btn am-btn-sm am-btn-success am-show-sm-only" data-am-collapse="{target: '#topbar-collapse'}"><span class="am-sr-only">导航切换</span> <span class="am-icon-bars"></span></button>

  <div class="am-collapse am-topbar-collapse" id="topbar-collapse">

    <ul class="am-nav am-nav-pills am-topbar-nav am-topbar-right admin-header-list">
    <!--
      <li><a href="javascript:;"><span class="am-icon-envelope-o"></span> 收件箱 <span class="am-badge am-badge-warning">5</span></a></li>
      -->
      <li class="am-dropdown" data-am-dropdown>
        <a class="am-dropdown-toggle" data-am-dropdown-toggle href="javascript:;">
          <span class="am-icon-users"></span> 管理员 <span class="am-icon-caret-down"></span>
        </a>
        <ul class="am-dropdown-content">
        <!--
          <li><a href="#"><span class="am-icon-user"></span> 资料</a></li>
          <li><a href="#"><span class="am-icon-cog"></span> 设置</a></li>
          -->
          <li><a href="<#$admindomain#>admin/master/logout"><span class="am-icon-power-off"></span> 退出</a></li>
        </ul>
      </li>
      <li><a href="javascript:;" id="admin-fullscreen"><span class="am-icon-arrows-alt"></span> <span class="admin-fullText">开启全屏</span></a></li>
    </ul>
  </div>
</header>
