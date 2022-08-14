
ï»¿<?php



// 28102004



// Datei:  forestlake.php

// Datum:  01.05.2004 ff

// Author: Durandil

// Inhalt: Flirt mit Aurelia / allgemein Flirt mit Partner im Wald.



/* Installation:

 - forestlake.php -> /logd/special/

 - forestlakepath.php -> /logd/

 - VerknÃ¼pfung in village.php (z.B. unter Wohnviertel) oder common.php (unter Plumpsklo-Zeile)

   mit folgender Zeile eintragen:

   if (@file_exists("forestlakepath.php")) addnav("Trampelpfad","forestlakepath.php");

*/



if (!isset($session)) exit();



// Einstellungen ...............................................................

$accountallowed = FALSE; // MuÃŸ auf FALSE stehen... wird spÃ¤ter automatisch berechnet.

if ($_GET[ziel]=="forestlake") {

    $allowtovillage = TRUE;

    $allowtoforest  = FALSE;

}else{

    $allowtovillage = FALSE;

    $allowtoforest  = TRUE;

}

$forestlakedebugoutput = FAlSE;

// .............................................................................



// Daten des Partners aus Datenbank suchen......................................

$lover = "";

$loverID = $session[user][marriedto];

$loverHorse = "Pferd";

$loverHorseID = 0;

$loved = $session[user][login];

$lovedHorse = "Pferd";

$lovedID = $session[user][acctid];

$lovedHorseID = $session[user][hashorse];

if ($session[user][marriedto]>0){

    // $sql = "SELECT acctid,login,marriedto,hashorse FROM accounts WHERE acctid=$loverID AND marriedto=$lovedID ORDER BY acctid DESC";

    $sql = "SELECT acctid,login,marriedto,charisma,hashorse FROM accounts WHERE acctid=$loverID ORDER BY acctid DESC";

     $result = db_query($sql) or die(db_error(LINK));

      if (db_num_rows($result)>0){

        $row = db_fetch_assoc($result);

            $lover = $row[login];

            $loverHorseID = $row[hashorse];

      }

}

if ($loverID==4294967295) { if ($session[user][sex]==0) { $lover = "Violet"; } else { $lover = "Seth"; } }

if ($row[charisma]==4294967295 && $row[marriedto]!=$session[user][acctid]) $loverID=0;





if (strcmp($session[user][acctid],$session[user][marriedto])<0){

    $chat = "Clearing_".$session[user][acctid]."_".$session[user][marriedto];

} else {

    $chat = "Clearing_".$session[user][marriedto]."_".$session[user][acctid];

}



if ($forestlakedebugoutput==TRUE){

    output("`nlogin: (".$session[user][acctid].") ".$session[user][login]);

      output("`nhorse: ".$lovedHorseID);

      output("`nlover: (".$loverID.") ".$lover);

      output("`nhorse: ".$loverHorseID);

      output("`nchat: ".$chat."`n");

}



if ($session[user][specialmisc]=="") $session[user][specialmisc]="done:";



