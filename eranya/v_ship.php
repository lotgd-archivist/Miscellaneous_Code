
<?php

/* * * * *
 * Handelsschiff - inspiriert vom Wanderhändler
 * Autor: Silva
 * erstellt für eranya.de
 * * * * */

require_once "common.php";

$show_invent = true;

require_once(LIB_PATH.'dg_funcs.lib.php');
if($session['user']['guildid'] && $session['user']['guildfunc'] != DG_FUNC_APPLICANT) {
        $rebate = dg_calc_boni($session['user']['guildid'],'rebates_vendor',0);
}

define('V_SHIPCOLORHEAD','`ß');
define('V_SHIPCOLORTEXT','`²');
define('V_SHIPCOLORTALK','`=');
define('V_SHIPCOLORTRADER','`Y');

$shipname = '`²A`Ûu`&ri`=c`ßa';
$shipname_link = strip_appoencode($shipname,3);
$trader = '`YI`zs`xm`za`Yíl';

page_header("Handelsschiff ".$shipname_link);

if($_GET['op'] == 'buy_do') {

        $show_invent = false;

        if($_GET['tpl_id']) {
                $item = item_get_tpl(' tpl_id="'.$_GET['tpl_id'].'" ');

                $name = $item['tpl_name'];

                $goldprice = round($item['tpl_gold'] * $_GET['gold_r']);
                $gemsprice = round($item['tpl_gems'] * $_GET['gems_r']);

                $item['tpl_gold'] = round($goldprice * 0.5);
                $item['tpl_gems'] = round($gemsprice * 0.5);

                item_add($session['user']['acctid'],0,$item);

                addnav("Mehr kaufen","v_ship.php?op=buy&act=new");
        }
        else {
                $item = item_get(' id="'.$_GET['id'].'" ', false);

                $name = $item['name'];

                $goldprice = round($item['gold'] * $_GET['gold_r']);
                $gemsprice = round($item['gems'] * $_GET['gems_r']);

                $item['gold'] = round($goldprice * 0.5);
                $item['gems'] = round($gemsprice * 0.5);

                item_set('id='.(int)$_GET['id'],array('deposit1'=>0,'deposit2'=>0,'owner'=>$session['user']['acctid'],'gold'=>$item['gold'],'gems'=>$item['gems']) );

                addnav("Mehr kaufen","v_ship.php?op=buy&act=old");
        }

        output(V_SHIPCOLORTEXT."Mit einem breiten Grinsen übergibt dir ".$trader.V_SHIPCOLORTEXT." ".$name.", während du `^".$goldprice." Gold
               ".($gemsprice?"und ".$gemsprice." Edelsteine":"").V_SHIPCOLORTEXT." abzählst. ");

        $session['user']['gold'] -= $goldprice;
        $session['user']['gems'] -= $gemsprice;

}
else if($_GET['op'] == 'sell_do') {

        $show_invent = false;

        $item = item_get(' id="'.$_GET['id'].'" ');

        $goldprice = round($item['gold'] * $_GET['gold_r']);
        $gemsprice = round($item['gems'] * $_GET['gems_r']);

        item_delete(' id='.$item['id']);

        $session['user']['gold'] += $goldprice;
        $session['user']['gems'] += $gemsprice;

        output(V_SHIPCOLORTEXT."Du übergibst ".$item['name']." an ".$trader.V_SHIPCOLORTEXT.", während er dir `^".$goldprice." Gold
               ".($gemsprice?"und ".$gemsprice." Edelsteine":"").V_SHIPCOLORTEXT." abzählt.");

        addnav('Mehr verkaufen','v_ship.php?op=sell');

}

else if ($_GET['op']=="buy")
{

        output(V_SHIPCOLORTEXT.
               "Du schlängelst dich an den anderen Leuten vorbei bis direkt vor den Stand, wo du eine gute Sicht auf die dargebotene Ware hast. Sofort
                eilt der Händler herbei, schiebt sich seinen Fes zurecht und verneigt sich dann tief vor dir. ".V_SHIPCOLORTRADER."\"Willkommen,
                Fremde".($session['user']['sex']?'':'r').", am Stand des weit gereisten ".$trader.V_SHIPCOLORTRADER.". Ich verkaufe Kostbarkeiten aus
                aller Welt. Was ist es, das Euer Auge erfreut?\" ".V_SHIPCOLORTEXT."Das seltsam gerollte R, das immer wieder herauszuhören ist, verrät
                dir dabei einmal mehr, dass er aus fernen südlichen Gefilden stammen muss.
                `n".($rebate?"Als ".$trader.V_SHIPCOLORTEXT." erkennt, dass du Mitglied einer Partnergilde bist, sichert er dir `^".$rebate." % ".V_SHIPCOLORTEXT."Rabatt
                auf all seine Waren zu.":""));

        $rebate = (100 - $rebate) * 0.01;
        
        output('`n`nSeine Neuwaren:`n`n');
        item_show_invent(' v_ship_new=1 ', true, 1, $rebate, $rebate);
        
        addnav('Zurück');
        addnav("Zur ".$shipname_link,"v_ship.php");

}

else if ($_GET['op']=="sell"){

        output(V_SHIPCOLORTEXT."Mit dem Blick eines Profis begutachtet ".$trader.V_SHIPCOLORTEXT." deinen Beutelinhalt und sucht sich jene Dinge heraus, die für ihn
               infrage kommen.`n`n");

        item_show_invent(' owner='.$session['user']['acctid'].' AND (v_ship=2 OR v_ship=3) ', false, 2, 1, 1);

        addnav('Zurück');
        addnav("Zur ".$shipname_link,"v_ship.php");

}
else if($_GET['op'] == 'work')
{
        $show_invent = false;
        switch($_GET['act'])
        {
                // nach Arbeit fragen
                case '':
                        $wages = $session['user']['level']*6;
                        if($wages < 30) {$wages = 30;}
                        $tout = V_SHIPCOLORTEXT.
                                "Direkt neben der Ladeluke sprichst du einen Mann an, der, bewaffnet mit Feder, Tinte und Papier, gewissenhaft notiert,
                                 was von der Ladung an Land getragen oder welche Ware an Bord gebracht wird. Kaum hat er erfahren, dass du nach Arbeit
                                 suchst, wandert sein Blick einmal vollständig an dir auf und ab, ehe ";
                        if($session['user']['dragonkills'] < 1 && $session['user']['level'] < 2)
                        {
                                $tout .= V_SHIPCOLORTEXT."er beide Brauen in die Höhe zieht. ".V_SHIPCOLORTALK."\"Wie? Du halbe Portion willst allen
                                         Ernstes hier arbeiten? Ha, ganz sicher nicht. Da musst du mir schon erstmal beweisen, dass in dir auch wirklich
                                         die nötige Kraft steckt, um mit den anderen mithalten zu können. Komm wieder, wenn du deinen ersten Meister bezwungen
                                         hast, dann sehen wir weiter.\" ".V_SHIPCOLORTEXT."Murrend lässt du ihn wieder seine Arbeit machen.`n`n";
                                addnav('Zurück');
                        }
                        elseif($session['user']['level'] == 15)
                        {
                                $tout .= V_SHIPCOLORTEXT."er den Kopf schüttelt. ".V_SHIPCOLORTALK."\"Nein, dich stelle ich nicht ein. Geh lieber den
                                         grünen Drachen töten; damit hilfst du uns und dem Dorf weit mehr, als wenn du nun hier deine Kraft
                                         verschwendest.\" ".V_SHIPCOLORTEXT."Eine klare Ansage. Hier wirst du zurzeit wohl keine Arbeit finden.`n`n";
                                addnav('Zurück');
                        }
                        else
                        {
                                $tout .= V_SHIPCOLORTEXT."er kurz nickt. ".V_SHIPCOLORTALK."\"Du scheinst kräftig genug zu sein für diesen Job. Gut, wenn
                                         du willst, kannst du hier arbeiten. Ich zahle dir `^".$wages." Gold ".V_SHIPCOLORTALK."pro Runde, die du beim
                                         Ein- und Ausladen mithilfst.\"`n
                                         `n
                                         ".V_SHIPCOLORTEXT."Für wie viele Runden möchtest du arbeiten?`n
                                         `n
                                         <form action='v_ship.php?op=work&act=do' method='post'>
                                         Runden: <input type='text' name='rounds'> <input type='submit' class='button' value='Arbeiten'>
                                         </form>`n`n";
                                addnav('','v_ship.php?op=work&act=do',false,false,false,false,true,'Bist du sicher?');
                                addnav('Lieber doch nicht');
                        }
                break;
                // Arbeiten
                case 'do':
                        // Wie viele Runden?
                        $rounds = $_POST['rounds'];
                        if($rounds == '') {$rounds = $session['user']['turns'];}
                        if($session['user']['turns'] < $rounds) {$rounds = $session['user']['turns'];}
                        // Wieviel Lohn?
                        $wages = $session['user']['level']*6;
                        if($wages < 30) {$wages = 30;}
                        // Lohn zahlen
                        $wages_ges = $wages*$rounds;
                        $session['user']['gold'] += $wages_ges;
                        $session['user']['turns'] -= $rounds;
                        $tout = V_SHIPCOLORTEXT."Du hilfst ".$rounds." Runden lang beim Hin- und Hertragen der Waren und erhältst dafür `^".$wages_ges."
                                Gold".V_SHIPCOLORTEXT.".`n`n";
                        addnav('Zurück');
                break;
                // Debug
                default:
                        $tout = "`&Nanu? Was machst du denn hier? Schnell zurück zum Spiel. Am besten schreibst du auch gleich dem Admin-Team eine Anfrage
                                 mit folgendem Satz:`n
                                 `n
                                 `^act: ".$_GET['act']." in v_ship nicht vorhanden.";
                        addnav('Zurück');
                break;
        }
        output($tout,true);
        addnav('A?Zurück zur '.$shipname_link,'v_ship.php');
}
else{
        checkday();
        output(V_SHIPCOLORHEAD."`c`bAuf der ".$shipname."`b`c`n".
               V_SHIPCOLORTEXT."Sie ist das größte Schiff, dass zurzeit im Hafen Eranyas vor Anker liegt: Mit ihren drei weißen Segeln und dem mächtigen
               Bug ragt die ".$shipname.V_SHIPCOLORTEXT." stolz über ihre Brüder und Schwestern hinweg und zieht augenblicklich sämtliche Aufmerksamkeit auf
               sich. Was wohl auch gewollt ist, immerhin handelt es sich bei ihr um ein Handelsschiff aus fernen Ländern, das Ladung aus aller Welt mit
               sich gebracht hat. Entsprechend lebendig und hektisch ist das Treiben an Bord des Schiffes: Kräftige Seemänner tragen Fässer und Kisten
               durch die Gegend, vorbei an einer Traube von Menschen, die sich um den Stand eines südländischen Händlers versammelt haben. Von antik bis
               sonderbar, jeder Stil scheint dort vertreten zu sein - vielleicht ein guter Grund, um selbst einmal einen Blick zu riskieren?`n`n");
        addcommentary();
        viewcommentary('handelsschiff','Sagen',15,'sagt');
        addnav('Das Handelsschiff');
        addnav("W?Waren ansehen","v_ship.php?op=buy");
        addnav("v?Etwas verkaufen","v_ship.php?op=sell");
        addnav('Arbeit');
        addnav('A?Nach Arbeit fragen','v_ship.php?op=work');
        knappentraining_link('v_ship');
        addnav('Zurück');

}

addnav("H?Zum Hafen","harbor.php");

page_footer();
?>

