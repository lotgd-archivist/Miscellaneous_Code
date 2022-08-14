
<?php

require_once("common.php");

page_header("Die alte Bank");

page_header("Die alte Bank");

output("`^`c`bDie alte Stadtbank`b`c`6");

if ($_GET[op]==""){

  checkday();

  output("Ein kleiner Mann in einem makellosen Anzug mit Lesebrille grÃ¼ÃŸt dich.`n`n");

  output("\"`5Hallo guter Mann,`6\" grÃ¼ÃŸt du zurÃ¼ck, \"`5Kann ich meinen Kontostand an diesem wunderschÃ¶nen Tag einsehen?`6\"`n`n");

  output("Der Bankier murmelt \"`3Hmm, ".$session[user][name]."`3, mal sehen.....`6\" wÃ¤hrend er die Seiten in seinem Buch ");

  output("sorgfÃ¤ltig Ã¼berfliegt.  ");

    if ($session[user][goldinbank]>=0){

        output("\"`3Aah ja, hier ist es. Du hast  `^".$session[user][goldinbank]." Gold`3 und `^".$session[user][gemsinbank]." Edelsteine `3bei unserer ");

        output("renommierten Bank.  Kann ich sonst noch etwas fÃ¼r dich tun?`6\"");

    }else{

        output("\"`3Aah ja, hier ist es.  Du hast `&Schulden`3 in HÃ¶he von `^".abs($session[user][goldinbank])." Gold`3 bei unserer ");

        output("renommierten Bank.  Kann ich sonst noch etwas fÃ¼r dich tun?`6\"`n`n(`iSchulden verfallen durch einen Drachenkill nicht!`i)");

    }

}else if($_GET['op']=="transfer"){

    output("`6`bGold Ã¼berweisen`b:`n");

    if ($session[user][goldinbank]>=0){

        output("Du kannst maximal `^".getsetting("transferperlevel",25)."`6 Gold pro Level des EmpfÃ¤ngers Ã¼berweisen.`n");

        $maxout = $session['user']['level']*getsetting("maxtransferout",25);

        $minfer = round(getsetting("transferperlevel",25)/10*((int)$session['user']['level']/2));

        output("Du musst mindestens `^$minfer`6 Gold Ã¼berweisen.`n");

        output("Du kannst nicht mehr als insgesamt `^$maxout`6 Gold Ã¼berweisen.");

        if ($session['user']['amountouttoday'] > 0) {

            output("(Du hast heute schon `^{$session['user']['amountouttoday']}`6 Gold Ã¼berwiesen.)`n`n");

        } else output("`n`n");

        output("<form action='bank.php?op=transfer2' method='POST'>Wieviel Ã¼<u>b</u>erweisen: <input name='amount' id='amount' accesskey='b' width='5'>`n",true);

        output("A<u>n</u>: <input name='to' accesskey='n'> (UnvollstÃ¤ndige Namen werden automatisch ergÃ¤nzt. Du wirst nochmal zum BestÃ¤tigen aufgefordert).`n",true);

        output("<input type='submit' class='button' value='Vorschau'></form>",true);

        output("<script language='javascript'>document.getElementById('amount').focus();</script>",true);

        addnav("","bank.php?op=transfer2");

    }else{

        output("`6Der kleine alte Bankier weigert sich, Geld fÃ¼r jemanden zu Ã¼berweisen, der Schulden hat.");

    }

}else if($_GET['op']=="transfer2"){

    output("`6`bÃœberweisung bestÃ¤tigen`b:`n");

    $string="%";

    for ($x=0;$x<strlen($_POST['to']);$x++){

        $string .= substr($_POST['to'],$x,1)."%";

    }

    $sql = "SELECT name,login FROM accounts WHERE name LIKE '".addslashes($string)."'";

    $result = db_query($sql);

    $amt = abs((int)$_POST['amount']);

    if (db_num_rows($result)==1){

        $row = db_fetch_assoc($result);

        output("<form action='bank.php?op=transfer3' method='POST'>",true);

        output("`6Ãœberweise `^$amt`6 an `&$row[name]`6.");

        output("<input type='hidden' name='to' value='".HTMLEntities($row['login'])."'><input type='hidden' name='amount' value='$amt'><input type='submit' class='button' value='Ãœberweisung abschlieÃŸen'></form>",true);

        addnav("","bank.php?op=transfer3");

    }elseif(db_num_rows($result)>100){

        output("Der Bankier schaut dich Ã¼berfordert an und schlÃ¤gt dir vor, deine Suche vielleicht etwas mehr einzuengen, indem du den Namen genauer festlegst.`n`n");

        output("<form action='bank.php?op=transfer2' method='POST'>Wieviel Ã¼<u>b</u>erweisen: <input name='amount' id='amount' accesskey='b' width='5' value='$amt'>`n",true);

        output("A<u>n</u>: <input name='to' accesskey='n' value='". $_POST['to'] . "'> (UnvollstÃ¤ndige Namen werden automatisch ergÃ¤nzt. Du wirst nochmal zum BestÃ¤tigen aufgefordert).`n",true);

        output("<input type='submit' class='button' value='Vorschau'></form>",true);

        output("<script language='javascript'>document.getElementById('amount').focus();</script>",true);

        addnav("","bank.php?op=transfer2");

    }elseif(db_num_rows($result)>1){

        output("<form action='bank.php?op=transfer3' method='POST'>",true);

        output("`6Ãœberweise `^$amt`6 an <select name='to' class='input'>",true);

        for ($i=0;$i<db_num_rows($result);$i++){

            $row = db_fetch_assoc($result);

            //output($row[name]." ".$row[login]."`n");

            output("<option value=\"".HTMLEntities($row['login'])."\">".preg_replace("'[`].'","",$row['name'])."</option>",true);

        }

        output("</select><input type='hidden' name='amount' value='$amt'><input type='submit' class='button' value='Ãœberweisung abschlieÃŸen'></form>",true);

        addnav("","bank.php?op=transfer3");

    }else{

        output("`6Es konnte niemand mit diesem Namen gefunden werden. Bitte versuchs nochmal.");

    }

}else if($_GET['op']=="transfer3"){

    $amt = abs((int)$_POST['amount']);

    output("`6`bÃœberweisung abschlieÃŸen`b`n");

    if ($session[user][gold]+$session[user][goldinbank]<$amt){

        output("`6Wie willst du `^$amt`6 Gold Ã¼berweisen, wenn du nur ".($session[user][gold]+$session[user][goldinbank])."`6 Gold hast?");

    }else{

        $sql = "SELECT name,acctid,level,transferredtoday,lastip,emailaddress,uniqueid FROM accounts WHERE login='{$_POST['to']}'";

        $result = db_query($sql);

        if (db_num_rows($result)==1){

            $row = db_fetch_assoc($result);

            $maxout = $session['user']['level']*getsetting("maxtransferout",25);

            $maxtfer = $row['level']*getsetting("transferperlevel",25);

            $minfer = round(getsetting("transferperlevel",25)/10*((int)$session['user']['level']/2));

            if ($session['user']['amountouttoday']+$amt > $maxout) {

                output("`6Die Ãœberweisung wurde nicht durchgefÃ¼hrt: Du darfst nicht mehr als `^$maxout`6 Gold pro Tag Ã¼berweisen.");

            }else if ($maxtfer<$amt){

                output("`6Die Ãœberweisung wurde nicht durchgefÃ¼hrt: `&{$row['name']}`6 darf maximal `^$maxtfer`6 Gold empfangen.");

            }else if($row['transferredtoday']>=getsetting("transferreceive",3)){

                output("`&{$row['name']}`6 hat heute schon zu viele Ãœberweisungen oder Edelsteine erhalten. Du wirst bis morgen warten mÃ¼ssen.");

            }else if($amt<$minfer){

                output("`6Du solltest etwas Ã¼berweisen, das sich auch lohnt. Wenigstens `^$minfer`6 Gold.");

            }else if($row['acctid']==$session['user']['acctid']){

                output("`6Du kannst dir nicht selbst Gold Ã¼berweisen. Das macht keinen Sinn!");

             } else if (ac_check($row)){

//             } else if ($session[user][emailaddress]==$row[emailaddress] && $row[emailaddress]){

                output("`\$`bNicht erlaubt!!`b Du darfst kein Gold an deine eigenen Charaktere Ã¼berweisen!");

            }else{

                //debuglog("transferred $amt gold to", $row['acctid']);

                $session[user][gold]-=$amt;

                if ($session[user][gold]<0){ //withdraw in case they don't have enough on hand.

                    $session[user][goldinbank]+=$session[user][gold];

                    $session[user][gold]=0;

                }

                $session['user']['amountouttoday']+= $amt;

                //$sql = "UPDATE accounts SET goldinbank=goldinbank+$amt WHERE acctid='{$row['acctid']}'";

                //db_query($sql);

                updateuser($row['acctid'],array('goldinbank'=>"+$amt"));

                output("`6Transfer vollstÃ¤ndig!");

                //$session['user']['donation']+=1;

                systemmail($row['acctid'],"`^Du hast eine Ãœberweisung erhalten!`0","`&{$session['user']['name']}`6 hat dir `^$amt`6 Gold auf dein Konto Ã¼berwiesen!");

            }

        }else{

            output("`6Die Ãœberweisung hat nicht geklappt. Bitte versuchs nochmal.");

        }

    }







}else if($_GET['op']=="gemtrans"){

    output("`6`bEdelstein versenden`b:`n");

    if (($session[user][gold]>=100 || $session[user][gold]+$session[user][goldinbank]>=100) && $session[user][gems]>0){

        output("Du kannst `#1 Edelstein`6 fÃ¼r eine VersandgebÃ¼hr von `^100 Gold`6 an einen beliebigen Charakter mit mindestens Level 3 verschenken.`n`n");

        output("<form action='bank.php?op=gemtrans2' method='POST'>Einen Edelstein versenden a<u>n</u>: <input name='to' accesskey='n'> (UnvollstÃ¤ndige Namen werden automatisch ergÃ¤nzt. Du wirst nochmal zum BestÃ¤tigen aufgefordert).`n",true);

        output("<input type='submit' class='button' value='Vorschau'></form>",true);

        addnav("","bank.php?op=gemtrans2");

    }else if ($session[user][gold]+$session[user][goldinbank]<100){

        output("`6Der kleine alte Bankier weigert sich, einen Edelstein kostenlos zu versenden.`nDu hast keine `^100`6Gold!");

    } else {

        output("`6Der kleine alte Bankier erklÃ¤rt dir lange und umstÃ¤ndlich, dass du keine Edelsteine verschenken kannst, wenn du keine hast!");

    }

}else if($_GET['op']=="gemtrans2"){

    output("`6`bVersand bestÃ¤tigen`b:`n");

    $string="%";

    for ($x=0;$x<strlen($_POST['to']);$x++){

        $string .= substr($_POST['to'],$x,1)."%";

    }

    $sql = "SELECT name,login FROM accounts WHERE name LIKE '".addslashes($string)."'";

    $result = db_query($sql);

    if (db_num_rows($result)==1){

        $row = db_fetch_assoc($result);

        output("<form action='bank.php?op=gemtrans3' method='POST'>",true);

        output("`6Verschenke `#1 Edelstein`6 fÃ¼r eine VersandgebÃ¼hr von `^100 Gold`6 an `&$row[name]`6.");

        output("<input type='hidden' name='to' value='".HTMLEntities($row['login'])."'><input type='submit' class='button' value='Versand abschlieÃŸen'></form>",true);

        addnav("","bank.php?op=gemtrans3");

    }elseif(db_num_rows($result)>100){

        output("Der Bankier schaut dich Ã¼berfordert an und schlÃ¤gt dir vor, deine Suche vielleicht etwas mehr einzuengen, indem du den Namen genauer festlegst.`n`n");

        output("<form action='bank.php?op=gemtrans2' method='POST'>Versende einen Edelstein a<u>n</u>: <input name='to' accesskey='n' value='". $_POST['to'] . "'> (UnvollstÃ¤ndige Namen werden automatisch ergÃ¤nzt. Du wirst nochmal zum BestÃ¤tigen aufgefordert).`n",true);

        output("<input type='submit' class='button' value='Vorschau'></form>",true);

        addnav("","bank.php?op=gemtrans2");

    }elseif(db_num_rows($result)>1){

        output("<form action='bank.php?op=gemtrans3' method='POST'>",true);

        output("`6Verschenke `#1 Edelstein`6 fÃ¼r eine VersandgebÃ¼hr von `^100 Gold`6 an <select name='to' class='input'>",true);

        for ($i=0;$i<db_num_rows($result);$i++){

            $row = db_fetch_assoc($result);

            output("<option value=\"".HTMLEntities($row['login'])."\">".preg_replace("'[`].'","",$row['name'])."</option>",true);

        }

        output("</select><input type='submit' class='button' value='Versand abschlieÃŸen'></form>",true);

        addnav("","bank.php?op=gemtrans3");

    }else{

        output("`6Es konnte niemand mit diesem Namen gefunden werden. Bitte versuchs nochmal.");

    }

}else if($_GET['op']=="gemtrans3"){

    output("`6`bVersand abschlieÃŸen`b`n");

    $sql = "SELECT name,acctid,level,lastip,emailaddress,transferredtoday,uniqueid FROM accounts WHERE login='{$_POST['to']}'";

    $result = db_query($sql);

    if (db_num_rows($result)==1){

        $row = db_fetch_assoc($result);

         if($row['level']<3){

            output("`&{$row['name']}`6 kann noch keine Edelsteine in Empfang nehmen. Der EmpfÃ¤nger muss mindestens Level 3 sein.");

        }else if($row['acctid']==$session['user']['acctid']){

            output("`6Du kannst dir nicht selbst einen Edelstein schenken. Das macht keinen Sinn!");

        }else if($row['transferredtoday']>=getsetting("transferreceive",3)){

            output("`&{$row['name']}`6 hat heute schon zu viele Ãœberweisungen oder Edelsteine erhalten. Du wirst bis morgen warten mÃ¼ssen.");

        } else if (ac_check($row)){

//        } else if ($session[user][emailaddress]==$row[emailaddress] && $row[emailaddress]){

            output("`\$`bNicht erlaubt!!`b Du darfst keine Edelsteine an deine eigenen Charaktere versenden!");

        }else{

            //debuglog("transferred 1 gem to", $row['acctid']);

            $session[user][gold]-=100;

            $session[user][gems]-=1;

            if ($session[user][gold]<0){ //withdraw in case they don't have enough on hand.

                $session[user][goldinbank]+=$session[user][gold];

                $session[user][gold]=0;

            }

            $sql = "UPDATE accounts SET transferredtoday=transferredtoday+1  WHERE acctid='{$row['acctid']}'";

            db_query($sql);

            updateuser($row['acctid'],array('gems'=>"+1"));

            output("`6Versand erfolgreich!");

            systemmail($row['acctid'],"`#Du hast einen Edelstein geschenkt bekommen!`0","`&{$session['user']['name']}`6 war so freundlich und hat dir `#1 Edelstein`6 geschenkt!");

        }

    }else{

        output("`6Der Versand hat nicht geklappt. Bitte versuchs nochmal.");

    }



}else if($_GET[op]=="deposit"){

  output("<form action='bank.php?op=depositfinish' method='POST'>Du hast ".($session[user][goldinbank]>=0?"ein Guthaben von":"Schulden in HÃ¶he von")." ".abs($session[user][goldinbank])." Gold bei der Bank.`n",true);

    output("`^Wie <u>v</u>iel ".($session[user][goldinbank]>=0?"einzahlen":"zurÃ¼ckzahlen").":  <input id='input' name='amount' width=5 accesskey='v'> <input type='submit' class='button' value='Einzahlen'>`n`iGib 0 oder gar nichts ein, um alles einzuzahlen.`i</form>",true);

    output("<script language='javascript'>document.getElementById('input').focus();</script>",true);

  addnav("","bank.php?op=depositfinish");

}else if($_GET[op]=="depositfinish"){

    $_POST[amount]=abs((int)$_POST[amount]);

    if ($_POST[amount]==0){

        $_POST[amount]=$session[user][gold];

    }

    if ($_POST[amount]>$session[user][gold]){

        output("`\$FEHLER: Soviel Gold hast du nicht dabei.`^`n`n");

        output("Du schmeiÃŸt deine `&".$session[user][gold]."`^ Gold auf den Schaltertisch und erklÃ¤rst, dass du die ganzen `&$_POST[amount]`^ Gold einzahlen mÃ¶chtest.");

        output("`n`nDer kleine alte Mann schaut dich nur verstÃ¤ndnislos an. Durch diesen seltsamen Blick verunsichert, zÃ¤hlst du noch einmal nach und erkennst deinen Irrtum. Verdammt, wozu soll ein Krieger rechnen kÃ¶nnen?");

    }else{

        output("`^`bDu zahlst `&$_POST[amount]`^ Gold auf dein Konto ein. ");

        //debuglog("deposited " . $_POST[amount] . " gold in the bank");

        $session[user][goldinbank]+=$_POST[amount];

        $session[user][gold]-=$_POST[amount];

        output("Du hast damit ".($session[user][goldinbank]>=0?"ein Guthaben von":"Schulden in HÃ¶he von")." `&".abs($session[user][goldinbank])."`^ Gold auf deinem Konto und `&".$session[user][gold]."`^ Gold hast du bei dir.`b");

    }

}else if($_GET[op]=="borrow"){

    if ($session['user']['reputation']<-35){

        output("Misstrauisch schaut dich der kleine Kerl eine Weile an. Dann, als ob er dein Gesicht erkannt hÃ¤tte, atmet er ein und erklÃ¤rt dir vorsichtig, dass er es nicht fÃ¼r klug hÃ¤lt, Leuten von deinem Schlag Geld zu leihen. Offenbar ist ihm dein schlechter Ruf zu Ohren gekommen und ist nun um den Ruf (und das Gold) seiner Bank besorgt...");

    }else{

        $maxborrow = $session[user][level]*getsetting("borrowperlevel",20);

          output("<form action='bank.php?op=withdrawfinish' method='POST'>Du hast ".($session[user][goldinbank]>=0?"ein Guthaben von":"Schulden in HÃ¶he von")." ".abs($session[user][goldinbank])." Gold bei der Bank.`n",true);

          output("`^Wieviel <u>l</u>eihen (mit deinem Level kannst du maximal $maxborrow leihen)? <input id='input' name='amount' width=5 accesskey='l'> <input type='hidden' name='borrow' value='x'><input type='submit' class='button' value='Leihen'>`n(Gold wird abgehoben, bis dein Konto leer ist. Der Restbetrag wird geliehen.)</form>",true);

        output("<script language='javascript'>document.getElementById('input').focus();</script>",true);

          addnav("","bank.php?op=withdrawfinish");

    }

}else if($_GET[op]=="withdraw"){

      output("<form action='bank.php?op=withdrawfinish' method='POST'>Du hast ".$session[user][goldinbank]." Gold bei der Bank.`n",true);

      output("`^Wieviel a<u>b</u>heben? <input id='input' name='amount' width=5 accesskey='b'> <input type='submit' class='button' value='Abheben'>`n`iGib 0 oder gar nichts ein, um alles abzuheben.`i</form>",true);

    output("<script language='javascript'>document.getElementById('input').focus();</script>",true);

      addnav("","bank.php?op=withdrawfinish");

}else if($_GET[op]=="withdrawfinish"){

    $_POST[amount]=abs((int)$_POST[amount]);

    if ($_POST[amount]==0){

        $_POST[amount]=abs($session[user][goldinbank]);

    }

    if ($_POST[amount]>$session[user][goldinbank] && $_POST[borrow]=="") {

        output("`\$FEHLER: Nicht genug auf dem Konto.`^`n`n");

        output("Nachdem du darÃ¼ber informiert wurdest, dass du `&".$session[user][goldinbank]."`^ Gold auf dem Konto hast, erklÃ¤rst du dem MÃ¤nnlein mit der Lesebrille, dass du gerne `&$_POST[amount]`^ davon abheben wÃ¼rdest.");

        output("`n`nDer Bankier schaut dich bedauernd an und erklÃ¤rt dir die Grundlagen der Mathematik. Nach einer Weile verstehst du deinen Fehler und wÃ¼rdest es gerne nochmal versuchen.");

    }else if($_POST[amount]>$session[user][goldinbank]){

        $lefttoborrow = $_POST[amount];

        $maxborrow = $session[user][level]*getsetting("borrowperlevel",20);

        if ($lefttoborrow<=$session[user][goldinbank]+$maxborrow){

            if ($session[user][goldinbank]>0){

                output("`6Du nimmst deine verbleibenden `^".$session[user][goldinbank]."`6 Gold und ");

                $lefttoborrow-=$session[user][goldinbank];

                $session[user][gold]+=$session[user][goldinbank];

                $session[user][goldinbank]=0;

                //debuglog("withdrew " . $_POST[amount] . " gold from the bank");

            }else{

                output("`6Du ");

            }

            if ($lefttoborrow-$session[user][goldinbank] > $maxborrow){

                output("fragst, ob du `^$lefttoborrow`6 Gold leihen kannst. Der kleine Mann informiert dich darÃ¼ber, dass er dir in deiner gegenwÃ¤rtigen Situation nur `^$maxborrow`6 Gold geben kann.");

            }else{

                output("leihst dir `^$lefttoborrow`6 Gold.");

                $session[user][goldinbank]-=$lefttoborrow;

                $session[user][gold]+=$lefttoborrow;

                //debuglog("borrows $lefttoborrow gold from the bank");

            }

        }else{

            output("`6Mit den schlappen `^{$session[user][goldinbank]}`6 Gold auf deinem Konto bittest du um einen Kredit von `^".($lefttoborrow-$session[user][goldinbank])."`6 Gold, aber

            der kurze kleine Mann informiert dich darÃ¼ber, dass du mit deinem Level hÃ¶chstens `^$maxborrow`6 Gold leihen kannst.");

        }

    }else{

        output("`^`bDu hast `&$_POST[amount]`^ Gold von deinem Bankkonto abgehoben. ");

        $session[user][goldinbank]-=$_POST[amount];

        $session[user][gold]+=$_POST[amount];

        //debuglog("withdrew " . $_POST[amount] . " gold from the bank");

        output("Du hast damit `&".$session[user][goldinbank]."`^ Gold auf deinem Konto und `&".$session[user][gold]."`^ Gold hast du bei dir.`b");

    }



}else if($_GET[op]=="gemdraw"){

      output("<form action='bank.php?op=gemdrawfinish' method='POST'>Du hast ".$session[user][gemsinbank]." Edelsteine bei der Bank.`n",true);

      output("`^Wieviel a<u>b</u>heben? <input id='input' name='amount' width=5 accesskey='b'> <input type='submit' class='button' value='Abheben'>`n`iGib 0 oder gar nichts ein, um alles abzuheben.`i</form>",true);

    output("<script language='javascript'>document.getElementById('input').focus();</script>",true);

      addnav("","bank.php?op=gemdrawfinish");

}else if($_GET[op]=="gemdrawfinish"){

    $_POST[amount]=abs((int)$_POST[amount]);

    if ($_POST[amount]==0){

        $_POST[amount]=abs($session[user][gemsinbank]);

    }

    if ($_POST[amount]>$session[user][gemsinbank]) {

        output("`\$FEHLER: Nicht genug auf dem Konto.`^`n`n");

        output("Nachdem du darÃ¼ber informiert wurdest, dass du `&".$session[user][gemsinbank]."`^ Edelsteine auf dem Konto hast, erklÃ¤rst du dem MÃ¤nnlein mit der Lesebrille, dass du gerne `&$_POST[amount]`^ davon abheben wÃ¼rdest.");

        output("`n`nDer Bankier schaut dich bedauernd an und erklÃ¤rt dir die Grundlagen der Mathematik. Nach einer Weile verstehst du deinen Fehler und wÃ¼rdest es gerne nochmal versuchen.");

    }else{

        output("`^`bDu hast `&$_POST[amount]`^ Edelsteine von deinem Bankkonto abgehoben. ");

        $session[user][gemsinbank]-=$_POST[amount];

        $session[user][gems]+=$_POST[amount];

        //debuglog("withdrew " . $_POST[amount] . " gems from the bank");

        output("Du hast damit `&".$session[user][gemsinbank]."`^ Edelsteine auf deinem Konto und `&".$session[user][gems]."`^ Edelsteine hast du bei dir.`b");

    }



}else if($_GET[op]=="gemdeposit"){

    output("<form action='bank.php?op=gemdepositfinish' method='POST'>Du hast ".$session[user][gemsinbank]." Edelsteine bei der Bank.`n",true);

    output("`^Wie <u>v</u>iel einzahlen?  <input id='input' name='amount' width=5 accesskey='v'> <input type='submit' class='button' value='Einzahlen'>`n`iGib 0 oder gar nichts ein, um alles einzuzahlen.`i</form>",true);

    output("<script language='javascript'>document.getElementById('input').focus();</script>",true);

    addnav("","bank.php?op=gemdepositfinish");

}else if($_GET[op]=="gemdepositfinish"){

    $_POST[amount]=abs((int)$_POST[amount]);

    if ($_POST[amount]==0){

        $_POST[amount]=$session[user][gems];

    }

    if ($_POST[amount]>$session[user][gems]){

        output("`\$FEHLER: Soviele Edelsteine hast du nicht dabei.`^`n`n");

        output("Du schmeiÃŸt deine `&".$session[user][gems]."`^ Edelsteine auf den Schaltertisch und erklÃ¤rst, dass du die ganzen `&$_POST[amount]`^ Edelsteine einzahlen mÃ¶chtest.");

        output("`n`nDer kleine alte Mann schaut dich nur verstÃ¤ndnislos an. Durch diesen seltsamen Blick verunsichert, zÃ¤hlst du noch einmal nach und erkennst deinen Irrtum. Verdammt, wozu soll ein Krieger rechnen kÃ¶nnen?");

    }else{

        output("`^`bDu zahlst `&$_POST[amount]`^ Edelsteine auf dein Konto ein. ");

        //debuglog("deposited " . $_POST[amount] . " Gems in the bank");

        $session[user][gemsinbank]+=$_POST[amount];

        $session[user][gems]-=$_POST[amount];

        output("Du hast damit `&".abs($session[user][gemsinbank])."`^ Edelsteine auf deinem Konto und `&".$session[user][gems]."`^ Edelsteine hast du bei dir.`b");

    }

}elseif($_GET[op]=="p38"){

    $sql = "SELECT * FROM items WHERE owner=".$session[user][acctid]." AND class='Dokument' AND name='`Ã¨Bescheinigung D17'";

    $result = db_query($sql);

    if (db_num_rows($result)>0){

        output("Der Bankier wedelt abwehrend mit seinen HÃ¤nden. Mehr als eine Bescheinigung brauchst du nicht!.`n`n");

    } else {

        output("Der Bankier nickt und sagt \"Eine Bescheinigung also? Die Bescheinigung D17 ist kostenlos, aber ich muss 5 GoldstÃ¼cke als BearbeitungsgebÃ¼hr verlangen.\"`n`n");

        output("`&5 GoldstÃ¼cke fÃ¼r Bescheinigung D17 ausgeben?`n");

            addnav("Kauf bestÃ¤tigen?");

            addnav("JA","bank.php?op=p38confirm");

        addnav("Nein","bank.php");

        addnav("Navigation");

    }

}elseif($_GET[op]=="p38confirm"){

    if($session['user']['gold']>=5) {

        output("Der Bankier reicht dir ein eng beschriebenes Pergament.");

        $sql="INSERT INTO items( name, owner, class, gold, gems, description ) VALUES ('`Ã¨Bescheinigung D17', '".$session[user][acctid]."', 'Dokument', '1', '0', 'Du hast diese Bescheinigung vom Bankier bekommen.')";



        db_query($sql);

        $session['user']['gold']-=5;

    } else {

        output("Der Bankier zeigt dir wo die TÃ¼r ist. \"Ohne BearbeitungsgebÃ¼hr auch keine Bescheinigung. Auf wiedersehen!`n`n");

    }

}



//addnav("ZurÃ¼ck zum Marktplatz","marktplatz.php");

addnav("ZurÃ¼ck zum Dorf","village.php");

if ($session[user][goldinbank]>=0){

    addnav("Abheben","bank.php?op=withdraw");

    addnav("Einzahlen","bank.php?op=deposit");

    if (getsetting("borrowperlevel",20)) addnav("Kredit aufnehmen","bank.php?op=borrow");

}else{

    addnav("Schulden begleichen","bank.php?op=deposit");

    if (getsetting("borrowperlevel",20)) addnav("Mehr leihen","bank.php?op=borrow");

}



if ($session[user][gemsinbank]>0) addnav("Edelsteine mitnehmen","bank.php?op=gemdraw");

if ($session[user][gems]>0) addnav("Edelsteine einlagern","bank.php?op=gemdeposit");



if (getsetting("allowgoldtransfer",1)){

    if ($session[user][level]>=getsetting("mintransferlev",3) || $session[user][dragonkills]>0){

        addnav("Gold Ã¼berweisen","bank.php?op=transfer");

    }

    addnav("Edelstein versenden","bank.php?op=gemtrans");

}



// Passierschein A38

if(getsetting("p38_quest",0) && $session['user']['p38'] & 32) {

    addnav("Besonderes");

    addnav("Bescheinigung D17","bank.php?op=p38");

}



page_footer();



?> 
