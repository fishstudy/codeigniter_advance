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
    <#include file="../common/header_widget.tpl" #>
    <link rel="icon" href="http://www.yixun.com/favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="http://www.yixun.com/favicon.ico" type="image/x-icon" />
    <script type="text/javascript">var timeStat=[];timeStat[0]=new Date();document.domain = 'yixun.com';</script>

    <style>

    </style>
</head>

<body class="">

<!--#include virtual="/sinclude/common/v1/utf8/head.html"-->

<!-- 主体部分 start -->
<div class="ic_content" id="container" >
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
				<!--STRAT fl conts_right  -->
                <div class="fl conts_right">

					<div class="u_info clearfix">
						<img class="fl u_poto" src="<#if $results.actLogo #>http://img10.360buyimg.com/yixun_zdm/s200x200_<#$results.actLogo#><#else#>http://st.icson.com/weizhan/mobile/img/default_wzlogo.png<#/if#>" />
						<div class="uin_menu">
							<span class="fl u_name"><strong><#if $results.name #><#$results.name#><#else#>创意家居生活<#/if#></strong><br />
							<a id="wz_mobile_url" href="<#$frontdomain#>/masterinfo/home/<#$wzid#>"><#$frontdomain#>/masterinfo/home/<#$wzid#></a>
							</span>
							<span class="fl s_code" id="wz_qrcode" >访问微站
								<div class="dload">
									手机扫码访问:
									<div id="J-qrcode">
									</div>
								</div>
							</span>						
						</div>
					</div>
					<div class="q_a">
						<#if $results.isclosed #>
						<p><font color="red">您的微站已经被关闭，原因如下：<#$results.closereason#></font></p>
						<#/if#>
						<p>感谢您使用易迅微站，我们还很年轻，还在努力充实微站功能，再次感谢您的支持！</p>
						<p>
							<span class="q"><font class="red">Q</font>：易迅微站有哪些功能？</span>
							<span class="a"><font class="gre">A</font>：易迅微站暂时为您提供个人资料管理、文章分享管理功能</span>
						</p>
						<p>
							<span class="q"><font class="red">Q</font>：文章从哪里来？</span>
							<span class="a"><font class="gre">A</font>：易迅微站的所有文章，都是由您自己从易选频道中加入，你可以加入别人的文章，也可以使用自己的文章，在加入文章后可以修改文章的标题、简介方便您更好的在微信朋友圈传播。</span>
						</p>
						<p>
							<span class="q"><font class="red">Q</font>：我加入别人的文章，万一我的朋友购买了文章里推荐的商品，返利给原文作者还是我？</span>
							<span class="a"><font class="gre">A</font>：返利会给您个人，因为是借助您的朋友圈和人脉实现的返利，所有返利收益都会归您个人所有，与原文作者无关。</span>
						</p>
						<p>
							<span class="q"><font class="red">Q</font>：现在的功能看起来很少，后面有什么新功能吗？</span>
							<span class="a"><font class="gre">A</font>：马上会加入站长推荐商品功能、站长成长计划、各种好玩的营销工具，帮助您更好的运营自己的小站，敬请期待吧！</span>
						</p>
					</div>
                </div
				<!--END fl conts_right  -->
        </div>
    </div>
</div>


<!-- 主体部分 end -->

<script type="text/javascript" src="http://st.icson.com/vendor/jquery/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="http://st.icson.com/vendor/jquery/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="http://st.icson.com/static_v1/js/global.js" charset="gb2312"></script>
<!-- https://github.com/jeromeetienne/jquery-qrcode -->
<script type="text/javascript" src="http://st.icson.com/vendor/qrcode/jquery.qrcode.min.js"></script>

<#include file="../common/jsi_widget.tpl" #>

<!--#include virtual="/sinclude/jsi/common/header.html"-->
<!--#include virtual="/sinclude/common/utf8/footer.html"-->


<script type="text/javascript">
    timeStat[5] = new Date() - timeStat[0];
</script>

<script>
    $(document).ready(function(){

    });
</script>


<script type="text/javascript">

    var wzUI = {
     
    };

  
    $(function() {
        init();		
    });
	
	
    function init() {
		initQRCode();
    }
	
	function initQRCode() {
		var url = $("#wz_mobile_url").prop("href");
        $("#J-qrcode").qrcode({
			"width": 150,
			"height": 150,
			"text":url
		});
		
		$("#wz_qrcode" ).on("click", function() {
			$(this).find('.dload').toggle()
		});
	}

	

</script>


</body>
</html>

