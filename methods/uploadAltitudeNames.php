<?php
    require_once('../private/initialize.php');

    $i = 0;
    $locations_altitudes = $_GET["altitudes"];
    $location_names = $_GET["names"];

    // echo var_dump($locations_altitudes);
    // echo var_dump($location_names);

    // All data in the MySQL table
    $all_geo_data = Geolocation::find_all();

    // Reverse all the geolocation data to Latitude and Longitude when it is first uploaded.
    foreach ($all_geo_data as $data) {
        $geoData = Geolocation::find_by_id($data->id);

        // Merge all the attribute and save to the MySQL Database
        $args = array (
            "name" => $location_names[$i],
            "altitude" => $locations_altitudes[$i]);
        $geoData->merge_attributes($args);
        $result=$geoData->save();
        $i++;

    }
?>