<!DOCTYPE html>
<html>
<head>
	{include file="../common/header.html"}
	<title>易迅微站</title>
	<link rel="stylesheet" type="text/css" href="http://st.icson.com/weizhan/mobile/css/wz_base.css{include file="../common/version.json"}">
	<link rel="stylesheet" type="text/css" href="http://st.icson.com/weizhan/mobile/css/masterinfor.css{include file="../common/version.json"}">
</head>
<body>
	<header class="about">
		<div class="relatv nav">
			<h2 class="abs sc_mw font_16">关于我</h2>
			
		</div>
		<div class="relatv user_info">
			
			<div class="abs u_info">

                <img class="fl" src={{$master_results.logo}} alt="" />
				<span class="u_name">{{$master_results.name}}</span>
				<span class="u_intro"></span>
			</div>
		</div>
	</header>
	<h2 class="goods_list_title">基本资料</h2>
	<ul class="basic_ps_info">
		<li><label>QQ：</label>{{$master_results.qq}}</li>
		<li><label>微博：</label>{{$master_results.weibo}}</li>
		<li><label>简介：</label><span class="intro_content">{{$master_results.introduction}}<span></li>
		<li><label>微站：</label><div id="J-qrcode" class="intro_content">
		</div></li>
		
	</ul>
	<input type="hidden" id="wz_page_info" data-masterid="{$master_results.id}"	data-siteid="{$site_results.id}"/>
	{include file="../common/footer.html"}
	<script> window.jQuery = Zepto;</script>
	<script type="text/javascript" src="http://st.icson.com/vendor/qrcode/jquery.qrcode.min.js"></script>
	<script>
		$("#J-qrcode").qrcode({
			"width": 140,
			"height": 140,
			"text":"{{$frontdomain}}/index/{{$master_results.id}}"
		});
		 yx.wz.base.tjopt = { "pageid" :"1101",
			ext:{
				"masterid": yx.wz.base.getWzMasterId(),
				"siteid" : yx.wz.base.getWzSiteId(),
				"opsystem":  yx.wz.base.versions.mobileSystem(),
				"browser": yx.wz.base.versions.browser()
			}
		};		
		shangbao.init(yx.wz.base.tjopt);
	</script>
</body>
</html>