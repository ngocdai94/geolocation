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

<div id="content">

  <a class="back-link" href="<?php echo ('/index.php'); ?>">&laquo; Back to List</a>

  <div class="geolocation delete">
    <h1>Delete Geolocation Data ID #<?php echo h(u($id));?></h1>
    <p>Are you sure you want to delete this data? </p>

    <form action="<?php echo $_SERVER['PHP_SELF'] . '?id=' . h(u($id)); ?>" method="post">
      <div id="operations">
        <input type="submit" name="commit" value="Delete Data" />
      </div>
    </form>
  </div>

</div>

<?php //include(SHARED_PATH . '/staff_footer.php'); ?>