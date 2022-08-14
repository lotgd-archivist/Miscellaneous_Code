
<?php

/*
* Die Sphinx, Besonderes Ereignis
* 
* 18.5.09
* Idee: Ein Spieler Alvions, Name unbekannt
* Umsetzung: Linus
* für "Die Wälder von Alvion"
* http.alvion-logd.de/logd/
*
*/

if (!isset($session)) exit();
page_header('Die Sphinx');

$out="`n`n";
switch($_GET['ops']){
    case 'zurueck':
        $session['user']['specialinc'] = '';
        $session['user']['reputation'] --;
        $ende=true;
        $out.="`tDie Sphinx neigt leicht ihr Haupt, während du dich verbeugst und ohne Worte zurückziehst. Du überlegst noch, ob du davon in der"
            ." Taverne erzählen solltest, aber das wird dir bestimmt niemand glauben, also verwirfst du diesen Gedanken wieder, während zur weiter"
            ." durch den Wald streifst.";        
    break;
    case 'pruef':
        $session['user']['specialinc'] = 'sphinx.php';
        $out.="`tDu entscheidest dich dafür, dich der Prüfung der Sphinx zu stellen und trittst ihr erhobenen Hauptes entgegen. Was sagst du?`n`n`n"

            ."<a href=forest.php?ops=pruef2&id=1>`&1. `Q\"Ich werde mich Eurer Prüfung unterziehen und auch bestehen!\""
            ." `tverkündest du laut und deutlich.</a>`n`n"

            ."<a href=forest.php?ops=pruef2&id=2>`&2. `Q\"Ich möchte mich Eurer Prüfung unterziehen, oh mächtiges Wesen, und sollte ich nicht bestehen, so werde ich meine Strafe"
            ." freiwillig hinnehmen.\" `tsagst du mit entschlossener Stimme und nickst leicht, um deinen Entschluss noch einmal zu unterstreichen.</a>`n`n"

            ."<a href=forest.php?ops=pruef2&id=3>`&3. `Q\"Tritt zur Seite, du dummes Ding. Ich schaffe Alles, und dich mit links!\" `twirfst du der Sphinx an"
            ." den Kopf und setzt einen trotzigen Blick auf, während du auf deine Passage wartest.</a>";        

        allownav('forest.php?ops=pruef2&id=1');
        allownav('forest.php?ops=pruef2&id=2');
        allownav('forest.php?ops=pruef2&id=3');
        addnav('1?Das Erste natürlich!','forest.php?ops=pruef2&id=1');
        addnav('2?Lieber das Zweite!','forest.php?ops=pruef2&id=2');
        addnav('3?Ganz klar! Nummer Drei','forest.php?ops=pruef2&id=3');
    break;

    case 'pruef2':
        switch(intval($_GET['id'])){
                case 2:
                    $gems=mt_rand(2,5);
                    $out.="`tDie Sphinx nickt leicht. `T\"Das sind die Worte eines weisen Wesens, das bereit ist, die Konsequenzen für seine Taten zu tragen."
                        ." Du hast dich als würdig erwiesen und hast die Prüfung bestanden. Du darfst passieren.\" `tentgegnet sie zu deiner Verwunderung"
                        ." und tritt zur Seite, so dass du passieren kannst.`nAls du am Ende des Weges ankommst, entdeckst du einen steinernen Altar, auf dem"
                        ." ein Beutel mit $gems Edelsteinen liegt.`n";
                    $session['user']['reputation'] ++;
                    $session['user']['gems'] += $gems;
                    $ende=true;
                break;

                case 3:
                    $out.="`T\"Du hast.. nicht bestanden!\" `tDie Sphinx schlägt dich mit der rechten Pranke. Du fliegst durch die Luft und verlierst dabei"
                        ." all dein Gold und 10% deiner Erfahrungspunkte.`nDu bist tot! War es das wert?`n";
                    $session['user']['reputation'] --;
                    $session['user']['hitpoints'] = 0;
                    $session['user']['gold'] = 0;
                    $session['user']['alive'] = false;
                    $session['user']['experience'] = round($session['user']['experience']*0.9,0);
                    addnews('`&'.$session['user']['name'].' `twurde von einer Sphinx erschlagen');
                    addnav('Tägliche News','news.php');
                break;

                default:
                    $out.="`tDie Mundwinkel der Sphinx heben sich leicht an. `T\"Hochmut kommt vor dem Fall.\" `tEs hebt eine Pranke und rammt dich"
                        ." unangespitzt in den Boden. Schwer verletzt schleppst du dich zurück in den Wald.`n"
                        ." Auf Grund deines Hochmuts verlierst du an Ehre und fast alle Lebenspunkte!`n";
                    $session['user']['reputation'] -= 5;
                    $session['user']['hitpoints'] = 1;
                    $ende=true;
        }
        $session['user']['specialinc'] = '';
    break;
    
    default:
        $session['user']['specialinc'] = 'sphinx.php';
        $out.="`tDu kommst plötzlich auf eine Straße aus Pflastersteinen. Moment mal! Pflastersteine mitten im Wald? Da stimmt doch was nicht!"
            ." Das ist auch dein Gedanke, als du dem Weg folgst. Doch gerade, als du weiter gehen willst, rascheln die Baumkronen und öffnen sich."
            ." Heraus tritt etwas Riesiges. Ein Löwe ... Das ist die erste Beschreibung, die dir dazu  einfällt, doch seit wann haben solch riesige Löwen"
            ." das Gesicht einer Frau? Das Gesicht eines Menschen, um genau zu sein."
            ."`n`T\"Sei gegrüßt Krieger, der du hier wandelst auf den Pfaden der Altvorderen ... Willst du hier weiter wandern, so stelle dich meiner Prüfung!"
            ." Solltest du bestehen, lasse ich dich gewähren. Solltest du scheitern, werde ich dich töten!"
            ." Ziehst du dich zurück, werde ich dich unangetastet gehen lassen. Überlege gut!\" `tspricht es, kaum, dass du den Mund aufmachst, um etwas zu sagen."
            ." Eine Sphinx ... Ja, von diesen mystischen Kreaturen hast du schon mal gehört. Hinter vorgehaltener Hand sprach man von diesen Wesen,"
            ." die den Körper eines Löwen, aber den Kopf einer hübschen, jungen Frau haben. Sie sollen angeblich Straßen bewachen, auf denen zu wandeln"
            ." nur Würdigen vorbehalten ist. Die Sphinx ist riesig, fast so groß wie die Bäume, durch die sie hervorgetreten ist."
            ."`n`nDas hier ist wohl eine dieser Kreaturen. Du überlegst, was du am Besten tun solltest ...";
            addnav('Möglichkeiten');
            addnav('P?Zur Prüfung','forest.php?ops=pruef');
            addnav('Zurück','forest.php?ops=zurueck');
}

output ($out,true);
if($ende) forest(true);

?>

