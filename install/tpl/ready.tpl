<div class="jumbotron">
  <h1><b class="text-success">Congratulations! </b></h1>
    <div class="row">
      <p>You successfully install new admin panel. Your next steps is:</p>
      <ol>
        <li>Remove <i>install</i> folder <span class="label label-danger">Important!</span></li>
        <li>Your api host for applications is <b><?php echo str_replace('http://', '', HOST) ?></b></li>
        <li>Your login to admin panel is <b>hero@foodeliveryapp.com</b></li>
        <li>Your password to admin panel is <b>youAreHero</b></li>
        <li>Configure your SMTP settings to email works</li>
        <li>Fill catalog and products</li>
        <li>Enjoy!</li>
      </ol>
      <div class="text-center">
        <a href="<?php echo HOST ?>/" class="btn btn-lg btn-success"><b>Goto admin panel</b></a>
      </div>
    </div>
    <div class="row">
      <p>Overall progress</p>
      <div class="progress">
        <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="6" style="width: 100%;">
        </div>
      </div>
    </div>

  <div class="clearfix"></div>
</div>