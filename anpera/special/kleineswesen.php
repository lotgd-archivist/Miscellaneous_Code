
ï»¿<?php



// 21122004



// Das kleine Wesen im Wald, Version 1.16

//

// Was ist das bloÃŸ fÃ¼r ein nerviges GerÃ¤usch ...

//

// Erdacht und umgesetzt von Oliver Wellinghoff.

// E-Mail: wellinghoff@gmx.de

// Erstmals erschienen auf: http://www.green-dragon.info

//

// Modifiziert von anpera

//

// Vorbereitungen:

//

// *** *** ***

//

/*

 ALTER TABLE accounts ADD kleineswesen INT(4) NOT NULL DEFAULT '0';

*/

// Suche in newday.php die Zeile:

// $config = unserialize($session['user']['donationconfig']);

// 

// FÃ¼ge davor ein:

/*



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



*/

// Suche in dragon.php die Zeile: 

// //Handle custom titles

// if ($session[user][ctitle] == "") {

//

// FÃ¼ge davor ein:

/*



 if($session['user']['login']==getsetting("kleineswesen","Violet")) savesetting("kleineswesen","Warchild");



*/

// Suche in configuration.php die Zeile:

//"weather"=>"Heutiges Wetter:,viewonly",

//

// FÃ¼ge danach ein:

/*



"kleineswesen"=>"Kleines Wesen (default Violet):",



*/



// Suche in user.php die Zeile:

//"drunkenness"=>"Betrunken (0-100),int",

//

// FÃ¼ge danach ein:

/*



"kleineswesen"=>"WKs durch kleines Wesen,int",



*/

// *** *** ***

//

//  - Version vom 21.12.2004 -



if (!isset($session)) exit();

$session['user']['specialinc'] = "kleineswesen.php";

$auswahl=array(1=>"Violet",2=>"Seth",3=>"Dag Durnick",4=>"Cedrik",5=>"WanderhÃ¤ndler Aeki",6=>"Pegasus",7=>"MightyE",8=>"Merick"); // do not change!

