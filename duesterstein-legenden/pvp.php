
<?
global $session;  // Guilds/Clans
require_once "common.php";
$session[user][locate]=8;
$pvptime = getsetting("pvptimeout",600);
$pvptimeout = date("Y-m-d H:i:s",strtotime("-$pvptime seconds"));
page_header("PvP Kampf!");
if ($HTTP_GET_VARS[op]=="" && $HTTP_GET_VARS[act]!="attack"){
    //if ($session['user']['age']<=5 && $session['user']['dragonkills']==0){
    //  output("`\$Warning!`^ Players are immune from Player vs Player (PvP) combat for their first 5 days in the game.  If you choose to attack another player, you will lose this immunity!`n`n");
    //}
    checkday();
    pvpwarning();
      output("`4Du gehst raus in die Felder, von denen Du weißt das hier einige Krieger schlafen.`n`nDu hast heute noch `^".$session[user][playerfights]."`4 PvP Kämpfe übrig.");
    addnav("Krieger auflisten","pvp.php?op=list");
      addnav("Zurück zum Dorf","village.php");
}else if ($HTTP_GET_VARS[op]=="list"){
    checkday();
    pvpwarning();
    $days = getsetting("pvpimmunity", 5);
    $exp = getsetting("pvpminexp", 1500);
      $sql = "SELECT name,alive,location,sex,level,laston,loggedin,login,pvpflag FROM accounts WHERE  
        (locked=0) AND  
        (age > $days OR dragonkills > 0 OR pk > 0 OR experience > $exp) AND 
        (level >= ".($session[user][level]-1)." AND level <= ".($session[user][level]+2).") AND 
        (alive=1 AND location=0) AND 
        (race>0 AND specialty>0) AND 
        (dragonkills >= ".($session[user][dragonkills]-5).") AND 
        (laston < '".date("Y-m-d H:i:s",strtotime("-".getsetting("LOGINTIMEOUT",900)." sec"))."' OR loggedin=0) AND 
        (acctid <> ".$session[user][acctid].") 
        ORDER BY level DESC";
    //echo ("<pre>$sql</pre>");
      $result = db_query($sql) or die(db_error(LINK));
    output("<table border='0' cellpadding='3' cellspacing='0'><tr><td>Name</td><td>Level</td><td>Ops</td></tr>",true);
     if ($session['user']['pvpflag']=="2033-10-06 00:42:00") output("`n`&(J.C. Petersen gewährt Dir PvP Immunität. Du wirst diese aber verlieren, solltest Du jetzt jemand anderen angreifen!)`0`n`n"); 
    for ($i=0;$i<db_num_rows($result);$i++){
      $row = db_fetch_assoc($result);
      $biolink="bio.php?char=".rawurlencode($row[login])."&ret=".urlencode($_SERVER['REQUEST_URI']);
      addnav("", $biolink);
        if($row[pvpflag]>$pvptimeout){
         output("<tr class='".($i%2?"trlight":"trdark")."'><td>$row[name]</td><td>$row[level]</td><td>[ <a href='$biolink'>Bio</a> | `iPvP Immun`i ]</td></tr>",true); 
        }else{
          output("<tr class='".($i%2?"trlight":"trdark")."'><td>$row[name]</td><td>$row[level]</td><td>[ <a href='$biolink'>Bio</a> | <a href='pvp.php?act=attack&name=".rawurlencode($row[login])."'>Attack</a> ]</td></tr>",true);
            addnav("","pvp.php?act=attack&name=".rawurlencode($row[login]));
        }
    }
    output("</table>",true);
    addnav("Krieger auflisten","pvp.php?op=list");
  addnav("Zurück zum Dorf","village.php");
   if (getsetting("hasegg",0)>0){ 
   $sql = "SELECT name FROM accounts WHERE acctid = ".getsetting("hasegg",0); 
   $result = db_query($sql) or die(db_error(LINK)); 
   $row = db_fetch_assoc($result); 
   output("`n`n$row[name] hat das goldene Ei!"); 
  }
} else if ($HTTP_GET_VARS[act] == "attack") {
  $sql = "SELECT name AS creaturename,
                       level AS creaturelevel,
            weapon AS creatureweapon,
            gold AS creaturegold,
            gems AS creaturegems,
            experience AS creatureexp,
            maxhitpoints AS creaturehealth,
            attack AS creatureattack,
            defence AS creaturedefense,
            bounty AS creaturebounty,
            loggedin,
            location,
            laston,
            alive,
            acctid,
            pvpflag
            , guildID, clanID, guildrank
    FROM accounts
    WHERE login=\"$HTTP_GET_VARS[name]\"";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)>0){
        $row = db_fetch_assoc($result);
        if (abs($session[user][level]-$row[creaturelevel])>2 && $row[location]!=2){
          output("`\$Fehler:`4 Der Spieler ist außerhalb Deiner Level Reichweite!");
        }elseif ($row[pvpflag] > $pvptimeout){
            output("`\$Oops:`4 Der Benutzer ist gerade durch jemand anderen beschäftigt, Du mußt leider warten bis Du dran bist! $row[pvpflag] : $pvptimeout");
        }else{
          if (strtotime($row[laston]) > strtotime("-".getsetting("LOGINTIMEOUT",900)." sec") && $row[loggedin]){
              output("`\$Fehler:`4 Der Benutzer in nun angemeldet.");
            }else{
              if ((int)$row[location]!=0 && 0 && $row[location]!=2){ 
                  output("`\$Fehler:`4 Der Benutzer ist an keinem Ort, wo Du ihn angreifen kannst.");
                }else{
                  if((int)$row[alive]!=1){
                      output("`\$Fehler:`4 Der Benutzer ist nicht am leben.");
                    }else{
                      if ($session[user][playerfights]>0){
                            $sql = "UPDATE accounts SET pvpflag=now() WHERE acctid=$row[acctid]";
                            db_query($sql);
                            $battle=true;
                            $row[pvp]=1;
                            $row[creatureexp] = round($row[creatureexp],0);
                            $row[playerstarthp] = $session[user][hitpoints];
                            $session[user][badguy]=createstring($row);
                            $session[user][playerfights]--;
                            $session['user']['buffbackup']="";
                             if ($session['user']['pvpflag']=="2033-10-06 00:42:00"){ 
                                    $session['user']['pvpflag']="1986-10-06 00:42:00"; 
                                    output("`n`4`bDu hast Deine Immunität verloren!`b`0`n"); 
                             }
                            pvpwarning(true);
                        }else{
                          output("`4Aufgrund Deiner Müdigkeit denkst Du, dass es für heute besser ist keinen weiteren Kampf mehr zu bestreiten.");
                        }
                    }
                }
            }
        }
    }else{
      output("`\$Fehler:`4 Der Benutzer wurde nicht gefunden!  Wie bist Du überhaupt hergekommen?");
    }
  if ($battle){
      
    }else{
      addnav("zurück zum Dorf","village.php");
    }
}
if ($HTTP_GET_VARS[op]=="run"){
  output("Dein stolz verhindert Deine Flucht");
    $HTTP_GET_VARS[op]="fight";
}
// Single use potions code - Dasher
if ($HTTP_GET_VARS[skill]!=""){
    if ($HTTP_GET_VARS['skill']=="UAP") {
    } else {
         output("Dein Stolz läßt Dich auf Deine Spezialfähigkeiten verzichten.");
    $HTTP_GET_VARS[skill]="";
    }
}
// end change
if ($HTTP_GET_VARS[op]=="fight" || $HTTP_GET_VARS[op]=="run"){
    $battle=true;
}
if ($battle){
  include("battle.php");
    if ($victory){
        //$badguy[creaturegold]=e_rand(0,$badguy[creaturegold]);
        $exp = round(getsetting("pvpattgain",10)*$badguy[creatureexp]/100,0);
        $expbonus = round(($exp * (1+.1*($badguy[creaturelevel]-$session[user][level]))) - $exp,0);
        output("`b`&$badguy[creaturelose]`0`b`n"); 
        output("`b`\$Du hast $badguy[creaturename]!`0 geschlagen`b`n");
        output("`#Du bekommst `^$badguy[creaturegold]`# gold ");
        //gems is a modification of LordRaven
        output("`#und Du bekommst `^$badguy[creaturegems]`# Edelsteine!`n");
        // Bounty Check - Darrell Morrone
        if ($badguy[creaturebounty]>0){
            output("`#Zusätzlich bekommst Du `^$badguy[creaturebounty]`# Gold als Kopfgeld!`n");
            }
        // End Bounty Check - Darrell Morrone
        if ($expbonus>0){
          output("`#***Wegen der Schwierigkeit dieses Kampfes bekommst Du `^$expbonus`# Erfahrungspunkte extra!`n");
        }else if ($expbonus<0){
          output("`#***Wegen der Leichtigkeit des Kampfes bekommst Du `^".abs($expbonus)."`# Erfahrungspunkte weniger!`n");
        }
        output("Du bekommst `^".($exp+$expbonus)."`# Erfahrungspunkte!`n`0");
        $session['user']['gold']+=$badguy['creaturegold'];
        if ($badguy['creaturegold']) {
            debuglog("Bekam {$badguy['creaturegold']} Gold für den Sieg über ", $badguy['acctid']);
        }
        // Add gems - LordRaven
        $session['user']['gems']+=$badguy['creaturegems'];
        if ($badguy['creaturegems']) {
            debuglog("Bekam {$badguy['creaturegems']} Edelsteine für den Sieg über ", $badguy['acctid']);
        }
        // Add Bounty Gold - Darrell Morrone
        $session['user']['gold']+=$badguy['creaturebounty'];
        if ($badguy['creaturebounty']) {
            debuglog("bekam {$badguy['creaturebounty']} Gold Kopfgeld für den Sieg über ", $badguy['acctid']);
        }
        $session['user']['experience']+=($exp+$expbonus);
        if ($badguy['location']==1){ 
            addnews("`4".$session['user']['name']."`3 besiegt `4{$badguy['creaturename']}`3 brutal in einem Zimmer in der Kneipe!"); 
            $killedin="`6der Kneipe"; 
        } else if ($badguy['location']==2){ 
            addnews("`4".$session['user']['name']."`3 besiegt `4{$badguy['creaturename']}`3 bei einem Einbruch ins Haus!"); 
            $killedin="`6`2einem Haus";
        }else{
          addnews("`4".$session['user']['name']."`3 besiegt `4{$badguy['creaturename']}`3 in einem fairen Kampf in den Feldern.");
            $killedin="`@den Feldern";
        }
        // Add Bounty Kill to the News - Darrell Mororne
        if ($badguy['creaturebounty']>0){
            addnews("`4".$session['user']['name']."`3 bekommt `4{$badguy['creaturebounty']} Gold Kopfgeld für den Sieg über `4{$badguy['creaturename']}!");
            }
              // Golden Egg - anpera 
              if ($badguy['acctid']==getsetting("hasegg",0)){ 
                 savesetting("hasegg",stripslashes($session[user][acctid])); 
                 output("`n`^Du nimmst $badguy[creaturename] `^das goldene Ei ab!`0`n"); 
                 addnews("`^".$session['user']['name']."`^ nimmt {$badguy['creaturename']}`^ das goldene Ei ab!"); 
              }
        $sql = "SELECT gold, gems FROM accounts WHERE acctid='".(int)$badguy['acctid']."'";
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        $badguy[creaturegold]=((int)$row[gold]>(int)$badguy[creaturegold]?(int)$badguy[creaturegold]:(int)$row[gold]);
        $badguy[creaturegems]=((int)$row[gems]>(int)$badguy[creaturegems]?(int)$badguy[creaturegems]:(int)$row[gems]);
        //$sql = "UPDATE accounts SET alive=0, killedin='$killedin', goldinbank=goldinbank-IF(gold<$badguy[creaturegold],gold-$badguy[creaturegold],0),gold=gold-$badguy[creaturegold], experience=experience*.95, slainby=\"".addslashes($session[user][name])."\" WHERE acctid=$badguy[acctid]";
// \/- Gunnar Kreitz
        $lostexp = round($badguy['creatureexp']*getsetting("pvpdeflose",5)/100,0);
         $mailmessage = "`^".$session['user']['name']."`2 hat Dich in $killedin`2 angegriffen und mit %p `^".$session['user']['weapon']."`2, besiegt!"
                ." `n`n%n %o hatte am Anfang `^".$badguy['playerstarthp']."`2 Lebenspunkte und %m hatte am Ende noch `^".$session['user']['hitpoints']."`2 übrig."
                ." `n`nAls Ergebnis verlierst Du `\$".getsetting("pvpdeflose",5)."%`2 Deiner Erfahrungspunkte (ungefähr $lostexp Punkte), `^".$badguy[creaturegold]."`2 Gold und `^".$badguy[creaturegems]."`2 Edelsteine. %n %o bekam zusätzlich `^".$badguy[creaturebounty]." `2Gold Kopfgeld."
                ." `n`nDenkst Du nicht, dass es Zeit ist sich zu rächen?";
         $mailmessage = str_replace("%p",($session['user']['sex']?"ihrer":"seiner"),$mailmessage);
         $mailmessage = str_replace("%o",($session['user']['sex']?"Gegnerin":"Gegner"),$mailmessage);
        $mailmessage = str_replace("%n",($session['user']['sex']?"Deine":"Dein"),$mailmessage);
        $mailmessage = str_replace("%m",($session['user']['sex']?"sie":"er"),$mailmessage);
         systemmail($badguy['acctid'],"`2Du wurdest in $killedin`2 getötet",$mailmessage); 
// /\- Gunnar Kreitz
        $sql = "UPDATE accounts SET alive=0, bounty=0, goldinbank=goldinbank-IF(gold<$badguy[creaturegold],gold-$badguy[creaturegold],0),gold=gold-$badguy[creaturegold], gems=0, experience=experience-$lostexp WHERE acctid=".(int)$badguy[acctid]."";        
        db_query($sql);
        
        $HTTP_GET_VARS[op]="";
        if ($badguy['location']==1){ 
            addnav("Zurück zur Kneipe","inn.php"); 
        } else if ($badguy['location']==2){ 
            addnav("Zurück zum Wohnviertel","houses.php"); 
        } else {
            addnav("Zurück zum Dorf","village.php");
        }
                // Guilds/Clans Code
                if ($session['user']['guildID']!=0) {
                    $MyGuild=&$session['guilds'][$session['user']['guildID']];
                    if (isset($MyGuild)) {
                        $sitepoints=1;
                        if ($badguy['guildID']!=0) {
                            $TheirGuild=$session['guilds'][$badguy['guildID']];
                            if ($MyGuild['ID']==$TheirGuild['ID']) {
                                // You have attacked somebody in your own guild!
                                output("`nDu hast jemanden aus Deiner Gilde angegriffen und getötet!!!`n
                                Deine Gilde wird über den Vorfall informiert!");
                                $who=(($MyGuild['HeadOfWar']!=0)?$MyGuild['HeadOfWar']:$MyGuild['GuildLeader']);
                                systemmail($who,"Gildenmitglied angegriffen und getötet!!",
                                    "Einer der Gildenmitglieder, ".$session['user']['name'].",
                                    hat das Mitglied ".$badguy['creaturename']." angegriffen und
                                    getötet!",0);
                                $sitepoints-=3;
                            }
                            if (isset($TheirGuild['Hitlist'][$session['user']['acctid']])) {
                            // But you were on their hitlist!
                                output("`&`nDu standest auf ihrer Hitlist, aber Du hast gut
                                gekämpft und gewonnen!`n
                                Herzlichen Glückwunsch!!");
                                $sitepoints+=1;
                            }
                        } else {
                            // They are not a member of a guild
                        }
                        if (isset($MyGuild['Hitlist'][$badguy['acctid']])) {
                            // They are on my guild hitlist
                            $sitepoints+=1;
                            output("`&`nDu hast erfolgreich gegen ein mächtiges Gildenmitglied
                            gekämpft, das auf der Hitlist stand!`n
                            Herzlichen Glückwunsch!!");
                            $hitlist=&$MyGuild['Hitlist']; // Remove them from the guild hitlist
                            unset($hitlist[$badguy['acctid']]);
                        }
                        $GuildFee = round((($MyGuild['PercentOfFightsEarned']['PvP']/100) * $badguy['creaturegold']),0);
                        if ($GuildFee<=0) $GuildFee=(($session['user']['level']*10) * ($MyGuild['PercentOfFightsEarned']['PvP']/100)+1);
                        output("`&`nDeine Gilde fordert ihren Anteil. Du zahlst `^".$GuildFee." Gold `&Tribut.");
                        output("`%`nDeine Gilde bekommt `&".$sitepoints." `%Punkte für diesen Kampf.");
                        $MyGuild['SitePoints']+=$sitepoints;
                        if ($session['user']['gold']<$GuildFee) {
                            $session['user']['goldinbank']+=($session['user']['gold']-$GuildFee);
                            $session['user']['gold']=0;
                            output("`nDu zahlst einen Teil des Tributs direkt von der Bank!");
                        } else {
                            $session['user']['gold']-=$GuildFee;
                        }
                        $MyGuild['gold']+=$GuildFee;
                        update_guild_info($MyGuild);
                        addnews("Im Namen seiner Gilde metzelte ".$session['user']['name']." seinen
                        Gegner ".$badguy['creaturename']." im Kampf nieder - hart und fair!");
                    } else {
                        // Error
                        // Their guildID is set but the information cannot be retrieved
                        $debug=print_r($session['user']['guildID'],true);
                        debuglog("MyGuild isn't set: ".$debug);
                    }
                } elseif ($session['user']['clanID']!=0) {
                    $MyClan=&$session['guilds'][$session['user']['clanID']];
                    if (isset($MyClan)) {
                        $sitepoints=1;
                        if ($badguy['clanID']!=0) {
                            $TheirClan=$session['guilds'][$badguy['clanID']];
                            if ($MyClan['ID']==$TheirClan['ID']) {
                                // You have attacked somebody in your own guild!
                                output("`nDu hast jemanden aus Deinem Clan angegriffen und getötet!!!`n
                                Dein Clan wird über den Vorfall informiert!");
                                $who=$MyClan['GuildLeader'];
                                systemmail($who,"Clanmitglied angegriffen und getötet!!",
                                    "Einer der Clanmitglieder, ".$session['user']['name'].",
                                    hat das Mitglied ".$badguy['creaturename']." angegriffen und
                                    getötet!",0);
                                $sitepoints-=3;
                            }
                            if (isset($TheirClan['Hitlist'][$session['user']['acctid']])) {
                            // But you were on their hitlist!
                                output("`&`nDu standest auf ihrer Hitlist, aber Du hast gut
                                gekämpft und gewonnen!`n
                                Herzlichen Glückwunsch!!");
                                $sitepoints+=1;
                            }
                        } else {
                            // They are not a member of a clan
                        }
                        if (isset($MyClan['Hitlist'][$badguy['acctid']])) {
                            // They are on my clan hitlist
                            $sitepoints+=1;
                            output("`&`nDu hast erfolgreich gegen ein mächtiges Clanmitglied
                            gekämpft, das auf der Hitlist stand!`n
                            Herzlichen Glückwunsch!!");
                            $hitlist=&$MyClan['Hitlist']; // Remove them from the clan hitlist
                            unset($hitlist[$badguy['acctid']]);
                        }
                        $GuildFee = round((($MyClan['PercentOfFightsEarned']['PvP']/100) * $badguy['creaturegold']),0);
                        if ($GuildFee<=0) $GuildFee=(($session['user']['level']*10) * ($MyClan['PercentOfFightsEarned']['PvP']/100)+1);
                        output("`&`nDein Clan fordert seinen Anteil. Du zahlst `^".$GuildFee." Gold `&Tribut.");
                        output("`%`nDein Clan bekommt `&".$sitepoints." `%Punkte für diesen Kampf.");
                        $MyClan['SitePoints']+=$sitepoints;
                        if ($session['user']['gold']<$GuildFee) {
                            $session['user']['goldinbank']+=($session['user']['gold']-$GuildFee);
                            $session['user']['gold']=0;
                            output("`nDu zahlst einen Teil des Tributs direkt von der Bank!");
                        } else {
                            $session['user']['gold']-=$GuildFee;
                        }
                        $MyClan['gold']+=$GuildFee;
                        update_guild_info($MyClan);
                        addnews("Im Namen seines Clans metzelte ".$session['user']['name']." seinen
                        Gegner ".$badguy['creaturename']." im Kampf nieder - hart und fair!");
                    } else {
                        // Error
                        // Their guildID is set but the information cannot be retrieved
                        $debug=print_r($session['user']['clanID'],true);
                        debuglog("MyClan isn't set: ".$debug);
                    }
                 } else {
                  // They don't belong to a guild or clan
                 }
                //
        $badguy=array();
    }else{
        if($defeat){
            addnav("Tägliche News","news.php");
            $sql = "SELECT taunt FROM taunts ORDER BY rand(".e_rand().") LIMIT 1";
            $result = db_query($sql) or die(db_error(LINK));
            $taunt = db_fetch_assoc($result);
            $taunt = str_replace("%s",($session[user][sex]?"her":"him"),$taunt[taunt]);
            $taunt = str_replace("%o",($session[user][sex]?"she":"he"),$taunt);
            $taunt = str_replace("%p",($session[user][sex]?"her":"his"),$taunt);
            $taunt = str_replace("%x",($session[user][weapon]),$taunt);
            $taunt = str_replace("%X",$badguy[creatureweapon],$taunt);
            $taunt = str_replace("%W",$badguy[creaturename],$taunt);
            $taunt = str_replace("%w",$session[user][name],$taunt);
            if ($badguy[location]==1){ 
                $killedin="`6der Kneipe"; 
            } else if ($badguy[location]==2){ 
                $killedin="`2einem Haus";
            }else{
                $killedin="`@den Feldern";
            }
            $badguy[acctid]=(int)$badguy[acctid];
            $badguy[creaturegold]=(int)$badguy[creaturegold];
            $badguy[creaturegems]=(int)$badguy[creaturegems];
            systemmail($badguy[acctid],"`2Du warst erfolgreich in $killedin`2","`^".$session[user][name]."`2 hat Dich in $killedin`2 angegriffen, aber Du hast gesiegt!`n`nAls Ergebnis bekommst Du `^".round($session[user][experience]*getsetting("pvpdefgain",10)/100,0)."`2 Erfahrung `^".$session[user][gold]."`2 Gold und `^".$session[user][gems]."`2 Edelsteine!"); 
            addnews("`%".$session[user][name]."`5 wurde niedergemtzelt, als ".($session[user][sex]?"sie":"er")." $badguy[creaturename] in $killedin angriff`5.`n$taunt");
                       // Guild Clans
                        if ($badguy['guildID']!=0) {
                            $TheirGuild=&$session['guilds'][$badguy['guildID']];
                            $GuildFee = round((($TheirGuild['PercentOfFightsEarned']['PvP']/100) * $session['user']['gold']),0);

                            $sitepoints=1;
                            if ($session['user']['guildID']!=0) {
                                $MyGuild=&$session['guilds'][$session['user']['guildID']];
                                if ($MyGuild['ID']==$TheirGuild['ID']) {
                                    // You have attacked somebody in your own guild!
                                    output("`nDu hast jemanden aus Deiner Gilde angegriffen!!!");
                                    $who=(($MyGuild['HeadOfWar']!=0)?$MyGuild['HeadOfWar']:$MyGuild['GuildLeader']);
                                    systemmail($who,"Gildenmitglied angegriffen!!",
                                        "Einer der Gildenmitglieder, ".$session['user']['name'].",
                                        hat das Mitglied ".$badguy['creaturename']." angegriffen -
                                        aber nicht getötet.",0);
                                    $sitepoints-=3;
                                }
                            } else {
                                // We don't belong to a guild
                            }
                            if (isset($TheirGuild['Hitlist'][$session['user']['acctid']])) {
                                // But you were on their hitlist!
                                output("`&`nDu standest auf ihrer Hitlist - und Du wurdest besiegt.`n
                                Boah!!");
                                $sitepoints+=1;
                            } else {
                             // You weren't on thier hitlist
                            }
                            $TheirGuild['SitePoints']+=$sitepoints;
                            $TheirGuild['gold']+=$GuildFee;
                            update_guild_info($TheirGuild);
                        } elseif ($badguy['clanID']!=0) {
                            $TheirClan=&$session['guilds'][$badguy['clanID']];
                            $GuildFee = round((($TheirClan['PercentOfFightsEarned']['PvP']/100) * $session['user']['gold']),0);

                            $sitepoints=1;
                            if ($session['user']['clanID']!=0) {
                                $MyClan=&$session['guilds'][$session['user']['clanID']];
                                if ($MyClan['ID']==$TheirClan['ID']) {
                                    // You have attacked somebody in your own clan!
                                    output("`nDu hast jemanden aus Deinem Clan angegriffen!!!");
                                    $who=$MyClan['GuildLeader'];
                                    systemmail($who,"Clanmitglied angegriffen!!",
                                        "Einer der Clanmitglieder, ".$session['user']['name'].",
                                        hat das Mitglied ".$badguy['creaturename']." angegriffen -
                                        aber nicht getötet.",0);
                                    $sitepoints-=3;
                                }
                            } else {
                                // We don't belong to a clan
                            }
                            if (isset($TheirClan['Hitlist'][$session['user']['acctid']])) {
                                // But you were on their hitlist!
                                output("`&`nDu standest auf ihrer Hitlist - und Du wurdest besiegt.`n
                                Boah!!");
                                $sitepoints+=1;
                            } else {
                             // You weren't on thier hitlist
                            }
                            $TheirClan['SitePoints']+=$sitepoints;
                            $TheirClan['gold']+=$GuildFee;
                            update_guild_info($TheirClan);
                        } else {
                                // They are not a member of a guild or clan
                                $GuildFee=0;
                        }
                        // End of Guild Clans change
            $sql = "UPDATE accounts SET goldinbank=goldinbank+".(int)$session[user][gold].", experience=experience+".round($session[user][experience]*getsetting("pvpdefgain",10)/100,0)." WHERE acctid=".(int)$badguy[acctid]."";
            db_query($sql);
            $sql = "SELECT * FROM depositbox WHERE acctid=".(int)$badguy[acctid]."";
            $result = db_query($sql) or die(db_error(LINK)); 
            if (db_num_rows($result)>0){
                $sql="UPDATE depositbox SET value = value+".(int)$session[user][gems]." WHERE acctid = ".(int)$badguy[acctid]."";
                db_query($sql);
            }else{
                $sql = "UPDATE accounts SET gems=gems+".(int)$session[user][gems]." WHERE acctid=".(int)$badguy[acctid]."";
                db_query($sql);
            }
            $session[user][alive]=false;
            debuglog("Hat {$session['user']['gold']} gold verloren, als er geschlagen wurde von ", $badguy['acctid']);
            debuglog("lost {$session['user']['gems']} gems verloren, als er geschlagen wurde von ", $badguy['acctid']);
            $session[user][gold]=0;
            $session[user][gems]=0;
            $session[user][hitpoints]=0;
            $session[user][experience]=round($session[user][experience]*(100-getsetting("pvpattlose",15))/100,0);
            $session[user][badguy]="";
            output("`b`&Du wurdest von `%$badguy[creaturename]`& geschlagen!!!`n");
            output("`4All Dein Gold und Deine Edelsteine, die Du bei Dir hattest, hast Du verloren!`n");
            output("`4".getsetting("pvpattlose",15)."% Deiner Erfahrung hast Du verloren!`n");
            output("Du darfst morgen wieder kämpfen.");
            
            page_footer();
        }else{
          fightnav(false,false);
        }
    }
}
page_footer();
?>


