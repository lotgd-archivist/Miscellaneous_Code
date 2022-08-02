<?php
header('Content-Type: text/html; charset=utf-8');
// 22062004

require_once "common.php";
page_header("Die alte Bank");
output("`^`c`bDie alte Stadtbank`b`c`k");
if ($HTTP_GET_VARS[op]==""){
  checkday();
  output("Ein kleiner Mann in einem makellosen Anzug mit Lesebrille grüßt dich.`n`n");
  output("\"`5Hallo guter Mann,`k\" grüßt du zurück, \"`5Kann ich meinen Kontostand an diesem wunderschönen Tag einsehen?`k\"`n`n");
  output("Der Bankier murmelt \"`3Hmm, ".$session[user][name]."`3, mal sehen.....`k\" während er die Seiten in seinem Buch ");
  output("sorgfältig überfliegt.  ");
    if ($session[user][goldinbank]>=0){
        output("\"`3Aah ja, hier ist es. Du hast  `^".$session[user][goldinbank]." Gold`3 bei unserer ");
        output("renommierten Bank.  Kann ich sonst noch etwas für dich tun?`k\"");
    }else{
        output("\"`3Aah ja, hier ist es.  Du hast `&Schulden`3 in Höhe von `^".abs($session[user][goldinbank])." Gold`3 bei unserer ");
        output("renommierten Bank.  Kann ich sonst noch etwas für dich tun?`k\"`n`n(`iSchulden verfallen durch einen Drachenkill nicht!`i)");
    }
}else if($_GET['op']=="transfer"){
    output("`k`bGold überweisen`b:`n");
    if ($session[user][goldinbank]>=0){
        output("Du kannst maximal `^".getsetting("transferperlevel",25)."`k Gold pro Level des Empfängers überweisen.`n");
        $maxout = $session['user']['level']*getsetting("maxtransferout",25);
        $minfer = round(getsetting("transferperlevel",25)/10*((int)$session['user']['level']/2));
        output("Du musst mindestens `^$minfer`k Gold überweisen.`n");
        output("Du kannst nicht mehr als insgesamt `^$maxout`k Gold überweisen.");
        if ($session['user']['amountouttoday'] > 0) {
            output("(Du hast heute schon `^{$session['user']['amountouttoday']}`k Gold überwiesen.)`n`n");
        } else output("`n`n");
        output("<form action='bank.php?op=transfer2' method='POST'>Wieviel ü<u>b</u>erweisen: <input name='amount' id='amount' accesskey='b' width='5'>`n",true);
        output("A<u>n</u>: <input name='to' accesskey='n'> (Unvollständige Namen werden automatisch ergänzt. Du wirst nochmal zum Bestätigen aufgefordert).`n",true);
        output("<input type='submit' class='button' value='Vorschau'></form>",true);
        output("<script language='javascript'>document.getElementById('amount').focus();</script>",true);
        addnav("","bank.php?op=transfer2");
    }else{
        output("`kDer kleine alte Bankier weigert sich, Geld für jemanden zu überweisen, der Schulden hat.");
    }
}else if($_GET['op']=="transfer2"){
    output("`k`bÜberweisung bestätigen`b:`n");
    $string="%";
    for ($x=0;$x<mb_strlen($_POST['to']);$x++){
        $string .= mb_substr($_POST['to'],$x,1)."%";
    }
    $sql = "SELECT name,login FROM accounts WHERE name LIKE '".addslashes($string)."'";
    $result = db_query($sql);
    $amt = abs((int)$_POST['amount']);
    if (db_num_rows($result)==1){
        $row = db_fetch_assoc($result);
        output("<form action='bank.php?op=transfer3' method='POST'>",true);
        output("`kÜberweise `^$amt`k an `&$row[name]`k.");
        output("<input type='hidden' name='to' value='".HTMLSpecialChars($row['login'])."'><input type='hidden' name='amount' value='$amt'><input type='submit' class='button' value='Überweisung abschließen'></form>",true);
        addnav("","bank.php?op=transfer3");
    }elseif(db_num_rows($result)>100){
        output("Der Bankier schaut dich überfordert an und schlägt dir vor, deine Suche vielleicht etwas mehr einzuengen, indem du den Namen genauer festlegst.`n`n");
        output("<form action='bank.php?op=transfer2' method='POST'>Wieviel ü<u>b</u>erweisen: <input name='amount' id='amount' accesskey='b' width='5' value='$amt'>`n",true);
        output("A<u>n</u>: <input name='to' accesskey='n' value='". $_POST['to'] . "'> (Unvollständige Namen werden automatisch ergänzt. Du wirst nochmal zum Bestätigen aufgefordert).`n",true);
        output("<input type='submit' class='button' value='Vorschau'></form>",true);
        output("<script language='javascript'>document.getElementById('amount').focus();</script>",true);
        addnav("","bank.php?op=transfer2");
    }elseif(db_num_rows($result)>1){
        output("<form action='bank.php?op=transfer3' method='POST'>",true);
        output("`kÜberweise `^$amt`k an <select name='to' class='input'>",true);
        for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            //output($row[name]." ".$row[login]."`n");
            output("<option value=\"".HTMLSpecialChars($row['login'])."\">".preg_replace("'[`].'","",$row['name'])."</option>",true);
        }
        output("</select><input type='hidden' name='amount' value='$amt'><input type='submit' class='button' value='Überweisung abschließen'></form>",true);
        addnav("","bank.php?op=transfer3");
    }else{
        output("`kEs konnte niemand mit diesem Namen gefunden werden. Bitte versuchs nochmal.");
    }
}else if($_GET['op']=="transfer3"){
    $amt = abs((int)$_POST['amount']);
    output("`k`bÜberweisung abschließen`b`n");
    if ($session[user][gold]+$session[user][goldinbank]<$amt){
        output("`kWie willst du `^$amt`k Gold überweisen, wenn du nur ".($session[user][gold]+$session[user][goldinbank])."`k Gold hast?");
    }else{
        $sql = "SELECT name,acctid,level,transferredtoday,lastip,emailaddress,uniqueid FROM accounts WHERE login='{$_POST['to']}'";
        $result = db_query($sql);
        if (db_num_rows($result)==1){
            $row = db_fetch_assoc($result);
            $maxout = $session['user']['level']*getsetting("maxtransferout",25);
            $maxtfer = $row['level']*getsetting("transferperlevel",25);
            $minfer = round(getsetting("transferperlevel",25)/10*((int)$session['user']['level']/2));
            if ($session['user']['amountouttoday']+$amt > $maxout) {
                output("`kDie Überweisung wurde nicht durchgeführt: Du darfst nicht mehr als `^$maxout`k Gold pro Tag überweisen.");
            }else if ($maxtfer<$amt){
                output("`kDie Überweisung wurde nicht durchgeführt: `&{$row['name']}`k darf maximal `^$maxtfer`k Gold empfangen.");
            }else if($row['transferredtoday']>=getsetting("transferreceive",3)){
                output("`&{$row['name']}`k hat heute schon zu viele Überweisungen oder Edelsteine erhalten. Du wirst bis morgen warten müssen.");
            }else if($amt<$minfer){
                output("`kDu solltest etwas überweisen, das sich auch lohnt. Wenigstens `^$minfer`k Gold.");
            }else if($row['acctid']==$session['user']['acctid']){
                output("`kDu kannst dir nicht selbst Gold überweisen. Das macht keinen Sinn!");
             } else if (ac_check($row)){
//             } else if ($session[user][emailaddress]==$row[emailaddress] && $row[emailaddress]){
                output("`\$`bNicht erlaubt!!`b Du darfst kein Gold an deine eigenen Charaktere überweisen!");
            }else{
                //debuglog("transferred $amt gold to", $row['acctid']);
                $session[user][gold]-=$amt;
                if ($session[user][gold]<0){ //withdraw in case they don't have enough on hand.
                    $session[user][goldinbank]+=$session[user][gold];
                    $session[user][gold]=0;
                }
                $session['user']['amountouttoday']+= $amt;
                $sql = "UPDATE accounts SET goldinbank=goldinbank+$amt,transferredtoday=transferredtoday+1 WHERE acctid='{$row['acctid']}'";
                db_query($sql);
                output("`kTransfer vollständig!");
                //$session['user']['donation']+=1;
                systemmail($row['acctid'],"`^Du hast eine Überweisung erhalten!`0","`&{$session['user']['name']}`k hat dir `^$amt`k Gold auf dein Konto überwiesen!");
            }
        }else{
            output("`kDie Überweisung hat nicht geklappt. Bitte versuchs nochmal.");
        }
    }



}else if($_GET['op']=="gemtrans"){
    output("`k`bEdelstein versenden`b:`n");
    if (($session[user][gold]>=100 || $session[user][gold]+$session[user][goldinbank]>=100) && $session[user][gems]>0){
        output("Du kannst `#1 Edelstein`k für eine Versandgebühr von `^100 Gold`k an einen beliebigen Charakter mit mindestens Level 3 verschenken.`n`n");
        output("<form action='bank.php?op=gemtrans2' method='POST'>Einen Edelstein versenden a<u>n</u>: <input name='to' accesskey='n'> (Unvollständige Namen werden automatisch ergänzt. Du wirst nochmal zum Bestätigen aufgefordert).`n",true);
        output("<input type='submit' class='button' value='Vorschau'></form>",true);
        addnav("","bank.php?op=gemtrans2");
    }else if ($session[user][gold]+$session[user][goldinbank]<100){
        output("`kDer kleine alte Bankier weigert sich, einen Edelstein kostenlos zu versenden.`nDu hast keine `^100`kGold!");
    } else {
        output("`kDer kleine alte Bankier erklärt dir lange und umständlich, dass du keine Edelsteine verschenken kannst, wenn du keine hast!");
    }
}else if($_GET['op']=="gemtrans2"){
    output("`k`bVersand bestätigen`b:`n");
    $string="%";
    for ($x=0;$x<mb_strlen($_POST['to']);$x++){
        $string .= mb_substr($_POST['to'],$x,1)."%";
    }
    $sql = "SELECT name,login FROM accounts WHERE name LIKE '".addslashes($string)."'";
    $result = db_query($sql);
    if (db_num_rows($result)==1){
        $row = db_fetch_assoc($result);
        output("<form action='bank.php?op=gemtrans3' method='POST'>",true);
        output("`kVerschenke `#1 Edelstein`k für eine Versandgebühr von `^100 Gold`k an `&$row[name]`k.");
        output("<input type='hidden' name='to' value='".HTMLSpecialChars($row['login'])."'><input type='submit' class='button' value='Versand abschließen'></form>",true);
        addnav("","bank.php?op=gemtrans3");
    }elseif(db_num_rows($result)>100){
        output("Der Bankier schaut dich überfordert an und schlägt dir vor, deine Suche vielleicht etwas mehr einzuengen, indem du den Namen genauer festlegst.`n`n");
        output("<form action='bank.php?op=gemtrans2' method='POST'>Versende einen Edelstein a<u>n</u>: <input name='to' accesskey='n' value='". $_POST['to'] . "'> (Unvollständige Namen werden automatisch ergänzt. Du wirst nochmal zum Bestätigen aufgefordert).`n",true);
        output("<input type='submit' class='button' value='Vorschau'></form>",true);
        addnav("","bank.php?op=gemtrans2");
    }elseif(db_num_rows($result)>1){
        output("<form action='bank.php?op=gemtrans3' method='POST'>",true);
        output("`kVerschenke `#1 Edelstein`k für eine Versandgebühr von `^100 Gold`k an <select name='to' class='input'>",true);
        for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            output("<option value=\"".HTMLSpecialChars($row['login'])."\">".preg_replace("'[`].'","",$row['name'])."</option>",true);
        }
        output("</select><input type='submit' class='button' value='Versand abschließen'></form>",true);
        addnav("","bank.php?op=gemtrans3");
    }else{
        output("`kEs konnte niemand mit diesem Namen gefunden werden. Bitte versuchs nochmal.");
    }
}else if($_GET['op']=="gemtrans3"){
    output("`k`bVersand abschließen`b`n");
    $sql = "SELECT name,acctid,level,lastip,emailaddress,transferredtoday,uniqueid FROM accounts WHERE login='{$_POST['to']}'";
    $result = db_query($sql);
    if (db_num_rows($result)==1){
        $row = db_fetch_assoc($result);
         if($row['level']<3){
            output("`&{$row['name']}`k kann noch keine Edelsteine in Empfang nehmen. Der Empfänger muss mindestens Level 3 sein.");
        }else if($row['acctid']==$session['user']['acctid']){
            output("`kDu kannst dir nicht selbst einen Edelstein schenken. Das macht keinen Sinn!");
        }else if($row['transferredtoday']>=getsetting("transferreceive",3)){
            output("`&{$row['name']}`k hat heute schon zu viele Überweisungen oder Edelsteine erhalten. Du wirst bis morgen warten müssen.");
        } else if (ac_check($row)){
//        } else if ($session[user][emailaddress]==$row[emailaddress] && $row[emailaddress]){
            output("`\$`bNicht erlaubt!!`b Du darfst keine Edelsteine an deine eigenen Charaktere versenden!");
        }else{
            //debuglog("transferred 1 gem to", $row['acctid']);
            $session[user][gold]-=100;
            $session[user][gems]-=1;
            if ($session[user][gold]<0){ //withdraw in case they don't have enough on hand.
                $session[user][goldinbank]+=$session[user][gold];
                $session[user][gold]=0;
            }
            $sql = "UPDATE accounts SET gems=gems+1,transferredtoday=transferredtoday+1  WHERE acctid='{$row['acctid']}'";
            db_query($sql);
            output("`kVersand erfolgreich!");
            systemmail($row['acctid'],"`#Du hast einen Edelstein geschenkt bekommen!`0","`&{$session['user']['name']}`k war so freundlich und hat dir `#1 Edelstein`k geschenkt!");
        }
    }else{
        output("`kDer Versand hat nicht geklappt. Bitte versuchs nochmal.");
    }




}else if($HTTP_GET_VARS[op]=="deposit"){
  output("<form action='bank.php?op=depositfinish' method='POST'>Du hast ".($session[user][goldinbank]>=0?"ein Guthaben von":"Schulden in Höhe von")." ".abs($session[user][goldinbank])." Gold bei der Bank.`n",true);
    output("`^Wie <u>v</u>iel ".($session[user][goldinbank]>=0?"einzahlen":"zurückzahlen").":  <input id='input' name='amount' width=5 accesskey='v'> <input type='submit' class='button' value='Einzahlen'>`n`iGib 0 oder gar nichts ein, um alles einzuzahlen.`i</form>",true);
    output("<script language='javascript'>document.getElementById('input').focus();</script>",true);
  addnav("","bank.php?op=depositfinish");
}else if($HTTP_GET_VARS[op]=="depositfinish"){
    $_POST[amount]=abs((int)$_POST[amount]);
    if ($_POST[amount]==0){
        $_POST[amount]=$session[user][gold];
    }
    if ($_POST[amount]>$session[user][gold]){
        output("`\$FEHLER: Soviel Gold hast du nicht dabei.`^`n`n");
        output("Du schmeißt deine `&".$session[user][gold]."`^ Gold auf den Schaltertisch und erklärst, dass du die ganzen `&$_POST[amount]`^ Gold einzahlen möchtest.");
        output("`n`nDer kleine alte Mann schaut dich nur verständnislos an. Durch diesen seltsamen Blick verunsichert, zählst du noch einmal nach und erkennst deinen Irrtum. Verdammt, wozu soll ein Krieger rechnen können?");
    }else{
        output("`^`bDu zahlst `&$_POST[amount]`^ Gold auf dein Konto ein. ");
        //debuglog("deposited " . $_POST[amount] . " gold in the bank");
        $session[user][goldinbank]+=$_POST[amount];
        $session[user][gold]-=$_POST[amount];
        output("Du hast damit ".($session[user][goldinbank]>=0?"ein Guthaben von":"Schulden in Höhe von")." `&".abs($session[user][goldinbank])."`^ Gold auf deinem Konto und `&".$session[user][gold]."`^ Gold hast du bei dir.`b");
    }
}else if($_GET[op]=="borrow"){
    if ($session['user']['reputation']<-35){
        output("Misstrauisch schaut dich der kleine Kerl eine Weile an. Dann, als ob er dein Gesicht erkannt hätte, atmet er ein und erklärt dir vorsichtig, dass er es nicht für klug hält, Leuten von deinem Schlag Geld zu leihen. Offenbar ist ihm dein schlechter Ruf zu Ohren gekommen und ist nun um den Ruf (und das Gold) seiner Bank besorgt...");
    }else{
        $maxborrow = $session[user][level]*getsetting("borrowperlevel",20);
          output("<form action='bank.php?op=withdrawfinish' method='POST'>Du hast ".($session[user][goldinbank]>=0?"ein Guthaben von":"Schulden in Höhe von")." ".abs($session[user][goldinbank])." Gold bei der Bank.`n",true);
          output("`^Wieviel <u>l</u>eihen (mit deinem Level kannst du maximal $maxborrow leihen)? <input id='input' name='amount' width=5 accesskey='l'> <input type='hidden' name='borrow' value='x'><input type='submit' class='button' value='Leihen'>`n(Gold wird abgehoben, bis dein Konto leer ist. Der Restbetrag wird geliehen.)</form>",true);
        output("<script language='javascript'>document.getElementById('input').focus();</script>",true);
          addnav("","bank.php?op=withdrawfinish");
    }
}else if($HTTP_GET_VARS[op]=="withdraw"){
      output("<form action='bank.php?op=withdrawfinish' method='POST'>Du hast ".$session[user][goldinbank]." Gold bei der Bank.`n",true);
      output("`^Wieviel a<u>b</u>heben? <input id='input' name='amount' width=5 accesskey='b'> <input type='submit' class='button' value='Abheben'>`n`iGib 0 oder gar nichts ein, um alles abzuheben.`i</form>",true);
    output("<script language='javascript'>document.getElementById('input').focus();</script>",true);
      addnav("","bank.php?op=withdrawfinish");
}else if($HTTP_GET_VARS[op]=="withdrawfinish"){
    $_POST[amount]=abs((int)$_POST[amount]);
    if ($_POST[amount]==0){
        $_POST[amount]=abs($session[user][goldinbank]);
    }
    if ($_POST[amount]>$session[user][goldinbank] && $_POST[borrow]=="") {
        output("`\$FEHLER: Nicht genug auf dem Konto.`^`n`n");
        output("Nachdem du darüber informiert wurdest, dass du `&".$session[user][goldinbank]."`^ Gold auf dem Konto hast, erklärst du dem Männlein mit der Lesebrille, dass du gerne `&$_POST[amount]`^ davon abheben würdest.");
        output("`n`nDer Bankier schaut dich bedauernd an und erklärt dir die Grundlagen der Mathematik. Nach einer Weile verstehst du deinen Fehler und würdest es gerne nochmal versuchen.");
    }else if($_POST[amount]>$session[user][goldinbank]){
        $lefttoborrow = $_POST[amount];
        $maxborrow = $session[user][level]*getsetting("borrowperlevel",20);
        if ($lefttoborrow<=$session[user][goldinbank]+$maxborrow){
            if ($session[user][goldinbank]>0){
                output("`kDu nimmst deine verbleibenden `^".$session[user][goldinbank]."`k Gold und ");
                $lefttoborrow-=$session[user][goldinbank];
                $session[user][gold]+=$session[user][goldinbank];
                $session[user][goldinbank]=0;
                //debuglog("withdrew " . $_POST[amount] . " gold from the bank");
            }else{
                output("`kDu ");
            }
            if ($lefttoborrow-$session[user][goldinbank] > $maxborrow){
                output("fragst, ob du `^$lefttoborrow`k Gold leihen kannst. Der kleine Mann informiert dich darüber, dass er dir in deiner gegenwärtigen Situation nur `^$maxborrow`k Gold geben kann.");
            }else{
                output("leihst dir `^$lefttoborrow`k Gold.");
                $session[user][goldinbank]-=$lefttoborrow;
                $session[user][gold]+=$lefttoborrow;
                //debuglog("borrows $lefttoborrow gold from the bank");
            }
        }else{
            output("`kMit den schlappen `^{$session[user][goldinbank]}`k Gold auf deinem Konto bittest du um einen Kredit von `^".($lefttoborrow-$session[user][goldinbank])."`k Gold, aber
            der kurze kleine Mann informiert dich darüber, dass du mit deinem Level höchstens `^$maxborrow`k Gold leihen kannst.");
        }
    }else{
        output("`^`bDu hast `&$_POST[amount]`^ Gold von deinem Bankkonto abgehoben. ");
        $session[user][goldinbank]-=$_POST[amount];
        $session[user][gold]+=$_POST[amount];
        //debuglog("withdrew " . $_POST[amount] . " gold from the bank");
        output("Du hast damit `&".$session[user][goldinbank]."`^ Gold auf deinem Konto und `&".$session[user][gold]."`^ Gold hast du bei dir.`b");
    }
    }else if($_GET[op]=="gemdraw"){
     output("<form action='bank.php?op=gemdrawfinish' method='POST'>Du hast ".$session[user][gemsinbank]." Edelsteine bei der Bank.`n",true);
     output("`^Wieviel a<u>b</u>heben? <input id='input' name='amount' width=5 accesskey='b'> <input type='submit' class='button' value='Abheben'>`n`iGib 0 oder gar nichts ein, um alles abzuheben.`i</form>",true);
   output("<script language='javascript'>document.getElementById('input').focus();</script>",true);
     addnav("","bank.php?op=gemdrawfinish");
}else if($_GET[op]=="gemdrawfinish"){
   $_POST[amount]=abs((int)$_POST[amount]);
   if ($_POST[amount]==0){
      $_POST[amount]=abs($session[user][gemsinbank]);
   }
   if ($_POST[amount]>$session[user][gemsinbank]) {
      output("`\$FEHLER: Nicht genug auf dem Konto.`^`n`n");
      output("Nachdem du darüber informiert wurdest, dass du `&".$session[user][gemsinbank]."`^ Edelsteine auf dem Konto hast, erklärst du dem Männlein mit der Lesebrille, dass du gerne `&$_POST[amount]`^ davon abheben würdest.");
      output("`n`nDer Bankier schaut dich bedauernd an und erklärt dir die Grundlagen der Mathematik. Nach einer Weile verstehst du deinen Fehler und würdest es gerne nochmal versuchen.");
   }else{
      output("`^`bDu hast `&$_POST[amount]`^ Edelsteine von deinem Bankkonto abgehoben. ");
      $session[user][gemsinbank]-=$_POST[amount];
      $session[user][gems]+=$_POST[amount];
      //debuglog("withdrew " . $_POST[amount] . " gems from the bank");
      output("Du hast damit `&".$session[user][gemsinbank]."`^ Edelsteine auf deinem Konto und `&".$session[user][gems]."`^ Edelsteine hast du bei dir.`b");
   }

}else if($_GET[op]=="gemdeposit"){
   output("<form action='bank.php?op=gemdepositfinish' method='POST'>Du hast ".$session[user][gemsinbank]." Edelsteine bei der Bank.`n",true);
   output("`^Wie <u>v</u>iel einzahlen?  <input id='input' name='amount' width=5 accesskey='v'> <input type='submit' class='button' value='Einzahlen'>`n`iGib 0 oder gar nichts ein, um alles einzuzahlen.`i</form>",true);
   output("<script language='javascript'>document.getElementById('input').focus();</script>",true);
   addnav("","bank.php?op=gemdepositfinish");
}else if($_GET[op]=="gemdepositfinish"){
   $_POST[amount]=abs((int)$_POST[amount]);
   if ($_POST[amount]==0){
      $_POST[amount]=$session[user][gems];
   }
   if ($_POST[amount]>$session[user][gems]){
      output("`\$FEHLER: Soviele Edelsteine hast du nicht dabei.`^`n`n");
      output("Du schmeißt deine `&".$session[user][gems]."`^ Edelsteine auf den Schaltertisch und erklärst, dass du die ganzen `&$_POST[amount]`^ Edelsteine einzahlen möchtest.");
      output("`n`nDer kleine alte Mann schaut dich nur verständnislos an. Durch diesen seltsamen Blick verunsichert, zählst du noch einmal nach und erkennst deinen Irrtum. Verdammt, wozu soll ein Krieger rechnen können?");
   }else{
      output("`^`bDu zahlst `&$_POST[amount]`^ Edelsteine auf dein Konto ein. ");
      //debuglog("deposited " . $_POST[amount] . " Gems in the bank");
      $session[user][gemsinbank]+=$_POST[amount];
      $session[user][gems]-=$_POST[amount];
      output("Du hast damit `&".abs($session[user][gemsinbank])."`^ Edelsteine auf deinem Konto und `&".$session[user][gems]."`^ Edelsteine hast du bei dir.`b");
   }
}
addnav("Zurück zum Dorf","village.php");
if ($session[user][goldinbank]>=0){
    addnav("Abheben","bank.php?op=withdraw");
    addnav("Einzahlen","bank.php?op=deposit");
    if (getsetting("borrowperlevel",20)) addnav("Kredit aufnehmen","bank.php?op=borrow");
}else{
    addnav("Schulden begleichen","bank.php?op=deposit");
    if (getsetting("borrowperlevel",20)) addnav("Mehr leihen","bank.php?op=borrow");
}
if ($session[user][gemsinbank]>0) addnav("Edelsteine mitnehmen","bank.php?op=gemdraw");
if ($session[user][gems]>0) addnav("Edelsteine einlagern","bank.php?op=gemdeposit");

if (getsetting("allowgoldtransfer",1)){
    if ($session[user][level]>=getsetting("mintransferlev",3) || $session[user][dragonkills]>0){
        addnav("Gold überweisen","bank.php?op=transfer");
    }
    addnav("Edelstein versenden","bank.php?op=gemtrans");
}

page_footer();

?> 