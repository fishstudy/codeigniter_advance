
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE8" />
    <meta name="description" content="易迅网" />
    <meta name="keywords" content="易迅网" />
    <title>易迅易选-易迅网</title>
    <link rel="dns-prefetch" href="http://st.icson.com/" />
    <link rel="stylesheet" href="http://static.gtimg.com/icson/css/common/gb.css?t=20140218103358" type="text/css" media="screen" />
    <link rel="stylesheet" type="text/css" href="//st.icson.com/weizhan/desktop/css/channel_weizhan.css">
	<link rel="icon" href="http://www.yixun.com/favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="http://www.yixun.com/favicon.ico" type="image/x-icon" />
	<script type="text/javascript">var timeStat=[];timeStat[0]=new Date();document.domain = 'yixun.com';</script>
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
			<div class="fl conts_right" ms-controller="hotevent_set">
				<h2>基本设置</h2>

					<div class="cr_cons">
						<form id="logo-form" class="crc_list crc_opt crc_form" >
              <h3>LOGO</h3>
              <div class="webuploader logo-box"><div id="upload-logo"><img id="logo-img" alt="user logo" src="" /></div></div>
              <input ms-duplex="logo" type="hidden" name="logourl" />
              <button type="submit">确定</button>
						</form>
            
            <form id="bg-form" class="crc_list crc_opt crc_form" >
              <h3>背景图</h3>
              <div class="webuploader bg-box"><div id="upload-bg"><img id="bg-img" alt="background image" src="" /></div></div>
              <input ms-duplex="bg" type="hidden" name="bgurl" />
              <button type="submit">确定</button>
						</form>
            
            <form id="notification-form" class="crc_list crc_opt crc_form" >
              <h3>置顶消息</h3>
              <label><span>标题</span><input type="text" maxlength="50" /></label>
              <button type="submit">确定</button>
						</form>
            
            <form id="notification-form" class="crc_list crc_opt crc_form" >
              <h3>优惠券</h3>
              是否在首页展示 <label><input type="radio" name="isshown" value="1" />是</label><label><input type="radio" name="isshown" value="0" />否</label>
              <button type="submit">确定</button>
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
<!--#include virtual="/sinclude/jsi/common/header.html"-->
<!--#include virtual="/sinclude/common/utf8/footer.html"-->

<script type="text/javascript">
	timeStat[5] = new Date() - timeStat[0];
</script>
<style>
.webuploader-container {position: relative;}
.webuploader-element-invisible {position: absolute !important; clip: rect(1px 1px 1px 1px); /* IE6, IE7 */ clip: rect(1px,1px,1px,1px);}
.webuploader-pick {position: relative; display: inline-block; cursor: pointer; color: #fff; text-align: left; border-radius: 3px; overflow: hidden; font-size: 14px; vertical-align: middle; width: 100%}
.webuploader-pick-hover { background: #00a2d4;}
.webuploader-pick-disable { opacity: 0.6; pointer-events:none;}

.form-item {padding: 5px 0}
.form-item input[type="text"] {padding: 2px;}
.webuploader-pick {width: 200px; height: 200px; line-height: 200px; text-align: center; vertical-align: top; color: #000; border: 1px solid #bbb}
.webuploader-pick img {display: inline; vertical-align: middle; max-widht: 200px; max-height: 200px}
button[type="submit"] {display: block; padding: 4px 20px; background: #09f; color: #fff; border: 0 none;}
</style>
<script>
var myvm = avalon.define({
    $id: "hotevent_set",
    logo: '',
    bg: ''
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
    pick: {id: '#upload-logo', multiple: false},

    // 只允许选择图片文件。
    accept: {
        title: 'Images',
        extensions: '.*',
        mimeTypes: 'image/*'
    }
  }),
  bgUploader = webupload.create({

    // 选完文件后，是否自动上传。
    auto: true,

    // swf文件路径
    //swf: BASE_URL + '/js/Uploader.swf',
    
    
    fileVal: 'uploadfile[]',
    
    // 文件接收服务端。
    server: 'http://upload.yixun.com/uploadimage.php?is_water=n',

    // 选择文件的按钮。可选。
    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
    pick: {id: '#upload-bg', multiple: false},

    // 只允许选择图片文件。
    accept: {
        title: 'Images',
        extensions: '.*',
        mimeTypes: 'image/*'
    }
  })
  
  logoUploader.on('fileQueued', function (file) {
    var img = document.getElementById('logo-img')
    logoUploader.makeThumb( file, function( error, src ) {
        //if ( error ) {alert('图片不能预览'); return;}
        img.src = 'http://st.icson.com/static_v1/img/loading18x34.gif'
    }, 80, 80)
  })
  
  bgUploader.on('fileQueued', function (file) {
    var img = document.getElementById('bg-img')
    logoUploader.makeThumb( file, function( error, src ) {
        //if ( error ) {alert('图片不能预览'); return;}
        img.src = 'http://st.icson.com/static_v1/img/loading18x34.gif'
    }, 80, 80)
  })
  
  logoUploader.on('uploadSuccess', function (file, res) {
    if (!res.code && Object.prototype.toString.apply(res).toLowerCase() === '[object array]') {
      myvm.logo = res[0].url
    }
    logoUploader.removeFile(file)
  })
  
  bgUploader.on('uploadSuccess', function (file, res) {
    if (!res.code && Object.prototype.toString.apply(res).toLowerCase() === '[object array]') {
      myvm.bg = res[0].url
    }
    bgUploader.removeFile(file)
  })
  
})

</script>
</body>

</html>

