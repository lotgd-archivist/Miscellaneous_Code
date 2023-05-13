
<?php

// 15082004

require_once 'common.php';
addcommentary();
$cost = $session['user']['level']*20;
$gems=array(1=>1,2,3);
$costs=array(1=>4000-3*getsetting("selledgems",0),7800-6*getsetting("selledgems",0),11400-9*getsetting("selledgems",0));
$scost=1200-getsetting("selledgems",0);

switch($_GET['op'])
{
    case 'killed':
    {
        page_header();
        addnews('`6Vessa`5 wurde beobachtet, wie sie die Leiche von `^'.$session['user']['name'].'`5 im Wald verscharrte.');
        output('`ZDu überreichst Vessa das verlangte Gold. Die Zigeunerin bittet dich, dass du dich auf einem Teppich hinlegst. Mit einem dir unbekannten Gesang versetzt sie dich langsam in Trance.
        `n`ZDu befindest dich nun im Reich der Toten, wandelst umher, auf der Suche nach dem wertvollen Kleinod.`n`n');

        if ($_GET['act']=='user')
        {
            output('`ZDas gefällt dem alten Besitzer natürlich gar nicht! Und bevor du dich versiehst, hast du eine Geisterhand an deiner Kehle, die unerbittlich zudrückt. Bevor dich Dunkelheit umgibt, hörst du von weitem noch `[Vessas`Z leises Fluchen, als sie deinen leblosen Körper entsorgt.');
        }
        elseif ($_GET['act']=='chance')
        {
            output('`ZDu willst gerade zurückkehren, als du bemerkst, dass plötzlich von überall her Hände nach dir greifen und dich festhalten. Du spürst, wie sich deine Seele von deinem Körper löst und bevor dich Dunkelheit umgibt, hörst du von weitem noch `[Vessas`Z leises Fluchen, als sie deinen leblosen Körper entsorgt.');
        }
        addnav('Du bist tot');
        killplayer(0,0,0,'shades.php','Och nee!');
        $session['bufflist']['headache'] = array('name'=>'`$Geisterhand`0','rounds'=>30,'wearoff'=>'`&Die Geisterhand lässt von dir ab.`0','atkmod'=>0.85,'defmod'=>0.85,'roundmsg'=>'Eine Geisterhand behindert dich.','activate'=>'defense');
        break;
    }
    case 'pay':
    {
        if ($session['user']['gold']>=$cost)
        { // Gunnar Kreitz
            $session['user']['gold']-=$cost;
            //debuglog('spent $cost gold to speak to the dead');
            if ($_GET['was']=='flirt')
            {
                 redirect('gypsy.php?op=flirt2');
            }
            else
            {
                redirect('gypsy.php?op=talk');
            }
        }
        else
        {
            page_header('Zigeunerzelt');
            addnav('Zurück zum Marktplatz','market.php');
            output('`ZDu bietest der alten Zigeunerin deine `$'.$session['user']['gold'].'`Z Gold für die Beschwörungssitzung. Sie informiert dich, dass die Toten zwar tot, aber deswegen trotzdem nicht billig sind.');
        }
        break;
    }
    case "talk":
    {
        page_header("`ZIn tiefer Trance sprichst du mit den Schatten");
        // by nTE- with modifications from anpera
        $sql="SELECT name FROM accounts WHERE locked=0 AND loggedin=1 AND alive=0 AND laston>'".date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("LOGINTIMEOUT",900)." seconds"))."' ORDER BY login ASC";
        $result=db_query($sql);
        $count=db_num_rows($result);
        $names=$count?'':'niemandem';
        for ($i=0;$i<$count;$i++)
        {
            $row=db_fetch_assoc($result);
            $names.='`^'.$row['name'];
            if ($i<$count-1) $names.='`^, ';
        }
        db_free_result($result);
        output('`ZDu fühlst die Anwesenheit von '.$names.'`Z.`n`n');
        output('`ZSolange du in tiefer Trance bist, kannst du mit den Toten sprechen:`n');
        viewcommentary('shade','Sprich zu den Toten',25,'spricht');
        addnav('Erwachen','market.php');
        break;
    }
    case "flirt2":
    {
        page_header('`ZIn tiefer Trance sprichst du mit den Schatten');
        output('`ZDie Zigeunerin versetzt dich in tiefe Trance.`n`Z Du findest '.($session['user']['sex']?'deinen Mann':'deine Frau').' im Land der Schatten und flirtest eine Weile mit '.($session['user']['sex']?'ihm, um sein':'ihr, um ihr').' Leid zu lindern. ');
        output('`n`4Du bekommst einen Charmepunkt.');
        $session['bufflist']['lover']=array('name'=>'`!Schutz der Liebe','rounds'=>60,'wearoff'=>'`!Du vermisst deine große Liebe!`0','defmod'=>1.2,'roundmsg'=>'Deine große Liebe lässt dich an deine Sicherheit denken!','activate'=>'defense');
        $session['user']['charm']++;
        $session['user']['seenlover']=1;
        addnav('Erwachen','market.php');
        break;
    }
    //Eier-Klau, die 2.
    case "egg":
    {
        page_header();
        $sql = 'SELECT acctid,name,loggedin,alive FROM accounts WHERE acctid = '.getsetting('hasegg',0);
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        $ecost=$session['user']['level']*100;
        if ($session['user']['gold']<$ecost)
        {
            output('`ZDein Griff ins Totenreich erwies sich sehr schnell als Griff ins Klo, nachdem die Zigeunerin bemerkt hatte, dass du dir dieses Unternehmen gar nicht leisten kannst. Mit hochrotem Kopf entfernst du dich rasch.`n');
            addnav('Nix wie weg','market.php');
        }
        else
        {
            $session['user']['gold']-=$ecost;
            output('`ZDu überreichst Vessa das verlangte Gold. Die Zigeunerin bittet dich, dass du dich auf einem Teppich hinlegst. Mit einem dir unbekannten Gesang versetzt sie dich langsam in Trance.
            `nDu befindest dich nun im Reich der Toten, wandelst umher, auf der Suche nach dem wertvollen Kleinod.`n`n');
            if ($row['alive'])
            {
                output('`ZZu deinem Ärger musst du jedoch feststellen, dass sich sowohl das `^goldene Ei`Z als auch sein Besitzer nicht mehr hier aufhalten. Da hat wohl jemand den Braten gerochen! Dir bleibt nichts weiter übrig, als wieder zurück zu kommen.`n');
                addnav('Zurück zum Marktplatz','market.php');
            }
            else
            {
                output ('`ZDann erblickst du es: Das `^goldene Ei`Z in strahlendem Glanz! Langsam pirschst du dich an '.$row['name'].' heran und schnappst dir das Ei.`n');
                if ($row['loggedin'])
                {
                    redirect('gypsy.php?op=killed&act=user');
                }
                else
                {
                    $dice=($session['user']['spirits'] == RP_RESURRECTION ? 5 : e_rand(1,5));
                    switch ($dice)
                    {
                        case 1 :
                            output('`ZMit mehr Glück als Verstand gelingt es dir tatsächlich, mit dem `^goldenen Ei`Z zu entkommen!`nOhne ein Wort des Dankes erhebst du dich schnell und flüchtest vor der Zigeunerin, die schon ganz gierig schaut.`n');
                            systemmail($row[acctid],'`$Diebstahl!`0','`$'.$session['user']['name'].'`$ hat dir im Totenreich das goldene Ei abgenommen!');
                            savesetting('hasegg',stripslashes($session['user']['acctid']));
                            item_set(' tpl_id=\'goldenegg\'', array('owner'=>$session['user']['acctid']) );
                            addnews("`^".$session['user']['name']."`^ stiehlt das goldene Ei aus dem Totenreich!");
                            addnav('Schnell weg','market.php');
                            break;
                        case 2 :
                        case 3 :
                        case 4 :
                        case 5 :
                            redirect('gypsy.php?op=killed&act=chance');
                        break;
                    }
                }
            }
        }
        break;
    }
    //Ausgeklaut
    case "buy":
    {
        page_header('Zigeunerzelt');
        $rowe = user_get_aei('gemsin'); //wenn man erst weniger als erlaubt kauft sind nochmal 3 möglich. ändern?
        if ($rowe['gemsin']>getsetting('transferreceive',3))
        {
            output('`ZDu hast heute schon genug Geschäfte gemacht. `[Vessa`Z hat keine Lust mehr, mit dir zu handeln. Warte bis morgen.');
        }
        else if ($session['user']['gems']>getsetting('selledgems',0))
        {
            output('`[Vessa`Z wirft einen neidischen Blick auf dein Säckchen Edelsteine und beschließt, dir nichts mehr zu geben, da du ohnehin mehr als sie hast.');
        }
        else if ($session['user']['gemsinbank']>99)
        {
            output('`ZWeil `[Vessa`Z dich öfter in die Bank gehen sieht, weiß sie auch, dass du dort nicht nur Gold bunkerst. Grün vor Neid beschließt sie, dir nichts mehr zu geben, da du ohnehin mehr als sie hast.');
        }
        else
        {
            if ($session['user']['gold']>=$costs[$_GET['level']])
            {
                if (getsetting("selledgems",0) >= $_GET['level'])
                {
                    output( '`[Vessa`Z grapscht sich deine `$'.($costs[$_GET['level']]).'`Z Goldstücke und gibt dir im Gegenzug `$'.($gems[$_GET['level']]).'`Z Edelstein'.($gems[$_GET['level']]>=2?'e':'').'.`n`n');
                    $session['user']['gold']-=$costs[$_GET['level']];
                    $session['user']['gems']+=$gems[$_GET['level']];
                    user_set_aei( array('gemsin'=>$rowe['gemsin']+$gems[$_GET['level']]) );
                    if (getsetting('selledgems',0) - $_GET['level'] < 1)
                    {
                        savesetting('selledgems','0');
                    }
                    else
                    {
                        savesetting('selledgems',getsetting('selledgems',0)-$_GET['level']);
                    }
                }
                else
                {
                    output('`[Vessa`Z teilt dir mit, dass sie nicht mehr so viele Edelsteine hat und bittet dich später noch einmal wiederzukommen.`n`n');
                }
            }
            else
            {
                output( '`[Vessa`Z zeigt dir nur ihre kalte Schulter, als du versuchst, ihr weniger zu zahlen, als ihre Edelsteine momentan Wert sind.`n`n');
            }
        }
        addnav('Zurück zum Marktplatz','market.php');
        break;
    }
    case "sell":
    {
        page_header('Zigeunerzelt');
        $rowe = user_get_aei('gemsout');
        $maxout = $session['user']['level']*getsetting('maxtransferout',25);
            if ($session['user']['gems']<1)
            {
                output('`[Vessa`Z haut mit der Faust auf den Tisch und fragt dich, ob du sie veralbern willst. Du hast keinen Edelstein.`n`n');
        }
        else if ($rowe['gemsout']>getsetting('transferreceive',3))
        {
            output('`ZDu hast heute schon genug Geschäfte gemacht. `[Vessa`Z hat keine Lust mehr, mit dir zu handeln. Warte bis morgen.');
            }
            else
            {
                output('`ZVessa nimmt deinen Edelstein und gibt dir dafür `$'.$scost.' `ZGoldstücke.`n`n');
                $session['user']['gold']+=$scost;
                $session['user']['gems']-=1;
                savesetting('selledgems',getsetting('selledgems',0)+1);
                user_set_aei( array('gemsout'=>$rowe['gemsout']+1) );
            }
        addnav('Zigeunerzelt','gypsy.php');
        addnav('M?Zurück zum Marktplatz','market.php');
        break;
    }
    default:
    {
        checkday();
        page_header('Zigeunerzelt');
                 output('`c`b`zDas Zigeunerzelt`b`c`n');
        $ecost=$cost*5;
        output('`ZAuf einer kleineren Wiese hinter den anderen Geschäften und Ständen des Marktplatzes hat Vessa, die Zigeunerin, ihr aus vielen bunten Stofffetzen bestehendes Zelt aufgeschlagen.
        Sollte man sich von den exotischen Düften anlocken lassen, die aus dem Inneren des Zigeunerzeltes strömen, wird man sogleich von wachsamen Augen ausführlich gemustert und die Worte
        `["Ich habe euch bereits erwartet, '.$session['user']['name'].'" `Zempfangen jeden Besucher. Graues Haar, gemischt mit wenigen schwarzen Strähnen, die unter einer haubenähnlichen Kopfbedeckung hervor ranken
        und das leicht faltige Gesicht der Zigeunerin umranden, geben Vessa einen fast mystischen Eindruck, ebenso wie das hochgeschlossene, sehr bunt gehaltene Kleid, das die Alte trägt.
        Abgerundet wird das sich den Besuchern bietende Bild durch eine Kristallkugel auf einem kleinen Tisch vor der Zigeunerin, die im Inneren fast rauchig wirkt.`n`n
        Gegen einen kleinen Aufpreis wird Vessa für dich einen Blick in die Kristallkugel werfen, um dir deine Zukunft zu verkünden
        Außerdem ist es im Dorf allgemein bekannt, dass dir Vessa für `$'.$cost.' Gold`Z ein Gespräch mit den Verstorbenen gestattet. Mit ihrer tiefen, rauchigen Stimme erklärt sie dir, dass du für `$'.$ecost.' Gold `Zversuchen kannst, dass goldene Ei aus dem Totenreich zu stehlen, wenn es sich dort befindet.
                 Zugleich deuten einige Säckchen, aus denen das Licht der Kerzen blitzend zurückgeworfen wird, darauf hin, dass Vessa auch mit Edelsteinen handelt. Momentan bewahrt sie `$'.getsetting('selledgems',0).' wertvolle Steine `Zin jenen Säckchen.');
        if (getsetting('selledgems',0)>=getsetting('gypsy_maxselledgems',100))
        {
            output(' Sie scheint aber kein Interesse an weiteren Edelsteinen zu haben. Oder sie hat einfach kein Gold mehr, um weitere Edelsteine zu kaufen.');
        }
        addnav('Bezahle und rede mit den Toten','gypsy.php?op=pay');

        //Goldenes Ei aus dem Totenreich klauen
        if (getsetting('hasegg',0)>0){
            $sql = 'SELECT name,loggedin,alive FROM accounts WHERE acctid = '.getsetting("hasegg",0);
            $result = db_query($sql);
            $row = db_fetch_assoc($result);
            if (!$row['alive'])
            {
                addnav('E?Versuche das goldene Ei aus dem Totenreich zu stehlen','gypsy.php?op=egg');
            }
        }
        //Klau-Ende

        if ($session['user']['charisma']==4294967295 && $session['user']['seenlover']<1)
        {
            $sql = "SELECT name,alive FROM accounts WHERE ".$session['user']['marriedto']." = acctid ORDER BY charm DESC";
            $result = db_query($sql);
            $row = db_fetch_assoc($result);
            if ($row['alive']==0) addnav('f?Bezahle und flirte mit '.$row['name'],'gypsy.php?op=pay&was=flirt');
        }
        addnav('Wahrsagen lassen','nerwen.php');
        //addnav("Tarotkarten legen (1 Edelstein)","tarot.php");
        if ($access_control->su_check(access_control::SU_RIGHT_COMMENT)) 
        {
            addnav('Superusereintrag','gypsy.php?op=talk');
        }
        addnav('Edelsteine');
        if ($session['user']['level']<15)
        {
            addnav('1?Kaufe 1 Edelstein ('.$costs[1].' Gold)','gypsy.php?op=buy&level=1');
            addnav('2?Kaufe 2 Edelsteine ('.$costs[2].' Gold)','gypsy.php?op=buy&level=2');
            addnav('3?Kaufe 3 Edelsteine ('.$costs[3].' Gold)','gypsy.php?op=buy&level=3');
        }
        if (getsetting('selledgems',100)<getsetting('gypsy_maxselledgems',100) && $session['user']['level']>1) addnav('Verkaufe 1 Edelstein für '.$scost.' Gold','gypsy.php?op=sell');
        addnav('Zurück');
        addnav('Zurück zum Marktplatz','market.php');
    }
}

page_footer();
?>

