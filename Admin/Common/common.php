<?php
/*========================================================================
#   FileName: common.php
#     Author: liufei
#      Email: liufei@rongyi.com
#   HomePage: http://www.rongyi.com
# LastChange: 2012-06-11 11:48:24
========================================================================*/
// $Id$
// 公共函数
function toDate($time, $format = 'Y-m-d H:i:s') {
	if (empty ( $time )) {
		return '';
	}
	$format = str_replace ( '#', ':', $format );
	return date ( $format, $time );
}

function get_client_ip() {
	if (getenv ( "HTTP_CLIENT_IP" ) && strcasecmp ( getenv ( "HTTP_CLIENT_IP" ), "unknown" ))
		$ip = getenv ( "HTTP_CLIENT_IP" );
	else if (getenv ( "HTTP_X_FORWARDED_FOR" ) && strcasecmp ( getenv ( "HTTP_X_FORWARDED_FOR" ), "unknown" ))
		$ip = getenv ( "HTTP_X_FORWARDED_FOR" );
	else if (getenv ( "REMOTE_ADDR" ) && strcasecmp ( getenv ( "REMOTE_ADDR" ), "unknown" ))
		$ip = getenv ( "REMOTE_ADDR" );
	else if (isset ( $_SERVER ['REMOTE_ADDR'] ) && $_SERVER ['REMOTE_ADDR'] && strcasecmp ( $_SERVER ['REMOTE_ADDR'], "unknown" ))
		$ip = $_SERVER ['REMOTE_ADDR'];
	else
		$ip = "unknown";
	return ($ip);
}

// 缓存文件
function cmssavecache($name = '', $fields = '') {
	$Model = D ( $name );
	$list = $Model->select ();
	$data = array ();
	foreach ( $list as $key => $val ) {
		if (empty ( $fields )) {
			$data [$val [$Model->getPk ()]] = $val;
		} else {
			// 获取需要的字段
			if (is_string ( $fields )) {
				$fields = explode ( ',', $fields );
			}
			if (count ( $fields ) == 1) {
				$data [$val [$Model->getPk ()]] = $val [$fields [0]];
			} else {
				foreach ( $fields as $field ) {
					$data [$val [$Model->getPk ()]] [] = $val [$field];
				}
			}
		}
	}
	$savefile = cmsgetcache ( $name );
	// 所有参数统一为大写
	$content = "<?php\nreturn " . var_export ( array_change_key_case ( $data, CASE_UPPER ), true ) . ";\n?>";
	file_put_contents ( $savefile, $content );
}

function cmsgetcache($name = '') {
	return DATA_PATH . '~' . strtolower ( $name ) . '.php';
}

function getStatus($status, $imageShow = true) {
	switch ($status) {
	case 0 :
		$showText = '禁用';
		$showImg = '<IMG SRC="' . WEB_PUBLIC_PATH . '/Images/locked.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="禁用">';
		break;
	case 2 :
		$showText = '待审';
		$showImg = '<IMG SRC="' . WEB_PUBLIC_PATH . '/Images/prected.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="待审">';
		break;
	case - 1 :
		$showText = '删除';
		$showImg = '<IMG SRC="' . WEB_PUBLIC_PATH . '/Images/del.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="删除">';
		break;
	case 1 :
	default :
		$showText = '正常';
		$showImg = '<IMG SRC="' . WEB_PUBLIC_PATH . '/Images/ok.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="正常">';
	}
	return ($imageShow === true) ? $showImg : $showText;
}

function getOilpercent($oil)
{
	// code...
	switch ($oil) {
	case 0:
		// code...
		return "空";

	case 1:
		// code...
		return "1/4";
	case 2:
		// code...
		return "半箱";
	case '3':
		// code...
		return "3/4";
	default:
		return "满箱";
	}

}

function getDefaultStyle($style) {
	if (empty ( $style )) {
		return 'blue';
	} else {
		return $style;
	}
}

function IP($ip = '', $file = 'UTFWry.dat') {
	$_ip = array ();
	if (isset ( $_ip [$ip] )) {
		return $_ip [$ip];
	} else {
		import ( "ORG.Net.IpLocation" );
		$iplocation = new IpLocation ( $file );
		$location = $iplocation->getlocation ( $ip );
		$_ip [$ip] = $location ['country'] . $location ['area'];
	}
	return $_ip [$ip];
}

