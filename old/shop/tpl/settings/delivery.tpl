<form action="<?=$url?>b=delivery" method="post">
	<table class="table table-bordered table condensed">
		<thead>
			<tr>
				<th><?=$_lang["shop_delivery_name"]?></th>
				<th width="170px"><?=$_lang["shop_delivery_cost"]?></th>
				<th width="130px"><?=$_lang["shop_default"]?></th>
				<th width="200px"></th>
			</tr>
		</thead>
		<tbody>
			<? foreach ($delivery as $key => $value): ?>
			<tr<?=($value['delivery_default'] ? " class='info'" : "")?>>
				<td><input type="text" name="delivery[title][<?=$value['delivery_id']?>]" value="<?=$value['delivery_title']?>" class="form-control "></td>
				<td><input type="text" name="delivery[cost][<?=$value['delivery_id']?>]" value="<?=$value['delivery_cost']?>" class="form-control "></td>
				<td align="center"><input type="radio" name="default" value="<?=$value['delivery_id']?>" <?=($value['delivery_default'] ? "checked" : "")?>></td>
				<td><a href="#" class="btn btn-danger" data-remove><span class="glyphicon glyphicon-remove-sign"></span> <?=$_lang["shop_delivery_delete"]?></a></td>
			</tr>
			<? endforeach ?>
			<tr>
				<td><input type="text" name="delivery[title][]" value="" class="form-control"></td>
				<td><input type="text" name="delivery[cost][]" value="" class="form-control"></td>
				<td colspan="2"></td>
			</tr>
		</tbody>
	</table>
	<input type="submit" value="<?=$_lang["shop_save_settings"]?>" class="btn btn-lg btn-primary">
</form>
<script>
	$("[data-remove]").on("click", function(){
		$(this).parents("tr").slideUp().remove();
	})
</script>