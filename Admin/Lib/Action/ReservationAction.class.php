<?php
class ReservationAction extends CommonAction {
	public function _filter(&$map){
		if(!empty($_POST['REAL_NAME']))  
		$map['REAL_NAME'] = array('eq',"".$_POST['REAL_NAME']."");
		if(!empty($_POST['IDENTITY_CODE']))
			$map['IDENTITY_CODE'] = array('eq',"".$_POST['IDENTITY_CODE']."");
		if(!empty($_POST['CONFIRMATION']))
			$map['CONFIRMATION'] = array('like',"%".$_POST['CONFIRMATION']."%");
		if(!empty($_POST['PICKUP_DATE']))
			$map['PICKUP_DATE'] = array('like',"%".$_POST['PICKUP_DATE']."%");
		if(!empty($_POST['RETURN_DATE']))
			$map['RETURN_DATE'] = array('like',"%".$_POST['RETURN_DATE']."%");
		if(!empty($_POST['WORK_PHONE']))
			$map['HOME_PHONE'] = array('eq',"".$_POST['WORK_PHONE']."");
		if(!empty($_POST['STATUS']))
		$map['STATUS'] = array('eq',"".$_POST['STATUS']."");
	}
		
	public function index() {
		// 新增新的数据库参数
		//$Model = D ( "Location" );
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
        if(count($maps['CONFIRMATION'][1])==0){
        
            $map['CONFIRMATION'] = array('like',$_SESSION['location_code']."%");
        }else{
            $map['CONFIRMATION'] = array('like',"%".$_POST['CONFIRMATION']."%");
        }
		if(empty($_POST['STATUS']))
            $map['STATUS'] = array(array('neq','CONTRACT'),array('neq','CANCEL'),array('neq','RETURN'));
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
			$voList = $Model->where ( $map )->field ( 'reservation_id,right(CONFIRMATION,15) as confirmation,real_name,identity_code,home_phone,left(pickup_date,16) as pickup_date,left(return_date,16) as return_date,car_type_name,CAR_MODEL_NAME,status,rate_code' )->order ( "`" . $order . "` " . $sort )->limit ( $p->firstRow . ',' . $p->listRows )->findAll ();
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
		
		//$dj =  D("Reservationby");
		//$djList = $dj->findAll ();
		//$this->assign("list",$djList);
		$this->display();			
	}
	public function djedit(){
		//代驾
		$dj =  D("Reservationby");
		$list = $dj->getById($_REQUEST['id']);
		//echo $dj->getLastSql();
		$this->assign("vo",$list);
		$this->display();
	}
	public function djshow(){
		//代驾
		$dj =  D("Reservationby");
		$list = $dj->getById($_REQUEST['id']);
		//echo $dj->getLastSql();
		$this->assign("vo",$list);
		$this->display();
	}
    function edit() {
        Debug::mark('start');
		$Model = M ( "Location","AdvModel" );
		$Model->addConnect ( C ( "DB_CRS" ), 1 );
		$Model->switchConnect ( 1, "reservation" );
		$id = $_REQUEST ['id'];
		$vo = $Model->where ( "reservation_id=" . $id )->find ();
		// echo $Model->getLastSql();exit;
		$Model->switchConnect ( 1, "agreement" );
		$agreeList = $Model->getByAgreementId ( 'HT' . substr ( $vo ['CONFIRMATION'], - 15 ) );
		//echo $Model->getLastSql();exit;
        $confirmation = explode ( '-', $vo ['PICKUP_LOCATION_CODE'] );
        $cc = explode('-',$vo['CAR_MODEL_NAME']);
        $vo['CAR_MODEL'] = $cc[0];
		$vo ['newcfm'] = $confirmation [0] . '-' . $confirmation [1] . '-' . $confirmation [2];
		if ($agreeList) {
			$vo ['status'] = $agreeList ['status'];
			$vo['agreement_id'] = $agreeList['id'];

        }
        
		//均价
		$average = round($vo['BASE_RATE_AMT'] /$vo['BASE_RATE_QTY']);
		$vo ['agreementid'] = 'ZJ' . substr ( $vo ['CONFIRMATION'], - 15 );
		$Model->switchConnect ( 1, "uni_rate" );
		$unirate = $Model->getByLocationCode(substr ( $vo ['CONFIRMATION'], 0,15 ));
		$this->assign('unirate',$unirate);
		
		$this->assign('average',$average);
		$this->assign('datenow',date('Y-m-d h:m'));
		$this->assign ( 'confirmation', $_REQUEST ['confirmation'] );
		$this->assign ( 'location_code', $vo ['PICKUP_LOCATION_CODE'] );
        Debug::mark('end');
        Log::write('Edit运行时间：'.Debug::useTime('start','end'), Log::DEBUG);
        if (isset($_REQUEST['rate_code'])&&$_REQUEST['rate_code']!='WEB') {
            // code...
            
		$vo ['agreementid'] = 'DJ' . substr ( $vo ['CONFIRMATION'], - 15 );
            $Model->switchConnect ( 1, "reservation_plan" );
            $plan = $Model->field('left(start_date,10) as START_DATE,left(end_date,10) as END_DATE,PLAN,ISOVERNIGHT')->where('CONFIRMATION="'.$vo['CONFIRMATION'].'"')->select();
            $this->assign('plan',$plan);
            $Model->switchConnect ( 1, "reservation_option" );
            $driverinfo = $Model->where('option_name like "%司机%" and CONFIRMATION="'.$vo["CONFIRMATION"].'"')->find();
            $this->assign('TECHTITLE',$driverinfo['OPTION_NAME']);

            $this->assign ( 'vo', $vo );
            $this->display ("Reservation:djedit");
        }else{
               
            $this->assign ( 'vo', $vo );
            $this->display ();
        }
	}
	public function selectCar(){
		$Model = M( "Car","AdvModel" );
		$Model->addConnect ( C ( "DB_CRS" ), 1 );
		$Model->switchConnect ( 1, "Car" );
		$carmodelcode = $_REQUEST ['modelcode'];
        $map['CAR_MODEL_CODE'] = $carmodelcode;
        $map['CURRENT_LOCATION_CODE'] = $_SESSION['location_code'];
		$map['status'] = 2;
		$list = $this->_lists ( $Model, $map, 'CAR_ID', false, 'CAR_ID' );
		//dump($list);exit;
		if(empty($list)){
			$cartypecode = $_REQUEST['cartypecode'];
			$Model->switchConnect ( 1, "car_type" );
			$cartypeinfo = $Model->getByCarTypeCode ( $cartypecode );
			$sequence = $cartypeinfo ['SEQUENCE'];
			$cartypecodeList = $Model->where ( 'SEQUENCE>' . $sequence )->field ( 'CAR_TYPE_CODE' )->select ();
			foreach ( $cartypecodeList as $key => $val ) {
				$cl [$key] = $val ['CAR_TYPE_CODE'];

			}
			unset($map['CAR_MODEL_CODE']);
			if(count($cl)){
				$map ['CAR_TYPE_CODE'] = array ('in', $cl );
			}
			$Model->switchConnect ( 1, "Car" );
			$list = $this->_lists ( $Model, $map, 'CAR_ID', false, 'CAR_ID' );
			if (empty($list)) {
				// code...
				$this->error ('库存为空!');
				exit;
			}
		}
		$this->display();
	}
    public function selectDriver(){
        $Model = M( "Car","AdvModel" );
		$Model->addConnect ( C ( "DB_CRS" ), 1 );
		$Model->switchConnect ( 1, "driver_info" );
        $map['status'] = 9;
        $map['TECHTITLE'] = $_REQUEST['TECHTITLE'];
		$this->_lists($Model,$map,'DRIVER_ID',false,'DRIVER_ID');
		$this->display();
	}
	public function createagreement_dj(){
		$model = M ( "Location","AdvModel" );
		$model->addConnect ( C ( "DB_CRS" ), 1 );
		$optionidList = $_POST['option'];
		$_POST ['agreement_id'] = 'DJ' . $_REQUEST ['confirmation'];
		$_POST ['createdate'] = date('Y-m-d h:m:s');
        $_POST ['status'] = "CONTRACT";
        
        foreach($_POST as $k=>$v){
            $k = str_ireplace('master_dwz_devLookup_','',$k);
            $data[$k] = $v;

        Log::write('调试癿SQL：'.$k."--".$v, Log::DEBUG); 
        }
        $data['location_code'] = $_SESSION['location_code'];
        
		
        if (false === $model->create ( $data )) {

			echo $model->getError ();
			exit ();
        }
        $model->switchConnect(1,'car');
        $carinfo = $model->getByCarTag($data["CAR_TAG"]);

        Log::write('调试癿SQL：'.$model->getLastSql(), Log::SQL); 
        Log::write('OUT_CALL_CAR：'.$_POST['OUT_CALL_CAR'], Log::DEBUG); 
        Log::write('CARINFOSTATUS：'.$carinfo['STATUS'], Log::DEBUG); 
        if($_POST['OUT_CALL_CAR']!=0||$_POST['OUT_CALL_CAR']==''){
        Log::write('CARINFOSTATUS：'.$carinfo['STATUS'], Log::DEBUG); 
            if($carinfo['STATUS']==2){
                $cares = $model->execute("update car set status=1 where CAR_TAG='".$data['CAR_TAG']."' ");
                Log::write('updateCarSQL：'.$model->getLastSql(), Log::SQL); 
            }else{
                echo '您选择的车辆'.$cartag.'不可用,请重新选择!';
                exit;
            }
        }
        $model->switchConnect ( 1, "driver_info" );
        $drivers = $model->where("DRIVER_NAME='".$data['DRIVER_NAME']."' and PHONE='".trim($data['PHONE'])."'")->find();
        if($drivers['STATUS']==9){
            $driver= $model->execute ( "update driver_info set  STATUS=0 where DRIVER_NAME='".$data['DRIVER_NAME']."' and PHONE='".trim($data['PHONE'])."'" );
        }else{
            echo '您选择的司机'.$data['DRIVER_NAME'].'不可用，请重新选择!';
            exit;
        }
        Log::write('调试癿SQL：'.$model->getLastSql(), Log::SQL);
        $model->switchConnect ( 1, "agreement" );
        
        $aglist = $model->where('agreement_id="'.$_POST['agreement_id'].'"')->find();
        if(count($aglist)>0){
        
            echo '合同编号'.$_POST['agreement_id'].'已被生成,请重新选择预订!';
            exit;
        }
				// 保存当前数据对象
        $list = $model->add ( $data );
         
        $model->switchConnect ( 1, "reservation" );
        $resup = $model->execute ( "update reservation set  STATUS='CONTRACT' where CONFIRMATION='" . $_SESSION['location_code'].'-'.$_REQUEST['confirmation']. "'" );

        if ($list!==false) {
            // code...
            $model->switchConnect ( 1, "reservation_option" );
            $options = $model->where('CONFIRMATION="'.$_REQUEST['CFM'].'"')->select();

            $model->switchConnect ( 1, "agreement_option" );
            foreach($options as $k=>$v){
                if (false === $model->create ( $v )) {
                    echo $model->getError ();
                    exit ();
                }
                // 保存当前数据对象
                $return = $model->add ( $v );                      

				Log::write('调试癿SQL：'.$model->getLastSql(), Log::SQL); 
            }
            $model->switchConnect ( 1, "reservation_plan" );
            $plans = $model->where('CONFIRMATION="'.$_REQUEST['CFM'].'"')->select();

            $model->switchConnect ( 1, "agreement_plan" );
            foreach($plans as $k=>$v){
                if (false === $model->create ( $v )) {
                    echo $model->getError ();
                    exit ();
                }
                // 保存当前数据对象
                $return = $model->add ( $v );                      

				Log::write('调试癿SQL：'.$model->getLastSql(), Log::SQL); 
            }

				//不是外调车辆，更新库存
                if($_POST['OUT_CALL_CAR']!=0){

                $model->switchConnect(1,'uni_inventory');
                $carmodelcode = $data['CAR_MODEL_CODE'];
				//$model->where("LOCATION_CODE='".$_SESSION['location_code']."' and CAR_MODEL_CODE='".$carmodelcode."' and left(START_DATE,10)>='".substr($_POST['PICKUP_DATE'],0,10)."' and left(END_DATE,10)<='".substr($_POST['RETURN_DATE'],0,10)."'")->setDec('REAL_INT',1);
				$model->execute("UPDATE `uni_inventory` SET `REAL_INT`=REAL_INT-1 where LOCATION_CODE='".$_SESSION['location_code']."' and left(START_DATE,10)>='".substr($_POST['PICKUP_DATE'],0,10)."' and left(END_DATE,10)<='".substr($_POST['RETURN_DATE'],0,10)."'");
                Log::write('调试癿SQL：'.$model->getLastSql(), Log::SQL); 
                }
        }
        	header ( "Content-Type:text/html; charset=utf-8" );
			exit ( json_encode ( 1 ) );
		
	}
	public function createagreement() {
		$model = M ( "Location","AdvModel" );
		$model->addConnect ( C ( "DB_CRS" ), 1 );
		$optionidList = $_POST['option'];
		$_POST ['agreement_id'] = 'ZJ' . $_REQUEST ['confirmation'];
	//	$_POST ['createdate'] = time ();

		$_POST ['createdate'] = date('Y-m-d h:m:s');
		$_POST ['status'] = "CONTRACT";
		//$data = str_ireplace("master_dwz_devLookup_","",$_REQUEST);
		$_POST['CAR_TAG'] =$_POST['master_dwz_devLookup_CAR_TAG'];
		$_POST['CAR_MODEL'] = $_POST['master_dwz_devLookup_CAR_MODEL'];
		$_POST['CAR_MODEL_NAME'] = $_POST['master_dwz_devLookup_CAR_MODEL_NAME'];

		$_POST['CURRENT_OIL'] = $_POST['master_dwz_devLookup_CURRENT_OIL'];
		$_POST['CURRENT_KM'] = $_POST['master_dwz_devLookup_CURRENT_KM'];
		//lost car_model_code
		$_POST['CAR_MODEL_CODE'] = $_POST['master_dwz_devLookup_CAR_MODEL_CODE'];
		$carmodelcode = $_POST['CAR_MODEL_CODE'];
		$carTypeCode = $_POST['master_dwz_devLookup_CAR_TYPE_CODE'];
		$cartag = $_POST['master_dwz_devLookup_CAR_TAG'];
		$validate = array (array ('CAR_TAG', 'require', '车牌必须!' ),array('work_phone','/^(1(([35][0-9])|(47)|[8][01236789]))\d{8}$/','租车人手机格式不正确!'),
					array('contactperson','require','紧急联系人必须!'),array('contactphone','/^(1(([35][0-9])|(47)|[8][01236789]))\d{8}$/','手机格式不正确!'),array('MEMBER_TYPE_NAME','require','会员类型必须'),array('REAL_NAME','require','客户名称必须'),array('sex','require','性别必须'),array('age','require','出生日期必须'),array('IDENTITY_CODE','require','身份证号码必须'),array('address','require','身份证地址必须'),array('driver_code','require','驾驶证号码必须')
		);
		$model->setProperty ( "_validate", $validate );
		if (false === $model->create ( $_POST )) {
			echo $model->getError ();
			exit ();
        }
        //选车时生成合同和选车逻辑优先级bug
        //添加合同事务处理
        
        $model->switchConnect(1,'car');
        $model->startTrans() ;
        
        $carinfo = $model->getByCarTag($cartag);
        if($_POST['OUT_CALL_CAR']==''){
            if($carinfo['STATUS']==2){
                $cares = $model->execute("update car set status=1 where CAR_TAG='".$cartag."' and CAR_MODEL_CODE='".$carmodelcode."'");

                Log::write('调试癿SQL：'.$model->getLastSql(), Log::SQL); 
            }else{
                echo '您选择的车辆'.$cartag.'不可用,请重新选择!';
                exit;
            }
        }
        

		$model->switchConnect ( 1, "agreement" );
        $aglist = $model->where('agreement_id="'.$_POST['agreement_id'].'"')->find();
        if(count($aglist)>0){
            
            echo '合同编号'.$_POST['agreement_id'].'已被生成,请重新选择预订!';
            $model->rollBack();
            exit;
        }

        $model->commit();
				// 保存当前数据对象
        $list = $model->add ( $_POST );
		if ($list !== false) { // 保存成功
			$model->switchConnect ( 1, "reservation_option" );
			$map['confirmation']	= $_SESSION['location_code'].'-'.$_REQUEST['confirmation'];
            $resOpt = $model->where($map)->group('option_id')->select();

                Log::write('调试癿SQL：'.$model->getLastSql(), Log::SQL); 
			unset($map);
			$model->switchConnect ( 1, "agreement_option" );
			
			foreach ($resOpt as $key=>$val){

				if(in_array($val['OPTION_ID'],$_REQUEST['option'])){
				//$data['CONFIRMATION'] = $val['CONFIRMATION'];
				$data['CONFIRMATION'] = $_POST ['agreement_id'];
				$data['OPTION_ID'] = $val['OPTION_ID'];
				$data['OPTION_NAME'] = $val['OPTION_NAME'];
				$data['OPTION_TYPE'] = $val['OPTION_TYPE'];
				$data['RATE'] = $val['RATE'];
				$data['PER_UNIT'] = $val['PER_UNIT'];
				$data['QTY'] =  $val['QTY'];
				$data['AMT'] = 	$val['AMT'];
				$data['SEQUENCE'] = $val['SEQUENCE'];
				$data['MANDATORY'] = $val['MANDATORY'];	
				$optionID[] = $val['OPTION_ID'];
				$model->add($data);
                }

                Log::write('调试癿SQL：'.$model->getLastSql(), Log::SQL); 
				//echo $model->getLastSql();
			}
			unset($data);
			$model->switchConnect(1,'uni_option');
			$map['option_id'] = array('in',$_REQUEST['option']);
			$map['location_code'] = $_REQUEST['location_code'];
			$map['OPTION_ID'] = array('not in',$optionID);
			$uniOpt = $model->where($map)->group('option_id')->select();

			$model->switchConnect ( 1, "agreement_option" );
			foreach($uniOpt as $k=>$v)
			{
				
				if(in_array($v['OPTION_ID'],$_REQUEST['option'])){
				//$data['CONFIRMATION'] = $_REQUEST['location_code'].'-'.$_REQUEST['confirmation'];
				$data['CONFIRMATION'] = $_POST ['agreement_id'];
				$data['OPTION_ID'] = $v['OPTION_ID'];
				$data['OPTION_NAME'] = $v['OPTION_NAME'];
				$data['OPTION_TYPE'] = $v['OPTION_TYPE'];
				$data['RATE'] = $v['RATE'];
				$data['PER_UNIT'] = $v['PER_UNIT'];
				$data['QTY'] = 1;
				$data['AMT'] =  $v['PER_UNIT'];
				$data['SEQUENCE'] = $v['SEQUENCE'];
				$data['MANDATORY'] = $v['MANDATORY'];
				$model->add($data);
				//echo $model->getLastSql();exit;
				}
			}
			
				unset($data);	
				$model->switchConnect ( 1, "agreement_disc" );
				$data['CONFIRMATION'] = $_SESSION['location_code'].'-'.$_REQUEST['confirmation'];
				$data['UNI_DISC_ID'] =$_REQUEST['discount'];
				$data['DISC_NAME'] = $_REQUEST['discountname'];
				//dump($data);exit;
				$model->add($data);
				$model->switchConnect ( 1, "reservation" );
                $resup = $model->execute ( "update reservation set  STATUS='CONTRACT' where CONFIRMATION='" . $data['CONFIRMATION']. "'" );
				Log::write('调试癿SQL：'.$model->getLastSql(), Log::SQL); 
				
				
                //$model->switchConnect(1,'car');
                //不是外调车辆，更新库存
                if($_POST['OUT_CALL_CAR']!=0){
                    $model->switchConnect(1,'uni_inventory');
                    //$model->where("LOCATION_CODE='".$_SESSION['location_code']."' and CAR_MODEL_CODE='".$carmodelcode."' and left(START_DATE,10)>='".substr($_POST['PICKUP_DATE'],0,10)."' and left(END_DATE,10)<='".substr($_POST['RETURN_DATE'],0,10)."'")->setDec('REAL_INT',1);
                    $model->execute("UPDATE `uni_inventory` SET `REAL_INT`=REAL_INT-1 where LOCATION_CODE='".$_SESSION['location_code']."'  and left(START_DATE,10)>='".substr($_POST['PICKUP_DATE'],0,10)."' and left(END_DATE,10)<='".substr($_POST['RETURN_DATE'],0,10)."'");
                    Log::write('调试癿SQL：'.$model->getLastSql(), Log::SQL); 
                }
			header ( "Content-Type:text/html; charset=utf-8" );
			exit ( json_encode ( 1 ) );
		
        } else { // 失败提示 
            header ( "Content-Type:text/html; charset=utf-8" ); 
            echo "-1";
			exit ();
		}
	}
	public function gotoPrint($agreementid = '') {
		Vendor ( "tcpdf.tcpdf" );
		Vendor ( "tcpdf.config.lang.eng" );
		$pdf = new TCPDF ( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false );
		$agreementid = $_REQUEST ['agreementid'];
		if(empty($agreementid)){
			$agreementid = $_REQUEST['id'];
		}
		//$model = M( "Location","AdvModel" );
		//$model->addConnect ( C ( "DB_CRS" ), 1 );
		//$model->switchConnect ( 1, "agreement" );
		//$list = $model->getByAgreementId ( $agreementid );
		$model = M("reservationby");
		$list = $model->getByReservationid($agreementid);
		//echo $model->getLastSql();exit;
		$pdf->SetAuthor ( 'RongYi' );
		$pdf->SetTitle ( 'Test' );
		$pdf->SetSubject ( 'Test' );
		$pdf->SetKeywords ( 'Test' );
		
		$params = $pdf->serializeTCPDFtagParameters ( array ($agreementid, 'QRCODE,H', 162, 68, 50, 50, array ('position' => 'S', 'border' => true, 'padding' => 4, 'fgcolor' => array (0, 0, 0 ), 'bgcolor' => array (255, 255, 255 ), 'text' => true, 'font' => 'helvetica', 'fontsize' => 8, 'stretchtext' => 4 ), 'N' ) );
		$this->assign ( "params", $params );
		$pdf->setHeaderFont ( Array (PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN ) );
		$pdf->setFooterFont ( Array (PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA ) );
		
		// set default monospaced font
		$pdf->SetDefaultMonospacedFont ( PDF_FONT_MONOSPACED );
		
		// set margins
		$pdf->SetMargins ( PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT );
		$pdf->SetHeaderMargin ( PDF_MARGIN_HEADER );
		$pdf->SetFooterMargin ( PDF_MARGIN_FOOTER );
		
		// set auto page breaks
		$pdf->SetAutoPageBreak ( TRUE, PDF_MARGIN_BOTTOM );
		
		// set image scale factor
		$pdf->setImageScale ( PDF_IMAGE_SCALE_RATIO );
		
		// set some language-dependent strings
		$pdf->setLanguageArray ( $l );
		
		// ---------------------------------------------------------
		
		// set default font subsetting mode
		$pdf->setFontSubsetting ( true );
		
		$pdf->SetFont ( 'stsongstdlight', '', 16 );
		
		// Add a page
		// This method has several options, check the source code documentation
		// for more
		// information.
		$pdf->AddPage ();
		$list ['upcode'] = $upcode;
		$this->assign ( 'vo', $list );
		// Set some content to print
		$html = $this->fetch ();
		// echo $html;exit;
		// Print text using writeHTMLCell()
		$pdf->writeHTMLCell ( $w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true );
		
		// Close and output PDF document
		// This method has several options, check the source code documentation
		// for more
		// information.
		$pdf->Output ( $agreementid . '.pdf', 'FD' );
	
	}
	