function getNodeName($id) {
	if (Session::is_set ( 'nodeNameList' )) {
		$name = Session::get ( 'nodeNameList' );
		return $name [$id];
	}
	$Group = D ( "Node" );
	$list = $Group->getField ( 'id,name' );
	$name = $list [$id];
	Session::set ( 'nodeNameList', $list );
	return $name;
}

function get_pawn($pawn) {
	if ($pawn == 0)
		return "<span style='color:green'>没有</span>";
	else
		return "<span style='color:red'>有</span>";
}

function get_patent($patent) {
	if ($patent == 0)
		return "<span style='color:green'>没有</span>";
	else
		return "<span style='color:red'>有</span>";
}

function getNodeGroupName($id) {
	if (empty ( $id )) {
		return '未分组';
	}
	if (isset ( $_SESSION ['nodeGroupList'] )) {
		return $_SESSION ['nodeGroupList'] [$id];
	}
	$Group = D ( "Group" );
	$list = $Group->getField ( 'id,title' );
	$_SESSION ['nodeGroupList'] = $list;
	$name = $list [$id];
	return $name;
}

function getCardStatus($status) {
	switch ($status) {
	case 0 :
		$show = '未启用';
		break;
	case 1 :
		$show = '已启用';
		break;
	case 2 :
		$show = '使用中';
		break;
	case 3 :
		$show = '已禁用';
		break;
	case 4 :
		$show = '已作废';
		break;
	}
	return $show;
}

// zhanghuihua@msn.com
function showStatus($status, $id, $callback = "") {
	switch ($status) {
	case 0 :
		$info = '<a href="__URL__/resume/id/' . $id . '/navTabId/__MODULE__" target="ajaxTodo" callback="' . $callback . '">恢复</a>';
		break;
	case 2 :
		$info = '<a href="__URL__/pass/id/' . $id . '/navTabId/__MODULE__" target="ajaxTodo" callback="' . $callback . '">批准</a>';
		break;
	case 1 :
		$info = '<a href="__URL__/forbid/id/' . $id . '/navTabId/__MODULE__" target="ajaxTodo" callback="' . $callback . '">禁用</a>';
		break;
	case - 1 :
		$info = '<a href="__URL__/recycle/id/' . $id . '/navTabId/__MODULE__" target="ajaxTodo" callback="' . $callback . '">还原</a>';
		break;
	}
	return $info;
}
function showDriverStatus($status, $id, $callback = "") {
	switch ($status) {
	case 0 :
		$info = '<a href="__URL__/resume/id/' . $id . '/navTabId/__MODULE__" target="ajaxTodo" callback="' . $callback . '">出车</a>';
		break;
	case 2 :
		$info = '<a href="__URL__/pass/id/' . $id . '/navTabId/__MODULE__" target="ajaxTodo" callback="' . $callback . '">批准</a>';
		break;
	case 1 :
		$info = '<a href="__URL__/forbid/id/' . $id . '/navTabId/__MODULE__" target="ajaxTodo" callback="' . $callback . '">未出车</a>';
		break;
	case - 1 :
		$info = '<a href="__URL__/recycle/id/' . $id . '/navTabId/__MODULE__" target="ajaxTodo" callback="' . $callback . '">还原</a>';
		break;
	}
	return $info;
}
// tenking@live.cn
function isshow($status) {
	switch ($status) {
	case 0 :
		$info = '隐藏';
		break;
	case 1 :
		$info = '显示';
		break;
	}
	return $info;
}

/**
 * +----------------------------------------------------------
 * 获取登录验证码 默认为4位数字
 * +----------------------------------------------------------
 *
 * @param $fmode string
 *       	 文件名
 *       	 +----------------------------------------------------------
 * @return string +----------------------------------------------------------
 */
function build_verify($length = 4, $mode = 1) {
	return rand_string ( $length, $mode );
}

function getGroupName($id) {
	if ($id == 0) {
		return '无上级组';
	}
	if ($list = F ( 'groupName' )) {
		return $list [$id];
	}
	$dao = D ( "Role" );
	$list = $dao->findAll ( array ('field' => 'id,name' ) );
	foreach ( $list as $vo ) {
		$nameList [$vo ['id']] = $vo ['name'];
	}
	$name = $nameList [$id];
	F ( 'groupName', $nameList );
	return $name;
}

