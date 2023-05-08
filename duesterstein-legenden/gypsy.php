
<?
require_once "common.php";
if ($session[user][locate]!=9){
    $session[user][locate]=9;
    redirect("gypsy.php");
}
addcommentary();
$cost = $session[user][level]*20;
$cost2 = $session[user][level]*10;
$gems=array(1=>1,2,3);
// Guilds/Clans Code
$guilddisc = 1;
if ($session['user']['guildID']!=0) {
    $MyGuild=&$session['guilds'][$session['user']['guildID']];
    if (isset($MyGuild)) {
        $guilddiscount = $MyGuild['GemPurchaseDiscount'];
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
        $guilddiscount = $MyClan['GemPurchaseDiscount'];
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
$costs=array(1=>round( (4000-3 * getsetting("selledgems",0))*$guilddisc ),
                round( (7800-6 * getsetting("selledgems",0))*$guilddisc ),
                round( (11400-9 * getsetting("selledgems",0))*$guilddisc )
            );
$scost=1200-getsetting("selledgems",0);
if ($_GET[op]=="pay"){
    if ($session[user][gold]>=$cost){ // Gunnar Kreitz
//    if ($session[user][gold]>$cost){ // Eric Stevens
        $session[user][gold]-=$cost;
        debuglog("spent $cost gold to speak to the dead");
        if ($_GET[was]=="flirt"){
             redirect("gypsy.php?op=flirt2");
        } else {
            redirect("gypsy.php?op=talk");
        }
    }else{
        page_header("Zigeunerzelt");
        addnav("Zurück zum Dorf","village.php");
        output("`5Du bietest der alten Zigeunerin deine `^{$session[user][gold]}`5 Gold für die Beschwörungssitzung. Sie informiert dich, dass die Toten zwar tot, aber deswegen trotzdem nicht billig sind.");
    }
}elseif ($_GET[op]=="ram"){
    page_header("Zigeunerzelt");
    if ($session[user][gold]>=$cost2){ // Gunnar Kreitz
//    if ($session[user][gold]>$cost){ // Eric Stevens
        $session[user][gold]-=$cost2;
        $fav = $session['user']['deathpower'];
        debuglog("spent $cost gold to ask Ramius about favors");
        output("`n`5Die Zigeunerin reibt an ihrer magischen Kristallkugel und scheint
        nach kurzer Zeit eine Verbindung mit Ramius aufnehmen zu können.`n
        Sie murmelt eine Frage...`n`n
        `%Dann hörst Du eine tiefe Stimme, die von allen Seiten zu kommen scheint.`n
        Ramius sagt: Du hast bei mir `$ $fav `%Gefallen.`0");
    }else{
        output("`5Du bietest der alten Zigeunerin deine `^{$session[user][gold]}`5 Gold
        für die Beschwörungssitzung. Sie informiert dich, dass Ramius zwar in der Unterwelt
        lebt, aber trotzdem das Gold liebt. Viel Gold.");
    }
    addnav("Zurück zum Dorf","village.php");
}elseif ($_GET[op]=="talk"){
    page_header("In tiefer Trance sprichst du mit den Schatten");
    // by nTE- with modifications from anpera
    $sql="SELECT name FROM accounts WHERE locked=0 AND loggedin=1 AND alive=0 AND laston>'".date("Y-m-d H:i:s",strtotime("-".getsetting("LOGINTIMEOUT",900)." seconds"))."' ORDER BY login ASC"; 
    $result=db_query($sql) or die(sql_error($sql));
    $count=db_num_rows($result);
    $names=$count?"":"niemandem";
    for ($i=0;$i<$count;$i++){ 
        $row=db_fetch_assoc($result); 
        $names.="`^$row[name]"; 
        if ($i<$count) $names.=", "; 
    } 
    db_free_result($result); 
    output("`5Du fühlst die Anwesenheit von $names`5.`n`n"); 
    output("`5Solange du in tiefer Trance bist, kannst du mit den Toten sprechen:`n");
    viewcommentary("shade","Sprich zu den Toten",25,"spricht");
    addnav("Aktualisieren","gypsy.php?op=talk");
    addnav("Erwachen","village.php");
} else if ($_GET[op]=="flirt2"){ 
    page_header("In tiefer Trance sprichst du mit den Schatten");
    debuglog("hat bei der Zigeunerin geflirtet");
    output("`5Die Zigeunerin versetzt dich in tiefe Trance.`n`% Du findest ".($session[user][sex]?"deinen Mann":"deine Frau")." im Land der Schatten und flirtest eine Weile mit ".($session[user][sex]?"ihm, um sein":"ihr, um ihr")." Leid zu lindern. ");
    output("`n`^Du bekommst einen Charmepunkt.");
    $session['bufflist']['lover']=array("name"=>"`!Schutz der Liebe","rounds"=>60,"wearoff"=>"`!Du vermisst deine große Liebe!`0","defmod"=>1.2,"roundmsg"=>"Deine große Liebe lässt dich an deine Sicherheit denken!","activate"=>"defense");
    $session['user']['charm']++;
    $session['user']['seenlover']=1;
    //addnav("Aktualisieren","gypsy.php?op=flirt2");
    addnav("Erwachen","village.php");
}elseif($_GET[op]=="buy"){
    page_header("Zigeunerzelt");
    if ($session[user][transferredtoday]>getsetting("transferreceive",3)){
        output("`5Du hast heute schon genug Geschäfte gemacht. `6Vessa`5 hat keine Lust mit dir zu handeln. Warte bis morgen.");
    }else if ($session[user][gems]>getsetting("selledgems",0)) {
        output("`6Vessa`5wirft einen neidischen Blick auf dein Säckchen Edelsteine und beschließt, dir nichts mehr zu geben.");
    } else {
                if ($session[user][gold]>=$costs[$_GET[level]]){
                       if (getsetting("selledgems",0) >= $_GET[level]) {
                              output( "`6Vessa`5 grapscht sich deine `^".($costs[$_GET[level]])."`5 Goldstücke und gibt dir im Gegenzug `#".($gems[$_GET[level]])."`5 Edelstein".($gems[$_GET[level]]>=2?"e":"").".`n`n");
                              $session[user][gold]-=$costs[$_GET[level]];
                              $session[user][gems]+=$gems[$_GET[level]];
                $session[user][transferredtoday]+=1;
                              if (getsetting("selledgems",0) - $_GET[level] < 1) {
                                savesetting("selledgems","0");
                              } else {
                                savesetting("selledgems",getsetting("selledgems",0)-$_GET[level]);
                              }
                    debuglog("Zigeunerzelt - ".$session[user][name]." kauft ".$_GET[level]." Edelsteine für ".$costs[$_GET[level]]." Gold");
                       } else {
                              output("`6Vessa`5 teilt dir mit, dass sie nicht mehr so viele Edelsteine hat und bittet dich später noch einmal wiederzukommen.`n`n");
                       }
                }else{
                        output( "`6Vessa`5 zeigt dir den Stinkefinger, als du versuchst, ihr weniger zu zahlen als ihre Edelsteine momentan Wert sind.`n`n");    
                }
    }
    addnav("Zurück zum Dorf","village.php");
}elseif($_GET[op]=="sell"){
    page_header("Zigeunerzelt");
    $maxout = $session[user][level]*getsetting("maxtransferout",25);
        if ($session[user][gems]<1){
                output("`6Vessa`5 haut mit der Faust auf den Tisch und fragt dich, ob du sie veralbern willst. Du hast keinen Edelstein.`n`n");
    }else if ($session[user][transferredtoday]>getsetting("transferreceive",3)){
        output("`5Du hast heute schon genug Geschäfte gemacht. `6Vessa`5 hat keine Lust mit dir zu handeln. Warte bis morgen.");
        }else{
                output("`6Vessa`5 nimmt deinen Edelstein und gibt dir dafür $scost Goldstücke.`n`n");
                $session[user][gold]+=$scost;
                $session[user][gems]-=1;
                savesetting("selledgems",getsetting("selledgems",0)+1);
        $session[user][transferredtoday]+=1;
        }
    addnav("Zigeunerzelt","gypsy.php");
    addnav("Zurück zum Dorf","village.php");
}elseif($_GET[op]=="stone"){
    page_header("Die magischen Steine");
    output("`!`b`cDie magischen Steine des Lava-Altars`c`b`n",true);
    output("`@Nun ".(($sex==1)?"junge Kriegerin, ":"junger Krieger, ")." Du möchtest
    wisssen, wer welchen magischen Stein besitzt? Sieh selbst:`n");
    output("<table cellspacing=2 cellpadding=2 align='center'>",true);
    output("<tr bgcolor='#FF0000'><td align='center'>`&`bStein N°`b</td>
    <td align='center'>`&`bStein`b</td>
    <td align='center'>`b`&Besitzer`b</td></tr>",true);
    $numstones = getsetting("magischesteine",0);
    for ($i = 1; $i < ($numstones+1); $i++){
        $sql = "SELECT name FROM accounts WHERE stone=$i";
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        if (db_num_rows($result) == 0) {
           $row[name]="`b`\$verfügbar`b";
           //$pietra1="`5Unknown";
           $pietra1=$stone[$i];
        }else $pietra1=$stone[$i];
        if ($row[name] == $session[user][name]) {
           output("<tr bgcolor='#007700'>", true);
        } else {
           output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
        }
        output("<td align='center'>`&".$i."</td><td align='center'>`&`b$pietra1`b</td><td align='center'>`&`b{$row[name]}`b</td></tr>",true);
    }
    output("</table>", true);
    addnav("Zigeunerzelt","gypsy.php");
    addnav("Zurück zum Dorf","village.php");
}else{
    checkday();
    page_header("Zigeunerzelt");
    $tag=getdayofweek();
    if ( $tag == "Freitag" ) {
        // heute geschlossen
        output("`5Du willst zur Zigeunerin, stehst aber vor einem verschlossenen Zelt.`n
        Dann fällt es Dir ein: Heute ist Freitag!`n`n
        Die Zigeunerin bereitet sich auf Feierlichkeiten zur Ehren ihrer Götter vor und
        ist deswegen für Geschäfte heute nicht zu sprechen.`n`0");
        addnav("Zurück");
        addnav("Zurück zum Dorf","village.php");
    }
    else {
        output("`5Du betrittst das Zigeunerzelt hinter `#Pegasus`5' Rüstungsladen, welches eine Unterhaltung mit den Verstorbenen verspricht. Im typischen Zigeunerstil sitzt eine alte Frau hinter
        einer irgendwie schmierigen Kristallkugel. Sie sagt dir, dass die Verstorbenen nur mit den Bezahlenden reden. Der Preis ist `^$cost`5 Gold.");
        output("`nDie Zigeunerin `6Vessa`5 gibt dir auch zu verstehen, dass sie mit Edelsteinen handelt.`nMomentan hat sie `#".getsetting("selledgems",0)."`5 Edelsteine auf Lager.");
        if (getsetting("selledgems",0)>=1000) output(" Sie scheint aber kein Interesse an weiteren Edelsteinen zu haben. Oder sie hat einfach kein Gold mehr, um weitere Edelsteine zu kaufen.");
        addnav("Bezahle und rede mit den Toten","gypsy.php?op=pay");
        addnav("Bezahle und frage Ramius","gypsy.php?op=ram");
        if ($session[user][charisma]==4294967295 && $session[user][seenlover]<1) {
            $sql = "SELECT name,alive FROM accounts WHERE ".$session[user][marriedto]." = acctid ORDER BY charm DESC";
              $result = db_query($sql) or die(db_error(LINK));
            $row = db_fetch_assoc($result);
            if ($row[alive]==0) addnav("Bezahle und flirte mit $row[name]","gypsy.php?op=pay&was=flirt");
        }
        addnav("Frage nach den Steinen","gypsy.php?op=stone");
        if ($session[user][superuser]>1) addnav("Superusereintrag","gypsy.php?op=talk");
        addnav("Edelsteine");
        if ($session['user']['level']<15){
            addnav("Kaufe 1 Edelstein ($costs[1] Gold)","gypsy.php?op=buy&level=1");
            addnav("Kaufe 2 Edelsteine ($costs[2] Gold)","gypsy.php?op=buy&level=2");
            addnav("Kaufe 3 Edelsteine ($costs[3] Gold)","gypsy.php?op=buy&level=3");
        }
        if (getsetting("selledgems",0)<100 && $session[user][level]>1) addnav("Verkaufe 1 Edelstein für $scost Gold","gypsy.php?op=sell");
        addnav("Zurück");
        // addnav("Forget it","village.php");
        addnav("Zurück zum Dorf","village.php");
    }
}
page_footer();
?>


