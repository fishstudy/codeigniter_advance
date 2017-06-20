<!DOCTYPE html>
<html>
<head>
	{include file="../common/header.html"}
    <title>易迅微站</title>
    <link rel="stylesheet" type="text/css" href="http://st.icson.com/weizhan/mobile/css/wz_base.css{include file="../common/version.json"}">
	 <link rel="stylesheet" type="text/css" href="http://st.icson.com/weizhan/mobile/css/product_list.css{include file="../common/version.json"}">
	<script type="text/javascript" src="http://st.yixun.com/weizhan/mobile/js/mustache.js"></script>
</head>
<body>
<header>
    <div class="relatv nav">
        <h2 class="abs sc_mw font_16">精选商品</h2>
        <p class="nav_lcr">
            <a class="fl" href="{$frontdomain}/index/{$master_results.id}"><img src={$master_results.logo} alt="" /></a>
        </p>		
    </div>
</header>
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
		{if $product_results|@count eq 0}
		<div class="no_art more_goods">还没有相关商品哟</div>
		{/if}
		</ul>
<div class="wait_loading" style="display:none"><span>努力加载中..</span></div>
<input type="hidden" id="wz_page_info" data-masterid="{$master_results.id}"	data-siteid="{$site_results.id}"/>
<textarea class = "J_template">	
			[[#product_results]]<li class="product_li">
				<a target="_blank" href="http://m.yixun.com/t/detail/index.html?pid=[[COMMODITYID]]" class="product_a">
					<img alt="" src="[[IMG]]" class="goods_img_80">
					<div class="product_info">
						<h3 class="product_name">[[TITLE]]</h3>
						<div class="product_price">
							<span class="price_new"><span>¥</span>[[MARKETPRICE]]</span>
							<span class="cart"></span>
						</div>
					</div>
				</a>
			</li><div class="border_line"></div>[[/product_results]]
</textarea>
{include file="../common/footer.html"}

<script type="text/javascript" src="http://st.icson.com/weizhan/mobile/js/product_list.js{include file="../common/version.json"}"></script>
<script type="text/javascript" >
 yx.wz.productlist.init();
 yx.wz.base.tjopt = { "pageid" :"1102",
			ext:{
				"masterid": yx.wz.base.getWzMasterId(),
				"siteid" : yx.wz.base.getWzSiteId(),
				"opsystem":  yx.wz.base.versions.mobileSystem(),
				"browser": yx.wz.base.versions.browser()
			}
		};		
shangbao.init(yx.wz.base.tjopt);
 yx.wz.base.tag();
</script>
</body>
</html>