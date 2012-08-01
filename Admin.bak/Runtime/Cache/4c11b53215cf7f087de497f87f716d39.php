<?php if (!defined('THINK_PATH')) exit();?><div class="pageContent">
	<form method="post" action="__URL__/returnedCar/" id="myform" class="pageForm" >
		<div class="pageFormContent" layoutH="58">
			<p>
				<label>实际还车时间：</label>
				<input type="text" name="REAL_RETURN_DATE" id="return_date" value="<?php echo ($vo["RETURN_DATE"]); ?>" size="20" class="required" />
			</p>
			<p>
				<label>公里数：</label>
				<input type="text" name="return_km" value="<?php echo ($vo["CURRENT_KM"]); ?>" size="20" class="required" />
			</p>
			<p>
			<label>还车油量：</label>
				<select id="return_oil" name="return_oil">
					<option value="0">油量为空</option>
					<option value="1">1/4</option>
					<option value="2">1/2</option>
					<option value="3">3/4</option>
					<option value="4">满箱</option>
				</select>
			</p>
		</div>
		<div class="formBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">提交</button></div></div></li>
				<li><div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div></li>
			</ul>
		</div>
	</form>
	
</div>
<script type="text/javascript">
	$.ajaxSettings.global=false;	
	$(function(){
		var options = {
			beforeSubmit : showRequest, // pre-submit callback
			success : showResponse // post-submit callback
		};
		$('#myform').submit(function() {
			// 提交表单
			$(this).ajaxSubmit(options);
			// 为了防止普通浏览器进行表单提交和产生页面导航（防止页面刷新？）返回false
			return false;
		});

		function showRequest(formData, jqForm, options) {

			var queryString = $.param(formData);

			//alert('About to submit: \n\n' + queryString);
			return true;
		}
		function showResponse(responseText, statusText, xhr, $form) {
			if (responseText == 'RETURN') {
				$("#statusCar").text("已还车").css({"background":"#009900"});
				$("#returncar").hide();
			} else {
				alert(responseText);
			}

			$.pdialog.closeCurrent();
		}


		$("<input type='hidden' name='id'>").val('<?php echo ($_GET['id']); ?>').appendTo('.pageForm');
		$("<input type='hidden' name='agreement_id'>").val('<?php echo ($_GET['agreement_id']); ?>').appendTo('.pageForm');
		$( "#return_date" ).datetimepicker({
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
			minuteGrid: 10	
		});
	})
</script>