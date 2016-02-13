<form action="<?=$url?>b=filters&d=save" method="post">
	<h3 class="muted"><?=$_lang["shop_filter_add"]?></h2>
	<table class="table table-bordered">
		<tr>
			<th width="200px"><?=$_lang["shop_filter_name"]?></th>
			<td><input type="text" name="filter[name]" class="form-control"></td>
		</tr>
		<tr>
			<th width="200px"><?=$_lang["shop_filter_desc"]?></th>
			<td><textarea name="filter[desc]" class="form-control"></textarea></td>
		</tr>
		<tr>
			<th><?=$_lang["shop_filter_type"]?></th>
			<td>
				<label><input type="radio" name="filter[type]" checked value="1"> <?=$_lang["shop_numerical"]?> </label><br>
				<label><input type="radio" name="filter[type]" value="2"> <?=$_lang["shop_or"]?> </label><br>
				<label><input type="radio" name="filter[type]" value="3"> <?=$_lang["shop_and"]?></label>
			</td>
		</tr>
		<tr>
			<th><?=$_lang["shop_bind_category"]?></th>
			<td>
				<select name="filter[category][]" multiple="multiple" class="form-control" data-placeholder="<?=$_lang["shop_bind_category_p"]?>" data-active="<?=$_GET['c']?>">
					<? while($cat = $modx->db->getRow($categories)):?>
						<option value="<?=$cat['id']?>"><?=$cat['pagetitle']?> (<?=$cat['id']?>)</option>
					<? endwhile ?>
				</select>
			</td>
		</tr>
	</table>
	<input type="submit" value="<?=$_lang["shop_filter_save"]?>" class="btn btn-primary">
</form>