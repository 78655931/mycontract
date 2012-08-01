<?php if (!defined('THINK_PATH')) exit();?><link rel="stylesheet" href="__PUBLIC__/css/booking.css" type="text/css" media="screen" />
<link rel="stylesheet" href="__PUBLIC__/css/jquery.ui.css" type="text/css" media="all" /> 
<style>
	.list tr {
		margin-bottom: 10px;
	}

	.label-left{
		float: left;
		width:70px;
	}

	.label-right{
		float: left;
		width:100px;
	}

	.cost
	{
		margin-bottom: 8px;
	}
</style>
<script type="text/javascript">
	$(function(){
		$.ajaxSettings.global=false;
		optionprice('<?php echo ($vo["BASE_RATE_QTY"]); ?>');
	});
	
	function optionprice(days){

				var confirmation ='<?php echo ($vo["agreement_id"]); ?>';
				$.getJSON('__URL__/optionprice',{table:'agreement_option','CONFIRMATION':confirmation},function(data){
				var perunit;
				var unit;
				var flats;
				var totalPrice=0;

					$.each(data,function(i,item){
						//alert(item.OPTION_NAME);
						//$("#optionprice").text(item.OPTION_NAME);
					//$("<option></option>").val(item['CAR_TAG']).text(item.CAR_TAG).appendTo($('#cartags'));
						perunit = item.PER_UNIT;
				switch(perunit){
				case "1":
				unit="次";
				flats = parseInt(perunit);
				break;
				case "D":
				unit = "天";
				flats = days;
				break;
				case "H":
					unit = "每小时";
				break;
				case "W":
					unit = "每周";
				break;
				case "M":
					unit="每月";
				break;
				}
				if(item.MANDATORY=='N'){
					
				$("<div style='margin-bottom: 5px;'></div>").html('<div class="cost" id="cost-option-'+item['OPTION_ID']+ '" style="display:block;"><span class="item">'+item.OPTION_NAME+'</span><span class="price"><span class="option-qty">'+flats+'</span><span class="option-unit">'+unit+'*</span><span class="option-rate">' +item ['RATE'] + '</span>元*'+item.QTY+'个 ＝<span class="option-amt">' +flats*item.RATE*item.QTY + '</span> 元</span></div>').appendTo("#optionprice2");
				}else{

				$("<div style='margin-bottom: 5px;'></div>").html('<div class="cost" id="cost-option-'+item['OPTION_ID']+ '" style="display:block;"><span class="item">'+item.OPTION_NAME+'</span><span class="price"><span class="option-qty">'+flats+'</span><span class="option-unit">'+unit+'*</span><span class="option-rate">' +item ['RATE'] + '</span>元*'+item.QTY+' ＝<span class="option-amt">' +flats*item.RATE*item.QTY + '</span> 元</span></div>').appendTo("#mandytail");
				}

					//	$("<div style='margin-bottom: 5px;'></div>").html('<div class="cost" id="cost-option-'+item['OPTION_ID']+ '" style="display: block;"><span class="item">'+item ['OPTION_NAME']+'</span><span class="price"><span class="option-qty">'+days+'</span><span class="option-unit">天*</span><span class="option-rate">' +item ['RATE'] + '</span> 元 ＝<span class="option-amt">' +days*item.RATE + '</span> 元</span></div>').appendTo("#optionprice2");
						totalPrice +=days*item.RATE;
		});
					$("#total-amt").val(totalPrice);
		});
}

	/**
	**/
