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
<div class="ic_content ms-controller" id="container"  ms-controller="wz-articles-import">
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
                    <h2>导入文章-<#$kindName#></h2> 
                    <div class="cr_cons" id="wz_datacontent">
                        <div class="crc_opt">
                            <span>文章类型</span>
                            <select id="aticle_type" class="aticle_type" ms-duplex="searchCategory" value="n" >
                                <#foreach from=$category key=key item=items #>
                                <option value="<#$key#>"><#$items#></option>
                                <#/foreach#>
                            </select>
                            <span>文章搜索</span>
                            <input type="text" class="int_search" placeholder="请输入文章ID" ms-duplex="searchKeywords"/>
                            <input type="button" class="int_submit" value="" ms-on-click="search" />
							<a class="btn fr" ms-attr-href="/articleinfo/articlelist?kindId={{oriCatalog}}" >返回自选列表</a>
                        </div>
                        <div id="data_articles" class="crc_list">
                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr height="57">
                                    <th width="20"></th>
                                    <th width="20"><input id="chk_all" type="hidden" ms-duplex-checked="allChecked" data-duplex-changed="checkAll"/></th>
                                    <th width="220">文章标题</th>
                                    <th width="150">描述</th>
                                    <th width="100">作者</th>
                                    <th width="50">上架时间</th>
                                    <th width="50">操作</th>
                                </tr>
                                <tr ms-repeat-item="articles" ms-data-article-id="item.id">
                                    <td>
										<span style="display:none" class="J_id">{{item.id}}</span>
                                    </td>
                                    <td><input type="hidden" ms-duplex-checked="item.ui_checked" ms-data-index=$index data-duplex-changed="checkOne"/></td>
                                    <td>
                                        <a class="crcl_name" ms-href="http://zdm.yixun.com/detail/{{item.id}}" target="_blank">{{item.title_text}}</a>
                                    </td>
                                    <td>
                                        <span>{{item.descript}}</span>
                                    </td>
                                    <td>{{item.author}}</td>
									<td>{{item.time}}</td>
									<td ms-visible="item.isIn">
                                       已导入
                                    </td>
                                    <td class="crcl_opt J_actions" ms-visible="!item.isIn" >
                                        <a href="javascript:void(0);" class="J_add" ms-data-id="item.id" ms-data-index=$index>导入</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="7" height="20">
                                        <input class="del_all" type="hidden" value="批量导入" ms-visible="articles.length" ms-on-click=""/>
										<span class="wz_search_info" ms-visible="!articles.length">没有文章可以显示</span>
                                        <span class="fr page_count">每页10条</span>
                                        <div class="fr page_count" ms-widget="pager,articlesPager,pages"></div>
                                    </td>
                                </tr>
                            </table>
                        </div>                        
                    </div>
                </div>
        </div>
    </div>
</div>


<div class="modal" id="wz_modal_order" ms-controller="wz_modal_order" style="display:none;">
  <h3>修改文章排序</h3>	
  <span class="J_id" style="display:none" ms-duplex="articleId" ></span>
  <p><label for="modal_edit_order">排序:</label><input type="text" id="modal_edit_order" ms-duplex="orderNum" /></p>
  <p>
  <button id="modal_order_ok" ms-on-click="updateOrder" ms-attr-disabled="isLoading" >{{buttonText}}</button>
  <span ms-visible="statusText" style="color: red;">{{statusText}}</span>
  <p>
</div>

<div class="modal" id="wz_modal_edit"  ms-controller="wz_modal_edit" style="display:none;">
  <h3>修改文章</h3>	
  <span class="J_id" style="display:none" ms-duplex="articleId" ></span>
  <p><label for="modal_edit_title">标题:</label><input type="text" id="modal_edit_title" ms-duplex="title"  max-length="20" /></p>
  <p><label for="modal_edit_summary">简介:</label><textarea id="modal_edit_summary" ms-duplex="brief"  max-length="40" /></textarea></p>
  <p>
	<button id="modal_edit_ok" ms-on-click="updateTitle" ms-attr-disabled="isLoading" >{{buttonText}}</button>
	<span ms-visible="statusText" style="color: red;">{{statusText}}</span>
  <p>
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

<script>
var block = false;
$(document).on('click', '#ceshib', function() {

	if(block){
		$('#wz_datacontent').unblock(); 
		block = false;
	} else {
		$('#wz_datacontent').block({ message: "加载中" }); 
		block = true;
	}
	
});

</script>


<script type="text/javascript">
    timeStat[5] = new Date() - timeStat[0];
</script>

<script>
    $(document).ready(function(){

    });
</script>

<script>

</script>

<script type="text/javascript">

    avalon.config({
        loader: false,
        debug: true
    });

    var wzUI = {
        navId: "wz-nav-articles-import",
		loading: false
    };

    //var wzVM;
	var VM;
    require(["wz/importArticlesPage","wz/navBar"], function(vm,nav) {
        VM = vm;
		//wzVM = vm.VM;
        vm.init();
		showLoading();
        vm.UI.showInfo = showInfo;
		vm.UI.showError = showError;
		
		vm.articleVM.$watch("isLoading", function(value, oldValue) {
			if(value) {
				showLoading();
			} else {
				hideLoading();
			}			
		});
			
		//navBar
		//nav.showChildren("article");
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


	 $("#data_articles tbody" ).on("click", ".J_actions .J_add", function() {
        VM.articleVM.addArticle($(this));
    });
	
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

