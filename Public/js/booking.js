/**
 * Booking页面脚本
 * @author qiujian <greatghoul@gmail.com>
 */
$(function() {
	
	// 勾选和取消增值服务时，重新计算订单总价
	$('.checkbox-option').click(function() {
		console.log("Click");
        //var costElem = $('#cost-option-' + $(this).val());
        //$(this).is(':checked') ? showCost(costElem) : hideCost(costElem);
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

	
	
	/**
	 * 选择优惠活动
	 */
	function selectDiscount(uniDiscName, uniDiscId, stackable) {
		
		$.post("http://127.0.0.1/ui/ui/Admin/index.php/Reservation/ajax_curl",{'uniDiscId': uniDiscId,'rateCode':rate_code,'carTypeCode':carTypeCode,'carModelCode':carModelCode,'pickupLocationCode':pickupLocationCode,'pickupDate':pickupDate,'returnDate':returnDate}, function(data){
				
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
	
	
	
	
	
});
