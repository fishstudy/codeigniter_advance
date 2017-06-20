<#include file="../common/header.tpl" #>

<div class="am-cf admin-main">
	<#include file="../common/left.tpl" #>

	<!-- content start -->
  <div class="admin-content">
    <div class="am-cf am-padding">
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">微站</strong> / <small>微站站长详细信息</small></div>
    </div>

    <hr/>

    <div class="am-g">
      <div class="am-u-sm-12 am-u-md-4 am-u-md-push-8"></div>

      <div class="am-u-sm-12 am-u-md-8 am-u-md-pull-4">
      
        <form name="detail" action="" class="am-form am-form-horizontal" method="post" >
        	<input type="hidden" name="c[msid]" value="<#if $results.id neq ""#><#$results.id#><#/if#>" id="msid" />
        	<input type="hidden" name="c[id]" value="<#if $results.wzid neq ""#><#$results.wzid#><#/if#>" id="wzid" />
        	<input type="hidden" name="c[pid]" value="<#if $results.product neq ""#><#$results.product.id#><#/if#>" />
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
		
          <div class="am-form-group">
            <label for="close" class="am-u-sm-3 am-form-label">投放ID：</label>
            <div class="am-u-sm-9">
              <input id="tfid" name="c[tfid]" placeholder="请输入投放ID" maxlength="20" width="40" />
              &nbsp;&nbsp;<#if $results.product neq "" #>已设置的投放ID为<#$results.product.tfId#><#else#>暂未设置投放ID<#/if#>
              <small><font color="red"></font></small>
            </div>
          </div>
        <hr />
        
          <div class="am-form-group">
            <div class="am-u-sm-9 am-u-sm-push-3">
              <input type="button" class="am-btn am-btn-primary" style="margin-left:10px;" onclick="checksubmit();" value="提交" />
              <input type="button" name="Submit" style="margin-left:10px;" onclick="javascript:history.back(-1);" value="返回上页" class="am-btn am-btn-primary" />
            </div>
          </div>
          
        </form>
      </div>
    </div>
    <hr />
    <div style="margin-left:20px;"><p><font color="red">注：已认证的微站，管理员可设置投放ID，投放ID长度在20个字符之内。</font></p></div>
  </div>
  <!-- content end -->
	
</div>
<script type="text/javascript">
	//
	function checksubmit(){
		var form = document.detail;
		var tfid = document.getElementById('tfid').value;
		var url = '<#$admindomain#>admin/master/saveproduct';
		if( !tfid || tfid.length>20) {
			alert("请填写投放ID，在20个字符之内！");
			return false;
		}
		form.action = url;
		form.submit();
	}
	
</script>

<#include file="../common/footer.tpl" #>
