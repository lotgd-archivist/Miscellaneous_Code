
<?php

require_once("common.php");

page_header("Mericks StÃ¤lle");



// Haustier-Mod by Chaosmaker <webmaster@chaosonline.de>

// http://logd.chaosonline.de

function getpet($petid=0) {

    $sql = "SELECT * FROM items WHERE id='$petid'";

    $result = db_query($sql);

    if (db_num_rows($result)>0) {

        $row = db_fetch_assoc($result);

        $row['buff'] = unserialize($row['buff']);

        return $row;

    }

    else {

        return array();

    }

}

$playerpet = getpet($session['user']['petid']);

$petrepaygems = round($playerpet['gems']*2/3);



$repaygold = round($playermount['mountcostgold']*2/3,0);

$repaygems = round($playermount['mountcostgems']*2/3,0);

$futtercost = $session['user']['level']*20;



addnav("Z?ZurÃ¼ck zum Dorf","village.php");



if ($_GET['op']==""){

    checkday();

    output("`7Hinter der Kneipe, etwas links von Pegasus' RÃ¼stungen, befindet sich ein Stall,

    wie man ihn in jedem Dorf erwartungsgemÃ¤ÃŸ findet.

    Darin kÃ¼mmert sich Merick, ein stÃ¤mmig wirkender Zwerg, um verschiedene Tiere.`nDu siehst Tiere, die dir im Kampf

    zur Seite stehen kÃ¶nnten, Tiere, auf denen du reiten kannst und Haustiere.");

    if ($session['user']['housekey']) output("`nDu kannst zusÃ¤tzlich zu einem normalen Tier ein Wachtier besitzen.");

    output("`n`n

    Du nÃ¤herst dich ihm, als er plÃ¶tzlich herumwirbelt und seine Heugabel in deine ungefÃ¤hre Richtung streckt. \"`&Ach,

    'tschuldigung min ".($session['user']['sex']?"MÃ¤dl":"Jung").", heb dich nit kommen hÃ¶rn un heb gedenkt,

    du bischt sicha Cedrik, der ma widda sein Zwergenweitwurf ufbessern will. Naaahw, wat

    kann ich fÃ¼r disch tun?`7\"");

} elseif ($_GET['op']=="examinepet") {

    $pet = getpet($_GET['id']);

    if (count($pet)==0) {

        output("`7\"`&Ach, ich heb keen solches Tier da!`7\" ruft der Zwerg!");

    }

    else {

        output("`7\"`&Ai, ich heb wirklich n paar feine Viecher hier!`7\" kommentiert der Zwerg.`n`n");

        output("`7Kreatur: `&{$pet['name']}`n");

        output("`7Beschreibung: `&{$pet['description']}`n");

        output("`7Preis: `^{$pet['gold']}`& Gold, `%{$pet['gems']}`& Edelstein".($pet['gems']==1?"":"e")."`n");

        output("`n");

        addnav("Dieses Tier kaufen","stables.php?op=buypet&id={$pet['id']}");

    }

}elseif($_GET['op']=="examine"){

    $sql = "SELECT * FROM mounts WHERE mountid='{$_GET['id']}'";

    $result = db_query($sql);

    if (db_num_rows($result)<=0){

        output("`7\"`&Ach, ich heb keen solches Tier da!`7\" ruft der Zwerg!");

    }else{

        output("`7\"`&Ai, ich heb wirklich n paar feine Viecher hier!`7\" kommentiert der Zwerg.`n`n");

        $mount = db_fetch_assoc($result);

        output("`7Kreatur: `&{$mount['mountname']}`n");

        output("`7Beschreibung: `&{$mount['mountdesc']}`n");

        output("`7Preis: `^{$mount['mountcostgold']}`& Gold, `%{$mount['mountcostgems']}`& Edelstein".($mount['mountcostgems']==1?"":"e")."`n");

        output("`n");

        addnav("Dieses Tier kaufen","stables.php?op=buymount&id={$mount['mountid']}");

    }

} elseif ($_GET['op']=='buypet') {

    $pet = getpet($_GET['id']);

    if (count($pet)==0) {

        output("`7\"`&Ach, ich heb keen solches Tier da!`7\" ruft der Zwerg!");

    }

    else {

        if (

            $session['user']['gold'] < $pet['gold']

             ||

            ($session['user']['gems']+$petrepaygems) < $pet['gems']

        ){

            output("`7Merick schaut dich schief von der Seite an. \"`&Ã„hm, was glÃ¤ubst du was du hier machst? Kanns u nich sehen, dass {$pet['name']} `^{$pet['gold']}`& Gold und `%{$pet['gems']}`& Edelsteine kostet?`7\"");

        }

        else {

            $feeddays = getsetting("daysperday",4);

            if ($session['user']['petid']>0) {

                output("`7Du Ã¼bergibst dein(e/n) {$playerpet['name']} und bezahlst den Preis fÃ¼r dein neues Tier. Merick fÃ¼hrt ein(e/n) schÃ¶ne(n/s) neue(n/s) `&{$pet['name']}`7  fÃ¼r dich heraus und gibt dir Futter fÃ¼r $feeddays Tage dazu!`n`n");

            }

            else {

                output("`7Du bezahlst den Preis fÃ¼r dein neues Tier und Merick fÃ¼hrt ein(e/n) schÃ¶ne(n/s) neue(n/s) `&{$pet['name']}`7 fÃ¼r dich heraus und gibt dir Futter fÃ¼r $feeddays Tage dazu!`n`n");

            }

            // delete old pet

            db_query("DELETE FROM items WHERE id='".$session['user']['petid']."'");

            // insert new pet

            $sql = "INSERT INTO items (name,class,owner,value1,value2,gold,gems,description,hvalue,buff)

                    VALUES ('".$pet['name']."','Haustiere','".$session['user']['acctid']."','".$pet['value1']."','".$pet['value2']."','".$pet['gold']."','".$pet['gems']."','".addslashes($pet['description'])."','".$session['user']['house']."','".addslashes(serialize($pet['buff']))."')";

            db_query($sql);

            $petID = db_fetch_assoc(db_query("SELECT * FROM items WHERE owner='".$session['user']['acctid']."' AND class='Haustiere' LIMIT 1"));

            $session['user']['petid'] = $petID['id'];

            $session['user']['petfeed'] = date('Y-m-d H:i:s',time() + $feeddays * (3600*24 / getsetting("daysperday",4)));

            $goldcost = -$pet['gold'];

            $session['user']['gold'] += $goldcost;

            $gemcost = $petrepaygems - $pet['gems'];

            $session['user']['gems'] += $gemcost;

            //debuglog(($goldcost <= 0?"spent ":"gained ") . abs($goldcost) . " gold and " . ($gemcost <= 0?"spent ":"gained ") . abs($gemcost) . " gems trading for a new pet");

            // Recalculate so the selling stuff works right

            $playerpet = $pet;

            $petrepaygems = round($playerpet['gems']*2/3,0);

        }

    }

}elseif($_GET['op']=='buymount'){

    $sql = "SELECT * FROM mounts WHERE mountid='".$_GET['id']."'";

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

            output("`7Merick schaut dich schief von der Seite an. \"`&Ã„hm, was glÃ¤ubst du was du hier machst? Kanns u nich sehen, dass ".$mount['mountname']." `^".$mount['mountcostgold']."`& Gold und `%".$mount['mountcostgems']."`& Edelsteine kostet?`7\"");

        }else{

            if ($session['user']['hashorse']>0){

                output("`7Du Ã¼bergibst dein(e/n) ".$playermount['mountname']." und bezahlst den Preis fÃ¼r dein neues Tier. Merick fÃ¼hrt ein(e/n) schÃ¶ne(n/s) neue(n/s) `&".$mount['mountname']."`7  fÃ¼r dich heraus!`n`n");

                $session['user']['reputation']--;

            }else{

                output("`7Du bezahlst den Preis fÃ¼r dein neues Tier und Merick fÃ¼hrt ein(e/n) schÃ¶ne(n/s) neue(n/s) `&".$mount['mountname']."`7 fÃ¼r dich heraus!`n`n");

            }

            $session['user']['hashorse']=$mount['mountid'];

            $goldcost = $repaygold-$mount['mountcostgold'];

            $session['user']['gold']+=$goldcost;

            $gemcost = $repaygems-$mount['mountcostgems'];

            $session['user']['gems']+=$gemcost;

            //debuglog(($goldcost <= 0?"spent ":"gained ") . abs($goldcost) . " gold and " . ($gemcost <= 0?"spent ":"gained ") . abs($gemcost) . " gems trading for a new mount");

            $session['bufflist']['mount']=unserialize($mount['mountbuff']);

            // Recalculate so the selling stuff works right

            $playermount = getmount($mount['mountid']);

            $repaygold = round($playermount['mountcostgold']*2/3,0);

            $repaygems = round($playermount['mountcostgems']*2/3,0);

        }

    }

} elseif ($_GET['op']=='sellpet') {

    $sql = 'DELETE FROM items WHERE id='.$session['user']['petid'];

    db_query($sql);

    $session['user']['gems'] += $petrepaygems;

    debuglog("gained $petrepaygems gems selling their pet");

    $session['user']['petid'] = 0;

    $session['user']['petfeed'] = '0000-00-00 00:00:00';

    output("`7So schwer es dir auch fÃ¤llt, dich von dein(er/em) {$playerpet['name']} zu trennen, tust du es doch und eine einsame TrÃ¤ne entkommt deinen Augen.`n`n");

    output("Aber in dem Moment, in dem du die `%$petrepaygems`7 Edelsteine erblickst, fÃ¼hlst du dich gleich ein wenig besser.");

}elseif($_GET['op']=='sellmount'){

    $session['user']['gold']+=$repaygold;

    $session['user']['gems']+=$repaygems;

    debuglog("gained $repaygold gold and $repaygems gems selling their mount");

    unset($session['bufflist']['mount']);

    $session['user']['hashorse']=0;

    output("`7So schwer es dir auch fÃ¤llt, dich von dein(er/em) {$playermount['mountname']} zu trennen, tust du es doch und eine einsame TrÃ¤ne entkommt deinen Augen.`n`n");

    output("Aber in dem Moment, in dem du die ".($repaygold>0?"`^$repaygold`7 Gold ".($repaygems>0?" und ":""):"").($repaygems>0?"`%$repaygems`7 Edelsteine":"")." erblickst, fÃ¼hlst du dich gleich ein wenig besser.");

    $session[user][reputation]-=2;

} elseif ($_GET['op']=='futterpet') {

    if (empty($_POST['days'])) {

        output("Das Futter kostet `^".$playerpet['value1']." Gold`0 und

                `%".$playerpet['value2']." Edelsteine`0 pro Tag.`n");

        output("<form action='stables.php?op=futterpet' method='post'>",true);

        output("FÃ¼r wie viele Tage mÃ¶chtest du Futter kaufen?");

        output("<input type='text' name='days' value='0'> <input type='submit' value='Kaufen!'>",true);

        output("</form>",true);

        addnav("","stables.php?op=futterpet");

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

            output("Merick nimmt die ".$coststr." und gibt dir genug Futter, um dein(e/n) ".$playerpet['name']." die nÃ¤chsten ".$days." Tage zu versorgen.`n");

            $oldtime = strtotime($session['user']['petfeed']);

            if ($oldtime < time()) $oldtime = time();

            $newtime = $oldtime + $days * (3600*24 / getsetting("daysperday",4));

            $session['user']['petfeed'] = date('Y-m-d H:i:s',$newtime);

        }else {

               output("`7Du kannst das Futter nicht bezahlen. Merick weigert sich, dein Tier fÃ¼r dich durchzufÃ¼ttern.");

        }

    }

}elseif($_GET['op']=='futter'){

    if ($session['user']['gold']>=$futtercost) {

                $buff = unserialize($playermount['mountbuff']);

                if ($session['bufflist']['mount']['rounds'] == $buff['rounds']) {

            output("Dein {$playermount['mountname']} ist satt und rÃ¼hrt das vorgesetzte Futter nicht an. Darum gibt Merick dir dein Gold zurÃ¼ck.");

        }else if ($session['bufflist']['mount']['rounds'] > $buff['rounds']*.5) {

            $futtercost=$futtercost/2;

            output("Dein {$playermount['mountname']} nascht etwas von dem vorgesetzten Futter und lÃ¤sst den Rest stehen. {$playermount['mountname']} ist voll regeneriert. ");

            output("Da aber noch Ã¼ber die HÃ¤lfte des Futters Ã¼brig ist, gibt dir Merick 50% Preisnachlass.`nDu bezahlst nur $futtercost Gold.");

            $session['user']['gold']-=$futtercost;

            $session['user']['reputation']--;

        }else{

            $session['user']['gold']-=$futtercost;

            output("Dein {$playermount['mountname']} macht sich gierig Ã¼ber das Futter her und frisst es bis auf den letzten KrÃ¼mel.`n");

            output("Dein {$playermount['mountname']} ist vollstÃ¤ndig regeneriert und du gibst Merick die $futtercost Gold.");

            $session['user']['reputation']--;

        }

               $session['bufflist']['mount']=$buff;

        $session['user']['fedmount']=1;

    } else {

        output("`7Du hast nicht genug Gold dabei, um das Futter zu bezahlen. Merick weigert sich dein Tier fÃ¼r dich durchzufÃ¼ttern und empfiehlt dir, im Wald nach einer grasbewachsenen Lichtung zu suchen.");

    }

}

