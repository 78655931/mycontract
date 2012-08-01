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
			$map['WORK_PHONE'] = array('eq',"".$_POST['WORK_PHONE']."");
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
		$map['CONFIRMATION'] = array('like',"%".$_SESSION['location_code']."%");
		if(empty($_POST['STATUS']))
		$map['STATUS'] = array(array('neq','CONTRACT'),array('neq','CANCEL'),array('neq','RETURN'));
		//dump($map);exit;
		// 取得满足条件的记录数
		$count = $Model->where ( $map )->count ( $Model->getPk () );
	//	echo $Model->getLastSql();
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
			$voList = $Model->where ( $map )->field ( 'reservation_id,right(CONFIRMATION,15) as confirmation,real_name,identity_code,home_phone,left(pickup_date,16) as pickup_date,left(return_date,16) as return_date,car_type_name,CAR_MODEL_NAME,status' )->order ( "`" . $order . "` " . $sort )->limit ( $p->firstRow . ',' . $p->listRows )->findAll ();
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
		$vo ['newcfm'] = $confirmation [0] . '-' . $confirmation [1] . '-' . $confirmation [2];
		if ($agreeList) {
			$vo ['status'] = $agreeList ['status'];
			$vo['agreement_id'] = $agreeList['id'];

        }
        /**
		//折扣信息
		$ipaddress="172.16.100.173";
		$discount_url = C('DISCOUNT');
		//dump($POST);exit;
		//拼接

		$companycode = "GONGSI";
		$pickupLocationCode = str_replace('MENGEN','GONGSI',$vo['PICKUP_LOCATION_CODE']);
		$disurl = $discount_url."dsRequest.ver=".urlencode('eng 1.0  ryxml 1.0')."&dsRequest.function=ds&dsRequest.transId=ry_GONGSI_1&dsRequest.companyCode=". str_replace('MENGEN','GONGSI',$vo['COMPANY_CODE'])."&dsRequest.brandCode=GONGSI&dsRequest.sourceCode=".$vo['SOURCE_CODE']."&dsRequest.vendorCode=W-GONGSI&dsRequest.vendorPass=".$vo['VENDOR_PASS']."&dsRequest.pickupCityCode=".$vo['PICKUP_CITY_CODE']."&dsRequest.pickupDistrictCode=".$vo['PICKUP_DISTRICT_CODE']."&dsRequest.pickupLocationCode=". str_replace('MENGEN','GONGSI',$vo['PICKUP_LOCATION_CODE'])."&dsRequest.pickupDate=".urlencode($vo['PICKUP_DATE'])."&dsRequest.returnDate=".urlencode($vo['RETURN_DATE'])."&dsRequest.returnCityCode=".$vo['RETURN_CITY_CODE']."&dsRequest.returnDistrictCode=".$vo['RETURN_DISTRICT_CODE']."&dsRequest.returnLocationCode=". str_replace('MENGEN','GONGSI',$vo['RETURN_LOCATION_CODE'])."&dsRequest.carTypeCode=".$carTypeCode."&dsRequest.carModelCode=".$carmodelcode."&dsRequest.rateCode=WEB&dsRequest.ipaddress=".$ipaddress."&dsRequest.langCode=zh_cn&dsRequest.loginName=ZOUCHANGLIANG&dsRequest.totalPrice=".$vo['TOTAL_PRICE'];
		//echo $disurl;
		$result = $this->curl($disurl);
		$result = json_decode($result);
		$result = $result->dsResponse->discounts->discount;
		foreach($result as $k=>$v){
			$vs[]=(array)$v;

		}
		//折扣选择
		$Model->switchConnect ( 1, "reservation_disc" );
		$discountck = $Model->where("CONFIRMATION='".$vo['CONFIRMATION']."'")->find();
		$vo['discountck'] = $discountck['UNI_DISC_ID'];
        $this->assign("discount",$vs);
        **/
		//均价
		$average = round($vo['BASE_RATE_AMT'] /$vo['BASE_RATE_QTY']);
		$vo ['agreementid'] = 'HT' . substr ( $vo ['CONFIRMATION'], - 15 );
		$Model->switchConnect ( 1, "uni_rate" );
		$unirate = $Model->getByLocationCode(substr ( $vo ['CONFIRMATION'], 0,15 ));
		$this->assign('unirate',$unirate);
		
		$this->assign('average',$average);
		$this->assign('datenow',date('Y-m-d h:m'));
		$this->assign ( 'confirmation', $_REQUEST ['confirmation'] );
		$this->assign ( 'location_code', $vo ['PICKUP_LOCATION_CODE'] );
        $this->assign ( 'vo', $vo );
        Debug::mark('end');
        Log::write('Edit运行时间：'.Debug::useTime('start','end'), Log::DEBUG);
		$this->display ();
	}
	public function selectCar(){
		$Model = M( "Car","AdvModel" );
		$Model->addConnect ( C ( "DB_CRS" ), 1 );
		$Model->switchConnect ( 1, "Car" );
		$carmodelcode = $_REQUEST ['modelcode'];
		$map['CAR_MODEL_CODE'] = $carmodelcode;
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
		$Model = M("Driver");
		$map['status'] = 1;
		$this->_lists($Model,$map,'id');
		$this->display();
	}
	public function createdj(){
		$model = D("reservationby");
		$data=$_POST;
		$data['driverCarType'] = $_POST['master_dwz_devLookup_CAR_MODEL'];
		$data['carTag'] = $_POST['master_dwz_devLookup_CAR_TAG'];
		$data['carModelName'] = $_POST['master_dwz_devLookup_CAR_MODEL_NAME'];
		$data['currentkm'] = $_POST['master_dwz_devLookup_CURRENT_KM'];
		$data['currentoil']=$_POST['master_dwz_devLookup_CURRENT_OIL'];
		$data['drivername']=$_POST['master_dwz_devLookup_drivername'];
		$data['driverphone'] = $_POST['master_dwz_devLookup_phonenumber'];
		$data['status']='contract';
		if (false === $model->create ( $data )) {
			echo $model->getError ();
			exit ();
		}
		$list = $model->save($data);
		//echo $model->getLastSql();
		if($list>0){
			header ( "Content-Type:text/html; charset=utf-8" );
			exit ( json_encode ( 1 ) );

		}else{
			header ( "Content-Type:text/html; charset=utf-8" );
			echo "-1";
			exit ();

		}
	}
	public function createagreement() {
		$model = M ( "Location","AdvModel" );
		$model->addConnect ( C ( "DB_CRS" ), 1 );
		$model->switchConnect ( 1, "agreement" );
		$optionidList = $_POST['option'];
		$_POST ['agreement_id'] = 'HT' . $_REQUEST ['confirmation'];
	//	$_POST ['createdate'] = time ();

		$_POST ['createdate'] = date('Y-m-d h:m:s');
		$_POST ['status'] = "CONTRACT";
		//$data = str_ireplace("master_dwz_devLookup_","",$_REQUEST);
		//dump($data);exit;
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
		//dump($_POST);exit;
				// 保存当前数据对象
		$list = $model->add ( $_POST );
		if ($list !== false) { // 保存成功
			$model->switchConnect ( 1, "reservation_option" );
			$map['confirmation']	= $_REQUEST['location_code'].'-'.$_REQUEST['confirmation'];
			$resOpt = $model->where($map)->group('option_id')->select();
			unset($map);
			$model->switchConnect ( 1, "agreement_option" );
			//$reservationOption = $model->where('CONFIRMATION="'.$map['confirmation'].'"')->getField('OPTION_ID,QTY');
			/**
			foreach($uniOpt as $k=>$v)
			{
				//$data['CONFIRMATION'] = $_REQUEST['location_code'].'-'.$_REQUEST['confirmation'];
				$data['CONFIRMATION'] = $_POST ['agreement_id'];
				$data['OPTION_ID'] = $v['OPTION_ID'];
				$data['OPTION_NAME'] = $v['OPTION_NAME'];
				$data['OPTION_TYPE'] = $v['OPTION_TYPE'];
				$data['RATE'] = $v['RATE'];
				$data['PER_UNIT'] = $v['PER_UNIT'];
				$data['QTY'] = $REQUEST['rate_qty'];
				$data['AMT'] =  $REQUEST['rate_qty']*$v['PER_UNIT'];
				$data['SEQUENCE'] = $v['SEQUENCE'];
				$data['MANDATORY'] = $v['MANDATORY'];
				$model->add($data);
				//echo $model->getLastSql();exit;
			}
			**/
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
			/**
			foreach ($resOpt as $key=>$val){
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
				 //$map['OPTION_ID'] = array('not in',$optionID);
				if(!in_array($val['OPTION_ID'],$_REQUEST['option']) && $val['MANDATORY']!='Y'){

				$model->add($data);
				//echo $model->getLastSql();
				}
			}
			**/
				unset($data);	
				$model->switchConnect ( 1, "agreement_disc" );
				$data['CONFIRMATION'] = $_REQUEST['location_code'].'-'.$_REQUEST['confirmation'];
				$data['UNI_DISC_ID'] =$_REQUEST['discount'];
				$data['DISC_NAME'] = $_REQUEST['discountname'];
				//dump($data);exit;
				$model->add($data);
				$model->switchConnect ( 1, "reservation" );
				$resup = $model->execute ( "update reservation set  STATUS='CONTRACT' where CONFIRMATION='" . $_REQUEST['location_code'].'-'.$_REQUEST['confirmation']. "'" );
				//echo $resup;
				//exit;
				/**
				$model->switchConnect ( 1, "uni_inventory" );
				//$model = new Model ();
				$model->startTrans ();

				$res = $model->execute ( "update uni_inventory set  `LOC_INT`=LOC_INT-1 where CAR_MODEL_CODE='" . $carmodelcode. "'" );
				//echo "RES".$res;exit;
				//echo $model->getLastSql();exit;
				if ($res > 0) {
					$model->commit ();
				} else {
					$model->rollback ();
				}
				**/
				$model->switchConnect(1,'car');
				$carinfo = $model->getByCarTag($cartag);
				if($carinfo['STATUS']==2){
					$cares = $model->execute("update car set status=1 where CAR_TAG='".$cartag."' and CAR_MODEL_CODE='".$carmodelcode."'");
				}
				$model->switchConnect(1,'uni_inventory');
				//$model->where("LOCATION_CODE='".$_SESSION['location_code']."' and CAR_MODEL_CODE='".$carmodelcode."' and left(START_DATE,10)>='".substr($_POST['PICKUP_DATE'],0,10)."' and left(END_DATE,10)<='".substr($_POST['RETURN_DATE'],0,10)."'")->setDec('REAL_INT',1);
				$model->execute("UPDATE `uni_inventory` SET `REAL_INT`=REAL_INT-1 where LOCATION_CODE='".$_SESSION['location_code']."' and CAR_MODEL_CODE='".$carmodelcode."' and left(START_DATE,10)>='".substr($_POST['PICKUP_DATE'],0,10)."' and left(END_DATE,10)<='".substr($_POST['RETURN_DATE'],0,10)."'");
				Log::write('调试癿SQL：'.$model->getLastSql(), Log::SQL); 
				
			header ( "Content-Type:text/html; charset=utf-8" );
			exit ( json_encode ( 1 ) );
		
		} else {
			// 失败提示
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
		echo $model;
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
        Debug::mark('start');
		// code...
		$Model = M ( "Location","AdvModel" );
		$Model->addConnect ( C ( "DB_CRS" ), 1 );
		$reservationid = $_SESSION['location_code'].'-'.$_GET['reservationid'];
		$map ['CONFIRMATION'] = $reservationid;
		$Model->switchConnect ( 1, "reservation_option" );
		$reserOpt = $Model->where ( $map )->select ();
		if ($reserOpt) {
			foreach ( $reserOpt as $k => $v ) {
				//if ($v['MANDATORY']=='N') {
					// code...

					$optionID[] = $v['OPTION_ID'];
					$resO[$v['OPTION_ID']] = $v['MANDATORY'];
				//}
			}
		}
		$Model->switchConnect ( 1, "location_option" );
		unset($map);
		$map['location_code'] = $_SESSION['location_code'];
		// $locationOpt = M ( 'location_option' );

		$listOpt = $Model->where ( $map )->group ( 'option_id' )->select ();
		//echo $Model->getLastSql();
		$Model->switchConnect ( 1, "options" );
		$options = $Model->getField('option_id,option_name');
		$Model->switchConnect ( 1, "reservation_option" );
		$reservation_options = $Model->where('CONFIRMATION="'.$reservationid.'"')->getField('option_id,qty');
		$reservation_amt = $Model->where('CONFIRMATION="'.$reservationid.'"')->getField('option_id,amt');
		foreach($listOpt as $k=>$v){
				if (in_array($v['OPTION_ID'],$optionID)) {
					// code...
					$listOpt[$k]['block'] ="true";
					$listOpt[$k]['MANDATORY']=$resO[$v['OPTION_ID']];
				}
				$optionid = $v['OPTION_ID'];
				$listOpt[$k]['option_name'] = $options[$optionid];
				$listOpt[$k]['QTY'] = $reservation_options[$optionid];
				$listOpt[$k]['AMT'] = $reservation_amt[$optionid];
        }
        Debug::mark('end');
        Log::write('optionPrice运行时间：'.Debug::useTime('start','end'), Log::DEBUG);
		header ( "Content-Type:text/html; charset=utf-8" );
		exit ( json_encode ( $listOpt ) );
	}

}
?>
