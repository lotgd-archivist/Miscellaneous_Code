<?php

// 16062004

require_once "dbwrapper.php";
require_once "anticheat.php";

$pagestarttime = getmicrotime();

$nestedtags=array();
$output="";

function pvpwarning($dokill=false) {
        global $session;
        $days = getsetting("pvpimmunity", 5);
        $exp = getsetting("pvpminexp", 1500);
        if ($session['user']['age'] <= $days &&
                $session['user']['dragonkills'] == 0 &&
                $session['user']['user']['pk'] == 0 &&
                $session['user']['experience'] <= $exp) {
                if ($dokill) {
                        output("`\$Warnung!`^ Da du selbst noch vor PvP geschützt warst, aber jetzt einen anderen Spieler angreifst, hast du deine Immunität verloren!!`n`n");
                        $session['user']['pk'] = 1;
                } else {
                        output("`\$Warnung!`^ Innerhalb der ersten $days  Tage in dieser Welt, oder bis sie $exp Erfahrungspunkte gesammelt haben, sind alle Spieler vor PvP-Angriffen geschützt. Wenn du einen anderen Spieler angreifst, verfällt diese Immunität für dich!`n`n");
                }
        }
}

function rawoutput($indata) {
        global $output;
        $output .= $indata . "\n";
}

function output($data,$priv=false){
        global $nestedtags,$output;
// Aprilscherz deaktiviert ;)
//        if (date("m-d")=="04-01"){
//                $out = appoencode($data,$priv);
//                if ($priv==false) $out = borkalize($out);
//                $output.=$out;
//        }else{
          $output.=appoencode($data,$priv);
//        }
        $output.="\n";
        return 0;
}

function compress_out ($input) {
//Based on old YaBBSE code (c)
//Open-Source Project by Zef Hemel (zef@zefnet.com <mailto:zef@zefnet.com>)
//Copyright (c) 2001-2002 The YaBB Development Team
        if((function_exists("gzcompress")) && (function_exists("crc32"))){
           if(strpos(" " . $_SERVER['HTTP_ACCEPT_ENCODING'], "x-gzip")){
              $encode = "x-gzip";
           }
           elseif(strpos(" " . $_SERVER['HTTP_ACCEPT_ENCODING'], "gzip")){
              $encode = "gzip";
           }
        if (isset($encode)){
                header("Content-Encoding: $encode");
                $encode_size = strlen($input);
                $encode_crc = crc32($input);
                $out = "\x1f\x8b\x08\x00\x00\x00\x00\x00";
                $out .= substr(gzcompress($input, 1), 0, -4);
                $out .= pack("V", $encode_crc);
                $out .= pack("V", $encode_size);
        }
        else{
                $out = $input;
        }
}
else{
        $out = $input;
}
return ($out);
}


function safeescape($input){
        return preg_replace('/([^\\\\])(["\'])/s',"\\1\\\\\\2",$input);

}

function petitionmail($subject,$body,$petition,$from,$seen=0,$to=0,$messageid=0) {
        $subject = safeescape($subject);
        $subject=str_replace("\n","",$subject);
        $subject=str_replace("`n","",$subject);
        $body = safeescape($body);

        $sql = "INSERT INTO petitionmail (petitionid,messageid,msgfrom,msgto,subject,body,sent,seen) VALUES ('".(int)$petition."','".(int)$messageid."','".(int)$from."','".(int)$to."','$subject','$body',now(),'$seen')";
        db_query($sql);
        $sql = 'UPDATE petitions SET lastact=NOW() WHERE petitionid="'.(int)$petition.'"';
        db_query($sql);
}

function systemmail($to,$subject,$body,$from=0,$noemail=false){
        $subject = safeescape($subject);
        $subject=str_replace("\n","",$subject);
        $subject=str_replace("`n","",$subject);
        $body = safeescape($body);
        //echo $subject."<br>".$body;
        $sql = "SELECT prefs,emailaddress FROM accounts LEFT JOIN accounts_text USING(acctid) WHERE accounts.acctid='$to'";
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        db_free_result($result);
        $prefs = unserialize($row['prefs']);

        if ($prefs['dirtyemail']){
                //output("Not cleaning: $prefs[dirtyemail]");
        }else{
                //output("Cleaning: $prefs[dirtyemail]");
                $subject=soap($subject);
                $body=soap($body);
        }

        $sql = "INSERT INTO mail (msgfrom,msgto,subject,body,sent) VALUES ('".(int)$from."','".(int)$to."','$subject','$body',now())";
        db_query($sql);
        $email=false;
        if ($prefs['emailonmail'] && $from>0){
                $email=true;
        }elseif($prefs[emailonmail] && $from==0 && $prefs[systemmail]){
                $email=true;
        }
        if (!is_email($row['emailaddress'])) $email=false;
        if ($email && !$noemail){
                $sql = "SELECT name FROM accounts WHERE acctid='$from'";
                $result = db_query($sql);
                $row1=db_fetch_assoc($result);
                db_free_result($result);
                if ($row1['name']!="") $fromline="From: ".preg_replace("'[`].'","",$row1[name])."\n";
                // We've inserted it into the database, so.. strip out any formatting
                // codes from the actual email we send out... they make things
                // unreadable
                $body = preg_replace("'[`]n'", "\n", $body);
                $body = preg_replace("'[`].'", "", $body);
                mail($row['emailaddress'],"Neue LoGD Mail","Du hast eine neue Nachricht von LoGD @ http://".$_SERVER[HTTP_HOST].dirname($_SERVER[SCRIPT_NAME])." empfangen.\n\n$fromline"
                        ."Betreff: ".preg_replace("'[`].'","",stripslashes($subject))."\n"
                        ."Body: ".stripslashes($body)."\n"
                        ."\nDu kannst diese Meldungen in deinen Einstellungen abschalten.",
                        "From: ".getsetting("gameadminemail","postmaster@localhost")
                );
        }
}

function isnewday($level){
        global $session;
        if ($session['user']['superuser']<$level) {
                clearnav();
                $session['output']="";
                page_header("FREVEL!");
                $session['bufflist']['angrygods']=array(
                        "name"=>"`^Die Götter sind wütend!",
                        "rounds"=>10,
                        "wearoff"=>"`^Es ist den Göttern langweilig geworden, dich zu quälen.",
                        "minioncount"=>$session['user']['level'],
                        "maxgoodguydamage"=> 2,
                        "effectmsg"=>"`7Die Götter verfluchen dich und machen dir `^{damage}`7 Schaden!",
                        "effectnodmgmsg"=>"`7Die Götter haben beschlossen, dich erstmal nicht zu quälen.",
                        "activate"=>"roundstart",
                        "survivenewday"=>1,
                        "newdaymessage"=>"`6Die Götter sind dir immer noch böse!"
                );
                output("Für den Versuch, die Götter zu betrügen, wurdest du niedergeschmettert!`n`n");
                output("`\$Ramius, der Gott der Toten`) erscheint dir in einer Vision. Dafür, dass du versucht hast, deinen Geist mit seinem zu messen, sagt er dir wortlos, dass du keinen Gefallen mehr bei ihm hast.`n`n");
                addnews("`&Für den Versuch, die Götter zu besudeln, wurde ".$session['user']['name']." zu Tode gequält! (Hackversuch gescheitert).");
                $session['user']['hitpoints']=0;
                $session['user']['alive']=0;
                $session['user']['soulpoints']=0;
                $session['user']['gravefights']=0;
                $session['user']['deathpower']=0;
                $session['user']['experience']*=0.75;
                addnav("Tägliche News","news.php");
                page_footer();
                $sql = "SELECT acctid FROM accounts WHERE superuser>=3";
                $result = db_query($sql);
                while ($row = db_fetch_assoc($result)) {
                        systemmail($row['acctid'],"`#{$session['user']['name']}`# hat versucht, Superuser-Seiten zu hacken!","Böse(r), böse(r), böse(r) {$session['user']['name']}, du bist ein Hacker!");
                }
                exit();
        }
}

function forest($noshowmessage=false) {
        global $session,$playermount;
  $conf = unserialize(gettexts('donationconfig'));
  if ($conf['healer'] || $session['user']['acctid']==getsetting("hasegg",0)) {
          addnav("H?Golindas Hütte","healer.php");
  } else {
          addnav("H?Hütte des Heilers","healer.php");
  }
  addnav("B?Etwas zum Bekämpfen suchen","forest.php?op=search");
  if ($session['user']['level']>1)
          addnav("H?Herumziehen","forest.php?op=search&type=slum");
  addnav("N?Nervenkitzel suchen","forest.php?op=search&type=thrill");
  //if ($session[user][hashorse]>=2) addnav("D?Dark Horse Tavern","forest.php?op=darkhorse");
  if ($playermount['tavern']>0) addnav("D?Nimm {$playermount['mountname']} zur Dark Horse Taverne","forest.php?op=darkhorse");
  if ($playermount['tavern']>0 && $conf['castle']) addnav("B?Nimm {$playermount['mountname']} zur Burg","forest.php?op=castle");
  addnav("Z?Zurück zum Dorf","village.php");
  addnav("","forest.php");
        if (($session['user']['level']>=15 || $conf['dragonvalley'])  && $session['user']['seendragon']==0){
                //addnav("G?`@Den Grünen Drachen suchen","forest.php?op=dragon");
                addnav("`@Reite ins Drachental","forest.php?op=drachental");
        }
        addnav("Spatzenfelsen","schnellbank.php");
        addnav("Sonstiges");
        addnav("G?Geheimnisvolle Lichtung","ritual.php");
        addnav("P?Plumpsklo","outhouse.php");
        if ($session['user']['turns']<=1 ) addnav("Hexenhaus","hexe.php");
        if ($noshowmessage!=true){
                output("`c`7`bDer Wald`b`0`c");
                output("Der Wald, Heimat von bösartigen Kreaturen und üblen Übeltätern aller Art.`n`n");
                output("Die dichten Blätter des Waldes erlauben an den meisten Stellen nur wenige Meter Sicht.  ");
                output("Die Wege würden dir verborgen bleiben, hättest du nicht ein so gut geschultes Auge. Du bewegst dich so leise wie ");
                output("eine milde Brise über den dicken Humus, der den Boden bedeckt. Dabei versuchst du es zu vermeiden ");
                output("auf dünne Zweige oder irgendwelche der ausgebleichten Knochenstücke zu treten, welche den Waldboden spicken. ");
                output("Du verbirgst deine Gegenwart vor den abscheulichen Monstern, die den Wald durchwandern.");
                if ($session['user']['turns']<=1) output(" In der Nähe siehst du wieder den Rauch aus dem Kamin eines windschiefen Hexenhäuschens aufsteigen, von dem du schwören könntest, es war eben noch nicht da. ");
                output("`n`n ");
                viewcommentary("forest","Rede mit den anderen:",25,"sagt");
        }
        if ($session['user']['superuser']>1){
                output("`n`nSUPERUSER Specials:`n");
                $d = dir("special");
                while (false !== ($entry = $d->read())){
                        // Skip non php files (including directories)
                        if(strpos($entry, ".php") === false) continue;
                        // Skip any hidden files
                        if (substr($entry,0,1)==".") continue;
                          output("<a href='forest.php?specialinc=$entry'>$entry</a>`n", true);
                        addnav("","forest.php?specialinc=$entry");
                }
        }
}

function borkalize($in){
        $out = $in;
        $out = str_replace(". ",". Bork bork. ",$out);
        $out = str_replace(", ",", bork, ",$out);
        $out = str_replace(" h"," hoor",$out);
        $out = str_replace(" v"," veer",$out);
        $out = str_replace("g ","gen ",$out);
        $out = str_replace(" p"," pere",$out);
        $out = str_replace(" qu"," quee",$out);
        $out = str_replace("n ","nen ",$out);
        $out = str_replace("e ","eer ",$out);
        $out = str_replace("s ","ses ",$out);
        return $out;
}

function getmicrotime(){
        list($usec, $sec) = explode(" ",microtime());
        return ((float)$usec + (float)$sec);
}
function make_seed() {
    list($usec, $sec) = explode(' ', microtime());
    return (float) $sec + ((float) $usec * 100000);
}
mt_srand(make_seed());

function e_rand($min=false,$max=false){
        if ($min===false) return mt_rand();
        $min*=1000;
        if ($max===false) return round(mt_rand($min)/1000,0);
        $max*=1000;
        if ($min==$max) return round($min/1000,0);
        // if ($min==0 && $max==0) return 0; //do NOT ask me why this line can be executed, it makes no sense, but it *does* get executed.
        if ($min<$max){
                return round(@mt_rand($min,$max)/1000,0);
        }else if($min>$max){
                return round(@mt_rand($max,$min)/1000,0);
        }
}

function is_email($email){
        return preg_match("/[[:alnum:]_.-]+[@][[:alnum:]_.-]{2,}.[[:alnum:]_.-]{2,}/",$email);
}

function checkban($login=false){
        global $session;
        if ($session['banoverride']) return false;
        if ($login===false){
                $ip=$_SERVER[REMOTE_ADDR];
                $id=$_COOKIE[lgi];
                //echo "<br>Orig output: $ip, $id<br>";
        }else{
                $sql = "SELECT lastip,uniqueid,banoverride FROM accounts WHERE login='$login'";
                $result = db_query($sql) or die(db_error(LINK));
                $row = db_fetch_assoc($result);
                if ($row['banoverride']){
                        $session['banoverride']=true;
                        //echo "`nYou are absolved of your bans, son.";
                        return false;
                }else{
                        //echo "`nNo absolution here, son.";
                }
                db_free_result($result);
                $ip=$row['lastip'];
                $id=$row['uniqueid'];
                //echo "<br>Secondary output: $ip, $id<br>";
        }
        $sql = "select * from bans where ((substring('$ip',1,length(ipfilter))=ipfilter AND ipfilter<>'') OR (uniqueid='$id' AND uniqueid<>'')) AND (banexpire='0000-00-00' OR banexpire>'".date("Y-m-d")."')";
        //echo $sql;
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)>0){
            // $msg.=$session['message'];
                $session=array();
                //$session['message'] = $msg;
                //echo "Session Abandonment";
                $session[message].="`n`4Du bist einer Verbannung zum Opfer gefallen:`n";
                for ($i=0;$i<db_num_rows($result);$i++){
                        $row = db_fetch_assoc($result);
                        $session[message].=$row[banreason];
                        if ($row[banexpire]=="0000-00-00") $session[message].="  `\$Die Verbannung ist permanent!`0";
                        if ($row[banexpire]!="0000-00-00") $session[message].="  `^Der Bann wird am ".date("M d, Y",strtotime($row[banexpire]))." aufgehoben `0";
                        $session[message].="`n";
                }
                $session[message].="`4Wenn du willst, kannst du mit einer Anfrage nach dem Grund fragen.";
                header("Location: index.php");
                exit();
        }
        db_free_result($result);
}

function increment_specialty(){
  global $session;
               /* if ($session[user][specialty]>0){
                        $skillnames = array(1=>"Dunkle Künste","Mystische Kräfte","Diebeskunst");
                        $skills = array(1=>"darkarts","magic","thievery");
                        $skillpoints = array(1=>"darkartuses","magicuses","thieveryuses");
                        $session[user][$skills[$session[user][specialty]]]++;
                        output("`nDu steigst in `&".$skillnames[$session[user][specialty]]."`# ein Level auf ".$session[user][$skills[$session[user][specialty]]]." auf. ");
                        $x = ($session[user][$skills[$session[user][specialty]]]) % 3;
                        if ($x == 0){
                                output("Du bekommst eine zusätzliche Anwendung!`n");
                                $session[user][$skillpoints[$session[user][specialty]]]++;
                        }else{
                                output("Nur noch ".(3-$x)." weitere Stufen, bis du eine zusätzliche Anwendung erhältst!`n");
                        }
                }   */
                //}else{
                //        output("`7Du wanderst ziel- und planlos durchs Leben. Du solltest eine Rast machen und einige wichtige Entscheidungen für dein weiteres Leben treffen.`n");
                //}
                //SKILLS MOD BY ANGEL START
                if ($session[user][skill]>0){
                   $session[user][skilllevel]++;
                   $sql="SELECT name FROM skills WHERE id=".$session[user][skill]."";
                   $result = db_query($sql) or die(db_error(LINK));
                   $row = db_fetch_assoc($result);
                   output("`nDu steigst in `&".$row['name']."`# ein Level auf ".$session[user][skilllevel]." auf. ");
                   $y = ($session[user][skilllevel]) % 3;
                   if ($y == 0){
                      output("Du bekommst eine zusätzliche Anwendung!`n");
                      $session[user][skillpoints]++;
                   }else{
                      output("Nur noch ".(3-$y)." weitere Stufen, bis du eine zusätzliche Anwendung erhältst!`n");
                   }
                }
                //else{
                //        output("`7Du wanderst ziel- und planlos durchs Leben. Du solltest eine Rast machen und einige wichtige Entscheidungen für dein weiteres Leben treffen.`n");
                //}
                //SKILLS MOD BY ANGEL ENDE
}