</script>
<div class="pageContent" layoutH="30">
	<!--table width="98%" class="list">
		<thead>
			<tr>
				<th width="100">预定人名称</th>
				<th width="200">取车时间</th>
				<th width="200">还车时间</th>
				<th width="200">预定车型</th>
				<th width="100">租金</th>
				<th width="100">租期类型</th>
				<th width="100">超天价格</th>
				<th width="100">超小时价格</th>
				<th width="100">费用总计</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php echo ($vo["REAL_NAME"]); ?></td>
				<td><?php echo (substr($vo["PICKUP_DATE"],0,16)); ?></td>
				<td><?php echo (substr($vo["RETURN_DATE"],0,16)); ?></td>
				<td ><?php echo (getObjInfo($vo["CAR_MODEL"],'carModel','carModelCode','CAR_MODEL_NAME')); ?></td>
				<td ><?php echo ($vo["RATE_AMT"]); ?></td>
				<td>自驾</td>
				<td><?php echo ($vo["XDAY"]); ?></td>
				<td><?php echo ($vo["XHOUR"]); ?></td>
				<td id="TOTAL_PRICE1"><?php echo ($vo["TOTAL_PRICE"]); ?></td>
			</tr>
		</tbody>
	</table-->
	
	<form id="myform" action="__URL__/createagreement" method="post">
		<input type="hidden" name="REAL_NAME" value="<?php echo ($vo["REAL_NAME"]); ?>" />
		<input type="hidden" name="CAR_MODEL_NAME" value="<?php echo ($vo["CAR_MODEL_NAME"]); ?>"/>
		<input type="hidden" name="XDAY" value="<?php echo ($vo["XDAY"]); ?>"/>
		<input type="hidden" name="XHOUR" value="<?php echo ($vo["XHOUR"]); ?>" />
		<input type="hidden" name="TOTAL_PRICE" value="<?php echo ($vo["TOTAL_PRICE"]); ?>"/>
		<input type="hidden" name="confirmation" value="<?php echo ($confirmation); ?>"/>
		<input type="hidden" name="location_code" value="<?php echo ($location_code); ?>"/>

		<table class="list" width="98%" style="margin-top:10px;height:233px">
			<tr>
				<td colspan="2" style="text-align:center">会员信息</td>
			</tr>
			<tr>
				<td  style="width: 400px;">
					<label class="label-left">会员类型:</label>
					<input type="text" readonly name="MEMBER_TYPE_NAME" value="<?php echo ($vo["MEMBER_TYPE_NAME"]); ?>"/></td>
				<td>
					<label class="label-right">租车人手机:</label>
					<input type="text" readonly name="work_phone" value="<?php echo ($vo["work_phone"]); ?>"/>
				</td>
			</tr>	
			<tr>
				<td  style="width: 400px;">
					<label class="label-left">姓名:</label>
					<input type="text" readonly  readonly id="REAL_NAME" name="REAL_NAME" value="<?php echo ($vo["REAL_NAME"]); ?>"/>	</td>
				<td>
					<label class="label-right">紧急联系人:</label><input type="text" readonly name="contactperson" value="<?php echo ($vo["contactperson"]); ?>"/>
					电话:<input type="text" readonly name="contactphone" value="<?php echo ($vo["contactphone"]); ?>"/>
				</td>
			</tr>	
			<tr>
				<td  style="width: 400px;">
					<label class="label-left">性别:</label>
					<input type="text" readonly readonly name="sex" id="sex" value="<?php echo ($vo["sex"]); ?>"/>
					出生日期:<input type="text" readonly readonly name="age" readonly value="<?php echo ($vo["age"]); ?>"/>
				</td>
				<td>
					<label class="label-right">驾驶证号码:</label>
					<input type="text" readonly name="IDENTITY_CODE" value="<?php echo ($vo["IDENTITY_CODE"]); ?>"/>
					<input type="hidden" name="DRIVER_CODE" value="<?php echo ($vo["IDENTITY_CODE"]); ?>"/>
				</td>
			</tr>	
			<tr>
				<td  style="width: 400px;">
					<label class="label-left">电子邮件:</label>
					<input type="text" readonly name="email" value="<?php echo ($vo["email"]); ?>" />
				</td>
				<td>
					<label class="label-right">驾驶证准驾车型:</label>
					<input type="text" readonly value=<?php echo ($vo["vehicletype"]); ?> />
				</td>
			</tr>	
			<tr>
				<td  style="width: 400px;">
					<label class="label-left">身份证号码:</label>
					<input type="text" readonly readonly id="IDENTITY_CODE" name="IDENTITY_CODE" value="<?php echo ($vo["IDENTITY_CODE"]); ?>"/>

				</td>
				<td>
					<label class="label-right">发证城市:</label>
					<input type="text" readonly name="driverofcity" value="<?php echo ($vo["driverofcity"]); ?>"/>

				</td>
			</tr>	
			<tr>				<td  style="width: 400px;">
					<label class="label-left">身份证地址:</label>
					<input type="text" readonly readonly name="address" size="50"  value="<?php echo ($vo["address"]); ?>"/>

				</td>
				<td>
					<label class="label-right">初次领证日期:</label>
					<input type="text" readonly  name="taketime" value="<?php echo ($vo["taketime"]); ?>" />

				</td>
			</tr>	
			<tr>
				<td  colspan="2" style="width: 400px;">
					<label class="label-left">现住址:</label>
					<input type="text" readonly size="50" name="consummeraddr" value="<?php echo ($vo["consummeraddr"]); ?>" />

				</td>
			</tr>	
		</table>	
		<table class="list" width="98%" style="margin-top:10px">
			<tbody>
				<tr>
					<td style="width: 270px;text-align:center">
						<ul>
							<li style="font-weight: bold">
							租期:<span>共</span><span id="delta-t" style="color: #fd6c02;"><?php echo ($vo["BASE_RATE_QTY"]); ?></span>天
							</li>						</ul>
						<input type="hidden" name="BASE_RATE_QTY" value="<?php echo ($vo["BASE_RATE_QTY"]); ?>"/>
						<br />
						<div style="display:block;margin-bottom:10px">
							<label style="width:15px">实际取车时间:</label>	
							<input type="text" readonly  name="PICKUP_DATE"    value="<?php echo ($vo["PICKUP_DATE"]); ?>"   id="from"/>	
						</div>
						<div style="display:block;margin-bottom:10px">
							<label style="width:15px">实际还车时间:</label>
							<input type="text" readonly id="to" name="RETURN_DATE"  value="<?php echo (substr($vo["RETURN_DATE"],0,16)); ?>" />

						</div>
					</td>
					<td>

						<table class="list" width="100%" style="height:80px">
							<thead>
								<tr>
									<th width="60">车牌号</th>
									<th width="80">车辆类型</th>
									<th width="200">车辆型号</th>
									<th width="100">当前里程</th>
									<th width="100">当前油量</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><input class="readonly"  value="<?php echo ($vo["CAR_TAG"]); ?>" readonly="readonly" type="text"/></td>
									<td>
										<input class="readonly" value="<?php echo ($vo["CAR_MODEL"]); ?>"  readonly="readonly" type="text"/>
									</td>
									<td>
										<input class="readonly" value="<?php echo ($vo["CAR_MODEL_NAME"]); ?>" size="40" readonly="readonly" type="text" />
									</td>
									<td>
										<input class="text"  value="<?php echo ($vo["CURRENT_KM"]); ?>" readonly="readonly" type="text" />
									</td>
									<td>
										<input class="text" value="<?php echo (getOilpercent($vo["CURRENT_OIL"])); ?>" readonly="readonly" type="text" />
									</td>
								</tr>
							</tbody>
						</table>

					</td>
				</tr>
			</tbody>
		</table>

		<table class="list" width="98%" style="margin-top:10px">
			<tbody>
				<tr>
					<td style="width:270px;">

						<input type="hidden" name="RATE_AMT" value="<?php echo ($vo["BASE_RATE_AMT"]); ?>"/>
						<input type="hidden" name="CURRENT_KM" value="<?php echo ($vo["CURRENT_KM"]); ?>"/>
						<input type="hidden" name="MANDATORY_CHARGES" value="<?php echo ($vo["MANDATORY_CHARGES"]); ?>"/>
						<input type="hidden" name="BASE_RATE_QTY" value="<?php echo ($vo["BASE_RATE_QTY"]); ?>"/>


						<h1 style="margin-bottom:20px">价格计算(租期<span id="delta-o" style="color: #fd6c02;"><?php echo ($vo["BASE_RATE_QTY"]); ?></span>天)</h1>
						<ul>
							<li align="right" class="cost">基本租金:<span class="option-amt"><?php echo ($vo["RATE_AMT"]); ?></span></li>
							<li align="right" class="cost">必须费用:<span class="option-amt"><?php echo ($vo["MANDATORY_CHARGES"]); ?></span></li>
							<li align="right" class="cost">必须费用明细:<span id="mandytail"></span></li>
							<li align="right" class="cost">增值服务费用:<span class="option-amt"><?php echo ($vo["OPTIONS_CHARGES"]); ?></span></li>
							<li id="optionprice2"> <?php echo (optionPrice($location_code,$Think['get']['id'],$vo['BASE_RATE_QTY'])); ?> </li>
							<li align="right">总金额: <span id="total-amt"><?php echo ($vo["TOTAL_PRICE"]); ?></span> 元

							<li align="right">实际金额: <span id="total-real"><?php echo ($vo["REALTOTAL"]); ?></span> 元

						</ul>
					</td>
					<td>	
						<h1>增值服务</h1>
						<div style="width:220px">	<?php echo (getOptions($vo["agreement_id"])); ?>
						</div>

					</td>
					
				</tr>
				<tr>
					<td colspan="2">						<a target="_blank" mask=true max=true maxable=true minable=true resizable=true drawable=true ref="contract"  href="__URL__/gotoPrint/agreementid/<?php echo (base64_encode($_GET['id'])); ?>">打印合同</a>					</td></tr>	
			</tbody>
		</table>
	</form>
</div>