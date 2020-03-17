<?php
  require_once('../private/initialize.php');

  if(is_post_request()) {
    // Create record using post parameters
    $args = $_POST['geoData'];
    $geoData = new Geolocation($args);
    
    // Reset latitude and longitude
    $geoData->latitude = 0;
    $geoData->longitude = 0;

    $result = $geoData->save();
  
    if($result === true) {
      $new_id = $geoData->id;
      $session->message('New Geolocation Data was added successfully.');
      redirect_to('/');
    } else {
      // show errors
    }
  
  } else {
    // display the form and instantiate a new Geolocation Object
    $geoData = new Geolocation;

    // instantiate latitude and longitude
    $geoData->latitude = 0;
    $geoData->longitude = 0;
  }
?>
<?php include "../private/config.php"?>
<?php include "../shared/php/header.php"?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
  <div id="content">
    <!-- <a class="back-link" href="<?php //echo ('/index.php'); ?>">&laquo; Back to List</a> -->

    <div class="geolocation add">
      <h1>Add a new Geolocation Data</h1>

      <?php echo display_errors($geoData->errors); ?>
      
      <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
          
          <?php include('form_fields.php'); ?>

          <div id="operations">
              <input type="submit" class="btn btn-outline-success" name="commit" value="Add" />
          </div>
      </form>
    </div>
  </div>
</main>
<?php include "../shared/php/footer.php"?>