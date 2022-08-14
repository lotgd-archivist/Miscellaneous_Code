
ï»¿<?php



// 20060323



require_once("common.php");



/***************

 **  SETTINGS **

 ***************/

$turnsperday = getsetting("turns",10);

$maxinterest = ((float)getsetting("maxinterest",10)/100) + 1; //1.1;

$mininterest = ((float)getsetting("mininterest",1)/100) + 1; //1.1;

//$mininterest = 1.01;

$dailypvpfights = getsetting("pvpday",3);



if ($_GET['resurrection']=="true") {

    $resline = "&resurrection=true";

} else if ($_GET['resurrection']=="egg") {

    $resline = "&resurrection=egg";

} else {

    $resline = "";

}



// $resline = $_GET['resurrection']=="true" ? "&resurrection=true" : "" ;

/******************

 ** End Settings **

 ******************/

if (count($session['user']['dragonpoints']) <$session['user']['dragonkills']&&$_GET['dk']!=""){

    array_push($session['user']['dragonpoints'],$_GET[dk]);

    switch($_GET['dk']){

    case "hp":

        $session['user']['maxhitpoints']+=5;

        break;

    case "at":

        $session['user']['attack']++;

        break;

    case "de":

        $session['user']['defence']++;

        break;

    }

}

if (count($session['user']['dragonpoints'])<$session['user']['dragonkills'] && $_GET['dk']!="ignore"){

    page_header("Drachenpunkte");

    addnav("Max Lebenspunkte +5","newday.php?dk=hp$resline");

    addnav("WaldkÃ¤mpfe +1","newday.php?dk=ff$resline");

    addnav("Angriff + 1","newday.php?dk=at$resline");

    addnav("Verteidigung + 1","newday.php?dk=de$resline");

    output("`@Du hast noch `^".($session['user']['dragonkills']-count($session['user']['dragonpoints']))."`@  Drachenpunkte Ã¼brig. Wie willst du sie einsetzen?`n`n");

    output("Du bekommst 1 Drachenpunkt pro getÃ¶tetem Drachen. Die Ã„nderungen der Eigenschaften durch Drachenpunkte sind permanent.");

}else if ((int)$session['user']['race']==0){

    page_header("Ein wenig Ã¼ber deine Vorgeschichte");

    if ($_GET['setrace']!=""){

        $session['user']['race']=(int)($_GET['setrace']);

        switch($_GET['setrace']){

        case "1":

            $session['user']['attack']++;

            output("`2Als Troll warst du immer auf dich alleine gestellt. Die MÃ¶glichkeiten des Kampfs sind dir nicht fremd.`n`^Du erhÃ¤ltst einen zusÃ¤tzlichen Punkt auf deinen Angriffswert!");

            break;

        case "2":

            $session['user']['defence']++;

            output("`^Als Elf bist du dir immer allem bewusst, was um dich herum passiert. Nur sehr wenig kann dich Ã¼berraschen.`nDu bekommst einen zusÃ¤tzlichen Punkt auf deinen Verteidigungswert!");

            break;

        case "3":

            output("`&Deine GrÃ¶ÃŸe und StÃ¤rke als Mensch erlaubt es dir, Waffen ohne groÃŸe Anstrengungen zu fÃ¼hren und dadurch lÃ¤nger durchzuhalten, als andere Rassen.`n`^Du hast jeden Tag einen zusÃ¤tzlichen Waldkampf!");

            break;

        case "4":

            output("`#Als Zwerg fÃ¤llt es dir leicht, den Wert bestimmter GÃ¼ter besser einzuschÃ¤tzen.`n`^Du bekommst mehr Gold durch WaldkÃ¤mpfe!");

            break;

        case "5":

            output("`5Als Echsenwesen hast du durch deine HÃ¤utungen einen klaren gesundheitlichen Vorteil gegenÃ¼ber anderen Rassen.`n`^Du startest mit einem permanenten Lebenspunkt mehr!");

            $session['user']['maxhitpoints']++;

            break;

        case "6":

            output("`4Als Vampir bedeuted Blut fÃ¼r dich neue Kraft.`n`^Du erhÃ¤ltst nach jedem Waldkampf einen kleinen Teil deiner Lebensenergie zurÃ¼ck!");

            break;

        }

        addnav("Weiter","newday.php?continue=1$resline");

        if ($session['user']['dragonkills']==0 && $session['user']['level']==1){

            addnews("`#{$session['user']['name']} `#hat unsere Welt betreten. Willkommen!");

        }

    }else{

        output("Wo bist du aufgewachsen?`n`n");

        output("<a href='newday.php?setrace=1$resline'>In den S&uuml;mpfen von Glukmoore</a> als `2Troll`0, auf dich alleine gestellt seit dem Moment, als du aus der lederartigen H&uuml;lle deines Eis geschl&uuml;pft bist und aus den Knochen deiner ungeschl&uuml;pften Geschwister ein erstes Festmahl gemacht hast.`n`n",true);

        output("<a href='newday.php?setrace=2$resline'>Hoch Ã¼ber den B&auml;umen</a> des Waldes Glorfindal, in zerbrechlich wirkenden, kunstvoll verzierten Bauten der `^Elfen`0, die so aussehen, als ob sie beim leisesten Windhauch zusammenst&uuml;rzen w&uuml;rden und doch schon Jahrhunderte &uuml;berdauern.`n`n",true);

        output("<a href='newday.php?setrace=3$resline'>Im Flachland in der Stadt Romar</a>, der Stadt der `&Menschen`0. Du hast immer nur zu deinem Vater aufgesehen und bist jedem seiner Schritte gefolgt, bis er auszog den `@Gr&uuml;nen Drachen`0 zu vernichten und nie wieder gesehen wurde.`n`n",true);

        output("<a href='newday.php?setrace=4$resline'>Tief in der Unterirdischen Festung Qexelcrag</a>, der Heimat der edlen und starken `#Zwerge`0, deren Verlangen nach Besitz und Reichtum in keinem Verh&auml;ltnis zu ihrer K&ouml;rpergr&ouml;sse steht.`n`n",true);

        output("<a href='newday.php?setrace=5$resline'>In einem Erdloch in der &ouml;den Landschaft</a> weit au&szlig;erhalb jeder Siedlung bist du als `5Echsenwesen`0 aus deinem Ei geschl&uuml;pft. Artverwandt mit den Drachen hast du es nicht leicht in dieser Welt.`n`n",true);

        output("<a href='newday.php?setrace=6$resline'>Der Friedhof</a> ist seit Langem dein Zuhause. Du hast den gr&ouml;&szlig;ten Teil deines bisherigen ... &auml;hm .. Lebens als `4Vampir`0 in einer Gruft verbracht.`n`n",true);

        addnav("WÃ¤hle deine Rasse");

        addnav("`2Troll`0","newday.php?setrace=1$resline");

        addnav("`^Elf`0","newday.php?setrace=2$resline");

        addnav("`&Mensch`0","newday.php?setrace=3$resline");

        addnav("`#Zwerg`0","newday.php?setrace=4$resline");

        addnav("`5Echse`0","newday.php?setrace=5$resline");

        addnav("`4Vampir`0","newday.php?setrace=6$resline");

        addnav("","newday.php?setrace=1$resline");

        addnav("","newday.php?setrace=2$resline");

        addnav("","newday.php?setrace=3$resline");

        addnav("","newday.php?setrace=4$resline");

        addnav("","newday.php?setrace=5$resline");

        addnav("","newday.php?setrace=6$resline");

    }

}else if ((int)$session['user']['specialty']==0){

  if ($_GET['setspecialty']===NULL){

        addnav("","newday.php?setspecialty=1$resline");

        addnav("","newday.php?setspecialty=2$resline");

        addnav("","newday.php?setspecialty=3$resline");

        addnav("","newday.php?setspecialty=4$resline");

        addnav("","newday.php?setspecialty=5$resline");

        addnav("","newday.php?setspecialty=6$resline");

        page_header("Ein wenig Ã¼ber deine Vorgeschichte");



        output("Du erinnerst dich, dass du als Kind:`n`n");

        output("<a href='newday.php?setspecialty=1$resline'>viele Kreaturen des Waldes get&ouml;tet hast (`\$Dunkle K&uuml;nste`0)</a>`n",true);

        output("<a href='newday.php?setspecialty=2$resline'>mit mystischen Kr&auml;ften experimentiert hast (`%Mystische Kr&auml;fte`0)</a>`n",true);

        output("<a href='newday.php?setspecialty=3$resline'>von den Reichen gestohlen und es dir selbst gegeben hast (`^Diebeskunst`0)</a>`n",true);

        output("<a href='newday.php?setspecialty=4$resline'>schon immer deine Mitsch&uuml;ler verpr&uuml;gelt hast (`qKampfkunst`0)</a>`n",true);

        output("<a href='newday.php?setspecialty=5$resline'>um g&ouml;ttlichen Beistand gebetet hast, dass du von deinen Mitsch&uuml;lern nicht verpr&uuml;gelt wirst (`#Spirituelle Kr&auml;fte`0)</a>`n",true);

        output("<a href='newday.php?setspecialty=6$resline'>schon immer in den Wald wolltest, es dir von Mami aber verboten wurde (`@Naturkraft`0)</a>`n",true);

        addnav("`\$Dunkle KÃ¼nste","newday.php?setspecialty=1$resline");

        addnav("`%Mystische KrÃ¤fte","newday.php?setspecialty=2$resline");

        addnav("`^DiebeskÃ¼nste","newday.php?setspecialty=3$resline");

        addnav("`qKampfkunst","newday.php?setspecialty=4$resline");

        addnav("`#Spirituelle KrÃ¤fte","newday.php?setspecialty=5$resline");

        addnav("`@Naturkraft","newday.php?setspecialty=6$resline");

  }else{

      addnav("Weiter","newday.php?continue=1$resline");

        switch($_GET['setspecialty']){

          case 1:

              page_header("Dunkle KÃ¼nste");

                output("`4Du erinnerst dich, dass du damit aufgewachsen bist, viele kleine Waldkreaturen zu tÃ¶ten, weil du davon Ã¼berzeugt warst, sie haben sich gegen dich verschworen. ");

                output("Deine Eltern haben dir einen idiotischen Zweig gekauft, weil sie besorgt darÃ¼ber waren, dass du die Kreaturen des Waldes mit bloÃŸen HÃ¤nden tÃ¶ten musst. ");

                output("Noch vor deinem Teenageralter hast du damit begonnen, finstere Rituale mit und an den Kreaturen durchzufÃ¼hren, wobei du am Ende oft tagelang im Wald verschwunden bist. ");

                output("Niemand auÃŸer dir wusste damals wirklich, was die Ursache fÃ¼r die seltsamen GerÃ¤usche aus dem Wald war...");

                break;

            case 2:

              page_header("Mystische KrÃ¤fte");

                output("`5Du hast schon als Kind gewusst, dass diese Welt mehr als das Physische bietet, woran du herumspielen konntest. ");

                output("Du hast erkannt, dass du mit etwas Training deinen Geist selbst in eine Waffe verwandeln kannst. ");

                output("Mit der Zeit hast du gelernt, die Gedanken kleiner Kreaturen zu kontrollieren und ihnen deinen Willen aufzuzwingen. ");

                output("Du bist auch auf die mystische Kraft namens Mana gestossen, die du in die Form von Feuer, Wasser, Eis, Erde, Wind bringen und sogar als Waffe gegen deine Feinde einsetzen kannst.");

                break;

            case 3:

              page_header("DiebeskÃ¼nste");

                output("`6Du hast schon sehr frÃ¼h bemerkt, dass ein gewÃ¶hnlicher Rempler im GedrÃ¤nge dir das Gold eines vom GlÃ¼ck bevorzugteren Menschen einbringen kann. ");

                output("AuÃŸerdem hast du entdeckt, dass der RÃ¼cken deiner Feinde anfÃ¤lliger gegen kleine Klingen ist, als deren Vorderseite gegen mÃ¤chtige Waffen.");

                break;

            case 4:

              page_header("Kampfkunst");

                output("`QDu erinnerst dich an eine Zeit, als dir das VerprÃ¼geln deiner MitschÃ¼ler nahrhaftere Pausensnacks einbrachte. ");

                output("Um deine HÃ¤nde zu schÃ¼tzen, hast du sehr bald gelernt, mit Waffen umzugehen.");

                output("Da einige deiner Einnahmequellen begannen, RÃ¼stungen zu tragen, lerntest du auch schon bald, dass diese mit ausreichend Wucht kein Hindernis mehr fÃ¼r dich darstellen.");

                break;

            case 5:

              page_header("Spirituelle KrÃ¤fte");

                output("`9Du hast schon immer in der Ruhe Kraft gefunden. Deine Probleme hast du Vorzugsweise in deinen \"Meditationen\" gelÃ¶st.");

                output("Eines Tages konntest du die Antworten einer inneren (oder Ã¤uÃŸeren?) Stimme tatsÃ¤chlich hÃ¶ren.");

                output("NatÃ¼rlich hatte das nie etwas mit dem vielen RÃ¤ucherwerk zu tun!");

                break;

            case 6:

              page_header("Naturkraft");

                output("`2Das Verbot deiner Mami in den Wald zu gehen, konnte dich nicht daran hindern, diesen mit sehr groÃŸem Interesse zu erkunden.");

                output("Den Grund fÃ¼r das Verbot hast du schnell herausgefunden: Du hast dich verirrt und musstest deine Kindheit alleine im Wald meistern.");

                output("Das Wichtigste, was du in dieser Zeit - neben dem Umgang mit den KrÃ¤ften der Natur - gelernt hast: \"HÃ¶re auf deine Eltern!\" ");

                break;

        }

        $session['user']['specialty']=$_GET['setspecialty'];

    }

}else{

  if ($session['user']['slainby']!=""){

        page_header("Du wurdest umgebracht!");

        output("`\$Im ".$session['user']['killedin']." hat dich `%".$session['user']['slainby']."`\$ getÃ¶tet und dein Gold genommen. Ausserdem hast du 5% deiner Erfahrungspunkte verloren. Meinst du nicht auch, es ist Zeit fÃ¼r Rache?");

        addnav("Weiter","newday.php?continue=1$resline");

      $session['user']['slainby']="";

    }else{

        page_header("Es ist ein neuer Tag!");

        $interestrate = (float)e_rand($mininterest*100,$maxinterest*100)/(float)100;

        output("`c<font size='+1'>`b`#Es ist ein neuer Tag!`0`b</font>`c",true);

if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/newday.wav\" width=\"10\" height=\"10\" autostart=\"true\" loop=\"false\" hidden=\"true\" volume=\"100\">",true);



        if ($session['user']['alive']!=1){

            $session['user']['resurrections']++;

            output("`@Du bist wiedererweckt worden! Dies ist der Tag deiner ".ordinal($session['user']['resurrections'])." Wiederauferstehung.`0`n");

            $session['user']['alive']=1;

        }

        $session['user']['age']++;

        $session['user']['seenmaster']=0;

        output("Du Ã¶ffnest deine Augen und stellst fest, dass dir ein neuer Tag geschenkt wurde. Dies ist dein `^".$session['user']['age'].".`0 Tag in diesem Land. ");

        output("Du fÃ¼hlst dich frisch und bereit fÃ¼r die Welt!`n");

        output("`2Runden fÃ¼r den heutigen Tag: `^$turnsperday`n");





        if ($session['user']['goldinbank']<0 && abs($session['user']['goldinbank'])<(int)getsetting("maxinbank",10000)){

            output("`2Heutiger Zinssatz: `^".(($interestrate-1)*100)."% `n");

            output("`2Zinsen fÃ¼r Schulden: `^".-(int)($session['user']['goldinbank']*($interestrate-1))."`2 Gold.`n");

        }else if ($session['user']['goldinbank']<0 && abs($session['user']['goldinbank'])>=(int)getsetting("maxinbank",10000)){

            output("`4Die Bank erlÃ¤sst dir deine Zinsen, da du schon hoch genug verschuldet bist.`n");

            $interestrate=1;

        }else if ($session['user']['goldinbank']>=0 && $session['user']['goldinbank']>=(int)getsetting("maxinbank",10000) && $session['user']['turns']<=getsetting("fightsforinterest",4)){

            $interestrate=1;

            output("`4Die Bank kann dir heute keinen Zinsen zahlen. Sie wÃ¼rde frÃ¼her oder spÃ¤ter an dir pleite gehen.`n");

        }else if ($session['user']['goldinbank']>=0 && $session['user']['goldinbank']<(int)getsetting("maxinbank",10000) && $session['user']['turns']<=getsetting("fightsforinterest",4)){

            output("`2Heutiger Zinssatz: `^".(($interestrate-1)*100)."% `n");

            output("`2Durch Zinsen verdientes Gold: `^".(int)($session['user']['goldinbank']*($interestrate-1))."`n");

        }else{

            $interestrate=1;

            output("`2Dein heutiger Zinssatz betrÃ¤gt `^0% (Die Bank gibt nur den Leuten Zinsen, die dafÃ¼r arbeiten)`n");

        }

        if ($session['user']['jail'] == true){

            output("`#Du wirst vom Pranger befreit`n");

            $session['user']['jail'] = false;

            $session['user']['location'] = 0;

        }

        output("`2Deine Gesundheit wurde wiederhergestellt auf `^".$session['user']['maxhitpoints']."`n");

        $sb = getsetting("specialtybonus",1);

        output("`2FÃ¼r dein Spezialgebiet `&".$specialty[$session['user']['specialty']]."`2, erhÃ¤ltst du zusÃ¤tzlich $sb Anwendung(en) in `&".$specialty[$session['user']['specialty']]."`2 fÃ¼r heute.`n");

        $session['user']['darkartuses'] = (int)($session['user']['darkarts']/3) + ($session['user']['specialty']==1?$sb:0);

        $session['user']['magicuses'] = (int)($session['user']['magic']/3) + ($session['user']['specialty']==2?$sb:0);

        $session['user']['thieveryuses'] = (int)($session['user']['thievery']/3) + ($session['user']['specialty']==3?$sb:0);

        $session['user']['warriorsartuses'] = (int)($session['user']['warriorsart']/3) + ($session['user']['specialty']==4?$sb:0);

        $session['user']['priestsartuses'] = (int)($session['user']['priestsart']/3) + ($session['user']['specialty']==5?$sb:0);

        $session['user']['rangersartuses'] = (int)($session['user']['rangersart']/3) + ($session['user']['specialty']==6?$sb:0);

        if ($session['user']['marriedto']==4294967295 || $session['user']['charisma']==4294967295){

            output("`n`%Du bist verheiratet, es gibt also keinen Grund mehr, das perfekte Image aufrecht zu halten. Du lÃ¤sst dich heute ein bisschen gehen.`n Du verlierst einen Charmepunkt.`n");

            $session['user']['charm']--;

            if ($session['user']['charm']<=0){

                $session['user']['charm']=0;

                output("`n`bAls du heute aufwachst, findest du folgende Notiz neben dir im Bett:`n`5".($session['user']['sex']?"Liebste":"Liebster")."");

                output("".$session['user']['name']."`5.");

                output("`nTrotz vieler groÃŸartiger KÃ¼sse, fÃ¼hle ich mich einfach nicht mehr so zu dir hingezogen wie es frÃ¼her war.`n`n");

                output("Nenne mich wankelmÃ¼tig, aber ich muss weiterziehen. Es gibt andere Krieger".($session['user']['sex']?"innen":"")." in diesem Dorf und ich glaube, ");

                output("einige davon sind wirklich heiss. Es liegt also nicht an dir, sondern an mir, usw. usw.");

                  $sql = "SELECT acctid,name FROM accounts WHERE locked=0 AND acctid=".$session['user']['marriedto']."";

                  $result = db_query($sql) or die(db_error(LINK));

                $row = db_fetch_assoc($result);

                $partner=$row['name'];

                if ($partner=="") $partner = $session['user']['sex']?"Seth":"Violet";

                output("`n`nSei nicht traurig!`nIn Liebe, $partner`b`n");

                addnews("`\$$partner `\$hat {$session['user']['name']}`\$ fÃ¼r \"andere Interessen\" verlassen!");

                if ($session['user']['marriedto']==4294967295) $session['user']['marriedto']=0;

                if ($session['user']['charisma']==4294967295){

                     $session['user']['charisma']=0;

                    $session['user']['marriedto']=0;

                    $sql = "UPDATE accounts SET charisma=0,marriedto=0 WHERE acctid='{$row['acctid']}'";

                    db_query($sql);

                    systemmail($row['acctid'],"`\$Wieder solo!`0","`6Du hast `&{$session['user']['name']}`6 verlassen. ".($session['user']['sex']?"Sie":"Er")." war einfach widerlich in letzter Zeit.");

                }

            }

        }



        //clear all standard buffs

        $tempbuf = unserialize($session['user']['bufflist']);

        $session['user']['bufflist']="";

        $session['bufflist']=array();

        while(list($key,$val)=@each($tempbuff)){

            if ($val['survivenewday']==1){

                $session['bufflist'][$key]=$val;

                output("{$val['newdaymessage']}`n");

            }

        }



        reset($session['user']['dragonpoints']);

        $dkff=0;

        while(list($key,$val)=each($session['user']['dragonpoints'])){

            if ($val=="ff"){

                $dkff++;

            }

        }

        if ($session['user']['hashorse']){

            $session['bufflist']['mount']=unserialize($playermount['mountbuff']);

        }

        if ($dkff>0) output("`n`2Du erhÃ¶hst deine WaldkÃ¤mpfe um `^$dkff`2 durch verteilte Drachenpunkte!");

        $r1 = e_rand(-1,1);

        $r2 = e_rand(-1,1);

        $spirit = $r1+$r2;

        if ($_GET['resurrection']=="true"){

            addnews("`&{$session['user']['name']}`& wurde von `\$Ramius`& wiedererweckt.");

            $spirit=-6;

            $session['user']['deathpower']-=100;

            $session['user']['restorepage']="village.php?c=1";

        }

        if ($_GET['resurrection']=="egg"){

            addnews("`&{$session['user']['name']}`& hat das `^goldene Ei`& benutzt und entkam so dem Schattenreich.");

            $spirit=-6;

            $session['user']['restorepage']="village.php?c=1";

            savesetting("hasegg","0");

        }

        output("`n`2Dein Geist und deine Stimmung ist heute `^".$spirits[$spirit]."`2!`n");

        if (abs($spirit)>0){

            output("Deswegen ");

            if($spirit>0){

                output("`^bekommst du zusÃ¤tzlich ");

            }else{

                output("`^verlierst du ");

            }

            output(abs($spirit)." Runden`2 fÃ¼r heute.`n");

        }

        $rp = $session['user']['restorepage'];

        $x = max(strrpos("&",$rp),strrpos("?",$rp));

        if ($x>0) $rp = substr($rp,0,$x);

        if (substr($rp,0,10)=="badnav.php"){

            addnav("Weiter","news.php");

        }else{

            addnav("Weiter",preg_replace("'[?&][c][=].+'","",$rp));

        }



        $session['user']['laston'] = date("Y-m-d H:i:s");

        $bgold=$session['user']['goldinbank'];

        $session['user']['goldinbank']=round($session['user']['goldinbank']*$interestrate);

        $nbgold=$session['user']['goldinbank']-$bgold;



        if ($nbgold != 0) {

            //debuglog(($nbgold >= 0 ? "earned " : "paid ") . abs($nbgold) . " gold in interest");

        }

        $session['user']['turns']=$turnsperday+$spirit+$dkff;

        if ($session['user']['maxhitpoints']<6) $session['user']['maxhitpoints']=6;

        $session['user']['hitpoints'] = $session['user']['maxhitpoints'];

        $session['user']['spirits'] = $spirit;

        $session['user']['playerfights'] = $dailypvpfights;

        $session['user']['transferredtoday'] = 0;

        $session['user']['amountouttoday'] = 0;

        $session['user']['seendragon'] = 0;

        $session['user']['seenmaster'] = 0;

        $session['user']['seenlover'] = 0;

        $session['user']['witch'] = 0;

        $session['user']['trauer'] = 0;

        $session['user']['usedouthouse'] = 0;

        $session['user']['seenAcademy'] = 0;

        $session['user']['gotfreeale'] = 0;

        $session['user']['fedmount'] = 0;

        if ($_GET['resurrection']!="true" && $_GET['resurrection']!="egg" ){

            $session['user']['soulpoints']=50 + 5 * $session['user']['level'];

            $session['user']['gravefights']=getsetting("gravefightsperday",10);

            $session['user']['reputation']+=5;

        }

        $session['user']['seenbard'] = 0;

        $session['user']['boughtroomtoday'] = 0;

        $session['user']['lottery'] = 0;

        $session['user']['recentcomments']=$session['user']['lasthit'];

        $session['user']['lasthit'] = date("Y-m-d H:i:s");

        if ($session['user']['drunkenness']>66){

          output("`&Wegen deines schrecklichen Katers wird dir 1 Runde fÃ¼r heute abgezogen.");

            $session['user']['turns']--;

        }



// following by talisman & JT

//Set global newdaysemaphore



       $lastnewdaysemaphore = convertgametime(strtotime(getsetting("newdaysemaphore","0000-00-00 00:00:00")));

       $gametoday = gametime();



        if (date("Ymd",$gametoday)!=date("Ymd",$lastnewdaysemaphore)){

            $sql = "LOCK TABLES settings WRITE";

            db_query($sql);



           $lastnewdaysemaphore = convertgametime(strtotime(getsetting("newdaysemaphore","0000-00-00 00:00:00")));



            $gametoday = gametime();

            if (date("Ymd",$gametoday)!=date("Ymd",$lastnewdaysemaphore)){

                //we need to run the hook, update the setting, and unlock.

                savesetting("newdaysemaphore",date("Y-m-d H:i:s"));

                $sql = "UNLOCK TABLES";

                db_query($sql);



            require_once("setnewday.php");



            }else{

                //someone else beat us to it, unlock.

                $sql = "UNLOCK TABLES";

                db_query($sql);

                output("Somebody beat us to it");

            }

        }



    output("`nDer Schmerz in deinen wetterfÃ¼hligen Knochen sagt dir das heutige Wetter: `6".$settings['weather']."`@.`n");

    if ($_GET['resurrection']==""){

        if ($session['user']['specialty']==1 && $settings['weather']=="Regnerisch"){

            output("`^`nDer Regen schlÃ¤gt dir aufs GemÃ¼t, aber erweitert deine Dunklen KÃ¼nste. Du bekommst eine zusÃ¤tzliche Anwendung.`n");

            $session['user']['darkartuses']++;

            }

        if ($session['user']['specialty']==2 and $settings['weather']=="Gewittersturm"){

            output("`^`nDie Blitze fÃ¶rdern deine Mystischen KrÃ¤fte. Du bekommst eine zusÃ¤tzliche Anwendung.`n");

            $session['user']['magicuses']++;

            }

        if ($session['user']['specialty']==3 and $settings['weather']=="Neblig"){

            output("`^`nDer Nebel bietet Dieben einen zusÃ¤tzlichen Vorteil. Du bekommst eine zusÃ¤tzliche Anwendung.`n");

            $session['user']['thieveryuses']++;

            }

        if ($session['user']['specialty']==4 and $settings['weather']=="HeiÃŸ und sonnig"){

            output("`^`nDie Hitze bringt dein Blut in Wallung. Du bekommst eine zusÃ¤tzliche Anwendung.`n");

            $session['user']['warriorsartuses']++;

            }

        if ($session['user']['specialty']==5 and $settings['weather']=="Kalt bei klarem Himmel"){

            output("`^`nKeine Wolken behindern den Kontakt \"nach oben\". Du bekommst eine zusÃ¤tzliche Anwendung.`n");

            $session['user']['priestsartuses']++;

            }

        if ($session['user']['specialty']==6 and $settings['weather']=="Starker Wind mit vereinzelten Regenschauern"){

            output("`^`nDas heutige Wetter begÃ¼nstigt das Wachstum der Natur. Du bekommst eine zusÃ¤tzliche Anwendung.`n");

            $session['user']['rangersartuses']++;

            }

    }

//End global newdaysemaphore code and weather mod.



        if ($session['user']['hashorse']){

output(str_replace("{weapon}",$session['user']['weapon'],"`n`&{$playermount['newday']}`n`0"));

            if ($playermount['mountforestfights']>0){

                output("`n`&Weil du ein(e/n) {$playermount['mountname']} besitzt, bekommst du `^".((int)$playermount['mountforestfights'])."`& Runden zusÃ¤tzlich.`n");

                $session['user']['turns']+=(int)$playermount['mountforestfights'];

            }

        }else{

            output("`n`&Du schnallst dein(e/n) `%".$session['user']['weapon']."`& auf den RÃ¼cken und ziehst los ins Abenteuer.`0");

        }

        if ($session['user']['race']==3) {

            $session['user']['turns']++;

            output("`n`&Weil du ein Mensch bist, bekommst du `^1`& Waldkampf zusÃ¤tzlich!`n`0");

        }

        if ($session['user']['kleineswesen']<0){

            output("`n`\$Weil Du einen schlimmen Alptraum hattest, verlierst Du `^".abs($session['user']['kleineswesen'])."`\$ Runden fÃ¼r heute!`n");

            $session['user']['turns']+=$session['user']['kleineswesen'];

            $session['user']['kleineswesen']=0;

        }

        if ($session['user']['kleineswesen']>0){

            output("`n`@Weil Du einen fantastischen Traum hattest, erhÃ¤ltst Du `^".$session['user']['kleineswesen']."`@ zusÃ¤tzliche Runden fÃ¼r heute!`n");

            $session['user']['turns']+=$session['user']['kleineswesen'];

            $session['user']['kleineswesen']=0;

        }

        $config = unserialize($session['user']['donationconfig']);

        if (!is_array($config['forestfights'])) $config['forestfights']=array();

        reset($config['forestfights']);

        while (list($key,$val)=each($config['forestfights'])){

            $config['forestfights'][$key]['left']--;

            output("`@Du bekommst eine Extrarunde fÃ¼r die Punkte vom `^{$val['bought']}`@.");

            $session['user']['turns']++;

            if ($val['left']>1){

                output(" Du hast `^".($val['left']-1)."`@ Tage von diesem Kauf Ã¼brig.`n");

            }else{

                unset($config['forestfights'][$key]);

                output(" Dieser Kauf ist damit abgelaufen.`n");

            }

        }

        if ($config['healer'] > 0) {

            $config['healer']--;

            if ($config['healer'] > 0) {

                output("`n`@Golinda ist bereit, dich noch {$config['healer']} weitere Tage zu behandeln.");

            } else {

                output("`n`@Golinda wird dich nicht lÃ¤nger behandeln.");

                unset($config['healer']);

            }

        }

        if ($config['goldmineday']>0) $config['goldmineday']=0;

        $session['user']['donationconfig']=serialize($config);

        if ($session['user']['hauntedby']>""){

            output("`n`n`)Du wurdest von {$session['user']['hauntedby']}`) heimgesucht und verlierst eine Runde!");

            $session['user']['turns']--;

            $session['user']['hauntedby']="";

        }

        // Ehre & Ansehen

        if ($session['user']['reputation']<=-50){

            $session['user']['reputation']=-50;

            output("`n`8Da du aufgrund deiner Ehrenlosigkeit hÃ¤ufig Steine in den Weg gelegt bekommst, kannst du heute 1 Runden weniger kÃ¤mpfen. AuÃŸerdem sind deine Feinde vor dir gewarnt.`nDu solltest dringend etwas fÃ¼r deine Ehre tun!");

            $session['user']['turns']--;

            $session['user']['playerfights']--;

        }else if ($session['user']['reputation']<=-30){

            output("`n`8Deine Ehrenlosigkeit hat sich herumgesprochen! Deine Feinde sind vor dir gewarnt, weshalb dir heute 1 Spielerkampf weniger gelingen wird.`nDu solltest dringend etwas fÃ¼r deine Ehre tun!");

            $session['user']['playerfights']--;

        }else if ($session['user']['reputation']<-10){

            output("`n`8Da du aufgrund deiner Ehrenlosigkeit hÃ¤ufig Steine in den Weg gelegt bekommst, kannst du heute 1 Runde weniger kÃ¤mpfen.");

            $session['user']['turns']--;

        }else if ($session['user']['reputation']>=30){

            output("`n`9Da du aufgrund deiner groÃŸen Ehrenhaftigkeit das Volk auf deiner Seite hast, kannst du heute 1 Runde und 1 Spielerkampf mehr kÃ¤mpfen.");

            if ($session['user']['reputation']>50) $session['user']['reputation']=50;

            $session['user']['turns']++;

            $session['user']['playerfights']++;

        }else if ($session['user']['reputation']>10){

            output("`n`9Da du aufgrund deiner groÃŸen Ehrenhaftigkeit das Volk auf deiner Seite hast, kannst du heute 1 Runde mehr kÃ¤mpfen.");

            $session['user']['turns']++;

        }



        $session['user']['drunkenness']=0;

        $session['user']['bounties']=0;

        // Buffs from items

        $sql="SELECT * FROM items WHERE (class='Fluch' OR class='Geschenk' OR class='Zauber' OR class='Beet' OR class='Kleidung') AND owner=".$session['user']['acctid']." ORDER BY id";

        $result=db_query($sql);

        for ($i=0;$i<db_num_rows($result);$i++){

              $row = db_fetch_assoc($result);

            if (strlen($row['buff'])>8){

                $row['buff']=unserialize($row['buff']);

                if ($row['class']!='Zauber' && $row['class']!='Kleidung') $session['bufflist'][$row['buff']['name']]=$row['buff'];

                if ($row['class']=='Fluch') output("`n`G{$row['name']}`G nagt an dir.");

                if ($row['class']=='Geschenk') output("`n`1{$row['name']}`1: {$row['description']}");

                if ($row['class']=='Kleidung' && $row['hvalue']>0){

                    output("`n`tDie magischen Eigenschaften von {$row['name']}`t entfalten ihre Wirkung.");

                    $session['bufflist'][$row['buff']['name']]=$row['buff'];

                }

            }

            if ($row['hvalue']>0 && $row['class']!="Kleidung"){

                $row['hvalue']--;

                if ($row['hvalue']<=0){

                    db_query("DELETE FROM items WHERE id={$row['id']}");

                    if ($row['class']=='Fluch') output(" Aber nur noch heute.");

                    if ($row['class']=='Zauber') output("`n`Q{$row['name']}`Q hat seine Kraft verloren.");

                }else{

                    $what="hvalue={$row['hvalue']}";

                    if ($row['class']=='Zauber') $what.=", value1={$row['value2']}";

                    db_query("UPDATE items SET $what WHERE id={$row['id']}");

                }

            }

            if ($row['class']=="Beet" && $row['value1']>0) db_query("UPDATE items SET value1=0 WHERE class='Beet' and owner=".$session['user']['acctid']);

        }

    }

}

page_footer();

?>

