
<?php
/***************************** Einbauanleitung *****************************
öffne inn.php und suche:
addnav("Was machst du?");
setze darunter:
addnav("Arbeiten","job.php");
*************************** Einbauanleitung Ende **************************/

require_once "common.php";

page_header("Arbeiten");
$session[user][standort]="Arbeiten";

$level = $session['user']['level'];
$gold = e_rand(13,20);
$goldgebnis = ($level*$gold);
$anzahl = $_POST['round'];

switch($_GET['op']){
case '':
output('`c`#Mit Großen Augen fragt dich Elias:`n
`@Wie viele Runden willst du den arbeiten?`n
`n`n`n`c');
break;

case 't1':
if ($session['user']['turns']>=1){
$session['user']['turns']-=1;
$er= $goldgebnis*1;
$session['user']['gold']+=$er;
output("`#Du staubst ab `#für `^1 `#Runden und erhählst `^".$er." `#Gold.");
break;
}
else{
output("`lSoviele Runden hast du nicht.`n");
}
break;

case 't2':
if ($session['user']['turns']>=5){
$session['user']['turns']-=5;
$er= $goldgebnis*5;
$session['user']['gold']+=$er;
output("`#Du kehrst den Boden `#für `^5 `#Runden und erhählst `^".$er." `#Gold.");
break;
}
else{
output("`lSoviele Runden hast du nicht.`n");
}
break;

case 't3':
if ($session['user']['turns']>=10){
$session['user']['turns']-=10;
$er= $goldgebnis*10;
$session['user']['gold']+=$er;
output("`#Du wäscht das Geschirr `#für `^10 `#Runden und erhählst `^".$er." `#Gold.");
break;
}
else{
output("`lSoviele Runden hast du nicht.`n");
}
break;

case 't4':
if ($session['user']['turns']>=15){
$session['user']['turns']-=15;
$er= $goldgebnis*15;
$session['user']['gold']+=$er;
output("`#Du räumst die Tische ab `#für `^15 `#Runden und erhählst `^".$er." `#Gold.");
break;
}
else{
output("`lSoviele Runden hast du nicht.`n");
}
break;

case 't5':
if ($session['user']['turns']>=20){
$session['user']['turns']-=20;
$er= $goldgebnis*20;
$session['user']['gold']+=$er;
output("`#Du wischt den Boden `#für `^20 `#Runden und erhählst `^".$er." `#Gold.");
break;
}
else{
output("`lSoviele Runden hast du nicht.`n");
}
break;

case 't6':
if ($session['user']['turns']>=25){
$session['user']['turns']-=25;
$er= $goldgebnis*25;
$session['user']['gold']+=$er;
output("`#Du `^kellnerst `#für `^25 `#Runden und erhählst `^".$er." `#Gold.");
break;
}
else{
output("`lSoviele Runden hast du nicht.`n");
}
break;

}

addnav("1 Runde","job.php?op=t1");
addnav("5 Runden","job.php?op=t2");
addnav("10 Runden","job.php?op=t3");
addnav("15 Runden","job.php?op=t4");
addnav("20 Runden","job.php?op=t5");
addnav("25 Runden","job.php?op=t6");
addnav("raus");
addnav("Zurück","kg.php");


page_footer();
?>

