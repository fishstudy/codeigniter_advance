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
<div class="ic_content ms-controller" id="container" ms-controller="wz-hotevent-qiang">
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
				<h2>本期抢购</h2>
					 <div class="cr_cons" id="wz_datacontent">
						<div class="crc_list" ms-repeat-item="products" ms-visible="products.length">
							<form class="crc_list crc_opt crc_form"   ms-on-submit="submitProduct($event, $index)" method="post">
							<h3>商品</h3>
							 <a class="fr crc_list_del"  ms-click="deleteProduct" ms-data-index="$index" ms-data-id="item.id">删除</a>
							 <p>
							  <label ms-attr-for="buy_title_{{$index}}">抢购标题：</label>
							  <input type="text" ms-attr-id="buy_title_{{$index}}" placeholder="最多30个字" name="c[shopproduct][title]" ms-attr-value="item.title" maxlength="30" size="50"/>
							  </p>
							  <!--<p>
							  <label ms-attr-for="product_category_{{$index}}">商品类目：</label>
							  <input type="text" ms-attr-id="product_category_{{$index}}" ms-attr-placeholder="{{item.category}}"/>
							  </p>-->
							  <p>
							  <label class="relatv" ms-attr-for="product_id_{{$index}}"><span class="required_sign">*</span>商品编号：</label>
							  <input type="text" ms-attr-id="product_id_{{$index}}" placeholder="商品编号" name="c[shopproduct][pId]" ms-attr-value="item.pId"/>
							  </p>
							  <input class="" type="hidden" ms-attr-value="item.id" name="c[shopproduct][id]"/>
							  <button type="submit">确定</button>
							</form>							
						</div>							
					</div>
					<a class="add-btn" href="javascript:void(0);" ms-css-display="{{isshow}}" ms-click="addProduct()" >+新增商品</a>
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
        navId: "wz-nav-qiang",
		loading: false
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
	
    require(["wz/purchasingPage"], function(vm) {
        VM = vm;
		//wzVM = vm.VM;
        vm.init();
        vm.UI.showInfo = showInfo;
		vm.UI.showError = showError;
		
		vm.ProductVM.$watch("isLoading", function(value, oldValue) {
			if(value) {
				showLoading();
			} else {
				hideLoading();
			}			
		});
		
	    function showInfo(message, title){
			title = title || "易迅微站";
			toastr.info(message, title);
		}
		
		function showError(message, title){
			title = title || "易迅微站";
			toastr.error(message, title);
		}
		
		function showLoading() {
		$('#wz_datacontent').block({ message: "加载中" }); 
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
		
		$("#" + wzUI.navId).addClass("curr");
    });
</script>


</body>

</html>

