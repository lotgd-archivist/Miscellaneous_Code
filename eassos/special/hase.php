
<?php 
/* 
verletztes Häschen 
by Vaan 
18//12//2004 
*/ 

require_once"common.php"; 
page_header("Verletztes Häschen"); 
if ($_GET[op]==""){ 
if (!isset($session)) exit(); 
$session[user][specialinc]="hase.php"; 
output("`2Als du so über den Almweg streifst, siehst du ein verletztes Häschen auf dem Boden liegen."); 
output("`2Was willst du machen?"); 
addnav("D?Das Häschen verarzten","berge.php?op=help"); 
addnav("E?Einfach weiter gehen","berge.php?op=gehe"); 
} 
else if ($_GET[op]=="help"){ 
output("`2Du nimmst das Häschen und fängst an es zu verarzten. Als du fertig bist hoppelt das Häschen vergnügt zurück in den Bergwald."); 
output("`n`n`^Da du solange gebraucht hast verlierst du einen Waldkampf."); 
output("`n`n`&Diese ehrenhafte Tat wird mit 5 Ansehenspunkten belohnt."); 
        $session[user][reputation]+=5; 
        $session[user][turns]--; 
$session[user][specialinc]=""; 
//addnav("Zurück","berge.php"); 
} 
else if ($_GET[op]=="gehe"){ 
output("`2Da dich so ein kleiner Hase nicht interesiert gehst du einfach weiter."); 
output("`n`n`&Diese unehrenhafte Tat wird mit einem Abzug von 5 Ansehenspunkten bestraft."); 
        $session[user][reputation]-=5; 
$session[user][specialinc]=""; 
//addnav("Zurück","berge.php"); 
} 
?>

