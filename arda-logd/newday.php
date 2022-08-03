<?php

// 24072004
require_once "common.php";

/**
 * *************
 * * SETTINGS **
 * *************
 */
$u = $session ['user'];
$oldturns = $session ['user'] ['turns'];
$session ['user'] ['turns'] = getsetting ( "turns", 10 );
$maxinterest = (( float ) getsetting ( "maxinterest", 10 ) / 100) + 1; // 1.1;
$mininterest = (( float ) getsetting ( "mininterest", 1 ) / 100) + 1; // 1.1;
                                                             // $mininterest = 1.01;
$dailypvpfights = getsetting ( "pvpday", 3 );

if ($_GET ['resurrection'] == "true") {
    $resline = "&resurrection=true";
} else if ($_GET ['resurrection'] == "egg") {
    $resline = "&resurrection=egg";
} else {
    $resline = "";
}

// $resline = $_GET['resurrection']=="true" ? "&resurrection=true" : "" ;
/**
 * ****************
 * * End Settings **
 * ****************
 */
$session ['user'] ['deadtreepick'] = 0;
if ($session ['user'] ['prison'])
    $session ['user'] ['prisondays'] --;
$session ['user'] ['loeschen'] = 0;
$session ['user'] ['aufbauii'] = 0;
if (count ( $session ['user'] ['dragonpoints'] ) < $session ['user'] ['dragonkills'] && $_GET ['dk'] != "") {
    array_push ( $session ['user'] ['dragonpoints'], $_GET [dk] );
    switch ($_GET ['dk']) {
        case "hp" :
            $session ['user'] ['maxhitpoints'] += 5;
            break;
        case "at" :
            $session ['user'] ['attack'] ++;
            break;
        case "de" :
            $session ['user'] ['defence'] ++;
            break;
    }
}
if (count ( $session ['user'] ['dragonpoints'] ) < $session ['user'] ['dragonkills'] && $_GET ['dk'] != "ignore") {
    page_header ( "Phoenixpunkte" );
    addnav ( "Max Lebenspunkte +5", "newday.php?dk=hp$resline" );
    addnav ( "Waldkämpfe +1", "newday.php?dk=ff$resline" );
    addnav ( "Angriff + 1", "newday.php?dk=at$resline" );
    addnav ( "Verteidigung + 1", "newday.php?dk=de$resline" );
    // addnav("Ignore (Dragon Points are bugged atm)","newday.php?dk=ignore$resline");
    output ( "`@Du hast noch `^" . ($session ['user'] ['dragonkills'] - count ( $session ['user'] ['dragonpoints'] )) . "`@  Phoenixpunkte übrig. Wie willst du sie einsetzen?`n`n" );
    output ( "Du bekommst 1 Phoenixpunkt pro getötetem Phoenix. Die Änderungen der Eigenschaften durch Phoenixpunkte sind permanent." );
} else if ($u ['race'] == '' || $u ['race'] == 'Unknown' || is_numeric ( $u ['race'] )) {

    switch ($_GET ['setRace']) {

        case 0 :

            page_header ( 'Ein wenig über deine Vorgeschichte' );

            output ( 'Wähle bitte deine Herkunft bzw. Rasse.`n`n' );

            $d = dir ( 'Races' );

            while ( false !== ($e = $d->read ()) ) {

                if (strpos ( $e, '.race' ) === false)
                    continue;
                if (substr ( $e, 0, 1 ) == '.')
                    continue;

                $mySpliter = explode ( '_', $e );

                if ($mySpliter [0]) {

                    $i ++;
                    $myData [$i] = unserialize ( file_get_contents ( 'Races/' . $e ) );
                    $myFiles [$i] = substr ( $e, 2, - 5 );
                }
            }

            addnav ( 'Rassen' );

            if (count ( $myData ) > 0) {

                $i = 0;
                while ( $i < count ( $myData ) ) {

                    $i ++;

                    if ($u ['dragonkills'] >= $myData [$i] [0] ['Dk']) {

                        if (! getsetting ( 'colorrace_newday', false )) {

                            addnav ( $myData [$i] ['Name'], 'newday.php?setRace=1&file=' . $myFiles [$i] . $resline );
                        } else {

                            addnav ( $myData [$i] ['cName'], 'newday.php?setRace=1&file=' . $myFiles [$i] . $resline );
                        }

                        addnav ( '', 'newday.php?setRace=1&file=' . $myFiles [$i] . $resline );

                        $myExplode = explode ( '<LINK>', $myData [$i] ['Desc'] );

                        output ( '<a href="newday.php?setRace=1&file=' . $myFiles [$i] . $resline . '">' . $myExplode [0] . '</a>' . $myExplode [1] . '`n`n', true );
                    }
                }
            } else {

                addnav ( 'Undefined', 'newday.php?setRace=1&file=undefined' . $resline );
                output ( 'Leider sind derzeit keine Rassen verfügbar. Wende Dich bitte an einen Systemadministratoren!' );
            }

            addnav ( 'Aktualisieren', 'newday.php' );

            break;

        case 1 :

            if ($_GET ['file'] == 'undefined') {

                page_header ( 'Keine Rassen verfügbar!' );

                output ( 'Leider sind keine Rassen verfügbar. - Wende Dich bitte an einen Systemadministrator.' );
                $session ['user'] ['race'] = $_GET ['file'];
            } else {

                $race = unserialize ( file_get_contents ( 'Races/1_' . $_GET ['file'] . '.race' ) );

                page_header ( 'Die ' . $race ['Plural'] );

                output ( $race ['Final_Desc'], true );
                $session ['user'] ['race'] = $race ['Name'];
                $session ['user'] ['crace'] = $race ['cName'];

                if ($race ['Bonus_Feld'] !== FALSE && is_numeric ( $race ['Bonus'] )) {

                    $session ['user'] [$race ['Bonus_Feld']] += $race ['Bonus'];
                }
            }

            if ($session ['user'] ['dragonkills'] == 0 && $session ['user'] ['level'] = 1) {

                addnews ( '`#' . $session [user] [name] . ' `#hat unsere Welt betreten. Willkommen!' );
            }

            addnav ( 'Aktionen' );
            addnav ( 'Weiter', 'newday.php?continue=1' . $resline );

            break;
    }
} else if (( int ) $session ['user'] ['specialty'] == 0) {
    if ($HTTP_GET_VARS ['setspecialty'] === NULL) {
        addnav ( "", "newday.php?setspecialty=1$resline" );
        addnav ( "", "newday.php?setspecialty=2$resline" );
        addnav ( "", "newday.php?setspecialty=3$resline" );
        page_header ( "Ein wenig über deine Vorgeschichte" );

        output ( "Du erinnerst dich, dass du als Kind:`n`n" );
        output ( "<a href='newday.php?setspecialty=1$resline'>viele Kreaturen des Waldes getötet hast (`\$Dunkle Künste`0)</a>`n", true );
        output ( "<a href='newday.php?setspecialty=2$resline'>mit mystischen Kräften experimentiert hast (`%Mystische Kräfte`0)</a>`n", true );
        output ( "<a href='newday.php?setspecialty=3$resline'>von den Reichen gestohlen und es dir selbst gegeben hast (`^Diebeskunst`0)</a>`n", true );
        addnav ( "`\$Dunkle Künste", "newday.php?setspecialty=1$resline" );
        addnav ( "`%Mystische Kräfte", "newday.php?setspecialty=2$resline" );
        addnav ( "`^Diebeskünste", "newday.php?setspecialty=3$resline" );
    } else {
        addnav ( "Weiter", "newday.php?continue=1$resline" );
        switch ($HTTP_GET_VARS ['setspecialty']) {
            case 1 :
                page_header ( "Dunkle Künste" );
                output ( "`5Du erinnerst dich, dass du damit aufgewachsen bist, viele kleine Waldkreaturen zu töten, weil du davon überzeugt warst, sie haben sich gegen dich verschworen. " );
                output ( "Deine Eltern haben dir einen idiotischen Zweig gekauft, weil sie besorgt darüber waren, dass du die Kreaturen des Waldes mit bloßen Händen töten musst. " );
                output ( "Noch vor deinem Teenageralter hast du damit begonnen, finstere Rituale mit und an den Kreaturen durchzuführen, wobei du am Ende oft tagelang im Wald verschwunden bist. " );
                output ( "Niemand außer dir wusste damals wirklich, was die Ursache für die seltsamen Geräusche aus dem Wald war..." );
                break;
            case 2 :
                page_header ( "Mystische Kräfte" );
                output ( "`3Du hast schon als Kind gewusst, dass diese Welt mehr als das Physische bietet, woran du herumspielen konntest. " );
                output ( "Du hast erkannt, dass du mit etwas Training deinen Geist selbst in eine Waffe verwandeln kannst. " );
                output ( "Mit der Zeit hast du gelernt, die Gedanken kleiner Kreaturen zu kontrollieren und ihnen deinen Willen aufzuzwingen. " );
                output ( "Du bist auch auf die mystische Kraft namens Mana gestossen, die du in die Form von Feuer, Wasser, Eis, Erde, Wind bringen und sogar als Waffe gegen deine Feinde einsetzen kannst." );
                break;
            case 3 :
                page_header ( "Diebeskünste" );
                output ( "`6Du hast schon sehr früh bemerkt, dass ein gewöhnlicher Rempler im Gedränge dir das Gold eines vom Glück bevorzugteren Menschen einbringen kann. " );
                output ( "Außerdem hast du entdeckt, dass der Rücken deiner Feinde anfälliger gegen kleine Klingen ist, als deren Vorderseite gegen mächtige Waffen." );
                break;
        }
        $session ['user'] ['specialty'] = $HTTP_GET_VARS ['setspecialty'];
    }
} // Anfang Klasse auswählen
else if (( int ) $session ['user'] ['admin'] == 0) {
    {
        page_header ( "Wähle eine Klasse" );
        output("`\$Du kannst hier eine Auswahl treffen, aber diese ist nicht bindend. Die Charakter-Klasse wird automatisch so gesetzt, daß Du sowohl in den Wald als auch RP machen kannst und bei beiden gleich viel Belohnung bekommt.`n");
        output("`\$Aber Achtung! Es ist egal, was hier ausgewählt wird, man wird automatisch so eingestellt, daß man alles kann. Also sowohl RPG als auch Waldkämpfe.");
        if ($_GET ['setadmin'] != "") {
            $session ['user'] ['admin'] = ( int ) ($_GET ['setadmin']);
            switch ($_GET ['setadmin']) {
                case "1" :
                    output ( "`i`b`c`9RP`3G-`#C`3ha`9ra`0`i`b`c`n" );
                    output ( "Diese Chara's haben keinen Wald und sind ausschlieslich am RPG interessiert.`0" );
                    break;
                case "2" :
                    output ( "`i`b`cMi`4x-`\$C`4hara`0`i`b`c`n" );
                    output ( "Diese Chara's können in den Wald kämpfen und sind auch am RPG interessiert, doch haben diese gewisse Einschränkungen.`0" );
                    break;
                case "3" :
                    output ( "`i`b`cLe`qve`2l-C`ghara`0`i`b`c`n" );
                    output ( "Diese Charas Leveln ausschlieslich und sind überhaupt nicht am RPG interessiert." );
                    break;
                case "4" :
                    output ( "`i`b`c`\$Spezielle-Chara`0`i`b`c`n" );
                    output ( "Diese Charaktere sind nur von Admins vergebbar, da sie RPG und Level ohne Einschränkungen nutzen können und somit anderen gegenüber einen Vorteil haben." );
                    break;
            }
        } else {
            output ( "Was ist deine Klasse?`n`n" );
            output ( "<a href='newday.php?setadmin=1$resline'>`9RP`3G-`#C`3ha`9ra`0 sind Accounts die nur für das RPG da sind und nicht Leveln können.</a>`n`n", true );
            output ( "<a href='newday.php?setadmin=2$resline'>Mi`4x-`\$C`4hara`0 sind Accounts die Leveln und RPG'n können, aber dafür eingeschränkt.</a>`n`n", true );
            output ( "<a href='newday.php?setadmin=3$resline'>Le`gve`2l-C`ghara`0 sind Accounts die nur Leveln und kein RPG machen.</a>`n`n", true );
            if ($session ['user'] ['superuser'] >= 1)
                output ( "<a href='newday.php?setadmin=4$resline'>`\$Spezielle-Chara`0 sind Accounts die alles können, daher werden sie nur von Admin vergeben.</a>`n`n", true );

            addnav ( "Wähle dein Reich" );
            addnav ( "RPG-Chara", "newday.php?setadmin=1$resline" );
            addnav ( "Mix-Chara", "newday.php?setadmin=2$resline" );
            addnav ( "Level-Chara", "newday.php?setadmin=3$resline" );
            if ($session ['user'] ['superuser'] >= 2)
                addnav ( "Spezielle-Chara", "newday.php?setadmin=4$resline" );

            addnav ( "", "newday.php?setadmin=1$resline" );
            addnav ( "", "newday.php?setadmin=2$resline" );
            addnav ( "", "newday.php?setadmin=3$resline" );
            if ($session ['user'] ['superuser'] >= 2)
                addnav ( "", "newday.php?setadmin=4$resline" );
        }
    }

    if ($session ['user'] ['admin'] > 0) {
        addnav ( "Weiter", "newday.php?continue=1$resline" );
    }
} else {
    if ($session ['user'] ['slainby'] != "") {
        page_header ( "Du wurdest umgebracht!" );
        output ( "`\$Im " . $session ['user'] ['killedin'] . " hat dich `%" . $session ['user'] ['slainby'] . "`\$ getötet und dein Gold genommen. Ausserdem hast du 5% deiner Erfahrungspunkte verloren. Meinst du nicht auch, es ist Zeit für Rache?" );
        addnav ( "Weiter", "newday.php?continue=1$resline" );
        $session ['user'] ['slainby'] = "";
    } else {
        page_header ( "Es ist ein neuer Tag!" );
        $interestrate = e_rand ( $mininterest * 100, $maxinterest * 100 ) / ( float ) 100;
        output ( "`c<font size='+1'>`b`#Es ist ein neuer Tag!`0`b</font>`c", true );
        if (! $session ['user'] ['prefs'] ['nosounds'])
            output ( "<embed src=\"media/newday.wav\" width=10 height=10 autostart=true loop=false hidden=true volume=100>", true );

        if ($session ['user'] ['alive'] != true) {
            $session ['user'] ['resurrections'] ++;
            output ( "`@Du bist wiedererweckt worden! Dies ist der Tag deiner " . ordinal ( $session ['user'] ['resurrections'] ) . " Wiederauferstehung.`0`n" );
            $session ['user'] ['alive'] = true;
        }
        $session [user] [age] ++;
        $session [user] [seenmaster] = 0;
        // Slavschafts Addon
        if ($session [user] [age] < 2 && $session [user] [slave] == 1) {
            $session [user] [master] = 'Bastor';

            output ( " `$ `n`nDu bist Eigentum des Sklavenhändlers Bastor. Das Dorfleben ist dir verwehrt, bis man dich erworben hat. Solange wirst du in den Sklavenbaracken dein Dasein fristen. " );
        }
        output ( "Du öffnest deine Augen und stellst fest, dass dir ein neuer Tag geschenkt wurde. Dies ist dein `^" . ordinal ( $session ['user'] ['age'] ) . "`0 Tag in diesem Land. " );
        output ( "Du fühlst dich frisch und bereit für die Welt!`n" );
        output ( "`2Runden für den heutigen Tag: `^$turnsperday`n" );

        if ($session [user] [goldinbank] < 0 && abs ( $session [user] [goldinbank] ) < ( int ) getsetting ( "maxinbank", 10000 )) {
            output ( "`2Heutiger Zinssatz: `^" . (($interestrate - 1) * 100) . "% `n" );
            output ( "`2Zinsen für Schulden: `^" . - ( int ) ($session ['user'] ['goldinbank'] * ($interestrate - 1)) . "`2 Gold.`n" );
        } else if ($session [user] [goldinbank] < 0 && abs ( $session [user] [goldinbank] ) >= ( int ) getsetting ( "maxinbank", 10000 )) {
            output ( "`4Die Bank erlässt dir deine Zinsen, da du schon hoch genug verschuldet bist.`n" );
            $interestrate = 1;
        } else if ($session [user] [goldinbank] > 0 && $session [user] [goldinbank] >= ( int ) getsetting ( "maxinbank", 10000 ) && $oldtruns <= getsetting ( "fightsforinterest", 4 )) {
            $interestrate = 1;
            output ( "`4Die Bank kann dir heute keinen Zinsen zahlen. Sie würde früher oder später an dir pleite gehen.`n" );
        } else if ($session [user] [goldinbank] > 0 && $session [user] [goldinbank] < ( int ) getsetting ( "maxinbank", 10000 ) && $oldtruns <= getsetting ( "fightsforinterest", 4 )) {
            output ( "`2Heutiger Zinssatz: `^" . (($interestrate - 1) * 100) . "% `n" );
            output ( "`2Durch Zinsen verdientes Gold: `^" . ( int ) ($session ['user'] ['goldinbank'] * ($interestrate - 1)) . "`n" );
        } else {
            $interestrate = 1;
            output ( "`2Dein heutiger Zinssatz beträgt `^0% (Die Bank gibt nur den Leuten Zinsen, die dafür arbeiten)`n" );
        }

        output ( "`2Deine Gesundheit wurde wiederhergestellt auf `^" . $session ['user'] ['maxhitpoints'] . "`n" );
        $skills = array (
                1 => "Dunkle Künste",
                "Mystische Kräfte",
                "Diebeskünste"
        );
        $sb = getsetting ( "specialtybonus", 1 );
        output ( "`2Für dein Spezialgebiet `&" . $skills [$session ['user'] ['specialty']] . "`2, erhältst du zusätzlich $sb Anwendung(en) in `&" . $skills [$session ['user'] ['specialty']] . "`2 für heute.`n" );
        $session ['user'] ['darkartuses'] = ( int ) ($session ['user'] ['darkarts'] / 3) + ($session ['user'] ['specialty'] == 1 ? $sb : 0);
        $session ['user'] ['magicuses'] = ( int ) ($session ['user'] ['magic'] / 3) + ($session ['user'] ['specialty'] == 2 ? $sb : 0);
        $session ['user'] ['thieveryuses'] = ( int ) ($session ['user'] ['thievery'] / 3) + ($session ['user'] ['specialty'] == 3 ? $sb : 0);

        // $session['user']['bufflist']=array(); // with this here, buffs are always wiped, so the preserve stuff fails!
        if ($session ['user'] ['marriedto'] == 4294967295 || $session ['user'] ['charisma'] == 4294967295) {
            output ( "`n`%Du bist verheiratet, es gibt also keinen Grund mehr, das perfekte Image aufrecht zu halten. Du lässt dich heute ein bisschen gehen.`n Du verlierst einen Charmepunkt.`n" );
            $session ['user'] ['charm'] --;
            if ($session ['user'] ['charm'] <= 0) {
                output ( "`n`bAls du heute aufwachst, findest du folgende Notiz neben dir im Bett:`n`5" . ($session [user] [sex] ? "Liebste" : "Liebster") . "" );
                output ( "" . $session ['user'] ['name'] . "`5." );
                output ( "`nTrotz vieler großartiger Küsse, fühle ich mich einfach nicht mehr so zu dir hingezogen wie es früher war.`n`n" );
                output ( "Nenne mich wankelmütig, aber ich muss weiterziehen. Es gibt andere Krieger" . ($session [user] [sex] ? "innen" : "") . " in diesem Dorf und ich glaube, " );
                output ( "einige davon sind wirklich heiss. Es liegt also nicht an dir, sondern an mir, usw. usw." );
                $sql = "SELECT acctid,name FROM accounts WHERE locked=0 AND acctid=" . $session [user] [marriedto] . "";
                $result = db_query ( $sql ) or die ( db_error ( LINK ) );
                $row = db_fetch_assoc ( $result );
                $partner = $row [name];
                if ($partner == "")
                    $partner = $session [user] [sex] ? "Seth" : "Violet";
                output ( "`n`nSei nicht traurig!`nIn Liebe, $partner`b`n" );
                addnews ( "`\$$partner `\$hat {$session['user']['name']}`\$ für \"andere Interessen\" verlassen!" );
                if ($session ['user'] ['marriedto'] == 4294967295)
                    $session ['user'] ['marriedto'] = 0;
                if ($session ['user'] ['charisma'] == 4294967295) {
                    $session ['user'] ['charisma'] = 0;
                    $session ['user'] ['marriedto'] = 0;
                    $sql = "UPDATE accounts SET charisma=0,marriedto=0 WHERE acctid='$row[acctid]'";
                    db_query ( $sql );
                    systemmail ( $row ['acctid'], "`\$Wieder solo!`0", "`6Du hast `&{$session['user']['name']}`6 verlassen. " . ($session [user] [sex] ? "Sie" : "Er") . " war einfach widerlich in letzter Zeit." );
                }
            }
        }

        // clear all standard buffs
        $tempbuf = unserialize ( $session ['user'] ['bufflist'] );
        $session ['user'] ['bufflist'] = "";
        $session ['bufflist'] = array ();
        while ( list ( $key, $val ) = @each ( $tempbuff ) ) {
            if ($val ['survivenewday'] == 1) {
                $session ['bufflist'] [$key] = $val;
                output ( "{$val['newdaymessage']}`n" );
            }
        }
        if ($session ['user'] ['sex'] == 1)
            $session ['user'] ['ssempf'] = e_rand () % 9;
        if ($row [ssstatus] == 1 && $row [ssmonat] <= 16) {
            output ( "Da deine Frau schwanger ist, bist Du ein wenig aufgeregt... gut Du bist sehr aufgeregt`n" );
            $session [bufflist] ['schwanger'] = array (
                    "name" => "`&Deine Frau ist schwanger",
                    "rounds" => 1000000,
                    "wearoff" => "Irgendwas stimmt nicht mehr.",
                    "defmod" => 0.2,
                    "roundmsg" => "`9Du bist abgelenkt an den Gedanken das Du bald Vater wirst.",
                    "activate" => "offense"
            );
        }

        if ($session [user] [ssstatus] == 1) {
            $session [user] [ssmonat] --;
            if ($session ['user'] ['ssmonat'] <= 16) {
                if ($session [user] [ssmonat] > 0) {
                    output ( "Du bist schwanger... Also pass auf dich auf`n" );
                    $session ['bufflist'] ['schwanger'] = array (
                            "name" => "`&Schwangerschaft",
                            "rounds" => 1000000,
                            "wearoff" => "Irgendwas stimmt nicht mehr.",
                            "defmod" => 0,
                            "roundmsg" => "`9Du versucht deinen Bauch zu schützen und nimmst so jeden anderen Treffer in kauf.",
                            "activate" => "offense"
                    );
                    if ($session [user] [superuser] >= 2)
                        output ( "Noch " . $session [user] [ssmonat] . " Tage" );
                } else {
                    $zwilling = e_rand () % 25;
                    if ($zwilling == 1) {
                        $session [user] [ssstatus] = 0;
                        $geschlechta = e_rand () % 2;
                        $geschlechtb = e_rand () % 2;
                        output ( "`&Du bist bist heute Mutter geworden... Es sind Zwillinge! Vergiss nicht die neuen Erdenbürger in der Kappelle zu taufen, sonst wird niemals jemand wissen das es ihn gibt und das wäre doch traurig!`n" );

                        if ($geschlechta == $geschlechtb && $geschlechtb == 1)
                            $t = "Es sind zwei Mädchen!`n";
                        else if ($geschlechta == $geschlechtb && $geschlechtb == 0)
                            $t = "Es sind zwei Jungs!`n";
                        else
                            $t = "Es ist ein Mädchen und ein Junge!`n";

                        output ( $t );

                        systemmail ( $session [user] [marriedto], "`%Du bist Vater!`0", "`&Deine Frau {$session['user']['name']}`6 hat heute ein zwei wunderschöne Babies zur Welt gebracht, vergesst nicht sie in der Kapelle zu taufen. " . $t );
                        systemmail ( $session [user] [acctid], "`%Du bist Mutter!`0", "`&Du`6 hast heute zwei wunderschöne Babies zur Welt gebracht, vergesst nicht sie in der Kapelle zu taufen. " . $t );
                        addnews ( $session [user] [name] . " & " . $row [name] . " sind heute Eltern geworden." );
                        if ($session [user] [sserzeug] != $session [user] [marriedto])
                            $unehelich = 1;
                        else
                            $unehelich = 0;
                        $sqlkind = "INSERT INTO kinder VALUES ('', '" . $session [user] [acctid] . "', '" . $session [user] [sserzeug] . "', '', '" . $geschlechta . "', '" . getgamedate () . "', $unehelich, '');";
                        db_query ( $sqlkind ) or die ( db_error ( LINK ) );
                        $sqlkind = "INSERT INTO kinder VALUES ('', '" . $session [user] [acctid] . "', '" . $session [user] [sserzeug] . "', '', '" . $geschlechtb . "', '" . getgamedate () . "', $unehelich, '');";
                        db_query ( $sqlkind ) or die ( db_error ( LINK ) );
                    } else {
                        $session [user] [ssstatus] = 0;
                        $geschlecht = e_rand () % 2;
                        output ( "`&Du bist bist heute Mutter geworden... Vergiss nicht den neuen Erdenbürger in der Kappelle zu taufen, sonst wird niemals jemand wissen das es ihn gibt und das wäre doch traurig!`n" );

                        if ($geschlecht == 1)
                            $t = "Es ist ein Mädchen!";
                        else
                            $t = "Es ist ein Junge!";

                        output ( $t );

                        systemmail ( $session [user] [marriedto], "`%Du bist Vater!`0", "`&Deine Frau {$session['user']['name']}`6 hat heute ein wunderschönes Baby zur Welt gebracht, vergesst nicht es in der Kapelle zu taufen. " . $t );
                        systemmail ( $session [user] [acctid], "`%Du bist Mutter!`0", "`&Du`6 hast heute ein wunderschönes Baby zur Welt gebracht, vergesst nicht es in der Kapelle zu taufen. " . $t );
                        addnews ( $session [user] [name] . " & " . $row [name] . " sind heute Eltern geworden." );
                        if ($session [user] [sserzeug] != $session [user] [marriedto])
                            $unehelich = 1;
                        else
                            $unehelich = 0;
                        $sqlkind = "INSERT INTO kinder VALUES ('', '" . $session [user] [acctid] . "', '" . $session [user] [sserzeug] . "', '', '" . $geschlecht . "', '" . getgamedate () . "', $unehelich, '');";
                        db_query ( $sqlkind ) or die ( db_error ( LINK ) );
                    }
                    // KIND BEKOMMEN
                }
            }
        }

        $session [user] [sexheute] = 0;

        if ($session [user] [sexgoettlich] > 0) {
            $session [user] [sexgoettlich] --;
            output ( "`&Du errinerst dich an die schönen Stunden die Du mit einem Gott verbracht hast`n" );
            $session ['bufflist'] ['goettlichersex'] = array (
                    "name" => "`%Göttliches Andenken",
                    "rounds" => $session [user] [sexgoettlich],
                    "wearoff" => "Die Errinerung verfliegt für heute!",
                    "atkmod" => 1.75,
                    "roundmsg" => "Du denkst immer noch an den göttlich intimen Stunden...",
                    "activate" => "offense"
            );
        }

        reset ( $session ['user'] ['dragonpoints'] );
        $dkff = 0;
        while ( list ( $key, $val ) = each ( $session ['user'] ['dragonpoints'] ) ) {
            if ($val == "ff") {
                $dkff ++;
            }
        }
        if ($session [user] [hashorse]) {
            $session ['bufflist'] ['mount'] = unserialize ( $playermount ['mountbuff'] );
        }
        if ($dkff > 0)
            output ( "`n`2Du erhöhst deine Waldkämpfe um `^$dkff`2 durch verteilte Phoenixpunkte!" );
        $r1 = e_rand ( - 1, 1 );
        $r2 = e_rand ( - 1, 1 );
        $spirits = $r1 + $r2;
        if ($_GET ['resurrection'] == "true") {
            addnews ( "`&{$session['user']['name']}`& wurde von `\$Ramius`& wiedererweckt." );
            $spirits = - 6;
            $session ['user'] ['deathpower'] -= 100;
            $session ['user'] ['restorepage'] = "village.php?c=1";
        }
        if ($_GET ['resurrection'] == "egg") {
            addnews ( "`&{$session['user']['name']}`& hat das `^goldene Ei`& benutzt und entkam so dem Schattenreich." );
            $spirits = - 6;
            // $session['user']['deathpower']-=100;
            $session ['user'] ['restorepage'] = "village.php?c=1";
            savesetting ( "hasegg", stripslashes ( 0 ) );
        }
        $sp = array (
                (- 6) => "Auferstanden",
                (- 2) => "Sehr schlecht",
                (- 1) => "Schlecht",
                "0" => "Normal",
                1 => "Gut",
                2 => "Sehr gut"
        );
        output ( "`n`2Dein Geist und deine Stimmung ist heute `^" . $sp [$spirits] . "`2!`n" );
        if (abs ( $spirits ) > 0) {
            output ( "`2Deswegen `^" );
            if ($spirits > 0) {
                output ( "bekommst du zusätzlich " );
            } else {
                output ( "verlierst du " );
            }
            output ( abs ( $spirits ) . " Runden`2 für heute.`n" );
        }
        $rp = $session ['user'] ['restorepage'];
        $x = max ( strrpos ( "&", $rp ), strrpos ( "?", $rp ) );
        if ($x > 0)
            $rp = substr ( $rp, 0, $x );
        if (substr ( $rp, 0, 10 ) == "badnav.php") {
            addnav ( "Weiter", "news.php" );
        } else {
            addnav ( "Weiter", preg_replace ( "'[?&][c][=].+'", "", $rp ) );
        }

        $session ['user'] ['laston'] = date ( "Y-m-d H:i:s" );
        $bgold = $session ['user'] ['goldinbank'];
        $session ['user'] ['goldinbank'] *= $interestrate;
        $nbgold = $session ['user'] ['goldinbank'] - $bgold;

        if ($nbgold != 0) {
            // debuglog(($nbgold >= 0 ? "earned " : "paid ") . abs($nbgold) . " gold in interest");
        }
        $session ['user'] ['turns'] = $session ['user'] ['turns'] + $spirits + $dkff;
        if ($session [user] [maxhitpoints] < 6)
            $session [user] [maxhitpoints] = 6;
        $session ['user'] ['hitpoints'] = $session [user] [maxhitpoints];
        $session ['user'] ['spirits'] = $spirits;
        $session ['user'] ['playerfights'] = $dailypvpfights;
        $session ['user'] ['sueturm'] = 0;
        $session ['user'] ['transferredtoday'] = 0;
        $session ['user'] ['pilzsuche'] = '0';
        $session ['user'] ['amountouttoday'] = 0;
        $session ['user'] ['seendragon'] = 0;
        $session ['user'] ['seenmaster'] = 0;
        $session ['user'] ['seenlover'] = 0;
        $session ['user'] ['witch'] = 0;
        $session ['user'] ['trauer'] = 0;
        $session ['user'] ['sanela'] ['turm'] = 0;
        $session ['user'] ['sanela'] ['grotte'] = 0;
        $session ['user'] ['sanela'] ['kirche'] = 0;
        $session ['user'] ['sanela'] ['sanela'] = 0;
        $session ['user'] ['sanela'] ['haganir'] = 0;
        $session ['user'] ['sanela'] ['haganirschmiede'] = 0;
        $session ['user'] ['sanela'] ['schwimm'] = 0;
        $session ['user'] ['sanela'] ['huegel'] = 0;
        $session ['user'] ['sanela'] ['strand'] = 0;
        $session ['user'] ['usedouthouse'] = 0;
        $session ['user'] ['seenAcademy'] = 0;
        $session ['user'] ['gotfreeale'] = 0;
        $session ['user'] ['schneeball'] = 0;
        $session ['user'] ['lurn'] = 0;
        $session ['user'] ['admin'] = 4;
        $session ['user'] ['weinkeller'] = 0;
        $session ['user'] ['astro'] = 0;
        $session ['user'] ['fedmount'] = 0;
        $session ['user'] ['specialinc'] = "";
        $session['user']['statue'] = 0;

        $session ['user'] ['gottempel'] = 0;
        $session ['user'] ['lucky'] = 0;
        $session ['user'] ['munz'] = 0;
        $session ['user'] ['geschenk'] = 0;
        if ($_GET ['resurrection'] != "true" && $_GET ['resurrection'] != "egg") {
            $session ['user'] ['soulpoints'] = 50 + 5 * $session ['user'] ['level'];
            $session ['user'] ['gravefights'] = getsetting ( "gravefightsperday", 10 );
            $session ['user'] ['reputation'] += 5;
        }
        $session ['user'] ['seenbard'] = 0;
        $session ['user'] ['boughtroomtoday'] = 0;
        $session ['user'] ['lottery'] = 0;
        $session ['user'] ['recentcomments'] = $session ['user'] ['lasthit'];
        $session ['user'] ['lasthit'] = date ( "Y-m-d H:i:s" );
        if ($session ['user'] ['drunkenness'] > 66) {
            output ( "`&Wegen deines schrecklichen Katers wird dir 1 Runde für heute abgezogen." );
            $session ['user'] ['turns'] --;
        }

        // following by talisman & JT
        // Set global newdaysemaphore

        $lastnewdaysemaphore = convertgametime ( strtotime ( getsetting ( "newdaysemaphore", "0000-00-00 00:00:00" ) ) );
        $gametoday = gametime ();

        if (date ( "Ymd", $gametoday ) != date ( "Ymd", $lastnewdaysemaphore )) {
            $sql = "LOCK TABLES settings WRITE";
            db_query ( $sql );

            $lastnewdaysemaphore = convertgametime ( strtotime ( getsetting ( "newdaysemaphore", "0000-00-00 00:00:00" ) ) );

            $gametoday = gametime ();
            if (date ( "Ymd", $gametoday ) != date ( "Ymd", $lastnewdaysemaphore )) {
                // we need to run the hook, update the setting, and unlock.
                savesetting ( "newdaysemaphore", date ( "Y-m-d H:i:s" ) );
                $sql = "UNLOCK TABLES";
                db_query ( $sql );

                require_once "setnewday.php";
            } else {
                // someone else beat us to it, unlock.
                $sql = "UNLOCK TABLES";
                db_query ( $sql );
                output ( "Somebody beat us to it" );
            }
        }
        // Adventspecial für Merydiâ, der Anfang ist in der setnewday.php, eine Anleitung findet ihr unter www.merydia.de, www.anpera.net oder bei http://www.dai-clan.de/SiliForum/wbb2/

        // Copyright by Leen/Cassandra (cassandra@leensworld.de)
        // SQL: ALTER TABLE `accounts` ADD `specialperday` INT( 11 ) NOT NULL ; <- auch nutzbar für andere Specials die an bestimmten REAL-Tagen stattfinden und man es nicht jeden Tag nutzen darf
        if ($session ['user'] ['einlass'] == 1) {
            if ($settings ['weihnacht'] > '0') {
                $datum = getsetting ( 'weihnacht', '01-01' );
                $adventtag = explode ( '-', $datum );
                if ($adventtag [1] <= 31 && $adventtag [1] > 0) {
                    output ( '`b`$`n`nHeute ist der ' . $adventtag [1] . '. Dezember! Du darfst heute den Beutel mit der Nummer ' . $adventtag [1] . ' aufmachen, schau schnell was du geschenkt bekommst!`n`0`b' );
                    if ($session ['user'] ['specialperday'] < $adventtag [1]) {
                        $session ['user'] ['specialperday'] = $adventtag [1];
                        $bild = $adventtag [1];
                        output ( '`n`c<img src="images/advent/' . $bild . '.gif" width="160" height="200">`c`n`n', true );
                        // Geschenke *sabber*
                        switch ($adventtag [1]) {
                            case 24 :
                                switch (e_rand ( 1, 5 )) {
                                    case 1 :
                                        if ($session ['user'] ['experience'] < 20000) {
                                            $session ['user'] ['experience'] += 4000;
                                            $session ['user'] ['turns'] += 30;
                                            output ( '`c`@Du öffnest den Beutel und findest `^4000 `@Erfahrungspunkte und Waldkämpfe.`n`bFrohe Weihnachten wünscht das Team von Merydiâ`c`b`n`n' );
                                            break;
                                        }
                                    case 2 :
                                        $gesamtgold = ($session ['user'] ['gold']) + ($session ['user'] ['goldinbank']);
                                        if ($gesamtgold < 50000) {
                                            $session ['user'] ['gold'] += 40000;
                                            $session ['user'] ['turns'] += 30;
                                            output ( '`c`@Du öffnest den Beutel und findest `^40000 `@Goldstücke und Waldkämpfe.`n`bFrohe Weihnachten wünscht das Team von Merydiâ`c`b`n`n' );
                                            break;
                                        }
                                    case 3 :
                                        if ($session ['user'] ['gems'] < 100) {
                                            $session ['user'] ['gems'] += 15;
                                            $session ['user'] ['turns'] += 30;
                                            output ( '`c`@Du öffnest den Beutel und findest `^15 `@Edelsteine und Waldkämpfe.`n`bFrohe Weihnachten wünscht das Team von Merydiâ`c`b`n`n' );
                                            break;
                                        }
                                    case 4 :
                                        $session ['user'] ['defence'] += 3;
                                        $session ['user'] ['attack'] += 3;
                                        $session ['user'] ['turns'] += 30;
                                        output ( '`c`@Du öffnest den Beutel und findest je `^3 `@Angriffs- und Verteidigungspunkte, sowie Waldkämpfe.`n`bFrohe Weihnachten wünscht das Team von Merydiâ`c`b`n`n' );
                                        break;
                                    case 5 :
                                        $session ['user'] ['deathpower'] += 200;
                                        $session ['user'] ['turns'] += 30;
                                        output ( '`c`@Du öffnest den Beutel und findest `^200 `@Gefallen und Waldkämpfe.`n`bFrohe Weihnachten wünscht das Team von Merydiâ`c`b`n`n' );
                                        break;
                                }
                                break;
                            default :
                                switch (e_rand ( 1, 5 )) {
                                    case 1 :
                                        if ($session ['user'] ['experience'] < 20000) {
                                            $session ['user'] ['experience'] += 500;
                                            $session ['user'] ['turns'] += 5;
                                            output ( '`c`@Du öffnest den Beutel und findest `^500 `@Erfahrungspunkte und Waldkämpfe.`c`n`n' );
                                            break;
                                        }
                                    case 2 :
                                        $gesamtgold = ($session ['user'] ['gold']) + ($session ['user'] ['goldinbank']);
                                        if ($gesamtgold < 50000) {
                                            $session ['user'] ['gold'] += 5000;
                                            $session ['user'] ['turns'] += 5;
                                            output ( '`c`@Du öffnest den Beutel und findest `^5000 `@Goldstücke und Waldkämpfe.`c`n`n' );
                                            break;
                                        }
                                    case 3 :
                                        if ($session ['user'] ['gems'] < 100) {
                                            $session ['user'] ['gems'] += 5;
                                            $session ['user'] ['turns'] += 5;
                                            output ( '`c`@Du öffnest den Beutel und findest `^5 `@Edelsteine und Waldkämpfe.`c`n`n' );
                                            break;
                                        }
                                    case 4 :
                                        $session ['user'] ['defence'] += 1;
                                        $session ['user'] ['attack'] += 1;
                                        $session ['user'] ['turns'] += 5;
                                        output ( '`c`@Du öffnest den Beutel und findest je `^1 `@Angriffs- und Verteidigungspunkt, sowie Waldkämpfe.`c`n`n' );
                                        break;
                                    case 5 :
                                        $session ['user'] ['deathpower'] += 50;
                                        $session ['user'] ['turns'] += 5;
                                        output ( '`c`@Du öffnest den Beutel und findest `^50 `@Gefallen und Waldkämpfe.`c`n`n' );
                                        break;
                                }
                                break;
                        }
                    } else {
                        output ( '`b`$`n`nDu hast heute schon deinen Beutel aufgemacht!`n`n`0`b' );
                    }
                }
            }
        } else {
            $session ['user'] ['specialperday'] = 0;
        }

        output ( "`nDer Schmerz in deinen wetterfühligen Knochen sagt dir das heutige Wetter: `6" . $settings ['weather'] . "`@.`n" );
        if ($_GET ['resurrection'] == "") {
            if ($session ['user'] ['specialty'] == 1 && $settings ['weather'] == "Regnerisch") {
                output ( "`^`nDer Regen schlägt dir aufs Gemüt, aber erweitert deine Dunklen Künste. Du bekommst eine zusätzliche Anwendung.`n" );
                $session [user] [darkartuses] ++;
            }
            if ($session ['user'] ['specialty'] == 2 and $settings ['weather'] == "Gewittersturm") {
                output ( "`^`nDie Blitze fördern deine Mystischen Kräfte. Du bekommst eine zusätzliche Anwendung.`n" );
                $session [user] [magicuses] ++;
            }
            if ($session ['user'] ['specialty'] == 3 and $settings ['weather'] == "Neblig") {
                output ( "`^`nDer Nebel bietet Dieben einen zusätzlichen Vorteil. Du bekommst eine zusätzliche Anwendung.`n" );
                $session [user] [thieveryuses] ++;
            }
        }
        // End global newdaysemaphore code and weather mod.

        if ($session ['user'] ['hashorse']) {
            // $horses=array(1=>"pony","gelding","stallion");
            // output("`n`&You strap your `%".$session['user']['weapon']."`& to your ".$horses[$session['user']['hashorse']]."'s saddlebags and head out for some adventure.`0");
            // output("`n`&Because you have a ".$horses[$session['user']['hashorse']].", you gain ".((int)$session['user']['hashorse'])." forest fights for today!`n`0");
            // $session['user']['turns']+=((int)$session['user']['hashorse']);
            output ( str_replace ( "{weapon}", $session ['user'] ['weapon'], "`n`&{$playermount['newday']}`n`0" ) );
            if ($playermount ['mountforestfights'] > 0) {
                output ( "`n`&Weil du ein(e/n) {$playermount['mountname']} besitzt, bekommst du `^" . (( int ) $playermount ['mountforestfights']) . "`& Runden zusätzlich.`n`0" );
                $session ['user'] ['turns'] += ( int ) $playermount ['mountforestfights'];
            }
        } else {
            output ( "`n`&Du schnallst dein(e/n) `%" . $session ['user'] ['weapon'] . "`& auf den Rücken und ziehst los ins Abenteuer.`0" );
        }
        if ($session ['user'] ['race'] == 3) {
            $session ['user'] ['turns'] ++;
            output ( "`n`&Weil du ein Mensch bist, bekommst du `^1`& Waldkampf zusätzlich!`n`0" );
        }
        $config = unserialize ( $session ['user'] ['donationconfig'] );
        if (! is_array ( $config ['forestfights'] ))
            $config ['forestfights'] = array ();
        reset ( $config ['forestfights'] );
        while ( list ( $key, $val ) = each ( $config ['forestfights'] ) ) {
            $config ['forestfights'] [$key] ['left'] --;
            output ( "`@Du bekommst eine Extrarunde für die Punkte auf `^{$val['bought']}`@." );
            $session ['user'] ['turns'] ++;
            if ($val ['left'] > 1) {
                output ( " Du hast `^" . ($val ['left'] - 1) . "`@ Tage von diesem Kauf übrig.`n" );
            } else {
                unset ( $config ['forestfights'] [$key] );
                output ( " Dieser Kauf ist damit abgelaufen.`n" );
            }
        }
        if ($config ['healer'] > 0) {
            $config ['healer'] --;
            if ($config ['healer'] > 0) {
                output ( "`n`@Golinda ist bereit, dich noch {$config['healer']} weitere Tage zu behandeln." );
            } else {
                output ( "`n`@Golinda wird dich nicht länger behandeln." );
                unset ( $config ['healer'] );
            }
        }
        if ($config ['goldmineday'] > 0)
            $config ['goldmineday'] = 0;
        $session ['user'] ['donationconfig'] = serialize ( $config );
        if ($session ['user'] ['hauntedby'] > "") {
            output ( "`n`n`(Du wurdest von {$session['user']['hauntedby']}`( heimgesucht und verlierst eine Runde!" );
            $session ['user'] ['turns'] --;
            $session ['user'] ['hauntedby'] = "";
        }
        // Ehre & Ansehen
        if ($session ['user'] ['reputation'] <= - 50) {
            $session ['user'] ['reputation'] = - 50;
            output ( "`n`8Da du aufgrund deiner Ehrenlosigkeit häufig Steine in den Weg gelegt bekommst, kannst du heute 1 Runden weniger kämpfen. Außerdem sind deine Feinde vor dir gewarnt.`nDu solltest dringend etwas für deine Ehre tun!" );
            $session ['user'] ['turns'] --;
            $session ['user'] ['playerfights'] --;
        } else if ($session ['user'] ['reputation'] <= - 30) {
            output ( "`n`8Deine Ehrenlosigkeit hat sich herumgesprochen! Deine Feinde sind vor dir gewarnt, weshalb dir heute 1 Spielerkampf weniger gelingen wird.`nDu solltest dringend etwas für deine Ehre tun!" );
            $session ['user'] ['playerfights'] --;
        } else if ($session ['user'] ['reputation'] < - 10) {
            output ( "`n`8Da du aufgrund deiner Ehrenlosigkeit häufig Steine in den Weg gelegt bekommst, kannst du heute 1 Runde weniger kämpfen." );
            $session ['user'] ['turns'] --;
        } else if ($session ['user'] ['reputation'] >= 30) {
            if ($session ['user'] ['reputation'] > 50)
                $session ['user'] ['reputation'] = 50;
            output ( "`n`9Da du aufgrund deiner großen Ehrenhaftigkeit das Volk auf deiner Seite hast, kannst du heute 1 Runde und 1 Spielerkampf mehr kämpfen." );
            $session ['user'] ['turns'] ++;
            $session ['user'] ['playerfights'] ++;
        } else if ($session ['user'] ['reputation'] > 10) {
            output ( "`n`9Da du aufgrund deiner großen Ehrenhaftigkeit das Volk auf deiner Seite hast, kannst du heute 1 Runde mehr kämpfen." );
            $session ['user'] ['turns'] ++;
        }
        if ($session ['user'] ['schuhe'] == 1) {

            output ( "`n`9Durch deine Wanderstiefel erhählst du `^1 `9Waldkampf mehr.`n`n" );

            $session ['user'] ['turns'] ++;
        }
        if ($session ['user'] ['schuhe'] == 2) {

            output ( "`n`9Durch deine Sportschuhe erhählst du `^2 `9Waldkämpfe mehr.`n`n" );

            $session ['user'] ['turns'] += 2;
        }
        if ($session ['user'] ['schuhe'] == 3) {

            output ( "`n`9Durch deine Lederstiefel erhählst du `^2 `9Waldkämpfe mehr.`n`n" );

            $session ['user'] ['turns'] += 3;
        }
        if ($session ['user'] ['schuhe'] == 4) {

            output ( "`n`9Durch deine Drachen Stiefel erhählst du `^4 `9Waldkämpfe mehr.`n`n" );

            $session ['user'] ['turns'] += 4;
        }
        if ($session ['user'] ['armband'] == 1) {

            if ($session ['user'] ['ause'] == 1) {

                output ( "`nDurch dein Goldenes Armband erhählst du 1 Verteidigung mehr.`n`n" );

                $session ['user'] ['defence'] -= 1;

                $session ['user'] ['defence'] += 1;
            } else {

                output ( "`nDurch dein Goldenes Armband erhählst du 1 Verteidigung mehr.`n`n" );

                $session ['user'] ['defence'] += 1;

                $session ['user'] ['ause'] = 1;
            }
        }
        if ($session ['user'] ['armband'] == 2) {

            if ($session ['user'] ['ause'] == 1) {

                output ( "`nDurch deine Drachen Armband erhählst du 2 Verteidigungspunkte mehr.`n`n" );

                $session ['user'] ['defence'] -= 2;

                $session ['user'] ['defence'] += 2;
            } else {

                output ( "`nDurch dein Drachen Armband erhählst du 2 Verteidigung mehr.`n`n" );

                $session ['user'] ['defence'] += 2;

                $session ['user'] ['ause'] = 1;
            }
        }
        if ($session ['user'] ['klamotten'] == 1) {

            if ($session ['user'] ['kuse'] == 1) {

                output ( "`nDurch dein Weises Kleid erhählst du 1 Angriff mehr.`n`n" );

                $session ['user'] ['attack'] -= 1;

                $session ['user'] ['attack'] += 1;
            } else {

                output ( "`nDurch dein Weises Kleid erhählst du 1 Angriff mehr.`n`n" );

                $session ['user'] ['attack'] += 1;

                $session ['user'] ['kuse'] = 1;
            }
        }
        if ($session ['user'] ['klamotten'] == 2) {

            if ($session ['user'] ['kuse'] == 1) {

                output ( "`nDurch dein Drachenleder Kleid erhählst du 2 Angriffe mehr.`n`n" );

                $session ['user'] ['attack'] -= 2;

                $session ['user'] ['attack'] += 2;
            } else {

                output ( "`nDurch dein Drachenleder Kleid erhählst du 2 Angriff mehr.`n`n" );

                $session ['user'] ['attack'] += 2;

                $session ['user'] ['kuse'] = 1;
            }
        }
        if ($session ['user'] ['klamotten'] == 3) {

            if ($session ['user'] ['kuse'] == 1) {

                output ( "`nDurch dein Schwarzes Jacket erhählst du 1 Angriff mehr.`n`n" );

                $session ['user'] ['attack'] -= 1;

                $session ['user'] ['attack'] += 1;
            } else {

                output ( "`nDurch dein Schwarzes Jacket erhählst du 1 Angriff mehr.`n`n" );

                $session ['user'] ['attack'] += 1;

                $session ['user'] ['kuse'] = 1;
            }
        }
        if ($session ['user'] ['klamotten'] == 4) {

            if ($session ['user'] ['kuse'] == 1) {

                output ( "`nDurch dein Drachenleder Jacket erhählst du 2 Angriffe mehr.`n`n" );

                $session ['user'] ['attack'] -= 2;

                $session ['user'] ['attack'] += 2;
            } else {

                output ( "`nDurch dein Drachenleder Jacket erhählst du 2 Angriff mehr.`n`n" );

                $session ['user'] ['attack'] += 2;

                $session ['user'] ['kuse'] = 1;
            }
        }
        $session ['user'] ['drunkenness'] = 0;
        $session ['user'] ['bounties'] = 0;
        if ($session ['user'] ['frisur'] <= 80) {

            $session ['user'] ['frisur'] += 1;
        } else {

            $session ['user'] ['frisur'] += 0;
        }

        if ($session ['user'] ['nagel'] <= 50) {

            $session ['user'] ['nagel'] += 1;
        } else {

            $session ['user'] ['nagel'] += 0;
        }
        // Buffs from items
        $sql = "SELECT * FROM items WHERE (class='Fluch' OR class='Geschenk' OR class='Zauber' OR class='Möbel' OR class='Schwarzmarkt') AND owner=" . $session [user] [acctid] . " ORDER BY id";
        $result = db_query ( $sql );
        
        for($i = 0; $i < db_num_rows ( $result ); $i ++) {
            $row = db_fetch_assoc ( $result );
            if (strlen ( $row [buff] ) > 8) {
                $row [buff] = unserialize ( $row [buff] );
                if ($row ['class'] != 'Zauber')
                    $session [bufflist] [$row [buff] [name]] = $row [buff];
                if ($row ['class'] == 'Fluch')
                    output ( "`n`G$row[name]`G nagt an dir." );
                if ($row ['class'] == 'Geschenk')
                    output ( "`n`1$row[name]`1: $row[description]" );
                if ($row ['class'] == 'Möbel')
                    output ( "`n`1Der Butler reicht dir eine Erfrischung" );
                if ($row ['name'] == '`ÀFer`ìk`êel')
                    output ( "`n`1Das Ferkel bringt dir einen Charmepunkt." );
                if ($row ['class'] == 'Schwarzmarkt' && $row[name] =='`ÀFer`ìk`êel')
                    $session[user][charisma]+=1;
                    db_query($sql);
                
            }
            if ($row [hvalue] > 0) {
                $row [hvalue] --;
                if ($row [hvalue] <= 0) {
                    db_query ( "DELETE FROM items WHERE id=$row[id]" );
                    if ($row ['class'] == 'Fluch')
                        output ( " Aber nur noch heute." );
                    if ($row ['class'] == 'Zauber')
                        output ( "`n`Q$row[name]`Q hat seine Kraft verloren." );
                } else {
                    $what = "hvalue=$row[hvalue]";
                    if ($row ['class'] == 'Zauber')
                        $what .= ", value1=$row[value2]";
                    db_query ( "UPDATE items SET $what WHERE id=$row[id]" );
                }
            }
        }
    }
}
// Beruf-Script (0.5v)
if ($session ['user'] ['experience'] > 0) {
    
        if ($session ['user'] ['jobid'] >= 1) {
            $sql = "SELECT name,id,lohn,turns FROM jobs";
            $result = db_query ( 'SELECT `name`,`id`,`lohn`,`turns` FROM `jobs` WHERE `id` = ' . $session ['user'] ['jobid'] );
            while ( $job = db_fetch_assoc ( $result ) ) {
                if ($job ['turns'] > $session ['user'] ['turns']) {
                    output ( "`n`$ `b Du bist zu erschöpft um zu arbeiten, und wirst daher von deinem Arbeitgeber gefeuert!" );
                    $session ['user'] ['jobid'] = 0;
                    $session ['user'] ['jobname'] = "Arbeitsloser";
                } else {
                    output ( "`nDa du ein fleißiger " . $job ['name'] . " bist, erhälst du deinen Tageslohn von " . $job ['lohn'] . "!" );
                    $session ['user'] ['gold'] += $job ['lohn'];
                    $session ['user'] ['turns'] -= $job ['turns'];
                }
            }
        } else {
            output ( "`n`6 Du bist leider Arbeitslos, daher bekommst du keinen Lohn" );
        
    }
}
$sql = "SELECT * FROM items WHERE (class='Fluch' OR class='Geschenk' OR class='Zauber' OR class='Möbel' OR class='Schwarzmarkt') AND owner=" . $session [user] [acctid] . " ORDER BY id";
if ($row ['name'] == '`^Der `Zgo`tlde`Zne `^Nierenstein von Ellalith')
                    output ( "`n`^Durch den Stein vergisst du, wo ein Teil deines Goldes ist." );
if ($row ['name'] == '`^Der `Zgo`tlde`Zne `^Nierenstein von Ellalith')
                    $session[user][gold]=$session[user][gold]*0.99;
                    db_query($sql);
if ($row ['name'] == '`ÀFer`ìk`êel')
                    output ( "`n`1Das Ferkel bringt dir einen Charmepunkt." );
                if ($row ['class'] == 'Schwarzmarkt' && $row[name] =='`ÀFer`ìk`êel')
                    $session[user][charisma]+=1;
                    db_query($sql);
// Ende Berufs-Script (0.5v)
page_footer ();
?>