<#include file="../common/header.tpl" #>

<div class="am-cf admin-main">
	<#include file="../common/left.tpl" #>

	<!-- content start -->
	<div class="admin-content">

    <div class="am-cf am-padding">
      <div class="am-fl am-cf"><strong class="am-text-primary am-text-lg">微站站长</strong> / <small>所有站长列表</small></div>
    </div>

    <div class="am-g">
      <!--<div class="am-u-md-6 am-cf">-->
      <div class="" style="float:left;">
        <form class="am-form" action="" name="searchmaster" method="get" >
        <div class="am-fl am-cf">
          <div class="am-btn-toolbar am-fl">
          <!--
            <div class="am-btn-group am-btn-group-xs">
              <button type="button" class="am-btn am-btn-default"><span class="am-icon-plus"></span> 新增</button>
              <button type="button" class="am-btn am-btn-default"><span class="am-icon-save"></span> 保存</button>
              <button type="button" class="am-btn am-btn-default"><span class="am-icon-archive"></span> 审核</button>
              <button type="button" class="am-btn am-btn-default"><span class="am-icon-trash-o"></span> 删除</button>
            </div>
            -->
            <!--
			<div class="am-btn-group am-btn-group-xs">
              <button type="button" class="am-btn am-btn-default"><span class="am-icon-plus"></span> 所有站长</button>
              <button type="button" class="am-btn am-btn-default"><span class="am-icon-save"></span> 待审核</button>
              <button type="button" class="am-btn am-btn-default"><span class="am-icon-archive"></span> 已生效</button>
              <button type="button" class="am-btn am-btn-default"><span class="am-icon-trash-o"></span> 已拒绝</button>
              <button type="button" class="am-btn am-btn-default"><span class="am-icon-trash-o"></span> 已关闭</button>
            </div>
            -->
            <div class="am-form-group am-margin-left am-fl">
              <select name="conditions">
                <option value="999" selected="selected">所有站长</option>
                <option value="18">待审核</option>
                <option value="1">已生效</option>
                <option value="17">已拒绝</option>
                <option value="19">已关闭</option>
              </select>
            </div>
          </div>
        </div>
      </div>
      <div class="" style="float:left;">
      <!--<div class="am-u-md-3 am-cf">-->
      
        <div class="am-fr">
          <div class="am-input-group am-input-group-sm" style="width:740px;margin-left:20px;">
			名字：<input type="text" name="username" value="" style="width:100px;" />
          	号码：<input type="text" name="mobile" value="" style="width:120px;" />
          	身份证：<input type="text" name="iccard" value="" style="width:180px;" />
          	<!--
            <input type="text" class="am-form-field">
            -->
                <span class="am-input-group-btn">
                  <input class="am-btn am-btn-default" type="button" value="搜索" onclick="checksearch();" />
                </span>
          </div>
        </div>
      </div>
    </div>

    <div class="am-g">
      <div class="am-u-sm-12">
          <table class="am-table am-table-striped am-table-hover table-main">
            <thead>
              <tr>
                <!--<th class="table-check"><input type="checkbox" /></th>-->
                <th class="table-id">ID</th>
                <th class="table-title">用户名</th>
                <th class="table-type">手机号码</th>
                <!--<th class="table-author">作者</th>-->
                <th class="table-title">认证状态</th>
                <th class="table-date">提交时间</th>
                <th class="table-set">操作</th>
              </tr>
          </thead>
          <tbody>
          <#foreach from=$results key=key item=items #>
            <tr>
              <!--<td><input type="checkbox" /></td>-->
              <td><#$key#></td>
              <td><a href="<#$admindomain#>admin/master/detail/<#$items.id#>"><#$items.username#></a></td>
              <td><#$items.mobile#></td>
              <td><#if $items.authentication eq 1#><font color="green">已认证</font>
              	<#elseif $items.authentication eq 2#>
              		<#if $items.conditions eq 17#><font color="red">已拒绝</font>
              		<#elseif $items.conditions eq 18#>待审核
              		<#elseif $items.conditions eq 19#><font color="#999999">已关闭</font>
              		<#/if#>
              	<#/if#></td>
              <td><#$items.createTime#></td>
              <td>
                <div class="am-btn-toolbar">
                  <div class="am-btn-group am-btn-group-xs">
                  <a href="<#$admindomain#>admin/master/detail/<#$items.id#>" class="am-btn am-btn-default am-btn-xs am-text-secondary" >
                  	<span class="am-icon-pencil-square-o"></span> 查看详情</a>
                  <!--
                    <button class="am-btn am-btn-default am-btn-xs am-text-secondary"><span class="am-icon-pencil-square-o"></span> 编辑</button>
                    <button class="am-btn am-btn-default am-btn-xs"><span class="am-icon-copy"></span> 复制</button>
                    <button class="am-btn am-btn-default am-btn-xs am-text-danger"><span class="am-icon-trash-o"></span> 删除</button>
                    -->
                  </div>
                </div>
              </td>
            </tr>
            <#/foreach#>
            
          </tbody>
        </table>
        <hr />
        <div id="pagelist" class="am-cf">
         	共 <#$num#> 条记录
         	<div class="am-fr">
			  <!--<ul class="am-pagination"></ul>-->
			   <#htmlspecialchars_decode($pages)#>
			 
			</div>
		</div>
		<!--
          <div class="am-cf">
			  共 <#$num#> 条记录
			  <div class="am-fr">
			    <ul class="am-pagination">
			      <li class="am-disabled"><a href="#">«</a></li>
			      <li class="am-active"><a href="#">1</a></li>
			      <li><a href="#">2</a></li>
			      <li><a href="#">3</a></li>
			      <li><a href="#">4</a></li>
			      <li><a href="#">5</a></li>
			      <li><a href="#">»</a></li>
			    </ul>
			  </div>
			</div>
		-->	
          <hr />
          <p>注：管理员一期关注待审核列表即可，点击详情页面审核。</p>
        </form>
      </div>

    </div>
  </div>
  <!-- content end -->
</div>

<script type="text/javascript">
	//提交搜索
	function checksearch(){
		var form = document.searchmaster;
		var url = '<#$admindomain#>admin/master/search';
		//alert(url);exit;
		form.action = url;
		form.submit();
	}
</script>

<#include file="../common/footer.tpl" #>