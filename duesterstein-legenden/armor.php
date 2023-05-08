
<? 

// modifications by anpera: 
// stealing enabled with 1:15 success (thieves have 2:12 chance) 

global $NavSystem;
global $GuildLeader, $HeadOfWar, $HeadOfMembership, $ClanLeader;
require_once "common.php";
require_once "guildclanfuncs.php";
checkday(); 

//
// ----------------------------------------------------------------------------
//
// armor.php
// -> Original work by LOTGD.NET/MightyE
// -> Updated to work with the Guilds & Clans Pages & custom weapons
// CR#Dasher#004
// 14th April 2004
// Version: 0.98.2 beta
// The latest version is always runnning at: www.sembrance.uni.cc/rpg
// (c) Dasher [david.ashwood@inspiredthinking.co.uk] 2004
// This module is relased under the LOTGD GNU public license
// You may change/modify this module and it's associated modules as you wish but
// this copyright MUST be retained.
//
// I would apprechiate feedback/updates on how it works and changes you make.
// Dasher
// ------------------------------------
// david.ashwood@inspiredthinking.co.uk
// MSNM: david@ashwood.net
// ----------------------------------------------------------------------------
//
// Has a dynamic return path using &return
// Called witht he &guild=GuildID to activate the guild functionality
// Supports Custom Guild Armor using the standard armor table.
// Uses an offset of 1024 in the level field by 1014+(GuildID*10)
// Uses the name of the Head Of War or Guild Leader as the Weapon Smith
// Defaults to MightyE if not called with the guild=GuildID set
// Uses the NavSystem talked about in the Guilds-Clans Pages
//
// ChangeLog
// 14th April 2004
// Fixed some minor bugs around the cost of items
// ----------------------------------------------------------------------------
// Gargamel Oct-14-2004: CLANS implemented based upon above described changes.
// ----------------------------------------------------------------------------
//
//

If (isset($HTTP_GET_VARS['guild'])) {
    // If we have come here from the guilds pages - &guild=GuildID will be set
    $url_mod="&guild=".$HTTP_GET_VARS['guild'];  // Save the guild ID to return
    $MyGuild=$session['guilds'][$HTTP_GET_VARS['guild']];
    $MgmtTeam=ManagementTeam($MyGuild['ID'],true);
//    $return=$HTTP_GET_VARS['return'];
    $return="guild.php?op=member&action=shop&id=".$MyGuild['ID'];
    $url_mod.="&return=".urlencode($return);
}

If (isset($HTTP_GET_VARS['clan'])) {
    // If we have come here from the clan pages - &clan=clanID will be set
    $url_mod="&clan=".$HTTP_GET_VARS['clan'];  // Save the guild ID to return
    $MyClan=$session['guilds'][$HTTP_GET_VARS['clan']];
    $MgmtTeam=ManagementTeam($MyClan['ID'],true);
//    $return=$HTTP_GET_VARS['return'];
    $return="clan.php?op=member&action=shop&id=".$MyClan['ID'];
    $url_mod.="&return=".urlencode($return);
}

// Determine the Weapon Smith Name
if (isset($MyGuild)){
    $Smithy=($MgmtTeam[$HeadOfWar]!=0?
        $MgmtTeam[$HeadOfWar]['name']:
        $MgmtTeam[$GuildLeader]['name']);
} elseif (isset($MyClan)){
    $Smithy=$MgmtTeam[$ClanLeader]['name'];
} else $Smithy="`!Pegasus";

// Determine Sex
if (isset($MyGuild)){
    $sex=($MgmtTeam[$HeadOfWar]!=0?
        $MgmtTeam[$HeadOfWar]['sex']:
        $MgmtTeam[$GuildLeader]['sex']);
} elseif (isset($MyClan)){
    $sex=$MgmtTeam[$ClanLeader]['sex'];
} else $sex=1;

// Determine the store name as the Guild Name if the Guild Functionality is being used, otherwise uses MightyE
if (isset($MyGuild)){
    $StoreName=$MyGuild['Name'];
} elseif (isset($MyClan)){
    $StoreName=$MyClan['Name'];
} else $StoreName="Pegasus";

