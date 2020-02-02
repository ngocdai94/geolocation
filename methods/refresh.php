<?php include "../private/initialize.php"?>
<?php 
// Initilize Pagination Variable and Get Number of Items in MySQL Database
$current_page = $_GET['page'] ?? 1;
$per_page = 25;
$total_count = Geolocation::count_all();

// Instantiate a pagination object
$pagination = new Pagination($current_page, $per_page, $total_count);

// Limit number of data rows per page
$sql = "SELECT * FROM geolocation_data ";
$sql .= "LIMIT {$per_page} ";
$sql .= "OFFSET {$pagination->offset()}";
$geo_data = Geolocation::find_by_sql($sql);

echo '
    <table class="table table-striped table-sm">
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
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
        <th>Altitude</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
      </tr>
    </thead>

    <tbody>';
    foreach ($geo_data as $data) {
        echo '<tr>
            <td>' . h($data->id) . '</td>';
        echo  '<td class="locationName">' . h($data->name) . '</td>';
        echo  '<td>' . h($data->lat_degree) . '</td>';
        echo  '<td>' . h($data->lat_minute) . '</td>';
        echo  '<td>' . h($data->lat_seconds) . '</td>';
        echo  '<td>' . h($data->lat_direction) . '</td>';

        echo  '<td>' . h($data->long_degree) . '</td>';
        echo  '<td>' . h($data->long_minute) . '</td>';
        echo  '<td>' . h($data->long_seconds) . '</td>';
        echo  '<td>' . h($data->long_direction) . '</td>';

        echo  '<td class="lat">' . h(number_format($data->latitude, 4)) . '</td>';
        echo  '<td class="long">' . h(number_format($data->longitude, 4)) . '</td>';

        echo  '<td>' . h(number_format($data->altitude, 2)) . 'm' . '</td>';

        echo  '<td><input class="btn btn-sm btn-outline-secondary reverseGeocode" type="button" value="Reverse Geocode"></td> ';
        echo  '<td><input class="btn btn-sm btn-outline-secondary" type="button" value="Edit" onclick="window.location.href='. '/methods/edit.php?id=' . h(u($data->id)) . "'" . '"' . '></td>';
        echo   '<td><input class="btn btn-sm btn-outline-secondary" type="button" value="Delete" onclick="window.location.href=' . '/methods/delete.php?id=' . h(u($data->id)) . "'" . '"' . '></td>';
        echo  '</tr>';
    }
echo '
    </tbody>
  </table>';

// echo $pagination->page_links($_SERVER['PHP_SELF']);
// echo $pagination->page_links('/tabs/mysql-geolocation.php');
?>