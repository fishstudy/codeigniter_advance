
微站内容管理--修改文章的标题和简介
<div>
<form action="http://wd.yixun.com/index.php?d=articleinfo&c=articleinfo&m=savetitle" method="post">
<input type="hidden" name="c[id]" value="<#if $results.id neq ""#><#$results.id#><#/if#>" />
文章标题：<input name="c[title]" value="<#if $results.title neq ""#><#$results.title#><#/if#>" size="100" /><br/><br/>
文章简介：<textarea rows="6" cols="60" name="c[brief]" value="" >
<#if $results.brief neq ""#><#$results.brief#><#/if#></textarea><br/>

<input type="submit" value="提交" />

</form>
</div>

