<h4 class="text-muted">
	<?=$_lang["shop_edit_product"]?> 
	<i class="text-normal"><?=$translate[$modx->config['lang_default']]['product_name']?></i> 
	<?=$product['product_code']?>
</h4>
<form action="<?=$url?>b=items&c=update" method="post" enctype="multipart/form-data">
	<div class="pull-right">
		<a href="<?=$modx->makeUrl($modx->config['shop_page_item']).$product['product_url']?>" class="btn btn-info" target="_blank"><span class="glyphicon glyphicon-eye-open"></span> <?=$_lang["shop_view_product_page"]?></a>
		&nbsp;
		<button class="btn btn-primary" type="submit"><span class="glyphicon glyphicon-floppy-disk"></span> <b><?=$_lang["shop_update_product"]?></b></button>
	</div>
	<ul class="nav nav-tabs" id="myTab">
	  <li class="active"><a href="#product" data-toggle="tab"><b><?=$_lang["shop_product"]?></b></a></li>
	  <li><a href="#text" data-toggle="tab"><b><?=$_lang["shop_texts"]?></b></a></li>
	  <li><a href="#seo" data-toggle="tab"><b>SEO</b></a></li>
	  <li><a href="#filters" data-toggle="tab"><b><?=$_lang["shop_filters"]?></b></a></li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane fade in active" id="product">
			<br>
			<table class="table table-condensed table-bordered table-striped">
				<tr>
					<td><b>URL</b></td>
					<td>
						<div class="input-group">
						  <span class="input-group-addon"><?=$modx->makeUrl(5, null, null, 1)?></span>
						  <input type="text" class="form-control" name="edit[url]" value="<?=$product['product_url']?>" id="alias" placeholder="<?=$_lang["shop_url_product"]?>">
						</div>
					</td>
				</tr>
				<tr>
					<td><b><?=$_lang["shop_product_code"]?></b></td>
					<td><input type="text" name="edit[code]" class="form-control" placeholder="<?=$_lang["shop_product_code_p"]?>" value="<?=$product['product_code']?>" /></td>
				</tr>
				<tr>
					<td><b><?=$_lang["shop_price"]?></b></td>
					<td>
						<div class="input-group">
						  <input type="text" class="form-control col-xs-2" name="edit[price]" data-allow="[^0-9\.?]" value="<?=$product['product_price']?>" placeholder="<?=$_lang["shop_price_p"]?>">
						  <span class="input-group-addon"><?=$modx->config['shop_curr_default_sign']?></span>
						</div>
					</td>
				</tr>
				<tr>
					<td><b><?=$_lang["shop_availability"]?></b></td>
					<td>
						<div class="make-switch" data-on="success" data-off="danger">
							<input type="checkbox" name="edit[visible]" <?=($product['product_price'] ? "checked" : "")?>  value="yes">
						</div>
					</td>
				</tr>
				<tr>
					<td><b><?=$_lang["shop_status"]?></b></td>
					<td>
						<label class="col-md-8"><input type="radio" <?=($product['product_available'] == 0 ? "checked" : "")?> name="edit[available]" value="0"> <span class="text-muted"><?=$_lang["shop_not_available"]?></span></label>
						<label class="col-md-8"><input type="radio" <?=($product['product_available'] == 1 ? "checked" : "")?> name="edit[available]" value="1" checked="checked"> <span class="text-success"><?=$_lang["shop_available"]?></span></label>
						<label class="col-md-8"><input type="radio" <?=($product['product_available'] == 2 ? "checked" : "")?> name="edit[available]" value="2"> <span class="text-info"><?=$_lang["shop_new"]?></span></label>
						<label class="col-md-8"><input type="radio" <?=($product['product_available'] == 3 ? "checked" : "")?> name="edit[available]" value="3"> <span class="text-warning"><?=$_lang["shop_bestseller"]?></span></label>
						<label class="col-md-8"><input type="radio" <?=($product['product_available'] == 4 ? "checked" : "")?> name="edit[available]" value="4"> <span class="text-danger"><?=$_lang["shop_promotional"]?></span></label>
					</td>
				</tr>
				<tr>
					<td><b><?=$_lang["shop_categories"]?></b></td>
					<td>
						<select name="edit[category][]" required multiple="multiple" class="form-control" data-placeholder="<?=$_lang["shop_categories_p"]?>" data-active="<?=$product['product_categories']?>">
							<? while($cat = $modx->db->getRow($categories)):?>
								<option value="<?=$cat['id']?>"><?=$cat['pagetitle']?> (<?=$cat['id']?>)</option>
							<? endwhile ?>
						</select>
					</td>
				</tr>
				<tr>
					<td><b><?=$_lang["shop_product_friends"]?></b></td>
					<td><input type="text" class="form-control col-xs-2" name="edit[friends]" placeholder="<?=$_lang["shop_product_friends_p"]?>" value="<?=$product['product_friends']?>"></td>
				</tr>
				<tr>
					<td><b><?=$_lang["shop_images"]?></b></td>
					<td>
						<a href="#" id="uploader" class="btn btn-warning"><?=$_lang["shop_load_images"]?></a>
						<ul class="images"></ul>
						<input type="hidden" name="edit[cover]" id="cover" value="<?=$product['product_cover']?>" />
					</td>
				</tr>
			</table>
		</div>
		<div class="tab-pane" id="text">
			<br>
			<ul class="nav nav-tabs" id="langTab">
				<? foreach ($languages as $lang): ?>
			  	<li><a href="#<?=$lang?>" data-toggle="tab"><b><?=strtoupper($lang)?></b></a></li>
				<? endforeach; ?>
			</ul>
			<br>
			<div class="tab-content">
				<? foreach ($translate as $key => $value): ?>
					<div class="tab-pane" id="<?=$key?>">
						<table class="table table-condensed table-bordered table-striped">
							<tr>
								<td width="100px"><b><?=$_lang["shop_name"]?></b></td>
								<td><input type="text" name="edit[<?=$key?>][name]" class="form-control" value="<?=$value['product_name']?>" /></td>
							</tr>
							<tr>
								<td width="100px"><b><?=$_lang["shop_introtext"]?></b></td>
								<td><textarea name="edit[<?=$key?>][introtext]" id="introtext_<?=$key?>"><?=$value['product_introtext']?></textarea></td>
							</tr>
							<tr>
								<td><b><?=$_lang["shop_description"]?></b></td>
								<td><textarea name="edit[<?=$key?>][description]" id="description_<?=$key?>"><?=$value['product_description']?></textarea></td>
							</tr>
						</table>
					</div>
				<? endforeach ?>
			</div>
		</div>
		<div class="tab-pane" id="seo">
			<br>
			<table class="table table-condensed table-bordered table-striped">
				<tr>
					<th width="100px">Title</th>
					<td><input type="text" name="tv_seo_title" value="<?=$product['tv_seo_title']?>" class="form-control"></td>
				</tr>
				<tr>
					<th>Keywords</th>
					<td><input type="text" name="tv_seo_keywords" value="<?=$product['tv_seo_keywords']?>" class="form-control"></td>
				</tr>
				<tr>
					<th>Description</th>
					<td><textarea name="tv_seo_description" class="form-control"><?=$product['tv_seo_description']?></textarea></td>
				</tr>
			</table>
		</div>
		<div class="tab-pane" id="filters">
			<br>
			<table class="table table-condensed table-hover table-bordered table-striped">
				<tr>
					<th><?=$_lang["shop_filter_name"]?></th>
					<th><?=$_lang["shop_value"]?></th>
					<th width="100px"><?=$_lang["shop_type"]?></th>
				</tr>
				<? foreach($filters as $filter): ?>
					<tr>
						<td width="200px">
							<b><?=$filter['filter_name']?></b>
              <? if ($filter['filter_desc'] != ""): ?>
                  <div class="glyphicon glyphicon-question-sign text-muted pull-right" title="<?=$filter['filter_desc']?>"></div>
              <? endif ?>
						</td>
				<?
					switch($filter['filter_type']) {
						case 1: // 	numeric 
						?>
								<td>
									<input type="text" class="form-control" name="filter[<?=$filter['filter_id']?>]" value="<?=$filter['value']?>">
								</td>
								<td><small><?=$_lang["shop_numerical"]?></small></td>
							</tr>						
						<?php
						break; 
						case 2: // OR
						?>
								<td>
									<label><input type="radio" name="filter[<?=$filter['filter_id']?>]" <?=($filter['value'] == 1 ? "checked" : "")?> value="1"> <span class="label label-success"><?=$_lang["yes"]?></span></label>
									<label><input type="radio" name="filter[<?=$filter['filter_id']?>]" <?=($filter['value'] == 0 ? "checked" : "")?> value="0"> <span class="label label-danger"><?=$_lang["no"]?></span></label>
								</td>
								<td><small><?=$_lang["shop_or"]?></small></td>
							</tr>						
						<?php
						break;
						case 3: // 	AND
						?>
								<td>
									<input type="text" class="form-control" id="filter_<?=$filter['filter_id']?>" autocomplete="off" name="filter[<?=$filter['filter_id']?>]" value="<?=$filter['value']?>">
									<script>
										$(document).ready(function(){
											$('#filter_<?=$filter['filter_id']?>').typeahead({
											  name: 'filter',
											  remote: '<?=$url?>b=filters&d=json&f=<?=$filter['filter_id']?>',
											  limit: 10
											});
										})
									</script>
								</td>
								<td><small><?=$_lang["shop_and"]?></small></td>
							</tr>						
						<?php
						break; 
					}
				endforeach; ?>
			</table>
		</div>
	</div>
	<button class="pull-right btn btn-primary" type="submit"><span class="glyphicon glyphicon-floppy-disk"></span> <b><?=$_lang["shop_update_product"]?></b></button>
	<input type="hidden" name="edit[id]" value="<?=$product['product_id']?>" />
	<a href="<?=$url?>b=items" class="btn btn-warning"><span class="icon icon-white icon-hand-left"></span> <?=$_lang["shop_cancel"]?></a>
