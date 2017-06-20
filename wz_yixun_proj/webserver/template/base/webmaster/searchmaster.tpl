<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
 <HEAD>
  <TITLE> this is a template test </TITLE>
  <META NAME="Generator" CONTENT="EditPlus">
  <META NAME="Author" CONTENT="">
  <META NAME="Keywords" CONTENT="">
  <META NAME="Description" CONTENT="">
 </HEAD>
 <BODY>
<TABLE border=1 width=500>
 admin后台，搜索得到的站长列表
 {foreach from=$results key=key item=items }
 	<tr>
 	{foreach from=$items key=key item=item}
 	<td>键:{$key}-值:{$item}</td>
 	{/foreach}
 	</tr>
 {/foreach}

</TABLE>
总数：{$num}
 </BODY>
</HTML>
