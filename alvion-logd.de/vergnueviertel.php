
<?php
require_once "common.php";
addcommentary();
checkday();
page_header("Vergnügungsviertel");
$session['user']['standort']="Vergnügungsviertel";
output("`c`b`)Ve`7rg`=nü`&gung`=svie`7rt`)el`b`c`n`n");
output("`)Als dich dein H`7eißhunger mal wi`=eder packt, besc`&hließt du, den We`=g in die Kneipe ei`7nzuschlagen u`)nd machst dich na`7türlich sofort au`=f den Weg dort hin. D`&ir begegnen bere`=its jetzt einige Li`7ebespärchen, der`)en Ziel ganz sicher n`7icht die Taverne i`=st. Im Gegente`&il... Ein Pärchen n`=ach dem anderen versc`7hwindet im gegenü`)berliegenden Gebä`7ude. Kopf schüttel`=nd, aber auch mit e`&inem Grinsen auf den L`=ippen setzt du deine`7n Weg fort, darum b`)emüht, nicht m`7it den Betrunken`=en zusammen z`&u stoßen. `n`n`n");

addnav("Taverne des Walddrachen","inn.php",true);
// addnav("Tanzsaal","tanzsaal.php");
addnav("Tanzsaal","dancehouse.php");
addnav("F?Freudenhaus","frdnhaus.php");
// addnav("H?Heldenlager","herocamp.php");
// addnav("K?In den Keller", "keller.php");

addnav("Zum Dorf","village.php");


viewcommentary("tavernengasse","Hinzufügen",25,"sagt",1,1);

page_footer();
?>

