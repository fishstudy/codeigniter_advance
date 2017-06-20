<?php
//定义版本信息
define ('VERSION', '2.8.10');
//调试日志记录到web服务器日志中
if(! defined('DEBUG_ON') ){
    define ('DEBUG_ON', false);
}
//调试级别，高于这个值的level都不会记录，1最低，3最高
if(! defined('DEBUG_LEVEL') ){
    define ('DEBUG_LEVEL', 1);
}
//最大占用内存限制30M
if(! defined('MEMORY_LIMIT') ){
    define ('MEMORY_LIMIT', '30M');
}
//关闭仿盗链
if(! defined('BLOCK_EXTERNAL_LEECHERS') ){
    define ('BLOCK_EXTERNAL_LEECHERS', false);
}
// 允许从外部获取图片
if(! defined('ALLOW_EXTERNAL') ){
    define ('ALLOW_EXTERNAL', TRUE);
}
//允许获取所有外部站点url
if(! defined('ALLOW_ALL_EXTERNAL_SITES') ){
    define ('ALLOW_ALL_EXTERNAL_SITES', false);
}
//启用文件缓存
if(! defined('FILE_CACHE_ENABLED') ){
    define ('FILE_CACHE_ENABLED', false);
}
//文件缓存更新时间，s
if(! defined('FILE_CACHE_TIME_BETWEEN_CLEANS')){
    define ('FILE_CACHE_TIME_BETWEEN_CLEANS', 86400);
}
//文件缓存生存时间，s，过了这个时间的缓存文件就会被删除
if(! defined('FILE_CACHE_MAX_FILE_AGE') ){
    define ('FILE_CACHE_MAX_FILE_AGE', 86400);
}
//缓存文件后缀
if(! defined('FILE_CACHE_SUFFIX') ){
    define ('FILE_CACHE_SUFFIX', '.timthumb.txt');
}
//缓存文件前缀
if(! defined('FILE_CACHE_PREFIX') ){
    define ('FILE_CACHE_PREFIX', 'timthumb');
}
//缓存文件目录，留空则使用系统临时目录（推荐）
if(! defined('FILE_CACHE_DIRECTORY') ){
    define ('FILE_CACHE_DIRECTORY', '/opt/log/cache');
}
//图片最大尺寸，此脚本最大能处理10485760字节的图片，也就是10M
if(! defined('MAX_FILE_SIZE') ){
    define ('MAX_FILE_SIZE', 10485760);
}
//CURL的超时时间
if(! defined('CURL_TIMEOUT') ){
    define ('CURL_TIMEOUT', 20);
}
//清理错误缓存的时间
if(! defined('WAIT_BETWEEN_FETCH_ERRORS') ){
    define ('WAIT_BETWEEN_FETCH_ERRORS', 3600);
}
//浏览器缓存时间
if(! defined('BROWSER_CACHE_MAX_AGE') ){
    define ('BROWSER_CACHE_MAX_AGE', 864000);
}
//关闭所有浏览器缓存
if(! defined('BROWSER_CACHE_DISABLE') ){
    define ('BROWSER_CACHE_DISABLE', false);
}
//最大图像宽度
if(! defined('MAX_WIDTH') ){
    define ('MAX_WIDTH', 1500);
}
//最大图像高度
if(! defined('MAX_HEIGHT') ){
    define ('MAX_HEIGHT', 1500);
}
//404错误时显示的提示图片地址，设置测值则不会显示具体的错误信息
if(! defined('NOT_FOUND_IMAGE') ){
    define ('NOT_FOUND_IMAGE', '');
}
//其他错误时显示的提示图片地址，设置测值则不会显示具体的错误信息
if(! defined('ERROR_IMAGE') ){
    define ('ERROR_IMAGE', '');
}
//PNG图片背景颜色，使用false代表透明
if(! defined('PNG_IS_TRANSPARENT') ){
    define ('PNG_IS_TRANSPARENT', FALSE);
}
//默认图片质量
if(! defined('DEFAULT_Q') ){
    define ('DEFAULT_Q', 90);
}
//默认需要对图片进行的处理操作，可选值和参数格式请参看processImageAndWriteToCache函数中的$filters和$imageFilters的注释
if(! defined('DEFAULT_F') ){
    define ('DEFAULT_F', '');
}
//是否对图片进行锐化
if(! defined('DEFAULT_S') ){
    define ('DEFAULT_S', 0);
}
//默认画布颜色
if(! defined('DEFAULT_CC') ){
    define ('DEFAULT_CC', 'ffffff');
}
//以下是图片压缩设置，前提是你的主机中有optipng或者pngcrush这两个工具，否则的话不会启用此功能
//此功能只对png图片有效
if(! defined('OPTIPNG_ENABLED') ){
    define ('OPTIPNG_ENABLED', false);
}
if(! defined('OPTIPNG_PATH') ){
    define ('OPTIPNG_PATH', '/usr/bin/optipng');
} //优先使用optipng,因为有更好的压缩比
if(! defined('PNGCRUSH_ENABLED') ){
    define ('PNGCRUSH_ENABLED', false);
}
if(! defined('PNGCRUSH_PATH') ){
    define ('PNGCRUSH_PATH', '/usr/bin/pngcrush');
} //optipng不存在的话，使用pngcrush
//图片压缩设置结束