// Gives an improved trade in percentage when used from guild pages
if (isset($MyGuild)){
    $TradeInRatio=0.85;
} elseif (isset($MyClan)){
    $TradeInRatio=0.85;
} else $TradeInRatio=0.75;
if ( $session['user']['stone'] == 3 )
    $TradeInRatio = 1;

// Determines the purchase discount, as set by the guild.
// They can spend SitePoints to get a better discount
// See the Guild/Clans pages for more information
if (isset($MyGuild)){
    $GuildDiscount=($MyGuild['ArmourDiscount']==0?
        0:($MyGuild['ArmourDiscount']/100));
} elseif (isset($MyClan)){
    $GuildDiscount=($MyClan['ArmourDiscount']==0?
        0:($MyClan['ArmourDiscount']/100));
} else $GuildDiscount=0;


if ( isset($MyGuild) || isset($MyClan) ) {
    if ($HTTP_GET_VARS[op]=="guildarmor") {
        page_header(color_sanitize($StoreName)."'s Rüstungen");
        output("`c`b`&".$StoreName."'s Rüstungen `b`ngeführt von ".$Smithy."`c`0`n",true);
        $tradeinvalue = round(($session['user']['armorvalue']*$TradeInRatio),0);
    } else if ($HTTP_GET_VARS[op]=="browse") {
        page_header(color_sanitize($StoreName)."'s Rüstungen");
        output("`c`b`&".$StoreName."'s Rüstungen `b`ngeführt von Pegasus`c`0`n",true);
        $tradeinvalue = round(($session['user']['armorvalue']*$TradeInRatio),0);
    } else {
        page_header(color_sanitize($StoreName)."'s Rüstungen");
        output("`c`b`&".$StoreName."'s Rüstungen `b`c`0`n",true);
        $tradeinvalue = round(($session['user']['armorvalue']*$TradeInRatio),0);
    }
}
else {
    page_header(color_sanitize($StoreName)."'s Rüstungen");
    output("`c`b`&".$StoreName."' Rüstungen `b`c`0`n",true);
    $tradeinvalue = round(($session['user']['armorvalue']*$TradeInRatio),0);
}

$NavSystem["Zurück"]["Zurück zum Dorf"]="village.php";

if (isset($MyGuild)) {
    // Links back to the guild and provides an option to display the Guild Defined Weapons
    $NavSystem["Zurück"]["Zurück zum Marktplatz"]=$return;
    //$NavSystem["Shop"]["`#".$Smithy."`0's Waren durchstöbern"]="armor.php?op=browse".$url_mod;
    $NavSystem["Shop"]["Gilden-eigene Rüstungen"]="armor.php?op=guildarmor".$url_mod;
}
if (isset($MyClan)) {
    // Links back to the guild and provides an option to display the Guild Defined Weapons
    //$NavSystem["Return"]["Zurück zu ".$MyGuild['Name']]=$return;
    $NavSystem["Zurück"]["Zurück zum Marktplatz"]=$return;
    //$NavSystem["Shop"]["`#".$Smithy."`0's Waren durchstöbern"]="armor.php?op=browse".$url_mod;
    $NavSystem["Shop"]["Clan-eigene Rüstungen"]="armor.php?op=guildarmor".$url_mod;
} 
if ( $session['user']['stone'] == 3 )
     $tradeinvalue = $session[user][armorvalue];
