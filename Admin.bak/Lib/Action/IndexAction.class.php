<?php

class IndexAction extends CommonAction {
	
	// 框架首页
	/*public function index() {*/
		//if (isset ( $_SESSION [C ( 'USER_AUTH_KEY' )] )) {
			////显示菜单项
			//$menu = array ();
			
			////读取数据库模块列表生成菜单项
			//$node = M ( "Node" );
			//$id = $node->getField ( "id" );
			//$where ['level'] = 2;
			//$where ['status'] = 1;
			//$where ['pid'] = $id;
			//$list = $node->where ( $where )->field ( 'id,name,group_id,title' )->order ( 'sort asc' )->select ();
			//$accessList = $_SESSION ['_ACCESS_LIST'];
			//foreach ( $list as $key => $module ) {
				//if (isset ( $accessList [strtoupper ( APP_NAME )] [strtoupper ( $module ['name'] )] ) || $_SESSION ['administrator']) {
					////设置模块访问权限
					//$module ['access'] = 1;
					//$menu [$key] = $module;
				//}
			//}
			
			//if (! empty ( $_GET ['tag'] )) {
				//$this->assign ( 'menuTag', $_GET ['tag'] );
			//}
			////dump($menu);
			//$this->assign ( 'menu', $menu );
		//}
		//C ( 'SHOW_RUN_TIME', false ); // 运行时间显示
		//C ( 'SHOW_PAGE_TRACE', false );
		//$this->display ();
	/*}*/

	public function index() {
		// 新增新的数据库参数
		//$Model = D ( "Location" );
	//echo 	$_SESSION['location_code'];
		$Model = M('Location','AdvModel');
		$Model->addConnect ( C ( "DB_CRS" ), 1 );
		// 切换数据库
		$Model->switchConnect ( 1, "reservation" );
		// $nowdate = Date("Y-m-d");
		$nowdate = '2011-12-04';
		$where = " left(booking_date,10)='" . $nowdate . "'";

		// 排序字段 默认为主键名
		if (! empty ( $_REQUEST ['_order'] )) {
			$order = $_REQUEST ['_order'];
		} else {
			$order = ! empty ( $sortBy ) ? $sortBy : $Model->getPk ();
		}
		// 排序方式默认按照倒序排列
		// 接受 sost参数 0 表示倒序 非0都 表示正序
		if (isset ( $_REQUEST ['_sort'] )) {
			// $sort = $_REQUEST ['_sort'] ? 'asc' : 'desc';
			$sort = $_REQUEST ['_sort'] == 'asc' ? 'asc' : 'desc'; // zhanghuihua@msn.com
		} else {
			$sort = $asc ? 'asc' : 'desc';
		}
		$map = $this->_search();
		if (method_exists($this, '_filter')) {
			$this->_filter($map);
		}

		$map['CONFIRMATION'] = array('like',"%".$_SESSION['location_code']."%");
		$map['STATUS'] = array(array('neq','CONTRACT'),array('neq','CANCEL'));
		//dump($map);exit;
		// 取得满足条件的记录数
		$count = $Model->where ( $map )->count ( $Model->getPk () );
		//echo $Model->getLastSql();exit;
		if ($count > 0) {
			import ( "@.ORG.Page" );
			// 创建分页对象
			if (! empty ( $_REQUEST ['listRows'] )) {
				$listRows = $_REQUEST ['listRows'];
			} else {
				$listRows = '30';
			}

			$p = new Page ( $count, $listRows );
			// 分页查询数据
			//dump($map);exit;
			$voList = $Model->where ( $map )->field ( 'reservation_id,right(CONFIRMATION,15) as confirmation,real_name,identity_code,work_phone,left(pickup_date,16) as pickup_date,left(return_date,16) as return_date,car_type_name,CAR_MODEL_NAME,status' )->order ( "`" . $order . "` " . $sort )->limit ( $p->firstRow . ',' . $p->listRows )->findAll ();
			// 分页跳转的时候保证查询条件
			foreach ( $map as $key => $val ) {
				if ( is_array ( $val )) {
					$p->parameter .= "&$key=" . urlencode ( str_replace('%','',$val[1] )) . "&";
				}
			}
			// 分页显示
			$page = $p->show ();
			// 列表排序显示
			$sortImg = $sort; // 排序图标
			$sortAlt = $sort == 'desc' ? '升序排列' : '倒序排列'; // 排序提示
			$sort = $sort == 'desc' ? 1 : 0; // 排序方式
			// 模板赋值显示
			$this->assign('realname',$_REQUEST['real_name']);
			//dump($voList);exit;
			$this->assign('pickup_date',$_REQUEST['pickup_date']);
			$this->assign('identitycode',$_REQUEST['identity_code']);
			$this->assign ( 'lists', $voList );
			$this->assign ( 'sort', $sort );
			$this->assign ( 'order', $order );
			$this->assign ( 'sortImg', $sortImg );
			$this->assign ( 'sortType', $sortAlt );
			$this->assign ( "page", $page );
		}

	// zhanghuihua@msn.com
		$this->assign ( 'totalCount', $count );
		$this->assign ( 'numPerPage', C ( 'PAGE_LISTROWS' ) );
		$this->assign ( 'currentPage', ! empty ( $_REQUEST [C ( 'VAR_PAGE' )] ) ? $_REQUEST [C ( 'VAR_PAGE' )] : 1 );
		Cookie::set ( '_currentUrl_', __SELF__ );
		// echo $reservation->getLastSql();
		// $this->assign('list',$reslist);
		$this->assign ( 'menu', $menu );
		
		$dj =  D("Reservationby");
		$djList = $dj->findAll ();
		$this->assign("list",$djList);
		$this->display("");			
		//}
	}
	public function dwzOrgLookup(){
		$this->display();
	}
}
?>