function getGroupNameByUserId($id) {
	$RoleUser = M ( "RoleUser" );
	$roleIdList = $RoleUser->where ( "user_id=$id" )->find ();
	$roleId = $roleIdList ['role_id'];
	if ($roleId == 0) {
		return '无权限组';
	}

	$dao = D ( "Role" );
	$list = $dao->findAll ( array ('field' => 'id,name' ) );
	foreach ( $list as $vo ) {
		$nameList [$vo ['id']] = $vo ['name'];
	}
	$name = $nameList [$roleId];
	return $name;
}

function sort_by($array, $keyname = null, $sortby = 'asc') {
	$myarray = $inarray = array ();
	// First store the keyvalues in a seperate array
	foreach ( $array as $i => $befree ) {
		$myarray [$i] = $array [$i] [$keyname];
	}
	// Sort the new array by
	switch ($sortby) {
	case 'asc' :
		// Sort an array and maintain index association...
		asort ( $myarray );
		break;
	case 'desc' :
	case 'arsort' :
		// Sort an array in reverse order and maintain index association
		arsort ( $myarray );
		break;
	case 'natcasesor' :
		// Sort an array using a case insensitive "natural order" algorithm
		natcasesort ( $myarray );
		break;
	}
	// Rebuild the old array
	foreach ( $myarray as $key => $befree ) {
		$inarray [] = $array [$key];
	}
	return $inarray;
}

/**
 * +----------------------------------------------------------
 * 产生随机字串，可用来自动生成密码
 * 默认长度6位 字母和数字混合 支持中文
 * +----------------------------------------------------------
 *
 * @param $len string
 *       	 长度
 * @param $type string
 *       	 字串类型
 *       	 0 字母 1 数字 其它 混合
 * @param $addChars string
 *       	 额外字符
 *       	 +----------------------------------------------------------
 * @return string +----------------------------------------------------------
 */
function rand_string($len = 6, $type = '', $addChars = '') {
	$str = '';
	switch ($type) {
	case 0 :
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . $addChars;
		break;
	case 1 :
		$chars = str_repeat ( '0123456789', 3 );
		break;
	case 2 :
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $addChars;
		break;
	case 3 :
		$chars = 'abcdefghijklmnopqrstuvwxyz' . $addChars;
		break;
	default :
		// 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
		$chars = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789' . $addChars;
		break;
	}
	if ($len > 10) { // 位数过长重复字符串一定次数
		$chars = $type == 1 ? str_repeat ( $chars, $len ) : str_repeat ( $chars, 5 );
	}
	if ($type != 4) {
		$chars = str_shuffle ( $chars );
		$str = substr ( $chars, 0, $len );
	} else {
		// 中文随机字
		for($i = 0; $i < $len; $i ++) {
			$str .= msubstr ( $chars, floor ( mt_rand ( 0, mb_strlen ( $chars, 'utf-8' ) - 1 ) ), 1 );
		}
	}
	return $str;
}

function pwdHash($password, $type = 'md5') {
	return hash ( $type, $password );
}

/**
 * +----------------------------------------------------------
 * 把返回的数据集转换成Tree
 * +----------------------------------------------------------
 *
 * @access public
 *         +----------------------------------------------------------
 * @param $list array
 *       	 要转换的数据集
 * @param $pid string
 *       	 parent标记字段
 * @param $level string
 *       	 level标记字段
 *       	 +----------------------------------------------------------
 * @return array +----------------------------------------------------------
 */
function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0) {
	// 创建Tree
	$tree = array ();
	if (is_array ( $list )) {
		// 创建基于主键的数组引用
		$refer = array ();
		foreach ( $list as $key => $data ) {
			$refer [$data [$pk]] = & $list [$key];
		}
		foreach ( $list as $key => $data ) {
			// 判断是否存在parent
			$parentId = $data [$pid];
			if ($root == $parentId) {
				$tree [] = & $list [$key];
			} else {
				if (isset ( $refer [$parentId] )) {
					$parent = & $refer [$parentId];
					$parent [$child] [] = & $list [$key];
				}
			}
		}
	}
	return $tree;
}