/*
 * * 以下是网站截图配置
* 首先，网站截图需要root权限
* Ubuntu 上使用网站截图的步骤：
*  1.用这个命令安装Xvfb  sudo apt-get install subversion libqt4-webkit libqt4-dev g++ xvfb
*  2.新建一个文件夹，并下载下面的源码
*  3.用这个命令下载最新的CutyCapt  svn co https://cutycapt.svn.sourceforge.net/svnroot/cutycapt
*  4.进入CutyCapt文件夹
*  5.编译并安装CutyCapt
*  6.尝试运行以下命令：  xvfb-run --server-args="-screen 0, 1024x768x24" CutyCapt --url="http://markmaunder.com/" --out=test.png
*  7.如果生成了一个 test.php的图片，证明一切正常，现在通过浏览器试试，访问下面的地址：http://yoursite.com/path/to/timthumb.php?src=http://markmaunder.com/&webshot=1
*
* 需要注意的地方：
*  1.第一次webshot加载时，需要数秒钟，之后加载就很快了
*
* 高级用户：
*  1.如果想提速大约25%，并且你了解linux，可以运行以下命令：
*  nohup Xvfb :100 -ac -nolisten tcp -screen 0, 1024x768x24 > /dev/null 2>&1 &
*  并设置 WEBSHOT_XVFB_RUNNING 的值为true
*
* */
//测试的功能，如果设置此值为true, 并在查询字符串中加上webshot=1,会让脚本返回浏览器的截图，而不是获取图像
if(! defined('WEBSHOT_ENABLED') ){
    define ('WEBSHOT_ENABLED', false);
}
//定义CutyCapt地址
if(! defined('WEBSHOT_CUTYCAPT') ){
    define ('WEBSHOT_CUTYCAPT', '/usr/local/bin/CutyCapt');
}
//Xvfb地址
if(! defined('WEBSHOT_XVFB') ){
    define ('WEBSHOT_XVFB', '/usr/bin/xvfb-run');
}
//截图屏幕宽度1024
if(! defined('WEBSHOT_SCREEN_X') ){
    define ('WEBSHOT_SCREEN_X', '1024');
}
//截图屏幕高度768
if(! defined('WEBSHOT_SCREEN_Y') ){
    define ('WEBSHOT_SCREEN_Y', '768');
}
//色深，作者说他只测试过24
if(! defined('WEBSHOT_COLOR_DEPTH') ){
    define ('WEBSHOT_COLOR_DEPTH', '24');
}
//截图格式
if(! defined('WEBSHOT_IMAGE_FORMAT') ){
    define ('WEBSHOT_IMAGE_FORMAT', 'png');
}
//截图超时时间，单位s
if(! defined('WEBSHOT_TIMEOUT') ){
    define ('WEBSHOT_TIMEOUT', '20');
}
//user_agent头
if(! defined('WEBSHOT_USER_AGENT') ){
    define ('WEBSHOT_USER_AGENT', "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.9.2.18) Gecko/20110614 Firefox/3.6.18");
}
//是否启用JS
if(! defined('WEBSHOT_JAVASCRIPT_ON') ){
    define ('WEBSHOT_JAVASCRIPT_ON', true);
}
//是否启用java
if(! defined('WEBSHOT_JAVA_ON') ){
    define ('WEBSHOT_JAVA_ON', false);
}
//开启flash和其他插件
if(! defined('WEBSHOT_PLUGINS_ON') ){
    define ('WEBSHOT_PLUGINS_ON', true);
}
//代理服务器
if(! defined('WEBSHOT_PROXY') ){
    define ('WEBSHOT_PROXY', '');
}
//如果运行了XVFB,此项设为true
if(! defined('WEBSHOT_XVFB_RUNNING') ){
    define ('WEBSHOT_XVFB_RUNNING', false);
}
// 如果 ALLOW_EXTERNAL 的值为真 并且 ALLOW_ALL_EXTERNAL_SITES 的值为假，那么截图的图片只能从下面这些数组中的域和子域进行
if(! isset($ALLOWED_SITES)){
    $ALLOWED_SITES = array (
                    'ifchang.com',
                    'uimg.dev.com',
    );
}
/*截图配置结束*/
//timthumb::start();