function fightnav($allowspecial=true, $allowflee=true){
  global $PHP_SELF,$session;
        //$script = str_replace("/","",$PHP_SELF);
        $script = substr($PHP_SELF,strrpos($PHP_SELF,"/")+1);
        addnav("Kämpfen","$script?op=fight");
        if ($allowflee) {
                addnav("Wegrennen","$script?op=run");
        }
        if (getsetting("autofight",0)){
                addnav("AutoFight");
                addnav("5 Runden kämpfen","$script?op=fight&auto=five");
                addnav("Bis zum bitteren Ende","$script?op=fight&auto=full");
        }
        if ($allowspecial) {
                //SKILLSMOD BY ANGEL START
                if ($session[user][skill]!=0 && $session[user][specialty]!=0){
                        addnav("`bBesondere Fähigkeiten`b");
                        $sql="SELECT * FROM skills WHERE id='".$session[user][skill]."'";
                        $result = db_query($sql); //Befehl senden
                        $row = db_fetch_assoc($result);
                        $c=$row[color];
                        if ($session[user][skillpoints]>0){
                           addnav("$c $row[name]`0", "");
                           addnav("$c &#149; $row[force1]`7 (1/".$session[user][skillpoints].")`0","$script?op=fight&skill=SK&1=1",true);
                        }
                        if ($session[user][skillpoints]>1)
                        addnav("$c &#149; $row[force2]`7 (2/".$session[user][skillpoints].")`0","$script?op=fight&skill=SK&1=2",true);
                        if ($session[user][skillpoints]>2)
                        addnav("$c &#149; $row[force3]`7 (3/".$session[user][skillpoints].")`0","$script?op=fight&skill=SK&1=3",true);
                        if ($session[user][skillpoints]>4)
                        addnav("$c &#149; $row[force4]`7 (5/".$session[user][skillpoints].")`0","$script?op=fight&skill=SK&1=5",true);
                }
               // if($session[user][specialty]!=0){
                //SKILLSMOD BY ANGEL ENDE
                        addnav("`bZusätzliche Fähigkeiten`b");
                        if ($session[user][darkartuses]>0) {
                                addnav("`\$Dunkle Künste`0", "");
                                addnav("`\$&#149; Skelette herbeirufen`7 (1/".$session[user][darkartuses].")`0","$script?op=fight&skill=DA&l=1",true);
                        }
                        if ($session[user][darkartuses]>1)
                                addnav("`\$&#149; Voodoo`7 (2/".$session[user][darkartuses].")`0","$script?op=fight&skill=DA&l=2",true);
                        if ($session[user][darkartuses]>2)
                                addnav("`\$&#149; Geist verfluchen`7 (3/".$session[user][darkartuses].")`0","$script?op=fight&skill=DA&l=3",true);
                        if ($session[user][darkartuses]>4)
                                addnav("`\$&#149; Seele verdorren`7 (5/".$session[user][darkartuses].")`0","$script?op=fight&skill=DA&l=5",true);
                        if ($session[user][thieveryuses]>0) {
                                addnav("`^Diebeskünste`0","");
                                addnav("`^&#149; Beleidigen`7 (1/".$session[user][thieveryuses].")`0","$script?op=fight&skill=TS&l=1",true);
                        }
                        if ($session[user][thieveryuses]>1)
                                addnav("`^&#149; Waffe vergiften`7 (2/".$session[user][thieveryuses].")`0","$script?op=fight&skill=TS&l=2",true);
                        if ($session[user][thieveryuses]>2)
                                addnav("`^&#149; Versteckter Angriff`7 (3/".$session[user][thieveryuses].")`0","$script?op=fight&skill=TS&l=3",true);
                        if ($session[user][thieveryuses]>4)
                                addnav("`^&#149; Angriff von hinten`7 (5/".$session[user][thieveryuses].")`0","$script?op=fight&skill=TS&l=5",true);
                        if ($session[user][magicuses]>0) {
                                addnav("`%Mystische Kräfte`0","");
                                addnav("g?`%&#149; Regeneration`7 (1/".$session[user][magicuses].")`0","$script?op=fight&skill=MP&l=1",true);
                        }
                        if ($session[user][magicuses]>1)
                                addnav("`%&#149; Erdenfaust`7 (2/".$session[user][magicuses].")`0","$script?op=fight&skill=MP&l=2",true);
                        if ($session[user][magicuses]>2)
                                addnav("L?`%&#149; Leben absaugen`7 (3/".$session[user][magicuses].")`0","$script?op=fight&skill=MP&l=3",true);
                        if ($session[user][magicuses]>4)
                                addnav("A?`%&#149; Blitz Aura`7 (5/".$session[user][magicuses].")`0","$script?op=fight&skill=MP&l=5",true);

                        if ($session[user][superuser]>=3) {
                                addnav("`&Superuser`0","");
                                addnav("!?`&&#149; __GOD MODE","$script?op=fight&skill=godmode",true);
                        }
                 //abschließende Klammer vom Skillsmod
                 //}
                 //
        }
}

function appoencode($data,$priv=false){
        global $nestedtags,$session;
        while( !(($x=strpos($data,"`")) === false) ){
                $tag=substr($data,$x+1,1);
                $append=substr($data,0,$x);
                //echo "<font color='green'>$tag</font><font color='red'>".((int)$x)."</font><font color='blue'>$data</font><br>";
                $output.=($priv?$append:HTMLEntities($append));
                $data=substr($data,$x+2);
                switch($tag){
                        case "0":
                        if ($nestedtags[font]) $output.="</span>";
                        unset($nestedtags[font]);
                break;
                        case "1":
                        if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                        $output.="<span class='colDkBlue'>";
                break;
                        case "2":
                        if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                        $output.="<span class='colDkGreen'>";
                break;
                        case "3":
                        if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                        $output.="<span class='colDkCyan'>";
                break;
                        case "4":
                        if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                        $output.="<span class='colDkRed'>";
                break;
                        case "5":
                        if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                        $output.="<span class='colDkMagenta'>";
                break;
                        case "6":
                        if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                        $output.="<span class='colDkYellow'>";
                break;
                        case "7":
                        if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                        $output.="<span class='colDkWhite'>";
                break;
                        case "8":
                        if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                        $output.="<span class='colLime'>";
                break;
                        case "9":
                        if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                        $output.="<span class='colBlue'>";
                break;
                        case "!":
                        if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                        $output.="<span class='colLtBlue'>";
                break;
                        case "@":
                        if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                        $output.="<span class='colLtGreen'>";
                break;
                        case "#":
                        if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                        $output.="<span class='colLtCyan'>";
                break;
                        case "$":
                        if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                        $output.="<span class='colLtRed'>";
                break;
                        case "%":
                        if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                        $output.="<span class='colLtMagenta'>";
                break;
                        case "^":
                        if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                        $output.="<span class='colLtYellow'>";
                break;
                        case "&":
                        if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                        $output.="<span class='colLtWhite'>";
                break;
                        case ")":
                        if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                        $output.="<span class='colLtBlack'>";
                break;
                        case "~":
                        if (($session[user][dragonkills]>=5)||($session[user][superuser]>=2)){
                                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                                $output.="<span class='colBlack'>";
                        }
                break;
                        case "Q":
            if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                    $output.="<span class='colDkOrange'>";
                break;
                        case "q":
            if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                    $output.="<span class='colOrange'>";
               break;
                        case "r":
                    if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                    $output.="<span class='colRose'>";
            break;
                        case "R":
                        if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colPlum'>";
        break;
                        case "V":
                           if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                    $output.="<span class='colBlueViolet'>";
                break;
                        case "v":
                           if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                    $output.="<span class='coliceviolet'>";
            break;
                    case "e":
                    if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                    $output.="<span class='colBrown'>";
            break;
                    case "E":
                    if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                        $output.="<span class='colDustyRose'>";
                break;
                        case "z":
                        if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
            $output.="<span class='colOrchid'>";
                break;
                        case "Z":
                        if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                 $output.="<span class='colMdOrchid'>";
                break;
                        case "x":
                        if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                   $output.="<span class='colCoral'>";
                break;
                        case "u":
                        if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
            $output.="<span class='colCopper'>";
                break;
                        case "f":
                        if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                   $output.="<span class='colFirebrick'>";
                break;
                        case "F":
                        if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                   $output.="<span class='colRed'>";
                break;
                        case "d":
                        if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
            $output.="<span class='colOrangeRed'>";
                break;
                        case "D":
                        if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
            $output.="<span class='colVioletRed'>";
        break;
                case "y":
            if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colSummerSky'>";
                break;
                        case "g":
                           if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                    $output.="<span class='colXLtGreen'>";
            break;
                    case "G":
                    if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                        $output.="<span class='colYellowGreen'>";
                break;
                        case "T":
                           if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                    $output.="<span class='colDkBrown'>";
                break;
                        case "t":
                           if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                    $output.="<span class='colLtBrown'>";
            break;
                        case "+":
                        if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                        $output.="<span class='colLtBlack'>";
                break;
                        case "a":
                           if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                    $output.="<span class='colLtGrey'>";
            break;
                        case "A":
                           if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                    $output.="<span class='colDkGrey'>";
            break;
                    case "k":
                    if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                    $output.="<span class='colXDkGreen'>";
                break;
                        case "c":
                        if ($nestedtags[div]) {
                                $output.="</div>";
                                unset($nestedtags[div]);
                        }else{
                                $nestedtags[div]=true;
                                $output.="<div align='center'>";
                        }
                break;
                        case "H":
                        if ($nestedtags[div]) {
                                $output.="</span>";
                                unset($nestedtags[div]);
                        }else{
                                $nestedtags[div]=true;
                                $output.="<span class='navhi'>";
                        }
                break;
                        case "b":
                        if ($nestedtags[b]){
                                $output.="</b>";
                                unset($nestedtags[b]);
                        }else{
                                $nestedtags[b]=true;
                          $output.="<b>";
                        }
                break;
                  case "i":
                  if ($nestedtags[i]) {
                          $output.="</i>";
                          unset($nestedtags[i]);
                  }else{
                          $nestedtags[i]=true;
                          $output.="<i>";
                  }
                break;
                        case "n":
                        $output.="<br>\n";
                break;
                        case "w":
                        $output.=$session[user][weapon];
                break;
                        case "`":
                        $output.="`";
                break;
                        default:
                        $output.="`".$tag;
                }
        }
        if ($priv){
                $output.=$data;
        }else{
                $output.=HTMLEntities($data);
        }
        return $output;
}

// Angegebene Tags am Ende des Strings schließen
// (macht keinen Sinn bei Farben, da die nicht geschlossen werden)
function closetags($string, $tags) {
        $tags = explode('`',$tags);
        foreach ($tags as $tihs) {
                $that = trim($tihs);
                if ($that=='') continue;
                if (substr_count($string,'`'.$that)%2) $string .= '`'.$that;
        }
        return $string;
}

function templatereplace($itemname,$vals=false){
        global $template;
        @reset($vals);
        if (!isset($template[$itemname])) output("`bWarnung:`b Das `i$itemname`i Template wurde nicht gefunden!`n");
        $out = $template[$itemname];
        //output($template[$itemname]."`n");
        while (list($key,$val)=@each($vals)){
                if (strpos($out,"{".$key."}")===false) output("`bWarnung:`b Das `i$key`i Teil wurde im `i$itemname`i Template nicht gefunden! (".$out.")`n");
                $out = str_replace("{"."$key"."}",$val,$out);
        }
        return $out;
}

function charstats(){
        global $session;
        $u =& $session[user];
        if ($session[loggedin]){
                $u['hitpoints']=round($u['hitpoints'],0);
                $u['experience']=round($u['experience'],0);
                $u['maxhitpoints']=round($u['maxhitpoints'],0);
                $spirits=array("-6"=>"wiedererweckt","-2"=>"sehr schlecht","-1"=>"schlecht","0"=>"normal","1"=>"gut","2"=>"sehr gut");
                if ($u[spirits]<-6) $spirits[$u[spirits]] = "wiedererweckt";
                if ($u[alive]){ }else{ $spirits[$u[spirits]] = "TOT"; }
                reset($session[bufflist]);
                $atk=$u[attack];
                $def=$u[defence];
                while (list($key,$val)=each($session[bufflist])){
                        $buffs.=appoencode("`#$val[name] `7($val[rounds] Runden übrig)`n",true);
                        if (isset($val[atkmod])) $atk *= $val[atkmod];
                        if (isset($val[defmod])) $def *= $val[defmod];
                }
                $atk = round($atk, 2);
                $def = round($def, 2);
                $atk = ($atk == $u[attack] ? "`^" : ($atk > $u[attack] ? "`@" : "`$")) . "`b$atk`b`0";
                $def = ($def == $u[defence] ? "`^" : ($def > $u[defence] ? "`@" : "`$")) . "`b$def`b`0";

                if (count($session[bufflist])==0){
                        $buffs.=appoencode("`^Keine`0",true);
                }
                $charstat=appoencode(templatereplace("statstart")
                .templatereplace("stathead",array("title"=>"Vital Info"))
                .templatereplace("statrow",array("title"=>"Name","value"=>appoencode($u[name],false)))
                ,true);
                if ($session['user']['alive']){

                        $charstat.=appoencode(
                        templatereplace("statrow",array("title"=>"Lebenspunkte","value"=>"$u[hitpoints]`0/$u[maxhitpoints]".grafbar($u[maxhitpoints],$u[hitpoints])))
                        .templatereplace("statrow",array("title"=>"Runden","value"=>$u['turns']))
                        ,true);
                }else{
                        $charstat.=appoencode(
                         templatereplace("statrow",array("title"=>"Seelenpunkte","value"=>"$u[soulpoints]".grafbar((5*$u[level]+50),$u[soulpoints])))
                         .templatereplace("statrow",array("title"=>"Foltern","value"=>$u['gravefights']))
                        ,true);
                }
                $charstat.=appoencode(
                templatereplace("statrow",array("title"=>"Stimmung","value"=>"`b".$spirits[(string)$u['spirits']]."`b"))
                .templatereplace("statrow",array("title"=>"Level","value"=>"`b".$u['level']."`b"))
                .($session['user']['alive']?
                         templatereplace("statrow",array("title"=>"Angriff","value"=>$atk))
                        .templatereplace("statrow",array("title"=>"Verteidigung","value"=>$def))
                        :
                         templatereplace("statrow",array("title"=>"Psyche","value"=>10 + round(($u['level']-1)*1.5)))
                        .templatereplace("statrow",array("title"=>"Geist","value"=>10 + round(($u['level']-1)*1.5)))
                        )
                .templatereplace("statrow",array("title"=>"Edelsteine","value"=>$u['gems']))
                .templatereplace("stathead",array("title"=>"Weitere Infos"))
                .templatereplace("statrow",array("title"=>"Gold","value"=>$u['gold']))
                /*angel mod - so hat man auch in der unterwelt ne sinnvolle EP anzeige allerdings mit gefallen*/
                .($session['user']['alive']?
                         templatereplace("statrow",array("title"=>"Erfahrung","value"=>expbar()))
                        :
                         templatereplace("statrow",array("title"=>"Gefallen","value"=>totbar()))
                        )
                /*end of mod und ausklammerung der alten zeile*/
                #.templatereplace("statrow",array("title"=>"Erfahrung","value"=>expbar()))
                //.templatereplace("statrow",array("title"=>"Neuer Tag","value"=>"{$tomorrow['hours']}h, {$tomorrow['minutes']}m, {$tomorrow['seconds']}s"))
                .templatereplace("statrow",array("title"=>"Waffe","value"=>$u['weapon']))
                .templatereplace("statrow",array("title"=>"Rüstung","value"=>$u['armor']))
                ,true);
                if (!is_array($session[bufflist])) $session[bufflist]=array();
                $tomorrow = timetotomorrow();
                $charstat.=appoencode(templatereplace("statrow",array("title"=>"Nächster Tag","value"=>"{$tomorrow['hours']}h, {$tomorrow['minutes']}m, {$tomorrow['seconds']}s")),true);
                $charstat.=appoencode(templatereplace("statbuff",array("title"=>"Aktionen","value"=>$buffs)),true);
                $charstat.=appoencode(templatereplace("statend"),true);
                return $charstat;
        }else{
                //return "Your character info will appear here after you've logged in.";
                //$sql = "SELECT name,alive,location,sex,level,laston,loggedin,lastip,uniqueid FROM accounts WHERE locked=0 AND loggedin=1 ORDER BY level DESC";
                $sql="SELECT name,alive,location,sex,level,laston,loggedin,lastip,uniqueid FROM accounts WHERE locked=0 AND loggedin=1 AND laston>'".date("Y-m-d H:i:s",strtotime("-".getsetting("LOGINTIMEOUT",900)." seconds"))."' AND invisible='0' ORDER BY level DESC";
                $result = db_query($sql) or die(sql_error($sql));
                $count = db_num_rows($result);
                $ret.=appoencode("`b$count Spieler Online:`b`n");
                for ($i=0;$i<db_num_rows($result);$i++){
                        $row = db_fetch_assoc($result);
                        $ret.=appoencode("`^$row[name]`n");
                            $onlinecount++;
                }
                db_free_result($result);
                if ($onlinecount==0) $ret.=appoencode("`iNiemand`i");
                $ret.=grafbar(getsetting("maxonline",10),(getsetting("maxonline",10)-$onlinecount),180);
                return $ret;
        }
}

