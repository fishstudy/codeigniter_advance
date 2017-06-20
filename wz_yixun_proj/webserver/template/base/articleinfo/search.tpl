
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
	          <div class="crumb_wrap">
                  当前位置:&nbsp;&nbsp;
                  <a  href="http://www.yixun.com" class="crumb_lk">首页</a>
                  <span class="crumb_arrow">&gt;</span>
                  <a class="crumb_lk" href="http://zdm.yixun.com">易选</a>
                  <span class="crumb_arrow">&gt;</span>
                  <a class="crumb_lk" href="http://base.wz.yixun.com">微站</a>
              </div>
		<div class="main_con clearfix">
            <ul class="fl nav_left">
                <li><i></i><a href="base.wz.yixun.com">微站概况</a></li>
                <li class="bg_2"><i></i>微站装修
                    <p class="nav_s">
                        <ahref="#">资料管理</a>
                    </p>
                </li>
                <li class="bg_3"><i></i>内容管理
                    <p class="nav_s">
                        <a  class="curr" href="#">文章管理</a>
                    </p>
                </li>
            </ul>
			<div class="fl conts_right">
				<h2>文章管理</h2>
				<ul class="c_r_abs clearfix">
					<li class="fl curr">文章列表</li>
				</ul>
				<div class="cr_cons">
					<div class="crc_opt">
						<span>文章类型</span>
						<select id="aticle_type" class="aticle_type">
						{foreach from=$category key=key item=items }
							<option value="{$key}">{$items}</option>
						{/foreach}
						</select>
						<span style="display:none">文章来源</span>
						<select  style="display:none" id="aticle_type" class="aticle_type">
							<option>所有</option>
							<option>原创</option>
							<option>转载</option>
						</select>
						<span>文章搜索</span>
						<input type="text" class="int_search" placeholder="请输入标题、内容关键字"/>
						<input type="button" class="int_submit" value="" />
					</div>
					<table class="crc_list" width="100%" cellpadding="0" cellspacing="0">
						<tr height="57">
							<th width="20"></th>
							<th width="20"><input id="chk_all" type="checkbox"/></th>
							<th width="300">文章标题</th>
							<th width="105">访问量<br /><span>(今天/昨天)</span></th>
							<th width="95">上架时间</th>
							<th width="68">排序</th>
							<th width="">操作</th>
						</tr>
						<tr>
							<td></td>
							<td><input type="checkbox"/></td>
							<td>
								<img class="b_img" src="" alt="" />
								<h4 class="crcl_name"> 标题 </h4>
							</td>
							<td>
								PV <span class="font_red">123</span>/456<br />
								UV <span class="font_red">45</span>/154
							</td>
							<!--
							<td >
								<span class="font_red">4</span>/3
							</td>
							-->
							<td>2015-1-15 22:15:11</td>
							<td>1</td>
							<td class="crcl_opt">
								<a href="javascript:;">删除</a>
								<a href="javascript:;">链接</a>
								<a href="javascript:;">二维码</a>
								<a href="javascript:;">修改标题</a>
								<a href="javascript:;">修改简介</a>
							</td>
						</tr>
						<tr>
							<td colspan="7" height="20">
								<input class="del_all" type="button" value="批量删除" />
								<span class="fr page_count">共xx条，每页xx条</span>
							</td>
						</tr>
					</table>
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

</body>

</html>


 {foreach from=$results key=key item=items}
<tr>
	{foreach from=$items item=item}
	<td>{$item}</td>
	{/foreach}
</tr>
{/foreach}