/**
 * 获取指定月份的第一天开始和最后一天结束的时间戳
 *
 * @param $y int
 *       	 年份 $m 月份
 * @return array(本月开始时间，本月结束时间)
 */
function datetimeFristAndLast() {
	$t = time ();
	$t1 = mktime ( 0, 0, 0, date ( "m", $t ), date ( "d", $t ), date ( "Y", $t ) );
	$t2 = mktime ( 0, 0, 0, date ( "m", $t ), 1, date ( "Y", $t ) );
	$t3 = mktime ( 0, 0, 0, date ( "m", $t ) - 1, 1, date ( "Y", $t ) );
	$t4 = mktime ( 0, 0, 0, 1, 1, date ( "Y", $t ) );
	$e1 = mktime ( 23, 59, 59, date ( "m", $t ), date ( "d", $t ), date ( "Y", $t ) );
	$e2 = mktime ( 23, 59, 59, date ( "m", $t ), date ( "t" ), date ( "Y", $t ) );
	$e3 = mktime ( 23, 59, 59, date ( "m", $t ) - 1, date ( "t", $t3 ), date ( "Y", $t ) );
	$e4 = mktime ( 23, 59, 59, 12, 31, date ( "Y", $t ) );
	/*
	 * //测试 echo date("当前 Y-m-d H:i:s",$t)." $t<br>"; echo date("今天起点 Y-m-d
	 * H:i:s",$t1)." $t1<br>"; echo date("今月起点 Y-m-d H:i:s",$t2)." $t2<br>";
	 * echo date("上月起点 Y-m-d H:i:s",$t3)." $t3<br>"; echo date("今年起点 Y-m-d
	 * H:i:s",$t4)." $t4<br>"; //测试 echo date("今天终点 Y-m-d H:i:s",$e1)."
	 * $e1<br>"; echo date("今月终点 Y-m-d H:i:s",$e2)." $e2<br>"; echo date("上月终点
	 * Y-m-d H:i:s",$e3)." $e3<br>"; echo date("今年终点 Y-m-d H:i:s",$e4)."
	 * $e4<br>";
	 */
	$returnTime = array ();
	$returnTime ['now'] = $t;
	$returnTime ['todaybegintime'] = $t1;
	$returnTime ['thismonthbegintime'] = $t2;
	$returnTime ['lastmonthbegintime'] = $t3;
	$returnTime ['thisyearbegintime'] = $t4;
	$returnTime ['todayendtime'] = $e1;
	$returnTime ['thismonthendtime'] = $e2;
	$returnTime ['lastmonthendtime'] = $e3;
	$returnTime ['thisyearendtime'] = $e4;
	return $returnTime;
}

/*
 * 比较时间段一与时间段二是否有交集
 */

function isMixTime($begintime1, $endtime1, $begintime2, $endtime2) {
	$status = $begintime2 - $begintime1;
	if ($status > 0) {
		$status2 = $begintime2 - $endtime1;
		if ($status2 > 0) {
			return false;
		} else {
			return true;
		}
	} else {
		$status2 = $begintime1 - $endtime2;
		if ($status2 > 0) {
			return false;
		} else {
			return true;
		}
	}
	return false;
}

/**
 * 转化 \ 为 /
 *
 * @param $path string       	
 * @return string
 */
function dir_path($path) {
	$path = str_replace ( '\\', '/', $path );
	if (substr ( $path, - 1 ) != '/')
		$path = $path . '/';
	return $path;
}

function dir_path2($path) {
	$path = str_replace ( '/', '\\', $path );
	if (substr ( $path, - 1 ) != '\\')
		$path = $path . '\\';
	return $path;
}

/**
 * 创建目录
 *
 * @param $path string       	
 * @param $mode string       	
 * @return string
 */

/**
 * 创建目录
 *
 * @param $path string       	
 * @param $mode string       	
 * @return string
 */
function dir_create($path, $mode = 0777) {
	if (is_dir ( $path ))
		return TRUE;
	$ftp_enable = 0;
	$path = dir_path ( $path );
	$temp = explode ( '/', $path );
	$cur_dir = '';
	$max = count ( $temp ) - 1;
	for($i = 0; $i < $max; $i ++) {
		$cur_dir .= $temp [$i] . '/';
		if (@is_dir ( $cur_dir ))
			continue;
		@mkdir ( $cur_dir, 0777, true );
		@chmod ( $cur_dir, 0777 );
	}
	return is_dir ( $path );
}

