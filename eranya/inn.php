
<?php
/***
 * neue Schenke
 *
 ***/
require_once('common.php');
require_once(LIB_PATH.'board.lib.php');
// Voreinstellungen - Farben
define('INNCOLORHEAD','`Ç');
define('INNCOLORTEXT','`P');
define('INNCOLORMAREK','`É');
define('INNCOLORSILAS','`F');
define('INNCOLOROPHELIA','`V');
define('INNCOLORTAFEL','`Y');
// Kosten pro Ale
define('ALECOST',10);
// end
// Für Lebenskraft-Trank
function get_lp_gems() {
    global $session;
    $lp_max = get_max_hp();
    $val = 1 + ceil(max($session['user']['maxhitpoints'] - $lp_max, 0 ) * 0.01 );
    return(min((int)$val , 15 ) );
}
// end
// Knappe laden
$arr_disc = get_disciple($session['user']['acctid']);
$bool_has_disc = (is_array($arr_disc) && $arr_disc['state'] > 0 && $arr_disc['at_home'] == 0 ? true : false);
// end
// Array mit Spendernamen & deren AcctIDs - sortiert nach Spendenumfang, angefangen mit dem spendabelsten Char (s. Zahlen rechts neben den Einträgen)
$arr_spender = array('names'=>array('°#424242;M°#535353;a°#646464;u°#767676;e°#878787;r°#989898;b°#A9A9A9;l°#BABABA;ü°#CBCBCB;m°#DDDDDD;c°#D2CDCF;h°#C7BDC1;e°#BBADB2;n °#B09DA4;E°#A58C96;l°#9A7C88;e°#8E6C79;n°#835C6B;a`0'    # 161911 Gold, 152 ES
                                   ,'`wB`Ke`Ün`pj`Pa`^m`Fi`^n `PM`pe`Üi`Ke`wr`0'                                                                                                                                                 # 168000 Gold, 140 ES
                                   ,'`ÒH`ñy`Ýe`çl`²í`Ûn `ÛS`²y`çï`Ýe`ñl`Òa`0'                                                                                                                                                    #  84566 Gold, 150 ES
                                   ,'`ÄV`Wo`är`Öd`]h`[u`{r `vK`ýj`7e`ßl`òl`Ts`mo`Zn`0'                                                                                                                                           #  89500 Gold, 130 ES
                                   ,'°#392e2a;V°#4b4f48;e°#5e7067;n°#719185;t°#83b2a4;a°#96d3c2;r°#a9f5e1;r°#cef6ec;ón °#a9f5e1;C°#96d3c2;a°#83b2a4;s°#719185;t°#5e7067;i°#4b4f48;e°#392e2a;l`0'                                 #  90342 Gold,  90 ES
                                   ,'`ÓS`âé`mv`Te`òr`ßi`=n `ÓC`âa`mn`Tt`òe`ßá`=s`0'                                                                                                                                              #  48596 Gold,  90 ES
                                   ,'`ÂI`Sl`)l`*u`+s`Üi`Ko`1n`Éi`+s`*t `ÜS`)a`St`Îr`Âe`0'                                                                                                                                        #  84000 Gold,  70 ES
                                   ,'`MA`Nu`ls`ñs`aä`Zt`fz`xi`zg`Ye `zG`xw`fe`Zn`ad`ño`ll`Ny`Mn`0'                                                                                                                               # 117000 Gold,  10 ES
                                   ,'`ÿP`Ýy`Ñt`ûh`yi`ça `²F`va`+r`*a`¢y`£a`0')                                                                                                                                                   #  48000 Gold,  20 ES
                    ,'acctids'=>array(2,1320,163,979,3,1353,820,985,942));
