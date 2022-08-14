
<?php 
//Fary 2 
//by Neppar 
// 
//Modyficated by Hadriel 
output("`2Als du durch den Bergwald gehst, gelangst du auf eine `@Lichtung.`n`n`2Plötzlich erscheint vor dir eine `^Fee`2 und spricht ...`#'Oh tapferer Krieger! Du bist hergekommen, um Ruhe zu finden nach all deinen Abenteuern! Hier hast du sie, ruh dich nur aus!'`n`n `2Danach streut sie etwas Feenstaub über dir aus. Dir wird wohl zu Mute und du schläfst ein. "); 
output("`n`@Du verlierst zwei Waldkämpfe beim Schlafen! Als du aufwachst, bemerkst du jedoch, dass du `5schöner `@geworden bist und du fühlst dich sehr `4kräftig!"); 
$session[user][hitpoints]+=round($session[user][maxhitpoints]*.1,0); 
$session[user][charm] += 3; 
$session['user']['turns']-=2; 
addnav("Zurück in den Wald","forest.php"); 
addnews($session[user][name]." wurde im Bergwald, von einem merkwürdigen Licht umgeben, schlafend auf einer Lichtung gesehen."); 
?>

