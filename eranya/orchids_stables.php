
<?php

// Haustier-Mod by Chaosmaker <webmaster@chaosonline.de>
// http://logd.chaosonline.de

// Anpassung, Bugfixes etc bye Maris (Maraxxus@gmx.de)
// Anpassung ans neue Itemsystem by talion

// Anpassung an Orchideenwiese by Silva (für http://eranya.de/)

require_once 'common.php';

page_header('Rossos Ställe');

function getpet($petid) {

        if(is_int($petid)) {
                $row = item_get(' id="'.$petid.'"' );
        }
        else {
                $row = item_get_tpl(' tpl_id="'.$petid.'"' );
        }

        if ($row['tpl_id']!='') {
                return $row;
        }
        else {
                return array();
        }
}

// Mount neu laden
getmount($session['user']['hashorse'],true);

$pointsavailable=$session['user']['donation']-$session['user']['donationspent'];

$playerpet = getpet((int)$session['user']['petid']);
$petrepaygems = round($playerpet['gems']*2/3);


$repaygold = round($playermount['mountcostgold']*2/3,0);
$repaygems = round($playermount['mountcostgems']*2/3,0);
$futtercost = $session[user][level]*20;

addnav("O?Zurück zur Orchideenwiese","orchids.php");

if ($_GET[op]==""){
        checkday();
        output("`7Anscheinend ist es zwergische Tradition, Ställe zu führen, denn kaum hast du das Gebäude betreten,
                siehst du auch schon einen der kleinen Männer auf dich zueilen. \"`&Kundschaft! Wie schön`7\", hörst
                du ihn rufen, dann steht er auch schon freundlich grinsend vor dir. \"`&Seid willkommen in meinen
                Ställen! Was kann der alte Rosso für Euch tun?`7\" So alt sieht der Gute gar nicht aus, denkst du
                dir, doch ist das Alter bei Zwergen sowieso eine ganz eigene Angelegenheit. Also widmest du dich lieber
                den umliegenden Tierunterbringungen - und staunst nicht schlecht darüber, was dir hier für Wesen dargeboten werden.");
}elseif($_GET['op']=="examine"){
        $sql = "SELECT * FROM mounts WHERE mountid='{$_GET['id']}'";
        $result = db_query($sql);
        if (db_num_rows($result)<=0){
                output("`7\"`&Tut mir Leid, aber momentan hab ich kein solches Tier in meinem Stall`7\", gibt Rosso schulterzuckend
                        von sich.");
        }
        else{

                output("`7Kaum hast du deinen Wunsch ausgesprochen, da schnippt Rosso auch schon mit den Fingern,
                        und Sekunden später führt ein junger Knabe das gewünschte Tier herbei.`n`n");
                $mount = db_fetch_assoc($result);
                $mount['mountbuff'] = unserialize($mount['mountbuff']);

                output("<table style='border: none;'>
                        <tr><td>`7Kreatur: </td><td>`&{$mount['mountname']}</td></tr>
                        <tr><td valign='top'>`7Beschreibung: </td><td>`&{$mount['mountdesc']}</td></tr>
                        <tr><td valign='top'>`7Preis: </td><td>`^{$mount['mountcostgold']} `&Gold`n`#{$mount['mountcostgems']}`& Edelstein".($mount['mountcostgems']==1?"":"e")."</td></tr>
                        </table>`n
                        `n
                        <table style='border: none;'>
                        <tr class='trhead'><td align='center'>`bEigenschaft`b</td><td align='center'>`bWert`b</td></tr>
                        <tr class='trlight'><td align='left'>Eigener Angriff (Multiplikator): </td><td align='center'>".(!empty($mount['mountbuff']['atkmod']) ? $mount['mountbuff']['atkmod'] : 1)."</td></tr>
                        <tr class='trdark'><td align='left'>Eigene Verteidigung (Multiplikator): </td><td align='center'>".(!empty($mount['mountbuff']['defmod']) ? $mount['mountbuff']['defmod'] : 1)."</td></tr>
                        <tr class='trlight'><td align='left'>Regeneration: </td><td align='center'>".(!empty($mount['mountbuff']['regen']) ? $mount['mountbuff']['regen'] : 'keine`nRegeneration')."</td></tr>
                        <tr class='trdark'><td align='left'>Life tap: </td><td align='center'>".(!empty($mount['mountbuff']['lifetap']) ? $mount['mountbuff']['lifetap'] : 1)."</td></tr>
                        <tr class='trlight'><td align='left'>Günstlingszähler (mehr Gold pro Runde): </td><td align='center'>".$mount['mountbuff']['minioncount']."</td></tr>
                        <tr class='trdark'><td align='left'>Schadensabwehr (Multiplikator): </td><td align='center'>".(!empty($mount['mountbuff']['damageshield']) ? $mount['mountbuff']['damageshield'] : 1)."</td></tr>
                        <tr class='trlight'><td align='left'>Gegner-Angriff (Multiplikator): </td><td align='center'>".(!empty($mount['mountbuff']['badguyatkmod']) ? $mount['mountbuff']['badguyatkmod'] : 1)."</td></tr>
                        <tr class='trdark'><td align='left'>Gegner-Verteidigung (Multiplikator): </td><td align='center'>".(!empty($mount['mountbuff']['badguydefmod']) ? $mount['mountbuff']['badguydefmod'] : 1)."</td></tr>
                        <tr class='trlight'><td align='left'>Gegner-Schaden (Multiplikator): </td><td align='center'>".(!empty($mount['mountbuff']['badguydmgmod']) ? $mount['mountbuff']['badguydmgmod'] : 1)."</td></tr>
                        <tr class='trdark'><td align='left'>Schaden am Gegner (Minimum | Maximum): </td><td align='center'>".(($mount['mountbuff']['maxbadguydamage'] == 0 || empty($mount['mountbuff']['maxbadguydamage'])) ? 'Tier fügt keinen`nSchaden zu' : $mount['mountbuff']['minbadguydamage'].' | '.$mount['mountbuff']['maxbadguydamage'])."</td></tr>
                        <tr class='trlight'><td align='left'>Runden: </td><td align='center'>".$mount['mountbuff']['rounds']."</td></tr>
                        <tr class='trdark'><td align='left'>Mindest-DK-Anzahl für Kauf: </td><td align='center'>".$mount['mindk']."</td></tr>
                        </table>`n`n");
                addnav("Dieses Tier kaufen","orchids_stables.php?op=buymount&id={$mount['mountid']}",false,false,false,false,true,'Willst du dieses Tier wirklich kaufen?');
        }
}elseif($_GET['op']=='buymount'){
        $sql = "SELECT * FROM mounts WHERE mountid='{$_GET['id']}'";
        $result = db_query($sql);
        if (db_num_rows($result)<=0){
                output("`7\"`&Tut mir Leid, aber momentan hab ich kein solches Tier da`7\", gibt Rosso schulterzuckend
                        von sich.");
        }else{
                $mount = db_fetch_assoc($result);
                if (
                        ($session['user']['gold']+$repaygold) < $mount['mountcostgold']
                         ||
                        ($session['user']['gems']+$repaygems) < $mount['mountcostgems']
                ){
                        output("`7Rosso schaut dich einen Moment lang schief von der Seite an, räuspert sich dann hörbar und deutet auf deinen leichten Goldbeutel. \"`& {$mount['mountname']} `&kostet `^{$mount['mountcostgold']}`& Gold und `%{$mount['mountcostgems']}`& Edelsteine. Ich übergebe Euch das Tier erst, wenn Ihr mir diesen Preis zahlen könnt.`7\"");
                }else{
                        if ($session['user']['hashorse']>0){
                                output("`7Du übergibst dein(e/n) {$playermount['mountname']} und bezahlst den Preis für dein neues Tier. Daraufhin drückt dir der Knabe die Leine in die Hand - `&{$mount['mountname']}`7  gehört damit offiziell dir.`n`n");
                                $session[user][reputation]--;

                        }
                        else{
                                output("`7Du bezahlst den Preis für dein neues Tier. Daraufhin drückt dir der Knabe die Leine in die Hand - `&{$mount['mountname']}`7  gehört damit offiziell dir.`n`n");
                    }

                        $sql = "UPDATE account_extra_info SET hasxmount=0,mountextrarounds=0 WHERE acctid=".$session[user][acctid]."";
                        db_query($sql);

                        $session['user']['hashorse']=$mount['mountid'];
                        $goldcost = $repaygold-$mount['mountcostgold'];
                        $session['user']['gold']+=$goldcost;
                        $gemcost = $repaygems-$mount['mountcostgems'];
                        $session['user']['gems']+=$gemcost;
                        debuglog(($goldcost <= 0?"spent ":"gained ") . abs($goldcost) . " gold and " . ($gemcost <= 0?"spent ":"gained ") . abs($gemcost) . " gems trading for a new mount");
                        $session['bufflist']['mount']=unserialize($mount['mountbuff']);
                        // Recalculate so the selling stuff works right

                        $repaygold = round($playermount['mountcostgold']*2/3,0);
                        $repaygems = round($playermount['mountcostgems']*2/3,0);

                        cache_release_local('playermount');
                }
        }
}elseif($_GET['op']=='sellmount')
{
        $session['user']['gold']+=$repaygold;
        $session['user']['gems']+=$repaygems;
        debuglog("gained {$repaygold} gold and {$repaygems} gems selling their mount");
        unset($session['bufflist']['mount']);

        cache_release_local('playermount');

        $session['user']['hashorse']=0;
        $sql = "UPDATE account_extra_info SET hasxmount=0,mountextrarounds=0 WHERE acctid=".$session[user][acctid]."";
        db_query($sql);

        output("`7So schwer es dir auch fällt, dich von dein(er/em) {$playermount['mountname']} zu trennen, tust du es doch und eine einsame Träne entkommt deinen Augen.`n`n");
        output("Aber in dem Moment, in dem du die ".($repaygold>0?"`^{$repaygold}`7 Gold ".($repaygems>0?" und ":""):"").($repaygems>0?"`%$repaygems`7 Edelsteine":"")." erblickst, fühlst du dich gleich ein wenig besser.");
        $session[user][reputation]-=2;
}

$sql = "SELECT mountname,mountid,mountcategory FROM mounts WHERE mountactive=1 AND mountcategory='Rossos Tiere' ORDER BY mountcostgems,mountcostgold";
$result = db_query($sql);

$count = db_num_rows($result);
addnav($row['mountcategory']);
for ($i=0;$i<$count;$i++){
        $row = db_fetch_assoc($result);
        addnav("Betrachte {$row['mountname']}`0","orchids_stables.php?op=examine&id={$row['mountid']}",false,false,false,false);
}
if ($session['user']['hashorse']>0){
        output("`n`n`&Rosso bietet dir `^{$repaygold}`& Gold und `%{$repaygems}`& Edelsteine für dein(e/n) {$playermount['mountname']}.");
        addnav("Sonstiges");
        addnav("Verkaufe ".strip_appoencode($playermount['mountname']),"orchids_stables.php?op=sellmount",false,false,false,false,true,'Willst du dein Tier wirklich verkaufen?');
}
page_footer();
?>