// end
// Das Übliche
page_header('Zum Lachenden Fass');
$str_schenkenname = '`ÇZ`(u`Pm `8L`Fa`qc`óhend`qe`Fn `8F`Pa`(s`Çs';
$str_filename = basename(__FILE__);
$str_tout = '';
// end Übliches
// Los geht's!
$str_op = (isset($_GET['op']) ? $_GET['op'] : '');
switch($str_op) {
    // Eingangsbereich / Begrüßung
    case 'strolldown':
    case '':
        output(INNCOLORHEAD.'`c`b'.$str_schenkenname.'`b`c`n'.
               INNCOLORTEXT.'Noch bevor deine Augen sich an die Lichtverhältnisse gewöhnt haben, verrät dir deine Nase schon, in welche Lokalität es dich gerade
               verschlagen hat: Der Dunst von Tabak und Alkohol wetteifert mit verbrauchter Luft, während gleichzeitig feine Gerüche nach Braten und
               Kräutern darauf schließen lassen, dass die Schenke neben alkoholischen Getränken auch mit herzhaften Gerichten aufwartet. Du gehst einen Schritt in
               den Raum hinein, der bis ins hinterste Eck mit Tischgruppen gefüllt ist. Lediglich um die Bar herum, die sich dir gegenüber am anderen Ende des
               Raums befindet, wurde genug Platz gelassen, sodass sich dort regelmäßig Trauben aus wartenden, durstigen Gästen bilden können.`n
               Du schlängelst dich an den - oft besetzten - Tischen vorbei und hebst dabei kurz die Hand, um Silas zu grüßen, der sich nahe des Kamins mit seiner
               Laute niedergelassen hat. Auch nach Ophelía hältst du Ausschau, doch die kurvige Kellnerin ist gerade damit beschäftigt einen allzu aufdringlichen
               Schenkengast gekonnt abzuwimmeln, während sie gleichzeitig seine Bestellung aufnimmt. Aus den Augenwinkeln nimmst du wahr, wie Marek sich
               hinter dem Tresen den nächsten Krug nimmt, um ihn unter den Zapfhahn zu halten. Nicht ein Blick geht in deine Richtung und doch bist du dir
               ziemlich sicher, dass der Wirt und Schenkeninhaber dich bereits bemerkt hat. Somit scheint alles wie immer zu sein, wäre da nicht die
               große, goldene Tafel, die schräg hinter der Bar an der Wand hängt und jedem Besucher sofort ins Auge springt - eine Erinnerung daran, wie `ineu`i
               der große Schenkenraum eigentlich noch ist.`n
               Vergnügt schreitest du also voran und suchst dir ein freies Plätzchen, von dem aus dir weitere Beobachtungen ungeniert möglich sind. Dabei bleibt dein Blick an zwei kleinen
               Figürchen hängen, die in den Rahmen der Eingangstür geschnitzt sind. Erst beim zweiten Hingucken fällt dir auf, dass es sich sogar um kleine Statuen handelt, die jedoch nicht - 
               wie fälschlicherweise angenommen - zwei Kinder darstellen, sondern Zwerge! Sie tragen Rüstungen und Waffen, sowie triumphierende Grinsen in ihren mehr oder weniger bärtigen
               Gesichtern. Trübe, rote Steine sind dort platziert, wo ihre Augen sind. Irritiert über dieses unerwartete Detail fragst du dich, welche Geschichte wohl hinter
               dieser Dekoration steckt und beschließt, an genau dem richtigen Ort zu sein um diesem Rätsel auf die Spur zu kommen.
               `n`n');
        board_view('inn',(su_check(SU_RIGHT_COMMENT))?2:1,
                   'Am schwarzen Brett neben der Tür flattern einige Nachrichten im Luftzug:',
                   'Am schwarzen Brett neben der Tür hängen gerade keine Nachrichten aus.');
        addnav('Schankraum');
        addnav('u?Mit Silas unterhalten',$str_filename.'?op=silas');
        if($session['user']['seenlover'] == 0 && $session['user']['charm'] < 50) {      # Mit Ophelía/Silas flirten (bis max. 50 CP - Starthilfe)
            addnav('i?Mit '.($session['user']['sex'] ? 'Silas' : 'Ophelía').' flirten',$str_filename.'?op=flirt');
        }
        addnav('B?An die Bar setzen',$str_filename.'?op=marek');
        addnav('E?Mit Erik Diniven sprechen','dag.php');
        addnav('R?Mit Rimgar sprechen','olddrawl.php');
        addnav('Rechts vom Eingang');
        addnav('p?Spender-Tafel',$str_filename.'?op=donators');
        // Hat User einen Knappen?
        if($bool_has_disc) {
            addnav('Knapp'.($arr_disc['sex'] ? 'in' : 'e'));
            addnav('A?'.$arr_disc['name'].' `0ein Ale ausgeben ('.($session['user']['level']*ALECOST).' Gold)',$str_filename.'?op=disc');
            addnav('O?Ophelía besuchen','knappentraining.php?npc=ophelia');
        }
        // end
        addnav('Unterhaltung');
        addnav('T?An einen Tisch setzen',$str_filename.'?op=converse');
        //addnav('Cocktail mischen',$str_filename.'?op=cocktail');             # clyrax - Idee: User mixt Cocktail und verschenkt ihn/trinkt ihn selbst
        if (item_count(' tpl_id="dineinl" AND owner='.$session['user']['acctid']) ) {
            addnav("z?Zum Dinner bei `hKerzenschein`0","dinner.php");
        }
        addnav('Spieltische');
        addnav('DragonMind','dragonmind.php',false,false,false,false);
        addnav('Dart','dart.php',false,false,false,false);
        addnav('Übernachtung');
        addnav('m?Ein Zimmer mieten (Logout)',$str_filename.'?op=room');
        /*addnav('Privater RP-Ort');
        addnav('I?Ins Schenkenzimmer','privplaces.php?rport=room');*/
        addnav('Zurück');
        addnav('S?Zum Stadtplatz','village.php');
    break;
    // Mit Ophelía/Silas flirten - Chance auf einen Charmepunkt
    case 'flirt':
        $str_tout .= INNCOLORHEAD.'`c`b'.$str_schenkenname.'`b`c`n'.INNCOLORTEXT.
                     (!$session['user']['sex'] ?
                           'Du richtest dich zu deiner vollen Größe auf und wartest, bis Ophelía in deine Richtung schaut. Dann erwiderst du wie zufällig ihren
                            Blick und grinst ihr zu..`n`n'
                         : 'Du näherst dich dem Barden langsam und gibst ihm Zeit, dich zu bemerken. Tatsächlich schweift Silas\' Blick kurz darauf in deine
                            Richtung und bleibt an dir hängen. Du schenkst ihm dein zauberhaftestes Lächeln..`n`n'
                     );
        $int_chance = e_rand(0,3);
        if($int_chance == 2) {          # User gewinnt CP
            $str_tout .= (!$session['user']['sex'] ?
                               '.., was sie mit einem gut gelaunten Lächeln beantwortet. Du verlierst keine Zeit und schiebst dich an den anderen Schenkengästen
                                vorbei, bis du bei der Kellnerin angelangst und ihr kurzerhand den Arm um die Taille legst. Ophelía fängt daraufhin zu lachen an
                                und lehnt sich leicht gegen dich, während sie dir zuzwinkert. '.INNCOLOROPHELIA.'"Hab ich ein Glück, von einem solch attraktiven
                                Mann angegraben zu werden"'.INNCOLORTEXT.', meint sie grinsend und drückt dir einen Kuss auf die Wange.`n
                                `n
                                `^Du erhältst einen Charmepunkt.'
                             : '..und erntest ein Grinsen seinerseits, zusammen mit einem einladenden Nicken.`n
                                Du verwickelst den Barden in ein lockeres Gespräch und hörst dir seine Geschichten an. Eine davon bringt dich herzlich zum Lachen,
                                woraufhin sich Silas näher zu dir beugt und dir eine Haarsträhne hinters Ohr streicht. '.INNCOLORSILAS.'"Du solltest öfter lachen"
                               '.INNCOLORTEXT.', raunt er dir zu, '.INNCOLORSILAS.'"denn dann siehst du noch hübscher aus als sonst." '.INNCOLORTEXT.'Das
                               Kompliment zaubert eine feine Röte auf deine Wangen und ein Lächeln auf deine Lippen.`n
                               `n
                               `^Du erhältst einen Charmepunkt.`n`n'
                         );
            $session['user']['charm']++;
        } elseif($int_chance == 1) {    # User verliert CP
            $str_tout .= (!$session['user']['sex'] ?
                               '..und erntest ein Lächeln ihrerseits - glaubst du. Als du dann allerdings bei der Kellnerin ankommst, lässt sie dich komplett
                                links liegen und winkt stattdessen einem Mann hinter dir zu, der dich auch prompt zur Seite stößt, um Ophelía einen Arm um die
                                Taille zu legen. Vor aller Augen plumpst du ziemlich ungalant auf deinen Hosenboden, was dir einige Lacher einbringt. Wie peinlich...`n
                                `n
                                `QDu verlierst einen Charmepunkt.`n`n'
                             : '..und erntest ein Lächeln seinerseits - glaubst du. Als du dann allerdings bei dem Barden ankommst, lässt er dich komplett links
                                liegen und winkt stattdessen einer Frau hinter dir zu, die sich auch prompt an dir vorbei drängelt und dich dabei zu Fall bringt.
                                Vor aller Augen plumpst du ziemlich ungalant auf deinen Hosenboden, was dir einige Lacher einbringt. Wie peinlich...`n
                                `n
                                `QDu verlierst einen Charmepunkt.`n`n'
                         );
            $session['user']['charm']--;
        } else {                        # User bekommt nix
            $str_tout .= (!$session['user']['sex'] ?
                               '.., doch Ophelía lupft nur eine Braue und wird dann abgelenkt von einem anderen Schenkengast. Zuerst irritiert dich ihre Reaktion,
                                aber wer nicht will, der hat schon, hm? Schulterzuckend wendest du dich anderem zu.`n`n'
                             : '.., doch Silas lupft nur eine Braue und wird dann abgelenkt von einem anderen Schenkengast. Zuerst irritiert dich seine Reaktion,
                                aber wer nicht will, der hat schon, hm? Schulterzuckend wendest du dich anderem zu.`n`n'
                         );
        }
        $session['user']['seenlover'] = 1;
        addnav('Zurück');
        addnav('Zum Schankraum',$str_filename);
    break;
    // Von Silas unterhalten lassen
    case 'silas':
        if($_GET['subop']=="hear") {
            $rowe = user_get_aei('seenbard');
            if ($rowe['seenbard']) {
                output(INNCOLORTEXT."Silas räuspert sich und trinkt einen Schluck Wasser. ".INNCOLORSILAS."\"Tut mir Leid, mein Hals ist einfach zu trocken.\"");
                // addnav("Return to the inn","inn.php");
            } else {
                user_set_aei(array('seenbard'=>1));
                $rnd = e_rand(0,18);
                output(INNCOLORTEXT."Silas räuspert sich und fängt an:`n`n".INNCOLORSILAS);
                switch($rnd) {
                case 0:
                    output(INNCOLORSILAS."\"`@Grüner Drache".INNCOLORSILAS." ist grün.`n`@Grüner Drache".INNCOLORSILAS." ist wild.`n`@Grünen Drachen".INNCOLORSILAS." wünsch ich
                            mir gekillt.\"
                            `n`n`^Du erhältst ZWEI zusätzliche Waldkämpfe für heute!");
                    $session['user']['turns']+=2;
                    break;
                case 1:
                    output(INNCOLORSILAS."\"Mireraband, ich spotte Euch und spuck auf Euren Fuß.`nDenn er verströmt fauligen Gestank mehr als er muss!\"
                            `n`n`^Du fühlst dich erheitert und bekommst einen extra Waldkampf.");
                    $session['user']['turns']++;
                    break;
                case 2:
                    if ($session['user']['prefs']['sounds']) {
                        output("<embed src=\"media/ragtime.mid\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);
                    }
                    output(INNCOLORSILAS."\"Membrain Mann, Membrain Mann.`nMembrain Mann hasst ".$session['user']['name'].INNCOLORSILAS.", Mann.`nSie haben einen
                            Kampf, Membrain gewinnt.`n
                            Membrain Mann.\"
                            `n`n`^Du bist dir nicht ganz sicher, was du davon halten sollst... du gehst lieber wieder weg und denkst, es ist besser, Silas wieder
                            zu besuchen, wenn er sich besser fühlt. Nach einer kurzen Verschnaufpause könntest du wieder ein paar böse Jungs verprügeln.");
                    $session['user']['turns']++;
                    break;
                case 3:
                    output(INNCOLORSILAS."\"Für eine Geschichte versammelt euch hier`neine Geschichte so schrecklich und hart`nüber Marek und sein gepanschtes Bier`nund wie
                            sehr er ihn hasst, mich, den Bard'!\"
                            `n`n`^Du stellst fest, dass er Recht hat, Mareks Bier ist wirklich eklig. Das dürfte der Grund dafür sein, warum die meisten Gäste
                            sein Ale bevorzugen. Du kannst der Geschichte von Silas nicht wirklich etwas abgewinnnen, aber du findest dafür etwas Gold auf dem
                            Boden!");
                    $gain = e_rand(10,50);
                    $session['user']['gold']+=$gain;
                    //debuglog("found $gain gold near Silas");
                    break;
                case 4:
                    output(INNCOLORSILAS."\"Der große grüne Drache hatte eine Gruppe Zwerge entdeckt und sie *schlurps* einfach aufgefuttert. Sein Kommentar
                           später war: 'Die schmecken ja toll, aber... kleiner sollten sie wirklich nicht sein!'\"");
                    if ($session['user']['race']=='zwg') {
                        output("`n`n`^Als Zwerg kannst du darüber nicht lachen. Mit grimmigem Gesichtsausdruck, der auch Silas' Lachen zu ersticken scheint, schlägst
                                du ihn zu Boden. Du bist so wütend, dass dich heute wohl nichts mehr erschrecken kann.");
                    } else {
                        output("`n`n`^Mit einem guten, herzlichen Kichern in deiner Seele rückst du wieder aus, bereit für was auch immer da kommen mag!");
                    }
                    $session['user']['hitpoints']=round($session['user']['maxhitpoints']*1.2,0);
                    break;
                case 5:
                    output(INNCOLORSILAS."\"Hört gut zu und nehmt es euch zu Herzen: Mit jeder Sekunde rücken wir dem Tod etwas näher, hehe.\"");
                    output("`n`n`^Deprimiert wendest du dich ab... und verlierst einen Waldkampf!");
                    $session['user']['turns']--;
                    if ($session['user']['turns']<0) {
                        $session['user']['turns']=0;
                    }
                    //$session['user']['donation']+=1;
                    break;
                case 6:
                    if ($session['user']['prefs']['sounds']) {
                        output("<embed src=\"media/matlock.mid\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);
                    }
                    output(INNCOLORSILAS."\"Ich liebe Yaris, die Waffen von Yaris, ich liebe Yaris, die Waffen von Yaris, ich liebe Yaris, die Waffen von Yaris, nichts tötet so
                            gut wie die WAFFEN von ... Yaris!\"
                            `n`n`^Du denkst, Silas ist ganz in Ordnung. Jetzt willst du los, um irgendwas zu töten. Aus irgendeinem Grund denkst du an Bienen
                            und Fisch.");
                    $session['user']['turns']++;
                    break;
                case 7:
                    if ($session['user']['prefs']['sounds']) {
                        output("<embed src=\"media/burp.wav\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);
                    }
                    output("Silas richtet sich auf und scheint sich auf etwas Eindrucksvolles vorzubereiten. Dann rülpst er dir laut ins Gesicht.
                           ".INNCOLORSILAS."\"War das unterhaltsam genug?\"
                            `n`n`^Der Gestank nach angedautem Ale ist überwältigend. Dir wird etwas übel und du verlierst ein paar Lebenspunkte.");
                    $session['user']['hitpoints']-= round($session['user']['maxhitpoints'] * 0.1,0);
                    if ($session['user']['hitpoints']<=0) {
                        $session['user']['hitpoints']=1;
                    }
                    //$session['user']['donation']+=1;
                    break;
                case 8:
                    if ($session['user']['gold'] >= 5) {
                        output(INNCOLORSILAS."\"Welches Geräusch macht es, wenn man mit einer Hand klatscht?\"".INNCOLORTEXT.", fragt Silas. Während du über diese
                               Scherzfrage nachgrübelst, 'befreit' Silas eine kleine Unterhaltungsgebühr aus deinem Goldsäckchen.
                               `n`n`^Du verlierst 5 Gold!");
                        $session['user']['gold']-=5;
                        //debuglog("lost 5 gold to Silas");
                    } else {
                        output(INNCOLORSILAS."\"Welches Geräusch macht es, wenn man mit einer Hand klatscht?\"".INNCOLORTEXT.", fragt Silas. Während du über diese
                               Scherzfrage nachgrübelst, versucht Silas eine kleine Unterhaltungsgebühr aus deinem Goldsäckchen zu befreien, findet aber nicht,
                               was er sich erhofft hat.");
                        //$session['user']['donation']+=1;
                    }
                    break;
                case 9:
                    output(INNCOLORSILAS."\"Welcher Fuss muss immer zittern?`n`nDer Hasenfuss.\"
                           `n`n`^Du gröhlst und Silas lacht herzlich. Kopfschüttelnd bemerkst du einen Edelstein im Staub.");
                    $session['user']['gems']++;
                    //debuglog("got 1 gem from Silas");
                    break;
                case 10:
                    output("Silas spielt eine sanfte, aber mitreißende Melodie.");
                    if ($session['user']['prefs']['sounds']) {
                        output("<embed src=\"media/indianajones.mid\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);
                    }
                    output("`n`n`^Du fühlst dich entspannt und erholt und deine Wunden scheinen sich zu schließen.");
                    if ($session['user']['hitpoints']<$session['user']['maxhitpoints']) {
                        $session['user']['hitpoints']=$session['user']['maxhitpoints'];
                    }
                    break;
                case 11:
                    output("Silas spielt dir ein düsteres Klagelied vor.");
                    if ($session['user']['prefs']['sounds']) {
                        output("<embed src=\"media/eternal.mid\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);
                    }
                    output("`n`n`^Deine Stimmung fällt und du wirst heute nicht mehr so viele Bösewichte erschlagen.");
                    $session['user']['turns']--;
                    if ($session['user']['turns']<0) {
                        $session['user']['turns']=0;
                    }
                    break;
                case 12:
                    if ($session['user']['prefs']['sounds']) {
                        output("<embed src=\"media/babyphan.mid\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);
                    }
                    output(INNCOLORSILAS."\"Die Ameisen marschieren in Einerreihen, hurra, hurra!`n
                           Die Ameisen marschieren in Einerreihen, hurra, hurra!`n
                           Die Ameisen marschieren in Einerreihen, hurra, hurra, und die kleinste stoppt und nuckelt am Daumen.`
                           nUnd sie alle marschieren in den Bau, um vorm Regen abzuhauen.`n
                           Bumm, bumm, bumm.`n
                           Die Ameisen marschieren in Zweierreihen, hurra, hurra! ...\"
                           `n`n`^Silas singt immer weiter, aber du hast nicht den Wunsch herauszufinden, wie weit Silas zählen kann, deswegen verschwindest du
                           leise. Nach dieser kurzen Rast fühlst du dich erholt.");
                    $session['user']['hitpoints']=$session['user']['maxhitpoints'];
                    break;
                case 13:
                    output(INNCOLORSILAS."\"Es war ein mal eine Dame von der Venus, ihr Körper war geformt wie ein ...\"");
                    if ($session['user']['sex']==1) {
                        output("`n`n`^Silas wird durch einen Schlag ins Gesicht unterbrochen. Jubelnd gratulierst du Ophelía zu dem ausgeführten Treffer und
                                fühlst dich selbst auch zum Kämpfen angespornt. Du erhältst einen zusätzlichen Waldkampf.");
                    } else {
                        output("`n`n`^Silas wird durch dein plötzliches lautes Gelächter unterbrochen, das du ausstößt, ohne seinen Reim vollständig gehört
                                haben zu müssen. So angespornt erhältst du einen zusätzlichen Waldkampf.");
                    }
                    $session['user']['turns']++;
                    break;
                case 14:
                    if ($session['user']['prefs']['sounds']) {
                        output("<embed src=\"media/knightrider.mid\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);
                    }
                    output("Silas spielt einen stürmischen Schlachtruf für dich, der den Kriegergeist in dir weckt.
                            `n`n`^Du bekommst einen zusätzlichen Waldkampf!");
                    $session['user']['turns']++;
                    break;
                case 15:
                    output("Silas scheint in Gedanken völlig woanders zu sein ... bei deinen ... Augen.");
                    if ($session['user']['sex']==1) {
                        output("`n`nDu erhältst einen Charmepunkt!");
                        $session['user']['charm']++;
                    } else {
                        output("`n`n`^Aufgebracht stürmst du aus der Bar! In deiner Wut bekommst du einen Waldkampf dazu.");
                        $session['user']['turns']++;
                    }
                    break;
                case 16:
                    if ($session['user']['prefs']['sounds']) {
                        output("<embed src=\"media/boioing.wav\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);
                    }
                    output("Silas fängt an zu spielen, aber eine Saite seiner Laute reißt plötzlich und schlägt dir flach ins Auge. ".INNCOLORSILAS."\"Uuuups! Vorsicht,
                            du wirst dir noch deine Augen ausschießen, Mensch!\"
                            `n`n`^Du verlierst einige Lebenspunkte!");
                    $session['user']['hitpoints']-=round($session['user']['maxhitpoints']*.1,0);
                    if ($session['user']['hitpoints']<1) {
                        $session['user']['hitpoints']=1;
                    }
                    break;
                case 17:
                    output("Er fängt an zu spielen, als ein rauflustiger Gast vorbeistolpert und Bier auf dich verschüttet. Du verpasst die ganze Vorstellung,
                            während du das Gesöff von deiner Rüstung putzt.");
                    //$session['user']['donation']+=1;
                    break;
                case 18:
                    output("Silas starrt dich gedankenvoll an. Offensichtlich komponiert er gerade ein episches Gedicht...`n
                           `n".INNCOLORSILAS."\"H-Ä-S-S-L-I-C-H, du kannst dich nicht verstecken! Du bist hässlich, yeah, yeah, so hässlich! So hässli-\"`n
                           `n
                           `iFlirr!`i ".INNCOLORSILAS."\"Autsch! Verdammt!\" ".INNCOLORTEXT."So klingt es, wenn einem Barden eine Saite reißt und gegen die
                           Spielhand schlägt. Fluchend reibt sich Silas die Hand und funkelt in die Runde, um sein lachendes Publikum zum Schweigen zu bringen.
                           Du findest, dass ihm das nur Recht geschieht! Besonders toll fühlst du dich aber trotzdem nicht...`n
                           `^Du verlierst einen Charmepunkt.");
                    if ($session['user']['charm']>0) {
                        $session['user']['charm']--;
                    }
                    break;
                }
            }
        } else {
            $str_tout .= INNCOLORHEAD.'`c`b'.$str_schenkenname.'`b`c`n'.INNCOLORTEXT.
                         'Du gesellst dich zu Silas, der gerade eine kurze Pause einlegt, um seine Laute zu stimmen. Seine musikalischen Leistungen haben dem
                          Barden mittlerweile einen ganz eigenen Ruf eingebracht - mal zum Positiven, mal zum Negativen. Was da wohl dran ist? Vielleicht
                          solltest du Silas einmal selbst hautnah erleben, um dir dein eigenes Urteil bilden zu können.';
            addnav('Unterhaltung');
            addnav('Silas um ein Lied bitten',$str_filename.'?op=silas&subop=hear');
            knappentraining_link('silas');
        }
        addnav('Zurück');
        addnav('Zum Schankraum',$str_filename);
    break;
    // Marek
    case 'marek':
        $bool_istspender = in_array($session['user']['acctid'],$arr_spender['acctids']);
        // Ist gerade eine Runde ausgegeben worden? <- wird im Text vermerkt
        $int_paidales = (int)getsetting("paidales",0);
        $str_tout .= INNCOLORHEAD.'`c`bAn der Bar`b`c`n'.
                     INNCOLORTEXT.'Du suchst dir einen freien Hocker an der Bar und siehst Marek ein Weilchen dabei zu, wie er geschäftig von einem Gast zum
                     nächsten wechselt, um mit der Gelassenheit aus jahrzehntelanger Erfahrung eine Bestellung nach der anderen abzuarbeiten. Auch du hebst
                     schließlich zwei Finger in die Luft und fragst den Wirt kurzerhand nach einem Krug Ale, während du gleichzeitig nach deinem Goldbeutel
                     angelst. Dich trifft ein abschätzender Blick, dann';
        if($bool_istspender) {
            $str_tout .= ' winkt Marek ab. '.INNCOLORMAREK.'"Dank Eurer Großzügigkeit steht unsere Schenke wieder. Eure Getränke gehen aufs Haus!"`n'.INNCOLORTEXT.
                         ($int_paidales > 0 ? 'Hinter ihm warte'.($int_paidales == 1 ? 't ein fertig gezapftes und bereits bezahltes Ale'
                                                                                     : 'n '.$int_paidales.' fertig gezapfte und bereits bezahlte Ale').' darauf,
                                               von durstigen Schenkenbesuchern getrunken zu werden.`n'
                                            : '').
                         '`n';
        } elseif($session['user']['marks'] >= 31) {
            $str_tout .= ' winkt Marek ab. '.INNCOLORMAREK.'"Auserwählte trinken hier aufs Haus!"`n'.INNCOLORTEXT.
                         ($int_paidales > 0 ? 'Hinter ihm warte'.($int_paidales == 1 ? 't ein fertig gezapftes und bereits bezahltes Ale'
                                                                                     : 'n '.$int_paidales.' fertig gezapfte und bereits bezahlte Ale').' darauf,
                                               von durstigen Schenkenbesuchern getrunken zu werden.`n'
                                            : '').
                         '`n';
        } else {
            if($int_paidales > 0 && $row_aei['gotfreeale'] == 0) {
                $str_tout .= ' deutet Marek auf '.$int_paidales.' bereits fertig gezapfte'.($int_paidales == 1 ? 's Ale, das' : ' Ale, die').' wohl jemand bereits
                              bezahlt hat. Super!`n`n';
            } else {
                $str_tout .= ' nennt Marek dir den Preis für ein Ale: '.INNCOLORHEAD.($session['user']['level']*ALECOST).' Gold'.INNCOLORTEXT.'.`n'.
                             ($int_paidales > 0 ? 'Hinter ihm warte'.($int_paidales == 1 ? 't ein fertig gezapftes und bereits bezahltes Ale'
                                                                                         : 'n '.$int_paidales.' fertig gezapfte und bereits bezahlte Ale').' darauf,
                                                   von anderen durstigen Schenkenbesuchern getrunken zu werden.`n'
                                                : '').
                             '`n';
            }
        }
        $str_section = 'inn_bar';
        $row_aei = user_get_aei('gotfreeale,spittoday');
        // Preis für Ale festlegen:
        if(($int_paidales == 0 || $row_aei['gotfreeale'] > 0) && $session['user']['marks'] < 31 && !$bool_istspender) {
            $int_alecost = $session['user']['level']*ALECOST;
        } else {
            $int_alecost = 0;
        }
        // end
        addnav('Am Tresen');
        addnav('Trank kaufen',$str_filename.'?op=potion');
        addnav('Ein Ale bestellen ('.($int_alecost > 0 ? $int_alecost.' Gold' : 'kostenlos').')',$str_filename.'?op=ale');
        if($int_paidales == 0) {                  # Mind. 5 Ale für andere bezahlen
            addnav('Runde ausgeben',$str_filename.'?op=buyround');
        }
        if($row_aei['spittoday'] == 0) {         # Sich mit Marek um ein kleines Bierfass prügeln
            addnav('Für ein wenig Krawall sorgen',$str_filename.'?op=boxing');
        }
        addnav('Nachrichten');
        addnav('Schwarzes Brett',$str_filename.'?op=msgboard');
        knappentraining_link('marek');
        // Für die Stadtwache:
        if($session['user']['profession'] == PROF_GUARD || $session['user']['profession'] == PROF_GUARD_SUB || $session['user']['profession'] == PROF_GUARD_HEAD) {
            addnav('Stadtwache');
            addnav("Razzia",$str_filename."?op=listupstairs");
        } elseif(getsetting("pvp",1)) {
            addnav('PvP');
            addnav('Nach Gästeliste fragen',$str_filename.'?op=askforguests');
        }
        addnav('Zurück');
        addnav('Zum Schankraum',$str_filename);
    break;
    // Ale trinken
    case 'ale':
        $int_paidales = (int)getsetting("paidales",0);
        $row_aei = user_get_aei('gotfreeale');
        $str_tout .= INNCOLORHEAD.'`c`bAn der Bar`b`c`n'.
                     INNCOLORTEXT.'Du lehnst dich ein Stück über den Tresen und rufst Marek deine Bestellung zu: einen Krug Ale, aber gut gefüllt! ';
        // wenn Char schon zu betrunken ist (z.B. vom Stadtfest), gibt es nix
        if($session['user']['drunkenness'] > 66) {
            $str_tout .= 'Dich trifft ein abschätziger Blick, dann schüttelt der Wirt entschieden den Kopf. '.INNCOLORMAREK.'"Du hast heute schon genug gehabt.
                          Werd\' erst mal wieder nüchtern." '.INNCOLORTEXT.'Du öffnest schon den Mund, um dich lautstark zu beschweren, doch irgendwie ist deine
                          Zunge merklich schwer, und dein Kopf scheint auch gerade etwas dagegen zu haben, vernünftige Sätze zu prudizieren. Also beschränkst du
                          dich auf ein missgelauntes Brummen, das Marek gekonnt ignoriert.';
        // wenn Char ggf. sein Freiale schon hatte und nicht genug Gold dabei hat, gibt es auch nix
        } elseif($session['user']['gold'] < $session['user']['level']*ALECOST && ($int_paidales == 0 || ($int_paidales > 0 && $row_aei['gotfreeale'] > 0))) {
            $str_tout .= 'Dann fällt dir allerdings auf, dass dir gerade das nötige Kleingeld fehlt. Gott sei Dank hat Marek deine Bestellung nicht mitbekommen...';
        } else {
            $session['user']['drunkenness'] += 33;
            if($int_paidales > 0 && $row_aei['gotfreeale'] == 0) {
                $int_paidales--;
                savesetting("paidales","".$int_paidales."");
                db_query("UPDATE account_extra_info SET gotfreeale = gotfreeale+1 WHERE acctid = ".$session['user']['acctid']) or die(db_error(LINK));
            } else {
                if($session['user']['marks'] < 31 && !in_array($session['user']['acctid'],$arr_spender['acctids'])) {
                    $session['user']['gold'] -= $session['user']['level']*ALECOST;
                }
            }
            // Beim dritten Ale...
            if($session['user']['drunkenness'] >= 99) {
                switch(e_rand(1,4)) {
                    // ..stirbt Char den Säufertod
                    case 1:
                        output("`QDu hast zu viel gesoffen und bist an einer Alkoholvergiftung gestorben.`n
                                      `n
                                      Du verlierst 5% deiner Erfahrungspunkte und die Hälfte deine Goldes!");
                        $session['user']['alive'] = false;
                        $session['user']['hitpoints'] = 0;
                        $session['user']['gold'] = $session['user']['gold']*0.5;
                        $session['user']['experience'] = $session['user']['experience']*0.95;
                        addnews('`^'.$session['user']['name']." `Estarb in der Schenke an einer Überdosis Ale!");
                        addnav("Tägliche News","news.php");
                    break;
                    // ..kommt Char in die Ausnüchterungszelle (außer Richter)
                    case 2:
                    case 3:
                    case 4:
                        // Richter kommen mit einem blauen Auge (und öffentlicher Demütigung) davon
                        if($session['user']['profession'] == PROF_JUDGE || $session['user']['profession'] == PROF_JUDGE_HEAD) {
                            output("`qDu hast zwar zu viel gesoffen, es aber gerade noch überlebt. Wegen deiner richterlichen Immunität musst du nicht in
                                          die Ausnüchterungszelle.`n
                                          Du verlierst fast alle deine Lebenspunkte!");
                            $session['user']['hitpoints'] = 1;
                            $session['user']['drunkenness'] = 50;
                            addnews("`&Richter ".$session['user']['name']." `Qentging nur knapp den Folgen einer Alkoholvergiftung und muss dank richterlicher Immunität nicht in den Kerker.");
                            addnav("Weiter","village.php");
                        // Der Rest muss bis zum nächsten newday in den Kerker
                        } else {
                            output("`qDu hast zwar zu viel gesoffen, es aber gerade noch überlebt. Du erwachst in der Ausnüchterungszelle.`n
                                          Du verlierst fast alle deine Lebenspunkte!");
                            $session['user']['hitpoints'] = 1;
                            $session['user']['imprisoned'] += 1;
                            addnews("`F".$session['user']['name']." `Qentging nur knapp den Folgen einer Alkoholvergiftung und verbringt die Nacht in der Ausnüchterungszelle.");
                            addnav("Weiter","prison.php");
                        }
                    break;
                }
                page_footer();
                exit;
            } else {
                $str_tout .= 'Marek reagiert prompt, indem er das von ihm gerade frisch gezapfte Ale mit Schwung zu dir rutschen lässt. Gekonnt fängst du den
                              Krug und leerst ihn in einem Zug.`n`n';
                switch(e_rand(1,3)) {
                    // mehr LP (temporär)
                    case 1:
                    case 2:
                        $str_tout .= '`^Du fühlst dich fit und gestärkt!`n`n';
                        $session['user']['hitpoints'] += round($session['user']['maxhitpoints']*0.1,0);
                    break;
                    // + 1 Waldkampf
                    case 3:
                        $str_tout .= '`^Du fühlst dich bereit für ein weiteres Abenteuer!`n`n';
                        $session['user']['turns']++;
                    break;
                }
                $session['bufflist']['101'] = array("name"=>"`#Rausch","rounds"=>10,"wearoff"=>"`KDein Rausch verschwindet.","atkmod"=>1.25,"roundmsg"=>"`KDu hast einen ordentlichen Rausch am laufen.","activate"=>"offense");
            }
        }
        addnav('Zurück');
        addnav('Zur Bar',$str_filename.'?op=marek');
        addnav('S?Zum Schankraum',$str_filename);
    break;
    // Runde ausgeben
    case 'buyround':
        $str_tout .= INNCOLORHEAD.'`c`bAn der Bar`b`c`n';
        $int_maxales = (int)getsetting("maxales",20);
        // Runde bezahlen (Überprüfung & Durchführung)
        if($_GET['act'] == 'buy') {
            $int_amnt_ale = abs((int)$_POST['amnt_ale']);
            $int_amnt_ale = ($int_amnt_ale > $int_maxales ? $int_maxales : $int_amnt_ale);
            // Bei weniger als 5 Ale lohnt sich der Kauf nicht...
            if($int_amnt_ale < 5) {
                $str_tout .= INNCOLORTEXT.'Prompt macht sich Enttäuschung im Schenkenraum breit, und nicht wenige runzeln die Stirn bei deiner Verkündung.
                             Marek schüttelt nur den Kopf und wendet sich anderen Gästen zu. Aber mal ehrlich, was hast du auch erwartet, wenn du sagst, dass du
                             sage und schreibe `b'.$_POST['amnt_ale'].' Ale`b ausgeben willst?!`n`n';
            // Es muss genug Gold vorhanden sein zum Bezahlen:
            } elseif($session['user']['gold'] < $int_amnt_ale * $session['user']['level'] * ALECOST) {
                $str_tout .= INNCOLORTEXT.'Du gibst an, '.$_POST['amnt_ale'].' Ale ausgeben zu wollen, doch als du dann die entsprechende Anzahl an Münzen in Mareks
                              ausgestreckte Hand fallen lassen möchtest, fällt dir auf, dass du gar nicht genug Gold mitgenommen hast. Ups...`n`n';
            // Wenn alles stimmt: Runde ausgeben:
            } else {
                $str_tout .= INNCOLORTEXT.'Du lässt einen klimpernden Beutel auf den Tresen fallen, woraufhin Marek beide Hände wie einen Trichter vor seinen
                              Mund hält und laut ruft: '.INNCOLORMAREK.'"Die nächste Runde geht auf '.$session['user']['name'].INNCOLORMAREK.'!" '.INNCOLORTEXT.'
                              Lautes Gejubel ist die Antwort, und einige Leute klopfen dir sogar auf die Schulter, ehe sie sich einen Krug nehmen und zu ihren
                              Tischen zurückkehren. Ein gutes Gefühl.`n`n';
                // Bei 10 Ale und mehr gibt's einen CP:
                if($int_amnt_ale >= 10) {
                    $session['user']['charm']++;
                    $str_tout .= '`^Du erhältst einen Charmepunkt.`n`n';
                }
                $session['user']['gold'] -= $int_amnt_ale * $session['user']['level'] * ALECOST;
                savesetting("paidales","".$int_amnt_ale."");
                debuglog('spendiert eine Runde ('.$int_amnt_ale.' Ale) für '.($int_amnt_ale * $session['user']['level'] * ALECOST).' Gold');
                // Für Ruhmeshalle:
                db_query("UPDATE account_extra_info SET beerspent = beerspent + ".$int_amnt_ale." WHERE acctid = ".$session['user']['acctid']);
                // end
            }
        // Wie viele Krüge Ale sollen spendiert werden?
        } else {
            $str_tout .= INNCOLORTEXT.'Oho, du willst eine Runde ausgeben? Sofort drehen sich mehrere Köpfe in deine Richtung. Wie vielen Schenkengästen
                          möchtest du denn einen Krug Ale spendieren? (Du kannst maximal '.$int_maxales.' gefüllte Krüge bestellen.)`n`n
                          <form action="'.$str_filename.'?op=buyround&act=buy" method="post">
                          <input name="amnt_ale" class="input" size="3"> <input type="submit" class="button" value="Spendieren">
                          </form>';
            addnav('',$str_filename.'?op=buyround&act=buy');
        }
        addnav('Zurück');
        addnav('Zur Bar',$str_filename.'?op=marek');
    break;
    // Tränke kaufen
    case 'potion':
        $str_tout .= INNCOLORHEAD.'`c`bAn der Bar`b`c`n';
        $int_cost_lp = get_lp_gems();
        if($_GET['act'] == 'buy') {
            // Sicherstellen, dass ES-Eingabe ein absoluter int ist:
            $int_gems = abs((int)$_POST['gems']);
            // Abbruch, falls $int_gems < 1 oder falls User nicht so viele ES dabei hat
            if($int_gems < 1 || $int_gems > $session['user']['gems']) {
                $str_tout .= INNCOLORTEXT.'Marek lupft nur eine Braue. Umsonst sind seine Tränke ganz sicher nicht!`n`n';
            // Ansonsten Aktion durchführen
            } else {
                $str_tout .= INNCOLORTEXT.'Du legst Marek insgesamt '.$int_gems.' Edelsteine auf den Tresen. Der Wirt zählt einmal sorgfältig nach, dann greift er
                              hinter sich und stellt eine Phiole mit ';
                switch($_POST['wish']) {
                    // Charme
                    case 'charm':
                        $int_cost = 2;                              # Preis pro CP
                        $int_amount = floor($int_gems/$int_cost);
                        $session['user']['charm'] += $int_amount;
                        $int_cost = $int_amount * $int_cost;        # zu zahlender Preis
                        $str_tout .= 'einer quietschpinken Flüssigkeit vor dir ab. Du nimmst den Trank an dich und leerst ihn in einem Zug. ..Igitt!`n
                                      `n
                                      `%Dein Charme steigt um '.$int_amount.' Punkte!`n`n';
                        $str_potion_info = $int_amount.' Charmepunkte';
                    break;
                    // permanente LP
                    case 'lp':
                        $int_amount = floor($int_gems/$int_cost_lp);
                        $session['user']['maxhitpoints'] += $int_amount;
                        $session['user']['hitpoints'] += $int_amount;
                        $int_cost = $int_amount * $int_cost_lp;     # zu zahlender Preis
                        $str_tout .= 'einer milchig-grünlichen Flüssigkeit vor dir ab. Du nimmst den Trank an dich und leerst ihn in einem Zug. ..Igitt!`n
                                      `n
                                      `PDeine Gesundheit steigt permanent um '.$int_amount.' Punkte!`n`n';
                        $str_potion_info = $int_amount.' permanente Lebenspunkte';
                    break;
                    // temporäre LP
                    case 'lptemp':
                        $int_cost = 0.2;                            # Preis pro LP
                        $int_amount = floor($int_gems/$int_cost);
                        $session['user']['hitpoints'] += $int_amount;
                        $int_cost = $int_gems;                      # zu zahlender Preis
                        $str_tout .= 'einer klaren, grünen Flüssigkeit vor dir ab. Du nimmst den Trank an dich und leerst ihn in einem Zug. ..Igitt!`n
                                      `n
                                      `GDeine Gesundheit steigt vorübergehend um '.$int_amount.' Punkte!`n`n';
                        $str_potion_info = $int_amount.' temporäre Lebenspunkte';

                    break;
                    // magische Künste wechseln
                    case 'spec':
                        $int_cost = 2;                              # zu zahlender Preis (fix)
                        $session['user']['specialty']=0;
                        $str_tout .= 'einer milchig-weißen Flüssigkeit vor dir ab. Du nimmst den Trank an dich und leerst ihn in einem Zug. Zuerst
                                      fühlst du keine Veränderung, doch gerade, als du an der Wirkung des Tranks zu zweifeln beginnst, fängt dein Kopf an zu
                                      pochen - und mit einem Mal ist er wie leer gefegt. Für einen endlos langen Moment weißt du gerade einmal noch, wie du
                                      heißt. Dann kehren deine Erinnerungen Stück für Stück zurück - allerdings mit spürbaren Lücken.`n
                                      `n
                                      `&Dein Spezialfähigkeiten wurde zurückgesetzt. Du kannst zu Beginn des nächsten Tagesabschnitts ein neues wählen.`n`n';
                        $str_potion_info = 'Spezialfähigkeiten zurückgesetzt';
                    break;
                    // Gegengift
                    case 'antidot':
                        $int_cost = 1;                              # zu zahlender Preis (fix)
                        $session['bufflist']['poison_potion']=array('name'=>'Gegengift',
                                                                    'rounds'=>1
                                                                   );
                        $str_tout .= 'einer giftig-gelben Flüssigkeit vor dir ab. Du nimmst den Trank an dich und leerst ihn in einem Zug. ..Igitt!`n
                                      `n
                                      °+EBFF00;Du bist nun gegen jedes Gift immun.`n`n';
                        $str_potion_info = 'Gegengift-Buff';
                    break;
                    // Rassenwechsel
                    case 'race':
                        $int_cost = 2;                              # zu zahlender Preis (fix)
                        // Rassenboni abnehmen
                        race_set_boni(true,true,$session['user']);
                        // Rasse zurücksetzen
                        $session['user']['race']='';
                        // Negativ-Buff eintragen
                        if (isset($session['bufflist']['transmute'])) {
                            $session['bufflist']['transmute']['rounds'] += 5;
                        } else {
                            $session['bufflist']['transmute']=array("name"=>"`6Transmutationskrankheit",
                            "rounds"=>5,
                            "wearoff"=>"Du hörst auf, deine Därme auszukotzen. Im wahrsten Sinne des Wortes.",
                            "atkmod"=>0.8,
                            "defmod"=>0.8,
                            "roundmsg"=>"Teile deiner Haut und deiner Knochen verformen sich wie Wachs.",
                            "survivenewday"=>1,
                            "newdaymessage"=>"`6Durch die Auswirkungen des Transmutationstranks fühlst du dich immer noch `2krank`6.",
                            "activate"=>"offense,defense"
                            );
                        }
                        $str_tout .= 'einer klaren, violetten Flüssigkeit vor dir ab. Du nimmst den Trank an dich und leerst ihn in einem Zug.`n
                                      Die Wirkung setzt sofort ein: Mit einem Mal wird dir speiübel... Marek grinst nur und nimmt die Phiole wieder an sich.`n
                                      `n
                                      `5Deine Rasse wurde zurückgesetzt. Du kannst zu Beginn des nächsten Tagesabschnitts eine neue wählen.`n`n';
                        $str_potion_info = 'Rasse zurückgesetzt';
                    break;
                    // debug
                    default:
                        $str_tout .= '`&Huch, wie kommst du denn hierher? Schicke bitte die folgende Meldung via Anfrage an das E-Team. Beschreibe außerdem, was du kurz vorher
                                      getan bzw. angeklickt hast. Hier kommt die Meldung:`n
                                      `n
                                      `^fehlende wish: '.$str_wish.' in '.$str_filename.'?op=potion';
                        addnav('Zurück zum Spiel',$str_filename);
                        output($str_tout);
                        page_footer();
                        exit;
                    break;
                }
                // ES abziehen
                $session['user']['gems'] -= $int_cost;
                debuglog('`xzahlt '.$int_cost.' ES für Trank bei Marek - Wirkung: '.$str_potion_info);
                if(($int_gems - $int_cost) > 0) {
                    $str_tout .= INNCOLORTEXT.'Der Wirt gibt dir die restlichen Edelsteine zurück, die du zu viel bezahlt hast.`n`n';
                }
            }
            addnav('Zurück');
            addnav('Genug gekauft',$str_filename.'?op=marek');
        } else {
            $str_tout .= INNCOLORTEXT.'Hinter der Bar türmt sich ein riesiges Regal gen Decke, das über und über gefüllt ist mit Flaschen. Auf den meisten
                         erkennst die Logos dir bekannter Ale-, Wein- und Schnapshersteller, doch eine kleine Auswahl an Phiolen ist lediglich mit Symbolen
                         gekennzeichnet. Als Marek das nächste Mal an dir vorbeiläuft, hältst du ihn auf und fragst nach dem Inhalt der ominösen Flaschen.
                         Der Wirt grinst. '.INNCOLORMAREK.'"Das sind selbstgebraute Tränke"'.INNCOLORTEXT.', erklärt er dir und gibt dir eine kurze
                         Beschreibung dessen, welche unterschiedlichen Wirkungen die Tränke erzielen. Außerdem betont er, dass die Tränke ebenfalls zum Verkauf
                         stehen, allerdings sind sie nicht ganz billig...`n`n
                         <form action="'.$str_filename.'?op=potion&act=buy" method="post">
                         '.INNCOLORTEXT.'Dir steht folgende Auswahl zur Verfügung:`n`n
                         <input type="radio" name="wish" class="input" value="charm" checked> Schönheitstrank `i(1 Charmepunkt für 2 Edelsteine)`i`n
                         <input type="radio" name="wish" class="input" value="lptemp"> Ausdauertrank `i(5 temporäre Lebenspunkte für 1 Edelstein)`i`n
                         <input type="radio" name="wish" class="input" value="lp"> Lebenskraft-Trank `i(1 permanenten Lebenspunkt für '.$int_cost_lp.' Edelstein'.($int_cost_lp > 1 ? 'e' : '').')`i`n
                         <input type="radio" name="wish" class="input" value="antidot"> Immunisierung gegen Gift `i(Gegengift-Buff für 1 Edelstein)`i`n
                         <input type="radio" name="wish" class="input" value="spec"> Trank des Vergessens `i(Spezialfähigkeiten-Wechsel für 2 Edelsteine)`i`n
                         <input type="radio" name="wish" class="input" value="race"> Transmutationstrank `i(Rassenwechsel für 2 Edelsteine)`i`n
                         `n
                         '.INNCOLORTEXT.'Wie viele Edelsteine möchtest du ausgeben?`n
                         `n
                         <input name="gems" class="input" size="3"> <input type="submit" class="button" value="Ausgeben">
                         </form>
                         `n';
            addnav('',$str_filename.'?op=potion&act=buy');
            addnav('Zurück');
            addnav('Lieber doch nicht',$str_filename.'?op=marek');
        }
    break;
    // Schwarzes Brett
    case "msgboard":
            output(INNCOLORHEAD.'`c`bAn der Bar`b`c');
            if ($_GET['act']=="add1") {
                    $msgprice=$session['user']['level']*6*(int)$_GET['amt'];
                    if ($_GET['board_action'] == "") {
                            output(INNCOLORTEXT."\"".INNCOLORMAREK."So, so, du willst also eine
                                    Nachricht hinterlassen? Na, dann leg mal los".INNCOLORTEXT."\",
                                    meint Joe zu dir, während er schon ein Stück Papier und einen
                                    Stift zückt.`n`n");
                            board_view_form('Ans schwarze Brett',
                            'Gib deine Nachricht ein:');
                    } else {
                            if ($session['user']['gold']<$msgprice) {
                                    $tout = INNCOLORTEXT."Gerade noch rechtzeitig bemerkst du, dass
                                            du gar nicht genügend Gold dabei hast. Deshalb winkst du
                                            schnell ab, zerknüllst den Zettel wieder und entfernst
                                            dich sicherheitshalber vom Tresen - bevor Marek deinen
                                            Fehler bemerkt.`n`n";
                            } else {
                                    if (board_add('inn',(int)$_GET['amt'],1) == -1) {
                                            $tout = INNCOLORTEXT."
                                                    Mit hochgezogener Braue deutet Marek auf das
                                                    schwarze Brett, wo bereits eine Nachricht von
                                                    dir hängt. \"".INNCOLORMAREK."Eine Nachricht pro
                                                    Person, so lautet die Regel. Reiß deine andere
                                                    erst ab, bevor du eine neue
                                                    aufgibst.".INNCOLORTEXT."\"`n`n";
                                    } else {
                                            $tout = INNCOLORTEXT."
                                                    Ohne ein weiteres Wort nimmt Marek dein Gold
                                                    entgegen, schreibt deine Nachricht auf einen
                                                    Zettel und hängt diesen zu den anderen ans
                                                    schwarze Brett. Dann widmet er sich wieder den
                                                    abgewaschenen Gläsern.`n`n";
                                            $session['user']['gold']-=$msgprice;
                                    }
                            }
                    }
            } else {
                    $msgprice=$session['user']['level']*6;
                    $msgdays=(int)getsetting('daysperday',4);
                    $tout = INNCOLORTEXT."Sogleich stellt Marek das Glas zur Seite und lehnt sich
                            auf dem Tresen vor. \"".INNCOLORMAREK."Soso, eine Nachricht für's
                            schwarze Brett also, ja? Und wie lang soll ich sie dort hängen
                            lassen? Je länger, umso teurer".INNCOLORTEXT."\", meint er und nennt
                            dir die Preise.`n";
                    addnav('Nachrichten');
                    addnav($msgdays.' Tagesabschnitte (`^'.$msgprice.'`0 Gold)',$str_filename.'?op=msgboard&act=add1&amt=1');
                    addnav(($msgdays*3).' Tagesabschnitte (`^'.($msgprice*3).'`0 Gold)',$str_filename.'?op=msgboard&act=add1&amt=3');
                    addnav(($msgdays*10).' Tagesabschnitte (`^'.($msgprice*10).'`0 Gold)',$str_filename.'?op=msgboard&act=add1&amt=10');
                    if ($session['user']['message']>'') {
                            $tout .= "`nAußerdem weist er dich darauf hin, dass er deine alte Nachricht
                                      abreißen wird, wenn du ihn nun eine neue aufsetzen lässt.`n";
                    }
                    $tout .= "`n";
            }
            output('`n'.$tout);
            addnav('Zurück');
            addnav('Lieber doch nicht',$str_filename.'?op=marek');
    break;
    // Mit Marek prügeln
    case "boxing":
        output(INNCOLORHEAD.'`c`bAn der Bar`b`c`n');
        $row_aei = user_get_aei('spittoday');
        if ($row_aei['spittoday'] == 0) {
            if ($session['user']['hitpoints'] >= $session['user']['maxhitpoints']) {
                output(INNCOLORTEXT."Du schleichst mit verschlagenem Blick zum Tresen und es scheint, als wisse Marek ganz genau, was du vor hast. Sein Grinsen
                        verrät dir, dass er nur darauf wartet, dass du den ersten Schritt machst.`n
                        Noch hast du die Möglichkeit umzukehren. Weißt du, was du da tust?");
                addnav("Klaro");
                addnav("Los gehts!",$str_filename."?op=boxing2&dam=0");
                addnav("Kneifen");
                addnav('Lieber nicht..',$str_filename.'?op=marek');
            } else {
                output(INNCOLORTEXT."Bist du wahnsinnig?`n
                        Wenn du schon Streit suchst, solltest du zumindest in bester körperlicher Verfassung sein!`n");
                addnav('Zurück');
                addnav('Zum Tresen',$str_filename.'?op=marek');
            }
        } else {
            output(INNCOLORTEXT."Marek grinst dich mit geschwollenem Auge an. ".INNCOLORMAREK."\"Das war ne herrliche Keilerei, aber ich hab zu tun... Viele
                    Gäste. Komm später wieder vorbei, dann ist es bestimmt wieder ruhiger.\"`n`n");
            addnav('Zurück');
            addnav('Zum Tresen',$str_filename.'?op=marek');
        }
    break;
    case "boxing2":
        $what=$_GET['what'];
        $ced_dam=$_GET['dam'];
        if (!$what) {
            output("`^Du ballst beide Hände und stürzt dich auf den Wirt!`0`n`n");
        } else {
            output("`&Du holst zu einem`^ ");
            switch ($what) {
            case 1:
                output("Schlag gegen den Kopf ");
                $chance=2;
                break;
            case 2:
                output("Kinnhaken ");
                $chance=5;
                break;
            case 3:
                output("Schlag gegen die Brust ");
                $chance=1;
                break;
            case 4:
                output("Schlag in den Magen ");
                $chance=3;
                break;
            case 5:
                output("Tiefschlag ");
                $chance=4;
                break;
            }

            if (e_rand(0,5)>=$chance) {
                output("`&aus und landest einen Treffer!`n");
                if ($what==1) {
                    output("`#Das klingt aber dumpf...");
                }
                if ($what==2) {
                    output("`#Marek taumelt einige Schritt zurück und prallt gegen ein Regal.");
                }
                if ($what==3) {
                    output("`#Marek tut so, als habe er es nicht bemerkt.");
                }
                if ($what==4) {
                    output("`#Marek wird blass im Gesicht und hält sich eine Hand vor den Mund.");
                }
                if ($what==5) {
                    output("`#Marek verdreht die Augen schreit mit hoher Stimme.");
                }
                $ced_dam+=$chance;
            } else {
                output("`&aus, doch Marek blockt ihn gekonnt.`n`n");
            }
            if (e_rand(1,2)==2 && $ced_dam<=15) {
                output("`4`n`nMarek trifft dich hart!`0`n`n");
                $punch=0.1*e_rand(1,3);
                (int)$damage=$session['user']['maxhitpoints']*$punch;
                (int)$session['user']['hitpoints']-=$damage;
                $session['user']['hitpoints']-=5;
            }
        }
        if ($session['user']['hitpoints']<=0) {
            output("`n`^Marek hat dich windelweich geprügelt und stößt dich zum Abkühlen in die Pferdetränke.`0`n`n");
            $session['user']['hitpoints']=1;
            addnav("Erwachen","village.php");
            user_set_aei(array('spittoday'=>1) );
            addnews("`^".$session['user']['name']."`# wurde von `^Marek`# verprügelt und in die Pferdetränke gestoßen.");
        } else {
            if ($ced_dam<=15) {
                addnav("Ziele auf seinen Körper!");
                output('<div><map name="Marek">
<area shape="circle" coords="205,40,25" href="inn.php?op=boxing2&what=1&dam='.$ced_dam.'" title="Kopfnuss">
<area shape="circle" coords="205,80,10" href="inn.php?op=boxing2&what=2&dam='.$ced_dam.'" title="Kinnhaken">
<area shape="rect" coords="135,160,260,100" href="inn.php?op=boxing2&what=3&dam='.$ced_dam.'" title="Brustschlag">
<area shape="circle" coords="190,200,30" href="inn.php?op=boxing2&what=4&dam='.$ced_dam.'" title="In den Magen">
<area shape="circle" coords="190,265,15" href="inn.php?op=boxing2&what=5&dam='.$ced_dam.'" title="Tiefschlag">
',true);
                addnav('','inn.php?op=boxing2&what=1&dam='.$ced_dam.'');
                addnav('','inn.php?op=boxing2&what=2&dam='.$ced_dam.'');
                addnav('','inn.php?op=boxing2&what=3&dam='.$ced_dam.'');
                addnav('','inn.php?op=boxing2&what=4&dam='.$ced_dam.'');
                addnav('','inn.php?op=boxing2&what=5&dam='.$ced_dam);
                output('</map></div>`n<p><center><img border="0" src="images/cedrik.jpg" usemap="#Marek"></center></p>`n',true);
                switch ($ced_dam) {
                case 0:
                case 1:
                    output("`@Marek geht es blendend.`n`&");
                    break;
                case 2:
                case 3:
                    output("`2Marek geht es recht gut.`n`&");
                    break;
                case 4:
                case 5:
                    output("`1Marek hält sich gut auf den Beinen.`n`&");
                    break;
                case 6:
                case 7:
                    output("`#Marek geht es den Umständen entsprechend gut.`n`&");
                    break;
                case 8:
                case 9:
                    output("`#Marek taumelt ein wenig.`n`&");
                    break;
                case 10:
                case 11:
                    output("`^Marek geht es gar nicht mehr so gut.`n`&");
                    break;
                case 12:
                case 13:
                    output("`4Marek ist recht übel zugerichtet.`n`&");
                    break;
                case 14:
                case 15:
                    output("`\$Marek steht kurz vor dem k.o.`n`&");
                    break;
                }
            } else {
                output("`n`n`FMarek geht zu Boden!`n`^Du schnappst dir ein kleines Fässchen seines hausgebrauten Spezialbieres und machst dich davon.");
                item_add($session['user']['acctid'],'klfale');
                user_set_aei(array('spittoday'=>1) );
                addnav("Zurück",$str_filename);
            }
        }
    break;
    // RP-Ort 2:
    case 'converse':
        $str_tout .= INNCOLORHEAD.'`c`b'.$str_schenkenname.'`b`c`n'.
                     INNCOLORTEXT.'Du entdeckst einige Bekannte und schlenderst zu ihrem Tisch hinüber, um dich zu ihnen zu gesellen.`n`n';
        $str_section = 'inn';
        addnav('Zurück');
        addnav('Zum Schankraum',$str_filename);
    break;
    // Übernachtung kaufen bzw. ausloggen
    case 'room':
        output(INNCOLORHEAD.'`c`b'.$str_schenkenname.'`b`c`n');
        $aei = user_get_aei('boughtroomtoday');
        $config = unserialize($session['user']['donationconfig']);
        $expense = round(($session['user']['level']*(10+log($session['user']['level']))),0);
        if ($_GET['pay']) {
            if ($_GET['coupon']==1) {
                $config['innstays']--;
                debuglog("Logout in der Schenke");
                $session['user']['donationconfig']=serialize($config);
                $session['user']['loggedin']=0;
                $session['user']['location']=USER_LOC_INN;
                user_set_aei(array('boughtroomtoday'=>1));
                saveuser();
                $session=array();
                redirect("index.php");
            } else {
                if ($_GET['pay'] == 2 || $session['user']['gold']>=$expense || $aei['boughtroomtoday']) {
                    if ($session['user']['loggedin']) {
                        if ($aei['boughtroomtoday']) {
                        } else {
                            if ($_GET['pay'] == 2) {
                                $fee = getsetting("innfee", "5%");
                                if (strpos($fee, "%")) {
                                    $expense += round($expense * $fee / 100,0);
                                } else {
                                    $expense += $fee;
                                }
                                $session['user']['goldinbank'] -= $expense;
                            } else {
                                $session['user']['gold'] -= $expense;
                            }
                            user_set_aei(array('boughtroomtoday'=>1));
                        }
                    }
                    redirect('login.php?op=logout&loc='.USER_LOC_INN.'&restatloc=0');
                } else {
                    output(INNCOLORTEXT.'Du zückst deinen Goldbeutel, doch als du den ausgemachten Preis zahlen möchtest, bemerkst du, dass du gar nicht genug
                           Gold dabei hast. Ups...');
                           addnav("Zurück",$str_filename);
                }
            }
        } else {
            if ($aei['boughtroomtoday']) {
                output(INNCOLORTEXT."Du hast heute schon für ein Zimmer bezahlt.");
                addnav("Gehe ins Zimmer","inn.php?op=room&pay=1");
            } else {
                if ($config['innstays']>0) {
                    addnav(INNCOLORTEXT."Zeige ihm den Gutschein für ".$config['innstays']." Übernachtungen","inn.php?op=room&pay=1&coupon=1");
                }
                output(INNCOLORTEXT.'Du fragst Marek nach einem Zimmer zum Übernachten. Der Wirt greift sofort unter den Tresen und zieht ein dickes, gebundenes
                       Buch hervor, in dem er kurz blättert. '.INNCOLORMAREK.'"Hast Glück, ein paar Zimmer sind noch frei. Macht `$'.$expense.INNCOLORMAREK.'
                       Gold für die Nacht." '.INNCOLORTEXT);
                $fee = getsetting("innfee","5%");
                if (strpos($fee, "%")) {
                    $bankexpense = $expense + round($expense * $fee / 100,0);
                } else {
                    $bankexpense = $expense + $fee;
                }
                if ($session['user']['goldinbank'] >= $bankexpense && $bankexpense != $expense) {
                    output(INNCOLORTEXT."Außerdem bietet er dir zum Preis von `$".$bankexpense.INNCOLORTEXT." Gold auch an, direkt von der Bank zu bezahlen.
                            Der Preis beinhaltet " . (strpos($fee, "%") ? $fee : $fee." Gold") . " Überweisungsgebühr.");
                }
                addnav('Bezahlen');
                addnav("Gib ihm ".$expense." Gold","inn.php?op=room&pay=1");
                if ($session['user']['goldinbank'] >= $bankexpense) {
                    addnav("Zahle ".$bankexpense." Gold von der Bank","inn.php?op=room&pay=2");
                }
                addnav('Zurück');
                addnav('Zum Schankraum',$str_filename);
            }
        }
        break;
    break;
    // Für Nicht-Stadtwache: Nach Übernachtungsgästen fragen -> Marek bestechen
    case 'askforguests':
        $str_tout .= INNCOLORHEAD.'`c`bAn der Bar`b`c`n';
        // Marek bestechen
        if($_GET['act'] == 'getlist') {
            // Wie spendabel ist User?
            $int_chance = (int)$_GET['bribeamnt'];
            $int_bribeamnt = ($_GET['bribetype'] == 'gold' ? $int_chance*1000 : $int_chance);
            if(e_rand(0,4) <= $int_chance) {                           # 'Spende' reicht aus
                redirect($str_filename.'?op=listupstairs');
            } else {                                                   # 'Spende' reicht nicht aus
                $str_tout .= INNCOLORTEXT.'Marek nimmt die '.($_GET['bribetype'] == 'gems' ? $int_bribeamnt.' Edelsteine' : $int_bribeamnt.' Goldstücke').' an
                              sich, dann wendet er sich um, sucht kurz im Regal und... findet ein halbwegs sauberes Handtuch, mit dem er anfängt, Gläser und
                              Krüge zu polieren. Als auch nach minutenlangem Warten nicht mehr passiert, siehst du ein, dass du dem Wirt wohl nicht großzügig
                              genug gewesen bist.`n`n';
            }
            $session['user'][$_GET['bribetype']] -= $int_bribeamnt;     # in beiden Fällen verliert Char gespendete/s ES/Gold
        // Um Gästeliste bitten
        } else {
            $str_tout .= INNCOLORTEXT.'Du lehnst dich etwas über den Tresen und raunst Marek zu, dass dich interessiert, wer denn so alles in seiner Schenke
                          abgestiegen ist. Marek lupft eine Braue und erklärt dir in exakt sieben Worten, dass dich das einen feuchten Kehricht angeht. Dabei
                          fixiert er dich allerdings mit vielsagendem Blick. Ob man ihn vielleicht doch zum Sprechen überreden kann? Mit den richtigen
                          Argumenten, versteht sich...`n
                          `n
                          <a href="'.$str_filename.'?op=askforguests&act=getlist&bribetype=gems&bribeamnt=1">Ihm 1 Edelstein anbieten</a>`n
                          `n
                          <a href="'.$str_filename.'?op=askforguests&act=getlist&bribetype=gems&bribeamnt=2">Ihm 2 Edelsteine anbieten</a>`n
                          `n
                          <a href="'.$str_filename.'?op=askforguests&act=getlist&bribetype=gems&bribeamnt=3">Ihm 3 Edelsteine anbieten</a>`n
                          `n
                          `n
                          <a href="'.$str_filename.'?op=askforguests&act=getlist&bribetype=gold&bribeamnt=1">Ihm 1000 Gold anbieten</a>`n
                          `n
                          <a href="'.$str_filename.'?op=askforguests&act=getlist&bribetype=gold&bribeamnt=2">Ihm 2000 Gold anbieten</a>`n
                          `n
                          <a href="'.$str_filename.'?op=askforguests&act=getlist&bribetype=gold&bribeamnt=3">Ihm 3000 Gold anbieten</a>`n
                          `n';
            addnav('',$str_filename.'?op=askforguests&act=getlist&bribetype=gems&bribeamnt=1');
            addnav('',$str_filename.'?op=askforguests&act=getlist&bribetype=gems&bribeamnt=2');
            addnav('',$str_filename.'?op=askforguests&act=getlist&bribetype=gems&bribeamnt=3');
            addnav('',$str_filename.'?op=askforguests&act=getlist&bribetype=gold&bribeamnt=1');
            addnav('',$str_filename.'?op=askforguests&act=getlist&bribetype=gold&bribeamnt=2');
            addnav('',$str_filename.'?op=askforguests&act=getlist&bribetype=gold&bribeamnt=3');
        }
        addnav('Zurück');
        addnav('Zur Bar',$str_filename.'?op=marek');
    break;
    // Übernachtungsgäste auflisten (bei Stadtwache: für Razzia)
    case 'listupstairs':
        require_once(LIB_PATH.'dg_funcs.lib.php');
        output(INNCOLORHEAD.'`c`bAn der Bar`b`c`n');
        addnav("Liste aktualisieren","inn.php?op=listupstairs");
        output("Marek legt einen Satz Schlüssel vor dich auf den Tresen und sagt dir, welcher Schlüssel wessen Zimmer öffnet. Du hast die Wahl. Du könntest bei
                jedem reinschlüpfen und angreifen.");
        if ($session['user']['profession'] == PROF_TEMPLE_SERVANT ) {
            output("`nAls Tempeldiener kehrst du jedoch besser gleich wieder um..");
        } else {
            $pvptime = getsetting("pvptimeout",600);
            $pvptimeout = date("Y-m-d H:i:s",strtotime(date("r")."-$pvptime seconds"));
            pvpwarning();
            if ($session['user']['pvpflag']==PVP_IMMU) {
                output("`n`&(Du hast PvP-Immunität gekauft. Diese verfällt, wenn du jetzt angreifst!)`0`n`n");
            }
            $days = getsetting("pvpimmunity", 5);
            $exp = getsetting("pvpminexp", 1500);
            if (($session['user']['profession']==0) || ($session['user']['profession']>PROF_GUARD_HEAD)) {
                    // Hot Items: Immu spielt bei Stadtwachen sowieso keine Rolle
                                    $res = item_list_get(' hot_item>0 AND owner>0 AND deposit1=0 ','',true,'owner');
                                    if(db_num_rows($res)) {
                                            $arr_hot_owners = db_create_list($res,'owner');
                                    } else {
                                            $arr_hot_owners = array();
                                    }
                $sql = "SELECT accounts.name,alive,location,sex,level,laston,loggedin,login,pvpflag,acctid,g.name AS guildname,accounts.guildid,accounts.guildfunc FROM accounts LEFT JOIN dg_guilds g ON (g.guildid=accounts.guildid AND guildfunc!=".DG_FUNC_APPLICANT.") WHERE
                                                    (locked=0) AND
                                                    (level >= ".($session['user']['level']-1)." AND level <= ".($session['user']['level']+2).") AND
                                                    (alive=1 AND location=".USER_LOC_INN.") AND
                                                    (age > $days OR dragonkills > 0 OR pk > 0 OR experience > $exp) AND
                                                    !(".user_get_online(0,0,true).") AND
                                                    (acctid <> ".$session['user']['acctid'].") AND
                                                    (dragonkills > ".($session['user']['dragonkills']-5).")
                                                    ORDER BY level DESC";
            } else {
                $sql = "SELECT accounts.name,alive,location,sex,level,laston,loggedin,login,pvpflag,acctid,g.name AS guildname,accounts.guildid,accounts.guildfunc FROM accounts LEFT JOIN dg_guilds g ON (g.guildid=accounts.guildid AND guildfunc!=".DG_FUNC_APPLICANT.") WHERE
(locked=0) AND
(level >= ".($session['user']['level']-1)." AND level <= ".($session['user']['level']+2).") AND
(alive=1 AND location=".USER_LOC_INN.") AND
(age > $days OR dragonkills > 0 OR pk > 0 OR experience > $exp) AND
!(".user_get_online(0,0,true).") AND
(acctid <> ".$session['user']['acctid'].")
ORDER BY level DESC";
            }
            $result = db_query($sql) or die(db_error(LINK));
            if ($session['user']['guildid']) {
                $guild = &dg_load_guild($session['user']['guildid'],array('treaties'));
            }
            output("`n`c<table bgcolor='#999999' border='0' cellpadding='3' cellspacing='0'><tr class='trhead'><td>Name</td><td>Level</td><td>Gilde</td><td>Ops</td></tr>",true);
            $count = db_num_rows($result);
            if ($count == 0) {
                output('<tr><td colspan="4" class="trlight">`iLeider erblickst du niemanden, der für dich in Frage käme!`0`i</td></tr>',true);
            }
            for ($i=0; $i<$count; $i++) {
                $row = db_fetch_assoc($result);
                $row['guildname'] = ($row['guildname']) ? $row['guildname'] : ' - ';
                if($session['user']['prefs']['popupbio'] == 1) {
                    $biolink="bio_popup.php?char=".rawurlencode($row['login']);
                    $str_biolink = "<a href='".$biolink."' target='_blank' onClick='".popup_fullsize($biolink).";return:false;'>Bio</a>";
                } else {
                    $biolink="bio.php?char=".rawurlencode($row['login'])."&ret=".urlencode($_SERVER['REQUEST_URI']);
                    $str_biolink = "<a href='".$biolink."'>Bio</a>";
                    addnav("","bio.php?char=".rawurlencode($row['login'])."&ret=".URLEncode($_SERVER['REQUEST_URI']));
                }
                $state_str = '';
                if ($row['guildid'] && $session['user']['guildid']) {
                    $state = dg_get_treaty($guild['treaties'][$row['guildid']]);
                    if ($state==1) {
                        $state_str = ' `@(befreundet)';
                    } elseif ($state==-1) {
                        $state_str = ' `4(Feind)';
                    }
                }
                output("<tr class='".($i%2?"trlight":"trdark")."'><td>".$row['name']."</td><td>".$row['level']."</td><td>".$row['guildname'].$state_str."</td><td>[ ".$str_biolink." |",true);
                if (
                        (
                                // Wenn kürzlich angegriffen und User keine Stadtwache
                                ($row['pvpflag']>$pvptimeout &&
                                        ($session['user']['profession']==0 || $session['user']['profession']>PROF_GUARD_HEAD)
                                        // Und kein Besitzer von Hot Items
                                        && !isset($arr_hot_owners[$row['acctid']])
                                )
                        )
                                // ODER gleiche Gilde
                        || ($session['user']['guildid']>0 && $session['user']['guildid'] == $row['guildid'])
                   ) {
                    output("`iimmun`i ]</td></tr>",true);
                } else {
                    output("<a href='pvp.php?act=attack&bg=1&id=".$row['acctid']."'>Angriff</a> ]</td></tr>",true);
                    addnav("","pvp.php?act=attack&bg=1&id=".$row['acctid']);
                }
            }
            output("</table>`c",true);
            $session['user']['reputation']--;
        }
        // END if erlaubt
        addnav('Zurück');
        addnav('Zur Bar',$str_filename.'?op=marek');
    break;
    // Knappenheilung
    case 'disc':
        $str_tout .= INNCOLORHEAD.'`c`b'.$str_schenkenname.'`b`c`n'.
                     INNCOLORTEXT.'Du legst '.$arr_disc['name'].INNCOLORTEXT.' den Arm um die Schultern und ziehst '.($arr_disc['sex'] ? 'sie' : 'ihn').' mit
                     in Richtung Tresen. Dort rufst du Marek zu: '.get_fc($prefs['commenttalkcolor']).'"Ein großes Ale für meinen Knappen!" '.INNCOLORTEXT.'Marek
                     lupft eine Braue und beäugt das Persönchen neben dir kritisch.';
        $row_aei = user_get_aei('disciple_gotale');
        if($session['user']['gold'] < $session['user']['level']*ALECOST) {            # Wenn User nicht genug Gold dabei hat
            $str_tout .= '`n`nDas ist der Moment, in dem dir einfällt, dass du ja überhaupt nicht genug Gold dabei hast, um das Ale bezahlen zu können. Also
                          tust du so, als würdest du Mareks Zweifeln über '.$arr_disc['name'].INNCOLORTEXT.' zustimmen und ziehst deinen Knappen schnell wieder
                          vom Tresen weg.`n`n';
        } elseif($row_aei['disciple_gotale'] == 1) {                             # Wenn Knappe schon ein Ale hatte
            $str_tout .= INNCOLORMAREK.' "'.($arr_disc['sex'] ? 'Dein Mädel' : 'Dein Kerlchen').' soll erst mal wieder nüchtern werden, dann können wir
                         noch mal drüber reden"'.INNCOLORTEXT.', brummt er und lässt euch beide stehen.`n`n';
        } else {
            $str_tout .= ' Schnell legst du das nötige Kleingeld auf den Tresen, woraufhin Marek mit den Achseln zuckt und einen gefüllten Krug vor
                         '.$arr_disc['name'].INNCOLORTEXT.' abstellt. Zuerst zögert diese'.($arr_disc['sex'] ? '' : 'r').', doch nach einem ermutigenden Nicken
                         deinerseits greift '.($arr_disc['sex'] ? 'sie' : 'er').' nach dem Krug - und leert ihn mit wenigen Schlücken. Da staunt nicht nur Marek
                         nicht schlecht!`n
                         `n
                         Dein Knappe grinst von einem Ohr zum anderen und ist bereit für weitere Schandtaten.`n`n';
            $session['bufflist']['decbuff'] = $arr_disc['buff'];
            user_set_aei(array('disciple_gotale'=>1));
            $session['user']['gold'] -= $session['user']['level']*ALECOST;
        }
        addnav('Zurück');
        addnav('Zum Schankraum',$str_filename);
    break;
    // Ehrung der großzügigsten Spender
    case 'donators':
        $str_tout .= INNCOLORHEAD.'`c`b'.$str_schenkenname.'`b`c`n'.
                     INNCOLORTEXT.'Mit einer Länge und Höhe von gut zwei Ellen prangt das Schild nur unweit von Mareks Bar entfernt an der Wand.
                     Was aus der Ferne noch wie Gold gewirkt hat, entpuppt sich als angemaltes Metall - nichtsdestotrotz jedoch bewacht Marek die Tafel mit
                     Argusaugen. Zurecht, wie du feststellst, als du näher trittst und die mit Sorgfalt eingeritzte Inschrift liest:`n`n`n';
        $str_tout .= '`c<div style="width: 50em; border: 4px double #F8DB83; background-color: #120C00; padding: 1.5em; border-radius: 0.5em;">
                     '.INNCOLORTAFEL.implode(INNCOLORTAFEL.',`n',$arr_spender['names']).'`n
                      <p style="text-align: justify;">'.INNCOLORTAFEL.'Diese Bürger Eranyas haben nach dem schrecklichen Brand im Jahr 1106 mit ihren Spenden
                      mehr als Dreiviertel des Wiederaufbaus der Schenke finanziert!`n
                      Möge diese Tafel fortbestehen und auf ewig an die Großzügigkeit erinnern, die den Schenkenbetreibern zuteil wurde.</p>
                     `i'.INNCOLORMAREK.'Marek '.INNCOLORTAFEL.'-- '.INNCOLORSILAS.'Silas '.INNCOLORTAFEL.'-- '.INNCOLOROPHELIA.'Ophelía`i'.INNCOLORTAFEL.'
                     </div>`c`n';
        addnav('Zurück');
        addnav('Zum Schankraum',$str_filename);
    break;
    // Debug
    default:
        $str_tout .= '`&Huch, wie kommst du denn hierher? Schicke bitte die folgende Meldung via Anfrage an das E-Team. Beschreibe außerdem, was du kurz vorher
                      getan bzw. angeklickt hast. Hier kommt die Meldung:`n
                      `n
                      `^fehlende op: '.$str_op.' in '.$str_filename;
        addnav('Zurück zum Spiel',$str_filename);
    break;
}
output($str_tout);
if(isset($str_section)) {
    addcommentary();
    viewcommentary($str_section,'Sagen',15,'sagt');
}
page_footer();
?>

