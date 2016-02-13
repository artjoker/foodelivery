<tr>
	<td><img src="<?=$modx->runSnippet("R", array("img" => "/assets/images/banners/".$banner['banner_image'], "opt" => "w=468&h=192&zc=1"))?>" alt="" class="thumbnail"></td>
	
	<td>
			<div class="col-md-4">
				<label><input type="radio" name="banner[<?=$banner['banner_id']?>][type]" <? if ($banner['banner_link_type'] == 1): ?>checked<? endif ?> value="1"> product</label>
			</div>
			<div class="col-md-8">
				<input type="text" name="banner[<?=$banner['banner_id']?>][link]" value="<?=$banner['banner_link_id']?>" class="form-control">
			</div>
			<div class="clearfix"></div>
			<div class="col-md-4">
				<label><input type="radio" name="banner[<?=$banner['banner_id']?>][type]" <? if ($banner['banner_link_type'] == 2): ?>checked<? endif ?> value="2"> category</label>
			</div>
			<div class="col-md-8">
				<select name="banner[<?=$banner['banner_id']?>][link]" class="form-control" data-placeholder="<?=$_lang["shop_categories_p"]?>" data-active="<?=$banner['banner_link_id']?>">
					<option value="0" disabled selected>--- None ---</option>
					<? foreach($categories as $cat):?>
						<option value="<?=$cat['id']?>"><?=$cat['pagetitle']?> (<?=$cat['id']?>)</option>
					<? endforeach ?>
				</select>
			</div>
	</td>
	<td><input type="text" name="banner[<?=$banner['banner_id']?>][position]" value="<?=$banner['banner_position']?>" class="form-control"></td>
	<td>
		<? if ($banner['banner_active']): ?>
			<span class="label label-success">Active</span>
		<? else: ?>
			<span class="label label-danger">Not active</span>
		<? endif ?>
	</td>
	<td>
		<? if ($banner['banner_active'] == 1): ?>
			<a href="<?=$url?>b=banners&c=active&active=0&update=<?=$banner['banner_id']?>"  class="btn btn-warning" title="Toggle activity"><span class="glyphicon glyphicon-eye-close"></span></a>
		<? else: ?>
			<a href="<?=$url?>b=banners&c=active&active=1&update=<?=$banner['banner_id']?>"  class="btn btn-success" title="Toggle activity"><span class="glyphicon glyphicon-eye-open"></span></a>
		<? endif ?>
	</td>
	<td>
		<a href="<?=$url?>b=banners&c=delete&delete=<?=$banner['banner_id']?>" onclick="return confirm('Are you sure?')" class="btn btn-danger" title="Remove banner"><span class="glyphicon glyphicon-remove"></span></a>
	</td>
</tr>