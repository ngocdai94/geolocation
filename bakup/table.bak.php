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

    <tbody>
    <?php
        foreach ($geo_data as $data) {
    ?>
    <tr>
        <td><?php echo h($data->id); ?></td>
        <td class="locationName"><?php echo h($data->name); ?></td>

        <td><?php echo h($data->lat_degree); ?></td>
        <td><?php echo h($data->lat_minute); ?></td>
        <td><?php echo h($data->lat_seconds); ?></td>
        <td><?php echo h($data->lat_direction); ?></td>

        <td><?php echo h($data->long_degree); ?></td>
        <td><?php echo h($data->long_minute); ?></td>
        <td><?php echo h($data->long_seconds); ?></td>
        <td><?php echo h($data->long_direction); ?></td>

        <td class="lat"><?php echo h(number_format($data->latitude, 4)); ?></td>
        <td class="long"><?php echo h(number_format($data->longitude, 4)); ?></td>

        <td><?php echo h(number_format($data->altitude, 2)) . ' m'; ?></td>

        <td><input class="btn btn-sm btn-outline-secondary reverseGeocode" type="button" value="Reverse Geocode"></td>
        <td><input class="btn btn-sm btn-outline-secondary" type="button" value="Edit" onclick="window.location.href='/methods/edit.php?id=<?php echo h(u($data->id)); ?>'"></td>
        <td><input class="btn btn-sm btn-outline-secondary" type="button" value="Delete" onclick="window.location.href='/methods/delete.php?id=<?php echo h(u($data->id)); ?>'"></td>
    </tr>
    <?php } ?>
    </tbody>
</table>