/**
 * 拷贝目录及下面所有文件
 *
 * @param $fromdir string       	
 * @param $todir string       	
 * @return string
 */
function dir_copy($fromdir, $todir) {
	$fromdir = dir_path ( $fromdir );
	$todir = dir_path ( $todir );
	if (! is_dir ( $fromdir ))
		return FALSE;
	if (! is_dir ( $todir ))
		dir_create ( $todir );
	$list = glob ( $fromdir . '*' );
	if (! empty ( $list )) {
		foreach ( $list as $v ) {
			$path = $todir . basename ( $v );
			if (is_dir ( $v )) {
				dir_copy ( $v, $path );
			} else {
				copy ( $v, $path );
				@chmod ( $path, 0777 );
			}
		}
	}
	return TRUE;
}

/**
 * 转换目录下面的所有文件编码格式
 *
 * @param $in_charset string       	
 * @param $out_charset string       	
 * @param $dir string       	
 * @param $fileexts string       	
 * @return string
 */
function dir_iconv($in_charset, $out_charset, $dir, $fileexts = 'php|html|htm|shtml|shtm|js|txt|xml') {
	if ($in_charset == $out_charset)
		return false;
	$list = dir_list ( $dir );
	foreach ( $list as $v ) {
		if (preg_match ( "/\.($fileexts)/i", $v ) && is_file ( $v )) {
			file_put_contents ( $v, iconv ( $in_charset, $out_charset, file_get_contents ( $v ) ) );
		}
	}
	return true;
}

/**
 * 列出目录下所有文件
 *
 * @param $path string       	
 * @param $exts string       	
 * @param $list array       	
 * @return array
 */
function dir_list($path, $exts = '', $list = array()) {
	$path = dir_path ( $path );
	$files = glob ( $path . '*' );
	foreach ( $files as $v ) {
		$fileext = fileext ( $v );
		if (! $exts || preg_match ( "/\.($exts)/i", $v )) {
			$list [] = $v;
			if (is_dir ( $v )) {
				$list = dir_list ( $v, $exts, $list );
			}
		}
	}
	return $list;
}

/**
 * 设置目录下面的所有文件的访问和修改时间
 *
 * @param $path string       	
 * @param $mtime int       	
 * @param $atime int       	
 * @return array true
 */
function dir_touch($path, $mtime = TIME, $atime = TIME) {
	if (! is_dir ( $path ))
		return false;
	$path = dir_path ( $path );
	if (! is_dir ( $path ))
		touch ( $path, $mtime, $atime );
	$files = glob ( $path . '*' );
	foreach ( $files as $v ) {
		is_dir ( $v ) ? dir_touch ( $v, $mtime, $atime ) : touch ( $v, $mtime, $atime );
	}
	return true;
}

/**
 * 目录列表
 *
 * @param $dir string       	
 * @param $parentid int       	
 * @param $dirs array       	
 * @return array
 */
function dir_tree($dir, $parentid = 0, $dirs = array()) {
	global $id;
	if ($parentid == 0)
		$id = 0;
	$list = glob ( $dir . '*' );
	foreach ( $list as $v ) {
		if (is_dir ( $v )) {
			$id ++;
			$dirs [$id] = array ('id' => $id, 'parentid' => $parentid, 'name' => basename ( $v ), 'dir' => $v . '/' );
			$dirs = dir_tree ( $v . '/', $id, $dirs );
		}
	}
	return $dirs;
}

/**
 * 删除目录及目录下面的所有文件
 *
 * @param $dir string       	
 * @return bool TRUE，失败则返回 FALSE
 */
function dir_delete($dir) {
	$dir = dir_path ( $dir );
	if (! is_dir ( $dir ))
		return FALSE;
	$list = glob ( $dir . '*' );
	foreach ( $list as $v ) {
		is_dir ( $v ) ? dir_delete ( $v ) : @unlink ( $v );
	}
	return @rmdir ( $dir );
}
/**
 * *跟据车model得到车辆信息
 */
