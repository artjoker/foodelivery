<form action="<?=$url?>" method="post">
	<h3><?=$_lang["shop_common"]?></h3>
	<table class="table table-bordered table condensed">
		<tr>
			<td width="250px">
				<b>
					<?=$_lang["shop_page_item"]?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<span class="pull-right text-muted glyphicon glyphicon-question-sign" title="<?=$_lang["shop_page_item_i"]?>"></span>
				</b>
				<small class="text-muted">shop_page_item</small>
			</td>
			<td><input type="text" name="shop_page_item" id="" class="form-control span6" value="<?=$modx->config['shop_page_item']?>" /></td>
		</tr>
		<tr>
			<td>
				<b>
					<?=$_lang["shop_tpl_category"]?>
					<span class="pull-right text-muted glyphicon glyphicon-question-sign" title="<?=$_lang["shop_tpl_category_i"]?>"></span>
				</b>
				<small class="text-muted">shop_tpl_category</small>
			</td>
			<td><input type="text" name="shop_tpl_category" id="" class="form-control span6" value="<?=$modx->config['shop_tpl_category']?>" /></td>
		</tr>
		<tr>
			<td>
				<b><?=$_lang["shop_catalog_root"]?> <!-- <span class="text-muted glyphicon glyphicon-question-sign" title=""></span> --></b>
				<br><small class="text-muted">shop_catalog_root</small>
			</td>
			<td><input type="text" name="shop_catalog_root" id="" class="form-control span6" value="<?=$modx->config['shop_catalog_root']?>" /></td>
		</tr>
	</table>
	<a href="https://www.google.com/recaptcha/admin/" target="_blank" class="btn btn-link pull-right"><?=$_lang["shop_get_keys"]?></a>
	<h3>reCaptcha</h3>
	<table class="table table-bordered table condensed">
		<tr>
			<td width="250px">
				<b>reCapthca public key <!-- <span class="text-muted glyphicon glyphicon-question-sign" title=""></span> --></b>
				<br><small class="text-muted">shop_rc_pukey</small>
			</td>
			<td><input type="text" name="shop_rc_pukey" id="" class="form-control span6" value="<?=$modx->config['shop_rc_pukey']?>" /></td>
		</tr>
		<tr>
			<td>
				<b>reCapthca private key <!-- <span class="text-muted glyphicon glyphicon-question-sign" title=""></span> --></b>
				<br><small class="text-muted">shop_rc_prkey</small>
			</td>
			<td><input type="text" name="shop_rc_prkey" id="" class="form-control span6" value="<?=$modx->config['shop_rc_prkey']?>" /></td>
		</tr>
	</table>
	<input type="submit" value="<?=$_lang["shop_save_settings"]?>" class="btn btn-lg btn-primary">
</form>