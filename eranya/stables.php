
<?php

// 24062004

require_once "common.php";
page_header("Keldorns Ställe");

// Haustier-Mod by Chaosmaker <webmaster@chaosonline.de>
// http://logd.chaosonline.de

// Anpassung, Bugfixes etc bye Maris (Maraxxus@gmx.de)
// Anpassung ans neue Itemsystem by talion

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
$futtercost = $session['user']['level']*20;

addnav("Zurück zum Marktplatz","market.php");

if ($session['user']['hashorse']>0 && $session['user']['fedmount']==0) {
        addnav("Futter");
        addnav("f?".strip_appoencode($playermount['mountname'])." füttern (`^$futtercost`0 Gold)","stables.php?op=futter");
}

if ($session['user']['petid']>0) addnav("t?".strip_appoencode($playerpet['name'])." füttern","stables.php?op=futterpet");


if (($pointsavailable>100) && ($session['user']['hashorse']>0)) {
        addnav("Spezial");
        addnav(strip_appoencode($playermount['mountname'])." taufen (100 DP)","stables.php?op=name");
}

if ($_GET['op']==""){
        checkday();
        output("`7Hinter der Kneipe, etwas links von Thoyas Rüstungen, befindet sich ein Stall,
        wie man ihn in jeder Stadt erwartungsgemäß findet.
        Darin kümmert sich Keldorn, ein stämmig wirkender Zwerg, um verschiedene Tiere.
        `n`n
        Du näherst dich ihm, als er plötzlich herumwirbelt und seine Heugabel in deine ungefähre Richtung streckt. \"`&Ach,
        'tschuldigung min ".($session['user']['sex']?"Mädl":"Jung").", heb dich nit kommen hörn un heb gedenkt,
        du bischt sicha Marek, der ma widda sein Zwergenweitwurf ufbessern will. Naaahw, wat
        kann ich für disch tun?`7\"`n
        `n
        `U`i(Schräg gedruckte Namen zeigen dir Tiere an, für die du noch nicht ausreichend viele Drachen getötet hast.)`i`n`n");
}
elseif ($_GET['op']=="examinepet")
{
        $pet = getpet($_GET['id']);
        if (count($pet)==0)
        {
                output("`7\"`&Ach, ich heb keen solches Tier da!`7\" ruft der Zwerg!");
        }
        else
        {
                $petbuffs = item_get_buffs(ITEM_BUFF_PET,','.$pet['buff1']);
                $petbuff = $petbuffs[0];
                output("`7\"`&Ai, ich heb wirklich n paar feine Viecher hier!`7\" kommentiert der Zwerg.`n`n
                        <table style='border: none;'>
                        <tr><td>`7Kreatur: </td><td>`&{$pet['tpl_name']}</td></tr>
                        <tr><td valign='top'>`7Beschreibung: </td><td>`&{$pet['tpl_description']}</td></tr>
                        <tr><td valign='top'>`7Preis: </td><td>`^{$pet['tpl_gold']} `&Gold`n`#{$pet['tpl_gems']}`& Edelstein".($pet['tpl_gems']==1?"":"e")."</td></tr>
                        </table>`n
                        `n
                        <table style='border: none;'>
                        <tr class='trhead'><td align='center'>`bEigenschaft`b</td><td align='center'>`bWert`b</td></tr>
                        <tr class='trlight'><td align='left'>Angriff: </td><td align='center'>".$petbuff['atkmod']."</td></tr>
                        <tr class='trdark'><td align='left'>Verteidigung: </td><td align='center'>".$petbuff['defmod']."</td></tr>
                        <tr class='trlight'><td align='left'>Lebenspunkte: </td><td align='center'>".$petbuff['regen']."</td></tr>
                        </table>`n");
                addnav("Kaufen");
                addnav("Dieses Tier kaufen","stables.php?op=buypet&id={$pet['tpl_id']}",false,false,false,false,true,'Willst du dieses Tier wirklich kaufen?');
        }

}elseif($_GET['op']=="examine"){
        $sql = "SELECT * FROM mounts WHERE mountid='{$_GET['id']}'";
        $result = db_query($sql);
        if (db_num_rows($result)<=0){
                output("`7\"`&Ach, ich heb keen solches Tier da!`7\" ruft der Zwerg!");
        }
        else{
                $mount = db_fetch_assoc($result);
                $mount['mountbuff'] = unserialize($mount['mountbuff']);
                $int_dksleft = $mount['mindk'] - $session['user']['dragonkills'];
                output("`7\"`&Ai, ich heb wirklich n paar feine Viecher hier!`7\" kommentiert der Zwerg.`n`n
                        <table style='border: none;'>
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
                if($int_dksleft > 0) {
                        output('`7Ach, da bischt du noch zu unerfahren für, min '.($session['user']['sex']?'Mädl':'Jung').'!
                                        Wennscht noch `b'.$int_dksleft.'`b Drachen mehr \'tötet hascht, kannscht widderkommn!`n');
                }
                else {
                        addnav("Dieses Tier kaufen","stables.php?op=buymount&id={$mount['mountid']}");
                }
        }
} elseif ($_GET['op']=='buypet')
{
        $tpl_id = $_GET['id'];

        $pet = getpet($tpl_id);

        if (count($pet)==0)
        {
                output("`7\"`&Ach, ich heb keen solches Tier da!`7\" ruft der Zwerg!");
        }
        else
        {
                if (
                        $session['user']['gold'] < $pet['tpl_gold']
                         ||
                        ($session['user']['gems']+$petrepaygems) < $pet['tpl_gems']
                )
                {
                        output("`7Keldorn schaut dich schief von der Seite an. \"`&Ähm, was gläubst du was du hier machst? Kanns u nich sehen, dass {$pet['name']} `^{$pet['gold']}`& Gold und `%{$pet['gems']}`& Edelsteine kostet?`7\"");
                }
                else
                {
                        $feeddays = getsetting("daysperday",4);
                        if ($session['user']['petid']>0)
                        {
                                output("`7Du übergibst dein(e/n) {$playerpet['tpl_name']} und bezahlst den Preis für dein neues Tier. Keldorn führt ein(e/n) schöne(n/s) neue(n/s) `&{$pet['tpl_name']}`7  für dich heraus und gibt dir Futter für $feeddays Tagesabschnitte dazu!`n`n");
                        }
                        else
                        {
                                output("`7Du bezahlst den Preis für dein neues Tier und Keldorn führt ein(e/n) schöne(n/s) neue(n/s) `&{$pet['tpl_name']}`7 für dich heraus und gibt dir Futter für $feeddays Tage dazu!`n`n");
                        }
                        // delete old pet
                        if($session['user']['petid'] > 0) {
                                item_delete(' id='.$session['user']['petid']);
                        }

                        // insert new pet
                        $pet['tpl_hvalue'] = $session['user']['house'];
                        item_add($session['user']['acctid'], $tpl_id, $pet);

                        $session['user']['petid'] = db_insert_id(LINK);

                        $session['user']['petfeed'] = date('Y-m-d H:i:s',time() + $feeddays * (3600*24 / getsetting("daysperday",4)));
                        $goldcost = -$pet['tpl_gold'];
                        $session['user']['gold'] += $goldcost;
                        $gemcost = $petrepaygems - $pet['tpl_gems'];
                        $session['user']['gems'] += $gemcost;
                        debuglog(($goldcost <= 0?"spent ":"gained ") . abs($goldcost) . " gold and " . ($gemcost <= 0?"spent ":"gained ") . abs($gemcost) . " gems trading for a new pet");
                        // Recalculate so the selling stuff works right
                        $playerpet = getpet((int)$session['user']['petid']);
                        $petrepaygems = round($playerpet['gems']*2/3,0);
                }
        }

}elseif($_GET['op']=='buymount'){
        $sql = "SELECT * FROM mounts WHERE mountid='{$_GET['id']}'";
        $result = db_query($sql);
        if (db_num_rows($result)<=0){
                output("`7\"`&Ach, ich heb keen solches Tier da!`7\" ruft der Zwerg!");
        }else{
                $mount = db_fetch_assoc($result);
                if (
                        ($session['user']['gold']+$repaygold) < $mount['mountcostgold']
                         ||
                        ($session['user']['gems']+$repaygems) < $mount['mountcostgems']
                ){
                        output("`7Keldorn schaut dich schief von der Seite an. \"`&Ähm, was gläubst du was du hier machst? Kanns u nich sehen, dass {$mount['mountname']} `^{$mount['mountcostgold']}`& Gold und `%{$mount['mountcostgems']}`& Edelsteine kostet?`7\"");
                }else{
                        if ($session['user']['hashorse']>0){
                                output("`7Du übergibst dein(e/n) {$playermount['mountname']} und bezahlst den Preis für dein neues Tier. Keldorn führt ein(e/n) schöne(n/s) neue(n/s) `&{$mount['mountname']}`7  für dich heraus!`n`n");
                                $session['user']['reputation']--;

                        }
                        else{
                                output("`7Du bezahlst den Preis für dein neues Tier und Keldorn führt ein(e/n) schöne(n/s) neue(n/s) `&{$mount['mountname']}`7 für dich heraus!`n`n");
                    }

                        $sql = "UPDATE account_extra_info SET hasxmount=0,mountextrarounds=0 WHERE acctid=".$session['user']['acctid']."";
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
} elseif ($_GET['op']=='sellpet')
{

        item_delete(' id='.$session['user']['petid']);

        $session['user']['gems'] += $petrepaygems;
        debuglog("gained $petrepaygems gems selling their pet");
        $session['user']['petid'] = 0;
        $session['user']['petfeed'] = '0000-00-00 00:00:00';
        output("`7So schwer es dir auch fällt, dich von dein(er/em) {$playerpet['name']} zu trennen, tust du es doch und eine einsame Träne entkommt deinen Augen.`n`n");
        output("Aber in dem Moment, in dem du die `%$petrepaygems`7 Edelsteine erblickst, fühlst du dich gleich ein wenig besser.");
}elseif($_GET['op']=='sellmount')
{
        $session['user']['gold']+=$repaygold;
        $session['user']['gems']+=$repaygems;
        debuglog("gained $repaygold gold and $repaygems gems selling their mount");
        if($session['bufflist']['mount']['liferaise'] > 0)
        {
                $session['user']['hitpoints'] /= $session['bufflist']['mount']['liferaise'];
                /*if($session['user']['hitpoints'] > $session['user']['maxhitpoints'])
                {
                        $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
                }*/
        }
        unset($session['bufflist']['mount']);

        cache_release_local('playermount');

        $session['user']['hashorse']=0;
        $sql = "UPDATE account_extra_info SET hasxmount=0,mountextrarounds=0 WHERE acctid=".$session['user']['acctid']."";
        db_query($sql);

        output("`7So schwer es dir auch fällt, dich von dein(er/em) {$playermount['mountname']} zu trennen, tust du es doch und eine einsame Träne entkommt deinen Augen.`n`n");
        output("Aber in dem Moment, in dem du die ".($repaygold>0?"`^$repaygold`7 Gold ".($repaygems>0?" und ":""):"").($repaygems>0?"`%$repaygems`7 Edelsteine":"")." erblickst, fühlst du dich gleich ein wenig besser.");
        $session['user']['reputation']-=2;
} elseif ($_GET['op']=='futterpet') {
        if (empty($_POST['days'])) {
                output('Das Futter kostet `^'.$playerpet['value1'].' Gold`0 und
                                `%'.$playerpet['value2'].' Edelsteine`0 pro Tag.`n`n');
                output('<form action="stables.php?op=futterpet" method="post">',true);
                output('Für wie viele Tage möchtest du Futter kaufen? ');
                output(' <input class="input" name="days" value="0" size="3"> <input type="submit" value="Kaufen!">',true);
                output('</form>',true);
                addnav('','stables.php?op=futterpet');
        }
        else {
                $days = (int)$_POST['days'];
                if ($session['user']['gold']>=$playerpet['value1']*$days && $session['user']['gems']>=$playerpet['value2']*$days) {
                        $session['user']['gold'] -= $playerpet['value1']*$days;
                        $session['user']['gems'] -= $playerpet['value2']*$days;
                        if ($playerpet['value1']>0) {
                                if ($playerpet['value2']>0) {
                                        $coststr = '`^'.($playerpet['value1']*$days).' Gold`0 und `%'.($playerpet['value2']*$days).' Edelsteine`0';
                                }
                                else $coststr = '`^'.($playerpet['value1']*$days).' Gold`0';
                        }
                        else {
                                $coststr = '`%'.($playerpet['value2']*$days).' Edelsteine`0';
                        }
                        output('Keldorn nimmt die '.$coststr.' und gibt dir genug Futter, um dein(e/n) '.$playerpet['name'].' die nächsten '.$days.' Tage zu versorgen.`n');
                        $oldtime = strtotime($session['user']['petfeed']);
                        if ($oldtime < time()) $oldtime = time();
                        $newtime = $oldtime + $days * (3600*24 / getsetting("daysperday",4));
                        $session['user']['petfeed'] = date('Y-m-d H:i:s',$newtime);
                }
                else {
                        output('`7Du kannst das Futter nicht bezahlen. Keldorn weigert sich, dein Tier für dich durchzufüttern.');
                }
        }
}elseif($_GET['op']=='futter'){
        if ($session['user']['gold']>=$futtercost) {


$sql = "SELECT mountextrarounds,hasxmount,xmountname FROM account_extra_info WHERE acctid=".$session['user']['acctid']."";
$result = db_query($sql) or die(db_error(LINK));
$rowm = db_fetch_assoc($result);

                        $buff = unserialize($playermount['mountbuff']);
                        if ($session['bufflist']['mount']['rounds']-$rowm['mountextrarounds'] == $buff['rounds']) {
                        output("Dein {$playermount['mountname']} ist satt und rührt das vorgesetzte Futter nicht an. Darum gibt Keldorn dir dein Gold zurück.");
                }else if ($session['bufflist']['mount']['rounds']-$rowm['mountextrarounds'] > $buff['rounds']*.5) {
                        $futtercost=$futtercost/2;
                        output("Dein {$playermount['mountname']} nascht etwas von dem vorgesetzten Futter und lässt den Rest stehen. {$playermount['mountname']} ist voll regeneriert. ");
                        output("Da aber noch über die Hälfte des Futters übrig ist, gibt dir Keldorn 50% Preisnachlass.`nDu bezahlst nur $futtercost Gold.");
                        $session['user']['gold']-=$futtercost;
                        $session['user']['reputation']--;
                }else{
                        $session['user']['gold']-=$futtercost;
                        output("Dein {$playermount['mountname']} macht sich gierig über das Futter her und frisst es bis auf den letzten Krümel.`n");
                        output("Dein {$playermount['mountname']} ist vollständig regeneriert und du gibst Keldorn die $futtercost Gold.");
                        $session['user']['reputation']--;
                }
                if($buff['liferaise'] > 0)
                {
                        $int_liferaise = $session['user']['maxhitpoints']*$buff['liferaise'];
                        $int_gethp = round(($session['user']['maxhitpoints']*$buff['liferaise'])/2);
                        $session['user']['hitpoints'] += $int_gethp;
                        if($session['user']['hitpoints'] > $int_liferaise)
                        {
                                $session['user']['hitpoints'] = $int_liferaise;
                        }
                }
                $session['bufflist']['mount']=$buff;
                $session['bufflist']['mount']['rounds']+=$rowm['mountextrarounds'];
                if ($rowm['hasxmount']==1)
                {
                        $session['bufflist']['mount']['name']=$rowm['xmountname']." `&({$session['bufflist']['mount']['name']}`&)";
                }

                $session['user']['fedmount']=1;
        } else {
                output("`7Du hast nicht genug Gold dabei, um das Futter zu bezahlen. Keldorn weigert sich, dein Tier für dich durchzufüttern, und empfiehlt dir, im Wald nach einer grasbewachsenen Lichtung zu suchen.");
        }
} elseif($_GET['op']=='name'){

        output("`bDein Tier taufen`b`n`n");

        output("`n`nDer Name deines treuen Freundes darf max. 25 Zeichen lang sein, exklusive Farbcodes.`n`n");
        $n = $playermount['mountname'];

        output("Dein Tier heißt bisher : `n".$n." `0( ");
        rawoutput($n);
        output("`0)`n`n`0Wie soll dein Tier ab sofort heißen ?`n");
        rawoutput("<form action='stables.php?op=namepreview' method='POST'><input name='newname' value=\"".HTMLEntities($newname)."\" size=\"30\" maxlength=\"255\"> <input type='submit' value='Vorschau'></form>");
        addnav("","stables.php?op=namepreview");

}elseif ($_GET['op']=="namepreview"){
        $n = $playermount['mountname'];

        // Alle anderen Tags als erlaubte Farbcodes rausschmeißen
        $_POST['newname'] = strip_appoencode(strip_tags(str_replace("`0","",$_POST['newname'])),2);

        if (strlen($_POST['newname'])>255) $msg.="Der neuer Name ist zu lang, inklusive Farbcodes darf er nicht länger als 255 Zeichen sein.`n";
        if (strlen(strip_appoencode($_POST['newname'],3)) > 25) $msg.="Der neuer Name ist zu lang, exklusive Farbcodes darf er nicht länger als 25 Zeichen sein.`n";

        if ($msg==""){
                $_POST['newname'] = stripslashes($_POST['newname']);
                output("Dein Tier wird so heißen: {$_POST['newname']}`n`n`0Ist es das, was du willst?`n`n");
                $p = 100;
                $output.="<form action=\"stables.php?op=changename\" method='POST'><input type='hidden' name='name' value=\"".HTMLEntities($_POST['newname'])."\"><input type='submit' value='Ja' class='button'>, mein Tier heißt nun ".appoencode("{$_POST['newname']}`0")." für $p Punkte.</form>";
                output("`n`n<a href='stables.php?op=name'>Nein, ich will nochmal </a>",true);
                addnav("","stables.php?op=name");
                addnav("","stables.php?op=changename");
        }else{
                output("`b`\$Falscher Name: `b`^".$msg."`0");
                output("`n`nDein Tier heißt bisher: ".$n." `0(");
                $output.=$n;
                output("`0), und wird so heißen: ".$_POST['newname']." `0(");
                $output.=$_POST['newname'];
                output("`0)`n`nWie soll dein Tier heißen?`n");
                $output.="<form action='stables.php?op=namepreview' method='POST'><input name='newname' value=\"".HTMLEntities($_POST['newname'])."\"size=\"30\" maxlength=\"255\"> <input type='submit' value='Vorschau'></form>";
                addnav("","stables.php?op=namepreview");
        }

} else
if ($_GET['op']=="changename"){
        $newname=stripslashes($_POST['name']);

        // Alle anderen Tags als erlaubte Farbcodes rausschmeißen
        $newname = strip_appoencode(strip_tags(str_replace("`0","",$newname)),2);

        $p = 100;
        if ($pointsavailable>=$p){
            $session['user']['donationspent']+=$p;

            $sql = "UPDATE account_extra_info SET hasxmount=1,xmountname='".addslashes($newname)."' WHERE acctid=".$session['user']['acctid']."";
            db_query($sql);
            debuglog('`rhat Tier für '.$p.' DP auf den Namen '.$newname.' `rtaufen lassen.');

            output("`&Keldorn hebt zeremoniell seine Peitsche und verkündet:`n\"`3Und im Namen von Epona, Fury und Lassie taufe ich dich auf den Namen {$newname}`0!`&\"`n`n");

            $arr_buff = unserialize($playermount['mountbuff']);

            $session['bufflist']['mount']['name'] = $newname." `&({$arr_buff['name']}`&)";
        }else{
            output("Eine Taufe kostet $p Punkte, aber du hast nur $pointsavailable Punkte.");
        }
        addnav("Zurück zum Marktplatz","market.php");
}

$sql = "SELECT mountname,mountid,mountcategory,mindk FROM mounts WHERE mountactive=1 AND mountcategory!='Rossos Tiere' ORDER BY mountcategory,mountcostgems,mountcostgold";
$result = db_query($sql);
$category="";

$count = db_num_rows($result);

for ($i=0;$i<$count;$i++){
        $row = db_fetch_assoc($result);
        if ($category!=$row['mountcategory']){
                addnav($row['mountcategory']);
                $category = $row['mountcategory'];
        }
        $mark_mount = "";
        if($row['mindk'] > $session['user']['dragonkills'])
        {
                $mark_mount = "`i";
        }
        addnav($mark_mount."Betrachte {$row['mountname']}`0".$mark_mount,"stables.php?op=examine&id={$row['mountid']}",false,false,false,false);
}
if ($session['user']['housekey']>0) {

        $result = item_tpl_list_get(' stables_pet>0 ', ' ORDER BY tpl_gold ASC, tpl_gems ASC ','tpl_name,tpl_id');

        if (db_num_rows($result)>0) {
                addnav('Haustiere');
                while ($row = db_fetch_assoc($result)) {
                        addnav("Betrachte {$row['tpl_name']}`0",'stables.php?op=examinepet&id='.$row['tpl_id'],false,false,false,false);
                }
        }
}
if($_GET['op'] == '')
{
        if ($session['user']['hashorse']>0){
                output("`n`n`&Keldorn bietet dir `^$repaygold`& Gold und `%$repaygems`& Edelsteine für dein(e/n) {$playermount['mountname']}.");
                addnav("Sonstiges");
                addnav("Verkaufe ".strip_appoencode($playermount['mountname']),"stables.php?op=sellmount",false,false,false,false,true,'Willst du dein Tier wirklich verkaufen?');
        }
        if ($session['user']['petid']>0) {
                if ($session['user']['hashorse']==0) addnav("Sonstiges");
                output("`n`nKeldorn bietet dir `%$petrepaygems`7 Edelsteine für dein(e/n) {$playerpet['name']}.");
                addnav("Verkaufe ".strip_appoencode($playerpet['name']),"stables.php?op=sellpet",false,false,false,false,true,'Willst du dein Tier wirklich verkaufen?');
        }
}
page_footer();
?>

