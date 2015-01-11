<?php

session_start();
$json = json_decode(file_get_contents('config.json'));
$status = (int) shell_exec("gpio -g read 13");
$rtmp = $json->temperature;
$mode = $json->mode;
?>
<html>
  <head>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.1/themes/ui-lightness/jquery-ui.css"/>
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
    <script src="jquery.ui.touch-punch.min.js"></script>
    <script>
      $(function() {
        $("#slider-vertical").slider({
          orientation: "vertical",
          range: "min",
          min: 60,
          max: 90,
          value: <?php print $rtmp; ?>,
          slide: function(event, ui) {
            $("#amount").val(ui.value);
          }
        });
        $("#amount").val($("#slider-vertical").slider("value"));
      });
    </script>
    <style>
    </style>
  </head>
  <body role="document">
    <div class="container theme-showcase" role="main">
      <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success" role="alert">
          <strong>Updated settings!</strong>
        </div>
        <?php
        unset($_SESSION['message']);
      endif;
      ?>
      <h1>Thermostat</h1>
      <div class="col-sm-4">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Set</h3>
          </div>
          <div class="panel-body">

            <form method=post action=action.php>
              <p>
                <label for="amount">Temperature</label>
                <input value="<?php print $rtmp; ?>" name="variable[temperature]" type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;">
              </p>
              <div id="slider-vertical" style="height:200px;"></div>
              <button type="submit" class="btn btn-lg btn-default">Set</button>
            </form>
          </div>
        </div>

      </div>
      <div class="col-sm-4">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Status</h3>
          </div>
          <div class="panel-body">
            <h1>
              <?php
              $temp = 0;
              while ($temp < 1) {
                $current_temp = shell_exec('./temper');
                $temp = floatval($current_temp);
              }
              $temp = ($temp * 9 / 5) + 32;
              ?>
              <?php print "$temp&deg; "; ?>
              <?php if ($status): ?>
                <span class="label label-success">Running</span>
              <?php else: ?>
                <span class="label label-default">Off</span>
              <?php endif; ?>
            </h1>

          </div>
        </div>
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title">Mode</h3>
          </div>
          <div class="panel-body">
            <form method=post action=action.php>
              <h1>
                <?php if ($mode != 'heat'): ?>
                  <button name="variable[mode]" value="heat" type="submit" class="btn btn-lg btn-default">Heat</button>
                <?php endif; ?>
                <?php if ($mode == 'cool'): ?>
                  <span class="label label-primary">Cool</span>
                <?php endif; ?>
                <?php if ($mode == 'heat'): ?>
                  <span class="label label-danger">Heat</span>
                <?php endif; ?>
                <?php if ($mode != 'cool'): ?>
                  <button name="variable[mode]" value="cool" type="submit" class="btn btn-lg btn-default">Cool</button>
                <?php endif; ?>
                <button name="variable[mode]" value="off" type="submit" class="btn btn-lg btn-default">Off</button>
              </h1>
            </form>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
