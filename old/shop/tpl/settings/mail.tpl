<a href="<?=$url?>b=mail&tpl=new" class="btn btn-success">
	<span class="glyphicon glyphicon-plus-sign"></span> 
	<?=$_lang["shop_new_mail_tpl"]?>
</a>
<a href="<?=$url?>b=mail&tpl=meta" class="btn btn-info pull-right">
	<span class="glyphicon glyphicon-plus-sign"></span> 
	<?=$_lang["shop_edit_meta_tpl"]?>
</a>
<h3><?=$_lang["shop_available_tpls"]?></h3>
<table class="table table-responsive table-bordered table-striped table-condensed">
	<thead>
		<tr>
			<th><?=$_lang["shop_name_tpl"]?></th>
			<th width="200px"><?=$_lang["shop_id_tpl"]?></th>
			<th width="100px"></th>
		</tr>
	</thead>
	<tbody>
<? while ($mail = $modx->db->getRow($mails)): ?>
		<tr <?=($mail['mail_name'] == "meta" ? 'class="info"' : '')?>>
			<td><?=$mail['mail_title']?></td>
			<td><?=$mail['mail_name']?></td>
			<td>
				<a href="<?=$url?>b=mail&tpl=<?=$mail['mail_name']?>" class="btn btn-primary text-left"><span class="glyphicon glyphicon-envelope"></span> <?=$_lang["shop_edit_tpl"]?></a>
			</td>
		</tr>
<? endwhile ?>
	</tbody>
</table>