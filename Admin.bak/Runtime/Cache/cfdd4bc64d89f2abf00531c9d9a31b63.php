<?php if (!defined('THINK_PATH')) exit();?><script type="text/javascript" src="__PUBLIC__/js/booking.js"></script>
<script type="text/javascript" charset="utf-8" src="__PUBLIC__/js/card.js"></script>

<script type="text/javascript" charset="utf-8">
	//alert(CALC_DISCOUNT_URL+"?"+carTypeCode+"&"+carModelCode+"&"+pickupLocationCode+"&"+pickupDate+"&"+returnDate+"&"+uniDiscId);
	CALC_DISCOUNT_URL = 'http://172.16.100.47:8099/crsCarRental/rcaculateDiscount';
	var rate_code = '<?php echo ($vo["RATE_CODE"]); ?>';
	var carTypeCode = '<?php echo ($vo["CAR_TYPE_CODE"]); ?>';
	var carModelCode= '<?php echo ($vo["CAR_MODEL_CODE"]); ?>';
	var pickupLocationCode = '<?php echo ($vo["PICKUP_LOCATION_CODE"]); ?>';
	var pickupDate = '<?php echo (substr($vo["PICKUP_DATE"],0,16)); ?>';
	pickupDate =pickupDate.replaceAll("-","");
	pickupDate = pickupDate.replace(" ","");
	pickupDate = pickupDate.replace(":","");
	
	var returnDate = '<?php echo (substr($vo["RETURN_DATE"],0,16)); ?>';
	//alert(pickDate);
	returnDate = returnDate.replaceAll("-","");
	returnDate = returnDate.replace(" ","");
	returnDate = returnDate.replace(":","");

</script>

<script type="text/javascript" charset="utf-8">
	$.ajaxSettings.global=false;
	var totalPrice =0;
