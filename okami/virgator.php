
<?php
/*******************************
Programm: Virgator
Version: 0.1
Datum: 25.05.2005
Ersteller: deZent von PD
NUR FÜR 0.97 LogD! Ich möchte ausdrücklich nicht, dass dieses Skript in höheren Versionen implementiert wird.
31.03.2007: Überarbeitet durch Maris (Maraxxus [-[at]-] gmx.de) atrahor.de

Änderungen (u.A.):
- maximale Versuche werden durch Setting "virgator_max" festgelegt. (Einbau in configutation.php nicht vergessen!)
- Mindest-DK gleichermaßen durch "virgator_dks" bestimmt.
- Karten sind nun als serialisierter Array gespeichert, virgator_xypos.php daher überflüssig
- Bewegungung können nun auch durch Klicken ins Spielfeld ausgeführt werden
********************************/

require_once 'common.php';
page_header('Virgator');
$str_filename = basename(__FILE__);
$str_backtext = 'Zum Trainingslager';
$str_backlink = 'train.php';
$arr_virgator = user_get_aei('virgator_level,virgator_count',$session['user']['acctid']);

switch ($_GET['op'])
{
    case 'eingang':
    {
        output('
            `c`b`@Virgator`0`b`c`n
            `n
            `7Du betrittst die ehrwürdigen Hallen des Virgators von '.getsetting('townname','Atrahor').'. 
            Hier wurden vor langer Zeit, weit vor dem Zeitalter des `@grünen Drachen`7, 
            die mächtigen Krieger ausgebildet.`n
            Neben Kampfeskunst und Kraft muss ein wahrhaft großer Krieger ebenso über List und Tücke verfügen.`n
            Hierzu wurde der Virgator erschaffen.`n
            `n
            Ein Tempel mit vielen Prüfungen.`n
        ');
        if ($arr_virgator['virgator_level']==0)
        {
            output('
                `n
                `n 
                Vorsichtig näherst du dich dem Tor welches dich vermutlich zu der Arena führt....`n
                Dummerweise ist das Tor mit einem `4rot leuchtendem Siegel`7 verschlossen...
            ');
            addnav('Siegel');
            addnav('Siegel aufbrechen',$str_filename.'?op=siegel');
        }
        else
        {
            output('
                `n
                `n
                Offensichtlich kennt dich das Tor... 
                die Schriftzüge, welche beim Zerschlagen des Siegels entstanden sind, 
                erleuchten als du dich dem Tor näherst.
            ');
            addnav('Virgator');
            addnav('Schriftzüge lesen',$str_filename.'?op=hilfe');
            if ($arr_virgator['virgator_count']<getsetting('virgator_max','3'))
            {
                addnav('Zu den Prüfungen',$str_filename);
                if($session['user']['gems']>0)
                {
                    addnav('Neu beginnen (1ES)',$str_filename.'?op=cheat&act=reset',false,false,false,false,'Achtung! Für 1 Edelstein Gebühr setzt du hiermit deinen Virgator-Level auf 1 und streicht dich aus der Ruhmeshalle. Du fängst also von vorne an. Willst du das wirklich?');
                }
            }
            else
            {
                output('
                    `Q`n
                    Als du dich dem Tor erneut näherst, verschließt es seine Pforten wieder. 
                    Vielleicht hast du heute schon zuviel Prügel eingesteckt...`n
                    `7
                ');
            }
        }
        /** Haben wir woanders
        addnav("Ruhmeshalle",$str_filename."?op=halle");
        addnav("Zurück");
        **/
        addnav($str_backtext,$str_backlink);
    }
    break;

    case 'siegel':
    {
        output('
            `c`b`@Virgator`0`b`c`n
            `n
            `7Du nimmst deine Waffe '.$session['user']['weapon'].' und schlägst mit aller Macht auf das Siegel...`n
            Funken spühren, das Tor
        ');
        if ($session['user']['dragonkills']<getsetting('virgator_dks','5'))
        {
            output(' erzittert...`n
                `n
                Und Sekunden später stellst du frustriert fest, dass es wohl eher ein Lachen war als ein zittern.`n
                Nachdem das Siegel wieder wie gewohnt `$rot glüht`7 siehst du ein, 
                dass du wohl noch nicht erfahren genug bist, um dich im Tempel des Virgators zu messen.`n
            ');
            addnav("Zurück");
            addnav($str_backtext,$str_backlink);
        }
        else
        {
            output(' er`iz`ii`btt`b`ier`it...`n
                `n
                Die blendend hellen Funken formen sich zu zu einem magischen Schriftzug rund um das Tor herum.`n
                Offensichtlich bist du erfahren genug um den Gefahren des Virgators entgegenzutreten.
            ');
            // erstes level freischalten
            $arr_virgator['virgator_level']=1;
            user_set_aei($arr_virgator,$session['user']['acctid']);
            addnav('Weiter');
            addnav('Gehe durchs Tor',$str_filename);
        }
    }
    break;

    case 'hilfe':
    {
        output('
            `c`b`@Virgator`0`b`c`n
            `n
            `^Du befindest dich in den heiligen Hallen des Virgators. 
            Hier wurden einst die mächtigsten aller Krieger ausgebildet.`n
            Es wurde die Lehre der tollkühnen `irunandhide`i Kampftechnik unterrichtet.`n
            Der große `@Meister Caldariusum `^war ungeschlagen in dieser edlen Technik. 
            Ihm sei dieser Ort gewidmet.`n
            `n
            Um die Prüfung zu bestehen musst du vor dem schwarzen Krieger das Ausgangstor erreichen.`n
            Der schwarze Krieger geht immer zuerst horizontal, dann vertikal.`n
            Locke ihn in den Treibsand, dann ist er für 4 Züge außer Gefecht!`n
            Du solltest dich jedoch vom Treibsand fern halten...`n
            Erwischt dich der schwarze Krieger, so kassierst du eine Tracht Prügel!`n
            Pro Tag darfst du es '.getsetting('virgator_max','3').'x versuchen.`n
            `n
            Viel Erfolg!`7`n
            (c) written by `qde`QZ`qent`7 for PD
        ');
        // Finger weg vom (c) !
        // - ok, wir sind ja nicht motwd *g*
        addnav('Virgator');
        addnav('Zum Eingang',$str_filename.'?op=eingang');
    }
    break;

    case 'halle':
    {
        $sql = "
            SELECT 
                `name`, 
                `virgator_level` 
            FROM 
                `accounts` 
            JOIN 
                `account_extra_info` 
            USING 
                (`acctid`) 
            WHERE 
                `virgator_level`    > '0'     AND 
                `superuser`            < '3' 
            ORDER BY 
                `virgator_level`     DESC, 
                `dragonkills`         DESC 
            LIMIT 
                50
        ";
        $result = db_query($sql);
        $str_output .= '
            `c`b`@Virgator`0`b`c`n
            `n
            `7An der Nordseite der Halle ist eine Steintafel auf der der Virgator die aktuellen Prüfungen 
            der einzelnen Abenteuerer festhält. Du siehst hier die 50 erfahrensten Prüflinge.`n
            
            <center>
                <table>
                    <tr>
                        <td style="background-color:#AFDB02;color:#000000;font-weight:bold;">
                            <center>Platz</center>
                        </td>
                        <td style="background-color:#AFDB02;color:#000000;font-weight:bold;">
                            <center>Name</center>
                        </td>
                        <td style="background-color:#AFDB02;color:#000000;font-weight:bold;">
                            Level
                        </td>
                    </tr>
        ';
        
        for($i = 1 ; $row = db_fetch_assoc($result) ; $i++)
        {
            $str_output .= "
                    <tr>
                        <td>
                            ".$i.".
                        </td>
                        <td>
                            ".$row['name']."
                        </td>
                        <td>
                            ".$row['virgator_level']."
                        </td>
                    </tr>
            ";
        }
        $str_output .= "
                </table>
            </center>
        ";
        addnav('Virgator');
        addnav('Zum Eingang',$str_filename.'?op=eingang');
    }
    break;

    case 'cheat':
    {
        if($_GET['act']=='reset')
        {
            $arr_virgator['virgator_level']=0;
            $session['user']['gems']--;
            debuglog('hat Virgator-Level zurückgesetzt');
        }
        $arr_virgator['virgator_level']++;
        user_set_aei($arr_virgator,$session['user']['acctid']);
        output('Dein Virgator-Level ist jetzt '.$arr_virgator['virgator_level']);
        addnav('Weiter');
        addnav('Nächste Prüfung',$str_filename);
        addnav('Zurück');
        addnav('Zum Eingang',$str_filename.'?op=eingang');
    }
    break;

    default:
    {
        output("`n`7Dies ist die `@".$arr_virgator['virgator_level'].".`7 Prüfung.`0`n`n`n
        <center><div style='position: relative;top: 0pt;left: 0pt;width: 350px;height: 350px;'>",true);
        $show_nav=true;

        // Daten holen
        $sql = "
            SELECT 
                * 
            FROM 
                `virgator_table` 
            WHERE 
                `level`    = '".$arr_virgator['virgator_level']."'
        ";
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        $data = unserialize($row['data']);

        //Endbild erstellen wenn kein gültiger Datensatz
        if(!$data[0][0]['bild'])
        {
            $data=unserialize('a:9:{i:0;a:6:{i:0;a:4:{s:4:"xpos";i:0;s:4:"ypos";i:0;s:4:"bild";s:9:"g_o_l.gif";s:3:"nav";s:5:";o;s;";}i:1;a:4:{s:4:"xpos";i:48;s:4:"ypos";i:0;s:4:"bild";s:7:"g_o.gif";s:3:"nav";s:7:";o;s;w;";}i:2;a:4:{s:4:"xpos";i:96;s:4:"ypos";i:0;s:4:"bild";s:7:"g_o.gif";s:3:"nav";s:7:";o;s;w;";}i:3;a:4:{s:4:"xpos";i:144;s:4:"ypos";i:0;s:4:"bild";s:7:"g_o.gif";s:3:"nav";s:7:";o;s;w;";}i:4;a:4:{s:4:"xpos";i:192;s:4:"ypos";i:0;s:4:"bild";s:7:"g_o.gif";s:3:"nav";s:7:";o;s;w;";}i:5;a:4:{s:4:"xpos";i:240;s:4:"ypos";i:0;s:4:"bild";s:9:"g_o_r.gif";s:3:"nav";s:5:";s;w;";}}i:1;a:6:{i:0;a:4:{s:4:"xpos";i:0;s:4:"ypos";i:48;s:4:"bild";s:7:"g_l.gif";s:3:"nav";s:7:";n;o;s;";}i:1;a:4:{s:4:"xpos";i:48;s:4:"ypos";i:48;s:4:"bild";s:5:"g.gif";s:3:"nav";s:9:";n;o;s;w;";}i:2;a:4:{s:4:"xpos";i:96;s:4:"ypos";i:48;s:4:"bild";s:5:"g.gif";s:3:"nav";s:9:";n;o;s;w;";}i:3;a:4:{s:4:"xpos";i:144;s:4:"ypos";i:48;s:4:"bild";s:5:"g.gif";s:3:"nav";s:9:";n;o;s;w;";}i:4;a:4:{s:4:"xpos";i:192;s:4:"ypos";i:48;s:4:"bild";s:5:"g.gif";s:3:"nav";s:9:";n;o;s;w;";}i:5;a:4:{s:4:"xpos";i:240;s:4:"ypos";i:48;s:4:"bild";s:7:"g_r.gif";s:3:"nav";s:7:";n;s;w;";}}i:2;a:6:{i:0;a:4:{s:4:"xpos";i:0;s:4:"ypos";i:96;s:4:"bild";s:7:"g_l.gif";s:3:"nav";s:7:";n;o;s;";}i:1;a:4:{s:4:"xpos";i:48;s:4:"ypos";i:96;s:4:"bild";s:5:"g.gif";s:3:"nav";s:9:";n;o;s;w;";}i:2;a:4:{s:4:"xpos";i:96;s:4:"ypos";i:96;s:4:"bild";s:5:"s.gif";s:3:"nav";s:9:";n;o;s;w;";}i:3;a:4:{s:4:"xpos";i:144;s:4:"ypos";i:96;s:4:"bild";s:5:"g.gif";s:3:"nav";s:9:";n;o;s;w;";}i:4;a:4:{s:4:"xpos";i:192;s:4:"ypos";i:96;s:4:"bild";s:5:"g.gif";s:3:"nav";s:9:";n;o;s;w;";}i:5;a:4:{s:4:"xpos";i:240;s:4:"ypos";i:96;s:4:"bild";s:7:"g_r.gif";s:3:"nav";s:7:";n;s;w;";}}i:3;a:6:{i:0;a:4:{s:4:"xpos";i:0;s:4:"ypos";i:144;s:4:"bild";s:7:"g_l.gif";s:3:"nav";s:7:";n;o;s;";}i:1;a:4:{s:4:"xpos";i:48;s:4:"ypos";i:144;s:4:"bild";s:5:"g.gif";s:3:"nav";s:9:";n;o;s;w;";}i:2;a:4:{s:4:"xpos";i:96;s:4:"ypos";i:144;s:4:"bild";s:5:"g.gif";s:3:"nav";s:9:";n;o;s;w;";}i:3;a:4:{s:4:"xpos";i:144;s:4:"ypos";i:144;s:4:"bild";s:5:"g.gif";s:3:"nav";s:9:";n;o;s;w;";}i:4;a:4:{s:4:"xpos";i:192;s:4:"ypos";i:144;s:4:"bild";s:5:"g.gif";s:3:"nav";s:9:";n;o;s;w;";}i:5;a:4:{s:4:"xpos";i:240;s:4:"ypos";i:144;s:4:"bild";s:7:"g_r.gif";s:3:"nav";s:7:";n;s;w;";}}i:4;a:6:{i:0;a:4:{s:4:"xpos";i:0;s:4:"ypos";i:192;s:4:"bild";s:7:"g_l.gif";s:3:"nav";s:7:";n;o;s;";}i:1;a:4:{s:4:"xpos";i:48;s:4:"ypos";i:192;s:4:"bild";s:5:"g.gif";s:3:"nav";s:9:";n;o;s;w;";}i:2;a:4:{s:4:"xpos";i:96;s:4:"ypos";i:192;s:4:"bild";s:5:"g.gif";s:3:"nav";s:9:";n;o;s;w;";}i:3;a:4:{s:4:"xpos";i:144;s:4:"ypos";i:192;s:4:"bild";s:5:"g.gif";s:3:"nav";s:9:";n;o;s;w;";}i:4;a:4:{s:4:"xpos";i:192;s:4:"ypos";i:192;s:4:"bild";s:5:"g.gif";s:3:"nav";s:9:";n;o;s;w;";}i:5;a:4:{s:4:"xpos";i:240;s:4:"ypos";i:192;s:4:"bild";s:7:"g_r.gif";s:3:"nav";s:7:";n;s;w;";}}i:5;a:6:{i:0;a:4:{s:4:"xpos";i:0;s:4:"ypos";i:240;s:4:"bild";s:9:"g_u_l.gif";s:3:"nav";s:5:";n;o;";}i:1;a:4:{s:4:"xpos";i:48;s:4:"ypos";i:240;s:4:"bild";s:7:"g_u.gif";s:3:"nav";s:7:";n;o;w;";}i:2;a:4:{s:4:"xpos";i:96;s:4:"ypos";i:240;s:4:"bild";s:7:"g_u.gif";s:3:"nav";s:7:";n;o;w;";}i:3;a:4:{s:4:"xpos";i:144;s:4:"ypos";i:240;s:4:"bild";s:7:"g_u.gif";s:3:"nav";s:7:";n;o;w;";}i:4;a:4:{s:4:"xpos";i:192;s:4:"ypos";i:240;s:4:"bild";s:7:"g_u.gif";s:3:"nav";s:7:";n;o;w;";}i:5;a:4:{s:4:"xpos";i:240;s:4:"ypos";i:240;s:4:"bild";s:9:"g_u_r.gif";s:3:"nav";s:7:";n;w;s;";}}s:4:"exit";a:2:{s:1:"x";i:5;s:1:"y";i:6;}s:9:"opp_start";a:2:{s:1:"x";i:5;s:1:"y";i:5;}s:7:"p_start";a:2:{s:1:"x";i:0;s:1:"y";i:0;}}');
        }

        // Sperren
        if(is_array($data['lock']))
        {
            foreach ($data['lock'] as $key => $val)
            {
                $vonx = $val['vonx'];
                $vony = $val['vony'];
                $nachx = $val['nachx'];
                $nachy = $val['nachy'];

                if ($vony == $nachy)
                {
                    // horizontale Sperre
                    $ausgabe.='<div style="position: absolute; top: '.($data[$vony-1][$vonx-1]['ypos'] -1).'px; left:'.($data[$vony-1][$vonx-1]['xpos'] -5 +48).'px; z-index:2"><img src="./images/virgator/sperre_v.gif"></div>';
                }
                elseif ($vonx == $nachx)
                {
                    // vertikale Sperre
                    $ausgabe.='<div style="position: absolute; top: '.($data[$vony-1][$vonx-1]['ypos'] -5 +48).'px; left:'.($data[$vony-1][$vonx-1]['xpos'] - 1).'px; z-index:2"><img src="./images/virgator/sperre_h.gif"></div>';
                }
            }
        }

        //Spieler- und Gegnerposition
        if ($_GET['x']=='' && $_GET['y']=='')
        {
            $_GET['x']=$data['p_start']['x'];
            $_GET['y']=$data['p_start']['y'];
        }
        if ($_GET['g1x']=='' && $_GET['g1y']=='')
        {
            $_GET['g1x']=$data['opp_start']['x'];
            $_GET['g1y']=$data['opp_start']['y'];
        }

        // Zieldaten
        $zx=$data['exit']['x']; $zy=$data['exit']['y'];

        if ($_GET['x']== $zx && $_GET['y']==$zy)
        {
            $gewonnen=true;
            $ausgabe2.='`@<h2>Du hast die Prüfung bestanden</h2>`7';
            // schauen ob es eine Belohnung gibt
            // das solltest du anpassen! auf unserem Server gibt es besondere Items, die jedoch auf unserem Itemsystem basieren.
            //darum hier immer 100gold.
            if(($arr_virgator['virgator_level'] % 5) == 0)
            {
                switch ($arr_virgator['virgator_level'])
                {
                    case 5:
                        $session['user']['gold']+=100;
                        $ausgabe2.='<h2>Als Anerkennung deiner Leistung bekommst du 100 Gold.</h2>';
                        break;
                    case 10:
                        $session['user']['gold']+=100;
                        $ausgabe2.='<h2>Als Anerkennung deiner Leistung bekommst du 100 Gold.</h2>';
                        break;
                    case 15:
                        $session['user']['gold']+=100;
                        $ausgabe2.='<h2>Als Anerkennung deiner Leistung bekommst du 100 Gold.</h2>';
                        break;
                    case 20:
                        $session['user']['gold']+=100;
                        $ausgabe2.='<h2>Als Anerkennung deiner Leistung bekommst du 100 Gold.</h2>';
                        break;
                    case 25:
                        $session['user']['gems']++;
                        $ausgabe2.='<h2>Als Anerkennung deiner Leistung bekommst du einen Edelstein.</h2>';
                        break;
                    case 50:
                        $session['user']['gems']+=2;
                        $ausgabe2.='<h2>Als Anerkennung deiner Leistung bekommst du ZWEI Edelsteine.</h2>';
                        break;
                    case 75:
                        $session['user']['gems']+=3;
                        $ausgabe2.='<h2>Als Anerkennung deiner Leistung bekommst du DREI Edelsteine.</h2>';
                        break;
                    default:
                        $session['user']['gold']+=100;
                        $ausgabe2.='<h2>Als Anerkennung deiner Leistung bekommst du 100 Gold.</h2>';
                        break;
                }
            }

            $arr_virgator['virgator_level']++;
            user_set_aei($arr_virgator,$session['user']['acctid']);
            // nächstes Level

            addnav('Weiter');
            addnav('Nächste Prüfung',$str_filename);
            addnav('Zurück');
            addnav('Zum Eingang',$str_filename.'?op=eingang');
        }


        // ###########################################################
        // gegner Positionieren
        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        // REGEL: Gegner läuft solange horizontal bis er auf höhe des Players ist.
        // aktueller Stand einzeichen
        if ($_GET['sperre']=='')
        {
            $_GET['sperre']=1;
        }
        // damit der Gegner beim Start nicht gleich losläuft
        $j=1;
        $ausgabe.='<div style="position: absolute; top: '.($data[$_GET['g1y']][$_GET['g1x']]['ypos']).'px; left:'.($data[$_GET['g1y']][$_GET['g1x']]['xpos']+3).'px; z-index:3"><img src="./images/virgator/geg'.$j.'.gif"></div>';

        // Schritte berechnen
        for ($i=0; $i<2; $i++)
        {
            $move_check=true;
            if ($_GET['x']!=$_GET['g1x'])
            {
                $check=$data[$_GET['g1y']][$_GET['g1x']]['nav'];
                // links oder rechts?
                if ($_GET['x']<$_GET['g1x'])
                {
                    // Player westlich vom gegner
                    $move=substr_count($check,";w");
                    // darf der Gegner nach westen?
                    if ($move)
                    {
                        if ($_GET['sperre']==0)
                        {
                            // Gegner ist im Sand, wenn Sperre >0
                            $_GET['g1x']-=1;
                            // ein Schritt nach Westen
                            $move_check=false;
                            if ($data[$_GET['g1y']][$_GET['g1x']]['bild']=='s.gif')
                            {
                                // Gengner ist in Sand getappt
                                $i=2;
                                $_GET['sperre']=4;
                            }
                        }
                    }
                }
                else
                {
                    // Player östlich vom Gegner
                    $move=substr_count($check,";o");
                    // darf der Gegner nach Osten?
                    if ($move)
                    {
                        if ($_GET['sperre']==0)
                        {
                            // Gegner ist im Sand, wenn Sperre >0
                            $_GET['g1x']+=1;
                            // ein Schritt nach Osten
                            $move_check=false;
                            if ($data[$_GET['g1y']][$_GET['g1x']]['bild']=='s.gif')
                            {
                                // Gegner ist in Sand getappt
                                $i=2;
                                $_GET['sperre']=4;
                            }
                        }
                    }
                }
                // Schritt vom Gegner einzeichen
                if (!$move_check)
                {
                    $j++;
                    $ausgabe.='<div style="position: absolute; top: '.($data[$_GET['g1y']][$_GET['g1x']]['ypos']).'px; left:'.($data[$_GET['g1y']][$_GET['g1x']]['xpos']+3).'px; z-index:3"><img src="./images/virgator/geg'.$j.'.gif"></div>';
                }
            }
            if ($_GET['y']!=$_GET['g1y'] && $move_check)
            {
                $check=$data[$_GET['g1y']][$_GET['g1x']]['nav'];
                // hoch oder runter?
                if ($_GET['y']<$_GET['g1y'])
                {
                    // Player nördlich vom gegner
                    $move=substr_count($check,";n");
                    // darf der Gegner nach Norden?
                    if ($move)
                    {
                        if ($_GET['sperre']==0)
                        {
                            // Gegner ist im Sand, wenn Sperre >0
                            $_GET['g1y']-=1;
                            // ein Schritt nach Norden
                            if ($data[$_GET['g1y']][$_GET['g1x']]['bild']=='s.gif')
                            {
                                // Gengner ist in Sand getappt
                                $i=2;
                                $_GET['sperre']=4;
                            }
                        }
                    }
                }
                else
                {
                    // Player südlich vom Gegner
                    $move=substr_count($check,";s");
                    // darf der Gegner nach Süden??
                    if ($move)
                    {
                        if ($_GET['sperre']==0)
                        {
                            // Gegner ist im Sand, wenn Sperre >0
                            $_GET['g1y']+=1;
                            // ein Schritt nach Süden
                            if ($data[$_GET['g1y']][$_GET['g1x']]['bild']=='s.gif')
                            {
                                // Gengner ist in Sand getappt
                                $i=2;
                                $_GET['sperre']=4;
                            }
                        }
                    }
                }
                // Schritt vom Gegner einzeichen
                $move_check=false;
                $j++;
                $ausgabe.='<div style="position: absolute; top: '.($data[$_GET['g1y']][$_GET['g1x']]['ypos']).'px; left:'.($data[$_GET['g1y']][$_GET['g1x']]['xpos']+3).'px; z-index:3"><img src="./images/virgator/geg'.$j.'.gif"></div>';
            }
            if ($_GET['y']==$_GET['g1y']  && $_GET['x']==$_GET['g1x'] && $gewonnen!=true)
            {
                // Gegner hat Player gefangen
                if ($nicht_doppelt!=true)
                {
                    $ausgabe2.="<h1>Der Virgator hat dich erwischt!</h1>";
                    $show_nav=false;
                    $arr_virgator['virgator_count']++;
                    user_set_aei($arr_virgator,$session['user']['acctid']);
                    addnav("Zurück");
                    addnav("Zum Eingang",$str_filename."?op=eingang");
                    $nicht_doppelt=true;
                    // Pfusch-- Prüfungszeit, was soll man sagen?!?
                }
            }
        }
        // ende for schleife
        // letzte Position immer einzeichen
        $ausgabe.='<div style="position: absolute; top: '.($data[$_GET['g1y']][$_GET['g1x']]['ypos']).'px; left:'.($data[$_GET['g1y']][$_GET['g1x']]['xpos']+3).'px; z-index:3"><img src="./images/virgator/geg3.gif"></div>';

        // check wielange der Gegner noch im Sand ist
        if ($_GET['sperre']>0)
        {
            $_GET['sperre']-=1;
        }

        // schauen ob der Player im Sand , und somit tot ist!
        if ($data[$_GET['y']][$_GET['x']]['bild']=='s.gif')
        {
            $ausgabe2.="<h1>Du bist im Sand gefangen!</h1>";
            $show_nav=false;
            addnav("Zurück");
            addnav("Zum Eingang",$str_filename."?op=eingang");
        }

        // Navs des Players bestimmen:
        if ($show_nav)
        {
            // player nicht gefangen, oder nicht im Sand!
            $allnav=explode(";",$data[$_GET['y']][$_GET['x']]['nav']);
            $int_count = count($allnav);
            for ($i=1; $i<$int_count-1; $i++)
            {
                switch ($allnav[$i])
                {
                    case 'n':
                        $str_lnk = addnav("W? (W) Norden",$str_filename."?x=".($_GET['x'])."&y=".($_GET['y']-1)."&g1x=".$_GET['g1x']."&g1y=".$_GET['g1y']."&sperre=".$_GET['sperre']);
                        $goto[$_GET[x].($_GET[y]-1)]=true;
                        $quickkeys['arrowup'] = "window.location='".$str_lnk."'";
                        break;
                    case 'o':
                        $str_lnk = addnav("D? (D) Osten",$str_filename."?x=".($_GET['x']+1)."&y=".($_GET['y'])."&g1x=".$_GET['g1x']."&g1y=".$_GET['g1y']."&sperre=".$_GET['sperre']);
                        $goto[($_GET[x]+1).$_GET[y]]=true;
                        $quickkeys['arrowright'] = "window.location='".$str_lnk."'";
                        break;
                    case 's':
                        $str_lnk = addnav("S? (S) Süden",$str_filename."?x=".($_GET['x'])."&y=".($_GET['y']+1)."&g1x=".$_GET['g1x']."&g1y=".$_GET['g1y']."&sperre=".$_GET['sperre']);
                        $goto[$_GET[x].($_GET[y]+1)]=true;
                        $quickkeys['arrowdown'] = "window.location='".$str_lnk."'";
                        break;
                    case 'w':
                        $str_lnk = addnav("A? (A) Westen",$str_filename."?x=".($_GET['x']-1)."&y=".($_GET['y'])."&g1x=".$_GET['g1x']."&g1y=".$_GET['g1y']."&sperre=".$_GET['sperre']);
                        $goto[($_GET[x]-1).$_GET[y]]=true;
                        $quickkeys['arrowleft'] = "window.location='".$str_lnk."'";
                        break;
                }
            }
            if($access_control->su_check(access_control::SU_RIGHT_DEBUG))
            {
                addnav('Cheaten',$str_filename.'?op=cheat');
                addnav('Raus hier',$str_backlink);
            }
        }

        // Player Position einzeichen, wenn nicht im Ziel
        if ($_GET['x']!= $zx || $_GET['y']!=$zy) $ausgabe.='<div style="position: absolute; top: '.($data[$_GET['y']][$_GET['x']]['ypos']).'px; left:'.($data[$_GET['y']][$_GET['x']]['xpos']).'px; z-index:2"><img src="./images/virgator/player.gif"></div>';

        // Ziel einzeichnen
        if ($zx==-1)
        {
            // ganz links
            $top_px = $data[$zy][$zx+1]['ypos'];
            $left_px = $data[$zy][$zx+1]['xpos'] -48;
            $z_index = 1;
        }
        elseif ($zx==6)
        {
            // ganz rechts
            $top_px = $data[$zy][$zx-1]['ypos'];
            $left_px = $data[$zy][$zx-1]['xpos'] +48;
            $z_index = 1;
        }
        elseif ($zy==-1)
        {
            // ganz oben
            $top_px = $data[$zy+1][$zx]['ypos'] -48;
            $left_px = $data[$zy+1][$zx]['xpos'];
            $z_index = 10;
        }
        elseif ($zy==6)
        {
            // ganz unten
            $top_px = $data[$zy-1][$zx]['ypos'] +48;
            $left_px = $data[$zy-1][$zx]['xpos'];
            $z_index = 10;
        }
        $ausgabe.='<div style="position: absolute; top: '.$top_px.'px; left:'.$left_px.'px; z-index:'.$z_index.'"><img src="./images/virgator/ziel.gif"></div>';
        // Ziel eingezeichnet

        // Karte ausgeben:
        for ($i=0; $i<6; $i++) //Wieso fangen die beiden Schleifen bei -1 an? Geändert auf 0 von Salator, Werbung dürfte nun nicht mehr im Bild sein.
        {
            for ($j=0; $j<6; $j++)
            {
                $ausgabe.='
                <div style="position: absolute; top: 0'.($data[$i][$j]['ypos']).'px; left: 0'.($data[$i][$j]['xpos']).'px; z-index:1">';

                if((($j==$_GET['x'] && ($i==$_GET['y']-1 || $i==$_GET['y']+1)) || ($i==$_GET['y'] && ($j==$_GET['x']-1 || $j==$_GET['x']+1))) && $goto[$j.$i] && $show_nav)
                {
                    $ausgabe.='<a href='.$str_filename.'?x='.$j.'&y='.$i.'&g1x='.$_GET[g1x].'&g1y='.$_GET[g1y].'&sperre='.$_GET[sperre].'>';
                    if ($j==$zx && $i==$zy)
                    {
                        $ausgabe.='<div style="position: absolute; top: '.$top_px.'px; left:'.$left_px.'px; z-index:'.$z_index.'"><img border="0" src="./images/virgator/ziel.gif"></a></div>';
                    }
                    else $ausgabe.='<img border="0" src="./images/virgator/'.$data[$i][$j]['bild'].'"></a></div>';
                    addnav("",$str_filename.'?x='.$j.'&y='.$i.'&g1x='.$_GET[g1x].'&g1y='.$_GET[g1y].'&sperre='.$_GET[sperre]);
                }
                elseif($data[$i][$j]['bild']!='') $ausgabe.='<img src="./images/virgator/'.$data[$i][$j]['bild'].'"></div>';
            }
        }
        output($ausgabe,true);
        output("</div></center><br>",true);
        output($ausgabe2,true);
    }
    break;
}

page_footer();
?>


