
<? 

// modifications by anpera: 
// stealing enabled with 1:15 success (thieves have 2:12 chance) and 'pay from bank' 

global $NavSystem;
global $GuildLeader, $HeadOfWar, $HeadOfMembership, $ClanLeader;
require_once "common.php";
require_once "guildclanfuncs.php"; 
checkday(); 

//
// ----------------------------------------------------------------------------
//
// Weapon.php
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
// Supports Custom Guild Weapons using the standard weapons table.
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
    $WeaponSmith=($MgmtTeam[$HeadOfWar]!=0?
        $MgmtTeam[$HeadOfWar]['name']:
        $MgmtTeam[$GuildLeader]['name']);
} elseif (isset($MyClan)){
    $WeaponSmith=$MgmtTeam[$ClanLeader]['name'];
} else $WeaponSmith="`!MightyE";

// Determine Sex
if (isset($MyGuild)){
    $sex=($MgmtTeam[$HeadOfWar]!=0?
        $MgmtTeam[$HeadOfWar]['sex']:
        $MgmtTeam[$GuildLeader]['sex']);
} elseif (isset($MyClan)){
    $sex=$MgmtTeam[$ClanLeader]['sex'];
} else $sex=0;

// Determine the store name as the Guild Name if the Guild Functionality is being used, otherwise uses MightyE
if (isset($MyGuild)){
    $StoreName=$MyGuild['Name'];
} elseif (isset($MyClan)){
    $StoreName=$MyClan['Name'];
} else $StoreName="MightyE";

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
    $GuildDiscount=($MyGuild['WeaponDiscount']==0?
        0:($MyGuild['WeaponDiscount']/100));
} elseif (isset($MyClan)){
    $GuildDiscount=($MyClan['WeaponDiscount']==0?
        0:($MyClan['WeaponDiscount']/100));
} else $GuildDiscount=0;


if ( isset($MyGuild) || isset($MyClan) ) {
    if ($HTTP_GET_VARS[op]=="guildweapons") {
        page_header(color_sanitize($StoreName)."'s Waffenladen");
        output("`c`b`&".$StoreName."'s Waffenladen `b`ngeführt von ".$WeaponSmith."`c`0`n",true);
        $tradeinvalue = round(($session['user']['weaponvalue']*$TradeInRatio),0);
    } else if ($HTTP_GET_VARS[op]=="peruse") {
        page_header(color_sanitize($StoreName)."'s Waffenladen");
        output("`c`b`&".$StoreName."'s Waffenladen `b`ngeführt von MightyE`c`0`n",true);
        $tradeinvalue = round(($session['user']['weaponvalue']*$TradeInRatio),0);
    } else {
        page_header(color_sanitize($StoreName)."'s Waffenladen");
        output("`c`b`&".$StoreName."'s Waffenladen `b`c`0`n",true);
        $tradeinvalue = round(($session['user']['weaponvalue']*$TradeInRatio),0);
    }
}
else {
    page_header(color_sanitize($StoreName)."'s Waffenladen");
    output("`c`b`&".$StoreName."'s Waffenladen `b`c`0`n",true);
    $tradeinvalue = round(($session['user']['weaponvalue']*$TradeInRatio),0);
}

$NavSystem["Zurück"]["Zurück zum Dorf"]="village.php";

if (isset($MyGuild)) {
    // Links back to the guild and provides an option to display the Guild Defined Weapons
    $NavSystem["Zurück"]["Zurück zum Marktplatz"]=$return;
    //$NavSystem["Shop"]["`#".$Smithy."`0's Waren durchstöbern"]="armor.php?op=browse".$url_mod;
    $NavSystem["Shop"]["Gilden-eigene Waffen"]="weapons.php?op=guildweapons".$url_mod;
}
if (isset($MyClan)) {
    // Links back to the guild and provides an option to display the Guild Defined Weapons
    //$NavSystem["Return"]["Zurück zu ".$MyGuild['Name']]=$return;
    $NavSystem["Zurück"]["Zurück zum Marktplatz"]=$return;
    //$NavSystem["Shop"]["`#".$Smithy."`0's Waren durchstöbern"]="armor.php?op=browse".$url_mod;
    $NavSystem["Shop"]["Clan-eigene Waffen"]="weapons.php?op=guildweapons".$url_mod;
} 
if ( $session['user']['stone'] == 5 )
     $tradeinvalue = $session[user][weaponvalue];