</form>
<script src="/assets/site/ajaxupload.js"></script>
<script>
	function updateImages(){
        $.ajax({url:"index.php",type:"GET", data:"a=255&b=get_images&folder=<?=$product['product_id']?>", success:function(ajax){
            $(".images").html(ajax);
            $("[title]").tooltip();
            $(".images img[alt='"+$("#cover").val()+"']").addClass("cover");
        }})
    }
    $(document).on("click", ".images .btn-danger", function() {
        if (!confirm("<?=$_lang['shop_images_delete']?>")) return false; 
        t = $(this).prevAll("img");
        if (t.hasClass("cover")) {
            $("#cover").val('');
            t.removeClass("cover");
        }
        $.ajax({url:"index.php",type:"GET", data:"a=255&b=delete_image&folder=<?=$product['product_id']?>&delete="+t.attr("alt"), success:function(ajax){
            t.parents("li").fadeOut().remove();
        }})
    })
    $(document).on("click", ".images .btn-primary, .image img", function(e) {
    		e.preventDefault();
    		_img = $(this).parents("li").find("img");
        $("#cover").val(_img.attr('alt'));
        $(".images .cover").removeClass("cover");
        _img.addClass("cover");
    })
    updateImages();

    $(document).ready(function(){
    	$("#langTab a:first").trigger("click");

	  	$("[data-allow]").on("keyup", function(){
	  			$(this).val($(this).val().replace(new RegExp($(this).attr("data-allow")), ''));
	  	});

			new AjaxUpload($("#uploader"), {
				action: "index.php?a=255&b=upload",
				multiple: true,
			  name: "uploader[]",
			  data: {
						"size"  : 2048576,
						"folder": '<?=$product['product_id']?>'
			  },
			  onSubmit: function(file, ext){
			      if (! (ext && /^(jpg|png|jpeg|JPG|PNG|JPEG)$/.test(ext))){ 
			          alert('<?=$_lang["shop_file_format"]?>');
			          return false;
			      }
			  },
			  onComplete: function(file, response){
			      updateImages();
			  }
			});
    })

</script>
<?=$tiny_mce?>
