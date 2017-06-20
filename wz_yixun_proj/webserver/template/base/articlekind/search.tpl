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
        .ms-controller{
            visibility: hidden;
        }
		

    </style>
</head>

<body class="">

<!--#include virtual="/sinclude/common/v1/utf8/head.html"-->

<!-- 主体部分 start -->
<div class="ic_content" id="container" ms-controller="wz-article-catalog">
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
					
					<div class="u_info url_info clearfix">
						<div>
							<span class="fl title">文章分类<br />
							<a id="wz_mobile_url" href="<#$frontdomain#>/article/newlists/<#$wzid#>/<#$msid#>/<#$results.0.id#>"><#$frontdomain#>/article/newlists/<#$wzid#>/<#$msid#>/<#$results.0.id#></a>
							</span>
							<span class="fl s_code" id="wz_qrcode" >访问文章
								<div class="dload">
									手机扫码访问:
									<div id="J-qrcode">
									</div>
								</div>
							</span>						
						</div>
					</div>
					<div class="cr_cons">
                        <form class="crc_list crc_opt crc_form" ms-on-submit="submitCatalog($event)" method="post" action="/articlekind/save?format=json">				
						 <p>
						  <label for="category_1"><span class="font_red">*</span>文章列表1：</label>
                                                  <input type="text" id="category_1" placeholder="最多10个字" name="c[0][articlekind][kindName]" value="<#$results.0.kindName#>" maxlength="10"/>
                                                  <input type="hidden" name="c[0][articlekind][id]" value="<#if $results.0.kindName eq ''#>0<#else#><#$results.0.id#><#/if#>" >
                                                  <#if $results.0.kindName neq ''#><a class="" href="/articleinfo/articlelist?kindId=<#$results.0.id#>">管理文章>></a><#/if#>	
						  </p>
						  <p>
						  <label for="category_2"><span class="font_red">*</span>文章列表2：</label>
						  <input type="text" id="category_2" placeholder="最多10个字" name="c[1][articlekind][kindName]" value="<#$results.1.kindName#>" maxlength="10"/>
                                                  <input type="hidden" name="c[1][articlekind][id]" value="<#if $results.1.kindName eq ''#>0<#else#><#$results.1.id#><#/if#>" >
						  <#if $results.1.kindName neq ''#><a class="" href="/articleinfo/articlelist?kindId=<#$results.1.id#>">管理文章>></a><#/if#>	
						  </p>
						  <p>
						  <label for="category_3"><span class="font_red">*</span>文章列表3：</label>
						  <input type="text" id="category_3" placeholder="最多10个字" name="c[2][articlekind][kindName]" value="<#$results.2.kindName#>" maxlength="10"/>
                                                  <input type="hidden" name="c[2][articlekind][id]" value="<#if $results.2.kindName eq ''#>0<#else#><#$results.2.id#><#/if#>" >
						  <#if $results.2.kindName neq ''#><a class="" href="/articleinfo/articlelist?kindId=<#$results.2.id#>">管理文章>></a><#/if#>
						  </p>
						  </br><p>分类添加后方可管理文章
						  <button type="submit" ms-disabled="isdisabled">确定</button>
						</form>
					</div>
				
			</div>
		</div>
	</div>
</div>

<!-- 主体部分 end -->

<script type="text/javascript" src="http://st.icson.com/vendor/jquery/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="http://st.icson.com/vendor/jquery/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="http://st.icson.com/static_v1/js/global.js" charset="gb2312"></script>

<script type="text/javascript" src="http://st.icson.com/vendor/qrcode/jquery.qrcode.min.js"></script>
<script type="text/javascript" src="http://st.icson.com/weizhan/desktop/widget/plugin/toastr/toastr.js"></script>
<script type="text/javascript" src="http://st.icson.com/weizhan/desktop/widget/plugin/modal/jquery.modal.js"></script>
<script type="text/javascript" src="http://st.icson.com/vendor/blockui/jquery.blockUI.js"></script>
<script type="text/javascript" src="http://st.icson.com/vendor/qrcode/jquery.qrcode.min.js"></script>


<#include file="../common/jsi_widget.tpl" #>

<!--#include virtual="/sinclude/jsi/common/header.html"-->
<!--#include virtual="/sinclude/common/utf8/footer.html"-->

<script type="text/javascript">
	timeStat[5] = new Date() - timeStat[0];
</script>

<script type="text/javascript">
	avalon.config({
		loader: false,
		debug: true
	});

	var wzUI = {
		navId: "wz-nav-articles-catalog"
	};

	//var wzVM;
	var VM;
	
	 $(function() {		
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
		initQRCode();
    });
	
	require(["wz/articleCatalogPage","wz/navBar"], function(vm,nav) {
		VM = vm;
		//wzVM = vm.VM;
        vm.UI.showInfo = showInfo;
		vm.UI.showError = showError;
				
		
	    function showInfo(message, title){
			title = title || "易迅微站";
			toastr.info(message, title);
		}
		
		function showError(message, title){
			title = title || "易迅微站";
			toastr.error(message, title);
		}
		
		nav.setCurrent(wzUI.navId);
	});
	
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

