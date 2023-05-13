
<?
// idea of gargamel @ www.rabenthal.de
/* modifications by  bibir (logd_bibir@email.de)
 *               and Chaosmaker (webmaster@chaosonline.de)
 *               for http://logd.chaosonline.de
 *
 * details:
 *  (22.08.04) $x[y][z] => $x['y']['z']
 *             Charmpoints and donationpoints to gain
 *
 */
if (!isset($session)) exit();

output("`nDir ist schon etwas mulmig, als du links und rechts neben dir die
steilen Abhänge siehst. Aber dein Weg führt dich nunmal genau hier durch diese
Schlucht hindurch...`n`n`0");
$was = e_rand(1,2);
switch ( $was ) {
    case 1:
        output("Plötzlich löst sich ein Felsen und rumpelt mit lautem Getöse den
        Abhang hinunter.`n`0");
    switch ( e_rand(1,5) ) {
        case 1:
            output("Du schaust nach oben, aber da kommt dir auch schon Geröll
            entgegen, das der Felsen losgerissen hat. Zum Glück kracht der Felsen
            neben dir zu Boden und du bekommst nur einige kleinere Blessuren ab.`n
            `5Du verlierst einige Lebenspunkte!`0");
            $session['user']['hitpoints']=round( $session['user']['hitpoints']*0.82 );
        break;
        case 2:
            output("Zum Glück bist du noch ein gutes Stück davon entfernt, so dass
            dir nichts passiert.`0");
        break;
        case 3:
            output("Du blickst dich um und siehst den Felsen hinter dir in einiger
            Entfernung in die Schlucht krachen. `9Gut, dass du die Stelle schon passiert
            hattest...`0");
        break;
        case 4:
            output("Du schaust nach oben und der Brocken kommt genau auf dich zu.
            `9Du kannst gerade noch zur Seite springen und wirst nicht verletzt.`0");
            output("Zuhause hast du viel zu erzählen.`nDu bekommst einen Charmepunkt!");
            $session['user']['charm']++;
        break;
        case 5:
            output("Du blickst hinauf, aber da ist es fast schon zu spät. Der Felsen
            schießt auf dich zu, du kannst nicht mehr ausweichen.`n
            `$ Obwohl dich der Felsen nur am Bein erwischt, bis zu schwer verletzt!`0");
            $session['user']['hitpoints']=1;
        break;
    }
    break;
    case 2:
        output("Du hörst ein tiefes Grollen und siehst, wie sich ein Erdrutsch löst.`n`0");
    switch ( e_rand(1,4) ) {
        case 1:
            output("Eine ganze Lawine aus Geröll und Sand stürzt in die Schlucht.
            Zum Glück ist dies ein ganzes Stück hinter dir, so dass dir nichts passiert.`n
            `9Mit zitterigen Knien setzt du deinen Weg fort.`0");
        break;
        case 2:
            output("Mit lautem Getöse donnert der Erdrutsch in die Schlucht und versperrt
            dir den Weg. `9Glücklich, nicht verschüttet worden zu sein, kletterst du über
            Sand und Geröll und setzt deinen Weg fort.`0");
            output("Zuhause hast du viel zu erzählen.`nDu bekommst einen Charmepunkt!");
            $session['user']['charm']++;
        break;
        case 3:
            output("Mit lautem Getöse donnert der Erdrutsch in die Schlucht und versperrt
            dir den Weg. Du selbst steckst jetzt bis zur Hüfte in Sand und musst dich
            erstmal befreien, bevor du weitergehen kannst. `7Der Weg über Geröll, Felsen,
            Sand und Schlamm kostet dich einen Waldkampf!`0");
            $session['user']['turns']--;
        break;
        case 4:
            output("Wie eine Lawine kommt eine Masse aus Sand und Geröll auf dich zu
            und verschüttet dich fast. Du bist verletzt, aber kannst dich gerade noch
            befreien.`n
            `$ Du verlierst zwei Waldkämpfe und viele Lebenspunkte.`0");
            $session['user']['turns']-= 2;
            $session['user']['hitpoints']=round( $session['user']['hitpoints']*0.25 );
        break;
    }
    break;
}

