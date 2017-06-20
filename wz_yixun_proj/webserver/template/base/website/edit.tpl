
微站首页装修--设置菜单文字
<div>
<form action="http://wd.yixun.com/index.php?d=public&c=website&m=savewebsite" method="post">
<input type="hidden" name="c[id]" value="{if $results.id neq ""}{$results.id}{/if}" />
我的文章：<input name="c[articleList]" value="{if $results.articleList neq ""}{$results.articleList}{/if}" /><br/>
我的商品：<input name="c[productList]" value="{if $results.productList neq ""}{$results.productList}{/if}" /><br/>
活动：<input name="c[actList]" value="{if $results.actList neq ""}{$results.actList}{/if}" /><br/>
<input type="submit" value="修改" />
</form>
</div>

