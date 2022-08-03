<?php

// 24062004

require_once "common.php";
page_header("Mericks Ställe");
// Haustier-Mod by Chaosmaker <webmaster@chaosonline.de>
    // http://logd.chaosonline.de
    function getpet($petid=0) {
        $sql = "SELECT * FROM items WHERE id='$petid'";
        $result = db_query($sql);
        if (db_num_rows($result)>0) {
            $row = db_fetch_assoc($result);
            $row['buff'] = unserialize($row['buff']);
            return $row;
        }
        else {
            return array();
        }
    }
    $playerpet = getpet($session['user']['petid']);
    $petrepaygems = round($playerpet['gems']*2/3);

$repaygold = round($playermount['mountcostgold']*2/3,0);
$repaygems = round($playermount['mountcostgems']*2/3,0);
$futtercost = $session[user][level]*20;
$userdk = $session['user']['dragonkills'];
$userdp = $session['user']['donation'];

addnav("Zurück zum Hafen","hafen.php");
//if ($session['user']['hashorse']>0 && $session[user][fedmount]<=2) addnav("f?{$playermount['mountname']} füttern (`^$futtercost`0 Gold)","stables.php?op=futter");
//if ($session['user']['petid']>0) addnav("t?{$playerpet['name']} füttern","stables.php?op=futterpet");

