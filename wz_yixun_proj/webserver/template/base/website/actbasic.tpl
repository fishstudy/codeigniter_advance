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
            <div class="fl conts_right" ms-controller="hotevent_basic">
            <form ms-on-submit="submitForm" action="<#$basedomain#>/website/savebasic" method="post" enctype="multipart/form-data">

                <h2>基本设置</h2>
                <div class="cr_cons inf_ctrl">
                	<div class="wz_logo form-item">
                    <h4><span class="font_red">*</span>LOGO</h4>
                    <div class="webuploader" id="logo-uploader" title="点击上传">
                      <span id="upload-logo"><img id="logo-img" src="<#if $results.actLogo neq ""#>http://img10.360buyimg.com/yixun_zdm/s80x80_<#$results.actLogo#><#else#>data:image/jpg;base64,/9j/4QAYRXhpZgAASUkqAAgAAAAAAAAAAAAAAP/sABFEdWNreQABAAQAAAA8AAD/4QMvaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjUtYzAyMSA3OS4xNTU3NzIsIDIwMTQvMDEvMTMtMTk6NDQ6MDAgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCBDQyAyMDE0IChXaW5kb3dzKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo0Q0VBRUIwRkUxQUQxMUU0OTBEQ0RCOTUzMDM3MThDRiIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDo0Q0VBRUIxMEUxQUQxMUU0OTBEQ0RCOTUzMDM3MThDRiI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjRDRUFFQjBERTFBRDExRTQ5MERDREI5NTMwMzcxOENGIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjRDRUFFQjBFRTFBRDExRTQ5MERDREI5NTMwMzcxOENGIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+/+4AJkFkb2JlAGTAAAAAAQMAFQQDBgoNAAAGFgAABo4AAAfo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<#/if#>" alt="添加图片" title="点击上传" /></span>
                    </div><span class="tip">大小100kb内的jpg、png、gif，尺寸80*80的图片</span>
                    <input type="hidden" name="c[actLogo]" ms-duplex="logoImg" />
                  </div>
                    
                  <div class="wz_banner form-item">
                    <h4><span class="font_red">*</span>背景图</h4>
                    <div class="webuploader" id="banner-uploader" title="点击上传">
                      <span id="upload-banner"><img id="banner-img" src="<#if $results.actPic neq ""#>http://img10.360buyimg.com/yixun_zdm/s640x300_<#$results.actPic#><#else#>data:image/jpg;base64,/9j/4QAYRXhpZgAASUkqAAgAAAAAAAAAAAAAAP/sABFEdWNreQABAAQAAAA8AAD/4QMvaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLwA8P3hwYWNrZXQgYmVnaW49Iu+7vyIgaWQ9Ilc1TTBNcENlaGlIenJlU3pOVGN6a2M5ZCI/PiA8eDp4bXBtZXRhIHhtbG5zOng9ImFkb2JlOm5zOm1ldGEvIiB4OnhtcHRrPSJBZG9iZSBYTVAgQ29yZSA1LjUtYzAyMSA3OS4xNTU3NzIsIDIwMTQvMDEvMTMtMTk6NDQ6MDAgICAgICAgICI+IDxyZGY6UkRGIHhtbG5zOnJkZj0iaHR0cDovL3d3dy53My5vcmcvMTk5OS8wMi8yMi1yZGYtc3ludGF4LW5zIyI+IDxyZGY6RGVzY3JpcHRpb24gcmRmOmFib3V0PSIiIHhtbG5zOnhtcD0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wLyIgeG1sbnM6eG1wTU09Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9tbS8iIHhtbG5zOnN0UmVmPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvc1R5cGUvUmVzb3VyY2VSZWYjIiB4bXA6Q3JlYXRvclRvb2w9IkFkb2JlIFBob3Rvc2hvcCBDQyAyMDE0IChXaW5kb3dzKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo0Q0VBRUIwRkUxQUQxMUU0OTBEQ0RCOTUzMDM3MThDRiIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDo0Q0VBRUIxMEUxQUQxMUU0OTBEQ0RCOTUzMDM3MThDRiI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjRDRUFFQjBERTFBRDExRTQ5MERDREI5NTMwMzcxOENGIiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjRDRUFFQjBFRTFBRDExRTQ5MERDREI5NTMwMzcxOENGIi8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+/+4AJkFkb2JlAGTAAAAAAQMAFQQDBgoNAAAGFgAABo4AAAfo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<#/if#>" title="点击上传" alt="添加图片" /></span>
                    </div><span class="tip">您的热销活动背景图，图片不大于100kb，尺寸：640*300，类型：jpg、png、gif</span>
                    <input type="hidden" name="c[actPic]" ms-duplex="bannerImg" />
                  </div>
                    
                  <div class="wz_infme form-item">
                      <h4>置顶消息</h4>
                      <div class="wz_indet">
                          <span>标题：</span><input type="text" class="ipt_wz_infme"  name="c[actTitle]" ms-duplex="title"  maxlength="50" size="75" placeholder="最多50个字"/>
                      </div>
                  </div>
                    
                  <div class="wz_infme form-item">
                      <h4>优惠券</h4>
                      <div class="wz_indet">
                          <span style="width:110px;">是否在首页展示：</span>
                          <label><input type="radio" class="" ms-duplex-string="isUseCoupon" name="c[actCoupon]" value="1" />是</label>
                          <label><input type="radio" class="" ms-duplex-string="isUseCoupon" name="c[actCoupon]" value="2" />否</label>
                      </div>
                  </div>
                    
                </div>

                <button type="submit" class="btn_sub" ms-attr-disabled="{{isloading}}" value="">提交</button>
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
<style>
.webuploader-container {position: relative;}
.webuploader-element-invisible {position: absolute !important; clip: rect(1px 1px 1px 1px); /* IE6, IE7 */ clip: rect(1px,1px,1px,1px);}
.webuploader-pick {position: relative; display: inline-block; cursor: pointer; color: #fff; text-align: left; border-radius: 0px; font-size: 14px; vertical-align: middle; width: 100%}
.webuploader-pick-hover { background: #fafcff;}
.webuploader-pick-disable { opacity: 0.6; pointer-events:none;}

.form-item {padding: 5px 0}
.form-item input[type="text"], textarea{background-color: #fafcff; border: 1px solid #e3e3e3; padding: 2px 10px;}
#logo-uploader {width: 80px}
#banner-uploader {width: 640px}
#logo-uploader .webuploader-pick {width: 80px; height: 80px; line-height: 80px; text-align: center; vertical-align: top; color: #000;}
#logo-uploader img {margin: 0; display: inline; vertical-align: middle; max-width: 80px; max-height: 80px;  width: auto; height: auto; margin-right: 0}
#banner-uploader .webuploader-pick {width: 640px; height: 300px; line-height: 300px; text-align: center; vertical-align: top; color: #000;}
#banner-uploader img {margin: 0; display: inline; vertical-align: middle; max-width: 640px; max-height: 300px; width: auto; height: auto; margin-right: 0}
button[type="submit"] {display: block; padding: 4px 20px; background: #09f; color: #fff; border: 0 none; margin: 10px auto 20px}
</style>
<script>
function post (form, success, error) {
  $.ajax({
    url: form.attr('action'),
    type: 'POST',
    data: form.serialize(),
    success: success || null,
    error: error || null
  })
}
var myvm;


$(function() {
	 $("#wz-nav-base").addClass("curr");
	
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

function showInfo(message, title){
	title = title || "易迅微站";
	toastr.info(message, title);
}

function showError(message, title){
	title = title || "易迅微站";
	toastr.error(message, title);
}

function htmlDecode ( str ) {
	  var ele = document.createElement('span');
	  ele.innerHTML = str;
	  return ele.textContent;
	}
	
myvm = avalon.define({
  $id: "hotevent_basic",
  logoImg: '<#$results.actLogo#>',
  bannerImg: '<#$results.actPic#>',
  title: htmlDecode('<#$results.actTitle#>'),
  isUseCoupon: '<#$results.actCoupon#>',
  isloading: false,
  submitForm: function (e) {
    myvm.isloading = true
    post($(this), function (d) {
      if (d.err_no === 0) {
        showInfo('设置成功')
		setTimeout(function(){location.reload()},500);
      } else if (d.err_no == 100007) {
		 showInfo("您的账号未登陆，请登陆之后再操作！");
		 setTimeout(function(){		
			location.href = d.login_url;
		 },500);
	  } else {
        showError(d.err_msg)
      }
      myvm.isloading = false
    }, function () {
      myvm.isloading = false
    })
    e.preventDefault()
  }
})



		
		
require(['plugin/webuploader/webupload'], function (webupload) {

	
  var logoUploader = webupload.create({

    // 选完文件后，是否自动上传。
    auto: true,

    // swf文件路径
    //swf: BASE_URL + '/js/Uploader.swf',
    
    
    fileVal: 'uploadfile[]',
    
    // 文件接收服务端。
    server: 'http://upload.yixun.com/uploadimage.php?is_water=n',

    // 选择文件的按钮。可选。
    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
    pick: {id: '#logo-uploader', multiple: false},
    // 只允许选择图片文件。
    accept: {
        title: 'Images',
        extensions: 'jpg,jpeg,png,gif',
        mimeTypes: 'image/*'
    },
    fileSingleSizeLimit: 100 * 1024,
    width: 80,
    height: 80
  }),
  bannerUploader = webupload.create({
    
    // 选完文件后，是否自动上传。
    auto: true,

    // swf文件路径
    //swf: BASE_URL + '/js/Uploader.swf',
    
    
    fileVal: 'uploadfile[]',
    
    // 文件接收服务端。
    server: 'http://upload.yixun.com/uploadimage.php?is_water=n',

    // 选择文件的按钮。可选。
    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
    pick: {id: '#banner-uploader', multiple: false},
    // 只允许选择图片文件。
    accept: {
        title: 'Images',
        extensions: 'jpg,jpeg,png,gif',
        mimeTypes: 'image/*'
    },
    fileSingleSizeLimit: 100 * 1024,
    width: 640,
    height: 300
  })
  
  var tempasrc = '', tempbsrc = ''
  logoUploader.on('fileQueued', function (file) {
    var img = document.getElementById('logo-img')
    logoUploader.makeThumb( file, function( error, src ) {
        //if ( error ) {alert('图片不能预览'); return;}
        img.src = 'http://st.icson.com/static_v1/img/loading18x34.gif'
        tempasrc = src
    }, 80, 80)
  })
  
  bannerUploader.on('fileQueued', function (file) {
    var img = document.getElementById('banner-img')
    bannerUploader.makeThumb( file, function( error, src ) {
        //if ( error ) {alert('图片不能预览'); return;}
        img.src = 'http://st.icson.com/static_v1/img/loading18x34.gif'
        tempbsrc = src
    }, 640, 300)
  })
  
  logoUploader.on('uploadSuccess', function (file, res) {
    if (!res.code && Object.prototype.toString.apply(res).toLowerCase() === '[object array]') {
      myvm.logoImg = res[0].url
      document.getElementById('logo-img').src = "http://img10.360buyimg.com/yixun_zdm/s80x80_"+res[0].url;
    }
    logoUploader.removeFile(file)
  })
  
  bannerUploader.on('uploadSuccess', function (file, res) {
    if (!res.code && Object.prototype.toString.apply(res).toLowerCase() === '[object array]') {
      myvm.bannerImg = res[0].url
      document.getElementById('banner-img').src = "http://img10.360buyimg.com/yixun_zdm/s640x300_"+res[0].url;
    }
    bannerUploader.removeFile(file)
  })
  
  function handleError (type) {
    var str = ''
    if (type === 'Q_TYPE_DENIED') {
      showError('上传出错，文件类型只能是JPG,GIF或PNG')
    } else if (type === 'F_EXCEED_SIZE') {
      showError('上传出错，文件不能超过100Kb')
    }
  }
  
  logoUploader.on('error', handleError)
  bannerUploader.on('error', handleError)
  
})

</script>
</body>
</html>