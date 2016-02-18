<div class="jumbotron">
  <h1><b class="text-primary">Master key </b></h1>
  <form action="<?php echo URL ?>?step=ready" method="post">
    <div class="row">
      <h3>Please store this secret key</h3>
      <p>If you lose administrator access you can recover it only if you have master key</p>
      <div class="form-group">
        <textarea readonly rows="2" class="form-control text-center"><?php echo $secret;?></textarea>
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
        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="6" style="width: 75%;">
        </div>
      </div>
    </div>
  </form>

  <div class="clearfix"></div>
</div>