switch($HTTP_GET_VARS[op]){



    case "mitnehmen":

    output("`#'Ein Kinderspiel!'`@ denkst Du Dir. Aber weit gefehlt! Das kleine Wesen erweist sich als schnell und wendig. Du musst Dein ganzes Geschick aufbringen, um ihm bei seinen rasanten Haken zu folgen. GebÃ¼ckt eilst Du von einem Busch zum nÃ¤chsten - und von einem Baum zum anderen. `n`nDein Ehrgeiz ist geweckt!");

    output("`@`n`n<a href='forest.php?op=mitnehmen2'>Weiter.</a>", true);

    addnav("","forest.php?op=mitnehmen2");

    addnav("Weiter.","forest.php?op=mitnehmen2");

    break;



    case "mitnehmen2":

    switch(e_rand(1,10)){ 

        case 1: 

        case 2: 

        case 3:

        case 4:

        $verkleinert=getsetting("kleineswesen","Violet");

        output("`@Minuten werden Stunden, Meter werden zu Kilometern ... In Deiner Euphorie ist Dir nicht aufgefallen, dass Du immer kleiner geworden bist - und das kleine Wesen immer grÃ¶ÃŸer! `n`#$verkleinert`@ steht nun Ã¼ber Dir und lacht. `n`n`#'Tja, was soll ich sagen? Wage es ja nicht, mir zu folgen und mich mit Deinen Hilfeschreien zu belÃ¤stigen!'`n`n`@Das Lachen geht Dir nicht mehr aus dem Kopf ..."); 

                    output("`n`@Jetzt bist Du allein.`nIm Wald.`nKlein.`nAber niedlich!`nMm, darÃ¼ber musst Du erst mal in Ruhe nachdenken ...");

        output("`n`n`@Weil Du so niedlich geworden bist, erhÃ¤ltst Du `^1`@ Charmepunkt!");

        if ($session['user']['turns']>=4){

                           output("`n`nDu bekommst `^".round($session['user']['experience']*0.08)."`@ Erfahrungspunkte hinzu, verlierst aber `\$4`@ WaldkÃ¤mpfe!");

                        $session['user']['experience']*=1.08;

        }else{

            output("`n`nDu bekommst `^".round($session['user']['experience']*0.05)."`@ Erfahrungspunkte hinzu, verlierst aber `\$alle`@ Ã¼brigen WaldkÃ¤mpfe!");

            $session['user']['experience']*=1.05;

        }

        $session['user']['turns']-=4;

        if ($session['user']['turns']<0) $session['user']['turns']=0;

        $session['user']['charm']++;

        //addnav("TÃ¤gliche News","news.php");

        addnav("ZurÃ¼ck in den Wald","forest.php");

        addnews("`qVon nun an muss sich `b".$session['user']['name']."`q `bim Wald vor KÃ¤fern in Acht nehmen!");

        savesetting("kleineswesen",$session['user']['login']);

                    break;

                    case 5:

                    case 6:

                    case 7:

                    case 8:

        $verkleinert=getsetting("kleineswesen","Violet");

                    output("`@Du jagst und jagst ... ein GrÃ¼ner Drache ist nichts dagegen! Endlich bekommst Du das Wesen zu fassen. In dem Moment, in dem Du es berÃ¼hrst, wirst Du zurÃ¼ckgeschleudert.`n`n Aus einer verpuffenden roten Wolke geht `#$verkleinert`@ hervor!"); 

                   $gems = e_rand(2,3); 

                   output("`n`n`@Als Du die verlorengeglaubte Seele bis zum Dorfrand geleitet hast, bekommst Du fÃ¼r Deine ehrvolle Tat eine Belohnung in HÃ¶he von `^$gems`@ Edelsteinen!");

                    $session['user']['gems']+=$gems;

           $session['user']['reputation']+=2;

        output("`n`nDu bekommst `^".round($session['user']['experience']*0.02)."`@ Erfahrungspunkte hinzu und verlierst einen Waldkampf!");

        $session['user']['experience']*=1.02;

                  $session['user']['turns']--; 

        //addnav("TÃ¤gliche News","news.php");

        addnav("ZurÃ¼ck in den Wald","forest.php");

                    addnews("`@`b".$session['user']['name']."`b `@kehrte mit der verlorengeglaubten Seele `#$verkleinert`@ aus dem Wald zurÃ¼ck!");

        if ($verkleinert=="Violet"

        || $verkleinert=="Dag Durnick"

        || $verkleinert=="Seth"

        || $verkleinert=="WanderhÃ¤ndler Aeki"

        || $verkleinert=="Pegasus"

        || $verkleinert=="MightyE"

        || $verkleinert=="Merick"

        || $verkleinert=="Warchild"

        || $verkleinert=="Cedrik"){

        }else{

            $sql="SELECT acctid, kleineswesen FROM accounts WHERE login='$verkleinert' LIMIT 1";

            $result = db_query($sql) or die(db_error(LINK));

            $rowgerettet = db_fetch_assoc($result);

            $schÃ¶nertraum = e_rand(2,4);

            $sql = "UPDATE accounts SET kleineswesen=$schÃ¶nertraum WHERE acctid=".$rowgerettet['acctid'];

            db_query($sql);

            $mailmessage1 = "`@Als Du heute erwachst, fÃ¼hlst Du Dich Ã¤uÃŸerst erholt. In Deinem Traum warst Du ein kleines Wesen, kaum einen Fingernagel hoch und riefst verzweifelt um Hilfe. Niemand, dem Du im Wald begegnetest reagierte auf Dich ...`n Doch dann - endlich! - blieb jemand stehen. Es war `^".$session['user']['name']."`@! Stundenlang versuchte ".($session['user']['sex']?"sie":"er").", Dich zu berÃ¼hren, doch Du konntest nicht anders - irgendetwas zwang Dich, immer wieder wegzulaufen. Aber `^".$session['user']['name']."`@ lieÃŸ sich nicht beirren, berÃ¼hrte Dich schlieÃŸlich und errettete Dich damit. Als wenn das noch nicht genug gewesen wÃ¤re, geleitete ".($session['user']['sex']?"sie":"er")." Dich sogar noch bis zum Dorf zurÃ¼ck. `n`nWenn das kein Traum gewesen wÃ¤re, mÃ¼sstest Du ".($session['user']['sex']?"ihr":"ihm")." nun Ã¤uÃŸerst dankbar sein. Es war doch nur ein Traum, oder?`n`nWeil Du besonders gut geschlafen hast, wirst Du `^".$schÃ¶nertraum."`@ zusÃ¤tzliche WaldkÃ¤mpfe erhalten!`n`n";

            systemmail($rowgerettet['acctid'],"`@Du hattest einen fantastischen Traum!",$mailmessage1);

            $nr = e_rand(1,8);

            savesetting("kleineswesen",$auswahl[$nr]);

        }

                break;            

                case 9:

        output("`@Du jagst und jagst ... ein GrÃ¼ner Drache ist nichts dagegen! Es wird immer spÃ¤ter ... aber Dein Ehrgeiz ist - einmal entfacht - nicht aufzuhalten. Viele Stunden sind vergangen, als Deine kÃ¶rperlichen KrÃ¤fte Dich verlassen. Du sinkst erschÃ¶pft zu Boden - und siehst das kleine Wesen auf dem Baumstumpf vor Dir stehen. Es zu greifen wÃ¤re nun ein Kinderspiel, aber dazu reicht Deine Kraft nicht mehr aus ... Zum ersten Mal hÃ¶rst Du genau hin, was es Dir zu sagen hat:"); 

                output("`#'Du hast Dich eifrig bemÃ¼ht, mich zu berÃ¼hren - wer mich berÃ¼hrt, macht mich frei! Leider darf ich es Dir nicht zu einfach machen ... Aber dafÃ¼r, dass Du es versucht hast, mÃ¶chte ich Dir etwas zeigen.'");

        output("`n`@Das kleine Wesen hÃ¼pft dreimal im Kreis auf dem Baumstumpf herum, woraufhin dieser in einer roten Wolke verpufft. Es bleibt keine Spur von dem seltsamen Wesen - dafÃ¼r lÃ¤sst es aber ein kleines SÃ¤ckchen zurÃ¼ck!");

        $gold =  e_rand(200,700) * e_rand(3,7);

        output("`n`n`@In dem SÃ¤ckchen befinden sich `^".$gold."`@ GoldstÃ¼cke!");

        $turns = e_rand(1,2);

        output("`n`n`^Du verlierst ".$turns." WaldkÃ¤mpfe!");

        $session['user']['gold']+=$gold;

        $session['user']['turns']-=$turns;

        if ($session['user']['turns']<0) $session['user']['turns']=0;

        break;

        case 10:

        output("`@Du jagst und jagst ... ein GrÃ¼ner Drache ist nichts dagegen! Es wird immer spÃ¤ter ... aber Dein Ehrgeiz ist - einmal entfacht - nicht aufzuhalten. Viele Stunden sind vergangen, als Deine kÃ¶rperlichen KrÃ¤fte Dich verlassen. Du sinkst erschÃ¶pft zu Boden - und siehst das kleine Wesen auf dem Baumstumpf vor Dir stehen. Es zu greifen wÃ¤re nun ein Kinderspiel, aber dazu reicht Deine Kraft nicht mehr aus ... Zum ersten Mal hÃ¶rst Du genau hin, was es Dir zu sagen hat:"); 

        output("`#'Du hast Dich eifrig bemÃ¼ht, mich zu berÃ¼hren - wer mich berÃ¼hrt, macht mich frei! Leider darf ich es Dir nicht zu einfach machen ... Na ja. Damit Du beim nÃ¤chsten Mal etwas wendiger bist, nimm diese Hilfe!'");

        output("`n`@Das kleine Wesen hÃ¼pft dreimal im Kreis auf dem Baumstumpf herum, woraufhin dieser in einer roten Wolke verpufft. Es bleibt keine Spur von dem seltsamen Wesen - aber Du fÃ¼hlst Dich frischer als je zuvor!");

        $turns = (e_rand(1,3));

        $leben = (e_rand(1,2));

        output("`n`n`@Du bekommst `^".$leben."`@ permanente(n) Lebenspunkt(e)!");

        output("`n`n`^Du verlierst ".$turns." WaldkÃ¤mpfe!");

        $session['user']['turns']-=$turns;

               if ($session['user']['turns']<0) $session['user']['turns']=0;

        $session['user']['maxhitpoints']+=$leben;

        $session['user']['hitpoints']+=$leben;

        break;

    }

    $session[user][specialinc]="";

    break;

    

    case "zertreten":

        output("`@Schweren Herzens hebst Du Deinen FuÃŸ, schaust hinauf zu den Baumwipfeln und`n- trittst mit einem krÃ¤ftigen Ruck zu!");

        output("`@`n`n<a href='forest.php?op=zertreten2'>Weiter.</a>", true);

        addnav("","forest.php?op=zertreten2");

        addnav("Weiter","forest.php?op=zertreten2");

        break;

        

    case "zertreten2":

    switch(e_rand(1,10)){ 

                case 1: 

                output("`@Als Du den FuÃŸ wieder hebst, stellst Du mit Erstaunen fest, dass das kleine Wesen verschwunden ist. Offenbar hat er es mit der Angst bekommen und ist geflohen. Dir fÃ¤llt ein Stein vom Herzen - so ist es fÃ¼r alle Beteiligten besser. ErfÃ¼llt von neuer Frische setzt Du Deinen Weg fort."); 

                output("`n`n`^Du erhÃ¤ltst einen zusÃ¤tzlichen Waldkampf!");

                $session['user']['turns']+=1; 

        $session[user][reputation]-=2;

        break;

                case 2:

                case 3:

                case 4:

                case 5: 

                case 6:

                case 7:

                output("`@Als Du den FuÃŸ wieder hebst, stellst Du angewidert fest, dass Du ganze Arbeit geleistet hast: von dem kleinen Wesen ist nur noch Matsch Ã¼brig geblieben. War das wirklich nÃ¶tig? Na ja, immerhin hat das Piepen aufgehÃ¶rt. Aber Du brauchst eine Weile, um diesen Vorfall zu vergessen."); 

                output("`n`n`^Du verlierst einen Waldkampf!");

                $session['user']['reputation']-=5;

                $session['user']['turns']-=1; 

        //addnav("TÃ¤gliche News","news.php");

        $verkleinert=getsetting("kleineswesen","Violet");

        addnews("`b`q".$session['user']['name']."`b `qhat die Hilfeschreie von `b$verkleinert`b `qleider `bvÃ¶llig`b missverstanden ...");

        if ($verkleinert=="Violet"

        || $verkleinert=="Dag Durnick"

        || $verkleinert=="Seth"

        || $verkleinert=="WanderhÃ¤ndler Aeki"

        || $verkleinert=="Pegasus"

        || $verkleinert=="MightyE"

        || $verkleinert=="Merick"

        || $verkleinert=="Warchild"

        || $verkleinert=="Cedrik"){

        }else{

            $sql="SELECT acctid, kleineswesen FROM accounts WHERE login='$verkleinert' LIMIT 1";

            $result = db_query($sql) or die(db_error(LINK));

            $rowdead = db_fetch_assoc($result);

            $alptraum = e_rand(-3,-5);

            $sql = "UPDATE accounts SET kleineswesen=$alptraum WHERE acctid=".$rowdead['acctid'];

            db_query($sql);

            $mailmessage1 = "`@Heute nach wachst Du schweiÃŸgebadet auf. In Deinem Traum warst Du ein kleines Wesen, kaum einen Fingernagel hoch und riefst verzweifelt um Hilfe. Niemand, dem Du im Wald begegnetest reagierte auf Dich ...`n Doch dann - endlich! - blieb jemand stehen. Es war `^".$session['user']['name']."`@! Aber ".($session['user']['sex']?"sie":"er")." blieb nicht stehen, um Dir zu helfen ...`n`n Es graut Dir noch immer bei der Erinnerung daran, wie es sich anfÃ¼hlte, als ".($session['user']['sex']?"ihr":"sein")." FuÃŸ niederraste und Dich zermatschte. Aber zum GlÃ¼ck war das alles ja nur ein Traum ... war es doch, oder?`n`nWeil Du schlecht geschlafen hast, wirst Du `\$".(abs($alptraum))."`@ WaldkÃ¤mpfe einbÃ¼ÃŸen!`n`n";

            systemmail($rowdead[acctid],"`\$Du hattest einen schrecklichen Alptraum!",$mailmessage1);

            $nr = e_rand(1,8);

            savesetting("kleineswesen",$auswahl[$nr]);

        }

        break;            

                case 8:

        case 9: 

                case 10:

                output("`@Erschrocken stellst Du fest, dass Dein Tritt kurz vor dem Boden gestoppt wurde. Von diesem kleinen Wesen?! - Zumindest ist es nicht so klein, als dass es Dich nicht gegen einen Baum schleudern kÃ¶nnte! Du rappelst Dich auf und rennst schreiend davon."); 

        output("`n`n`^Du verlierst die meisten Deiner Lebenspunkte!");

        output("`n`^Du verlierst einen Waldkampf!");

        $session['user']['reputation']-=5;

        $session['user']['hitpoints']=1;

        $session['user']['turns']--; 

        //addnav("TÃ¤gliche News","news.php");

        addnews("`q`b".$session[user][name]."`b `qwurde im Wald von einem DÃ¤umling erniedrigt.");

        break;

    }

    $session[user][specialinc]="";

    addnav("ZurÃ¼ck in den Wald","forest.php");

    break;



    case "ruhe":

    switch(e_rand(1,10)){ 

        case 1: 

        case 2: 

        case 3:

        case 4:

        case 5:

        output("`@Du reiÃŸt Dich zusammen und musst das Piepen noch etliche Stunden ertragen. Aber letzten Endes war es wirklich nicht so schlimm."); 

        //output("`n`n`^Du erhÃ¤ltst einen zusÃ¤tzlichen Waldkampf!");

                    //$session['user']['turns']++;

        break;

        case 6: 

        case 7: 

        case 8: 

        output("`@Du reiÃŸt Dich zusammen und musst das Piepen noch etliche Stunden ertragen. Letzten Endes war es aber wirklich nicht so schlimm.`n`n`^Du bÃ¼ÃŸt nur einen einzigen Waldkampf ein!"); 

        $session['user']['turns']--; 

        break;        

        case 9: 

        case 10:

        output("`@Du reiÃŸt Dich zusammen und musst das Piepen noch etliche Stunden ertragen. Arrrrrrgh! Wenn es doch bloÃŸ aufhÃ¶rte! Es bringt Dich beinahe um den Verstand.`n`n`^Weil Du Dich nicht konzentrieren kannst, bÃ¼ÃŸt Du gleich zwei WaldkÃ¤mpfe ein!"); 

        $session['user']['turns']-=2; 

        if ($session['user']['turns']<0) $session['user']['turns']=0;

        break;

    }

    $session[user][specialinc]="";

    break;



    default:

    $verkleinert = getsetting("kleineswesen","Violet");

    if ($session['user']['login'] == $verkleinert){

        output("`@Ist das nicht ... doch, das ist der Ort, an dem Du neulich verkleinert wurdest! Du erschauderst und gehst mit schnellen Schritten weiter.`n`n Wobei, jetzt bist Du ja gar nicht mehr klein ... war das alles womÃ¶glich nur eine Illusion? Ein Traum? Wer weiÃŸ ...`n`nAuf jeden Fall mÃ¶chtest Du hier nicht lÃ¤nger verweilen.");

        $session['user']['specialinc'] = "";

    }else{

        output("`@Du ziehst durch den Wald und schwelgst in der selbstbewussten Gewissheit zukÃ¼nftiger Heldentaten. In Gedanken schon fast beim GrÃ¼nen Drachen angelangt, bleibst Du plÃ¶tzlich genervt stehen. Dieses Piepen! Wie von einer Maus! Schon seit geraumer Zeit verfolgt es Dich ... Also jetzt reicht's aber!");

        output("`n`@Du bÃ¼ckst Dich, um den Boden abzusuchen. Das Piepen verstummt fÃ¼r einen Moment - woher kommt es? Dann wird es lauter und hektischer als je zuvor. Dort, zwischen den BlÃ¤ttern: ein niedliches, kleines Wesen, kaum einen Fingernagel hoch, das Dir seltsam bekannt vorkommt. Dem Aussehen nach kÃ¶nnte es `#$verkleinert`@ sein ... Aber ist das denn mÃ¶glich?!`n`nWas wirst Du jetzt tun?");

        output("`n`n`@<a href='forest.php?op=mitnehmen'>Wie sÃ¼ÃŸ! Ich nehme es mit.</a>", true);

        output("`@`n`n<a href='forest.php?op=zertreten'>Jetzt reicht's! Ich zertrete es.</a>", true);

        output("`@`n`n<a href='forest.php?op=ruhe'>Ich lasse es in Ruhe - so schlimm ist sein Piepen nun auch wieder nicht.</a>", true);

        addnav("","forest.php?op=mitnehmen");

        addnav("","forest.php?op=zertreten");

        addnav("","forest.php?op=ruhe");

        addnav("Mitnehmen","forest.php?op=mitnehmen");

        addnav("Zertreten","forest.php?op=zertreten");

        addnav("In Ruhe lassen","forest.php?op=ruhe");

        $session['user']['specialinc'] = "kleineswesen.php";

    }

    break;

}

?>