if ($_GET[op]==""){
    checkday();
    output("`7Hinter der Kneipe, etwas links von Pegasus' Rüstungen, befindet sich ein Stall, 
    wie man ihn in jedem Dorf erwartungsgemäß findet. 
    Darin kümmert sich Merick, ein stämmig wirkender Zwerg, um verschiedene Tiere.
    `n`n
    Du näherst dich ihm, als er plötzlich herumwirbelt und seine Heugabel in deine ungefähre Richtung streckt. \"`&Ach, 
    'tschuldigung min ".($session[user][sex]?"Mädl":"Jung").", heb dich nit kommen hörn un heb gedenkt,
    du bischt sicha Cedrik, der ma widda sein Zwergenweitwurf ufbessern will. Naaahw, wat 
    kann ich für disch tun?`7\"");
} elseif ($_GET['op']=="examinepet") {
        $pet = getpet($_GET['id']);
        if (count($pet)==0) {
            output("`7\"`&Ach, ich heb keen solches Tier da!`7\" ruft der Zwerg!");
        }
        else {
            output("`7\"`&Ai, ich heb wirklich n paar feine Viecher hier!`7\" kommentiert der Zwerg.`n`n");
            output("`7Kreatur: `&{$pet['name']}`n");
            output("`7Beschreibung: `&{$pet['description']}`n");
            output("`7Preis: `^{$pet['gold']}`& Gold, `%{$pet['gems']}`& Edelstein".($pet['gems']==1?"":"e")."`n");
            output("`n");
            addnav("Dieses Tier kaufen","stables.php?op=buypet&id={$pet['id']}");
        }
}elseif($_GET['op']=="examine"){
    $sql = "SELECT * FROM mounts WHERE mountid='{$_GET['id']}'";
    $result = db_query($sql);
    if (db_num_rows($result)<=0){
        output("`7\"`&Ach, ich heb keen solches Tier da!`7\" ruft der Zwerg!");
    }else{
        output("`7\"`&Ai, ich heb wirklich n paar feine Viecher hier!`7\" kommentiert der Zwerg.`n`n");
        $mount = db_fetch_assoc($result);
        output("`7Kreatur: `&{$mount['mountname']}`n");
        output("`7Beschreibung: `&{$mount['mountdesc']}`n");
        output("`7Preis: `^{$mount['mountcostgold']}`& Gold, `%{$mount['mountcostgems']}`& Edelstein".($mount['mountcostgems']==1?"":"e")."`n");
        output("`n");
        addnav("Dieses Tier kaufen","stables.php?op=buymount&id={$mount['mountid']}");
    }
} elseif ($_GET['op']=='buypet') {
        $pet = getpet($_GET['id']);
        if (count($pet)==0) {
            output("`7\"`&Ach, ich heb keen solches Tier da!`7\" ruft der Zwerg!");
        }
        else {
            if (
                $session['user']['gold'] < $pet['gold']
                 ||
                ($session['user']['gems']+$petrepaygems) < $pet['gems']
            ){
                output("`7Merick schaut dich schief von der Seite an. \"`&Ähm, was gläubst du was du hier machst? Kanns u nich sehen, dass {$pet['name']} `^{$pet['gold']}`& Gold und `%{$pet['gems']}`& Edelsteine kostet?`7\"");
            }
            else {
                $feeddays = getsetting("daysperday",4);
                if ($session['user']['petid']>0) {
                    output("`7Du übergibst dein(e/n) {$playerpet['name']} und bezahlst den Preis für dein neues Tier. Merick führt ein(e/n) schöne(n/s) neue(n/s) `&{$pet['name']}`7  für dich heraus und gibt dir Futter für $feeddays Tage dazu!`n`n");
                }
                else {
                    output("`7Du bezahlst den Preis für dein neues Tier und Merick führt ein(e/n) schöne(n/s) neue(n/s) `&{$pet['name']}`7 für dich heraus und gibt dir Futter für $feeddays Tage dazu!`n`n");
                }
                // delete old pet
                $sql = 'DELETE FROM items WHERE id='.$session['user']['petid'];
                db_query($sql);
                // insert new pet
                $sql = "INSERT INTO items (name,class,owner,value1,value2,gold,gems,description,hvalue,buff)
                        VALUES ('{$pet['name']}','Haustiere',{$session['user']['acctid']},{$pet['value1']},{$pet['value2']},{$pet['gold']},{$pet['gems']},'".addslashes($pet['description'])."',{$session['user']['house']},'".addslashes(serialize($pet['buff']))."')";
                db_query($sql);
                $session['user']['petid'] = db_insert_id(LINK);
                $session['user']['petfeed'] = date('Y-m-d H:i:s',time() + $feeddays * (3600*24 / getsetting("daysperday",4)));
                $goldcost = -$pet['gold'];
                $session['user']['gold'] += $goldcost;
                $gemcost = $petrepaygems - $pet['gems'];
                $session['user']['gems'] += $gemcost;
                debuglog(($goldcost <= 0?"spent ":"gained ") . abs($goldcost) . " gold and " . ($gemcost <= 0?"spent ":"gained ") . abs($gemcost) . " gems trading for a new pet");
                // Recalculate so the selling stuff works right
                $playerpet = $pet;
                $petrepaygems = round($playerpet['gems']*2/3,0);
            }
        }
}elseif($_GET['op']=='buymount'){
    $sql = "SELECT * FROM mounts WHERE mountid='{$_GET['id']}'";
    $result = db_query($sql);
    if (db_num_rows($result)<=0){
        output("`7\"`&Ach, ich heb keen solches Tier da!`7\" ruft der Zwerg!");
    }else{
        $mount = db_fetch_assoc($result);
        if ( 
            ($session['user']['gold']+$repaygold) < $mount['mountcostgold']
             || 
            ($session['user']['gems']+$repaygems) < $mount['mountcostgems']
        ){
            output("`7Merick schaut dich schief von der Seite an. \"`&Ähm, was gläubst du was du hier machst? Kanns u nich sehen, dass {$mount['mountname']} `^{$mount['mountcostgold']}`& Gold und `%{$mount['mountcostgems']}`& Edelsteine kostet?`7\"");
        }else{
            if ($session['user']['hashorse']>0){
                output("`7Du übergibst dein(e/n) {$playermount['mountname']} und bezahlst den Preis für dein neues Tier. Merick führt ein(e/n) schöne(n/s) neue(n/s) `&{$mount['mountname']}`7  für dich heraus!`n`n");
                $session[user][reputation]--;
            }else{
                output("`7Du bezahlst den Preis für dein neues Tier und Merick führt ein(e/n) schöne(n/s) neue(n/s) `&{$mount['mountname']}`7 für dich heraus!`n`n");
            }
            $session['user']['hashorse']=$mount['mountid'];
            $goldcost = $repaygold-$mount['mountcostgold'];
            $session['user']['gold']+=$goldcost;
            $gemcost = $repaygems-$mount['mountcostgems'];
            $session['user']['gems']+=$gemcost;
            debuglog(($goldcost <= 0?"spent ":"gained ") . abs($goldcost) . " gold and " . ($gemcost <= 0?"spent ":"gained ") . abs($gemcost) . " gems trading for a new mount");
            $session['bufflist']['mount']=unserialize($mount['mountbuff']);
            // Recalculate so the selling stuff works right
            $playermount = getmount($mount['mountid']);
            $repaygold = round($playermount['mountcostgold']*2/3,0);
            $repaygems = round($playermount['mountcostgems']*2/3,0);
        }
    }
} elseif ($_GET['op']=='sellpet') {
        $sql = 'DELETE FROM items WHERE id='.$session['user']['petid'];
        db_query($sql);
        $session['user']['gems'] += $petrepaygems;
        debuglog("gained $petrepaygems gems selling their pet");
        $session['user']['petid'] = 0;
        $session['user']['petfeed'] = '0000-00-00 00:00:00';
        output("`7So schwer es dir auch fällt, dich von dein(er/em) {$playerpet['name']} zu trennen, tust du es doch und eine einsame Träne entkommt deinen Augen.`n`n");
        output("Aber in dem Moment, in dem du die `%$petrepaygems`7 Edelsteine erblickst, fühlst du dich gleich ein wenig besser.");
}elseif($_GET['op']=='sellmount'){
    $session['user']['gold']+=$repaygold;
    $session['user']['gems']+=$repaygems;
    debuglog("gained $repaygold gold and $repaygems gems selling their mount");
    unset($session['bufflist']['mount']);
    $session['user']['hashorse']=0;
    output("`7So schwer es dir auch fällt, dich von dein(er/em) {$playermount['mountname']} zu trennen, tust du es doch und eine einsame Träne entkommt deinen Augen.`n`n");
    output("Aber in dem Moment, in dem du die ".($repaygold>0?"`^$repaygold`7 Gold ".($repaygems>0?" und ":""):"").($repaygems>0?"`%$repaygems`7 Edelsteine":"")." erblickst, fühlst du dich gleich ein wenig besser.");
    $session[user][reputation]-=2;
} elseif ($_GET['op']=='futterpet') {
        if (empty($_POST['days'])) {
            output('Das Futter kostet `^'.$playerpet['value1'].' Gold`0 und
                    `%'.$playerpet['value2'].' Edelsteine`0 pro Tag.`n');
            output('<form action="stables.php?op=futterpet" method="post">',true);
            output('Für wie viele Tage möchtest du Futter kaufen?');
            output('<input type="text" name="days" value="0"> <input type="submit" value="Kaufen!">',true);
            output('</form>',true);
            addnav('','stables.php?op=futterpet');
        }
        else {
            $days = (int)$_POST['days'];
            if ($session['user']['gold']>=$playerpet['value1']*$days && $session['user']['gems']>=$playerpet['value2']*$days) {
                $session['user']['gold'] -= $playerpet['value1']*$days;
                $session['user']['gems'] -= $playerpet['value2']*$days;
                if ($playerpet['value1']>0) {
                    if ($playerpet['value2']>0) {
                        $coststr = '`^'.($playerpet['value1']*$days).' Gold`0 und `%'.($playerpet['value2']*$days).' Edelsteine`0';
                    }
                    else $coststr = '`^'.($playerpet['value1']*$days).' Gold`0';
                }
                else {
                    $coststr = '`%'.($playerpet['value2']*$days).' Edelsteine`0';
                }
                output('Merick nimmt die '.$coststr.' und gibt dir genug Futter, um dein(e/n) '.$playerpet['name'].' die nächsten '.$days.' Tage zu versorgen.`n');
                $oldtime = strtotime($session['user']['petfeed']);
                if ($oldtime < time()) $oldtime = time();
                $newtime = $oldtime + $days * (3600*24 / getsetting("daysperday",4));
                $session['user']['petfeed'] = date('Y-m-d H:i:s',$newtime);
            }
            else {
                    output('`7Du kannst das Futter nicht bezahlen. Merick weigert sich, dein Tier für dich durchzufüttern.');
            }
        }
}elseif($_GET['op']=='futter'){
    if ($session[user][gold]>=$futtercost) {
                $buff = unserialize($playermount['mountbuff']);
                if ($session['bufflist']['mount']['rounds'] == $buff['rounds']) {
            output("Dein {$playermount['mountname']} ist satt und rührt das vorgesetzte Futter nicht an. Darum gibt Merick dir dein Gold zurück.");
        }else if ($session['bufflist']['mount']['rounds'] > $buff['rounds']*.5) {
            $futtercost=$futtercost/2;
            output("Dein {$playermount['mountname']} nascht etwas von dem vorgesetzten Futter und lässt den Rest stehen. {$playermount['mountname']} ist voll regeneriert. ");
            output("Da aber noch über die Hälfte des Futters übrig ist, gibt dir Merick 50% Preisnachlass.`nDu bezahlst nur $futtercost Gold.");
            $session[user][gold]-=$futtercost;
            $session[user][reputation]--;
        }else{
            $session[user][gold]-=$futtercost;
            output("Dein {$playermount['mountname']} macht sich gierig über das Futter her und frisst es bis auf den letzten Krümel.`n");
            output("Dein {$playermount['mountname']} ist vollständig regeneriert und du gibst Merick die $futtercost Gold."); 
            $session[user][reputation]--;
        }
               $session['bufflist']['mount']=$buff;
        $session[user][fedmount]++;
    } else {
        output("`7Du hast nicht genug Gold dabei, um das Futter zu bezahlen. Merick weigert sich dein Tier für dich durchzufüttern und empfiehlt dir, im Wald nach einer grasbewachsenen Lichtung zu suchen.");
    }
}

