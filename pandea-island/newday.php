<?php
require_once "common.php";

/***************
 **  SETTINGS **
 ***************/
$turnsperday = getsetting("turns",20);
$maxinterest = ((float)getsetting("maxinterest",10)/100) + 1; //1.1;
$mininterest = ((float)getsetting("mininterest",1)/100) + 1; //1.1;
//$mininterest = 1.01;
$dailypvpfights = getsetting("pvpday",3);
$config = unserialize(gettexts('donationconfig'));

$oldpvps=$_GET['pvps'];
if ($_GET['resurrection']=="true") {
        $resline = "&resurrection=true";
} else if ($_GET['resurrection']=="egg") {
        $resline = "&resurrection=egg";
} else {
        $resline = "";
}

// $resline = $_GET['resurrection']=="true" ? "&resurrection=true" : "" ;
/******************
 ** End Settings **
 ******************/
if (count($session['dragonpoints']) <$session['user']['dragonkills']&&$_GET['dk']!=""){
        array_push($session['dragonpoints'],$_GET[dk]);
        switch($_GET['dk']){
        case "hp":
                $session['user']['maxhitpoints']+=5;
                break;
        case "at":
                $session['user']['attack']++;
                break;
        case "de":
                $session['user']['defence']++;
                break;
        }
}
if (count($session['dragonpoints'])<$session['user']['dragonkills'] && $_GET['dk']!="ignore"){
        if ($session['quest']=="2") $isquestteilnehmer=1;
        page_header("Drachenpunkte");
        addnav("Max Lebenspunkte +5","newday.php?dk=hp$resline");
        addnav("Waldkämpfe +1","newday.php?dk=ff$resline");
        addnav("Angriff + 1","newday.php?dk=at$resline");
        addnav("Verteidigung + 1","newday.php?dk=de$resline");
        //addnav("Ignore (Dragon Points are bugged atm)","newday.php?dk=ignore$resline");
        //output("`@Du hast noch `^".($session['user']['dragonkills']-count($session['dragonpoints']))."`@  Drachenpunkte übrig. Wie willst du sie einsetzen?`n`n");
        $free_dragonpoints = $session['user']['dragonkills'] - count($session['dragonpoints']);
        output("`@Du hast noch `^{$free_dragonpoints}`@  Drachenpunkt".( $free_dragonpoints != 1 ? 'e' : '' )." übrig. Wie willst du ".( $free_dragonpoints != 1 ? 'sie' : 'ihn' )." einsetzen?`n`n");
        output("Du bekommst 1 Drachenpunkt pro getötetem Drachen. Die Änderungen der Eigenschaften durch Drachenpunkte sind permanent.");

}else if (!$session['user']['race'] || $session['user']['race']=="Unbekannt"|| $session['user']['race']=="0")
{
     page_header("Ein wenig über deine Vorgeschichte");
     $sql = "SELECT * FROM race WHERE rid='{$_GET['setrace']}' LIMIT 1";
     $result = db_query($sql);
     $row = db_fetch_assoc($result);
     if ($_GET['setrace']!="")
     {
          $session['user']['race'] = ($row['name']);
          switch($_GET['setrace'])
          {
               case $row['rid']:
               output("{$row['story']}");
               $bonus = unserialize($row['bonus']);
               $session['user']['maxhitpoints']+=(int)$bonus['lp'];
               $session['user']['defence']+=(int)$bonus['def'];
               $session['user']['attack']+=(int)$bonus['atk'];
               break;
          }
          if ($session['user']['weaponvalue']<0)
          $session['user']['attack']+=$session['user']['weapondmg'];
          if ($session['user']['armorvalue']<0)
          $session['user']['defence']+=$session['user']['armordef'];
          addnav("Weiter","newday.php?continue=1$resline");
          if ($session['user']['dragonkills']==0 && $session['user']['level']==1)
          {
               addnews("`#{$session[user][name]} `#hat unsere Welt betreten. Willkommen!");
          }
     }
     else
     {
          if (!$session['user']['superuser'])
          $sql = "SELECT * FROM race WHERE dk<='{$session['user']['dragonkills']}' AND name!='Unbekannt' AND active=1 ORDER BY category,name,rid";   else
          $sql = "SELECT * FROM race WHERE dk<='{$session['user']['dragonkills']}' AND name!='Unbekannt' ORDER BY category,name,rid";
          $result = db_query($sql);
          $category = "";
          while ($row = db_fetch_assoc($result))
          {
               if ($category!=$row['category'])
               {
                    addnav($row['category']);
                    $category = $row['category'];
               }
               $link = "newday.php?setrace={$row['rid']}$resline";
               addnav("{$row['color']} {$row['name']}",$link);
               output("<a href=\"$link\">".$row['link']."</a>`n`n",true);
               addnav("",$link);
          }
     }
/*}else if ((int)$session['user']['race']['raceid']==$startrace['raceid']){
        page_header("Ein wenig über deine Vorgeschichte");
        if ($_GET['setrace']!=""){
                changerace((int)$_GET['setrace']);
                output($session['user']['race']['color'].$session['user']['race']['description']);
                // Vorzüge der neuen Rasse nennen
                foreach ($session['user']['race']['buffs'] AS $tihsbuff=>$buffval) {
                        switch ($tihsbuff) {
                                case 'attack':
                                        if ($buffval==0) continue;
                                        if ($buffval>0) {
                                                output('`n`^Du bekommst '.($buffval==1?'einen zusätzlichen Punkt':$buffval.' zusätzliche Punkte').' auf deinen Angriffswert!');
                                        }
                                        else {
                                                output('`n`^Dir werden '.-$buffval.($buffval==-1?' Punkt':' Punkte').' vom Angriffswert abgezogen!');
                                        }
                                        break;
                                case 'defence':
                                        if ($buffval==0) continue;
                                        if ($buffval>0) {
                                                output('`n`^Du bekommst '.($buffval==1?'einen zusätzlichen Punkt':$buffval.' zusätzliche Punkte').' auf deinen Verteidigungswert!');
                                        }
                                        else {
                                                output('`n`^Dir werden '.-$buffval.($buffval==-1?' Punkt':' Punkte').' vom Verteidigungswert abgezogen!');
                                        }
                                        break;
                                case 'maxhitpoints':
                                        if ($buffval==0) continue;
                                        output('`n`^Du startest mit '.(abs($buffval)==1?'einem permanenten Lebenspunkt':abs($buffval).' permanenten Lebenspunkten').($buffval>0?' mehr!':'weniger!'));
                                        break;
                                case 'goldfactor':
                                        if ($buffval==100) continue;
                                        if ($buffval==0) $word = 'kein';
                                        elseif ($buffval < 1) $word = 'weniger';
                                        else $word = 'mehr';
                                        output('`n`^Du bekommst '.$word.' Gold durch Waldkämpfe!');
                                        break;
                                case 'fight':
                                        if ($buffval==0) continue;
                                        if ($buffval>0) {
                                                output('`n`^Du hast jeden Tag '.($buffval==1?'einen zusätzlichen Waldkampf!':$buffval.' zusätzliche Waldkämpfe!'));
                                        }
                                        else {
                                                output('`n`^Du hast jeden Tag '.($buffval==-1?'einen Waldkampf':-$buffval.' Waldkämpfe').' weniger!');
                                        }
                                        break;
                                case 'absfight':
                                        if ($buffval<=0) continue;
                                        output('`n`^Du hast jeden Tag '.$buffval.' Waldkämpfe!');
                                        break;
                        }
                }
                if ($session['user']['race']['undeadbonus']!=0){
                   $udb=$session['user']['race']['undeadbonus'];
                   if ($udb<0){
                      $nudb=(-1)*$udb;
                      output("`n`^Du hast jeden Tag $nudb Seelenpunkte weniger!");
                   }else{
                      output("`n`^Du hast jeden Tag $udb Seelenpunkte mehr!");
                   }
                }
                if ($session['user']['race']['undeadfights']!=0){
                   $udf=$session['user']['race']['undeadfights'];
                   if ($udf<0){
                      $nudf=(-1)*$udf;
                      output("`n`^Du hast jeden Tag $nudf Grabkämpfe weniger!");
                   }else{
                      output("`n`^Du hast jeden Tag $udf Grabkämpfe mehr!");
                   }
                }

                addnav("Weiter","newday.php?continue=1$resline");
                if ($session[user][dragonkills]==0 && $session[user][level]==1) {
                        addnews("`#{$session[user][name]} `#hat unsere Welt betreten. Willkommen!");
                        savesetting("newplayer",addslashes($session[user][name]));
                }
        }else{
//                if(($session['user']['dragonkills']%10)==0 && $session['user']['level']<2 ){//made by aragon to not change the race
                        output("Wo bist du aufgewachsen?`n`n");

                        addnav("Wähle deine Rasse");
                        foreach ($races AS $tihsrace) {
                if ($tihsrace['raceid']==$startrace['raceid'] || $tihsrace['activated']==0) continue;
                if ($tihsrace['adminonly']==1 && $session['user']['superuser'] < 3) continue;
                if ($tihsrace['mindk'] > $session['user']['dragonkills']) continue;
                if ($tihsrace['maxdk']!=0 && $tihsrace['maxdk'] < $session['user']['dragonkills']) continue;
                if ( -(int)$tihsrace['buffs']['attack'] > ($session['user']['attack']-$session['user']['weapondmg']-(int)$session['user']['race']['buffs']['attack'])
                    || -(int)$tihsrace['buffs']['defence'] > ($session['user']['defence']-$session['user']['armordef']-(int)$session['user']['race']['buffs']['defence'])
                    || -(int)$tihsrace['buffs']['maxhitpoints'] > ($session['user']['maxhitpoints']-(int)$session['user']['race']['buffs']['maxhitpoints']-6)
                    ) continue;
                if ($tihsrace['rprace']==0){
                    output(str_replace("<a>","<a href='newday.php?setrace=$tihsrace[raceid]$resline'>",$tihsrace['grownup']).'`n`n',true);
                    addnav($tihsrace['color'].$tihsrace['title'].'`0',"newday.php?setrace=$tihsrace[raceid]$resline");
                }else{
                        if ($config[$tihsrace['title']]){
                                output(str_replace("<a>","<a href='newday.php?setrace=$tihsrace[raceid]$resline'>",$tihsrace['grownup']).'`n`n',true);
                            addnav($tihsrace['color'].$tihsrace['title'].'`0',"newday.php?setrace=$tihsrace[raceid]$resline");
                            }
                    }
                addnav('',"newday.php?setrace=$tihsrace[raceid]$resline");
                        }
                }
                else
                {
                output("`^Du bist im moment mit deiner Rasse sehr zufrieden und hast keine Bedürfnisse, sie zu ändern.`0`n");
                addnav("Weiter","newday.php?setrace=".$session[user][race][raceid]);
                }

        }
*/
/*}else if ((int)$session['user']['specialty']==0){
  if ($_GET['setspecialty']===NULL){
                addnav("","newday.php?setspecialty=1$resline");
                addnav("","newday.php?setspecialty=2$resline");
                addnav("","newday.php?setspecialty=3$resline");
                page_header("Ein wenig über deine Vorgeschichte");

                output("Du erinnerst dich, dass du als Kind:`n`n");
                output("<a href='newday.php?setspecialty=1$resline'>viele Kreaturen des Waldes getötet hast (`\$Dunkle Künste`0)</a>`n",true);
                output("<a href='newday.php?setspecialty=2$resline'>mit mystischen Kräften experimentiert hast (`%Mystische Kräfte`0)</a>`n",true);
                output("<a href='newday.php?setspecialty=3$resline'>von den Reichen gestohlen und es dir selbst gegeben hast (`^Diebeskunst`0)</a>`n",true);
                addnav("`\$Dunkle Künste","newday.php?setspecialty=1$resline");
                addnav("`%Mystische Kräfte","newday.php?setspecialty=2$resline");
                addnav("`^Diebeskünste","newday.php?setspecialty=3$resline");
  }else{
          addnav("Weiter","newday.php?continue=1$resline");
                switch($_GET['setspecialty']){
                  case 1:
                          page_header("Dunkle Künste");
                                output("`5Du erinnerst dich, dass du damit aufgewachsen bist, viele kleine Waldkreaturen zu töten, weil du davon überzeugt warst, sie haben sich gegen dich verschworen. ");
                                output("Deine Eltern haben dir einen idiotischen Zweig gekauft, weil sie besorgt darüber waren, dass du die Kreaturen des Waldes mit bloßen Händen töten musst. ");
                                output("Noch vor deinem Teenageralter hast du damit begonnen, finstere Rituale mit und an den Kreaturen durchzuführen, wobei du am Ende oft tagelang im Wald verschwunden bist. ");
                                output("Niemand außer dir wusste damals wirklich, was die Ursache für die seltsamen Geräusche aus dem Wald war...");
                                break;
                        case 2:
                          page_header("Mystische Kräfte");
                                output("`3Du hast schon als Kind gewusst, dass diese Welt mehr als das Physische bietet, woran du herumspielen konntest. ");
                                output("Du hast erkannt, dass du mit etwas Training deinen Geist selbst in eine Waffe verwandeln kannst. ");
                                output("Mit der Zeit hast du gelernt, die Gedanken kleiner Kreaturen zu kontrollieren und ihnen deinen Willen aufzuzwingen. ");
                                output("Du bist auch auf die mystische Kraft namens Mana gestossen, die du in die Form von Feuer, Wasser, Eis, Erde, Wind bringen und sogar als Waffe gegen deine Feinde einsetzen kannst.");
                                break;
                        case 3:
                          page_header("Diebeskünste");
                                output("`6Du hast schon sehr früh bemerkt, dass ein gewöhnlicher Rempler im Gedränge dir das Gold eines vom Glück bevorzugteren Menschen einbringen kann. ");
                                output("Außerdem hast du entdeckt, dass der Rücken deiner Feinde anfälliger gegen kleine Klingen ist, als deren Vorderseite gegen mächtige Waffen.");
                                break;
                }
                $session['user']['specialty']=$_GET['setspecialty'];
        }
*/
}else if ((int)$session['user']['skill']==0 || (int)$session['user']['specialty']==0){
/*
original-line: }else if ((int)$session['user']['skill']==0 && (int)$session['user']['specialty']==0){
didn't work, when specialty was reset to 0 -> inn.php only resettet that field, so -> only if A && B == 0, this part
    would run <- inn.php made A=1 B=0 <- so guess where's the problem ;-)
    replacement of AND to OR so it runs as it was wanted before
*/

  if ($_GET['setspecialty']===NULL){
     $sql2="SELECT * FROM skills WHERE activated='1'";
     $result2 = db_query($sql2) or die(db_error(LINK));
     $max = db_num_rows($result2);
     page_header("Ein wenig über deine Vorgeschichte");
     output("Du erinnerst dich, dass du als Kind:`n`n");
     for($i=0;$i<$max;$i++){
             $row2 = db_fetch_assoc($result2);
             $ending=$row2[id].$resline;
             addnav("","newday.php?setspecialty=$ending");
             output("<a href='newday.php?setspecialty=$ending'>$row2[shortdesc] ($row2[color] $row2[name]`0)</a>`n",true);
             addnav("$row2[color] $row2[name]","newday.php?setspecialty=$ending");
     }
  }else{
          addnav("Weiter","newday.php?continue=1$resline");
          $id=$_GET['setspecialty'];
          $sql3="SELECT * FROM skills WHERE id=$id";
          $result3 = db_query($sql3) or die(db_error(LINK));
          $row3 = db_fetch_assoc($result3);
          page_header($row3[name]);
          output($row3[description]);
          $session['user']['skill']=$_GET['setspecialty'];
          $session['user']['skilllevel']=1;
          $session['user']['specialty']=$id;
          $session['user']['thieveryuses']=0;
          $session['user']['magicuses']=0;
          $session['user']['darkartuses']=0;
  }
}else{
  if ($session['user']['jailtime'] > 0) {
                page_header("Am Pranger!");
                output("Du hängst am Pranger! Du hast noch ".$session['user']['jailtime']." Tage dort zu verweilen.");
                addnav("Zum Pranger","jail.php");
                $session['user']['lasthit'] = date("Y-m-d H:i:s");
        }else{
                page_header("Es ist ein neuer Tag!");
                $interestrate = e_rand($mininterest*100,$maxinterest*100)/(float)100;
                output("`c<font size='+1'>`b`#Es ist ein neuer Tag!`0`b</font>`c",true);
if (!$session['prefs']['nosounds']) output("<embed src=\"media/newday.wav\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);

                if ($session['user']['alive']!=true){
                        $session['user']['resurrections']++;
                        output("`@Du bist wiedererweckt worden! Dies ist der Tag deiner ".ordinal($session['user']['resurrections'])." Wiederauferstehung.`0`n");
                        $session['user']['alive']=true;
                }
                $session[user][age]++;
                $session[user][seenmaster]=0;
                output("Du öffnest deine Augen und stellst fest, dass dir ein neuer Tag geschenkt wurde. Die Sonne blinzelt dich an, an deinem `^".ordinal($session['user']['age'])."`0 Tag in diesem Land. ");
                output("Du fühlst dich frisch und bereit für die Welt!`n");
//                if ($session['user']['race']['buffs']['absfight'] > 0) $turnsperday = $session['user']['race']['buffs']['absfight'];
                output("`2Runden für den heutigen Tag: `^$turnsperday`n");


                if ($session[user][goldinbank]<0 && abs($session[user][goldinbank])<(int)getsetting("maxinbank",10000)){
                        output("`2Heutiger Zinssatz: `^".(($interestrate-1)*100)."% `n");
                        output("`2Zinsen für Schulden: `^".-(int)($session['user']['goldinbank']*($interestrate-1))."`2 Gold.`n");
                }else if ($session[user][goldinbank]<0 && abs($session[user][goldinbank])>=(int)getsetting("maxinbank",10000)){
                        output("`4Die Bank erlässt dir deine Zinsen, da du schon hoch genug verschuldet bist.`n");
                        $interestrate=1;
                }else if ($session[user][goldinbank]>=0 && $session[user][goldinbank]>=(int)getsetting("maxinbank",10000) && $session['user']['turns']<=getsetting("fightsforinterest",4)){
                        $interestrate=1;
                        output("`4Die Bank kann dir heute keinen Zinsen zahlen. Sie würde früher oder später an dir pleite gehen.`n");
                }else if ($session[user][goldinbank]>=0 && $session[user][goldinbank]<(int)getsetting("maxinbank",10000) && $session['user']['turns']<=getsetting("fightsforinterest",4)){
                        output("`2Heutiger Zinssatz: `^".(($interestrate-1)*100)."% `n");
                        output("`2Durch Zinsen verdientes Gold: `^".(int)($session['user']['goldinbank']*($interestrate-1))."`n");
                }else{
                        $interestrate=1;
                        output("`2Dein heutiger Zinssatz beträgt `^0% (Die Bank gibt nur den Leuten Zinsen, die dafür arbeiten)`n");
                }


/*
                if ($session['user']['turns']>getsetting("fightsforinterest",4) && $session['user']['goldinbank']>=0) {
                        $interestrate=1;
                        output("`2Today's interest rate: `^0% (Bankers in this village only give interest to those who work for it)`n");
                }else{
                        output("`2Today's interest rate: `^".(($interestrate-1)*100)."% `n");
                        if (abs($session['user']['goldinbank'])>(int)getsetting("maxinbank",10000)){
                                 if ($session['user']['goldinbank']>=0 ){
                                        output("`4Die Bank kann dir heute keinen Zinsen zahlen. Sie würde früher oder später an dir pleite gehen.`n");
                                }else{
                                        output("`4Die Bank erlässt dir deine Zinsen, da du schon hoch genug verschuldet bist.`n");
                                }
                                $interestrate=1;
                        }else if ($session['user']['goldinbank']>=0 ){
                                output("`2Gold earned from interest: `^".(int)($session['user']['goldinbank']*($interestrate-1))."`n");
                        }else{
                                output("`2Zinsen für Schulden: `^".-(int)($session['user']['goldinbank']*($interestrate-1))."`2 Gold.`n");
                        }
                }
*/
                output("`2Deine Gesundheit wurde wiederhergestellt auf `^".$session['user']['maxhitpoints']."`n");
                $sb = getsetting("specialtybonus",1);

                //ANGELMOD
                $session['user']['skillpoints'] = (int)($session['user']['skilllevel']/3) + $sb;

                //$skills = array(1=>"Dunkle Künste","Mystische Kräfte","Diebeskünste");
                //output("`2Für dein Spezialgebiet `&".$skills[$session['user']['specialty']]."`2, erhältst du zusätzlich $sb Anwendung(en) in `&".$skills[$session['user']['specialty']]."`2 für heute.`n");
               // $session['user']['darkartuses'] = (int)($session['user']['darkarts']/3) + ($session['user']['specialty']==1?$sb:0);
               // $session['user']['magicuses'] = (int)($session['user']['magic']/3) + ($session['user']['specialty']==2?$sb:0);
               // $session['user']['thieveryuses'] = (int)($session['user']['thievery']/3) + ($session['user']['specialty']==3?$sb:0);
                //$session['user']['bufflist']=array(); // with this here, buffs are always wiped, so the preserve stuff fails!
                if ($session['user']['marriedto']==4294967295 || $session['user']['charisma']==4294967295){
                        output("`n`%Du bist verheiratet, es gibt also keinen Grund mehr, das perfekte Image aufrecht zu halten. Du lässt dich heute ein bisschen gehen.`n Du verlierst einen Charmepunkt.`n");
                        $session['user']['charm']--;
                        if ($session['user']['charm']<=0){
                                output("`n`bAls du heute aufwachst, findest du folgende Notiz neben dir im Bett:`n`5".($session[user][sex]?"Liebste":"Liebster")."");
                                output("".$session['user']['name']."`5.");
                                output("`nTrotz vieler großartiger Küsse fühle ich mich einfach nicht mehr so zu dir hingezogen wie früher.`n`n");
                                output("Nenne mich wankelmütig, aber ich muss weiterziehen. Es gibt andere Krieger".($session[user][sex]?"innen":"")." in diesem Dorf und ich glaube, ");
                                output("einige davon sind wirklich heiß. Es liegt also nicht an dir, sondern an mir, usw. usw.");
                                  $sql = "SELECT acctid,name FROM accounts WHERE locked=0 AND acctid=".$session[user][marriedto]."";
                                  $result = db_query($sql) or die(db_error(LINK));
                                $row = db_fetch_assoc($result);
                                $partner=$row[name];
                                if ($partner=="") $partner = $session[user][sex]?"Seth":"Violet";
                                output("`n`nSei nicht traurig!`nIn Liebe, $partner`b`n");
                                addnews("`\$$partner `\$hat {$session['user']['name']}`\$ wegen \"anderer Interessen\" verlassen!");
                                if ($session['user']['marriedto']==4294967295) $session['user']['marriedto']=0;
                                if ($session['user']['charisma']==4294967295){
                                         $session['user']['charisma']=0;
                                        $session['user']['marriedto']=0;
                                        $sql = "UPDATE accounts SET charisma=0,marriedto=0 WHERE acctid='$row[acctid]'";
                                        db_query($sql);
                                        systemmail($row['acctid'],"`\$Wieder solo!`0","`6Du hast `&{$session['user']['name']}`6 verlassen. ".($session[user][sex]?"Sie":"Er")." war einfach widerlich in letzter Zeit.");
                                }
                        }
                }

                //clear all standard buffs
                $tempbuf = unserialize(gettexts('bufflist'));
                updatetexts('bufflist','');
                $session['bufflist']=array();
                while(list($key,$val)=@each($tempbuff)){
                        if ($val['survivenewday']==1){
                                $session['bufflist'][$key]=$val;
                                output("{$val['newdaymessage']}`n");
                        }
                }

                reset($session['dragonpoints']);
                $dkff=0;
                while(list($key,$val)=each($session['dragonpoints'])){
                        if ($val=="ff"){
                                $dkff++;
                        }
                }
                if ($session[user][hashorse]){
                        $session['bufflist']['mount']=unserialize($playermount['mountbuff']);
                }
                //if ($dkff>0 && $session['user']['race']['buffs']['nodragonwk']!=1) output("`n`2Du erhöhst deine Waldkämpfe um `^$dkff`2 durch verteilte Drachenpunkte!");
                $session['user']['turns']=$turnsperday+$dkff;
                $r1 = e_rand(-1,1);
                $r2 = e_rand(-1,1);
                $spirits = $r1+$r2;
                if ($_GET['resurrection']=="true"){
                        addnews("`&{$session['user']['name']}`& wurde von `\$Ramius`& wiedererweckt.");
                        $spirits=-round(0.3*$session['user']['turns'],0);
                        $session['user']['deathpower']-=100;
                        $session['user']['restorepage']="village.php?c=1";
                }
                if ($_GET['resurrection']=="egg"){
                        addnews("`&{$session['user']['name']}`& hat das `^goldene Ei`& benutzt und entkam so dem Schattenreich.");
                        $spirits=-6;
                        //$session['user']['deathpower']-=100;
                        $session['user']['restorepage']="village.php?c=1";
                        savesetting("hasegg",stripslashes(0));
                }

                $sp = array((-2)=>"sehr schlecht",(-1)=>"schlecht","0"=>"normal",1=>"gut",2=>"sehr gut");
                if (($_GET['resurrection']=="true") || ($_GET['resurrection']=="egg")) {
                        output("`n`2Du bist `^auferstanden`2!`n");
                }else {
                     if($session['user']['fixedmood']==0){
                         output("`n`2Dein Zustand und deine Stimmung sind heute `^".$sp[$spirits]."`2!`n");
                     }else{
                         $sp2 = array(1=>"sehr schlecht",2=>"schlecht",3=>"normal",4=>"gut",5=>"sehr gut");
                         $grinsekatze=$session[user][fixedmood];
                         output("`n`2Dein Zustand und deine Stimmung sind heute `^".$sp2[$grinsekatze]."`2!`n");
                         $spirits=$session[user][fixedmood]-3;
                     }
                }
                /*
                if ($session['user']['race']['buffs']['nospiritwk']!=1) {
                        if (abs($spirits)>0){
                                output("`2Deswegen `^");
                                if($spirits>0){
                                        output("bekommst du zusätzlich ");
                                }else{
                                        output("verlierst du ");
                                }
                                output(abs($spirits)." Runden`2 für heute.`n");
                        }
                }
                */
                $rp = $session['user']['restorepage'];
                $x = max(strrpos("&",$rp),strrpos("?",$rp));
                if ($x>0) $rp = substr($rp,0,$x);
                if (substr($rp,0,10)=="badnav.php"){
                        addnav("Weiter","news.php");
                }else{
                        addnav("Weiter",preg_replace("'[?&][c][=].+'","",$rp));
                }

                $session['user']['laston'] = date("Y-m-d H:i:s");
                $bgold = $session['user']['goldinbank'];
                $session['user']['goldinbank']*=$interestrate;
                $nbgold = $session['user']['goldinbank'] - $bgold;

                if ($nbgold != 0) {
                        //debuglog(($nbgold >= 0 ? "earned " : "paid ") . abs($nbgold) . " gold in interest");
                }
                $session['user']['turns']+=$spirits;
                if ($session[user][maxhitpoints]<6) $session[user][maxhitpoints]=6;
                $session['user']['hitpoints'] = $session[user][maxhitpoints];
                $session['user']['spirits'] = $spirits;
                if ($resline == "&resurrection=true"){
                        $session['user']['playerfights'] = $oldpvps;
                        output("`n`^Du hast `4$oldpvps `^deiner alten Spielerkämpfe übrig!`0`n");
                }else{
                        $session['user']['playerfights'] = $dailypvpfights;
                }
                $session['user']['transferredtoday'] = 0;
                $session['user']['putingems']=0;
                $session['user']['amountouttoday'] = 0;
                $session['user']['seendragon'] = 0;
                $session['user']['seenmaster'] = 0;
                $session['user']['seenlover'] = 0;

$session['user']['witch'] = 0;
                $session['user']['usedouthouse'] = 0;
//Cardhouse mod anfang
$session['user']['cardhouseallowed'] = 3;
//Cardhouse mod ende
$session['user']['seenAcademy'] = 0;
                $session['user']['gotfreeale'] = 0;
                $session['user']['fedmount'] = 0;
                $session['user']['boughtatcandy'] = 0;
                $session['user']['visitedkala'] = 0;
                if ($_GET['resurrection']!="true" && $_GET['resurrection']!="egg" ){
                        $session['user']['soulpoints']=50 + 5 * $session['user']['level'];
                        $session['user']['gravefights']=getsetting("gravefightsperday",10);
                        $session['user']['thefttoday']=0;
                }
                $session['user']['seenbard'] = 0;
                $session['user']['boughtroomtoday'] = 0;
                $session['user']['lottery'] = 0;
                $session['user']['recentcomments']=$session['user']['lasthit'];
                $session['user']['lasthit'] = date("Y-m-d H:i:s");
                if ($session['user']['drunkenness']>66){
                  output("`&Wegen deines schrecklichen Katers wird dir 1 Runde für heute abgezogen.");
                        $session['user']['turns']--;
                }
                if ($session['user']['shopban']!=0){
                        if ($session['user']['shopban']>0){
                                $session['user']['shopban']--;
                        }
                        else
                        {
                                $session['user']['shopban']=0;
                        }
                }


// following by talisman & JT
//Set global newdaysemaphore

       $lastnewdaysemaphore = convertgametime(strtotime(getsetting("newdaysemaphore","0000-00-00 00:00:00")));
       $gametoday = gametime();

        if (date("Ymd",$gametoday)!=date("Ymd",$lastnewdaysemaphore)){
            #$sql = "LOCK TABLES settings WRITE";
            #db_query($sql);

           $lastnewdaysemaphore = convertgametime(strtotime(getsetting("newdaysemaphore","0000-00-00 00:00:00")));

            $gametoday = gametime();
            if (date("Ymd",$gametoday)!=date("Ymd",$lastnewdaysemaphore)){
                //we need to run the hook, update the setting, and unlock.
                savesetting("newdaysemaphore",date("Y-m-d H:i:s"));
                $sql = "UNLOCK TABLES";
                db_query($sql);

                        require_once "setnewday.php";

            }else{
                //someone else beat us to it, unlock.
                $sql = "UNLOCK TABLES";
                db_query($sql);
                                output("Somebody beat us to it");
            }
        }

        output("`nDer Schmerz in deinen wetterfühligen Knochen sagt dir das heutige Wetter: `6".$settings['weather']."`@.`n");
        if ($_GET['resurrection']==""){
                if ($session['user']['specialty']==1 && $settings['weather']=="Regnerisch"){
                        output("`^`nDer Regen schlägt dir aufs Gemüt, aber erweitert deine Dunklen Künste. Du bekommst eine zusätzliche Anwendung.`n");
                        $session[user][darkartuses]++;
                        }
                if ($session['user']['specialty']==2 and $settings['weather']=="Gewittersturm"){
                        output("`^`nDie Blitze fördern deine Mystischen Kräfte. Du bekommst eine zusätzliche Anwendung.`n");
                        $session[user][magicuses]++;
                        }
                if ($session['user']['specialty']==3 and $settings['weather']=="Neblig"){
                        output("`^`nDer Nebel bietet Dieben einen zusätzlichen Vorteil. Du bekommst eine zusätzliche Anwendung.`n");
                        $session[user][thieveryuses]++;
                        }
        }
        /*angel edit*/
        if ($settings['weather']=="Schneesturm"){
                output("`^`nDie Kälte und Unbarmherzigkeit des Winters schlagen wieder einmal zu. Aufgrund der schlechten Sichtverhältnisse
                in diesem Schneegestöber verlierst du 4 Waldkämpfe");
                $session[user][turns]-=4;
        }
//End global newdaysemaphore code and weather mod.

                if ($session['user']['hashorse']){
                        //$horses=array(1=>"pony","gelding","stallion");
                        //output("`n`&You strap your `%".$session['user']['weapon']."`& to your ".$horses[$session['user']['hashorse']]."'s saddlebags and head out for some adventure.`0");
                        //output("`n`&Because you have a ".$horses[$session['user']['hashorse']].", you gain ".((int)$session['user']['hashorse'])." forest fights for today!`n`0");
                        //$session['user']['turns']+=((int)$session['user']['hashorse']);
                        output(str_replace("{weapon}",$session['user']['weapon'],"`n`&{$playermount['newday']}`n`0"));
 /*                       if ($playermount['mountforestfights']>0 && $session['user']['race']['buffs']['nohorsewk']!=1){
                                output("`n`&Weil du ein(e/n) {$playermount['mountname']} besitzt, bekommst du `^".((int)$playermount['mountforestfights'])."`& Runden zusätzlich.`n`0");
                                $session['user']['turns']+=(int)$playermount['mountforestfights'];
                        } */
                }else{
                        output("`n`&Du schnappst dir deine Waffe und ziehst los ins Abenteuer.`0");
               }
                /*
                if ($session['user']['race']==3) {
                        $session['user']['turns']++;
                        output("`n`&Weil du ein Mensch bist, bekommst du `^1`& Waldkampf zusätzlich!`n`0");
                }
                */
                /*
                if (isset($session['user']['race']['buffs']['fight']) && $session['user']['race']['buffs']['fight']!=0) {
                        $racefight = $session['user']['race']['buffs']['fight'];
                        if ($racefight>0) {
                                output('`n`&Als '.$session['user']['race']['title'].' bekommst du `^'.$racefight.'`& '.($racefight==1?'Waldkampf':'Waldkämpfe').' zusätzlich!`n`0');
                        }
                        else {
                                output('`n`&Als '.$session['user']['race']['title'].' verlierst du `^'.-$racefight.'`& '.($racefight==-1?'Waldkampf':'Waldkämpfe').'!`n`0');
                        }
                        $session['user']['turns'] += $racefight;
                }
                */
                //zusätzliche Waldkämpfe & Anwendungen für bestimmte Rassen:
                $sql = "SELECT * FROM race WHERE name='".$session['user']['race']."'";
                $result = db_query($sql);
                $row = db_fetch_assoc($result);
                $bonus = unserialize($row['bonus']);
                //$buff = unserialize($row['buff']);
                //if (is_array($buff))
                //$session['bufflist']['race'] = $buff;
                $session['user']['turns'] += $bonus['wk'];
                if ($bonus['wk']!=0)
                output("`2`nDa du ein(e) {$session[user][race]}`2 bist,".((int)$bonus['wk']>0 ? " bekommst du zusätzliche `^".(int)$bonus['wk']."`2 Waldkämpfe für heute.`n"
                : " verlierst du `^".(int)$bonus['wk']*(-1)."`2 Waldkämpfe für heute.`n")."");
                $session['user']['soulpoints'] += $bonus['ulp'];
                if ($bonus['ulp']!=0)
                output("`2`n".((int)$bonus['ulp']>0 ? "Ramius findet deine Rasse sympatisch und deshalb startest du in der Unterwelt mit `^".(int)$bonus['ulp']."`2 Seelenpunkten zusätzlich.`n"
                : "Ramius hat eine gewisse Abneigung gegen deine Rasse und deshalb startest du in der Unterwelt mit `^".((-1)*(int)$bonus['ulp'])."`2 Seelenpunkten weniger.`n")."");
                $session['user']['gravefights'] += $bonus['ufi'];
                if ($bonus['ufi']!=0)
                output("`2`n".((int)$bonus['ufi']>0 ? "Ramius findet deine Rasse sympatisch und deshalb kannst du in der Unterwelt `^".(int)$bonus['ufi']."`2 Runden länger kämpfen.`n"
                : "Ramius hat eine gewisse Abneigung gegen deine Rasse und deshalb musst du in der Unterwelt schon `^".((-1)*(int)$bonus['ufi'])."`2 Runden früher aufhören zu kämpfen.`n")."");
                //$session['user']['darkartuses'] +=((int)$bonus['da']);
                //$session['user']['magicuses'] +=((int)$bonus['mk']);
                //$session['user']['thieveryuses'] +=((int)$bonus['tv']);
                 // END
                $config = unserialize(gettexts('donationconfig'));
                if ($_GET['resurrection'] != "true") {
                        if (!is_array($config['forestfights'])) $config['forestfights']=array();
                        if ($session['user']['race']['buffs']!=1) {
                                reset($config['forestfights']);
                                while (list($key,$val)=each($config['forestfights'])){
                                        $config['forestfights'][$key]['left']--;
                                    if ($val['boughtmonth']=='1') $monat='Januar';
                           if ($val['boughtmonth']=='2') $monat='Februar';
                    if ($val['boughtmonth']=='3') $monat='März';
                           if ($val['boughtmonth']=='4') $monat='April';
                    if ($val['boughtmonth']=='5') $monat='Mai';
                    if ($val['boughtmonth']=='6') $monat='Juni';
                    if ($val['boughtmonth']=='7') $monat='Juli';
                    if ($val['boughtmonth']=='8') $monat='August';
                    if ($val['boughtmonth']=='9') $monat='September';
                    if ($val['boughtmonth']=='10') $monat='Oktober';
                    if ($val['boughtmonth']=='11') $monat='November';
                        if ($val['boughtmonth']=='12') $monat='Dezember';
                               output("`@Du bekommst eine Extrarunde für die Punkte vom `^{$val['boughtday']}. ".$monat."`@.");
                                        #output("`@Du bekommst eine Extrarunde für die Punkte vom `^{$val['bought']}`@.");
                                        $session['user']['turns']++;
                                        if ($val['left']>1){
                                                output(" Du hast `^".($val['left']-1)."`@ Tage von diesem Kauf übrig.`n");
                                        }else{
                                                unset($config['forestfights'][$key]);
                                                output(" Dieser Kauf ist damit abgelaufen.`n");
                                        }
                                }
                        }
                }
                if ($config['healer'] > 0) {
                        $config['healer']--;
                        if ($config['healer'] > 0) {
                                output("`n`@Golinda ist bereit, dich noch {$config['healer']} weitere Tage zu behandeln.");
                        } else {
                                output("`n`@Golinda wird dich nicht länger behandeln.");
                                unset($config['healer']);
                        }
                }
                updatetexts('donationconfig',serialize($config));
                if ($session['user']['hauntedby']>""){
                        output("`n`n`)Du wurdest von {$session['user']['hauntedby']}`) heimgesucht und verlierst eine Runde!");
                        $session['user']['turns']--;
                        $session['user']['hauntedby']="";
                }
                $session['user']['drunkenness']=0;
                $session['user']['bounties']=0;
                // Buffs from items
                $sql="SELECT * FROM items WHERE (class='Fluch' OR class='Geschenk') AND owner=".$session[user][acctid]." ORDER BY id";
                $result=db_query($sql);
                for ($i=0;$i<db_num_rows($result);$i++){
                          $row = db_fetch_assoc($result);
                        if (strlen($row[buff])>8){
                                $row[buff]=unserialize($row[buff]);
                                $session[bufflist][$row[buff][name]]=$row[buff];
                                if ($row['class']=='Fluch') output("`n`G$row[name]`G nagt an dir.");
                                if ($row['class']=='Geschenk') output("`n`1$row[name]`1: $row[description]");
                        }
                        if ($row[hvalue]>0){
                                $row[hvalue]--;
                                if ($row[hvalue]<=0){
                                        output(" Aber nur noch heute.");
                                }
                        }
                }
                if (db_num_rows($result)>0) {
                        db_query("DELETE FROM items WHERE (class='Fluch' OR class='Geschenk') AND owner=".$session[user][acctid]." AND hvalue <= 1");
                        db_query("UPDATE items SET hvalue=hvalue-1 WHERE (class='Fluch' OR class='Geschenk') AND owner=".$session[user][acctid]);
                }
        }
}
page_footer();
?> 