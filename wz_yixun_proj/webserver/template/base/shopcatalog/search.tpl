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
<div class="ic_content" id="container" ms-controller="wz-hotevent-popular">
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
				<h2>热销爆品</h2>

					<div class="cr_cons">
                        <form class="crc_list crc_opt crc_form" ms-on-submit="submitCatalog" method="post" >				
						 <p>
						  <label for="category_1"><span class="font_red">*</span>类目1：</label>
                                                  <input type="text" id="category_1" placeholder="最多10个字" name="c[0][shopcatalog][catalogName]" value="<#$results.0.catalogName#>" maxlength="10"/>
                                                  <input type="hidden" name="c[0][shopcatalog][id]" value="<#if $results.0.catalogName eq ''#>0<#else#><#$results.0.id#><#/if#>" >
                                                  <#if $results.0.catalogName  neq ''#><a class="" href="/shopproduct/hotSearch?catalogId=<#$results.0.id#>">管理商品>></a><#/if#>	
						  </p>
						  <p>
						  <label for="category_2"><span class="font_red">*</span>类目2：</label>
						  <input type="text" id="category_2" placeholder="最多10个字" name="c[1][shopcatalog][catalogName]" value="<#$results.1.catalogName#>" maxlength="10"/>
                                                  <input type="hidden" name="c[1][shopcatalog][id]" value="<#if $results.1.catalogName eq ''#>0<#else#><#$results.1.id#><#/if#>" >
						  <#if $results.1.catalogName  neq ''#><a class="" href="/shopproduct/hotSearch?catalogId=<#$results.1.id#>">管理商品>></a><#/if#>	
						  </p>
						  <p>
						  <label for="category_3"><span class="font_red">*</span>类目3：</label>
						  <input type="text" id="category_3" placeholder="最多10个字" name="c[2][shopcatalog][catalogName]" value="<#$results.2.catalogName#>" maxlength="10"/>
                                                  <input type="hidden" name="c[2][shopcatalog][id]" value="<#if $results.2.catalogName eq ''#>0<#else#><#$results.2.id#><#/if#>" >
						  <#if $results.2.catalogName neq ''#><a class="" href="/shopproduct/hotSearch?catalogId=<#$results.2.id#>">管理商品>></a><#/if#>
						  </p>
						  <p>
						  <label for="category_4"><span class="font_red">*</span>类目4：</label>
						  <input type="text" id="category_4" placeholder="最多10个字" name="c[3][shopcatalog][catalogName]" value="<#$results.3.catalogName#>" maxlength="10"/>
                                                  <input type="hidden" name="c[3][shopcatalog][id]" value="<#if $results.3.catalogName eq ''#>0<#else#><#$results.3.id#><#/if#>" >
						  <#if $results.3.catalogName neq ''#><a class="" href="/shopproduct/hotSearch?catalogId=<#$results.3.id#>">管理商品>></a><#/if#>
						  </p>
						  </br><p>类目添加后方可管理商品
						  </p>
						  <button class="" type="submit" ms-disabled="isdisabled">确定</button>
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
		navId: "wz-nav-popular"
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
    });
	
	require(["wz/popularCatalogPage","wz/navBar"], function(vm,nav) {
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

		$("#" + wzUI.navId).addClass("curr");
		nav.showChildren("search");
	});
</script>


</body>

</html>

