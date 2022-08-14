
<?php

require_once "common.php";
require_once "classes_Kampfsystem.php";
addcommentary();

page_header("Vergnügungsbezirk");




switch($_GET['op']){
    default:
    
    addnav("Kultur");
addnav("test","fighttest.php?op=test");


addnav("Zurück");
addnav("nach Astaros","village.php");

break;
case "test";



output("heheh");

$kampf = new getFight();
$kampf -> getEnemyStats();
$kampf -> getSpielerStats($session['user']['name'], $session['user']['hitpoints'], $session['user']['attack'],$session['user']['defence']);
$kampf -> getSpielerCrit(100);
$kampf -> enemyFight($script);
$kampf -> kampfnav();
addnav("nach Astaros","village.php");
break;


}






    
checkday();

page_footer();

?>

