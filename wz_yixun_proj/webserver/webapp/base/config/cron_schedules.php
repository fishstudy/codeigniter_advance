<?php
// 定时发送文章
// $cron_schedule['test_crontab'] = array(
//                 'schedule'  => array(
//                                 'config_path' => '',            // cron表达式的标识 用于在配置文件或数据库中获取表达式 直接指定时为空
//                                 'cron_expr'   => '*/2 * * * *'// 直接指定cron表达式 在配置文件或数据库中获取表达式为空
//                 ),
//                 'run'       => array(
//                                 'filepath'  => 'crontab',          // 文件所在的目录 相对于APPPATH
//                                 'filename'  => 'test_crontab.php',   // 文件名
//                                 'class'     => 'TestCrontab',       // 类名 如果只是简单函数 可为空
//                                 'function'  => 'execute',     // 要执行的函数
//                                 'params'    =>  date('Y-m-d H:i:s', time()-900) ,         // 需要传递的参数 增量数据
//                 )
// );

//定时同步易迅主站的header和footer
$cron_schedule['header_crontab'] = array(
		'schedule'  => array(
				'config_path' => '',            	// cron表达式的标识 用于在配置文件或数据库中获取表达式 直接指定时为空
				'cron_expr'   => '*/2 * * * *'		// 直接指定cron表达式 在配置文件或数据库中获取表达式为空
		),
		'run'       => array(
				'filepath'  => 'crontab',          // 文件所在的目录 相对于APPPATH
				'filename'  => 'header_crontab.php',   // 文件名
				'class'     => 'HeaderCrontab',       // 类名 如果只是简单函数 可为空
				'function'  => 'execute',     // 要执行的函数
				'params'    =>  date('Y-m-d H:i:s', time()-900) ,         // 需要传递的参数 增量数据
		)
);
