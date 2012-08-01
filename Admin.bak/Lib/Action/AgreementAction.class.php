<?php
class AgreementAction extends CommonAction {
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
		$Model = M ( "Location","AdvModel" );
		// 新增新的数据库参数
		$Model->addConnect ( C ( "DB_CRS" ), 1 );
		// 切换数据库
		$Model->switchConnect ( 1, "agreement" );
		// $nowdate = Date("Y-m-d");
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
		$map['STATUS'] =array('neq','CANCEL') ;
		// 取得满足条件的记录数
		$count = $Model->where ( $map )->count ( $Model->getPk () );
		//echo $Model->getLastSql();exit;
		if ($count > 0) {
			import ( "@.ORG.Page" );
			// 创建分页对象
			if (! empty ( $_REQUEST ['listRows'] )) {
				$listRows = $_REQUEST ['listRows'];
			} else {
				$listRows = '12';
			}

			$p = new Page ( $count, $listRows );
			// 分页查询数据

			$voList = $Model->where ( $map )->order ( "`" . $order . "` " . $sort )->limit ( $p->firstRow . ',' . $p->listRows )->findAll ();
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
			$this->assign('pickup_date',$_REQUEST['pickup_date']);
			$this->assign('identitycode',$_REQUEST['identity_code']);
			$this->assign ( 'list', $voList );
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
		$this->display();			
	}
	public function showcontract(){
		$Model = M ( "Location","AdvModel" );
		$Model->addConnect ( C ( "DB_CRS" ), 1 );
		$Model->switchConnect ( 1, "agreement" );
		$id = $_REQUEST ['id'];
		$vo = $Model->where ( "agreement_id='".$id."'"  )->find ();
		//echo $Model->getLastSql();exit;
		$Model->switchConnect ( 1, "agreement" );
		$vo ['newcfm'] = $vo['location_code'].'-'.substr($vo['agreement_id'],2);
		$BASE_RATE_QTY = $vo['BASE_RATE_QTY'];
		//增值费用
		$confirmation = $vo['location_code'].'-'.str_replace('HT','',$vo['agreement_id']);
		$Model->switchConnect(1,"agreement_option");
		$mandy=$Model->where("CONFIRMATION='".$confirmation."' and MANDATORY='N'")->select();
		$this->assign("confirmation",$confirmation);
		foreach($mandy as $k=>$v){
				$rate += $v['RATE']*$BASE_RATE_QTY;
		}
		$this->assign ( 'rate', $rate );
		$this->assign ( 'location_code', $vo ['PICKUP_LOCATION_CODE'] );
		$this->assign ( 'vo', $vo );
		$this->display();
	}
	public function gotoPrint($agreementid = '') {
				$agreementid = base64_decode($_REQUEST ['agreementid']);
		if(empty($agreementid)){
			$agreementid = base64_decode($_REQUEST['id']);
		}
		//$model = M( "Location","AdvModel" );
		//$model->addConnect ( C ( "DB_CRS" ), 1 );
		//$model->switchConnect ( 1, "agreement" );
		//$list = $model->getByAgreementId ( $agreementid );
		$Model = M ( "Location","AdvModel" );
		// 新增新的数据库参数
		$Model->addConnect ( C ( "DB_CRS" ), 1 );
		$Model->switchConnect ( 1, "agreement" );

		$vo = $Model->getByAgreementId($agreementid);
		$BASE_RATE_QTY = $vo['BASE_RATE_QTY'];
		//增值费用
		$confirmation = $vo['location_code'].'-'.str_replace('HT','',$vo['agreement_id']);
		$Model->switchConnect(1,"agreement_option");
		$mandy=$Model->where("CONFIRMATION='".$confirmation."' and MANDATORY='N'")->select();
		$this->assign("confirmation",$confirmation);
		foreach($mandy as $k=>$v){
				$rate += $v['RATE']*$BASE_RATE_QTY;
		}


		$Model->switchConnect ( 1, "company" );
		$company = $Model->find();
		$Model->switchConnect ( 1, "brand" );
		$brand = $Model->getByCompanyId($company['COMPANY_ID']);
		$Model->switchConnect(1,"reservation");
		$reservation = $Model->where("CONFIRMATION='".$confirmation."' and STATUS!='CANCEL'")->find();
		$cartypecode = $reservation['CAR_TYPE_CODE'];
		$Model->switchConnect(1,'car_type');
		$cartype = $Model->getByCarTypeCode($cartypecode);
		$Model->switchConnect(1,'car');
		$this->assign('car',$Model->getByCarTag($vo['CAR_TAG']));
		$Model->switchConnect(1,"uni_rule");
		$this->assign('unirule',$Model->getByRuleCode($reservation['RULE_CODE']));
		$this->assign('cartype',$cartype);
		$this->assign('date',date('Y-m-d h:s:m'));
		$this->assign('reservation',$reservation);
		$this->assign('brand',$brand);
		$this->assign('company',$company);
		$this->assign ( 'rate', $rate );

		$this->assign("vo",$vo);
		$this->display();
	}
	/**
	 * 个吖方法
	 **/
	public function newcontract()
	{
		// code...
		$vo['PICKUP_DATE'] = date('Y-m-d H:i');
		$date = strtotime(date('Y-m-d H:i'));
		$vo['RETURN_DATE'] =date('Y-m-d H:i',$date + 2*24*60*60);
		$vo['BASE_RATE_QTY'] =2;
		$this->assign("vo",$vo);
		$this->assign('location_code',$_SESSION['location_code']);
		$this->display();
	}
	public function getCarType()
	{
		// code...
		$city_url = C("CARTYPE") ;
		//echo $city_url;
		$result = $this->curl($city_url);
		echo $result;
		exit;

		
	}
	public function getCarModel()
	{
		// code...
		$map['CAR_TYPE_CODE'] = $_GET['cartype'];
		$map['LOCATION_CODE'] = $_SESSION['location_code'];
		$Model = M ( "Location","AdvModel" );
		$Model->addConnect ( C ( "DB_CRS" ), 1 );
		$Model->switchConnect ( 1, "location_cartype_model" );
		$list = $Model->where($map)->findAll();
		if(empty($list)){
			exit(json_encode(-1));
		}
		header ( "Content-Type:text/html; charset=utf-8" );
		exit ( json_encode ( $list ) );

	}
	public function getCarTags()
	{
		// code...
		$Model = M ( "Location","AdvModel" );
		$Model->addConnect ( C ( "DB_CRS" ), 1 );
		$Model->switchConnect ( 1, "car" );
		$map['status'] =2;
		$map['CAR_MODEL_CODE'] = $_GET['carmodel'];
		$map['CURRENT_LOCATION_CODE'] = $_SESSION['location_code'];
		$list = $Model->where($map)->findAll();
		if(empty($list)){
			exit(json_encode(-1));
		}
		header ( "Content-Type:text/html; charset=utf-8" );
		exit ( json_encode ( $list ) );

	}
	public function optionprice()
	{
		// code...
		$Model = M ( "Location","AdvModel" );
		$Model->addConnect ( C ( "DB_CRS" ), 1 );
		if($_GET['table']){

			$Model->switchConnect ( 1, "agreement_option" );
			$map['CONFIRMATION'] =$_GET['CONFIRMATION'];
			//$map['MANDATORY'] ='N';
		}else{
			$Model->switchConnect ( 1, "location_option" );
		// $locationOpt = M ( 'location_option' );
		$map ['location_code'] = $_SESSION['location_code'];

		}
		$listOpt = $Model->where ( $map )->group ( 'option_id' )->select ();
		if(empty($listOpt)){
			exit(json_encode(-1));
		}
		header ( "Content-Type:text/html; charset=utf-8" );
		exit ( json_encode ( $listOpt ) );
	}
	/**
	public function optionprice()
	{
		// code...
		$Model = M ( "Location","AdvModel" );
		$Model->addConnect ( C ( "DB_CRS" ), 1 );

		$map ['CONFIRMATION'] = array('like',"%".$_SESSION['location_code']."%");
		$Model->switchConnect ( 1, "reservation_option" );
		$reserOpt = $Model->where ( $map )->select ();
		if ($reserOpt) {
			foreach ( $reserOpt as $k => $v ) {
				if ($v['MANDATORY']=='N') {
					// code...

					$optionID[] = $v['OPTION_ID'];
				}
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
		foreach($listOpt as $k=>$v){
				if (in_array($v['OPTION_ID'],$optionID)) {
					// code...
					$listOpt[$k]['block'] ="true";
				}
				$optionid = $v['OPTION_ID'];
				$listOpt[$k]['option_name'] = $options[$optionid];
		}
		header ( "Content-Type:text/html; charset=utf-8" );
		exit ( json_encode ( $listOpt ) );
	}
	*/
	public function getMember()
	{
		// code...
		$Model = M ( "Location","AdvModel" );
		$Model->addConnect ( C ( "DB_CRS" ), 1 );
		$Model->switchConnect ( 1, "member" );
		$validate = array (array ('HOME_PHONE', 'require', '电话必须' ));
		$Model->setProperty ( "_validate", $validate );
		$result = $Model->create($_GET); 
		if(!$result){
			exit($Model->getError()); 
		}
		$map['HOME_PHONE'] = $_GET['phonenumber'];
		$map['REAL_NAME'] = $_GET['realname'];
		$map['IDENTITY_CODE'] = $_GET['IDENTITY_CODE'];
		$map['_logic'] = 'or'; 
		$list = $Model->where($map)->find();
		//echo $Model->getLastSql();
		$Model->switchConnect ( 1, "member_type" );
		$vo =$Model->getByMemberTypeId($list['MEMBER_TYPE_ID']);
		$list['MEMBER_TYPE_NAME'] = $vo['MEMBER_TYPE_NAME'];
		header ( "Content-Type:text/html; charset=utf-8" );
				
		if(empty($list)){
			echo '-1';exit;
		}else{
			exit ( json_encode ( $list ) );
		}

	}
	public function addMember()
	{
		//echo "asdf";exit;
		// code...
		$Model = M ( "Location","AdvModel" );
		$Model->addConnect ( C ( "DB_CRS" ), 1 );
		$Model->switchConnect ( 1, "brand" );
		$memberUrl = C('MEMURL');
		import('@.ORG.String');
		$password = String::rand_string(6,'3');
		$map['BRAND_CODE']=C('BRANDCODE');
		 $locationcode= $_SESSION['location_code'];
		$vo =  $Model->where($map)->find();
		$disurl = $memberUrl."ruRequest.ver=".urlencode('eng 1.0  ryxml 1.0')."&ruRequest.function=ru&ruRequest.transId=ry_gongsi_1&ruRequest.companyCode=GONGSI&ruRequest.brandCode=".C('BRANDCODE')."&ruRequest.brandName=".$vo['BRAND_NAME']."&ruRequest.sourceCode=w&ruRequest.vendorCode=W-XIECHENGWANG&ruRequest.vendorPass=123456&ruRequest.ipaddress=127.0.0.1&ruRequest.langCode=zh_cn&ruRequest.passwordEncrypt=pwd&ruRequest.siteUrl=http://www.rongyitech.com&ruRequest.referrerMember=&ruRequest.consummerAddr=西安市长安区&ruRequest.consummerCity=西安&ruRequest.workPhone=&ruRequest.nickName=昵称&ruRequest.email=".$_GET['email']."&ruRequest.realName=".trim($_GET['REAL_NAME'])."&ruRequest.homePhone=".$_GET['work_phone']."&ruRequest.identityTypeName=身份证&ruRequest.identityCode=".$_GET['IDENTITY_CODE']."&ruRequest.password=".$password."&ruRequest.repassword=".$password."";
		//echo $disurl;exit;
		$result = $this->curl($disurl);
		echo $result;exit;
	}

