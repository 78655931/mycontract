<?php
// 用户模型
class AgreementModel extends CommonModel {
	public $_validate	=	array(
		
		array('CAR_TAG','require','昵称必须'),
		
		);

	
}
?>