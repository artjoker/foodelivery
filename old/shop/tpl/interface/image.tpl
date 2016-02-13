<li>
	<img src="/<?=$modx->runSnippet("phpthumb", Array("input" => $folder.'/'.$image, "options" => "w=128&h=128&far=1"))?>" alt="<?=$image?>" class="img-thumbnail" />
	<a href="#" title="<?=$_lang["shop_images_delete"]?>" class="btn btn-xs btn-danger"><span class="glyphicon glyphicon-remove-sign"></span></a>
	<a href="#" title="<?=$_lang["shop_set_cover"]?>" class="btn btn-xs btn-primary"><span class="glyphicon glyphicon-camera"></span></a>
</li>