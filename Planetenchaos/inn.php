
<?php

// 15082004

require_once "common.php";

addcommentary();

checkday();

if ($session['user']['slainby']!=""){

    page_header("Du wurdest besiegt!");

        output("`\$Du wurdest in ".$session['user']['killedin']."`\$ von `%".$session['user']['slainby']."`\$ besiegt und um alles Gold beraubt, das du bei dir hattest. Das kostet dich 5% deiner Erfahrung. Meinst du nicht, es ist Zeit fÃ¼r Rache?");

    addnav("Weiter",$REQUEST_URI);

    $session['user']['slainby']="";

    $session['user']['donation']+=1;

    page_footer();

}

$buff = array("name"=>"`!Schutz der Liebe","rounds"=>60,"wearoff"=>"`!Du vermisst ".($session['user']['sex']?"Seth":"`5Violet`")."!.`0","defmod"=>1.2,"roundmsg"=>"Deine groÃŸe Liebe lÃ¤sst dich an deine Sicherheit denken!","activate"=>"defense");

page_header("Schenke zum Eberkopf");

output("<span style='color: #9900FF'>",true);

output("`c`bSchenke zum Eberkopf`b`c");

if ($_GET['op']=="strolldown"){

    output("Wiedermal bereit fÃ¼r's Abenteuer schlenderst du die Treppen der Schenke runter!  ");

}

if ($_GET['op']==""){

    output("Du tauchst in eine schummerige Schenke ab, die du sehr gut kennst. Der stechende Geruch von Pfeifentabak erfÃ¼llt ");

    output("die Luft.");

}

