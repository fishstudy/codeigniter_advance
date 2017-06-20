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
  <?php
  	if (empty($results)) {
  		echo "website暂无数据";
  	}else {
		foreach($results as $row){
  ?>
	<TR>
		<?php foreach($row as $col){ ?>
			<TD><?php echo $col;?></TD>
		<?php }?>
	</TR>
 <?php }}?>
</TABLE>
 </BODY>
</HTML>
