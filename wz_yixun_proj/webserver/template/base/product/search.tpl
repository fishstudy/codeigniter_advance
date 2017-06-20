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
<div class="ic_content ms-controller" id="container"  ms-controller="wz-products-manager">
    <div class="wz_head clearfix">
        <div class="grid_c1">
            <div class="wz_head_1">
                <img class="logo" alt="" src="http://st.icson.com/weizhan/desktop/img/weizhan.png">
				<input type="hidden" id="wz_msid" value="<#$msid#>" >
				<input type="hidden" id="wz_wzid"  value="<#$wzid#>" >
            </div>
        </div>
    </div>
    <div class="grid_c1 font_songt">
        <#include file="../common/base/crumb.tpl" #>

        <div class="main_con clearfix">
            <#include file="../common/base/nav.tpl" #>
                <div class="fl conts_right">
                    <h2>商品</h2> 
                    <div class="cr_cons" id="wz_datacontent">                      
                        <div id="data_articles" class="crc_list">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr height="57">
                                    <th width="20"></th>
                                    <th width="320">商品名称</th>
                                    <th width="105">商品图片<br /></th>
                                    <th width="100">原价</th>
									<th width="100">销售价</th>
                                </tr>
                                <tr ms-repeat-item="products" ms-data-product-id="item.COMMODITYID">
                                    <td>
										<span style="display:none" class="J_id">{{item.COMMODITYID}}</span>
                                    </td>              
									<td>
                                        <a class="crcl_name" ms-href="item.URL" target="_blank">{{item.TITLE}}</a>
                                    </td> 
									<td>
										<img ms-src="item.IMG" width="70">
									</td>
									<td>
										<span><del>{{item.MARKETPRICE}}</del></span>
									</td>
									<td>
										<span>{{item.PRICE}}</span>
									</td>
									 <tr>
                                    <td colspan="7" height="20">
										<span class="wz_search_info" ms-visible="!products.length">没有商品可以显示</span>
                                        <span class="fr page_count">每页10条</span>
                                        <div class="fr page_count" ms-widget="pager,productsPager,pages"></div>
                                    </td>
                                </tr>
                                </tr>                         
                            </table>
                        </div>                        
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

<script>
    $(document).ready(function(){

    });
</script>


<script type="text/javascript">

    avalon.config({
        loader: false,
        debug: true
    });

    var wzUI = {
        navId: "wz-nav-products",
		loading: false
    };

    //var wzVM;
	var VM;
    require(["wz/productPage"], function(vm) {
        VM = vm;
		//wzVM = vm.VM;
        vm.init();
		showLoading();
        vm.UI.showInfo = showInfo;
		vm.UI.showError = showError;
		
		vm.productVM.$watch("isLoading", function(value, oldValue) {
			if(value) {
				showLoading();
			} else {
				hideLoading();
			}			
		});

		
    });

    $(function() {
        init();
		
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
	
	function showLoading() {
		$('#wz_datacontent').block({ message: "努力加载中" }); 
		//if(!wzUI.loading){
			//$('#wz_datacontent').unblock(); 
			//$('#wz_datacontent').block({ message: "加载中" }); 
			//wzUI.loading = true;
		//}
	}
	
	function hideLoading() {
		$('#wz_datacontent').unblock(); 
		/*
		if(wzUI.loading){
			$('#wz_datacontent').unblock(); 
			wzUI.loading = false;
		}
		*/
	}

    function showInfo(message, title){
        title = title || "易迅微站";
        toastr.info(message, title);
    }
	
	function showError(message, title){
        title = title || "易迅微站";
        toastr.error(message, title);
    }

    function init() {
        if(wzUI&& wzUI.navId) {
            $("#" + wzUI.navId).addClass("curr");
        }
    }


    function showQRCode(element) {
        var e = $(element).children("div");
        if(!e.data("qrcode")) {
            e.qrcode(e.data("qrtext"));
            e.data("qrcode","true");
        }
		e.show();
    }

    function hideQRCode(element) {
        $(element).children("div").hide();
    }

	
</script>


</body>
</html>

