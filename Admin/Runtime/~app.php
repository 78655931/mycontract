<?php  function toDate($time, $format = 'Y-m-d H:i:s') { if (empty ( $time )) { return ''; } $format = str_replace ( '#', ':', $format ); return date ( $format, $time ); } function get_client_ip() { if (getenv ( "HTTP_CLIENT_IP" ) && strcasecmp ( getenv ( "HTTP_CLIENT_IP" ), "unknown" )) $ip = getenv ( "HTTP_CLIENT_IP" ); else if (getenv ( "HTTP_X_FORWARDED_FOR" ) && strcasecmp ( getenv ( "HTTP_X_FORWARDED_FOR" ), "unknown" )) $ip = getenv ( "HTTP_X_FORWARDED_FOR" ); else if (getenv ( "REMOTE_ADDR" ) && strcasecmp ( getenv ( "REMOTE_ADDR" ), "unknown" )) $ip = getenv ( "REMOTE_ADDR" ); else if (isset ( $_SERVER ['REMOTE_ADDR'] ) && $_SERVER ['REMOTE_ADDR'] && strcasecmp ( $_SERVER ['REMOTE_ADDR'], "unknown" )) $ip = $_SERVER ['REMOTE_ADDR']; else $ip = "unknown"; return ($ip); } function cmssavecache($name = '', $fields = '') { $Model = D ( $name ); $list = $Model->select (); $data = array (); foreach ( $list as $key => $val ) { if (empty ( $fields )) { $data [$val [$Model->getPk ()]] = $val; } else { if (is_string ( $fields )) { $fields = explode ( ',', $fields ); } if (count ( $fields ) == 1) { $data [$val [$Model->getPk ()]] = $val [$fields [0]]; } else { foreach ( $fields as $field ) { $data [$val [$Model->getPk ()]] [] = $val [$field]; } } } } $savefile = cmsgetcache ( $name ); $content = "<?php\nreturn " . var_export ( array_change_key_case ( $data, CASE_UPPER ), true ) . ";\n?>"; file_put_contents ( $savefile, $content ); } function cmsgetcache($name = '') { return DATA_PATH . '~' . strtolower ( $name ) . '.php'; } function getStatus($status, $imageShow = true) { switch ($status) { case 0 : $showText = '禁用'; $showImg = '<IMG SRC="' . WEB_PUBLIC_PATH . '/Images/locked.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="禁用">'; break; case 2 : $showText = '待审'; $showImg = '<IMG SRC="' . WEB_PUBLIC_PATH . '/Images/prected.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="待审">'; break; case - 1 : $showText = '删除'; $showImg = '<IMG SRC="' . WEB_PUBLIC_PATH . '/Images/del.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="删除">'; break; case 1 : default : $showText = '正常'; $showImg = '<IMG SRC="' . WEB_PUBLIC_PATH . '/Images/ok.gif" WIDTH="20" HEIGHT="20" BORDER="0" ALT="正常">'; } return ($imageShow === true) ? $showImg : $showText; } function getOilpercent($oil) { switch ($oil) { case 0: return "空"; case 1: return "1/4"; case 2: return "半箱"; case '3': return "3/4"; default: return "满箱"; } } function getDefaultStyle($style) { if (empty ( $style )) { return 'blue'; } else { return $style; } } function IP($ip = '', $file = 'UTFWry.dat') { $_ip = array (); if (isset ( $_ip [$ip] )) { return $_ip [$ip]; } else { import ( "ORG.Net.IpLocation" ); $iplocation = new IpLocation ( $file ); $location = $iplocation->getlocation ( $ip ); $_ip [$ip] = $location ['country'] . $location ['area']; } return $_ip [$ip]; } function getNodeName($id) { if (Session::is_set ( 'nodeNameList' )) { $name = Session::get ( 'nodeNameList' ); return $name [$id]; } $Group = D ( "Node" ); $list = $Group->getField ( 'id,name' ); $name = $list [$id]; Session::set ( 'nodeNameList', $list ); return $name; } function get_pawn($pawn) { if ($pawn == 0) return "<span style='color:green'>没有</span>"; else return "<span style='color:red'>有</span>"; } function get_patent($patent) { if ($patent == 0) return "<span style='color:green'>没有</span>"; else return "<span style='color:red'>有</span>"; } function getNodeGroupName($id) { if (empty ( $id )) { return '未分组'; } if (isset ( $_SESSION ['nodeGroupList'] )) { return $_SESSION ['nodeGroupList'] [$id]; } $Group = D ( "Group" ); $list = $Group->getField ( 'id,title' ); $_SESSION ['nodeGroupList'] = $list; $name = $list [$id]; return $name; } function getCardStatus($status) { switch ($status) { case 0 : $show = '未启用'; break; case 1 : $show = '已启用'; break; case 2 : $show = '使用中'; break; case 3 : $show = '已禁用'; break; case 4 : $show = '已作废'; break; } return $show; } function showStatus($status, $id, $callback = "") { switch ($status) { case 0 : $info = '<a href="__URL__/resume/id/' . $id . '/navTabId/__MODULE__" target="ajaxTodo" callback="' . $callback . '">恢复</a>'; break; case 2 : $info = '<a href="__URL__/pass/id/' . $id . '/navTabId/__MODULE__" target="ajaxTodo" callback="' . $callback . '">批准</a>'; break; case 1 : $info = '<a href="__URL__/forbid/id/' . $id . '/navTabId/__MODULE__" target="ajaxTodo" callback="' . $callback . '">禁用</a>'; break; case - 1 : $info = '<a href="__URL__/recycle/id/' . $id . '/navTabId/__MODULE__" target="ajaxTodo" callback="' . $callback . '">还原</a>'; break; } return $info; } function showDriverStatus($status, $id, $callback = "") { switch ($status) { case 0 : $info = '<a href="__URL__/resume/id/' . $id . '/navTabId/__MODULE__" target="ajaxTodo" callback="' . $callback . '">出车</a>'; break; case 2 : $info = '<a href="__URL__/pass/id/' . $id . '/navTabId/__MODULE__" target="ajaxTodo" callback="' . $callback . '">批准</a>'; break; case 1 : $info = '<a href="__URL__/forbid/id/' . $id . '/navTabId/__MODULE__" target="ajaxTodo" callback="' . $callback . '">未出车</a>'; break; case - 1 : $info = '<a href="__URL__/recycle/id/' . $id . '/navTabId/__MODULE__" target="ajaxTodo" callback="' . $callback . '">还原</a>'; break; } return $info; } function isshow($status) { switch ($status) { case 0 : $info = '隐藏'; break; case 1 : $info = '显示'; break; } return $info; } function build_verify($length = 4, $mode = 1) { return rand_string ( $length, $mode ); } function getGroupName($id) { if ($id == 0) { return '无上级组'; } if ($list = F ( 'groupName' )) { return $list [$id]; } $dao = D ( "Role" ); $list = $dao->findAll ( array ('field' => 'id,name' ) ); foreach ( $list as $vo ) { $nameList [$vo ['id']] = $vo ['name']; } $name = $nameList [$id]; F ( 'groupName', $nameList ); return $name; } function getGroupNameByUserId($id) { $RoleUser = M ( "RoleUser" ); $roleIdList = $RoleUser->where ( "user_id=$id" )->find (); $roleId = $roleIdList ['role_id']; if ($roleId == 0) { return '无权限组'; } $dao = D ( "Role" ); $list = $dao->findAll ( array ('field' => 'id,name' ) ); foreach ( $list as $vo ) { $nameList [$vo ['id']] = $vo ['name']; } $name = $nameList [$roleId]; return $name; } function sort_by($array, $keyname = null, $sortby = 'asc') { $myarray = $inarray = array (); foreach ( $array as $i => $befree ) { $myarray [$i] = $array [$i] [$keyname]; } switch ($sortby) { case 'asc' : asort ( $myarray ); break; case 'desc' : case 'arsort' : arsort ( $myarray ); break; case 'natcasesor' : natcasesort ( $myarray ); break; } foreach ( $myarray as $key => $befree ) { $inarray [] = $array [$key]; } return $inarray; } function rand_string($len = 6, $type = '', $addChars = '') { $str = ''; switch ($type) { case 0 : $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . $addChars; break; case 1 : $chars = str_repeat ( '0123456789', 3 ); break; case 2 : $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $addChars; break; case 3 : $chars = 'abcdefghijklmnopqrstuvwxyz' . $addChars; break; default : $chars = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789' . $addChars; break; } if ($len > 10) { $chars = $type == 1 ? str_repeat ( $chars, $len ) : str_repeat ( $chars, 5 ); } if ($type != 4) { $chars = str_shuffle ( $chars ); $str = substr ( $chars, 0, $len ); } else { for($i = 0; $i < $len; $i ++) { $str .= msubstr ( $chars, floor ( mt_rand ( 0, mb_strlen ( $chars, 'utf-8' ) - 1 ) ), 1 ); } } return $str; } function pwdHash($password, $type = 'md5') { return hash ( $type, $password ); } function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = '_child', $root = 0) { $tree = array (); if (is_array ( $list )) { $refer = array (); foreach ( $list as $key => $data ) { $refer [$data [$pk]] = & $list [$key]; } foreach ( $list as $key => $data ) { $parentId = $data [$pid]; if ($root == $parentId) { $tree [] = & $list [$key]; } else { if (isset ( $refer [$parentId] )) { $parent = & $refer [$parentId]; $parent [$child] [] = & $list [$key]; } } } } return $tree; } function datetimeFristAndLast() { $t = time (); $t1 = mktime ( 0, 0, 0, date ( "m", $t ), date ( "d", $t ), date ( "Y", $t ) ); $t2 = mktime ( 0, 0, 0, date ( "m", $t ), 1, date ( "Y", $t ) ); $t3 = mktime ( 0, 0, 0, date ( "m", $t ) - 1, 1, date ( "Y", $t ) ); $t4 = mktime ( 0, 0, 0, 1, 1, date ( "Y", $t ) ); $e1 = mktime ( 23, 59, 59, date ( "m", $t ), date ( "d", $t ), date ( "Y", $t ) ); $e2 = mktime ( 23, 59, 59, date ( "m", $t ), date ( "t" ), date ( "Y", $t ) ); $e3 = mktime ( 23, 59, 59, date ( "m", $t ) - 1, date ( "t", $t3 ), date ( "Y", $t ) ); $e4 = mktime ( 23, 59, 59, 12, 31, date ( "Y", $t ) ); $returnTime = array (); $returnTime ['now'] = $t; $returnTime ['todaybegintime'] = $t1; $returnTime ['thismonthbegintime'] = $t2; $returnTime ['lastmonthbegintime'] = $t3; $returnTime ['thisyearbegintime'] = $t4; $returnTime ['todayendtime'] = $e1; $returnTime ['thismonthendtime'] = $e2; $returnTime ['lastmonthendtime'] = $e3; $returnTime ['thisyearendtime'] = $e4; return $returnTime; } function isMixTime($begintime1, $endtime1, $begintime2, $endtime2) { $status = $begintime2 - $begintime1; if ($status > 0) { $status2 = $begintime2 - $endtime1; if ($status2 > 0) { return false; } else { return true; } } else { $status2 = $begintime1 - $endtime2; if ($status2 > 0) { return false; } else { return true; } } return false; } function dir_path($path) { $path = str_replace ( '\\', '/', $path ); if (substr ( $path, - 1 ) != '/') $path = $path . '/'; return $path; } function dir_path2($path) { $path = str_replace ( '/', '\\', $path ); if (substr ( $path, - 1 ) != '\\') $path = $path . '\\'; return $path; } function dir_create($path, $mode = 0777) { if (is_dir ( $path )) return TRUE; $ftp_enable = 0; $path = dir_path ( $path ); $temp = explode ( '/', $path ); $cur_dir = ''; $max = count ( $temp ) - 1; for($i = 0; $i < $max; $i ++) { $cur_dir .= $temp [$i] . '/'; if (@is_dir ( $cur_dir )) continue; @mkdir ( $cur_dir, 0777, true ); @chmod ( $cur_dir, 0777 ); } return is_dir ( $path ); } function dir_copy($fromdir, $todir) { $fromdir = dir_path ( $fromdir ); $todir = dir_path ( $todir ); if (! is_dir ( $fromdir )) return FALSE; if (! is_dir ( $todir )) dir_create ( $todir ); $list = glob ( $fromdir . '*' ); if (! empty ( $list )) { foreach ( $list as $v ) { $path = $todir . basename ( $v ); if (is_dir ( $v )) { dir_copy ( $v, $path ); } else { copy ( $v, $path ); @chmod ( $path, 0777 ); } } } return TRUE; } function dir_iconv($in_charset, $out_charset, $dir, $fileexts = 'php|html|htm|shtml|shtm|js|txt|xml') { if ($in_charset == $out_charset) return false; $list = dir_list ( $dir ); foreach ( $list as $v ) { if (preg_match ( "/\.($fileexts)/i", $v ) && is_file ( $v )) { file_put_contents ( $v, iconv ( $in_charset, $out_charset, file_get_contents ( $v ) ) ); } } return true; } function dir_list($path, $exts = '', $list = array()) { $path = dir_path ( $path ); $files = glob ( $path . '*' ); foreach ( $files as $v ) { $fileext = fileext ( $v ); if (! $exts || preg_match ( "/\.($exts)/i", $v )) { $list [] = $v; if (is_dir ( $v )) { $list = dir_list ( $v, $exts, $list ); } } } return $list; } function dir_touch($path, $mtime = TIME, $atime = TIME) { if (! is_dir ( $path )) return false; $path = dir_path ( $path ); if (! is_dir ( $path )) touch ( $path, $mtime, $atime ); $files = glob ( $path . '*' ); foreach ( $files as $v ) { is_dir ( $v ) ? dir_touch ( $v, $mtime, $atime ) : touch ( $v, $mtime, $atime ); } return true; } function dir_tree($dir, $parentid = 0, $dirs = array()) { global $id; if ($parentid == 0) $id = 0; $list = glob ( $dir . '*' ); foreach ( $list as $v ) { if (is_dir ( $v )) { $id ++; $dirs [$id] = array ('id' => $id, 'parentid' => $parentid, 'name' => basename ( $v ), 'dir' => $v . '/' ); $dirs = dir_tree ( $v . '/', $id, $dirs ); } } return $dirs; } function dir_delete($dir) { $dir = dir_path ( $dir ); if (! is_dir ( $dir )) return FALSE; $list = glob ( $dir . '*' ); foreach ( $list as $v ) { is_dir ( $v ) ? dir_delete ( $v ) : @unlink ( $v ); } return @rmdir ( $dir ); } function getCarmodel($car_model_code, $condition = '') { $Model = M ( "Location","AdvModel" ); $Model->addConnect ( C ( "DB_CRS" ), 1 ); $Model->switchConnect ( 1, "car_model" ); $cartype = $Model->getByCarModelCode ( $car_model_code ); return $cartype [$condition]; } function getCartype($car_type_code, $condition = '') { $Model =M( "Location","AdvModel" ); $Model->addConnect ( C ( "DB_CRS" ), 1 ); $Model->switchConnect ( 1, "car_type" ); $cartype = $Model->getByCarTypeCode ( $car_type_code ); return $cartype [$condition]; } function getlocationopt($localcode, $confirmation='') { $Model = M ( "Location","AdvModel" ); $Model->addConnect ( C ( "DB_CRS" ), 1 ); $map2 ['confirmation'] = $localcode . '-' . $confirmation; $map ['location_code'] = $localcode; $Model->switchConnect ( 1, "reservation_option" ); $reserOpt = $Model->where ( $map2 )->select (); $html = ""; $perunit = ''; $real_inv = ''; if ($reserOpt) { foreach ( $reserOpt as $k => $v ) { if ($v['MANDATORY']=='Y') { $disabled = "disabled"; } $html .= '<label class="checkbox inline">
				<input type="checkbox" name="option[]" '.$disabled.' class="checkbox-option" checked="true"  value="' . $v ['OPTION_ID'] . '" />
				' . $v ['OPTION_NAME'] . '
				</label> '; $optionID[] = $v['OPTION_ID']; } } $Model->switchConnect ( 1, "location_option" ); if(!empty($reserOpt)){ $map['OPTION_ID'] = array('not in',$optionID); } $listOpt = $Model->where ( $map )->group ( 'option_id' )->order('MANDATORY="Y" desc')->select (); $Model->switchConnect ( 1, "options" ); $options = $Model->getField('option_id,option_name'); if ($listOpt) { foreach ( $listOpt as $key => $val ) { if ($val ['REAL_INV']) { $real_inv = $val ['REAL_INV']; } if ($val['MANDATORY']=='Y') { $o= "disabled checked"; }else{ $o=''; } $optionid = $val['OPTION_ID']; $html .= '<label class="checkbox inline">
				<input type="checkbox" name="option[]" '.$o.' class="checkbox-option" value="' . $val ['OPTION_ID'] . '" />
				' . $options[$optionid] . '
				</label> '; } } return $html; } function optionPrice($localcode, $confirmation, $xday) { $Model = M ( "Location","AdvModel" ); $Model->addConnect ( C ( "DB_CRS" ), 1 ); $Model->switchConnect ( 1, "location_option" ); $map ['location_code'] = $localcode; $listOpt = $Model->where ( $map )->group ( 'option_id' )->select (); $html = ""; $perunit = ''; if ($listOpt) { foreach ( $listOpt as $key => $val ) { switch ($val ['PER_UNIT']) { case 1 : $perunit = '次'; break; case 'D' : $perunit = '天'; break; case 'H' : $perunit = '小时'; break; case 'W' : $perunit = '周'; break; case 'M' : $perunit = '月'; break; case 'X' : $perunit = '周末'; break; } if ($val ['REAL_INV']) { $real_inv = $val ['REAL_INV']; } $html .= ' <div class="cost" id="cost-option-' . $val ['OPTION_ID'] . '" style="display: none;">
				<span class="item">' . $val ['LOCATION_OPTION_DESC'] . '</span>
				<span class="price">
				<span class="option-qty">' . $xday . '</span>
				<span class="option-unit">' . $perunit . '</span> ×
				<span class="option-rate">' . $val ['RATE'] . '</span> 元 ＝
				<span class="option-amt">' . $xday * $val ['RATE'] . '</span> 元
				</span>
				</div>'; } } return $html; } function convert_stat($stat){ switch($stat){ case 'NOPREPAY': return '<td style="background:#ffff00;">未支付</td>'; case 'PAID': return '<td style="background:#ff0066;">已支付</td>'; case 'CANCEL': return '<td style="background:#66ccff;">取消预订</td>'; case "PICKUP": return "<td style='background:#00ffff;'>已取车</td>"; case "CONTRACT": return "<td id='statusCar' style='background:#ff6600;'>已生成</td>"; case "RETURN": return "<td style='background:#009900;'>已还车</td>"; } } function getOptions($confirmation) { $Model = M ( "Location","AdvModel" ); $Model->addConnect ( C ( "DB_CRS" ), 1 ); $Model->switchConnect ( 1, "agreement_option" ); $reserOpt = $Model->where("CONFIRMATION='".$confirmation."'")->getField("OPTION_ID,OPTION_NAME"); $html = ""; $perunit = ''; $real_inv = ''; if ($reserOpt) { foreach ( $reserOpt as $k => $v ) { $html .= '<label class="checkbox inline">
				<input type="checkbox" name="option[]" disabled class="checkbox-option" checked="checked"  value="' . $k . '" />
				' . $v . '
				</label> '; $optionID[] = $v['OPTION_ID']; } } return $html; } function options($optionid) { $Model = M ( "Location","AdvModel" ); $Model->addConnect ( C ( "DB_CRS" ), 1 ); $Model->switchConnect ( 1, "options" ); $options = $Model->getField('option_id,option_name'); return $options[$optionid]; } function generateQRfromGoogle($chl,$widhtHeight ='100',$EC_level='L',$margin='0') { echo '<img src="http://chart.apis.google.com/chart?chs='.$widhtHeight.'x'.$widhtHeight.'&cht=qr&chld='.$EC_level.'|'.$margin.'&chl='.$chl.'" alt="QR code" widhtHeight="'.$size.'" widhtHeight="'.$size.'" />'; } function getMemberName($membertypeid){ $Model = M ( "Location","AdvModel" ); $Model->addConnect ( C ( "DB_CRS" ), 1 ); $Model->switchConnect ( 1, "member_type" ); $list = $Model->getByMemberTypeId($membertypeid); return $list['MEMBER_TYPE_NAME']; } function getObjInfo($condition,$table,$column,$return){ $Model = M ( "Location","AdvModel" ); $Model->addConnect ( C ( "DB_CRS" ), 1 ); $Model->switchConnect ( 1, $table ); $column = 'getBy'.$column; $list = $Model->$column($condition); return $list[$return]; } function getDeposit($location_code,$carmodelcode){ $Model = M ( "Location","AdvModel" ); $Model->addConnect ( C ( "DB_CRS" ), 1 ); $Model->switchConnect ( 1, 'location_cartype_model' ); $map['CAR_MODEL_CODE'] = $carmodelcode; $map['LOCATION_CODE'] = $location_code; $list =$Model->where($map)->find(); return $list['DEPOSIT']; } function encrypt_url($url,$key) { return rawurlencode(base64_encode(encrypt($url,$key))); } function decrypt_url($url,$key) { return decrypt(base64_decode(rawurldecode($url)),$key); } return array ( 'app_debug' => false, 'app_domain_deploy' => false, 'app_sub_domain_deploy' => false, 'app_plugin_on' => false, 'app_file_case' => false, 'app_group_depr' => '.', 'app_group_list' => '', 'app_autoload_reg' => false, 'app_autoload_path' => 'Think.Util.', 'app_config_list' => array ( 0 => 'taglibs', 1 => 'routes', 2 => 'tags', 3 => 'htmls', 4 => 'modules', 5 => 'actions', ), 'cookie_expire' => 3600, 'cookie_domain' => '', 'cookie_path' => '/', 'cookie_prefix' => '', 'default_app' => '@', 'default_group' => 'Home', 'default_module' => 'Index', 'default_action' => 'index', 'default_charset' => 'utf-8', 'default_timezone' => 'PRC', 'default_ajax_return' => 'JSON', 'default_theme' => 'default', 'default_lang' => 'zh-cn', 'db_type' => 'mysql', 'db_host' => '117.34.70.20', 'db_name' => 'test', 'db_user' => 'root', 'db_pwd' => '123.com', 'db_port' => '3306', 'db_prefix' => '', 'db_suffix' => '', 'db_fieldtype_check' => false, 'db_fields_cache' => true, 'db_charset' => 'utf8', 'db_deploy_type' => 0, 'db_rw_separate' => false, 'data_cache_time' => -1, 'data_cache_compress' => false, 'data_cache_check' => false, 'data_cache_type' => 'File', 'data_cache_path' => './Runtime/Temp/', 'data_cache_subdir' => false, 'data_path_level' => 1, 'error_message' => '您浏览的页面暂时发生了错误！请稍后再试～', 'error_page' => '', 'html_cache_on' => false, 'html_cache_time' => 60, 'html_read_type' => 0, 'html_file_suffix' => '.shtml', 'lang_switch_on' => false, 'lang_auto_detect' => true, 'log_exception_record' => true, 'log_record' => false, 'log_file_size' => 2097152, 'log_record_level' => array ( 0 => 'EMERG', 1 => 'ALERT', 2 => 'CRIT', 3 => 'ERR', ), 'page_rollpage' => 5, 'page_listrows' => 20, 'session_auto_start' => true, 'show_run_time' => false, 'show_adv_time' => false, 'show_db_times' => false, 'show_cache_times' => false, 'show_use_mem' => false, 'show_page_trace' => false, 'show_error_msg' => true, 'tmpl_engine_type' => 'Think', 'tmpl_detect_theme' => false, 'tmpl_template_suffix' => '.html', 'tmpl_content_type' => 'text/html', 'tmpl_cachfile_suffix' => '.php', 'tmpl_deny_func_list' => 'echo,exit', 'tmpl_parse_string' => '', 'tmpl_l_delim' => '{', 'tmpl_r_delim' => '}', 'tmpl_var_identify' => 'array', 'tmpl_strip_space' => false, 'tmpl_cache_on' => true, 'tmpl_cache_time' => -1, 'tmpl_action_error' => 'Public:success', 'tmpl_action_success' => 'Public:success', 'tmpl_trace_file' => '../ThinkPHP/Tpl/PageTrace.tpl.php', 'tmpl_exception_file' => '../ThinkPHP/Tpl/ThinkException.tpl.php', 'tmpl_file_depr' => '/', 'taglib_begin' => '<', 'taglib_end' => '>', 'taglib_load' => true, 'taglib_build_in' => 'cx', 'taglib_pre_load' => '', 'tag_nested_level' => 3, 'tag_extend_parse' => '', 'token_on' => false, 'token_name' => '__hash__', 'token_type' => 'md5', 'url_case_insensitive' => false, 'url_router_on' => false, 'url_route_rules' => array ( ), 'url_model' => 1, 'url_pathinfo_model' => 2, 'url_pathinfo_depr' => '/', 'url_html_suffix' => '', 'var_group' => 'g', 'var_module' => 'm', 'var_action' => 'a', 'var_router' => 'r', 'var_page' => 'pageNum', 'var_template' => 't', 'var_language' => 'l', 'var_ajax_submit' => 'ajax', 'var_pathinfo' => 's', 'db_crs' => array ( 'dbms' => 'mysql', 'username' => 'root', 'password' => '123.com', 'hostname' => '117.34.70.20', 'hostport' => '3306', 'database' => 'ry_crsengine_db', ), 'user_auth_on' => true, 'user_auth_type' => 2, 'user_auth_key' => 'CONTRACT_AUTH', 'admin_auth_key' => 'administrator', 'user_auth_model' => 'User', 'auth_pwd_encoder' => 'md5', 'user_auth_gateway' => '/Public/login', 'not_auth_module' => 'Public,Index', 'require_auth_module' => '', 'not_auth_action' => '', 'require_auth_action' => 'add,edit,index,foreverdelete', 'guest_auth_on' => false, 'guest_auth_id' => 0, 'brandcode' => 'GONGSI', 'db_like_fields' => 'title|remark', 'rbac_role_table' => 'role', 'rbac_user_table' => 'role_user', 'rbac_access_table' => 'access', 'rbac_node_table' => 'node', 'car' => 'http://172.16.100.47/findCar?', 'discount' => 'http://172.16.100.47:8099/crsCarRental/findDiscount?', 'cartype' => 'http://172.16.100.52:8099/crsCarRental/SelectCarType?paraRequest.sourceCode=W&paraRequest.vendorCode=W-GONGSI&paraRequest.vendorPass=123456&paraRequest.companyCode=GONGSI&paraRequest.brandCode=GONGSI&paraRequest.carTypeCode=', 'memurl' => 'http://172.16.100.121:8080/crsCarRental/regUser?', 'sitename' => '荣益租车合同管理系统 v1.0', 'phone' => '', 'fax' => '', 'address' => '', 'offlinemessage' => '本站正在维护中，暂不能访问。<br /> 请稍后再访问本站。', ); ?>