if ($HTTP_GET_VARS[op]==""){
    if (isset($MyGuild)) {
        output("`5Du stehst vor einem kleinen Rüstungsladen, der Deiner Gilde gehört
        und von `#$Smithy`5 geführt wird.`n
        `%Hier können nur Gilden-Mitglieder einkaufen!`n`n`0");
        output("`5Direkt daneben ist ein weiteres Geschäft, das blankgeputzte Rüstungen
        ausgestellt hat. Aus dem Laden lächelt Dich die gerechte und hübsche `#Pegasus`5 an.`n
        `%Deine Gilde räumt Dir großzügigerweise einen Rabatt von `&"
        .($GuildDiscount*100)."%`% auf Deine Einkäufe ein!`n`n`0");
        $NavSystem["Shop"]["`#Pegasus`0' Waren durchstöbern"]="armor.php?op=browse".$url_mod;
    }
    elseif (isset($MyClan)) {
        output("`5Du stehst vor einem kleinen Rüstungsladen, der Deinem Clan gehört
        und von `#$Smithy`5 geführt wird.`n
        `%Hier können nur Clan-Mitglieder einkaufen!`n`n`0");
        output("`5Direkt daneben ist ein weiteres Geschäft, das blankgeputzte Rüstungen
        ausgestellt hat. Aus dem Laden lächelt Dich die gerechte und hübsche `#Pegasus`5 an.`n
        `%Dein Clan räumt Dir großzügigerweise einen Rabatt von `&"
        .($GuildDiscount*100)."%`% auf Deine Einkäufe ein!`n`n`0");
        $NavSystem["Shop"]["`#Pegasus`0' Waren durchstöbern"]="armor.php?op=browse".$url_mod;
    }
    else {
        output("`5Die gerechte und hübsche `#Pegasus`5 begrüßt Dich mit einem herzlichen
        Lächeln, als du ihren bunten Zigeunerwagen betrittst, der nicht ganz zufällig direkt
        neben `!MightyE`5's Waffenladen steht. Ihr Erscheinungsbild ist genauso grell und
        farbenfroh wie ihr Wagen, und lenkt Dich fast (aber nicht ganz) von ihren großen
        grauen Augen und der zwischen ihren nicht ganz ausreichenden Zigeunerklamotten
        hindurchleuchtenden Haut ab.");
        output("`n`n");
        $NavSystem["Shop"]["`#Pegasus`0' Waren durchstöbern"]="armor.php?op=browse".$url_mod;
    }
}else if ($HTTP_GET_VARS[op]=="browse"){ 
    $sql = "SELECT max(level) AS level FROM armor WHERE level<=".$session[user][dragonkills]; 
    $result = db_query($sql) or die(db_error(LINK)); 
    $row = db_fetch_assoc($result); 

      $sql = "SELECT * FROM armor WHERE level=$row[level] ORDER BY value"; 
    $result = db_query($sql) or die(db_error(LINK)); 
    output("`5Du blickst über die verschiedenen Kleidungsstücke und fragst dich, ob `#Pegasus`5 einige davon für dich "); 
    output("anprobieren würde. Aber dann bemerkst du, daß sie damit beschäftigt ist, `!MightyE`5 verträumt durch das Fenster seines Ladens dabei zu beobachten "); 
    output("wie er gerade mit nacktem Oberkörper einem Kunden den Gebrauch einer seiner Waren demonstriert. Als sie kurz wahrnimmt, dass du "); 
    output("ihre Waren durchstöberst, blickt sie auf dein(e/n) ".$session[user][armor]." und bietet dir dafür im Tausch `^$tradeinvalue`5 Gold an."); 
    if($session[user][thefttoday]) output("`nSie sieht dich misstrauisch an, als ob sie wüsste, dass du hier hin und wieder versuchst, ihr ihre schönen Rüstungen zu klauen."); 
    output("<table border='0' cellpadding='0'>",true); 
    output("<tr class='trhead'><td>`bName`b</td><td align='center'>`bVerteidigung`b</td><td align='right'>`bPreis`b</td></tr>",true); 
    for ($i=0;$i<db_num_rows($result);$i++){ 
          $row = db_fetch_assoc($result); 
        $bgcolor=($i%2==1?"trlight":"trdark"); 
        $cost=round($row['value'] * (1-$GuildDiscount),0);
        if ($cost<=($session['user']['gold']+$tradeinvalue)){
            output("<tr class='$bgcolor'><td>
                Kaufe <a href='armor.php?op=buy&id=".$row['armorid'].$url_mod."'>".$row['armorname']."</a></td>
                <td align='center'>".$row['defense']."</td><td align='right'>".$cost."</td></tr>",true); 
            addnav("","armor.php?op=buy&id=$row[armorid]".$url_mod."");
        }else{ 
            output("<tr class='$bgcolor'><td>
                - - - - <a href='armor.php?op=buy&id=".$row['armorid'].$urlmod."'>".$row['armorname']."</a></td>
                <td align='center'>".$row['defense']."</td><td align='right'>".$cost."</td></tr>",true); 
            addnav("","armor.php?op=buy&id=".$row['armorid'].$url_mod.""); 

        }
    } 
    output("</table>",true); 
    addnav("Zurück zum Dorf","village.php"); 
}else if ($HTTP_GET_VARS[op]=="buy"){ 
      $sql = "SELECT * FROM armor WHERE armorid='$HTTP_GET_VARS[id]'"; 
    $result = db_query($sql) or die(db_error(LINK)); 
    if (db_num_rows($result)==0){ 
        output("`#".$Smithy."`5 schaut dich ein paar Sekunden verwirrt an, entschließt sich dann aber zu glauben, dass du wohl ein paar Schläge zu viel auf den Kopf bekommen hast und nickt lächelnd.");
        addnav("Nochmal?","armor.php".$url_mod);
    }else{ 
          $row = db_fetch_assoc($result); 
        if ($row[value]>($session[user][gold]+$tradeinvalue)){ 
            if ($session[user][thievery]>=2) { 
                $klau=e_rand(1,15); 
            } else { 
                $klau=e_rand(2,18); 
            } 
            if ($session[user][thefttoday]>0){ 
                if ($session[user][thefttoday]==2) $klau=10; 
                $session[user][thefttoday]=2; 
                if ($klau==1){ // Fall nur für Diebe 
                    output("`5Mit den Fertigkeiten eines erfahrenen Diebes tauschst du `%$row[armorname]`5 gegen `%".$session[user][armor]."`5 aus und verlässt fröhlich pfeifend den Laden. "); 
                    output(" `bGlück gehabt!`b `#Pegasus`5 starrt immer noch verträumt zu MightyE rüber und hat nichts bemerkt. Aber nochmal wird ihr das nicht passieren! Stolz auf deine "); 
                    output("fette Beute stolzierst du über den Dorfplatz - bis dir jemand mitteilt, daß dir da noch ein Preisschild herumbaumelt...`nDu verlierst einen Charmepunkt!"); 
                     $session[user][armor] = $row[armorname]; 
                    if ($session[user][charm]) $session[user][charm]-=1; 
                    $session[user][defence]-=$session[user][armordef]; 
                    $session[user][armordef] = $row[defense]; 
                    $session[user][defence]+=$session[user][armordef]; 
                    $session[user][armorvalue] = $row[value]; 
                    //addnav("Zurück zum Dorf","village.php"); 
                } else if ($klau==2 || $klau==3) { // Diebstahl gelingt perfekt 
                    output("`5Du grabschst dir `%$row[armorname]`5 und tauscht `%".$session[user][armor]."`5 unauffälig dagegen aus. "); 
                    output(" `bGlück gehabt!`b `#Pegasus`5 starrt immer noch verträumt zu MightyE rüber und hat nichts bemerkt. Aber nochmal wird ihr das nicht passieren! Stolz auf deine "); 
                    output("fette Beute stolzierst du über den Dorfplatz - bis dir jemand mitteilt, daß dir da noch ein Preisschild herumbaumelt...`nDu verlierst einen Charmepunkt!"); 
                     $session[user][armor] = $row[armorname]; 
                    if ($session[user][charm]) $session[user][charm]-=1; 
                    $session[user][defence]-=$session[user][armordef]; 
                    $session[user][armordef] = $row[defense]; 
                    $session[user][defence]+=$session[user][armordef]; 
                    $session[user][armorvalue] = $row[value]; 
                    //addnav("Zurück zum Dorf","village.php"); 
                } else if ($klau==4 || $klau==5) { // Diebstahl gelingt, aber nachher erwischt 
                    output("`5Du grabschst dir `%$row[armorname]`5 und tauscht `%".$session[user][armor]."`5 unauffälig dagegen aus. "); 
                    output(" So schnell und unauffällig wie du kannst verlässt du den Laden. Geschafft! Als du mit deiner Beute über den Dorfplatz stolzierst, siehst du aus dem "); 
                    output("Augenwinkel `#Pegasus`5 knapp an dir vorbei Richtung Stadtbank laufen. Im Vorbeigehen reisst sie das Preisschild ab, das noch immer von deiner neuen Rüstung baumelt...`n`n"); 
                    if ($session[user][goldinbank]<0){ 
                        output("Da du jedoch schon Schulden bei der Bank hast, bekam `#Pegasus`5 von dort nicht was sie verlangte.`n"); 
                        output("Als du dein(e/n) `%$row[armorname]`5 stolz auf dem Dorfplatz präsentierst, packt dich von hinten `!MightyE`5's starke Hand. Er entreisst dir $row[armorname] gewaltsam, "); 
                        output(" drückt dir dein(e/n) alte(n/s) ".$session[user][armor]." in die Hand und schlägt dich nieder. Er raunzt noch etwas, daß du Glück hast, so arm zu sein, sonst hätte er er dich umgebracht und daß er dich beim nächsten Diebstahl"); 
                        output(" ganz sicher umbringen wird, bevor er in seinen Laden zurück geht, wo bereits ein Kunde wartet.`n"); 
                        output(" `#Pegasus`5 wird dir sowas nicht nochmal durchgehen lassen!"); 
                        $session[user][hitpoints]=round($session[user][hitpoints]/2); 
                    }else{ 
                        output("`#Pegasus`5 hat sich die".($row['value']-$tradeinvalue)." Gold, die du ihr schuldest, von der Bank geholt!"); 
                        output(" Sie wird dir sowas nicht nochmal durchgehen lassen."); 
                        $session[user][goldinbank]-=($row[value]-$tradeinvalue); 
                        if ($session[user][goldinbank]<0) output("`nDu hast dadurch jetzt `^".abs($session[user][goldinbank])." Gold`5 Schulden bei der Bank!!"); 
                        debuglog("lost " . ($row['value']-$tradeinvalue) . " gold in bank for stealing the " . $row['armorname'] . " armor"); 
                         $session[user][armor] = $row[armorname]; 
                        $session[user][defence]-=$session[user][armordef]; 
                        $session[user][armordef] = $row[defense]; 
                        $session[user][defence]+=$session[user][armordef]; 
                        $session[user][armorvalue] = $row[value]; 
                    } 
                    //addnav("Zurück zum Dorf","village.php"); 
                } else { // Diebstahl gelingt nicht 
                      output("`5Du wartest, bis `#Pegasus`5 wieder abgelenkt ist, dann näherst du dich vorsichtig `%$row[armorname]`5, und läßt die Rüstung leise vom Stapel verschwinden, auf dem sie lag. "); 
                    output("Deiner Beute sicher drehst du dich um ... nur um festzustellen, dass du dich nicht ganz umdrehen kannst, weil sich zwei Hände fest um deinen "); 
                    output("Hals schliessen. Du schaust runter, verfolgst die Hände bis zu einem Arm, an dem sie befestigt sind, der wiederum an einem äußerst muskulösen `!MightyE`5 befestigt ist. Du versuchst "); 
                    output("zu erklären was hier passiert ist, aber dein Hals scheint nicht in der Lage zu sein, deine Stimme, oder gar den so dringend benötigten Sauerstoff hindurch zu lassen.  "); 
                    output("`n`nAls langsam Dunkelheit in deine Wahrnehmung schlüpft, schaust du flehend zu `%Pegasus`5, doch die starrt nur völlig verträumt und mit den Händen seitlich auf dem lächelnden Gesicht "); 
                    output("auf `!MightyE`5.`n`n"); 
                    $session[user][alive]=false; 
                    debuglog("lost " . $session['user']['gold'] . " gold on hand due to stealing from Pegasus"); 
                    $session[user][gold]=0; 
                    $session[user][hitpoints]=0; 
                    $session[user][experience]=round($session[user][experience]*.9,0); 
                    $session[user][gravefights]=round($session[user][gravefights]*.75); 
                    output("`b`&Du wurdest von `!MightyE`& umgebracht!!!`n"); 
                    output("`4Das Gold, das du dabei hattest, hast du verloren!`n"); 
                    output("`4Du hast 10% deiner Erfahrung verloren!`n"); 
                    output("Du kannst morgen wieder kämpfen.`n"); 
                    output("`nWegen der Unehrenhaftigkeit deines Todes landest du im Fegefeuer und wirst das Reich der Schatten aus eigener Kraft heute nicht mehr verlassen können!"); 
                    addnav("Tägliche News","news.php"); 
                    addnews("`%".$session[user][name]."`5 wurde von `!MightyE`5 für den Versuch, bei `#Pegasus`5 zu stehlen, erwürgt."); 
            unset($NavSystem["Return"]["Zurück zum Dorf"]);
                } 
            }else{ 
                $session[user][thefttoday]=1; 
                if ($klau==1){ // Fall nur für Diebe 
                    output("`5Mit den Fertigkeiten eines erfahrenen Diebes tauschst du `%$row[armorname]`5 gegen `%".$session[user][armor]."`5 aus und verlässt fröhlich pfeifend den Laden. "); 
                    output(" `bGlück gehabt!`b `#Pegasus`5 starrt immer noch verträumt zu MightyE rüber und hat nichts bemerkt. Trotzdem wird sie den Diebstahl früher oder später bemerken und in Zukunft besser aufpassen! Stolz auf deine "); 
                    output("fette Beute stolzierst du über den Dorfplatz - bis dir jemand mitteilt, daß dir da noch ein Preisschild herumbaumelt...`nDu verlierst einen Charmepunkt!"); 
                     $session[user][armor] = $row[armorname]; 
                    if ($session[user][charm]) $session[user][charm]-=1; 
                    $session[user][defence]-=$session[user][armordef]; 
                    $session[user][armordef] = $row[defense]; 
                    $session[user][defence]+=$session[user][armordef]; 
                    $session[user][armorvalue] = $row[value]; 
                    //addnav("Zurück zum Dorf","village.php"); 
                } else if ($klau==2 || $klau==3) { // Diebstahl gelingt perfekt 
                    output("`5Du grabschst dir `%$row[armorname]`5 und tauscht `%".$session[user][armor]."`5 unauffälig dagegen aus. "); 
                    output(" `bGlück gehabt!`b `#Pegasus`5 starrt immer noch verträumt zu MightyE rüber und hat nichts bemerkt. Trotzdem wird sie den Diebstahl früher oder später bemerken und in Zukunft besser aufpassen! Stolz auf deine "); 
                    output("fette Beute stolzierst du über den Dorfplatz - bis dir jemand mitteilt, daß dir da noch ein Preisschild herumbaumelt...`nDu verlierst einen Charmepunkt!"); 
                     $session[user][armor] = $row[armorname]; 
                    if ($session[user][charm]) $session[user][charm]-=1; 
                    $session[user][defence]-=$session[user][armordef]; 
                    $session[user][armordef] = $row[defense]; 
                    $session[user][defence]+=$session[user][armordef]; 
                    $session[user][armorvalue] = $row[value]; 
                    //addnav("Zurück zum Dorf","village.php"); 
                } else if ($klau==4 || $klau==5) { // Diebstahl gelingt, aber nachher erwischt 
                    output("`5Du grabschst dir `%$row[armorname]`5 und tauscht `%".$session[user][armor]."`5 unauffälig dagegen aus. "); 
                    output(" So schnell und unauffällig wie du kannst verlässt du den Laden. Geschafft! Als du mit deiner Beute über den Dorfplatz stolzierst, siehst du aus dem "); 
                    output("Augenwinkel `#Pegasus`5 knapp an dir vorbei Richtung Stadtbank laufen. Im Vorbeigehen reisst sie das Preisschild ab, das noch immer von deiner neuen Rüstung baumelt...`n`n"); 
                    if ($session[user][goldinbank]<0){ 
                        output("Da du jedoch schon Schulden bei der Bank hast, bekam `#Pegasus`5 von dort nicht was sie verlangte.`n"); 
                        output("Als du dein(e/n) `%$row[armorname]`5 stolz auf dem Dorfplatz präsentierst, packt dich von hinten `!MightyE`5's starke Hand. Er entreisst dir $row[armorname] gewaltsam, "); 
                        output(" drückt dir dein(e/n) alte(n/s) ".$session[user][armor]." in die Hand und schlägt dich nieder. Er raunzt noch etwas, daß du Glück hast, so arm zu sein, sonst hätte er er dich umgebracht und daß er dich beim nächsten Diebstahl"); 
                        output(" ganz sicher umbringen wird, bevor er in seinen Laden zurück geht, wo bereits ein Kunde wartet.`n"); 
                        output(" `#Pegasus`5 wird dich in Zukunft sehr genau im Auge behalten, wenn du ihren Laden betrittst."); 
                        $session[user][hitpoints]=round($session[user][hitpoints]/2); 
                    }else{ 
                        output("`#Pegasus`5 hat sich die".($row['value']-$tradeinvalue)." Gold, die du ihr schuldest, von der Bank geholt!"); 
                        output(" Sie wird dich in Zukunft sehr genau im Auge behalten, wenn du ihren Laden betrittst."); 
                        $session[user][goldinbank]-=($row[value]-$tradeinvalue); 
                        if ($session[user][goldinbank]<0) output("`nDu hast dadurch jetzt `^".abs($session[user][goldinbank])." Gold`5 Schulden bei der Bank!!"); 
                        debuglog("lost " . ($row['value']-$tradeinvalue) . " gold in bank for stealing the " . $row['armorname'] . " armor"); 
                         $session[user][armor] = $row[armorname]; 
                        $session[user][defence]-=$session[user][armordef]; 
                        $session[user][armordef] = $row[defense]; 
                        $session[user][defence]+=$session[user][armordef]; 
                        $session[user][armorvalue] = $row[value]; 
                    } 
                    //addnav("Zurück zum Dorf","village.php"); 
                } else { // Diebstahl gelingt nicht 
                    output("`5Du grabschst dir `%$row[armorname]`5 und tauscht `%".$session[user][armor]."`5 unauffälig dagegen aus. "); 
                    output(" So schnell und unauffällig wie du kannst verlässt du den Laden. Geschafft! "); 
                    output("Als du dein(e/n) `%$row[armorname]`5 stolz auf dem Dorfplatz präsentierst, packt dich von hinten `!MightyE`5's starke Hand. Er entreisst dir $row[armorname] gewaltsam, "); 
                    output(" drückt dir dein(e/n) alte(n/s) ".$session[user][armor]." in die Hand und schlägt dich nieder. Er raunzt noch etwas, daß er dich beim nächsten Diebstahl"); 
                    output(" ganz sicher umbringen wird, bevor er in seinen Laden zurück geht, wo bereits ein Kunde wartet.`n"); 
                    $session[user][hitpoints]=1; 
                    if ($session[user][turns]>0){ 
                        output("`n`4Du verlierst einen Waldkampf und fast alle Lebenspunkte."); 
                        $session[user][turns]-=1; 
                    }else{ 
                        output("`n`4MightyE hat dich so schlimm erwischt, dass eine Narbe bleiben wird.`nDu verlierst 3 Charmepunkte und fast alle Lebenspunkte."); 
                        $session[user][charm]-=3; 
                        if ($session[user][charm]<0) $session[user][charm]=0; 
                    } 
                    //addnav("Zurück zum Dorf","village.php"); 
                } 
            } 
        }else{ 
            output("`#".$Smithy."`5  nimmt dein Gold und sehr zu deiner Überraschung nimmt ".(($sex==1)?"sie":"er")." auch dein(e/n) `%".$session[user][armor]."`5  hängt ein Preisschild dran und legt die Rüstung hübsch zu den anderen. "); 
            output("`n`nIm Gegenzug händigt ".(($sex==1)?"sie":"er")." dir deine wunderbare neue Rüstung `%$row[armorname]`5 aus."); 
            output("`n`nDu fängst an zu protestieren: \"`@Werde ich nicht albern aussehen, wenn ich nichts ausser `&$row[armorname]`@ tragen?`5\" Du denkst einen Augenblick darüber nach, dann wird dir klar, dass jeder in der  "); 
            output("Stadt ja das selbe macht.    \"`@Na gut. Andere Länder, andere Sitten`5\""); 
            $cost=round($row['value'] * (1-$GuildDiscount),0);
            debuglog("spent " . ($row['value']-$tradeinvalue) . " gold on the " . $row['armorname'] . " armor"); 
              $session[user][gold]-=$cost; 
            $session[user][armor] = $row[armorname]; 
            $session[user][gold]+=$tradeinvalue; 
            $session[user][defence]-=$session[user][armordef]; 
            $session[user][armordef] = $row[defense]; 
            $session[user][defence]+=$session[user][armordef]; 
            $session[user][armorvalue] = $cost; 
            //addnav("Zurück zum Dorf","village.php");
            unset($NavSystem['Shop']);
        }
    } 
}else if ($HTTP_GET_VARS[op]=="guildarmor"){ 
    // Displays the custom Armors defined by the Guild or Clan
    if (isset($MyGuild)) {
        $ArmorRef=1014+($MyGuild['ID']*100);  // Determine the offset in the Armors table Level field
    } else {
        $ArmorRef=1014+($MyClan['ID']*100);  // Determine the offset in the Armors table Level field
    }
    $sql = "SELECT * FROM armor WHERE level >= ".$ArmorRef." and level<=".($ArmorRef+99)." ORDER BY defense ASC";
    $result = db_query($sql) or die(db_error(LINK));
    output("`7Du gehst zum Verkaufstisch und tust so, als ob Du genau wüsstest, was diese ganze Ausrüstung bewirkt.
    ".$Smithy."`7 lächelt Dich an und steckt dann ein Ast in ein Loch in der Wand. Sofort schwingt eine geheime
    Tür auf, und Du kannst Dir die Rüstungen ansehen, die vom Schmied der Guilde angefertigt wurden.`n
    ".$Smithy."`7 schaut Dich an und sagt: \"`#Ich kann Dir `^".$tradeinvalue." Gold `# für Dein `5"
    .$session['user']['armor']."`# geben. Klicke auf die Rüstung, die Du kaufen willst. Was immer auch \"Klick\"
    heissen mag...`7\". Erwartungsvoll schaut ".(($sex==1)?"sie":"er")." Dich an.`n`n`0");

    $RowCount=db_num_rows($result);
    if ($RowCount!=0) {
        output("<table border='0' cellpadding='0'>",true);
        output("<tr class='trhead'><td>`bName`b</td><td align='center'>`bVerteidigung`b</td><td align='right'>`bGoldpreis`b</td></tr>",true);
        for ($i=0;$i<$RowCount;$i++){
            $row = db_fetch_assoc($result);
            $bgcolor=($i%2==1?"trlight":"trdark");
            $cost=round($row['value'] * (1-$GuildDiscount),0);
            if ($cost<=($session['user']['gold']+$tradeinvalue)){
                output("<tr class='$bgcolor'><td>
                <a href='armor.php?op=buy&id=".$row['armorid'].$url_mod."'>".$row['armorname']."</a></td>
                <td align='center'>".$row['defense']."</td><td align='right'>".$cost."</td></tr>",true);
                addnav("","armor.php?op=buy&id=".$row['armorid'].$url_mod."");
            }else{
                output("<tr class='$bgcolor'><td>".$row['armorname']."</td>
                <td align='center'>".$row['defense']."</td><td align='right'>".$cost."</td></tr>",true);
                addnav("","armor.php?op=buy&id=".$row['armorid'].$url_mod);
            }
        }
        output("</table>",true);
    } else {
        output("`n`n".$Smithy." `7 blickt zu den leeren Regalen und Haken an der Wand.
        \"Hmm... Schaut aus, als ob da nichts hängt, was weg kann...\" murmelt "
        .(($sex==1)?"sie.":"er.")."",true);
        output("Du entscheidest Dich, es später nochmal zu versuchen in der Hoffnung,
        dass dann der Verkauf irgendwie läuft.");
    }
    $NavSystem["Shop"]["`#Pegasus`0' Waren durchstöbern"]="armor.php?op=browse".$url_mod;
    if ( isset($MyGuild) ) unset($NavSystem["Shop"]["Gilden-eigene Rüstungen"]);
    elseif ( isset($MyClan) ) unset($NavSystem["Shop"]["Clan-eigene Rüstungen"]);
}
PopulateNavs();  // The part of the Nav Management System that displays the Nav's
page_footer(); 
?> 
