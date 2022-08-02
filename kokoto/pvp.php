<?php

// 15082004
global $session;
require_once "common.php";
checkday();
$pvptime = getsetting("pvptimeout",600);
$pvptimeout = date("Y-m-d H:i:s",strtotime(date("c")."-$pvptime seconds"));
page_header("Spielerkampf!");
if ($_GET['op']=='' && $_GET['act']!='attack'){

      output("`4Du machst dich auf in die Felder, wo einige unwissende Krieger schlafen.`n`nDu hast noch `^".$session['user']['playerfights']."`4 PvP Kämpfe übrig für heute.");
    addnav("Krieger auflisten","pvp.php?op=list");
      addnav("Zurück zum Dorf","village.php");
}else if ($_GET['op']=='list'){
      $sql = "SELECT name,acctid,alive,location,sex,level,laston,loggedin,login,pvpflag FROM accounts WHERE
    (locked=0) AND
    (level >= ".($session['user']['level']1)." AND level <= ".($session['user']['level']2).") AND
    alive=1 AND
    location=0 AND
    (race>0 AND specialty>0) AND
    (dragonkills >= ".($session['user']['dragonkills']5).") AND
    (laston < '".date("Y-m-d H:i:s",strtotime(date("c")."-".getsetting("logintimeout",900)." sec"))."' OR loggedin=0) AND
    (acctid <> ".$session['user']['acctid'].")
    ORDER BY level DESC";
    //echo ("<pre>$sql</pre>");
      $result = db_query($sql);
    if ($session['user']['pvpflag']=="5013-10-06 00:42:00"){
        output("`n`&Du hast PvP-Immunität gekauft. Diese verfällt, wenn du jetzt angreifst!`0`n`n");
    }
    rawoutput("<table border='0' cellpadding='3' cellspacing='0'><tr><td>Name</td><td>Level</td><td>Ops</td></tr>");
    for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
				if ($session['user']['prefs']['biopop']==1){
		$biolink = "biopop.php?id=".$row['acctid']."' target=\"_blank\" onClick=\"".popup("biopop.php?id=".$row['acctid']."").";return false;\" ";
		}else{
          $biolink="bio.php?id=".$row['acctid']."&ret=".urlencode($_SERVER['REQUEST_URI']);
		  }
          allownav($biolink);
        if($row['pvpflag']>$pvptimeout){
              output("<tr class='".($i2?"trlight":"trdark")."'><td>".$row['name']."</td><td>".$row['level']."</td><td>[ <a href='$biolink'>Bio</a> | `iimmun`i ]</td></tr>",true);
        }else{
              output("<tr class='".($i2?"trlight":"trdark")."'><td>".$row['name']."</td><td>".$row['level']."</td><td>[ <a href='$biolink'>Bio</a> | <a href='pvp.php?act=attack&id=".$row['acctid']."'>Angriff</a> ]</td></tr>",true);
            allownav("pvp.php?act=attack&id=".$row['acctid']);
        }
    }
    rawoutput('</table>');
    addnav("Krieger auflisten","pvp.php?op=list");
      addnav("Zurück zum Dorf","village.php");
       if (getsetting("hasegg",0)>0){
        $sql = "SELECT name FROM accounts WHERE acctid = ".getsetting("hasegg",0);
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        output("`n`n".$row['name']." hat das `^Goldene Ei!");
      }
        if (getsetting("hasmagicstone",0)>0){
        $sql = "SELECT name FROM accounts WHERE acctid = ".getsetting("hasmagicstone",0);
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        output("`n`n".$row['name']." hat den `9 Magischen Stein!");
        }
        if (getsetting("hasamulett",0)>0){
        $sql = "SELECT name FROM accounts WHERE acctid = ".getsetting("hasamulett",0);
        $result = db_query($sql);

        $row = db_fetch_assoc($result);
        output("`n`n".$row['name']." hat das `VAmulett der Macht!");
        }
} else if ($_GET['act'] == "attack") {
      $sql = "SELECT name AS creaturename,guildID, guildrank,
     level AS creaturelevel,
     weapon AS creatureweapon,
     gold AS creaturegold,
     experience AS creatureexp,
     maxhitpoints AS creaturehealth,
     attack AS creatureattack,
     defence AS creaturedefense,
     bounty AS creaturebounty,
     loggedin,
     location,
     dragonkills,
     laston,
     alive,
     acctid,
     lastip,
     emailaddress,
     pvpflag,
     uniqueid
     FROM accounts
     WHERE acctid=\"".(int)$_GET['id']."\"";
    $result = db_query($sql);
    if (db_num_rows($result)>0){
        $row = db_fetch_assoc($result);
        if (abs($session['user']['level']$row['creaturelevel'])>2 && $row['location']!=2){
              output("`\$Fehler:`4 Dieser Spieler ist nicht in deinem Levelbereich!");
        }elseif ($row['pvpflag'] > $pvptimeout){
            output("`\$Uuuups:`4 Dieser Krieger ist gerade anderweitig ... beschäftigt. Du wirst etwas auf deine Chance warten müssen! ".$row['pvpflag']." : $pvptimeout");
        }elseif ($session['user']['dragonkills'] >($row['dragonkills']5) && $row['location']!=2){
            output("`\$Mööööp:`4 Dieser Gegner ist unter deiner Würde!");
        } elseif (ac_check($row)){
            output("`\$`bNicht schummeln!!`b Du darfst deinen eigenen Charakter nicht angreifen!");
        }else{
              if (strtotime($row['laston']) > strtotime(date("c")."-".getsetting("logintimeout",900)." sec") && $row['loggedin']){
//            
                  output("`\$Fehler:`4 Dieser Krieger ist inzwischen online.");
            }else{
                  if ((int)$row['location']!=0 && 0 && $row['location']!=2){
                      output("`\$ Fehler:`4 Dieser Krieger befindet sich nicht an einem Ort, wo du ihn angreifen kannst.");
                }else{
                      if((int)$row['alive']!=1){
                          output("`\$Fehler:`4 Dieser Krieger lebt nicht.");
                    }else{
                          if ($session['user']['playerfights']>0){
                            $sql = "UPDATE accounts SET pvpflag=now() WHERE acctid=".$row['acctid'];
                            db_query($sql);
                            $battle=true;
                            $row['pvp']=1;
                            $row['creatureexp'] = round($row['creatureexp'],0);
                            $row['playerstarthp'] = $session['user']['hitpoints'];
                            $session['user']['badguy']=createstring($row);
                            $session['user']['playerfights']--;
                            $session['user']['buffbackup']='';
                            if ($session['user']['pvpflag']=="5013-10-06 00:42:00"){
                                $session['user']['pvpflag']="1986-10-06 00:42:00";
                                output("`n`4`bDeine Immunität ist hiermit verfallen!`b`0`n");
                            }
                           
                            if ($session['user']['prefs']['nosounds']==0) rawoutput("<embed src=\"media/bigbong.wav\" width=10 height=10 autostart=true loop=false hidden=true volume=100>");
                        }else{
                              output("`4Du bist zu müde, um heute einen weiteren Kampf mit einem Krieger zu riskieren.");
                        }
                    }
                }
            }
        }
    }else{
          output("`\$Fehler:`4 Dieser Krieger wurde nicht gefunden. Darf ich fragen, wie du überhaupt hier her gekommen bist?");
    }
      if ($battle){

    }else{
          addnav("Zurück zum Dorf","village.php");
    }
}
if ($_GET['op']=="run"){
      output("Deine Ehre verbietet es dir wegzulaufen.");
    $_GET['op']="fight";
}
if ($_GET['skill']!=''){
      output("Deine Ehre verbietet es dir, deine besonderen Fähigkeiten einzusetzen.");
    $_GET['skill']='';
}
if ($_GET['op']=="fight" || $_GET['op']=="run"){
    $battle=true;
}
if ($battle){
      include("battle.php");
    if ($victory){
        $exp = round(getsetting("pvpattgain",10)$badguy['creatureexp']100,0);
        $expbonus = round(($exp  (1.1($badguy['creaturelevel']$session['user']['level'])))  $exp,0);
        output('`b`&'.$badguy['creaturelose'].'`0`b`n`b`$Du hast '.$badguy['creaturename'].' besiegt!`0`b`n`#Du erbeutest `^'.$badguy['creaturegold'].'`# Gold!`n');
        // Bounty Check - Darrell Morrone
        if ($badguy['creaturebounty']>0){
                output("`#Außerdem erhältst du das Kopfgeld in Höhe von `^".$badguy['creaturebounty']."`# Gold!`n");
            $session['user']['donation']+=1;
            $session['user']['reputation']+=2;
        }
            // End Bounty Check - Darrell Morrone
        if ($expbonus>0){
              output('`#*** Durch die hohe Schwierigkeit des Kampfes erhältst du zusätzlich `^'.$expbonus.'`# Erfahrungspunkte!`n');
            $session['user']['reputation']++;
        }else if ($expbonus<0){
              output('`#*** Weil dieser Kampf so leicht war, verlierst du `^'.abs($expbonus).'`# Erfahrungspunkte!`n');
            $session['user']['reputation']--;
        }
        output('Du bekommst insgesamt `^'.($exp$expbonus).'`# Erfahrungspunkte!`n`0');
        // start: xp-loss for killing lowdk players
        $xplossfactor = 0;
        $mindks = getsetting("pvpmindkxploss",10);
        $dksdiff = $session['user']['dragonkills']  $badguy['dragonkills'];
        if ($dksdiff>$mindks){
            $xplossfactor = 1  (($badguy['dragonkills']  3)  ($session['user']['dragonkills']));
            $session['user']['reputation']--;
            $loss = round(($exp$expbonus)  $xplossfactor);
            output("`#Davon werden dir `\$$loss `#Erfahrungspunkte abgezogen, weil dein Gegner $dksdiff Drachenkills weniger als du hat.");
        }
        // end: xp-loss for killing lowdk players
        $session['user']['gold']+=$badguy['creaturegold'];
        if ($badguy['creaturegold']) {
            debuglog("gained {$badguy['creaturegold']} gold for killing ", $badguy['acctid']);
        }
        // Add Bounty Gold - Darrell Morrone
        $session['user']['gold']+=$badguy['creaturebounty'];
        if ($badguy['creaturebounty']) {
            debuglog("gained {$badguy['creaturebounty']} gold bounty for killing ", $badguy['acctid']);
        }
        $session['user']['experience']+=($exp$expbonus$loss);
        if ($badguy['location']==1){
              addnews("`4".$session['user']['name']."`3 besiegt `4{$badguy['creaturename']}`3 brutal in einem Zimmer in der Kneipe!");
            $killedin="`6der Kneipe";
            $session['user']['reputation']-=2;
        } else if ($badguy['location']==2){
              addnews("`4".$session['user']['name']."`3 besiegt `4{$badguy['creaturename']}`3 bei einem Einbruch ins Haus!");
            $killedin="`6`2einem Haus";
            $session['user']['reputation']-=5;
        }else{
              addnews("`4".$session['user']['name']."`3 besiegt `4{$badguy['creaturename']}`3 in einem fairen Kampf in den Feldern.");
            $killedin="`@den Feldern";
            $session['user']['reputation']--;
        }
        // Add Bounty Kill to the News - Darrell Mororne
        if ($badguy['creaturebounty']>0){
                addnews("`4".$session['user']['name']."`3 verdient `4{$badguy['creaturebounty']} Gold`3 für den Kopf von `4{$badguy['creaturename']}`3!");
            $session['user']['reputation']++;
        }
        // Golden Egg - anpera
        if ($badguy['acctid']==getsetting("hasegg",0)){
            savesetting("hasegg",stripslashes($session['user']['acctid']));
            output("`n`^Du nimmst ".$badguy['creaturename']." `^das goldene Ei ab!`0`n");
            addnews("`^".$session['user']['name']."`^ nimmt {$badguy['creaturename']}`^ das goldene Ei ab!");
            $session['user']['reputation']+=2;

        }
                if ($badguy['acctid']==getsetting("hasmagicstone",0)){
         savesetting("hasmagicstone",stripslashes($session['user']['acctid']));
         output("`n`&Du nimmst ".$badguy['creaturename']." `9 Den Magischen Stein `&ab ab!`0`n");
         addnews("`$ ".$session['user']['name']."`$ nimmt {$badguy['creaturename']} den `9Magischen Stein `$ ab!");
      }
                // Amulett
      if ($badguy['acctid']==getsetting("hasamulett",0)){
         savesetting("hasamulett",stripslashes($session['user']['acctid']));
         output("`n`VDu nimmst ".$badguy['creaturename']." `Vdas Amulett der Macht ab ab!`0`n");
         addnews("`V".$session['user']['name']."`V nimmt {$badguy['creaturename']}`V das Amulett der Macht ab!");
      }
        $sql = "SELECT gold FROM accounts WHERE acctid='".(int)$badguy['acctid']."'";
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        $badguy[creaturegold]=((int)$row['gold']>(int)$badguy['creaturegold']?(int)$badguy['creaturegold']:(int)$row['gold']);

        // \/- Gunnar Kreitz
        $lostexp = round($badguy['creatureexp']getsetting("pvpdeflose",5)100,0);
        // start: xp-loss for killing lowdk players
        $lostexp -= round($lostexp$xplossfactor,0);
        // end: xp-loss for killing lowdk players
        $mailmessage = "`^".$session['user']['name']."`2 hat dich mit %p `^".$session['user']['weapon']."`2 in $killedin`2 angegriffen und gewonnen!"
                ." `n`n%o hatte anfangs `^".$badguy['playerstarthp']."`2 Lebenspunkte und kurz bevor du gestorben bist, hatte %o noch `^".$session['user']['hitpoints']."`2 Lebenspunkte übrig."
                ." `n`nDu hast `\$".(round(getsetting("pvpdeflose",5)$xplossfactorgetsetting("pvpdeflose",5)))."%`2 deiner Erfahrungspunkte (etwa $lostexp Punkte) und `^".$badguy['creaturegold']."`2 Gold verloren. Dein Angreifer kassierte ausserdem das Kopfgeld in Höhe von `^".$badguy[creaturebounty]." `2Gold ein."
                ." `n`nGlaubst du nicht auch, es ist Zeit dich zu rächen?";
        $mailmessage = str_replace_c("%p",($session['user']['sex']?"ihre(r/m)":"seine(r/m)"),$mailmessage);
        $mailmessage = str_replace_c("%o",($session['user']['sex']?"sie":"er"),$mailmessage);
        systemmail($badguy['acctid'],"`2Du wurdest in $killedin`2 umgebracht",$mailmessage);
        // /\- Gunnar Kreitz

        $sql = "UPDATE accounts SET alive=0, bounty=0, goldinbank=goldinbank-IF(gold<".$badguy['creaturegold'].",gold-".$badguy['creaturegold'].",0),gold=gold-".$badguy['creaturegold'].", experience=experience-$lostexp WHERE acctid=".(int)$badguy['acctid']."";
        db_query($sql);
        $_GET['op']='';
        if ($badguy['location']==1){
            addnav("Zurück zur Kneipe","inn.php");
        } else if ($badguy['location']==2){
            addnav("Zurück zum Wohnviertel","houses.php?op=einbruch");
        } else {
            addnav("Zurück zum Dorf","village.php");
        }
        // Guilds/Clans Code
                if ($session['user']['guildID']!=0) {
                    $MyGuild=&$session['guilds'][$session['user']['guildID']];
                    if (isset($MyGuild)) {
                        $sitepoints=1;
                        if ($badguy['guildID']!=0) {
                            $TheirGuild=$session['guilds'][$badguy['guildID']];
                            if ($MyGuild['ID']==$TheirGuild['ID']) {
                                // You have attacked somebody in your own guild!
                                output("`nDu hast jemanden aus Deiner Gilde angegriffen und getötet!!!`n
                                Deine Gilde wird über den Vorfall informiert!");
                                $who=(($MyGuild['HeadOfWar']!=0)?$MyGuild['HeadOfWar']:$MyGuild['GuildLeader']);
                                systemmail($who,"Gildenmitglied angegriffen und getötet!!",
                                    "Einer der Gildenmitglieder, ".$session['user']['name'].",
                                    hat das Mitglied ".$badguy['creaturename']." angegriffen und
                                    getötet!",0);
                                $sitepoints-=2;
                            }
                            if (isset($TheirGuild['Hitlist'][$session['user']['acctid']])) {
                            // But you were on their hitlist!
                                output("`&`nDu standest auf ihrer Hitlist, aber Du hast gut
                                gekämpft und gewonnen!`n
                                Herzlichen Glückwunsch!!");
                                $sitepoints+=1;
                            }
                        } else {
                            // They are not a member of a guild
                        }
                        if (isset($MyGuild['Hitlist'][$badguy['acctid']])) {
                            // They are on my guild hitlist
                            $sitepoints+=1;
                            output("`&`nDu hast erfolgreich gegen ein mächtiges Gildenmitglied
                            gekämpft, das auf der Hitlist stand!`n
                            Herzlichen Glückwunsch!!");
                            $hitlist=&$MyGuild['Hitlist']; // Remove them from the guild hitlist
                            unset($hitlist[$badguy['acctid']]);
                        }
                        $GuildFee = round((($MyGuild['PercentOfFightsEarned']['PvP']100)  $badguy['creaturegold']),0);
                        if ($GuildFee<=0) $GuildFee=(($session['user']['level']10)  ($MyGuild['PercentOfFightsEarned']['PvP']100)1);
                        output("`&`nDeine Gilde fordert ihren Anteil. Du zahlst `^".$GuildFee." Gold `&Tribut.");
                        output("`%`nDeine Gilde bekommt `&".$sitepoints." `%Punkte für diesen Kampf.");
                        $MyGuild['SitePoints']+=$sitepoints;
                        if ($session['user']['gold']<$GuildFee) {
                            $session['user']['goldinbank']+=($session['user']['gold']$GuildFee);
                            $session['user']['gold']=0;
                            output("`nDu zahlst einen Teil des Tributs direkt von der Bank!");
                        } else {
                            $session['user']['gold']-=$GuildFee;
                        }
                        $MyGuild['gold']+=$GuildFee;
                        update_guild_info($MyGuild);
                        addnews("Im Namen seiner Gilde metzelte ".$session['user']['name']." seinen
                        Gegner ".$badguy['creaturename']." im Kampf nieder - hart und fair!");
                    } else {
                        // Error
                        // Their guildID is set but the information cannot be retrieved
                        $debug=print_r($session['user']['guildID'],true);
                        debuglog("MyGuild isn't set: ".$debug);
                    }
                 } else {
                  // They don't belong to a guild
                 }
                //
        $badguy=array();
    }else{
        if($defeat){
            addnav("Tägliche News","news.php");
			$sql = "SELECT taunt FROM taunts ORDER BY rand(".e_rand().") LIMIT 1";
			$result = db_query($sql);
			$taunt = db_fetch_assoc($result);
			$taunt = stripslashes($taunt['taunt']);
			$taunt = str_replace_c("%s",($session['user']['sex']?"sie":"ihn"),$taunt);
			$taunt = str_replace_c("%o",($session['user']['sex']?"sie":"er"),$taunt);
			$taunt = str_replace_c("%p",($session['user']['sex']?"ihr":"sein"),$taunt);
			$taunt = str_replace_c("%x",($session['user']['weapon']),$taunt);
			$taunt = str_replace_c("%X",$badguy['creatureweapon'],$taunt);
			$taunt = str_replace_c("%W",$badguy['creaturename'],$taunt);
			$taunt = str_replace_c("%w",$session['user']['name'],$taunt);
            if ($badguy['location']==1){
                $killedin="`6der Kneipe";
            } else if ($badguy['location']==2){
                $killedin="`2einem Haus";
            }else{
                $killedin="`@den Feldern";
            }
            $badguy['acctid']=(int)$badguy['acctid'];
            $badguy['creaturegold']=(int)$badguy['creaturegold'];
            systemmail($badguy['acctid'],"`2Du warst in $killedin`2 erfolgreich! ","`^".$session['user']['name']."`2 hat dich in $killedin`2 angegriffen, aber du hast gewonnen!`n`nDafür hast du `^".round($session['user']['experience']getsetting("pvpdefgain",10)100,0)."`2 Erfahrungspunkte und `^".$session['user']['gold']."`2 Gold erhalten!");
            addnews("`%".$session['user']['name']."`5 wurde bei ".($session['user']['sex']?"ihrem":"seinem")."`5 Angriff auf`% ".$badguy['creaturename']." `5  in $killedin `5getötet.`n$taunt");
            // Guild Clans
                        if ($badguy['guildID']!=0) {
                            $TheirGuild=&$session['guilds'][$badguy['guildID']];
                            $GuildFee = round((($TheirGuild['PercentOfFightsEarned']['PvP']100)  $session['user']['gold']),0);

                            $sitepoints=1;
                            if ($session['user']['guildID']!=0) {
                                $MyGuild=&$session['guilds'][$session['user']['guildID']];
                                if ($MyGuild['ID']==$TheirGuild['ID']) {
                                    // You have attacked somebody in your own guild!
                                    output("`nDu hast jemanden aus Deiner Gilde angegriffen!!!");
                                    $who=(($MyGuild['HeadOfWar']!=0)?$MyGuild['HeadOfWar']:$MyGuild['GuildLeader']);
                                    systemmail($who,"Gildenmitglied angegriffen!!",
                                        "Einer der Gildenmitglieder, ".$session['user']['name'].",
                                        hat das Mitglied ".$badguy['creaturename']." angegriffen -
                                        aber nicht getötet.",0);
                                    $sitepoints-=2;
                                }
                            } else {
                                // We don't belong to a guild
                            }
                            if (isset($TheirGuild['Hitlist'][$session['user']['acctid']])) {
                                // But you were on their hitlist!
                                output("`&`nDu standest auf ihrer Hitlist - und Du wurdest besiegt.`nBoah!!");
                                $sitepoints+=1;
                            } else {
                             // You weren't on thier hitlist
                            }
                            $TheirGuild['SitePoints']+=$sitepoints;
                            $TheirGuild['gold']+=$GuildFee;
                            update_guild_info($TheirGuild);
                        } else {
                                // They are not a member of a guild
                                $GuildFee=0;
                        }
                        // End of Guild Clans change
            $sql = "UPDATE accounts SET gold=gold+".(int)$session['user']['gold'].", experience=experience+".round($session['user']['experience']getsetting("pvpdefgain",10)100,0)." WHERE acctid=".(int)$badguy['acctid']."";
            db_query($sql);
            $session['user']['alive']=false;
            debuglog("lost {$session['user']['gold']} gold being slain by ", $badguy['acctid']);
            $session['user']['gold']=0;
            $session['user']['hitpoints']=0;
            $session['user']['experience']=round($session['user']['experience'](100getsetting("pvpattlose",15))100,0);
            $session['user']['badguy']="";
            output("`b`&Du wurdest von `%".$badguy['creaturename']." `&besiegt!!!`n `4Alles Gold, das du bei dir hattest, hast du verloren!`n `4".getsetting("pvpattlose",15)."%  deiner Erfahrung ging verloren!`n Du kannst morgen wieder kämpfen.");
            $session['user']['reputation']--;
            page_footer();
        }else{
              fightnav(false,false);
        }
    }
}

page_footer();
?>