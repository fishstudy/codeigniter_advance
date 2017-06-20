<?php
/*
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 * This can be set to anything, but default usage is:
 *
 *     development
 *     testing
 *     production
 *
 * NOTE: If you change these, also change the error_reporting() code below
 *
 */
setlocale(LC_ALL, "en_US.UTF-8");
$envName = get_cfg_var('env.name');
$envName = ($envName === FALSE) ? 'production' : $envName;
define('ENVIRONMENT', $envName);
if (defined('ENVIRONMENT'))
{
	switch (ENVIRONMENT)
	{
		case 'development':
			ini_set('display_errors',1);
			error_reporting(E_ALL);
		break;

		case 'testing':
		case 'production':
			ini_set('display_errors',1);
			error_reporting(E_ALL);
			//error_reporting(0);
		break;

		default:
			exit('The application environment is not set correctly.');
	}
}

/*
 *----------------------------------------------------
 * php5.3 ，当对使用date()等函数时，如果timezone设置不正确，
 * 在每一次调用时间函数时,都会产生E_NOTICE 或者 E_WARNING 信息。
 *----------------------------------------------------
 */
 ini_set('date.timezone','Asia/Shanghai');
 $system_path = "/data/release/webserver/framework";
 $application_folder = '/data/release/webserver/webapp/weizhan';
// view 目录
$view_folder = '/data/release/webserver/template/weizhan';
$third_path = '/data/release/webserver/third';

// Set the current directory correctly for CLI requests
if (defined('STDIN'))
{
	chdir(dirname(__FILE__));
}
if (realpath($system_path) !== FALSE)
{
	$system_path = realpath($system_path).'/';
}

// ensure there's a trailing slash
$system_path = rtrim($system_path, '/').'/';

// Is the system path correct?
//echo $system_path;die();
if ( ! is_dir($system_path))
{
	exit("Your system folder path does not appear to be set correctly. Please open the following file and correct this: ".pathinfo(__FILE__, PATHINFO_BASENAME));
}

/*
 * -------------------------------------------------------------------
 *  Now that we know the path, set the main path constants
 * -------------------------------------------------------------------
 */
	// The name of THIS file
	define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

	// The PHP file extension
	// this global constant is deprecated.
	define('EXT', '.php');

	// Path to the system folder
	define('BASEPATH', str_replace("\\", "/", $system_path));

	// Path to the front controller (this file)
	define('FCPATH', str_replace(SELF, '', __FILE__));

	// Name of the "system folder"
	define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));


	// The path to the "application" folder
	if (is_dir($application_folder))
	{
		define('APPPATH', $application_folder.'/');
	}
	else
	{
		if ( ! is_dir(BASEPATH.$application_folder.'/'))
		{
			exit("Your application folder path does not appear to be set correctly. Please open the following file and correct this: ".SELF);
		}

		define('APPPATH', BASEPATH.$application_folder.'/');
	}

    // the path to the "view" floder
	define('VIEWPATH', $view_folder.'/');
	define('THIRDPATH', $third_path.'/');
    if ( ! is_dir(VIEWPATH.'/'))
    {
        exit("Your view folder path does not appear to be set correctly. Please open the following file and correct this: ".SELF);
    }

/*
 * --------------------------------------------------------------------
 * LOAD THE BOOTSTRAP FILE
 * --------------------------------------------------------------------
 *
 * And away we go...
 *
 */
require_once BASEPATH.'core/CodeIgniter.php';

/* End of file index.php */
/* Location: ./index.php */
