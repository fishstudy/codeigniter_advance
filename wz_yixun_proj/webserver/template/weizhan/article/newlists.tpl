<!DOCTYPE html>
<html>
<head>
{include file="../common/header.html"}
<title>易迅微站</title>
<link rel="stylesheet" type="text/css" href="http://st.icson.com/weizhan/mobile/css/wz_global.css{include file="../common/version.json"}">
</head>
<body>

<div class="text_con">
            <ul class="article_catalog">
            	{foreach $kindId_results as $key=>$value}
                <li {if $key eq $currentKindId.id} class="cur" {/if}><a href="{$frontdomain}/article/newlists/{$site_results.id}/{$master_results.id}/{$key}" >{$value}</a></li>
                {/foreach}
            </ul>
        </div>
<div class="article">
	{section name=k loop=$article_results}
    <div class="inner">
        <a href="{$frontdomain}/article/detail/{$article_results[k].id}/{$site_results.id}/{$master_results.id}/{$currentKindId.id}" target="_blank"><p class = "a_title">{$article_results[k].title}</p>
			<img class="pic" src="{$article_results[k].microUrl}">
			<p class="desc">
			{$article_results[k].brief}
			</p>
			<p class="fuc">
				<a href="javascript:;" class="agree"><i class="i_agree"></i><span>{$article_results[k].praiseNum}</span></a>
				<a href="javascript:;" class="comment"><i class="i_comment"></i><span>{$article_results[k].readNum}</span></a>
			</p>    
		</a>
    </div>
    {/section}

	{if $article_results|@count eq 0}
	<div class="no_art more_goods">还没有相关文章哟</div>
	{/if}
	
</div>
<div class="wait_loading" style="display:none"><span>努力加载中..</span></div>
<script type="text/template">
[#article_results]
	<div class="inner">
        <a href="{$frontdomain}/article/detail/[id]/{$site_results.id}/{$master_results.id}/{$currentKindId.id}"><p class = "a_title">[title]</p><img class="pic" src="[microUrl]">
			<p class="desc">
			[brief]
			</p>
			<p class="fuc">
				<a href="javascript:;" class="agree"><i class="i_agree"></i><span>[praiseNum]</span></a>
				<a href="javascript:;" class="comment"><i class="i_comment"></i><span>[readNum]</span></a>
			</p>  
		</a>		
    </div>
[/article_results]
</script>
<input type="hidden" id="category" value="{$currentKindId.id}" />
<input type="hidden" id="wz_page_info" data-masterid="{$master_results.id}"	data-siteid="{$site_results.id}"/>
{include file="../common/footer.html"}

<script type="text/javascript" src="http://st.icson.com/weizhan/mobile/js/article_list.js{include file="../common/version.json"}"></script>
<script type="text/javascript" src="http://st.yixun.com/touch_v1/js/mustache.js"></script>
<script type="text/javascript" >
Mustache.tags = ["[","]"];
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
<html>