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
<div class="ic_content ms-controller" id="container" ms-controller="wz-product-pick">
  <div ms-class="loading:isLoading" class="load-block"><p class="t"><span>加载数据中...</span></p></div>
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
							<span class="fl title">精选商品<br />
							<a id="wz_mobile_url" href="<#$frontdomain#>/product/newlists/<#$wzid#>/4"><#$frontdomain#>/product/newlists/<#$wzid#>/4</a>
							</span>
							<span class="fl s_code" id="wz_qrcode" >访问商品
								<div class="dload">
									手机扫码访问:
									<div id="J-qrcode">
									</div>
								</div>
							</span>						
						</div>
					</div>
				
				<div class="cr_cons" ms-repeat-item="products">				
				  <form class="crc_list crc_opt crc_form" ms-on-submit="submitProduct($event, $index)" ms-data-index= action="/shopproduct/recommandSave?format=json" method="post">
            <h3>商品</h3> <a class="fr crc_list_del"  ms-click="deleteProduct($index)" ms-data-index="$index">删除</a>
            
            <!--div class="form-item"><label><span>商品类目：</span><select name="cate"><option>小家电</option></select></label></div-->
            <div class="form-item"> <label class="relatv" ms-attr-for="product_id_{{item.index}}"><span class="required_sign">*</span><span>商品编号：</span></label>
                <input type="text" ms-attr-id="product_id_{{item.index}}" placeholder="商品编号" name="c[shopproduct][pId]" ms-duplex="item.productId" /><!--button type="button">搜索</button--></div>
            <div class="form-item"> <label class="relatv" ms-attr-for="product_desc_{{item.index}}"><span class="required_sign">*</span><span>商品说明：</span></label>
            <input type="text" ms-attr-id="product_desc_{{item.index}}" name="c[shopproduct][info]" ms-duplex="item.description" maxlength="50" size="75" placeholder="最多50个字"/></div>
            <div class="form-item"><label class="relatv""><span class="required_sign">*</span><span>商品图片：</span><span class="webuploader"><span ms-attr-id="upload_{{item.index}}" ms-data-index="$index"><img ms-attr-id="image_{{item.index}}" ms-attr-src="{{item.img}}" alt="添加图片"  title="点击上传"/></span></span></label><span class="tip">您的图片大小不大于300kb，尺寸：781X440，类型：jpg、png、gif</span></div>
            <input ms-duplex="item.url" type="hidden" name="c[shopproduct][pic]" />
            <input ms-duplex="item.id" type="hidden" name="c[shopproduct][id]" />
            <button type="submit" ms-attr-disabled="{{item.isloading}}" >确定</button>
				  </form>
				</div>
				<a class="add-btn" href="javascript:void(0);" ms-click="addProduct()" ms-css-display="{{isshow}}">+新增商品</a>
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
<style>
.webuploader-container {position: relative;}
.webuploader-element-invisible {position: absolute !important; clip: rect(1px 1px 1px 1px); /* IE6, IE7 */ clip: rect(1px,1px,1px,1px);}
.webuploader-pick {position: relative; display: inline-block; cursor: pointer; color: #fff; text-align: left; border-radius: 3px; font-size: 14px; vertical-align: middle; width: 100%}
.webuploader-pick-hover { background: #fafcff;}
.webuploader-pick-disable { opacity: 0.6; pointer-events:none;}

textarea {vertical-align: top; margin: 2px; width: 400px; height: 200px; padding: 2px;}
.form-item input[type="text"], textarea{background-color: #fafcff; border: 1px solid #e3e3e3; padding: 2px 10px;}
.form-item {padding: 5px 0}
.form-item input[type="text"] {padding: 2px;}
.webuploader-pick {width: 200px; height: 200px; line-height: 200px; text-align: center; vertical-align: top; color: #000; }
.webuploader-pick img {display: inline; vertical-align: middle; max-widht: 200px; max-height: 200px}
button[type="submit"] {display: block; padding: 4px 20px; background: #09f; color: #fff; border: 0 none;}
.add-btn {padding: 4px 20px; background: #09f; color: #fff; border: 0 none; margin: 10px 0; display: inline-block}
.load-block.loading {display: block;}
.load-block {position: fixed; width: 100%; height: 100%; display: none; text-align: center; top: 0; bottom: 0; z-index: 999;}
.load-block .t {position: absolute; top: 50%; left: 0; width: 100%; height: 20px; line-height: 20px; text-align: center; margin: -10px 0 0}
.load-block .t span {color: #fff; padding: 4px 8px; background: #000}
.crc_list_del {cursor: pointer}
</style>


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
        navId: "wz-nav-products",
        loading: false
    };

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

  require(['wz/pickPage'], function(vm) {
	
		VM = vm;
		//wzVM = vm.VM;
        vm.UI.showInfo = showInfo;
		vm.UI.showError = showError;

		
		vm.init();
		
		  function showInfo(message, title){
			title = title || "易迅微站";
			toastr.info(message, title);
		  }

		  function showError(message, title){
			title = title || "易迅微站";
			toastr.error(message, title);
		  }
		  
		$("#" + wzUI.navId).addClass("curr");
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
			$(this).find('.dload').css("z-index",5);
		});
	}

</script>


</body>

</html>

