<?php
// prevents this code from being loaded directly in the browser
// or without first setting the necessary object
if(!isset($geoData)) {
  // redirect_to(url_for('/staff/geoDatas/index.php'));
  redirect_to('/index.php');
}
?>

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
  <dt>Attitude</dt>
  <dd><input class="grayout" style="background-color:#999;" type="text" name="geoData[attitude]" disabled value="<?php echo h(number_format($geoData->attitude, 4)); ?>" /></dd>
</dl>
