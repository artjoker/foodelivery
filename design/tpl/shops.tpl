<div class="panel panel-default">
  <div class="panel-heading">
    <a href="#map_faq" data-toggle="modal" class="link pull-right"><span class="glyphicon glyphicon-question-sign"></span> <?php echo $app->lang->get('How to use')?></a>
    <?php echo $app->lang->get('Shop list')?>
  </div>
  <div class="panel-body" id="gmap">
    Google Map here
  </div>
  <div class="panel-footer">
    <table class="table table-condensed table-bordered">
      <thead>
      <tr>
        <th><?php echo $app->lang->get('Name')?></th>
        <th><?php echo $app->lang->get('Address')?></th>
        <th width="130px"><?php echo $app->lang->get('Lat')?></th>
        <th width="130px"><?php echo $app->lang->get('Lng')?></th>
        <th width="80px"><?php echo $app->lang->get('Active')?></th>
        <th width="100px"></th>
      </tr>
      </thead>
      <tbody>
        <?php foreach($shops as $shop): ?>
        <tr>
          <td><input type="text" name="shop[<?php echo $shop['shop_id']?>][name]" value="<?php echo $shop['shop_name']?>" class="form-control"></td>
          <td><input type="text" name="shop[<?php echo $shop['shop_id']?>][addr]" value="<?php echo $shop['shop_addr']?>" class="form-control"></td>
          <td><input type="text" name="shop[<?php echo $shop['shop_id']?>][lat]" value="<?php echo $shop['shop_lat']?>" class="form-control"></td>
          <td><input type="text" name="shop[<?php echo $shop['shop_id']?>][lng]" value="<?php echo $shop['shop_lng']?>" class="form-control"></td>
          <td><input type="checkbox" name="shop[<?php echo $shop['shop_id']?>][active]" value="1" class="make-switch"></td>
          <td>
            <a href="#" data-show="<?php echo $shop['shop_lat']?>,<?php echo $shop['shop_lng']?>" class="btn btn-info"><span class="glyphicon glyphicon-map-marker"></span></a>
            <a href="#" title="<?php echo $app->lang->get('Remove')?>" class="btn btn-danger"><span class="glyphicon glyphicon-remove-sign"></span></a>
          </td>
        </tr>
        <?php endforeach ?>
      </tbody>
    </table>
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
  function initMap() {
    map = new google.maps.Map(document.getElementById('gmap'), {
      center: {lat: -34.397, lng: 150.644},
      zoom: 8
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
//    map.panTo(latLng);
    $("tbody").append(row.clone());
    $("tbody tr:last input").val('');
    $("tbody tr:last .bootstrap-switch").remove();
    $("tbody tr:last [data-show]").attr('data-show', latLng.lat()+','+latLng.lng());
    $("tbody tr:last [name*='lat']").val(latLng.lat());
    $("tbody tr:last [name*='lng']").val(latLng.lng());
  }
  $(document).ready(function(){
    mrk = [];
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
  })
</script>