	protected function _lists($model, $map, $sortBy = '', $asc = false, $id = 'id') {
		// 排序字段 默认为主键名
		if (isset ( $_REQUEST ['_order'] )) {
			$order = $_REQUEST ['_order'];
		} else {
			$order = ! empty ( $sortBy ) ? $sortBy : $model->getPk ();
		}
		// 排序方式默认按照倒序排列
		// 接受 sost参数 0 表示倒序 非0都 表示正序
		if (isset ( $_REQUEST ['_sort'] )) {
			$sort = $_REQUEST ['_sort'] ? 'asc' : 'desc';
		} else {
			$sort = $asc ? 'asc' : 'desc';
		}
		// 取得满足条件的记录数
        $count = $model->where ( $map )->count ( $id );
        //echo $model->getLastSql();exit;
		if ($count > 0) {
			import ( "ORG.Util.Page" );
			// 创建分页对象
			if (! empty ( $_REQUEST ['listRows'] )) {
				$listRows = $_REQUEST ['listRows'];
			} else {
				$listRows = '';
			}
			$p = new Page ( $count, $listRows );
			// 分页查询数据

			$voList = $model->where ( $map )->order ( "`" . $order . "` " . $sort )->limit ( $p->firstRow . ',' . $p->listRows )->findAll ();
			//$carmodel = getCarmodel ( $map ['CAR_MODEL_CODE'], 'CAR_MODEL' );
			foreach ( $voList as $k => $v ) {

				$voList [$k] ['CAR_MODEL'] = getCarmodel ( $v ['CAR_MODEL_CODE'], 'CAR_MODEL' );
				$voList [$k] ['CAR_MODEL_NAME'] = getCarmodel ( $v ['CAR_MODEL_CODE'], 'CAR_MODEL_NAME' );
				$voList [$k] ['CAR_TYPE_NAME'] = getCartype ( $v ['CAR_TYPE_CODE'], 'CAR_TYPE_NAME' );
				$voList [$k] ['CAR_MODEL_SIZE'] = getCarmodel ( $v ['CAR_TYPE_CODE'], 'CAR_MODEL_SIZE' );
			}

			// Log::write('调试的SQL：'.print_r($voList));
			// Log::write('调试的SQL：'.print_r($carmodel));
			// echo $model->getlastsql();
			// 分页跳转的时候保证查询条件
			foreach ( $map as $key => $val ) {
				if (! is_array ( $val )) {
					$p->parameter .= "$key=" . urlencode ( $val ) . "&";
				}
			}
			// 分页显示
			$page = $p->show ();
			// 列表排序显示
			$sortImg = $sort; // 排序图标
			$sortAlt = $sort == 'desc' ? '升序排列' : '倒序排列'; // 排序提示
			$sort = $sort == 'desc' ? 1 : 0; // 排序方式
			// 模板赋值显示
			// $this->assign('list', $voList);
			//模板赋值显示
			//dump($voList);exit;
			$this->assign ( 'list', $voList );
			$this->assign ( 'sort', $sort );
			$this->assign ( 'order', $order );
			$this->assign ( 'sortImg', $sortImg );
			$this->assign ( 'sortType', $sortAlt );
			$this->assign ( "page", $page );
		}
		//zhanghuihua@msn.com
		$this->assign ( 'totalCount', $count );
		$this->assign ( 'numPerPage', C('PAGE_LISTROWS') );
		$this->assign ( 'currentPage', !empty($_REQUEST[C('VAR_PAGE')])?$_REQUEST[C('VAR_PAGE')]:1);

		Cookie::set ( '_currentUrl_', __SELF__ );
		return $voList;

	}

