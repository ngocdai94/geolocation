<?php
    $MAP_API_KEY = 'AIzaSyDcHfA2WqTubyiS9ABL3Qi8y7xZkf3-s9c';
    $LAT = $_GET["LAT"];
    $LONG = $_GET["LONG"];

    // The URL that we want to GET.
    $url = 'https://maps.googleapis.com/maps/api/elevation/json?locations=' . $LAT . ',' . $LONG . '&key=' . $MAP_API_KEY;

    // Use file_get_contents to GET the URL in question.
    $elevation = file_get_contents($url);
    $elevationJSON = json_decode($elevation, true);

    // If $contents is not a boolean FALSE value.
    if($elevation !== false){
        //Print out the contents.
        // echo '<br><pre>';
        //     echo var_dump($elevation);
        //     echo var_dump($elevationJSON);
        // echo '<br></pre>';
        echo $elevationJSON["results"][0]["elevation"];
    }
?>