switch($HTTP_GET_VARS[op]){

case "search":

case "":

    // Zutritt ...................................................................

        $session[user][specialinc] = "forestlake.php";

        output("`@WÃ¤hrend Du durch den Wald lÃ¤ufst, fÃ¤llt Dir ein Baum auf, in den etwas geritzt ist. Aus der NÃ¤he siehst Du, dass es ein Herz ist, in das ");

        if ($loverID==0) { output("`$ Aurelia & Durandil forever"); } else { output("`$ $loved & $lover forever"); }

        output(" `@ geritzt ist.");

        output("`@AuÃŸerdem fallen Dir kleine Zweige auf, die alle so geflochten scheinen, als wÃ¼rden sie in eine bestimmte Richtung zeigen. ");

        if ($loverID>0) {

        if ($loverID==4294967295) {

            output("Du Ã¼berlegst, den Pfeilen zu folgen, aber denkst Dir dann, dass diese Zeichen bestimmt nicht fÃ¼r Dich bestimmt sind, weil Dein Schatz ja noch in der Kneipe arbeitet und gar nicht hier im Wald sein kann.`n`n");

              } else {

            output("Du Ã¼berlegst, den Pfeilen zu folgen, denn irgendwie hast Du ein gutes GefÃ¼hl dabei und muÃŸt an Deinen Schatz denken.`n`n");

                    addnav("Folge den Pfeilen","forest.php?op=clearing");

              }

        } else {

        output("Aber da Du eh' Single bist, kann dieses Zeichen gar nicht fÃ¼r Dich bestimmt sein, und Du ziehst Dich diskret in den Wald zurÃ¼ck.`n`n");

        }

        output("`n`n`n`n`n`n`n`n`n`n`$@`@}-,-Â´-");

        if ($allowtovillage==TRUE) addnav("ZurÃ¼ck ins Dorf","village.php");

        if ($allowtoforest==TRUE) addnav("ZurÃ¼ck in den Wald","forest.php?op=runaway");

      break;

      // Auf der Lichtung ..........................................................

      case "clearing":

        output("`@Nach wenigen hundert Metern kommst Du an eine kleine Lichtung, die an einem klaren Waldsee liegt.`n");

        output("`@Auf den ersten Blick siehst Du, dass erst vor kurzem noch jemand hier war. Aber bevor Du Dir groÃŸ Gedanken darÃ¼ber machen kannst, spÃ¼rst Du wie sich plÃ¶tzlich von hinten zwei Arme um Dich schlingen...`n`n");

        output("`^$lover`& kÃ¼sst Dich sanft in den Nacken und flÃ¼stert Dir in's Ohr: `3\"`#Hallo Liebling, schÃ¶n dass Du hierher gefunden hast.`n");

        output("Was hÃ¤ltst Du davon, wenn wir uns einfach mal einen schÃ¶nen Tag machen? Auf was hÃ¤ttest Du Lust?`3\"`n`n");

        output("`@Auf der Lichtung vor Dir bemerkst Du nun eine von Rosen umkranzte Decke, neben der auch ein Picknickkorb zu stehen scheint. ");

        output("`@Etwas weiter links lÃ¤dt der See mit seinem klaren Wasser zum Schwimmen ein. ");

        output("`@Und ganz hinten scheinen die Sonnenstrahlen durch auf einen umgefallenen Baum, der jedoch noch sehr stabil aussieht und der ideale Platz wÃ¤re, sich zu einem kleinen Plausch zu setzen.`n");

        if ($loverHorseID+$lovedHorseID>0) {

        output("`@In nicht allzu weiter Ferne Ã¼ber den Baumwipfel seht ihr zudem eine Turmruine. Wenn ihr wollt, kÃ¶nnt ihr auch gemeinsam einen Ausritt dorthin unternehmen.`n");

        } else {

        output("`@Wenn einer von euch ein Reittier besitzen wÃ¼rde, kÃ¶nntet ihr auch einen kleinen Ausritt unternehmen - Aber ihr habt ja genug netter MÃ¶glichkeiten.`n");

        }

        output("`n`n`n`n`n`n`n`n`n`n`$@`@}-,-Â´-");

        if (!strpos($session[user][specialmisc],"_swim")) addnav("Im See schwimmen","forest.php?op=swim");

        if (!strpos($session[user][specialmisc],"_music")) addnav("Musizieren","forest.php?op=music");

        if (!strpos($session[user][specialmisc],"_picknick")) addnav("Picknicken","forest.php?op=picknick");

        if (!strpos($session[user][specialmisc],"_flirt")) addnav("Plaudern","forest.php?op=flirt");

        if ($loverHorseID+$lovedHorseID>0 && !strpos($session[user][specialmisc],"_ride") ) addnav("Ausreiten","forest.php?op=ride");

        if ($allowtovillage==TRUE) addnav("ZurÃ¼ck ins Dorf","village.php");

        if ($allowtoforest==TRUE) addnav("ZurÃ¼ck in den Wald","forest.php?op=return");

        $session[user][specialinc] = "forestlake.php";

      break;

// Ausreiten .................................................................

case "ride":

    $session[user][specialmisc].="_ride";

    // Besorge Namen der Tiere

        $sql = "SELECT mountid,mountname FROM mounts WHERE mountid=$loverHorseID OR mountid=$lovedHorseID ORDER BY mountid DESC";

        $result = db_query($sql) or die(db_error(LINK));

        if (db_num_rows($result)>0) {

        $row = db_fetch_assoc($result);

              if ($row[mountid]==$loverHorseID) $loverHorse = $row[mountname];

              if ($row[mountid]==$lovedHorseID) $lovedHorse = $row[mountname];

        }

        if (($loverHorseID==0) && ($lovedHorseID>0)){ // Du hast ein Pferd

        output("`@Da $lover kein eigenes Reittier besitzt, stellst Du Deinen $lovedHorse zur VerfÃ¼gung. Ihr reitet gemeinsam den See entlang.`n");

        } else if (($loverHorseID>0) && ($lovedHorseID==0)) // Der andere hat ein Pferd

            { output("`@Du hast zwar kein Reittier, aber $lover hilft Dir und ihr reitet gemeinsam auf $loverHorse den See entlang.`n");

       } else // Beide besitzen Tiere

            { output("`@Ihr schwingt euch auf eure Tiere, Du auf Dein $lovedHorse und $lover auf einem $loverHorse, und reitet den See entlang.`n");

        }

        output("`@Am hinteren Ende des Sees angekommen seht ihr, dass der Zufluss sich in Richtung der Turmruine durch den Wald windet, und dass es euch ein schmaler Streifen Wiese erlaubt, dorthin zu reiten.`n");

        output("  `n`@`^$lover`@ ruft Dir frÃ¶hlich zu:`n");

        output("          `3\"`#Komm schon $loved, wer zuerst da ist gewinnt und darf sich etwas wÃ¼nschen!`3\"`n`n");

        output("`@Kopf an Kopf wetteifernd eilt ihr den Fluss entlang, und schon bald bemerkt ihr, wie der Boden sich hebt und ihr einen HÃ¼gel hinaufreitend dem Turm nÃ¤her kommt, und innerhalb einer knappen Viertelstunde seid ihr an der Ruine angekommen.`n`n");

        output("`@Der Turm scheint ein alter Signalturm gewesen sein, wirkt allerdings schon ziemlich mitgenommen; die Zinnen Ã¼ber dem dritten Stockwerk sind bereits weggebrÃ¶ckelt, und auch die Decken sind teilweise eingestÃ¼rzt.`n");

        output("`@Trotzdem macht die Ruine irgendwie einen romantischen Eindruck, und Du kannst Dir vorstellen, dass man von oben - sofern die Treppen noch vorhanden sein sollten - bestimmt einen groÃŸartigen Ausblick hat. Wollt ihr euch den Turm nichtmal nÃ¤her anschauen?`n");

        output("`n`n`n`n`n`n`n`n`n`n`$@`@}-,-Â´-");

        addnav("Turm untersuchen","forest.php?op=towerruin");

        addnav("ZurÃ¼ck zur Lichtung","forest.php?op=rideback");

        $session[user][specialinc] = "forestlake.php";

      break;

  // Im Turm ...................................................................

  case "towerruin":

      output("`@Ihr betretet Hand in Hand das ErdgeschoÃŸ des Turms. Das GlÃ¼ck ist euch hold, denn die Holzdecken oberhalb des ersten Obergeschosses sind zwar eingestÃ¼rzt, die an den WÃ¤nden entlanglaufende Treppe ist jedoch aus Stein gemauert und hÃ¤lt euch, so dass ihr gemeinsam nach oben gehen und die Aussicht genieÃŸen kÃ¶nnt.`n");

        output("`@Und diese ist noch weit schÃ¶ner als ihr euch vorgestellt habt - soweit das Auge reicht seht ihr grÃ¼ne MischwÃ¤lder, gelegentlich unterbrochen von riesigen erhabenen Sequoias, die noch aus einer frÃ¼heren Zeit zu stammen scheinen. ");

        output("`@Ihr beobachtet, wie der Fluss sich frÃ¶hlich in das Tal schlÃ¤ngelt, in den See fliesst, und sich mit kleinen SeitenlÃ¤ufen vereint in der Ferne verliert...`n`n");

        output("`@Doch wÃ¤hrend ihr so verschlungen da oben steht, merkt ihr, wie der Wind immer heftiger an euch rÃ¼ttelt, und als ihr euch umdreht, seht ihr eine dunkle Wetterfront auf euch zuziehen.`n");

        output("`@Ihr bleibt trotzdem noch eine Weile oben, lasst den Wind durch eure Haare streifen und beobachtet, wie es in der Ferne blitzt und donnert. ");

        output("`@Erst als es ungemÃ¼tlich nass wird, eilt ihr die Treppen wieder herunter, macht es euch in einer trockenen Ecke des Untergeschosses mit ein paar Decken gemÃ¼tlich und verbringt die Zeit bis zum Ende des Unwetters damit, den Gewalten der Natur zu lauschen und miteinander zu flÃ¼stern.`n`n");

        viewcommentary($chat,"FlÃ¼stern",10,"flÃ¼stert zÃ¤rtlich");

        output("`n`n`n`n`n`n`n`n`n`n`$@`@}-,-Â´-");

        addnav("ZurÃ¼ck zur Lichtung","forest.php?op=rideback");

        $session[user][specialinc] = "forestlake.php";

      break;

 // ZurÃ¼ck vom Ausritt ........................................................

 case "rideback":

        output("`@Ihr beeilt euch, zurÃ¼ck zur Waldlichtung zu kommen, schlieÃŸlich wartet da immer noch der Picknickkorb auf euch, und eine Runde im See zu schwimmen wÃ¤re auch eine gute Idee. Oder wollt ihr einfach nur ein gemÃ¼tliches PlÃ¤uschchen halten?`n");

        output("`n`n`n`n`n`n`n`n`n`n`$@`@}-,-Â´-");

        if (!strpos($session[user][specialmisc],"_swim")) addnav("Im See schwimmen","forest.php?op=swim");

        if (!strpos($session[user][specialmisc],"_music")) addnav("Musizieren","forest.php?op=music");

        if (!strpos($session[user][specialmisc],"_picknick")) addnav("Picknicken","forest.php?op=picknick");

        if (!strpos($session[user][specialmisc],"_flirt")) addnav("Plaudern","forest.php?op=flirt");

        if ($loverHorseID+$lovedHorseID>0 && !strpos($session[user][specialmisc],"_ride") ) addnav("Ausreiten","forest.php?op=ride");

        if ($allowtovillage==TRUE) addnav("ZurÃ¼ck ins Dorf","village.php");

        if ($allowtoforest==TRUE) addnav("ZurÃ¼ck in den Wald","forest.php?op=return");

        $session[user][specialinc] = "forestlake.php";

      break;

 // Zusammen Musik machen .....................................................

 case "music":

    $session[user][specialmisc].="_music";

       output("`3\"`#Wollen wir nicht zusammen etwas singen?`3\" fragt $lover, packt eine Laute aus und fÃ¤ngt an, die Saiten zu stimmen.`n`n");

        output("`@Als $lover damit fertig ist, stimmt Du leise ein Lied an, und $lover begleitet Dich mit sanften KlÃ¤ngen...`n`n`n`%");



        $rand = e_rand(0,1); // ZufÃ¤llige Ereignisse wÃ¤hrend des Schwimmens.

        switch($rand) {

        case 0:

                if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/rightherewaitingforyou.mid\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);

                output("Oceans apart day after day`nAnd I slowly go insane`nI hear your voice on the line`nBut it doesn't stop the pain`n`n");

                output("If I see you next to never`nHow can we say forever`n`n");

                output("Wherever you go`nWhatever you do`nI will be right here waiting for you`nWhatever it takes`nOr how my heart breaks`nI will be right here waiting for you`n`n");

                output("I took for granted, all the times`nThat I though would last somehow`nI hear the laughter, I taste the tears`nBut I can't get near you now`n`n");

                output("Oh, can't you see it baby`nYou've got me goin' crazy`n`n");

                output("Wherever you go`nWhatever you do`nI will be right here waiting for you`nWhatever it takes`nOr how my heart breaks`nI will be right here waiting for you`n`n");

                output("I wonder how we can survive`nThis romance`nBut in the end if I'm with you`nI'll take the chance`n`n");

                output("Oh, can't you see it baby`nYou've got me goin' crazy`n`n");

                output("Wherever you go`nWhatever you do`nI will be right here waiting for you`nWhatever it takes`nOr how my heart breaks`nI will be right here waiting for you`n`n");

              break;

              case 1:

              output("Take me now baby here as I am`nPull me close try an understand`nI work all day out in the hot sun`nStay with me now till the mornin' comesCome on now try and understand`nThe way I feel when I'm in your hands`nTake me now as the sun descends`nThey can't hurt you now`nThey can't hurt you now`nThey can't hurt you now`n`n");

                output("Because the night belongs to lovers`nBecause the night belongs to us`nBecause the night belongs to lovers`nBecause the night belongs to us`n`n");

                output("What I got I have earned`nWhat I'm not I have learned`nDesire and hunger is the fire I breathe`nJust stay in my bed till the morning comes`nCome on now try and understand`nThe way I feel when I'm in your hands`nTake me now as the sun descends`nThey can't hurt you now`nThey can't hurt you now`nThey can't hurt you now`n`n");

                output("Because the night...`n`n");

                output("Your love is here and now`nThe vicious circle turns and burns without`nThough I cannot live forgive me now`nThe time has come to take this moment and`nThey can't hurt you now`n`n");

                output("Because the night...`n`n");

              break;

        }

        output("`@Leise lÃ¤sst Du Deinen Gesang zu den letzten Akkorden ausklingen, und ihr sitzt noch minutenlang da und schaut euch vertrÃ¤umt in die Augen...`n");

        output("`n`n`n`n`n`n`n`n`n`n`$@`@}-,-Â´-");

        if (!strpos($session[user][specialmisc],"_swim")) addnav("Im See schwimmen","forest.php?op=swim");

        if (!strpos($session[user][specialmisc],"_music")) addnav("Musizieren","forest.php?op=music");

        if (!strpos($session[user][specialmisc],"_picknick")) addnav("Picknicken","forest.php?op=picknick");

        if (!strpos($session[user][specialmisc],"_flirt")) addnav("Plaudern","forest.php?op=flirt");

        if ($loverHorseID+$lovedHorseID>0 && !strpos($session[user][specialmisc],"ride") ) addnav("Ausreiten","forest.php?op=ride");

        if ($allowtovillage==TRUE) addnav("ZurÃ¼ck ins Dorf","village.php");

        if ($allowtoforest==TRUE) addnav("ZurÃ¼ck in den Wald","forest.php?op=return");

        $session[user][specialinc] = "forestlake.php";

      break;

  // Im See Schwimmen ..........................................................

 case "swim":

        output("`@Noch etwas schÃ¼chtern entkleidest du dich, genieÃŸt aber doch, wie `^$lover`@ deinen nackten KÃ¶rper bewundert und sich ebenfalls zu entkleiden beginnt. ");

        output("`@Nackt wie von Gott geschaffen schwimmt ihr gemeinsam an das andere Ufer und zurÃ¼ck, wo ihr im seichteren Wasser herumtollt und euch gegenseitig unter Wasser zu drÃ¼cken versucht.");

        $rand = e_rand(1,50); // ZufÃ¤llige Ereignisse wÃ¤hrend des Schwimmens.

        switch ($rand){

        case 1:

                output("`n`n`^WÃ¤hrend Du untertauchst, siehst Du etwas am Boden des Sees schimmern. Bei nÃ¤herem Hinsehen erkennst Du einen Ring. Von einem unguten GefÃ¼hl erfasst beschlieÃŸt Du, den Ring ganz schnell wieder zu vergessen, und widmest Dich schnell wieder ganz und gar $lover.");

                $exp = e_rand(20,200);

                output("`n`n`^Du erhÃ¤ltst $exp Erfahrungspunkt fÃ¼r diese weise Entscheidung.");

                $session[user][experience]+=$exp;

              break;

              case 2:

              output("`n`n`^WÃ¤hrend Du untertauchst, siehst Du etwas am Boden des Sees schimmern. Bei nÃ¤herem Hinsehen erkennst Du einen Ring. Du willst ihn bergen, doch bevor Du wirklich herankommst, merkst Du auf einmal, daÃŸ Dein Bein in einigen Schlingpflanzen am Grund verheddert ist. Du versuchst Dich loszureissen, doch es will Dir nicht gelingen. Nach einigen Minuten geht Dir der Atem aus und Deine Sinne schwinden...");

                $exp = e_rand(20,200);

                if ($exp>$session[user][experience])

                    { output("`n`n`^WÃ¤hrend Du langsam wieder zu BewuÃŸtsein kommst, merkst Du, daÃŸ Du Dich nicht mehr daran erinnern kannst, was Du in den letzten Tagen getan hast.");

                      $session[user][experience]=0;

                } else

                    { output("`n`n`^WÃ¤hrend Du langsam wieder zu BewuÃŸtsein kommst, wird Dir bewuÃŸt, was fÃ¼r ein Trottel Du doch bist - durch diese dumme Aktion hast Du $exp Erfahrungspunkte verloren!");

                      $session[user][experience]-=$exp;

                }

              break;

              case 3:

                output("`n`n`^WÃ¤hrend Du untertauchst, siehst Du etwas am Boden des Sees schimmern. Du tauchst hinab und greifst danach. Als Du wieder zurÃ¼ck an die OberflÃ¤che kommst, siehst Du, dass Du zwei Ringe gefunden hast, von denen Du einen $lover gibst und den anderen selber ansteckst.");

                $mhp = e_rand(1,round($session[user][level]/3));

                output("`n`n`^Der Ring verleiht Dir $mhp zusÃ¤tzliche Lebenspunkte.");

                $session[user][maxhitpoints]+=$mhp;

        $db_query("INSERT INTO items (name,owner,class,value1,gold,gems,description) VALUES ('Ring des Lebens',".$session[user][acctid].",'Schmuck',$mhp,100,$mhp,'Dieser schÃ¶ne Ring aus dem See im Wald gibt dir $mhp Lebenspunkte')");

        $db_query("INSERT INTO items (name,owner,class,value1,gold,gems,description) VALUES ('Ring des Lebens',$loverID,'Schmuck',0,100,0,'Diesen schÃ¶nen Ring hat dir ".$session[user][login]."`0 am See im Wald geschenkt.')");

              break;

              case 4:

                output("`n`n`^WÃ¤hrend Du untertauchst, siehst Du etwas am Boden des Sees schimmern. Du tauchst hinab und greifst danach. Dabei rutscht der Ring auf Deinen Finger, und Du fÃ¼hlst Dich plÃ¶tzlich schwÃ¤cher. ZurÃ¼ck an der OberflÃ¤che merkst Du zudem, daÃŸ Du den Ring nicht wieder abbekommst!");

                $mhp = e_rand(1,round($session[user][level]/3));

                if ($session[user][maxhitpoints]<10) { $mhp = 0; }

                if (($mhp>0) && ((($session[user][maxhitpoints]-$mhp)<10))) { $mhp = $session[user][maxhitpoints]-10; }

                output("`n`n`^Der Ring raubt Dir $mhp Lebenspunkte!");

        $db_query("INSERT INTO items (name,owner,class,value1,gold,gems,description) VALUES ('Der Ring',".$session[user][acctid].",'Fluch',$mhp,500,$mhp,'Dieser Ring saugt dir $mhp Lebenspunkte ab.')");

                $session[user][maxhitpoints]-=$mhp;

              break;

        }

        if ($rand<10)

            { if ($session[user][hitpoints]<$session[user][maxhitpoints])

                  { $session[user][hitpoints]=$session[user][maxhitpoints];

                    output("`n`n`^Das herrlich klare und saubere Wasser hat Deine Lebenspunkte vollstÃ¤ndig aufgefÃ¼llt.");

              }

        }

        output("`n`n`n`@Nach einer guten Stunde gibt `^$lover`@ erschÃ¶pft auf, nimmt Dich in die Arme und spricht zÃ¤rtlich:`n");

        output("          `3\"`#Hat das einen SpaÃŸ gemacht. $loved, Du bist wundervoll und ich liebe Dich.`3\"`n`n");

        output("`@Gemeinsam steigt ihr aus dem Wasser, trocknet euch ein wenig lÃ¤nger als notwendig gegenseitig ab, und Ã¼berlegt, was ihr wohl als nÃ¤chstes tun werdet.`n");

        output("`n`n`n`n`n`n`n`n`n`n`$@`@}-,-Â´-");

    $session[user][specialmisc].="_swim";

        if (!strpos($session[user][specialmisc],"_swim")) addnav("Im See schwimmen","forest.php?op=swim");

        if (!strpos($session[user][specialmisc],"_music")) addnav("Musizieren","forest.php?op=music");

        if (!strpos($session[user][specialmisc],"_picknick")) addnav("Picknicken","forest.php?op=picknick");

        if (!strpos($session[user][specialmisc],"_flirt")) addnav("Plaudern","forest.php?op=flirt");

        if ($loverHorseID+$lovedHorseID>0 && !strpos($session[user][specialmisc],"_ride") ) addnav("Ausreiten","forest.php?op=ride");

        if ($allowtovillage==TRUE) addnav("ZurÃ¼ck ins Dorf","village.php");

        if ($allowtoforest==TRUE) addnav("ZurÃ¼ck in den Wald","forest.php?op=return");

        $session[user][specialinc] = "forestlake.php";

      break;

  // GemÃ¼tlich picknicken ......................................................

  case "picknick":

    $session[user][specialmisc].="_picknick";

        output("`@Gemeinsam setzt ihr euch auf die Decke. `^$lover`@ steckt Dir eine der Rosen in's Haar, kÃ¼sst Dich zÃ¤rtlich, und holt eine Weinflasche und zwei GlÃ¤ser aus dem Picknickkorb. ");

        output("`@Nachdem `^$lover`@ Dir ein Glas eingeschenkt hat, stoÃŸt ihr auf eure gemeinsame Zukunft an, und beginnt Ã¼ber Gott und die Welt und vor allem Ã¼ber euch zu reden.`n`n");

        output("`@Es dauert nicht lange, dann ist die erste Weinflasche leer, und bald darauf eine Zweite. Um dem Alkohol eine Grundlage zu geben beschlieÃŸt ihr, etwas zu essen, und fÃ¼ttert euch gegenseitig mit Konfekt und kandiertem Obst aus dem scheinbar bodenlosen Korb.`n`n");

        output("`@Als es irgendwann dunkel wird, seid ihr beide sehr satt und leicht beschwippst, und kuschelt euch gemeinsam unter eine Decke. Auf dem RÃ¼cken liegend beobachtet ihr, wie die Sterne langsam herauskommen. ");

        // Rausch hinzufÃ¼gen?

        $session[user][drunkenness]+=3;

        if (($lover=="Durandil") && ($loved=="Aurelia")) output("Bald merkst Du, dass es der Abendstern Durandil besonders angetan hat. ");

        output("`n`n`n`n`n`n`n`n`n`n`$@`@}-,-Â´-");

        if (!strpos($session[user][specialmisc],"_swim")) addnav("Im See schwimmen","forest.php?op=swim");

        if (!strpos($session[user][specialmisc],"_music")) addnav("Musizieren","forest.php?op=music");

        if (!strpos($session[user][specialmisc],"_picknick")) addnav("Picknicken","forest.php?op=picknick");

        if (!strpos($session[user][specialmisc],"_flirt")) addnav("Plaudern","forest.php?op=flirt");

        if ($loverHorseID+$lovedHorseID>0 && !strpos($session[user][specialmisc],"_ride") ) addnav("Ausreiten","forest.php?op=ride");

        if ($allowtovillage==TRUE) addnav("ZurÃ¼ck ins Dorf","village.php");

        if ($allowtoforest==TRUE) addnav("ZurÃ¼ck in den Wald","forest.php?op=return");

        $session[user][specialinc] = "forestlake.php";

      break;

  // Auf Baumstamm setzen und plaudern .........................................

  case "flirt":

    $session[user][specialmisc].="_flirt";

      output("`@Zusammen mit `^$lover`@ setzt Du Dich auf den Baumstamm, um ein wenig zu plaudern. Instinktiv wisst ihr, dass euch hier niemand belauschen kann, und ihr euren GefÃ¼hlen freien Lauf lassen kÃ¶nnt.`n`n");

        $rand = e_rand(1,100); // schenk ihr etwas, z.B. einen Edelstein!

        switch ($rand){

        case 1:

                output("`&WÃ¤hrend des GesprÃ¤ches steckt $lover Dir eine weitere Rose in's Haar und flÃ¼stert:`n`3\"`#Du bist wunderschÃ¶n...`3\"`n`n"); break;

                $session[user][charm]++;

              break;

              case 2:

                output("`&WÃ¤hrend des GesprÃ¤ches bemerkst Du immer wieder, wie toll $lover doch aussiehst, und schÃ¤mst Dich, daÃŸ Du Dich heute morgen nicht sorgfÃ¤ltiger frisch gemacht hast.`n`n"); break;

                $session[user][charm]--;

              break;

              case 3:

                $gems = e_rand(1, $session[user][level]/7+1);

                output("`&$lover schenkt Dir wÃ¤hrend des GesprÃ¤ches $gems Edelsteine:`n`3\"`#Sie sind zwar nicht so schÃ¶n wie Du es bist, aber ich hoffe sie gefallen Dir trotzdem...`3\"`n`n");

                $session[user][gems] += $gems;

              break;

              case 4:

                $gems = e_rand(1, $session[user][level]/7+1);

                if ($gems>$session[user][gems])

                    { $session[user][gems] = 0;

                      output("`&Du bist so in das GesprÃ¤ch vertieft, daÃŸ Du gar nicht merkst, wie Dir alle Deine Edelsteine aus der Tasche purzeln und im tiefen Gras verschwinden!`n`n"); break;

                } else

                    { $session[user][gems] -= $gems;

                      output("`&Du bist so in das GesprÃ¤ch vertieft, daÃŸ Du gar nicht merkst, wie Dir $gems Edelsteine aus der Tasche purzeln und im tiefen Gras verschwinden!`n`n"); break;

                }

              break;

        }

        viewcommentary($chat,"FlÃ¼stern",10,"flÃ¼stert zÃ¤rtlich");

        output("`n`n`n`n`n`n`n`n`n`n`$@`@}-,-Â´-");

        if (!strpos($session[user][specialmisc],"_swim")) addnav("Im See schwimmen","forest.php?op=swim");

        if (!strpos($session[user][specialmisc],"_music")) addnav("Musizieren","forest.php?op=music");

        if (!strpos($session[user][specialmisc],"_picknick")) addnav("Picknicken","forest.php?op=picknick");

        if (!strpos($session[user][specialmisc],"_flirt")) addnav("Plaudern","forest.php?op=flirt");

        if ($loverHorseID+$lovedHorseID>0 && !strpos($session[user][specialmisc],"_ride") ) addnav("Ausreiten","forest.php?op=ride");

        if ($allowtovillage==TRUE) addnav("ZurÃ¼ck ins Dorf","village.php");

        if ($allowtoforest==TRUE) addnav("ZurÃ¼ck in den Wald","forest.php?op=return");

        $session[user][specialinc] = "forestlake.php";

      break;

      case "return":

        output("`@Nachdem ihr euch eine verdiente Auszeit genommen habt, wird es Zeit, sich wieder den Gefahren des Alltags zu stellen.");

        output("`@Ihr verabschiedet euch sehr herzlich und versprecht, euch so bald wie mÃ¶glich wieder hier zu treffen.");

        output("`n`n`n`n`n`n`n`n`n`n`$@`@}-,-Â´-");

        //output("`n`n`n`n`n`n`n`n`n`7Mein Dank geht an Kaiserin siwi dafÃ¼r, dass sie diese Waldbegegnung eingebunden hat, and natÃ¼rlich und vor allem an Aurelia, die mich dazu inspiriert hat.");

        $session[user][specialinc]="";

      break;

      case "runaway":

        output("`@Irgendwie kannst Du Dich nicht recht aufraffen, den Pfeilen zu folgen. KÃ¶nnte ja auch eine Falle sein, oder?`n");

        output("Und falls dort jemand auf Dich wartet, soll er halt warten.");

        $session[user][specialinc]="";

      break;

}

?>

