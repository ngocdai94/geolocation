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
  </body>
</html>