$accesskeys=array();
$quickkeys=array();
function addnav($text,$link=false,$priv=false,$pop=false){
        global $nav,$session,$accesskeys,$REQUEST_URI,$quickkeys;
/*
        if (date("m-d")=="04-01"){
                $text = borkalize($text);
        }
*/
        if ($link===false){
                $nav.=templatereplace("navhead",array("title"=>appoencode($text,$priv)));
        }elseif ($link === "") {
                $nav.=templatereplace("navhelp",array("text"=>appoencode($text,$priv)));
        }else{
                if ($text!=""){
                        $extra="";
                        if (1) {
                                if (strpos($link,"?")){
                                        $extra="&c=$session[counter]";
                                }else{
                                        $extra="?c=$session[counter]";
                                }
                        }

                        $extra.="-".date("His");
                        //$link = str_replace(" ","%20",$link);
                        //hotkey for the link.
                        $key="";
                        if (substr($text,1,1)=="?") {
                                // check to see if a key was specified up front.
                                if ($accesskeys[strtolower(substr($text, 0, 1))]==1){
                                        // output ("key ".substr($text,0,1)." already taken`n");
                                        $text = substr($text,2);
                                }else{
                                        $key = substr($text,0,1);
                                        $text = substr($text,2);
                                        //output("key set to $key`n");
                                        $found=false;
                                        for ($i=0;$i<strlen($text); $i++){
                                                $char = substr($text,$i,1);
                                                if ($ignoreuntil == $char){
                                                        $ignoreuntil="";
                                                }else{
                                                        if ($ignoreuntil<>""){
                                                                if ($char=="<") $ignoreuntil=">";
                                                                if ($char=="&") $ignoreuntil=";";
                                                                if ($char=="`") $ignoreuntil=substr($text,$i+1,1);
                                                        }else{
                                                                if ($char==$key) {
                                                                        $found=true;
                                                                        break;
                                                                }
                                                        }
                                                }
                                        }
                                        if ($found==false) {
                                                if (strpos($text, "__") !== false)
                                                        $text=str_replace("__", "(".$key.") ", $text);
                                                else
                                                        $text="(".strtoupper($key).") ".$text;
                                                $i=strpos($text, $key);
                                                // output("Not found`n");
                                        }
                                }
                                //
                        }
                        if ($key==""){
                                for ($i=0;$i<strlen($text); $i++){
                                        $char = substr($text,$i,1);
                                        if ($ignoreuntil == $char) {
                                                $ignoreuntil="";
                                        }else{
                                                if (($accesskeys[strtolower($char)]==1) || (strpos("abcdefghijklmnopqrstuvwxyz0123456789", strtolower($char)) === false) || $ignoreuntil<>"") {
                                                        if ($char=="<") $ignoreuntil=">";
                                                        if ($char=="&") $ignoreuntil=";";
                                                        if ($char=="`") $ignoreuntil=substr($text,$i+1,1);
                                                }else{
                                                        break;
                                                }
                                        }
                                }
                        }
                        if ($i<strlen($text)){
                                $key=substr($text,$i,1);
                                $accesskeys[strtolower($key)]=1;
                                $keyrep=" accesskey=\"$key\" ";
                        }else{
                                $key="";
                                $keyrep="";
                        }
                        //output("Key is $key for $text`n");

                        if ($key==""){
                                //$nav.="<a href=\"".HTMLEntities($link.$extra)."\" class='nav'>".appoencode($text,$priv)."<br></a>";
                                //$key==""; // This is useless
                        }else{
                                $text=substr($text,0,strpos($text,$key))."`H".$key."`H".substr($text,strpos($text,$key)+1);
                                if ($pop){
                                        $quickkeys[$key]=popup($link.$extra);
                                }else{
                                        $quickkeys[$key]="window.location='$link$extra';";
                                }
                        }
                        $nav.=templatereplace("navitem",array(
                                "text"=>appoencode($text,$priv),
                                "link"=>HTMLEntities($link.$extra),
                                "accesskey"=>$keyrep,
                                "popup"=>($pop==true ? "target='_blank' onClick=\"".popup($link.$extra)."; return false;\"" : "")
                                ));
                        //$nav.="<a href=\"".HTMLEntities($link.$extra)."\" $keyrep class='nav'>".appoencode($text,$priv)."<br></a>";
                }
                $session[allowednavs][$link.$extra]=true;
                $session[allowednavs][str_replace(" ", "%20", $link).$extra]=true;
                $session[allowednavs][str_replace(" ", "+", $link).$extra]=true;
        }
}

function savetexts() {
        global $session;
        $update = '';
        foreach ($session['usertexts_changed'] AS $key=>$val) {
                if ($val) {
                        if (is_array($session['usertexts'][$key])) {
                                $session['usertexts'][$key] = serialize($session['usertexts'][$key]);
                        }
                        $update .= ', '.$key.'="'.addslashes($session['usertexts'][$key]).'"';
                }
        }
        if ($update!='') {
                $sql = 'UPDATE accounts_text SET '.substr($update,2).' WHERE acctid='.(int)$session['user']['acctid'];
                db_query($sql);
        }
}

function updatetexts($key,$val) {
        global $session;
        if (serialize($val)!=serialize($session['usertexts'][$key])) {
                $session['usertexts'][$key] = $val;
                $session['usertexts_changed'][$key] = true;
        }
}

function loadtexts() {
        global $session;
        $session['usertexts'] = $session['usertexts_changed'] = array();
        $sql = 'SELECT * FROM accounts_text WHERE acctid='.(int)$session['user']['acctid'];
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        $keys = array_keys($row);
        foreach ($keys AS $tihskey) $session['usertexts_changed'][$tihskey] = false;
        $session['usertexts'] = $row;
}

function gettexts($key) {
        global $session;
        if (!is_array($session['usertexts'])) {
                if ($session['loggedin']) loadtexts();
                else return '';
        }
        return $session['usertexts'][$key];
}

function savesetting($settingname,$value){
        global $settings;
        loadsettings();
        if ($value>""){
                if (!isset($settings[$settingname])){
                        $sql = "INSERT INTO settings (setting,value) VALUES (\"".addslashes($settingname)."\",\"".addslashes($value)."\")";
                }else{
                        $sql = "UPDATE settings SET value=\"".addslashes($value)."\" WHERE setting=\"".addslashes($settingname)."\"";
                }
                db_query($sql) or die(db_error(LINK));
                $settings[$settingname]=$value;
                if (db_affected_rows()>0) return true; else return false;
        }
        return false;
}

function loadsettings(){
        global $settings;
        //as this seems to be a common complaint, examine the execution path of this function,
        //it will only load the settings once per page hit, in subsequent calls to this function,
        //$settings will be an array, thus this function will do nothing.
        if (!is_array($settings)){
                $settings=array();
                $sql = "SELECT * FROM settings";
                $result = db_query($sql) or die(db_error(LINK));
                for ($i=0;$i<db_num_rows($result);$i++){
                        $row = db_fetch_assoc($result);
                        $settings[$row[setting]] = $row[value];
                }
                db_free_result($result);
                $ch=0;
                if ($ch=1 && strpos($_SERVER['SCRIPT_NAME'],"login.php")){
                        //@file("http://www.mightye.org/logdserver?".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
                }
        }
}

function getsetting($settingname,$default){
        global $settings;
        loadsettings();
        if (!isset($settings[$settingname])){
                savesetting($settingname,$default);
                return $default;
        }else{
                if (trim($settings[$settingname])=="") $settings[$settingname]=$default;
                return $settings[$settingname];
        }
}

//beginning of häuserreform

function savewvs($settingname,$value){
        global $wohnviertelsettings;
        loadwvs();
        if ($value>""){
                if (!isset($wohnviertelsettings[$settingname])){
                        $sql = "INSERT INTO wohnviertelsettings (setting,value) VALUES (\"".addslashes($settingname)."\",\"".addslashes($value)."\")";
                }else{
                        $sql = "UPDATE wohnviertelsettings SET value=\"".addslashes($value)."\" WHERE setting=\"".addslashes($settingname)."\"";
                }
                db_query($sql) or die(db_error(LINK));
                $wohnviertelsettings[$settingname]=$value;
                if (db_affected_rows()>0) return true; else return false;
        }
        return false;
}

function loadwvs(){
        global $wohnviertelsettings;
        //as this seems to be a common complaint, examine the execution path of this function,
        //it will only load the settings once per page hit, in subsequent calls to this function,
        //$settings will be an array, thus this function will do nothing.
        if (!is_array($wohnviertelsettings)){
                $wohnviertelsettings=array();
                $sql = "SELECT * FROM wohnviertelsettings";
                $result = db_query($sql) or die(db_error(LINK));
                for ($i=0;$i<db_num_rows($result);$i++){
                        $row = db_fetch_assoc($result);
                        $wohnviertelsettings[$row[setting]] = $row[value];
                }
                db_free_result($result);
                $ch=0;
                if ($ch=1 && strpos($_SERVER['SCRIPT_NAME'],"login.php")){
                        //@file("http://www.mightye.org/logdserver?".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
                }
        }
}

function getwvs($settingname,$default){
        global $wohnviertelsettings;
        loadwvs();
        if (!isset($wohnviertelsettings[$settingname])){
                savewvs($settingname,$default);
                return $default;
        }else{
                if (trim($wohnviertelsettings[$settingname])=="") $wohnviertelsettings[$settingname]=$default;
                return $wohnviertelsettings[$settingname];
        }
}

//end of häuserreform

function savequest($settingname,$value){
        global $quest;
        loadquest();
        if ($value>""){
                if (!isset($quest[$settingname])){
                        $sql = "INSERT INTO quest (quest,value) VALUES (\"".addslashes($settingname)."\",\"".addslashes($value)."\")";
                }else{
                        $sql = "UPDATE quest SET value=\"".addslashes($value)."\" WHERE quest=\"".addslashes($settingname)."\"";
                }
                db_query($sql) or die(db_error(LINK));
                $quest[$settingname]=$value;
                if (db_affected_rows()>0) return true; else return false;
        }
        return false;
}

function loadquest(){
        global $quest;
        //as this seems to be a common complaint, examine the execution path of this function,
        //it will only load the settings once per page hit, in subsequent calls to this function,
        //$settings will be an array, thus this function will do nothing.
        if (!is_array($quest)){
                $quest=array();
                $sql = "SELECT * FROM quest";
                $result = db_query($sql) or die(db_error(LINK));
                for ($i=0;$i<db_num_rows($result);$i++){
                        $row = db_fetch_assoc($result);
                        $quest[$row[quest]] = $row[value];
                }
                db_free_result($result);
                $ch=0;
                if ($ch=1 && strpos($_SERVER['SCRIPT_NAME'],"login.php")){
                        //@file("http://www.mightye.org/logdserver?".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
                }
        }
}

function getquest($settingname,$default){
        global $quest;
        loadquest();
        if (!isset($quest[$settingname])){
                savequest($settingname,$default);
                return $default;
        }else{
                if (trim($quest[$settingname])=="") $quest[$settingname]=$default;
                return $quest[$settingname];
        }
}


function quitthequest(){
        $sql="UPDATE quest SET value=0 WHERE value>0";
        db_query($sql) or die(db_error(LINK));
        $sql="UPDATE accounts SET quest=0";
        db_query($sql) or die(db_error(LINK));
        $sql="UPDATE accounts SET questpoints=0";
        db_query($sql) or die(db_error(LINK));
}

function showform($layout,$row,$nosave=false){
        global $output;
        output("<table>",true);
        while(list($key,$val)=each($layout)){
                $info = split(",",$val);
                if ($info[1]=="title"){
                        output("<tr><td colspan='2' bgcolor='#666666'>",true);
                        output("`b`^$info[0]`0`b");
                        output("</td></tr>",true);
                }else{
                        output("<tr><td nowrap valign='top'>",true);
                        output("$info[0]");
                        output("</td><td>",true);
                }
                switch ($info[1]){
                case "title":

                        break;
                case "enum":
                        reset($info);
                        list($k,$v)=each($info);
                        list($k,$v)=each($info);
                        $output.="<select name='$key'>";
                        while (list($k,$v)=each($info)){
                                $optval = $v;
                                list($k,$v)=each($info);
                                $optdis = $v;
                                $output.="<option value='$optval'".($row[$key]==$optval?" selected":"").">".HTMLEntities("$optval : $optdis")."</option>";
                        }
                        $output.="</select>";
                        break;
                case "password":
                        $output.="<input type='password' name='$key' value='".HTMLEntities($row[$key])."'>";
                        break;
                case "bool":
                        $output.="<select name='$key'>";
                        $output.="<option value='0'".($row[$key]==0?" selected":"").">Nein</option>";
                        $output.="<option value='1'".($row[$key]==1?" selected":"").">Ja</option>";
                        $output.="</select>";
                        break;
                case "hidden":
                        $output.="<input type='hidden' name='$key' value=\"".HTMLEntities($row[$key])."\">".HTMLEntities($row[$key]);
                        break;
                case "viewonly":
                        output(dump_item($row[$key]), true);
//                        output(str_replace("{","<blockquote>{",str_replace("}","}</blockquote>",HTMLEntities(preg_replace("'(b:[[:digit:]]+;)'","\\1`n",$row[$key])))),true);
                        break;
                case "int":
                        $output.="<input name='$key' value=\"".HTMLEntities($row[$key])."\" size='5'>";
                        break;
                case "textarea":
                        $output.="<textarea name='$key' class='input' cols='$info[2]' rows='$info[3]'>".HTMLEntities($row[$key])."</textarea>";
                        break;
                default:
                        $output.=("<input size='50' name='$key' value=\"".HTMLEntities($row[$key])."\">");
                        //output("`n$val");
                }
                output("</td></tr>",true);
        }
        output("</table>",true);
        if ($nosave) {} else output("<input type='submit' class='button' value='Speichern'>",true);

}

function clearnav(){
        $session[allowednavs]=array();
}

function redirect($location,$reason=false){
        global $session,$REQUEST_URI;
        if ($location!="badnav.php"){
                $session[allowednavs]=array();
                addnav("",$location);
        }
        if (strpos($location,"badnav.php")===false) $session[output]="<a href=\"".HTMLEntities($location)."\">Hier klicken</a>";
        $session['debug'].="Redirected to $location from $REQUEST_URI.  $reason\n";
        saveuser();
        header("Location: $location");
        echo $location;
        echo $session['debug'];
        exit();
}

function loadtemplate($templatename){
        if (!file_exists("templates/$templatename") || $templatename=="") $templatename="yarbrough.htm";
        $fulltemplate = join("",file("templates/$templatename"));
        $fulltemplate = split("<!--!",$fulltemplate);
        while (list($key,$val)=each($fulltemplate)){
                $fieldname=substr($val,0,strpos($val,"-->"));
                if ($fieldname!=""){
                        $template[$fieldname]=substr($val,strpos($val,"-->")+3);
                }
        }
        return $template;
}

function maillink(){
        global $session;
        $sql = "SELECT sum(if(seen=1,1,0)) AS seencount, sum(if(seen=0,1,0)) AS notseen FROM mail WHERE msgto=\"".$session[user][acctid]."\"";
        $result = db_query($sql) or die(mysql_error(LINK));
        $row = db_fetch_assoc($result);
        db_free_result($result);
        $row[seencount]=(int)$row[seencount];
        $row[notseen]=(int)$row[notseen];
        if ($row[notseen]>0){
                return "<a href='mail.php' target='_blank' onClick=\"".popup("mail.php").";return false;\" class='hotmotd'>Ye Olde Mail: $row[notseen] neu, $row[seencount] alt</a>";
        }else{
                return "<a href='mail.php' target='_blank' onClick=\"".popup("mail.php").";return false;\" class='motd'>Ye Olde Mail: $row[notseen] neu, $row[seencount] alt</a>";
        }
}

