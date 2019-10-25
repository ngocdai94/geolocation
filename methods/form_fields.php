<?php
// prevents this code from being loaded directly in the browser
// or without first setting the necessary object
if(!isset($geoData)) {
  // redirect_to(url_for('/staff/geoDatas/index.php'));
  redirect_to('/index.php');
}
?>

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

<!-- <dl>
  <dt>LAT (gray out)</dt>
  <dd><input type="text" name="geoData[lat]" value="<?php //echo h($geoData->lat); ?>" /></dd>
</dl>

<dl>
  <dt>LONG (gray out)</dt>
  <dd><input type="text" name="geoData[lat]" value="<?php //echo h($geoData->long); ?>" /></dd>
</dl> -->
