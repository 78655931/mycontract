<?php if (!defined('THINK_PATH')) exit();?><form id="pagerForm" action="__URL__" method="post">
	<input type="hidden" name="pageNum" value="1"/>
	<input type="hidden" name="_order" value="<?php echo ($_REQUEST["_order"]); ?>"/>
	<input type="hidden" name="_sort" value="<?php echo ($_REQUEST["_sort"]); ?>"/>
</form>

<div class="pageHeader">
	<!--form rel="pagerForm" method="post" action="__URL__/selectCar/" onsubmit="return dwzSearch(this, 'dialog');">
	<div class="searchBar">
		<ul class="searchContent">
			<li>
			<label>车牌号:</label>
			<input class="textInput" name="CAR_TAG" value="" type="text">
			</li>	  
		</ul>
		<div class="subBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">查询</button></div></div></li>
			</ul>
		</div>
	</div>
	</form-->
</div>
<div class="pageContent">

	<table class="table" layoutH="118" targettype="dialog" width="100%">
		<thead>
			<tr>
				<th orderfield="CAR_TAG">车牌号码</th>
				<th orderfield="CAR_MODEL">厂商</th>
				<th orderfield="CAR_MODEL_NAME">车型</th>
				<th orderfield="CAR_TYPE_NAME">车辆类型</th>
				<th orderfield="CURRENT_OIL">油量</th>
				<th orderfield="CAR_MODEL_SIZE">排量</th>
				<th orderfield="COLOR">颜色</th>
				<th orderfield="ATMT">档型</th>
				<th orderfield="CURRENT_KM">公路数</th>
				<th width="80">选车</th>
			</tr>
		</thead>			

		<tbody>
			<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): ++$i;$mod = ($i % 2 )?><tr>
				<td><?php echo ($vo["CAR_TAG"]); ?></td>
				<td><?php echo ($vo["CAR_MODEL"]); ?></td>
				<td><?php echo ($vo["CAR_MODEL_NAME"]); ?></td>
				<td><?php echo ($vo["CAR_TYPE_NAME"]); ?></td>
				<td><?php echo (getOilpercent($vo["CURRENT_OIL"])); ?></td>
				<td><?php echo (getObjInfo($vo["CAR_MODEL_CODE"],'car_model','carModelCode','CAR_MODEL_SIZE')); ?></td>
				<td><?php echo ($vo["COLOR"]); ?></td>
				<td><?php echo ($vo["ATMT"]); ?></td>
				<td><?php echo ($vo["CURRENT_KM"]); ?>  </td>
				<td>
					<a class="select" ref="reser2" href="javascript:$.bringBack({ CAR_TAG:'<?php echo ($vo["CAR_TAG"]); ?>', CAR_MODEL:'<?php echo ($vo["CAR_MODEL"]); ?>', CAR_MODEL_NAME:'<?php echo ($vo["CAR_MODEL_NAME"]); ?>',CURRENT_KM:'<?php echo ($vo["CURRENT_KM"]); ?>',CURRENT_OIL:'<?php echo (getOilpercent($vo["CURRENT_OIL"])); ?>',CAR_MODEL_CODE:'<?php echo ($vo["CAR_MODEL_CODE"]); ?>',CAR_TYPE_CODE:'<?php echo ($vo["CAR_TYPE_CODE"]); ?>'},'dialog','seldialog')" title="查找带回">选择</a>
				</td>
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
		</tbody>
	</table>

	<div class="panelBar">
		<div class="pages">
			<span>共<?php echo ($totalCount); ?>条</span>
		</div>
		<div class="pagination" targetType="navTab" totalCount="<?php echo ($totalCount); ?>" numPerPage="<?php echo ($numPerPage); ?>" pageNumShown="10" currentPage="<?php echo ($currentPage); ?>"></div>
	</div>
</div>