function motdlink(){
    // missing $session caused unread motd's to never highlight the link
        global $session;
        if ($session[needtoviewmotd]){
                return "<a href='motd.php' target='_blank' onClick=\"".popup("motd.php").";return false;\" class='hotmotd'><b>MoTD</b></a>";
        }else{
                return "<a href='motd.php' target='_blank' onClick=\"".popup("motd.php").";return false;\" class='motd'><b>MoTD</b></a>";
        }
}

function page_header($title=""){
        global $header,$SCRIPT_NAME,$session,$template;
        $nopopups["login.php"]=1;
        $nopopups["motd.php"]=1;
        $nopopups["index.php"]=1;
        $nopopups["create.php"]=1;
        $nopopups["about.php"]=1;
        $nopopups["mail.php"]=1;
        $nopopups["chat.php"]=1;

        if ($title=='') $title = getsetting('server_name','Pandea Island');

        $header = $template['header'];
        $sql = "SELECT motddate FROM motd ORDER BY motditem DESC LIMIT 1";
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        db_free_result($result);
        if (($row[motddate]>$session[user][lastmotd]) && $nopopups[$SCRIPT_NAME]!=1 && $session[user][loggedin]){
                $header=str_replace("{headscript}","<script language=\"JavaScript\" type=\"text/javascript\">".popup("motd.php")."</script>",$header);
                $session[needtoviewmotd]=true;
        }else{
                $header=str_replace("{headscript}","",$header);
                $session[needtoviewmotd]=false;
        }
        $header=str_replace("{title}",$title,$header);
}

function popup($page){
  return "window.open('$page','".preg_replace("([^[:alnum:]])","",$page)."','scrollbars=yes,resizable=yes,width=650,height=400')";
}

function page_footer(){
        global $output,$nestedtags,$header,$nav,$session,$REMOTE_ADDR,$REQUEST_URI,$SCRIPT_NAME,$pagestarttime,$quickkeys,$template,$logd_version,$dbqueriesthishit,$dbtimethishit;
        $forumlink=getsetting("forum","http://lotgd.net/forum");
        //$forumlink="http://www.anpera.net/forum/index.php?c=12#";
        while (list($key,$val)=each($nestedtags)){
                $output.="</$key>";

                unset($nestedtags[$key]);
        }
        $script.="<script language=\"JavaScript\" type=\"text/javascript\">
        <!--
        document.onkeypress=keyevent;
        function keyevent(e){
                var c;
                var target;
                var altKey;
                var ctrlKey;
                if (window.event != null) {
                        c=String.fromCharCode(window.event.keyCode).toUpperCase();
                        altKey=window.event.altKey;
                        ctrlKey=window.event.ctrlKey;
                }else{
                        c=String.fromCharCode(e.charCode).toUpperCase();
                        altKey=e.altKey;
                        ctrlKey=e.ctrlKey;
                }
                if (window.event != null)
                        target=window.event.srcElement;
                else
                        target=e.originalTarget;
                if (target.nodeName.toUpperCase()=='INPUT' || target.nodeName.toUpperCase()=='TEXTAREA' || altKey || ctrlKey){
                }else{";
        reset($quickkeys);
        while (list($key,$val)=each($quickkeys)){
                $script.="\n                        if (c == '".strtoupper($key)."') { $val; return false; }";
        }
        $script.="
                }
        }
        //-->
        </script>";


        $footer = $template['footer'];
        if (strpos($footer,"{paypal}") || strpos($header,"{paypal}")){ $palreplace="{paypal}"; }else{ $palreplace="{stats}"; }

        //NOTICE
        //NOTICE Although I will not deny you the ability to remove the below paypal link, I do request, as the author of this software
        //NOTICE that you leave it in.
        //NOTICE
        $paypalstr = '<table align="center"><tr><td>';
        $paypalstr .= '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="nahdude81@hotmail.com">
<input type="hidden" name="item_name" value="Legend of the Green Dragon Author Donation from '.preg_replace("/[`]./","",$session['user']['name']).'">
<input type="hidden" name="item_number" value="'.htmlentities($session['user']['login']).":".$_SERVER['HTTP_HOST']."/".$_SERVER['REQUEST_URI'].'">
<input type="hidden" name="no_shipping" value="1">
<input type="hidden" name="cn" value="Your Character Name">
<input type="hidden" name="cs" value="1">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="tax" value="0">
<input type="image" src="images/paypal1.gif" class="noborder" name="submit" alt="Donate!">
</form>';
        $paysite = getsetting("paypalemail", "");
        if ($paysite != "") {
                $paypalstr .= '</td><td>';
                $paypalstr .= '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
                <input type="hidden" name="cmd" value="_xclick">
                <input type="hidden" name="business" value="'.$paysite.'">
<input type="hidden" name="item_name" value="Legend of the Green Dragon Site Donation from '.preg_replace("/[`]./","",$session['user']['name']).'">
<input type="hidden" name="item_number" value="'.htmlentities($session['user']['login']).":".$_SERVER['HTTP_HOST']."/".$_SERVER['REQUEST_URI'].'">
<input type="hidden" name="no_shipping" value="1">
<input type="hidden" name="cn" value="Your Character Name">
<input type="hidden" name="cs" value="1">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="tax" value="0">
<input type="image" src="images/paypal2.gif" class="noborder" name="submit" alt="Donate!">
</form>';
        }
    $paypalstr .= '</td></tr></table>';
        $footer=str_replace($palreplace,(strpos($palreplace,"paypal")?"":"{stats}").$paypalstr,$footer);
        $header=str_replace($palreplace,(strpos($palreplace,"paypal")?"":"{stats}").$paypalstr,$header);
        //NOTICE
        //NOTICE Although I will not deny you the ability to remove the above paypal link, I do request, as the author of this software
        //NOTICE that you leave it in.
        //NOTICE
        $header=str_replace("{nav}",$nav,$header);
        $footer=str_replace("{nav}",$nav,$footer);

        $header = str_replace("{motd}", motdlink(), $header);
        $footer = str_replace("{motd}", motdlink(), $footer);
        $header = str_replace("{forum}", "<a href='$forumlink' target='_blank' class='motd'>Forum</a>", $header);
        $footer = str_replace("{forum}", "<a href='$forumlink' target='_blank' class='motd'>Forum</a>", $footer);

        if ($session[user][acctid]>0) {
                $header=str_replace("{mail}",maillink(),$header);
                $footer=str_replace("{mail}",maillink(),$footer);
                            $header=str_replace("{chat}","<a href='chat.php' target='_blank' class='motd' onClick=\"".popup("chat.php").";return false;\">Chat</a>",$header);
                            $footer=str_replace("{chat}","<a href='chat.php' target='_blank' class='motd' onClick=\"".popup("chat.php").";return false;\">Chat</a>",$footer);
         }else{
                $header=str_replace("{mail}","",$header);
                $footer=str_replace("{mail}","",$footer);
                            $header=str_replace("{chat}","",$header);
                            $footer=str_replace("{chat}","",$footer);
        }
        $header=str_replace("{petition}","<a href='petition.php' onClick=\"".popup("petition.php").";return false;\" target='_blank' class='motd'>Hilfe anfordern</a>",$header);
        $footer=str_replace("{petition}","<a href='petition.php' onClick=\"".popup("petition.php").";return false;\" target='_blank' class='motd'>Hilfe anfordern</a>",$footer);
        if ($session['user']['superuser']>1){
                $sql = "SELECT max(lastact) AS lastact, count(petitionid) AS c,status FROM petitions GROUP BY status";
                $result = db_query($sql);
                $petitions=array(0=>0,1=>0,2=>0);
                $petitions['unread'] = false;
                for ($i=0;$i<db_num_rows($result);$i++){
                        $row = db_fetch_assoc($result);
                        $petitions[(int)$row['status']] = $row['c'];
                        if ($row['lastact']>$session['lastlogoff']) $petitions['unread'] = true;
                }
                db_free_result($result);
                // Neue Petitionen; schauen, ob Sternchen nötig ist
                $petitions['star'] = '';
                if ($petitions['unread']) {
                        $sql = 'SELECT petitionid, lastact FROM petitions WHERE lastact > "'.$session['lastlogoff'].'"';
                        $result = db_query($sql);
                        while ($row = db_fetch_assoc($result)) {
                                if ($row['lastact']>$session['petitions'][$row['petitionid']]) {
                                        $petitions['star'] = '<span class="colDkRed">*</span>';
                                }
                        }
                        db_free_result($result);
                }

                $avtivate_todolist = '';
                $sql = 'SELECT COUNT(*) AS zahl FROM accounts WHERE activated="0"';
                $result = db_query($sql);
                $row = db_fetch_assoc($result);
                db_free_result($result);
                if ($row['zahl']>0) {
                        $activate_todolist .= '<br><b><a href="activate.php">Freischaltungen</a><span class="colDkRed">*</span></b>';
                        addnav('','activate.php');
                }
                $sql = 'SELECT COUNT(*) AS zahl FROM wohnviertel WHERE status="0"';
                $result = db_query($sql);
                $row = db_fetch_assoc($result);
                db_free_result($result);
                if ($row['zahl']>0) {
                        $activate_haueser .= '<br><b><a href="hausmeister.php">Hausanträge</a><span class="colDkRed">*</span></b>';
                        addnav('','hausmeister.php');
                }
                $sql = 'SELECT t.taskid, t.postdate, MAX(c.postdate) AS lastcomment FROM todolist t LEFT JOIN commentary c ON c.section=CONCAT("todolist-",t.taskid) GROUP BY t.taskid HAVING t.postdate > "'.$session['lastlogoff'].'" OR lastcomment > "'.$session['lastlogoff'].'"';
                $result = db_query($sql);
                while ($row = db_fetch_assoc($result)) {
                        if (max($row['postdate'],$row['lastcomment'])>$session['todolist'][$row['taskid']]) {
                                $activate_todolist .= '<br><b><a href="sutodolist.php">Todolist</a><span class="colDkRed">*</span></b>';
                                addnav('','sutodolist.php');
                                break;
                        }
                }
                db_free_result($result);

                $footer = "<table border='0' cellpadding='5' cellspacing='0' align='right'><tr><td align='right'><b><a href='viewpetition.php'>Anfragen</a>$petitions[star]:</b> $petitions[0] Ungelesen, $petitions[1] Gelesen, $petitions[2] Geschlossen.$activate_todolist</td></tr></table>".$footer;
                addnav('','viewpetition.php');
        }
        $footer=str_replace("{stats}",charstats(),$footer);
        $header=str_replace("{stats}",charstats(),$header);
        $header=str_replace("{script}",$script,$header);
        $footer=str_replace("{source}","<a href='source.php?url=".preg_replace("/[?].*/","",($_SERVER['REQUEST_URI']))."' target='_blank'>PHP&nbsp;Source&nbsp;anzeigen</a>",$footer);
        $header=str_replace("{source}","<a href='source.php?url=".preg_replace("/[?].*/","",($_SERVER['REQUEST_URI']))."' target='_blank'>PHP&nbsp;Source&nbsp;anzeigen</a>",$header);
        $footer=str_replace("{copyright}","Copyright 2002-2003, Game: Eric Stevens",$footer);
        $footer=str_replace("{version}", "Version: $logd_version", $footer);
        $gentime = getmicrotime()-$pagestarttime;
        $session[user][gentime]+=$gentime;
        $session[user][gentimecount]++;
        $footer=str_replace("{pagegen}","Seitengenerierung: ".round($gentime,2)."s, Schnitt: ".round($session[user][gentime]/$session[user][gentimecount],2)."s - ".round($session[user][gentime],2)."/".round($session[user][gentimecount],2).", MySQL: $dbqueriesthishit Queries in ".round($dbtimethishit,2)."s",$footer);
        if (strpos($_SERVER['HTTP_HOST'],"lotgd.net")!==false){
                $footer=str_replace(
                        "</html>",
                        '<script language="JavaScript" type="text/JavaScript" src="http://www.reinvigorate.net/archive/app.bin/jsinclude.php?5193"></script></html>',
                        $footer
                        );
        }

        if (getsetting('scriptlog',0)==1) {
                $sql = 'UPDATE scriptlog SET hits=hits+1,gentime=gentime+'.$gentime.' WHERE scriptname="'.$SCRIPT_NAME.'"';
                db_query($sql);
        }

        $output=$header.$output.$footer;
        $session['user']['gensize']+=strlen($output);
        $session[output]=$output;
        saveuser();

        session_write_close();
        //`mpg123 -g 100 -q hit.mp3 2>&1 > /dev/null`;
        echo compress_out($output);
//        echo $output;
        exit();
}

function popup_header($title="Legend of the Green Dragon"){
  global $header;
        $header.="<html><head><title>$title</title>";
        $header.="<link href=\"newstyle.css\" rel=\"stylesheet\" type=\"text/css\">";
        $header.="</head><body bgcolor='#000000' text='#CCCCCC'><table cellpadding=5 cellspacing=0 width='100%'>";
        $header.="<tr><td class='popupheader'><b>$title</b></td></tr>";
        $header.="<tr><td valign='top' width='100%'>";
}

function popup_footer(){
  global $output,$nestedtags,$header,$nav,$session,$pagestarttime,$SCRIPT_NAME;
        while (list($key,$val)=each($nestedtags)){
                $output.="</$key>";
                unset($nestedtags[$key]);
        }
        $output.="</td></tr><tr><td bgcolor='#330000' align='center'>Copyright 2002, Eric Stevens</td></tr></table></body></html>";
        $output=$header.$output;
        //$session[output]=$output;

        if (getsetting('scriptlog',0)==1) {
                $gentime = getmicrotime()-$pagestarttime;
        //        $sql = 'UPDATE scriptlog SET hits=hits+1,gentime=gentime+'.$gentime.' WHERE scriptname="'.$SCRIPT_NAME.'"';
        //        db_query($sql);
        }

        saveuser();
        echo $output;
        exit();
}

function clearoutput(){
  global $output,$nestedtags,$header,$nav,$session;
        $session[allowednavs]="";
        $output="";
  unset($nestedtags);
        $header="";
        $nav="";
}

function soap($input){
        if (getsetting("soap",1)){
        /*
                $search = "*damn* *dyke *fuck* *phuck* *shit* asshole amcik andskota arschloch arse* atouche ayir bastard bitch* boiolas bollock* buceta butt-pirate cabron cawk cazzo chink chraa chuj cipa clit cock* "
                                                . "cum cunt* dago daygo dego dick* dildo dike dirsa dupa dziwka ejackulate ekrem* ekto enculer faen fag* fanculo fanny fatass fcuk feces feg felcher ficken fitta fitte flikker foreskin phuck fuk* fut futkretzn fuxor gay gook guiena hor "
                                                . "hell helvete hoer* honkey hore huevon hui injun jism jizz kanker* kawk kike klootzak knulle kraut kuk kuksuger kurac kurwa kusi* kyrpä* leitch lesbian lesbo mamhoon masturbat* merd merde mibun monkleigh mouliewop muie "
                                                . "mulkku muschi nazis nepesaurio nigga* *nigger* nutsack orospu paska* pendejo penis perse phuck picka pierdol* pillu* pimmel pimpis piss* pizda poontsee poop porn pron preteen preud prick pula pule pusse pussy puta puto qahbeh queef* queer* "
                                                . "qweef rautenberg schaffer scheiss* scheisse schlampe schmuck screw scrotum sharmuta sharmute shemale shipal shiz skribz skurwysyn slut smut sphencter spic spierdalaj splooge suka teets teez testicle tits titties titty twat twaty vittu "
                                                . "votze woose wank* wetback* whoar whore wichser wop yed zabourah ass";
        */
                $sql = "SELECT * FROM nastywords";
                $result = db_query($sql);
                $row = db_fetch_assoc($result);
                $search = $row['words'];
                $search = str_replace("a",'[a4@]',$search);
                $search = str_replace("l",'[l1!]',$search);
                $search = str_replace("i",'[li1!]',$search);
                $search = str_replace("e",'[e3]',$search);
                $search = str_replace("t",'[t7+]',$search);
                $search = str_replace("o",'[o0]',$search);
                $search = str_replace("s",'[sz$]',$search);
                $search = str_replace("k",'c',$search);
                $search = str_replace("c",'[c(k]',$search);
                $start = "'(\s|\A)";
                $end = "(\s|\Z)'iU";
                $search = str_replace("*","([[:alnum:]]*)",$search);
                $search = str_replace(" ","$end $start", $search);
                $search = "$start".$search."$end";
                //echo $search;
                $search = split(" ",$search);
                //$input = " $input ";

                return preg_replace($search,"\\1`i$@#%`i\\2",$input);
        }else{
                return $input;
        }
}