$(document).ready(function() {


		//$("#myform").validate();
		//alert(pickupDate);
	var str;
		str = SynCardOcx1.FindReader();
		if(str>0){

		SynCardOcx1.SetSexType (1);
		SynCardOcx1.SetNationType(2);
	}
		DAYS ='<?php echo ($vo["BASE_RATE_QTY"]); ?>';
	optionprice(DAYS);
		var dates = $( "#from, #to" ).datetimepicker({
			defaultdate: "+1w",
			timeFormat: 'hh:mm',
			showmonthafteryear: true,
			numberofmonths: 2,  
			mindate: 0 ,
			dateFormat:'yy-mm-dd',
			closetext:'关闭',
			duration: 'fast',
			showanim:'fadein',
			showon:'button', 
			buttontext:'选择日期',
			showbuttonpanel: false,
			stepHour: 2,//设置步长
			stepMinute: 10,
			stepSecond: 10,
			hourGrid: 4,
			minuteGrid: 10,

			onSelect: function( selectedDate ) {

				var option = this.id == "from" ? "minDate" : "maxDate",
				instance = $( this ).data( "datepicker" ),
				date = $.datepicker.parseDate(
					instance.settings.dateFormat ||
					$.datepicker._defaults.dateFormat,
				selectedDate, instance.settings );
				//dates.not( this ).datetimepicker( "option", option, date );

				var begintime = $("#from").val();
				var endtime = $("#to").val();

				//计算时间 差 
				if(begintime != "" && endtime != ""){
					var   bt   =   new Date(Date.parse(begintime.replace(/-/g,   "/")));   

					var   et   =   new  Date(Date.parse(endtime.replace(/-/g,   "/"))); 

					var days = et.getTime() - bt.getTime(); 
					var day =Math.round(days/(3600*24*1000)) ;
					DAYS = day;
					$("#delta-t").text(day);
					$("#delta-o").text(day);
					$("#delta-i").html('<input type="hidden" name="BASE_RATE_QTY" value="'+day+'"/>');
					$("input.checkbox-option").attr('checked', false);
					$("#optionprice").html("");
					$("#total-amt").html("");
					optionprice(DAYS);
				}

			}
		});


		var options = {
			beforeSubmit : showRequest, // pre-submit callback
			success : showResponse // post-submit callback
		};
		$('#myform').submit(function() {
			// 提交表单
			//alert("aaa");
		$('<input type="hidden" name="TOTAL_PRICE">').val($("#total-amt").text()).appendTo("#myform");
			//<?php echo ($vo["OPTIONS_CHARGES"]); ?>

		$('<input type="hidden" name="TOTAL_PRICE">').val($("#total-amt").text()).appendTo("#myform");
		$('<input type="hidden" name="OPTIONS_CHARGES">').val($("#charge").text()).appendTo("#myform");
		$('<input type="hidden" name="MANDATORY_CHARGES">').val($("#mandy").text()).appendTo("#myform");
			$(this).ajaxSubmit(options);
			// 为了防止普通浏览器进行表单提交和产生页面导航（防止页面刷新？）返回false
			return false;
		});
		
		function showRequest(formData, jqForm, options) {

			//INSERT INTO `agreement_option` (`CONFIRMATION`,`OPTION_ID`,`OPTION_NAME`,`OPTION_TYPE`,`RATE`,`PER_UNIT`,`QTY`,`AMT`,`SEQUENCE`,`MANDATORY`) VALUES ('MENGEN-610101-01-201206111118022','6','送车上门服务','O','10','D',null,null,'6','N')
			var queryString = $.param(formData);

			//alert(queryString);
			//alert('About to submit: \n\n' + queryString);
			return true;
		}
		// post-submit callback
		function showResponse(responseText, statusText, xhr, $form) {
			if (responseText == 1) {
				PRINTURL = '__APP__'+'/Agreement/gotoPrint/id/'+'<?php echo (base64_encode($vo["agreementid"])); ?>';
				//alert(PRINTURL);
				//return false;
				//$('#subbtn').attr('disabled', true);
				  alertMsg.correct('合同生成成功!');
				//alert("合同生成成功!");
				$('#subbtn').hide();
				$("#crtagreement").show().html('<input  type="button" onclick="openDialog();" value="打印合同"/>');
			} else {
				alert(responseText);
			}

		}

	});

	function ReadCard_onclick()
	{
		var nRet;
		SynCardOcx1.SetReadType(0);
		nRet = SynCardOcx1.ReadCardMsg();
		//alert(nRet);
		var REAL_NAME = $("#REAL_NAME").val();
		var IDENTITY_CODE = $("#IDENTITY_CODE").val();
		if(nRet==0)
		{	
			$("#sex").val(SynCardOcx1.Sex);
			document.all['address'].value = SynCardOcx1.Address;
			document.all['age'].value = SynCardOcx1.Born;
			//$("input[name=IDENTITY_CODE]").val(SynCardOcx1.CardNo);

			if(SynCardOcx1.NameA.trim() != REAL_NAME)
			{
				alert("姓名信息不符");
			}
			if(SynCardOcx1.CardNo.trim()!=IDENTITY_CODE)
			{
				alert("身份证号码不符");
			}
			/*if(SynCardOcx1.CardNo!=IDENTITY_CODE)
			{
				alert(123);
				}

				*/
		}
	} 
	
	function optionprice(days){
		//alert(days);
		
			
		//var totalPrice=0;
		var opp =0;
		var perunit;
		var unit;
		var flats;
		var charge=0;
		var mandy =0;
		var reservationid ='<?php echo ($_GET['confirmation']); ?>';
		$.getJSON('__URL__/optionprice',{'reservationid':reservationid},function(data){
			$.each(data,function(i,item){
				//alert(days * item['RATE']);
				//alert(item['OPTION_ID']);
				var shows;
				if(item.block=="true"){
					 shows = "block";
					//opp+=days*item.RATE;
					opp+=parseInt(item.AMT);
					 }else{
			
					shows = "none";
					}
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
				//alert(item.MANDATORY);
					if(item.QTY==null){
						item.QTY=1;
						item.AMT=parseInt(item.RATE)*item.QTY*flats;
						
					}
				if(item.MANDATORY=='N'){
				
				$("<div style='margin-bottom: 5px;'></div>").html('<div class="cost" id="cost-option-'+item['OPTION_ID']+ '" style="display:'+shows+';"><span class="item">'+item.option_name+'</span><span class="price"><span class="option-qty">'+flats+'</span><span class="option-unit">'+unit+'*</span><span class="option-rate">' +item ['RATE'] + '</span>元*'+item.QTY+'个 ＝<span class="option-amt">' +item.AMT + '</span> 元</span></div>').appendTo("#optionprice");
				
			
				}else{

				$("<div style='margin-bottom: 5px;'></div>").html('<div class="cost" id="cost-option-'+item['OPTION_ID']+ '" style="display: block;"><span class="item">'+item.option_name+'</span><span class="price"><span class="option-qty">'+flats+'</span><span class="option-unit">'+unit+'*</span><span class="option-rate">' +item ['RATE'] + '</span>元*'+item.QTY+'个 ＝<span class="option-amt">' +item.AMT + '</span> 元</span></div>').appendTo("#tailmandy");
				
				mandy+=parseInt(item.AMT);
				}
				totalPrice +=days*item.RATE;
				//$("#total-amt").val(totalPrice);
		});
			//	calcChargeAmount();
				//var rateamt = parseInt($("#rate-amt").text());
				//var mandy = parseInt($("#mandy").text());
				//var charge = parseInt($("#charge").text());
				//$("#charge").text(charge);
				//$("#mandy").text(mandy);
				//$("#total-amt").text(rateamt+mandy+charge);
				//alert(totalPrice);
	});
	}
