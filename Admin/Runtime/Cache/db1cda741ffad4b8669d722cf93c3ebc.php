<?php if (!defined('THINK_PATH')) exit();?><form id="pagerForm" action="__URL__" method="post">
	<input type="hidden" name="pageNum" value="1"/>
	<input type="hidden" name="_order" value="<?php echo ($_REQUEST["_order"]); ?>"/>
	<input type="hidden" name="_sort" value="<?php echo ($_REQUEST["_sort"]); ?>"/>
</form>

<div class="pageContent">


<script type="text/javascript">
	$(function(){
	var dates = $( "#from, #to" ).datepicker({
			defaultdate: "+1w",
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

			onSelect: function( selectedDate ) {

				

			}
		});
	})
</script>
<div class="pageHeader">
	<form action="__URL__" method="post" onsubmit="return navTabSearch(this)" >
		<input type="hidden" name="pageNum" value="1" />
		<table>
			<tr>
				<td>身份证号</td>
				<td><input type="text" name="IDENTITY_CODE" value="<?php echo ($IDENTITY_CODE); ?>" /></td>
				<td>客户电话</td>
				<td><input type="text"  name="WORK_PHONE" value="<?php echo ($WORK_PHONE); ?>"/></td>
				<td>预定单编号</td>
				<td><input type="text" name="CONFIRMATION" value="<?php echo ($CONFIRMATION); ?>"/></td>
			</tr>
				<tr>
					<td>取车时间</td>
					<td><input type="text" name="PICKUP_DATE" value="<?php echo ($PICKUP_DATE); ?>" id="from"/></td>
					<td>还车时间</td>
					<td><input  id="to" type="text" name="RETURN_DATE" value="<?php echo ($RETURN_DATE); ?>"/> 
</td>
					<td>客户姓名</td>
					<td><input type="text" title="名称查询" name="REAL_NAME" value="<?php echo ($REAL_NAME); ?>" class="medium" > <input type="submit" value="查询" />
</td>
				</tr>
		</table>
		<!--<div class="searchBar">
			<div class="fLeft">
				<ul>
					<li>身份证号: <input type="text" name="IDENTITY_CODE" value="<?php echo ($IDENTITY_CODE); ?>" /> 客户电话: <input type="text"  name="WORK_PHONE" value="<?php echo ($WORK_PHONE); ?>"/>预定单编号: <input type="text" name="CONFIRMATION" value="<?php echo ($CONFIRMATION); ?>"/></li>
					<li>取车时间: <input type="text" name="PICKUP_DATE" value="<?php echo ($PICKUP_DATE); ?>" id="from"/> 还车时间: <input  id="to" type="text" name="RETURN_DATE" value="<?php echo ($RETURN_DATE); ?>"/> 
					客户姓名:<input type="text" title="名称查询" name="REAL_NAME" value="<?php echo ($REAL_NAME); ?>" class="medium" > <input type="submit" value="查询" />

</li>
				</ul>
								
			</div>

		</div>-->
	</form>
</div>

<table layoutH="100%" class="table" width="1100">
	<thead>
		<tr>
			<th width="12%">预订单编号</th>
			<th width="6%">客户姓名</th>
			<th width="12%">身份证号</th>
			<th width="8%">客户电话</th>
			<th width="10%">取车时间</th>
			<th width="10%">还车时间</th>
			<th width="25%">车型</th>
			<th width="5%">状态</th>
			<th width="8%"></th>
		</tr>
	</thead>

	<tbody>
		<?php if(is_array($lists)): $i = 0; $__LIST__ = $lists;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><tr >
			<td ><?php echo ($vo['confirmation']); ?></td>
			<td ><?php echo ($vo['real_name']); ?></td>
			<td ><?php echo ($vo['identity_code']); ?></td>
			<td ><?php echo ($vo['home_phone']); ?></td>
			<td ><?php echo ($vo['pickup_date']); ?></td>
			<td ><?php echo ($vo['return_date']); ?></td>
			<td ><?php echo ($vo['CAR_MODEL_NAME']); ?></td>
			<?php echo (convert_stat($vo['status'])); ?>
			<td > <ul class="toolBar"><li><a width="15%" href="__URL__/edit/id/<?php echo ($vo['reservation_id']); ?>/confirmation/<?php echo ($vo['confirmation']); ?>/navTabId/__MODULE__" target="dialog" max="true" maxable="false" minable="false" resizable="false"  rel="reser2" fresh="true"><span>预订单生成合同</span></a></li></ul>

			</td>
		</tr><?php endforeach; endif; else: echo "" ;endif; ?>

		</tbody>
</table>

</div>

<div class="panelBar">
		<div class="pages">
			<span>共<?php echo ($totalCount); ?>条</span>
		</div>
		<div class="pagination" targetType="navTab" totalCount="<?php echo ($totalCount); ?>" numPerPage="<?php echo ($numPerPage); ?>" pageNumShown="10" currentPage="<?php echo ($currentPage); ?>"></div>
	</div>

<!--table layoutH="100%" class="table">
	<thead>
		<tr>
			<th width="200">预订单编号</th>
			<th width="100">客户姓名</th>
			<th width="150">客户电话</th>
			<th width="150">上车时间</th>
			<th width="200">车型</th>
			<th width="100">出租类型</th>
			<th width="50">状态</th>
			<th width="150"></th>
		</tr>
	</thead>

	<tbody>
		<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><tr>
		<td><?php echo ($vo["reservationID"]); ?></td>	
		<td><?php echo ($vo["reservationBy"]); ?></td>	
		<td><?php echo ($vo["phonenumber"]); ?></td>	
		<td><?php echo ($vo["upTime"]); ?></td>	
		<td><?php echo ($vo["driverCarType"]); ?></td>	
		<td><?php echo ($vo["driveType"]); ?></td>	
		<td><?php echo ($vo["status"]); ?></td>	
		<td><?php if($vo['status'] == 'contract'): ?><a href="__URL__/gotoPrint/id/<?php echo ($vo['reservationID']); ?>" target="blank">打印合同</a>&nbsp;&nbsp;<a href="__URL__/djshow/id/<?php echo ($vo['id']); ?>" target="dialog" width="1200" height="600">查看合同</a><?php else: ?><a href="__URL__/djedit/id/<?php echo ($vo['id']); ?>" target="navTab">建立合同</a><?php endif; ?></td>	
		</tr><?php endforeach; endif; else: echo "" ;endif; ?>	
		</tbody>
</table-->