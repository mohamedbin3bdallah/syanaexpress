<?php 
$title = '';
$description = '';
if(isset($_GET['lang'])) {
  if($_GET['lang'] == 'ar') {
    $title = $privacy->title_ar;
    $description = $privacy->description_ar;
  }
  else {
    $title = $privacy->title;
    $description = $privacy->description;
  }
}
else {
  $title = $privacy->title;
  $description = $privacy->description;
}
?>
<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width">
      <title><?php echo $title; ?></title>
      <style>body{font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; padding:1em;}</style>
   </head>
   <body>
      <h2><?php echo $title; ?></h2>
      <?php echo $description; ?>
   </body>
</html>