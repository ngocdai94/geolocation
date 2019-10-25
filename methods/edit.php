<?php
require_once('../private/initialize.php');

// require_login();

if(!isset($_GET['id'])) {
  // redirect_to(url_for('/index.php'));
  redirect_to('/index.php');
}
$id = $_GET['id'];
$geoData = Geolocation::find_by_id($id);
if($geoData == false) {
  // redirect_to(url_for('/index.php'));
  redirect_to('/index.php');
}

if(is_post_request()) {

  // Save record using post parameters
  $args = $_POST['geoData'];
  $geoData->merge_attributes($args);
  $result = $geoData->save();

  if($result === true) {
    $session->message('The geoData was updated successfully.');
    redirect_to('/index.php');
  } else {
    // show errors
  }

} else {
  // display the form
}

?>

<div id="content">

  <a class="back-link" href="<?php echo ('/index.php'); ?>">&laquo; Back to List</a>

  <div class="bicycle edit">
    <h1>Edit Turple Coordinates For ID # <?php echo h(u($id));?></h1>

    <?php echo display_errors($geoData->errors) ?>

    <form action="<?php echo '/methods/edit.php?id=' . h(u($id)); ?>" method="post">

      <?php include('form_fields.php'); ?>

      <div id="operations">
        <input type="submit" value="Edit Coordinates" />
      </div>
    </form>

  </div>

</div>