class timthumb {
   protected $_imgrequest = array();
	protected $src = "";  //需要获取的图片url
	protected $is404 = false;  //404错误码
	protected $docRoot = "";  //服务器文档根目录
	protected $lastURLError = false; //上一次请求外部url的错误信息
	protected $localImage = ""; //如果请求的url是本地图片，则为本地图片的地址
	protected $localImageMTime = 0;  //本地图片的修改时间
	protected $url = false;  //用parse_url解析src后的数组
    protected $myHost = "";  //本机域名
	protected $isURL = false;  //是否为外部图片地址
	protected $cachefile = ''; //缓存文件地址
	protected $errors = array();  //错误信息列表
	protected $toDeletes = array(); //析构函数中需要删除的资源列表
	protected $cacheDirectory = ''; //缓存地址
	protected $startTime = 0;  //开始时间
	protected $lastBenchTime = 0; //上一次debug完成的时间
	protected $cropAlign = false;  //是否启用裁剪
	protected $salt = "";  //文件修改时间和inode连接的字符串的盐值
	protected $fileCacheVersion = 1; //文件缓存版本，当这个类升级或者被更改时，这个值应该改变，从而重新生成缓存
	protected $filePrependSecurityBlock = "<?php die('Execution denied!'); //"; //缓存文件安全头，防止直接访问
	protected static $curlDataWritten = 0;  //将curl获取到的数据写入缓存文件的长度
	protected static $curlFH = false;  //curl请求成功后要将获取到的数据写到此文件内
	/*外部调用接口*/
	 public static function start($url='src=http://uimg.dev.com/2c/articles/201312/1386232805.4427.jpeg'){
	    parse_str($url,$request);
	  	//实例化模型
	  	$tim = new timthumb($request);
		$tim->run();
		exit(0);
	}
	/*构造方法，用来获取和设置一些基本属性*/
	public function __construct($request){
	    $this->_imgrequest = $request; 
	  	//将允许的域设为全局变量
	  	global $ALLOWED_SITES;
		$this->src = $this->param('src');
		//此数组是解析src后的结果，包括scheme,host,port,user,pass,path,query,fragment其中一个或多个值
		$this->url = parse_url($this->src);
		
		return true;
	}
	
