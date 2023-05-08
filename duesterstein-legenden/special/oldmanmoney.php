
<?
// found in www
// translation and mods by gargamel @ www.rabenthal.de
if (!isset($session)) exit();

    $oldm = round($session[user][level] * e_rand(5,50));
    $gold = $session[user][gold];
    switch (e_rand(1,2)) {
        case 1: // Gold weg
        output("`nEin `^alter Mann`0 schlägt Dich mit seinem Stock nieder und raubt Dich
        aus!`0");
        if ( $oldm < $gold ) {
            output("`n`QEr erleichtert Dich um $oldm Goldstücke.`0");
            $session[user][gold]-=$oldm;
        }
        else {
            output("`n`QEr nimmt Dir Dein gesamtes Gold.`0");
            $session[user][gold] = 0;
        }
        output("`n`nDu könntest heulen! Die Rentner werden auch immer aggressiver!
        Zügig setzt Du Deinen Weg fort, schließlich musst Du ja wieder Gold verdienen...`0");
        break;
        
        case 2: // neues Gold
        output("`nEin `^alter Mann`0 humpelt auf Dich zu und steckt Dir `^$oldm Gold`0 zu!`0");
        $session[user][gold]+=$oldm;
        output("`n`nDu freust Dich natürlich, fragst Dich aber auch, ob Du wirklich schon
        so heruntergekommen aussiehst...`0");
        break;
    }
    //abschluss
    $session[user][specialinc] = "";

?>


