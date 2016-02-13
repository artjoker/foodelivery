<form action="index.php" method="get" role="form" class="pull-right form-inline">
	<? foreach ($_GET as $k => $v): if ($k != "s"): ?>
		<input type="hidden" name="<?=$k?>" value="<?=$v?>" />
	<? endif; endforeach ?>
		<div class="form-group">
			<input type="text" name="s" class="form-control" placeholder="<?=$_lang["shop_search_by"];?>" value="<?=$_GET['s']?>" />
		</div>
		<button class="btn btn-primary"><span class="glyphicon glyphicon-search"></span></button>
</form>
<a href="<?=$url?>b=items&c=publish&category=<?=$_GET['c']?>" class="btn btn-success">
	<span class="glyphicon glyphicon-plus-sign"></span> 
	<b><?=$_lang["shop_new_product"];?></b>
</a>
<div class="clear"></div><br>
<table class="table table-condensed table-striped table-bordered table-hover">
	<thead>
		<tr>
			<th width="50px">ID</th>
			<th><?=$_lang["shop_name"];?></th>
			<th width="150px"><?=$_lang["shop_price"];?></th>
			<th width="70px"><?=$_lang["shop_status"];?></th>
			<th width="120px"><?=$_lang["shop_availability"];?></th>
			<th width="260px"></th>
		</tr>
	</thead>
	<tbody>
		<? 
			if ($modx->db->getRecordCount($items) == 0):
		?>
			<tr>
				<th colspan="6" class="text-center"><h4><?=$_lang["shop_on_request"];?> <i><u><?=$_GET['s']?></u></i> <?=$_lang["shop_not_found"];?></h4></th>
			</tr>
		<?
			else: 
			while ($pro = $modx->db->getRow($items)): 
			$i++;
		?>
			<tr>
				<td><?=$pro['product_id']?></td>
				<td>
					<img src="<?=$modx->runSnippet("R", Array("img" => "/assets/images/items/".$pro['product_id']."/".$pro['product_cover'], "opt" => "w=32&h=32&far=1"))?>" class="img-thumbnail" alt="">
					<b><a href="<?=$modx->makeUrl($modx->config['shop_page_item']).$pro['product_url']?>" target="_blank"><?=$pro['product_name']?></a></b>
				</td>
				<td>					
					<div class="input-group">
					  <input type="text" class="form-control" data-product-price="<?=$pro['product_id']?>" value="<?=$pro['product_price']?>" data-allow="[^0-9\.?]" placeholder="Разделитель точка">
					  <span class="input-group-addon"><?=$modx->config['shop_curr_default_sign']?></span>
					</div>
				</td>
				<td><?=($pro['product_visible'] ? '<span class="label label-success">'.$_lang["shop_visible"].'</span>' : '<span class="label label-danger">'.$_lang["shop_not_visible"].'</span>')?></td>
				<td>
					<b>
						<? if ($pro['product_available'] == 0): ?><span class="text-muted"><?=$_lang["shop_not_available"]?></span><? endif ?>
						<? if ($pro['product_available'] == 1): ?><span class="text-success"><?=$_lang["shop_available"]?></span><? endif ?>
						<? if ($pro['product_available'] == 2): ?><span class="text-info"><?=$_lang["shop_new"]?></span><? endif ?>
						<? if ($pro['product_available'] == 3): ?><span class="text-warning"><?=$_lang["shop_bestseller"]?></span><? endif ?>
						<? if ($pro['product_available'] == 4): ?><span class="text-danger"><?=$_lang["shop_promotional"]?></span><? endif ?>
					</b>
				</td>
				<td>
					<a href="<?=$url?>b=items&c=edit&i=<?=$pro['product_id']?>" class="btn btn-mini btn-primary"><span class="glyphicon glyphicon-edit"></span> <?=$_lang["shop_edit"]?> </a>
					<a href="<?=$url?>b=items&c=delete&i=<?=$pro['product_id']?>" onclick="return confirm('Are you sure?');" class="btn btn-mini btn-danger"><span class="glyphicon glyphicon-remove-sign"></span> <?=$_lang["shop_delete"]?> </a>
				</td>
			</tr>
		<? endwhile;endif; ?>		
	</tbody>
</table>
<ul class="pagination"><?=$pagin;?></ul>