if ($session['user']['hashorse']>0 && $session['user']['fedmount']==0) addnav("f?{$playermount['mountname']} fÃ¼ttern (`^$futtercost`0 Gold)","stables.php?op=futter");

if ($session['user']['petid']>0) addnav("t?{$playerpet['name']} fÃ¼ttern","stables.php?op=futterpet");



$sql = "SELECT mountname,mountid,mountcategory FROM mounts WHERE mountactive=1 ORDER BY mountcategory,mountcostgems,mountcostgold";

$result = db_query($sql);

$category="";

for ($i=0;$i<db_num_rows($result);$i++){

    $row = db_fetch_assoc($result);

    if ($category!=$row['mountcategory']){

        addnav($row['mountcategory']);

        $category = $row['mountcategory'];

    }

    addnav("Betrachte {$row['mountname']}`0","stables.php?op=examine&id={$row['mountid']}");

}

if ($session['user']['housekey']>0) {

    $sql = 'SELECT name, id FROM items WHERE class="Haust.Prot" ORDER BY gold ASC, gems ASC';

    $result = db_query($sql);

    if (db_num_rows($result)>0) {

        addnav('Wachtiere');

        while ($row = db_fetch_assoc($result)) {

            addnav("Betrachte {$row['name']}`0",'stables.php?op=examinepet&id='.$row['id']);

        }

    }

}



if ($session['user']['hashorse']>0){

    output("`n`nMerick bietet dir `^$repaygold`& Gold und `%$repaygems`& Edelsteine fÃ¼r dein(e/n) {$playermount['mountname']}.");

    addnav("Sonstiges");

    //addnav("Verkaufe {$playermount['mountname']}","stables.php?op=sellmount");

    $nav.="<a href='stables.php?op=sellmount' class='nav' onClick='return confirm(\"Willst du {$playermount['mountname']} wirklich verkaufen?\")'>Verkaufe {$playermount['mountname']}</a>";

    addnav("","stables.php?op=sellmount");

}

if ($session['user']['petid']>0) {

    if ($session['user']['hashorse']==0) addnav("Sonstiges");

    output("`n`nMerick bietet dir `%$petrepaygems`7 Edelsteine fÃ¼r dein(e/n) {$playerpet['name']}.");

    $pettime = strtotime($session['user']['petfeed'])-time();

    output("`n`n<table><tr><td>{$playerpet['name']}: </td><td>".grafbar(24*3600,$pettime,100,10)."</td></tr></table>",true);

    addnav("Verkaufe {$playerpet['name']}","stables.php?op=sellpet");

}



page_footer();

?>

