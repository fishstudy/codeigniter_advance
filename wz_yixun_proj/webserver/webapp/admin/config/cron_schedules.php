<?php

// 定时处理历史数据
$cron_schedule['dowith_history'] = array(
    'schedule'  => array(
        'config_path' => '',            // cron表达式的标识 用于在配置文件或数据库中获取表达式 直接指定时为空
        'cron_expr'   => '42 4 * * *',  // 直接指定cron表达式 在配置文件或数据库中获取表达式为空
    ),
    'run'       => array(
        'filepath'  => 'tools/crontab',          // 文件所在的目录 相对于APPPATH
        'filename'  => 'dowith_history.php',   // 文件名
        'class'     => 'DowithHistory',       // 类名 如果只是简单函数 可为空
        'function'  => 'execute',     // 要执行的函数
        'params'    =>   FALSE,         // 需要传递的参数
    )
);

/***********************定期修改文章和推送计划的状态********************/
$cron_schedule['change_article'] = array(
    'schedule'  => array(
        'config_path' => '',            // cron表达式的标识 用于在配置文件或数据库中获取表达式 直接指定时为空
        'cron_expr'   => '*/10 6-23 * * *',  // 直接指定cron表达式 在配置文件或数据库中获取表达式为空
    ),
    'run'       => array(
        'filepath'  => 'tools/crontab',          // 文件所在的目录 相对于APPPATH
        'filename'  => 'change_article.php',   // 文件名
        'class'     => 'ChangeArticle',       // 类名 如果只是简单函数 可为空
        'function'  => 'execute',     // 要执行的函数
        'params'    =>   FALSE,         // 需要传递的参数
    )
);
/*********************定时跑增量数据或全量数据*****************************/
/*$cron_schedule['export_all'] = array(
    'schedule'  => array(
        'config_path' => '',            // cron表达式的标识 用于在配置文件或数据库中获取表达式 直接指定时为空
        'cron_expr'   => '30 3 * * *',  // 直接指定cron表达式 在配置文件或数据库中获取表达式为空
    ),
    'run'       => array(
        'filepath'  => 'tools/export/',          // 文件所在的目录 相对于APPPATH
        'filename'  => 'exportor.php',   // 文件名
        'class'     => 'Exportor',       // 类名 如果只是简单函数 可为空
        'function'  => 'execute',     // 要执行的函数
        'params'    =>   FALSE,         // 需要传递的参数 全量数据
    )
);*/

// $cron_schedule['export_inc'] = array(
//     'schedule'  => array(
//         'config_path' => '',            // cron表达式的标识 用于在配置文件或数据库中获取表达式 直接指定时为空
//         'cron_expr'   => '*/3 8-23 * * *'// 直接指定cron表达式 在配置文件或数据库中获取表达式为空
//     ),
//     'run'       => array(
//         'filepath'  => 'tools/export/',          // 文件所在的目录 相对于APPPATH
//         'filename'  => 'exportor.php',   // 文件名
//         'class'     => 'Exportor',       // 类名 如果只是简单函数 可为空
//         'function'  => 'execute',     // 要执行的函数
//         'params'    =>  date('Y-m-d H:i:s', time()-180) ,         // 需要传递的参数 增量数据
//     )
// );

// 定时跑文章给到贴标签
// $cron_schedule['article_libraries'] = array(
//     'schedule'  => array(
//         'config_path' => '',            // cron表达式的标识 用于在配置文件或数据库中获取表达式 直接指定时为空
//         'cron_expr'   => '*/3 * * * *'// 直接指定cron表达式 在配置文件或数据库中获取表达式为空
//     ),
//     'run'       => array(
//         'filepath'  => 'tools/export/',          // 文件所在的目录 相对于APPPATH
//         'filename'  => 'exportor.php',   // 文件名
//         'class'     => 'Exportor',       // 类名 如果只是简单函数 可为空
//         'function'  => 'execute',     // 要执行的函数
//         'params'    =>  date('Y-m-d H:i:s', time()-180) ,         // 需要传递的参数 增量数据
//     )
// );

// 定时发送文章
$cron_schedule['auto_send'] = array(
    'schedule'  => array(
        'config_path' => '',            // cron表达式的标识 用于在配置文件或数据库中获取表达式 直接指定时为空
        'cron_expr'   => '23,35,53 * * * *'// 直接指定cron表达式 在配置文件或数据库中获取表达式为空
    ),
    'run'       => array(
        'filepath'  => 'tools/export/',          // 文件所在的目录 相对于APPPATH
        'filename'  => 'auto_send.php',   // 文件名
        'class'     => 'AutoSend',       // 类名 如果只是简单函数 可为空
        'function'  => 'execute',     // 要执行的函数
        'params'    =>  date('Y-m-d H:i:s', time()-900) ,         // 需要传递的参数 增量数据
    )
);

// 定时跑程序存储图片，排版等
$cron_schedule['auto_draft'] = array(
    'schedule'  => array(
        'config_path' => '',            // cron表达式的标识 用于在配置文件或数据库中获取表达式 直接指定时为空
        'cron_expr'   => '1,10,20,30,40 * * * *'// 直接指定cron表达式 在配置文件或数据库中获取表达式为空
    ),
    'run'       => array(
        'filepath'  => 'tools/export/',          // 文件所在的目录 相对于APPPATH
        'filename'  => 'auto_draft.php',   // 文件名
        'class'     => 'AutoDraft',       // 类名 如果只是简单函数 可为空
        'function'  => 'execute',     // 要执行的函数
        'params'    =>  false,         // 需要传递的参数 增量数据
    )
);

// 定时发送文章
$cron_schedule['statistics'] = array(
                'schedule'  => array(
                                'config_path' => '',            // cron表达式的标识 用于在配置文件或数据库中获取表达式 直接指定时为空
                                'cron_expr'   => '10 4 * * *'// 直接指定cron表达式 在配置文件或数据库中获取表达式为空
                ),
                'run'       => array(
                                'filepath'  => 'tools/export/',          // 文件所在的目录 相对于APPPATH
                                'filename'  => 'statistics.php',   // 文件名
                                'class'     => 'Statistics',       // 类名 如果只是简单函数 可为空
                                'function'  => 'execute',     // 要执行的函数
                                'params'    =>  date('Y-m-d H:i:s', time()-900) ,         // 需要传递的参数 增量数据
                )
);
//$cron_schedule['clear_log'] = ...
//$cron_schedule['create_sitemap'] = ...
//$cron_schedule['backup_database'] = ...