function openDialog(){
	//	alert(PRINTURL);
	//$.pdialog.close('contract');
	//$.pdialog.open(PRINTURL,'newdialog','打印上门取车合同',{max:true,mask:true});
	window.open(PRINTURL);
		//$.pdialog.open(PRINTURL, 'openDialog', 'testDialog');
}
</script>
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
<div id="seldialog">
<div class="pageContent"  layoutH="30" >
	<input type="button" onclick="ReadCard_onclick();" value="读取身份证信息" />



	<table width="98%" class="list">
		<thead>
			<tr>
				<th width="80">预定人名称</th>
				<th width="200">取车时间</th>
				<th width="200">还车时间</th>
				<th width="200">预定车型</th>
				<th width="100">均价</th>
				<th width="100">租期类型</th>
				<th width="100">超天价格</th>
				<th width="100">超小时价格</th>
				<!--<th width="100">超公里价格</th>-->
				<th width="100">费用总计</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?php echo ($vo["REAL_NAME"]); ?></td>
				<td><?php echo (substr($vo["PICKUP_DATE"],0,16)); ?></td>
				<td><?php echo (substr($vo["RETURN_DATE"],0,16)); ?></td>
				<td ><?php echo ($vo["CAR_MODEL_NAME"]); ?></td>
				<td ><?php echo ($average); ?>元</td>
				<td>自驾</td>
				<td><?php echo ($unirate["XDAY"]); ?>元</td>
				<td><?php echo ($unirate["XHOUR"]); ?>元</td>
				<!--<td></td>-->
				<td id="TOTAL_PRICE1"><?php echo ($vo["TOTAL_PRICE"]); ?></td>
			</tr>
		</tbody>
	</table>
	<table style="float:left;width:18%">
		<tr>
			<td >
			    <object classid='clsid:4B3CB088-9A00-4D24-87AA-F65C58531039' id='SynCardOcx1' codeBase='SynCardOcx.CAB#version=1,0,0,1' style='width:100px;height:150px' ></object>

			</td>
		</tr>
	</table>
	<form id="myform" action="__URL__/createagreement" method="post" >
		<input type="hidden" name="REAL_NAME" value="<?php echo ($vo["REAL_NAME"]); ?>" />
		<input type="hidden" name="CAR_MODEL_NAME" value="<?php echo ($vo["CAR_MODEL_NAME"]); ?>"/>
		<input type="hidden" name="XDAY" value="<?php echo ($vo["XDAY"]); ?>"/>
		<input type="hidden" name="XHOUR" value="<?php echo ($vo["XHOUR"]); ?>" />
		<input type="hidden" name="confirmation" value="<?php echo ($confirmation); ?>"/>
		<input type="hidden" name="location_code" value="<?php echo ($location_code); ?>"/>
		<input type="hidden" name="AVERAGE" value="<?php echo ($average); ?>"/>
		<input type="hidden" name="BRAND_CODE" value="<?php echo ($vo["BRAND_CODE"]); ?>"/>
		<input type="hidden" name="COMPANY_CODE" value="<?php echo ($vo["COMPANY_CODE"]); ?>"/>
		<input type="hidden" name="PICKUP_CITY_CODE" value="<?php echo ($vo["PICKUP_CITY_CODE"]); ?>"/>
		<input type="hidden" name="PICKUP_DISTRICT_CODE" value="<?php echo ($vo["PICKUP_DISTRICT_CODE"]); ?>"/>
		<input type="hidden" name="PICKUP_LOCATION_CODE" value="<?php echo ($vo["PICKUP_LOCATION_CODE"]); ?>"/>
		<input type="hidden" name="RETURN_CITY_CODE" value="<?php echo ($vo["RETURN_CITY_CODE"]); ?>"/>
		<input type="hidden" name="RETURN_DISTRICT_CODE" value="<?php echo ($vo["RETURN_DISTRICT_CODE"]); ?>"/>
		<input type="hidden" name="RETURN_LOCATION_CODE" value="<?php echo ($vo["RETURN_LOCATION_CODE"]); ?>"/>

		<input type="hidden" name="SOURCE_CODE" value="<?php echo ($vo["SOURCE_CODE"]); ?>"/>
		<input type="hidden" name="VENDOR_CODE" value="<?php echo ($vo["VENDOR_CODE"]); ?>"/>
		<input type="hidden" name="VENDOR_PASS" value="<?php echo (getObjInfo($vo["VENDOR_CODE"],'vendor','vendorCode','VENDOR_PASSWORD')); ?>"/>
		<table class="list" width="98%" style="margin-top:10px;height:233px">
			<tr>
				<td colspan="2" style="text-align:center">会员信息</td>
			</tr>
			<tr>
				<td  style="width: 400px;">
					<label class="label-left">会员类型:</label>
					<input type="text" name="MEMBER_TYPE_NAME" class="required textInput" readonly value="<?php echo (getMemberName($vo["MEMBER_TYPE_ID"])); ?>"/></td>
				<td>
					<label class="label-right">租车人手机:</label>
					<input type="text" name="work_phone" class="required phone textInput" value="<?php echo ($vo["HOME_PHONE"]); ?>"/>
				</td>
			</tr>	
			<tr>
				<td  style="width: 400px;">
					<label class="label-left">姓名:</label>
					<input type="text"   id="REAL_NAME"  class="required textInput" name="REAL_NAME" value="<?php echo ($vo["REAL_NAME"]); ?>"/>	</td>
				<td>
					<label class="label-right">紧急联系人:</label><input type="text" class="required textInput" name="contactperson" value=""/>
					紧急联系人手机:<input type="text" name="contactphone" class="required phone textInput" value=""/>
				</td>
			</tr>	
			<tr>
				<td  style="width: 400px;">
					<label class="label-left">性别:</label>
					<input type="text"  name="sex" id="sex" value="" class="required  textInput"/>
					出生日期:<input type="text"  name="age"  value="" class="required  textInput"/>
				</td>
				<td>
					<label class="label-right">驾驶证号码:</label>
					<input type="text" name="driver_code" class="required  textInput" value="<?php echo ($vo["IDENTITY_CODE"]); ?>"/>
				</td>
			</tr>	
			<tr>
				<td  style="width: 400px;">
					<label class="label-left">电子邮件:</label>
					<input type="text" name="email" value="<?php echo ($vo["EMAIL"]); ?>"  />
				</td>
				<td>
					<label class="label-right">驾驶证准驾车型:</label>
					<select name="vehicletype" >
						<option value="A1">A1</option>
						<option value="B1">B1</option>
						<option value="B2">B2</option>
						<option value="C1" selected>C1</option>
					</select>
				</td>
			</tr>	
			<tr>
				<td  style="width: 400px;">
					<label class="label-left">身份证号码:</label>
					<input type="text"  id="IDENTITY_CODE" name="IDENTITY_CODE" value="<?php echo ($vo["IDENTITY_CODE"]); ?>" class="required textInput"/>

				</td>
				<td>
					<label class="label-right">发证城市:</label>
					<input type="text" name="driverofcity" value=""/>

				</td>
			</tr>	
			<tr>
				<td  style="width: 400px;">
					<label class="label-left">身份证地址:</label>
					<input type="text"  name="address" size="50"  value="" class="required textInput"/>

				</td>
				<td>
					<label class="label-right">初次领证日期:</label>
					<input type="text"  name="taketime" value="" />

				</td>
			</tr>	
			<tr>
				<td  colspan="2" style="width: 400px;">
					<label class="label-left">现住址:</label>
					<input type="text" size="50" name="consummeraddr" value="<?php echo ($vo["CONSUMMER_ADDR"]); ?>" />

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
							</li>
						</ul>
						<input type="hidden" name="BASE_RATE_QTY" value="<?php echo ($vo["BASE_RATE_QTY"]); ?>"/>
						<br />
						<div style="display:block;margin-bottom:10px">
							<label style="width:15px">实际取车时间:</label>	
							<input type="text" readonly  name="PICKUP_DATE"     value="<?php echo ($datenow); ?>" id="from1"/>	
						</div>
						<div style="display:block;margin-bottom:10px">
							<label style="width:15px">实际还车时间:</label>
							<input type="text" readonly id="to1" name="RETURN_DATE"  value="<?php echo (substr($vo["RETURN_DATE"],0,16)); ?>" />

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
									<td><input class="readonly" name="master.dwz_devLookup.CAR_TAG" value="" readonly="readonly" type="text"/></td>
									<td>
										<input class="readonly" name="master.dwz_devLookup.CAR_MODEL" value=""  readonly="readonly" type="text"/>
									</td>
									<td>
										<input class="readonly" name="master.dwz_devLookup.CAR_MODEL_NAME" value="" size="40" readonly="readonly" type="text" />
										<input type="hidden" name="master.dwz_devLookup.CAR_MODEL_CODE" value=""  />
										<input type="hidden" name="master.dwz_devLookup.CAR_TYPE_CODE" value=""  />
									</td>
									<td>
										<input class="text" name="master.dwz_devLookup.CURRENT_KM" value="" readonly="readonly" type="text" />
									</td>
									<td>
										<input class="text" name="master.dwz_devLookup.CURRENT_OIL" value="" readonly="readonly" type="text" />
									</td>
								</tr>
								<tr>
									<td colspan="5" style="padding-left: 300px;">
										<a class="btnLook" target="dialog" ref="reser2" href="__APP__/Reservation/selectCar/modelcode/<?php echo ($vo['CAR_MODEL_CODE']); ?>/cartypecode/<?php echo ($vo['CAR_TYPE_CODE']); ?>" lookupGroup="master" lookupName="devLookup" width="840" height="380">选 车</a>	
										<span class="info">选 车</span>

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
						<div id="delta-i"></div>

						<h1 style="margin-bottom:20px">价格计算(租期<span id="delta-o" style="color: #fd6c02;"><?php echo ($vo["BASE_RATE_QTY"]); ?></span>天)</h1>
						<ul>
							<li align="right" class="cost">
							<label class="label-right">基本租金: </label><span class="option-amt" id="rate-amt"><?php echo ($vo["BASE_RATE_AMT"]); ?>元</span></li>
							<li class="cost"><span id="tailmandy"></span></li>
							<li align="right" class="cost"><label class="label-right">必须费用合计: </label><span id="mandy" ><?php echo ($vo["MANDATORY_CHARGES"]); ?></span>元</li>
							<!--<li> <?php echo (optionPrice($location_code,$confirmation,$vo['BASE_RATE_QTY'])); ?> </li>-->

							<li align="right" class="cost"><label class="label-right" id="feeoption">增值服务费用合计: </label><span  id="charge"><?php echo ($vo["OPTIONS_CHARGES"]); ?>元</span></li>
							<li id="optionprice">
							
							</li>

							<li id="tpl-cost-discount"></li>
							<li align="right" id="cost-total"><label class="label-right">总金额: </label><span id="total-amt"><?php echo ($vo['BASE_RATE_AMT']+$vo['MANDATORY_CHARGES']+$vo['OPTIONS_CHARGES']); ?></span> 元</li>
							<li align="right" id="real-total"><label class="label-right">实际金额: </label><input type="text" name="REALTOTAL" value="<?php echo ($vo['BASE_RATE_AMT']+$vo['MANDATORY_CHARGES']+$vo['OPTIONS_CHARGES']); ?>"> 元</li>
							<li>车辆预售权:<?php echo (getDeposit($location_code,$vo['CAR_MODEL_CODE'])); ?></li>
						</ul>
					</td>
					<td>	
						<h1>增值服务</h1>
						<div style="width:220px">
							<?php echo (getlocationopt($location_code,$confirmation)); ?>
						</div>

					</td>
					<!-- <td>
						<h1>折扣信息</h1>
						<div  style="width: 484px;">
							<?php if(is_array($discount)): $i = 0; $__LIST__ = $discount;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vs): ++$i;$mod = ($i % 2 )?><?php if($vs['discountStackable'] == 'N'): ?><label class="radio">
								<input type="radio" <?php if($vs["discountId"] == $vo['discountck']): ?>checked<?php endif; ?> class="discount discount-unstackable"  id="discount-<?php echo ($vs["discountId"]); ?>" name="discount" value="<?php echo ($vs["discountId"]); ?>" onclick="selectDiscount('<?php echo ($vs['discountName']); ?>','<?php echo ($vs['discountId']); ?>','unstackable',this);" />
								<?php echo ($vs["discountName"]); ?>
							</label>
							<?php else: ?>
							<label class="checkbox">
								<input type="checkbox" class="discount discount-stackable" id="discount-<?php echo ($vs["discountId"]); ?>" name="discount" value="<?php echo ($vs["discountId"]); ?>" onclick="selectDiscount('<?php echo ($vs['discountName']); ?>','<?php echo ($vs['discountId']); ?>','stackable',this);" />
								<?php echo ($vs["discountName"]); ?>
							</label><?php endif; ?>
							<input type="hidden" name="discountname" value="<?php echo ($vs["discountName"]); ?>"/><?php endforeach; endif; else: echo "" ;endif; ?>
						</div>	
					</td> -->	
				</tr>
				<tr>
					<td colspan="2">
						<?php if($vo['status'] == 'CONTRACT'): ?><span id="crtagreement" style="display:block">
							<?php else: ?>
							<input  type="submit" id="subbtn" value="生成合同" />
							<span id="crtagreement" style="display:none"><?php endif; ?><a href="__APP__/agreement/showcontract/id/<?php echo ($vo["agreementid"]); ?>" max="true" mask=true target="dialog" >打印合同</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="__APP__/agreement/showcontract/id/<?php echo ($vo["agreementid"]); ?>" target="dialog"  max="true" mask=true >查看合同列表</a>
							</td>
						</tr>	
					</tbody>
				</table>
			</form>
		</div>

