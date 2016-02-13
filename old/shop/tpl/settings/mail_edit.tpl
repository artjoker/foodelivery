<form action="<?=$url?>b=mail" method="post">
	<table class="table table-condesed">
		<tr>
			<th width="200px"><?=$_lang["shop_name_tpl"]?></th>
			<td><input type="text" maxlength="32" name="mail[title]" <?=($_GET['tpl'] == "meta" ? "readonly" : "")?> value="<?=$template[$modx->config['lang_default']]['mail_title']?>" class="form-control"></td>
		</tr>
		<tr>
			<th><?=$_lang["shop_id_tpl"]?></th>
			<td><input type="text" maxlength="16" name="mail[name]" <?=($_GET['tpl'] == "meta" ? "readonly" : "")?> value="<?=$template[$modx->config['lang_default']]['mail_name']?>" class="form-control"></td>
		</tr>
	</table>
		<? foreach ($languages as $lang): ?>
			<h4><?=$_lang["shop_version_lang"]?>: <kbd><?=$lang?></kbd></h4>
			<input type="text" name="mail[subject][<?=$lang?>]" placeholder="<?=$_lang["shop_mail_subject_p"]?>" class="form-control" value="<?=$template[$lang]['mail_subject']?>">
			<br>
			<textarea name="mail[tpl][<?=$lang?>]" id="<?=$lang?>" class="form-control" rows="10"><?=($template[$lang]['mail_template'] == "" ? $_lang['shop_mail_main_p'] : $template[$lang]['mail_template'] )?></textarea>
		<? endforeach ?>
	<? if ($_GET['tpl'] == "meta"): ?>
		<p class="text-danger">
			<?=$_lang["shop_mail_i"]?><code>{body}</code> <?=$_lang["shop_mail_i"]?>
		</p>
	<? endif ?>
	<button class="btn btn-lg btn-primary" type="submit"><span class="glyphicon glyphicon-floppy-disk"></span> <?=$_lang["shop_save_tpl"]?></button>
</form>
<?=$shop->CM("mail[tpl][*]")?>