<?php
/* Ruis Tempel
von Narjana*/
require_once "common.php";
addcommentary();
checkday();

   switch($_GET['op'])
{
        case 'hl':
        {
                page_header("große Höhle");
                output("`c`bgroße Höhle`b`n`n
                                DIE WAHRHEIT IST: WAS LEBT, IST DEM TODE GEWEIHT`n
                                So die Inschrift auf dem riesigen Torbogen aus tiefschwarzem Stein, die dir knochenbleich entgegenscheint. Noch während deine Augen auf dem Schriftzug
                                verweilen, hörst du ein leises Schleifen, als sich das Tor darunter auftut: was anfangs als gravierte Steinwand erschien teilt sich, schiebt sich ineinander
                                und gegeneinander wie Schuppen und Windungen einer riesenhaften Schlange. Innerhalb von Augenblicken ist das Tor für dich geöffnet und gibt den Tempel der
                                Totengöttin preis, ein Ort der jedem offen steht.`n`n`c");

                viewcommentary("hl","sagt:",15);
                addnav("Tempelportal","rui_tempel.php");
                addnav("in Ruis Kristallgärten","moor_unten.php?op=mus");
//                addnav("Mitte des Teiches","moor_unten.php?op=treppe");
//                addnav("In die Felder (Logout)","login.php?op=logout",true);
                break;
        }
        case 'wein':
        {
                page_header("Weinkeller");
                output("`cWeinkeller`n`c
                ");

                viewcommentary("hoehle","sagt:",15);
                addnav("alte Weinflachen","rui_tempel.php?op=weinflasche");
                addnav("Tempel","rui_tempel.php");
        //        addnav("Uferrand","moor_unten.php?op=teich");
//                addnav("magische Wendeltreppe runtersteigen","moor_unten.php");
//                addnav("magische Wendeltreppe hochsteigen","Elfengarten.php");
                break;
        }
        case 'weinflasche':
        {
                if($session['user']['weinkeller']){
                page_header("Weinkeller");
                output("`c`bWeinkeller`b`n`n
                                Meinst du nicht du hast heute schon genug getrunken?");
                addnav ("zurück","rui_tempel.php?op=wein");
                break;
                }else{

                page_header ("Weinkeller");
                output("`cWeinkeller`n`c
                ");

                addnav ("Rotwein","rui_tempel.php?op=rot");
                addnav ("Whiskey","rui_tempel.php?op=whis");
                addnav ("Rum","rui_tempel.php?op=rum");
                addnav ("Grüner Wein","rui_tempel.php?op=gruen");
                addnav ("Schwarzer Wein","rui_tempel.php?op=schwarz");
                addnav ("Goldener Wein","rui_tempel.php?op=gold");
                addnav ("Violetter Wein","rui_tempel.php?op=viol");
                addnav ("Blauer Wein","rui_tempel.php?op=blau");

                $session['user']['weinkeller']='1';
                break;
                }
        }
        case 'rot':
        {
                page_header ("Weinkeller");
                output ("`cWeinkeller`n`n
                                Du öffnest die staubige alte Weinflasche und genemigst dir einen tiefen Schluck. Uralt wie Erde und warm fließt der Wein durch deine Adern.`c`n");
                $session[user][attack]=round($session[user][attack]*1.03);
                $session[user][defence]=round($session[user][defence]*1.03);
                addnav ("zurück","rui_tempel.php?op=wein");
                addnav ("nochmal","rui_tempel.php?op=rot2");
                break;
        }
        case 'rot2':
        {
                page_header ("Weinkeller");
                output("`cWeinkeller`n`n
                                Du bekommst einfach nicht genug von diesem himmlischen Getränk. Und was soll es schon schaden? Erneut füllst du dir deinen Kelch und leerst ihn zu ehren der
                                - äh -von irgendwem wirds schon sein. Du kannst dich nicht so recht erinnern.");
                $session[user][attack]=round($session[user][attack]*1.07);
                $session[user][defence]=round($session[user][defence]*1.07);
                $session['user']['turns']-=1;
                addnav ("zurück","rui_tempel.php?op=wein");
                addnav ("nochmal","rui_tempel.php?op=rot3");
                break;
        }
        case 'rot3':
        {
                page_header ("Weinkeller");
                output("`cWeinkeller`n`n
                                Irgendwie weckt das Zeug den Helden in dir. Hoch schwenkst du dein drittes Glas und schüttest dir schon beinahe die Hälfte in den Kragen während du parallel versuchst
                                lauthals Loblieder auf irgendwelche nackten Elfen, die nachts mit den Irrlichtern tanzen und morgens auf Rui treffen zu singen. Du wirst Stunden brauchen, um
                                überhaupt wieder klar sehen zu können.");
                $session[user][attack]=round($session[user][attack]*.90);
                $session[user][defence]=round($session[user][defence]*.90);
                $session['user']['turns']-=5;
                addnav("zurück","rui_tempel.php?op=wein");
                addnav("nochmal","rui_tempel.php?op=rot4");


                break;
        }

        case 'rot4':
        {
                page_header ("Weinkeller");
                output("`cWeinkeller`n`n
                                Du kannst wohl wirklich nicht genug haben. Das war dein viertes Glas von etwa 200 Jahre altem Wein. Voll Euphorie brichst du im Weinkeller zusammen und bemerkst
                                erst jetzt wie viele Leichen da schon an den Rändern aufgestapelt wurden. Du hast dich totgesoffen.`c`n`n");

        addnews("`3".$session['user']['name']." `3hat sich in Ruis Tempel Totgesoffen`3.");
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        //                $session['user']['experience']*=1.3;
        addnav("du bist tot","news.php");
                break;
        }
        case 'whis':
        {
                page_header ("Weinkeller");
                output("`cWeinkeller`n`n
                                Scharf wie Feuer brennt sich das uralte Gesöff durch deine Adern. Du keuchst und deine Sicht benebelt sich. GLeichzeitig fängst du beinahe wie irre an zu kichern.
                                Innerhalb von wenigen Sekunden bist du Hakedicht.");
                $session[user][attack]=round($session[user][attack]*1.10);
                $session[user][defence]=round($session[user][defence]*1.10);
                $session['user']['turns']-=10;
                addnav("zurück","rui_tempel.php?op=wein");
                addnav("nochmal","rui_tempel.php?op=whis2");
                break;
        }
        case 'whis2':
        {
                page_header ("Weinkeller");
                output ("`c`bWeinkeller`b`n`n
                                        Du bist wohl wirklich des Wahnsinns noch mehr von dem Zeug zu saufen. Oder aber Ruis Bar hat dir so gut gefallen, dass du ihr gleich persönlich für den Schluck
                                        danken willst. Auf jedenfall bist du TOT.`c`n");
                addnews("`3".$session['user']['name']." `3hat sich in Ruis Tempel Totgesoffen`3.");
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        //                $session['user']['experience']*=1.3;
        addnav("du bist tot","news.php");
                break;
        }
        case 'rum':
        {
                page_header ("Weinkeller");
                output ("`c`bWeinkeller`b`n`n
                                Der Rum den du dir aus einem alten Fass zapfst scheint original von einem gesunkenen Piratenschiff zu stammen. Auf jedenfall inspiriert er dich direkt zu einer
                                Hymne über die See und die furchtlosen Freibeuter, die dir zu diesem Zeug verholfen haben.`n`n`c");
                $session[user][attack]=round($session[user][attack]*1.05);
                $session[user][defence]=round($session[user][defence]*1.05);
                addnav ("zurück","rui_tempel.php?op=wein");
                addnav("nochmal","rui_tempel.php?op=rum2");
                break;
        }
        case 'rum2':
        {
                page_header ("Weinkeller");
                output ("`c`bWeinkeller`b`n`n
                                \"Hey Ho und ne Buddel voll Rum\" So wirklich klar klingt deine Gesanksdarbietung wirklich nicht mehr, aber du hast das berauschende Gefühl als würde ein rauer
                                Männerchor von 100 Stimmen dich begleiten. Du hörst gelächter und schwankst torkelnt, die Flasche im Arm durch den Raum während du irgendwelchen Illusionen
                                zuprostest.");
                $session[user][attack]=round($session[user][attack]*.93);
                $session[user][defence]=round($session[user][defence]*.93);
                $session['user']['turns']-=4;
                addnav("zurück","rui_tempel.php?op=wein");
                addnav("nochmal","rui_tempel.php?op=rum3");
                break;
        }
        case 'rum3':
        {
                page_header ("Weinkeller");
                output ("`c`bWeinkeller`b`n`n
                                Immer lauter werden die anderen Stimmen und du befindest dich in der Mitte einer Farbenfroh gekleideten Gesellschaft, die mit Säbeln bewaffnet ist, singen und dir
                                zuprosten. Erst später merkst du, dass die Piraten alle schon tot sind. Genau wie du jetzt auch. PROST`n`n");
                                addnews("`3".$session['user']['name']." `3hat sich in Ruis Tempel Totgesoffen`3.");
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        //                $session['user']['experience']*=1.3;
        addnav("du bist tot","news.php");
                break;
        }
        case 'gruen':
        {
                page_header ("Weinkeller");
                output ("`c`bWeinkeller`b`n`n
                                Du setzt das Gesöff an, das eine merkwürdige Grüne Farbe aufweißt. Vielleicht katapultiert es dich ja direkt zu den Feen? Die Hoffnung trügt, wie du merkst, während
                                Die Tränen beginnen über dein Gesicht zu laufen. Schmerzen wie du sie noch nie gekannt hast, brennen durch deinen Körper und verwandeln dein Blut in schwarze Tinte
                                Du schreist - aber nichts und niemand kann dir noch helfen, außer dir den ersehnten Tot schenken - was du schließlich selbst in die Hand nimmst.
                                Schwarzes Blut tropft auf den Boden - du bist tot.");
                addnews("`3".$session['user']['name']." `3Vergiftete sich in Ruis Weinkeller`3.");
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        //                $session['user']['experience']*=1.3;
        addnav("du bist tot","news.php");
                break;
        //tödliches gift
        }
        case 'gold':
        {
                page_header("Weinkeller");
                output("`c`bWeinkeller`b`n`n
                                Sanft gleitet die goldene Flüssigkeit durch deine Kehle. Du fühlst als würden sich Engelsschwingen auf deinem Rücken ausbreiten, Euphorie und Glück breiten sich
                                in deinem ganzen Körper aus. So gut hast du dich noch nie im Leben gefühlt.");
                $session[user][attack]=round($session[user][attack]*1.05);
                $session[user][defence]=round($session[user][defence]*1.05);
                $session['user']['turns']+=2;
                addnav("zurück","rui_tempel.php?op=wein");
                addnav("nochmal","rui_tempel.php?op=gold2");
        //euphorie und stärker geworden etc und klar wie nie (angriff steigern) aber kein alkohol. beim zweiten mal wird man zu gold
                break;
        }
        case 'gold2':
        {//tot
                page_header("Weinkeller");
                output("`c`bWeinkeller`b`n`n
                                Oh - du willst dass dieses Himmlische Gefühl niemals endet und so nimmst du ein zweites Glas von dieser herrlichen, goldenen Flüssigkeit. Du fühlst dich als köntest du die
                                ganze Welt erobern - wenn das bewegen dir nur nicht so schwer fallen würde. Langsam erklimmst du die Treppe, während deine Haut einen immer stärkeren Goldschimmer bekommt.
                                Gerade ebenso erreichst du die Schatzkammer - so dass du dich zu den anderen Schätzen legen kannst. Du bist zu Gold geworden. Aber immerhin ist das eine dir unver
                                gessliche erfahrung");
                addnews("`3".$session['user']['name']." `3hat sich selbst vergoldet`3.");
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
                   $session['user']['experience']*=1.3;
        addnav("du bist tot","news.php");
                break;
        }
        case 'schwarz':
        {
                page_header("Weinkeller");
                output("`c`bWeinkeller`b`n`n
                                Leicht bitter riecht das Getränk als du es in dein Glas schüttest. Aber mutig wie du bist schüttest du es dir dennoch in die Kehle hinein. Augenblicklich wird die
                                schlecht. Altes Blut bringt deinen Magen zum rebellieren. Dieser Blutwein hat eindeutig zu lange auf einen Trinker gewartet.");
                $session['user']['turns']-=5;
                $session[user][attack]=round($session[user][attack]*.90);
                $session[user][defence]=round($session[user][defence]*.90);
                addnews("`3".$session['user']['name']." `3 hat bemerkt dass Jahrhunderte alter Blutwein nicht gut für den Magen ist. Er ist momentan mit erbrechen beschäftigt.`3.");
                addnav("zurück","rui_tempel.php?op=wein");
        //uraltes, totes blut (davon kotzt du dich sowas von aus) anriff und verteidigung runter (vampierwein)
                break;
        }
        case 'viol':
        {
                page_header("Weinkeller");
                output("`c`bWeinkeller`b`n`n
                                Du trinkst einen Kelch voll dieser seltsamen, violetten Flüssigkeit und auf einmal fühlst du dich leicht. Du spührst wie Magie durch deine Adern fließt und
                                zusätzliche Kraft freisetzt. Du kannst nicht anders. Du musst lachen.");
                increment_specialty();
                addnav ("zurück","rui_tempe.php?op=wein");
                addnav("nochmal","rui_tempel.php?op=viol2");
        //zwei zusätzliche anwendungen für den tag. bei noch einem glas löst sich der geist aus dem körper und er stirbt
                break;
        }
        case 'viol2':
        {//tot
                page_header("Weinkeller");
                output("`c`bWeinkeller`b`n`n
                                Du genehmigst dir noch einen weiteren Schluck und wieder durchfährt die leichtigkeit dich. Du merkst kaum wie dein Geist sich von deinem Körper löst - so voller
                                Magie ist der jetzt. Leider kann ein Körper ohne Geist nicht weiterexistieren. Du bist tot.");
                addnews("`3".$session['user']['name']." `3 starb durch eine überdosis Magie`3.");
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        //                $session['user']['experience']*=1.3;
        addnav("du bist tot","news.php");
                break;
        }
        case 'blau':
        {
                page_header ("Weinkeller");
                output ("`c`bWeinkeller`b`n`n
                                        Weich sie schwarzer Samt glitt die Flüssigkeit deine Kehle herunter. Süß und warm schmeckt der Trank und eine wohlige Wärme breitet sich in deinem Körper
                                        aus. Ein Himmlisches Gefühl breitet sich aus und du sinkst glücklich lächelnd zu Boden, schläfst sanft ein. Du hattest sicher mit einend er schönsten Tode von
                                        allen Wesen in diesem Land");
                addnews("`3".$session['user']['name']." `3schaffte es nicht aus dem samtschwarzen Traum wieder aufzuwachen`3.");
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        //                $session['user']['experience']*=1.3;
        addnav("du bist tot","news.php");
                break;
                //schlafmittel und tot.

        }


        default:
        {

                page_header("Der Tempel der Totengöttin");

                output("`c`bTempel der Totengöttin`b`n
                Schlangen aus schwarz glänzendem Metall umschlingen die Säulen die die Halle säumen. Der Boden ist glatt, fast spiegelnd, ausgelegt mit dunkel-und hellgrauen Fliesen,
                und das unwirkliche Licht dass den Ort erhellt kommt von überall und nirgends zugleich. Am Kopfende der Halle, eingelassen in den dunkelgrauen Marmor der Wand, ein
                einzelnes Symbol oder Schriftzeichen, knochenbleich, dessen Bedeutung sich niemandem so leicht erschließen wird.
                Um viele der Säulen häufen sich Schätze auf, Goldmünzen, Edelsteine, Waffen, Rüstungen, Schmuck, wohl die einzige Farbe in der ansonsten trostlosen Umgebung. Das letzte
                Hemd
                hat keine Taschen, und der Besitz der den wandernden Kriegern bei ihrem Tod verlorengeht, alles was mit einem Verstorbenen begraben wird, all das landet hier. Wer sich
                an den Schätzen der Toten bereichern will, dem steht es frei sein Glück zu versuchen.`n`c
                ");


                viewcommentary("rui_tempel","sagt",15);

                addnav("in die Krypta","kapell.php?op=krypta");
                addnav("Tempeleingang","rui_tempel.php?op=hl");
                addnav("Weinkeller","rui_tempel.php?op=wein");
                break;
     }

}
page_footer();

?>