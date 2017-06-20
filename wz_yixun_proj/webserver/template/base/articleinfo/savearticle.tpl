
 <BODY>
<TABLE border=1 width=500>
微站从值得买导入文章详情：<#$err_msg#>
	<tr><td>微站文章ID</td><td><#if $id neq ""#><#$id#><#/if#></td></tr>
 	<tr><td>值得买文章ID</td><td><#if $results.art_id neq ""#><#$results.art_id#><#/if#></td></tr>
 	<tr><td>文章加密字符串</td><td><#if $results.id neq ""#><#$results.id#><#/if#></td></tr>
 	<tr><td>文章标题</td><td><#if $results.title neq ""#><#$results.title#><#/if#></td></tr>
 	<tr><td>去样式的标题，仅文字</td><td><#if $results.title_text neq ""#><#$results.title_text#><#/if#></td></tr>
 	<tr><td>文章作者</td><td><#if $results.author neq ""#><#$results.author#><#/if#></td></tr>
 	<tr><td>文章作者id</td><td><#if $results.author_id neq ""#><#$results.author_id#><#/if#></td></tr>
 	<tr><td>作者用户中心地址</td><td><#if $results.author_center_url neq ""#><#$results.author_center_url#><#/if#></td></tr>
 	<tr><td>文章创建时间</td><td><#if $results.create_time neq ""#><#$results.create_time#><#/if#></td></tr>
 	<tr><td>文章阅读数</td><td><#if $results.read_num neq ""#><#$results.read_num#><#/if#></td></tr>
 	<tr><td>文章点赞数</td><td><#if $results.good_num neq ""#><#$results.good_num#><#/if#></td></tr>
	<tr><td>文章内容</td><td><#if $results.content neq ""#><#$results.content#><#/if#></td></tr>
	
</TABLE>
 </BODY>
