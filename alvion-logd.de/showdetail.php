
<?php
require_once "common.php";
require_once "lib/gilden.php";
require_once "func/popup.php";

if(file_exists("lib/showuserbio.php"))
{
  require_once "lib/showuserbio.php";

if($_GET['art']=="bio")
{
  showuserbio($_GET['bionum']);
}
else
{
  popup_header("Details der Gilden");
  showguilds($_GET['id']);
}
}
else
{
  popup_header("Details der Gilden");
  showguilds($_GET['id']);
}

popup_footer();
?>