function getCarmodel($car_model_code, $condition = '') {
	$Model = M ( "Location","AdvModel" );
	$Model->addConnect ( C ( "DB_CRS" ), 1 );
	$Model->switchConnect ( 1, "car_model" );
	// $model = M ( 'car_model' );
	$cartype = $Model->getByCarModelCode ( $car_model_code );
	// Log::write ( '调试的SQL：' . $model->getLastSql (), Log::SQL );
	// echo $model->getLastSql();
	return $cartype [$condition];
}
/**
 * *跟据车type得到车辆信息
 */
function getCartype($car_type_code, $condition = '') {
	$Model =M( "Location","AdvModel" );
	$Model->addConnect ( C ( "DB_CRS" ), 1 );
	$Model->switchConnect ( 1, "car_type" );
	// $model = M ( 'car_type' );
	$cartype = $Model->getByCarTypeCode ( $car_type_code );
	// echo $model->getLastSql();
	return $cartype [$condition];
}
/*
 * 增值服务2012-06-08
 */
function getlocationopt($localcode, $confirmation='') {
    //Date:20120801 16:16
	$Model = M ( "Location","AdvModel" );
	$Model->addConnect ( C ( "DB_CRS" ), 1 );
	$map2 ['confirmation'] = $localcode . '-' . $confirmation;
	$map ['location_code'] = $localcode;
	$Model->switchConnect ( 1, "reservation_option" );
	$reserOpt = $Model->where ( $map2 )->select ();
	$html = "";
	$perunit = '';
	$real_inv = '';
	if ($reserOpt) {
		foreach ( $reserOpt as $k => $v ) {
			if ($v['MANDATORY']=='Y') {
				$disabled = "disabled";
			}
			$html .= '<label class="checkbox inline">
				<input type="checkbox" name="option_r[]" '.$disabled.' class="checkbox-option" checked="true"  value="' . $v ['OPTION_ID'] . '" />
				' . $v ['OPTION_NAME'] . '
				</label> ';
			$optionID[] = $v['OPTION_ID'];
		}
	}
    $Model->switchConnect ( 1, "uni_option" );
    $wh ='';
	if(!empty($reserOpt)){
        $wh.=" and option_id not in (".implode(',',$optionID).")";
	}
    $listOpt = $Model->where ( "location_code='".$localcode."' and (rate_code='LOC' or rate_code is NULL) AND LEFT(NOW(),10)=START_DATE ".$wh )->select ();

        Log::write('增值服务SQL：'.$Model->getLastSql(), Log::SQL);
	$Model->switchConnect ( 1, "options" );
	$options = $Model->getField('option_id,option_name');
	if ($listOpt) {
		foreach ( $listOpt as $key => $val ) {
            if ($val['MANDATORY']=='Y') {
				$o= "disabled checked";
				
			}else{
				$o='';
			}
			$optionid = $val['OPTION_ID'];
			$html .= '<label class="checkbox inline">
				<input type="checkbox" name="option_u[]" '.$o.' class="checkbox-option" value="' . $val ['UNI_OPTION_ID'] . '" />
				' . $options[$optionid] . '
				</label> ';
		}
	}
	return $html;
}
/*
 * 增值服务价格
 */
/**
 * function optionPrice($localcode, $confirmation, $xday) {
	// echo $xday;
	$Model = M ( "Location","AdvModel" );
	$Model->addConnect ( C ( "DB_CRS" ), 1 );
	$Model->switchConnect ( 1, "location_option" );
	// $locationOpt = M ( 'location_option' );
	$map ['location_code'] = $localcode;
	$listOpt = $Model->where ( $map )->group ( 'option_id' )->select ();
	$html = "";
	$perunit = '';
	if ($listOpt) {
		foreach ( $listOpt as $key => $val ) {
			switch ($val ['PER_UNIT']) {
			case 1 :
				$perunit = '次';
				break;
			case 'D' :
				$perunit = '天';
				break;
			case 'H' :
				$perunit = '小时';
				break;
			case 'W' :
				$perunit = '周';
				break;
			case 'M' :
				$perunit = '月';
				break;
			case 'X' :
				$perunit = '周末';
				break;
			}
			if ($val ['REAL_INV']) {
				$real_inv = $val ['REAL_INV'];
			}
			$html .= ' <div class="cost" id="cost-option-' . $val ['OPTION_ID'] . '" style="display: none;">
				<span class="item">' . $val ['LOCATION_OPTION_DESC'] . '</span>
				<span class="price">
				<span class="option-qty">' . $xday . '</span>
				<span class="option-unit">' . $perunit . '</span> ×
				<span class="option-rate">' . $val ['RATE'] . '</span> 元 ＝
				<span class="option-amt">' . $xday * $val ['RATE'] . '</span> 元
				</span>
				</div>';
		}

	}
	return $html;
 }
    **/
