<#include file="../common/header.tpl" #>

<div class="am-cf admin-main">
	<#include file="../common/left.tpl" #>

	<!-- content start -->
  <div class="admin-content">
    <div class="am-cf am-padding">
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">微站</strong> / <small>申请人详细信息</small></div>
    </div>

    <hr/>

    <div class="am-g">
      <div class="am-u-sm-12 am-u-md-4 am-u-md-push-8"></div>

      <div class="am-u-sm-12 am-u-md-8 am-u-md-pull-4">
      
        <form name="detail" action="" class="am-form am-form-horizontal" method="post" >
        	<input type="hidden" name="c[id]" value="<#$results.id#>" id="masterid" />
          <div class="am-form-group">
            <label for="user-name" class="am-u-sm-3 am-form-label">姓名：</label>
            <div class="am-u-sm-9" style="padding-top:8px;">
              <span ><#$results.username#></span>
            </div>
          </div>
		<hr />
          <div class="am-form-group" style="line-height: 30px;">
            <label for="user-phone" class="am-u-sm-3 am-form-label">手机号码：</label>
            <div class="am-u-sm-9" style="padding-top:8px;">
              <span ><#$results.mobile#></span>
            </div>
          </div>
		<hr />
          <div class="am-form-group">
            <label for="user-weibo" class="am-u-sm-3 am-form-label">身份证号码：</label>
            <div class="am-u-sm-9" style="padding-top:8px;">
              <span ><#$results.iccard#></span>
            </div>
          </div>
		<hr />
		  <div class="am-form-group">
            <label for="user-weibo" class="am-u-sm-3 am-form-label">认证状态：</label>
            <div class="am-u-sm-9" style="padding-top:8px;">
              <span ><#if $results.authentication eq 1#><font color="green">已认证</font>
              	<#elseif $results.authentication eq 2#>
              		<#if $results.conditions eq 17#><font color="red">已拒绝</font>
              		<#elseif $results.conditions eq 18#>待审核
              		<#elseif $results.conditions eq 19#><font color="#999999">已关闭</font>
              		<#/if#>
              	<#/if#></span>
            </div>
          </div>
		<hr />
		
		<#if $results.authentication eq 2 && $results.conditions eq 17#>
		<div class="am-form-group">
            <label for="user-weibo" class="am-u-sm-3 am-form-label">拒绝原因：</label>
            <div class="am-u-sm-9" style="padding-top:8px;">
              <span ><#$results.rejection#></span>
            </div>
          </div>
		<hr />
        <#/if#>
        
        <#if $results.authentication eq 2 && $results.conditions eq 19#>
		<div class="am-form-group">
            <label for="user-weibo" class="am-u-sm-3 am-form-label">关闭原因：</label>
            <div class="am-u-sm-9" style="padding-top:8px;">
              <span ><#$results.closereason#></span>
            </div>
          </div>
		<hr />
        <#/if#>
        
		<#if $results.authentication eq 2 && $results.conditions eq 18#>
		  <div class="am-form-group">
            <label for="rejection" class="am-u-sm-3 am-form-label">拒绝原因：</label>
            <div class="am-u-sm-9">
              <textarea name="c[rejection]" class="" rows="8" id="rejection" placeholder="请输入拒绝原因，通过不要填写"></textarea>
              <small><font color="red">注：审核通过时，此处不要填写；拒绝时请务必写明原因，在256个字符之内。</font></small>
            </div>
          </div>
          <#/if#>
          
          <#if $results.authentication eq 1#>
          <div class="am-form-group">
            <label for="close" class="am-u-sm-3 am-form-label">关闭原因：</label>
            <div class="am-u-sm-9">
              <textarea id="closereason" name="c[closereason]" class="" rows="8" id="" placeholder="请输入关闭原因"></textarea>
              <small><font color="red">注：管理员关闭微站时请务必写明原因，在256个字符之内。</font></small>
            </div>
          </div>
          <#/if#>
          
          <div class="am-form-group">
            <div class="am-u-sm-9 am-u-sm-push-3">
            <!--已通过的微站只能关闭 ；未认证的可通过或拒绝-->
            <#if $results.authentication eq 2#>
              <input type="button" class="am-btn am-btn-primary" onclick="pass();" value="通过" />
              <#if $results.conditions eq 18#>
              <input type="button" class="am-btn am-btn-primary" style="margin-left:10px;" onclick="checkrefuse();" value="拒绝" />
              <#/if#>
            <#/if#>
              <!--<button type="button" class="am-btn am-btn-primary" onclick="pass();">通过</button>-->
              <!--<input type="button" class="am-btn am-btn-primary" style="margin-left:10px;" onclick="checkrefuse();" value="拒绝" />-->
            <!--已通过的微站只能关闭 -->
            <#if $results.authentication eq 1#>
              <input type="button" class="am-btn am-btn-primary" style="margin-left:10px;" onclick="checkclose();" value="关闭" />
            <#/if#>
              <input type="button" name="Submit" style="margin-left:10px;" onclick="javascript:history.back(-1);" value="返回上页" class="am-btn am-btn-primary" />
            </div>
          </div>
          
        </form>
      </div>
    </div>
    <hr />
    <div style="margin-left:20px;"><p><font color="red">注：已认证的微站，管理员可关闭微站；未认证的微站，管理员可通过、拒绝认证。</font></p></div>
  </div>
  <!-- content end -->
	
</div>
<script type="text/javascript">
	//通过
	function pass(){
		var form = document.detail;
		var url = '<#$admindomain#>admin/master/pass';
		var rejection = document.getElementById('rejection');
		if( rejection && rejection.value != "") {
			alert("通过时请不要在拒绝原因里填写内容，谢谢！");
			return false;
		} 
		form.action = url;
		form.submit();
	}
	//拒绝
	function checkrefuse(){
		var form = document.detail;
		var rejection = document.getElementById('rejection').value;
		var url = '<#$admindomain#>admin/master/refuse';
		if( !rejection || rejection.length>256) {
			alert("请填写拒绝原因，在256个字符之内！");
			return false;
		}
		form.action = url;
		form.submit();
	}
	//关闭
	function checkclose(){
		var form = document.detail;
		var closereason = document.getElementById('closereason').value;
		var url = '<#$admindomain#>admin/master/close';
		if( !closereason || closereason.length>256) {
			alert("请填写关闭原因，在256个字符之内！");
			return false;
		}
		form.action = url;
		form.submit();
	}
	
</script>

<#include file="../common/footer.tpl" #>
