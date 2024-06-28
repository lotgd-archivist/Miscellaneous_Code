
<?php
require_once "common.php";
addcommentary();

/*ALTER TABLE `accounts` ADD `gott` TINYINT(4) NOT NULL DEFAULT '0',
ADD `beten` TINYINT(4) NOT NULL DEFAULT '0';*/

page_header("Gottes Tempel");
$session[user][standort]="Gottes Tempel";

switch($_GET['op']){
case '':
//output("<img src='images/lag2.jpg'>`c",true);
output("`c`n`n`&
Du betrittst den Tempel der Götter von Wolfsrealm. Du kannst dich für einen der drei Götter entscheiden 
und jeden Tag zu ihm beten. Doch bedenke deine Wahl gut, den einen um entscheiden ist nicht mehr 
möglich. Um mehr über die Götter zu erfahren trete zu ihnen.`c");
output("`n`n`n`lMit anderen flüster:`n`n");
viewcommentary("Gott","flüstern",5);

addnav("Götter");

if ($session['user']['gott']==0){
addnav("Zu Shira","gott.php?op=a");
addnav("Zu Sharem","gott.php?op=b");
addnav("Zu Shiana","gott.php?op=c");
}

if ($session['user']['beten']==0){
if ($session['user']['gott']==1){
addnav("Shira anbeten","gott.php?op=a2");
}
if ($session['user']['gott']==2){
addnav("Sharem anbeten","gott.php?op=b2");
}
if ($session['user']['gott']==3){
addnav("Shiana anbeten","gott.php?op=c2");
}
}
break;


case 'a':
output('`c`b`eS`Qc`qh`Aa`lt`8t`le`An `qd`Qe`es `QL`qe`Ab`le`8n `lS`Ah`qi`Qr`ea`n`b`&,`n
sie ist stark und mutig, möchte das Reich beschützen um allen einen Unterschlupf bieten zu können.
Wählst du sie zu deiner Göttin wird sie dir Edelsteine schenken.`c');
addnav("Wählen","gott.php?op=a1");
addnav("Zurück","gott.php");
break;

case 'b':
output('`c`b`TW`qo`tlf`&sg`7o`Ltt `TS`qh`ta`&r`7e`Lm`b`&,`n
er ist stark und mutig, möchte in seinen Reich nur Wölfen einen Unterschlupf bieten und sonst niemanden.
Wählst du im zu deinen Gott wird er dir Kämpfe schenken.`c');
addnav("Wählen","gott.php?op=b1");
addnav("Zurück","gott.php");
break;

case 'c':
output('`c`b`~W`4o`$l`Qf`qw`Qe`$s`4e`~n `4S`$h`Qi`$a`4n`~a`&,`n
Tochter der Göttin und versucht hier raus zufinden wer sie ist. Beschützt jeden der sie um Schutz bittet.
Wählst du sie zu deiner Göttint schenkt sie dir Lebenspunkte');
addnav("Wählen","gott.php?op=c1");
addnav("Zurück","gott.php");
break;


case 'a1':
output('`c`&Du hast dich für `eS`Qc`qh`Aa`lt`8t`le`An `qd`Qe`es `QL`qe`Ab`le`8n `lS`Ah`qi`Qr`ea `&Beschützerin des Reiches entschieden.`c');
$session['user']['gott']=1;
addnav("beten","gott.php?op=a2");
addnav("Zurück","gott.php");
break;

case 'b1':
output('`c`&Du hast dich für die `TW`qo`tlf`&sg`7o`Ltt `TS`qh`ta`&r`7e`Lm `&Beschützers des hiesiegen 
Reich entschieden.`c');
$session['user']['gott']=2;
addnav("beten","gott.php?op=b2");
addnav("Zurück","gott.php");
break;

case 'c1':
output('`c`&Du hast dich für die `~W`4o`$l`Qf`qw`Qe`$s`4e`~n `4S`$h`Qi`$a`4n`~a `&Wohltäterin jedes Lebewesen entschieden.`c');
$session['user']['gott']=3;
addnav("beten","gott.php?op=c2");
addnav("Zurück","gott.php");
break;


case 'a2':
output('`c`&Als '.($session['user']['sex']?"Anhängerin":"Anhänger").' von `eS`Qc`qh`Aa`lt`8t`le`An `qd`Qe`es 
`QL`qe`Ab`le`8n `lS`Ah`qi`Qr`ea `&betest du zu dir und sie gewährt dir 1 Edelstein.`c');
$session['user']['beten']=1;
$session['user']['gems']++;
break;

case 'b2':
output('`c`&Als '.($session['user']['sex']?"Anhängerin":"Anhänger").' von `TW`qo`tlf`&sg`7o`Ltt 
`TS`qh`ta`&r`7e`Lm `&betest du zu ihm und er gewährt dir 2 Kämpfe.`c');
$session['user']['beten']=1;
$session['user']['turns']+=2;
break;

case 'c2':
output('`c`&Als '.($session['user']['sex']?"Anhängerin":"Anhänger").' von `~W`4o`$l`Qf`qw`Qe`$s`4e`~n `4S`$h`Qi`$a`4n`~a 
`&betest du zu ihr und sie gewährt dir 5 Gesundheitspunkte.`c');
$session['user']['beten']=1;
$session['user']['maxhitpoints']+=5;

break;

addnav("Wege");
addnav("Zur","kir.php");
page_footer();
?>

