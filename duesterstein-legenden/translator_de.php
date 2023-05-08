
<?
// German translation file by anpera 2003 for "Legend of the Green Dragon" by Eric Stevens
//
// Running on www.anpera.net/logd
//
// version: 2.0.1 for 0.9.7 
// changes:
// 2.0.1: fixed some errors, added referral.php, moved superuserparts to the end of the file
//
// please report bugs (and fixes if you know) to http://sourceforge.net/projects/lotgd or to logd@anpera.de
//
// I have to thank the following persons for additional translations, help, and errorfinding:
// raybe, rheiny, traumhaft, weasle
//

$translate_page = $_SERVER['PHP_SELF'];
$translate_page = substr($translate_page,strrpos($translate_page,"/")+1);
function translate($input){
    global $translate_page;
    //echo $_SERVER['SCRIPT_FILENAME'];
    //echo $translate_page;
    switch ($translate_page){

// Start Superuser parts -- remove to increase server performance

// End Superuser
    }
    return replacer($input,$replace);
}

?>

