
<?
require_once "common.php";

$balance = getsetting("creaturebalance", 0.33);

// Handle updating any commentary that might be around.
addcommentary();

//savesetting("creaturebalance","0.33");
if ($HTTP_GET_VARS[op]=="darkhorse"){
    $HTTP_GET_VARS[op]="";
    $session[user][specialinc]="darkhorse.php";
}
$fight = false;
page_header("Der Wald");
if (maxlimit(5)==0 && $session[user][locate]!=5        // Wald
            && $session[user][locate]!=27    // Akwark
            && $session[user][locate]!=28    // Quintarra
            && $session[user][locate]!=29    // Thais
            && $session[user][locate]!=30    // Waldpark
            ){                // Maxuser für Wald ermitteln
    output("`c`b`4~ Wald ist voll ~`0`b`c`n`n
        `6Du betrittst den Wald und schon siehst du keine paar Meter von Dir entfernt schon den
            ersten anderen Bewohner von Düsterstein, der wie Du auf Beute wartet.
            Böse schaut er Dich an, weil Du ihm gerade ein Monster verjagt hast, welches
            auf Dich aufmerksam wurde.
            `nUm Ärger zu vermeinden entschließt du dich, erstmal wieder ins Dorf zurückzukehren
            und darauf zu warten, dass weniger Mitbewohner von Düsterstein im Wald sind. 
            Du weißt das Deine Zeit kommen wird.`0");
    addnav("Zurück","village.php");
}else{
if ($session[user][locate]!=5){
    $session[user][locate]=5;
    redirect("forest.php");
}
if ($session[user][superuser]>1 && $HTTP_GET_VARS[specialinc]!=""){
  $session[user][specialinc] = $HTTP_GET_VARS[specialinc];
}
$config = unserialize($session['user']['donationconfig']);
if ($config['goldmine']>0 && $HTTP_GET_VARS[specialinc]=="goldmine.php"){
    $session[user][specialinc] = $HTTP_GET_VARS[specialinc];
}
if ($config['castle']==100 && $HTTP_GET_VARS[specialinc]=="castle.php"){
    if ($session['user']['hashorse']>0 && $playermount['mountcategory']=='Pferde'){
        $session[user][specialinc] = $HTTP_GET_VARS[specialinc];
    }else{
        output("`bDu brauchst ein Reittier um zur Burg zu kommen`b`n`n");
    }
}
if ($session[user][race]==5 && $HTTP_GET_VARS[specialinc]=="castle.php"){
        $session[user][specialinc] = $HTTP_GET_VARS[specialinc];
}
if ($config['castle']==100 && $HTTP_GET_VARS[specialinc]=="drachental.php"){
    if ($session['user']['hashorse']>0 && $playermount['mountcategory']=='Kreaturen'){
        $session[user][specialinc] = $HTTP_GET_VARS[specialinc];
    }else{
        output("`bDu brauchst einen Drachen um ins Drachental zu kommen`b`n`n");
    }
}

$session['user']['donationconfig'] = serialize($config);

if ($session[user][specialinc]!=""){
      // block inventory
    $session['user']['blockinventory']=1; // Gargamel
  //echo "$x including special/".$session[user][specialinc];

    output("`^`c`bEtwas Besonderes!`c`b`0");
    $specialinc = $session[user][specialinc];
    $session[user][specialinc] = "";
    include("special/".$specialinc);
    if (!is_array($session['allowednavs']) || count($session['allowednavs'])==0) {
        forest(true);
        //output(serialize($session['allowednavs']));
    }
    page_footer();
    exit();
}
if ($HTTP_GET_VARS[op]=="run"){
    if (e_rand()%3 == 0){
        output ("`c`b`&Du bist erfolgreich vor deinem Gegner geflohen!`0`b`c`n");
        $HTTP_GET_VARS[op]="";
    }else{
        output("`c`b`\$Dir ist es nicht gelungen deinem Gegner zu entkommen!`0`b`c");
    }
}
if ($HTTP_GET_VARS[op]=="dragon"){
    addnav("Betrete die Höhle","dragon.php");
    addnav("Renne weg wie ein Baby","inn.php");
    output("`\$Du betrittst den dunklen Eingang einer Höhle in den Tiefen des Waldes, ");
    output(" im Umkreis von mehreren hundert Metern sind die Bäume bis zu den Stümpfen niedergebrannt.  ");
    output("Rauchschwaden steigen an der Decke des Höhleneinganges empor und werden plötzlich ");
    output("von einer kalten Windböe verweht.  Der Eingang der Höhle liegt an der Seite eines Felsens ");
    output("ein Dutzent Meter über dem Boden des Waldes, wobei Geröll eine kegelförmige ");
    output("Rampe zum Eingang bildet.  Stalaktiten und Stalagmiten nahe des Einganges ");
    output("erwecken in dir dein Eindruck, dass der Höhleneingang in Wirklichkeit ");
    output("das Maul einer riesigen Bestie ist.  ");
    output("`n`nAls du vorsichtig den Eingang der Höhle betrittst, hörst - oder besser fühlst du, ");
    output("ein lautes Rumpeln, das etwa dreißig Sekunden andauert, bevor es wieder verstummt ");
    output("Du bemerkst, dass dir ein Schwefelgeruch entgegenkommt.  Das Poltern ertönt erneut, und hört wieder auf, ");
    output("in einem regelmäßigen Rhythmus.  ");
    output("`n`nDu kletterst den Geröllhaufen rauf, der zum Eingang der Höhle führt. Deine Schritte zerbrechen ");
    output("die scheinbaren Überreste ehemaliger Helden.");
    output("`n`nJeder Instinkt in deinem Körper will fliehen und so schnell wie möglich zurück ins warme Wirtshaus und ");
    output(" ".($session[user][sex]?"zum noch wärmeren Seth":"zur noch wärmeren Violet").".  Was tust du?");
    $session[user][seendragon]=1;
}
if ($HTTP_GET_VARS[op]=="search"){
    checkday();
   //Free Daylock in settings
   $sql="UPDATE settings SET value='0' WHERE setting='daylock'";
   db_query($sql);

  if ($session[user][turns]<=0){
    output("`\$`bDu bist zu müde um heute den Wald weiter zu durchsuchen. Vielleicht hast du morgen mehr Energie dazu.`b`0");
    $HTTP_GET_VARS[op]="";
  }else{
      $session[user][drunkenness]=round($session[user][drunkenness]*.9,0);
      $specialtychance = e_rand()%7;
      //if ($specialtychance==0){
      if ($specialtychance==0 && $session['user']['stone'] != 20 ){
          output("`^`c`bEtwas Besonderes!`c`b`0");
            if ($handle = opendir("special")){
              $events = array();
              while (false !== ($file = readdir($handle))){
                  if (strpos($file,".php")>0){
                        // Skip the darkhorse if the horse knows the way
                      if ($session['user']['hashorse'] > 0 && 
                            $playermount['tavern'] > 0 &&
                          strpos($file, "darkhorse") !== false) {
                          continue;
                      }
                      array_push($events,$file);
                    }
                }
                $x = e_rand(0,count($events)-1);
                if (count($events)==0){
                  output("`b`@Arrr, dein Administrator hat entschieden, dass es dir nicht erlaubt ist, besondere Ereignisse zu haben.  Beschwer dich bei ihm, nicht bei mir.");
                }else{
                  $y = $HTTP_GET_VARS[op];
                    $HTTP_GET_VARS[op]="";
                  //echo "$x including special/".$events[$x];
                  include("special/".$events[$x]);
                    $HTTP_GET_VARS[op]=$y;
                }
            }else{
              output("`c`b`\$ERROR!!!`b`c`&Es ist nicht möglich die Speziellen Ereignisse zu öffnen! Bitte benachrichtige den Administrator!!");
            }
          if ($nav=="") forest(true);
      }else{
      $session[user][turns]--;
          $battle=true;
            if (e_rand(0,2)==1){
                $plev = (e_rand(1,5)==1?1:0);
                $nlev = (e_rand(1,3)==1?1:0);
            }else{
              $plev=0;
                $nlev=0;
            }
            if ($HTTP_GET_VARS['type']=="slum"){
              $nlev++;
                output("`\$Du steuerst den Abschnitt des Waldes an, von dem du weißt, dass sich dort Feinde aufhalten,  die dir ein bisschen angenehemer sind.`0`n");
            }
            if ($HTTP_GET_VARS['type']=="thrill"){
              //$plev+=3;
              $plev++;
                output("`\$Du steuerst den Abschnitt des Waldes an, in dem sich Kreaturen deiner schlimmsten Alpträume aufhalten, in der Hoffnung dass Du eine findest die verletzt ist.`0`n");
            }
            $targetlevel = ($session['user']['level'] + $plev - $nlev );
            if ($targetlevel<1) $targetlevel=1;
            $sql = "SELECT * FROM creatures WHERE creaturelevel = $targetlevel ORDER BY rand(".e_rand().") LIMIT 1";
            $result = db_query($sql) or die(db_error(LINK));
            $badguy = db_fetch_assoc($result);
            $expflux = round($badguy['creatureexp']/10,0);
            $expflux = e_rand(-$expflux,$expflux);
            $badguy['creatureexp']+=$expflux;

            //make badguys get harder as you advance in dragon kills.
            //output("`#Debug: badguy gets `%$dk`# dk points, `%+$atkflux`# attack, `%+$defflux`# defense, +`%$hpflux`# hitpoints.`n");
            $badguy['playerstarthp']=$session['user']['hitpoints'];
            $dk = 0;
            while(list($key, $val)=each($session[user][dragonpoints])) {
                if ($val=="at" || $val=="de") $dk++;
            }
            //$dk += (int)(($session['user']['maxhitpoints']-
            //    ($session['user']['level']*10))/5);
            $dk += (int)(($session['user']['maxhitpoints']-
                ($session['user']['level']*10))/4);
            if (!$beta) $dk = round($dk * 0.25, 0);
            else $dk = round($dk,0);

            $atkflux = e_rand(0, $dk);
            if ($beta) $atkflux = min($atkflux, round($dk/4));
            $defflux = e_rand(0, ($dk-$atkflux));
            if ($beta) $defflux = min($defflux, round($dk/4));
            $hpflux = ($dk - ($atkflux+$defflux)) * 5;
            if ($HTTP_GET_VARS['type']=="thrill"){
                //$atkflux = round($atkflux * 1.2,0);
                //$defflux = round($defflux * 1.2,0);
                $hpflux = ($dk - ($atkflux+$defflux)) * 8;
            }
            $badguy['creatureattack']+=$atkflux;
            $badguy['creaturedefense']+=$defflux;
            $badguy['creaturehealth']+=$hpflux;
            if ($beta) {
                $badguy['creaturedefense']*=0.66;
                $badguy['creaturegold']*=(1+(.05*$dk));
                if ($session['user']['race']==4) $badguy['creaturegold']*=1.1;
            } else {
                if ($session['user']['race']==4) $badguy['creaturegold']*=1.2;
            }
            $badguy['diddamage']=0;
            $session['user']['badguy']=createstring($badguy);
            if ($beta) {
                if ($session['user']['superuser']>=3){
                    output("Debug: $dk dragon points.`n");
                    output("Debug: +$atkflux attack.`n");
                    output("Debug: +$defflux defense.`n");
                    output("Debug: +$hpflux health.`n");
                } 
            }
        }
    }
}
if ($HTTP_GET_VARS[op]=="fight" || $HTTP_GET_VARS[op]=="run"){
    $battle=true;
}
if ($battle){
  include("battle.php");
//    output(serialize($badguy));
    if ($victory){
        if (getsetting("dropmingold",0)){
            $badguy[creaturegold]=e_rand($badguy[creaturegold]/4,3*$badguy[creaturegold]/4);
        }else{
            if ($session[user][hashorse] == 4){
                $badguy[creaturegold]=e_rand(0,$badguy[creaturegold]/1.5);
            }else{
                $badguy[creaturegold]=e_rand(0,$badguy[creaturegold]);
            }
        }
        $expbonus = round(
            ($badguy[creatureexp] *
                (1 + .25 *
                    ($badguy[creaturelevel]-$session[user][level])
                )
            ) - $badguy[creatureexp],0
        );
        output("`b`&$badguy[creaturelose]`0`b`n");
        output("`b`\$Du erledigst $badguy[creaturename]!`0`b`n");
        output("`#Du bekommst `^$badguy[creaturegold]`# Gold!`n");
        if ($badguy['creaturegold']) {
            //debuglog("received {$badguy['creaturegold']} gold for slaying a monster.");
            // Guilds/Clans Code
            if ($session['user']['guildID']!=0) {
                $MyGuild=&$session['guilds'][$session['user']['guildID']];
                if (isset($MyGuild)) {
                    $GuildFee = round((($MyGuild['PercentOfFightsEarned']['FF']/100) * $badguy['creaturegold']),0);
                    if ($GuildFee<=0) $GuildFee=(($session['user']['level']*10) * ($MyGuild['PercentOfFightsEarned']['FF']/100)+1);
            //$GuildFee = abs($GuildFee);
                    output("`3Deine Gilde fordert ihren Anteil. Du zahlst `^".$GuildFee." Gold `3Tribut.`n`n`#");
                    if ($session['user']['gold']<$GuildFee) {
                        $session['user']['goldinbank']+=($session['user']['gold']-$GuildFee);
                        $session['user']['gold']=0;
                        output("`nDu zahlst einen Teil des Tributs direkt von der Bank!");
                    } else {
                        $session['user']['gold']-=abs($GuildFee);
                    }
                    $MyGuild['gold']+=$GuildFee;
                    //update_guild_info($MyGuild);
             set_clanguild_var("addguildgold","".abs($GuildFee)."","0",$session[user][acctid],$session['user']['guildID'],"Wald Gold");
                } else {
                    // Error
                    // Their guildID is set but the information cannot be retrieved
                    $debug=print_r($session['user']['guildID'],true);
                    debuglog("MyGuild isn't set: ".$debug);
                }
            } elseif ($session['user']['clanID']!=0) {
                $MyClan=&$session['guilds'][$session['user']['clanID']];
                if (isset($MyClan)) {
                    $ClanFee = round((($MyClan['PercentOfFightsEarned']['FF']/100) * $badguy['creaturegold']),0);
                    if ($ClanFee<=0) $ClanFee=round((($session['user']['level']*10) * ($MyClan['PercentOfFightsEarned']['FF']/100)+1),0);
                    output("`3Dein Clan fordert seinen Anteil. Du zahlst `^".$ClanFee." Gold `3Tribut.`n`n`#");
                    if ($session['user']['gold']<$ClanFee) {
                        $session['user']['goldinbank']+=($session['user']['gold']-$ClanFee);
                        $session['user']['gold']=0;
                        output("`nDu zahlst einen Teil des Tributs direkt von der Bank!");
                    } else {
                        $session['user']['gold']-=abs($ClanFee);
                    }
                    $MyClan['gold']+=$ClanFee;
                    //update_guild_info($MyClan);
             set_clanguild_var("addclangold","".abs($ClanFee)."","0",$session[user][acctid],$session['user']['clanID'],"Wald Gold");
                } else {
                    // Error
                    // Their guildID is set but the information cannot be retrieved
                    $debug=print_r($session['user']['guildID'],true);
                    debuglog("MyClan isn't set: ".$debug);
                }
            } else {
                  // They don't belong to a clan
            }
            //
        }
        if (e_rand(1,25) == 1) {
          output("`&Du findest EINEN EDELSTEIN!`n`#");
          $session['user']['gems']++;
          debuglog("Einen Edelstein gefunden weil ein Monster getötet wurde");
        }
        if ($expbonus>0){
          output("`#*** Durch die hohe Schwierigkeit des Kampfes erhältst du zusätzlich `^$expbonus`# Erfahrungspunkte! `n($badguy[creatureexp] + ".abs($expbonus)." = ".($badguy[creatureexp]+$expbonus).") ");
        }else if ($expbonus<0){
          output("`#*** Weil dieser Kampf so leicht war, verlierst du `^".abs($expbonus)."`# Erfahrungspunkte! `n($badguy[creatureexp] - ".abs($expbonus)." = ".($badguy[creatureexp]+$expbonus).") ");
        }
        output("Du bekommst `^".($badguy[creatureexp]+$expbonus)."`# Erfahrungspunkte!`n`0");
        $session[user][gold]+=$badguy[creaturegold];
        $session[user][experience]+=($badguy[creatureexp]+$expbonus);
        $creaturelevel = $badguy[creaturelevel];
        $HTTP_GET_VARS[op]="";
        //if ($session[user][hitpoints] == $session[user][maxhitpoints]){
        if ($badguy['diddamage']!=1){
            if ($session[user][level]>=getsetting("lowslumlevel",4) || $session[user][level]<=$creaturelevel){
                output("`b`c`&~~ Perfekter Kampf! ~~`\$`n`bDu erhältst eine Extrarunde!`c`0`n");
                $session[user][turns]++;
            }else{
                output("`b`c`&~~ Perfekter Kampf! ~~`b`\$`nEin schwierigerer Kampf hätte dir eine extra Runde gebracht !`c`n`0");
            }
        }
        $dontdisplayforestmessage=true;
        addhistory(($badguy['playerstarthp']-$session['user']['hitpoints'])/max($session['user']['maxhitpoints'],$badguy['playerstarthp']));
        $badguy=array();
    }else{
        if($defeat){
            addnav("Daily news","news.php");
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
            addhistory(1);
            addnews("`%".$session[user][name]."`5 wurde im Wald von $badguy[creaturename] niedergemetzelt`n$taunt");
            $session[user][alive]=false;
            debuglog("lost {$session['user']['gold']} gold und {$session['user']['gems']} Edelsteine when they were slain in the forest");
            $session[user][gold]=0;
            $session[user][gems]=0;  // LordRaven
            $session[user][hitpoints]=0;
            $session[user][experience]=round($session[user][experience]*.9,0);
            $session[user][badguy]="";
            output("`b`&Du wurdest niedergemetzelt von `%$badguy[creaturename]`&!!!`n");
            output("`4Dein ganzes Gold wurde dir abgenommen!`n");
            output("`410% deiner Erfahrung hast du verloren!`n");
            output("Du kannst morgen weiter kämpfen.");
            
            page_footer();
        }else{
          fightnav();
        }
    }
}

if ($HTTP_GET_VARS[op]==""){
    // Need to pass the variable here so that we show the forest message
    // sometimes, but not others.
    forest($dontdisplayforestmessage);
}
}
page_footer();

function addhistory($value){
/*
    global $session,$balance;
    $history = unserialize($session['user']['history']);
    $historycount=50;
    for ($x=0;$x<$historycount;$x++){
        if (!isset($history[$x])) $history[$x]=$balance;
    }
    array_shift($history);
    array_push($history,$value);
    $history = array_values($history);
    for ($x=0;$x<$historycount;$x++){
        $history[$x] = round($history[$x],4);
        if ($session['user']['superuser']>=3) output("History: {$history[$x]}`n");
    }
    $session['user']['history']=serialize($history);
 */
}
?>


