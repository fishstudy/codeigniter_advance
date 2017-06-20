<div class="fl nav_left">
	<ul>
		<li><i></i><a href="/webmaster/index">微站概况</a></li>
		<li class="bg_2"><i></i>微站装修
			<p class="nav_s">
				<a href="/webmaster/edit" id="wz-nav-baseinfo">资料管理</a>
			</p>
		</li>
		<li class="bg_3"><i></i>内容管理
			<div class="nav_s">
				<a  id="wz-nav-articles-catalog" href="/articlekind/search">文章分类</a>
				<div class="nav_t hide" id ="article">
					<#if isset($articlekind.0.kindName)#><a  id="wz-nav-articles-import-<#$articlekind.0.id#>" href="/articleinfo/zdmlist?kindId=<#$articlekind.0.id#>"><#$articlekind.0.kindName#></a><#/if#>
					<#if isset($articlekind.1.kindName)#><a  id="wz-nav-articles-import-<#$articlekind.1.id#>" href="/articleinfo/zdmlist?kindId=<#$articlekind.1.id#>"><#$articlekind.1.kindName#></a><#/if#>
					<#if isset($articlekind.2.kindName)#><a  id="wz-nav-articles-import-<#$articlekind.2.id#>" href="/articleinfo/zdmlist?kindId=<#$articlekind.2.id#>"><#$articlekind.2.kindName#></a><#/if#>
				</div>
				<a  id="wz-nav-articles" href="/articleinfo/articlelist">微站文章</a>
			</div>
		</li>
		<!--
		<li class="bg_3"><i></i>商品管理
			<p class="nav_s">
				<a  id="wz-nav-products" href="/product/search">商品管理</a>
			</p>
		</li> -->
		<li class="bg_4"><i></i>热销活动
			<div class="nav_s">
				<a  id="wz-nav-base" href="/website/actbasic">基本设置</a>
				<a  id="wz-nav-famous" href="/shopproduct/recommandSearch">达人推荐</a>
				<a  id="wz-nav-qiang" href="/shopproduct/qiangSearch">本期抢购</a>
				<a  id="wz-nav-popular" href="/shopcatalog/search">热销爆品</a>
				<div class="nav_t hide" id ="search">
				<#if isset($results.0.catalogName)#><a id="wz-nav-popular-<#$results.0.id#>" href="/shopproduct/hotSearch?catalogId=<#$results.0.id#>"><#$results.0.catalogName#></a><#/if#>
				<#if isset($results.1.catalogName)#><a id="wz-nav-popular-<#$results.1.id#>" href="/shopproduct/hotSearch?catalogId=<#$results.1.id#>"><#$results.1.catalogName#></a><#/if#>
				<#if isset($results.2.catalogName)#><a id="wz-nav-popular-<#$results.2.id#>" href="/shopproduct/hotSearch?catalogId=<#$results.2.id#>"><#$results.2.catalogName#></a><#/if#>
				<#if isset($results.3.catalogName)#><a id="wz-nav-popular-<#$results.3.id#>" href="/shopproduct/hotSearch?catalogId=<#$results.3.id#>"><#$results.3.catalogName#></a><#/if#>
				</div>
			</div>
		</li>
		<li class="bg_3"><i></i>精选商品
			<p class="nav_s">
				<a  id="wz-nav-products" href="/shopproduct/selectionSearch">精选商品</a>
			</p>
		</li>
	</ul>
</div>