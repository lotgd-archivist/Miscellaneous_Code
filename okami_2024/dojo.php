
<?php

/***************************** Einbauanleitung *****************************
*                                                                          *
* öffne z.B. village.php und suche:                                        *
* addnav("Trainingslager","train.php");                                    *
*                                                                          *
* setze darunter:                                                          *
* addnav("Dojo","dojo.php");                                               *
*                                                                          *
*************************** Einbauanleitung Ende **************************/

require_once "common.php";
checkday();
page_header("Dojo");
$session[user][standort]="Dojo";

$level = $session['user']['level'];
$exp = e_rand(5,10);
$gold = e_rand(8,15);
$ergebnis = ($level*$exp);
$goldgebnis = ($level*$gold);
$anzahl = $_POST['round'];

switch($_GET['op']){
case '':
output('`c `^Der Wächter des Dojo sieht dich fragend an.`n
Wieviele Runden willst du den Tranieren?`n`n`n`c');
break;

case 't1':
if ($session['user']['turns']>=1){
$session['user']['turns']-=1;
$er= $ergebnis*1;
$session['user']['experience']+=$er;
output("`#Du hast mit `^oyakáta `#traniert. Du bekommst für die `^1 `#Runden `^".$er." `#Erfahrung.");
break;
}
else{
output("`lSoviele Runden hast du nicht.`n");
}
break;

case 't2':
if ($session['user']['turns']>=5){
$session['user']['turns']-=5;
$er= $ergebnis*5;
$session['user']['experience']+=$er;
output("`#Du hast mit `^Praeceptor `#traniert. Du bekommst für die `^5 `#Runden `^".$er." `#Erfahrung.");
break;
}
else{
output("`lSoviele Runden hast du nicht.`n");
    }
break;

case 't3':
if ($session['user']['turns']>=10){
$session['user']['turns']-=10;
$er= $ergebnis*10;
$session['user']['experience']+=$er;
output("`#Du hast mit `^Kyoshi `#traniert. Du bekommst für die `^10 `#Runden `^".$er." `#Erfahrung.");
break;
}
else{
output("`lSoviele Runden hast du nicht.`n");
    }
break;

case 't4':
if ($session['user']['turns']>=15){
$session['user']['turns']-=15;
$er= $ergebnis*15;
$session['user']['experience']+=$er;
output("`#Du hast mit `^Kenkaku `#traniert. Du bekommst für die `^15 `#Runden `^".$er." `#Erfahrung.");
break;
}
else{
output("`lSoviele Runden hast du nicht.`n");
    }
break;

case 't5':
if ($session['user']['turns']>=20){
$session['user']['turns']-=20;
$er= $ergebnis*20;
$session['user']['experience']+=$er;
output("`#Du hast mit `^Pugnator `#traniert. Du bekommst für die `^20 `#Runden `^".$er." `#Erfahrung.");
break;
}
else{
output("`lSoviele Runden hast du nicht.`n");
    }
break;

case 't6':
if ($session['user']['turns']>=25){
$session['user']['turns']-=25;
$er= $ergebnis*25;
$session['user']['experience']+=$er;
output("`#Du hast mit `^Senséi `#traniert. Du bekommst für die `^25 `#Runden `^".$er." `#Erfahrung.");
break;
}
else{
output("`lSoviele Runden hast du nicht.`n");
    }
break;
}

addnav("1 Runde","dojo.php?op=t1");
addnav("5 Runden","dojo.php?op=t2");
addnav("10 Runden","dojo.php?op=t3");
addnav("15 Runden","dojo.php?op=t4");
addnav("20 Runden","dojo.php?op=t5");
addnav("25  Runden","dojo.php?op=t6");

addnav("raus");
addnav("Zurück","kg.php");


page_footer();
?>

