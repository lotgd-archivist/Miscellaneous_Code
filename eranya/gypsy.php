
<?php

// 1508004

require_once "common.php";
addcommentary();

if ($_GET['op']=="killed")
{   page_header();
    addnews("`5Scytha`5 wurde beobachtet, wie sie die Leiche von `^".$session['user']['name']."`5 im Wald verscharrte.");
    output("`5Du bezahlst und Scytha versetzt dich in Trance.`n");
    output("`5Du befindest dich nun im Reich der Toten, wandelst umher, auf der Suche nach dem wertvollen Kleinod.`n`n");

if ($_GET['act']=="user") {
output("Das gefällt dem alten Besitzer natürlich gar nicht! Und bevor du dich versiehst hast du eine Geisterhand an deiner Kehle, die unerbittlich zudrückt. Bevor dich Dunkelheit umgibt hörst du `5Scytha`5 leise fluchen, als sie deinen leblosen Körper entsorgt."); }
if ($_GET['act']=="chance") {
output("Du willst gerade zurückkehren, als du bemerkst, dass plötzlich von überall her Hände nach dir greifen und dich festhalten. Du spürst wie sich deine Seele von deinem Körper körper löst und bevor dich Dunkelheit umgibt hörst du `5Scytha`5 leise fluchen, als sie deinen leblosen Körper entsorgt."); }
    $session['user']['alive']=false;
    $session['user']['hitpoints']=0;
    addnav("Du bist tot");
    addnav("Och nee!","shades.php");
} else {

$cost = $session['user']['level']*20;
$gems=array(1=>1,2,3);
$costs=array(1=>4000-3*getsetting("selledgems",0),7800-6*getsetting("selledgems",0),11400-9*getsetting("selledgems",0));
$scost=1200-getsetting("selledgems",0);
if ($_GET['op']=="pay"){
        if ($session['user']['gold']>=$cost)
        { // Gunnar Kreitz
                $session['user']['gold']-=$cost;
                //debuglog("spent $cost gold to speak to the dead");
                if ($_GET['was']=="flirt")
                {
                         redirect("gypsy.php?op=flirt2");
                } 
                else 
                {
                        redirect("gypsy.php?op=talk");
                }
        }
        else
        {
                page_header("Scythas Zelt");
                addnav("Zurück zum Marktplatz","market.php");
                output("`5Du bietest der Händlerin deine `^{$session['user']['gold']}`5 Gold für die Beschwörungssitzung. Sie informiert dich, dass die Toten zwar tot, aber deswegen trotzdem nicht billig sind.");
        }
}
else if ($_GET['op']=="talk")
{
        page_header("In tiefer Trance sprichst du mit den Schatten");
        // by nTE- with modifications from anpera
        $sql="SELECT name FROM accounts WHERE locked=0 AND loggedin=1 AND alive=0 AND laston>'".date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("LOGINTIMEOUT",900)." seconds"))."' ORDER BY login ASC"; 
        $result=db_query($sql) or die(sql_error($sql));
        $count=db_num_rows($result);
        $names=$count?"":"niemandem";
        for ($i=0;$i<$count;$i++)
        { 
                $row=db_fetch_assoc($result); 
                $names.="`^".$row['name'];
                if (($i+1)<$count) $names.=", "; 
        } 
        db_free_result($result); 
        output("`5Du fühlst die Anwesenheit von {$names}`5.`n`n");
        output("`5Solange du in tiefer Trance bist, kannst du mit den Toten sprechen:`n");
        viewcommentary("shade","Sprich zu den Toten",25,"spricht");
        addnav("Erwachen","market.php");
} 
else if ($_GET['op']=="flirt2")
{ 
        page_header("In tiefer Trance sprichst du mit den Schatten");
        output("`5Die Händlerin versetzt dich in tiefe Trance.`n`% Du findest ".($session['user']['sex']?"deinen Mann":"deine Frau")." im Land der Schatten und flirtest eine Weile mit ".($session['user']['sex']?"ihm, um sein":"ihr, um ihr")." Leid zu lindern. ");
        output("`n`^Du bekommst einen Charmepunkt.");
        $session['bufflist']['lover']=array("name"=>"`!Schutz der Liebe","rounds"=>60,"wearoff"=>"`!Du vermisst deine große Liebe!`0","defmod"=>1.2,"roundmsg"=>"Deine große Liebe lässt dich an deine Sicherheit denken!","activate"=>"defense");
        $session['user']['charm']++;
                        
        $session['user']['seenlover']=1;
        addnav("Erwachen","market.php");
}
//Eier-Klau, die 2.
else if($_GET['op']=="egg")
{
page_header();
$sql = "SELECT acctid,name,loggedin,alive FROM accounts WHERE acctid = ".getsetting("hasegg",0);
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        
$ecost=$session['user']['level']*100;
if ($session['user']['gold']<$ecost) {
output("`5Dein Griff ins Totenreich erwies sich sehr schnell als Griff ins Klo, nachdem Scytha bemerkt hatte, dass du dir dieses Unternehmen gar nicht leisten kannst. Mit hochrotem Kopf entfernst du dich rasch.`n");
addnav("Nix wie weg","market.php");
   } else {
   $session['user']['gold']-=$ecost;
   output("`5Du bezahlst und Scytha versetzt dich in Trance.`n");
   output("`5Du befindest dich nun im Reich der Toten, wandelst umher, auf der Suche nach dem wertvollen Kleinod.`n`n");
   if ($row['alive']) {
   output("`5Zu deinem Ärger musst du jedoch feststellen, dass sich sowohl das `^goldenen Ei`5 als auch sein Besitzer nicht mehr hier aufhalten. Da hat wohl jemand den Braten gerochen! Dir bleibt nichts weiter übrig, als wieder zurück zu kommen.`n");
   addnav("Zurück zum Marktplatz","market.php");
   } else {
   output ("Dann erblickst du es: Das `^goldene Ei`5 in strahlendem Glanz! Langsam pirschst du dich an ".$row['name']." heran und schnappst dir das Ei.`n");
   if ($row['loggedin']) {
   redirect('gypsy.php?op=killed&act=user');
   } else {
      switch (e_rand(1,5)){

case 1 :
output("Mit mehr Glück als Verstand gelingt es dir tatsächlich, mit dem `^goldenen Ei`5 zu entkommen!`n");
output("Ohne ein Wort des Dankes erhebst du dich schnell und flüchtest vor Scytha, die schon ganz gierig schaut.`n");

systemmail($row['acctid'],"`\$Diebstahl!`0","`\${$session['user']['name']}`\$ hat dir im Totenreich das goldene Ei abgenommen!");
savesetting("hasegg",stripslashes($session['user']['acctid']));
 item_set(' tpl_id="goldenegg"', array('owner'=>$session['user']['acctid']) );
addnews("`^".$session['user']['name']."`^ stiehlt das goldene Ei aus dem Totenreich!");
addnav("Schnell weg","market.php");
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
}
//Ausgeklaut
else if($_GET['op']=="buy")
{

        page_header("Scythas Zelt");
        
        $rowe = user_get_aei('gemsin');
        $selled_gems = getsetting("selledgems",0);
        
        if ($rowe['gemsin']>getsetting("transferreceive",3))
        {
                output("`5Du hast heute schon genug Geschäfte gemacht. `5Scytha`5 hat keine Lust mit dir zu handeln. Warte bis morgen.");
        }
        else if ($selled_gems < 50 && $session['user']['gems']>$selled_gems)
        {
                output("`5Scytha`5wirft einen neidischen Blick auf dein Säckchen Edelsteine und beschließt, dir nichts mehr zu geben.");
        }
        else 
        {
                        if ($session['user']['gold']>=$costs[$_GET['level']])
                        {
                        if ($selled_gems >= $_GET['level'])
                        {
                                output( "`5Scytha`5 grapscht sich deine `^".($costs[$_GET['level']])."`5 Goldstücke und gibt dir im Gegenzug `#".($gems[$_GET['level']])."`5 Edelstein".($gems[$_GET['level']]>=2?"e":"").".`n`n");
                                $session['user']['gold']-=$costs[$_GET['level']];
                                $session['user']['gems']+=$gems[$_GET['level']];
                                
                                user_set_aei( array('gemsin'=>$rowe['gemsin']+$gems[$_GET['level']]) );
                                
                                if ($selled_gems - $_GET['level'] < 1)
                                {
                                        savesetting("selledgems","0");
                                }
                                else 
                                {
                                        savesetting("selledgems",$selled_gems-$_GET['level']);
                                }
                        } 
                        else 
                        {
                                output("`5Scytha`5 teilt dir mit, dass sie nicht mehr so viele Edelsteine hat, und bittet dich, später noch einmal wiederzukommen.`n`n");
                        }
                }
                else
                {
                        output( "`5Scytha`5 zeigt dir den Stinkefinger, als du versuchst, ihr weniger zu zahlen, als ihre Edelsteine momentan wert sind.`n`n");
                }
        }
        addnav("Zurück zum Marktplatz","market.php");
}
elseif($_GET['op']=="sell")
{
        page_header("Scythas Zelt");
        
        $rowe = user_get_aei('gemsout');
        
        $maxout = $session['user']['level']*getsetting("maxtransferout",25);
            if ($session['user']['gems']<1)
            {
                output("`5Scytha`5 haut mit der Faust auf den Tisch und fragt dich, ob du sie veralbern willst. Du hast keinen Edelstein.`n`n");
        }
        else if ($rowe['gemsout']>getsetting("transferreceive",3))
        {
                output("`5Du hast heute schon genug Geschäfte gemacht. `5Scytha`5 hat keine Lust mehr, mit dir zu handeln. Warte bis morgen.");
            }
            else
            {
                output("`5Scytha`5 nimmt deinen Edelstein und gibt dir dafür {$scost} Goldstücke.`n`n");
                $session['user']['gold']+=$scost;
                $session['user']['gems']-=1;
                savesetting("selledgems",getsetting("selledgems",0)+1);
                        
                        user_set_aei( array('gemsout'=>$rowe['gemsout']+1) );
            }
        addnav("Zum Zelt","gypsy.php");
        addnav("Zurück zum Marktplatz","market.php");
}
elseif($_GET['op'] == 'pc')
{
    page_header("Scythas Zelt");
    if($_GET['what'] == 'trade')
    {
        if($_GET['act'] == 'confirm')
        {
            $row = user_get_aei('pumpkin_coins');
            if($row['pumpkin_coins'] < 10)
            {
                output('`5Scytha sieht dich nur schief an und rät dir, dich nicht mit ihr anzulegen. Umsonst sind ihre Items nämlich nicht!');
            }
            else
            {
                output('`5Du lässt 10 Münzen in `5Scythas`5 Hände fallen und beobachtest, wie sie diese zuerst geschwind in einen Beutel verschwinden
                        lässt und dann die Box vor sich aufstellt, um sie anschließend mit ein paar Handgriffen zu öffen. Heraus kommt/kommen...`n
                        `n');
                switch(e_rand(1,8))
                {
                    case 1:
                        output('`5eine kleine Palette `%b`Lu`Rn`rt`ser `oS`{c`[h`]m`}i`Ön`äk`Äe`5, mit ganz vielen Orange- und Rottönen und jeder Menge Schwarz, passend zur
                                Feierlichkeit.');
                        item_add($session['user']['acctid'],'col_makeup');
                    break;
                    case 2:
                        output('`5eine Handvoll `óZ`xu`zc`Yk`te`&r`=k`ße`òk`Ts`me`5! Mhm, die sehen ja richtig lecker aus.');
                        item_add($session['user']['acctid'],'sgrcookies');
                    break;
                    case 3:
                        output('`5zwei glibberige `kF`ur`2o`Gs`pc`gh`+a`#u`3g`1e`Jn`5! Ein breites Grinsen legt sich auf Scythas Lippen, als du sie mit spitzen
                                Fingern entgegen nimmst. Ürks!');
                        item_add($session['user']['acctid'],'frogeyes');
                    break;
                    case 4:
                        output('`5ein Säckchen voller `4Z`ôa`Eu`db`Fe`^r`@p`*u`wl`9v`ùe`!r`5. Als du Scytha fragst, was es damit auf sich hat, zuckt diese nur mit den
                                Schultern und meint, dass du das schon selbst herausfinden musst.');
                        item_add($session['user']['acctid'],'magicpwder');
                    break;
                    case 5:
                        output('`5eine kleine Schachtel mit `Jm`1a`3g`wi`Ks`#c`jh`+en `%S`Lc`Rh`ra`&u`sm`oz`7u`)c`Ok`Se`Ur`À-`JR`1a`3t`Kt`#e`+n`5. Auch wenn sie richtig lecker aussehen, sagt dir dein Bauchgefühl, dass
                                du sie besser nicht versuchen solltest zu essen.');
                        item_add($session['user']['acctid'],'sgrrats');
                    break;
                    case 6:
                        output('`5ein `$Z`Eu`Qc`dk`qe`Fr`^s`hp`Ii`°nn`=e`ßn`òn`Te`mt`Êz`5. Himmel, ist das klebrig!');
                        item_add($session['user']['acctid'],'sgrspdrweb');
                    break;
                    case 7:
                        output('`5`Âu`În`Ue`Sc`Oh`)t`7e `.V`:a`-m`_pi`§rz`ôäh`4ne`5. Die sehen richtig schick aus... Da wirst du gleich zu Hause mal schauen, wie gut sie dir stehen.');
                        item_add($session['user']['acctid'],'vmpteeth');
                    break;
                    case 8:
                        output('`5ein `ÂT`Îo`Ut`Se`On`)k`7o`op`sf`r-`[L`]o`Vl`}l`éi`5! Wie cool! Den musst du gleich probieren! Und schon hast du das süße Stück im Mund und lutschst
                                fröhlich darauf herum. Der Moment wäre perfekt...,`n
                                wäre da nicht dieses riesige Grinsen in `5Scythas `5Gesicht, das dich zunehmend irritiert. Du hältst kurz inne und nimmst
                                den Lolli aus dem Mund, um sie zu fragen, was das denn soll - doch kaum hast du deinen Mund geöffnet, fängt die
                                Händlerin auch schon lauthals an zu lachen. Sogleich wirfst du einen Blick in den nächstgelegenen Spiegel - und
                                erschreckst dich ganz schön, als du die pechschwarze Zunge siehst, die dir dein Spiegelbild entgegen streckt. Da hat
                                dich `5Scytha`5 ja ganz schön aufs Korn genommen... Doch sie entschuldigt sich bei dir in Form eines Edelsteins.');
                        $session['user']['gems'] += 1;
                    break;
                    default:
                        output('`5... ein Beutel mit `q10 `EK`Qü`dr`qb`zis`Ym`Xü`fn`Zz`men`5. Und ein kleiner Zettel, über den du gebeten wirst, dem
                                hiesigen Adminteam mitzuteilen, dass dein angestrebter Tausch nicht geklappt hast.');
                        $row['pumpkin_coins'] += 10;
                    break;
                }
                $row['pumpkin_coins'] -= 10;
                user_set_aei($row);
            }
            addnav('Zurück');
            addnav('Zurück zum Zelt','gypsy.php');
        }
        else
        {
            output('`5Scytha`5 verlangt von dir `q10 `EK`Qü`dr`qb`zis`Ym`Xü`fn`Zz`men`5 für das Item. Als du jedoch sichergehen willst und fragst, was
                    für ein Item du von ihr bekommst, grinst sie nur breit und klopft mit einem Fingerknöchel gegen eine schwarze Box, die schräg
                    hinter ihr steht. `?"Lass dich überraschen!"`n
                    `5Möchtest du deine Münzen gegen ihr Item eintauschen?`n`n');
            addnav('Münzen tauschen');
            addnav('Na, klar!','gypsy.php?op=pc&what=trade&act=confirm');
            addnav('Lieber nicht','gypsy.php');
        }
    }
    else
    {
        output('`c`5Dies sind die fleißigsten `EK`Qü`dr`qb`zis`Ym`Xü`fn`Zz`men`5- und `ÂH`@e`2x`ue`Ânt`âa`4l`Ce`Âr`5-Sammler in '.getsetting('townname','Eranya').':`n
                `n
                <table cellpadding="20"><tr><td valign="top">
                <table>
                  <tr class="trhead">
                    <td align="center">Münzensammler</td>',true);
        $sql_add = '';
        if(su_check(SU_RIGHT_GROTTO)) {
            output('<td>Münzen (SU)</td>',true);
        } else {
            //$sql_add = 'AND superuser=0 ';
        }
        output('</tr>',true);
        $sql = db_query('SELECT name,pumpkin_coins AS pc FROM account_extra_info LEFT JOIN accounts USING(acctid) WHERE pumpkin_coins>0 '.$sql_add.'ORDER BY pumpkin_coins DESC,dragonkills DESC,login ASC');
        $count = db_num_rows($sql);
        if($count > 0)
        {
            for($i=0;$i<$count;$i++)
            {
                $row = db_fetch_assoc($sql);
                output('<tr class="'.($i%2?'trlight':'trdark').'">
                          <td align="center">'.$row['name'].'</td>',true);
                if(su_check(SU_RIGHT_GROTTO)) {output('<td align="center">'.$row['pc'].'</td>',true);}
                output('</tr>',true);
            }
        }
        else
        {
            output('<tr><td class="trlight">`iEs wurden noch keine`nMünzen gefunden.`i</td></tr>',true);
        }
        output('</table>
                </td><td valign="top">
                <table>
                  <tr class="trhead">
                    <td align="center">Talersammler</td>',true);
        if(su_check(SU_RIGHT_GROTTO)) {output('<td>Taler (SU)</td>',true);}
        output('</tr>',true);
        $sql = db_query('SELECT name,witch_coins AS wc FROM account_extra_info LEFT JOIN accounts USING(acctid) WHERE witch_coins>0 '.$sql_add.'ORDER BY witch_coins DESC,dragonkills DESC,login ASC');
        $count = db_num_rows($sql);
        if($count > 0)
        {
            for($i=0;$i<$count;$i++)
            {
                $row = db_fetch_assoc($sql);
                output('<tr class="'.($i%2?'trlight':'trdark').'">
                          <td align="center">'.$row['name'].'</td>',true);
                if(su_check(SU_RIGHT_GROTTO)) {output('<td align="center">'.$row['wc'].'</td>',true);}
                output('</tr>',true);
            }
        }
        else
        {
            output('<tr><td class="trlight">`iEs wurden noch keine`nTaler gesammelt.`i</td></tr>',true);
        }
        output('</table>
                </td></tr></table>`c`n',true);
        addnav('Zurück');
        addnav('Zurück zum Zelt','gypsy.php');
    }
}
elseif($_GET['op'] == 'wc')
{
        page_header("Scythas Zelt");
        $row = user_get_aei('witch_coins');
        if($_GET['act'] == 'confirm')
        {
            if($row['witch_coins'] < 5)
            {
                output('`5Die Händlerin sieht dich nur schief an und rät dir, dich nicht mit ihr anzulegen. Umsonst sind ihre Items nämlich nicht!');
            }
            else
            {
                output('`5Du lässt 5 Taler in `5Scythas`5 Hände fallen und beobachtest, wie sie diese zuerst geschwind in einen Beutel verschwinden
                        lässt und dann die Box vor sich aufstellt, um sie anschließend mit ein paar Handgriffen zu öffen. Heraus kommt/kommen...`n
                        `n');
                switch(e_rand(1,8))
                {
                    case 1:
                        output('`5eine kleine Palette `%b`Lu`Rn`rt`ser `oS`{c`[h`]m`}i`Ön`äk`Äe`5, mit ganz vielen Orange- und Rottönen und jeder Menge Schwarz, passend zur
                                Feierlichkeit.');
                        item_add($session['user']['acctid'],'col_makeup');
                    break;
                    case 2:
                        output('`5eine Handvoll `óZ`xu`zc`Yk`te`&r`=k`ße`òk`Ts`me`5! Mhm, die sehen ja richtig lecker aus.');
                        item_add($session['user']['acctid'],'sgrcookies');
                    break;
                    case 3:
                        output('`5zwei glibberige `kF`ur`2o`Gs`pc`gh`+a`#u`3g`1e`Jn`5! Ein breites Grinsen legt sich auf Scythas Lippen, als du sie mit spitzen
                                Fingern entgegen nimmst. Ürks!');
                        item_add($session['user']['acctid'],'frogeyes');
                    break;
                    case 4:
                        output('`5ein Säckchen voller `4Z`ôa`Eu`db`Fe`^r`@p`*u`wl`9v`ùe`!r`5. Als du Scytha fragst, was es damit auf sich hat, zuckt diese nur mit den
                                Schultern und meint, dass du das schon selbst herausfinden musst.');
                        item_add($session['user']['acctid'],'magicpwder');
                    break;
                    case 5:
                        output('`5eine kleine Schachtel mit `Jm`1a`3g`wi`Ks`#c`jh`+en `%S`Lc`Rh`ra`&u`sm`oz`7u`)c`Ok`Se`Ur`À-`JR`1a`3t`Kt`#e`+n`5. Auch wenn sie richtig lecker aussehen, sagt dir dein Bauchgefühl, dass
                                du sie besser nicht versuchen solltest zu essen.');
                        item_add($session['user']['acctid'],'sgrrats');
                    break;
                    case 6:
                        output('`5ein `$Z`Eu`Qc`dk`qe`Fr`^s`hp`Ii`°nn`=e`ßn`òn`Te`mt`Êz`5. Himmel, ist das klebrig!');
                        item_add($session['user']['acctid'],'sgrspdrweb');
                    break;
                    case 7:
                        output('`5`Âu`În`Ue`Sc`Oh`)t`7e `.V`:a`-m`_pi`§rz`ôäh`4ne`5. Die sehen richtig schick aus... Da wirst du gleich zu Hause mal schauen, wie gut sie dir stehen.');
                        item_add($session['user']['acctid'],'vmpteeth');
                    break;
                    case 8:
                        output('`5ein `ÂT`Îo`Ut`Se`On`)k`7o`op`sf`r-`[L`]o`Vl`}l`éi`5! Wie cool! Den musst du gleich probieren! Und schon hast du das süße Stück im Mund und lutschst
                                fröhlich darauf herum. Der Moment wäre perfekt...,`n
                                wäre da nicht dieses riesige Grinsen in `5Scythas `5Gesicht, das dich zunehmend irritiert. Du hältst kurz inne und nimmst
                                den Lolli aus dem Mund, um sie zu fragen, was das denn soll - doch kaum hast du deinen Mund geöffnet, fängt die
                                Händlerin auch schon lauthals an zu lachen. Sogleich wirfst du einen Blick in den nächstgelegenen Spiegel - und
                                erschreckst dich ganz schön, als du die pechschwarze Zunge siehst, die dir dein Spiegelbild entgegen streckt. Da hat
                                dich `5Scytha`5 ja ganz schön aufs Korn genommen... Doch sie entschuldigt sich bei dir in Form eines Edelsteins.');
                        $session['user']['gems'] += 1;
                    break;
                    default:
                        output('`5... ein Beutel mit `$5 `ÂH`@e`2x`ue`Ânt`âa`4l`Ce`Ârn`5. Und ein kleiner Zettel, über den du gebeten wirst, dem
                                hiesigen Adminteam mitzuteilen, dass dein angestrebter Tausch nicht geklappt hast.');
                        $row['witch_coins'] += 5;
                    break;
                }
                $row['witch_coins'] -= 5;
                user_set_aei($row);
            }
            addnav('Zurück');
            addnav('Zurück zum Zelt','gypsy.php');
        }
        else
        {
            output('`5Scytha`5 verlangt von dir `$5 `ÂH`@e`2x`ue`Ânt`âa`4l`Ce`Âr`5 für das Item. Als du jedoch sichergehen willst und fragst, was
                    für ein Item du von ihr bekommst, grinst sie nur breit und klopft mit einem Fingerknöchel gegen eine schwarze Box, die schräg
                    hinter ihr steht. `?"Lass dich überraschen!"`n
                    `5Möchtest du deine Taler gegen ihr Item eintauschen?`n`n');
            addnav('Taler tauschen');
            addnav('Na, klar!','gypsy.php?op=wc&act=confirm');
            addnav('Lieber nicht','gypsy.php');
        }
}
else if($_GET['op'] == 'ankh')
{
        page_header('Scythas Zelt');
        if($session['user']['gold'] < 1000 || $session['user']['gems'] < 1)
        {
                output('Das Ankh kostet 1000 Gold und 1 Edelstein. Soviel hast du leider nicht dabei.');
                addnav('Mist..');
                addnav('Zurück zu Scytha','gypsy.php');
        }
        else if ($_GET['act'] == 'search' && strlen($_POST['search']) > 2)
        {
                $search = str_create_search_string($_POST['search']);

                $sql = 'SELECT name,acctid FROM accounts WHERE name LIKE "'.$search.'" AND acctid!='.$session['user']['acctid'];
                $res = db_query($sql);

                $link = 'gypsy.php?op=ankh&op2=item_ok';

                output('<form action="'.$link.'" method="POST">
                        <select name="acctid">',true);

                while ($p = db_fetch_assoc($res) )
                {
                        output('<option value="'.$p['acctid'].'">'.preg_replace("'[`].'","",$p['name']).'</option>',true);
                }

                output('</select>`n`n
                        <input type="submit" class="button" value="Auswählen!"></form>',true);
                addnav('',$link);
                addnav('Zurück');
                addnav('Lieber doch nichts versenden','gypsy.php');
        }
        else if($_GET['op2'] == 'item_ok')
        {
                $acctid = (int)$_POST['acctid'];

                if(item_count('tpl_id="ankh" AND owner='.$acctid) > 0)
                {
                        output('Dieser Spieler besitzt bereits ein Ankh und darf deshalb kein weiteres erhalten.');
                        addnav('Zurück');
                        addnav('Zurück zu Scytha','gypsy.php');
                }
                else
                {
                        item_add($acctid , 'ankh');

                        systemmail($acctid,'`2Ein Geschenk!',$session['user']['name'].'`2 hat dir ein Ankh zukommen lassen. War das nicht nett von '.
                                   ($session['user']['sex']?'ihr':'ihm').'?');
                        output('`7Und schon macht sich ein Ankh auf den Weg zu seinem neuen Besitzer.');
                        debuglog('hat ein `^Ankh`0 verschickt an:',$acctid);
                        $session['user']['gold'] -= 1000;
                        $session['user']['gems'] -= 1;
                        addnav('Weiter');
                        addnav('Sehr schön','gypsy.php');
                }
        }
        else if($_GET['act'] == 'buy')
        {
                if(item_count('tpl_id="ankh" AND owner='.$session['user']['acctid']) > 0)
                {
                        output('Du besitzt bereits ein Ankh und darfst deshalb kein weiteres erhalten.');
                        addnav('Zurück');
                        addnav('Zurück zu Scytha','gypsy.php');
                }
                else if($session['user']['gold'] < 1000 || $session['user']['gems'] < 1)
                {
                        output('Das Ankh kostet 1000 Gold und 1 Edelstein. Soviel hast du leider nicht dabei.');
                        addnav('Mist..');
                        addnav('Zurück zu Scytha','gypsy.php');
                }
                else
                {
                        item_add($session['user']['acctid'] , 'ankh');
                        
                        output('`7Du zahlst den von Scytha verlangten Preis und nimmst im Gegenzug das Ankh entgegen.');
                        debuglog('hat ein `^Ankh`0 gekauft; Eigenbedarf');
                        $session['user']['gold'] -= 1000;
                        $session['user']['gems'] -= 1;
                        addnav('Weiter');
                        addnav('Sehr schön','gypsy.php');
                }
        }
        else
        {
                $link = 'gypsy.php?op=ankh&act=search';

                output('`7Mit einem Ankh kann ein Spieler einmalig die Welt der Lebenden besuchen, ohne jedoch vollständig wiedererweckt zu werden (=
                        RP-Wiederbelebung).`n
                        `n
                        Ein Ankh kostet 1000 Gold und 1 Edelsteine. Beachte außerdem, dass pro Charakter nur ein Ankh zulässig ist. Dafür kannst du
                        es auch deinen eigenen Chars zusenden.`n
                        `n
                        An wen möchtest du das Ankh versenden?`n
                        `n
                        <form action="'.$link.'" method="POST">
                        Name: <input type="text" name="search"> <input type="submit" class="button" value="Suchen!"></form>`n
                        <a href="gypsy.php?op=ankh&act=buy" class="button">Für Eigenbedarf kaufen</a>');
                addnav('',$link);
                addnav('','gypsy.php?op=ankh&act=buy');
                addnav('Zurück');
                addnav('Lieber doch nichts versenden','gypsy.php');
        }
}
else
{
        checkday();
        page_header("Scythas Zelt");
        $ecost=$cost*5;
        output("`c`b`?In Scythas Zelt`b`c`n
                `5Du betrittst das Zelt hinter Thoyas Rüstungsladen, das dir zwischen all den festgebauten Läden sofort ins Auge springt, obwohl es selbst völlig unscheinbar wirkt.
                Im Inneren jedoch empfängt dich fremdländisches Chaos: Wo immer der Platz ausreicht, stehen Kisten herum, deren Inhalt sich nicht selten auch auf den Boden daneben verteilt,
                und einige hohe Regal befinden sich im hinteren Teil des Zelts und bieten Stauraum für weitere, kleine Kästchen, Schalen oder Gläser, allesamt mit wunderlichen Dingen gefüllt. Hier und da hängen getrocknete Äste
                dir unbekannter Pflanzen von der Zeltdecke - ob von ihnen der süßlich-herbe Geruch ausgeht, der das Zelt erfüllt?`n
                Vor den Regalen, gekleidet in einen weiten Rock aus bunten Flicken und eine weiße Bluse, steht eine Frau mit olivfarbener Haut, die in diesem Moment zu dir hinübersieht: Scytha. Sie
                mustert dich kurz und eingehend, dann beginnt sie von jetzt auf gleich zu sprechen.`n
                Sie informiert dich darüber, dass sie derzeit `#".getsetting("selledgems",0)."`5 Edelsteine auf Lager hat, die zum Verkauf stehen.
               ".(getsetting("selledgems",0)>=100 ? "" : "Auch bietet sie dir einen `ifairen`i Preis für deine eigenen gesammelten Edelsteine.")."`n
                Außerdem kann sie dir, sollte sich das `^goldene Ei`5 im Totenreich befinden, den Versuch ermöglichen, es zu stehlen. Dies kostet `^{$ecost}`5 Gold.`n
                Und nicht zuletzt kannst du von ihr das magische Ankh erhalten, das es dir gestattet, nach deinem Ableben den Kontakt zur Welt der Lebenden aufrecht zu erhalten.");

        if(getsetting('halloween_special_coins',0) == 1)
        {
            output('`n`nSo kurz vor Halloween kannst du zudem deine im Wald gesammelten `EK`Qü`dr`qb`zis`Ym`Xü`fn`Zz`men `5und `ÂH`@e`2x`ue`Ânt`âa`4l`Ce`Âr `5bei ihr gegen nützliche Items
                    eintauschen und dir die Namen der momentanen Münzen- und Talersammler-Champions nennen lassen.');
        }
        //addnav("Bezahle und rede mit den Toten","gypsy.php?op=pay");

//Goldenes Ei aus dem Totenreich klauen
if (getsetting("hasegg",0)>0){
        $sql = "SELECT name,loggedin,alive FROM accounts WHERE acctid = ".getsetting("hasegg",0);
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        if (!$row['alive']) {
        addnav("Versuche das goldene Ei aus dem Totenreich zu stehlen","gypsy.php?op=egg"); }}
//Klau-Ende

    if ($session['user']['charisma']==4294967295 && $session['user']['seenlover']<1)
        {
                  $sql = "SELECT name,alive FROM accounts WHERE ".$session['user']['marriedto']." = acctid ORDER BY charm DESC";
                  $result = db_query($sql) or die(db_error(LINK));
                $row = db_fetch_assoc($result);
                if ($row['alive']==0) addnav("Bezahle und flirte mit ".$row['name'],"gypsy.php?op=pay&was=flirt");
        }
        //addnav('Wahrsagen lassen','nerwen.php');
        //addnav("Tarotkarten legen (1 Edelstein)","tarot.php");
        //if (su_check(SU_RIGHT_COMMENT)) addnav("Superusereintrag","gypsy.php?op=talk");
        if ($session['user']['level']<15)
        {
                addnav('Edelsteinkauf');
                addnav("Kaufe 1 Edelstein ({$costs[1]} Gold)","gypsy.php?op=buy&level=1");
                addnav("Kaufe 2 Edelsteine ({$costs[2]} Gold)","gypsy.php?op=buy&level=2");
                addnav("Kaufe 3 Edelsteine ({$costs[3]} Gold)","gypsy.php?op=buy&level=3");
        }
        if (getsetting("selledgems",100)<100 && $session['user']['level']>1) {
            addnav('Edelsteinverkauf');
            addnav("Verkaufe 1 Edelstein für {$scost} Gold","gypsy.php?op=sell");
        }
        addnav('RP-Wiederbelebung');
        addnav('A?Versende ein Ankh','gypsy.php?op=ankh');
        if(getsetting('halloween_special_coins',0) == 1)
        {
            addnav('Halloween');
            addnav('Kürbismünzen eintauschen','gypsy.php?op=pc&what=trade');
            addnav('Hexentaler eintauschen','gypsy.php?op=wc');
            addnav('Liste der Sammler','gypsy.php?op=pc');
        }
        knappentraining_link('scytha');
        addnav("Zurück");
        addnav("Zurück zum Marktplatz","market.php");
}
}

page_footer();
?>

