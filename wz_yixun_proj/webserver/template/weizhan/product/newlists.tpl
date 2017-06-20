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

<div id="slider" class="goods_container">
{section name=k loop=$results max=10}
<div class="goods" style="background-image:url(http://img10.360buyimg.com/yixun_zdm/s640x480_{$results[k].pic})">
    <i class="trg trg_left"></i>
    <i class="trg trg_right"></i>
    <div class="desc">
        <a href="http://m.yixun.com/t/detail/index.html?pid={$results[k].pId}" title="{$results[k].title}" class="name" target="_blank">{$results[k].title} {$results[k].info}</a>
        <a href="http://m.yixun.com/t/detail/index.html?pid={$results[k].pId}" title="">
            <img src="http://st.icson.com/weizhan/mobile/img/buy_btn.png" class="buy">
        </a>
    </div>
</div>
{/section}
</div>

<input type="hidden" id="wz_page_info" data-masterid="{$master_results.id}"	data-siteid="{$site_results.id}"/>
<style>
.slider-wrapper {
position: relative; border: 1px solid #159957; height: 100%
}
      .slider-content {
	  width: 100%; font-size: 0px; -webkit-text-size-adjust: none; height: 100%; position: relative; overflow: hidden; color: #1e6bb8;
	  }
      
      .slider-steps {
	  position: absolute; bottom: 5px; height: 10px; width: 100%; text-align: center; font-size: 0px
	  }
      .slider-steps .step {
	  font-size: 0px; display: inline-block; margin: 0 10px; width: 10px; height: 10px; border-radius: 5px; background: #159957; overflow: hidden; text-indent: -20px;
	  }
      .slider-steps .current {
	  background: #1e6bb8
	  }
      
      .slider-control .next {
	  position: absolute; z-index: 2; right: 0; top: 50%; margin-top: -30px; height: 60px; width: 60px; border-radius: 30px; background: #159957
	  }
      .slider-control .next-s {
	  width: 10px; height: 10px; border-radius: 5px; position: absolute; right: 5px; margin-top: -5px; top: 50%; background: rgba(255, 255, 255, 0.6)
	  }
      .slider-control .prev {
	  position:absolute; z-index: 2; left: 0; top: 50%; margin-top: -30px; height: 60px; width: 60px; border-radius: 30px; background: #159957
	  }
      .slider-control .prev-s {
	  width: 10px; height: 10px; border-radius: 5px; position: absolute; left: 5px; margin-top: -5px; top: 50%; background: rgba(255, 255, 255, 0.6)
	  }
      .slider-control .disable {
	  opacity: 0.5
	  }
	  .slider-control {
		display:none
	  }
</style>
<script type="text/javascript" src="http://st.icson.com/weizhan/mobile/js/weizhan_base.js{include file="../common/version.json"}"></script>
{include file="../common/footer.html"}
<script src="http://st.icson.com/weizhan/desktop/widget/plugin/niceslider/niceslider.js"></script>

<script type="text/javascript">
	var slider1;
	setTimeout(function () {
	    slider1 = new NiceSlider('#slider', { 
			unlimit: false
		});
		var evt = "onorientationchange" in window ? "orientationchange" : "resize";
		window.addEventListener(evt, function() {
		    setTimeout(function () {
				slider1.refresh()
			},400);
		}, false);
	}, 400);
	yx.wz.base.tjopt = { "pageid" :"1201",
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
