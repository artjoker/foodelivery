<div class="panel panel-default">
  <div class="panel-heading">
    <a href="#map_faq" data-toggle="modal" class="link pull-right"><span class="glyphicon glyphicon-question-sign"></span> <?php echo $app->lang->get('How to use')?></a>
    <?php echo $app->lang->get('Shop list')?>
  </div>
  <div class="panel-body" id="gmap">
    Google Map here
  </div>
  <div class="panel-footer">
    <form action="<?php echo URL_ROOT ?>admin/shops" method="post">
      <table class="table table-condensed table-bordered table-responsive">
      <thead>
      <tr>
        <th width="220px"><?php echo $app->lang->get('Name')?></th>
        <th><?php echo $app->lang->get('Address')?></th>
        <th><?php echo $app->lang->get('Phone')?></th>
        <th><?php echo $app->lang->get('Lat')?></th>
        <th><?php echo $app->lang->get('Lng')?></th>
        <th width="80px"><?php echo $app->lang->get('Active')?></th>
        <th width="100px"></th>
      </tr>
      </thead>
      <tbody>
        <?php
          $i = 0;
          foreach($shops as $shop):
         ?>
        <tr>
          <td><input type="text" name="shop[name][<?php echo $shop['shop_id']?>]" value="<?php echo $shop['shop_name']?>" class="form-control"></td>
          <td><input type="text" name="shop[addr][<?php echo $shop['shop_id']?>]" value="<?php echo $shop['shop_addr']?>" class="form-control"></td>
          <td><input type="text" name="shop[phone][<?php echo $shop['shop_id']?>]" value="<?php echo $shop['shop_phone']?>" class="form-control"></td>
          <td><input type="text" name="shop[lat][<?php echo $shop['shop_id']?>]" value="<?php echo $shop['shop_lat']?>" class="form-control"></td>
          <td><input type="text" name="shop[lng][<?php echo $shop['shop_id']?>]" value="<?php echo $shop['shop_lng']?>" class="form-control"></td>
          <td><input type="checkbox" name="shop[active][<?php echo $shop['shop_id']?>]" <?php echo $shop['shop_active'] == 1 ? "checked" : ""?> value="yes" class="make-switch"></td>
          <td>
            <a href="#" data-show="<?php echo $shop['shop_lat']?>,<?php echo $shop['shop_lng']?>" class="btn btn-info"><span class="glyphicon glyphicon-map-marker"></span></a>
            <a href="#" title="<?php echo $app->lang->get('Remove')?>" class="btn btn-danger" data-marker="<?php echo $i?>"><span class="glyphicon glyphicon-remove-sign"></span></a>
          </td>
        </tr>
        <?php
          $i++;
          endforeach;
        ?>
      </tbody>
    </table>
      <button type="submit" class="btn btn-lg btn-primary"><span class="glyphicon glyphicon-save"></span> <b><?php echo $app->lang->get('Save')?></b></button>
    </form>
  </div>
</div>

<div class="modal fade" id="map_faq" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php echo $app->lang->get('How to use')?></h4>
      </div>
      <div class="modal-body">
        <dl class="dl-horizontal">
          <dt><kbd>Double click</kbd></dt>
          <dd>Place new marker on map</dd>
        </dl>
      </div>
    </div>
  </div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GMAP_KEY?>&callback=initMap"
        defer></script>
<script>
  var map,row;
  var mrk = [];
  function initMap() {
    coord = $("[data-show]:first").attr("data-show").split(",");
    map = new google.maps.Map(document.getElementById('gmap'), {
      center: {lat: parseFloat(coord[0]), lng: parseFloat(coord[1])},
      zoom: 16
    });
    map.addListener('dblclick', function(e) {
      placeMarkerAndPanTo(e.latLng, map);
    });
  }
  function placeMarkerAndPanTo(latLng, map) {
    var marker = new google.maps.Marker({
      position: latLng,
      map: map
    });
    // add to markers array
    mrk.push(marker);
    // create new row for shop
    $("tbody").append(row.clone());
    $("tbody tr:last .bootstrap-switch").remove();
    $("tbody tr:last input").each(function(){
      $(this).attr("name", $(this).attr("name").replace(/\[\d\]/g, '[]'));
      $(this).val('');
    })
    $("tbody tr:last [data-show]").attr('data-show', latLng.lat()+','+latLng.lng());
    $("tbody tr:last [name*='lat']").val(latLng.lat());
    $("tbody tr:last [name*='lng']").val(latLng.lng());
    // attach row to marker
    $("tbody tr:last [data-marker]").attr('data-marker', mrk.length - 1);

  }
  $(document).ready(function(){
    row = $("tbody tr:first").clone();
    <?php foreach ($shops as $shop):?>
    mrk.push(new google.maps.Marker({
      position: {lat: <?php echo $shop['shop_lat']?>, lng: <?php echo $shop['shop_lng']?>},
      map: map
    }));
    <?php endforeach ?>
    // show shop on map
    $("tbody").on("click", "[data-show]", function(){
      coords = $(this).attr("data-show").split(",");
      map.setCenter({lat: parseFloat(coords[0]), lng: parseFloat(coords[1])});
      map.setZoom(16);
      return false;
    })
    // remove marker on map
    $("tbody").on("click", "[data-marker]", function(){
      id = $(this).attr("data-marker");
      mrk[parseInt(id)].setMap(null);
      $(this).closest("tr").remove();
      return false;
    })
  })
</script>