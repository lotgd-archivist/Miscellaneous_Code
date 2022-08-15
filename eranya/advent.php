
<?php
/* * * *
 * Adventskalender
 * Autor: Silva
 * erstellt für http://eranya.de
 * * * */
require_once 'common.php';
page_header('Der Adventskalender');
// Aktuelles Datum erfassen
$day = date('d');
$int_day = (int)$day;
$daysleft = 24-$int_day;
$month = date('m');
// end
// Sonstige Variablen
$sql = db_query('SELECT adventpick FROM account_extra_info WHERE acctid='.$session['user']['acctid']);
$r = db_fetch_assoc($sql);
$adventpick = $r['adventpick'];
$bool_dbquery = true;
// end
// Vorbereitung: Aktive SU in array speichern
$int_su_amnt = 3;
$arr_su = array(array('elfid'=>985,'elfcolor'=>'`Q','elftalk'=>'`Q"Heh, '.$session['user']['name'].'`Q! Na, wie gefällt dir unser Weihnachtsbaum?"`o','elfaction'=>'eine kleine Holzfigur an einen Ast hängt')
               ,array('elfid'=>979,'elfcolor'=>'`[','elftalk'=>'`["Hallo, '.$session['user']['name'].'`[! Sieh dir nur unseren riesigen Weihnachtsbaum an. Ist er nicht wieder großartig geworden?"`o','elfaction'=>'einen Strohstern an einen Ast hängt')
               ,array('elfid'=>979,'elfcolor'=>'`_','elftalk'=>'`_"Schau mal, '.$session['user']['name'].'`_! Ist der Baum nicht wieder schick geworden dieses Jahr?"`o','elfaction'=>'etwas Lametta über einen Ast hängt')
               );

/*  ,array('elfid'=>1,'elfcolor'=>'`h','elftalk'=>'`h"Huhu, '.$session['user']['name'].'`h! Schön, dich zu sehen. Sieht er nicht klasse aus, unser Weihnachtsbaum?"`o','elfaction'=>'eine kleine Holzfigur an einen Ast hängt')*/
    
