<?php include "private/initialize.php"?>
<?php include "private/config.php"?>
<?php include "shared/php/header.php"?>
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

    $target_dir = "uploads/";
    $isUpload = false;
    $uploadOk = 1;
    $size = 0;
    $target_file = $target_dir . basename($_FILES["upload_file"]["name"]);
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
      redirect_to('/');
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
        <section id="Home">
            <h2>Welcome to Geolocation Reversing Web Application!</h2>
            <div>
                <h3>How to navigate through the Web App?</h3>
                <div>
                    <h4>MySQL Database Page</h4>
                    <p>The MySQL Database tab on the left will load all the data in the current MySQL Database. The MySQL Database page will look like the <i>Figure 1</i> below. </p>
                    <figure>
                        <img src="/shared/image/1.PNG" alt="MySQL Database">
                        <figcaption>1. MySQL Database Page Navigation</figcaption>
                    </figure>
                    <p>Each row of the MySQL data table has three buttons, Reverse Geocode, Edit, and Delete. The Reverse Geocode button will reverse individual data geolocation on the embedded Google Maps below the table. The Edit button is used to edit any properties of the data excepts for the LAT, LONG, and Altitude since these datas are calcualted base on  Degree, Minutes, and Seconds from Latitude and Longitude. And of course, the Delete button will delete the current row in the table. Rest asure, there will be a delete comfimation prompt incase the Delete button is clicked accidently.</p>
                    <p>Another three buttons are Refresh, Add a New Data, and Reverse All Data from MySQL. The refresh button will refresh the current Database in case there is some network lattency causing missing data on the table. The Add a new Data button will add a new data to the MySQL Database. The Reverse All Data from MySQL will reverse all geolocation data in the MySQL and display on the Google Map.</p>
                    <p>Finally, the Upload and Replace Table will delete and upload the entire MySQL Database. However, to use this button, a file must be formatted in .csv format. Furthermore, the structure/column of .csv file has to match the MySQL Database column name. One important thing to note is that the process to clear and update the entire MySQL Database might take quite a bit of time due to network latency to the Web Server. The <i>Figure 2</i> shows the current column names in the MySQL Database. </p>
                    <figure>
                        <img src="/shared/image/2.PNG" alt="MySQL Database Column Names">
                        <figcaption>2. MySQL Database Column Names</figcaption>
                    </figure>
                    <p>A sample geolocation data, which is called <a href="https://raw.githubusercontent.com/ngocdai94/geolocation/master/geo_data-6.csv">geo_data-6</a>, is prepared to test the web application. The file can be downloaded to a computer by right clicking to it and choose "Save link as..." Since this is a developer account, there is a free limit on how many requests to the Google Geolocation service per day. If the requests exceeds, the web app will not be able to request from Google services, or there will be a charge billed to the web app owner.</p>
                </div>
            </div>

            <div>
                <h4>DMS Geolocation</h4>
                <p>The DMS Geolocation page is a tool to lookup any address from latitude and longitude decimal degree (DD) or degree, minutes, and seconds (DMS). The <i>Figure 3</i> shows how the page looks like.</p>
                <figure>
                    <img src="/shared/image/3.PNG" alt="MySQL Database Column Names">
                    <figcaption>3. Geolocation Tool</figcaption>
                </figure>
                <p>At the first sight, the DMS Geolocation page shows some input fields and another embedded Google Maps, which will display the address location result from any clicked buttons from the left.</p>
                <p>The Get Current GPS Coordinates will get the current GPS address from where it is requested from. The Get Address from DD will reverse geolocation from Latitude and Longitude input fields. The Address and Lat,Long text input field is grayed out since they are use to display text only. The Address text input field is under contruction on implementing the Autocomplete feature from the Google Maps API. When the contruction finishes, the Address input filed will be back to normal. Similar to the Get Address from DD button, the Get Address from DMS will reverse geolocation from DMS to actual location and display on the Google Maps on the right</p>
                <p>These three buttons are hiddenly linked together. For example, when clicking to the Get Current GPS Coordinates, the web application will autofill DD (decimal degree) and DMS (degree, minutes, seconds) sections.</p>
            </div>
        </section>

        <section id="MySQL_Database">
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
        </section>

        <section id="DMS_Geo_Calculation">
            <div class="grid-container"> 
                <div class="grid-item">
                    <section class="left">
                        <h2>Get Reverse Geolocation by Longitude & Latitude</h2>
                        <div>
                            <form class="form-horizontal" role="form">
                            <h4>Address</h4>
                                <div class="form-group">
                                    <div class="col-md-12 addressWrapper">
                                        <input id="address" class="form-control" type="text" value="" disabled>
                                        <div id="resultsWrapper">
                                            <div id="results" class="mapSearchResults"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6">
                                    <button type="button" class="btn btn-primary" onclick="codeAddress()">Get Current GPS Coordinates</button>
                                    </div>
                                </div>
                            </form>
                            
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
                                    <label class="col-md-3 control-label" for="longitude">Lat,Long</label>
                                    <div class="col-md-9">
                                        <input id="latlong" class="form-control selectall" type="text" disabled>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-primary" onclick="codeLatLngEmbedded()">Get
                                            Address from DD</button>
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
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-primary" onclick="dmsversdd()">Get
                                            Address from DMS</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </section>
                </div>

                <div class="grid-item">
                    <div id="mapEmbedded"></div>
                </div>
            </div>
        </section>
    </main>

<?php include "shared/php/footer.php"?>
