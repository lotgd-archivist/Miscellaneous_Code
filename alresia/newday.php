<?php

// 24072004

require_once "common.php";
header('Content-Type: text/html; charset=utf-8');
/***************
 **  SETTINGS **
 ***************/
$turnsperday = getsetting("turns",10);
$maxinterest = ((float)getsetting("maxinterest",10)/100) + 1; //1.1;
$mininterest = ((float)getsetting("mininterest",1)/100) + 1; //1.1;
//$mininterest = 1.01;
$dailypvpfights = getsetting("pvpday",3);

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
if (count($session['user']['dragonpoints']) <$session['user']['dragonkills']&&$_GET['dk']!=""){
    array_push($session['user']['dragonpoints'],$_GET[dk]);
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
if (count($session['user']['dragonpoints'])<$session['user']['dragonkills'] && $_GET['dk']!="ignore"){
    page_header("Drachenpunkte");
    addnav("Max Lebenspunkte +5","newday.php?dk=hp$resline");
    addnav("Waldkämpfe +1","newday.php?dk=ff$resline");
    addnav("Angriff + 1","newday.php?dk=at$resline");
    addnav("Verteidigung + 1","newday.php?dk=de$resline");
    //addnav("Ignore (Dragon Points are bugged atm)","newday.php?dk=ignore$resline");
    output("`@Du hast noch `k".($session['user']['dragonkills']-count($session['user']['dragonpoints']))."`@  Drachenpunkte übrig. Wie willst du sie einsetzen?`n`n");
    output("Du bekommst 1 Drachenpunkt pro getötetem Drachen. Die Änderungen der Eigenschaften durch Drachenpunkte sind permanent.");
}else if (!$session['user']['race'] || $session['user']['race']=="Unbekannt"|| $session['user']['race']=="0") 
{ 
     page_header("Ein wenig über deine Vorgeschichte"); 
     $sql = "SELECT * FROM race WHERE raceid='{$_GET['setrace']}' LIMIT 1"; 
     $result = db_query($sql); 
     $row = db_fetch_assoc($result); 
     if ($_GET['setrace']!="") 
     { 
          $session['user']['race'] = ($row['color'].$row['name']); 
          switch($_GET['setrace']) 
          { 
               case $row['raceid']: 
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
{ 
$sql = "SELECT * FROM race WHERE dk<='{$session['user']['dragonkills']}' AND `sex` LIKE {$session['user']['sex']} ORDER BY category,name,raceid"; 
} 
else 
{ 
$sql = "SELECT * FROM race WHERE dk<='{$session['user']['dragonkills']}' ORDER BY category,name,raceid"; 
} 
$result = db_query($sql); 
$category = "";
          while ($row = db_fetch_assoc($result)) 
            { 
               if ($category!=$row['category']) 
               { 
                    addnav($row['category']); 
                    $category = $row['category']; 
               } 
               $link = "newday.php?setrace={$row['raceid']}$resline"; 
               addnav("{$row['color']} {$row['name']}",$link); 
               output("<a href=\"$link\">".$row['link']."</a>`n`n",true); 
               addnav("",$link); 
          } 
     }



}else if ((int)$session['user']['specialty']==0){
  if ($HTTP_GET_VARS['setspecialty']===NULL){
        addnav("","newday.php?setspecialty=1$resline");
        addnav("","newday.php?setspecialty=2$resline");
        addnav("","newday.php?setspecialty=3$resline");
        page_header("Ein wenig über deine Vorgeschichte");
        
        output("Du erinnerst dich, dass du als Kind:`n`n");
        output("<a href='newday.php?setspecialty=1$resline'>viele Kreaturen des Waldes getötet hast (`\$Dunkle Künste`0)</a>`n",true);
        output("<a href='newday.php?setspecialty=2$resline'>mit mystischen Kräften experimentiert hast (`%Mystische Kräfte`0)</a>`n",true);
        output("<a href='newday.php?setspecialty=3$resline'>von den Reichen gestohlen und es dir selbst gegeben hast (`kDiebeskunst`0)</a>`n",true);
        addnav("`\$Dunkle Künste","newday.php?setspecialty=1$resline");
        addnav("`%Mystische Kräfte","newday.php?setspecialty=2$resline");
        addnav("`kDiebeskünste","newday.php?setspecialty=3$resline");
  }else{
      addnav("Weiter","newday.php?continue=1$resline");
        switch($HTTP_GET_VARS['setspecialty']){
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
        $session['user']['specialty']=$HTTP_GET_VARS['setspecialty'];
    }
}else{
  if ($session['user']['slainby']!=""){
        page_header("Du wurdest umgebracht!");
        output("`\$Im ".$session['user']['killedin']." hat dich `%".$session['user']['slainby']."`\$ getötet und dein Gold genommen. Ausserdem hast du 5% deiner Erfahrungspunkte verloren. Meinst du nicht auch, es ist Zeit für Rache?");
        addnav("Weiter","newday.php?continue=1$resline");
      $session['user']['slainby']="";
    }else{
        page_header("Es ist ein neuer Tag!");
        $interestrate = e_rand($mininterest*100,$maxinterest*100)/(float)100;
        output("`c<font size='+1'>`b`#Es ist ein neuer Tag!`0`b</font>`c",true);
if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/newday.wav\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);

        if ($session['user']['alive']!=true){
            $session['user']['resurrections']++;
            output("`@Du bist wiedererweckt worden! Dies ist der Tag deiner ".ordinal($session['user']['resurrections'])." Wiederauferstehung.`0`n");
            $session['user']['alive']=true;
        }
        $session[user][age]++;
        $session[user][seenmaster]=0;
        output("Du öffnest deine Augen und stellst fest, dass dir ein neuer Tag geschenkt wurde. Dies ist dein `k".ordinal($session['user']['age'])."`0 Tag in diesem Land. ");
        output("Du fühlst dich frisch und bereit für die Welt!`n");
        output("`]Runden für den heutigen Tag: `k$turnsperday`n");


        if ($session[user][goldinbank]<0 && abs($session[user][goldinbank])<(int)getsetting("maxinbank",10000)){
            output("`]Heutiger Zinssatz: `k".(($interestrate-1)*100)."% `n");
            output("`]Zinsen für Schulden: `k".-(int)($session['user']['goldinbank']*($interestrate-1))."`] Gold.`n");
        }else if ($session[user][goldinbank]<0 && abs($session[user][goldinbank])>=(int)getsetting("maxinbank",10000)){
        output("`]Die Bank erlässt dir deine Zinsen, da du schon hoch genug verschuldet bist.`n");
            $interestrate=1;
        }else if ($session[user][goldinbank]>=0 && $session[user][goldinbank]>=(int)getsetting("maxinbank",10000) && $session['user']['turns']<=getsetting("fightsforinterest",4)){
            $interestrate=1;
            output("`]Die Bank kann dir heute keinen Zinsen zahlen. Sie würde früher oder später an dir pleite gehen.`n");
        }else if ($session[user][goldinbank]>=0 && $session[user][goldinbank]<(int)getsetting("maxinbank",10000) && $session['user']['turns']<=getsetting("fightsforinterest",4)){
            output("`]Heutiger Zinssatz: `k".(($interestrate-1)*100)."% `n");
            output("`]Durch Zinsen verdientes Gold: `k".(int)($session['user']['goldinbank']*($interestrate-1))."`n");
        }else{
            $interestrate=1;
            output("`]Dein heutiger Zinssatz beträgt `k0% (Die Bank gibt nur den Leuten Zinsen, die dafür arbeiten)`n");
        }


/*
        if ($session['user']['turns']>getsetting("fightsforinterest",4) && $session['user']['goldinbank']>=0) {
            $interestrate=1;
            output("`]Today's interest rate: `k0% (Bankers in this village only give interest to those who work for it)`n");
        }else{
            output("`]Today's interest rate: `k".(($interestrate-1)*100)."% `n");
            if (abs($session['user']['goldinbank'])>(int)getsetting("maxinbank",10000)){
                 if ($session['user']['goldinbank']>=0 ){
                    output("`4Die Bank kann dir heute keinen Zinsen zahlen. Sie würde früher oder später an dir pleite gehen.`n");
                }else{
                    output("`4Die Bank erlässt dir deine Zinsen, da du schon hoch genug verschuldet bist.`n");
                }
                $interestrate=1;
            }else if ($session['user']['goldinbank']>=0 ){
                output("`]Gold earned from interest: `k".(int)($session['user']['goldinbank']*($interestrate-1))."`n");
            }else{
                output("`]Zinsen für Schulden: `k".-(int)($session['user']['goldinbank']*($interestrate-1))."`] Gold.`n");
            }
        }
*/
        output("`]Deine Gesundheit wurde wiederhergestellt auf `k".$session['user']['maxhitpoints']."`n");
        $skills = array(1=>"Dunkle Künste","Mystische Kräfte","Diebeskünste");
        $sb = getsetting("specialtybonus",1);
        output("`]Für dein Spezialgebiet `&".$skills[$session['user']['specialty']]."`], erhältst du zusätzlich $sb Anwendung(en) in `&".$skills[$session['user']['specialty']]."`] für heute.`n");
        $session['user']['darkartuses'] = (int)($session['user']['darkarts']/3) + ($session['user']['specialty']==1?$sb:0);
        $session['user']['magicuses'] = (int)($session['user']['magic']/3) + ($session['user']['specialty']==2?$sb:0);
        $session['user']['thieveryuses'] = (int)($session['user']['thievery']/3) + ($session['user']['specialty']==3?$sb:0);
        //$session['user']['bufflist']=array(); // with this here, buffs are always wiped, so the preserve stuff fails!
        if ($session['user']['marriedto']==4294967295 || $session['user']['charisma']==4294967295){
            output("`n`uDu bist verheiratet, es gibt also keinen Grund mehr, das perfekte Image aufrecht zu halten. Du lässt dich heute ein bisschen gehen.`n Du verlierst einen Charmepunkt.`n");
            $session['user']['charm']--;
            if ($session['user']['charm']<=0){
                output("`n`bAls du heute aufwachst, findest du folgende Notiz neben dir im Bett:`n`5".($session[user][sex]?"Liebste":"Liebster")."");
                output("".$session['user']['name']."`5.");
                output("`nTrotz vieler großartiger Küsse, fühle ich mich einfach nicht mehr so zu dir hingezogen wie es früher war.`n`n");
                output("Nenne mich wankelmütig, aber ich muss weiterziehen. Es gibt andere Krieger".($session[user][sex]?"innen":"")." in diesem Dorf und ich glaube, ");
                output("einige davon sind wirklich heiss. Es liegt also nicht an dir, sondern an mir, usw. usw.");
                  $sql = "SELECT acctid,name FROM accounts WHERE locked=0 AND acctid=".$session[user][marriedto]."";
                  $result = db_query($sql) or die(db_error(LINK));
                $row = db_fetch_assoc($result);
                $partner=$row[name];
                if ($partner=="") $partner = $session[user][sex]?"Steve":"Wanda";
                output("`n`nSei nicht traurig!`nIn Liebe, $partner`b`n");
                addnews("`\$$partner `\$hat {$session['user']['name']}`\$ für \"andere Interessen\" verlassen!");
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
        $tempbuf = unserialize($session['user']['bufflist']);
        $session['user']['bufflist']="";
        $session['bufflist']=array();
        while(list($key,$val)=@each($tempbuff)){
            if ($val['survivenewday']==1){
                $session['bufflist'][$key]=$val;
                output("{$val['newdaymessage']}`n");
            }
        }

        
        if($session['user']['sex'] == 1)
            $session['user']['ssempf'] = e_rand()%9;
        if($row[ssstatus] == 1 && $row[ssmonat] <= 16)
        {
            output("Da deine Frau schwanger ist, bist Du ein wenig aufgeregt... gut Du bist sehr aufgeregt`n");
            $session[bufflist]['schwanger'] = array("name"=>"`&Deine Frau ist schwanger","rounds"=>1000000,"wearoff"=>"Irgendwas stimmt nicht mehr.","defmod"=>0.2,"roundmsg"=>"`9Du bist abgelenkt an den Gedanken das Du bald Vater wirst.","activate"=>"offense");
        }

            
        if($session[user][ssstatus] == 1)
        {
            $session[user][ssmonat]--;
            if($session['user']['ssmonat'] <= 16)
            {
                if($session[user][ssmonat] > 0)
                {
                    output("Du bist schwanger... Also pass auf dich auf`n");
                    $session['bufflist']['schwanger'] = array("name"=>"`&Schwangerschaft","rounds"=>1000000,"wearoff"=>"Irgendwas stimmt nicht mehr.","defmod"=>0,"roundmsg"=>"`9Du versucht deinen Bauch zu schützen und nimmst so jeden anderen Treffer in kauf.","activate"=>"offense");
                    if($session[user][superuser] >= 2)
                        output("Noch " . $session[user][ssmonat] . " Tage");
                }
                else
                {
                    $zwilling = e_rand()%25;
                    if($zwilling == 1)
                    {
                        $session[user][ssstatus] = 0;
                        $geschlechta = e_rand()%2;
                        $geschlechtb = e_rand()%2;
                        output("`&Du bist bist heute Mutter geworden... Es sind Zwillinge! Vergiss nicht die neuen Erdenbürger in der Kappelle zu taufen, sonst wird niemals jemand wissen das es ihn gibt und das wäre doch traurig!`n");
                        
                        if($geschlechta == $geschlechtb && $geschlechtb == 1)
                            $t = "Es sind zwei Mädchen!`n";
                        else if($geschlechta == $geschlechtb && $geschlechtb == 0)
                            $t = "Es sind zwei Jungs!`n";
                        else
                            $t = "Es ist ein Mädchen und ein Junge!`n";
    
                        output($t);
                        
                        systemmail($session[user][marriedto],"`%Du bist Vater!`0","`&Deine Frau {$session['user']['name']}`6 hat heute ein zwei wunderschöne Babies zur Welt gebracht, vergesst nicht sie in der Kapelle zu taufen. " . $t);
                        systemmail($session[user][acctid],"`%Du bist Mutter!`0","`&Du`6 hast heute zwei wunderschöne Babies zur Welt gebracht, vergesst nicht sie in der Kapelle zu taufen. " . $t);
                        addnews($session[user][name] . " & " . $row[name] . " sind heute Eltern geworden.");
                        if($session[user][sserzeug] != $session[user][marriedto])
                            $unehelich = 1;
                        else
                            $unehelich = 0;
                        $sqlkind = "INSERT INTO kinder VALUES ('', '" . $session[user][acctid] .  "', '" . $session[user][sserzeug] .  "', '', '" . $geschlechta . "', '" . getgamedate() . "', $unehelich, '');";
                        db_query($sqlkind) or die(db_error(LINK));
                        $sqlkind = "INSERT INTO kinder VALUES ('', '" . $session[user][acctid] .  "', '" . $session[user][sserzeug] .  "', '', '" . $geschlechtb . "', '" . getgamedate() . "', $unehelich, '');";
                        db_query($sqlkind) or die(db_error(LINK));
                    }
                    else
                    {
                        $session[user][ssstatus] = 0;
                        $geschlecht = e_rand()%2;
                        output("`&Du bist bist heute Mutter geworden... Vergiss nicht den neuen Erdenbürger in der Kappelle zu taufen, sonst wird niemals jemand wissen das es ihn gibt und das wäre doch traurig!`n");
                        
                        if($geschlecht == 1)
                            $t = "Es ist ein Mädchen!";
                        else
                            $t = "Es ist ein Junge!";
                            
                        output($t);
                        
                        systemmail($session[user][marriedto],"`%Du bist Vater!`0","`&Deine Frau {$session['user']['name']}`6 hat heute ein wunderschönes Baby zur Welt gebracht, vergesst nicht es in der Kapelle zu taufen. " . $t);
                        systemmail($session[user][acctid],"`%Du bist Mutter!`0","`&Du`6 hast heute ein wunderschönes Baby zur Welt gebracht, vergesst nicht es in der Kapelle zu taufen. " . $t);
                        addnews($session[user][name] . " & " . $row[name] . " sind heute Eltern geworden.");
                        if($session[user][sserzeug] != $session[user][marriedto])
                            $unehelich = 1;
                        else
                            $unehelich = 0;
                        $sqlkind = "INSERT INTO kinder VALUES ('', '" . $session[user][acctid] .  "', '" . $session[user][sserzeug] .  "', '', '" . $geschlecht . "', '" . getgamedate() . "', $unehelich, '');";
                        db_query($sqlkind) or die(db_error(LINK));
                    }
                    // KIND BEKOMMEN
                }
            }
        }
        
        $session[user][sexheute] = 0;

        if($session[user][sexgoettlich] > 0)
        {
            $session[user][sexgoettlich]--;
            output("`&Du errinerst dich an die schönen Stunden die Du mit einem Gott verbracht hast`n");
            $session['bufflist']['goettlichersex'] = array("name"=>"`%Göttliches Andenken","rounds"=>$session[user][sexgoettlich],"wearoff"=>"Die Errinerung verfliegt für heute!","atkmod"=>1.75,"roundmsg"=>"Du denkst immer noch an den göttlich intimen Stunden...","activate"=>"offense");
        }

        
        reset($session['user']['dragonpoints']);
        $dkff=0;
        while(list($key,$val)=each($session['user']['dragonpoints'])){
            if ($val=="ff"){
                $dkff++;
            }
        }
        if ($session[user][hashorse]){
            $session['bufflist']['mount']=unserialize($playermount['mountbuff']);
        }
        if ($dkff>0) output("`n`]Du erhöhst deine Waldkämpfe um `k$dkff`] durch verteilte Drachenpunkte!"); 
        $r1 = e_rand(-1,1);
        $r2 = e_rand(-1,1);
        $spirits = $r1+$r2;
        if ($_GET['resurrection']=="true"){
            addnews("`&{$session['user']['name']}`& wurde von `\$Niva`& wiedererweckt.");
            $spirits=-6;
            $session['user']['deathpower']-=100;
            $session['user']['restorepage']="village.php?c=1";
        }
        if ($_GET['resurrection']=="egg"){
            addnews("`&{$session['user']['name']}`& hat das `kgoldene Ei`& benutzt und entkam so dem Schattenreich.");
            $spirits=-6;
            //$session['user']['deathpower']-=100;
            $session['user']['restorepage']="village.php?c=1";
            savesetting("hasegg",stripslashes(0));
        }
        $sp = array((-6)=>"Auferstanden",(-2)=>"Sehr schlecht",(-1)=>"Schlecht","0"=>"Normal",1=>"Gut",2=>"Sehr gut");
        output("`n`]Dein Geist und deine Stimmung ist heute `k".$sp[$spirits]."`]!`n");
        if (abs($spirits)>0){
            output("`]Deswegen `k");
            if($spirits>0){
                output("bekommst du zusätzlich ");
            }else{
                output("verlierst du ");
            }
            output(abs($spirits)." Runden`] für heute.`n");
        }
        $rp = $session['user']['restorepage'];
        $x = max(mb_strrpos("&",$rp),mb_strrpos("?",$rp));
        if ($x>0) $rp = mb_substr($rp,0,$x);
        if (mb_substr($rp,0,10)=="badnav.php"){
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
        $session['user']['turns']=$turnsperday+$spirits+$dkff;
        if ($session[user][maxhitpoints]<6) $session[user][maxhitpoints]=6;
        $session['user']['hitpoints'] = $session[user][maxhitpoints];
        $session['user']['spirits'] = $spirits;
        $session['user']['playerfights'] = $dailypvpfights;
        $session['user']['transferredtoday'] = 0;
        $session['user']['amountouttoday'] = 0;
        $session['user']['seendragon'] = 0;
        $session['user']['auferstanden']=0;
        $session['user']['seenmaster'] = 0;
        $session['user']['seenlover'] = 0;
        $session['user']['witch'] = 0;
        $session['user']['trauer'] = 0;
        $session['user']['usedouthouse'] = 0;
        $session['user']['seenAcademy'] = 0;
        $session['user']['gotfreeale'] = 0;
        $session['user']['fedmount'] = 0;
        if ($_GET['resurrection']!="true" && $_GET['resurrection']!="egg" ){
            $session['user']['soulpoints']=50 + 5 * $session['user']['level'];
            $session['user']['gravefights']=getsetting("gravefightsperday",10);
            $session['user']['reputation']+=5;
        }
        $session['user']['seenbard'] = 0;
        $session['user']['seenminx'] = 0;
        $session['user']['waisen2']=0;
        $session['user']['boughtroomtoday'] = 0;
        $session['user']['lottery'] = 0;
        $session['user']['recentcomments']=$session['user']['lasthit'];
        $session['user']['lasthit'] = date("Y-m-d H:i:s");
        if ($session['user']['drunkenness']>66){
          output("`&Wegen deines schrecklichen Katers wird dir 1 Runde für heute abgezogen.");
            $session['user']['turns']--;
        }
        
// following by talisman & JT
//Set global newdaysemaphore

       $lastnewdaysemaphore = convertgametime(strtotime(getsetting("newdaysemaphore","0000-00-00 00:00:00")));
       $gametoday = gametime();
        
        if (date("Ymd",$gametoday)!=date("Ymd",$lastnewdaysemaphore)){
            $sql = "LOCK TABLES settings WRITE";
            db_query($sql);

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



// Adventspecial für Merydiâ, der Anfang ist in der setnewday.php, eine Anleitung findet ihr unter www.merydia.de, www.anpera.net oder bei http://www.dai-clan.de/SiliForum/wbb2/

// Copyright by Leen/Cassandra (cassandra@leensworld.de)
// SQL: ALTER TABLE `accounts` ADD `specialperday` INT( 11 ) NOT NULL ; <- auch nutzbar für andere Specials die an bestimmten REAL-Tagen stattfinden und man es nicht jeden Tag nutzen darf
if ($settings['weihnacht'] <> '0')
    {
    $datum = getsetting('weihnacht','1-1');
    $adventtag = explode('-',$datum);
    if ($adventtag[1] <= 24 && $adventtag[1] > 0)
        {
        output('`b`$`n`nHeute ist der '.$adventtag[1].'. Dezember! Du darfst heute den Beutel mit der Nummer '.$adventtag[1].' aufmachen, schau schnell was du geschenkt bekommst!`n`0`b');
        if ($session['user']['specialperday'] < $adventtag[1])
            {
            $session['user']['specialperday'] = $adventtag[1];
            $bild = $adventtag[1];
            output('`n`c<img src="images/advent/'.$bild.'.gif" width="160" height="200">`c`n`n',true);
            //Geschenke *sabber*
            switch ($adventtag[1])
                {
                case 24:
                switch (e_rand(1,5))
                    {
                    case 1:
                    if ($session['user']['experience'] < 20000)
                        {
                        $session['user']['experience'] += 4000;
                        $turnsperday += 30;
                        output('`c`@Du öffnest den Beutel und findest `^4000 `@Erfahrungspunkte und Waldkämpfe.`n`bFrohe Weihnachten!`c`b`n`n');
                        break;
                        }
                    case 2:
                    $gesamtgold = ($session['user']['gold'])+($session['user']['goldinbank']);
                    if ($gesamtgold < 50000)
                        {
                        $session['user']['gold'] += 40000;
                        $turnsperday += 30;
                        output('`c`@Du öffnest den Beutel und findest `^40000 `@Goldstücke und Waldkämpfe.`n`bFrohe Weihnachten!`c`b`n`n');
                        break;
                        }
                    case 3:
                    if ($session['user']['gems'] < 100)
                        {
                        $session['user']['gems'] += 15;
                        $turnsperday += 30;
                        output('`c`@Du öffnest den Beutel und findest `^15 `@Edelsteine und Waldkämpfe.`n`bFrohe Weihnachten!`c`b`n`n');
                        break;
                        }
                    case 4:
                    $session['user']['defence'] += 3;
                    $session['user']['attack'] += 3;
                    $turnsperday += 30;
                    output('`c`@Du öffnest den Beutel und findest je `^3 `@Angriffs- und Verteidigungspunkte, sowie Waldkämpfe.`n`bFrohe Weihnachten!`c`b`n`n');
                    break;
                    case 5:
                    $session['user']['deathpower'] += 200;
                    $turnsperday += 30;
                    output('`c`@Du öffnest den Beutel und findest `^200 `@Gefallen und Waldkämpfe.`n`bFrohe Weihnachten! `c`b`n`n');
                    break;
                    }
                break;
                default:
                switch (e_rand(1,5))
                    {
                    case 1:
                    if ($session['user']['experience'] < 20000)
                        {
                        $session['user']['experience'] += 500;
                        $turnsperday += 5;
                        output('`c`@Du öffnest den Beutel und findest `^500 `@Erfahrungspunkte und Waldkämpfe.`c`n`n');
                        break;
                        }
                    case 2:
                    $gesamtgold = ($session['user']['gold'])+($session['user']['goldinbank']);
                    if ($gesamtgold < 50000)
                        {
                        $session['user']['gold'] += 5000;
                        $turnsperday += 5;
                        output('`c`@Du öffnest den Beutel und findest `^5000 `@Goldstücke und Waldkämpfe.`c`n`n');
                        break;
                        }
                    case 3:
                    if ($session['user']['gems'] < 100)
                        {
                        $session['user']['gems'] += 5;
                        $turnsperday += 5;
                        output('`c`@Du öffnest den Beutel und findest `^5 `@Edelsteine und Waldkämpfe.`c`n`n');
                        break;
                        }
                    case 4:
                    $session['user']['defence'] += 1;
                    $session['user']['attack'] += 1;
                    $turnsperday += 5;
                    output('`c`@Du öffnest den Beutel und findest je `^1 `@Angriffs- und Verteidigungspunkt, sowie Waldkämpfe.`c`n`n');
                    break;
                    case 5:
                    $session['user']['deathpower'] += 50;
                    $turnsperday += 5;
                    output('`c`@Du öffnest den Beutel und findest `^50 `@Gefallen und Waldkämpfe.`c`n`n');
                    break;
                    }
                break;
                }
            }
        else
            {
            output('`b`$`n`nDu hast heute schon deinen Beutel aufgemacht!`n`n`0`b');
            }
        }
    }
else
    {
    $session['user']['specialperday'] = 0;
    }

//Ende des Weihnachtsmarktscripts

    output("`nDer Schmerz in deinen wetterfühligen Knochen sagt dir das heutige Wetter: `k".$settings['weather']."`@.`n");
    if ($_GET['resurrection']==""){
        if ($session['user']['specialty']==1 && $settings['weather']=="Regnerisch"){
            output("`k`nDer Regen schlägt dir aufs Gemüt, aber erweitert deine Dunklen Künste. Du bekommst eine zusätzliche Anwendung.`n");
            $session[user][darkartuses]++;
            }    
        if ($session['user']['specialty']==2 and $settings['weather']=="Gewittersturm"){
            output("`k`nDie Blitze fördern deine Mystischen Kräfte. Du bekommst eine zusätzliche Anwendung.`n");
            $session[user][magicuses]++;
            }    
        if ($session['user']['specialty']==3 and $settings['weather']=="Neblig"){
            output("`k`nDer Nebel bietet Dieben einen zusätzlichen Vorteil. Du bekommst eine zusätzliche Anwendung.`n");
            $session[user][thieveryuses]++;
            }        
    }
//End global newdaysemaphore code and weather mod.

        if ($session['user']['hashorse']){
            //$horses=array(1=>"pony","gelding","stallion");
            //output("`n`&You strap your `%".$session['user']['weapon']."`& to your ".$horses[$session['user']['hashorse']]."'s saddlebags and head out for some adventure.`0");
            //output("`n`&Because you have a ".$horses[$session['user']['hashorse']].", you gain ".((int)$session['user']['hashorse'])." forest fights for today!`n`0");
            //$session['user']['turns']+=((int)$session['user']['hashorse']);
            output(str_replace("{weapon}",$session['user']['weapon'],"`n`&{$playermount['newday']}`n`0"));
            if ($playermount['mountforestfights']>0){
                output("`n`&Weil du ein(e/n) {$playermount['mountname']} besitzt, bekommst du `k".((int)$playermount['mountforestfights'])."`& Runden zusätzlich.`n`0");
                $session['user']['turns']+=(int)$playermount['mountforestfights'];
            }
        }else{
            output("`n`&Du schnallst dein(e/n) `%".$session['user']['weapon']."`& auf den Rücken und ziehst los ins Abenteuer.`0");
            //zusätzliche Waldkämpfe & Anwendungen für bestimmte Rassen: 
     $sql = "SELECT * FROM race WHERE colorname='".$session['user']['race']."'"; 
     $result = db_query($sql); 
     //print $result; 
     $row = db_fetch_assoc($result); 
     //print_r($row); 
     $bonus = unserialize($row['bonus']); 
     //print_r($bonus); 
     $buff = unserialize($row['buff']);                //  switch{case true: return;continue;break;default} 
     if (is_array($buff)) 
     $session['bufflist']['race'] = $buff; 
     $session['user']['turns'] += $bonus['wk']; 
     if ($bonus['wk']!=0) 
     output("`]`nDa du ein {$session[user][race]}`] bist,".((int)$bonus['wk']>0 ? " bekommst du zusätzliche `k".(int)$bonus['wk']."`] Waldkämpfe für heute.`n" 
     : " verlierst du `k".(int)$bonus['wk']*(-1)."`] Waldkämpfe für heute.`n").""); 
     // print_r($bonus); 
     $session['user']['darkartuses'] +=((int)$bonus['da']); 
     $session['user']['magicuses'] +=((int)$bonus['mk']);  
     $session['user']['thieveryuses'] +=((int)$bonus['tv']); 
 // END

        }
        if ($session['user']['race']==3) {
            $session['user']['turns']++;
            output("`n`&Weil du ein Mensch bist, bekommst du `k1`& Waldkampf zusätzlich!`n`0");
        }
        $config = unserialize($session['user']['donationconfig']);
        if (!is_array($config['forestfights'])) $config['forestfights']=array();
        reset($config['forestfights']);
        while (list($key,$val)=each($config['forestfights'])){
            $config['forestfights'][$key]['left']--;
            output("`@Du bekommst eine Extrarunde für die Punkte auf `k{$val['bought']}`@.");
            $session['user']['turns']++;
            if ($val['left']>1){
                output(" Du hast `k".($val['left']-1)."`@ Tage von diesem Kauf übrig.`n");
            }else{
                unset($config['forestfights'][$key]);
                output(" Dieser Kauf ist damit abgelaufen.`n");
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
        if ($config['goldmineday']>0) $config['goldmineday']=0;
        $session['user']['donationconfig']=serialize($config);
        if ($session['user']['hauntedby']>""){
            output("`n`n`)Du wurdest von {$session['user']['hauntedby']}`) heimgesucht und verlierst eine Runde!");
            $session['user']['turns']--;
            $session['user']['hauntedby']="";
        }
        // Ehre & Ansehen
        if ($session['user']['reputation']<=-50){
            $session['user']['reputation']=-50;
            output("`n`8Da du aufgrund deiner Ehrenlosigkeit häufig Steine in den Weg gelegt bekommst, kannst du heute 1 Runden weniger kämpfen. Außerdem sind deine Feinde vor dir gewarnt.`nDu solltest dringend etwas für deine Ehre tun!");
            $session['user']['turns']--;
            $session['user']['playerfights']--;
        }else if ($session['user']['reputation']<=-30){
            output("`n`8Deine Ehrenlosigkeit hat sich herumgesprochen! Deine Feinde sind vor dir gewarnt, weshalb dir heute 1 Spielerkampf weniger gelingen wird.`nDu solltest dringend etwas für deine Ehre tun!");
            $session['user']['playerfights']--;
        }else if ($session['user']['reputation']<-10){
            output("`n`8Da du aufgrund deiner Ehrenlosigkeit häufig Steine in den Weg gelegt bekommst, kannst du heute 1 Runde weniger kämpfen.");
            $session['user']['turns']--;
        }else if ($session['user']['reputation']>=30){
            if ($session['user']['reputation']>50) $session['user']['reputation']=50;
            output("`n`9Da du aufgrund deiner großen Ehrenhaftigkeit das Volk auf deiner Seite hast, kannst du heute 1 Runde und 1 Spielerkampf mehr kämpfen.");
            $session['user']['turns']++;
            $session['user']['playerfights']++;
        }else if ($session['user']['reputation']>10){
            output("`n`mDa du aufgrund deiner großen Ehrenhaftigkeit das Volk auf deiner Seite hast, kannst du heute 1 Runde mehr kämpfen.");
            $session['user']['turns']++;
        }

        $session['user']['drunkenness']=0;
        $session['user']['bounties']=0;
        // Buffs from items
        $sql="SELECT * FROM items WHERE (class='Fluch' OR class='Geschenk' OR class='Zauber') AND owner=".$session[user][acctid]." ORDER BY id";
        $result=db_query($sql);
        for ($i=0;$i<db_num_rows($result);$i++){
              $row = db_fetch_assoc($result);
            if (mb_strlen($row[buff])>8){
                $row[buff]=unserialize($row[buff]);
                if ($row['class']!='Zauber') $session[bufflist][$row[buff][name]]=$row[buff];
                if ($row['class']=='Fluch') output("`n`G$row[name]`G nagt an dir.");
                if ($row['class']=='Geschenk') output("`n`1$row[name]`1: $row[description]");
            }
            if ($row[hvalue]>0){
                $row[hvalue]--;
                if ($row[hvalue]<=0){
                    db_query("DELETE FROM items WHERE id=$row[id]");
                    if ($row['class']=='Fluch') output(" Aber nur noch heute.");
                    if ($row['class']=='Zauber') output("`n`Q$row[name]`Q hat seine Kraft verloren.");
                }else{
                    $what="hvalue=$row[hvalue]";
                    if ($row['class']=='Zauber') $what.=", value1=$row[value2]";
                    db_query("UPDATE items SET $what WHERE id=$row[id]");
                }
            }
        }        
    }
}
page_footer();
?> 