// end
// Wer wird heute zum Wichtel? (:
$int_switch = $int_day%$int_su_amnt;                   # Es wird täglich unter den SU rotiert
$elfid = $arr_su[$int_switch]['elfid'];                # AcctID des SU -> zur Ermittlung des vollständigen Charnamens
$elfcolor = $arr_su[$int_switch]['elfcolor'];          # SU-Sprechfarbe
$elftalk = $arr_su[$int_switch]['elftalk'];            # Das, was der SU zur Begrüßung sagt
$elfaction = $arr_su[$int_switch]['elfaction'];        # Das, was der SU zu Anfang tut
// Name aus DB holen & Variablen füllen
$row = db_fetch_assoc(db_query('SELECT a.name, a.sex, aei.cname FROM accounts a LEFT JOIN account_extra_info aei USING (acctid) WHERE a.acctid = "'.$elfid.'" LIMIT 1'));
$elf = $row['name'];
$elfname = $row['cname'];
$elfsexn = ($row['sex'] ? 'sie' : 'er');
$elfsexd = ($row['sex'] ? 'ihrem' : 'seinem');
// end
if($month == '12' && $day <= '24')
{
    if($adventpick != date('Y-m-d'))
    {
        $tout = '`oMitten im Wald, umringt von anderen immergrünen Nadelbäumen, entdeckst du bei deiner Wanderung plötzlich eine riesige Tanne, an deren
             Ästen bunt schillernde Kristallkugeln hängen. In allen Farben gibt es sie, selbst schwarze und weiße kannst du entdecken; und allesamt
             leuchten sie von innen heraus, als hätte jemand Kerzen in ihrem Inneren versteckt.`n
             Apropos jemand: Als du genauer hinsiehst, entdeckst du zwischen den nadeligen Ästen eine Gestalt, die gerade '.$elfaction.'. Moment... Ist das nicht
             '.$elf.'`o? Tatsächlich! Und als '.$elfsexn.' dich bemerkt, springt '.$elfsexn.' auch sogleich von '.$elfsexd.' Ast herunter und
             winkt dich zu sich her. '.$elftalk.' Dabei deutet '.$elfsexn.' auf die Tanne hinter sich. Eine Weile betrachtet ihr das riesige Meer an Farben, das
             da über euren Köpfen leuchtet - ehe dir plötzlich etwas in die Hand gedrückt wird. '.$elfcolor.'"Hier, für dich!"`o, hörst du '.$elfname.'
             `osagen. '.$elfcolor
            .($daysleft == 0 ? '"Ein kleines Geschenk zu Weihnachten."'
                             : '"Von heute an sind es nur noch '.$daysleft.' Tage bis Weihnachten." ')
            .'`n`oErstaunt stellst du fest, dass '.$elfsexn.' dir ';
        switch($day)
        {
        case '01':    # Ankh
            $tout .= 'eine `Xleuchtend braune `oKristallkugel geschenkt hat. Als du sie vorsichtig öffnest, findest du ein kleines `XAnkh`o in ihrem
                      Inneren, das du sofort einsteckst.';
            item_add($session['user']['acctid'],'ankh');
        break;
        case '02':    # 2 CP
            $tout .= 'eine `%leuchtend pinke `oKristallkugel geschenkt hat. Ihr Leuchten geht sofort auf dich über und lässt einige Narben verschwinden,
                      die du bei deinen Kämpfen im Wald davongetragen hast. Dadurch siehst du nun viel hübscher aus. Du bekommst `%zwei Charmepunkte`o.';
            $session['user']['charm'] += 2;
        break;
        case '03':    # 3 ES
            $tout .= 'eine `#türkisblau glitzernde `oKristallkugel geschenkt hat. In ihrem Inneren findest du `#3 Edelsteine`o.';
            $session['user']['gems'] += 3;
        break;
        case '04':    # 4% Erfahrung
            $tout .= 'eine `Vlila glimmende `oKristallkugel geschenkt hat. Ihr sachtes Leuchten nimmt sofort deine Augen gefangen - und plötzlich
                      fühlst du, wie `Vneues Wissen `odich durchflutet. Deine Erfahrung steigt.';
            $session['user']['experience'] *= 1.04;
        break;
        case '05':    # Handvoll Erdnüsse
            $tout .= 'eine `Yleuchtend braune `oKristallkugel geschenkt hat. Als du sie vorsichtig öffnest, findest du eine `YHandvoll Ernüsse`o in ihrem
                      Inneren, die du sofort einsteckst.';
            item_add($session['user']['acctid'],'erdnuss');
        break;
        case '06':    # 6 DP
            $tout .= 'eine `4rot-`Tbraun `oleuchtende Kristallkugel geschenkt hat. In ihrem Inneren findest du einen Mini-Nikolaus - und einen Zettel,
                      der dir rät, die Figur zu Peterson zu bringen. Damit sind dir `T6 `4Donationspunkte `osicher.';
            $session['user']['donation'] += 6;
        break;
        case '07':    # 7 WK
            $tout .= 'eine `2leuchtend grüne `oKristallkugel geschenkt hat. Ihr Leuchten geht sofort auf dich über und erfüllt dich mit frischer
                      Energie. Genug, um noch einmal ordentlich `2den Wald unsicher `omachen zu können.';
            $session['user']['turns'] += 7;
        break;
        case '08':    # Golem
            $tout .= 'eine `mtiefbraune `oKristallkugel geschenkt hat. Als du sie vorsichtig öffnest, findest du einen `mErdklumpen`o in ihrem
                      Inneren. Was du damit wohl anfangen kannst..?';
            item_add($session['user']['acctid'],'golem');
        break;
        case '09':    # 1 kleiner Heiltrank
            $tout .= 'eine `qleuchtend orangene `oKristallkugel geschenkt hat. Als du sie vorsichtig öffnest, findest du einen `qkleinen Heiltrank`o in ihrem
                      Inneren, den du sofort einsteckst.';
            item_add($session['user']['acctid'],'klnrhltrnk');
        break;
        case '10':    # 1 goldenes Amulett
            $tout .= 'eine `tmattgoldene `oKristallkugel geschenkt hat. In ihrem Inneren findest du ein `tgoldenes Amulett`o, das dich von nun an
                      einige Zeit beschützen wird.';
            item_add($session['user']['acctid'],'gamulett');
        break;
        case '11':    # +1 ANGR & +1 VERT
            $tout .= 'eine `7silbrig glänzende `oKristallkugel geschenkt hat. ihr Glanz geht auf dich über, und im nächsten Moment spürst du, wie deine
                      Muskeln größer und stärker werden. Das wird sicher nicht nur deinen `7Angriff `ostärken, sondern auch deine `7Verteidigung`o
                      verbessern.';
            $session['user']['attack'] += 1;
            $session['user']['defence'] += 1;
        break;
        case '12':    # Macadamia-Nüsse
            $tout .= 'eine `Xleuchtend braune `oKristallkugel geschenkt hat. Als du sie vorsichtig öffnest, findest du eine Handvoll `XMacadamia-Nüsse`o in ihrem
                      Inneren, die du sofort einsteckst.';
            item_add($session['user']['acctid'],'macanut');
        break;
        case '13':    # 13% non-permanente LP
            $tout .= 'eine `(giftgrüne `oKristallkugel geschenkt hat. Ihr Schimmer überträgt sich auf deine Haut und macht sie `(widerstandsfähiger`o.';
            $session['user']['hitpoints'] *= 1.13;
        break;
        case '14':    # 1400 Gold
            $tout .= 'eine `^gelb glitzernde `oKristallkugel geschenkt hat. In ihrem Inneren findest du `^1400 Goldstücke`o.';
            $session['user']['gold'] += 1400;
        break;
        case '15':    # 5 ES
            $tout .= 'eine `#türkisblau leuchtende `oKristallkugel geschenkt hat. In ihrem Inneren findest du `#5 Edelsteine`o.';
            $session['user']['gems'] += 5;
        break;
        case '16':    # Beutel Heilkräuter
            $tout .= 'eine `udunkelgrüne `oKristallkugel geschenkt hat. Als du sie vorsichtig öffnest, findest du einen `uBeutel mit Heilkräutern darin,
                      den du sofort einsteckst.';
            item_add($session['user']['acctid'],'hlkrter');
        break;
        case '17':    # Funkenregen
            $tout .= 'eine `gpastellgrüne `oKristallkugel geschenkt hat. In ihr entdeckst du einen Beutel mit `ggrünlichen Pulver`o, das seltsam knistert,
                      als du den Beutel bewegst. Was du damit wohl anfangen kannst..?';
            item_add($session['user']['acctid'],'fnknrgn');
        break;
        case '18':    # 18 RPP
            $tout .= 'eine `&leuchtend weiße `oKristallkugel geschenkt hat. Ihr Leuchten geht prompt auf dich über und umhüllt dich mit ihrem reinen
                      Licht. Hast du nicht neulich den Zaven davon reden hören?';
            db_query('UPDATE account_stats SET rppoints = rppoints+18 WHERE acctid = '.$session['user']['acctid']);
        break;
        case '19':    # 3 CP
            $tout .= 'eine `ýfliederfarbene `oKristallkugel geschenkt hat. Ihr Leuchten geht sofort auf dich über und lässt einige Narben verschwinden,
                      die du bei deinen Kämpfen im Wald davongetragen hast. Dadurch siehst du nun viel hübscher aus. Du bekommst `ýdrei Charmepunkte`o.';
            $session['user']['charm'] += 3;
        break;
        case '20':    # 2 permanente LP
            $tout .= 'eine `wblaue glitzernde `oKristallkugel geschenkt hat. Das Glitzerpuder bleibt an deinen Fingern haften und zieht in deine Haut
                      ein, um dort dauerhaft für zusätzlichen Schutz zu sorgen. Du erhältst `wzwei permanente Lebenspunkte`o.';
            $session['user']['maxhitpoints'] += 2;
        break;
        case '21':    # großer Heiltrank
            $tout .= 'eine `Qleuchtend orangene `oKristallkugel geschenkt hat. Als du sie vorsichtig öffnest, findest du einen `Qgroßen Heiltrank`o in ihrem
                      Inneren, den du sofort einsteckst.';
            item_add($session['user']['acctid'],'grsshltrnk');
        break;
        case '22':    # 2200 Gold
            $tout .= 'eine `^leuchtend gelbe `oKristallkugel geschenkt hat. In ihrem Inneren findest du `^2200 Goldstücke`o.';
            $session['user']['gold'] += 2200;
        break;
        case '23':    # 23 Gefallen
            $tout .= 'eine `µpechschwarze `oKristallkugel geschenkt hat. Ihr Leuchten geht sofort auf dich über und verleiht dir eine finstere Aura -
                      die sogar `²J`ça`Âr`Îc`Âa`çt`²h `obeeindruckt. Als Zeichen seiner Anerkennung gewährt er dir `µ23 zusätzliche Gefallen`o.';
            $session['user']['soulpoints'] += 23;
        break;
        case '24':    # 24 DP
            $tout .= 'eine `$rot-`&weiß `oleuchtende Kristallkugel geschenkt hat. Sogar ein kleiner Weihnachtsmann mitsamt Schlitten und Rentieren ist
                      auf ihre Oberfläche gemalt worden, in akribischer Detailarbeit. '.$elfname.' `olächelt dich fröhlich an und gibt dir den Tipp, die
                      Kugel schnell zu Peterson zu bringen. Zur Feier des Tages schenkt er dir dafür `&24 `$Donationspunkte`o!`n
                      `n
                      `c`b`^Das E-Team wünscht dir ein schönes Weihnachtsfest!`b`n
                      `i`7(Und den Weihnachtsmuffeln natürlich ebenso ein paar entspannte Tage (: )`i`c';
            $session['user']['donation'] += 24;
        break;
        default:
            $tout .= '`inichts`i geschenkt hat?? Hm, wohl ein Fehler. Das solltest du schnellstens dem E-Team berichten.';
            //$bool_dbquery = false;
        break;
        }
        if($bool_dbquery != false)
        {
        db_query('UPDATE account_extra_info SET adventpick=\''.date('Y-m-d').'\' WHERE acctid='.$session['user']['acctid']);
        }
    }
    else
    {
        $tout = '`oMitten im Wald, umringt von anderen immergrünen Nadelbäumen, entdeckst du bei deiner Wanderung plötzlich eine riesige Tanne, an deren
             Ästen bunt schillernde Kristallkugeln hängen. In allen Farben gibt es sie, selbst schwarze und weiße kannst du entdecken; und allesamt
             leuchten sie von innen heraus, als hätte jemand Kerzen in ihrem Inneren versteckt.`n
             Apropos jemand: Als du genauer hinsiehst, entdeckst du zwischen den nadeligen Ästen eine Gestalt, die gerade '.$elfaction.'. Moment... Ist das nicht '.$elf.'`o? Tatsächlich! Und als '.$elfsexn.' dich bemerkt, springt '.$elfsexn.' auch sogleich von '.$elfsexd.' Ast herunter und
             winkt dich zu sich her. '.$elftalk.' Dabei deutet '.$elfsexn.' auf die Tanne hinter sich. Eine Weile betrachtet ihr das riesige Meer an Farben, das
             da über euren Köpfen leuchtet - dann wünschst du '.$elfname.' `onoch einen schönen Tag und ziehst von dannen.`n
             `n
             `^Du hast dein Adventsgeschenk heute bereits abgeholt. Schaue doch morgen nochmal vorbei. Dann wird sicher wieder etwas Neues für dich
             in den Kugeln zu finden sein.';
    }
}
else
{
    $tout = '`oDu kommst an einem Teil des Waldes vorbei, der dir irgendwie seltsam erscheint.., doch du kannst beim besten Willen nicht sagen, warum.
             Vielleicht solltest du wann anders nochmal hierher zurückkommen - dann fällt dir sicher wieder ein, was das Besondere an diesem Ort war.';
}
addnav('W?Zurück in den Wald','woods.php');
addnav('S?Zurück in die Stadt','village.php');
// Textausgabe & Schluss
output($tout.'`n`n',true);
page_footer();
?>

