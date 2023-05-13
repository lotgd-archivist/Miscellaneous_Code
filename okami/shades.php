
<?php
/**
 * shades.php: Die Unterwelt - Hauport für die Toten.
 * @author LOGD-Core
 * @version DS-E V/2
*/

require_once('common.php');

if ($session['user']['imprisoned']>0) {
    redirect("prison.php");
}

page_header('Land der Schatten');
$session[user][standort]="Tot";
addcommentary();
checkday();

music_set('unterwelt');

if ($session['user']['alive']) {
    redirect('village.php');
}

/**
 * @ TODO: Talion - sinnvollere Lösung finden, z.B. generelles Flag für Buffs, das die Nutzung im Totenreich erlaubt.
 * Salator - Das mal etwas umgestellt um mehr als den Knappen wiederzuholen
 */
$buffsave=$session['bufflist'];
$session['bufflist']=array();
if(isset($buffsave['decbuff']) && ($buffsave['decbuff']['state'] == 20 || $buffsave['decbuff']['state'] == 21) && $buffsave['decbuff']['rounds'] > 0) {    // untoter+besessener Knappe
    $session['bufflist']['decbuff']=$buffsave['decbuff'];
}
if(isset($buffsave['headache'])) {    // Malus-Buff vom Totsaufen
    $session['bufflist']['headache']=$buffsave['headache'];
}

$str_output .= '`c`b`$Die Schatten`0`b`c
`n`$Du wandelst jetzt unter den Toten, du bist nur noch ein Schatten. Überall um dich herum sind die Seelen der in alten Schlachten und bei
gelegentlichen Unfällen gefallenen Kämpfer. Jede trägt Anzeichen der Niedertracht, durch welche sie ihr Ende gefunden haben.`n`n
Im Dorf dürfte es jetzt etwa `^'.getgametime(true).'`$ sein, aber hier herrscht die Ewigkeit und Zeit gibt es mehr als genug.`n`n';

// Asgarath - Ab sofort wird im Totenreich eine Statue der untoten Knappen angezeigt
$sql = 'SELECT disciples.name AS name,disciples.level AS level ,accounts.name AS master FROM disciples LEFT JOIN accounts ON accounts.acctid=disciples.master WHERE best_one=2 LIMIT 1';
$result = db_query($sql);
if (db_num_rows($result)>0) {
    $rowk = db_fetch_assoc($result);

    $str_output .='`$Eine kleine verfallene Statue ehrt `q'.$rowk['name'].'`$, einen untoten Knappen der '.$rowk['level'].'. Stufe, der zusammen mit '.$rowk['master'].'`$ eine Heldentat vollbrachte.`n`n';
}
$str_output .='`$Die verlorenen Seelen flüstern ihre Qualen und plagen deinen Geist mit ihrer Verzweiflung.`n`n`0';

output($str_output);
viewcommentary('shade','Verzweifeln',25,'jammert');
addnav('Das Totenreich');
addnav('Der Friedhof','graveyard.php');
addnav('Halle der Geister','halle_der_geister.php');

//RUNEN MOD
//wenn man eine eiwazrune hat, kommt man wieder nach oben
if( item_count('tpl_id="r_eiwaz" AND owner='.$session['user']['acctid']) > 0 ){
    addnav('Runenkraft');
    addnav('Benutze eine Eiwaz-Rune','newday.php?resurrection=rune',false,false,false,false,'Willst du wirklich eine Eiwaz-Rune dafür verwenden, in die Welt der Lebenden zurückzukehren?');
}
//RUNEN END


if ($session['user']['acctid']==getsetting('hasegg',0)){
    addnav('Das goldene Ei');
    addnav('Benutze das goldene Ei','newday.php?resurrection=egg');
}

addnav('Sonstiges');
addnav('b?`^Drachenbücherei`0','library.php');
addnav('In Diskussionsräume geistern','ooc.php?op=ooc');
addnav('Einwohnerliste','list.php');
addnav('R?In Ruhmeshalle spuken','hof.php');
addnav('Zurück');
addnav('Neuigkeiten','news.php');

if ($access_control->su_check(access_control::SU_RIGHT_LIVE_DIE))
{
    addnav('Back to Life','superuser.php?op=iwilldie',false,false,false,false,'Willst du wirklich hinter dem Rücken von Ramius wieder in die Welt der Lebenden klettern?');
}

addnav('Logout');
addnav('#?Schlaf der Schlaflosen','login.php?op=logout',true);


page_footer();
?>


