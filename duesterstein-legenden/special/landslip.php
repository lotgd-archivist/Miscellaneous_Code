
<?
// idea of gargamel @ www.rabenthal.de
if (!isset($session)) exit();

    output("`nDir ist schon etwas mulmig, als Du links und rechts neben Dir die
    steilen Abhänge siehst. Aber Dein Weg führt Dich nunmal genau hier durch diese
    Schlucht hindurch....`n`n`0");
    $was = e_rand(1,2);
    switch ( $was ) {
        case 1:
        output("Plötzlich löst sich ein Felsen und rumpelt mit lautem Getöse den
        Abhang hinunter. `n`0");
        switch ( e_rand(1,5) ) {
            case 1:
            output("Du schaust nach oben, aber da kommt Dir auch schon Geröll
            entgegen, das der Felsen losgerissen hat. Zum Glück kracht der Felsen
            neben Dir zu Boden und Du bekommst nur einige kleinere Blessuren ab.`n
            `5Du verlierst einige Lebenspunkte.`0");
            $session[user][hitpoints]=round( $session[user][hitpoints]*0.82 );
            break;
            case 2:
            output("Zum Glück bist Du noch ein gutes Stück davon entfernt, so dass
            Dir nichts passiert.`0");
            break;
            case 3:
            output("Du blickst Dich um und siehst den Felsen hinter Dir in einiger
            Entfernung in die Schlucht krachen. `9Gut, dass Du die Stelle schon passiert
            hattest...`0");
            break;
            case 4:
            output("Du schaust nach oben und der Brocken kommt genau auf Dich zu.
            `9Du kannst gerade noch zur Seite springen und wirst nicht verletzt.`0");
            break;
            case 5:
            output("Du blickst hinauf, aber da ist es fast schon zu spät. Der Felsen
            schiesst auf Dich zu, Du kannst nicht mehr ausweichen.`n
            `$ Obwohl Dich der Felsen nur am Bein erwischt, bis zu schwer verletzt.`0");
            $session[user][hitpoints]=1;
            break;
        }
        break;
        case 2:
        output("Du hörst ein tiefes Grollen und siehst, wie sich ein Erdrutsch löst.`n`0");
        switch ( e_rand(1,4) ) {
            case 1:
            output("Eine ganze Lawine aus Geröll und Sand stürzt in die Schlucht.
            Zum Glück ist dies ein ganzes Stück hinter Dir, so dass Dir nichts passiert.`n
            `9Mit zitterigen Knien setzt Du Deinen Weg fort.`0");
            break;
            case 2:
            output("Mit lautem Getöse donnert der Erdrutsch in die Schlucht und versperrt
            Dir den Weg. `9Glücklich, nicht verschüttet worden zu sein, kletterst Du über
            Sand und Geröll und setzt Deinen Weg fort.`0");
            break;
            case 3:
            output("Mit lautem Getöse donnert der Erdrutsch in die Schlucht und versperrt
            Dir den Weg. Du selbst steckst jetzt bis zur Hüfte in Sand und musst Dich
            erstmal befreien, bevor Du weitergehen kannst. `7Der Weg über Geröll, Felsen,
            Sand und Schlamm kostet Dich einen Waldkampf.`0");
            $session[user][turns]--;
            break;
            case 4:
            output("Wie eine Lawine kommt eine Masse aus Sand und Geröll auf Dich zu
            und verschüttet Dich fast. Du bist verletzt, aber kannst Dich gerade noch
            befreien.`n
            `$ Du verlierst zwei Waldkämpfe und viele Lebenspunkte.`0");
            $session[user][turns]-= 2;
            $session[user][hitpoints]=round( $session[user][hitpoints]*0.75 );
        }
    }
?>


