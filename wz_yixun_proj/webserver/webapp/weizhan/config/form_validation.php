<?php
$config = array(
       'articles/set' => array(
               array(
                    'field' => 'c[article][id]',
                    'label' => '文章id',
                    'rules' => 'required|numeric',
                ),
                array(
                    'field' => 'c[article][status]', 
                    'label' => '修改文章状态',
                    'rules' => 'required|numeric',
                ),
                
       ),
       'categories/save' => array(
                array(
                    'field' => 'c[category][name]',
                    'label' => '栏目名称',
                    'rules' => 'required|max_length[128]',
                ),
                array(
                    'field' => 'c[category][order_num]',
                    'label' => '栏目排序',
                    'rules' => 'required|numeric|greater_than[0]|less_than[51]',
                ),
       ),
       'categories/set' => array(
                array(
                    'filed' => 'c[category][id]',
                    'label' => '栏目id',
                    'rules' => 'required|numeric',
                ),
                array(
                    'filed' => 'c[category][is_deleted]',
                    'label' => '是否删除',
                    'rules' => 'required|numeric',
                ),
       ),
       'periodicals/save' => array(
                array(
                    'field' => 'c[periodical][published_date]',
                    'label' => '推送日期',
                    'rules' => 'required',
                ),
                array(
                    'field' => 'c[periodical][published_time]',
                    'label' => '推送时间',
                    'rules' => 'required',
                ),
       ),
       'periodicals/set' => array(
                array(
                    'field' => 'c[periodical][id]',
                    'label' => '推送计划',
                    'rules' => 'required|numeric',
                ),
                array(
                    'field' => 'c[periodical][published_time]',
                    'label' => '推送时间',
                    'rules' => 'required',
                ),
                array(
                    'field' => 'c[periodical][published_date]',
                    'label' => '推送日期',
                    'rules' => 'required',
                ),
                array(
                    'field' => 'c[periodical][status]',
                    'label' => '推送状态',
                    'rules' => 'required|numeric',
                ),
       ),
       'articles/save' => array(
                array(
                    'field' => 'c[article][category_id]',
                    'label' => '栏目',
                    'rules' => 'required',
                ),
                array(
                    'field' => 'c[article][author]',
                    'label' => '作者/来源',
                    'rules' => 'required',
                ),
                array(
                    'field' => 'c[article][showed_time]',
                    'label' => '日期',
                    'rules' => 'required',
                ),
                 array(
                    'field' => 'c[article][title]',
                    'label' => '标题',
                    'rules' => 'required',
                ),
                 array(
                    'field' => 'c[article][content]',
                    'label' => '正文',
                    'rules' => 'required',
                ),
                 array(
                    'field' => 'c[article][kind]',
                    'label' => '是否需要猎头贴标签',
                    'rules' => 'required',
                ),
       ),
       'persons/save' => array(
            array(
                'field' => 'c[person][name]',
                'label' => '姓名填写',
                'rules' => 'min_length[2]|max_length[50]',
            ),
            array(
                'field' => 'c[person][birth_years]',
                'label' => '出生年',
                'rules' => 'regex_match[/\d{4}/]',
            ),
            array(
                'field' => 'c[person][birth_months]',
                'label' => '出生月',
                'rules' => 'regex_match[/\d{1,2}/]',
            ),
            array(
                'field' => 'c[person][description]',
                'label' => '个人描述',
                'rules' => '',
            ),
       
       ),

);
