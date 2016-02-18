<div class="jumbotron">
  <h1><b class="text-primary">Migration </b></h1>
  <form action="<?php echo URL ?>?step=secret" method="post">
    <div class="row">
      <h3>Did you use previous version Foodelivery with MODx admin panel?</h3>
      <p>
        <label>
          <input type="radio" name="old" value="true"> <b class="text-success">Yes</b>
        </label>
        <label>
          <input type="radio" name="old" checked value="false"> <b class="text-danger">No</b>
        </label>
      </p>
      <h3>Do you want try move your old database to new admin panel?</h3>
      <i><span class="text-success">Clients, managers, products, catalog, filters will be moved</span>. <span class="text-danger">Orders, config (email templates), shop list not moved!</span></i>
      <p>
        <label>
          <input type="radio" name="migrate" value="true"> <b class="text-success">Yes</b>
        </label>
        <label>
          <input type="radio" name="migrate" checked value="false"> <b class="text-danger">No</b>
        </label>
      </p>
    </div>
    <div class="row">
      <div class="col-md-5 col-md-offset-3">
        <p>
          <button type="submit" class="btn btn-lg btn-block btn-success  ">
            <b>Continue installation</b>
            <span class="glyphicon glyphicon-arrow-right"></span>
          </button>
        </p>
      </div>
    </div>
    <div class="row">
      <p>Overall progress</p>
      <div class="progress">
        <div class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="6" style="width: 50%;">
        </div>
      </div>
    </div>
  </form>

  <div class="clearfix"></div>
</div>