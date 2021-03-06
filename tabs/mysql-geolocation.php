<?php include "../private/initialize.php"?>
<?php include "../private/config.php"?>
<?php include "../shared/php/header.php"?>
<?php
  // load PHP Excel
  // use SimpleExcel\SimpleExcel;

  //// Initilize Pagination Variable and Get Number of Items in MySQL Database
  $current_page = $_GET['page'] ?? 1;
  $per_page = 25;
  $total_count = Geolocation::count_all();

  //// Instantiate a pagination object
  $pagination = new Pagination($current_page, $per_page, $total_count);

  if (isset($_POST['upload'])) { //check if form was submitted
    $input = $_POST['upload_file']; //get input text
    // $message = "Success! You uploaded: " . $input;

    $target_dir = "../uploads/";
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
      redirect_to('/tabs/mysql-geolocation.php');
    }
  }

  // All data in the MySQL table
  $all_geo_data = Geolocation::find_all();

  // Reverse all the geolocation data to Latitude and Longitude when it is first uploaded.
  foreach ($all_geo_data as $data) {
    if ($data->latitude == 0 || $data->longitude == 0)
    {
      $geoData = Geolocation::find_by_id($data->id);
      
      // Find actual Latitude, Longitude and Attitude
      $data->latitude = $data->convertLat();
      $data->longitude = $data->convertLong();

      // Merge all the attribute and save to the MySQL Database
      $args = array (
        "name" => '',
        "lat_degree" => $data->lat_degree,
        "lat_minute" => $data->lat_minute,
        "lat_seconds" => $data->lat_seconds,
        "lat_direction" => $data->lat_direction,
        "long_degree" => $data->long_degree,
        "long_minute" => $data->long_minute,
        "long_seconds" => $data->long_seconds,
        "long_direction" => $data->long_direction,
        "latitude" => $data->latitude,
        "longitude" => $data->longitude,
        "altitude" => 0);
      $geoData->merge_attributes($args);
      $result=$geoData->save();
    }
  }

  //// Limit number of data rows per page

  // $sql = "SELECT * FROM geolocation_data ";
  // $sql .= "LIMIT {$per_page} ";
  // $sql .= "OFFSET {$pagination->offset()}";
  // $geo_data = Geolocation::find_by_sql($sql);
?>
  <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    <section class="mysql">
      <h2>Data From MySQL Database</h2>

      <div id="mysql-table" class="table-responsive">
          <!-- MySQL Table Will Load Here -->
      </div>
      
      <!-- Pagination Links -->
      <?php 
          echo $pagination->page_links("");
      ?>

      <!-- Hidden Fields -->
      <div id="allLatLongMySQL" class="hidden">
          <table>
          <?php
            foreach ($all_geo_data as $data) {
          ?>
            <tr>
              <td class="latAll"><?php echo h(number_format($data->latitude, 4)); ?></td>
              <td class="longAll"><?php echo h(number_format($data->longitude, 4)); ?></td>
            </tr> 
          <?php } ?>
          </table>
      </div>

      <div id="nameAndAltitude" class="hidden"></div>
      <!-- Hidden Fields Ends-->

      <!-- Buttons -->
      <div class="button_wrapper">
        <input class="btn btn-sm btn-outline-primary" type="button" value="Refresh" onclick="refreshMySQL()">&nbsp;&nbsp;
        <input class="btn btn-sm btn-outline-primary" type="button" value="Add a New Data" onclick="window.location.href='/methods/add.php'">&nbsp;&nbsp;
        <input class="btn btn-sm btn-outline-primary" type="button" value="Reverse All Data from MySQL" onclick="callReverseAll()">
      </div><br>
      
      <div class="input-group button_wrapper">
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
          <div class="input-group">
            <div class="custom-file">
              <input type="file" class="custom-file-input" name="upload_file" id="upload_file">
              <label class="custom-file-label" for="upload_file">Choose a file (.csv) to upload</label>
            </div>
            <div class="input-group-append">
              <input class="btn btn-outline-danger" type="submit" value="Upload and Replace Table" name="upload">
            </div>
          </div>
        </form>
      </div>
    </section>

    <!-- Google Maps -->
    <section class="map">
        <div id="map"></div>
    </section>
  </main>

<?php include "../shared/php/footer.php"?>
