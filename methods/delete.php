<?php
  require_once('../private/initialize.php');

  if(!isset($_GET['id'])) {
    redirect_to('/index.php');
  }
  $id = $_GET['id'];
  $geoData = Geolocation::find_by_id($id);
  if($geoData == false) {
    redirect_to('/index.php');
  }

  if(is_post_request()) {
    // Delete bicycle
    $result = $geoData->delete();
    $session->message('The bicycle was deleted successfully.');
    redirect_to('/index.php');
  } else {
    // Display form
  }
?>

<?php //$page_title = 'Delete Bicycle'; ?>
<?php //include(SHARED_PATH . '/staff_header.php'); ?>
<?php include "../private/config.php"?>
<?php include "../shared/php/header.php"?>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
  <div id="content">
    <!-- <a class="back-link" href="<?php //echo ('/index.php'); ?>">&laquo; Back to List</a> -->

    <div class="geolocation delete">
      <h1>Delete Geolocation Data ID #<?php echo h(u($id));?></h1><br>
      <p style="color:#ff0000;font-size:1rem;font-weight:600;">Are you sure you want to delete this data? </p>

      <form action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . h(u($id)); ?>" method="post">
        <div id="operations">
          <input type="submit" class="btn btn-outline-danger" name="commit" value="YES, Delete It!" />
        </div>
      </form>
    </div>
  </div>
</main>

<?php include "../shared/php/footer.php"?>
