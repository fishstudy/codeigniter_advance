<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
    <meta name="description" content="易迅网" />
    <meta name="keywords" content="易迅网" />
    <title>易迅微站-易迅网</title>
    <link rel="dns-prefetch" href="http://st.icson.com/" />
    <link rel="stylesheet" href="http://static.gtimg.com/icson/css/common/gb.css?t=20140218103358" type="text/css" media="screen" />
    
	<link rel="icon" href="http://www.yixun.com/favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="http://www.yixun.com/favicon.ico" type="image/x-icon" />
	<script type="text/javascript">var timeStat=[];timeStat[0]=new Date();document.domain = 'yixun.com';</script>
	<#include file="../common/header_widget.tpl" #>
	<style>
	.ms-controller{
		visibility: hidden;
	}
	</style>
</head>

<body class="">
<!--#include virtual="/sinclude/common/v1/utf8/head.html"-->

<!-- 主体部分 start -->
<div class="ic_content" id="container">
    <div class="wz_head clearfix">
        <div class="grid_c1">
            <div class="wz_head_1">
                <img class="logo" alt="" src="http://st.icson.com/weizhan/desktop/img/weizhan.png">
            </div>
        </div>
    </div>
    <div class="grid_c1 font_songt">
         <#include file="../common/base/crumb.tpl" #>

        <div class="main_con clearfix">
           <#include file="../common/base/nav.tpl" #>
            <div class="fl conts_right">
            <form action="" method="post" enctype="multipart/form-data">

                <h2>微站资料管理</h2>
                <div class="cr_cons inf_ctrl">
                    <div class="wz_name">
                        <h4>1.  微站名字</h4>
                        <input type="hidden" name="c[id]" value="<#$results.id#>" readonly="readonly" />
                        <input type="text" class="ipt_wz_name"  placeholder="通俗易懂的名字，能帮用户快速找到您的微站，最长不超过8个字" name="c[name]" value="<#if $results.name neq ""#><#$results.name#><#/if#>" maxlength="8" />
                    </div>
					<!--
                    <div class="wz_logo clearfix">
                        <h4>2.  微站LOGO</h4>
                        <img class="fl" src="<#if $results.logo neq ""#><#$results.logo#><#/if#>" alt="" />
						<span class="relatv">大小100kb内的jpg、png<br />
							<div id='upload_logo'></div>
						</span>
						<input type="hidden" name="c[logo]" value="<#if $results.logo neq ""#><#$results.logo#><#/if#>"  maxlength="100"/>
                    </div>-->
                    <div class="wz_abme">
                        <h4>2.  自我介绍</h4>
                        <textarea title="自我介绍不超过50个字" class="ipt_wz_abme" placeholder="请输入50字以内的自我介绍"  name="c[introduction]"  maxlength="50"><#if $results.introduction neq ""#><#$results.introduction#><#/if#></textarea>
                    </div>
                    <div class="wz_infme">
                        <h4>3.  个人资料</h4>
                        <div class="wz_indet">
                            <span>QQ：</span><input type="text" class="ipt_wz_infme"  name="c[qq]" value="<#if $results.qq neq ""#><#$results.qq#><#/if#>"  maxlength="20"/>
							<span id="err_qq" class="err"></span><br />
                            <span>微博：</span><input type="text" class="ipt_wz_infme" name="c[weibo]" value="<#if $results.weibo neq ""#><#$results.weibo#><#/if#>"  maxlength="50" />
							<span id="err_wb" class="err"></span>
                        </div>
                    </div>
					<!--
                    <div class="wz_banner">
                        <h4>5.  微站招牌</h4>
						<span class="relatv">您的网站招牌，图片不大于100kb，类型：jpg、png
						<img class="" src="<#$results.bgPicture#>" alt="" />
						<div id='upload_big'>							
						</div>
						</span>
						 <input type="hidden" name="c[bgPicture]" value="<#$results.bgPicture#>"  maxlength="100"/>
                    </div>-->
                </div>

                <button type="button" class="btn_sub" value="">提交</button>
            </form>
            </div>
        </div>
    </div>
</div>
<!-- 主体部分 end -->

<script type="text/javascript" src="http://st.icson.com/vendor/jquery/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="http://st.icson.com/vendor/jquery/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="http://st.icson.com/static_v1/js/global.js" charset="gb2312"></script>
<script type="text/javascript" src="http://st.icson.com/weizhan/desktop/widget/plugin/toastr/toastr.js"></script>
<#include file="../common/jsi_widget.tpl" #>

<!--#include virtual="/sinclude/jsi/common/header.html"-->
<!--#include virtual="/sinclude/common/utf8/footer.html"-->

<script type="text/javascript">
	timeStat[5] = new Date() - timeStat[0];
</script>

<script>
$(function() {
	 $("#wz-nav-baseinfo").addClass("curr");
	
	toastr.options = {
			"closeButton": true,
			"debug": false,
			"positionClass": "toast-bottom-center",
			"onclick": null,
			"showDuration": "500",
			"hideDuration": "500",
			"timeOut": "2000",
			"extendedTimeOut": "500",
			"showEasing": "swing",
			"hideEasing": "linear",
			"showMethod": "fadeIn",
			"hideMethod": "fadeOut"
		}
	});
	require(["wz/webmasterEdit", "http://st.icson.com/static_v1/uploadify/imgupload.js"], function(p){
		G.app.imgupload.init(p.uploadparam1);
		G.app.imgupload.init(p.uploadparam2);
	});
</script>

</body>

</html>
