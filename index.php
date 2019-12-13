<?php require_once('private/initialize.php'); ?>
<?php
  // load PHP Excel
  use SimpleExcel\SimpleExcel;

  // Initilize Pagination Variable and Get Number of Items in MySQL Database
  $current_page = $_GET['page'] ?? 1;
  $per_page = 25;
  $total_count = Geolocation::count_all();

  // Instantiate a pagination object
  $pagination = new Pagination($current_page, $per_page, $total_count);

  // Find all geolocation data by using pagination 
  // instead of $geo_data = Geolocation::find_all();
  $sql = "SELECT * FROM geolocation_data ";
  $sql .= "LIMIT {$per_page} ";
  $sql .= "OFFSET {$pagination->offset()}";
  $geo_data = Geolocation::find_by_sql($sql);

  // All data in the MySQL table
  $all_geo_data = Geolocation::find_all();

  if (isset($_POST['upload'])) { //check if form was submitted
    $input = $_POST['upload_file']; //get input text
    // $message = "Success! You uploaded: " . $input;

    $target_dir = "uploads/";
    $isUpload = false;
    $size = 0;
    $target_file = $target_dir . basename($_FILES["upload_file"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    // Check file size
    if ($_FILES["upload_file"]["size"] > 500000) {
      echo "Sorry, your file is too large.";
      $uploadOk = 0;
    }
    // Allow certain file formats
    if($fileType != "xlsx" && $fileType != "csv") {
      echo "Sorry, only XLSX and CSV files are allowed.";
      $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
      echo "Sorry, your file was not uploaded.";

    // if everything is ok, try to upload file
    } else {
      if (move_uploaded_file($_FILES["upload_file"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["upload_file"]["name"]). " has been uploaded.";
        $isUpload = true;
      } else {
        echo "Sorry, there was an error uploading your file.";
      }
    }

    if ($isUpload) {
      $size = $_FILES["upload_file"]["size"];
    
      // erase content in database
      $deleteAllRecord = Geolocation::deleteAllRecords();

      // upload new data
      $file = fopen($target_file, "r");
      while (($mapData = fgetcsv($file, $size, ",")) !== FALSE)
      {
        $sql = "INSERT INTO " . 
          "geolocation_data(
            lat_degree,
            lat_minute,
            lat_seconds,
            lat_direction,
            long_degree,
            long_minute,
            long_seconds,
            long_direction) " . 
          "values(
            '$mapData[2]',
            '$mapData[3]',
            '$mapData[4]',
            '$mapData[5]',
            '$mapData[6]',
            '$mapData[7]',
            '$mapData[8]',
            '$mapData[9]')";
        $database->query($sql);
      }
      fclose($file);
      redirect_to('/index.php');
    }

    // Reverse all the geolocation data to Latitude and Longitude when it is first uploaded.
    foreach ($all_geo_data as $data) {
      $temp_id = 0;
      if ($data->latitude == 0 || $data->longitude == 0) {
        $temp_id = $data->id;
        $data->latitude = $data->convertLat();
        $data->longitude = $data->convertLong();
        $data->save();
      }
    }
  }
?>


<!DOCTYPE html>
<html lang="en-us">

<head>
  <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
  <meta charset="utf-8">
  <title>Reverse Geocoding</title>
  <link rel="stylesheet" type="text/css" media="screen" href="/shared/css/main.css" />
</head>

<body>
  <main>
    <section class="mysql">
      <h2>Testing Queries From MySQL Database</h2>
      <table>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Lat_Degree</th>
          <th>Lat_Minute</th>
          <th>Lat_Seconds</th>
          <th>Lat_Direction</th>
          <th>Long_Degree</th>
          <th>Long_Minute</th>
          <th>Long_Seconds</th>
          <th>Long_Direction</th>
          <th>LAT</th>
          <th>LONG</th>
          <th>Attitude</th>
          <th>&nbsp;</th>
        </tr>

        <?php
          // $geo_data = Geolocation::find_all();
          // has been intergrated with Pagination Class and moved to the top
        ?>

        <?php
        foreach ($geo_data as $data) {
          $temp_id = 0;
          if ($data->latitude == 0 || $data->longitude == 0) {
            $temp_id = $data->id;
            $data->latitude = $data->convertLat();
            $data->longitude = $data->convertLong();
            $data->save();
          }
        ?>
          <tr>
            <td><?php echo h($data->id); ?></td>
            <td><?php echo h($data->name); ?></td>

            <td><?php echo h($data->lat_degree); ?></td>
            <td><?php echo h($data->lat_minute); ?></td>
            <td><?php echo h($data->lat_seconds); ?></td>
            <td><?php echo h($data->lat_direction); ?></td>

            <td><?php echo h($data->long_degree); ?></td>
            <td><?php echo h($data->long_minute); ?></td>
            <td><?php echo h($data->long_seconds); ?></td>
            <td><?php echo h($data->long_direction); ?></td>

            <td class="lat"><?php echo h(number_format($data->latitude, 4)); ?></td>
            <td class="long"><?php echo h(number_format($data->longitude, 4)); ?></td>

            <td><?php echo h($data->attitude); ?></td>

            <td><input class="reverseGeocode" type="button" value="Reverse Geocode"></td>
            <!-- <td><a href="methods/edit.php?id=<?php //echo h(u($data->id)); ?>">Edit</a></td> -->
            <!-- <td><a href="methods/delete.php?id=<?php //echo h(u($data->id)); ?>">Delete</a></td> -->
            <td><input class="ultiliti_buttons" type="button" value="Edit" onclick="window.location.href='methods/edit.php?id=<?php echo h(u($data->id)); ?>'"></td>
            <td><input class="ultiliti_buttons" type="button" value="Delete" onclick="window.location.href='methods/delete.php?id=<?php echo h(u($data->id)); ?>'"></td>
          </tr>
        <?php } ?>
      </table>

      <!-- Pagination Links -->
      <?php 
        echo $pagination->page_links($_SERVER['PHP_SELF']);
      ?>

      <!-- Buttons -->
      <div class="button_wrapper">
        <input class="ultiliti_buttons" type="button" value="Add a New Data" onclick="window.location.href='methods/add.php'">
      </div>

      <div>
          <button type="button">Reverse All Data from MySQL</button>
      </div>

      <div class="button_wrapper">
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
          Select a file to upload:
          <input type="file" name="upload_file" id="upload_file">
          <input type="submit" value="Upload and Replace Table" name="upload">
        </form>
      </div>
    </section>

    <!-- Google Maps -->
    <section class="map">
        <div id="map"></div>
    </section>

    <section>
      <div class="left">
        <h2>Get Reverse Geolocation by Longitude & Latitude</h2>
        <div>
          <form class="form-horizontal" role="form">
            <h4>DD (decimal degrees)*</h4>
            <div class="form-group">
              <label class="col-md-3 control-label" for="latitude">Latitude</label>
              <div class="col-md-9">
                <input id="latitude" class="form-control" type="text">
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-3 control-label" for="longitude">Longitude</label>
              <div class="col-md-9">
                <input id="longitude" class="form-control" type="text">
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-4">
                <button type="button" class="btn btn-primary" onclick="codeLatLng(1)">Get Address</button>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-3 control-label" for="longitude">Lat,Long</label>
              <div class="col-md-9">
                <input id="latlong" class="form-control selectall" type="text">
              </div>
            </div>

          </form>
        </div>

        <div>
          <form class="form-horizontal" role="form">
            <h4>DMS (degrees, minutes, seconds)*</h4>
            <div class="form-group">
              <label class="col-md-3 control-label" for="latitude">Latitude</label>
              <div class="col-md-9">
                <label class="radio-inline">
                  <input type="radio" name="latnordsud" value="nord" id="nord" checked="">
                  N
                </label>
                <label class="radio-inline">
                  <input type="radio" name="latnordsud" value="sud" id="sud">
                  S
                </label>

                <input class="form-control sexagesimal" id="latitude_degres" type="text">
                <label for="latitude_degres">°</label>
                <input class="form-control sexagesimal" id="latitude_minutes" type="text">
                <label for="latitude_minutes">'</label>
                <input class="form-control sexagesimalsec" id="latitude_secondes" type="text">
                <label for="latitude_secondes">''</label>
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-3 control-label" for="longitude">Longitude</label>
              <div class="col-md-9">
                <label class="radio-inline">
                  <input type="radio" name="lngestouest" value="est" id="est" checked="">
                  E
                </label>
                <label class="radio-inline">
                  <input type="radio" name="lngestouest" value="ouest" id="ouest">
                  W
                </label>

                <input class="form-control sexagesimal" id="longitude_degres" type="text">
                <label for="longitude_degres">°</label>


                <input class="form-control sexagesimal" id="longitude_minutes" type="text">
                <label for="longitude_minutes">'</label>
                <input class="form-control sexagesimalsec" id="longitude_secondes" type="text">
                <label for="longitude_secondes">''</label>
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-4">
                <button type="button" class="btn btn-primary" onclick="dmsversdd()">Get Address</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </section>
    
    <br><br>
  </main>
  
  <footer></footer>

  <script defer src="/shared/js/googleGeolocation_MySQL.js"></script>
  <script defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDcHfA2WqTubyiS9ABL3Qi8y7xZkf3-s9c&libraries=places&callback=initMap"></script>
</body>

</html>