$(function(){
    var options;
    var a;
    options = { 
        serviceUrl: '__URL__/suggest'//获取数据的后台页面
    };
    a = $('#REAL_NAME').autocomplete(options);
    var a = {
        serviceUrl: '__URL__/suggest',//获取数据的后台页面
        delimiter: /(,|;)\s*/,
        onSelect:  function(value, data){ 
        },//选中之后的回调函数
        deferRequestBy: 0, //单位微秒
        params: { country: 'Yes' },//参数
        noCache: false //是否启用缓存 默认是开启缓存的
    };
    $("#CAR_MODEL_CODE").click(function(){
        var stat = $("#stat").val();
        var iscity = $("#iscity").val();
        var ratecode = '';
        var perunit = '';
        var flag='';
        var CAR_MODEL_CODE = $("#CAR_MODEL_CODE").val();
        var BASE_RATE_QTY = $("#BASE_RATE_QTY").val();
        var PICKUP_DATE = $("#PICKUP_DATE").val();
        var  MANDATORY = 0;
        var DRIVER_CHARGES =0;
        var TOTAL_PRICE =0,RATE=0;
        var OPTIONCHARGES =0;
        var MANDY = 0;
        if(stat=='perday'&&iscity=='1'){
            ratecode = "DSR";
        }else if(stat=='flight'&&iscity=='1'){
            ratecode = "DSJ";
        }
        $.getJSON('__URL__/djOption',{'CAR_MODEL_CODE':CAR_MODEL_CODE,'ratecode':ratecode,'PICKUP_DATE':PICKUP_DATE},function(data){
            $("#option").html('');
            $("#optionfee").html('');
            $.each(data,function(i,item){
                if(item.FLAG=='S'){
                    flag= "元";   
                }
                if(item.MANDATORY=='Y'){
                    MANDATORY+=item.MANDATORY;
                }
                switch(item.PER_UNIT){
                    case '1':
                        perunit = '/次';
                        break;
                    case 'D':
                        perunit = "/天";
                        break;
                    case 'H':
                        perunit = "/小时";
                        break;
                    case 'W':
                        perunit = "/每周";
                        break;
                    case 'M':
                        perunit = "/周末";
                        break;
                    case 'B':
                        perunit = "/瓶";
                        break;
                    case 'R':
                        perunit = "/人";
                        break;
                }
				if(item.OPTION_NAME.indexOf('司机')>0){
                    //$("#option").html('<tr><td>'+item.OPTION_NAME+'</td><td></td></tr>');
                    if(i%2==0)
					{
						$('<tr></tr>').html('<td><input type="radio" value="'+BASE_RATE_QTY*item.RATE+'" onclick="changeFee(this.value,0);" name="optionname" checked/>'+item.OPTION_NAME+'</td><td>'+item.RATE+flag+perunit+'</td><td>='+BASE_RATE_QTY*item.RATE+flag+'</td>').appendTo('#option');
						$("#DRIVER_VALUE").val(BASE_RATE_QTY*item.RATE);
					}else
					{
						 $('<tr></tr>').html('<td><input type="radio" value="'+BASE_RATE_QTY*item.RATE+'" onclick="changeFee(this.value,0); "name="optionname"/>'+item.OPTION_NAME+'</td><td>'+item.RATE+flag+perunit+'</td><td>='+BASE_RATE_QTY*item.RATE+flag+'</td>').appendTo('#option');
					}
					
                   // DRIVER_CHARGES+= BASE_RATE_QTY*item.RATE;
                    //alert(DRIVER_CHARGES);
                }else{
                    var checkValue = "";
				    if(item.MANDATORY=='Y'){
                         checkValue ="CHECKED";
                         MANDY +=BASE_RATE_QTY*item.RATE;
                         $('<tr></tr>').html('<td><input DISABLED id="'+i+'" type="checkbox"  value="'+BASE_RATE_QTY*item.RATE+'" onclick="changeFee(this.id,1);" name="optionname" CHECKED="'+checkValue+'" />'+item.OPTION_NAME+'</td><td>'+item.RATE+flag+perunit+'</td><td>='+BASE_RATE_QTY*item.RATE+flag+'</td>').appendTo('#option');

						 //$("#OPTION_VALUE").val(MANDY);
                    }else{
                        OPTIONCHARGES += BASE_RATE_QTY*item.RATE;
                        $('<tr></tr>').html('<td><input  id="'+i+'" type="checkbox"  value="'+BASE_RATE_QTY*item.RATE+'" onclick="changeFee(this.id,3);" name="optionname" CHECKED="'+checkValue+'" />'+item.OPTION_NAME+'</td><td>'+item.RATE+flag+perunit+'</td><td>='+BASE_RATE_QTY*item.RATE+flag+'</td>').appendTo('#optionfee');
                        
                    }
                                    }
				
            });
            $.getJSON('__URL__/djRate',{'CAR_MODEL_CODE':CAR_MODEL_CODE,'ratecode':ratecode,'PICKUP_DATE':PICKUP_DATE},function(data){
                $.each(data,function(i,item){
                    $("#rate").html(''+item.RATE+flag+'<input type="hidden" id="BASE_RATE_AMT" name="BASE_RATE_AMT" value="'+item.RATE+'">');
                    RATE=parseInt(item.RATE);
                    $("#OPTION_VALUE").val(MANDY);
                    $("#OPTIONCHARGES").html('<input type="hidden" value="'+OPTIONCHARGES+'" class="OPTIONCHARGES"/>'+OPTIONCHARGES+flag+'');
                    MANDATORY = parseInt($("#DRIVER_VALUE").val())+MANDY;
                    $("#MANDATORY").html(MANDATORY);
                    $("#MANDATORY").html('<input type="hidden" value="'+MANDATORY+'" name="MANDATORY" class="MANDYTORY">'+MANDATORY+flag+'');
                    TOTAL_PRICE = OPTIONCHARGES+MANDATORY+RATE;
                    $("#TOTAL_PRICE").html('<input type="hidden" value="'+TOTAL_PRICE+'" name="TOTAL_PRICE"  class="TOTAL_PRICE">'+TOTAL_PRICE+flag+'');
                });  
            });

            				 

        });
       
            }); 
});



