<!DOCTYPE html>
<html>
<head>
	{include file="../common/header.html"}
	<title>易迅微站</title>
	<link rel="stylesheet" type="text/css" href="http://st.icson.com/weizhan/mobile/css/wz_base.css{include file="../common/version.json"}">
	<link rel="stylesheet" type="text/css" href="http://st.icson.com/weizhan/mobile/css/article_detail.css{include file="../common/version.json"}">
</head>
<body>
	<header>
		<div class="relatv nav nav_wx">
			<p class="nav_lcr font_12">
                <a class="fl sl" href="{$frontdomain}/index/{$master_results.id}" target="_self"><img src={$master_results.logo} alt="" /> {$master_results.name}</a>
			</p>
		</div>
	</header>
	<header>
		<div class="relatv nav nav_common" >
			<p class="nav_lcr font_12">
                <a class="fl sl" href="{$frontdomain}/index/{$master_results.id}" target="_self"><img src={$master_results.logo} alt="" /> {$master_results.name}</a>
				<a class="fr" href="#" onclick="yx.wz.detail.share();"><img src="http://st.icson.com/weizhan/mobile/img/iconfont-share.png" alt="" /></a>
			</p>
		</div>
	</header>
	<div class="goods_detail" data-articleid="{$article_results.secretid}">
		<h3 class="goods_name font_20 head_common">{$article_results.title}</h3>
		<p class="goods_name wx_head_small" >(原标题：{$article_results.zdmTitle})</p>
		<div class="g_d_info font_10">
			<span class="pub_time">{$article_results.hitShelveTime}{if $article_results.originalAuthor eq 0}			
			{else}
			转载自
			{/if}</span><span class="pub_user">
			{$article_results.author}</span>
			<span class="fr read_num"></span>
		</div>
		<article class="goods_text font_14">
			<span class="fl gd_sub gd_sub_s{{$article_results.categories}} font_10">{$article_results.categoryTest}</span>
			{$article_results.content|unescape:"html"}
			</article>
	</div>

	<div class="social_share">
		<span class="s_s_zan">赞（<font class="font_red"></font>）</span>
		<span class="s_s_share"><img src="http://st.icson.com/weizhan/mobile/img/iconfont-share.png" alt=""/>分享</span>
	</div><div class="border_line"></div>
	<div class="page_link">
		{if !empty($article_results.prevArticle)}
        <a class="prve" href="{$frontdomain}/article/detail/{$article_results.prevArticle.id}/{$site_id}/{$master_results.id}"><font>上一篇：</font>{$article_results.prevArticle.title}</a>
		{/if}
		{if !empty($article_results.nextArticle)}
        <a class="next" href="{$frontdomain}/article/detail/{$article_results.nextArticle.id}/{$site_id}/{$master_results.id}"><font>下一篇：</font>{$article_results.nextArticle.title}</a>
		{/if}
	</div>
	<ul class="all_discuss">	
		<li class="a_d_title"></li>
		<a class="extend_comment" target="_blank" onclick= "yx.wz.detail.comment.getComment()" style="display:none">展开评论</a>
		
	</ul>
	<div class="wait_loading" style="display:none"><span>努力加载中..</span></div>
	<div class="cover">
    <ul>
      <li class="common_share"><a href="#" onclick = "yx.wz.share.init('weibo',share_info)"><p><img src="http://st.icson.com/weizhan/mobile/img/sina.png" width="48" height="48" /></p><p>新浪微博</p></a></li>
      <li class="common_share"><a href="#" onclick = "yx.wz.share.init('qzone',share_info)"><p><img src="http://st.icson.com/weizhan/mobile/img/qq.png" width="48" height="48" /></p><p>QQ空间</p></a></li>
      <li class="common_share"><a href="#" onclick = "yx.wz.share.init('qq_t',share_info)"><p><img src="http://st.icson.com/weizhan/mobile/img/weibo.png" width="48" height="48" /></p><p>腾讯微博</p></a></li>
	  <li class="wx_share"><a href="#" onclick = "yx.wz.share.init('weixin',share_info)"><p><img src="http://st.icson.com/weizhan/mobile/img/weixin.png" width="48" height="48" /></p><p>微信</p></a></li>	 
    </ul>
    <div style="clear:both"></div>
    <div class="cancelShare"><a>取消</a></div>
</div>
	<div class="share_cover"></div>
	
	<input type="hidden" id="wz_page_info" data-articleid="{$article_results.id}" data-masterid="{$master_results.id}"	data-zdmtitle="{$article_results.zdmTitle}" data-siteid="{$site_id}"/>
	{include file="../common/footer.html"}
	<script type="text/javascript" src="http://st.yixun.com/touch_v1/js/mustache.js"></script>	
	<script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>
	<script type="text/javascript" src="http://st.icson.com/weizhan/mobile/js/article_detail.js{include file="../common/version.json"}"></script>
	<script type="text/javascript">//数据存入json
		$(document).ready( function(){
			window.share_info = {
				share_pic : "{$article_results.microUrl}" ,
				share_url : window.location.href ,
				share_title : yx.wz.base.isWeixin() ? "{$article_results.title}-{$master_results.name}" : "{$article_results.zdmTitle}-{$master_results.name}" ,
				share_site: "www.yixun.com",
				share_desc: ""
			}
			share_info.share_desc = yx.wz.detail.init();
			if (yx.wz.base.isWeixin()){
					setTimeout( function(){
					yx.wz.share.init('weixin',share_info);},2000);
			}
			
			yx.wz.base.tjopt = { "pageid" :"1201",
				ext:{
					"masterid": yx.wz.base.getWzMasterId(),
					"articleid" : yx.wz.base.getWzArticleId(),
					"siteid" : yx.wz.base.getWzSiteId(),
					"opsystem":  yx.wz.base.versions.mobileSystem(),
					"browser": yx.wz.base.versions.browser()
				}
			};		
			shangbao.init(yx.wz.base.tjopt);
			yx.wz.base.tag();
		});
	</script>

</body>
</html>