function saveuser(){
        global $session,$dbqueriesthishit;
//        $cmd = date("Y-m-d H:i:s")." $dbqueriesthishit ".$_SERVER['REQUEST_URI'];
//        @exec("echo $cmd >> /home/groups/l/lo/lotgd/sessiondata/data/queryusage-".$session['user']['login'].".txt");
        if ($session[loggedin] && $session[user][acctid]!=""){
                updatetexts('output',$session['output']);
                updatetexts('allowednavs',$session['allowednavs']);
                updatetexts('bufflist',$session['bufflist']);
                updatetexts('dragonpoints',$session['dragonpoints']);
                updatetexts('prefs',$session['prefs']);
                savetexts();
                unset($session['usertexts']);
                // ERSTMAL DEAKTIVIERT VON ANGEL - TEST ^^ $session['user']['race'] = $session['user']['race']['raceid'];
                //$session[user][laston] = date("Y-m-d H:i:s");
                $sql="UPDATE accounts SET ";
                reset($session[user]);
                while(list($key,$val)=each($session[user])){
                        if (is_array($val)){
                                        $sql.="$key='".addslashes(serialize($val))."', ";
                                }else{
                                        $sql.="$key='".addslashes($val)."', ";
                                }
                }
                $sql = substr($sql,0,strlen($sql)-2);
                $sql.=" WHERE acctid = ".$session[user][acctid];
                db_query($sql);
        }
}

function createstring($array){
  if (is_array($array)){
    reset($array);
    while (list($key,$val)=each($array)){
      $output.=rawurlencode( rawurlencode($key)."\"".rawurlencode($val) )."\"";
    }
    $output=substr($output,0,strlen($output)-1);
  }
  return $output;
}

function createarray($string){
  $arr1 = split("\"",$string);
  $output = array();
  while (list($key,$val)=each($arr1)){
    $arr2=split("\"",rawurldecode($val));
    $output[rawurldecode($arr2[0])] = rawurldecode($arr2[1]);
  }
  return $output;
}

function output_array($array,$prefix=""){
  while (list($key,$val)=@each($array)){
    $output.=$prefix."[$key] = ";
    if (is_array($val)){
      $output.="array{\n".output_array($val,$prefix."[$key]")."\n}\n";
    }else{
      $output.=$val."\n";
    }
  }
  return $output;
}

function dump_item($item){
        $output = "";
        if (is_array($item)) $temp = $item;
        else $temp = unserialize($item);
        if (is_array($temp)) {
                $output .= "array(" . count($temp) . ") {<blockquote>";
                while(list($key, $val) = @each($temp)) {
                        $output .= "'$key' = '" . dump_item($val) . "'`n";
                }
                $output .= "</blockquote>}";
        } else {
                $output .= $item;
        }
        return $output;
}

function addnews($news,$acctid=-1){
        if ($acctid==-1) {
                global $session;
                $acctid = $session[user][acctid];
        }
        $invisible=$session[user][invisible];
        if ($invisible=='0'){
            $sql = "INSERT INTO news(newstext,newsdate,accountid) VALUES ('".addslashes($news)."',NOW(),".$acctid.")";
        if (!($query = db_query($sql))) die(db_error(LINK));
        $lastid = db_insert_id(LINK);
    }
    db_query('DELETE FROM news WHERE newsid <= '.($lastid-80));
    return $query;
}

function checkday() {
        global $session,$revertsession,$REQUEST_URI;
  //output("`#`iChecking to see if you're due for a new day: ".$session[user][laston].", ".date("Y-m-d H:i:s")."`i`n`0");
        if ($session['user']['loggedin']){
                output("<!--CheckNewDay()-->",true);
                if(is_new_day()){
                        $session=$revertsession;
                        $session[user][restorepage]=$REQUEST_URI;
                        $session[allowednavs]=array();
                        addnav("","newday.php");
                        redirect("newday.php");
                }
        }
}

function is_new_day(){
        global $session;
        $t1 = gametime();
        $t2 = convertgametime(strtotime($session[user][lasthit]));
        $d1 = date("Y-m-d",$t1);
        $d2 = date("Y-m-d",$t2);
        if ($d1!=$d2){
                return true;
        }else{
                return false;
        }
}

function getgametime(){
        //return date("g:i a",gametime());
        return date(getsetting('gametimeformat','g:i a'),gametime());
}

// Gamedate-Mod by Chaosmaker
function getgamedate() {
        $date = explode('-',getsetting('gamedate','1600-01-01'));
        $find = array('%Y','%y','%m','%n','%d','%j');
        $replace = array($date[0],sprintf('%02d',$date[0]%100),sprintf('%02d',$date[1]),(int)$date[1],sprintf('%02d',$date[2]),(int)$date[2]);
        return str_replace($find,$replace,getsetting('gamedateformat','%Y-%m-%d'));
}

function gametime(){
        $time = convertgametime(strtotime("now"));
        return $time;
}

function convertgametime($intime){
        //$time = (strtotime(date("1971-m-d H:i:s",strtotime("-".getsetting("gameoffsetseconds",0)." seconds",$intime))))*getsetting("daysperday",4) % strtotime("1971-01-01 00:00:00");
        /*
        // bibirs Rechnung
        $intime -= getsetting("gameoffsetseconds",0); // real-offset einberechnen
        $time = strtotime(date('1971-05-01 H:i:s',$intime)) - strtotime(date('1971-05-01 00:00:00',$intime)); // reale uhrzeit seit 0-uhr(referenzzeit)
        $time *= getsetting("daysperday",4); // in vergangene spielzeit gerechnet
        $time += strtotime(date('1971-05-01 00:00:00',$intime)); // wieder mit realer uhrzeit vereinbaren
        */

        // Noch ne neue Berechnung...
        $multi = getsetting("daysperday",4);
        $time = mktime(date('H',$intime)*$multi,
                                                date('i',$intime)*$multi,
                                                (date('s',$intime)-getsetting("gameoffsetseconds",0))*$multi,
                                                4,
                                                date('d',$intime)*$multi,
                                                2004,
                                                1);

        // Ganz neu, ganz toll, ganz ungetestet
        /*
        $monat = date('n',$intime);
        if (date('Y',$intime)>2004) $monat += 12*(date('Y',$intime)-2004);
        $days = 0;
        if ($monat < 4) {
          for ($i=$monat; $i<4; $i++) $days -= date('t',mktime(0,0,0,$i,1,2004));
        }
        elseif ($monat > 4) {
          for ($i=4; $i<$monat; $i++) $days += date('t',mktime(0,0,0,$i,1,2004));
        }
        $multi = getsetting("daysperday",4);
        $time = mktime(date('H',$intime)*$multi,
                                                date('i',$intime)*$multi,
                                                (date('s',$intime)-getsetting("gameoffsetseconds",0))*$multi,
                                                4,
                                                (date('d',$intime)+$days)*$multi,
                                                2004,
                                                1);
        */

        return $time;
}

function timetotomorrow($what="array"){
            $time = gametime();
            $tomorrow = mktime(0,0,0,date('m',$time),date('d',$time)+1,date('Y',$time));
            $secstotomorrow = $tomorrow-$time;
            $realsecstotomorrow = round($secstotomorrow / (int)getsetting("daysperday",4));
            $hours=(int)($realsecstotomorrow/60/60);
            $minutes=(int)($realsecstotomorrow/60)-$hours*60;
            $seconds=$realsecstotomorrow-($hours*3600+$minutes*60);
            if ($what=="hours"){
                        return($hours);
                    }
        elseif ($what=="minutes"){
                        return($minutes);
                    }
        elseif ($what=="seconds"){
                        return($seconds);
                    }
        elseif ($what=="realsecs"){
                        return($realsecstotomorrow);
                    }
        else{
                        return(array(
                                    "hours"=>$hours,
                                    "minutes"=>$minutes,
                                    "seconds"=>$seconds,
                                    "realsecs"=>$realsecstotomorrow
                                        ));
                    }
        }

function sql_error($sql){
        global $session;
        return output_array($session)."SQL = <pre>$sql</pre>".db_error(LINK);
}

function ordinal($val){
  $exceptions = array(1=>"ten",2=>"ten",3=>"ten",11=>"ten",12=>"ten",13=>"ten");
        $x = ($val % 100);
        if (isset($exceptions[$x])){
          return $val.$exceptions[$x];
        }else{
          $x = ($val % 10);
                if (isset($exceptions[$x])){
                  return $val.$exceptions[$x];
                }else{
                  return $val."ten";
                }
        }
}

// Schwarze Bretter
function viewmessageboard($boardid, $messages='Am schwarzen Brett flattern einige Nachrichten im Luftzug:', $nomessages='Am schwarzen Brett ist nicht eine einzige Nachricht zu sehen.', $allowdelete=0) {
        global $session,$REQUEST_URI;

        // Nachricht löschen
        if ($_GET['boardact']=='del') {
                $sql = 'DELETE FROM messageboard WHERE messageid="'.$_GET['msg'].'"';
                db_query($sql);
        }

        // Löschen-Link vorbereiten
        $req = preg_replace("'&?(c|boardact|msg)=(\w|-)*'","",$REQUEST_URI)."&boardact=del";
        $req = str_replace("?&","?",$req);
        if (!strpos($req,"?")) $req = str_replace("&","?",$req);

        // Wer darf löschen?
        if ($session['user']['superuser']>=3 || $allowdelete===true || $allowdelete===(int)$session['user']['acctid'] || (is_array($allowdelete) && in_array($session['user']['acctid']))) {
                $delete = true;
        }
        else $delete = false;

        $sql = 'SELECT * FROM messageboard WHERE boardid="'.$boardid.'" ORDER BY messageid DESC';
        $result = db_query($sql);
        if (db_num_rows($result)==0) {
                output($nomessages);
        }
        else {
                output($messages);
                while ($row = db_fetch_assoc($result)) {
                        $shortname = preg_replace('/`./','',trim(strrchr($row['name'],' ')));
                        output("`n`n<a href=\"mail.php?op=write&to=".rawurlencode($shortname)."\" target=\"_blank\" onClick=\"".popup("mail.php?op=write&to=".rawurlencode($shortname)."").";return false;\"><img src='images/newscroll.GIF' width='16' height='16' alt='Mail schreiben' border='0'></a>",true);
                        output("`& $row[name]`&:`n`^$row[message]`0 ");
                        if ($row[acctid]==$session[user][acctid] || $delete){
                                output("[<a href='".$req."&msg=".$row['messageid']."'>entfernen</a>]",true);
                                addnav("",$req."&msg=".$row['messageid']);
                        }
                }
        }
}

function addmessageboard($allow_multiple=true,$expire=0) {
        global $session,$doublepost;
        $doublepost = 0;

        if (!empty($_POST['insertblackboard'][$_GET['boardid']]) && trim($_POST['insertblackboard'][$_GET['boardid']])!='') {
                $message = str_replace('`n','',soap($_POST['insertblackboard'][$_GET['boardid']]));

                if ($allow_multiple) {
                        $sql = "SELECT COUNT(messageid) AS zahl FROM messageboard WHERE boardid='$_GET[boardid]' AND acctid='".$session['user']['acctid']."' AND message='$message'";
                        $result = db_query($sql) or die(db_error(LINK));
                        $row = db_fetch_assoc($result);
                        db_free_result($result);
                        $count = $row['zahl'];
                }
                else {
                        $sql = "DELETE FROM messageboard WHERE boardid='$_GET[boardid]' AND acctid='".$session['user']['acctid']."'";
                        db_query($sql);
                        $count = 0;
                }

                if ($count==0){
                        $sql = 'INSERT INTO messageboard (boardid, acctid, name, message, expire) VALUES ("'.$_GET['boardid'].'","'.$session['user']['acctid'].'","'.$session['user']['name'].'","'.$message.'","'.$expire.'")';
                        db_query($sql) or die(db_error(LINK));
                        return true;
                } else {
                        $doublepost = 1;
                        return false;
                }
        }
        else return false;
}

function formmessageboard($boardid,$buttontext='Ans schwarze Brett',$pretext='Gib deine Nachricht ein:') {
        global $REQUEST_URI;

        // Formularziel vorbereiten
        $req = preg_replace("'&?(c|boardid)=(\w|-)*'","",$REQUEST_URI)."&boardid=".$boardid;
        $req = str_replace("?&","?",$req);
        if (!strpos($req,"?")) $req = str_replace("&","?",$req);

        output("<form action=\"$req\" method='POST'>",true);
        output("`n$pretext`n<input name='insertblackboard[$boardid]' maxlength='250' size='50'>`n",true);
        output("<input type='submit' class='button' value='$buttontext'>",true);
        addnav("",$req);
}

function addcommentary() {
        global $_POST,$session,$REQUEST_URI,$_GET,$doublepost;
        $doublepost=0;
        /*
        // Ab in die setnewday.php damit!
        if ((int)getsetting("expirecontent",180)>0){
                $sql = "DELETE FROM commentary WHERE postdate<'".date("Y-m-d H:i:s",strtotime("-".getsetting("expirecontent",180)." days"))."'";
                db_query($sql);
        }
        */
        $section=$_POST['section'];
        $talkline=$_POST['talkline'];
        if ($_POST[insertcommentary][$section]!==NULL &&
                trim($_POST[insertcommentary][$section])!="") {
                $commentary = str_replace("`n","",soap($_POST[insertcommentary][$section]));
                $y = strlen($commentary);
                for ($x=0;$x<$y;$x++){
                        if (substr($commentary,$x,1)=="`"){
                                $colorcount++;
                                if ($colorcount>=getsetting("maxcolors",10)){
                                        $commentary = substr($commentary,0,$x).preg_replace("'[`].'","",substr($commentary,$x));
                                        $x=$y;
                                }
                                $x++;
                        }
                }
                if (substr($commentary,0,1)!=":" &&
                    substr($commentary,0,2)!="::" &&
                    substr($commentary,0,3)!="/me" &&
                    substr($commentary,0,3)!="/ms" &&
                    substr($commentary,0,3)!="/gm" &&
                    $session['user']['drunkenness']>0) {
                        //drunk people shouldn't talk very straight.
                        $straight = $commentary;
                        $replacements=0;
                        while ($replacements/strlen($straight) < ($session['user']['drunkenness'])/500 ){
                                $slurs = array("a"=>"aa","e"=>"ee","f"=>"ff","h"=>"hh","i"=>"ij","l"=>"ll","m"=>"mm","n"=>"nn","o"=>"oo","r"=>"rr","s"=>"sh","u"=>"uu","v"=>"vv","w"=>"ww","y"=>"yy","z"=>"zz");
                                if (e_rand(0,9)) {
                                        srand(e_rand());
                                        $letter = array_rand($slurs);
                                        $x = strpos(strtolower($commentary),$letter);
                                        if ($x!==false &&
                                                substr($comentary,$x,5)!="*hic*" &&
                                                substr($commentary,max($x-1,0),5)!="*hic*" &&
                                                substr($commentary,max($x-2,0),5)!="*hic*" &&
                                                substr($commentary,max($x-3,0),5)!="*hic*" &&
                                                substr($commentary,max($x-4,0),5)!="*hic*"
                                                ){
                                                if (substr($commentary,$x,1)<>strtolower($letter)) $slurs[$letter] = strtoupper($slurs[$letter]); else $slurs[$letter] = strtolower($slurs[$letter]);
                                                        $commentary = substr($commentary,0,$x).$slurs[$letter].substr($commentary,$x+1);
                                                $replacements++;
                                        }
                                }else{
                                        $x = e_rand(0,strlen($commentary));
                                        if (substr($commentary,$x,5)=="*hic*") {$x+=5; } //output("moved 5 to $x ");
                                        if (substr($commentary,max($x-1,0),5)=="*hic*") {$x+=4; } //output("moved 4 to $x ");
                                        if (substr($commentary,max($x-2,0),5)=="*hic*") {$x+=3; } //output("moved 3 to $x ");
                                        if (substr($commentary,max($x-3,0),5)=="*hic*") {$x+=2; } //output("moved 2 to $x ");
                                        if (substr($commentary,max($x-4,0),5)=="*hic*") {$x+=1; } //output("moved 1 to $x ");
                                        $commentary = substr($commentary,0,$x)."*hic*".substr($commentary,$x);
                                        //output($commentary."`n");
                                        $replacements++;
                                }//end if
                        }//end while
                        //output("$replacements replacements (".($replacements/strlen($straight)).")`n");
                        while (strpos($commentary,"*hic**hic*"))
                                $commentary = str_replace("*hic**hic*","*hic*hic*",$commentary);
                }//end if
                $commentary = preg_replace("'([^[:space:]]{45,45})([^[:space:]])'","\\1 \\2",$commentary);
                if ($session['user']['drunkenness']>50) $talkline = "lallt";

                //mod by aragon
                if (substr($commentary,0,1)==':' || substr($commentary,0,3)=='/me' || substr($commentary,0,3)=='/ms') {
            if (substr($commentary,0,3)=='/me'){
                    $strpos = 3;
            }elseif (substr($commentary,0,3)=='/ms'){
                    $strpos = 3;
            }elseif (substr($commentary,0,2)=='::'){
                    $strpos = 2;
            }else{
                    $strpos = 1;
            }
            $colortag="`".$session['user']['usercolor'];
            if ($session['user']['activatecolor']=='1') $commentary = substr($commentary,0,$strpos).$colortag.substr($commentary,$strpos);
        }
        //end

                if (($talkline!="says" // do an emote if the area has a custom talkline and the user isn't trying to emote already.
                && substr($commentary,0,1)!=":"
                && substr($commentary,0,2)!="::"
                && substr($commentary,0,3)!="/me"
                && substr($commentary,0,3)!="/ms"
                && substr($commentary,0,3)!="/gm")
                || (substr($commentary,0,3)=="/gm"
                && $session['user']['gm']!=1))
                        $commentary = ":`3$talkline: \\\"`#$commentary`3\\\"";
                $sql = "SELECT commentary.comment,commentary.author FROM commentary WHERE section='$section' ORDER BY commentid DESC LIMIT 1";
                $result = db_query($sql) or die(db_error(LINK));
                $row = db_fetch_assoc($result);
                db_free_result($result);
                if ($row[comment]!=$commentary || $row[author]!=$session[user][acctid]){
                  $sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'$section',".$session[user][acctid].",\"$commentary\")";
                        db_query($sql) or die(db_error(LINK));
            return true;
                } else {
                        $doublepost = 1;
                }
        }
        return false;
}

