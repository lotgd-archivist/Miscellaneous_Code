
<?php

// 24062004

require_once "common.php";
page_header("Lupus Ställe");

// Haustier-Mod by Chaosmaker <webmaster [-[at]-] chaosonline.de>
// http://logd.chaosonline.de

// Anpassung, Bugfixes etc by Maris (Maraxxus [-[at]-] gmx.de)
// Anpassung ans neue Itemsystem by talion
// 19.1.07 Noch mehr bugfixes by Maris

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

if($session['user']['hashorse']>0){
    $repaygold = round($playermount['mountcostgold']*0.45,0);
    $repaygems = round($playermount['mountcostgems']*0.45,0);
}
else {
    $repaygold=0;
    $repaygems=0;
}
if($session['user']['petid']>0){
    $playerpet = getpet((int)$session['user']['petid']);
    $petrepaygems = round($playerpet['gems']*2/3);
}
$futtercost = $session['user']['level']*20;
$pointsavailable=$session['user']['donation']-$session['user']['donationspent'];

addnav('Zurück');
addnav('Zurück zum Marktplatz','market.php');
output('`c`b`7Lupos Ställe`0`b`c`n');
if ($_GET['op']=='')
{
    checkday();
    output('`7Etwas abseits der anderen Gebäude und Händlerstände findet man ein Gebilde aus Holz vor, bei dem jeden Besucher wohl schon die Nase den rechten Weg weisen könnte. Der Geruch von Heu und Stroh mischt sich hier mit dem der Tiere, und scheint fast schon aufdringlich, sofern man an diese "Duftmischung" nicht gewöhnt ist.
    `nHier kümmert sich `fLupus`7 um die verschiedensten Tierarten. Jeder findet bei dem stämmigen Zwerg etwas nach seinen Vorstellungen...
    `n`n
    Du näherst dich ihm, als er plötzlich herumwirbelt und seine Heugabel in deine ungefähre Richtung streckt. "`&Ach,
    \'tschuldigung min '.($session['user']['sex']?'Mädl':'Jung').', heb dich nit kommen hörn un heb gedenkt,
    du bischt sicha Cedrik, der ma widda sein Zwergenweitwurf ufbessern will. Naaahw, wat
    kann ich für disch tun?`7"');
}
elseif ($_GET['op']=='examinepet')
{
    $pet = getpet($_GET['id']);
    if (count($pet)==0)
    {
        output('`7"`&Ach, ich heb keen solches Tier da!`7" ruft der Zwerg!');
    }
    else
    {


        output('`7"`&Ai, ich heb wirklich n paar feine Viecher hier!`7" kommentiert der Zwerg.`n`n
        Kreatur: `&'.$pet['tpl_name'].'`n
        Beschreibung: `&'.$pet['tpl_description'].'`n
        `7Preis: `^'.$pet['tpl_gold'].'`& Gold, `%'.$pet['tpl_gems'].'`& Edelstein'.($pet['tpl_gems']==1?'':'e').'`n
        `n');
        addnav('Kaufen');
        addnav('Dieses Tier kaufen','stables.php?op=buypet&id='.$pet['tpl_id']);
    }

}
elseif($_GET['op']=='examine')
{
    $sql = 'SELECT * FROM mounts WHERE mountid='.$_GET['id'];
    $result = db_query($sql);
    if (db_num_rows($result)<=0){
        output('`7"`&Ach, ich heb keen solches Tier da!`7" ruft der Zwerg!');
    }
    else{
        $mount = db_fetch_assoc($result);
        $int_dksleft = $mount['mindk'] - $session['user']['dragonkills'];
        output('`7"`&Ai, ich heb wirklich n paar feine Viecher hier!`7" kommentiert der Zwerg.`n`n');
        output('`7Kreatur: `&'.$mount['mountname'].'`n');
        output('`7Beschreibung: `&'.$mount['mountdesc'].'`n');
        output('`7Preis: `^'.$mount['mountcostgold'].'`& Gold, `%'.$mount['mountcostgems'].'`& Edelstein'.($mount['mountcostgems']==1?'':'e').'`n');
        output('`n');
        if($int_dksleft > 0)
        {
            output('`7Ach, da bischt du noch zu unerfahren für, min '.($session['user']['sex']?'Mädl':'Jung').'!
            Wennscht noch `b'.$int_dksleft.'`b Heldedaden mehr g\'moacht hascht, kannscht widderkommn!`n');
        }
        elseif($session['user']['hashorse']==0)
        {
            addnav('Kaufen');
            addnav('Dieses Tier kaufen','stables.php?op=buymount&id='.$mount['mountid'],false,false,false,true,($session['user']['hashorse']>0?'Du kannst nur 1 Tier gleichzeitig führen. '.strip_appoencode($playermount['mountname'],3).' in Zahlung geben?':''));
        }
        else
        {// Sicherheitsabfragen werden immer seltener gelesen, also automatischer Tiertausch komplett weg
            addnav('Kaufen');
            addnav('Du hast schon ein Tier','');
        }
    }
} 
elseif ($_GET['op']=='buypet')
{
    $tpl_id = $_GET['id'];

    $pet = getpet($tpl_id);

    if (count($pet)==0)
    {
        output('`7"`&Ach, ich heb keen solches Tier da!`7" ruft der Zwerg!');
    }
    else
    {
        if ($session['user']['gold'] < $pet['tpl_gold'] || ($session['user']['gems']+$petrepaygems) < $pet['tpl_gems'])
        {
            output('`7Lupus schaut dich schief von der Seite an. "`&Ähm, was gläubst du was du hier machst? Kanns u nich sehen, dass '.$pet['tpl_name'].' `^'.$pet['tpl_gold'].'`& Gold und `%'.$pet['tpl_gems'].'`& Edelsteine kostet?`7"');
        }
        else
        {
            $feeddays = getsetting("daysperday",4);
            if ($session['user']['petid']>0)
            {
                output('`7Du übergibst dein '.$playerpet['tpl_name'].' und bezahlst den Preis für dein neues Tier. Lupus führt ein schönes neues `&'.$pet['tpl_name'].'`7-Exemplar  für dich heraus und gibt dir Futter für '.$feeddays.' Tage dazu!`n`n');
            }
            else
            {
                output('`7Du bezahlst den Preis für dein neues Tier und Lupus führt ein schönes neues `&'.$pet['tpl_name'].'`7-Exemplar für dich heraus und gibt dir Futter für '.$feeddays.' Tage dazu!`n`n');
            }
            // delete old pet
            if($session['user']['petid'] > 0) {
                item_delete(' id='.$session['user']['petid']);
            }
            // insert new pet
            $pet['tpl_hvalue'] = $session['user']['house'];
            item_add($session['user']['acctid'], $tpl_id, $pet);
            $session['user']['petid'] = db_insert_id(LINK);
            $session['user']['petfeed'] = date('Y-m-d H:i:s',time() + $feeddays * (3600*24 / getsetting('daysperday',4)));
            $goldcost = -$pet['tpl_gold'];
            $session['user']['gold'] += $goldcost;
            $gemcost = $petrepaygems - $pet['tpl_gems'];
            $session['user']['gems'] += $gemcost;
            debuglog(($goldcost <= 0?'spent ':'gained ') . abs($goldcost) . ' gold and ' . ($gemcost <= 0?'spent ':'gained ') . abs($gemcost) . ' gems trading for a new pet');
            // Recalculate so the selling stuff works right
            $playerpet = getpet((int)$session['user']['petid']);
            $petrepaygems = round($playerpet['gems']*2/3,0);
        }
    }
}
elseif($_GET['op']=='buymount')
{
    getmount($session['user']['hashorse'],true);
    $sql = 'SELECT * FROM mounts WHERE mountid='.$_GET['id'];
    $result = db_query($sql);
    if (db_num_rows($result)<=0){
        output('`7"`&Ach, ich heb keen solches Tier da!`7" ruft der Zwerg!');
    }
    else
    {
        $mount = db_fetch_assoc($result);
        if (($session['user']['gold']+$repaygold) < $mount['mountcostgold'] || ($session['user']['gems']+$repaygems) < $mount['mountcostgems'])
        {
            output('`7Lupus schaut dich schief von der Seite an. "`&Ähm, was gläubst du was du hier machst? Kanns u nich sehen, dass '.$mount['mountname'].' `^'.$mount['mountcostgold'].'`& Gold und `%'.$mount['mountcostgems'].'`& Edelsteine kostet?`7"');
        }
        else
        {
            if ($session['user']['hashorse']>0){
                output('`7Du übergibst dein '.$playermount['mountname'].' und bezahlst den Preis für dein neues Tier. Lupus führt ein schönes neues `&'.$mount['mountname'].'`7-Exemplar  für dich heraus!`n`n');
                $session['user']['reputation']--;
                cache_release_local('playermount');
                $session['bufflist']['mount']=unserialize($mount['mountbuff']);
            }
            else{
                output('`7Du bezahlst den Preis für dein neues Tier und Lupus führt ein schönes neues `&'.$mount['mountname'].'`7-Exemplar für dich heraus!`n`n');
            }

            $sql = 'UPDATE account_extra_info SET hasxmount=0,mountextrarounds=0,xmountname="",mount_sausage='.$mount['mount_sausage'].' WHERE acctid='.$session['user']['acctid'];
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
            $session['bufflist']['mount']=unserialize($mount['mountbuff']);
        }
    }
} 
elseif ($_GET['op']=='sellpet')
{
    getmount($session['user']['hashorse'],true);
    item_delete(' id='.$session['user']['petid']);
    $session['user']['gems'] += $petrepaygems;
    $session['user']['petid'] = 0;
    $session['user']['petfeed'] = '0000-00-00 00:00:00';
    output('`7So schwer es dir auch fällt, dich von deinem '.$playerpet['name'].' zu trennen, tust du es doch und eine einsame Träne entkommt deinen Augen.`n`n
    Aber in dem Moment, in dem du die `%'.$petrepaygems.'`7 Edelsteine erblickst, fühlst du dich gleich ein wenig besser.');
    debuglog('gained '.$petrepaygems.' gems selling their pet');
}
elseif($_GET['op']=='sellmount')
{
    $session['user']['gold']+=$repaygold;
    $session['user']['gems']+=$repaygems;
    $session['user']['hashorse']=0;
    debuglog('gained '.$repaygold.' gold and '.$repaygems.' gems selling their mount');
    unset($session['bufflist']['mount']);
    cache_release_local('playermount');

    $sql = 'UPDATE account_extra_info SET hasxmount=0,mountextrarounds=0,xmountname="",mount_sausage=0 WHERE acctid='.$session['user']['acctid'];
    db_query($sql);

    output('`7So schwer es dir auch fällt, dich von deinem '.$playermount['mountname'].'`7 zu trennen, tust du es doch und eine einsame Träne entkommt deinen Augen.`n`n
    Aber in dem Moment, in dem du die '.($repaygold>0?'`^'.$repaygold.'`7 Gold '.($repaygems>0?' und ':''):'').($repaygems>0?'`%'.$repaygems.'`7 Edelsteine':'').'`7 erblickst, fühlst du dich gleich ein wenig besser.');
    $session['user']['reputation']-=2;
} 
elseif ($_GET['op']=='futterpet') 
{
    if (empty($_POST['days'])) {
        output('`0Das Futter kostet `^'.$playerpet['value1'].' Gold`0 und
        `%'.$playerpet['value2'].' Edelsteine`0 pro '.(getsetting('dayparts','1') > 1?'Tagesabschnitt':'Tag').'.`n
        <form action="stables.php?op=futterpet" method="post">
        Für wie viele '.(getsetting('dayparts','1') > 1?'Tagesabschnitte':'Tage').' möchtest du Futter kaufen?
        <input type="text" name="days" value="0"> <input type="submit" value="Kaufen!">
        </form>');
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
            output('Lupus nimmt die '.$coststr.' und gibt dir genug Futter, um dein(e/n) '.$playerpet['name'].' die nächsten '.$days.' Tage zu versorgen.`n');
            $oldtime = strtotime($session['user']['petfeed']);
            if ($oldtime < time()) $oldtime = time();
            $newtime = $oldtime + $days * (3600*24 / getsetting("daysperday",4));
            $session['user']['petfeed'] = date('Y-m-d H:i:s',$newtime);
        }
        else {
            output('`7Du kannst das Futter nicht bezahlen. Lupus weigert sich, dein Tier für dich durchzufüttern.');
        }
    }
}
elseif($_GET['op']=='futter')
{
    if ($session['user']['gold']>=$futtercost || $_GET['what']=='coupon') 
    {
        getmount($session['user']['hashorse'],true);

        $sql = 'SELECT mountextrarounds,hasxmount,xmountname FROM account_extra_info WHERE acctid='.$session['user']['acctid'];
        $result = db_query($sql);
        $rowm = db_fetch_assoc($result);

        $buff = unserialize($playermount['mountbuff']);
        if($_GET['what']=='coupon') //Idee von plueschdrache.de
        {
            output('Dein '.$playermount['mountname'].'`7 macht sich gierig über den Gutschein her. So war das eigentlich nicht gedacht, aber wenns schmeckt, kann man nichts machen...`nDein '.$playermount['mountname'].'`7 ist vollständig regeneriert.');
            item_delete('tpl_id="feedcoupon" AND owner='.$session['user']['acctid'],1);
        }
        else if ($session['bufflist']['mount']['rounds']-$rowm['mountextrarounds'] == $buff['rounds']) 
        {
            output('Dein '.$playermount['mountname'].'`7 ist satt und rührt das vorgesetzte Futter nicht an. Darum gibt Lupus dir dein Gold zurück.');
        }
        else if ($session['bufflist']['mount']['rounds']-$rowm['mountextrarounds'] > $buff['rounds']*0.5) 
        {
            $futtercost=$futtercost/2;
            output('Dein '.$playermount['mountname'].'`7 nascht etwas von dem vorgesetzten Futter und lässt den Rest stehen. '.$playermount['mountname'].'`7 ist voll regeneriert. 
            Da aber noch über die Hälfte des Futters übrig ist, gibt dir Lupus 50% Preisnachlass.`nDu bezahlst nur '.$futtercost.' Gold.');
            $session['user']['gold']-=$futtercost;
            $session['user']['reputation']--;
        }
        else
        {
            $session['user']['gold']-=$futtercost;
            output('Dein '.$playermount['mountname'].' macht sich gierig über das Futter her und frisst es bis auf den letzten Krümel.`n
            Dein '.$playermount['mountname'].' ist vollständig regeneriert und du gibst Lupus die '.$futtercost.' Gold.');
            $session['user']['reputation']--;
        }
        
        $session['bufflist']['mount']=$buff;
        $session['bufflist']['mount']['rounds']+=$rowm['mountextrarounds'];
        if ($rowm['hasxmount']==1) 
        {
            $session['bufflist']['mount']['name']=$rowm['xmountname'].' `&('.$session['bufflist']['mount']['name'].'`&)'; 
        }
        $session['user']['fedmount']=1;
    } 
    else 
    {
        output('`7Du hast nicht genug Gold dabei, um das Futter zu bezahlen. Lupus weigert sich dein Tier für dich durchzufüttern und empfiehlt dir, im Wald nach einer grasbewachsenen Lichtung zu suchen.');
    }
} 
elseif ($_GET['op']=='noname')
{
  output('`7Lupus sieht dich zwar etwas zweifelnd an, erfüllt dir jedoch deinen Wunsch. Von nun an ist dein '.$playermount['mountname'].' `7wieder bekannt als... '.$playermount['mountname'].'.`n`n');
  $sql = 'UPDATE account_extra_info SET hasxmount=0,xmountname="" WHERE acctid='.$session['user']['acctid'];
  db_query($sql);
  
  $arr_buff = $session['bufflist']['mount'];
  $mount_name = $playermount['mountname'];
    $mount_rounds = $arr_buff['rounds'];
    cache_release_local('playermount');
    getmount($session['user']['hashorse'],true);
    $session['bufflist']['mount']['name'] = $mount_name;
    $session['bufflist']['mount']['mountbuff']['rounds'] = $mount_rounds;            
      
  addnav('Zu den Ställen','stables.php');
    page_footer();
}
elseif ($_GET['op']=='name')
{
    getmount($session['user']['hashorse'],true);
    $n = $playermount['mountname'];
    $cost = $_GET['cost'];
    $msg = '';
    $pointsavailable=$session['user']['donation']-$session['user']['donationspent'];
    
    if ($pointsavailable < $cost) {
        output('Eine Taufe kostet '.$cost.' Punkte, aber du hast nur '.$pointsavailable.' Punkte.');
        addnav('Zu den Ställen','stables.php');
        page_footer();
    }
    
    if(isset($_POST['newname'])) {
    
        $newname = str_replace('`0','',stripslashes($_POST['newname']));
                
        // Alle anderen Tags als erlaubte Farbcodes rausschmeißen
        $newname = preg_replace('/[`][^'.regex_appoencode(1,false).']/','',$newname);
        
        if(strlen($newname) == 0) {
            $msg.='Einfalls-Los, gefällig?`n';
        }
                
        if (strlen($newname)>25) {
            $msg.='Der neuer Name ist zu lang, inklusive Farbcodes darf er nicht länger als 25 Zeichen sein.`n';
        }
        
        $colorcount = substr_count($_POST['newname'],'`');
        if (getsetting('mount_maxcolors',10) != -1 && $colorcount>getsetting('mount_maxcolors',10))
        {
            $msg.='`0Du hast zu viele Farben im Namen benutzt. Du kannst maximal '.getsetting('mount_maxcolors',10).' Farbcodes benutzen.`n';
        }
        
        // Umbenennen!
        if (empty($msg)){
            $session['user']['donationspent']+=$cost;
            $sql = 'UPDATE account_extra_info SET rename_mount=1,hasxmount=1,xmountname="'.addslashes($newname).'" WHERE acctid='.$session['user']['acctid'];
            db_query($sql);
            output('`&Lupus hebt zeremoniell seine Peitsche und verkündet:`n"`3Und im Namen von Epona, Fury und Lassie taufe ich dich auf den Name '.$newname.'`3!`&"`n`n');
            
            $arr_buff = $session['bufflist']['mount'];
            $mount_name = $newname.' `&('.$playermount['mountname'].'`&)';
            $mount_rounds = $arr_buff['rounds'];
            cache_release_local('playermount');
            getmount($session['user']['hashorse'],true);
            $session['bufflist']['mount']['name'] = $mount_name;
            $session['bufflist']['mount']['mountbuff']['rounds'] = $mount_rounds;            
            addnav('Zu den Ställen','stables.php');
            page_footer();
        }
        else{
            output('`b`$Falscher Name!`0`b`&`n'.$msg.'`n');
        }
    }
    
    $sql = 'SELECT mountextrarounds,hasxmount,xmountname FROM account_extra_info WHERE acctid='.$session['user']['acctid'];
    $result = db_query($sql);
    $rowm = db_fetch_assoc($result);
    output('`bDein Tier (um)taufen`b`n`n
    `n`nDer Name deines treuen Freundes darf 25 Zeichen lang sein und Farbcodes enthalten.`n`n
    Dein Tier heißt bisher : `n
    '.($rowm['hasxmount']==1?$rowm['xmountname']:$n).'
    `n`n`0Wie soll dein Tier ab sofort heißen ?`n');
    rawoutput("<form action='stables.php?op=name&amp;cost=$cost' method='POST'>
                <input name='newname' id='newname' value=\"".
                (!empty($newname) ? $newname : 
                                        ($rowm['hasxmount']==1 ? $rowm['xmountname']:'')
                                    )."\" size=\"30\" maxlength=\"25\">");
    output('    `n`nVorschau: '.js_preview('newname').'
                `n`n<input type="submit" class="button" value="JA, Tier für '.$cost.' DP auf diesen Namen taufen!"></form>',true);
    addnav('','stables.php?op=name&cost='.$cost);
}

getmount($session['user']['hashorse'],true);
$pointsavailable=$session['user']['donation']-$session['user']['donationspent'];
$playerpet = getpet((int)$session['user']['petid']);
$petrepaygems = round($playerpet['gems']*2/3);
$repaygold = round($playermount['mountcostgold']*0.45,0);
$repaygems = round($playermount['mountcostgems']*0.45,0);
$futtercost = $session['user']['level']*20;

addnav('Spielen');
addnav('Hasenjagd','bunnyhunt.php');

if ($session['user']['hashorse']>0 && $session['user']['fedmount']==0)
{
    addnav('Begleiter-Futter');
    addnav('f?'.$playermount['mountname'].' füttern (`^'.$futtercost.'`0 Gold)','stables.php?op=futter');
    if(item_count('tpl_id="feedcoupon" AND owner='.$session['user']['acctid'])>=1)
    {
        addnav('G?'.$playermount['mountname'].' mit Gutschein füttern','stables.php?op=futter&what=coupon');
    }
}
if ($session['user']['petid']>0)
{
    addnav('Hauswächter-Futter');
    addnav('t?'.$playerpet['name'].' füttern','stables.php?op=futterpet');
}
$sql = 'SELECT hasxmount,rename_mount FROM account_extra_info WHERE acctid='.$session['user']['acctid'];
$result = db_query($sql);
$rowt = db_fetch_assoc($result);
if ($rowt['hasxmount']==1 || $rowt['rename_mount']==1)
{
    $req=10;
}
else
{
    $req=100;
}
if (($pointsavailable>=$req) && ($session['user']['hashorse']>0)) 
{
    addnav('Spezial');
    if ($rowt['hasxmount']==1)
    {
        addnav($playermount['mountname'].'`0 umtaufen (10 DP)','stables.php?op=name&cost=10');
    addnav('Taufe aufheben','stables.php?op=noname',false,false,false,false,'Willst du wirklich den Namen deines Tieres aufgeben?');
    }
    else if ($rowt['rename_mount']==1)
    {
        addnav($playermount['mountname'].'`0 taufen (10 DP)','stables.php?op=name&cost=10');
    }
    else
    {
        addnav($playermount['mountname'].'`0 taufen (100 DP)','stables.php?op=name&cost=100');
    }
}
if($session['user']['exchangequest']==22)
{
    addnav('Nach einem Zahn fragen','exchangequest.php?op=stables');
}
$sql = 'SELECT mountname,mountid,mountcategory FROM mounts WHERE mountactive=1 ORDER BY mountcategory,mountcostgems,mountcostgold';
$result = db_query($sql);
$category='';

$count = db_num_rows($result);

for ($i=0;$i<$count;$i++)
{
    $row = db_fetch_assoc($result);
    if ($category!=$row['mountcategory']){
        addnav($row['mountcategory']);
        $category = $row['mountcategory'];
    }
    $row['mountname'] = strip_appoencode($row['mountname'],3);
    addnav('Betrachte '.$row['mountname'].'`0','stables.php?op=examine&id='.$row['mountid'],false,false,false,false);
}
if ($session['user']['house']>0) {

    $result = item_tpl_list_get(' stables_pet>0 ', ' ORDER BY tpl_gold ASC, tpl_gems ASC ','tpl_name,tpl_id');
    if (db_num_rows($result)>0)
    {
        addnav('Hauswächter');
        while ($row = db_fetch_assoc($result)) {
            addnav('Betrachte '.$row['tpl_name'].'`0','stables.php?op=examinepet&id='.$row['tpl_id'],false,false,false,false);
        }
    }
}
if ($session['user']['hashorse']>0)
{
    getmount($session['user']['hashorse'],true);
    $repaygold = round($playermount['mountcostgold']*0.45,0);
    $repaygems = round($playermount['mountcostgems']*0.45,0);
    output('`n`n`0Lupus würde dir '.$playermount['mountname'].' für `^'.$repaygold.'`0 Gold und `%'.$repaygems.'`0 Edelsteine abkaufen.');
    addnav('Sonstiges');
    addnav('Verkaufe '.$playermount['mountname'],'stables.php?op=sellmount',false,false,false,false);
}
if ($session['user']['petid']>0)
{
    if ($session['user']['hashorse']==0) addnav("Sonstiges");
    output('`n`n`0Lupus würde dir '.$playerpet['name'].' für `%'.$petrepaygems.'`0 Edelsteine abkaufen.');
    addnav('Verkaufe '.$playerpet['name'],'stables.php?op=sellpet',false,false,false,false);
}
page_footer();
?>


