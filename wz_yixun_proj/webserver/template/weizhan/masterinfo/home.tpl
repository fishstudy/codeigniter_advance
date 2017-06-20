<!DOCTYPE html>
<html>
<head>
{include file="../common/header.html"}
<title>易迅微站</title>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
<link rel="stylesheet" type="text/css" href="http://st.icson.com/weizhan/mobile/css/wz_global.css{include file="../common/version.json"}">
</head>
<body>
    <!--<img src="http://img2.icson.com/event/2015/07/09/14364394786233.jpg" class="f_bottom" id="fix_bottom">-->
	
	<div class="cap" id="cap" style="display:none">
	<!--
	<div class="inner">
		<img src="img/a_3.png" class="top" onclick="document.getElementById('cap').style.display='none';">
		<div class="list" id="cap_list">
			
			<a data-id="new-3621" class="J_coupon" href="javascript:void(0)"><img class="item" src="img/q_1.png"/></a>
			<a data-id="new-3621" class="J_coupon" href="javascript:void(0)"><img class="item" src="img/q_2.png"/></a>
			<a data-id="new-3621" class="J_coupon" href="javascript:void(0)"><img class="item" src="img/q_3.png"/></a>
			<a data-id="new-3621" class="J_coupon" href="javascript:void(0)"><img class="item" src="img/q_4.png"/></a>
			<a data-id="new-3621" class="J_coupon" href="javascript:void(0)"><img class="item2" src="img/q_7.png"/></a>
			
			<a data-id="new-2932" class="J_coupon" href="javascript:void(0)"><img class="item" src="http://img2.icson.com/event/2015/07/03/14358943977960.png"/></a>
			<a data-id="new-2932" class="J_coupon" href="javascript:void(0)"><img class="item" src="http://img2.icson.com/event/2015/07/03/14358943977960.png"/></a>
			<a data-id="new-2932" class="J_coupon" href="javascript:void(0)"><img class="item" src="http://img2.icson.com/event/2015/07/03/14358943977960.png"/></a>
			<a data-id="new-2932" class="J_coupon" href="javascript:void(0)"><img class="item" src="http://img2.icson.com/event/2015/07/03/14358943977960.png"/></a>
			
			<a data-id="new-2932" class="J_coupon" href="javascript:void(0)"><img class="item" src="http://img2.icson.com/event/2015/07/03/14358943977960.png"/></a>
			<a data-id="new-2932" class="J_coupon" href="javascript:void(0)"><img class="item" src="http://img2.icson.com/event/2015/07/03/14358943977960.png"/></a>
			
		</div>
		<img src="img/a_4.png" class="bottom">
	</div>-->
	</div>
    <div class="J_ping">
    <img src="http://img10.360buyimg.com/yixun_zdm/s640x430_{$site_results.actPic}" class="block_img">
    {if $site_results.actCoupon eq 1}
    <div class="quan_info">
        <div class="left">
            <i class="icon"></i>
            <p class="text">
            微站专享优惠券
            <b>您还有<i>5</i>张券可以领</b>
            </p>
        </div>
        <a href="javascript:void(0)" class="right">马上领</a>
    </div>
    <!--券的信息-->
    {/if}
    <div class="recommand">
        <div class="title">达人推荐</div>
        <div class="banner">
            <i class="icon"></i>
            <img src="http://img10.360buyimg.com/yixun_zdm/s200x200_{$site_results.actLogo}" class="header">
            <div class="content">
                <p class="c_info">{$site_results.recommend}</p>
            </div>
        </div>
        <!--推荐-->
        <div class="pd_con">
            <ul>
                <li>
                    {if $recommand|@count neq 0}
                    <div class="inner J_Product" data-pid="{$recommand.0.pId}">
                        <a href="javascript:;"><img src="{if $recommand.0.pic}http://img10.360buyimg.com/yixun_zdm/s200x200_{$recommand.0.pic}{/if}" class="pd_pic J_Pic"></a>
                        <span class="pd_name J_Desc">{$recommand.0.info}</span>
                        <p class="pd_price J_Price">读取中...</p>
                        <a class="pd_buy" href = "http://m.yixun.com/t/detail/?pid={$recommand.0.pId}" target="_blank">立即购买</a>
                    </div>
                    {else}
					<div>还没有商品上架哦</div>
                    {/if}
                </li>
                <li>
                    {if $recommand|@count neq 0}
                    <div class="inner J_Product" data-pid="{$recommand.1.pId}">
                        <a href=""><img src="{if $recommand.1.pic}http://img10.360buyimg.com/yixun_zdm/s200x200_{$recommand.1.pic}{/if}" class="pd_pic J_Pic"></a>
                        <span class="pd_name J_Desc">{$recommand.1.info}</span>
                        <p class="pd_price J_Price">读取中...</p>
                        <a class="pd_buy" href="http://m.yixun.com/t/detail/?pid={$recommand.1.pId}" target="_blank">立即购买</a>
                    </div>
                    {else}
					<div>还没有商品上架哦</div>
                    {/if}
                </li>
            </ul>   
        </div>
        <div class="swipe_icons hide">
            <span class="cur"></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <!--达人推荐-->
    <div class="qianggou">
         <div class="title">本期抢购</div>
         
        {if $qiang|@count neq 0}
		<div class="scroller pd_con2" id="scroller">
            <ul>		
				{foreach from=$qiang item=item key=key}
                <li class="cur">
                    <!--<div class="top_info">6月12日</div>-->
					<div class = "J_Product" data-pid="{$item.pId}">
                    <div class="top_info top_info_2 ">{$item.title}</div>
                    <i class="icon"></i>
                    <a href=""><img src="" class="pd_pic J_Pic"></a>
                    <a href="" class="pd_name J_Name"></a>
                    <p class="pd_desc J_Desc"></p>
                    <p class="pd_price J_Price">读取中...</p>
                    <a href="http://m.yixun.com/t/detail/?pid={$item.pId}" class="pd_buy" target="_blank">去看看</a>
					</div>
                </li>
				
                {/foreach}
			</ul>
        {else}
		<div style="height:30px;">还没有商品上架哦</div>
        {/if}
            
        </div>
    </div>
    <!--抢购-->
    <div class="gdgroup">
        <div class="text_con">
            <ul>
                {if $shopcatalog|@count neq 0 }
				{foreach $shopcatalog as $key=>$value}
                <li {if $value@first }class="cur" {/if} scId="{$value['id']}"><a href="">{$value.catalogName}</a></li>
                {/foreach} 
                {else}
                <li class="cur"><a href="">楼层1</a></li>
                <li><a href="">楼层2</a></li>
                <li><a href="">楼层3</a></li>
                <li><a href="">楼层4</a></li>
                {/if}
            </ul>   
        </div>
         {if $shopcatalog|@count neq 0 }   
        {foreach from=$shopcatalog item=cat key=k}

        <div class="pd_con3 hide" scId="{$cat['id']}">
            <ul>
                {foreach from=$hot item=q key=key}
                 {if $cat['id'] eq $q['scId'] }   
                <li class="clear">
                    <div class="left"><a href="javascript:;" class="J_ytag J_Product " data-pid="{$q.pId}" data-mprice="700" ytag="20024">
                        <p class="img_wrap"><img class="J_Pic" src=""></p>
                        
                    </a></div>
                    <div class="right"><a href="javascript:;" class="J_ytag J_Product " data-pid="{$q.pId}" data-mprice="700" ytag="20025">
                        
                        <div class="txt_wrap">
                            <p class="name" pid="{$q.pId}">{$q.title}</p>
                            <p class="desc J_Desc"></p>
                            <p class="price y_pf"><b class="num"><span class = "J_Price">读取中...</span></b></p>
                        </div>
                    </a><a href="http://m.yixun.com/t/detail/?pid={$q.pId}" class="J_ytag J_Product" data-pid="{$q.pId}" data-mprice="700" ytag="20026" target="_blank"><img src="http://img2.icson.com/event/2015/05/22/14322632482833.png" class="buy_icon"></a></div><div class="clearfix"></div>
                </li>
                {/if}
                {/foreach}
                
                
            </ul>
        </div>


        {/foreach}
        {/if}
    </div>
    <!--商品组-->
</div>
<input type="hidden" id="wz_page_info" data-masterid="{$master_results.id}"	data-siteid="{$site_results.id}"/>
{include file="../common/footer.html"}
<script type="text/javascript">
var w_width = document.body.clientWidth > 640 ? 640 : document.body.clientWidth;  
var li_width = (parseInt(w_width)/10*4);
var scroll_ul = document.getElementById("scroller").getElementsByTagName("ul")[0];
var li_list = document.getElementById("scroller").getElementsByTagName("li");
for(var i = 0; i < li_list.length; i++){
    li_list[i].style.width = li_width+'px';
}
scroll_ul.style.width = (li_width+101) * li_list.length + 'px';
//document.getElementById("scroller").getElementsByTagName("ul")[0].style.width = (li_width+3) * document.getElementById("scroller").getElementsByTagName("li").length + 'px';
</script>
<script type="text/javascript" src="http://st.icson.com/weizhan/mobile/js/weizhan_base.js{include file="../common/version.json"}"></script>
<script type="text/javascript" src="http://st.icson.com/weizhan/mobile/js/homePage.js{include file="../common/version.json"}"></script>
<script>
	yx.wz.homaPage.init();
	yx.wz.base.tjopt = { "pageid" :"1001",
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