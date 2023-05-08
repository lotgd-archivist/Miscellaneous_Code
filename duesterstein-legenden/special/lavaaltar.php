
<?
/*
/ pietre.php - Magic Stones V0.2.1
/ Originally by Excalibur (www.ogsi.it)
/ English cleanup by Talisman (dragonprime.cawsquad.net)
/ Original concept from Aris (www.ogsi.it)
/ May 2004
/
/ translation and adjustments
/ - pietra -> stones
/ - changed storyline to the lava altar
/ - new stones with VARIOUS MAGIC throughout the whole game
/ by gargamel @ www.rabenthal.de
*/

/*
to do:  - table stones by user löschen berücksichtigen!
*/


if (!isset($session)) exit();

$numstones = getsetting("magischesteine",0);


if ($HTTP_GET_VARS[op]==""){
    output("<font size='+1'>`c`b`!Der Lava-Altar`b`c`n</font>",true);
    if ($session['user']['stone']==0){
        output("`n`@Auf Deinem Weg auf der Suche nach Abenteuer findest Du hinter
        einer Baumgruppe den legendären Lava-Altar.`n
        Der auffällige Findling aus schwarzem Basalt zieht Dich in seinen Bann.
        Du spürst die Hitze, die in der Nähe aus einer Erdspalte dringt. Und Du
        erinnerst Dich an die Legende, die Du einst irgendwo gehört hast...`n`n
        \"Wenn der Lava-Altar von "
        .($session[user][sex]?"einer Würdigen ":"einem Würdigen ").
        "berührt wird, öffnet sich die Erde und spuckt einen magischen Stein aus,
        der seine Kräfte mit Dir teilt. Jene hat vielen geholfen, aber einigen
        unglücklichen Besuchern auch eine schwere Bürde aufgeladen. Was auch immer
        der Altar für Dich bereit hält, Du kannst die Kraft nicht ablegen - sie
        muss Dir genommen werden. Wenn Deine Würde in den Augen der Götter schwindet
        und ein Anderer den Altar berührt.`n
        Die Kräfte wirken sofort, oder vom nächsten Tag an.\"`n
        So hast Du es einst vernommen...`n`n
        Du stehst etwas ratlos vor dem schwarzen Findling. Sollst Du es wagen?
        Bist Du würdig?`0");
        //abschluss intro
        addnav("Altar berühren","forest.php?op=touch");
        addnav("einfach weitergehen","forest.php?op=cont");
        $session[user][specialinc] = "lavaaltar.php";
    }
    else {
        $tmp=$session['user']['stone'];
        output("`n`@Auf Deinem Weg triffst Du wieder auf die Baumgruppe hinter der
        sich der Lava-Altar befindet. `n
        Weil Du den $stone[$tmp] besitzt, regenerierst Du
        vollständig. Deine Heilung dauert jedoch eine Weile und Du verlierst einen
        Waldkampf.`0");
        $session[user][turns]-=1;
        if ($session[user][hitpoints]<$session[user][maxhitpoints])
           $session[user][hitpoints]=$session[user][maxhitpoints];
        $session[user][specialinc]="";
    }
}
else if ($HTTP_GET_VARS[op]=="cont"){   // einfach weitergehen
    output("`n`5Du verlässt den schwarzen Findling. Die Sache ist Dir irgendwie
    nicht geheuer...`0");
    $session[user][specialinc]="";
}
else if ($HTTP_GET_VARS[op]=="touch"){
    $chance = e_rand(1,100);
    if ( $chance < 70 ) {
        output("`nEtwas zaghaft legst Du Deine Hand an den schwarzen Findling.
        Sorgsam beobachtest Du die Umgebung. Nichts passiert...`n
        Auch der Findling liegt da wie zuvor.`n`n
        Du musst einsehen, dass Du nicht würdig bist und gehst zurück in den
        Wald.`0");
    }
    else {
        output("`nDu legst Deine Hand an den schwarzen Findling und wartest...`n
        Schon bald fängt Deine Hand an zu kribbeln und Du spürst die Hitze weiter
        ansteigen. Sollte sich etwa die Erde öffnen?`n`n`0");
        $stones = e_rand(1,$numstones);
        $sql="SELECT stone,owner,class,description FROM stones WHERE stone = $stones";
        $result = db_query($sql) or die(db_error(LINK));
        if ( db_num_rows($result) == 0 ) {
            output("`nEtwas zaghaft legst Du Deine Hand an den schwarzen Findling.
            Sorgsam beobachtest Du die Umgebung. Nichts passiert...`n
            Auch der Findling liegt da wie zuvor.`n`n
            Du musst einsehen, dass Du nicht würdig bist und gehst zurück in den
            Wald.`0");
        }
        else {
            $row = db_fetch_assoc($result);
            $stein = $row['stone'];
            $bisher = $row['owner'];
            $welcher = $stone[$stein];
            if ( $bisher == 0 ) {  // stein zuteilen
                output("Mit tiefem Donner schiesst Dampf aus der nahegelegenen Erdspalte.
                Durch den Druck wird ein kleiner Stein mit herausgeschleudert und landet
                direkt vor Deinen Füssen. `bDu hast einen magischen Stein erhalten!`b `n`n`0");
                switch ( $row['class'] ) {
                    case 0: //positiv
                    output("Zu Deiner grossen Freude erkennst Du, es ist: ".$welcher."`n`n
                    ".$row['description']."`n`0");
                    break;
                    case 1: //negativ
                    output("Zu Deiner Bestürzung erkennst Du, es ist: ".$welcher."`n`n
                    ".$row['description']."`n`0");
                    break;
                    case 2: //neutral
                    output("Du erkennst, es ist: ".$welcher."`n`n
                    ".$row['description']."`n`0");
                    break;
                }
                $session['user']['stone']=$stein;
                $id=$session[user][acctid];
                $sql="UPDATE stones set owner = '".$id."'
                      WHERE stone = '".$stein."'";
                db_query($sql);
                addnews($session[user][name]." `Qhat ".$welcher." erhalten!");
            }
            else {  // stein wegnehmen und zuteilen
                $sql="SELECT name FROM accounts WHERE acctid = '".$bisher."'";
                $result = db_query($sql) or die(db_error(LINK));
                if ( db_num_rows($result) == 0 ) {
                    $bishername = "Unbekannt";
                }
                else {
                    $row2 = db_fetch_assoc($result);
                    $bishername = $row2['name'];
                }
                output("Mit tiefem Donner schiesst Dampf aus der nahegelegenen Erdspalte.
                Durch den Druck wird ein kleiner Stein mit herausgeschleudert und landet
                direkt vor Deinen Füssen. `bDu hast einen magischen Stein erhalten!`b `n`n
                Dazu hörst Du eine Stimme sagen: $bishername ist in Ungnade gefallen,
                deshalb bekommst Du den Stein! Du bist würdiger!`n`n`0");
                switch ( $row['class'] ) {
                    case 0: //positiv
                    output("Zu Deiner grossen Freude erkennst Du, es ist: ".$welcher."`n`n
                    ".$row['description']."`n`0");
                    break;
                    case 1: //negativ
                    output("Zu Deiner Bestürzung erkennst Du, es ist: ".$welcher."`n`n
                    ".$row['description']."`n`0");
                    break;
                    case 2: //neutral
                    output("Du erkennst, es ist: ".$welcher."`n`n
                    ".$row['description']."`n`0");
                    break;
                }
                // stein altem besitzer nehmen
                $sql2="UPDATE accounts set stone = 0 WHERE acctid = '".$bisher."'";
                db_query($sql2);
                // und neu zuteilen
                $session['user']['stone']=$stein;
                $id=$session[user][acctid];
                $sql="UPDATE stones set owner = '".$id."'
                      WHERE stone = '".$stein."'";
                db_query($sql);
                addnews($session[user][name]." `Qhat ".$welcher." erhalten!");
                // nur zur Sicherheit: stein altem besitzer nehmen
                // zweites mal....
                $sql2="UPDATE accounts set stone = 0 WHERE acctid = '".$bisher."'";
                db_query($sql2);

                //output("$bisher");
                $mailmessage = "`@{$session['user']['name']} `@hat den Lava-Altar gefunden.
                Die Götter haben entschieden, Deinen Stein an `@{$session['user']['name']}
                zu geben, weil Du in Ungnade gefallen bist!";
                systemmail($bisher,"`2Dein Stein wurde weitergegeben!",$mailmessage);

                // nur zur Sicherheit: stein altem besitzer nehmen
                // drittes mal....
                $sql3="UPDATE accounts set stone = 0 WHERE acctid = '".$bisher."'";
                db_query($sql3);

            }
            // # hier evtl. switch auf class ?
        }
    }
    $session[user][specialinc]="";
}
?>


