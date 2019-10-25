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
  </head>
  <body>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
      Select file to upload:
      <input type="file" name="fileToUpload" id="fileToUpload">
      <input type="submit" value="Upload File" name="submit">
    </form>

    <main>
      <p>Testing Query From MySQL Database</p>

      <table>
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
          <th>Long_Direction</th>
          <th>Long_Direction</th>
          <th>&nbsp;</th>
        </tr>

        <?php 
          $geo_data = Geolocation::find_all();
        ?>

        <?php foreach($geo_data as $data) { ?>
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
          <td>&nbsp;</td>
          <td>&nbsp;</td>
          <!-- <td><a href="geo_actions/edit.php?id=<?php //echo $data->id; ?>">View</a></td> -->
          <td><a href="methods/edit.php?id=<?php echo h(u($data->id)); ?>">Edit</a></td>
        </tr>
        <?php } ?>
      </table>

      <div>

      </div>
    </main>
  </body>
</html>