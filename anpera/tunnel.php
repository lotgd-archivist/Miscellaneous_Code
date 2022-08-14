
ï»¿<?php



// 20160420



require_once("common.php");

page_header("EingestÃ¼rzter Tunnel");

output("`t`c`bEingestÃ¼rzter Tunnel`b`c`6");



// multivillages mod

if (isset($_GET['mv']) && $_GET['mv']!=$session['user']['specialmisc']) $session['user']['specialmisc']=$_GET['mv'];

$village=($session['user']['specialmisc']>0)?"tradervillages.php?village={$session['user']['specialmisc']}":"academy.php";



checkday();

output("`n`nDu bestaunst den alten und wenig gesicherten Tunnel, der bei Arbeiten an der DorfstraÃŸe wiederentdeckt wurde. ");

output("Du kannst eine ganze Weile durch den gewundenen Gang laufen. Das Beleuchtungssystem scheint immer noch intakt zu sein. Bei genauerem Hinsehen ");

output("- warum bist du auch so neugierig? - basiert es auf fluoreszierenden Insekten, die an der Decke und den WÃ¤nden entlang krabbeln.");

//output("Leider musst du deine Entdeckungstour abbrechen und umkehren, da die Tunneldecke an einer Stelle eingebrochen ist und der Weg durch jede");

//output(" Menge GerÃ¶ll und Erde blockiert wird. Hier mÃ¼ssen erst die StraÃŸenarbeiter ran, um das andere Ende des Tunnels freizurÃ¤umen.`n");

//output("`nEnttÃ¤uscht machst du dich auf den RÃ¼ckweg. Aber vielleicht ist der Durchgang ja bald frei!?");

output("Du machst eine Pause, als du Licht am Ende des Tunnels siehst. Noch kannst du umkehren...");

$result = db_query("SELECT name from items where owner=".$session['user']['acctid']." and name='Karte des HÃ¤ndlers'");

if (db_num_rows($result)>=1){

   output("`n`n`tAnhand deiner `@Karte des HÃ¤ndlers`t schÃ¤tzt du, dass der Tunnel grob Richtung `@Arator`t geht.");

}

addnav("Weiter","library.php");

addnav("ZurÃ¼ck",$village);



page_footer();



?>