	public function createagreement() {
		$model = M ( "Location","AdvModel" );
		$model->addConnect ( C ( "DB_CRS" ), 1 );
		$model->switchConnect ( 1, "agreement" );
		$optionidList = $_POST['option'];
		$_POST ['agreement_id'] = 'HT' . date('YmdHis').rand(0,6);
		$_POST ['createdate'] = time ();
		$_POST ['status'] = "CONTRACT";
		$_POST['location_code'] = $_SESSION['location_code'];
		$validate = array (array ('CAR_TAG', 'require', '车牌必须!' ),
			array('REAL_NAME','require','请填入真实姓名!'),
			array('IDENTITY_CODE','require','身份证号为空!'),
			array('work_phone','require','手机号码为空!'),
			array('MEMBER_TYPE_NAME','require','您还未成为会员，请注册!'),
			array('email','require','电子邮件地址为空!'),
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
			$model->switchConnect ( 1, "location_option" );
			$map['LOCATION_CODE']	= $_SESSION['location_code'];
			$resOpt = $model->where($map)->group('option_id')->select();
			unset($map);
			$model->switchConnect(1,'uni_option');
			$map['option_id'] = array('in',$_REQUEST['option']);
			$map['location_code'] = $_SESSION['location_code'];
			$uniOpt = $model->where($map)->group('option_id')->select();
			$model->switchConnect ( 1, "agreement_option" );
			foreach($uniOpt as $k=>$v)
			{
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
			unset($data);
				$model->switchConnect ( 1, "agreement_disc" );
				$data['CONFIRMATION'] = $_POST ['agreement_id'];
				$data['UNI_DISC_ID'] =$_REQUEST['discount'];
				$data['DISC_NAME'] = $_REQUEST['discountname'];
				//dump($data);exit;
				$model->add($data);
				$model->switchConnect ( 1, "reservation" );
				$reservation = $model->where("CONFIRMATION='" . $_REQUEST['location_code'].'-'.$_REQUEST['confirmation']. "'")->find();
				$resup = $model->execute ( "update reservation set  STATUS='CONTRACT' where CONFIRMATION='" . $_REQUEST['location_code'].'-'.$_REQUEST['confirmation']. "'" );
				$model->switchConnect(1,'car');
				$carinfo = $model->getByCarTag($cartag);
				if($carinfo['STATUS']==2){
					$cares = $model->execute("update car set status=1 where CAR_TAG='".$cartag."' and CAR_MODEL_CODE='".$carmodelcode."'");
				}
				//修改实际库存
				$model->switchConnect(1,"uni_inventory");
				unset($map);
				//$map('CAR_MODEL_CODE') = ;
				
				header ( "Content-Type:text/html; charset=utf-8" );
				echo $_POST ['agreement_id'];exit;
		
		} else {
			// 失败提示
			header ( "Content-Type:text/html; charset=utf-8" );
			echo "-1";
			exit ();
		}
	}
	public function getCarInfo()
	{
		// code...
		$Model = M ( "Location","AdvModel" );
		$Model->addConnect ( C ( "DB_CRS" ), 1 );
		$Model->switchConnect ( 1, "uni_rate" );
		$locationcode = $_SESSION['location_code'];
		//必须费用
		$cartypecode = $_GET['cartypecode'];
		$carmodelcode = $_GET['carmodel'];
		$list = $Model->where('left(start_date,10)="'.date('Y-m-d').'" and LOCATION_CODE="'.$locationcode.'" and CAR_MODEL_CODE="'.$carmodelcode.'" and rate_code="LOC"  and car_type_code="'.$cartypecode.'"')->find();
		$Model->switchConnect ( 1, "car" );
		$map['status'] =2;
		$map['CAR_MODEL_CODE'] = $_GET['carmodel'];
		$map['CURRENT_LOCATION_CODE'] = $_SESSION['location_code'];
		$map['CAR_TAG'] =$_GET['CAR_TAG'];
		$list2 = $Model->where($map)->find();
		$Model->switchConnect(1,"car_model");
		$list3 = $Model->where('CAR_MODEL_CODE="'.$_GET['carmodel'].'" and car_type_code="'.$cartypecode.'" ')->find();
		//echo $Model->getLastSql();exit;
		header ( "Content-Type:text/html; charset=utf-8" );

		exit ( json_encode (array_merge($list,$list2,$list3) ) );

	}
	public function returnedCar()
	{
		// code...
		
		$Model = M ( "Location","AdvModel" );
		$Model->addConnect ( C ( "DB_CRS" ), 1 );
		$Model->switchConnect ( 1, "agreement" );
		if($_GET['agreement_id']){
			$vo = $Model->where('agreement_id="'.$_GET['agreement_id'].'"')->find();
			$this->assign("vo",$vo);
			$this->display("Agreement:returncar");
			exit;
		}
		$map['RETURN_KM'] = $_POST['return_km'];
		$map['RETURN_OIL'] = $_POST['return_oil'];
		$map['REAL_RETURN_DATE'] = $_POST['REAL_RETURN_DATE'];
		$map['status'] = 'RETURN';
		if (false === $Model->create ($map)) {
			$this->error ( $Model->getError () );
		}
		// 更新数据
		$list=$Model->where('agreement_id="'.$_POST['agreement_id'].'"')->save ($map);
		if($list){
			//修改预定单状态

			$vo = $Model->where('agreement_id="'.$_POST['agreement_id'].'"')->find();
			unset($map);
			$confirmation = $vo['location_code'].'-'.str_replace('HT','',$_POST['agreement_id']);
			$map['STATUS'] = 'RETURN';
			$Model->switchConnect(1,"reservation");
			$cons = $Model->where('CONFIRMATION="'.$confirmation.'"')->find();
			if (count($cons)) {
				// code...

			if (false === $Model->create ($map)) {

					$this->error ( $Model->getError () );
				}

				$Model->where('CONFIRMATION="'.$confirmation.'"')->save($map);
			}
			
			
			//修改车状态
			unset($map);
			$Model->switchConnect(1,"car");
			$map['STATUS'] = 2;
			if (false === $Model->create ($map)) {
				$this->error ( $Model->getError () );
			}
			$Model->where('CAR_TAG="'.$vo['CAR_TAG'].'"')->save($map);
			$Model->switchConnect(1,'uni_inventory');
			$Model->where("LOCATION_CODE='".$_SESSION['location_code']."' and CAR_MODEL_CODE='".$vo['CAR_MODEL_CODE']."' and left(START_DATE,10)='".date('Y-m-d')."'")->setInc('REAL_INT',1);

			//真实库存
			echo $vo['status'];exit;
		}

	}
}
?>
