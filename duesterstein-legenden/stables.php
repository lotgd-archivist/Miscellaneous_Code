
<?
global $session;   //clan/guilds
require_once "common.php";
if ($session[user][locate]!=18){
    $session[user][locate]=18;
    redirect("stables.php");
}
page_header("Merick's Ställe");

// Guilds/Clans Code
$guilddisc = 1;
if ($session['user']['guildID']!=0) {
    $MyGuild=&$session['guilds'][$session['user']['guildID']];
    if (isset($MyGuild)) {
        $guilddiscount = $MyGuild['OtherSitepoints']['mount'];
        if ( $guilddiscount > 0 ) $guilddisc = ( 1 - ($guilddiscount/100) );
    } else {
        // Error
        // Their guildID is set but the information cannot be retrieved
        $debug=print_r($session['user']['guildID'],true);
        debuglog("MyGuild isn't set: ".$debug);
    }
} elseif ($session['user']['clanID']!=0) {
    $MyClan=&$session['guilds'][$session['user']['clanID']];
    if (isset($MyClan)) {
        $guilddiscount = $MyClan['OtherSitepoints']['mount'];
        if ( $guilddiscount > 0 ) $guilddisc = ( 1 - ($guilddiscount/100) );
    } else {
        // Error
        // Their clanID is set but the information cannot be retrieved
        $debug=print_r($session['user']['clanID'],true);
        debuglog("MyClan isn't set: ".$debug);
    }
} else {
      // They don't belong to a guild or clan
}
//

$repaygold = round($playermount['mountcostgold']*(2/3)*$guilddisc,0);
$repaygems = round($playermount['mountcostgems']*(2/3)*$guilddisc,0);
$wetter=$settings['weather'];
if ( $wetter == "Gewittersturm" ) {
    $futtercost = round($session[user][level]*24*$guilddisc,0);
} else {
    $futtercost = round($session[user][level]*20*$guilddisc,0);
}

addnav("Zurück zum Dorf","village.php");
if ($session['user']['hashorse']>0) addnav("f?{$playermount['mountname']} füttern (`^$futtercost`0 Gold)","stables.php?op=futter");

