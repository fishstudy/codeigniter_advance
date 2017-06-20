<!DOCTYPE html>
<html>
<head>
	{include file="../common/header.html"}
	<title>易迅微站</title>
	<link rel="stylesheet" type="text/css" href="http://st.icson.com/weizhan/mobile/css/wz_base.css{include file="../common/version.json"}">
	<link rel="stylesheet" type="text/css" href="http://st.icson.com/weizhan/mobile/css/index.css{include file="../common/version.json"}">
	
</head>
<body>
	<header>
		<div class="nav nav_home nav_wx abs">
			<p class="nav_lcr">
				<a class="fl" href="http://m.zdm.yixun.com"><img src="http://st.icson.com/weizhan/mobile/img/weizhan.png" alt="" /></a>
			</p>
		</div>
		<div class="relatv user_info">
			<div class="u_bg">			
				<img src="{$site_results.bgPicture}" alt="" />
			</div>
			<div class="abs u_info">
                <a href="{$frontdomain}/masterinfo/masterinfor/{$master_results.id}">
				<img class="fl" src="{$master_results.logo}" alt="" />
				<span class="u_name font_16">{$master_results.name}</span>
				<span class="u_intro font_12">{$master_results.introduction}</span></a>
			</div>
		</div>
	</header>
	<!-- 评论分类 S -->

	<!-- 评论分类 E -->	
	<!-- 本期福利 S -->
	
	<!-- 本期福利 E -->
	<!-- 经验杂谈 S -->
	<div class="experic_talk">		
		<h2 class="goods_list_title font_14">我的文章</h2>
		 {section name=k loop=$article_results max=5}
		<div class="goods_list g_list_sp">
			
			<span class="gd_sub gd_sub_s{$article_results[k].categories}">{$article_results[k].categoryText}</span>
            <a class="goods_a clear" href="{$frontdomain}/article/detail/{$article_results[k].id}/{$site_results.id}/{$master_results.id}" target="_self">
				<h3 class="goods_name font_16">{$article_results[k].title}</h3>
				<img class="goods_img_80 fl" src="{$article_results[k].microUrl}" alt="" />
				<article class="goods_text">{$article_results[k].brief}...</article>
			</a>
			<span class="pub_time font_10">{$article_results[k].hitShelveTime}</span>			
			<div class="goods_discus" data-articleId="{$article_results[k].articleId}">			
				<a href="javascript:;"><i></i></a>
			</div>
		</div>
		<div class="border_line"></div>
		{/section}
		{if $article_results|@count eq 0}
		<div class="no_art more_goods">去添加一些文章吧</div>
		{else}
        <a class="more_goods" href=" {$frontdomain}/article/lists/{$site_results.id}/{$master_results.id}" target="_blank"><i>更多文章</i></a>
		{/if}
	</div>
	<!-- 经验杂谈 E -->
	<!-- 商品精选 S -->
	<div class="favourite_product">
		<h2 class="goods_list_title font_14">精选商品</h2>
		<ul class="product_list">
			{section name=k loop=$product_results max=10}
			<li class="product_li">
				<a target="_blank" href="http://m.yixun.com/t/detail/index.html?pid={$product_results[k].COMMODITYID}" class="product_a">
					<img alt="" src="{$product_results[k].IMG}" class="goods_img_80">
					<div class="product_info">
						<h3 class="product_name">{$product_results[k].TITLE}</h3>
						<div class="product_price">
							<span class="price_new"><span>¥</span>{$product_results[k].MARKETPRICE}</span>
							<span class="cart"></span>
						</div>
					</div>
				</a>
			</li>
			<div class="border_line"></div>
			{/section}				
		</ul>
		{if $product_results|@count eq 0}
			<div class="no_art more_goods">去添加一些商品吧</div>
		{else}
		<a class="more_goods" href="{$frontdomain}/product/lists/{$site_results.id}/{$master_results.id}" target="_blank"><i>更多商品</i></a>
		{/if}
	</div>
	<!-- 商品精选 E -->
	<!-- 易选精选 S -->
	
	<!-- 易选精选 E -->
	<!-- 推荐达人 S -->
	
	<!-- 推荐达人 E -->
	<!-- 广告区 S -->
	<div class="wz_ad">
		<a target="_self" href="http://m.zdm.yixun.com/list/xinpin">
			<img src="http://img2.icson.com/ICSONAD/201304/1_big20130427103107460661.jpg" alt="" />
		</a>
	</div>
	<!-- 广告区 E -->
	<input type="hidden" id="wz_page_info" data-masterid="{$master_results.id}"	data-siteid="{$site_results.id}" data-articleIdList="{$articleIdList}"/>
	{include file="../common/footer.html"}
	<script type="text/javascript" src="http://st.icson.com/weizhan/mobile/js/index.js{include file="../common/version.json"}"></script>	
	
	<script type="text/javascript" src="http://st.icson.com/vendor/require/require.js" data-main="http://st.icson.com/weizhan/mobile/js/main.js"></script>
	<script>yx.wz.index.setCommentNum()</script>
</body>
</html>