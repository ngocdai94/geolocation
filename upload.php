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
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #map {
          display: block;
          height: 70%;
          width: 80%;
          margin: 0 auto;
          margin-top: 5%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #floating-panel {
        /* position: absolute; */
        top: 10px;
        left: 25%;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
        text-align: center;
        font-family: 'Roboto','sans-serif';
        line-height: 30px;
        padding-left: 10px;
      }
      #floating-panel {
        /* position: absolute; */
        top: 5px;
        left: 50%;
        /* margin-left: -180px; */
        width: 350px;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
      }
      #latlng {
        width: 225px;
      }
    </style>
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
        <section>
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
        </section>

        <section>
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
        </section>

        <aside>
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
        </aside>

        <section>
          <div id="map"></div>
        </section>
      </main>
      <script>
        let map;
        let geocoder;
        let infowindow;
        let marker = null;

        function initMap() {
          map = new google.maps.Map(document.getElementById('map'), {
            zoom: 8,
            center: {lat: 40.731, lng: -73.997}
          });
          geocoder = new google.maps.Geocoder;
          infowindow = new google.maps.InfoWindow;

          // document.getElementByID('submit').addEventListener('click', function() {
          //   geocodeLatLng(geocoder, map, infowindow);
          // });
          let classSubmit = document.getElementsByClassName('submit');
          let classLatLng = document.getElementsByClassName('latlng');
          for (let i = 0; i < classSubmit.length; i++) {
            classSubmit[i].addEventListener('click', function() {
              geocodeLatLng(geocoder, map, infowindow, classLatLng[i].value);
            });
          }
        }

        function geocodeLatLng(geocoder, map, infowindow, input) {
          // let input = document.getElementById('latlng').value;
          let latlngStr = input.split(',', 2);
          let latlng = {lat: parseFloat(latlngStr[0]), lng: parseFloat(latlngStr[1])};
          geocoder.geocode({'location': latlng}, function(results, status) {
            if (status === 'OK') {
              console.log(results);
              if (results[0]) {
                map.setZoom(17);
                map.setCenter(latlng);
                marker = new google.maps.Marker({
                  position: latlng,
                  map: map
                });
                infowindow.setContent(results[0].formatted_address);
                infowindow.open(map, marker);
              } else {
                window.alert('No results found');
              }
            } else {
              window.alert('Geocoder failed due to: ' + status);
            }
          });
        }
      </script>
      <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCdsim6obIAp8R4-uVq6H_U1GcZEUr6CxE&libraries=places&callback=initMap">
      </script>


      <script>
        function codeLatLng(origin) {
            var lat = parseFloat(document.getElementById("latitude").value) || 0;
            var lng = parseFloat(document.getElementById("longitude").value) || 0;
            if (lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180) {
                var latlng = new google.maps.LatLng(lat, lng);
                if (origin == 1) ddversdms();
                
                map.setCenter(latlng); // NEED TO FIX THIS TO LINK TO THE CURRENT MAP
                map.setZoom(17);

                if (marker != null) marker.setMap(null);
                marker = new google.maps.Marker({
                    map: map,
                    position: latlng
                });
                myReverseGeocode(lat, lng, "");
                fromPlace = 0
            } else alert(trans.InvalidCoordinatesShort)
        }

        function dmsversdd() {
          var lat, lng, nordsud, estouest, latitude_degres, latitude_minutes, latitude_secondes, longitude_degres, longitude_minutes, longitude_secondes;
          if (document.getElementById("sud").checked) nordsud = -1;
          else nordsud = 1;
          if (document.getElementById("ouest").checked) estouest = -1;
          else estouest = 1;
          latitude_degres = parseFloat(document.getElementById("latitude_degres").value) || 0;
          latitude_minutes = parseFloat(document.getElementById("latitude_minutes").value) || 0;
          latitude_secondes = parseFloat(document.getElementById("latitude_secondes").value) || 0;
          longitude_degres = parseFloat(document.getElementById("longitude_degres").value) || 0;
          longitude_minutes = parseFloat(document.getElementById("longitude_minutes").value) || 0;
          longitude_secondes = parseFloat(document.getElementById("longitude_secondes").value) || 0;
          lat = nordsud * (latitude_degres + latitude_minutes / 60 + latitude_secondes / 3600);
          lng = estouest * (longitude_degres + longitude_minutes / 60 + longitude_secondes / 3600);
          document.getElementById("latitude").value = Math.round(lat * 1e7) / 1e7;
          document.getElementById("longitude").value = lng;
          setTimeout(codeLatLng(2), 1e3)
        }

        function ddversdms() {
          var lat, lng, latdeg, latmin, latsec, lngdeg, lngmin, lngsec;
          lat = parseFloat(document.getElementById("latitude").value) || 0;
          lng = parseFloat(document.getElementById("longitude").value) || 0;
          if (lat >= 0) document.getElementById("nord").checked = true;
          if (lat < 0) document.getElementById("sud").checked = true;
          if (lng >= 0) document.getElementById("est").checked = true;
          if (lng < 0) document.getElementById("ouest").checked = true;
          lat = Math.abs(lat);
          lng = Math.abs(lng);
          latdeg = Math.floor(lat);
          latmin = Math.floor((lat - latdeg) * 60);
          latsec = Math.round((lat - latdeg - latmin / 60) * 1e3 * 3600) / 1e3;
          lngdeg = Math.floor(lng);
          lngmin = Math.floor((lng - lngdeg) * 60);
          lngsec = Math.floor((lng - lngdeg - lngmin / 60) * 1e3 * 3600) / 1e3;
          document.getElementById("latitude_degres").value = latdeg;
          document.getElementById("latitude_minutes").value = latmin;
          document.getElementById("latitude_secondes").value = latsec;
          document.getElementById("longitude_degres").value = lngdeg;
          document.getElementById("longitude_minutes").value = lngmin;
          document.getElementById("longitude_secondes").value = lngsec
        }

        function myReverseGeocode(latToGeocode, lngToGeocode, intro) {
          latToGeocode = parseFloat(latToGeocode);
          lngToGeocode = parseFloat(lngToGeocode);
          // if (latToGeocode >= -90 && latToGeocode <= 90 && lngToGeocode >= -180 && lngToGeocode <= 180) {
          //     $.ajax({
          //         type: "GET",
          //         url: "https://api.opencagedata.com/geocode/v1/json?q=" + latToGeocode + "+" + lngToGeocode + "&key=" + trans.OpenKey + "&no_annotations=1&language=" + trans.Locale,
          //         dataType: "json",
          //         success: function(data) {
          //             if (data.status.code == 200) {
          //                 if (data.total_results >= 1) {
          //                     if (intro == -1) geolocAddr = data.results[0].formatted;
          //                     updateAll(intro, data.results[0].formatted, latToGeocode, lngToGeocode)
          //                 } else {
          //                     if (intro == -1) geolocAddr = trans.NoResolvedAddress;
          //                     updateAll(intro, trans.NoResolvedAddress, latToGeocode, lngToGeocode)
          //                 }
          //             } else {
          //                 if (intro == -1) geolocAddr = trans.GeocodingError;
          //                 updateAll(intro, trans.InvalidCoordinates, latToGeocode, lngToGeocode)
          //             }
          //         },
          //         error: function(xhr, err) {
          //             updateAll(trans.Geolocation, trans.InvalidCoordinates, latToGeocode, lngToGeocode)
          //         }
          //     }).always(function() {
          //         if (intro == -1) initializeMap()
          //     });
          //     return false
          // } else alert(trans.InvalidCoordinatesShort)
        }
      </script>
  </body>
</html>
