
ï»¿<?php

/*

* Kekse! - Das Keksskript

* written by Asuka and Zelda (THX GIRLS!)

* coded by Warchild ( warchild@gmx.org )

* 4/2004

* Version 0.9dt

* Letzte Ã„nderungen:

*

*/



if ($HTTP_GET_VARS[op]=="")

{

    output("`n`6Auf der Suche nach weiteren Gegnern steigt Dir plÃ¶tzlich ein sÃ¼ÃŸlicher Geruch in die Nase. Irritiert biegst Du die Zweige des nÃ¤chsten Buschs auseinander und hast klaren Ausblick auf einen nahezu kreisrunden Platz, der mit `7schwarz-`&weiÃŸen `6Platten ausgelegt ist, auf denen dÃ¼nnes `2Gras `6wuchert. `nIn der Mitte dieses Ortes steht ein quadratischer `&weiÃŸer `6Stein, von leichtem Dunst umgeben, auf dem ein GebÃ¤ckstÃ¼ck in Form eines `^`bKekses`b `6liegt!`nDer verlockende Duft lÃ¤sst erahnen, dass er `ifrisch`i ist, was ja eigentlich gar nicht sein kann...`n");



    // Player is a reptile

    if ((int)$session[user][race] == 5)

    {

        output("Deine Echsensinne strÃ¤uben sich vor dem Geruch menschlichen Back-Wahns doch noch kÃ¤mpfst Du mit Dir.");

        output("`n`7Wirst Du den Keks nehmen und trotz des Ekels hinunterschlingen?`n`7Oder lÃ¤sst Du lieber Deine schuppigen Finger davon?");

        addnav("Keks nehmen","forest.php?op=cookie");

        addnav("Den Ort verlassen","forest.php?op=nocookie");

    }

    else

    {

        output("`bNun liegt es an Dir:`b`n");

        output("`n`7Nimmst Du den Keks, da Du dem Duft einfach nicht wiederstehen kannst?!`n`7Oder lÃ¤sst Du den Keks liegen wo er ist und lÃ¤ufst zurÃ¼ck in den Wald, da Dir sofort klar ist: `n`^Kekse im Wald? Das ist nicht normal!");

        addnav("Keks nehmen","forest.php?op=cookie");

        addnav("Den Ort verlassen","forest.php?op=nocookie");

    }

    $session[user][specialinc]="cookies.php";

}

else

