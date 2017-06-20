<?php
/**
 * 分页
 * 
 */
//分页方法自动测定你 URI 的哪个部分包含页数
$config['uri_segment'] = 4;
//放在你当前页码的前面和后面的“数字”链接的数量
$config['num_links'] = 2;
//默认分页URL中是显示每页记录数
$config['use_page_numbers'] = TRUE;
//链接将自动地被用查询字符串重写
$config['page_query_string'] = TRUE;
//把打开的标签放在所有结果的左侧。
$config['full_tag_open'] = '<ul class="am-pagination">';
//把关闭的标签放在所有结果的右侧。
$config['full_tag_close'] = '</ul>';
//在分页的左边显示“第一页”链接的名字。如果你不希望显示，可以把它的值设为 FALSE 。
$config['first_link'] = '1';
//“第一页”链接的打开标签。
$config['first_tag_open'] = '<li class="am-active">';
//“第一页”链接的关闭标签。
$config['first_tag_close'] = '</li> <li><span class="space">...</span></li>';
//在分页的右边显示“最后一页”链接的名字。如果你不希望显示，可以把它的值设为 FALSE 。
$config['last_link'] = '末页';
//“最后一页”链接的打开标签。
$config['last_tag_open'] = '<li><span class="space">...</span></li><li>';
//“最后一页”链接的关闭标签。
$config['last_tag_close'] = '</li>';
//在分页中显示“下一页”链接的名字。如果你不希望显示，可以把它的值设为 FALSE 。
$config['next_link'] = '&gt;';
$config['next_link'] = '下一页';
//“下一页”链接的打开标签。
$config['next_tag_open'] = '<li>';
//“下一页”链接的关闭标签。
$config['next_tag_close'] = '</li>';
//在分页中显示“上一页”链接的名字。如果你不希望显示，可以把它的值设为 FALSE 。
$config['prev_link'] = '&lt;';
$config['prev_link'] ='上一页';
//“上一页”链接的打开标签。
$config['prev_tag_open'] = '<li>';
//“上一页”链接的关闭标签。
$config['prev_tag_close'] = '</li>';
//“当前页”链接的打开标签。
$config['cur_tag_open'] = '<li><span class="active">';
//“当前页”链接的关闭标签。
$config['cur_tag_close'] = '</span></li>';
//“数字”链接的打开标签。
$config['num_tag_open'] = '<li>';
//“数字”链接的关闭标签。
$config['num_tag_close'] = '</li>';
//每页显示几条数据
$config['per_page'] = 10;
//不想显示“数字”链接（比如只显示 “上一页” 和 “下一页”链接）你可以添加如下配置：
//$config['display_pages'] = FALSE;
//给每一个链接添加 CSS 类，你可以添加如下配置：
//$config['anchor_class'] = "";
