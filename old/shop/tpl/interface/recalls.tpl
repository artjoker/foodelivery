<div class="clear"></div><br>
<form action="<?=$url?>b=recalls" method="post" enctype="multipart/form-data" class="pull-right">
	<table class="table-condensed">
		<tr>
			<td class="text-right"><?=$_lang["shop_from"]?></td>
			<td>
				<div id="datetimepicker" class="input-append date">
				  <input data-format="yyyy-MM-dd" type="text" name="date_from" id="datetimepicker" class="form-control add-on" style="width: 100px;" value="<?=$_REQUEST['date_from']?>"></input>
				</div>
			</td>
			<td><?=$_lang["shop_to"]?></td>
			<td>
				<div id="datetimepicker2" class="input-append date">
				  <input data-format="yyyy-MM-dd" type="text" name="date_to" id="datetimepicker2" class="form-control add-on" style="width: 100px;" value="<?=$_REQUEST['date_to']?>"></input>
				</div>
			</td>
		</tr>
		<tr>
			<td><?=$_lang["shop_status"]?></td>
			<td colspan="3">
				<select name="status" class="nc form-control">
					<option value="0" <?php if($_REQUEST['status'] == 0) echo 'selected';?> ><?=$_lang["shop_not_pub"]?></option>
					<option value="1" <?php if($_REQUEST['status'] == 1) echo 'selected';?> ><?=$_lang["shop_pub"]?></option>
					<option value="-1" <?php if($_REQUEST['status'] == -1) echo 'selected';?> ><?=$_lang["shop_all"]?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="4" class="text-right">
				<button name="b" value="recalls" class="btn btn-primary"><span class="glyphicon glyphicon-filter"></span> <?=$_lang["shop_filter"]?></button>
			</td>
		</tr>
	</table>
</form>
<table class="table table-condensed table-striped table-bordered table-hover">
	<thead>
		<tr>
			<th width="50px">ID</th>
			<th><?=$_lang["shop_product"];?></th>
			<th><?=$_lang["shop_recall_name"];?></th>
			<th width="110px"><?=$_lang["shop_recall_email"];?></th>
			<th width="120px"><?=$_lang["shop_recall_date"];?></th>
			<th width="120px"><?=$_lang["shop_recall_moderated"];?></th>
			<th width="260px"></th>
		</tr>
	</thead>
	<tbody>
		<? 
			if ($modx->db->getRecordCount($recalls) == 0):
		?>
			<tr>
				<th colspan="7" class="text-center"><h4><?=$_lang["shop_on_request"];?> <i><u><?=$_GET['s']?></u></i> <?=$_lang["shop_not_found"];?></h4></th>
			</tr>
		<?
			else: 
			while ($recall = $modx->db->getRow($recalls)): 
			$i++;
		?>
			<tr>				
				<td><?=$recall['recall_id']?></td>
				<td><a target="_blank" href="<?=$modx->makeUrl($modx->config['shop_page_item']).$recall['pruduct_url']?>"><?=$recall['pruduct_name']?></a></td>
				<td><?=$recall['recall_name']?></td>
				<td><?=$recall['recall_email']?></td>
				<td><?=$recall['recall_pub_date']?></td>
				<td>
					<b>
						<? if ($recall['recall_moderated'] == 0): ?><span class="label label-danger"><?=$_lang["shop_not_pub"]?></span><? endif ?>
						<? if ($recall['recall_moderated'] == 1): ?><span class="label label-success"><?=$_lang["shop_pub"]?></span><? endif ?>
					</b>
				</td>
				<td>
					<a href="<?=$url?>b=recalls&c=edit&i=<?=$recall['recall_id']?>" class="btn btn-mini btn-primary"><span class="glyphicon glyphicon-edit"></span> <?=$_lang["shop_edit"]?> </a>
					<a href="<?=$url?>b=recalls&c=delete&i=<?=$recall['recall_id']?>" class="btn btn-mini btn-danger"><span class="glyphicon glyphicon-remove-sign"></span> <?=$_lang["shop_delete"]?> </a>
				</td>
			</tr>
		<? endwhile;endif; ?>		
	</tbody>
</table>
<ul class="pagination"><?=$pagin;?></ul>