{

  $session[user][specialinc]="";

    if ($HTTP_GET_VARS[op]=="cookie")

    {

        if ($session[user][race] == 5) $rand = e_rand(1,6); // Echsen kriegen eher schlechte Kekse

        else $rand = e_rand(1,7);

        output("`n`6Du schnappst Dir gierig den Keks. Kauend bemerkst Du...");

        switch ($rand)

            {

            case 1:

                output("`n`^`bes ist ein Butterkeks`b!`n`6Zu spÃ¤t bemerkst Du jedoch die `4Dunkle Aura,`6 die den Keks umgibt. Du stellst mit Schrecken fest, dass dieser Keks entweder verflucht oder von einem `5DÃ¤mon `6besessen sein muss. `n`^Der Keks erwacht zum Leben `6und verbeiÃŸt sich in Deine Hand. SchmerzerfÃ¼llt reiÃŸt Du den Keks los und rennst blutend und panisch in den Wald zurÃ¼ck.`n`n");

                $lifelost = e_rand(0,$session[user][hitpoints]-5);

                if ($lifelost < 0) $lifelost = 0;

                output("`&Du verlierst ".$lifelost." Lebenspunkt(e)!");

                $session[user][hitpoints] -= $lifelost;

                break;

            case 2:

                output("`n`^`bes ist ein Schokokeks`b!`n`6Sogleich beginnst Du seltsamerweise in `^Erinnerungen an ".($session[user][sex]?"Deinen MÃ¤rchenprinzen":"Deine MÃ¤rchenprinzessin")." `6 zu schwelgen. Als Du bemerkst, dass Du den Keks schon aufgegessen hast und immer noch vertrÃ¤umt lÃ¤chelst, fÃ¼hlst Du Dich viel wohler in Deiner Haut. Du kehrst gut gelaunt in den Wald zurÃ¼ck.`n`n");

                output("`&Du erhÃ¤ltst einen Charmpunkt!");

                $session[user][charm]++;

                break;

            case 3:

                output("`n`^`bes ist ein schlichter Keks`b!`n`6FrÃ¶hlich schmatzend bemerkst Du, das dieser Keks eine leckere Karamell - FÃ¼llung enthÃ¤lt. Jedoch kannst Du Dich darÃ¼ber nicht all zu lange freuen, denn die `^FÃ¼llung des Kekses beginnt plÃ¶tzlich steinhart zu werden!`6 Sie verklebt Dir Deinen Mund! Panisch versuchst Du noch die ZÃ¤hne auseinander zu bekommen, doch vorerst wird Dir das wohl nicht gelingen. Wutentbrannt stÃ¼rmst Du zurÃ¼ck in den Wald!`n`n");

                if ($session['user']['specialty'] == 1)

                {

                    output("`&Du hast keine MÃ¶glichkeit mehr, Deine `4Dunklen KÃ¼nste `&einzusetzen!");

                    $session['user']['darkartuses'] = 0;

                }

                else if ($session['user']['specialty'] == 2)

                {

                    output("`&Du hast keine MÃ¶glichkeit mehr, Deine `5Mystischen KrÃ¤fte `&einzusetzen!");

                    $session['user']['magicuses'] = 0;

                }

                else if ($session['user']['specialty'] == 3)

                {

                    output("`&Du hast keine MÃ¶glichkeit mehr, Deine `6DiebeskÃ¼nste `&einzusetzen!");

                    $session['user']['thieveryuses'] = 0;

                }

                else if ($session['user']['specialty'] == 4)

                {

                    output("`&Du hast keine MÃ¶glichkeit mehr, Deine `QKampfkunst `&einzusetzen!");

                    $session['user']['warriorsartuses'] = 0;

                }

                else if ($session['user']['specialty'] == 5)

                {

                    output("`&Du hast keine MÃ¶glichkeit mehr, Deine `9Spirituellen KrÃ¤fte `&einzusetzen!");

                    $session['user']['priestsartuses'] = 0;

                }

                else if ($session['user']['specialty'] == 6)

                {

                    output("`&Du hast keine MÃ¶glichkeit mehr, Deine `2Naturkraft `&einzusetzen!");

                    $session['user']['rangersartuses'] = 0;

                }

                break;

            case 4:

                output("`n`^`beinen Keks mit OrangenfÃ¼llung`b!`n`6Der ekelige Geschmack ist durchdringend und Du spuckst sofort alles aus. Die FÃ¼llung muss wohl schon schlecht gewesen sein. `^Du fÃ¼hlst Dich ziemlich schlecht `6und musst Dich erst einmal ein wenig ausruhen, bevor Du weiterziehen kannst.`n`n");

                output("`&Du verlierst einen Waldkampf!");

                if ($session[user][turns] > 0)

                    $session[user][turns]--;

                break;

            case 5:

                output("`n`^`bes ist ein Goldkeks`b!`n`6Wie schÃ¶n wÃ¤re es doch, wenn der Keks echtes Gold wÃ¤re! PlÃ¶tzlich springt ein kleiner Kobold aus dem GebÃ¼sch, klaut Dir den angebissenen Keks aus der Hand und rennt mit meckerndem Lachen davon. WÃ¼tend willst Du dem Dieb hinterher rennen, bemerkst jedoch ein `^SÃ¤ckchen voller Gold`6 vor deinen FÃ¼ÃŸen liegen, welches der Kobold wohl verloren haben muss. Zufrieden nimmst Du das SÃ¤ckchen Gold als EntschÃ¤digung an Dich und verlÃ¤sst die Lichtung wieder in Richtung Wald.`n`n");

                $goldamount = e_rand(10,$session[user][level] * 10 + 1);

                output("`&Du erhÃ¤ltst $goldamount Gold!");

                $session[user][gold] += $goldamount;

                //debuglog("got $goldamount gold from the cookies");

                break;

            case 6:

                output("`n`^`bden Geschmack des Asuze Kekses`b!`n`6Du kaust laut schmatzend und versuchst zu schlucken, doch Du bemerkst, wie immer mehr KrÃ¼mel sich in Deinem Hals ansammeln. Verzweifelt nach Luft schnappend und keuchend fÃ¤llt Dir der Rest des Kekses aus der Hand, wÃ¤hrend Dir allmÃ¤hlich die Sinne schwinden.`n`n");

                output("`&Du stirbst den KrÃ¼meltod! Du verlierst all Dein Gold und 5% Deiner Erfahrung!");

                $session[user][alive]=0;

                $session[user][hitpoints]=0;

                $session[user][gold] = 0;

                $session[user][experience]=$session[user][experience]*0.95;

                addnav("TÃ¤gliche News","news.php");

                addnews("`&".$session[user][name]."`0 starb den `^KrÃ¼meltod`0!");

                break;

            case 7:

                output("`n`^`bes ist ein Gute-Laune-Keks`b!`n`6Du stellst fest, das dies der `^leckerste Keks `6 aller Zeiten ist. Dieser umwerfende Geschmack hebt mÃ¤chtig Deine Laune; Du bist bereit ein paar Monstern mehr den Gar aus zu machen.`n`n");

                $fightamount = e_rand(1,3);

                if ($fightamount == 1)

                    output("`&Du erhÃ¤ltst $fightamount Waldkampf dazu!");

                else

                    output("`&Du erhÃ¤ltst $fightamount WaldkÃ¤mpfe dazu!");

                $session[user][turns] += $fightamount;

                break;

            }

    }

    else

    {

      output("`n`6Du lÃ¤sst die Zweige wieder leise zurÃ¼ckfallen und schleichst von dannen. `nIst doch nur Kinderkram, oder? Jopp, definitv!");

    }

}

?>

