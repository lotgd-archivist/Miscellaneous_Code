
<?php
/**
 * shades.php: Die Unterwelt - Hauport für die Toten. 
 * @author LOGD-Core
 * @version DS-E V/2
*/

require_once('common.php');

page_header('Land der Schatten');
addcommentary();
checkday();

music_set('shades');

if ($session['user']['alive']) {
    redirect('village.php');
}

// * Buffs verwalten -> nur Totenreich-Buffs zulassen
$arr_buffsave = array();
// Buff "untoter Knappe" sichern:
$sql = "SELECT name,state FROM disciples WHERE master=".$session['user']['acctid']." AND at_home=0";
$result = db_query($sql) or die(db_error(LINK));
$rowk = db_fetch_assoc($result);
if ($rowk['state'] == 20) {
    $arr_buffsave['decbuff'] = $session['bufflist']['decbuff'];
}
// Buffs, welche im Totenreich erlaubt sind, sichern:
$sql2 = db_query("SELECT ib.name FROM items_buffs ib LEFT JOIN items_tpl it ON (ib.id = it.buff1 OR ib.id = it.buff2) WHERE (it.buff1 > 0 OR it.buff2 > 0) AND it.battle_graveyard = 1 AND ib.name IN ('".join("','",array_keys($session['bufflist']))."')");
while($rowbuff = db_fetch_assoc($sql2)) {
    $arr_buffsave[$rowbuff['name']] = $session['bufflist'][$rowbuff['name']];
}
// Buffliste überspeichern:
$session['bufflist'] = $arr_buffsave;
// end Buffs verwalten *

output('`$Du wandelst jetzt unter den Toten, du bist nur noch ein Schatten. Überall um dich herum sind die Seelen der in alten Schlachten und bei  
gelegentlichen Unfällen gefallenen Kämpfer. Jede trägt Anzeichen der Niedertracht, durch welche sie ihr Ende gefunden haben.`n`n
Im Dorf dürfte es jetzt etwa `^'.date('G:i').'`$ sein, aber hier herrscht die Ewigkeit und Zeit gibt es mehr als genug.`n`n
Die verlorenen Seelen flüstern ihre Qualen und plagen deinen Geist mit ihrer Verzweiflung.`n');

viewcommentary('shade','Verzweifeln',25,'jammert');
addnav('Das Totenreich');
addnav('Der Friedhof','graveyard.php');

//RUNEN MOD
//wenn man eine eiwazrune hat, kommt man wieder nach oben
if( item_count('tpl_id="r_eiwaz" AND owner='.$session['user']['acctid']) > 0 ){
    addnav('Runenkraft');
    addnav('Benutze eine Eiwaz-Rune','newday.php?resurrection=rune');    
}
//RUNEN END

// Ankh
if( item_count('tpl_id="ankh" AND owner='.$session['user']['acctid']) > 0 )
{
    addnav('RP-Wiederbelebung');
    addnav('Ankh verwenden','graveyard.php?op=rp_resurrect&act=ok&ankh=true');
}
// end

if ($session['user']['acctid']==getsetting('hasegg',0)){ 
    addnav('Das goldene Ei');
    addnav('Benutze das goldene Ei','newday.php?resurrection=egg');
}

addnav('Sonstiges');
addnav('`^Drachenbücherei`0','library.php');
addnav('Einwohnerliste','list.php');
addnav('In Ruhmeshalle spuken','hof.php');

if(su_check(SU_RIGHT_GROTTO)) {
                addnav('Admin-Aktionen');
    addnav('Admin-Grotte','superuser.php');
    addnav('Back to Life','superuser.php?op=iwilldie',false, false, false, false);
}

addnav('Zurück');
addnav('Neuigkeiten','news.php');  

page_footer();
?>

