
<?php

require_once ('common.php');

page_header("Beleidigter Pirat-Test");

function in_multi_array($needle, $haystack)
{
    $in_multi_array = false;
    if(in_array($needle, $haystack))
    {
        $in_multi_array = true;
    }
    else
    {   
        for($i = 0; $i < sizeof($haystack); $i++)
        {
            if(is_array($haystack[$i]))
            {
                if(in_multi_array($needle, $haystack[$i]))
                {
                    $in_multi_array = true;
                    break;
                }
            }
        }
    }
    return $in_multi_array;
} 

$out='`n`n';
        $fragen = array(1 =>'Mein Schwert wird dich aufspießen wie einen Schaschlik!',
            2 =>'Deine Fuchtelei hat nichts mit Fechtkunst zu tun!',
            3 =>'Niemand wird mich verlieren sehen, auch du nicht!',
            4 =>'Meine Großmutter hat mehr Kraft als du Wicht!',
            5 =>'Nach diesem Spiel trägst du den Arm in Gips.',
            6 =>'Aargh ... ich zerreiße deine Hand in eine Million Fetzen.',
            7 =>'Aaagh ... hey, schau mal da drüben!',
            8 =>'Aargh ... ich werde deine Knochen zu Brei zermalmen.',
            9 =>'Ich kenne Läuse mit stärkeren Muskeln.',
            10 =>'Alle Welt fürchtet die Kraft meiner Faust.',
            11 =>'Ungh ... gibt es auf dieser Welt eine größere Memme als dich?',
            12 =>'Ungh ... du bist das hässlichste Wesen, das ich jemals sah ... grr.',
            13 =>'Ungh ... viele Menschen sagen, meine Kraft ist unglaublich.',
            14 =>'Ungh ... Ich hab\' mit diesen Armen schon Kraken bezwungen.',
            15 =>'Ungh, ha ... sehe ich da Spuren von Angst in deinem Gesicht?',
            16 =>'Ich hatte mal einen Hund, der war klüger als du.',
            17 =>'Du hast die Manieren eines Bettlers.',
            18 =>'Jeder hier kennt dich als unerfahrenen Dummkopf.',
            19 =>'Du kämpfst wie ein dummer Bauer.',
            20 =>'Meine Narbe im Gesicht stammt aus einem harten Kampf.',
            21 =>'Menschen fallen mir zu Füßen, wenn ich komme.',
            22 =>'Dein Schwert hat schon bessere Zeiten gesehen.',
            23 =>'Du bist kein Gegner für mein geschultes Gehirn.',
            24 =>'Trägst du immer noch Windeln?',
            25 =>'An deiner Stelle würde ich zur Landratte werden.',
            26 =>'Alles, was du sagst, ist dumm.',
            27 =>'Hast du eine Idee, wie du hier lebend herauskommst?',
            28 =>'Mein Schwert wird dich in 1000 Stücke reißen.',
            29 =>'Niemand wird sehen, dass ich so schlecht kämpfe wie du.',
            30 =>'Nach dem letzten Kampf war meine Hand blutüberströmt.',
            31 =>'Kluge Gegner laufen weg, bevor sie mich sehen.',
            32 =>'Überall in der Gegend kennt man meine Klinge.',
            33 =>'Bis jetzt wurde jeder Gegner von mir eliminiert!',
            34 =>'Du bist so hässlich wie ein Affe im Negligé!',
            35 =>'Dich zu töten wäre eine legale Beseitigung!',
            36 =>'Warst Du schon immer so hässlich oder bis du mutiert?',
            37 =>'Ich spieß\' Dich auf wie eine Sau am Buffet!',
            38 =>'Wirst du laut Testament eingeäschert oder einbalsamiert?',
            39 =>'Ein Jeder hat vor meiner Schwertkunst kapituliert!',
            40 =>'Ich werde Dich richten - und es gibt kein Plädoyer!',
            41 =>'Himmel bewahre! Für einen Hintern wäre dein Gesicht eine Beleidigung!',
            42 =>'Fühl ich den Stahl in der Hand, bin ich in meinem Metier!',
            43 =>'Haben sich deine Eltern nach deiner Geburt sterilisiert?',
            44 =>'En garde! Touché!',
            45 =>'Überall im Drachental wird mein Name respektiert!',
            46 =>'Niemand kann mich stoppen: mich - den Schrecken der See!',
            47 =>'Mein Minenspiel zeigt Dir meine Missbilligung!',
            48 =>'Ganze Inselreiche haben vor mir kapituliert',
            49 =>'Du hast soviel Sexappeal wie ein Croupier!',
            50 =>'Bist Du das? Es riecht hier so nach Jauche und Dung!',
            51 =>'Wurdest Du damals von einem Schwein adoptiert?',
            52 =>'Auch wenn Du es nicht glaubst, aus Dir mach\' ich Haschee!',
            53 =>'Ich lass\' dir die Wahl: erdolcht, erhängt oder guillotiniert!',
            54 =>'Dein Geplänkel bringt mich richtig in Schwung!',
            55 =>'Ich weiß nicht, welche meiner Eigenschaften Dir am meisten imponiert!',
            56 =>'Jetzt werde ich dich erstechen, da hilft kein Protegée!',
            57 =>'Ist ein Blick in den Spiegel nicht jeden Tag für dich eine Erniedrigung?',
            58 =>'Ich lauf\' auf glühenden Kohlen und barfuß im Schnee!',
            59 =>'Du bist eine Schande für Deine Gattung, so dilettiert!',
            60 =>'Deine Mutter trägt ein Toupet!',
            61 =>'Durch meine Fechtkunst bin ich zum Siegen prädestiniert!',
            62 =>'Es mit mir aufzunehmen gleicht einer Odyssee!',
            63 =>'Mein Antlitz zeugt von edler Abstammung!',
            64 =>'Ungh ... Memmen wie dich vernasch\' ich zum Frühstück.',
            65 =>'Ich habe Muskeln an Stellen, von denen du nichts ahnst.',
            66 =>'Gib auf oder ich zerquetsch\' dich wie eine lästige Mücke.',
            67 =>'Allein mit meinem Bart bin ich so adrett...',
            68 =>'Ich könnte dich besiegen!',
            69 =>'Du hast dein Maul aber sehr weit offen.',
            70 =>'Deine Frau hat mich zum Stöhnen gebracht!',
            71 =>'Ungh ... Du bist ein großer Eierkopf.',
            72 =>'Willst du hören, wie ich drei Männer zugleich besiegte?',
            73 =>'Mit meinem Taschentuch werde ich dein Blut aufwischen!',
            74 =>'Hast du mal Feuer?',
            75 =>'Dich zu töten wird eine Erlösung für mich sein!',
            76 =>'Ich werf dich in den See und ertränke dich!',
            77 =>'Dein Geschlecht ist doch nicht echt, du gibst nur vor, so zu sein!',
            78 =>'Ich verfolge dich überall hin.',
            79 =>'Ich hol gleich meine Brüder.. ach was, meine ganze Familie!',
            80 =>'Ich gehöre zu den oberen Zehntausend, du Nichts!',
            81 =>'Du bist nicht besonders nett zu mir.',
            82 =>'Lass uns etwas zusammen machen, was mir sehr gefällt.',
            83 =>'Du hast die Wahl, arbeite beim Bauern oder tanze mit mir!',
            84 =>'Warum bist du immer anderer Ansicht als ich?',
            85 =>'Du hast wirklich Mut, mir immer noch ins Gesicht zu sehen!',
            86 =>'Kannst du eigentlich gar nichts?',
            87 =>'Ich kenne einige Affen, die haben mehr drauf, als du.',
            88 =>'Mein Name ist in jeder dreckigen Ecke gefürchtet.',
            89 =>'Dein verbogenes Schwert wird mich nicht berühren.',
            90 =>'Ich habe nur einmal einen Feigling wie dich getroffen.',
            91 =>'Jetzt gibt es keine Finten mehr, die dir helfen.',
            92 =>'Nach jedem Kampf war meine Hand blutüberströmt.',
            93 =>'Sind alle Männer so? Dann heirate ich ein Schwein.'
        );
        
        $antworten = array(1 =>'Dann mach damit nicht rum, wie mit dem Staubwedel.',
            2 =>'Doch, doch, du hast sie nur nie gelernt.',
            3 =>'Du kannst so schnell davon laufen?',
            4 =>'Dafür hab\' ich in der Hand nicht die Gicht!',
            5 =>'Das sind große Worte für \'nen Kerl ohne Grips!',
            6 =>'Grrrgh! Ich wusste gar nicht, dass du so weit zählen kannst. Aargh!',
            7 =>'Ja, ja, ich weiß, ein dreiköpfiger Affe!',
            8 =>'Ungh! Ich werde mich wehren, bis die Griffel dir qualmen!',
            9 =>'Aargh! Behalt sie für dich, sonst bekomm\' ich noch Pusteln!',
            10 =>'Ungh ... wobei mir vor allem vor deinem Atem graust.',
            11 =>'Ungh! Sie sitzt mir gegenüber, also was fragst du mich?',
            12 =>'Ungh! Mit Ausnahme von deiner Frau, soviel ist klar!',
            13 =>'Aargh! Unglaublich erbärmlich, das sag\' ab jetzt ich.',
            14 =>'Ungh! Und Babys wohl auch, na, der Witz ist gelungen.',
            15 =>'Das ist ein Lachen, du schwächlicher Wicht!',
            16 =>'Er muss dir das Fechten beigebracht haben.',
            17 =>'Ich wollte, dass du dich wie zuhause fühlst.',
            18 =>'Zu schade, dass dich überhaupt keiner kennt.',
            19 =>'Ich schaudere, ich schaudere.',
            20 =>'Aha, mal wieder in der Nase gebohrt, wie?',
            21 =>'Auch bevor sie deinen Atem riechen?',
            22 =>'Und du wirst deine rostige Klinge nie wieder sehen.',
            23 =>'Vielleicht solltest du es endlich mal benutzen.',
            24 =>'Wieso, die könntest du viel eher brauchen.',
            25 =>'Hattest du das nicht vor kurzem getan?',
            26 =>'Ich wollte, dass du dich wie zuhause fühlst.',
            27 =>'Wieso, die könntest du viel eher brauchen.',
            28 =>'Dann mach damit nicht rum, wie mit dem Staubwedel.',
            29 =>'Du kannst so schnell davon laufen?',
            30 =>'Aha, mal wieder in der Nase gebohrt, wie?',
            31 =>'Auch bevor sie deinen Atem riechen?',
            32 =>'Zu schade, dass dich überhaupt keiner kennt.',
            33 =>'Das war ja auch leicht, dein Atem hat sie paralysiert!',
            34 =>'Hoffentlich zerrst Du mich nicht ins Separée!',
            35 =>'Dich zu töten, wäre dann eine legale Reinigung!',
            36 =>'Da hat sich wohl dein Spiegelbild in meinem Säbel reflektiert!',
            37 =>'Wenn ich mit dir fertig bin, bist du nur noch Filet!',
            38 =>'Sollt\' ich in deiner Nähe sterben, möcht\' ich, dass man mich desinfiziert!',
            39 =>'Dein Geruch allein reicht aus, und ich wär\' kollabiert!',
            40 =>'Dass ich nicht lache! Du und welche Armee?',
            41 =>'In Formaldehyd aufbewahrt trügest Du bei zu meiner Erheiterung.',
            42 =>'Ich glaub\', es gibt für dich noch eine Stelle beim Varieté!',
            43 =>'Zumindest hat man meine identifiziert!',
            44 =>'Oh, das ist ein solch übles Klischee!',
            45 =>'Zu schade, dass das hier Niemand tangiert!',
            46 =>'Ich könnte es tun, hättest Du nur ein Atemspray!',
            47 =>'Für dein Gesicht bekommst du \'ne Begnadigung.',
            48 =>'Das war ja auch leicht, dein Atem hat sie paralysiert!',
            49 =>'Hoffentlich zerrst Du mich nicht ins Separée!',
            50 =>'Dich zu töten, wäre dann eine legale Reinigung!',
            51 =>'Da hat sich wohl dein Spiegelbild in meinem Säbel reflektiert!',
            52 =>'Wenn ich mit dir fertig bin, bist du nur noch Filet!',
            53 =>'Sollt\' ich in deiner Nähe sterben, möcht\' ich, dass man mich desinfiziert!',
            54 =>'Dann wäre koffeinfreier Kaffee ein erster Schritt zur Läuterung!',
            55 =>'Dein Geruch allein reicht aus, und ich wär\' kollabiert!',
            56 =>'Dass ich nicht lache! Du und welche Armee?',
            57 =>'In Formaldehyd aufbewahrt trügest du bei zu meiner Erheiterung.',
            58 =>'Ich glaub\', es gibt für dich noch eine Stelle beim Varieté!',
            59 =>'Zumindest hat man meine identifiziert!',
            60 =>'Oh, das ist ein solch übles Klischee!',
            61 =>'Zu schade, dass das hier Niemand tangiert!',
            62 =>'Ich könnte es tun, hättest du nur ein Atemspray!',
            63 =>'Für dein Gesicht bekommst du \'ne Begnadigung.',
            64 =>'Ungh ... wobei mir vor allem vor deinem Atem graust.',
            65 =>'Oooh. Zu schade, dass keine davon in diesen Armen ist.',
            66 =>'Wenn ich mit dir fertig bin, brauchst du \'ne Krücke!',
            67 =>'Ja, sogar deine Schwester findet ihn in der Nacht nett...',
            68 =>'Dann wird deine Frau dich kriegen!',
            69 =>'Dann wolln wir mal auf keine Maulsperre hoffen.',
            70 =>'Damit hat sie kein großes Wunder vollbracht!',
            71 =>'Argh... und DU bist ein armer Tropf!',
            72 =>'Willst du mich mit Deinem Geschwafel ermüden?',
            73 =>'Also hast Du doch den Job als Putze gekriegt?',
            74 =>'Piek dir ins Auge, das brennt auch!',
            75 =>'Und dich zu töten eine Erlösung für alle!',
            76 =>'Ich halt mich an dir fest, Fett schwimmt ja!',
            77 =>'Frag doch deine Mutter, der weiß das.',
            78 =>'Ich würd dich ja gern mitnehmen, aber mein Vermieter erlaubt kein Ungeziefer!',
            79 =>'Das ist keine Familie, das ist ein Laborexperiment!',
            80 =>'Stimmt, du bist die letzte Null!',
            81 =>'Das war Mutter Natur wohl auch nicht.',
            82 =>'Ich will nicht im Ohr bohren und dabei grinsen wie ein Idiot.',
            83 =>'Lieber eine Kuh melken, als mit nem Ochsen ringen!',
            84 =>'Weil wir sonst beide unrecht hätten!',
            85 =>'Man gewöhnt sich an Alles.',
            86 =>'Moment, das ist doch dein Spezialgebiet...',
            87 =>'Aha, du warst also beim letzten Familientreffen.',
            88 =>'Also hast du doch den Job als Putze gekriegt?',
            89 =>'Und du wirst deine rostige Klinge nie wieder sehen.',
            90 =>'Er muss dir das Fechten beigebracht haben.',
            91 =>'Doch, doch, du hast sie nur nie gelernt.',
            92 =>'Aha, mal wieder in der Nase gebohrt, wie?',
            93 =>'Hattest du das nicht vor Kurzem getan?'
        );
        