	/*主函数，通过不同参数调用不同的图片处理函数*/
	public function run(&$temp_data, $imageinfo, $config){
	    $this->cropAlign = $config['crop_align'];
	    //默认 缩放/裁剪 模式，0：根据传入的值进行缩放（不裁剪）， 1：以最合适的比例裁剪和调整大小（裁剪）， 2：按比例调整大小，并添加边框（裁剪），3：按比例调整大小，不添加边框（裁剪）
	    if(! defined('DEFAULT_ZC') ){
	        define ('DEFAULT_ZC',$config['zoom_crop']);
	    }
		$data = $this->serveExternalImage($temp_data, $imageinfo);
		
		return $data;
	}

	
	/*核心函数，处理图片并写入缓存*/
	protected function processImageAndWriteToCache($localImage, $sData){
		//图像类型标记
		$origType = $sData[2];
		//mime类型
		$mimeType = $sData['mime'];
		//写日志，记录传入图像的mime类型
		//进行图像mime类型验证，只允许gif , jpg 和 png
		if(! preg_match('/^image\/(?:gif|jpg|jpeg|png)$/i', $mimeType)){
		  	//如果不是这四种类型，记录错误信息，并退出脚本
			return $this->error("The image being resized is not a valid gif, jpg or png.");
		}
		//图片处理需要GD库支持，这里检测是否安装了GD库
		if (!function_exists ('imagecreatetruecolor')) {
		    //没有安装的话推出脚本
		    return $this->error('GD Library Error: imagecreatetruecolor does not exist - please contact your webhost and ask them to install the GD library');
		}
		//如果安装了GD库，并且支持图像过滤器函数imagefilter，且支持IMG_FILTER_NEGATE常量
		if (function_exists ('imagefilter') && defined ('IMG_FILTER_NEGATE')) {
		  	//定义一个过滤器效果数组，后面的数字代表需要额外传入的参数
		  	$imageFilters = array (
		    		//负片
			  	1 => array (IMG_FILTER_NEGATE, 0),
				//黑白的
				2 => array (IMG_FILTER_GRAYSCALE, 0),
				//亮度级别
				3 => array (IMG_FILTER_BRIGHTNESS, 1),
				//对比度级别
				4 => array (IMG_FILTER_CONTRAST, 1),
				//图像转换为制定颜色
				5 => array (IMG_FILTER_COLORIZE, 4),
				//突出边缘
				6 => array (IMG_FILTER_EDGEDETECT, 0),
				//浮雕
				7 => array (IMG_FILTER_EMBOSS, 0),
				//用高斯算法模糊图像
				8 => array (IMG_FILTER_GAUSSIAN_BLUR, 0),
				//模糊图像
				9 => array (IMG_FILTER_SELECTIVE_BLUR, 0),
				//平均移除法来达到轮廓效果
				10 => array (IMG_FILTER_MEAN_REMOVAL, 0),
				//平滑处理
				11 => array (IMG_FILTER_SMOOTH, 0),
			);
		}

		//生成图片宽度，由get中w参数指定，默认为0		
		$new_width =  (int) abs ($this->param('w', 0));
		//生成图片高度，由get中h参数指定，默认为0
		$new_height = (int) abs ($this->param('h', 0));
		//生成图片缩放模式，由get中zc参数指定，默认为配置文件中DEFAULT_ZC的值
		$zoom_crop = (int) $this->param('zc', DEFAULT_ZC);
		//var_dump($zoom_crop);
		//生成图片的质量，由get中q参数指定，默认为配置文件中DEFAULT_Q的值
		$quality = (int) abs ($this->param('q', DEFAULT_Q));
		//裁剪的位置
		$align = $this->cropAlign;
		//var_dump($align);
		//需要进行的图片处理操作，多个过滤器用"|"分割，可选参数请参看$imageFilters处的注释，由于不同的过滤器需要的参数不同，如一个过滤器需要多个参数，多个参数用,分隔。例:1,2|3,1,1  代表对图像分别应用1和3过滤效果，1和3所对应的过滤效果是由$imageFilters数组确定的，其中1号过滤器还需要一个额外的参数，这里传了1，3号过滤器还需要2个额外的参数，这里传了1和1.
		$filters = $this->param('f', DEFAULT_F);
		//是否对图片进行锐化，由get中s参数指定，默认为配置文件中DEFAULT_S的值
		$sharpen = (bool) $this->param('s', DEFAULT_S);
		//生成图片的默认背景画布颜色，由get中cc参数指定，默认为配置文件中DEFAULT_CC的值
		$canvas_color = $this->param('cc', DEFAULT_CC);
		//生成png图片的背景是否透明
		$canvas_trans = (bool) $this->param('ct', '1');

		// 如果高度和宽度都没有指定，设置他们为100*100
		if ($new_width == 0 && $new_height == 0) {
		    $new_width = 120;
		    $new_height = 78;
		}
		
		// 限制最大高度和最大宽度
		$new_width = min ($new_width, MAX_WIDTH);
		$new_height = min ($new_height, MAX_HEIGHT);
		$image = imagecreatefromstring($localImage);
		//如果打开失败，记录信息并退出脚本
		if ($image === false) {
			return $this->error('Unable to open image.');
		}

		// 获得原始图片，也就是上面打开图片的宽和高
		$width = imagesx ($image);
		$height = imagesy ($image);
		$origin_x = 0;
		$origin_y = 0;

		// 如果新生成图片的宽或高没有指定，则用此等比算法算出高或宽的值
		if ($new_width && !$new_height) {
			$new_height = floor ($height * ($new_width / $width));
		} else if ($new_height && !$new_width) {
			$new_width = floor ($width * ($new_height / $height));
		}

		// 如果缩放模式选择的是3，也就是说get中zc=3或者配置文件中DEFAULT_ZC=3，则进行等比缩放,不裁剪
		if ($zoom_crop == 3) {

			$final_height = $height * ($new_width / $width);
			//根据等比算法设置等比计算后的宽或高
			if ($final_height > $new_height) {
				$new_width = $width * ($new_height / $height);
			} else {
				$new_height = $final_height;
			}

		}

		// 利用处理完毕的长和宽创建新画布，
		$canvas = imagecreatetruecolor ($new_width, $new_height);
		//关闭混色模式，也就是把PNG的alpha值保存，从而使背景透明
		imagealphablending ($canvas, false);
		//进行默认画布颜色的检测并转换，如果给出的是3个字符长度表示的颜色值
		if (strlen($canvas_color) == 3) { //if is 3-char notation, edit string into 6-char notation
		  	//转换为6个字符表示的颜色值
		  	$canvas_color =  str_repeat(substr($canvas_color, 0, 1), 2) . str_repeat(substr($canvas_color, 1, 1), 2) . str_repeat(substr($canvas_color, 2, 1), 2); 
		//如果不是3个长度也不是6个长度的字符串，则为非法字符串，设置为默认值
		} else if (strlen($canvas_color) != 6) {
			$canvas_color = DEFAULT_CC;
 		}
		//将上面得到的R 、G 、B 三种颜色值转换为10进制表示
		$canvas_color_R = hexdec (substr ($canvas_color, 0, 2));
		$canvas_color_G = hexdec (substr ($canvas_color, 2, 2));
		$canvas_color_B = hexdec (substr ($canvas_color, 4, 2));

		// 如果传入图片的格式是png，并且配置文件设置png背景颜色为透明，并且在get传入了ct的值为真，那么就设置背景颜色为透明
		if(preg_match('/^image\/png$/i', $mimeType) && !PNG_IS_TRANSPARENT && $canvas_trans){ 
		  	$color = imagecolorallocatealpha ($canvas, $canvas_color_R, $canvas_color_G, $canvas_color_B, 127);
		//反之设置为不透明
		}else{
			$color = imagecolorallocatealpha ($canvas, $canvas_color_R, $canvas_color_G, $canvas_color_B, 0);
		}

		// 使用分配的颜色填充背景
		imagefill ($canvas, 0, 0, $color);
		

		// 如果缩放模式选择的是2，那么画布的体积是按传入的值创建的，并计算出边框的宽度
		if ($zoom_crop == 2) {
		  	//等比缩放的高度
			$final_height = $height * ($new_width / $width);
			//如果计算出的等比高度，大于传入的高度
			if ($final_height > $new_height) {
				//origin_x等于传入的新高度的二分之一
			  	$origin_x = $new_width / 2;
			  	//设置新宽度为等比计算出的值
				$new_width = $width * ($new_height / $height);
				//计算出两次origin_x的差值
				$origin_x = round ($origin_x - ($new_width / 2));
			//否则，计算出两次origin_y的差值
			} else {
				$origin_y = $new_height / 2;
				$new_height = $final_height;
				$origin_y = round ($origin_y - ($new_height / 2));

			}

		}

		// 保存图像时保存完整的alpha信息
		imagesavealpha ($canvas, true);

		//如果缩放模式选择的是1或2或3
		if ($zoom_crop > 0) {

		  	$src_x = $src_y = 0;
			//图片原宽度
			$src_w = $width;
			//图片原高度
			$src_h = $height;

			//图片纵横比
			$cmp_x = $width / $new_width;
			$cmp_y = $height / $new_height;

			//裁剪算法
			if ($cmp_x > $cmp_y) {
				$src_w = round ($width / $cmp_x * $cmp_y);
				$src_x = round (($width - ($width / $cmp_x * $cmp_y)) / 2);

			} else if ($cmp_y > $cmp_x) {

				$src_h = round ($height / $cmp_y * $cmp_x);
				$src_y = round (($height - ($height / $cmp_y * $cmp_x)) / 2);

			}

			// 根据传入参数算出裁剪的位置
			if ($align) {
				if (strpos ($align, 't') !== false) {
					$src_y = 0;
				}
				if (strpos ($align, 'b') !== false) {
					$src_y = $height - $src_h;
				}
				if (strpos ($align, 'l') !== false) {
					$src_x = 0;
				}
				if (strpos ($align, 'r') !== false) {
					$src_x = $width - $src_w;
				}
			}

			//将图像根据算法进行裁剪，并拷贝到背景图片上
			imagecopyresampled ($canvas, $image, $origin_x, $origin_y, $src_x, $src_y, $new_width, $new_height, $src_w, $src_h);

		} else {
			//裁剪模式选择的是0，则不进行裁剪，并生成图像
			imagecopyresampled ($canvas, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

		}
		//如果定义了图片处理操作，并且支持图片处理函数
		if ($filters != '' && function_exists ('imagefilter') && defined ('IMG_FILTER_NEGATE')) {
			// 分割每个过滤处理
			$filterList = explode ('|', $filters);
			foreach ($filterList as $fl) {
			  	//分割一个过滤操作中的参数
			  	$filterSettings = explode (',', $fl);
				//如果所选的过滤操作存在
				if (isset ($imageFilters[$filterSettings[0]])) {
					//将所有参数转为int类型
					for ($i = 0; $i < 4; $i ++) {
						if (!isset ($filterSettings[$i])) {
							$filterSettings[$i] = null;
						} else {
							$filterSettings[$i] = (int) $filterSettings[$i];
						}
					}
					//根据$imageFilters中定义的每个过滤效果需要的参数的不同，对图像应用过滤器效果
					switch ($imageFilters[$filterSettings[0]][1]) {

						case 1:

							imagefilter ($canvas, $imageFilters[$filterSettings[0]][0], $filterSettings[1]);
							break;

						case 2:

							imagefilter ($canvas, $imageFilters[$filterSettings[0]][0], $filterSettings[1], $filterSettings[2]);
							break;

						case 3:

							imagefilter ($canvas, $imageFilters[$filterSettings[0]][0], $filterSettings[1], $filterSettings[2], $filterSettings[3]);
							break;

						case 4:

							imagefilter ($canvas, $imageFilters[$filterSettings[0]][0], $filterSettings[1], $filterSettings[2], $filterSettings[3], $filterSettings[4]);
							break;

						default:

							imagefilter ($canvas, $imageFilters[$filterSettings[0]][0]);
							break;

					}
				}
			}
		}

		// 如果设置了锐化值，并且系统支持锐化函数，则进行锐化操作
		if ($sharpen && function_exists ('imageconvolution')) {

			$sharpenMatrix = array (
					array (-1,-1,-1),
					array (-1,16,-1),
					array (-1,-1,-1),
					);

			$divisor = 8;
			$offset = 0;

			imageconvolution ($canvas, $sharpenMatrix, $divisor, $offset);

		}
		//如果图片是PNG或者GIF，则用imagetruecolortopalette来减小他们的体积
		if ( (IMAGETYPE_PNG == $origType || IMAGETYPE_GIF == $origType) && function_exists('imageistruecolor') && !imageistruecolor( $image ) && imagecolortransparent( $image ) > 0 ){
			imagetruecolortopalette( $canvas, false, imagecolorstotal( $image ) );
		}
		//根据生成的不同图片类型，生成图片缓存,$imgType的值用于生成安全头
		$imgType = "";
		ob_start();
		if(preg_match('/^image\/(?:jpg|jpeg)$/i', $mimeType)){ 
			$imgType = 'jpg';
			imagejpeg($canvas, null, $quality); 
		} else if(preg_match('/^image\/png$/i', $mimeType)){ 
			$imgType = 'png';
			imagepng($canvas, null, floor($quality * 0.09));
		} else if(preg_match('/^image\/gif$/i', $mimeType)){
			$imgType = 'gif';
			imagegif($canvas, null);
		} else {
		  	//如果不是以上三种类型，记录这次错误并退出
			return $this->sanityFail("Could not match mime type after verifying it previously.");
		}
		//优先使用optipng工具进行png图片的压缩，前提是你装了这个工具
		/*if($imgType == 'png' && OPTIPNG_ENABLED && OPTIPNG_PATH && @is_file(OPTIPNG_PATH)){
		  	//记录optipng的地址
		  	$exec = OPTIPNG_PATH;
			//获取图片大小
			$presize = filesize($tempfile);
			//进行图片压缩操作
			$out = `$exec -o1 $tempfile`;
		       	//清除文件状态缓存	
			clearstatcache();
			//获取压缩后的文件大小
			$aftersize = filesize($tempfile);
			//算出压缩了多大
			$sizeDrop = $presize - $aftersize;
			//根据算出的不同的值，写日志，级别1
			if($sizeDrop > 0){
				$this->debug(1, "optipng reduced size by $sizeDrop");
			} else if($sizeDrop < 0){
				$this->debug(1, "optipng increased size! Difference was: $sizeDrop");
			} else {
				$this->debug(1, "optipng did not change image size.");
			}
		//optipng不存在，就尝试使用pngcrush
		} else if($imgType == 'png' && PNGCRUSH_ENABLED && PNGCRUSH_PATH && @is_file(PNGCRUSH_PATH)){
		  	$exec = PNGCRUSH_PATH;
			//和optipng不同的是，pngcrush会将处理完的文件新生成一个文件，所以这里新建个文件
			$tempfile2 = tempnam($this->cacheDirectory, 'timthumb_tmpimg_');
			//写日志，记录文件名
			$this->debug(3, "pngcrush'ing $tempfile to $tempfile2");
			//运行pngcrush
			$out = `$exec $tempfile $tempfile2`;
			$todel = "";
			//如果生成文件成功
			if(is_file($tempfile2)){
			  	//算出压缩后的文件大小的差值
			  	$sizeDrop = filesize($tempfile) - filesize($tempfile2);
				//如果是一次有效的压缩，则将压缩后的文件作为缓存文件
				if($sizeDrop > 0){
					$this->debug(1, "pngcrush was succesful and gave a $sizeDrop byte size reduction");
					$todel = $tempfile;
					$tempfile = $tempfile2;
				//否则的话则这个文件没有存在的必要
				} else {
					$this->debug(1, "pngcrush did not reduce file size. Difference was $sizeDrop bytes.");
					$todel = $tempfile2;
				}
			//没有运行成功也需要删除这个文件
			} else {
				$this->debug(3, "pngcrush failed with output: $out");
				$todel = $tempfile2;
			}
			//删除无效文件或压缩前比较大的文件
			@unlink($todel);
		}*/
		$img_data = ob_get_contents();
		ob_end_clean();
		
		//释放图片资源
		imagedestroy($canvas);
		imagedestroy($image);
		//生成缓存成功返回真
		return $img_data;
	}

	/*此函数用来从外部url获取图像*/
	protected function serveExternalImage(&$temp_data, $imageinfo){
		$img_data = $this->processImageAndWriteToCache($temp_data, $imageinfo);
		
		return $img_data;
	}
	
	/*此函数设置图片输出必要的http头*/
	protected function sendImageHeaders($mimeType, $dataSize=''){
	  	//补全图片的mime信息
		if(! preg_match('/^image\//i', $mimeType)){
			$mimeType = 'image/' . $mimeType;
		}
		//将jpg的mime类型写标准，这里不标准的原因是在验证文件安全头时追求了便利性
		if(strtolower($mimeType) == 'image/jpg'){
			$mimeType = 'image/jpeg';
		}
		//浏览器缓存失效时间
		$gmdate_expires = gmdate ('D, d M Y H:i:s', strtotime ('now +10 days')) . ' GMT';
		//文档最后被修改时间，用来让浏览器判断是否需要重新请求页面
		$gmdate_modified = gmdate ('D, d M Y H:i:s') . ' GMT';
		// 设置HTTP头
		header ('Content-Type: ' . $mimeType);
		header ('Accept-Ranges: none'); 
		header ('Last-Modified: ' . $gmdate_modified);
		//header ('Content-Length: ' . $dataSize);
		//如果配置文件禁止浏览器缓存，则设置相应的HTTP头信息
		if(BROWSER_CACHE_DISABLE){
			$this->debug(3, "Browser cache is disabled so setting non-caching headers.");
			header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
			header("Pragma: no-cache");
			header('Expires: ' . gmdate ('D, d M Y H:i:s', time()));
		//否则按配置文件设置缓存时间
		} else {
			$this->debug(3, "Browser caching is enabled");
			header('Cache-Control: max-age=' . BROWSER_CACHE_MAX_AGE . ', must-revalidate');
			header('Expires: ' . $gmdate_expires);
		}
		//运行成功返回真
		return true;
	}
	
	/*此函数用来获取$_GET数组中的参数，并允许设置默认值*/
	protected function param($property, $default = ''){
	  	//如果参数存在则返回此参数
		if (isset ($this->_imgrequest[$property])) {
		  	return  $this->_imgrequest[$property];
		//不存在的话返回默认值
		} else {
			return $default;
		}
	}
	/*此函数根据传入mime类型，打开图像资源*/
	protected function openImage($mimeType, $src){
		switch ($mimeType) {
			case 'image/jpeg':
				$image = imagecreatefromjpeg ($src);
				break;

			case 'image/png':
				$image = imagecreatefrompng ($src);
				break;

			case 'image/gif':
				$image = imagecreatefromgif ($src);
				break;
			//不是这三种的话，脚本退出
			default:
				$this->error("Unrecognised mimeType");
		}
		//返回图像资源
		return $image;
	}
	
	/*debug运行日志函数，用来向系统日志记录操作信息*/
	protected function debug($level, $msg){
	  	//如果开启了debug，并且$level也就是调试级别小于等于配置文件中的值，则开始记录
	  	if(DEBUG_ON && $level <= DEBUG_LEVEL){
			//格式化并记录开始时间，保留小数点后6位,这个时间代表实例化类后到这个debug执行所经历的时间
		  	$execTime = sprintf('%.6f', microtime(true) - $this->startTime);
			//这个值代表从上次debug结束，到这次debug的用时
			$tick = sprintf('%.6f', 0);
			//如果上次debug时间存在，则用当前时间减去上次debug时间，得出差值
			if($this->lastBenchTime > 0){
				$tick = sprintf('%.6f', microtime(true) - $this->lastBenchTime);
			}
			//将时间更新
			$this->lastBenchTime = microtime(true);
			//将debug信息写到系统日志中
			error_log("TimThumb Debug line " . __LINE__ . " [$execTime : $tick]: $msg");
		}
	}
	/*此函数用来记录未知BUG*/
	protected function sanityFail($msg){
	  	//记录BUG信息
		return $this->error("There is a problem in the timthumb code. Message: Please report this error at <a href='http://code.google.com/p/timthumb/issues/list'>timthumb's bug tracking page</a>: $msg");
	}
	/*此函数用来返回图片文件的MIME信息*/
	protected function getMimeType($file){
	  	//获取图片文件的信息
	  	$info = getimagesize($file);
		//成功则返回MIME信息
		if(is_array($info) && $info['mime']){
			return $info['mime'];
		}
		//失败返回空
		return '';
	}
	/*此函数用来检测并设置php运行时最大占用内存的值*/
	protected function setMemoryLimit(){
	  	//获取php.ini中的最大内存占用的值
	  	$inimem = ini_get('memory_limit');
		//将上面得到的值转换为以字节为单位的数值
		$inibytes = timthumb::returnBytes($inimem);
		//算出配置文件中内存限制的值
		$ourbytes = timthumb::returnBytes(MEMORY_LIMIT);
		//如果php配置文件中的值小于自己设定的值
		if($inibytes < $ourbytes){
		  	//则将php.ini配置中关于最大内存的值设置为自己设定的值
		  	ini_set ('memory_limit', MEMORY_LIMIT);
		  	//写日志，记录改变内存操作，级别3
			$this->debug(3, "Increased memory from $inimem to " . MEMORY_LIMIT);
		//如果自己设置的值小于php.ini中的值
		} else {
		  	//则不进行任何操作，写日志记录此条信息即可，级别3
			$this->debug(3, "Not adjusting memory size because the current setting is " . $inimem . " and our size of " . MEMORY_LIMIT . " is smaller.");
		}
	}
	/*此函数将G, KB, MB 转为B(字节)*/
	protected static function returnBytes($size_str){
	  	//取最后一个单位值，进行转换操作，并返回转换后的值
		switch (substr ($size_str, -1))
		{
			case 'M': case 'm': return (int)$size_str * 1048576;
			case 'K': case 'k': return (int)$size_str * 1024;
			case 'G': case 'g': return (int)$size_str * 1073741824;
			default: return $size_str;
		}
	}
	/*此函数用来将url中的资源读取到tempfile文件中*/
	protected function getURL($url, $tempfile=''){
	  	//重置上次url请求错误信息
	  	$this->lastURLError = false;
	  	//进行url编码
		$url = preg_replace('/ /', '%20', $url);
		//优先使用curl扩展
		if(function_exists('curl_init')){
			//初始化curl
			$curl = curl_init($url);
			//curl选项设置
			curl_setopt ($curl, CURLOPT_TIMEOUT, CURL_TIMEOUT);
			curl_setopt ($curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.122 Safari/534.30");
			curl_setopt ($curl, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt ($curl, CURLOPT_HEADER, 0);
			curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			//关闭会话时执行curlWrite
			//curl_setopt ($curl, CURLOPT_WRITEFUNCTION, 'timthumb::curlWrite');
			@curl_setopt ($curl, CURLOPT_FOLLOWLOCATION, true);
			@curl_setopt ($curl, CURLOPT_MAXREDIRS, 10);
			//执行本次请求，并将结果赋给$curlResult
			$curlResult = curl_exec($curl);
			//释放文件资源
			//fclose(self::$curlFH);
			//获取最后一个受到的HTTP码
			$httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			//如果是404，那么设置404错误并退出
			if($httpStatus == 404){
				$this->set404();
			}
			//如果请求成功
			if($curlResult){
			  	//关闭curl，并执行curlWrite将数据写到文件中
			  	curl_close($curl);
			  	//返回真，请求完成
				return $curlResult;
			//如果请求不成功
			} else {
			  	//记录错误信息
			  	$this->lastURLError = curl_error($curl);
				//关闭资源
				curl_close($curl);
				//执行不成功
				return false;
			}
		//如果不支持curl，用file_get_contents获取数据
		}

	}
	/*此函数输出指定的图片，用于输出错误信息中*/
	protected function serveImg($file){
	  	//获取图像信息
	  	$s = getimagesize($file);
		//如果获取不到图像信息，推出
		if(! ($s && $s['mime'])){
			return false;
		}
		//设置http头，输出图片
		header ('Content-Type: ' . $s['mime']);
		header ('Content-Length: ' . filesize($file) );
		header ('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
		header ("Pragma: no-cache");
		//使用readfile输出图片
		$bytes = @readfile($file);
		if($bytes > 0){
			return true;
		}
		//如果失败，使用file_get_contents和echo输出图片
		$content = @file_get_contents ($file);
		if ($content != FALSE){
			echo $content;
			return true;
		}
		//还失败的话返回假
		return false;
	}
	
	/*此函数用来记录错误信息*/
	protected function error($err){
	    
	    return false;
	}
	
	/*此函数设置404 错误码*/
	protected function set404(){
		$this->is404 = true;
	}
	/*此函数返回404错误码*/
	protected function is404(){
		return $this->is404;
	}
}

