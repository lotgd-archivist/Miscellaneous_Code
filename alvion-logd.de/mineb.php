
<?php



require_once "common.php";







page_header("Die Mine");





    $skillarray = 350;

    $req=$session['user']['berufskill'];

    $rech= $skillarray/100;

    $erg= round($session[user][berufskill]/$rech);







switch($_GET['op']):

    case "";

    

    

    

addnav("Mine","mineb.php?op=test");



output("Hi");

break;



case "test";



output("Du baust 1 Erz ab.");



$session[user][berufskill]++;

rawoutput("Dein derzeitiger Berufskill ".$session['user']['berufskill']."/".$skillarray."".grafbar($skillarray,$req)." ".$erg." %");



break;





endswitch;







page_footer();

?>

