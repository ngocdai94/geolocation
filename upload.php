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

      <div id="map"></div>
      <script>
        function initMap() {
          let map = new google.maps.Map(document.getElementById('map'), {
            zoom: 8,
            center: {lat: 40.731, lng: -73.997}
          });
          let geocoder = new google.maps.Geocoder;
          let infowindow = new google.maps.InfoWindow;

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
                map.setZoom(16);
                map.setCenter(latlng);
                let marker = new google.maps.Marker({
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
  </body>
</html>
