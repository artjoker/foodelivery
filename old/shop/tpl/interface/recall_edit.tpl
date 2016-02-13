<form action="<?=$url?>b=recalls&c=update" method="post">
	<h3 class="muted"><?=$_lang["shop_recall_edit"]?></h2>
	<table class="table table-bordered">
		<tr>
			<th width="200px"><?=$_lang["shop_recall_name"]?></th>
			<td><input type="text" name="recall[name]" class="form-control" value="<?=$recall['recall_name']?>"></td>
		</tr>
		<tr>
			<th width="200px"><?=$_lang["shop_recall_email"]?></th>
			<td><input type="text" name="recall[email]" class="form-control" value="<?=$recall['recall_email']?>"></td>
		</tr>
		<tr>
			<th width="200px"><?=$_lang["shop_recall_text"]?></th>
			<td><textarea name="recall[text]" class="form-control"><?=$recall['recall_text']?></textarea></td>
		</tr>
		<tr>
			<th><?=$_lang["shop_recall_moderated"]?></th>
			<td>
				<label><input type="radio" name="recall[moderated]" <?=($recall['recall_moderated'] == 0 ? 'checked' : '')?> value="0"> <?=$_lang["shop_not_pub"]?> </label><br>
				<label><input type="radio" name="recall[moderated]" <?=($recall['recall_moderated'] == 1 ? 'checked' : '')?> value="1"> <?=$_lang["shop_pub"]?> </label><br>
			</td>
		</tr>
		<tr>
			<th><?=$_lang["shop_recall_date"]?></th>
			<td><?=$recall['recall_pub_date']?></td>
		</tr>
		<tr>
			<th><?=$_lang["shop_product"]?></th>
			<td><a target="_blank" href="<?=$modx->makeUrl($modx->config['shop_page_item']).$recall['pruduct_url']?>"><?=$recall['pruduct_name']?></a></td>
		</tr>
	</table>
	<input type="hidden" name="recall[id]" value="<?=$recall['recall_id']?>">
	<input type="submit" value="<?=$_lang["shop_recall_update"]?>" class="btn btn-primary">
</form>