$sql = "SELECT mountname,mountid,mountcategory FROM mounts WHERE mountactive=1  ORDER BY mountcategory,mountcostgems,mountcostgold";
$result = db_query($sql);
$category="";
for ($i=0;$i<db_num_rows($result);$i++){
    $row = db_fetch_assoc($result);
    if ($category!=$row['mountcategory']){
        addnav($row['mountcategory']);
        $category = $row['mountcategory'];
    }
    addnav("Betrachte {$row['mountname']}`0","stables.php?op=examine&id={$row['mountid']}");
}
if ($session['user']['housekey']>0) {
        $sql = 'SELECT name, id FROM items WHERE class="Haust.Prot" ORDER BY gold ASC, gems ASC';
        $result = db_query($sql);
        if (db_num_rows($result)>0) {
            addnav('Haustiere');
            while ($row = db_fetch_assoc($result)) {
                addnav("Betrachte {$row['name']}`0",'stables.php?op=examinepet&id='.$row['id']);
            }
        }
    }
if ($session['user']['hashorse']>0){
    output("`n`nMerick bietet dir `^$repaygold`& Gold und `%$repaygems`& Edelsteine für dein(e/n) {$playermount['mountname']}.");
    addnav("Sonstiges");
    addnav("Verkaufe {$playermount['mountname']}","stables.php?op=sellmount");
}
if ($session['user']['petid']>0) {
        if ($session['user']['hashorse']==0) addnav("Sonstiges");
        output("`n`nMerick bietet dir `%$petrepaygems`7 Edelsteine für dein(e/n) {$playerpet['name']}.");
        addnav("Verkaufe {$playerpet['name']}","stables.php?op=sellpet");
    }

page_footer();
?>