if ($_GET['op']=="" || $_GET['op']=="strolldown"){

    output("  Du winkst einigen deiner Kumpels und zwinkerst ".

                ($session['user']['sex']?

                "`^Seth`0 zu, der seine Harfe beim Feuer stimmt.":

                "`5Violet`0 zu, die ein paar einheimischen Ale serviert.").

                " Der Barkeeper Cedrik steht hinter seiner Theke und quatscht mit irgendjemandem. Du kannst nicht genau verstehen "

                ." was er sagt, aber es ist irgendwas Ã¼ber ");

    switch (e_rand(1,16)){

        case 1:

            output("Drachen.");

            break;

        case 2:

            output("Seth.");

            break;

        case 3:

            output("Violet.");

            break;

        case 4:

            output("MightyE.");

            break;

        case 5:

            output("leckeres Ale.");

            break;

        case 6:

            output("anpera.");

            break;

        case 7:

            output("Reandor.");

            break;

        case 8:

            output("Kala.");

            break;

        case 9:

            output("Zwergenweitwurf.");

            break;

        case 10:

            output("das elementare ZerwÃ¼rfnis des Seins.");

            break;

        case 11:

            output("hÃ¤ufig gestellte Fragen.");

            break;

        case 12:

            output("Manwe und bibir.");

            break;

        default:

            $row = db_fetch_assoc(db_query("SELECT name FROM accounts WHERE locked=0 ORDER BY rand(".e_rand().") LIMIT 1"));

            output("`%$row['name']`0.");

            break;

    }

    if (getsetting("pvp",1)) {

        output(" Dag Durnick sitzt Ã¼bel gelaunt mit einer Pfeife fest im Mund in der Ecke. ");

    }

    output("`n`nDie Uhr am Kamin zeigt `6".getgametime()."`0.");

    $sql = "UPDATE accounts SET message='',msgdate='0000-00-00 00:00:00' WHERE message>'' AND msgdate<'".date("Y-m-d H:i:s")."'";

    db_query($sql);

    output("`n`n");

    $sql = "SELECT acctid,login,name,message,msgdate FROM accounts WHERE message>'' ORDER BY msgdate DESC";

    $result = db_query($sql) or die(db_error(LINK));

    if (db_num_rows($result)<=0){

        output("Am schwarzen Brett neben der TÃ¼r ist nicht eine einzige Nachricht zu sehen.");

    }else{

        output("Am schwarzen Brett neben der TÃ¼r flattern einige Nachrichten im Luftzug:");

        for ($i=0;$i<db_num_rows($result);$i++){

            $row = db_fetch_assoc($result);

            output("`n`n<a href=\"mail.php?op=write&to=".rawurlencode($row['login'])."\" target=\"_blank\" onClick=\"".popup("mail.php?op=write&to=".rawurlencode($row['login'])."").";return false;\"><img src='images/newscroll.GIF' width='16' height='16' alt='Mail schreiben' border='0'></a>",true);

            output("`& $row['name']`&:`n`^$row['message']`0");

            if ($row['acctid']==$session['user']['acctid']){

                output("[<a href='inn.php?op=msgboard&act=del'>entfernen</a>]",true);

                addnav("","inn.php?op=msgboard&act=del");

            }

        }

    }

    addnav("Was machst du?");

    if ($session['user']['sex']==0) addnav("V?Flirte mit Violet","inn.php?op=violet");

    if ($session['user']['sex']==1) addnav("V?Quatsche mit Violet","inn.php?op=violet");

    addnav("S?Rede mit dem Barden Seth","inn.php?op=seth");

    addnav("Mit Freunden unterhalten","inn.php?op=converse");

    addnav("B?Spreche mit Barkeeper Cedrik","inn.php?op=bartender");

    if (getsetting("pvp",1)) {

        addnav("D?Mit Dag Durnick sprechen","dag.php");

    }

                addnav("O?Mit Old Drawl sprechen","olddrawl.php"); 

    addnav("Sonstiges");

    // addnav("Lotterie","lottery.php"); // siehe old drawl

    addnav("n?Zimmer nehmen (Log out)","inn.php?op=room");

    addnav("ZurÃ¼ck zum Dorf","village.php");

}else{

  switch($_GET['op']){

    case "msgboard":

    if ($_GET['act']=="del"){

        $session['user']['message']="";

        $session['user']['msgdate']="0000-00-00 00:00:00";

        output("Du reisst deine eigene Nachricht vom schwarzen Brett ab. Der Fall hat sich fÃ¼r dich erledigt.");

        addnav("Neue Nachricht","inn.php?op=msgboard"); 

    }else if ($_GET['act']=="add1"){

        $msgprice=$session['user']['level']*6*(int)$_GET['amt'];

        output("Cedrik kramt einen Zettel und einen Stift unter der Theke hervor und schaut dich fragend an, was er fÃ¼r dich schreiben soll. Offenbar ");

        output("sind viele seiner Kunden der Kunst des Schreibens nicht mÃ¤chtig. \"`%Das macht dann `^$msgprice`% Gold. Wie soll die Nachricht lauten?`0\"`n`n");

        output("<form action=\"inn.php?op=msgboard&act=add2&amt=$_GET['amt']\" method='POST'>",true);

        output("`nGib deine Nachricht ein:`n<input name='msg' maxlength='250' size='50'>`n",true);

        output("<input type='submit' class='button' value='Ans schwarze Brett'>",true);

        addnav("","inn.php?op=msgboard&act=add2&amt=$_GET['amt']");

    }else if ($_GET['act']=="add2"){

        $msgprice=$session['user']['level']*6*(int)$_GET['amt'];

        $msgdate=date("Y-m-d H:i:s",strtotime(date('c')."+$_GET['amt'] days"));

        if ($session['user']['gold']<$msgprice){

            output("Als Cedrik bemerkt, dass du offensichtlich nicht genug Gold hast, schnauzt er dich an: \"`%So kommen wir nicht ins GeschÃ¤ft, Kleine".($session['user']['sex']?"":"r").". Sieh zu, dass du Land gewinnst. Oder im Lotto.`0\"");

        }else{

            output("MÃ¼rrisch nimmt Cedrik dein Gold, schreibt deinen Text auf den Zettel und ohne ihn nochmal durchzulesen, heftet er ihn zu den anderen an das schwarze Brett neben der EingangstÃ¼r.");

            $session['user']['message']=stripslashes($_POST['msg']);

            $session['user']['msgdate']=$msgdate;

            $session['user']['gold']-=$msgprice;

        }

    }else{

        $msgprice=$session['user']['level']*6;

        $msgdays=(int)getsetting("daysperday",4);

        output("\"`%Du mÃ¶chtest eine Nachricht am schwarzen Brett hinterlassen, ja? Wie lang soll die Nachricht denn dort zu sehen sein?`0\" fragt dich Cedrik fordernd und nennt die Preise.");

        addnav("$msgdays Tage (`^$msgprice`0 Gold)","inn.php?op=msgboard&act=add1&amt=1");

        addnav("".($msgdays*3)." Tage (`^".($msgprice*3)."`0 Gold)","inn.php?op=msgboard&act=add1&amt=3");

        addnav("".($msgdays*10)." Tage (`^".($msgprice*10)."`0 Gold)","inn.php?op=msgboard&act=add1&amt=10");

        if ($session['user']['message']>"") output("`nEr macht dich noch darauf aufmerksam, dass er deine alte Nachricht entfernen wird, wenn du jetzt eine neue anbringen willst.");

    }

    break;

      case "violet":

            /*Wink

            Kiss her hand

            Peck her on the lips

            Sit her on your lap

            Grab her backside

            Carry her upstairs

            Marry her*/

            if ($session['user']['sex']==1){

                if ($_GET['act']==""){

                    addnav("Tratsch","inn.php?op=violet&act=gossip");

                    addnav("Frage, ob dich dein ".$session['user']['armor']." dick aussehen lÃ¤sst","inn.php?op=violet&act=fat");

                    output("Du gehst rÃ¼ber zu `5Violet`0 und hilfst ihr dabei, ein paar Ales zu servieren. Als sie alle ausgeteilt sind, ");

                    output("wischt sie sich mit einem Lappen den SchweiÃŸ von der Stirn und dankt dir herzlich. NatÃ¼rlich war es ");

                    output("fÃ¼r dich selbstverstÃ¤ndlich, schlieÃŸlich ist sie eine deiner Ã¤ltesten und besten Freundinnen!");

                }else if($_GET['act']=="gossip"){

                    output("FÃ¼r ein paar Minuten tratschst du mit `5Violet`0 Ã¼ber alles und nichts. Sie bietet dir eine Essiggurke an. ");

                    output("Das liegt in ihrer Natur, da sie frÃ¼her Gurken angebaut und verkauft hat. Du nimmst an. Nach ein paar Minuten ");

                    output("bemerkst du die brennenden Blicke, die Cedrik immer hÃ¤ufiger in eure Richtung wirft und du beschlieÃŸt, dass es besser ist, Violet wieder ihre Arbeit machen zu lassen. ");

                }else if($_GET['act']=="fat"){

                    $charm = $session['user']['charm']+e_rand(-1,1);

                    output("Violet schaut dich ernst von oben bis unten an. Nur ein echter Freund kann wirklich ehrlich sein und genau deswegen ");

                    output("hast du sie gefragt. SchlieÃŸlich fasst sie einen Entschluss und sagt: \"`%");

                    switch($charm){

                        case -3:

                        case -2:

                        case -1:

                        case 0:

                            output("Dein Outfit lÃ¤sst nicht viel Spielraum fÃ¼r Fantasie, aber Ã¼ber manche Dinge sollte man auch wirklich nicht nachdenken. Du solltest etwas weniger freizÃ¼gige Kleidung in der Ã–ffentlichkeit tragen!");

                            break;

                        case 1:

                        case 2:

                        case 3:

                            output("Ich habe schon einige reizvolle Damen gesehn, aber ich fÃ¼rchte du bist keine davon.");

                            break;

                        case 4:

                        case 5:

                        case 6:



                            output("Ich habe schon schlimmeres gesehen, aber nur beim Verfolgen eines Pferdes.");

                            break;

                        case 7:

                        case 8:

                        case 9:

                            output("Du bist ziemlicher Durchschnitt, meine Gute.");

                            break;

                        case 10:

                        case 11:

                        case 12:

                            output("Du bist schon etwas zum Anschauen, aber lass dir das nicht zu sehr zu Kopfe steigen, ja?");

                            break;

                        case 13:

                        case 14:

                        case 15:

                            output("Du siehst schon ein bisschen besser aus als der Durchschnitt!");

                            break;

                        case 16:

                        case 17:

                        case 18:

                            output("Nur wenige Frauen kÃ¶nnen von sich behaupten, sich mit dir messen zu kÃ¶nnen!");

                            break;

                        case 19:

                        case 20:

                        case 21:

                        case 22:

                            output("Willst du mich mit dieser Frage neidisch machen? Oder mich einfach nur Ã¤rgern?");

                            break;

                        case 23:

                        case 24:

                        case 25:

                            output("Ich bin von deiner SchÃ¶nheit geblendet.");

                            break;

                        case 26:

                        case 27:

                        case 28:

                        case 29:

                        case 30:

                            output("Ich hasse dich. Warum? Weil du einfach die schÃ¶nste Frau aller Zeiten bist!");

                            break;

                        default:

                            output("Vielleicht solltest du langsam etwas gegen deine Ã¼berirdische SchÃ¶nheit tun. Du bist unerreichbar!");

                    }

                    output("`0\"");

                }

            }

            if ($session['user']['sex']==0){

                  //$session['user']['seenlover']=0;

              if ($session['user']['seenlover']==0){

                    if ($session['user']['marriedto']==4294967295){

                      if (e_rand(1, 4)==1){

                      output("Du gehst rÃ¼ber zu Violet um sie zu knuddeln und sie auf Gesicht und Hals zu kÃ¼ssen, aber sie brummelt nur etwas ");

                      switch(e_rand(1,4)){

                      case 1:

                          output("davon, dass sie zu beschÃ¤ftigt damit ist, diese Schweine zu bedienen. ");

                        break;

                      case 2:

                          output("wie \"diese Zeit des Monats\".");

                        break;

                      case 3:

                          output("wie \"eine   leichte   ErkÃ¤ltung...  *hust hust* .. siehst du?\". ");

                          break;

                      case 4:

                          output("darÃ¼ber, dass alle MÃ¤nner Schweine sind.");

                        break;

                      }

                      output(" Nach so einem Kommentar lÃ¤sst du sie stehen und haust ab!");

                      $session['user']['charm']--;

                      output("`n`n`^Du VERLIERST einen Charmepunkt!");

                    } else {

                        output("Du und `5Violet`0 nehmt euch etwas Zeit fÃ¼r euch selbst und du verlÃ¤sst die Schenke zuversichtlich strahlend!");

                        $session['bufflist']['lover']=$buff;

                        $session['user']['charm']++;

                        output("`n`n`^Du erhÃ¤ltst einen Charmepunkt!");

                    }

                    $session['user']['seenlover']=1;

                  } elseif ($_GET['flirt']==""){

                        output("Du starrst vertrÃ¤umt durch den Raum auf `5Violet`0, die sich Ã¼ber einen Tisch beugt, ");

                        output("um einem Gast einen Drink zu servieren. Dabei zeigt sie vielleicht etwas mehr Haut als ");

                        output("nÃ¶tig, aber du fÃ¼hlst absolut keinen Drang danach, ihr das vorzuhalten.");

                        addnav("Flirt");

                        addnav("Zwinkern","inn.php?op=violet&flirt=1");

                        addnav("Handkuss","inn.php?op=violet&flirt=2");

                        addnav("KÃ¼sschen auf die Lippen","inn.php?op=violet&flirt=3");

                        addnav("Setze sie auf deinen SchoÃŸ","inn.php?op=violet&flirt=4");

                        addnav("Greif ihr an den Hintern","inn.php?op=violet&flirt=5");

                        addnav("Trag sie nach oben","inn.php?op=violet&flirt=6");

                        if ($session['user']['charisma']!=4294967295) addnav("Heirate sie","inn.php?op=violet&flirt=7");

                    }else{

                      $c = $session['user']['charm'];

                        $session['user']['seenlover']=1;

                      switch($_GET['flirt']){

                          case 1:

                              if (e_rand($c,2)>=2){

                                  output("Du zwinkerst `5Violet`0 zu und sie gibt dir ein warmes LÃ¤cheln zurÃ¼ck.");

                                    if ($c<4) $c++;

                                }else{

                                  output("Du zwinkerst `5Violet`0 zu, doch sie tut so, als ob sie es nicht bemerkt hÃ¤tte.");

                                }

                              break;

                          case 2:

                              if (e_rand($c,4)>=4){

                                  output("Selbstsicher schlenderst du Richtung `5Violet`0 durch den Raum. Du nimmst ihre Hand, ");

                                    output("kÃ¼sst sie sanft und hÃ¤ltst so fÃ¼r einige Sekunden inne. `5Violet`0 ");

                                    output("errÃ¶tet und streift eine HaarstrÃ¤hne hinter ihr Ohr. WÃ¤hrend du dich zurÃ¼ckziehst, presst sie ");

                                    output("die RÃ¼ckseite ihrer Hand sehnsÃ¼chtig an ihre Wange.");

                                    if ($c<7) $c++;

                                }else{

                                  output("Selbstsicher schlenderst du Richtung `5Violet`0 durch den Raum und greifst nach ihrer Hand.  ");

                                    output("`n`nAber `5Violet`0 zieht ihre Hand rasch zurÃ¼ck und fragt dich, ob du vielleicht ein Ale haben willst.");

                                }

                              break;

                          case 3:

                              if (e_rand($c,7)>=7){

                                  output("Du lehnst mit deinem RÃ¼cken an einer hÃ¶lzernen SÃ¤ule und wartest, bis `5Violet`0 in ");

                                    output("deine Richtung lÃ¤uft. Dann rufst du sie zu dir. Sie nÃ¤hert sich dir mit der Andeutung eines LÃ¤chelns im Gesicht. ");

                                    output("Du fasst ihr Kinn, hebst es etwas und presst ihr einen schnellen Kuss auf ihre prallen ");

                                    output("Lippen.");

                                    if ($session['user']['charisma']==4294967295) {

                                        output(" Deine Frau wird gar nicht begeistert sein, wenn sie davon erfÃ¤hrt!");

                                        $c--;

                                    } else {

                                        if ($c<11) $c++;

                                    }

                                }else{

                                  output("Du lehnst mit deinem RÃ¼cken an einer hÃ¶lzernen SÃ¤ule und wartest, bis `5Violet`0 in ");

                                    output("deine Richtung lÃ¤uft. Dann rufst du sie zu dir. Sie lÃ¤chelt und bedauert, dass sie ");

                                    output("mit ihrer Arbeit einfach zu beschÃ¤ftigt ist, um sich einen Moment fÃ¼r dich Zeit zu nehmen.");

                                }

                              break;

                          case 4:

                              if (e_rand($c,11)>=11){

    if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/giggle.wav\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);    

                                  output("Du sitzt an einem Tisch und lauerst auf deine Gelegenheit. Als `5Violet`0 bei dir vorbei kommt, ");

                                    output("umarmst du sie an der HÃ¼fte und ziehst sie auf deinen Schoss. Sie lacht ");

                                    output("und wirft dir ihre Arme in einer warmen Umarmung um den Hals. SchlieÃŸlich klopft sie dir auf die Brust ");

                                    output("und besteht darauf, dass sie wirklich wieder an die Arbeit gehen sollte.");

                                    if ($session['user']['charisma']==4294967295) {

                                        output(" Deine Frau wird gar nicht begeistert sein, wenn sie davon erfÃ¤hrt!");

                                        $c--;

                                    } else {

                                        if ($c<14) $c++;

                                    }

                                }else{

                                  output("Du sitzt an einem Tisch und lauerst auf deine Gelegenheit. Als `5Violet`0 bei dir vorbei kommt, ");

                                    output("grapschst du nach ihrer HÃ¼fte, aber sie weicht geschickt aus, ohne auch nur einen Tropfen von ");

                                    output("dem Ale zu verschÃ¼tten, das sie trÃ¤gt.");

                                    if ($c>0 && $c<10) $c--;

                                }

                              break;

                          case 5:

                              if (e_rand($c,14)>=14){

                                  output("Du wartest, bis `5Violet`0 an dir vorbeirauscht und gibst ihr einen Klaps auf den Hintern. Sie dreht sich um und ");

                                    output("gibt dir ein warmes, wissendes LÃ¤cheln.");

                                    if ($session['user']['charisma']==4294967295) {

                                        $c--;

                                    } else {

                                        if ($c<18) $c++;

                                    }

                                }else{

                                  output("Du wartest, bis `5Violet`0 an dir vorbeirauscht und gibst ihr einen Klaps auf den Hintern. Sie dreht sich um und ");

                                    output("verpasst dir eine Ohrfeige. Eine krÃ¤ftige! Vielleicht solltest du es etwas langsamer angehen.");

                                    //$session['user']['hitpoints']=1;

                                    if ($c>0 && $c<13) $c--;

                                }

                            if ($session['user']['charisma']==4294967295) output(" Deine Frau wird gar nicht begeistert sein, wenn sie davon erfÃ¤hrt!");

                              break;

                          case 6:

                              if (e_rand($c,18)>=18){

                                output("Wie ein Wirbelwind braust du durch die Schenke, schnappst dir `5Violet`0, die dir ihre Arme ");

                                    output("um den Hals wirft, und trÃ¤gst sie in ihren Raum nach oben. Kaum 10 Minuten spÃ¤ter ");

                                    output("stolzierst du, eine Pfeife rauchend und bis zu den Ohren grinsend, die Treppe wieder runter.  ");

                                    if ($session['user']['turns']>0){

                                      output("Du fÃ¼hlst dich ausgelaugt!  ");

                                        $session['user']['turns']-=2;

                                        if ($session['user']['turns']<0) $session['user']['turns']=0;

                                    }

                                    addnews("`@Es wurde beobachtet, wie ".$session['user']['name']."`@ und `5Violet`@ gemeinsam die Treppen in der Schenke nach oben gingen.");

                                    if ($session['user']['charisma']==4294967295 && e_rand(1,3)==2) {

                                          $sql = "SELECT acctid,name FROM accounts WHERE locked=0 AND acctid=".$session['user']['marriedto']."";

                                          $result = db_query($sql) or die(db_error(LINK));

                                        $row = db_fetch_assoc($result);

                                        $partner=$row['name'];

                                        addnews("`\$$partner hat ".$session['user']['name']."`\$ wegen eines Seitensprungs mit `5Violet`\$ verlassen!");

                                        output("`nDas war zu viel fÃ¼r $partner! Sie reicht die Scheidung ein. Die HÃ¤lfte deines Goldes auf der Bank wird ihr zugesprochen. Ab sofort bist du wieder solo!");

                                        $session['user']['charisma']=0;

                                        $session['user']['marriedto']=0;

                                        if ($session['user']['goldinbank']>0) $getgold=round($session['user']['goldinbank']/2);

                                        $session['user']['goldinbank']-=$getgold;

                                        $sql = "UPDATE accounts SET charisma=0,marriedto=0,goldinbank=goldinbank+$getgold WHERE acctid='$row['acctid']'";

                                        db_query($sql);

                                        systemmail($row['acctid'],"`\$Seitensprung!`0","`&{$session['user']['name']}`6 geht mit Violet fremd!`nDas ist Grund genug fÃ¼r dich, die Scheidung einzureichen. Ab sofort bist du wieder solo.`nDu bekommst `^$getgold`6 von seinem VermÃ¶gen auf dein Bankkonto.");

                                    }else if ($session['user']['charisma']==4294967295) {

                                        output(" Deine Frau wird gar nicht begeistert sein, wenn sie davon erfÃ¤hrt!");

                                        $c--;

                                    }else{

                                        if ($c<25) $c++;

                                    }

                                }else{

                                  output("Wie ein Wirbelwind fegst du durch die Schenke und schnappst nach `5Violet`0. Sie dreht sich um und ");

                                    output("schlÃ¤gt dir ins Gesicht! \"`%FÃ¼r was hÃ¤ltst du mich eigentlich?`0\" brÃ¼llt sie dich an! ");

                                    if ($c>0) $c--;

                                }

                              break;

                            case 7:

                                output("`5Violet`0 arbeitet fieberhaft, um einige GÃ¤ste der Schenke zu bedienen. Du schlenderst zu ihr rÃ¼ber, ");

                                output("nimmst ihr die Becher aus der Hand und stellst sie auf den nÃ¤chsten Tisch. Unter ihrem Protest kniest du dich auf einem Knie vor sie hin und nimmst ihre Hand. ");

                                output("Sie verstummt plÃ¶tzlich. Du starrst zu ihr hoch ");

                                output("und Ã¤uÃŸerst die Frage, von der du nie fÃ¼r mÃ¶glich gehalten hast, dass du sie einmal stellen wirst. ");

                                output("Sie starrt dich an und du liest sofort die Antwort aus ihrem Gesicht. ");

                              if ($c>=22){

                                  output("`n`nEs ist ein Ausdruck Ã¼berschÃ¤umender Freude. \"`%Ja! Ja, ja, ja!`0\" sagt sie. ");

                                    output(" Ihre letzten BestÃ¤tigungen gehen dabei in einem Sturm von KÃ¼ssen auf dein Gesicht und deinen Hals unter. ");

                                    output("`n`5Violet`0 und du heiraten in der Kirche am Ende der Strasse in ");

                                    output("einer prachtvollen Feier mit vielen rausgeputzten MÃ¤dels.");

                                    addnews("`&".$session['user']['name']." und `%Violet`& haben heute feierlich den Bund der Ehe geschlossen!!!");

                                    $session['user']['marriedto']=4294967295; // int max. I very much doubt that anyone is going to be

                                    $session['bufflist']['lover']=$buff;

                                    $session['user']['donation']+=1;

                                }else{

                                  output("`n`nEs ist ein sehr trauriger Blick. Sie sagt: \"`%Nein, ich bin noch nicht bereit fÃ¼r eine feste Bindung.`0\"");

                                    output("`n`nEntmutigt und enttÃ¤uscht hast du heute keine Lust mehr auf irgendwelche Abenteuer im Wald.");

                                    $session['user']['turns']=0;

                                }

                        }

                        if ($c > $session['user']['charm']) output("`n`n`^Du erhÃ¤ltst einen Charmepunkt!");

                        if ($c < $session['user']['charm']) output("`n`n`\$Du VERLIERST einen Charmepunkt!");

                        $session['user']['charm']=$c;

                    }

                }else{

                  output("Du denkst, es ist besser, dein GlÃ¼ck mit `5Violet`0 heute nicht mehr herauszufordern.");

                }

            }else{

              //sorry, no lezbo action here.

            }

            break;

        case "seth":

            /*Wink

            Flutter Eyelashes

            Drop Hankey

            Ask the bard to buy you a drink

            Kiss the bard soundly

            Completely seduce the bard

            Marry him*/

      if ($_GET[subop]=="" && $_GET['flirt']==""){

        output("Seth schaut dich erwartungsvoll an.");

        addnav("Fordere Seth auf, dich zu unterhalten","inn.php?op=seth&subop=hear");

        if ($session['user']['sex']==1){

            if ($session['user']['marriedto']==4294967295) {

                addnav("Flirte mit Seth", "inn.php?op=seth&flirt=1");

            } else {

                addnav("Flirt");

                addnav("Zwinkern","inn.php?op=seth&flirt=1");

                addnav("Mit den Wimpern klimpern","inn.php?op=seth&flirt=2");

                addnav("Taschentuch fallenlassen","inn.php?op=seth&flirt=3");

                addnav("Frage ihn nach einem Drink","inn.php?op=seth&flirt=4");

                addnav("KÃ¼sse ihn gerÃ¤uschvoll","inn.php?op=seth&flirt=5");

                addnav("Den Barden komplett verfÃ¼hren","inn.php?op=seth&flirt=6");

                if ($session['user']['charisma']!=4294967295) addnav("Heirate ihn","inn.php?op=seth&flirt=7");

            }

        } else {

            addnav("Frage Seth nach seiner Meinung Ã¼ber dein(e/n) ".$session['user']['armor'],"inn.php?op=seth&act=armor");

        }

      }

            if ($_GET['act']=="armor"){

                $charm = $session['user']['charm']+e_rand(-1,1);

                output("Seth schaut dich ernst von oben bis unten an. Nur wahre Freunde kÃ¶nnen wirklich ehrlich sein, das ist der Grund, weshalb du ");

                output("ihn gefragt hast. SchlieÃŸlich kommt er zu einem Schluss und sagt: \"`%");

                switch($charm){

                    case -3:

                    case -2:

                    case -1:

                    case 0:

                        output("Du machst mich glÃ¼cklich, dass ich nicht schwul bin!");

                        break;

                    case 1:

                    case 2:

                    case 3:

                        output("Ich habe einige hÃ¼bsche MÃ¤nner in meinem Leben gesehen, aber ich fÃ¼rchte du bist keiner von ihnen.");

                        break;

                    case 4:

                    case 5:

                    case 6:

                        output("Ich habe schon schlimmeres gesehen, aber nur beim Verfolgen eines Pferdes.");

                        break;

                    case 7:

                    case 8:

                    case 9:

                        output("Du bist ziemlicher Durchschnitt, mein Freund.");

                        break;

                    case 10:

                    case 11:

                    case 12:

                        output("Du bist schon etwas zum Anschauen, aber lass dir das nicht zu sehr zu Kopfe steigen, ja?");

                        break;

                    case 13:

                    case 14:

                    case 15:

                        output("Du siehst schon ein bisschen besser aus als der Durchschnitt!");

                        break;

                    case 16:

                    case 17:

                    case 18:

                        output("Nur wenige Frauen kÃ¶nnten dir widerstehen!");

                        break;

                    case 19:

                    case 20:

                    case 21:

                    case 22:

                        output("Willst du mich mit dieser Frage neidisch machen? Oder mich einfach nur Ã¤rgern?");

                        break;

                    case 23:

                    case 24:

                    case 25:

                        output("Ich bin von deiner SchÃ¶nheit geblendet.");

                        break;

                    case 26:

                    case 27:

                    case 28:

                    case 29:

                    case 30:

                        output("Ich hasse dich. Warum? Weil du einfach der schÃ¶nste Mann aller Zeiten bist!");

                        break;

                    default:

                        output("Vielleicht solltest du langsam etwas gegen deine Ã¼berirdische SchÃ¶nheit tun. Du bist unerreichbar!");

                }

                output("`0\"");

            }

      if ($_GET[subop]=="hear"){

        //$session['user']['seenbard']=0;

        if($session['user']['seenbard']){

          output("Seth rÃ¤uspert sich und trinkt einen Schluck Wasser. \"Tut mir Leid, mein Hals ist einfach zu trocken.\"");

         // addnav("Return to the inn","inn.php");

        }else{

          $rnd = e_rand(0,18);

          output("Seth rÃ¤uspert sich und fÃ¤ngt an:`n`n`^");

          $session['user']['seenbard']=1;

          switch ($rnd){

            case 0:

              output("`@GrÃ¼ner Drache`^ ist grÃ¼n.`n`@GrÃ¼ner Drache`^ ist wild.`n`@GrÃ¼nen Drachen`^ wÃ¼nsch ich mir gekillt. ");

              output("`n`n`0Du erhÃ¤ltst ZWEI zusÃ¤tzliche WaldkÃ¤mpfe fÃ¼r heute!");

              $session['user']['turns']+=2;

              break;

            case 1:

              output("Mireraband, ich spotte euch und spuck auf euren FuÃŸ.`nDenn er verstrÃ¶mt fauligen Gestank mehr als er muÃŸ! ");

              output("`n`n`0Du fÃ¼hlst dich erheitert und bekommst einen extra Waldkampf.");

              $session['user']['turns']++;

              break;

            case 2:

    if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/ragtime.mid\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);    

              output("Membrain Mann Membrain Mann.`nMembrain Mann hasst ".$session['user']['name']."`^ Mann.`nSie haben einen Kampf, Mambrain gewinnt.`nMembrain Mann. ");

              output("`n`n`0Du bist dir nicht ganz sicher, was du davon halten sollst... du gehst lieber wieder weg und denkst, es ist besser, Seth wieder zu besuchen, wenn er sich besser fÃ¼hlt. ");

                            output("Nach einer kurzen Verschnaufpause kÃ¶nntest du wieder ein paar bÃ¶se Jungs verprÃ¼geln.");

                            $session['user']['turns']++;

              break;

            case 3:

              output("FÃ¼r eine Geschichte versammelt euch hier`neine Geschichte so schrecklich und hart`nÃ¼ber Cedrik und sein gepanschtes Bier`nund wie sehr er ihn hasst, mich, den Bard'! ");

              output("`n`n`0Du stellst fest, dass er Recht hat, Cedriks Bier ist wirklich eklig. Das dÃ¼rfte der Grund dafÃ¼r sein, warum die meisten GÃ¤ste sein Ale bevorzugen. Du kannst der Geschichte von Seth nicht wirklich etwas abgewinnnen, aber du findest dafÃ¼r etwas Gold auf dem Boden!");

              $gain = e_rand(10,50);

              $session['user']['gold']+=$gain;

              //debuglog("found $gain gold near Seth");

              break;

            case 4:

    output("Der groÃŸe grÃ¼ne Drache hatte eine Gruppe Zwerge entdeckt und sie *schlurps* einfach aufgefuttert. Sein Kommentar spÃ¤ter war: \"Die schmecken ja toll... aber... kleiner sollten sie wirklich nicht sein!\" ");

    if ($session['user']['race']==4){

        output("Als Zwerg kannst du darÃ¼ber nicht lachen. Mit grimmigem Gesichtsausdruck, der auch Seths Lachen zu ersticken scheint, schlÃ¤gst du ihn zu Boden.");

        output ("Du bist so wÃ¼tend, dass dich heute wohl nichts mehr erschrecken kann.");

    }else{

                     output("`n`n`0Mit einem guten, herzlichen Kichern in deiner Seele rÃ¼ckst du wieder aus, bereit fÃ¼r was auch immer da kommen mag!");

    }

              $session['user']['hitpoints']=round($session['user']['maxhitpoints']*1.2,0);

              break;

            case 5:

              output("HÃ¶rt gut zu und nehmt es euch zu Herzen: Mit jeder Sekunde rÃ¼cken wir dem Tod etwas nÃ¤her. *zwinker*");

              output("`n`n`0Deprimiert wendest du dich ab... und verlierst einen Waldkampf!");

              $session['user']['turns']--;

                            if ($session['user']['turns']<0) $session['user']['turns']=0;

    $session['user']['donation']+=1;

              break;

            case 6:

    if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/matlock.mid\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);

              output("Ich liebe MightyE, die Waffen von MightyE, ich liebe MightyE, die Waffen von MightyE, ich liebe MightyE, die Waffen von MightyE, nichts tÃ¶tet so gut wie die WAFFEN von ... MightyE!");

              output("`n`n`0Du denkst, Seth ist ganz in Ordnung... jetzt willst du los, um irgendwas zu tÃ¶ten. Aus irgendeinem Grund denkst du an Bienen und Fisch.");

              $session['user']['turns']++;

              break;

            case 7:

    if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/burp.wav\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);

              output("`0Seth richtet sich auf und scheint sich auf etwas eindrucksvolles vorzubereiten. Dann rÃ¼lpst er dir laut ins Gesicht. \"`^War das unterhaltsam genug?`0\"");

              output("`n`n`0Der Gestank nach angedautem Ale ist Ã¼berwÃ¤ltigend. Dir wird etwas Ã¼bel und du verlierst ein paar Lebenspunkte.");

              $session['user']['hitpoints']-= round($session['user']['maxhitpoints'] * 0.1,0);

              if ($session['user']['hitpoints']<=0) $session['user']['hitpoints']=1;

    $session['user']['donation']+=1;

              break;

            case 8:

                if ($session['user']['gold'] >= 5) {

                  output("`0\"`^Welches GerÃ¤usch macht es, wenn man mit einer Hand klatscht?`0\", fragt Seth. WÃ¤hrend du Ã¼ber diese Scherzfrage nachgrÃ¼belst, \"befreit\" Seth eine kleine UnterhaltungsgebÃ¼hr aus deinem GoldsÃ¤ckchen.");

                  output("`n`nDu verlierst 5 Gold!");

                  $session['user']['gold']-=5;

                  //debuglog("lost 5 gold to Seth");

                } else {

                  output("`0\"`^Welches GerÃ¤usch macht es, wenn man mit einer Hand klatscht?`0\", fragt Seth. WÃ¤hrend du Ã¼ber diese Scherzfrage nachgrÃ¼belst, versucht Seth eine kleine UnterhaltungsgebÃ¼hr aus deinem GoldsÃ¤ckchen zu befreien, findet aber nicht, was er sich erhofft hat.");

        $session['user']['donation']+=1;

                }

              break;

            case 9:

              output("Welcher Fuss muss immer zittern?`n`nDer Hasenfuss.");

              output("`n`nDu grÃ¶hlst und Seth lacht herzlich. KopfschÃ¼ttelnd bemerkst du einen Edelstein im Staub.");

              $session['user']['gems']++;

              //debuglog("got 1 gem from Seth");

              break;

            case 10:

              output("Seth spielt eine sanfte, aber mitreiÃŸende Melodie.");

    if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/indianajones.mid\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);

              output("`n`nDu fÃ¼hlst dich entspannt und erholt und deine Wunden scheinen sich zu schlieÃŸen.");

              if ($session['user']['hitpoints']<$session['user']['maxhitpoints']) $session['user']['hitpoints']=$session['user']['maxhitpoints'];

              break;

            case 11:

              output("Seth spielt dir ein dÃ¼steres Klagelied vor.");

    if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/eternal.mid\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);

              output("`n`nDeine Stimmung fÃ¤llt und du wirst heute nicht mehr so viele BÃ¶sewichte erschlagen.");

              $session['user']['turns']--;

              if ($session['user']['turns']<0) $session['user']['turns']=0;

              break;

            case 12:

    if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/babyphan.mid\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);

              output("Die Ameisen marschieren in Einerreihen, Hurra, Hurra!`nDie Ameisen marschieren in Einerreihen, Hurra, Hurra!`nDie Ameisen marschieren in Einerreihen, Hurra, Hurra, und die kleinste stoppt und nuckelt am Daumen.`nUnd sie alle marschieren in den Bau um vorm Regen abzuhaun.`nBumm, bumm, bumm.`nDie Ameisen marschieren in Zweierreihen, Hurra, Hurra! ....");

              output("`n`n`0Seth singt immer weiter, aber du hast nicht den Wunsch herauszufinden, wie weit Seth zÃ¤hlen kann, deswegen verschwindest du leise. Nach dieser kurzen Rast fÃ¼hlst du dich erholt.");

                            $session['user']['hitpoints']=$session['user']['maxhitpoints'];

              break;

            case 13:

              output("Es war ein mal eine Dame von der Venus, ihr KÃ¶rper war geformt wie ein ...");

              if ($session['user']['sex']==1){

                output("`n`n`0Seth wird durch einen barschen Schlag ins Gesicht unterbrochen. Du fÃ¼hlst dich rauflustig und gewinnst einen Waldkampf dazu.");

              }else{

                output("`n`n`0Seth wird durch dein plÃ¶tzliches lautes GelÃ¤chter unterbrochen, das du ausstÃ¶ÃŸt, ohne seinen Reim vollstÃ¤ndig gehÃ¶rt haben zu mÃ¼ssen. So angespornt erhÃ¤ltst du einen zusÃ¤tzlichen Waldkampf.");

              }

              $session['user']['turns']++;

              break;

            case 14:

    if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/knightrider.mid\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);

              output("Seth spielt einen stÃ¼rmischen Schlachtruf fÃ¼r dich, der den Kriegergeist in dir erweckt.");

              output("`n`n`0Du bekommst einen zusÃ¤tzlichen Waldkampf!");

              $session['user']['turns']++;

              break;

            case 15:

              output("Seth scheint in Gedanken vÃ¶llig woanders zu sein ... bei deinen ... Augen.");

              if ($session['user']['sex']==1){

                output("`n`n`0Du erhÃ¤ltst einen Charmepunkt!");

                $session['user']['charm']++;

              }else{

                output("`n`n`0Aufgebracht stÃ¼rmst du aus der Bar! In deiner Wut bekommst du einen Waldkampf dazu.");

                $session['user']['turns']++;

              }

              break;

            case 16:

    if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/boioing.wav\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);

              output("Seth fÃ¤ngt an zu spielen, aber eine Saite seiner Laute reiÃŸt plÃ¶tzlich und schlÃ¤gt dir flach ins Auge.`n`n`0\"`^Uuuups! Vorsicht, du wirst dir noch deine Augen ausschieÃŸen, Mensch!`0\"");

              output("`n`nDu verlierst einige Lebenspunkte!");

              $session['user']['hitpoints']-=round($session['user']['maxhitpoints']*.1,0);

                            if ($session['user']['hitpoints']<1) $session['user']['hitpoints']=1;

              break;

            case 17:

              output("Er fÃ¤ngt an zu spielen, als ein rauflustiger Gast vorbeistolpert und Bier auf dich verschÃ¼ttet. Du verpasst die ganze Vorstellung wÃ¤hrend du das GesÃ¶ff von deine(r/m) ".$session['user']['armor']." putzt.");

    $session['user']['donation']+=1;     

         break;

            case 18:

              output("`0Seth starrt dich gedankenvoll an. Offensichtlich komponiert er gerade ein episches Gedicht...`n`n`^H-Ã„-S-S-L-I-C-H, du kannst dich nicht verstecken -- Du bist hÃ¤sslich, yeah, yeah, so hÃ¤sslich!");

              $session['user']['charm']--;

              if ($session['user']['charm']<0){

                output("`n`n`0Wenn du einen Charmepunkt hÃ¤ttest, hÃ¤ttest du ihn jetzt verloren. Aber so reiÃŸt Seth nur eine Saite seiner Laute.");

              }else{

                output("`n`n`0Deprimiert verlierst du einen Charmepunkt.");

              }

              break;

          }

        }

      }

            if ($session['user']['sex']==1 && $_GET['flirt']<>""){

              //$session['user']['seenlover']=0;

              if ($session['user']['seenlover']==0){

                    if ($session['user']['marriedto']==4294967295){

                    if (e_rand(1,4)==1){

                      output("Du gehst rÃ¼ber zu Seth, um ihn zu knuddeln und mit KÃ¼ssen zu Ã¼berhÃ¤ufen, aber er brummelt nur etwas ");

                      switch(e_rand(1,4)){

                      case 1:

                        output("darÃ¼ber, dass er damit beschÃ¤ftigt ist, seine Laute zu stimmen. ");

                        break;

                      case 2:

                        output("wie \"um diese Zeit?\" ");

                        break;

                       case 3:

                        output("wie \"leicht erkÃ¤ltet...  *hust hust* siehst du?\" ");

                        break;

                      case 4:

                        output("darÃ¼ber, dass er sich ein Bier holen will. ");

                        break;

                      }

                      output("Nach so einem Kommentar lÃ¤sst du ihn stehen und haust ab!");

                      $session['user']['charm']--;

                      output("`n`n`^Du VERLIERST einen Charmepunkt!");

                    }else{

                      output("Du und Seth nehmt euch etwas Zeit fÃ¼reinander und du verlÃ¤sst die Schenke mit einem zuversichtlichen Strahlen!");

                      $session['bufflist']['lover']=$buff;

                      $session['user']['charm']++;

                      output("`n`n`^Du erhÃ¤ltst einen Charmepunkt!");

                    }

                    $session['user']['seenlover']=1;

                  } elseif ($_GET['flirt']==""){

                    }else{

                      $c = $session['user']['charm'];

                        $session['user']['seenlover']=1;

                      switch($_GET['flirt']){

                          case 1:

                              if (e_rand($c,2)>=2){

                                  output("Seth grinst ein breites Grinsen. Hach, ist dieses GrÃ¼bchen in seinem Kinn nicht sÃ¼ÃŸ??");

                                    if ($c<4) $c++;

                                }else{

                                  output("Seth hebt eine Augenbraue und fragt dich, ob du etwas im Auge hast.");

                                }

                              break;

                          case 2:

                              if (e_rand($c,4)>=4){

                                  output("Seth lÃ¤chelt dich an und sagt: \"`^Du hast wunderschÃ¶ne Augen`0\"");

                                    if ($c<7) $c++;

                                }else{

                                    output("Seth lÃ¤chelt und winkt ... zu der Person hinter dir.");

                                }

                              break;

                          case 3:

                              if (e_rand($c,7)>=7){

                                  output("WÃ¤hrend Seth sich bÃ¼ckt, um dir dein Taschentuch zurÃ¼ckzugeben, bewunderst du seinen knackigen Hintern.");

                                    if ($session['user']['charisma']==4294967295) {

                                        output(" Dein Mann wird gar nicht begeistert sein, wenn er davon erfÃ¤hrt!");

                                        $c--;

                                    } else {

                                        if ($c<11) $c++;

                                    }

                                }else{

                                    output("Seth hebt das Taschentuch auf, putzt sich damit die Nase und gibt es dir zurÃ¼ck.");

                                }

                              break;

                          case 4:

                              if (e_rand($c,11)>=11){

                                  output("Seth platziert seinen Arm um deine HÃ¼fte, geleitet dich an die Bar und kauft dir eines der kÃ¶stlichsten GetrÃ¤nke, die es in der Schenke gibt.");

                                    if ($session['user']['charisma']==4294967295) {

                                        output(" Dein Mann wird gar nicht begeistert sein, wenn er davon erfÃ¤hrt!");

                                        $c--;

                                    } else {

                                        if ($c<14) $c++;

                                    }

                                }else{

                                  output("Seth bedauert: \"`^Tut mir Leid, meine Dame, ich habe kein Geld zu verschenken.`0\" Dabei stÃ¼lpt er seine mottenzerfressenen Taschen nach auÃŸen.");

                                    if ($c>0 && $c<10) $c--;

                                }

                              break;

                          case 5:

                              if (e_rand($c,14)>=14){

                                  output("Du lÃ¤ufst auf Seth zu, packst ihn am Hemd, stellst ihn auf die Beine und drÃ¼ckst ihm einen krÃ¤ftigen, langen Kuss direkt auf seine attraktiven Lippen. Seth bricht fast zusammen - mit zerzausten Haaren und ziemlich atemlos.");

                                    if ($session['user']['charisma']==4294967295) {

                                        $c--;

                                    } else {

                                        if ($c<18) $c++;

                                    }

                                }else{

                                  output("Du bÃ¼ckst dich zu Seth herunter, um ihn auf die Lippen zu kÃ¼ssen, doch als sich eure Lippen gerade berÃ¼hren wollen, bÃ¼ckt sich Seth, um sich den Schuh zuzubinden.");

                                    // $session['user']['hitpoints']=1; //why the heck was this here???

                                    if ($c>0 && $c<13) $c--;

                                } 

                            if ($session['user']['charisma']==4294967295) output(" Dein Mann wird gar nicht begeistert sein, wenn er davon erfÃ¤hrt!");

                              break;

                          case 6:

                              if (e_rand($c,18)>=18){

                                output("Du stehst auf der ersten Treppenstufe und gibst Seth ein 'komm hierher' Zeichen. Er folgt dir wie ein SchoÃŸhÃ¼ndchen.");

                                    if ($session['user']['turns']>0){

                                      output("Du fÃ¼hlst dich ausgelaugt!  ");

                                      $session['user']['turns']-=2;

                                      if ($session['user']['turns']<0) $session['user']['turns']=0;

                                    }

                                    addnews("`@Es wurde beobachtet, wie ".$session['user']['name']."`@ und `^Seth`@ gemeinsam die Treppen in der Schenke nach oben gingen.");

                                    if ($session['user']['charisma']==4294967295 && e_rand(1,3)==2) {

                                        $sql = "SELECT acctid,name FROM accounts WHERE locked=0 AND acctid=".$session['user']['marriedto']."";

                                          $result = db_query($sql) or die(db_error(LINK));

                                        $row = db_fetch_assoc($result);

                                        $partner=$row['name'];

                                        addnews("`\$$partner hat ".$session['user']['name']."`\$ wegen eines Seitensprungs mit `^Seth`\$ verlassen!");

                                        output("`nDas war zu viel fÃ¼r $partner! Er reicht die Scheidung ein. Die HÃ¤lfte deines Goldes auf der Bank wird ihm zugesprochen. Ab sofort bist du wieder solo!");

                                        $session['user']['charisma']=0;

                                        $session['user']['marriedto']=0;

                                        if ($session['user']['goldinbank']>0) $getgold=round($session['user']['goldinbank']/2);

                                        $session['user']['goldinbank']-=$getgold;

                                        $sql = "UPDATE accounts SET charisma=0,marriedto=0,goldinbank=goldinbank+$getgold WHERE acctid='$row['acctid']'";

                                        db_query($sql);

                                        systemmail($row['acctid'],"`\$Seitensprung!`0","`&{$session['user']['name']}`6 geht mit Seth fremd!`nDas ist Grund genug fÃ¼r dich, die Scheidung einzureichen. Ab sofort bist du wieder solo.`nDu bekommst `^$getgold`6 von ihrem VermÃ¶gen auf dein Bankkonto.");

                                    }else if ($session['user']['charisma']==4294967295) {

                                        output(" Dein Mann wird gar nicht begeistert sein, wenn er davon erfÃ¤hrt!");

                                        $c--;

                                    }else{

                                        if ($c<25) $c++;

                                    }

                                }else{

                                  output("\"`^Tut mir Leid meine Dame, aber ich habe in 5 Minuten einen Auftritt.`0\"");

                                    if ($c>0) $c--;

                                }

                              break;

                            case 7:

                                output("Du gehst zu Seth und verlangst ohne Umschweife von ihm, daÃŸ er dich heiratet.`n`nEr schaut dich ein paar Sekunden lang an.`n`n");

                              if ($c>=22){

                                    output("\"`^NatÃ¼rlich, meine Liebe!`0\", sagt er. Die nÃ¤chsten wochen bist du damit beschÃ¤ftigt, eine gigantische Hochzeit vorzubereiten, die natÃ¼rlich Seth zahlt, und danach geht es in den dunklen Wald in die Flitterwochen.");

                                    addnews("`&".$session['user']['name']." und `^Seth`& haben heute feierlich den Bund der Ehe geschlossen!!!");

                                    $session['user']['marriedto']=4294967295; //int max.

                                      $session['bufflist']['lover']=$buff;

                                    $session['user']['donation']+=1;

                                }else{

                                    output("`^Seth sagt: \"`^Es tut mir Leid, offensichtlich habe ich einen falschen Eindruck erweckt. Ich denke, wir sollten einfach nur Freunde sein.`0\"  Deprimiert hast du heute kein Verlangen mehr danach, nochmal im Wald kÃ¤mpfen zu gehen.");

                                    $session['user']['turns']=0;

                                }

                        }

                        if ($c > $session['user']['charm']) output("`n`n`^Du bekommst einen Charmepunkt!");

                        if ($c < $session['user']['charm']) output("`n`n`\$Du VERLIERST einen Charmepunkt!");

                        $session['user']['charm']=$c;

                    }

                }else{

                      output("Du denkst, es ist besser, dein GlÃ¼ck mit `^Seth`0 heute nicht mehr herauszufordern.");

                }

            }else{

              //sorry, no lezbo action here.

            }

            break;

        case "converse":

          output("Du schlenderst rÃ¼ber zu einem Tisch, stellst den FuÃŸ auf die Bank und lauschst der Unterhaltung:`n");

            viewcommentary("inn","Zur Unterhaltung beitragen:",20);

            break;

        case "bartender":

        if (getsetting("paidales",0)<=1 || $session['user']['gotfreeale']>=2) {

            $alecost = $session['user']['level']*10;

        } else {

            $alecost = 0;

        }

          if ($_GET['act']==""){

                output("Cedrik schaut dich irgendwie schrÃ¤g an. Er ist keiner von der Sorte, die einem Mann viel weiter trauen, ");

                output("als sie ihn werfen kÃ¶nnen, was Zwergen einen entscheidenden Vorteil verleiht. Mit Ausnahme von Regionen natÃ¼rlich, ");

                output("in denen Zwergenweitwurf verboten wurde.  Cedrik poliert ein Glas und hÃ¤lt es ins Licht, das durch die TÃ¼r hereinscheint, als ein Gast ");

                output("die Schenke verlÃ¤ÃŸt. Dann verzieht er das Gesicht, spuckt auf das Glas ");

                output("und fÃ¤hrt mit der Politur fort. \"`%Was willst'n?`0\", fragt er dich schroff.");

                addnav("Schwarzes Brett","inn.php?op=msgboard");

                addnav("Bestechen","inn.php?op=bartender&act=bribe");

                addnav("Edelsteine","inn.php?op=bartender&act=gems");

                if (getsetting("paidales",0)<=1) {

                    addnav("Ale (`^$alecost`0 Gold)","inn.php?op=bartender&act=ale");

                    addnav("Runde schmeiÃŸen","inn.php?op=bartender&act=schmeiss");

                } else {

                    $amt=getsetting("paidales",0)-1;

                    addnav("Ale (`^".($session['user']['gotfreeale']>=2?"$alecost`0 Gold":"schon bezahlt`0").")","inn.php?op=bartender&act=ale");

                    output("`nEs stehen noch $amt frisch gefÃ¼llte und schon bezahlte KrÃ¼ge mit Ale vor Cedrik.");

                    if ($session['user']['gotfreeale']>=2) output(" Leider hattest du dein Frei-Ale fÃ¼r heute schon und du wirst selbst bezahlen mÃ¼ssen.");

                }

              $drunkenness = array(-1=>"absolut nÃ¼chtern",

                                                         0=>"ziemlich nÃ¼chtern",

                                                         1=>"kaum berauscht",

                                                          2=>"leicht berauscht",

                                                         3=>"angetrunken",

                                                         4=>"leicht betrunken",

                                                         5=>"betrunken",

                                                         6=>"ordentlich betrunken",

                                                         7=>"besoffen",

                                                         8=>"richtig zugedrÃ¶hnt",

                                                         9=>"fast bewusstlos"

                                    );

                $drunk = round($session['user']['drunkenness']/10-.5,0);

                if ($drunkenness[$drunk]){

                    output("`n`n`7Du fÃ¼hlst dich ".$drunkenness[$drunk]."`n`n");

                }else{

                    output("`n`n`7Du fÃ¼hlst dich nicht mehr.`n`n");

                }

            }else if ($_GET['act']=="gems"){

              if ($_POST['gemcount']==""){

                    output("\"`%Du hast Edelsteine, oder?`0\", fragt dich Cedrik. \"`%Nun, fÃ¼r `^zwei Edelsteine`% werd ich dir nen magischen Trank machen!`0\"");

                    output("`n`nWieviele Edelsteine gibst du ihm?");

                    output("<form action='inn.php?op=bartender&act=gems' method='POST'><input name='gemcount' value='0'><input type='submit' class='button' value='Weggeben'>`n",true);

                    output("Und was willst du dafÃ¼r?`n<input type='radio' name='wish' value='1' checked> Charme`n<input type='radio' name='wish' value='2'> Lebenskraft`n",true);

                    addnav("","inn.php?op=bartender&act=gems");

                    output("<input type='radio' name='wish' value='3'> Gesundheit`n",true);

                    output("<input type='radio' name='wish' value='4'> Vergessen`n",true);

                    output("<input type='radio' name='wish' value='5'> Transmutation</form>",true);

                }else{

                  $gemcount = abs((int)$_POST['gemcount']);

                    if ($gemcount>$session['user']['gems']){

                      output("Cedrik starrt dich an und sagt: \"`%Du hast nich so viele Edelsteine, `bzieh los und besorg dir noch welche!`b`0\"");

                    }else{

                      output("`#Du platzierst $gemcount Edelsteine auf der Theke.");

                        if ($gemcount % 2 == 0){

                        }else{

                            output("  Cedrik, der Ã¼ber deine absolute mathematische UnfÃ¤higkeit bescheid weiss, ");

                            output("gibt dir einen davon zurÃ¼ck.");

                            $gemcount-=1;

                        }

                        if ($gemcount>0) output("  Du trinkst den Trank, den Cedrik dir im Austausch fÃ¼r deine Edelsteine gegeben hat und.....`n`n");

                        $session['user']['gems']-=$gemcount;

                          //debuglog("used $gemcount gems on potions");

                        if ($gemcount>0){

                            switch($_POST[wish]){

                                case 1:

                                    $session['user']['charm']+=($gemcount/2);

                                    output("`&Du fÃ¼hlst dich charmant! `^(Du erhÃ¤ltst Charmepunkte)");

                                    break;

                                case 2:

                                    $session['user']['maxhitpoints']+=($gemcount/2);

                                    $session['user']['hitpoints']+=($gemcount/2);

                                    output("`&Du fÃ¼hlst dich lebhaft! `^(Deine maximale Lebensenergie erhÃ¶ht sich permanent)");

                                    break;

                                case 3:

                                    if ($session['user']['hitpoints']<$session['user']['maxhitpoints']) $session['user']['hitpoints']=$session['user']['maxhitpoints'];

                                    $session['user']['hitpoints']+=($gemcount*10);

                                    output("`&Du fÃ¼hlst dich gesund! `^(Du erhÃ¤ltst vorÃ¼bergehend mehr Lebenspunkte)");

                                    break;

                                case 4:

                                    $session['user']['specialty']=0;

                                    output("`&Du fÃ¼hlst dich vÃ¶llig ziellos in deinem Leben. Du solltest eine Pause machen und einige wichtige Entscheidungen Ã¼ber dein Leben treffen! `^(Dein Spezialgebiet wurde zurÃ¼ckgesetzt)");

                                    break;

                                case 5:

                                    if ($session['user']['race']==1) $session['user']['attack']--;

                                    if ($session['user']['race']==2) $session['user']['defence']--;

                                    if ($session['user']['race']==5) $session['user']['maxhitpoints']--;

                                    $session['user']['race']=0;

                                    output("`@Deine Knochen werden zu Gelatine und du musst vom Effekt des Tranks ordentlich wÃ¼rgen!`^(Deine Rasse wurde zurÃ¼ckgesetzt. Du kannst morgen eine neue wÃ¤hlen.)");

                                    if (isset($session['bufflist']['transmute'])) {

                                        $session['bufflist']['transmute']['rounds'] += 10;

                                    } else {

                                        $session['bufflist']['transmute']=array(

                                            "name"=>"`6Transmutationskrankheit",

                                            "rounds"=>10,

                                            "wearoff"=>"Du hÃ¶rst auf, deine DÃ¤rme auszukotzen. Im wahrsten Sinne des Wortes.",

                                            "atkmod"=>0.75,

                                            "defmod"=>0.75,

                                            "roundmsg"=>"Teile deiner Haut und deiner Knochen verformen sich wie Wachs.",

                                            "survivenewday"=>1,

                                            "newdaymessage"=>"`6Durch die Auswirkungen des Transmutationstranks fÃ¼hlst du dich immer noch `2krank`6.",

                                            "activate"=>"offense,defense"

                                        );

                                    }

                                    break;

                            }

                        }else{

                          output("`n`nDu hast das unbestimmte GefÃ¼hl, dass deine Edelsteine fÃ¼r etwas anderes als stinkende TrÃ¤nke besser eingesetzt wÃ¤ren.");

                        }

                    }

                }

            }else if ($_GET['act']=="schmeiss"){

                output("Du bist guter Laune und Ã¼berlegst dir, ob du fÃ¼r deine Kumpels hier in der Schenke ne Runde Ale spendieren solltest.`n");

                output("`n1 Ale kostet dich `^$alecost`0 Gold.`n");

                output("<form action='inn.php?op=bartender&act=schmeiss2' method='POST'>Die nÃ¤chsten <input name='runden' id='runden' width='4'> Ale gehen auf deine Rechnung.`n",true);

                output("<input type='submit' class='button' value='Ausgeben'></form>",true);

                output("<script language='javascript'>document.getElementById('runden').focus();</script>",true);

                addnav("","inn.php?op=bartender&act=schmeiss2");

            }else if ($_GET['act']=="schmeiss2"){

                $amt = abs((int)$_POST['runden']);

                $jamjam=$amt*$alecost;

                $schussel=$session['user']['name'];

                if ($session['user']['gold']<$jamjam){

                    output("Du stellst gerade noch rechtzeitig vor einer Blamage fest, dass du nicht genug Gold dabei hast.");

                } else if (getsetting("paidales",0)>1 || $alecost==0){

                    output("Tja, der gute Wille war da, doch ein anderer war schneller als du! EnttÃ¤uscht bewegst du dich Richtung Freiale und schwÃ¶rst dir, in Zukunft schneller zu sein.");

                } else if (abs($session['user']['gotfreeale']-2)==1){

                    output("Cedrik schaut dir tief in die Augen und meint nur \"`%Du hast heute schonmal eine Runde spendiert. In meiner Schenke machst du niemanden zum SÃ¤ufer. Alles klar?`0\"");

                } else if ($amt>getsetting("maxales",50)){

                    output("\"`%Hast du sie noch alle, hier so mit deinem Gold anzugeben? Schau dich doch mal um, wieviele Ã¼berhaupt da sind!`0\" Mit diesen Worten zeigt dir Cedrik einen Vogel und dreht sich gelangweilt weg. ");

                }else{

                    output("Du sprichst mit Barkeeper Cedrik und schiebst ihm `^$jamjam`0 Gold rÃ¼ber. Dieser nickt mit dem Kopf und grÃ¶lt in die Runde \"`%Die nÃ¤chsten $amt Ale gehen auf $schussel !!`0\".");

                    output("Ein allgemeiner Freudenschrei ist die Antwort und du bist der Held der Stunde.`n`n");

                    if ($amt>5){

                        output("`^Du erhÃ¤ltst einen Charmepunkt!`0");

                        $session['user']['charm']+=1;

                    }

                    //if ($amt>10) $session['user']['donation']+=1;

                    savesetting("paidales",$amt+1);

                    $session['user']['gold']-=$jamjam;

                    $session['user']['gotfreeale']++;

                    $sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'inn',".$session['user']['acctid'].",'/me spendiert die nÃ¤chsten `^$amt`& Ale!')";

                    db_query($sql) or die(db_error(LINK));

                }

            }else if ($_GET['act']=="bribe"){

                $g1 = $session['user']['level']*10;

                $g2 = $session['user']['level']*50;

                $g3 = $session['user']['level']*100;

                $session['user']['reputation']--;

                if ($_GET[type]==""){

                    output("Wie viel willst du ihm anbieten?");

                    addnav("1 Edelstein","inn.php?op=bartender&act=bribe&type=gem&amt=1");

                    addnav("2 Edelsteine","inn.php?op=bartender&act=bribe&type=gem&amt=2");

                    addnav("3 Edelsteine","inn.php?op=bartender&act=bribe&type=gem&amt=3");

                    addnav("$g1 Gold","inn.php?op=bartender&act=bribe&type=gold&amt=$g1");

                    addnav("$g2 Gold","inn.php?op=bartender&act=bribe&type=gold&amt=$g2");

                    addnav("$g3 Gold","inn.php?op=bartender&act=bribe&type=gold&amt=$g3");

                }else{

                  if ($_GET[type]=="gem"){

                        if ($session['user']['gems']<$_GET['amt']){

                            $try=false;

                            output("Du hast keine {$_GET['amt']} Edelsteine!");

                        }else{

                             $chance = $_GET['amt']/4;

                            $session['user']['gems']-=$_GET['amt'];

                              //debuglog("spent {$_GET['amt']} gems on bribing Cedrik");

                            $try=true;

                        }

                    }else{

                        if ($session['user']['gold']<$_GET['amt']){

                            output("Du hast keine {$_GET['amt']} Gold!");

                            $try=false;

                        }else{

                            $try=true;

                            $chance = $_GET['amt']/($session['user']['level']*110);

                            $session['user']['gold']-=$_GET['amt'];

                              //debuglog("spent {$_GET['amt']} gold bribing Cedrik");

                        }

                    }

                    $chance*=100;

                    if ($try){

                        if (e_rand(0,100)<$chance){

                            output("Cedrik lehnt sich zu dir Ã¼ber die Theke und fragt: \"`%Was kann ich fÃ¼r dich tun, Kleine".($session['user']['sex']?"":"r")."?`0\"");

                                if (getsetting("pvp",1)) {

                                addnav("Wer schlÃ¤ft oben?","inn.php?op=bartender&act=listupstairs");

                            }

                            addnav("Farbenlehre","inn.php?op=bartender&act=colors");

                            addnav("Spezialgebiet wechseln","inn.php?op=bartender&act=specialty");

                        }else{

                            output("Cedrik fÃ¤ngt an, die OberflÃ¤che der Theke zu wischen, was eigentlich schon vor langer Zeit wieder einmal nÃ¶tig gewesen wÃ¤re.  ");

                            output("Als er damit fertig ist, ".($_GET[type]=="gem"?($_GET['amt']>0?"sind deine Edelsteine":"ist dein Edelstein"):"ist dein Gold"));

                            output(" verschwunden. Du fragst wegen deinem Verlust nach, aber Cedrik starrt dich nur mit leerem Blick an.");

                        }

                    }else{

                        output("`n`nCedrik steht nur da und schaut dich ausdruckslos an.");

                    }

                }

            }else if ($_GET['act']=="ale"){

              output("Du schlÃ¤gst mit der Faust auf die Bar und verlangst ein Ale");

                if ($session['user']['drunkenness']>66){

                  //************************************************************************************************************************************

                    output(", aber Cedrik fÃ¤hrt unbekÃ¼mmert damit fort, das Glas weiter zu polieren, an dem er gerade arbeitet. \"`%Du hast genug gehabt ".($session['user']['sex']?"MÃ¤dl":"Bursche").".`0\" ");

                }else{

                  if ($session['user']['gold']>=$alecost){

                      $session['user']['drunkenness']+=33;

                        $session['user']['gold']-=$alecost;

                        if (getsetting("paidales",0)>1 && $session['user']['gotfreeale']<2) {

                            savesetting("paidales",getsetting("paidales",0)-1);

                            $session['user']['gotfreeale']+=2;

                        }

                        //debuglog("spent $alecost gold on ale");

                        output(".  Cedrik nimmt ein Glas und schenkt schÃ¤umendes Ale aus einem angezapften Fass hinter ihm ein.  ");

                        output("Er gibt dem Glas Schwung und es rutscht Ã¼ber die Theke, wo du es mit deinen Kriegerreflexen fÃ¤ngst.  ");

                        output("`n`nDu drehst dich um, trinkst dieses herzhafte GesÃ¶ff auf ex und gibst ".($session['user']['sex']?"Seth":"Violet"));

                        output(" ein LÃ¤cheln mit deinem Ale-Schaum-Oberlippenbart.`n`n");

                        switch(e_rand(1,3)){

                          case 1:

                            case 2:

                              output("`&Du fÃ¼hlst dich gesund!");

                                $session['user']['hitpoints']+=round($session['user']['maxhitpoints']*.1,0);

                                break;

                            case 3:

                              output("`&Du fÃ¼hlst dich lebhaft!");

                                $session['user']['turns']++;

                        }

                        if ($session['user']['drunkenness']>33) $session['user']['reputation']--;

                        $session['bufflist'][101] = array("name"=>"`#Rausch","rounds"=>10,"wearoff"=>"Dein Rausch verschwindet.","atkmod"=>1.25,"roundmsg"=>"Du hast einen ordentlichen Rausch am laufen.","activate"=>"offense");

                    }else{

                      output("Du hast aber nicht genug Geld bei dir. Wie kannst du ein Ale haben wollen, wenn du das Geld dafÃ¼r nicht hast!?!");

                    }

                }

            }else if ($_GET['act']=="listupstairs"){

                addnav("Liste aktualisieren","inn.php?op=bartender&act=listupstairs");

                output("Cedrik legt einen Satz SchlÃ¼ssel vor dich auf die Theke und sagt dir, welcher SchlÃ¼ssel wessen Zimmer Ã¶ffnet. Du hast die Wahl. Du kÃ¶nntest bei jedem reinschlÃ¼pfen und angreifen.");

                $pvptime = getsetting("pvptimeout",600);

                $pvptimeout = date("Y-m-d H:i:s",strtotime(date('c')."-$pvptime seconds"));

                pvpwarning();

    if ($session['user']['pvpflag']=="5013-10-06 00:42:00"){

        output("`n`&(Du hast PvP-ImmunitÃ¤t gekauft. Diese verfÃ¤llt, wenn du jetzt angreifst!)`0`n`n");

    }

                $days = getsetting("pvpimmunity", 5);

                $exp = getsetting("pvpminexp", 1500);

                $sql = "SELECT name,alive,location,sex,level,laston,loggedin,login,pvpflag FROM accounts WHERE 

                (locked=0) AND 

                (level >= ".($session['user']['level']-1)." AND level <= ".($session['user']['level']+2).") AND 

                (alive=1 AND location=1) AND 

                (age > $days OR dragonkills > 0 OR pk > 0 OR experience > $exp) AND

                (laston < '".date("Y-m-d H:i:s",strtotime(date('c')."-".getsetting("LOGINTIMEOUT",900)." sec"))."' OR loggedin=0) AND

                (acctid <> ".$session['user']['acctid'].") AND

                (dragonkills > ".($session['user']['dragonkills']-5).")

                ORDER BY level DESC";

                $result = db_query($sql) or die(db_error(LINK));

                output("<table border='0' cellpadding='3' cellspacing='0'><tr><td>Name</td><td>Level</td><td>Ops</td></tr>",true);

                for ($i=0;$i<db_num_rows($result);$i++){

                    $row = db_fetch_assoc($result);

                    $biolink = "bio.php?char=".rawurlencode($row['login'])."&ret=".urlencode($_SERVER['REQUEST_URI']);

                    addnav("", $biolink);

                    if($row[pvpflag]>$pvptimeout){

                        output("<tr class='".($i%2?"trlight":"trdark")."'><td>$row['name']</td><td>$row['level']</td><td>[ <a href='$biolink'>Bio</a> | `iimmun`i ]</td></tr>",true);

                    }else{

                        output("<tr class='".($i%2?"trlight":"trdark")."'><td>$row['name']</td><td>$row['level']</td><td>[ <a href='$biolink'>Bio</a> | <a href='pvp.php?act=attack&bg=1&name=".rawurlencode($row['login'])."'>Angriff</a> ]</td></tr>",true);

                        addnav("","pvp.php?act=attack&bg=1&name=".rawurlencode($row['login']));

                    }

                }

                output("</table>",true);

                $session['user']['reputation']--;

            }else if($_GET['act']=="colors"){

              output("Cedrik lehnt sich weiter Ã¼ber die Bar. \"`%Du willst also was Ã¼ber Farben wissen, hmm?`0\" ");

                output("  Du willst gerade antworten, als du feststellst, dass das eine rhetorische Frage war.  ");

                output("Cedrik fÃ¤hrt fort: \`%Um Farbe in deine Texte zu bringen, musst du folgendes tun: Zuerst machst du ein &#0096; Zeichen ",true);

                output(" (Shift und die Taste links neben Backspace), gefolgt von 1, 2, 3, 4, 5, 6, 7, 8, 9, !, @, #, $, %, ^, Q oder &. Jedes dieser Zeichen entspricht ");

                output("einer Farbe, die folgendermaÃŸen aussieht: `n`1&#0096;1 `2&#0096;2 `3&#0096;3 `4&#0096;4 `5&#0096;5 `6&#0096;6 `7&#0096;7 `8&#0096;8 `9&#0096;9  ",true);

                output("`n`!&#0096;! `@&#0096;@ `#&#0096;# `\$&#0096;\$ `%&#0096;% `^&#0096;^ `q&#0096;q `Q&#0096;Q `&&#0096;& `n",true);

                output("`T&#0096;T `t&#0096;t `R&#0096;R `r&#0096;r `V&#0096;V `v&#0096;v `g&#0096;g`n",true);

                output("`% kapiert?`0\"`n Hier kannst du testen:");

                output("<form action=\"$REQUEST_URI\" method='POST'>",true);

                output("Deine Eingabe: ".str_replace_c("`","&#0096;",HTMLEntities($_POST['testtext']))."`n",true);

                output("Sieht so aus: ".$_POST['testtext']." `n");

                output("<input name='testtext' id='input'><input type='submit' class='button' value='Testen'></form>",true);

                output("<script language='javascript'>document.getElementById('input').focus();</script>",true);

                output("`0`n`nDu kannst diese Farben in jedem Text verwenden, den du eingibst.");

                addnav("",$REQUEST_URI);

            }else if($_GET['act']=="specialty"){

                if ($_GET['specialty']==""){

                    output("\"`2Ich will mein Spezialgebiet wechseln`0\", verkÃ¼ndest du Cedrik.`n`n");

                    output("Ohne ein Wort packt Cedrik dich am Hemd, zieht dich Ã¼ber die Theke und zerrt dich hinter die FÃ¤sser hinter ihm. ");

                    output("Dann dreht er am Hahn eines kleinen FÃ¤sschens mit der Aufschrift \"Feines GesÃ¶ff XXX\"");

                    output("`n`nDu schaust dich um und erwartest, dass irgendwo eine GeheimtÃ¼r aufgeht, aber nichts passiert. Stattdessen ");

                    output("dreht Cedrik den Hahn wieder zurÃ¼ck und hebt einen frisch mit seinem vermutlich besten GebrÃ¤u gefÃ¼llten Krug. Das Zeug schÃ¤umt und ist von blau-grÃ¼nlicher Farbe.");

                    output("`n`n\"`3Was? Du hast einen geheimen Raum erwartet?`0\", fragt er dich. \"`3Also dann solltest du noch ");

                    output("besser aufpassen, wie laut du sagst, dass du deine FÃ¤higkeiten Ã¤ndern willst. Nicht jeder sieht ");

                    output("mit Wohlwollen auf diese Art von Dingen.`n`nWelches neue Spezialgebiet hast du dir denn gedacht?`0\"");

                    addnav("Dunkle KÃ¼nste",preg_replace("/[&?]c=[[:digit:]-]*/","",$REQUEST_URI)."&specialty=1");

                    addnav("Mystische KrÃ¤fte",preg_replace("/[&?]c=[[:digit:]-]*/","",$REQUEST_URI)."&specialty=2");

                    addnav("DiebeskÃ¼nste",preg_replace("/[&?]c=[[:digit:]-]*/","",$REQUEST_URI)."&specialty=3");

                

                }else{

                    output("\"`3Ok, du hast es.`0\"`n`n \"`2Das war schon alles?`0\", fragst du ihn.");

                    output("`n`n\"`nCedrik fÃ¤ngt laut an zu lachen: \"`3Jup. Was hasten erwartet? Irgendne Art fantastisches und geheimnisvolles Ritual??? ");

                    output("Du bist in Ordnung, Kleiner... spiel nur niemals Poker, ok?`0\"");

                    output("`n`n\"`Ach, nochwas. Obwohl du dein KÃ¶nnen in deiner alten Fertigkeit jetzt nicht mehr einsetzen kannst, hast du es immer noch. ");

                    output("Deine neue Fertigkeit wirst du trainieren mÃ¼ssen, um wirklich gut darin zu sein.`0\"");

                    //addnav("Return to the inn","inn.php");

                    $session['user']['specialty']=$_GET['specialty'];

                }

            }

            break;

        case "room":

            $config = unserialize($session['user']['donationconfig']);

          $expense = round(($session['user']['level']*(10+log($session['user']['level']))),0);

            if ($_GET[pay]){

                if ($_GET['coupon']==1){

                  $config['innstays']--;

                    debuglog("logged out in the inn");

                    $session['user']['donationconfig']=serialize($config);

                    $session['user']['loggedin']=0;

                    $session['user']['location']=1;

                    $session['user']['boughtroomtoday']=1;

                    saveuser();

                    $session=array();

                    redirect("index.php");

                }else{

                    if ($_GET[pay] == 2 || $session['user']['gold']>=$expense || $session['user']['boughtroomtoday']){

                        if ($session['user']['loggedin']){

                            if ($session['user']['boughtroomtoday']) {

                            }else{

                                if ($_GET[pay] == 2) {

                                    $fee = getsetting("innfee", "5%");

                                    if (strpos_c($fee, "%"))

                                        $expense += round($expense * $fee / 100,0);

                                    else

                                        $expense += $fee;

                                    $goldline = ",goldinbank=goldinbank-$expense";

                                } else {

                                    $goldline = ",gold=gold-$expense";

                                }

                                $goldline .= ",boughtroomtoday=1";

                            }

                              debuglog("spent $expense gold on an inn room");

                            $sql = "UPDATE accounts SET loggedin=0,location=1 $goldline WHERE acctid = ".$session['user']['acctid'];

                            db_query($sql) or die(sql_error($sql));

                        }

                        $session=array();

                        redirect("index.php");

                    }else{

                        output("\"Aah, so ist das also.\", sagt Cedrik und hÃ¤ngt den SchlÃ¼ssel, den er gerade geholt hat, wieder an seinen Haken hinter der Theke. ");

                        output("Vielleicht solltest du erstmal fÃ¼r das nÃ¶tige Kleingeld sorgen, bevor du dich am ");

                        output("Ã¶rtlichen Handel beteiligst.");

                    }

                }

            }else{

                if ($session['user']['boughtroomtoday']){

                    output("Du hast heute schon fÃ¼r ein Zimmer bezahlt.");

                    addnav("Gehe ins Zimmer","inn.php?op=room&pay=1");

                }else{

                    if ($config['innstays']>0){

                        addnav("Zeige ihm den Gutschein fÃ¼r ".$config['innstays']." Ãœbernachtungen","inn.php?op=room&pay=1&coupon=1");

                    }

                    output("Du trottest zum Barkeeper und fragst nach einem Zimmer. Er betrachtet dich und sagt: \"Das kostet `\$".$expense."`0 Gold fÃ¼r die Nacht.\"");

                    $fee = getsetting("innfee", "5%");

                    if (strpos_c($fee, "%"))

                        $bankexpense = $expense + round($expense * $fee / 100,0);

                    else

                        $bankexpense = $expense + $fee;

                    if ($session['user']['goldinbank'] >= $bankexpense && $bankexpense != $expense) {

                        output("Weil du so eine nette Person bist, bietet er dir zum Preis von `\$".$bankexpense."`0 Gold auch an, direkt von der Bank zu bezahlen. Der Preis beinhaltet " . (strpos_c($fee, "%") ? $fee : "$fee Gold") . " ÃœberweisungsgebÃ¼hr.");

                    }

                                

                    output("`n`nDu willst dich nicht von deinem Gold trennen und fÃ¤ngst an darÃ¼ber zu debattieren, dass man in den Feldern auch kostenlos "

                                ."schlafen kÃ¶nnte. SchlieÃŸlich siehst du aber ein, dass ein Zimmer in der Schenke vielleicht der sicherere Platz zum Schlafen ist, da es schwieriger fÃ¼r Herumstreicher sein dÃ¼rfte, "

                                ."in einen verschlossenen Raum einzudringen.");

                    addnav("Gib ihm $expense Gold","inn.php?op=room&pay=1");

                    if ($session['user']['goldinbank'] >= $bankexpense) {

                        addnav("Zahle $bankexpense Gold von der Bank","inn.php?op=room&pay=2");

                    }

                }

            }

            break;

    }

  addnav("ZurÃ¼ck zur Schenke","inn.php");

}

output("</span>",true);

page_footer();

?>

