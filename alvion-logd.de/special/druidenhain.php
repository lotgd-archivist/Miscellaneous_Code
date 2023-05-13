
<?php
// 10052006
// Script von -DoM für MotWD (http://my.logd.com/motwd) und MoT (http://my-logd.com/mot)
// Idee und viele Texte dieser Story von einem Spieler von MoTWD Namens: Cadderly
if (!isset($session)) exit();
$filename = basename(__FILE__);
$fn = "forest.php";
$spi = ($session['user']['specialinc']=$filename);

switch ($_GET['op']){
    case "rueckweg":
        $session['user']['specialinc']="";
        $what = array(1=>"`^Gold`\$","`#Edelsteinen`\$","`7Büchern`\$","`%Stoffballen`\$");
        output("`qAuf deinem Rückweg kommst du wieder an dem bewusstlosen Tiger vorbei. So wie es scheint, ist er noch immer hinüber,
                aber du schleichst dennoch auf Samtpfoten an ihm vorbei.`n`n");
        switch (e_rand(1,6)){
            case 3:
            case 5:
                output("`\$Gerade, als du an dem Tiger vorbei ist, wacht dieser auf und brüllt markerschütternd. Du willst wegrennen,
                        aber deine Taschen sind vollgestopft mit ".$_GET['howm']." ".$what[$_GET['what']]."....`n`n
                        Du bist zu langsam und der Tiger erwischt dich.`n
                        Du bist tot und kannst Morgen weiter spielen. Du bekommst aber 10% Erfahrung hinzu.");
                addnews($session['user']['name']."`q konnte nicht schnell genug vor einem Tiger wegrennen, weil ".($session['user']['sex']?"ihre":"seine")." Taschen voll mit ".$_GET['howm']." ".$what[$_GET['what']]."`q gestopft waren.");
                $session['user']['gold']=0;
                $session['user']['experience']*1.1;
                $session['user']['hitpoints']=0;
                $session['user']['alive']=false;
                addnav("Tägliche News","news.php");
            break;
            case 1:
            case 2:
            case 4:
            case 6:
                output("`\$Gerade als du an dem Tiger vorbei bist, wacht dieser auf und brüllt dich an. Wegrennen fällt aus, weil deine
                        Taschen ja mit ".$_GET['howm']." ".$what[$_GET['what']]." vollgestopft sind. Geistesgegenwärtig tarnst du dich,
                        wie der alte Druide, mit Laub und legst dich flach auf den Boden. Du harrst der Dinge.....`n`n
                        So harrst du Stunden aus, bis der Tiger seine Suche aufgibt.`n`n
                        Dein Verstecken kostet dich sämtliche Waldkämpfe, aber du konntest deine Beute sichern.`n");
                addnews($session['user']['name']."`q konnte im Druidenhain ".($session['user']['sex']?"ihre":"seine")." Taschen mit ".$_GET['howm']." ".$what[$_GET['what']]."`q vollstopfen.");
                switch ($_GET['what']){
                    case 1:
                        output("Du bekommst `^".$_GET['howm']." Gold`\$!");
                        $session['user']['gold']+=$_GET['howm'];
                    break;
                    case 2:
                        output("Du bekommst `#".$_GET['howm']." Edelsteine`\$!");
                        $session['user']['gems']+=$_GET['howm'];
                    break;
                    case 3:
                        output("Für deine `7".$_GET['howm']." Bücher`\$ erhältst du in jeder Fertigkeit Anwendungen hinzu");
                        $session['user']['thievery']+=$_GET['howm']*3;
                        $session['user']['darkarts']+=$_GET['howm']*3;
                        $session['user']['magic']+=$_GET['howm']*3;
                        $session['user']['fire']+=$_GET['howm']*3;
                        $session['user']['wmagie']+=$_GET['howm']*3;
                                            break;
                    case 4:
                        output("Für deine `%".$_GET['howm']." Stoffballen lässt du dir feine Gewänder schneidern. Dieses tolle Äussere
                                bringt dir viel Charme hinzu.");
                        $session['user']['charm']+=($_GET['howm']*10);
                    break;
                }
                $session['user']['turns']=0;
            break;
        }
    break;
    case "schatz":
        switch ($_GET['act']){
            case 1:
                $spi;
                output("`qDu entdeckst einen riesigen Schatz. Das muss der sagenhafte Schatz der Druiden sein. Du erblickst `^Gold`q,
                        `#Edelsteine`q, `%feine Stoffe`q und einen Haufen eingestaubter `7Bücher.`n`n
                        `\$Was möchtest du mitnehmen?");
                addnav("`^Das Gold",$fn."?op=schatz&act=2");
                addnav("`#Die Edelsteine",$fn."?op=schatz&act=3");
                addnav("`%Feine Stoffe",$fn."?op=schatz&act=5");
                addnav("`7Die Bücher",$fn."?op=schatz&act=4");
            break;
            case 2:
                $spi;
                $gold = e_rand(20000,80000);
                output("`qDu entscheidest dich für das Gold. Du packst alles, was du verstauen kannst, ein und machst dich auf den Rückweg.`n`n
                        `\$Du kannst `^".$gold." Gold`\$ in deinen Taschen verstauen");
                addnav("Weiter",$fn."?op=rueckweg&what=1&howm=$gold");
            break;
            case 3:
                $spi;
                $gems = e_rand(20,80);
                output("`qDu entscheidest dich für die Edelsteine. Du packst alles, was du verstauen kannst, ein und machst dich auf den Rückweg.`n`n
                        `\$Du kannst `#".$gems." Edelsteine`\$ in deinen Taschen verstauen.");
                addnav("Weiter",$fn."?op=rueckweg&what=2&howm=$gems");
            break;
            case 4:
                $spi;
                $books = e_rand(8,20);
                output("`qDu entscheidest dich für die Bücher. Du packst alles, was du verstauen kannst, ein und machst dich auf den Rückweg.`n`n
                        `\$Du kannst `7".$books." Bücher`\$ in deinen Taschen verstauen.");
                addnav("Weiter",$fn."?op=rueckweg&what=3&howm=$books");
            break;
            case 5:
                $spi;
                $stoffe = e_rand(5,15);
                output("`qDu entscheidest dich für die feinen Stoffballen. Du packst alles, was du verstauen kannst, ein und machst dich auf den Rückweg.`n`n
                        `\$Du kannst `%".$stoffe." Stoffballen`\$ in deinen Taschen verstauen.");
                addnav("Weiter",$fn."?op=rueckweg&what=4&howm=$stoffe");
            break;
            default:
                $spi;
                output("`QDu bist immer noch überwältigt von diesem unberührten Fleckchen Erde, als du plötzlich ein Funkeln
                        zwischen den Ästen und Büschen entdeckst. Du nimmst deine ".$session['user']['weapon']." und schlägst dir
                        den Weg frei.");
                addnav("Weiter",$fn."?op=schatz&act=1");
            break;
        }
    break;
    case "hain":
        switch ($_GET['act']){
            case 3:
                output("`QEhe du weißt, wie dir geschieht, hebt der Tiger laut knurrend mit einem mächtigen Satz vom Boden ab.`n");
                switch (e_rand(1,9)){
                    case 1:
                    case 4:
                        output("`qDu versuchst, deine ".$session['user']['weapon']."`q zu ziehen, aber du schaffst es nicht mehr
                                rechtzeitig. Der Tiger zerfleischt dich.`n`n
                                Als Du bei Ramius wieder aufwachst, hättest du schwören können, dass der Tiger genau die gleichen
                                Augen hatte, wie der ehrwürdige, alte Druide.`n`n
                                Die Erkenntnis hat dich gelehrt, dass du vielleicht darauf hören solltest, wenn dich nächstes
                                Mal einer darum bittet, seinen heiligen Ort nicht zu betreten, ");
                        switch (e_rand(1,6)){
                            case 1:
                            case 4:
                                output("weshalb dir keine Erfahrungspunkte abgezogen werden.`n`n
                                        `\$Du bist tot und kannst Morgen weiter spielen. Du erleidest keinen Erfahrungsverlust, aber
                                        hast all dein Gold verloren.");
                                $session['user']['gold']=0;
                            break;
                            case 2:
                            case 5:
                                $expmalus = round($session['user']['experience']*(e_rand(1,10)/100));
                                output("weshalb dir Erfahrungspunkte abgezogen werden.`n`n
                                        `\$Du bist tot und kannst Morgen weiter spielen. Du verlierst ".$expmalus." Erfahrungspunkte
                                        und außerdem alles Gold, das du bei dir hattest.");
                                $session['user']['experience']-=$expmalus;
                                $session['user']['gold']=0;
                            break;
                            case 3:
                            case 6:
                                $expbonus = round($session['user']['experience']*(e_rand(1,10)/100));
                                $gemmalus = round($session['user']['gems']*(e_rand(1,10)/100));
                                output("weshalb du Erfahrungspunkte hinzu bekommst.`n`n
                                `\$Du bist tot und kannst Morgen weiter spielen. Du bekommst ".$expbonus." Erfahrungspunkte, aber
                                verlierst ".$gemmalus." Edelsteine.");
                                $session['user']['experience']+=$expbonus;
                                $session['user']['gems']-=$gemmalus;
                            break;
                        }
                        $session['user']['specialinc']="";
                        addnews($session['user']['name']."`Q wurde von einem weißen Tiger zerfleischt, weil ".($session['user']['sex']?"sie":"er")." nicht auf einen alten Druiden hören wollte.");
                        $session['user']['hitpoints']=0;
                        $session['user']['alive']=false;
                        addnav("Tägliche News","news.php");
                    break;
                    case 2:
                    case 5:
                    case 6:
                    case 9:
                        $session['user']['specialinc']="";
                        output("`qDu versuchst, deine ".$session['user']['weapon']."`q zu ziehen und schaffst es gerade noch rechtzeitig.
                                Du rammst dem Tiger deine Waffe noch während des Fluges in den Bauch. Die Wucht des Aufpralls reißt dich
                                aber mit zu Boden.`n`nDas Blut des Tigers überströmt deinen gesamten Körper, ");
                        switch (e_rand(1,6)){
                            case 1:
                            case 4:
                                output("welches deiner Waffe gar nicht gut tut. Du siehst, wie sich vor deinen Augen deine
                                        ".$session['user']['weapon']."`q komplett auflöst. In Panik stößt du den Tiger von deinem Leib
                                        und schmeisst deine kaputte Waffe weg.`n`n
                                        `\$Fluchend schaust du dir die Überreste deiner ".$session['user']['weapon']."`\$ an. Du solltest
                                        dich nach einer neuen Waffe umschauen, denkst du dir, aber nimmst erst mal den erstbesten Stock.");
                                addnews($session['user']['name']."'s`q Waffe wurde vom Blut eines Tigers aufgelöst!");
                                debuglog(" verlor die Waffe im Druidenhain.");
                                $session[user][attack]-=$session[user][weapondmg];
                                $session['user']['weapon']="dünner Stock";
                                $session['user']['weapondmg']=1;
                                $session['user']['weaponvalue']=1;
                                $session[user][attack]+=$session[user][weapondmg];
                            break;
                            case 2:
                            case 5:
                                output("welches deiner Haut gar nicht gut tut. Du bemerkst einen stechenden Geruch von verbranntem Fleisch.
                                        Ehe du bemerkst, dass es dein eigenes ist, ist es schon zu spät.`n`n
                                        `\$Du erleidest schwere Verbrennungen, die deinem Äußeren gar nicht gut tun. Daher verlierst du
                                        ein Viertel deines Charmes und fast alle Lebenspunkte.");
                                addnews($session['user']['name']."`q entkam mit schweren Verbrennungen nur knapp dem Tod!");
                                debuglog(" verlor die die Hälfte seiner Charmepunkte.");
                                $session['user']['charm']=($session['user']['charm']*0.9);
                                $session['user']['hitpoints']=1;
                            break;
                            case 3:
                            case 6:
                                output("welches deinem Duft gar nicht gut tut. Du stinkst wie 10 läufige Trollfrauen. Dieser Höllengestank
                                        macht alle Wesen, auf die du treffen wirst, höllisch wütend. Das wird eine beschwerliche Zeit für
                                        dich.");
                                $buff = array( "name" => "Höllengestank","roundmsg" => "Der Gestank macht deine Gegner noch wütender!!!","wearoff" => "Der Gestank verfliegt.","rounds" => "250","badguyatkmod" => "2.5","badguydefmod" => "2.5","activate" => "defense");
                                $session['bufflist']['hoellengestank'] = $buff;
                            break;
                        }
                    break;
                    case 3:
                    case 7:
                    case 8:
                        $spi;
                        output("`qDu weichst dem springenden Tiger gazellengleich aus und der Tiger prallt ungebremst gegen einen Baum.
                                Du hast Glück, denn der Tiger scheint bewusstlos zu sein. Schnellen Schrittes gehst du weiter, um dich
                                genauer umzusehen.");
                        addnav("Weiter",$fn."?op=schatz");
                    break;
                }
            break;
            case 2:
                $spi;
                output("`QEs ist ein Stück unberührter Natur, selbst ein Einhorn kannst du kurz erblicken, das durch den Wald läuft.
                        Überwältigt von diesem Anblick, drehst du dich im Kreis, um Alles genau ansehen zu können, doch noch ehe du
                        dich einmal gedreht hast, schnellt ein weißer Tiger auf dich zu, dessen Muskeln eine Einheit
                        bilden, und sein glänzendes Fell ist einfach nur wunderschön.`n`n");
                addnav("Weiter",$fn."?op=hain&act=3");
            break;
            default:
                $spi;
                output("`QDir ist es egal, was der alte Mann sagte, auch, dass dieser Ort ein heiliger Platz der Druiden ist. Du stößt den alten Mann einfach weg und gehst an ihm vorbei. Aus den Augenwinkeln kannst du
                        noch sehen, wie der alte Mann über einen Ast stolpert und sich auf seinen Allerwertesten setzt. Doch auch
                        das interessiert dich nicht. Du gehst weiter und langsam blickst du von links nach rechts, und nun verstehst
                        du allmählich, was ein Druidenhain ist.`n`n");
                addnav("Weiter",$fn."?op=hain&act=2");
            break;
        }
    break;
    case "ausgang":
        $session['user']['specialinc']="";
        output("`QDu nimmst dir die Worte des alten Druiden zu Herzen und verlässt diesen Ort.`n`n
                `\$Durch deinen kleinen Abstecher vom Weg verlierst du einen Waldkampf, aber bekommst 5 Charmepunkte.");
        if ($session['user']['turns']>1){
            $session['user']['turns']--;
        }else{
            $session['user']['turns']=0;
        }
        $session['user']['charm']+=5;
    break;
    case "wald":
        $session['user']['specialinc']="";
        output("`QDu willst gar nicht wissen, Wer oder Was das war.... Deine Waffe schnappend, machst du dich weiter auf Monsterjagd.");
        if ($session['user']['turns']>1){
            $session['user']['turns']--;
        }else{
            $session['user']['turns']=0;
        }
    break;
    case "story":
        switch ($_GET['act']){
            case 3:
                $spi;
                output("`QDu blickst den Druiden eine Weile voll Neugierde an und fragst dich, was wohl ein Druidenhain ist. Aber
                        du hast jetzt keine Zeit zum Nachdenken, sondern musst dich entscheiden, was Du tun möchtest.`n`n
                        `qWillst Du seinem Wunsch entsprechen und umkehren, oder gewinnt die Neugier und du gehst weiter?");
                addnav("Ort untersuchen",$fn."?op=hain");
                addnav("Ich kehre um",$fn."?op=ausgang");
            break;
            case 2:
                $spi;
                output("`QSchließlich spricht der Mann dich an:`n`n
                        `&\"Keinen Schritt weiter! Hier befindet Ihr Euch im Druidenhain Dendurian! Mir, dem obersten Druiden,
                        obliegt es, Euch zum Umkehren zu bewegen, denn dies ist ein heiliger Ort, und keinem außer uns Druiden
                        ist es gestattet, sich hier aufzuhalten. Kehrt also um!\"`Q`n`n");
                addnav("Weiter",$fn."?op=story&act=3");
            break;
            default:
                $spi;
                output("`QEr sieht zwar aus wie ein Mensch, doch ist er mit Laub bedeckt, was ihm natürlich eine sehr gute Tarnung
                        verliehen hat. Nur dadurch war es für ihn möglich, sich so nah an dich heranzuschleichen.");
                addnav("Weiter",$fn."?op=story&act=2");
            break;
        }
    break;
    default:
        $spi;
        output("`QDu bist guter Dinge, als du durch den Wald gehst, deinen wachsamen Augen entgeht Nichts. Nichts ahnend gehst du
                vorsichtig durch den Wald, bis vor dir jemand auftaucht. Doch wer oder was ist das?`n`n");
        addnav("Sich diesem \"Etwas\" stellen",$fn."?op=story");
        addnav("Nein, lieber nicht",$fn."?op=wald");
    break;
}
?>

