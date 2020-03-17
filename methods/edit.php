<?php
  require_once('../private/initialize.php');

  if(!isset($_GET['id'])) {
    redirect_to('/tabs/mysql-geolocation.php');
  }
  $id = $_GET['id'];
  $geoData = Geolocation::find_by_id($id);
  if($geoData == false) {
    redirect_to('/tabs/mysql-geolocation.php');
  }

  if(is_post_request()) {
    // Reset Latitude and Longitude to 0
    $geoData->latitude = 0;
    $geoData->longitude = 0;
    //$geoData->attitude = 0;
    
    // Save record using post parameters
    $args = $_POST['geoData'];
    $geoData->merge_attributes($args);
    $result = $geoData->save();

    if($result === true) {
      $session->message('The geoData was updated successfully.');
      redirect_to('/');
    } else {
      // show errors
    }

  } else {
    // display the form
  }

?>
<?php include "../private/config.php"?>
<?php include "../shared/php/header.php"?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
  <div id="content">
    <!-- <a class="back-link" href="<?php //echo ('/tabs/mysql-geolocation.php'); ?>">&laquo; Back to Database</a> -->

    <div class="geolocation edit">
      <h1>Edit Turple Coordinates For ID #<?php echo h(u($id));?></h1>

      <?php echo display_errors($geoData->errors) ?>

      <form action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . h(u($id)); ?>" method="post">
        <?php include('form_fields.php'); ?>

        <div id="operations">
          <input type="submit" class="btn btn-outline-success" value="Edit Coordinates" />
        </div>
      </form>

    </div>
  </div>
</main>

<?php include "../shared/php/footer.php"?>