if ($_GET[op]==""){
    checkday();
    output("`7Hinter der Kneipe, etwas links von Pegasus' Waffen, befindet sich ein Stall 
    wie man ihn in jedem Dorf erwartumgsgemäß findet. 
    Darin kümmerst sich Merick, ein stämmig wirkender Zwerg, um verschiedene Tiere.
    `n`n
    Du näherst dich ihm, als er plötzlich herumwirbelt und seine Heugabel in deine ungefähre Richtung streckt. \"`&Ach, 
    'tschuldigung min ".($session[user][sex]?"Mädl":"Jung").", heb dich nit kommen hörn un heb gedenkt,
    du bischt sicha Cedrik, der ma widda sein Zwergenweitwurf ufbessern will. Naaahw, wat 
    kann ich für disch tun?`7\"");
    if ( $wetter == "Gewittersturm" ) {
        output("`n`n`&\"Aber eens sach isch Dir gleech, bei diese Mischtwetter heb isch keen Lust zum
        Futter zu geh'n. Dat wird Disch paar meh' Gold koschten!`7\"");
    }
}elseif($_GET['op']=="examine"){
    $sql = "SELECT * FROM mounts WHERE mountid='{$_GET['id']}'";
    $result = db_query($sql);
    if (db_num_rows($result)<=0){
        output("`7\"`&Ach, ich heb keen solches Tier da!`7\" ruft der Zwerg!");
    }else{
        output("`7\"`&Ai, ich heb wirklich n paar feine Viecher hier!`7\" kommentiert der Zwerg.`n`n");
        $mount = db_fetch_assoc($result);
            $gold = round($mount['mountcostgold']*$guilddisc,0);
            $gem = round($mount['mountcostgems']*$guilddisc,0);
        output("`7Kreatur: `&{$mount['mountname']}`n");
        output("`7Beschreibung: `&{$mount['mountdesc']}`n");
        output("`7Preis: `^$gold`& gold, `%$gem`& gems`n");
        output("`n");
        addnav("Dieses Tier kaufen","stables.php?op=buymount&id={$mount['mountid']}");
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
            output("`7Merick schaut dich schief von der Seite an. \"`&Ähm, was gläubst du was du hier machst? Kanns u nich sehen, dass {$mount['mountname']} `^{$mount['mountcostgold']}`& Gold und `%{$mount['mountcostgems']}`& Edelsteine kostet?`7\"");
        }else{
            if ($session['user']['hashorse']>0){
                output("`7Du übergibst dein(e/n) {$playermount['mountname']} und bezahlst den Preis für dein neues Tier. Merick führt ein(e/n) schöne(n/s) neue(n/s) `&{$mount['mountname']}`7  für dich heraus!`n`n");
            }else{
                output("`7Du bezahlst den Preis für dein neues Tier und Merick führt ein(e/n) schöne(n/s) neue(n/s) `&{$mount['mountname']}`7 für dich heraus!`n`n");
            }
            $session['user']['hashorse']=$mount['mountid'];
                    $goldprice = round($mount['mountcostgold']*$guilddisc,0);
                    $gemprice = round($mount['mountcostgems']*$guilddisc,0);
            $goldcost = $repaygold-$goldprice;
            $session['user']['gold']+=$goldcost;
            $gemcost = $repaygems-$gemprice;
            $session['user']['gems']+=$gemcost;
            debuglog(($goldcost <= 0?"spent ":"gained ") . abs($goldcost) . " gold and " . ($gemcost <= 0?"spent ":"gained ") . abs($gemcost) . " gems trading for a new mount");
            $session['bufflist']['mount']=unserialize($mount['mountbuff']);
            // Recalculate so the selling stuff works right
            $playermount = getmount($mount['mountid']);
            $repaygold = round($playermount['mountcostgold']*(2/3)*$guilddisc,0);
            $repaygems = round($playermount['mountcostgems']*(2/3)*$guilddisc,0);
        }
    }
}elseif($_GET['op']=='futter'){
    //if ($session[user][feeding]<= 0) {
        if ($session[user][feeding]<= 0 && $session[user][stone]!= 1 ) {
        output("Merik hat leider kein Futter mehr für Dein Tier übrig, aber er verspricht Dir bis morgen wieder
            frisches Futter besorgt zu haben.");
    }else{
        if ($session[user][gold]>=$futtercost) {
                $buff = unserialize($playermount['mountbuff']);
                if ($session['bufflist']['mount']['rounds'] == $buff['rounds']) {
                output("Dein {$playermount['mountname']} ist satt und rührt das vorgesetzte Futter nicht an. Darum gibt Merick dir dein Gold zurück.");
            }else if ($session['bufflist']['mount']['rounds'] > $buff['rounds']*.5) {
                $futtercost=$futtercost/2;
                output("Dein {$playermount['mountname']} nascht etwas von dem vorgesetzten Futter und lässt den Rest stehen. {$playermount['mountname']} ist voll regeneriert. ");
                output("Da aber noch über die Hälfte des Futters übrig ist, gibt dir Merick 50% Preisnachlass.`nDu bezahlst nur $futtercost Gold.");
                $session[user][gold]-=$futtercost;
            }else{
                $session[user][gold]-=$futtercost;
                output("Dein {$playermount['mountname']} macht sich gierig über das Futter her und frisst es bis auf den letzten Krümel.`n");
                output("Dein {$playermount['mountname']} ist vollständig regeneriert und du gibst Merick die $futtercost Gold."); 
            }
                   $session['bufflist']['mount']=$buff;
            $session[user][feeding]--;
        } else {
            output("`7Du hast nicht genug Gold dabei, um das Futter zu bezahlen. Merick weigert sich dein Tier für dich durchzufüttern und empfiehlt dir, im Wald nach einer grasbewachsenen Lichtung zu suchen.");
        }
    }
}elseif($_GET['op']=='sellmount'){
    $session['user']['gold']+=$repaygold;
    $session['user']['gems']+=$repaygems;
    debuglog("gained $repaygold gold and $repaygems gems selling their mount");
    unset($session['bufflist']['mount']);
    $session['user']['hashorse']=0;
    output("`7So schwer es dir auch fällt, dich von dein(er/em) {$playermount['mountname']} zu trennen, tust du es doch und eine einsame Träne entkommt deinen Augen.`n`n");
    output("Aber in dem Moment, in dem du die ".($repaygold>0?"`^$repaygold`7 Gold ".($repaygems>0?" und ":""):"").($repaygems>0?"`%$repaygems`7 Edelsteine":"")." erblickst, fühlst du dich gleich ein wenig besser.");
}

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
if ($session['user']['hashorse']>0){
    output("`n`nMerick bietet dir `^$repaygold`& Gold und `%$repaygems`& Edelsteine für dein(e/n) {$playermount['mountname']}.");
    addnav("Sonstiges");
    addnav("Verkaufe {$playermount['mountname']}","stables.php?op=sellmount");
}

page_footer();
?>


