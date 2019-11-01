<?php
  // load PHP Excel
  include('private/initialize.php');
  use SimpleExcel\SimpleExcel;

  $message = "";
  if(isset($_POST['submit'])){ //check if form was submitted
    $input = $_POST['fileToUpload']; //get input text
    $message = "Success! You entered: ".$input;
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
    <form action="upload.php" method="POST" enctype="multipart/form-data">
      Select file to upload:
      <input type="file" name="fileToUpload" id="fileToUpload">
      <input type="submit" value="Upload File" name="submit">
    </form>

    <main>
      <p>Testing Query From MySQL Database</p>

      <table id="mysql">
        <tr>
          <th>ID</th>
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
          <th>&nbsp;</th>
        </tr>

        <?php 
          $geo_data = Geolocation::find_all();
        ?>

        <?php 
          foreach($geo_data as $data) {
            $temp_id = 0;
            if ($data->latitude == 0 || $data->longitude == 0)  {
              $temp_id = $data->id;
              $data->latitude=$data->convertLat();
              $data->longitude=$data->convertLong();
              $data->save();
            }
          ?>
        <tr>
          <td><?php echo h($data->id); ?></td>
          <td><?php echo h($data->lat_degree); ?></td>
          <td><?php echo h($data->lat_minute); ?></td>
          <td><?php echo h($data->lat_seconds); ?></td>
          <td><?php echo h($data->lat_direction); ?></td>

          <td><?php echo h($data->long_degree); ?></td>
          <td><?php echo h($data->long_minute); ?></td>
          <td><?php echo h($data->long_seconds); ?></td>
          <td><?php echo h($data->long_direction); ?></td>

          <td><?php echo h(number_format($data->latitude, 4)); ?></td>
          <td><?php echo h(number_format($data->longitude, 4)); ?></td>
          <!-- <td><a href="methods/edit.php?id=<?php //echo h(u($data->id)); ?>">View</a></td> -->
          <td><a href="methods/edit.php?id=<?php echo h(u($data->id)); ?>">Edit</a></td>
          <td><a href="methods/delete.php?id=<?php echo h(u($data->id)); ?>">Delete</a></td>
        </tr>
        <?php } ?>
      </table>

      <div>

      </div>
    </main>
  </body>
</html>