if(in_array('Und du wirst deine rostige Klinge nie wieder sehen.',$antworten))
    output('`$Und du wirst deine rostige Klinge nie wieder sehen.`0 ist im Array \$antworten enthalten!`n`n');


if(!in_array('Doch, doch, du hast sie nur nie gelernt.',$antworten))
    output('`$Doch, doch, du hast sie nur nie gelernt.`0 ist im Array \$antworten nicht enthalten!`n`n');



//Thx to Eliwood
        $schonausgwaehlteaws_id = array();
        $schonausgwaehltefragenundantworten = array();
        $awtotal = count($antworten);
        $zustellendeaw = 4; # 3 Fragen stellen
        for($c = 1; $c <= $zustellendeaw; $c++) {
            while(1) {
                $fragennummer = mt_rand(1, $awtotal);
                $_SESSION['session']['richtigeaw'] = $fragennummer;
                if(!isset($schonausgwaehlteaws_id[$fragennummer])) {
//                    if(!in_array($antworten[$fragennummer],$schonausgwaehltefragenundantworten)) output('`$ '.$antworten[$fragennummer].' `7ist nicht drin!`n`n');
//                    else output('`$ '.$antworten[$fragennummer].' `7ist drin!`n`n');
                    $schonausgwaehlteaws_id[$fragennummer] = true;
                    $schonausgwaehltefragenundantworten[$antworten[$fragennummer]]= array(0=>"<a href=forest.php?op=kampf&id=$fragennummer>`($antworten[$fragennummer]</a>" ,1=>$fragennummer);
                if(in_multi_array($antworten[$fragennummer],$schonausgwaehltefragenundantworten)) output('`^ '.$antworten[$fragennummer].' `7ist da drin!`n`n');
                    else output('`^ '.$antworten[$fragennummer].' `7ist nicht drin!`n`n');
//                    allownav("forest.php?op=kampf&id=$fragennummer");                    
                    break;
                }
            }
        }
        print_r($schonausgwaehltefragenundantworten);

    
addnav('Zurück','superuser.php');    

page_footer();

