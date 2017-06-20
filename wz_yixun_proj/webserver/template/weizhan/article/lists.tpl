<!DOCTYPE html>
<html>
<head>
	{include file="../common/header.html"}
    <title>易迅微站</title>
    <link rel="stylesheet" type="text/css" href="http://st.icson.com/weizhan/mobile/css/wz_base.css{include file="../common/version.json"}">
    <link rel="stylesheet" type="text/css" href="http://st.icson.com/weizhan/mobile/css/article_list.css{include file="../common/version.json"}">
	<script type="text/javascript" src="http://st.yixun.com/touch_v1/js/mustache.js"></script>
</head>
<body>
<header>
    <div class="relatv nav">
        <h2 class="abs sc_mw font_16">经验杂谈</h2>
        <p class="nav_lcr">
            <a class="fl" href="{$frontdomain}/index/{$master_results.id}"><img src={$master_results.logo} alt="" /></a>
            <span class="fr nav_more"><i id="icon_nav" onclick="$('.yx_nav').toggle()">导航</i></span>
        </p>		
    </div>
	<nav class="yx_nav">
				{foreach key=key item=item from=$category_results}
                <a href="{$frontdomain}/article/lists/{$site_results.id}/{$master_results.id}/{$key}" category-type="{$key}">{$item}</a>
				{/foreach}
		</nav>
</header>
<ul class="goods_list g_list_5">
    {section name=k loop=$article_results}
    <li class="goods_li g_list_sp">
		<span class="pub_time font_10">{$article_results[k].hitShelveTime}</span>
        <span class="gd_sub gd_sub_s{$article_results[k].categories} font_10">{$article_results[k].categoryText}</span>
        <a class="goods_a clear" href="{$frontdomain}/article/detail/{$article_results[k].id}/{$site_results.id}/{$master_results.id}" target="_blank">
            <h3 class="goods_name font_16">{$article_results[k].title}</h3>
            <img class="goods_img_80 fl" src="{$article_results[k].microUrl}" alt="" />
            <article class="goods_text">{$article_results[k].brief}...</article>
        </a>
        
        <div class="goods_discus" style="display:none">
            <a href="javascript:;" ><i></i></a>
        </div>
		
    </li>
	<div class="border_line"></div>
    {/section}
	{if $article_results|@count eq 0}
	<div class="no_art more_goods">还没有相关文章哟</div>
	{/if}
</ul>
<div class="wait_loading" style="display:none"><span>努力加载中..</span></div>
<input type="hidden" id="category" value="{$currentCategory.id}" />
<input type="hidden" id="wz_page_info" data-masterid="{$master_results.id}"	data-siteid="{$site_results.id}"/>

{include file="../common/footer.html"}

<script type="text/javascript" src="http://st.icson.com/weizhan/mobile/js/article_list.js{include file="../common/version.json"}"></script>
<script type="text/javascript" >
 yx.wz.list.init();
 yx.wz.base.tjopt = { "pageid" :"1101",
			ext:{
				"masterid": yx.wz.base.getWzMasterId(),
				"siteid" : yx.wz.base.getWzSiteId(),
				"opsystem":  yx.wz.base.versions.mobileSystem(),
				"browser": yx.wz.base.versions.browser()
			}
		};		
		yx.wz.base.tag();
shangbao.init(yx.wz.base.tjopt);
</script>
</body>
</html>