if ($HTTP_GET_VARS[op]==""){ 
    if (isset($MyGuild)) {
        output("`5Du stehst vor einem kleinen Waffenladen, der Deiner Gilde gehört
        und von `#$WeaponSmith`5 geführt wird.`n
        `%Hier können nur Gilden-Mitglieder einkaufen!`n`n`0");
        output("`5Direkt daneben ist ein weiteres Geschäft, das Waffen jeglicher Art 
        ausgestellt hat. Du kannst hinter dem Tresen MightyE ausmachen, der dort ein 
        wenig beschäftigungslos herumsteht.`n
        `%Deine Gilde räumt Dir großzügigerweise einen Rabatt von `&"
        .($GuildDiscount*100)."%`% auf Deine Einkäufe ein!`n`n`0");
        $NavSystem["Shop"]["MightyE's Waffen anschauen"]="weapons.php?op=peruse".$url_mod;
    }
    elseif (isset($MyClan)) {
        output("`5Du stehst vor einem kleinen Waffenladen, der Deinem Clan gehört
        und von `#$WeaponSmith`5 geführt wird.`n
        `%Hier können nur Clan-Mitglieder einkaufen!`n`n`0");
        output("`5Direkt daneben ist ein weiteres Geschäft, das Waffen jeglicher Art
        ausgestellt hat. Du kannst hinter dem Tresen MightyE ausmachen, der dort ein
        wenig beschäftigungslos herumsteht.`n
        `%Dein Clan räumt Dir großzügigerweise einen Rabatt von `&"
        .($GuildDiscount*100)."%`% auf Deine Einkäufe ein!`n`n`0");
        $NavSystem["Shop"]["MightyE's Waffen anschauen"]="weapons.php?op=peruse".$url_mod;
    }
    else {
  output("`!MightyE `7steht hinter einem Ladentisch und scheint dir nur wenig Interesse entgegen zu bringen, als du eintrittst. "); 
    output("Aus Erfahrung weisst du aber, dass er jede deiner Bewegungen misstrauisch beobachtet. Er mag ein bescheidener "); 
    output("Waffenhändler sein, aber er trägt immer noch die Grazie eines Mannes in sich, der seine Waffen gebraucht hat, "); 
    output("um stärkere ".($session[user][gender]?"Frauen":"Männer")." als dich zu töten.`n`n"); 
    output("Der massive Griff eines Claymore ragt hinter seiner Schulter hervor, dessen Schimmer im Licht der Fackeln "); 
    output("viel heller wirkt, als seine Glatze, die er mehr zum strategischen Vorteil rasiert hält, "); 
    output("obwohl auch die Natur bereits auf einem bestimmten Level der Kahlköpfigkeit besteht. "); 
    output("`n`n`!MightyE`7  nickt dir schliesslich zu und wünscht sich, während er seinen Spitzbart streichelt "); 
    output("eine Gelegenheit um eine seiner Waffen benutzen zu können.`n`n"); 
        $NavSystem["Shop"]["Waffen anschauen"]="weapons.php?op=peruse".$url_mod;
    } 
}else if ($HTTP_GET_VARS[op]=="peruse"){ 
    $sql = "SELECT max(level) AS level FROM weapons WHERE level<=".(int)$session[user][dragonkills]; 
    $result = db_query($sql) or die(db_error(LINK)); 
    $row = db_fetch_assoc($result); 
     
  $sql = "SELECT * FROM weapons WHERE level = ".(int)$row[level]." ORDER BY damage ASC"; 
    $result = db_query($sql) or die(db_error(LINK)); 
    output("`7Du schlenderst durch den Laden und tust dein Bestes so auszusehen, als ob du wüßtest, was die meisten dieser Objekte machen. "); 
    output("`!MightyE`7 schaut dich an und sagt \"`#Ich gebe dir `^$tradeinvalue`# "); 
    output(" Gold für `5".$session[user][weapon]."`#. Klicke einfach auf die Waffe, die du kaufen willst... was auch immer 'klick' bedeuten mag`7,\". "); 
    output("Dabei schaut er völlig verwirrt. Er steht ein paar Sekunden nur da, schnippt mit den Fingern und fragt sich, ob das "); 
    output("mit 'klicken' gemeint sein könnte, bevor er sich wieder seiner Arbeit zuwendet: Herumstehen und gut aussehen."); 
    if($session[user][thefttoday]) output("`nEr sieht dich misstrauisch an, als ob er wüsste, dass du hier hin und wieder versuchst, ihm seine schönen Waffen zu klauen."); 
    output("<table border='0' cellpadding='0'>",true); 
    output("<tr class='trhead'><td>`bName`b</td><td align='center'>`bSchaden`b</td><td align='right'>`bPreis`b</td></tr>",true); 
    for ($i=0;$i<db_num_rows($result);$i++){ 
          $row = db_fetch_assoc($result); 
        $bgcolor=($i%2==1?"trlight":"trdark"); 
         $cost=round($row['value'] * (1-$GuildDiscount),0); 
        if ($cost<=($session[user][gold]+$tradeinvalue)){ 
            output("<tr class='$bgcolor'><td>Kaufe 
            <a href='weapons.php?op=buy&id=".$row[weaponid].$url_mod."'>$row[weaponname]</a></td>
            <td align='center'>$row[damage]</td><td align='right'>".$cost."</td></tr>",true); 
            addnav("","weapons.php?op=buy&id=".$row[weaponid].$url_mod.""); 
        }else{ 
            output("<tr class='$bgcolor'><td>
            - - - - <a href='weapons.php?op=buy&id=$row[weaponid]'>$row[weaponname]</a></td>
            <td align='center'>$row[damage]</td><td align='right'>".$cost."</td></tr>",true); 
            addnav("","weapons.php?op=buy&id=$row[weaponid]"); 
        } 
    } 
    output("</table>",true); 
    //addnav("Zurück ins Dorf","village.php"); 
}else if ($HTTP_GET_VARS[op]=="buy"){ 
      $sql = "SELECT * FROM weapons WHERE weaponid='$HTTP_GET_VARS[id]'"; 
    $result = db_query($sql) or die(db_error(LINK)); 
    if (db_num_rows($result)==0){ 
        output("`!".$WeaponSmith."`7 schaut dich eine Sekunde lang verwirrt an und kommt zu dem Schluss, daß du ein paar Schläge zuviel auf den Kopf bekommen hast. Schliesslich nickt er und grinst."); 
        addnav("Nochmal versuchen?","weapons.php.$url_mod"); 
        //addnav("Zurück zum Dorf","village.php"); 
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
                if ($klau==1){ // Fall nur für Diebe 
                    output("`5Mit den Fertigkeiten eines erfahrenen Diebes tauschst du `%$row[weaponname]`5 gegen `%".$session[user][weapon]."`5 aus und verlässt fröhlich pfeifend den Laden. "); 
                    output(" `bGlück gehabt!`b  `!MightyE`5 war gerade durch irgendwas am Fenster abgelenkt. Aber nochmal passiert ihm das nicht! Stolz auf deine "); 
                    output("fette Beute stolzierst du über den Dorfplatz - bis dir jemand mitteilt, daß dir da noch ein Preisschild herumbaumelt...`nDu verlierst einen Charmepunkt!"); 
                    $session[user][weapon] = $row[weaponname]; 
                    $session[user][attack]-=$session[user][weapondmg]; 
                    $session[user][weapondmg] = $row[damage]; 
                    $session[user][attack]+=$session[user][weapondmg]; 
                    $session[user][weaponvalue] = $row[value]; 
                    if ($session[user][charm]) $session[user][charm]-=1; 
                    //addnav("Zurück zum Dorf","village.php"); 
                } else if ($klau==2 || $klau==3) { // Diebstahl gelingt perfekt 
                    output("`5Da dir das nötige Kleingold fehlt, grabschst du dir `%$row[weaponname]`5 und tauscht `%".$session[user][weapon]."`5 unauffälig dagegen aus. "); 
                    output(" `bGlück gehabt!`b `!MightyE`5 war gerade durch irgendwas am Fenster abgelenkt. Aber nochmal wird ihm das nicht passieren! Stolz auf deine "); 
                    output("fette Beute stolzierst du über den Dorfplatz - bis dir jemand mitteilt, daß dir da noch ein Preisschild herumbaumelt...`nDu verlierst einen Charmepunkt!"); 
                    $session[user][weapon] = $row[weaponname]; 
                    $session[user][attack]-=$session[user][weapondmg]; 
                    $session[user][weapondmg] = $row[damage]; 
                    $session[user][attack]+=$session[user][weapondmg]; 
                    $session[user][weaponvalue] = $row[value]; 
                    if ($session[user][charm]) $session[user][charm]-=1; 
                    //addnav("Zurück zum Dorf","village.php"); 
                } else if ($klau==4 || $klau==5) { // Diebstahl gelingt, aber nachher erwischt 
                    output("`5Du grabschst dir `%$row[weaponname]`5 und tauscht `%".$session[user][weapon]."`5 unauffälig dagegen aus. "); 
                    output(" So schnell und unauffällig wie du kannst verlässt du den Laden. Geschafft! Als du mit deiner Beute über den Dorfplatz stolzierst, siehst du aus dem "); 
                    output("Augenwinkel `#MightyE`5 auf dich zurauschen. Er packt dich mit einer Hand an ".$session[user][armor]." und zerrt dich mit zur Stadtbank...`n`n"); 
                    output("`#MightyE`5 zwingt dich mit seinen Händen eng um deinen Hals geschlungen dazu, die `^".($row['value']-$tradeinvalue)."`5 Gold, die du ihm schuldest, von der Bank zu zahlen!"); 
                    if ($session[user][goldinbank]<0){ 
                        output("Da du jedoch schon Schulden bei der Bank hast, bekommt er von dort nicht was er verlangt.`n"); 
                        output("Er entreisst dir $row[weaponname] gewaltsam, "); 
                        output(" drückt dir dein(e/n) alte(n/s) ".$session[user][weapon]." in die Hand und schlägt dich nieder. Er raunzt noch etwas, daß du Glück hast, so arm zu sein, sonst hätte er er dich umgebracht und daß er dich beim nächsten Diebstahl"); 
                        output(" ganz sicher umbringen wird, bevor er in seinen Laden zurück geht, wo bereits ein Kunde wartet.`n"); 
                        $session[user][hitpoints]=round($session[user][hitpoints]/2); 
                    }else{ 
                        $session[user][goldinbank]-=($row[value]-$tradeinvalue); 
                        if ($session[user][goldinbank]<0) output("`nDu hast dadurch jetzt `^".abs($session[user][goldinbank])." Gold`5 Schulden bei der Bank!!"); 
                        output("`nDas nächste Mal bringt er dich um. Da bist du ganz sicher."); 
                        debuglog("lost " . ($row['value']-$tradeinvalue) . " gold in bank for stealing the " . $row['weaponname'] . " weapon"); 
                        $session[user][weapon] = $row[weaponname]; 
                        $session[user][attack]-=$session[user][weapondmg]; 
                        $session[user][weapondmg] = $row[damage]; 
                        $session[user][attack]+=$session[user][weapondmg]; 
                        $session[user][weaponvalue] = $row[value]; 
                    } 
                    //addnav("Zurück zum Dorf","village.php"); 
                } else { // Diebstahl gelingt nicht 
                      output("Während du wartest, bis `!MightyE`7 in eine andere Richtung schaut, näherst du dich vorsichtig dem `5$row[weaponname]`7 und nimmst es leise vom Regal. "); 
                    output("Deiner fetten Beute gewiss drehst du dich leise, vorsichtig, wie ein Ninja, zur Tür, nur um zu entdecken, "); 
                    output("dass `!MightyE`7 drohend in der Tür steht und die den Weg abschneidet. Du versuchst einen Flugtritt. Mitten im Flug hörst du das \"SCHING\" eines Schwerts, "); 
                    output("das seine Scheide verlässt.... dein Fuß ist weg. Du landest auf dem Beinstumpf und `!MightyE`7 steht immer noch im Torbogen, das Schwert ohne Gebrauchsspuren wieder im  Halfter und mit "); 
                    output("vor der stämmigen Brust bedrohlich verschränkten Armen. \"`#Vielleicht willst du dafür bezahlen?`7\" ist alles, was er sagt, "); 
                    output("während du vor seinen Füßen zusammen brichst und deinen Lebenssaft unter deinem dir verbliebenen Fuß über den Boden ausschüttest.`n"); 
                    $session[user][alive]=false; 
                    debuglog("lost " . $session['user']['gold'] . " gold on hand due to stealing from Pegasus"); 
                    $session[user][gold]=0; 
                    $session[user][hitpoints]=0; 
                    $session[user][experience]=round($session[user][experience]*.9,0); 
                    $session[user][gravefights]=round($session[user][gravefights]*0.75); 
                    output("`b`&Du wurdest von `!MightyE`& umgebracht!!!`n"); 
                    output("`4Das Gold, das du dabei hattest, hast du verloren!`n"); 
                    output("`4Du hast 10% deiner Erfahrung verloren!`n"); 
                    output("Du kannst morgen wieder kämpfen.`n"); 
                    output("`nWegen der Unehrenhaftigkeit deines Todes landest du im Fegefeuer und wirst das Reich der Schatten aus eigener Kraft heute nicht mehr verlassen können!"); 
                    addnav("Tägliche News","news.php"); 
                    addnews("`%".$session[user][name]."`5 wurde beim Versuch in `!MightyE`5's Waffenladen zu stehlen niedergemetzelt."); 
            unset($NavSystem["Zurück"]["Zurück zum Dorf"]);
                } 
                if ($session[user][thefttoday]==1) $session[user][thefttoday]=2; 
            }else{ 
                $session[user][thefttoday]=1; 
                if ($klau==1){ // Fall nur für Diebe 
                    output("`5Mit den Fertigkeiten eines erfahrenen Diebes tauschst du `%$row[weaponname]`5 gegen `%".$session[user][weapon]."`5 aus und verlässt fröhlich pfeifend den Laden. "); 
                    output(" `bGlück gehabt!`b  `!MightyE`5 war gerade durch irgendwas am Fenster abgelenkt. Aber irgendwann wird er den Diebstahl bemerken und in Zukunft wesentlich besser aufpassen! Stolz auf deine "); 
                    output("fette Beute stolzierst du über den Dorfplatz - bis dir jemand mitteilt, daß dir da noch ein Preisschild herumbaumelt...`nDu verlierst einen Charmepunkt!"); 
                    $session[user][weapon] = $row[weaponname]; 
                    $session[user][attack]-=$session[user][weapondmg]; 
                    $session[user][weapondmg] = $row[damage]; 
                    $session[user][attack]+=$session[user][weapondmg]; 
                    $session[user][weaponvalue] = $row[value]; 
                    if ($session[user][charm]) $session[user][charm]-=1; 
                    //addnav("Zurück zum Dorf","village.php"); 
                } else if ($klau==2 || $klau==3) { // Diebstahl gelingt perfekt 
                    output("`5Da dir das nötige Kleingold fehlt, grabschst du dir `%$row[weaponname]`5 und tauscht `%".$session[user][weapon]."`5 unauffälig dagegen aus. "); 
                    output(" `bGlück gehabt!`b `!MightyE`5 war gerade durch irgendwas am Fenster abgelenkt. Aber irgendwann wird er den Diebstahl bemerken und in Zukunft besser aufpassen. Stolz auf deine "); 
                    output("fette Beute stolzierst du über den Dorfplatz - bis dir jemand mitteilt, daß dir da noch ein Preisschild herumbaumelt...`nDu verlierst einen Charmepunkt!"); 
                    $session[user][weapon] = $row[weaponname]; 
                    $session[user][attack]-=$session[user][weapondmg]; 
                    $session[user][weapondmg] = $row[damage]; 
                    $session[user][attack]+=$session[user][weapondmg]; 
                    $session[user][weaponvalue] = $row[value]; 
                    if ($session[user][charm]) $session[user][charm]-=1; 
                    //addnav("Zurück zum Dorf","village.php"); 
                } else if ($klau==4 || $klau==5) { // Diebstahl gelingt, aber nachher erwischt 
                    output("`5Du grabschst dir `%$row[weaponname]`5 und tauscht `%".$session[user][weapon]."`5 unauffälig dagegen aus. "); 
                    output(" So schnell und unauffällig wie du kannst verlässt du den Laden. Geschafft! Als du mit deiner Beute über den Dorfplatz stolzierst, siehst du aus dem "); 
                    output("Augenwinkel `#MightyE`5 auf dich zurauschen. Er packt dich mit einer Hand an ".$session[user][armor]." und zerrt dich mit zur Stadtbank...`n`n"); 
                    output("`#MightyE`5 zwingt dich mit seinen Händen eng um deinen Hals geschlungen dazu, die `^".($row['value']-$tradeinvalue)."`5 Gold, die du ihm schuldest, von der Bank zu zahlen!"); 
                    if ($session[user][goldinbank]<0){ 
                        output("Da du jedoch schon Schulden bei der Bank hast, bekommt er von dort nicht was er verlangt.`n"); 
                        output("Er entreisst dir $row[weaponname] gewaltsam, "); 
                        output(" drückt dir dein(e/n) alte(n/s) ".$session[user][weapon]." in die Hand und schlägt dich nieder. Er raunzt noch etwas, daß du Glück hast, so arm zu sein, sonst hätte er er dich umgebracht und daß er dich beim nächsten Diebstahl"); 
                        output(" ganz sicher umbringen wird, bevor er in seinen Laden zurück geht, wo bereits ein Kunde wartet.`n"); 
                        $session[user][hitpoints]=round($session[user][hitpoints]/2); 
                    }else{ 
                        $session[user][goldinbank]-=($row[value]-$tradeinvalue); 
                        if ($session[user][goldinbank]<0) output("`nDu hast dadurch jetzt `^".abs($session[user][goldinbank])." Gold`5 Schulden bei der Bank!!"); 
                        debuglog("lost " . ($row['value']-$tradeinvalue) . " gold in bank for stealing the " . $row['weaponname'] . " weapon"); 
                        output("`nDas nächste Mal bringt er dich wahrscheinlich um."); 
                        $session[user][weapon] = $row[weaponname]; 
                        $session[user][attack]-=$session[user][weapondmg]; 
                        $session[user][weapondmg] = $row[damage]; 
                        $session[user][attack]+=$session[user][weapondmg]; 
                        $session[user][weaponvalue] = $row[value]; 
                    } 
                    //addnav("Zurück zum Dorf","village.php"); 
                } else { // Diebstahl gelingt nicht 
                    output("`5Du grabschst dir `%$row[weaponname]`5 und tauscht `%".$session[user][weapon]."`5 unauffälig dagegen aus. "); 
                    output(" So schnell und unauffällig wie du kannst verlässt du den Laden. Geschafft! Als du mit deiner Beute über den Dorfplatz stolzierst, siehst du aus dem "); 
                    output("Augenwinkel `#MightyE`5 auf dich zurauschen. Er packt dich mit einer Hand an ".$session[user][armor].".`n`n"); 
                    output("Er entreisst dir $row[weaponname] gewaltsam, "); 
                    output(" drückt dir dein(e/n) alte(n/s) ".$session[user][weapon]." in die Hand und schlägt dich nieder. Er raunzt noch etwas, daß er dich beim nächsten Diebstahl"); 
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
            //output("`!MightyE`7 nimmt dein `5".$session[user][weapon]."`7 stellt es aus und hängt sofort ein neues Preisschild dran. "); 
            output("`!".$WeaponSmith."`7 nimmt dein `5".$session[user][weapon]."`7 stellt es aus und hängt sofort ein neues Preisschild dran. "); 
            $cost=round($row['value'] * (1-$GuildDiscount),0);
            debuglog("spent " . ($cost-$tradeinvalue) . " gold on the " . $row['weaponname'] . " weapon"); 
             $session[user][gold]-=$cost; 
            $session[user][weapon] = $row[weaponname]; 
            $session[user][gold]+=$tradeinvalue; 
            $session[user][attack]-=$session[user][weapondmg]; 
            $session[user][weapondmg] = $row[damage]; 
            $session[user][attack]+=$session[user][weapondmg]; 
            $session[user][weaponvalue] = $cost; 
            output("`n`nIm Gegenzug händigt er dir ein glänzendes neues `5$row[weaponname]`7 aus, das du probeweise im Raum schwingst. Dabei schlägst du `!MightyE`7' fast den Kopf ab. "); 
            output("Er duckt sich so, als ob du nicht der erste bist, der seine neue Waffe sofort ausprobieren will..."); 
            //addnav("Zurück zum Dorf","village.php"); 
            unset($NavSystem['Shop']); 
        }
    } 
}else if ($HTTP_GET_VARS[op]=="guildweapons"){ 
    // Displays the custom Weapons defined by the Guild or Clan
    if (isset($MyGuild)) {
        $WeaponRef=1014+($MyGuild['ID']*100);  // Determine the offset in the Weapon table Level field
    } else {
        $WeaponRef=1014+($MyClan['ID']*100);  // Determine the offset in the Weapon table Level field
    }
    $sql = "SELECT * FROM weapons WHERE level >= ".$WeaponRef." and level<=".($WeaponRef+99)." ORDER BY damage ASC";
    $result = db_query($sql) or die(db_error(LINK));
    output("`7Du gehst zum Verkaufstisch und tust so, als ob Du genau wüsstest, was diese ganzen Waffen bewirken.
    ".$WeaponSmith."`7 lächelt Dich an und steckt dann ein Ast in ein Loch in der Wand. Sofort schwingt eine geheime
    Tür auf, und Du kannst Dir die Waffen ansehen, die vom Schmied der Guilde angefertigt wurden.`n
    ".$WeaponSmith."`7 schaut Dich an und sagt: \"`#Ich kann Dir `^".$tradeinvalue." Gold `# für Dein `5"
    .$session['user']['weapon']."`# geben. Klicke auf die Waffe, die Du kaufen willst. Was immer auch \"Klick\"
    heissen mag...`7\". Erwartungsvoll schaut ".(($sex==1)?"sie":"er")." Dich an.`n`n`0");
    $RowCount=db_num_rows($result);
    if ($RowCount!=0) {
        output("<table border='0' cellpadding='0'>",true);
        output("<tr class='trhead'><td>`bName`b</td><td align='center'>`bSchaden`b</td><td align='right'>`bGoldpreis`b</td></tr>",true);
        for ($i=0;$i<$RowCount;$i++){
          $row = db_fetch_assoc($result);
            $bgcolor=($i%2==1?"trlight":"trdark");
            $cost=round($row['value'] * (1-$GuildDiscount),0);
            if ($cost<=($session[user][gold]+$tradeinvalue)){
                output("<tr class='$bgcolor'><td><a href='weapons.php?op=buy&id=".$row[weaponid].$url_mod."'>$row[weaponname]</a></td><td align='center'>$row[damage]</td><td align='right'>".$cost."</td></tr>",true);
                addnav("","weapons.php?op=buy&id=".$row[weaponid].$url_mod."");
            }else{
                output("<tr class='$bgcolor'><td>$row[weaponname]</td><td align='center'>$row[damage]</td><td align='right'>".$cost."</td></tr>",true);
                addnav("","weapons.php?op=buy&id=$row[weaponid]");
            }
        }
        output("</table>",true);
    } else {
        output("`n`n".$WeaponSmith." `7 blickt zu den leeren Regalen und Haken an der Wand.
        \"Hmm... Schaut aus, als ob da nichts hängt, was weg kann...\" murmelt "
        .(($sex==1)?"sie.":"er.")."",true);
        output("Du entscheidest Dich, es später nochmal zu versuchen in der Hoffnung,
        dass dann der Verkauf irgendwie läuft.");
    }
    $NavSystem["Shop"]["MightyE's Waffen anschauen"]="weapons.php?op=peruse".$url_mod;
    if ( isset($MyGuild) ) unset($NavSystem["Shop"]["Gilden-eigene Waffen"]);
    elseif ( isset($MyClan) ) unset($NavSystem["Shop"]["Clan-eigene Waffen"]);
}

PopulateNavs();  // The part of the Nav Management System that displays the Nav's
page_footer(); 
?> 
