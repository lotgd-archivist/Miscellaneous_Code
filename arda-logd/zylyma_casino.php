<?php 
// some games inside. Look on Dragonprime for the single sources 

require_once "common.php"; 
checkday(); 
addcommentary(); 

page_header("`^Ca`Zsi`^no"); 
output("`n`c`^<h4>`^Ca`Zsi`^no</h4>`0`c",true); 
$jack=round(stripslashes(getsetting("jackpot",""))); 

if ($HTTP_GET_VARS[op]==""){ 
    output("`c`^Hi`Zer `Rgibt es alles, was das Spielerherz beg`Zeh`^rt.`n
`^Vo`Zm R`Roulette bis zur wilden Si`Zeb`^en`n
`^is`Zt a`Rlles vertr`Zet`^en.`n
`^Al`Zle`Rs ist hier in rotem Samt geha`Zlt`^en`n
`^un`Zd i`Rst darauf ausge`Zle`^gt,`n
`^da`Zß m`Ran sich hier wohl f`Züh`^lt`n
`^un`Zd v`Ror allem Gold l`Zäs`^st.`n
`^Wi`Zll`Rst auch du ein Spielchen w`Zag`^en?`n`n`n`c"); 
    if ($session[user][gold]<1){output("Du hast zwar kein Gold dabei aber gucken kann man ja mal.");} 
    output("`n`n`\$Auf einer Tafel steht mit Kreide geschrieben: <h5>`c`0JACKPOT `^$jack `0Gold`c</h5>",true); 
    addnav("Zurück nach Zylyma","marktplatz.php"); 
    addnav("Spiele"); 
    addnav("G?Spiele Glücksrad", "zylyma_casino.php?op=rad"); 
    addnav("K?Spiele höchste Karte", "zylyma_casino.php?op=highcard"); 
    addnav("6?Spiele Super 6", "supersix.php"); 
    addnav("W?Spiele Würfel", "zylyma_casino.php?op=slots"); 
    addnav("H?Spiele Hütchen", "zylyma_casino.php?op=hut"); 


    viewcommentary("necroncasino", "Fluche hier", 25, "flucht"); 
}else if ($HTTP_GET_VARS[op]=="rad"){ 

$cost = $session[user][level] * 50; 


if  (($_GET[cost]=="pay") && ($session[user][gold]>=$cost))    { 
        if ($HTTP_GET_VARS[cost] == "pay" ) redirect("zylyma_casino.php?op=rad&action=spiel"); 
    }    elseif (($_GET[cost]=="pay") && ($session[user][gold]<$cost))    { 

        addnav("Zurück nach Zylyma","marktplatz.php"); 
        output("`n`7".($session[user][sex]?"Der Mann ":"Die Frau")." schaut dich an und macht dich darauf aufmerksam, 
        daß Du nicht genug Gold zum spielen hast und Kredit gibts hier wohl nicht!"); 
    }elseif ($HTTP_GET_VARS[action]=="spiel")    { 

    if ($HTTP_GET_VARS[action] == "spiel" ) $pay = $cost; 

    $session[necron][jackpot]=$jack+$pay*0.1; 
    savesetting("jackpot",addslashes($session[necron][jackpot])); 
    $rand=e_rand(1,2000); 
    $session[user][gold]-=$cost; 
    $session[necron][jackpot]=$jack+=$cost*0.1; 
    savesetting("jackpot",addslashes($session[necron][jackpot])); 
    if ($rand==777){ 
        output("`c`^<h1>JACKPOT</h1>`c",true); 
        output("<h5>`c`@Im Casino bricht die Hölle los. Du weisst erst garnicht was passiert ist. Dann siehst Du, dass das 
        Rad auf dem `^goldenen `@Feld zum Stillstand gekommen ist. Jeder will dich beglückwünschen und dir die Hände 
        schütteln. `n`nDu wusstest es genau, heute ist `bDein`b Tag! Heute ist er gefallen!`c</h5>",true); 
        addnews("`^".$session[user][name]." `3hat im Casino Faire Chancen den Jackpot geknackt und `^$jack Gold `3gewonnen"); 
        $session[user][gold]+=$jack; 
        $session[necron][jackpot]=1000; 
        savesetting("jackpot",addslashes($session[necron][jackpot])); 
    }else if ($rand<=999){ 
        $session[user][gold]+=$cost*2; 
        output("`c`@".($session[user][sex]?"Der Mann":"Die Frau")." dreht das Rad. Ganz langsam läuft es aus. `n`n`\$Rot `0Weiss`@ 
        `0Weiss `\$Rot`@ `n`nVerdammt das `^goldene `@Feld ist schon wieder vorbei und es bleibt auf deiner Farbe stehen. `n`n`^Du hast GEWONNEN!`c"); 
        }else{ 
        output("`c`@".($session[user][sex]?"Der Mann":"Die Frau")." dreht das Rad. Ganz langsam läuft es aus. `n`n`\$Rot `0Weiss`@ 
        `0Weiss `\$Rot`@ `n`nVerdammt das `^goldene `@Feld ist schon wieder vorbei. `n`nEs bleibt stehen und du hast `n`n`^VERLOREN!`c"); 
    } 
    addnav("Nochmal spielen","zylyma_casino.php?op=rad"); 
    addnav("oder"); 
    addnav("Anderer Tisch ", "zylyma_casino.php"); 
    addnav("Zurück nach Zylyma","marktplatz.php"); 
}else{ 
    output("`c`3Du willst dich am Glücksrad versuchen. Wohlwissend, daß Du den Jackpot damit knacken könntest. 
    Das riesige Rad, fast bis bis zur Decke reichend. Rote und weisse Felder wechseln sich ab und nur eins, `^Goldfarbend 
    `3so klein und doch so anziehend.    Ein".($session[user][sex]?"muskulöser Kerl":"e leicht bekleidete Frau")." lächelt 
    dir gewinnend entgegen.    `n`n`^Du weisst es genau, heute ist `bDein`b Tag! Heute `\$`bmuss`b `^er fallen!`c`n"); 
    addnav("$cost Gold auf `\$Rot", "zylyma_casino.php?op=rad&cost=pay&color=Rot"); 
    addnav("$cost Gold auf `&Weiss", "zylyma_casino.php?op=rad&cost=pay&color=Weiss"); 
    addnav("oder"); 
    addnav("Anderer Tisch ", "zylyma_casino.php"); 
    addnav("Zurück nach Zylyma","marktplatz.php"); 
} 
}else if ($HTTP_GET_VARS[op]=="highcard"){ 
    $bet = abs((int)$HTTP_GET_VARS[bet] + (int)$HTTP_POST_VARS[bet]); 
    $enough = 1; 
    if($bet > $session[user][gold]) 
    { 
    output("`n`7".($session[user][sex]?"Der Mann ":"Die Frau")." schaut dich an und macht dich darauf aufmerksam, 
        daß Du nicht genug Gold zum spielen hast und Kredit gibts hier wohl nicht!"); 
    $enough = 0; 
    } 

    if($HTTP_GET_VARS[action]=="play" && !$bet) 
    { 
        output("`@".($session[user][sex]?"Der Mann":"Die Frau")." sagt: \"`^Dein Einsatz bitte!`@\""); 
    addnav("Einsatz 10", "zylyma_casino.php?op=highcard&action=play&bet=10"); 
    addnav("Einsatz 100", "zylyma_casino.php?op=highcard&action=play&bet=100"); 
    addnav("Einsatz 250", "zylyma_casino.php?op=highcard&action=play&bet=250"); 
    addnav("Einsatz 500", "zylyma_casino.php?op=highcard&action=play&bet=500"); 
    addnav("Einsatz 1000", "zylyma_casino.php?op=highcard&action=play&bet=1000"); 

    } 
    if($HTTP_GET_VARS[action] == "play" && $bet && $enough) 
    { 
    addnav("Nochmal spielen", "zylyma_casino.php?op=highcard&action=play"); 
    $yourcard = e_rand(2, 14); 
    $yoursuit = e_rand(0, 3); 
    $mycard = e_rand(2, 14); 
    $mysuit = e_rand(0, 3); 
    $suitName = array("Karo", "Herz", "Pik", "Kreuz"); 
    $specialCard = array("Bauer", "Dame", "König", "As"); 
    if(($yourcard == $mycard) && ($mysuit == $yoursuit)) 
      $result = 0; 
    if(($yourcard == $mycard) && ($mysuit < $yoursuit)) 
      $result = 1; 
    if(($yourcard == $mycard) && ($mysuit > $yoursuit)) 
      $result = -1; 
    if($yourcard > $mycard) 
      $result = 1; 
    if($yourcard < $mycard) 
      $result = -1; 
    if($yourcard > 10) 
    { 
        $yourcard = $specialCard[$yourcard - 11]; 
    } 
    if($mycard > 10) 
    { 
        $mycard = $specialCard[$mycard - 11]; 
    } 
    $session[necron][jackpot]=$jack+$bet*0.1; 
    savesetting("jackpot",addslashes($session[necron][jackpot])); 

    output("`2Du wettest: `^$bet Gold`2"); 
    output("`n`nMeine Karte ist: ".$suitName[$mysuit]." $mycard" ); 
    output("`n`nDeine Karte ist: ".$suitName[$yoursuit]." $yourcard"); 
     switch($result) 
    { 
     case 1: 
        output("`n`n`^Du gewinnst!!!"); 
        $session[user][gold] += $bet; 
        break; 
     case 0: 
        output("`n`n`^Gleichstand. Behalte dein Geld"); 
        break; 
     case -1: 
        output("`n`n`^Ich gewinne, her mit dem Geld!"); 
        $session[user][gold] -= $bet; 
        break; 
    } 
    } 
    if($HTTP_GET_VARS[action]=="") 
    { 
    output("`3Du schlenderst rüber zu einem der schmutzigen hölzernen Tische. Du kannst sehen, daß Bier über den Tisch 
    verschüttet wurde und zerbrochenes Glas sich davor verteilt. Also die besten Vorraussetzungen für ein dreck...., aehm 
    sauberes Spiel.`n`nEs gibt aber eine gute Nachricht denn ".($session[user][sex]?"der Mann":"die Frau")." vor dir, 
    mischt zwei Decks Karten und hält ein Stapel Gold bereit, daß das deine sein könnte.`n`n"); 
    addnav("Spielen", "zylyma_casino.php?op=highcard&action=play"); 
    } 
    addnav("oder"); 
    addnav("Anderer Tisch ", "zylyma_casino.php"); 
    addnav("Zurück nach Zylyma","marktplatz.php"); 
}else if ($HTTP_GET_VARS[op]=="21"){ 

    addnav("oder"); 
    addnav("Anderer Tisch ", "zylyma_casino.php"); 
    addnav("Zurück nach Zylyma","marktplatz.php"); 
}else if ($HTTP_GET_VARS[op]=="high"){ 

    addnav("oder"); 
    addnav("Anderer Tisch ", "zylyma_casino.php"); 
    addnav("Zurück nach Zylyma","marktplatz.php"); 
}else if($HTTP_GET_VARS[op] == "slots"){ 

    $enough = 1; 
    if($HTTP_GET_VARS[bet]) 
    { 
    if($session[user][gold] < $HTTP_GET_VARS[bet]) 
    { 
        output("`n`7".($session[user][sex]?"Der Mann ":"Die Frau")." schaut dich an und macht dich darauf aufmerksam, 
        daß Du nicht genug Gold zum spielen hast und Kredit gibts hier wohl nicht!"); 
        $enough = 0; 
    } 
    $session[necron][jackpot]=$jack+$bet*0.1; 
    savesetting("jackpot",addslashes($session[necron][jackpot])); 
    $_SESSION[bet] = $HTTP_GET_VARS[bet]; 
    $session[user][gold] -= $HTTP_GET_VARS[bet]; 
    if($enough){ 
      addnav("Würfeln", "zylyma_casino.php?op=slots&action=play&turn=1"); 
    } 
    }else if($HTTP_GET_VARS[action] == "play" ){ 
    if($HTTP_GET_VARS[turn] == 1) 
    { 
        $_SESSION[user][first] = e_rand(1,6); 
        output("`2`bDein erster Wurf gibt eine`b`n`n"); 
        output("`%| " . $_SESSION[user][first] . " |  |  |`n"); 
        addnav("Weiter", "zylyma_casino.php?op=slots&action=play&turn=2"); 
    }elseif($HTTP_GET_VARS[turn] == 2) 
        { 
        $_SESSION[user][secondd] = e_rand(1,6); 
        output("`2`bDein zweiter Wurf gibt eine`b`n`n"); 
        output("`%| " . $_SESSION[user][first] . " | " . $_SESSION[user][secondd] . " |  |`n"); 
        addnav("Weiter", "zylyma_casino.php?op=slots&action=play&turn=3"); 
        }elseif($HTTP_GET_VARS[turn] == 3) 
        { 
            $_SESSION[user][thirdd] = e_rand(1,6); 
            output("`2`bDein dritter Wurf gibt eine`b`n`n"); 
            output("`%| " . $_SESSION[user][first] . " | " . $_SESSION[user][secondd] . " | " . $_SESSION[user][thirdd] . " |`n"); 
            addnav("Spiel nochmal", "zylyma_casino.php?op=slots"); 
            $one = 0; 
            $two = 0; 
            $three = 0; 
            $four = 0; 
            $five = 0; 
            $six = 0; 
            if($_SESSION[user][first] == "1"){$one++;} 
            if($_SESSION[user][secondd] == "1"){$one++;} 
            if($_SESSION[user][thirdd] == "1"){$one++;} 
            if($_SESSION[user][first] == "2"){$two++;} 
            if($_SESSION[user][secondd] == "2"){$two++;} 
            if($_SESSION[user][thirdd] == "2"){$two++;} 
            if($_SESSION[user][first] == "3"){$three++;} 
            if($_SESSION[user][secondd] == "3"){$three++;} 
            if($_SESSION[user][thirdd] == "3"){$three++;} 
            if($_SESSION[user][first] == "4"){$four++;} 
            if($_SESSION[user][secondd] == "4"){$four++;} 
            if($_SESSION[user][thirdd] == "4"){$four++;} 
            if($_SESSION[user][first] == "5"){$five++;} 
            if($_SESSION[user][secondd] == "5"){$five++;} 
            if($_SESSION[user][thirdd] == "5"){$five++;} 
            if($_SESSION[user][first] == "6"){$six++;} 
            if($_SESSION[user][secondd] == "6"){$six++;} 
            if($_SESSION[user][thirdd] == "6"){$six++;} 
            if(($two==3) || ($three==3) || ($four==3) || ($five==3)) 
            { 
            output("`n`n`3 3 Gleiche!  Du `2verdoppelst`3 dein Einsatz!!"); 
            output("`n`n`@".($session[user][sex]?"Der Mann ":"Die Frau")." schiebt dir deine `^" . $_SESSION[bet] * 2 . "`@ Gold über den Tisch!!"); 
            $session[user][gold] += $_SESSION[bet] * 2; 
            } 
            else if($one == 3) 
            { 
            output("`n`n`3 3 Einsen!  Du erhälst den `2dreifachen`3 Einsatz!!"); 
            output("`n`n`@".($session[user][sex]?"Der Mann ":"Die Frau")." schiebt dir deine `^" . $_SESSION[bet] * 3 . "`@ Gold über den Tisch!!"); 
            $session[user][gold] += $_SESSION[bet] * 3; 
            } 
            else if($six == 3) 
            { 
            output("`n`n`3 3 Sechsen!  Du `2vervierfachst`3 deinen Einsatz!!!"); 
            output("`n`n`@".($session[user][sex]?"Der Mann ":"Die Frau")." schiebt dir deine `^" . $_SESSION[bet] * 4 . "`@ Gold über den Tisch!!"); 
            $session[user][gold] += $_SESSION[bet] * 4; 
            } 
            else if(($one==2) || ($two==2) || ($three==2) || ($four==2) || ($five==2) || ($six==2)) 
            { 
            output("`n`n`3 2 Gleiche!  Du `2behälst`3 dein Geld!!"); 
            output("`n`n`@".($session[user][sex]?"Der Mann ":"Die Frau")." schiebt dir deine `^" . $_SESSION[bet] . "`@ Gold über den Tisch!!"); 
            $session[user][gold] += $_SESSION[bet]; 
            } 
            else 
            { 
            output("`n`n`^Du verlierst, sorry"); 
            } 
        } 
    } 
    else 
    { 
    $_SESSION[user][first] = 0; 
    $_SESSION[user][secondd] = 0; 
    $_SESSION[user][thirdd] = 0; 
    output("`n`n`@".($session[user][sex]?"Der Mann ":"Die Frau")." erklärt dir kurz die Regeln.`n`n"); 
    output("`^Du wettest und würfelst. Das ist alles!"); 
    output("`n`n2x das Gleiche gibt deinen Einsatz zurück`n"); 
    output("`n3x die 2, 3, 4 oder 5 `\$verdoppelt`^ deinen Einsatz`n"); 
    output("`n3x die 1 `\$verdreifacht`^ deinen Einsatz`n"); 
    output("`n3x die 6 `\$vervierfacht`^ deinen Einsatz`n"); 
    output("`n`b`5Wieviel willst du einsetzen?`b"); 

    addnav("Einsatz 10", "zylyma_casino.php?op=slots&bet=10&turn=1"); 
    addnav("Einsatz 100", "zylyma_casino.php?op=slots&bet=100&turn=1"); 
    addnav("Einsatz 250", "zylyma_casino.php?op=slots&bet=250&turn=1"); 
    addnav("Einsatz 500", "zylyma_casino.php?op=slots&bet=500&turn=1"); 
    addnav("Einsatz 1000", "zylyma_casino.php?op=slots&bet=1000&turn=1"); 
    } 
    addnav("oder"); 
    addnav("Anderer Tisch ", "zylyma_casino.php"); 
    addnav("Zurück nach Zylyma","marktplatz.php"); 
}else if ($HTTP_GET_VARS[op]=="stones"){ 

    addnav("oder"); 
    addnav("Anderer Tisch ", "zylyma_casino.php"); 
    addnav("Zurück nach Zylyma","marktplatz.php"); 
}else if ($HTTP_GET_VARS[op]=="hut"){ 

$cost = $session[user][level] * 10; 
$cost1 = $session[user][level] * 20; 
$cost2 = $session[user][level] * 50; 


if ( (($_GET[cost]=="pay1") && ($session[user][gold]>=$cost)) || 
     (($_GET[cost]=="pay2") && ($session[user][gold]>=$cost1)) || 
     (($_GET[cost]=="pay3") && ($session[user][gold]>=$cost2)) ) 
    { 
        if ($HTTP_GET_VARS[cost] == "pay1" ) redirect("zylyma_casino.php?op=hut&action=spiel1"); 
        if ($HTTP_GET_VARS[cost] == "pay2" ) redirect("zylyma_casino.php?op=hut&action=spiel2"); 
        if ($HTTP_GET_VARS[cost] == "pay3" ) redirect("zylyma_casino.php?op=hut&action=spiel3"); 
    } 
elseif ((($_GET[cost]=="pay1") && ($session[user][gold]<$cost)) || 
         (($_GET[cost]=="pay2") && ($session[user][gold]<$cost1)) || 
         (($_GET[cost]=="pay3") && ($session[user][gold]<$cost2)) ) 
    { 
        addnav("Zurück nach Zylyma","marktplatz.php"); 
        output("`n`7".($session[user][sex]?"Der Mann ":"Die Frau")." schaut dich an und macht dich darauf aufmerksam, 
        daß Du nicht genug Gold zum spielen hast und Kredit gibts hier wohl nicht!"); 
    } 
elseif (($HTTP_GET_VARS[action]=="spiel1") || ($HTTP_GET_VARS[action]=="spiel2") || ($HTTP_GET_VARS[action]=="spiel3")){ 
    if ($HTTP_GET_VARS[action] == "spiel1" ) $pay = $cost; 
    if ($HTTP_GET_VARS[action] == "spiel2" ) $pay = $cost1; 
    if ($HTTP_GET_VARS[action] == "spiel3" ) $pay = $cost2; 
    $session[necron][jackpot]=$jack+$pay*0.1; 
    savesetting("jackpot",addslashes($session[necron][jackpot])); 
    $round = rand(1,3); 
    output ("Das Spiel beginnt!`n`n"); 
    output("`2".($session[user][sex]?"Der Mann":"Die Frau")." stülpt eins der kleinen, schwarzen Hütchen über die 
    Glasperle und beginnt diese wie wild über den Tisch zu schieben.`n`n"); 
    output("Ziemlich schnell hast du das richtige Hütchen aus den Augen verloren so das du nicht mehr weisst wo nun 
    die Perle ist. Am Ende deutest du einfach auf eins der schwarzen Hütchen.`n`n"); 
    if ($round == 1) { 
        $session[user][gold]+=$pay*3; 
        output(($session[user][sex]?"Er ":"Sie")." hebt das Hütchen an und die Perle liegt drunter. `\$Du hast gewonnen!"); 
    } 
    if ($round == 2 || $round == 3) { 
        $session[user][gold]-=$pay; 
        output(($session[user][sex]?"Er ":"Sie")." hebt das Hütchen an und zeigt das nichts drunter ist. `\$Du hast verloren!"); 
    } 
    addnav("Nochmal spielen","zylyma_casino.php?op=hut"); 
    addnav("oder"); 
    addnav("Anderer Tisch ", "zylyma_casino.php"); 
    addnav("Zurück nach Zylyma","marktplatz.php"); 
}else{ 
     
     
    output("`3Du schlenderst interessiert zu einem Tisch auf dem 3 kleine, schwarze Hütchen stehen und siehst eine 
    Glasperle, welche davor liegt. ".($session[user][sex]?"Ein Mann ":"Eine Frau")." erklärt das Spiel.`n`n"); 
    output(($session[user][sex]?"Er ":"Sie")." wird die Glasperle unter einem der Hüte verstecken und diese dann 
    auf dem Tisch verschieben. `nDu gewinnst wenn du das richtige Hütchen am Ende kennst.`n`n"); 
    output(($session[user][sex]?"Er ":"Sie")." fragt dich mit einem Lächeln ob du es mal probieren willst.`n`n"); 
    output("`3Du kannst für`^ $cost, $cost1 or $cost2`3 Gold wetten.`n`n"); 
    output("Bei Gewinn bekommst du den `^3fachen `3Einsatz zurück."); 
    addnav("Wette `^ $cost Gold","zylyma_casino.php?op=hut&cost=pay1"); 
    addnav("Wette `^ $cost1 Gold","zylyma_casino.php?op=hut&cost=pay2"); 
    addnav("Wette `^ $cost2 Gold","zylyma_casino.php?op=hut&cost=pay3"); 
    addnav("oder"); 
    addnav("Anderer Tisch ", "zylyma_casino.php"); 
    addnav("Zurück nach Zylyma","marktplatz.php"); 
}} 

page_footer(); 
?> 