<?php
// prevents this code from being loaded directly in the browser
// or without first setting the necessary object
if(!isset($geoData)) {
  // redirect_to(url_for('/staff/geoDatas/index.php'));
  redirect_to('/index.php');
}
?>

<div class="form-group row">
  <label for="geoData[name]" class="col-sm-2 col-form-label">Location Name</label>
  <div class="col-sm-10">
    <input type="text" class="form-control" name="geoData[name]" value="<?php echo $geoData->name ?>">
  </div>
</div>

<div class="form-group row">
  <label for="geoData[lat_degree]" class="col-sm-2 col-form-label">Latitude Degree</label>
  <div class="col-sm-10">
    <input type="text" class="form-control" name="geoData[lat_degree]" value="<?php echo $geoData->lat_degree ?>">
  </div>
</div>

<div class="form-group row">
  <label for="geoData[lat_minute]" class="col-sm-2 col-form-label">Latitude Minute</label>
  <div class="col-sm-10">
    <input type="text" class="form-control" name="geoData[lat_minute]" value="<?php echo $geoData->lat_minute ?>">
  </div>
</div>

<div class="form-group row">
  <label for="geoData[lat_seconds]" class="col-sm-2 col-form-label">Latitude Seconds</label>
  <div class="col-sm-10">
    <input type="text" class="form-control" name="geoData[lat_seconds]" value="<?php echo $geoData->lat_seconds ?>">
  </div>
</div>

<div class="form-group row">
  <label for="geoData[lat_direction]" class="col-sm-2 col-form-label">Latitude Direction</label>
  <div class="col-sm-10">
    <input type="text" class="form-control" name="geoData[lat_direction]" value="<?php echo $geoData->lat_direction ?>">
  </div>
</div>

<div class="form-group row">
  <label for="geoData[long_degree]" class="col-sm-2 col-form-label">Longitude Degree</label>
  <div class="col-sm-10">
    <input type="text" class="form-control" name="geoData[long_degree]" value="<?php echo $geoData->long_degree ?>">
  </div>
</div>

<div class="form-group row">
  <label for="geoData[long_minute]" class="col-sm-2 col-form-label">Longitude Minute</label>
  <div class="col-sm-10">
    <input type="text" class="form-control" name="geoData[long_minute]" value="<?php echo $geoData->long_minute ?>">
  </div>
</div>

<div class="form-group row">
  <label for="geoData[long_seconds]" class="col-sm-2 col-form-label">Longitude Seconds</label>
  <div class="col-sm-10">
    <input type="text" class="form-control" name="geoData[long_seconds]" value="<?php echo $geoData->long_seconds ?>">
  </div>
</div>

<div class="form-group row">
  <label for="geoData[long_direction]" class="col-sm-2 col-form-label">Longitude Direction</label>
  <div class="col-sm-10">
    <input type="text" class="form-control" name="geoData[long_direction]" value="<?php echo $geoData->long_direction ?>">
  </div>
</div>

<div class="form-group row">
  <label for="geoData[latitude]" class="col-sm-2 col-form-label">LAT</label>
  <div class="col-sm-10">
    <input type="text" readonly class="form-control-plaintext" name="geoData[latitude]" value="<?php echo $geoData->latitude ?>">
  </div>
</div>

<div class="form-group row">
  <label for="geoData[longitude]" class="col-sm-2 col-form-label">LONG</label>
  <div class="col-sm-10">
    <input type="text" readonly class="form-control-plaintext" name="geoData[longitude]" value="<?php echo $geoData->longitude ?>">
  </div>
</div>

<div class="form-group row">
  <label for="geoData[altitude]" class="col-sm-2 col-form-label">Altitude</label>
  <div class="col-sm-10">
    <input type="text" readonly class="form-control-plaintext" name="geoData[altitude]" value="<?php echo $geoData->altitude ?>">
  </div>
</div>

<!-- 
<dl>
  <dt>Name</dt>
  <dd><input class="grayout" style="background-color:#999;" type="text" name="geoData[name]" disabled value="<?php echo $geoData->name ?>" /></dd>
</dl>


<dl>
  <dt>Latitude Degree</dt>
  <dd><input type="text" name="geoData[lat_degree]" value="<?php echo h($geoData->lat_degree); ?>" /></dd>
</dl>

<dl>
  <dt>Latitude Minute</dt>
  <dd><input type="text" name="geoData[lat_minute]" value="<?php echo h($geoData->lat_minute); ?>" /></dd>
</dl>

<dl>
  <dt>Latitude Seconds</dt>
  <dd><input type="text" name="geoData[lat_seconds]" value="<?php echo h($geoData->lat_seconds); ?>" /></dd>
</dl>

<dl>
  <dt>Latitude Direction</dt>
  <dd><input type="text" name="geoData[lat_direction]" value="<?php echo h($geoData->lat_direction); ?>" /></dd>
</dl>

<dl>
  <dt>Longitude Degree</dt>
  <dd><input type="text" name="geoData[long_degree]" value="<?php echo h($geoData->long_degree); ?>" /></dd>
</dl>

<dl>
  <dt>Longitude Minute</dt>
  <dd><input type="text" name="geoData[long_minute]" value="<?php echo h($geoData->long_minute); ?>" /></dd>
</dl>

<dl>
  <dt>Longitude Seconds</dt>
  <dd><input type="text" name="geoData[long_seconds]" value="<?php echo h($geoData->long_seconds); ?>" /></dd>
</dl>

<dl>
  <dt>Longitude  Direction</dt>
  <dd><input type="text" name="geoData[long_direction]" value="<?php echo h($geoData->long_direction); ?>" /></dd>
</dl>

<dl>
  <dt>LAT</dt>
  <dd><input class="grayout" style="background-color:#999;" type="text" name="geoData[latitude]" disabled value="<?php echo h(number_format($geoData->latitude, 4)); ?>" /></dd>
</dl>

<dl>
  <dt>LONG</dt>
  <dd><input class="grayout" style="background-color:#999;" type="text" name="geoData[longitude]" disabled value="<?php echo h(number_format($geoData->longitude, 4)); ?>" /></dd>
</dl>

<dl>
  <dt>Altitude</dt>
  <dd><input class="grayout" style="background-color:#999;" type="text" name="geoData[altitude]" disabled value="<?php echo h(number_format($geoData->altitude, 4)); ?>" /></dd>
</dl> -->
