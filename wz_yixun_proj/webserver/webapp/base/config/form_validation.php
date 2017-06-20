<?php
$config = array(
       'shopproduct/recommandSave' => array(

                array(
                    'field' => 'c[0][shopproduct][info]',
                    'label' => '商品说明A',
                    'rules' => 'max_length[50]',
                ),
                array(
                    'field' => 'c[1][shopproduct][info]',
                    'label' => '商品说明B',
                    'rules' => 'max_length[50]',
                ),
                array(
                    'field' => 'c[0][shopproduct][pId]',
                    'label' => '商品A编号',
                    'rules' => 'required|numeric|max_length[15]',
                ),
               array(
                    'field' => 'c[1][shopproduct][pId]',
                    'label' => '商品B编号',
                    'rules' => 'required|numeric|max_length[15]',
                ),
       ),
      'shopproduct/qiangSave' => array(
               array(
                    'field' => 'c[shopproduct][pId]',
                    'label' => '商品编号',
                    'rules' => 'required|numeric|max_length[15]',
                ),
                array(
                    'field' => 'c[shopproduct][title]',
                    'label' => '商品标题',
                    'rules' => 'max_length[30]',
                ),
       ),
      'shopproduct/hotSave' => array(
               array(
                    'field' => 'c[shopproduct][pId]',
                    'label' => '商品编号',
                    'rules' => 'required|numeric|max_length[15]',
                ),
                array(
                    'field' => 'c[shopproduct][scId]',
                    'label' => '类目id',
                    'rules' => 'required|numeric',
                ),
       ),
      'shopproduct/selectionSave' => array(
               array(
                    'field' => 'c[shopproduct][pId]',
                    'label' => '商品编号',
                    'rules' => 'required|numeric|max_length[15]',
                ),
                array(
                    'field' => 'c[shopproduct][info]',
                    'label' => '商品信息',
                    'rules' => 'required|max_length[50]',
                ),
                array(
                    'field' => 'c[shopproduct][pic]',
                    'label' => '商品图片',
                    'rules' => 'required',
                ),
       ),
      'shopcatalog/save' => array(
               array(
                    'field' => 'c[0][shopcatalog][catalogName]',
                    'label' => '类目1',
                    'rules' => 'required|max_length[10]',
                ),
                array(
                    'field' => 'c[1][shopcatalog][catalogName]',
                    'label' => '类目2',
                    'rules' => 'required|max_length[10]',
                ),
                array(
                    'field' => 'c[2][shopcatalog][catalogName]',
                    'label' => '类目3',
                    'rules' => 'required|max_length[10]',
                ),
                array(
                    'field' => 'c[3][shopcatalog][catalogName]',
                    'label' => '类目4',
                    'rules' => 'required|max_length[10]',
                ),          
       ),
      'articlekind/save' => array(
               array(
                    'field' => 'c[0][articlekind][kindName]',
                    'label' => '文章列表1',
                    'rules' => 'required|max_length[10]',
                ),
                array(
                    'field' => 'c[1][articlekind][kindName]',
                    'label' => '文章列表2',
                    'rules' => 'required|max_length[10]',
                ),
                array(
                    'field' => 'c[2][articlekind][kindName]',
                    'label' => '文章列表3',
                    'rules' => 'required|max_length[10]',
                ),       
       ),

);
