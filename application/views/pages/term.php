<?php 
$title = '';
$description = '';
if(isset($_GET['lang'])) {
  if($_GET['lang'] == 'ar') {
    $title = $term->title_ar;
    $description = $term->description_ar;
  }
  else {
    $title = $term->title;
    $description = $term->description;
  }
}
else {
  $title = $term->title;
  $description = $term->description;
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