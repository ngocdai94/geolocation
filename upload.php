<?php
    include('private/initialize.php');
    use SimpleExcel\SimpleExcel;

    $target_dir = "uploads/";
    $isUpload = false;
    $size = 0;
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    // if(isset($_POST["submit"])) {
    //     $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    //     if($check !== false) {
    //         echo "File is an image - " . $check["mime"] . ".";
    //         $uploadOk = 1;
    //     } else {
    //         echo "File is not an image.";
    //         $uploadOk = 0;
    //     }
    // }

    // Check if file already exists
    // if (file_exists($target_file)) {
    //     echo "Sorry, file already exists.";
    //     $uploadOk = 0;
    // }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
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
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
            $isUpload = true;
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    if ($isUpload) {
        // echo "<br> test <br>" . $target_file;
        $excel = new SimpleExcel('csv');
        $excel->parser->loadFile($target_file);

        $foo = $excel->parser->getField();                  // get complete array of the table
        $bar = $excel->parser->getRow(3);                   // get specific array from the specified row (3rd row)
        $baz = $excel->parser->getColumn(4);                // get specific array from the specified column (4th row)
        $qux = $excel->parser->getCell(2,1);                // get specific data from the specified cell (2nd row in 1st column)

        $col_10 = $excel->parser->getColumn(10);
        $col_11 = $excel->parser->getColumn(11);
        $size = count($col_10);
        echo '<pre>';
        print_r($col_10);                                      // echo the array
        print_r($col_11);
        echo '</pre>';
    }
?>

<!DOCTYPE html>
<html lang="en-us">
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Reverse Geocoding</title>
    <link rel="stylesheet" type="text/css" href="css/main.css">
  </head>
  <body>
      <header style="text-align:center;">
        <a href="/">HOME</a>
      </header>
      <!-- <form action="upload.php" method="POST" enctype="multipart/form-data">
          Select file to upload:
          <input type="file" name="fileToUpload" id="fileToUpload">
          <input type="submit" value="Upload File" name="submit">
      </form> -->

      <main>
        <section class="left">
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
        </section>

        <section class="right">
          <h2>Entities from the input files</h2>
          <div id="floating-panel">
            <?php 
              for ($i = 1; $i < $size; $i++) {
                echo "<div><span>Entity ". $i .": </span>\n";
                echo '<input class="latlng" type="text" value=' . $col_10[$i] . ',' . $col_11[$i] . ">\n";
                echo '<input class="submit" type="button" value="Reverse Geocode"> </div>' . "\n";
              }
            ?>
            <!-- <span>Entity 1: </span>
            <input id="latlng" type="text" value=<?php //echo $col_10[2] . ',' . $col_11[2];?>>
            <input id="submit" type="button" value="Reverse Geocode"> -->
          </div>          
        </section>

        <section class="map">
          <div id="map"></div>
        </section>
      </main>
      
      <script defer src="shared/js/googleGeolocation.js"></script>
      <script defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCdsim6obIAp8R4-uVq6H_U1GcZEUr6CxE&libraries=places&callback=initMap">
      </script>
  </body>
</html>
