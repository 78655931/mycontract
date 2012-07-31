/**
 * Booking页面脚本
 * @author qiujian <greatghoul@gmail.com>
 */
$(function() {
	
	function showCost(costElem) {
		costElem.addClass('cost-highlight').fadeIn('fast', function() {
			$(this).removeClass('cost-highlight');
			calcTotalAmount();
		});
	}
	
	function hideCost(costElem, removeCost) {
		costElem.addClass('cost-highlight').fadeOut('fast', function() {
			$(this).removeClass('cost-highlight');
			calcTotalAmount();
			removeCost && $(this).remove();
		});
	}
	
	/**
	 * 重新计算订单总价
	 */
    function calcTotalAmount() {
    	var totalAmt = 0;
        $('.cost .option-amt:visible').each(function() {
        	var optionAmt = parseFloat($(this).html());
			
        	totalAmt += optionAmt;
        });
        $('#total-amt').html(totalAmt.toFixed(2));
    }

	// 勾选和取消增值服务时，重新计算订单总价
	$('.checkbox-option').click(function() {
		//alert($(this).val());
        var costElem = $('#cost-option-' + $(this).val());
        $(this).is(':checked') ? showCost(costElem) : hideCost(costElem);
	});
	
	
});