function viewcommentary($section,$message="Kommentar hinzufügen?",$limit=10,$talkline="sagt",$isguard=false) {
        global $_POST,$session,$REQUEST_URI,$SCRIPT_NAME,$_GET, $doublepost;
        $nobios = array("motd.php"=>true);
        if ($nobios[basename($_SERVER['SCRIPT_NAME'])]) $linkbios=false; else $linkbios=true;
        //output("`b".basename($_SERVER['SCRIPT_NAME'])."`b`n");
        if ($doublepost) output("`\$`bDoppelpost?`b`0`n");
        /*
        // Ab in die setnewday.php damit!
        if ((int)getsetting("expirecontent",180)>0){
                $sql = "DELETE FROM commentary WHERE postdate<'".date("Y-m-d H:i:s",strtotime("-".getsetting("expirecontent",180)." days"))."'";
                db_query($sql);
        }
        */
        $com=(int)$_GET[comscroll];
        $commentids = $op = array();
        $i = 0;
        $sql = "SELECT commentary.*,
                                                accounts.name,
                                                accounts.login,
                                                accounts.loggedin,
                                                accounts.location,
                                                accounts.laston,
                                                accounts.invisible
                  FROM commentary
                 INNER JOIN accounts
                    ON accounts.acctid = commentary.author
                 WHERE section = '$section'
                   AND accounts.locked=0
                 ORDER BY commentid DESC
                 LIMIT ".($com*$limit).",$limit";
        $result = db_query($sql) or die(db_error(LINK));
        while ($row = db_fetch_assoc($result)) {
        //$counttoday=0;
        //for ($i=0;$i < db_num_rows($result);$i++){
        //        $row = db_fetch_assoc($result);
                $row[comment]=preg_replace("'[`][^\^123456789!@#$%&QqRr*~?VvGgTtAaZzDdFfEexuy+aAk]'","",$row[comment]);
                $commentids[] = $row[commentid];
/*
                if (date("Y-m-d",strtotime($row[postdate]))==date("Y-m-d")){
//                        if ($row[name]==$session[user][name] && substr($section,0,5)!="house") $counttoday++;
                }
*/
                //$x=0;
                $ft="";
                for ($x=0;strlen($ft)<3 && $x<strlen($row[comment]);$x++){
                        if (substr($row[comment],$x,1)=="`" && strlen($ft)==0) {
                                $x++;
                        }else{
                                $ft.=substr($row[comment],$x,1);
                        }
                }
                $link = "bio.php?char=".rawurlencode($row[login]) . "&ret=".URLEncode($_SERVER['REQUEST_URI']);
                if (substr($ft,0,2)=="::") $ft = substr($ft,0,2);
                elseif (substr($ft,0,1)==":") $ft = substr($ft,0,1);

//ms
                if ($ft=="/ms"){
                        $x = strpos($row[comment],$ft);
                        //if ($x!==false){
                                if (substr($row[name],-2) == "`0") $row[name] = substr($row[name],0,strrpos($row[name],"`")); //`0 am Ende des Namens entfernen, um das angefügte s dem Namen farblich anzupassen
                                if ($linkbios)
                                        $op[] = str_replace("&amp;","&",HTMLEntities(substr($row[comment],0,$x)))
                                        ."`0<a href='$link' style='text-decoration: none'>\n`&$row[name]s`0</a>\n`& "
                                        .str_replace("&amp;","&",HTMLEntities(substr($row[comment],$x+strlen($ft))))
                                                ."`0`n";
                                else
                                        $op[] = str_replace("&amp;","&",HTMLEntities(substr($row[comment],0,$x)))
                                        ."`0\n`&$row[name]s`0\n`& "
                                        .str_replace("&amp;","&",HTMLEntities(substr($row[comment],$x+strlen($ft))))
                                                ."`0`n";
                        //}
                }
//ms

//gm - Spielleiter-Kommentar: Der Name fällt weg
                elseif ($ft=="/gm"){
                        $gmc = 1;
                        $x = strpos($row[comment],$ft);
                        $op[] = str_replace("&amp;","&",HTMLEntities(substr($row[comment],0,$x)))
                                        .str_replace("&amp;","&",HTMLEntities(substr($row[comment],$x+strlen($ft))))
                                                ."`0`n";
                }
//gm

                elseif ($ft=="::" || $ft=="/me" || $ft==":"){
                        $x = strpos($row[comment],$ft);
                        // Was soll die Abfrage? Ist doch implizit durch das vorherige if schon geschehn...
                        //if ($x!==false){
                                if ($linkbios)
                                        $op[] = str_replace("&amp;","&",HTMLEntities(substr($row[comment],0,$x)))
                                        ."`0<a href='$link' style='text-decoration: none'>\n`&$row[name]`0</a>\n`& "
                                        .str_replace("&amp;","&",HTMLEntities(substr($row[comment],$x+strlen($ft))))
                                                ."`0`n";
                                else
                                        $op[] = str_replace("&amp;","&",HTMLEntities(substr($row[comment],0,$x)))
                                        ."`0\n`&$row[name]`0\n`& "
                                        .str_replace("&amp;","&",HTMLEntities(substr($row[comment],$x+strlen($ft))))
                                                ."`0`n";
                        //}
                }
                else
                        if ($linkbios)
                                $op[] = "`0<a href='$link' style='text-decoration: none'>`&$row[name]`0</a>`3 sagt: \"`#"
                                        .str_replace("&amp;","&",HTMLEntities($row[comment]))."`3\"`0`n";
                        else
                                $op[] = "`0`&$row[name]`0`3 sagt: \"`#"
                                    .str_replace("&amp;","&",HTMLEntities($row[comment]))."`3\"`0`n";
                if ($message=="X") $op[$i]="`0($row[section]) ".$op[$i];
                $loggedin=(date("U") - strtotime($row[laston]) < getsetting("LOGINTIMEOUT",900) && $row[loggedin] && $row[location]==0);
                #mod by ang37@arcor.de for invisible users
                if ($row['postdate']>=$session['user']['recentcomments']){
                        if ($gmc == 1){
                                $op[$i]=("<img src='images/new-online.gif' alt='&gt;' width='3' height='5' align='absmiddle'> ").$op[$i];
                                $gmc = 0;
                        }elseif ($row[invisible]=='0'){
                            $op[$i]=($loggedin?"<img src='images/new-online.gif' alt='&gt;' width='3' height='5' align='absmiddle'>":"<img src='images/new.gif' alt='&gt;' width='3' height='5' align='absmiddle'> ").$op[$i];
                        }else{
                                $op[$i]=("<img src='images/new.gif' alt='&gt;' width='3' height='5' align='absmiddle'> ").$op[$i];
                        }
                }
                #end of mod
                addnav("",$link);
                $i++;
        }
        $outputcomments=array();
        $sect="x";
        for ($i--;$i>=0;$i--){
                $out="";
                if ($message=="X"){
                        if ($session[user][superuser]>=3) {
                                $out.="`0[ <a href='superuser.php?op=commentdelete&commentid=$commentids[$i]&return=".URLEncode($_SERVER['REQUEST_URI'])."'>Löschen</a> ]&nbsp;";
                                addnav("","superuser.php?op=commentdelete&commentid=$commentids[$i]&return=".URLEncode($_SERVER['REQUEST_URI']));
                                $matches=array();
                                preg_match("/[(][^)]*[)]/",$op[$i],$matches);
                                $sect=$matches[0];
                        }
                        elseif ($isguard) {
                                if (count($_GET)>0) {
                                        $url = $REQUEST_URI.'&msgop=commentdelete&commentid='.$commentids[$i];
                                }
                                else $url = $SCRIPT_NAME.'?msgop=commentdelete&commentid='.$commentids[$i];
                                $out.="`0[ <a href='$url'>Melden</a> ]&nbsp;";
                                addnav("",$url);
                                $matches=array();
                                preg_match("/[(][^)]*[)]/",$op[$i],$matches);
                                $sect=$matches[0];
                        }
                }
                //output($op[$i],true);
                $out.=$op[$i];
                if (!is_array($outputcomments[$sect])) $outputcomments[$sect]=array();
                array_push($outputcomments[$sect],$out);
        }
        ksort($outputcomments);
        reset($outputcomments);
        while (list($sec,$v)=each($outputcomments)){
                if ($sec!="x") output("`n`b$sec`b`n");
                /*
                reset($v);
                while (list($key,$val)=each($v)){
                        output($val,true);
                }
                */
                output(implode('',$v),true);
        }

        if ($session[user][loggedin]) {
                if (substr($session[user][name],-2) == "`0") $jsname = substr($session[user][name],0,strrpos($session[user][name],"`"));
                else $jsname= $session[user][name];
                $length=getsetting("maxpostinglength",255);
                output("<script language='JavaScript'>
                        function previewtext(t){
                                if (t.substr(0,3)=='/ms') { var beg = \"Vorschau:<br><span class=\'colLtWhite\'>".$jsname."s \"; }
                                else if (t.substr(0,3)=='/gm' && ".$session['user']['gm']."==1) { var beg = \"Vorschau:<br><span class=\'colLtWhite\'>\"; }
                                else { var beg = \"Vorschau:<br><span class=\'colLtWhite\'>".$session[user][name]." \"; }
                        var out = '';
                        var end = '</span>';
                        var x=0;
                        var y='';
                        var z='';
                        var ac='".$session['user']['activatecolor']."';
                        var c='".$session['user']['usercolor']."';
                        var sc='';
                        if (ac=='1'){
                                if (c=='1'){
                                    sc += '</span><span class=\'colDkBlue\'>';
                            }else if (c=='2'){
                                sc += '</span><span class=\'colDkGreen\'>';
                            }else if (c=='3'){
                                sc += '</span><span class=\'colDkCyan\'>';
                            }else if (c=='4'){
                                sc += '</span><span class=\'colDkRed\'>';
                            }else if (c=='5'){
                                sc += '</span><span class=\'colDkMagenta\'>';
                            }else if (c=='6'){
                                sc += '</span><span class=\'colDkYellow\'>';
                            }else if (c=='7'){
                                sc += '</span><span class=\'colDkWhite\'>';
                            }else if (c=='8'){
                                sc += '</span><span class=\'colLime\'>';
                            }else if (c=='9'){
                                sc += '</span><span class=\'colBlue\'>';
                            }else if (c=='Q'){
                                sc += '</span><span class=\'colDkOrange\'>';
                            }else if (c=='V'){
                                sc += '</span><span class=\'colBlueViolet\'>';
                            }else if (c=='v'){
                                sc += '</span><span class=\'coliceviolet\'>';
                            }else if (c=='e'){
                                sc += '</span><span class=\'colBrown\'>';
                            }else if (c=='E'){
                                sc += '</span><span class=\'colDustyRose\'>';
                            }else if (c=='c'){
                                sc += '</span><span class=\'colOrchid\'>';
                            }else if (c=='c'){
                                sc += '</span><span class=\'colMdOrchid\'>';
                            }else if (c=='x'){
                                sc += '</span><span class=\'colCoral\'>';
                            }else if (c=='q'){
                                sc += '</span><span class=\'colOrange\'>';
                            }else if (c=='u'){
                                sc += '</span><span class=\'colCopper\'>';
                            }else if (c=='f'){
                                sc += '</span><span class=\'colFirebrick\'>';
                            }else if (c=='F'){
                                sc += '</span><span class=\'colRed\'>';
                            }else if (c=='d'){
                                sc += '</span><span class=\'colOrangeRed\'>';
                            }else if (c=='D'){
                                sc += '</span><span class=\'colVioletRed\'>';
                            }else if (c=='y'){
                                sc += '</span><span class=\'colSummerSky\'>';
                            }else if (c=='G'){
                                sc += '</span><span class=\'colYellowGreen\'>';
                            }else if (c=='T'){
                                sc += '</span><span class=\'colDkBrown\'>';
                            }else if (c=='t'){
                                sc += '</span><span class=\'colLtBrown\'>';
                            }else if (c=='r'){
                                sc += '</span><span class=\'colRose\'>';
                            }else if (c=='R'){
                                sc += '</span><span class=\'colPlum\'>';
                            }else if (c=='g'){
                                sc += '</span><span class=\'colXLtGreen\'>';
                            }else if (c=='!'){
                                sc += '</span><span class=\'colLtBlue\'>';
                            }else if (c=='@'){
                                sc += '</span><span class=\'colLtGreen\'>';
                            }else if (c==''){
                                sc += '</span><span class=\'colLtCyan\'>';
                            }else if (c=='$'){
                                sc += '</span><span class=\'colLtRed\'>';
                            }else if (c=='%'){
                                sc += '</span><span class=\'colLtMagenta\'>';
                            }else if (c=='^'){
                                sc += '</span><span class=\'colLtYellow\'>';
                            }else if (c=='&'){
                                sc += '</span><span class=\'colLtWhite\'>';
                            }else if (c=='Q'){
                                sc += '</span><span class=\'colLtOrange\'>';
                            }else if (c=='+'){
                                sc += '</span><span class=\'colLtBlack\'>';
                            }else if (c=='~'){
                                sc += '</span><span class=\'colLtBlack\'>';
                            }else if (c=='#'){
                                sc += '</span><span class=\'colLtCyan\'>';
                            }else if (c=='a'){
                                    sc += '</span><span class=\'colLtGrey\'>';
                            }else if (c=='A'){
                                    sc += '</span><span class=\'colDkGrey\'>';
                            }else if (c=='k'){
                                    sc += '</span><span class=\'colXDkGreen\'>';
                            }else{
                                        sc += '</span><span class=\'colLtWhite\'>';
                                   }
                        }
                        if (t.substr(0,2)=='::'){
                            x=2;
                            if (sc=='') out += '</span><span class=\'colLtWhite\'>';
                        }else if (t.substr(0,1)==':'){
                            x=1;
                            if (sc=='') out += '</span><span class=\'colLtWhite\'>';
                        }else if (t.substr(0,3)=='/me' || t.substr(0,3)=='/ms' || (t.substr(0,3)=='/gm' && ".$session['user']['gm']."==1)){
                            x=3;
                            if (sc=='') out += '</span><span class=\'colLtWhite\'>';
                        }else{
                            if (sc=='') out += '</span><span class=\'colDkCyan\'>sagt: \"</span><span class=\'colLtCyan\'>';
                            if (sc!='') out += 'sagt: \"';
                            if (sc=='') end += '</span><span class=\'colDkCyan\'>\"';
                            if (sc!='') end += '\"';
                        }
                        for (; x < t.length; x++){
                            y = t.substr(x,1);
                            if (y=='<'){
                                out += '&lt;';
                                continue;
                            }else if(y=='>'){
                                out += '&gt;';
                                continue;
                            }else if (y=='`'){
                                if (x < t.length-1){
                                    z = t.substr(x+1,1);
                                if (z=='0'){
                                        out += '</span>';
                                }else if (z=='1'){
                                        out += '</span><span class=\'colDkBlue\'>';
                                    }else if (z=='2'){
                                        out += '</span><span class=\'colDkGreen\'>';
                                    }else if (z=='3'){
                                        out += '</span><span class=\'colDkCyan\'>';
                                    }else if (z=='4'){
                                        out += '</span><span class=\'colDkRed\'>';
                                    }else if (z=='5'){
                                        out += '</span><span class=\'colDkMagenta\'>';
                                    }else if (z=='6'){
                                        out += '</span><span class=\'colDkYellow\'>';
                                    }else if (z=='7'){
                                        out += '</span><span class=\'colDkWhite\'>';
                                    }else if (z=='8'){
                                        out += '</span><span class=\'colLime\'>';
                                    }else if (z=='9'){
                                        out += '</span><span class=\'colBlue\'>';
                                    }else if (z=='Q'){
                                        out += '</span><span class=\'colDkOrange\'>';
                                    }else if (z=='V'){
                                        out += '</span><span class=\'colBlueViolet\'>';
                                    }else if (z=='v'){
                                        out += '</span><span class=\'coliceviolet\'>';
                                    }else if (z=='e'){
                                        out += '</span><span class=\'colBrown\'>';
                                    }else if (z=='E'){
                                        out += '</span><span class=\'colDustyRose\'>';
                                    }else if (z=='z'){
                                        out += '</span><span class=\'colOrchid\'>';
                                    }else if (z=='Z'){
                                        out += '</span><span class=\'colMdOrchid\'>';
                                    }else if (z=='x'){
                                        out += '</span><span class=\'colCoral\'>';
                                    }else if (z=='q'){
                                        out += '</span><span class=\'colOrange\'>';
                                    }else if (z=='u'){
                                        out += '</span><span class=\'colCopper\'>';
                                    }else if (z=='f'){
                                        out += '</span><span class=\'colFirebrick\'>';
                                    }else if (z=='F'){
                                        out += '</span><span class=\'colRed\'>';
                                    }else if (z=='d'){
                                        out += '</span><span class=\'colOrangeRed\'>';
                                    }else if (z=='D'){
                                        out += '</span><span class=\'colVioletRed\'>';
                                    }else if (z=='y'){
                                        out += '</span><span class=\'colSummerSky\'>';
                                    }else if (z=='G'){
                                        out += '</span><span class=\'colYellowGreen\'>';
                                    }else if (z=='T'){
                                        out += '</span><span class=\'colDkBrown\'>';
                                    }else if (z=='t'){
                                        out += '</span><span class=\'colLtBrown\'>';
                                    }else if (z=='r'){
                                        out += '</span><span class=\'colRose\'>';
                                    }else if (z=='R'){
                                        out += '</span><span class=\'colPlum\'>';
                                    }else if (z=='g'){
                                        out += '</span><span class=\'colXLtGreen\'>';
                                    }else if (z=='!'){
                                        out += '</span><span class=\'colLtBlue\'>';
                                    }else if (z=='@'){
                                        out += '</span><span class=\'colLtGreen\'>';
                                    }else if (z==''){
                                        out += '</span><span class=\'colLtCyan\'>';
                                    }else if (z=='$'){
                                        out += '</span><span class=\'colLtRed\'>';
                                    }else if (z=='%'){
                                        out += '</span><span class=\'colLtMagenta\'>';
                                    }else if (z=='^'){
                                        out += '</span><span class=\'colLtYellow\'>';
                                    }else if (z=='&'){
                                        out += '</span><span class=\'colLtWhite\'>';
                                    }else if (z=='Q'){
                                        out += '</span><span class=\'colLtOrange\'>';
                                    }else if (z=='+'){
                                        out += '</span><span class=\'colLtBlack\'>';
                                    }else if (z=='~'){
                                        out += '</span><span class=\'colLtBlack\'>';
                                    }else if (z=='#'){
                                        out += '</span><span class=\'colLtCyan\'>';
                                    }else if (z=='a'){
                                        out += '</span><span class=\'colLtGrey\'>';
                                    }else if (z=='A'){
                                        out += '</span><span class=\'colDkGrey\'>';
                                    }else if (z=='k'){
                                        out += '</span><span class=\'colXDkGreen\'>';
                                    }
                                    x++;
                                }
                            }else{
                                out += y;
                            }
                        }
                        document.getElementById(\"previewtext\").innerHTML=beg+sc+out+end+'<br/>';
                    }
                    </script>",true);

                        if ($message!="X"){
                                if ($talkline!="says") $tll = strlen($talkline)+11; else $tll=0;
                                if ($session['user']['preview']==1){
                                    output("<form action=\"$REQUEST_URI\" method='POST'>
                    `@$message`n
                    <input name='insertcommentary[$section]' id='commentary' onKeyUp='previewtext(document.getElementById(\"commentary\").value);' size='40' maxlength='".($length-$tll)."'>
                    <input type='hidden' name='talkline' value='$talkline'>
                    <input type='hidden' name='section' value='$section'>
                    <input type='submit' class='button' value='Hinzufügen'><br>
                    <div id=\"previewtext\"></div><br>
                    </form>",true);
                }else{
                        output("<form action=\"$REQUEST_URI\" method='POST'>
                    `@$message`n
                    <input name='insertcommentary[$section]' size='40' maxlength='".($length-$tll)."'>
                    <input type='hidden' name='talkline' value='$talkline'>
                    <input type='hidden' name='section' value='$section'>
                    <input type='submit' class='button' value='Hinzufügen'><br>
                    </form>",true);
                }
                                addnav("",$REQUEST_URI);
                        }
        }
        if (db_num_rows($result)>=$limit){
                $req = preg_replace("'[&]?c(omscroll)?=([[:digit:]-])*'","",$REQUEST_URI)."&comscroll=".($com+1);
                        //$req = substr($REQUEST_URI,0,strpos($REQUEST_URI,"c="))."&amp;c=$_GET[c]"."&comscroll=".($com+1);
                        $req = str_replace("?&","?",$req);
                        if (!strpos($req,"?")) $req = str_replace("&","?",$req);
                        output("<a href=\"$req\">&lt;&lt; Vorherige</a>",true);
                        addnav("",$req);
        }
        $req = preg_replace("'[&]?c(omscroll)?=([[:digit:]]|-)*'","",$REQUEST_URI)."&comscroll=0";
        //$req = substr($REQUEST_URI,0,strpos($REQUEST_URI,"c="))."&amp;c=$_GET[c]"."&comscroll=".($com-1);
        $req = str_replace("?&","?",$req);
        if (!strpos($req,"?")) $req = str_replace("&","?",$req);
        output("&nbsp;<a href=\"$req\">Aktualisieren</a>&nbsp;",true);
        addnav("",$req);
        if ($com>0){
                $req = preg_replace("'[&]?c(omscroll)?=([[:digit:]]|-)*'","",$REQUEST_URI)."&comscroll=".($com-1);
                //$req = substr($REQUEST_URI,0,strpos($REQUEST_URI,"c="))."&amp;c=$_GET[c]"."&comscroll=".($com-1);
                $req = str_replace("?&","?",$req);
                if (!strpos($req,"?")) $req = str_replace("&","?",$req);
                output(" <a href=\"$req\">Nächste &gt;&gt;</a>",true);
                addnav("",$req);
        }
        db_free_result($result);
}

function dhms($secs,$dec=false){
        if ($dec===false) $secs=round($secs,0);
        return (int)($secs/86400)."d".(int)($secs/3600%24)."h".(int)($secs/60%60)."m".($secs%60).($dec?substr($secs-(int)$secs,1):"")."s";
}

function getmount($horse=0) {
        $sql = "SELECT * FROM mounts WHERE mountid='$horse'";
        $result = db_query($sql);
        if (db_num_rows($result)>0){
                return db_fetch_assoc($result);
        }else{
                return array();
        }
}

function debuglog($message,$target=0){
        global $session;
        $sql = "INSERT INTO debuglog VALUES(0,now(),{$session['user']['acctid']},$target,'".addslashes($message)."')";
        db_query($sql);
}
// exp bar mod coded by: dvd871  modifications by: anpera

function expbar() {
        global $session;
        $exparray=array(1=>100,400,1002,1912,3140,4707,6641,8985,11795,15143,19121,23840,29437,36071,43930,55000);
        $expprodk=array(1=>10,22,37,57,80,107,138,174,214,259,309,363,424,490,563,635);
        while (list($key,$val)=each($exparray)){
                $exparray[$key]= round(
                //$val + ($session['user']['dragonkills']/4) * $key * 100
                $val + $session['user']['dragonkills'] * $expprodk[$key]
                ,0);
        }
        $exp = $session[user][experience]-$exparray[$session[user][level]-1];
        $req=$exparray[$session[user][level]]-$exparray[$session[user][level]-1];
        $u="<font face=\"verdana\" size=2>".$session[user][experience]."<br>".grafbar($req,$exp)."</font>";
        return($u);
}

// end exp bar mod

/* exp bar mod coded by dvd871 modifications by: angel*/

function totbar() {
        global $session;
        $exp=$session[user][deathpower];
        $req=100;
        $u="<font face=\"verdana\" size=2>".$session[user][deathpower]."<br>".grafbar($req,$exp)."</font>";
        return($u);
}

/*end of tot bar mod*/

function grafbar($full,$left,$width=70,$height=5) {
        $col2="#000000";
        if ($left<=0){
                $col="#000000";
        }else if ($left<$full/4){
                $col="#FF0000";
        }else if ($left<$full/2){
                $col="yellow";
        }else if ($left>=$full){
                $col="#00AA00";
                $col2="#00AA00";
        }else{
                $col="#00FF00";
        }
        if($full==0) $full = 1;
        $u = "<table cellspacing=\"0\" style=\"border: solid 1px #000000\" width=\"$width\" height=\"$height\"><tr><td width=\"" . ($left / $full * 100) . "%\" bgcolor=\"$col\"></td><td width=\"".(100-($left / $full * 100)) ."%\" bgcolor=\"$col2\"></td></tr></table>";
        return($u);
}

function setrace($unset=false) {
        global $session;
        $sql2 = "SELECT * FROM race WHERE name='{$session[user][race]}' LIMIT 1";
        $result2 = db_query($sql2);
        $row2 = db_fetch_assoc($result2);
        $bonus = unserialize($row['bonus']);
        $bonuslp=(int)$bonus['lp'];
        $bonusdef=(int)$bonus['def'];
        $bonusatk=(int)$bonus['atk'];
        if ($unset){
           $bonuslp=(-1)*$bonuslp;
           $bonusdef=(-1)*$bonusdef;
           $bonusatk=(-1)*$bonusatk;
        }
        $session['user']['maxhitpoints']+=$bonuslp;
        $session['user']['defence']+=$bonusdef;
        $session['user']['attack']+=$bonusatk;
        /*foreach ($session['user']['race']['bonus'] AS $tihsbonus=>$bonusval) {
                if ($bonusval==0) continue;
                // Wenn Rasse gelöscht werden soll, dementsprechend setzen
                if ($unset) $bonusval = -(int)$bonusval;
                switch ($tihsbonus) {
                        case 'atk': $session['user']['attack'] += $bonusval; break;
                        case 'def': $session['user']['defence'] += $bonusval; break;
                        case 'lp': $session['user']['maxhitpoints'] += $bonusval; break;
                }
        }*/

}

function changerace($newrace) {
        global $session,$races;

        setrace(true);
        // Neue Rasse setzen
        $session['user']['race'] = 'Unbekannt';
        // Neue Boni anrechnen
        setrace();
}

function deleteuser($userid,$stdreason,$reason='',$savereason=0,$addnews=true) {
        $sql = "SELECT name,emailaddress from accounts WHERE acctid='$userid'";
        $res = db_query($sql);
        // inventar und haus löschen und partner und ei freigeben
        if ($userid==getsetting("hasegg",0)) savesetting("hasegg",stripslashes(0));
            $sql = "UPDATE items SET owner=0 WHERE owner=$userid";
        db_query($sql);
        $sql = "UPDATE houses SET owner=0,status=3 WHERE owner=$userid AND status=1";
        db_query($sql);
        $sql = "UPDATE houses SET owner=0,status=4 WHERE owner=$userid AND status=0";
        db_query($sql);
        $sql = "UPDATE accounts SET charisma=0,marriedto=0 WHERE marriedto=$userid";
        db_query($sql);
        $sql = "DELETE FROM pvp WHERE acctid2=$userid OR acctid1=$userid";
        db_query($sql) or die(db_error(LINK));
        $sql = "DELETE FROM bounties WHERE acctid='$userid'";
        db_query($sql);
        savebackup($userid);
        $sql = "DELETE FROM accounts_text WHERE acctid='$userid'";
        db_query($sql);
        $sql = "DELETE FROM accounts WHERE acctid='$userid'";
        db_query($sql);
        if ($row = db_fetch_assoc($res)) {
                if ($addnews) addnews("`#{$row['name']} wurde von den Göttern aufgelöst.",0);

                if ($row['emailaddress']!='' && ($reason!='' || $stdreason > 0)) {
                        if ($reason=='') {
                                $sql = 'SELECT reason FROM deluser_reasons WHERE reasonid="'.(int)$stdreason.'"';
                                $rs = db_fetch_assoc(db_query($sql));
                                $reason = $rs['reason'];
                        }
                        else {
                                if (!empty($savereason)) {
                                        $sql = 'INSERT INTO deluser_reasons (reason) VALUES ("'.$reason.'")';
                                        db_query($sql);
                                }
                                $reason = stripslashes($reason);
                        }
                        mail(
                                $row['emailaddress'],
                                "LoGD Account Löschung",
                                "Dein LoGD-Account wurde von einem Admin aus folgendem Grund gelöscht:\n".$reason,
                                "From: ".getsetting("gameadminemail","postmaster@localhost.com")
                        );
                }
        }
}

function adminlog() {
        global $session, $SCRIPT_NAME;
        $sql = 'INSERT INTO adminlog VALUES ("",NOW(),"'.$SCRIPT_NAME.'","'.addslashes(serialize($_GET)).'","'.addslashes(serialize($_POST)).'","'.$_SERVER['REMOTE_ADDR'].'",'.$session['user']['acctid'].',"'.$_COOKIE['lgi'].'")';
        db_query($sql);
}

if (file_exists("dbconnect.php")){
        require_once "dbconnect.php";
}else{
        echo "You must edit the file named \"dbconnect.php.dist,\" and provide the requested information, then save it as \"dbconnect.php\"".
        exit();
}

$link = db_pconnect($DB_HOST, $DB_USER, $DB_PASS) or die (db_error($link));
db_select_db ($DB_NAME) or die (db_error($link));
define("LINK",$link);


session_register("session");
function register_global(&$var){
        @reset($var);
        while (list($key,$val)=@each($var)){
                global $$key;
                $$key = $val;
        }
        @reset($var);
}
$session =& $_SESSION['session'];
//echo nl2br(htmlentities(output_array($session)));
//register_global($_SESSION);
register_global($_SERVER);
//die("Work in progress // wir arbeiten daran, dass Pandea bald wieder läuft <3");

if (strtotime("-".getsetting("LOGINTIMEOUT",900)." seconds") > $session['lasthit'] && $session['lasthit']>0 && $session[loggedin]){
        //force the abandoning of the session when the user should have been sent to the fields.
        //echo "Session abandon:".(strtotime("now")-$session[lasthit]);

        $session=array();
        $session['message'].="`nDeine Session ist abgelaufen!`n";
}
$session[lasthit]=strtotime("now");
//die("test 3");

$revertsession=$session;
//die("test 4");

if ($PATH_INFO != "") {
        $SCRIPT_NAME=$PATH_INFO;
        $REQUEST_URI="";
}
if ($REQUEST_URI==""){
        //necessary for some IIS installations (CGI in particular)
        if (is_array($_GET) && count($_GET)>0){
                $REQUEST_URI=$SCRIPT_NAME."?";
                reset($_GET);
                $i=0;
                while (list($key,$val)=each($_GET)){
                        if ($i>0) $REQUEST_URI.="&";
                        $REQUEST_URI.="$key=".URLEncode($val);
                        $i++;
                }
        }else{
                $REQUEST_URI=$SCRIPT_NAME;
        }
        $_SERVER['REQUEST_URI'] = $REQUEST_URI;
}
$SCRIPT_NAME=substr($SCRIPT_NAME,strrpos($SCRIPT_NAME,"/")+1);
if (strpos($REQUEST_URI,"?")){
        $REQUEST_URI=$SCRIPT_NAME.substr($REQUEST_URI,strpos($REQUEST_URI,"?"));
}else{
        $REQUEST_URI=$SCRIPT_NAME;
}

$allowanonymous=array("index.php"=>true,"login.php"=>true,"team.php"=>true,"create.php"=>true,"about.php"=>true,"list.php"=>true,"petition.php"=>true,"connector.php"=>true,"logdnet.php"=>true,"referral.php"=>true,"news.php"=>true,"motd.php"=>true,"topwebvote.php"=>true,"source.php"=>true,"creaturelist.php"=>true);
$allownonnav = array("badnav.php"=>true,"motd.php"=>true,"petition.php"=>true,"mail.php"=>true,"source.php"=>true,"topwebvote.php"=>true,"chat.php"=>true,"creaturelist.php"=>true);
if ($session[loggedin]){
        $sql = "SELECT * FROM accounts WHERE acctid = '".$session[user][acctid]."'";
        $result = db_query($sql);
        if (db_num_rows($result)==1){
                $session[user]=db_fetch_assoc($result);
                $session[output]=gettexts('output');
                $session['dragonpoints']=unserialize(gettexts('dragonpoints'));
                $session['prefs']=unserialize(gettexts('prefs'));
                if (!is_array($session['dragonpoints'])) $session['dragonpoints']=array();
                if (is_array(unserialize(gettexts('allowednavs')))){
                        $session[allowednavs]=unserialize(gettexts('allowednavs'));
                }else{
                        //depreciated, left only for legacy support.
                        $session[allowednavs]=createarray(gettexts('allowednavs'));
                }
                if (($SCRIPT_NAME != "jail.php") && ($session[user][jailtime] > 0) && ($SCRIPT_NAME != "newday.php") && ($SCRIPT_NAME != "mail.php") && ($SCRIPT_NAME != "motd.php") && ($SCRIPT_NAME != "chat.php") && ($SCRIPT_NAME != "login.php")) {
                        redirect("jail.php");
                }
                if (($SCRIPT_NAME != "folter.php") && ($session[user][folter] > 0) && ($SCRIPT_NAME != "newday.php") && ($SCRIPT_NAME != "mail.php") && ($SCRIPT_NAME != "motd.php") && ($SCRIPT_NAME != "chat.php") && ($SCRIPT_NAME != "login.php")) {
                        redirect("folter.php");
                }
                if (!$session[user][loggedin] || (0 && (date("U") - strtotime($session[user][laston])) > getsetting("LOGINTIMEOUT",900)) ){
                        $session=array();
                        redirect("index.php?op=timeout","Account ist nicht eingeloggt, aber die Session denkt, er ist es.");
                }
        }else{
                $session=array();
                $session[message]="`4Fehler! Dein Login war falsch.`0";
                redirect("index.php","Account verschwunden!");
        }
        db_free_result($result);
        if ($session[allowednavs][$REQUEST_URI] && !$allownonnav[$SCRIPT_NAME]){
                $session[allowednavs]=array();
        }else{
                if (!$allownonnav[$SCRIPT_NAME]){
                        redirect("badnav.php","Navigation auf $REQUEST_URI nicht erlaubt");
                }
        }
}else{
        //if ($SCRIPT_NAME!="index.php" && $SCRIPT_NAME!="login.php" && $SCRIPT_NAME!="create.php" && $SCRIPT_NAME!="about.php"){
        if (!$allowanonymous[$SCRIPT_NAME]){
                $session['message']="Du bist nicht eingeloggt. Wahrscheinlich ist deine Sessionzeit abgelaufen.";
                redirect("index.php?op=timeout","Not logged in: $REQUEST_URI");
        }
}
//if ($session[user][loggedin]!=true && $SCRIPT_NAME!="index.php" && $SCRIPT_NAME!="login.php" && $SCRIPT_NAME!="create.php" && $SCRIPT_NAME!="about.php"){
if ($session[user][loggedin]!=true && !$allowanonymous[$SCRIPT_NAME]){
        redirect("login.php?op=logout");
}

$session[counter]++;
$nokeeprestore=array("newday.php"=>1,"badnav.php"=>1,"motd.php"=>1,"mail.php"=>1,"petition.php"=>1,"chat.php"=>1);
if (!$nokeeprestore[$SCRIPT_NAME]) { //strpos($REQUEST_URI,"newday.php")===false && strpos($REQUEST_URI,"badnav.php")===false && strpos($REQUEST_URI,"motd.php")===false && strpos($REQUEST_URI,"mail.php")===false
  $session[user][restorepage]=$REQUEST_URI;
}else{

}

if ($session['user']['hitpoints']>0){
        $session['user']['alive']=true;
}else{
        $session['user']['alive']=false;
}

$session[bufflist]=unserialize(gettexts('bufflist'));
if (!is_array($session[bufflist])) $session[bufflist]=array();
$session[user][lastip]=$REMOTE_ADDR;
if (strlen($_COOKIE[lgi])<32){
        if (strlen($session[user][uniqueid])<32){
                $u=md5(microtime());
                setcookie("lgi",$u,strtotime("+365 days"));
                $_COOKIE['lgi']=$u;
                $session[user][uniqueid]=$u;
        }else{
                setcookie("lgi",$session[user][uniqueid],strtotime("+365 days"));
        }
}else{
        $session[user][uniqueid]=$_COOKIE[lgi];
}

/*
$url = "http://".$_SERVER['SERVER_NAME'].dirname($_SERVER['REQUEST_URI']);
$url = substr($url,0,strlen($url)-1);

if (substr($_SERVER['HTTP_REFERER'],0,strlen($url))==$url || $_SERVER['HTTP_REFERER']==""){

}else{
        $sql = "SELECT * FROM referers WHERE uri='{$_SERVER['HTTP_REFERER']}'";
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        db_free_result($result);
        $site = str_replace("http://","",$_SERVER['HTTP_REFERER']);
        if (strpos($site,"/"))
                $site = substr($site,0,strpos($site,"/"));
        if ($row['refererid']>""){
                $sql = "UPDATE referers SET count=count+1,last=now(),site='".addslashes($site)."' WHERE refererid='{$row['refererid']}'";
        }else{
                $sql = "INSERT INTO referers (uri,count,last,site) VALUES ('{$_SERVER['HTTP_REFERER']}',1,now(),'".addslashes($site)."')";
        }
        db_query($sql);
}
*/

if ($_COOKIE['template']!="") $templatename=$_COOKIE['template'];
if ($templatename=="" || !file_exists("templates/$templatename")) $templatename="yarbrough.htm";
$template = loadtemplate($templatename);
//tags that must appear in the header
$templatetags=array("title","headscript","script");
foreach ($templatetags AS $val) {
        if (strpos($template['header'],"{".$val."}")===false) $templatemessage.="You do not have {".$val."} defined in your header\n";
}
//tags that must appear in the footer
$templatetags=array();
foreach ($templatetags AS $val) {
        if (strpos($template['footer'],"{".$val."}")===false) $templatemessage.="You do not have {".$val."} defined in your footer\n";
}
//tags that may appear anywhere but must appear
$templatetags=array("nav","stats","petition","motd","mail","paypal","copyright","source");
foreach ($templatetags AS $val) {
        if (strpos($template['header'],"{".$val."}")===false && strpos($template['footer'],"{".$val."}")===false) $templatemessage.="You do not have {".$val."} defined in either your header or footer\n";
}

if ($templatemessage!=""){
        echo "<b>Du hast einen oder mehrere Fehler in deinem Template!</b><br>".nl2br($templatemessage);
        $template=loadtemplate("yarbrough.htm");
}

//$races=array(1=>"Troll",2=>"Elf",3=>"Mensch",4=>"Zwerg",5=>"Echse",0=>"Unbekannt",50=>"Hoverschaf");

/*old version
$races = array();
$sql = 'SELECT * FROM races';
$result = db_query($sql);
while ($row = db_fetch_assoc($result)) {
        $row['buffs'] = unserialize($row['buffs']);
        $races[$row['raceid']] = $row;
}
$startrace = $races[1];
if (isset($session['user']['race'])) $session['user']['race'] = $races[$session['user']['race']];
*/

$logd_version = "0.9.7+jt ext (GER) Pandea Island Edition";
$session['user']['laston']=date("Y-m-d H:i:s");

$playermount = getmount($session['user']['hashorse']);

$titles = array(
/*
0=>array("Bauernjunge", "Bauernmädchen"),
1=>array("Knecht", "Magd"),
2=>array("Bauer", "Bäuerin"),
3=>array("Grossbauer", "Grossbäuerin"),
4=>array("Händler", "Händlerin"),
5=>array("Großhändler", "Großhändlerin"),
6=>array("Gutshofverwalter", "Gutshofverwalterin"),
7=>array("Gutsherr", "Gutsherrin"),
8=>array("Verwalter", "Verwalterin"),
9=>array("Bürger", "Bürgerin"),
10=>array("Freibeuter", "Freibeuterin"),
11=>array("Abenteurer", "Abenteurerin"),
12=>array("Schatzsucher", "Schatzsucherin"),
13=>array("Pirat", "Piratin"),
14=>array("Skipper", "Skipperin"),
15=>array("Anwärter", "Anwärterin"),
16=>array("Rekrut", "Rekrutin"),
17=>array("Legionär", "Legionärin"),
18=>array("Centurio", "Centurioness"),
19=>array("Söldner", "Söldnerin"),
20=>array("Waffenmeister", "Waffenmeisterin"),
21=>array("Gladiator", "Gladiatorin"),
22=>array("Drachentöter","Drachentöterin"),
23=>array("Veteran", "Veteranin"),
24=>array("Ratsherr", "Ratsfrau"),
25=>array("Bürgermeister", "Bürgermeisterin"),
26=>array("Oberbürgermeister", "Oberbürgermeisterin"),
27=>array("Vorkoster", "Vorkosterin"),
28=>array("Höfling", "Hofdame"),
29=>array("Edler", "Edle"),
30=>array("Ritter", "Ritterin"),
31=>array("Junker", "Junkerin"),
32=>array("Freiherr", "Freifrau"),
33=>array("Lehensherr", "Lehensherrin"),
34=>array("Gouverneur", "Gouverneurin"),
35=>array("Baron", "Baroness"),
36=>array("Fürst", "Fürstin"),
37=>array("Grossfürst", "Grossfürstin"),
38=>array("Herzog", "Herzogin"),
39=>array("Graf", "Gräfin"),
40=>array("Prinz", "Prinzessin"),
41=>array("Kronprinz", "Kronprinzessin"),
42=>array("Novize", "Novizin"),
43=>array("Ordensbruder", "Ordensschwester"),
44=>array("Abt", "Äbtissin"),
45=>array("Priester", "Priesterin"),
46=>array("Hohepriester", "Hohepriesterin"),
47=>array("Patriarch", "Matriarch"),
48=>array("Bischof", "Bischöfin"),
49=>array("Erzbischof", "Erzbischöfin"),
50=>array("Kardinal", "Kardinälin"),
51=>array("Papst", "Päpstin"),
52=>array("Seeliger", "Seelige"),
53=>array("Heiliger", "Heilige"),
54=>array("Engel", "Engel"),
55=>array("Erzengel", "Erzengel"),
56=>array("Mächtiger", "Mächtige"),
57=>array("Cherub", "Cherub"),
58=>array("Seraph", "Seraph"),
59=>array("Wasserelementar", "Wasserelementar"),
60=>array("Luftelementar", "Luftelementar"),
61=>array("Feuerelementar", "Feuerelementar"),
62=>array("Erdelementar", "Erdelementar"),
63=>array("Halbgott", "Halbgöttin"),
64=>array("Kindgott", "Kindgöttin"),
65=>array("Schutzgott", "Schutzgöttin"),
66=>array("Liebesgott", "Liebesgöttin"),
67=>array("Jagdgott", "Jagdgöttin"),
68=>array("Rachegott", "Rachegöttin"),
69=>array("Kriegsgott", "Kriegssgöttin"),
70=>array("Allmächtiger","Allmächtige"),
*/
0=>array("Bauernjunge","Bauernmädchen"),
1=>array("Knecht","Magd"),
2=>array("Freiknecht","Freimagd"),
3=>array("Bauer","Bäuerin"),
4=>array("Grossbauer","Grossbäuerin"),
5=>array("Verwalter","Verwalterin"),
6=>array("Gutshofverwalter","Gutshofverwalterin"),
7=>array("Gutsherr","Gutsherrin"),
8=>array("Gerber","Gerberin"),
9=>array("Fischer","Fischerin"),
10=>array("Bäcker","Bäckerin"),
11=>array("Hufschmied","Hufschmiedin"),
12=>array("Waffenschmied","Waffenschmiedin"),
13=>array("Goldschmied","Goldschmiedin"),
14=>array("Bettler","Bettlerin"),
15=>array("Wahrsager","Wahrsagerin"),
16=>array("Apotheker","Apothekerin"),
17=>array("Heiler","Heilerin"),
18=>array("Händler","Händlerin"),
19=>array("Großhändler","Großhändlerin"),
20=>array("Bürger","Bürgerin"),
21=>array("Ratsherr","Ratsfrau"),
22=>array("Bürgermeister","Bürgermeisterin"),
23=>array("Oberbürgermeister","Oberbürgermeisterin"),
24=>array("Advokat","Advokatin"),
25=>array("Richter","Richterin"),
26=>array("Schatzsucher","Schatzsucherin"),
27=>array("Abenteurer","Abenteurerin"),
28=>array("Skipper","Skipperin"),
29=>array("Freibeuter","Freibeuterin"),
30=>array("Pirat","Piratin"),
31=>array("Gladiator","Gladiatorin"),
32=>array("Söldner","Söldnerin"),
33=>array("Anwärter","Anwärterin"),
34=>array("Rekrut","Rekrutin"),
35=>array("Milizsoldat","Milizsoldatin"),
36=>array("Freischärler","Freischärlerin"),
37=>array("Heerführer","Heerführerin"),
38=>array("Legionär","Legionärin"),
39=>array("Dekurio","Dekurioness"),
40=>array("Centurio","Centurioness"),
41=>array("Optio","Optioness"),
42=>array("Plänker","Plänkerin"),
43=>array("Reiter","Reiterin"),
44=>array("Ritter","Ritterin"),
45=>array("Chevalier","Chevalierin"),
46=>array("Schwertmeister","Schwertmeisterin"),
47=>array("Bogenschütze","Bogenschützin"),
48=>array("Armbrustschütze","Armbrustschützin"),
49=>array("Bogenmeister","Bogenmeisterin"),
50=>array("Waffenmeister","Waffenmeisterin"),
51=>array("Veteran","Veteranin"),
52=>array("Drachentöter","Drachentöterin"),
53=>array("Botenjunge","Botenmädchen"),
54=>array("Vorkoster","Vorkosterin"),
55=>array("Höfling","Hofdame"),
56=>array("Edler","Edle"),
57=>array("Junker","Junkerin"),
58=>array("Freiherr","Freifrau"),
59=>array("Lehensherr","Lehensherrin"),
60=>array("Gouverneur","Gouverneurin"),
61=>array("Baron","Baroness"),
62=>array("Fürst","Fürstin"),
63=>array("Großfürst","Großfürstin"),
64=>array("Markgraf","Markgräfin"),
65=>array("Landgraf","Landgräfin"),
66=>array("Herzog","Herzogin"),
67=>array("Großherzog","Großherzogin"),
68=>array("Kurprinz","Kurprinzessin"),
69=>array("Kurfürst","Kurfürstin"),
70=>array("Erzherzog","Erzherzogin"),
71=>array("Prinz","Prinzessin"),
72=>array("Kronprinz","Kronprinzessin"),
73=>array("Novize","Novizin"),
74=>array("Ordensbruder","Ordensschwester"),
75=>array("Abt","Äbtissin"),
76=>array("Priester","Priesterin"),
77=>array("Hohepriester","Hohepriesterin"),
78=>array("Patriarch","Matriarch"),
79=>array("Bischof","Bischöfin"),
80=>array("Erzbischof","Erzbischöfin"),
81=>array("Kardinal","Kardinälin"),
82=>array("Papst","Päpstin"),
83=>array("Seeliger","Seelige"),
84=>array("Heiliger","Heilige"),
85=>array("Engel","Engel"),
86=>array("Erzengel","Erzengel"),
87=>array("Gewalt","Gewalt"),
88=>array("Macht","Macht"),
89=>array("Thron","Thron"),
90=>array("Cherub","Cherub"),
91=>array("Seraph","Seraph"),
92=>array("Wasserelementar","Wasserelementar"),
93=>array("Luftelementar","Luftelementar"),
94=>array("Feuerelementar","Feuerelementar"),
95=>array("Erdelementar","Erdelementar"),
96=>array("Lichtelemantar","Lichtelementar"),
97=>array("Titan","Titan"),
98=>array("Halbgott","Halbgöttin"),
99=>array("Kindgott","Kindgöttin"),
100=>array("Schutzgott","Schutzgöttin"),
101=>array("Liebesgott","Liebesgöttin"),
102=>array("Diebesgott","Diebesgöttin"),
103=>array("Jagdgott","Jagdgöttin"),
104=>array("Rachegott","Rachegöttin"),
105=>array("Kriegsgott","Kriegssgöttin"),
106=>array("Allmächtiger","Allmächtige")
);
?> 