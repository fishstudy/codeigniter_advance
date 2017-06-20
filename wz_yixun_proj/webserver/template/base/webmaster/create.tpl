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
				<!--STRAT fl conts_right  -->
                <div class="conts_right apply_step">
					
					<h2>微站开通申请</h2>

					<!-- 第一个步骤 -->
					<div class="a_step as_1">					
					</div>
					<form action="<#$basedomain#>/webmaster/save" method="post"  ms-controller="wz_create"  ms-widget="validation" id="wz_create" >
						<div class="apply_info">
							<p class="J-apply-item">
								<label>您的姓名：</label>
								<!--
								<input ms-duplex-require-minlength-chs="username" data-duplex-event="change"  data-duplex-minlength="2"  data-duplex-message="必填，而且长度在2-8个汉字" name="c[username]" type="text" placeholder="请填写真实姓名" max-length="8" />
								-->
								<input ms-duplex-require-minlength-chs="username"   data-duplex-minlength="2"  name="c[username]" type="text" placeholder="请填写真实姓名" maxlength="8" />
								<span>*</span>
								<span class="label label-warning J-error-tip" style="display:none">请填写2-8个汉字</span>
							</p>
							<p class="J-apply-item">
								<label>身份证号：</label>	
														
								<input ms-duplex-require-id="iccard" name="c[iccard]" type="text" placeholder="请填写您的有效身份证号码"  maxlength="20" />
								
								<span>*</span>
								<span class="label label-warning J-error-tip" style="display:none">身份证号码无效</span>
							</p>
							<p class="J-apply-item">
								<label>手机号码：</label>
								
								<input ms-duplex-require-phone="mobile" name="c[mobile]" ms-input="checkStatus" type="text" placeholder="请填写真实手机号码，便于审核" maxlength="11" />
								
								<span>*</span>
								<span class="label label-warning J-error-tip" style="display:none">手机号码无效</span>
							</p>
							<p style="display:none">
								<label>短信验证码：</label>
								<input class="ipt_short" type="text" />
								<span class="get_scode">获取</span>
							</p>
							<p>
							
							</p>
						</div>
						<div class="apply_b">
							客服将在24小时内审核您的个人资料并电话联系您确认开通微站，请等待审核结果……
							<button class="apply_go" type="submit" ms-attr-disabled="isInvalid">立即申请</button>
						</div>
					<form>
                </div
				<!--END fl conts_right  -->
        </div>
    </div>
</div>


<!-- 主体部分 end -->

<script type="text/javascript" src="http://st.icson.com/vendor/jquery/jquery-1.11.2.min.js"></script>
<script type="text/javascript" src="http://st.icson.com/vendor/jquery/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="http://st.icson.com/static_v1/js/global.js" charset="gb2312"></script>

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

    }

   
	
	
</script>

<script>
	
	require(["avalon/validation/avalon.validation"], function() {
		var validationVM
		
		/*
		function showError(el, data) {
			var next = el.nextSibling
			if (!(next && next.className === "error-tip")) {
				next = document.createElement("div")
				next.className = "error-tip"
				el.parentNode.appendChild(next)
			}
			next.innerHTML = data.getMessage()
		}
		function removeError(el) {
			var next = el.nextSibling
			while (next) {
				if (next.className === "error-tip") {
					el.parentNode.removeChild(next)
					break
				}
				 next = next.nextSibling
			}
		}
		*/
		
		function showError(el, data) {
			$(el).closest(".J-apply-item").find(".J-error-tip").show();		
		}
		function removeError(el) {
			$(el).closest(".J-apply-item").find(".J-error-tip").hide();
		}
		
		function submitForm(el) {
			$("#wz_create").submit();
		}
		
		var model = avalon.define({
			$id: "wz_create",
			$skipArray: ["validation"],
			
			username: "",
			iccard: "",
			mobile: "",
			
			isInvalid: false,
			
			checkStatus: function() {
				//console.log("checkStatus");
			},
		
			validation: {
				onInit: function(v) {
					validationVM = v
					//model.isInvalid = true;
				},
				onReset: function() {
					avalon(this).removeClass("error success")
					removeError(this)
				},
				onError: function(reasons) {
					reasons.forEach(function(reason) {
						avalon(this).removeClass("success").addClass("error")
						showError(this, reason)
					}, this)
				},
				onSuccess: function() {
					avalon(this).removeClass("error").addClass("success")
					removeError(this)
				},
				validateInKeyup: true,
				onValidateAll: function(reasons) {
					reasons.forEach(function(reason) {
						avalon(reason.element).removeClass("success").addClass("error")
						showError(reason.element, reason)
					})
					if (reasons.length === 0) {
						model.statusText = "全部验证成功."
						//model.isInvalid = false;
						submitForm();
					} else {
						//model.isInvalid = true;
						model.statusText = "提交的数据不符合格式, 请修改后再提交."
					}
				}
			},
			
	
			statusText: ""
		})
		
		avalon.scan()
	})

</script>

</body>
</html>