</div>
<script>
	/**
	 * 选择优惠活动
	 */
	function selectDiscount(uniDiscName, uniDiscId, stackable,chk) {
		
		if(chk.checked){
		$.post("__URL__/ajax_curl",{'uniDiscId': uniDiscId,'rateCode':rate_code,'carTypeCode':carTypeCode,'carModelCode':carModelCode,'pickupLocationCode':pickupLocationCode,'pickupDate':pickupDate,'returnDate':returnDate}, function(data){
				
				// 排他折扣和非排他折扣不能同时使用
			//alert(data.discMessage.message);
				if (stackable == 'unstackable') {
					// 清空选择的非排他折扣
					//alert("aaa");
					$('input.discount-stackable').attr('checked', false);
					$('.cost-discount-stackable').remove();
					$('.cost-discount-unstackable').remove();
				} else {
					// 多个非排他折扣可以共存，选择非排他折扣时，需要清除选择的排他折扣
					$('input.discount-unstackable').attr('checked', false);
					$('.cost-discount-unstackable').remove();
				}
				
				
				$('#tpl-cost-discount').html('<li class="cost cost-discount cost-discount-'+stackable+'  cost-discount-'+uniDiscId+'  hide" ><div class="item">'+uniDiscName+'  <span class="gray">'+ data.discMessage.message+'  </span></div><div class="price"><span class="option-amt red">-'+data.discMessage.count.toFixed(0)+' </span> 元</div></li>').insertBefore("#cost-total");
				calcTotalAmount();
				
		},"json");
		}else{
		
		$('.cost-discount-' + $(this).val()).hide();
			calcTotalAmount();
		}
		
	}
	// 绑定排他折扣单选按钮的点击事件
	$('body').delegate('input.discount-unstackable', 'change', function() {
		selectDiscount($.trim($(this).parent().text()), $(this).val(), 'unstackable');
	});
	
	// 绑定非排他折扣复选按钮的点击事件
	$('body').delegate('input.discount-stackable', 'change', function() {
		if ($(this).is(':checked')) {
			selectDiscount($.trim($(this).parent().text()), $(this).val(), 'stackable');
		} else {
			// 当取消选择非排他折扣时，清除其价格信息
			//alert($(this).val());
			$('.cost-discount-' + $(this).val()).hide();
			calcTotalAmount();
		}
	});

</script>