	public function ajax_curl()
	{
		// code...
	
		$url="http://172.16.100.47:8099/crsCarRental/rcaculateDiscount?"."uniDiscId=".$_POST['uniDiscId']."&rateCode=".$_POST['rateCode']."&carTypeCode=".$_POST['carTypeCode']."&carModelCode=".$_POST['carModelCode']."&pickupLocationCode=".$_POST['pickupLocationCode']."&pickupDate=".$_POST['pickupDate']."&returnDate=".$_POST['returnDate'];
		
		$result = $this->curl($url);
		echo $result;exit;
	}
	public function optionprice()
    { 
        $Model = M ( "Location","AdvModel" );
        $Model->addConnect ( C ( "DB_CRS" ), 1 );
        $map2 ['confirmation'] = $_SESSION['location_code'].'-'.$_GET['reservationid'];
        $map ['location_code'] = $_SESSION['location_code'];
        $localcode = $_SESSION['location_code'];
        $Model->switchConnect ( 1, "reservation_option" );
        $listOpt['res'] = $Model->where ( $map2 )->select ();
        $html = "";
        $perunit = '';
        $real_inv = '';
        if ($listOpt['res']) {
            foreach ( $listOpt['res'] as $k => $v ) {
                
                $optionID[] = $v['OPTION_ID'];
            }
        }
        $Model->switchConnect ( 1, "uni_option" );
        $wh ='';
        if(!empty($listOpt['res'])){
            $wh.=" and option_id not in (".implode(',',$optionID).")";
        }
        $listOpt['uni'] = $Model->where ( "location_code='".$localcode."' and (rate_code='WEB' or rate_code is NULL) AND LEFT(NOW(),10)=START_DATE ".$wh )->select ();

        Log::write('增值服务SQL：'.$Model->getLastSql(), Log::SQL);
        header ( "Content-Type:text/html; charset=utf-8" );
        exit ( json_encode ( $listOpt ) );


    }
    public function suggest()
    {
        $q = strtolower($_GET["q"]);
        if (!$q) return;
        $model = M ( "Location","AdvModel" );
        $model->addConnect ( C ( "DB_CRS" ), 1 );
        $model->switchConnect ( 1, "customers" );
        $customers =  $model->getField('MEMORY_CODE,CUSTOMER_NAME');
        $result = array();
        foreach($customers as $key=>$value){
            if (strpos(strtolower($key), $q) !== false) {
                echo "$value\n";
            }
        }

    }
    public function djOption()
    {
        $model = M ( "Location","AdvModel" );
		$model->addConnect ( C ( "DB_CRS" ), 1 );
        $model->switchConnect ( 1, "uni_option" );
        $map['LOCATION_CODE'] = $_SESSION['location_code'];
        $map['RATE_CODE'] = $_GET['ratecode'];
        if($_GET['ratecode']=='WEB'){
            
            $map['OPTION_CLASS'] = array(array('eq','Z'),array('eq','ALL'),'or');
        }else{
        
            $map['OPTION_CLASS'] = array('neq','Z');
        }
        //$map['CAR_TYPE_CODE']= array(array('exp','is NULL'),array('eq',$_GET['car_type_code']),'or');
        $map['START_DATE'] = array('eq',substr($_GET['PICKUP_DATE'],0,10));
        $result = $model->where($map)->group('OPTION_ID')->findAll();
        $map['CAR_MODEL_CODE'] = $_GET['CAR_MODEL_CODE'];
       // echo $model->getLastSql();
        header ( "Content-Type:text/html; charset=utf-8" );
        exit ( json_encode ( $result ) );

    }
    public function djRate()
    {
        $model = M ( "Location","AdvModel" );
        $model->addConnect ( C ( "DB_CRS" ), 1 );
        $model->switchConnect(1,'uni_rate');
        $map['LOCATION_CODE'] = $_SESSION['location_code'];
        $map['RATE_CODE'] = $_GET['ratecode'];
        $map['START_DATE'] = array('like',"%".substr($_GET['PICKUP_DATE'],0,10)."%");
        $map['CAR_MODEL_CODE'] = $_GET['CAR_MODEL_CODE'];
        $result = $model->where($map)->findAll();
       // echo $model->getLastSql();
        header ( "Content-Type:text/html; charset=utf-8" );
        exit ( json_encode ( $result ) );

    }
    public function flight()
    {
        $map['LOCATION_CODE'] = $_SESSION['location_code'];
        $model = M ( "Location","AdvModel" );
        $model->addConnect ( C ( "DB_CRS" ), 1 );
        $model->switchConnect(1,'location');
        $list = $model->where($map)->find();
        $model->switchConnect(1,'car_type');
        $option = $model->findAll();
        
        $model->switchConnect(1,'airport');
        $this->assign('tomorrow',strtotime('now')+60*60*24);

        $this->assign('return',strtotime('now')+60*60*24*2);
        $airport = $model->findAll();
        $this->assign("airport",$airport);
        $this->assign("option",$option);
        //判断机场
        $this->assign('cityCode',$list['CITY_CODE']);
        $this->assign("vo",$list);
        $this->display();
    }
    public function findDJCars()
    {
        //echo $_GET['RETURN_DATE'];exit;
        $selcars = C('SELCAR');
        $url = $selcars."&psRequest.rateCode=".$_GET['RATE_CODE']."&psRequest.pickupCityCode=".$_GET['CITY_CODE']."&psRequest.pickupDistrictCode=".$_GET['DISTRICT_CODE']."&psRequest.pickupLocationCode=".$_SESSION['location_code']."&psRequest.pickupDate=".$_GET['PICKUP_DATE']."&psRequest.returnCityCode=".$_GET['CITY_CODE']."&psRequest.returnDistrictCode=".$_GET['DISTRICT_CODE']."&psRequest.returnLocationCode=".$_SESSION['location_code']."&psRequest.returnDate=".$_GET['RETURN_DATE']."&psRequest.ipaddress=".$_SERVER["REMOTE_ADDR"]."&psRequest.carTypeCode=".$_GET['CAR_TYPE_CODE']."&psRequest.optionClass=".$_GET['OPTION_CLASS']."&psRequest.discountCode=";
        // echo $url;
        
        Log::write('调试findDJCars：'.$url, Log::DEBUG); 
        $result = $this->curl($url);
        exit($result);
		$result = json_decode($result);
		$result = $result->dsResponse->discounts->discount;
		foreach($result as $k=>$v){
			$vs[]=(array)$v;

        }
    }
    public function djRule()
    {
        $model = M ( "Location","AdvModel" );
        $model->addConnect ( C ( "DB_CRS" ), 1 );
        $model->switchConnect(1,'uni_rule');
        $map['RULE_CODE'] = $_GET['RULE_CODE'];
        $list = $model->where($map)->find();
        header ( "Content-Type:text/html; charset=utf-8" );
        exit ( json_encode ( $list ) );
    }
    public function driverInfo()
    {
        // code...
        $model = M ( "Location","AdvModel" );
        $model->addConnect ( C ( "DB_CRS" ), 1 );
        $model->switchConnect(1,'driver_info');
        $map['TECHTITLE'] = $_GET['TECHTITLE'];
        $vo = $model->where($map)->find();
        header ( "Content-Type:text/html; charset=utf-8" );
        exit ( json_encode ( $vo ) );
    }
    public function  DSB_create()
    {
        $location_code = $_SESSION['location_code'];
        $map['location_code'] = $location_code;
        $model = M ( "Location","AdvModel" );
        $model->addConnect ( C ( "DB_CRS" ), 1 );
        $model->switchConnect(1,'location');
        $location = $model->where($map)->find();
        $model->switchConnect(1,'brand');
        $brand = $model->getByCompanyCode($location['COMPANY_CODE']);
        
        $model->switchConnect(1,'airport');
        $airport = $model->getField('AIRPORT_CODE,AIRPORT_NAME');
        $model->switchConnect(1,'member_type');
        $member = $model->where('MEMBER_TYPE_ID='.$_POST['MEMBER_TYPE_ID'])->find();
        $model->switchConnect(1,'reservation');
        $jsoncarinfo= "[".$_POST['jsoncarinfo']."]";
        $jsoncarinfo = str_replace('\\','',$jsoncarinfo);
        //echo $jsoncarinfo;
        $jsoncarinfo = json_decode((string)$jsoncarinfo);
        $data = array_merge($location,$_POST);
        $data = array_merge($data,$brand);
        $confirmation = $_SESSION['location_code'].'-'.date('YmdHis').rand(0,6);
        
        Log::write('CONFIRMATIONID：'.$confirmation, Log::DEBUG); 
        $data['CONFIRMATION'] = $confirmation;
        $optionidarr = $data['optionname'];
        $carinfo = (array)$jsoncarinfo[0];
        $cars = (array)$carinfo['vehicle'];
        $data['ATMT'] = $cars['atmt'];
        $data['BIG_PACKAGE'] = $cars['bigPackage'];
        $data['CAR_MODEL_CODE'] = $cars['carModelCode'];
        $data['CAR_MODEL_IMG_URL'] = $cars['carModelImgUrl'];
        $data['CAR_MODEL_NAME'] = $cars['carModelName'];
        $data['CAR_TYPE_CODE'] = $cars['carTypeCode'];
        $data['CAR_TYPE_IMG_URL'] = $cars['carTypeImgUrl'];
        $data['CAR_TYPE_NAME'] = $cars['carTypeName'] ;
        $data['PERSONS'] = $cars['persons'];
        $data['SMALL_PACKAGE'] = $cars['smallPackage'];
        $data['RATE_CODE'] = $data['ratecode'];
        $data['DEPOSIT'] = $carinfo['deposit'];
        $data['EXT_HOUR'] = $carinfo['extHour'];
        $data['XHOUR'] = $carinfo['extHour'];
        $data['XDAY'] = $carinfo['extraDayRate'];
        $data['RULE_CODE'] = $carinfo['ruleCode'];
        $data['XDIS_RATE'] = $carinfo['xdisRate'];
        $data['XHOUR_NEXT_DAY'] = $carinfo['xhourNextDay'];
        $data['DIS_FREE'] = $carinfo['disFree'];
        $data['SOURCE_CODE'] = C('SOURCE_CODE');
        $data['MANDATORY_CHARGES'] = $data['MANDATORY'];
        $data['TEXT'] = $carinfo['text'];
        $data['STATUS'] = 'NOPREPAY';
        $data['RETURN_DATE'] = $_POST['RETURN_DATE']." ".$_POST['_hour']."-".$_POST["_minute"];
        if($data['RETURN_DATE']='0000-00-00 00:00'){
            $data['RETURN_DATE'] = $_POST['RETURN_DATE_D'];
        }
        
        if($data['RATE_CODE']=='WEB'){
            $data['REAL_NAME'] = $_POST['REAL_NAME_ZJ'];
            $data['RETURN_DATE'] = $_POST['RETURN_DATE_ZJ'];
            $data['MEMBER_TYPE_NAME'] = $member['MEMBER_TYPE_NAME'];  
        }
        if($data['RATE_CODE']=='DSJ'){

            $data['RETURN_DATE'] = $_POST['RETURN_DATE_D'];
            $data['AIRPORT_CODE'] = $_POST['AIRPORT_CODE_J'];
            $data['AIRPORT_NAME'] = $airport[$data['AIRPORT_CODE']];
        }
        if($data['RATE_CODE']=='WEB'){
            $validate_zj = array (array ('REAL_NAME_ZJ', 'require', '自驾会员名称不能为空' ),array('HOME_PHONE','/^(1(([35][0-9])|(47)|[8][01236789]))\d{8}$/','租车人手机格式不正确!'),
                array('IDENTITY_CODE','require','身份证号码必须'),
                array('RETURN_DATE_ZJ','require','还车日期必须'),
                array('PICKUP_DATE','require','取车日期必须')
            );
            $model->setProperty ( "_validate", $validate_zj);
        }else if($data['RATE_CODE']=='DSB'){
            $validate_dsb = array (array ('REAL_NAME', 'require', '客户名称不能为空' ),
                array('PICKUP_DATE','require','带驾日期必须')
            );
            $model->setProperty ( "_validate", $validate_dsb);
            $data['RETURN_DATE'] = substr($_POST['PICKUP_DATE'],0,10)." ".$_POST['_hour'].":".$_POST['_minute'];

        }else if($data['RATE_CODE']=='DSJ'){
            $validate_dsj= array (array ('REAL_NAME', 'require', '客户名称不能为空' ),
                array('PICKUP_DATE','require','带驾日期必须')
            );
            $model->setProperty ( "_validate", $validate_dsj);

        }else if($data['RATE_CODE']=='DSR'){
            $validate_dsr= array (array ('REAL_NAME', 'require', '客户名称不能为空' ),
                array('PICKUP_DATE','require','带驾日期必须')
            );
            $model->setProperty ( "_validate", $validate_dsr);

        }

        Log::write('RETURN_DATE：'.$_POST['RETURN_DATE_D'], Log::DEBUG); 
        if (false === $model->create ( $data )) {
			echo $model->getError ();
			exit ();
        }
        $reservation_add = $model->add($data);
        
        Log::write('调试癿SQL：'.$model->getLastSql(), Log::SQL); 
        $model->switchConnect(1,'uni_inventory');
        $carmodelcode = $data['CAR_MODEL_CODE'];
        //$model->where("LOCATION_CODE='".$_SESSION['location_code']."' and CAR_MODEL_CODE='".$carmodelcode."' and left(START_DATE,10)>='".substr($_POST['PICKUP_DATE'],0,10)."' and left(END_DATE,10)<='".substr($_POST['RETURN_DATE'],0,10)."'")->setDec('REAL_INT',1);
        $model->execute("UPDATE `uni_inventory` SET RATE_CODE_BOOKINGS=RATE_CODE_BOOKINGS+1 where LOCATION_CODE='".$_SESSION['location_code']."' and CAR_TYPE_CODE='".$data['CAR_TYPE_CODE']."' and CAR_MODEL_CODE='".$carmodelcode."' and left(START_DATE,10)>='".substr($_POST['PICKUP_DATE'],0,10)."' and left(END_DATE,10)<='".substr($data['RETURN_DATE'],0,10)."' and RATE_CODE='".$data['RATE_CODE']."' ");

        Log::write('预订BOOKING_SQL：'.$model->getLastSql(), Log::SQL); 
        $model->execute("UPDATE `uni_inventory` SET CAR_MODEL_BOOKINGS=CAR_MODEL_BOOKINGS+1 where LOCATION_CODE='".$_SESSION['location_code']."' and CAR_TYPE_CODE='".$data['CAR_TYPE_CODE']."' and  CAR_MODEL_CODE='".$carmodelcode."' and left(START_DATE,10)>='".substr($_POST['PICKUP_DATE'],0,10)."' and left(END_DATE,10)<='".substr($data['RETURN_DATE'],0,10)."'  ");

        Log::write('预订BOOKING_SQL：'.$model->getLastSql(), Log::SQL); 
        $model->execute("UPDATE `uni_inventory` SET CAR_TYPE_BOOKINGS=CAR_TYPE_BOOKINGS+1 where LOCATION_CODE='".$_SESSION['location_code']."'  and left(START_DATE,10)>='".substr($_POST['PICKUP_DATE'],0,10)."' and left(END_DATE,10)<='".substr($data['RETURN_DATE'],0,10)."' and CAR_TYPE_CODE='".$data['CAR_TYPE_CODE']."' ");

        Log::write('预订BOOKING_SQL：'.$model->getLastSql(), Log::SQL); 
        $model->execute("UPDATE `uni_inventory` SET LOC_BOOKINGS=LOC_BOOKINGS+1 where LOCATION_CODE='".$_SESSION['location_code']."'  and left(START_DATE,10)>='".substr($_POST['PICKUP_DATE'],0,10)."' and left(END_DATE,10)<='".substr($data['RETURN_DATE'],0,10)."'");
        Log::write('预订BOOKING_SQL：'.$model->getLastSql(), Log::SQL); 

        //行程安排
        $model->switchConnect(1,'reservation_plan');
        if($data['RATE_CODE']=='DSJ'){
            $plan['PLAN'] = $data['UP_DOWN_ADDRESS'];
        }elseif($data['RATE_CODE']=='DSR'){
            foreach($_POST['PLAN'] as $k=>$v){
                foreach($_POST['ISOVERNIGHT'] as $x=>$y){
                    if($x==$k){
                        $plan['ISOVERNIGHT'] = 1;
                    }else{

                        $plan['ISOVERNIGHT'] = 0;
                    }
                }
                $plan['PLAN'] = $v;
                $plan['START_DATE'] = $k;
                $plan['END_DATE'] = $k;
                $plan['CONFIRMATION'] = $confirmation;
                if (false === $model->create ( $plan )) {
                    echo $model->getError ();
                    exit ();
                }
                $list = $model->add($plan);

                Log::write('调试癿SQL：'.$model->getLastSql(), Log::SQL); 
            }
        }
        
        unset($data);
        $_POST['optionname'][$_POST['OPTION_ID']] = $_POST['rdioption'];
        //print_r($OPTION_ID);exit;
       // $_POST['optionname'] = array_merge($_POST['OPTION'],$OPTION_ID);
        foreach($_POST['optionname'] as $k=>$v){
            $model->switchConnect(1,'uni_option');
            $OPTION_ID = $k;
            $option = $model->where('option_id='.$k.' and left(start_date,10)="'.substr($_POST['PICKUP_DATE'],0,10).'" and status="Y"')->find();
            $data['OPTION_ID'] = $k;
            $data['OPTION_NAME'] = $option['OPTION_NAME'];
            $data['MANDATORY'] = $option['MANDATORY'];
            $data['RATE'] = $option['RATE'];
            $data['PER_UNIT'] = $option['PER_UNIT'];
            if($option['PER_UNIT']=='D'){
                    $data['QTY'] = $_POST['BASE_RATE_QTY'];
            }else{
                    $data['QTY'] =1;
            }
            $data['FLAG'] = $option['FLAG'];
            $data['OPTION_TYPE'] = $option['OPTION_TYPE'];
            $data['AMT'] = $option['RATE'];
            $data['CONFIRMATION'] = $confirmation;
            $model->switchConnect(1,'reservation_option');
             if (false === $model->create ( $data )) {
                echo $model->getError ();
                exit ();
            }
            $list = $model->add($data);
            Log::write('调试癿SQL：'.$model->getLastSql(), Log::SQL); 
        }
        echo $reservation_add;
        exit;
        foreach($optionidarr as $k=>$v){
            $optionid = $k;
            dump($optionid);
        }   
        exit;
    }
    public function memberInfo(){
        $q = strtolower($_GET["q"]);
        if (!$q) return;
        $model = M ( "Location","AdvModel" );
        $model->addConnect ( C ( "DB_CRS" ), 1 );
        $model->switchConnect ( 1, "member" );
        $customers =  $model->field('HOME_PHONE,REAL_NAME,IDENTITY_CODE,EMAIL,HOME_PHONE,MEMBER_TYPE_ID')->where('HOME_PHONE like "'.$_GET[q].'%"')->findAll();
        $result = array();
        foreach($customers as $key=>$value){
            if(strpos(strtolower($value['HOME_PHONE'], $q)) !== false) {
                //echo "name:".$value['REAL_NAME'].",email:".$value['EMAIL'].",IDENTITY_CODE:".$value['IDENTITY_CODE']."\n";
                array_push($result,array(
                    'home_phone'=>$value['HOME_PHONE'],
                    'name'=>$value['REAL_NAME'],
                    'email'=>$value['EMAIL'],
                    'IDENTITY_CODE'=>$value['IDENTITY_CODE'],
                    'MEMBER_TYPE_ID'=>$value['MEMBER_TYPE_ID']
                ));
            }
        } 
     echo json_encode($result);   exit;

    }
    function cancelRes(){
        $model = M ( "Location","AdvModel" );
        $model->addConnect ( C ( "DB_CRS" ), 1 );
        $model->switchConnect(1,'reservation');
        $map['RESERVATION_ID'] = $_GET['reservationid'];
        $data['STATUS']= 'CANCEL';
        $result = $model->where($map)->save($data);
        if($result>0){
            
            $this->success ('取消成功！');

        }
        $this->forward ();
    }
}
?>
