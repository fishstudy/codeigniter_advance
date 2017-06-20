
微站首页装修--设置文章列表模版

<div>
<form action="http://wd.yixun.com/index.php?d=public&c=website&m=savetemplate" method="post" enctype="multipart/form-data">
<input type="hidden" name="c[id]" value="{if $results.id neq ""}{$results.id}{/if}" />

请选择一种文章列表模版：<br/>
大带小图文列表1<input type="radio" name="c[articleModel]" value="1" {if $results.articleModel eq 1}checked="checked"{/if} /><br/>
大带小图文列表2<input type="radio" name="c[articleModel]" value="2" {if $results.articleModel eq 2}checked="checked"{/if} /><br/>
大图文列表<input type="radio" name="c[articleModel]" value="3" {if $results.articleModel eq 3}checked="checked"{/if} /><br/>
小图文列表<input type="radio" name="c[articleModel]" value="4" {if $results.articleModel eq 4}checked="checked"{/if} /><br/>
纯文字列表<input type="radio" name="c[articleModel]" value="5" {if $results.articleModel eq 5}checked="checked"{/if} /><br/>
<input type="submit" value="提交" /><br/>

</form>
</div>

