<div class="jumbotron">
  <h1><b class="text-primary">Database </b></h1>
  <h3>First of all set up your MySQL credentials</h3>
  <form action="<?php echo URL ?>?step=secret" method="post">
    <div class="row">
      <div class="col-md-6 col-lg-6 col-sm-8 col-md-offset-2">
        <div class="form-group">
          <dl class="dl-horizontal">
            <dt><b>Hostname</b></dt>
            <dd><input type="text" name="host" value="localhost" required placeholder="localhost" class="form-control"></dd>
          </dl>
        </div>
        <div class="form-group">
          <dl class="dl-horizontal">
            <dt><b>Username</b></dt>
            <dd><input type="text" name="user" required placeholder="" class="form-control"></dd>
          </dl>
        </div>
        <div class="form-group">
          <dl class="dl-horizontal">
            <dt><b>Password</b></dt>
            <dd><input type="text" name="pass" placeholder="" class="form-control"></dd>
          </dl>
        </div>
        <div class="form-group">
          <dl class="dl-horizontal">
            <dt><b>Database</b></dt>
            <dd><input type="text" name="base" required placeholder="" class="form-control"></dd>
          </dl>
        </div>
      </div>
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
        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="6" style="width: 25%;">
        </div>
      </div>
    </div>
  </form>

  <div class="clearfix"></div>
</div>