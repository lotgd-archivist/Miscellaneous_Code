
<?php
/*
* Kekse! - Das Keksskript
* written by Asuka and Zelda (THX GIRLS!)
* coded by Warchild ( warchild@gmx.org )
* 4/2004
* Version 0.9dt
* Letzte Änderungen: 
* 
* Angepasst für Saint-Omar
* by Nashyan (nashyan@habmalnefrage.de
* 30/4/2011
*/

if ($_GET[op]=="")
{
    output("`n`6Auf der Suche nach weiteren Gegnern steigt Dir plötzlich ein süßlicher Geruch in die Nase. Irritiert biegst Du die Zweige des nächsten Buschs auseinander und hast klaren Ausblick auf einen nahezu kreisrunden Platz, der mit `7schwarz-`&weißen `6Platten ausgelegt ist, auf denen dünnes `2Gras `6wuchert. `nIn der Mitte dieses Ortes steht ein quadratischer `&weißer `6Stein, von leichtem Dunst umgeben, auf dem ein Gebäckstück in Form eines `^`bKekses`b `6liegt!`nDer verlockende Duft lässt erahnen, dass er `ifrisch`i ist, was ja eigentlich gar nicht sein kann...`n");

    // Player is a reptile
    if ((int)$session[user][race] == 5)
    {
        output("Deine Echsensinne sträuben sich vor dem Geruch menschlichen Back-Wahns, doch noch kämpfst Du mit Dir.");
        output("`n`7Wirst Du den Keks nehmen und trotz des Ekels hinunterschlingen?`n`7Oder lässt Du lieber Deine schuppigen Finger davon?");
        addnav("Keks nehmen","forest.php?op=cookie");
        addnav("Den Ort verlassen","forest.php?op=nocookie");
    }
    else
    {
        output("`bNun liegt es an Dir:`b`n");
        output("`n`7Nimmst Du den Keks, da Du dem Duft einfach nicht wiederstehen kannst?!`n`7Oder lässt Du den Keks liegen, wo er ist und läufst zurück in den Wald, da Dir sofort klar ist: `n`^Kekse im Wald? Das ist nicht normal!");
        addnav("Keks nehmen","forest.php?op=cookie");
        addnav("Den Ort verlassen","forest.php?op=nocookie");
    }
    $session[user][specialinc]="cookies.php";
}
else
{
  $session[user][specialinc]="";
    if ($_GET[op]=="cookie")
    {
        if ($session[user][race] == 5) $rand = e_rand(1,6); // Echsen kriegen eher schlechte Kekse
        else $rand = e_rand(1,7);
        output("`n`6Du schnappst Dir gierig den Keks. Kauend bemerkst Du, dass...");
        switch ($rand)
            {
            case 1:
                output("`n`^`bes ein Butterkeks`b ist!`n`6Zu spät bemerkst Du jedoch die `4Dunkle Aura,`6 die den Keks umgibt. Du stellst mit Schrecken fest, dass dieser Keks entweder verflucht oder von einem `5Dämon `6besessen sein muss. `n`^Der Keks erwacht zum Leben `6und verbeißt sich in Deine Hand. Schmerzerfüllt reißt Du den Keks los und rennst blutend und panisch in den Wald zurück.`n`n");
                $lifelost = e_rand(0,$session[user][hitpoints]-5);
                if ($lifelost < 0) $lifelost = 0;
                output("`&Du verlierst ".$lifelost." Lebenspunkt(e)!");
                $session[user][hitpoints] -= $lifelost;
                break;
            case 2:
                output("`n`^`bes ein Schokokeks ist`b!`n`6Sogleich beginnst Du seltsamerweise in `^Erinnerungen an ".($session[user][sex]?"Deinen Märchenprinzen":"Deine Märchenprinzessin")." `6 zu schwelgen. Als Du bemerkst, dass Du den Keks schon aufgegessen hast und immer noch verträumt lächelst, fühlst Du Dich viel wohler in Deiner Haut. Du kehrst gut gelaunt in den Wald zurück.`n`n");
                output("`&Du erhältst einen Charmpunkt!");
                $session[user][charm]++;
                break;
            case 3:
                output("`n`^`bes ein schlichter Keks ist`b!`n`6Fröhlich schmatzend bemerkst Du, das dieser Keks eine leckere Karamell-Füllung enthält. Jedoch kannst Du Dich darüber nicht allzu lange freuen, denn die `^Füllung des Kekses beginnt plötzlich steinhart zu werden!`6 Sie verklebt Dir Deinen Mund! Panisch versuchst Du noch die Zähne auseinander zu bekommen, doch vorerst wird Dir das wohl nicht gelingen. Wutentbrannt stürmst Du zurück in den Wald!`n`n");
                
                //Geändert von Nashyan -> Anpassung auf die Spezialkräfte in Saint-Omar
                //30.04.2011
                switch($session['user']['specialty'])
                {
                    case 1:
                        output("`&Du hast keine Möglichkeit mehr, deine `4Dunklen Künste `&einzusetzen!");
                        $session['user']['darkartuses'] = 0;
                    break;
                    case 2:
                        output("`&Du hast keine Möglichkeit mehr, deine `#Mystischen Kräfte `&einzusetzen!");
                        $session['user']['magicuses'] = 0;
                    break;
                    case 3:
                        output("`&Du hast keine Möglichkeit mehr, deine `^Diebeskünste `&einzusetzen!");
                        $session['user']['thieveryuses'] = 0;
                    break;
                    case 4:
                        output("`&Du hast keine Möglichkeit mehr, deine `QFeuermagie `&einzusetzen!");
                        $session['user']['fireuses'] = 0;
                    break;
                    case 5:
                        output("`&Du hast keine Möglichkeit mehr, deine `#Schwertkünste `&einzusetzen!");
                        $session['user']['sworduses'] = 0;
                    break;
                    case 6:
                        output("`&Du hast keine Möglichkeit mehr, deine `2Naturmagie `&einzusetzen!");
                        $session['user']['natureuses'] = 0;
                    break;
                    case 7:
                        output("`&Du hast keine Möglichkeit mehr, deine `9Wassermagie `&einzusetzen!");
                        $session['user']['wateruses'] = 0;
                    break;
                    case 8:
                        output("`&Du hast keine Möglichkeit mehr, deine `7Windmagie `&einzusetzen!");
                        $session['user']['winduses'] = 0;
                    break;
                    case 9:
                        output("`&Du hast keine Möglichkeit mehr, deine `5Chaosmagie `&einzusetzen!");
                        $session['user']['chaosuses'] = 0;
                    break;
                    default:
                        output("Wie es aussieht hast du spezielle Kräfte entwickelt, die sich dem Verständnis dieser Welt entziehen! Bitte schreibe eine Anfrage,
                                damit sich jemand um diesen Fehler kümmern kann.");
                    break;
                }
            
                break;
            case 4:
                output("`n`^`b es ein Keks mit Orangenfüllung ist`b!`n`6Der eklige Geschmack ist durchdringend und Du spuckst sofort alles aus. Die Füllung muss wohl schon schlecht gewesen sein. `^Du fühlst Dich ziemlich schlecht `6und musst Dich erst einmal ein wenig ausruhen, bevor Du weiterziehen kannst.`n`n");
                output("`&Du verlierst einen Waldkampf!");
                if ($session[user][turns] > 0)
                    $session[user][turns]--;
                break;
            case 5:
                output("`n`^`bes ein Goldkeks ist`b!`n`6Wie schön wäre es doch, wenn der Keks echtes Gold wäre! Plötzlich springt ein kleiner Kobold aus dem Gebüsch, klaut Dir den angebissenen Keks aus der Hand und rennt mit meckerndem Lachen davon. Wütend willst Du dem Dieb hinterher rennen, bemerkst jedoch ein `^Säckchen voller Gold`6 vor deinen Füßen liegen, welches der Kobold wohl verloren haben muss. Zufrieden nimmst Du das Säckchen Gold als Entschädigung an Dich und verlässt die Lichtung wieder in Richtung Wald.`n`n");
                $goldamount = e_rand(10,$session[user][level] * 10 + 1);
                output("`&Du erhältst $goldamount Gold!");
                $session[user][gold] += $goldamount;
                //debuglog("got $goldamount gold from the cookies");
                break;
            case 6:
                output("`n`^`bdu den Geschmack des Asuze Kekses auf der Zunge hast`b!`n`6Du kaust laut schmatzend und versuchst zu schlucken, doch Du bemerkst, wie immer mehr Krümel sich in Deinem Hals ansammeln. Verzweifelt nach Luft schnappend und keuchend fällt Dir der Rest des Kekses aus der Hand, während Dir allmählich die Sinne schwinden.`n`n");
                output("`&Du stirbst den Krümeltod! Du verlierst all Dein Gold und 5% Deiner Erfahrung!");
                $session[user][alive]=false;
                $session[user][hitpoints]=0;
                $session[user][gold] = 0;
                $session[user][experience]=$session[user][experience]*0.95;
                addnav("Tägliche News","news.php");
                addnews("`&".$session[user][name]."`0 `jstarb den `9Krümeltod`j!`0");
                break;
            case 7:
                output("`n`^`bes ein Gute-Laune-Keks ist`b!`n`6Du stellst fest, dass dies der `^leckerste Keks `6 aller Zeiten ist. Dieser umwerfende Geschmack hebt mächtig Deine Laune; Du bist bereit ein paar Monstern mehr den Garaus zu machen.`n`n");
                $fightamount = e_rand(1,3);
                if ($fightamount == 1)
                    output("`&Du erhältst $fightamount Waldkampf dazu!");
                else
                    output("`&Du erhältst $fightamount Waldkämpfe dazu!");
                $session[user][turns] += $fightamount;
                break;
            }
    }
    else
    {
      output("`n`6Du lässt die Zweige wieder leise zurückfallen und schleichst von dannen. `nIst doch nur Kinderkram, oder? Jopp, definitv!");
    }
}
?>

