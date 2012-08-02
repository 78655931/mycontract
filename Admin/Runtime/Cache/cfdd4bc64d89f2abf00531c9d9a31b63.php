<?php if (!defined('THINK_PATH')) exit();?><script type="text/javascript" charset="utf-8" src="__PUBLIC__/js/card.js"></script>
<script type="text/javascript" charset="utf-8">
    $.ajaxSettings.global=true;
    var totalPrice =0;
    $(document).ready(function() {
            var str;
            /**
            str = SynCardOcx1.FindReader();
            if(str>0){

            SynCardOcx1.SetSexType (1);
            SynCardOcx1.SetNationType(2);
            }
            **/
            DAYS ='<?php echo ($vo["BASE_RATE_QTY"]); ?>';
            optionprice(DAYS);


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
    var queryString = $.param(formData);
    return true;
}
// post-submit callback
function showResponse(responseText, statusText, xhr, $form) {
    if (responseText == 1) {
        PRINTURL = '__APP__'+'/Agreement/gotoPrint/id/'+'<?php echo (base64_encode($vo["agreementid"])); ?>';
        alertMsg.correct('合同生成成功!');
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
    var REAL_NAME = $("#REAL_NAME").val();
    var IDENTITY_CODE = $("#IDENTITY_CODE").val();
    if(nRet==0)
    {	
        $("#sex").val(SynCardOcx1.Sex);
        document.all['address'].value = SynCardOcx1.Address;
        document.all['age'].value = SynCardOcx1.Born;
        if(SynCardOcx1.NameA.trim() != REAL_NAME)
        {
            alert("姓名信息不符");
        }
        if(SynCardOcx1.CardNo.trim()!=IDENTITY_CODE)
        {
            alert("身份证号码不符");
        }
    }
} 

function optionprice(days){
    var opp =0;
    var perunit;
    var unit;
    var flats;
    var charge=0;
    var mandy =0;
    var disable;
    var o;
    var reservationid ='<?php echo ($_GET['confirmation']); ?>';
    $.getJSON('__URL__/optionprice',{'reservationid':reservationid},function(data){
            $.each(data['res'],function(i,item){
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
                if(item.QTY==null){
                    item.QTY=1;
                    item.AMT=parseInt(item.RATE)*item.QTY*flats;

                }
                // alert(item.OPTION_NAME);
                if(item.MANDATORY=='Y'){

                    disable= "disabled"; 

                    $("<div></div>").html('<div class="cost" id="cost-option-'+item['OPTION_ID']+ '" style="display: block;"><span class="item">'+item.OPTION_NAME+'</span><span class="price"><span class="option-qty">'+flats+'</span><span class="option-unit">'+unit+'*</span><span class="option-rate">' +item ['RATE'] + '</span>元*'+item.QTY+'个 ＝<span class="option-amt">' +item.AMT + '</span> 元</span></div>').appendTo("#tailmandy");

                }else{
                    disable="";
                    $("<div></div>").html('<div class="cost" id="cost-option-'+item['OPTION_ID']+ '" style="display:none;"><span class="item">'+item.OPTION_NAME+'</span><span class="price"><span class="option-qty">'+flats+'</span><span class="option-unit">'+unit+'*</span><span class="option-rate">' +item ['RATE'] + '</span>元*'+item.QTY+'个 ＝<span class="option-amt">' +item.AMT + '</span> 元</span></div>').appendTo("#optionprice");
                }
                $("#Soption").append('<label class="checkbox inline"><input type="checkbox" name="option[]" '+disable+' class="checkbox-option" checked="true"  value="' +item['OPTION_ID'] + '" />' + item ["OPTION_NAME"] + '</label>');
                });
            $.each(data['uni'],function(i,item){
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
                    if(item.QTY==null){
                        item.QTY=1;
                        item.AMT=parseInt(item.RATE)*item.QTY*flats;

                    }

                    $("<div></div>").html('<div class="cost" id="cost-option-'+item['OPTION_ID']+ '" style="display:none;"><span class="item">'+item.OPTION_NAME+'</span><span class="price"><span class="option-qty">'+flats+'</span><span class="option-unit">'+unit+'*</span><span class="option-rate">' +item ['RATE'] + '</span>元*'+item.QTY+'个 ＝<span class="option-amt">' +item.AMT + '</span> 元</span></div>').appendTo("#optionprice");
                    $("#Soption").append('<label class="checkbox inline"><input type="checkbox" name="option[]"  class="checkbox-option" value="' +item['OPTION_ID'] + '" />'+item["OPTION_NAME"]+'</label> ');
            });
            $('.checkbox-option').click(function() {
                    var costElem = $('#cost-option-' + $(this).val());
                    $(this).is(':checked') ? showCost(costElem) : hideCost(costElem);
                    });
function showCost(costElem) {
		
		costElem.addClass('cost-highlight').fadeIn('fast', function() {
			$(this).removeClass('cost-highlight');
			//$("#feeoption").show();
			calcChargeAmount();

			//calcMandyAmount();
			//console.log(charge);
			calcTotalAmount();
		});
	}
	
	function hideCost(costElem, removeCost) {
		costElem.addClass('cost-highlight').fadeOut('fast', function() {
			$(this).removeClass('cost-highlight');
		//	$("#feeoption").hide();
			calcTotalAmount();

			//calcMandyAmount();
			calcChargeAmount();
			removeCost && $(this).remove();
		});
	}
	function calcMandyAmount(){

			var mandy =0;
			$('#tailsmandy .cost .option-amt:visible').each(function() {
			
			var otail = $(this).html();

			if(otail){

				var ot = parseFloat(otail);
				//console.log(option2);
				mandy+=ot;
			}
			
	});
	
        $('#mandy').html(mandy.toFixed(2));
			//$('#charge').text(charge);
	}
	function calcChargeAmount(){

			var charge =0;
			$('#optionprice .cost .option-amt:visible').each(function() {
			
			var option1 = $(this).html();

			if(option1){

				var option2 = parseFloat(option1);
				//console.log(option2);
				charge+=option2;
			}
			
	});

        $('#charge').html(charge.toFixed(2));
			//$('#charge').text(charge);
	}

	/**
	 * 重新计算订单总价
	 */
    function calcTotalAmount() {
    	var totalAmt = 0;
        $('.cost .option-amt:visible').each(function() {
			
			var optionAmt = $(this).html();
			if(optionAmt){
				
				var optionAmt = parseFloat(optionAmt);
				totalAmt += optionAmt;
			}
        	
        });
	//alert(totalAmt);
        $('#total-amt').html(totalAmt.toFixed(2));
      //  $('#rate-amt').html(totalAmt.toFixed(2));
    }


    });
}
function openDialog(){
    window.open(PRINTURL);
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
                <!--
                <object classid='clsid:4B3CB088-9A00-4D24-87AA-F65C58531039' id='SynCardOcx1' codeBase='SynCardOcx.CAB#version=1,0,0,1' style='width:100px;height:150px' ></object>
                -->

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
		<table class="list" width="80%" style="margin-top:10px;height:233px;float:right">
			<tr>
				<td colspan="2" style="text-align:center">会员信息</td>
			</tr>
			<tr>
				<td  style="width: 50%">
					<label class="label-left">会员类型:</label>
					<input type="text" name="MEMBER_TYPE_NAME" class="required textInput" readonly value="<?php echo (getMemberName($vo["MEMBER_TYPE_ID"])); ?>"/></td>
				<td>
					<label class="label-right">租车人手机:</label>
					<input type="text" name="work_phone" class="required phone textInput" value="<?php echo ($vo["HOME_PHONE"]); ?>"/>
				</td>
			</tr>	
			<tr>
				<td  >
					<label class="label-left">姓名:</label>
					<input type="text"   id="REAL_NAME"  class="required textInput" name="REAL_NAME" value="<?php echo ($vo["REAL_NAME"]); ?>"/>	</td>
				<td>
					<label class="label-right">紧急联系人:</label><input type="text" class="required textInput" name="contactperson" value=""/>
					紧急联系人手机:<input type="text" name="contactphone" class="required phone textInput" value=""/>
				</td>
			</tr>	
			<tr>
				<td  >
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
				<td  >
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
				<td  >
					<label class="label-left">身份证号码:</label>
					<input type="text"  id="IDENTITY_CODE" name="IDENTITY_CODE" value="<?php echo ($vo["IDENTITY_CODE"]); ?>" class="required textInput"/>

				</td>
				<td>
					<label class="label-right">发证城市:</label>
					<input type="text" name="driverofcity" value=""/>

				</td>
			</tr>	
			<tr>
				<td  >
					<label class="label-left">身份证地址:</label>
					<input type="text"  name="address" size="50"  value="" class="required textInput"/>

				</td>
				<td>
					<label class="label-right">初次领证日期:</label>
					<input type="text"  name="taketime" value="" />

				</td>
			</tr>	
			<tr>
				<td  colspan="2" >
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
							<!--<input type="text" readonly  name="PICKUP_DATE"     value="<?php echo ($datenow); ?>" id="from1"/>	-->

							<input type="text" readonly  name="PICKUP_DATE"     value="<?php echo (substr($vo["PICKUP_DATE"],0,16)); ?>" id="from1"/>	
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
						<input type="hidden" name="MANDATORY_CHARGES" value="<?php echo ($vo["MANDATORY_CHARGES"]); ?>"/>
						<div id="delta-i"></div>

						<h1 style="margin-bottom:20px">价格计算(租期<span id="delta-o" style="color: #fd6c02;"><?php echo ($vo["BASE_RATE_QTY"]); ?></span>天)</h1>
						<ul>
							<li align="right" class="cost">
							<label class="label-right">基本租金: </label><span class="option-amt" id="rate-amt"><?php echo ($vo["BASE_RATE_AMT"]); ?>元</span></li>
							<li class="cost"><span id="tailmandy"></span></li>
							<li align="right" class="cost"><label class="label-right">必须费用合计: </label><span id="mandy" ><?php echo ($vo["MANDATORY_CHARGES"]); ?></span>元</li>

							<li align="right" class="cost"><label class="label-right" id="feeoption">增值服务费用合计: </label><span  id="charge"><?php echo ($vo["OPTIONS_CHARGES"]); ?></span>元</li>
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
						<div style="width:220px" id="Soption">
						</div>

					</td>
				</tr>
				<tr>
					<td colspan="2">
							<input  type="submit" id="subbtn" value="生成合同" />
                            <span id="crtagreement" style="display:none"></span>
							</td>
						</tr>	
					</tbody>
				</table>
			</form>
		</div>

</div>
<script type="text/javascript" src="__PUBLIC__/js/booking.js"></script>