function convert_stat($stat){

	switch($stat){
	case 'NOPREPAY':
		return '<td style="background:#ffff00;">未支付</td>';	
	case 'PAID':
		return '<td style="background:#ff0066;">已支付</td>';
	case 'CANCEL':
		return '<td style="background:#66ccff;">取消预订</td>';
	case "PICKUP":
		return "<td style='background:#00ffff;'>已取车</td>";
	case "CONTRACT":
		return "<td id='statusCar' style='background:#ff6600;'>已生成</td>";
	case "RETURN":
		return "<td style='background:#009900;'>已还车</td>";
	}
}
function getOptions($confirmation) {
	$Model = M ( "Location","AdvModel" );
	$Model->addConnect ( C ( "DB_CRS" ), 1 );
	$Model->switchConnect ( 1, "agreement_option" );
	$reserOpt = $Model->where("CONFIRMATION='".$confirmation."'")->getField("OPTION_ID,OPTION_NAME");
	 //echo $Model->getLastSql();
	$html = "";
	$perunit = '';
	$real_inv = '';
	if ($reserOpt) {
		foreach ( $reserOpt as $k => $v ) {
			$html .= '<label class="checkbox inline">
				<input type="checkbox" name="option[]" disabled class="checkbox-option" checked="checked"  value="' . $k . '" />
				' . $v . '
				</label> ';
			$optionID[] = $v['OPTION_ID'];
		}
	}

	
	return $html;
}
function options($optionid)
{
	// code...
	$Model = M ( "Location","AdvModel" );
	$Model->addConnect ( C ( "DB_CRS" ), 1 );
	$Model->switchConnect ( 1, "options" );
	$options = $Model->getField('option_id,option_name');
	return $options[$optionid];
}
//二维码
function generateQRfromGoogle($chl,$widhtHeight ='100',$EC_level='L',$margin='0')
{
 echo '<img src="http://chart.apis.google.com/chart?chs='.$widhtHeight.'x'.$widhtHeight.'&cht=qr&chld='.$EC_level.'|'.$margin.'&chl='.$chl.'" alt="QR code" widhtHeight="'.$size.'" widhtHeight="'.$size.'" />';
}
function getMemberName($membertypeid){
	$Model = M ( "Location","AdvModel" );
	$Model->addConnect ( C ( "DB_CRS" ), 1 );
	$Model->switchConnect ( 1, "member_type" );
	$list = $Model->getByMemberTypeId($membertypeid);
	return $list['MEMBER_TYPE_NAME'];
}
function getObjInfo($condition,$table,$column,$return){

	$Model = M ( "Location","AdvModel" );
	$Model->addConnect ( C ( "DB_CRS" ), 1 );
	$Model->switchConnect ( 1, $table );
	$column = 'getBy'.$column; 
	$list = $Model->$column($condition);
	//echo $Model->getLastSql(); 
	//echo $return;exit;
	return $list[$return];
	
}
function getDeposit($location_code,$carmodelcode){
	$Model = M ( "Location","AdvModel" );
	$Model->addConnect ( C ( "DB_CRS" ), 1 );
	$Model->switchConnect ( 1, 'location_cartype_model' );
	$map['CAR_MODEL_CODE'] = $carmodelcode;
	$map['LOCATION_CODE'] = $location_code;
	$list =$Model->where($map)->find();
	return $list['DEPOSIT'];

}
function encrypt_url($url,$key)  
{  
    return rawurlencode(base64_encode(encrypt($url,$key)));  
}  
function decrypt_url($url,$key)  
{  
    return decrypt(base64_decode(rawurldecode($url)),$key);  
}

?>
