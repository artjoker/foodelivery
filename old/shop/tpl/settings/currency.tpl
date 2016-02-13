<form action="<?=$url?>b=currency" method="post">
	<table class="table table-bordered table condensed">
		<thead>
			<tr>
				<th><?=$_lang["shop_currency_name"]?></th>
				<th><?=$_lang["shop_internal_id"]?> <div class="pull-right glyphicon glyphicon-question-sign text-muted" title="<?=$_lang["shop_internal_id_i"]?>"></div></th>
				<th><?=$_lang["shop_currency_sign"]?> <div class="pull-right glyphicon glyphicon-question-sign text-muted" title="<?=$_lang["shop_currency_sign_i"]?>"></div></th>
				<th><?=$_lang["shop_currency"]?> <div class="pull-right glyphicon glyphicon-question-sign text-muted" title="<?=$_lang["shop_currency_i"]?>"></div></th>
				<th><?=$_lang["shop_summary"]?></th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<? foreach ($names as $key => $value): ?>
			<tr>
				<td><input type="text" name="name[]" value="<?=$value?>" class="form-control span4"></td>
				<td><input type="text" name="code[]" value="<?=$codes[$key]?>" class="form-control span4"></td>
				<td><input type="text" name="sign[]" value="<?=$signs[$key]?>" class="form-control span4"></td>
				<td><input type="text" name="rate[]" value="<?=$rates[$key]?>" class="form-control span4"></td>
				<td align="center">
					<input type="radio" name="r"<?=($activ[$key] ? "checked" : "")?>>
					<input type="hidden" name="active[]" value="<?=$activ[$key]?>">
				</td>
				<td><a href="#" class="btn btn-danger" data-remove><span class="glyphicon glyphicon-remove-sign"></span> <?=$_lang["shop_currency_delete"]?></a></td>
			</tr>
			<? endforeach ?>
			<tr>
				<td><input type="text" name="name[]" value="" class="form-control span4"></td>
				<td><input type="text" name="code[]" value="" class="form-control span4"></td>
				<td><input type="text" name="sign[]" value="" class="form-control span4"></td>
				<td><input type="text" name="rate[]" value="" class="form-control span4"></td>
				<td align="center">
					<input type="radio" name="r">
					<input type="hidden" name="active[]" value="<?=$activ[$key]?>">
				</td>
				<td><a href="#" class="btn btn-danger" data-remove><span class="glyphicon glyphicon-remove-sign"></span> <?=$_lang["shop_currency_delete"]?></a></td>
			</tr>
		</tbody>
	</table>
	<input type="submit" value="<?=$_lang["shop_save_settings"]?>" class="btn btn-lg btn-primary">
</form>
<script>
	$("[type=radio]").on("click", function(){
		$("[type=hidden]").val('0');
		$(this).next("[type=hidden]").val('1');
	})
	$("[data-remove]").on("click", function(){
		$(this).parents("tr").slideUp().remove();
	})
</script>