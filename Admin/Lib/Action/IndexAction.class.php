<?php

class IndexAction extends CommonAction {
	
    public function index() {
        if (isset ( $_SESSION [C ( 'USER_AUTH_KEY' )] )) {
            // 新增新的数据库参数
            //$Model = D ( "Location" );
            //echo 	$_SESSION['location_code'];

            $Model = M('Location','AdvModel');
            $Model->addConnect ( C ( "DB_CRS" ), 1 );
            // 切换数据库
            $Model->switchConnect ( 1, "reservation" );
            $nowdate = Date("Y-m-d");
            //$nowdate = '2011-12-04';
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
            $map['CONFIRMATION'] = array('like',$_SESSION['location_code']."%");
            if(empty($_POST['STATUS']))
                // $map['STATUS'] = array(array('neq','CONTRACT'),array('neq','CANCEL'),array('neq','RETURN'));
                $map['CONFIRMED_BY'] = array('exp','is null');
            //dump($map);exit;
            // 取得满足条件的记录数
            $count = $Model->where ( $map )->count ( $Model->getPk () );

            //echo $Model->getLastSql();
            if ($count > 0) {
                import ( "@.ORG.Page" );
                // 创建分页对象
                if (! empty ( $_REQUEST ['listRows'] )) {
                    $listRows = $_REQUEST ['listRows'];
                } else {
                    $listRows = '20';
                }

                $p = new Page ( $count, $listRows );
                //dump($p);
                // 分页查询数据
                //dump($map);exit;
                $voList = $Model->where ( $map )->field ( 'reservation_id,right(CONFIRMATION,15) as confirmation,real_name,identity_code,home_phone,left(pickup_date,16) as pickup_date,left(return_date,16) as return_date,car_type_name,CAR_MODEL_NAME,status,rate_code' )->order ("STATUS desc," ."`" . $order . "` " . $sort)->limit ( $p->firstRow . ',' . $p->listRows )->findAll ();
                //echo $Model->getLastSql();
                // 分页跳转的时候保证查询条件
                foreach ( $map as $key => $val ) {
                    if ( is_array ( $val )) {
                        $p->parameter .= "&$key=" . urlencode ( str_replace('%','',$val[1] )) . "&";
                    }
                }
                // 分页显示
                $page = $p->show ();
                //echo $page;exit;
                // 列表排序显示
                $sortImg = $sort; // 排序图标
                $sortAlt = $sort == 'desc' ? '升序排列' : '倒序排列'; // 排序提示
                $sort = $sort == 'desc' ? 1 : 0; // 排序方式
                // 模板赋值显示
                $this->assign('realname',$_REQUEST['real_name']);
                //dump($voList);exit;
                $this->assign('pickup_date',$_REQUEST['pickup_date']);
                $this->assign('identitycode',$_REQUEST['identity_code']);
                $this->assign("REAL_NAME",$_POST['REAL_NAME']);
                $this->assign("IDENTITY_CODE",$_POST['IDENTITY_CODE']);
                $this->assign("CONFIRMATION",$_POST['CONFIRMATION']);
                $this->assign("PICKUP_DATE",$_POST['PICKUP_DATE']);
                $this->assign("RETURN_DATE",$_POST['RETURN_DATE']);
                $this->assign("WORK_PHONE",$_POST['WORK_PHONE']);
                $this->assign("STATUS",$_POST['STATUS']);

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
        }

		//$dj =  D("Reservationby");
		//$djList = $dj->findAll ();
		//$this->assign("list",$djList);
		$this->display();			
				
		//}
	}
	public function dwzOrgLookup(){
		$this->display();
	}
}
?>
