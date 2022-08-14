
<?php

// 24072004

require_once "common.php";

if($_GET['act'] == 'admin')
    debuglog('Neuen Tag gewährt.');

/***************
 **  SETTINGS **
 ***************/

$turnsperday = getsetting("turns",10);
$maxinterest = ((float)getsetting("maxinterest",10)/100) + 1; //1.1;
$mininterest = ((float)getsetting("mininterest",1)/100) + 1; //1.1;
$dailypvpfights = getsetting("pvpday",3);

if($_GET['resurrection']=="true"){
    $resline = "&resurrection=true";
}else if($_GET['resurrection']=="egg"){
    $resline = "&resurrection=egg";
}else{
    $resline = "";
}

/******************
 ** End Settings **
 ******************/
if(count($session['user']['dragonpoints']) <$session['user']['dragonkills']&&$_GET['dk']!=""){
    array_push($session['user']['dragonpoints'],$_GET[dk]);
    switch($_GET['dk']){
    case "hp":
        $session['user']['maxhitpoints']+=5;
    break;
    
    case "at":
        $session['user']['attack']++;
    break;
    
    case "de":
        $session['user']['defence']++;
    break;    
    }
}

if(count($session['user']['dragonpoints'])<$session['user']['dragonkills'] && $_GET['dk']!="ignore")
{
    page_header("Drachenpunkte");
    addnav("Max Lebenspunkte +5","newday.php?dk=hp$resline");
    addnav("Waldkämpfe +1","newday.php?dk=ff$resline");
    addnav("Angriff + 1","newday.php?dk=at$resline");
    addnav("Verteidigung + 1","newday.php?dk=de$resline");
    output("`@Du hast noch `^".($session['user']['dragonkills']-count($session['user']['dragonpoints']))."`@  Drachenpunkte übrig. Wie willst du sie einsetzen?`n`n");
    output("Du bekommst 1 Drachenpunkt pro getötetem Drachen. Die Änderungen der Eigenschaften durch Drachenpunkte sind permanent.");
}

else if ((int)$session['user']['specialty']==0)
{
          if ($_GET['setspecialty']===NULL){
            addnav("","newday.php?setspecialty=1$resline");
            addnav("","newday.php?setspecialty=2$resline");
            addnav("","newday.php?setspecialty=3$resline");
            addnav("","newday.php?setspecialty=4$resline");
            addnav("","newday.php?setspecialty=5$resline");
            addnav("","newday.php?setspecialty=6$resline");
            addnav("","newday.php?setspecialty=7$resline");
            addnav("","newday.php?setspecialty=8$resline");
            addnav("","newday.php?setspecialty=9$resline");
            page_header("Ein wenig über deine Vorgeschichte");

            output("Du erinnerst dich, dass du als Kind:`n`n");
            output("<a href='newday.php?setspecialty=1$resline'>viele Kreaturen des Waldes getötet hast (`µD`Gu`Ín`Ók`Úl`Ée K`Úü`Ón`Ís`Gt`µe`0)</a>`n",true);
            output("<a href='newday.php?setspecialty=2$resline'>mit mystischen Kräften experimentiert hast (`5M`Oy`Js`%t`li`Ms`Rche `MK`lr`%ä`Jf`Ot`5e`0)</a>`n",true);
            output("<a href='newday.php?setspecialty=4$resline'>gelernt hast mit dem Feuer umzugehen (`îF`Ôe`ßu`se`4r`om`Qa`qg`6i`^e`0)</a>`n",true);
            output("<a href='newday.php?setspecialty=6$resline'>schon immer mit der Natur verbunden warst (`ÛN`Îa`yt`ôu`úr`àm`ôa`yg`Îi`Ûe`0)</a>`n",true);
            output("<a href='newday.php?setspecialty=7$resline'>immer etwas mit Wasser zu tun hattest (`1W`!a`9s`ùs`ìe`jr`ìm`ùa`9g`!i`1e`0)</a>`n",true);
            output("<a href='newday.php?setspecialty=8$resline'>von Stürmen fasziniert warst (`IW`fi`|n`Nd`3m`Wa`=g`#i`Äe`0)</a>`n",true);
            output("<a href='newday.php?setspecialty=9$resline'>das düstere Chaos gemocht hast ( `ÂC`üh`Ta`âo`Ês`êm`~agie`0)</a>`n",true);
            output("<a href='newday.php?setspecialty=3$resline'>von den Reichen gestohlen und es dir selbst gegeben hast (`EDi`De`_be`(skü`_n`Ds`Ete`0)</a>`n",true);
            output("<a href='newday.php?setspecialty=5$resline'>oft mit dem Schwert gespielt hast (`AS`?c`*h`aw`+ertkü`an`*s`?t`Ae`0)</a>`n",true);


            addnav("Magie");            
            addnav("`µD`Gu`Ín`Ók`Úl`Ée K`Úü`Ón`Ís`Gt`µe`0","newday.php?setspecialty=1$resline");
            addnav("`5M`Oy`Js`%t`li`Ms`Rche `MK`lr`%ä`Jf`Ot`5e`0","newday.php?setspecialty=2$resline");
            addnav("`îF`Ôe`ßu`se`4r`om`Qa`qg`6i`^e`0","newday.php?setspecialty=4$resline");
            addnav("`ÛN`Îa`yt`ôu`úr`àm`ôa`yg`Îi`Ûe`0","newday.php?setspecialty=6$resline");
            addnav("`1W`!a`9s`ùs`ìe`jr`ìm`ùa`9g`!i`1e`0","newday.php?setspecialty=7$resline");
            addnav("`IW`fi`|n`Nd`3m`Wa`=g`#i`Äe`0","newday.php?setspecialty=8$resline");
            addnav("`ÂC`üh`Ta`âo`Ês`êm`~agie`0","newday.php?setspecialty=9$resline");
            addnav("Künste");
            addnav("`EDi`De`_be`(skü`_n`Ds`Ete`0","newday.php?setspecialty=3$resline");
            addnav("`AS`?c`*h`aw`+ertkü`an`*s`?t`Ae`0","newday.php?setspecialty=5$resline");
        }
        else
        {
            addnav("Weiter","newday.php?continue=1$resline");
                switch($_GET['setspecialty']){
                    case 1:
                        page_header("Dunkle Künste");
                                                output("`c`n`n`n`n`b`µD`Gu`Ín`Ók`Úl`Ée K`Úü`Ón`Ís`Gt`µe `b`0`n`n`n`n");
                        output("`7Du erinnerst dich, dass du damit aufgewachsen bist, viele kleine Waldkreaturen zu töten, weil du davon überzeugt warst, sie haben sich gegen dich verschworen. ");
                        output("Deine Eltern haben dir einen idiotischen Zweig gekauft, weil sie besorgt darüber waren, dass du die Kreaturen des Waldes mit bloßen Händen töten musst. ");
                        output("Noch vor deinem Teenageralter hast du damit begonnen, finstere Rituale mit und an den Kreaturen durchzuführen, wobei du am Ende oft tagelang im Wald verschwunden bist. ");
                        output("Niemand außer dir wusste damals wirklich, was die Ursache für die seltsamen Geräusche aus dem Wald war...`c");
                    break;
                    
                    case 2:
                        page_header("Mystische Kräfte");
                                                output("`c`n`n`n`n`b`5M`Oy`Js`%t`li`Ms`Rche `MK`lr`%ä`Jf`Ot`5e `b`0`n`n`n`n");
                        output("`7Du hast schon als Kind gewusst, dass diese Welt mehr als das Physische bietet, woran du herumspielen konntest. ");
                        output("Du hast erkannt, dass du mit etwas Training deinen Geist selbst in eine Waffe verwandeln kannst. ");
                        output("Mit der Zeit hast du gelernt, die Gedanken kleiner Kreaturen zu kontrollieren und ihnen deinen Willen aufzuzwingen. ");
                        output("Du bist auch auf die mystische Kraft namens Mana gestossen, die du in die Form von Feuer, Wasser, Eis, Erde, Wind bringen und sogar als Waffe gegen deine Feinde einsetzen kannst.`c");
                    break;
                    
                    case 3:
                        page_header("Diebeskünste");
                                                output("`c`n`n`n`n`b`EDi`De`_be`(skü`_n`Ds`Ete `b`0`n`n`n`n");
                        output("`7Du hast schon sehr früh bemerkt, dass ein gewöhnlicher Rempler im Gedränge dir das Gold eines vom Glück bevorzugteren Menschen einbringen kann. ");
                        output("Außerdem hast du entdeckt, dass der Rücken deiner Feinde anfälliger gegen kleine Klingen ist, als deren Vorderseite gegen mächtige Waffen.`c");
                    break;
                    
                    case 4:
                        page_header("Feuermagie");
                                                output("`c`n`n`n`n`b`îF`Ôe`ßu`se`4r`om`Qa`qg`6i`^e `b`0`n`n`n`n");
                        output("`7Früh hast du gemerkt, dass das Feuer dein Lieblingselement ist.`c");
                    break;
                    
                    case 5:
                        page_header("Schwertkünste");
                                                output("`c`n`n`n`n`b`AS`?c`*h`aw`+ertkü`an`*s`?t`Ae`b`0`n`n`n`n");
                        output("`7Früh hast du gemerkt , dass du sehr gut mit dem Schwert umgehen kannst.`c");
                    break;
                    
                    case 6:
                        page_header("Naturmagie");
                                                output("`c`n`n`n`n`b`ÛN`Îa`yt`ôu`úr`àm`ôa`yg`Îi`Ûe `b`0`n`n`n`n");
                        output("`7Früh hast du gemerkt , dass du der Natur sehr verbunden bist.`c");
                    break;
                    
                    case 7:
                        page_header("Wassermagie");
                                                output("`c`n`n`n`n`b`1W`!a`9s`ùs`ìe`jr`ìm`ùa`9g`!i`1e `b`0`n`n`n`n");
                        output("`7Früh hast du gemerkt , dass du dem Element des Wassers sehr verbunden bist.`c");
                    break;
                    
                    case 8:
                        page_header("Windmagie");
                                                output("`c`n`n`n`n`b`IW`fi`|n`Nd`3m`Wa`=g`#i`Äe`b`0`n`n`n`n");
                        output("`7Früh hast du gemerkt , dass du dem Element des Windes sehr verbunden bist.");
                    break;
                    
                    case 9:
                        page_header("Chaosmagie");
                                                output("`c`n`n`n`n`b`ÂC`üh`Ta`âo`Ês`êm`~agie`b`0`n`n`n`n");
                        output("`7Früh hast du gemerkt , dass deine negative Energie großen Schaden anrichten kann.`c");
                    break;
                }
            $session['user']['specialty']=$_GET['setspecialty'];
        }
}
//Anfang Klasse auswählen
else if((int)$session['user']['rp_char'] == 0){
    page_header("Wähle eine Klasse");
    
    if ($_GET['setadmin']!="")
    {
        $char_class = (int)($_GET['setadmin']);
        /*if($char_class == 1){
            $session['user']['rp_char'] = 2;
            
            $sql_lehrer = "SELECT acctid FROM accounts WHERE superuser=2 LIMIT 1";
            $result_lehrer = db_query($sql_lehrer) or die(db_error(LINK));
            while ($row_lehrer = db_fetch_assoc($result_lehrer)) {
                systemmail($row_lehrer['acctid'],"`@Neue RP-Bewerbung",$session['user']['name']." `^hat sich als RP-Schüler beworben.");
            }
            
            $session['user']['rpschueler'] = 1;
            
            $sql_in_school = "INSERT INTO `rpbewerb` (id, name) VALUES ('".$session['user']['acctid']."','".$session['user']['name']."')";
            db_query($sql_in_school) or die (db_error(LINK));            
            
        }else{
            $session['user']['rp_char'] = $char_class;
        }*/
        
        $session['user']['rp_char'] = $char_class;
        addnav("Weiter","newday.php?continue=1$resline");
        
        switch($_GET['setadmin']){
            case "1":
                output("`i`b`c#4a4a4aR#676767P#858585-#949494C#a2a2a2h#b1b1b1a#c0c0c0r`0`0`i`b`c`n");
                //output("Diese Chars haben keinen Wald und sind ausschlieslich am RPG interessiert.`n`n`4Der Antrag auf zum RP-Char ernannt zu werden, wurde der RP-Schule geschickt. Bitte hab ein wenig Geduld bis man sich bei dir meldet und weiter entschieden wird.`0");
                output("Diese Chars haben keinen Wald und sind ausschlieslich am RPG interessiert.`0");
            break;
            
            case "2":
                output("`i`b`c#c0c0c0M#b1b1b1i#a2a2a2x#949494-#949494C#7b7b7bh#626262a#4a4a4ar`0`0`i`b`c`n");
                output("Diese Chars können in den Wald kämpfen und sind auch am RPG interessiert, doch haben diese gewisse Einschränkungen.`0");
            break;
            
            case "3":
                output("`i`b`c#c0c0c0L#a2a2a2e#949494v#6f6f6fe#4a4a4al#4a4a4a-#6f6f6fC#949494h#949494a#b1b1b1r`0`i`b`c`n");
                output("Diese Chars Leveln ausschlieslich und sind überhaupt nicht am RPG interessiert.");
            break;
        }        
    }
    else
    {
        output("Was ist deine Klasse?`n`n");
        //output("<a href='newday.php?setadmin=1$resline'>#4a4a4aR#676767P#858585-#949494C#a2a2a2h#b1b1b1a#c0c0c0r`0 `7sind ausschlieslich am RPG interessiert und haben keinen Wald. `b`4Achtung! Dies ist lediglich eine Bewerbung für diese Char-Klasse, bei der Wahl dieser wird eine Anfrage an die RP-Schule gestellt. Der Char wird bis zu einer Entscheidung vorerst unter Mix-Char geführt!`0`b</a>`n`n",true);
        output("<a href='newday.php?setadmin=1$resline'>#4a4a4aR#676767P#858585-#949494C#a2a2a2h#b1b1b1a#c0c0c0r`0 `7sind ausschlieslich am RPG interessiert und haben keinen Wald.`0</a>`n`n", true);
        output("<a href='newday.php?setadmin=2$resline'>#c0c0c0M#b1b1b1i#a2a2a2x#949494-#949494C#7b7b7bh#626262a#4a4a4ar`0 `7sind Accounts die Leveln und RPG'n können, aber dafür eingeschränkt.</a>`n`n",true);
        output("<a href='newday.php?setadmin=3$resline'>#c0c0c0L#a2a2a2e#949494v#6f6f6fe#4a4a4al#4a4a4a-#6f6f6fC#949494h#949494a#b1b1b1r`0 `7sind Accounts die nur Leveln und kein RPG machen.</a>`n`n",true);

        addnav("Wähle deine Klasse");
        addnav("RP-Char","newday.php?setadmin=1$resline");
        addnav("Mix-Char","newday.php?setadmin=2$resline");
        addnav("Kämpfer-Char","newday.php?setadmin=3$resline");
        
        addnav("","newday.php?setadmin=1$resline");
        addnav("","newday.php?setadmin=2$resline");
        addnav("","newday.php?setadmin=3$resline");
    }
}


/**
 * Rassen beim ersten Betreten des Servers anzeigen.
 */

elseif($session['user']['seen_races'] == 0)
{    
    page_header('Wichtige Infos zu den Rassen in New Orleans');
    
    $out = '<p style="font-size: 13pt; font-weight: bold; font-variant: small-caps">`$Wichtiges zur Rassenwahl`0</p> 
<div align=justify>(kann jederzeit unter Rasseninfo eingesehen werden)`0

Bevor ihr einen Char erstellt, werft bitte einen Blick in die <b><i>Rassenliste</i></b>, zu finden unter Serverplot - Rasseninfos!
Manche Rassen, die besonders mächtig sind, sind nur gegen vorherige Absprache mit dem Team gestattet und als solche auch kenntlich gemacht. 
Wer also eine Idee hat, für einen Gott zum Beispiel, setzt sich bitte vor der Erstellung mit uns zusammen. Wir beißen nicht, keine Sorge :) 
Plotchars wie Ratsmitglieder sind bitte ebenfalls mit uns abzusprechen, Informationen dazu findet ihr auch in der Charakterliste.
Schickt dazu einfach über den Support eine Anfrage mit eurem Charkonzept und eurer Charidee, damit wir einen Eindruck bekommen können, dass ihr euch Gedanken gemacht habt und der Char auch durchdacht ist und nicht nur da ist, um sich an der Macht oder Position zu ergötzen und wir schauen, dass es passt ;)

<b>Allgemeines zur Rassenwahl</b>
Die aufgeführten Rassen sind diejenigen, welche am häufigsten vertreten sind. Andere Rassen sind zwar auch möglich, aber auf zwei pro Spieler zu begrenzen (schließlich ist das Übernatürliche am Aussterben ;) ).
In New Orleans (bzw für die Welt unseres Servers) gilt, die Magie und alles was dazu gehört geheim zu halten. Das bedeutet, alle nicht-menschlich ausehenden Wesen sind in die Mythenstadt (Myth Algiers) verbannt. Dort dürfen sie sein, was immer sie wollen. Die Räte der Kontinente kontrollieren die Einhaltung sehr streng (auch Ingame ;) )
Wesen, die Übernatürlich sind, aber menschlich Aussehen, dürfen frei wählen, ob sie in der Mythenstadt oder New Orleans selbst leben wollen.
Desweiteren müssen Handlungen vor Menschen unterbunden werden, die die Geheimhaltung der übernatürlichen Welt gefährden, näheres erfahrt ihr in den Regeln des Rates.

<b>Stärkeverhältnisse</b>
Wie überall gibt es stärkere und schwächere Wesen und auch hier möchten wir sicherstellen, dass es nicht zu Unklarheiten kommt.
(Im Folgenden werden Beispiele genannt, die stellvertretend für alle normalen oder speziellen Rassen gelten! Wird bei Bedarf erweitert.)

&raquo;   Am Schwächsten sind bekanntlich die einfachen <b>Menschen</b>, die über keinerlei magischen Fähigkeiten verfügen.
&raquo;   Menschenähnliche Wesen wie <b>Feen</b>, sind nicht besonders Stark, verfügen aber oft über magische Kräfte.
&raquo;   <b>Menschliche Spezialeinheiten</b> sind je nach ihrer Ausbildungsrichtung einem normalen, übernatürlichen Wesen ebenbürtig.
&raquo;   Ein normaler <b>Hybrid oder Dämon etc</b> wird einem einfachen Vampir etc. normalerweise überlegen sein.
&raquo;   <b>Elitekrieger</b> sind deutlich mächtiger als ihre normalen Verwandten.
&raquo;   <b>Rudelführer, Älteste</b> etc. übersteigen die Elitekrieger um einiges.
&raquo;   <b>Die Hand des Rates</b> besitzen besondere Fähigkeiten, um auch Elitekrieger und Älteste in ihre Schranken weisen zu können 
(doch dürfen sie nicht ohne triftigen Grund von diesen Fähigkeiten gebrauch machen).
&raquo;   <b>UrWesen und hochrangige Dämonen etc.</b> besitzen ungeheure Kräfte und kommen (zum Glück) nur sehr, sehr selten vor.


&raquo;   <b>Götter</b>!
</div>            
            
            
            ';
    
    
    
    
    
    
    output(nl2br($out), true);
    $session['user']['seen_races'] = 1;

    addnav('Weiter', 'newday.php?continue=1'.$resline);
    
    page_footer();
}

/*     Serverplot Char oder nicht? 
    Von Andras für New Orleans */
/*elseif($session['user']['is_plot_char'] == 0)
{
    if(empty($_GET['set_plot']))
    {
        page_header('Plot-Char?');
        
        output('`7Hier geht es darum, ob Du einen Plot-Char spielen willst. Plot Chars nehmen aktiv am Serverplot teil und helfen, diesen weiterzuführen und über die Zeit stetig weiter zu entwickeln. `n
                Dafür sollten sie sich jedoch an einige Vorgaben orientieren, damit das Wissen der Spieler gleich ist und nicht zwei unterschiedliche Sichten und Konzepte aufeinander prallen. Schau Dir am besten erst einmal an, ob Dir die Konzepte
                zu den Hauptrassen im Plot zusagen, falls Du eine von diesen spielen willst. Falls Du z.B. lieber einen Vampir oder Werwolf nach deinen eigenen Vorstellungen gestalten willst, dann wähle "Nein". `n
                Mit der Wahl des Plotchars zeigst du deinen Mitspielern, dass du dich an den Serverrassen orientierst und deinen Char auch so spielst.
                Wählst du "Nein" aus, wissen deine Mitspieler, dass dein Char anders konstruiert und erdacht wurde und von der Servernorm abweichen kann. `n`n
                `n
                Es liegt nun an Dir, was dir lieber ist. Willst Du einen Plot-Char spielen und am Serverplot teilnehmen? `n
                `n
                '.createLink('Ja, mache mich zum Plot-Char!', 'newday.php?set_plot=2'.$resline).' `n
                `n
                '.createLink('Nein, lieber nicht...', 'newday.php?set_plot=1'.$resline), true);
        
        addnav('Plot-Char werden?');
        addnav('Jaaaah!!', 'newday.php?set_plot=2'.$resline);
        addnav('Nöööö!', 'newday.php?set_plot=1'.$resline);
        
        addnav('', 'newday.php?set_plot=2'.$resline);
        addnav('', 'newday.php?set_plot=1'.$resline);
    }
    else
    {
        if($_GET['set_plot'] == 1)
        {
            page_header('Du bist kein Plot-Char');
            
            output('`7Du hast Dich dazu entschieden, nicht am Serverplot teilzunehmen.');
        }            
        else
        {
            page_header('Du bist ein Plot-Char');
            
            output('`7Du hast Dich dazu entschieden, am Serverplot teilzunehmen!');
        }
        
        $session['user']['is_plot_char'] = (INT)$_GET['set_plot'];
        
        addnav('Weiter', 'newday.php?continue=1'.$resline);
    }
}*/

/* Gesinnung by Horus */

else if ((int)$session['user']['gesinnung']==0)
{
    if ($_GET['setgesinnung'] === NULL)
    {

        addnav("","newday.php?setgesinnung=1$resline");
        addnav("","newday.php?setgesinnung=2$resline");
        addnav("","newday.php?setgesinnung=3$resline");
        addnav("","newday.php?setgesinnung=4$resline");
        addnav("","newday.php?setgesinnung=5$resline");
        addnav("","newday.php?setgesinnung=6$resline");
        addnav("","newday.php?setgesinnung=7$resline");
        addnav("","newday.php?setgesinnung=8$resline");
        addnav("","newday.php?setgesinnung=9$resline");
        addnav("","newday.php?setgesinnung=10$resline");

        page_header("Deine Gesinnung");

        output("Zwischen Gut und Böse herrscht schon seit langer Zeit Krieg. Aber welcher Gesinnung gehörst `$ du`0 an?`n`n");
        output("`7Ein kleiner Hinweis noch am Rande: Gesinnungen sind gänzlich Freiwillig und können genutzt werden. Man kann die Gesinnung später
        nach belieben in der Bio anpassen. Wer keine Gesinnung angeben möchte, oder eine eigene Interpretation hätte, kann auch die Option `iKeine Angabe`i
        auswählen. Falls jemand ne Idee braucht, was einzelne Gesinnungen bedeuten können, findet man nachfolgend ein paar Beispiele.`0`n`n");

        output("<a href='newday.php?setgesinnung=1$resline'><b>Du bist Rechschaffen Gut</b></a>
        <br><br>Erklärung: <i>Paladine sind immer rechtschaffen gute Chars. Sie sind die Ritter des Rechts, die Vorkämpfer für das Gute und Reine. 
        Die Gesetze und die Gedanken hinter den Gesetzen sind dabei ihre Richtschnur, um das Böse in jedweder Form zu bekämpfen. Ein Rechtschaffen 
        guter Char hilft alten Damen über die Strasse, versorgt Bettler, lässt Verwundete nie zurück und ist, ganz allgemein gesprochen, der strahlende 
        Held seiner gesamten Nachbarschaft. Ein Polizist ist ein gutes Beispiel für einen rechtschaffen guten Char.</i><br><br>
        `n",true);

        output("<a href='newday.php?setgesinnung=2$resline'><b>Du bist Neutral Gut</b></a>
        <br><br>Erklärung: <i>Neutral gute Chars sind bemüht, das Beste für alle zu erreichen. Die Gesetze eines Landes kümmern sie dabei weniger, 
        denn sie können das Leid nicht mit ansehen. Er oder sie würde nicht gegen das Gesetz verstoßen, wenn es nicht sein muss, aber wenn die Gesetze 
        das Leid begründen - beispielsweise durch ein ungerechtes Kastensystem - dann kümmert diesen Char nicht, ob er mit seiner Hilfe dagegen handelt. 
        Mutter Theresa ist ein gutes Beispiel für einen neutral guten Char. </i><br><br>
        `n",true);

        output("<a href='newday.php?setgesinnung=3$resline'><b>Du bist Chaotisch Gut</b></a>
        <br><br>Erklärung: <i>Chaotisch gute Chars sind darauf aus, die Situation für alle zu verbessern. Dabei sehen sie die Gesetze oftmals als etwas 
        an, das dem entgegen steht. Robin Hood ist ein gutes Beispiel für einen Chaotisch guten Char. Für sich selbst will ein chaotisch guter Char 
        nichts.</i><br><br>
        `n",true);

        output("<a href='newday.php?setgesinnung=4$resline'><b>Du bist Rechtschaffen Neutral</b></a>
        <br><br>Erklärung: <i>Rechtschaffen neutrale Chars halten sich an die Gesetze, und alles andere kümmert sie kaum, sei es, weil es sie schlicht 
        nicht interessiert oder weil sie der restliche Welt so weit entrückt sind, daß sie nichts davon wahrnehmen. Neutrale Chars haben auch die 
        Möglichkeit, einmal altruistisch und freigiebig, und dann wieder egoistisch und selbstbezogen zu handeln - solange sich ihr Vorgehen dabei die 
        Waage hält. Ein Anwalt ist ein gutes Beispiel für einen rechtschaffen neutralen Char.
        </i><br><br>
        `n",true);


        output("<a href='newday.php?setgesinnung=5$resline'><b>Du bist (Absolut) Neutral</b></a>
        <br><br>Erklärung: <i>Wahrhaft neutrale Chars stehen zwischen allem, sowohl zwischen Gut und Böse wie auch zwischen Gesetz und Chaos. Sie müssen ein kompliziertes Gleichgewicht aller vier 
        Faktoren halten, um wahrhaft neutral zu bleiben. Am einfachsten ginge das natürlich, wenn man sich nie und nirgends einmischt - aber in der 
        realen Welt ist dies natürlich nicht möglich. Ein gutes Beispiel für einen wahrhaft neutralen Char ist ein Einsiedler, der irgendwo ganz alleine lebt.</i><br><br>
        `n",true);

        output("<a href='newday.php?setgesinnung=6$resline'><b>Du bist Chaotisch Neutral</b></a>
        <br><br>Erklärung: <i>Chaotisch neutrale Chars versuchen, sich selbst und andere nicht zu kurz kommen zu lassen. Ob das gegen das Gesetz verstösst ist ihnen dabei 
        schnurz. Chaotisch neutrale Chars handeln natürlich dennoch logisch und nachvollziehbar, und werden sich nicht ohne Grund irgendwo Ärger suchen. 
        Oft sehen sich chaotisch neutrale Chars auch als Freischärler und Rebellen für das Gute, doch ihre Ziele zeigen zumeist, das das mit dem Guten 
        entweder nur halbherzig gemeint ist oder auch persönliche Bereicherung zu den Zielen des Chars gehört. Ein Sektenführer, der das Geld seiner 
        Gläubigen nimmt und ihnen dafür das Paradies verspricht, ist ein Beispiel für einen chaotisch neutralen Char.</i><br><br>
        `n",true);

        output("<a href='newday.php?setgesinnung=7$resline'><b>Du bist Rechtschaffen Böse</b></a>
        <br><br>Erklärung: <i>Rechtschaffen böse Chars nutzen den Buchstaben des Gesetzes bis aufs letzte Jota aus, um sich den grösstmöglichen Vorteil 
        zu verschaffen. Oberflächlich betrachtet sind sie dabei Ehrenmänner mit weißer Weste. Ein Beispiel für einen rechtschaffen bösen Char wäre ein 
        Geldhai, der für Wucherzinsen Geld verleiht, oder ein Bordellbesitzer.</i><br><br>
        `n",true);

        output("<a href='newday.php?setgesinnung=8$resline'><b>Du bist Neutral Böse</b></a>
        <br><br>Erklärung: <i>Neutral böse Chars wollen alles für sich. Sie sind skrupellos und gehen über Leichen, ohne mit der Wimper zu zucken. 
        Dennoch ist es ihnen zumeist möglich, mit anderen zu kooperieren, Kompromisse zu schliessen, um selbst mehr Macht zu bekommen. Ein Mafiaboß ist 
        ein gutes Beispiel für einen neutral bösen Char.</i><br><br>
        `n",true);

        output("<a href='newday.php?setgesinnung=9$resline'><b>Du bist Chaotisch Böse</b></a>
        <br><br>Erklärung: <i>Chaotisch böse Chars wollen alles für sich, und alles, was sie nicht haben können, zerstören. Sie enden zumeist im Wahnsinn. 
        Da sie fürchten, daß ihnen jedweder andere irgendetwas wegnehmen könnte oder sie sich mit ihm/ihr arrangieren müssten, arbeiten sie zumeist 
        allein. Chaotisch Böse Chars könnte man mit Bankräubern oder Erpressern vergleichen, die nicht einer kriminellen Organisation zugehörig sind.</i><br><br>
        `n",true);

        output("<a href='newday.php?setgesinnung=10$resline'><b>Keine Angabe</b></a>
        <br><br>Erklärung: <i>Du möchtest keine Aussage treffen, oder du keine der Angaben treffen für deinen Char zu.</i><br><br><br><br><br>
        <font size=1pt>Beschreibungen gefunden auf: http://rollenspiel.doogle.de/cms/front_content.php?idart=804</font> 
        `n",true);

        addnav("Rechschaffen Gut","newday.php?setgesinnung=1$resline");
        addnav("Neutral Gut","newday.php?setgesinnung=2$resline");
        addnav("Chaotisch Gut","newday.php?setgesinnung=3$resline");
        addnav("Rechtschaffen Neutral","newday.php?setgesinnung=4$resline");
        addnav("(Absolut) Neutral","newday.php?setgesinnung=5$resline");
        addnav("Chaotisch Neutral","newday.php?setgesinnung=6$resline");
        addnav("Rechtschaffen Böse","newday.php?setgesinnung=7$resline");
        addnav("Neutral Böse","newday.php?setgesinnung=8$resline");
        addnav("Chaotisch Böse","newday.php?setgesinnung=9$resline");
        addnav("Keine Angabe","newday.php?setgesinnung=10$resline");
    }
    else
    {
        addnav("Weiter","newday.php?continue=1$resline");
        switch($HTTP_GET_VARS['setgesinnung'])
        {
            case 1:

                page_header("Rechschaffen Gut");

                output("`@Du hast dich für `bRechschaffen Gut`b gut entschieden.");
                break;

            case 2:

                page_header("Neutral Gut");

                output("`2Du hast dich für `bNeutral Gut`b entschieden.");
                
                break;

            case 3:

                page_header("Chaotisch Gut");

                output("`7Du hast dich für `bChaotisch Gut`b entschieden.");
                break;

            case 4:

                page_header("Rechtschaffen Neutral");

                output("`4Du hast dich für `bRechtschaffen Neutral`b entschieden.");
                break;

            case 5:

                page_header("(Absolut)Neutral");

                output("`$Du hast dich für `b(Absolut)Neutral`b entschieden.");
                break;

            case 6:

                page_header("Chaotisch Neutral");

                output("`2Du hast dich für `bChaotisch Neutral`b entschieden.");
                break;

            case 7:

                page_header("Rechtschaffen Böse");

                output("`7Du hast dich für `bRechtschaffen Böse`b entschieden.");
                break;

            case 8:

                page_header("Neutral Böse");

                output("`4Du hast dich für `bNeutral Böse`b entschieden.");
                break;

            case 9:

                page_header("Chaotisch Böse");

    output("`$Du hast dich für `bChaotisch Böse`b entschieden.");
                break;

            case 10:

                page_header("Keine Angabe");

                output("`$Du hast dich für `bKeine Angabe`b entschieden.");
                break;


        }
        
        $session['user']['gesinnung'] = $_GET['setgesinnung'];
    }

}
else
{
    if($session['user']['slainby']!=""){
        page_header("Du wurdest umgebracht!");
        output("`\$Im ".$session['user']['killedin']." hat dich `%".$session['user']['slainby']."`\$ getötet und dein Gold genommen. Ausserdem hast du 5% deiner Erfahrungspunkte verloren. Meinst du nicht auch, es ist Zeit für Rache?");
        addnav("Weiter","newday.php?continue=1$resline");
        $session['user']['slainby']="";
    }
    else
    {
        page_header("Neuer Tagesabschnitt!");
        $interestrate = e_rand($mininterest*100,$maxinterest*100)/(float)100;

        if(getgametime() >= "00:00" AND getgametime() < "03:00"){
            output("`c<font size='+4'>`b`rEs ist Mitternacht!`0`b</font>`c",true);
            output("`n`c`9Die Glocken in Astaros läuten den neuen Tag ein! Es ist Mitternacht, die Zeit der Geister und dunklen Rituale.`0`c");
        }else if(getgametime() >= "03:00" AND getgametime() < "06:00"){
            output("`c<font size='+4'>`b`rEs ist späte Nacht!`0`b</font>`c",true);
            output("`n`c`9Es ist drei Uhr in der Frühe, die letzten Schenkengänger machen sich auf den Heimweg.`0`c");
        }else if(getgametime() >= "06:00" AND getgametime() < "09:00"){
            output("`c<font size='+4'>`b`rEs ist Morgengrauen!`0`b</font>`c",true);
            output("`n`c`9Es ist noch sehr früh am Morgen, langsam verdrängen die ersten Sonnenstrahlen die finstere Nacht und die ersten Wesen verlassen ihre Häuser.`0`c");
        }else if(getgametime() >= "09:00" AND getgametime() < "12:00"){
            output("`c<font size='+4'>`b`rEs ist Vormittag!`0`b</font>`c",true);
            output("`n`c`9Es herrscht bereits geschäftiges Treiben auf den Straßen von New Orleans und auf den Stadt- und Marktplätzen bieten die Händler ihre Waren feil.`0`c");
        }else if(getgametime() >= "12:00" AND getgametime() < "15:00"){
            output("`c<font size='+4'>`b`rEs ist Mittagsstunde!`0`b</font>`c",true);
            output("`n`c`9Die Sonne steht am höchsten Punkt und erhellt die schöne Stadt New Orleans. Vielleicht wäre es an der Zeit das Mittagsmahl einzunehmen?`0`c");
        }else if(getgametime() >= "15:00" AND getgametime() < "18:00"){
            output("`c<font size='+4'>`b`rEs ist Nachmittag!`0`b</font>`c",true);
            output("`n`c`9Langsam geht es auf den Abend zu, doch es ist noch nicht zu spät um einen Spaziergang zu machen oder wichtige Geschäfte zu erledigen.`0`c");
        }else if(getgametime() >= "18:00" AND getgametime() < "21:00"){
            output("`c<font size='+4'>`b`rEs ist früher Abend!`0`b</font>`c",true);
            output("`n`c`9Ganz langsam neigt sich die Sonne dem Horizont entgegen, vielleicht der perfekte Zeitpunkt, um noch schnell etwas zu erledigen oder sich auf den Heimweg zu machen.`0`c");
        }else if(getgametime() >= "21:00" AND getgametime() < "24:00"){
            output("`c<font size='+4'>`b`rEs ist Abend!`0`b</font>`c",true);
            output("`n`c`9Die Nacht ist über New Orleans hereingebrochen, langsam leeren sich die Straßen und die Häuser und Schenken füllen sich. Sei auf der Hut! Die Dunkelheit öffnet nicht nur den schönen Dingen ihre Pforten, auch die dunklen Kreaturen erwachen.`0`c");
        }

        //if(!$session['user']['prefs']['nosounds']) output("<embed src=\"media/newday.wav\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);
        
        //Bauernhof by MorganleFay
        /*if($session['user']['bauernhof'] >= 1){
            output("`n`nDein Bauernhof:`n");
            
            if($session['user']['tier_kuh'] == 1){
                $session['user']['gold'] += 200;
                output("`1Da du eine Kuh besitzt bekommst du `^200 Gold`1.`n");
            }else if($session['user']['tier_kuh'] > 1){
                $session['user']['gold'] += ($session['user']['tier_kuh']*200);
                output("`1Da du ".$session['user']['tier_kuh']." Kühe besitzt bekommst du `^".($session['user']['tier_kuh']*200)." Gold`1.`n");
            }
            
            if($session['user']['tier_pferd'] == 1){
                $session['user']['gold'] += 100;
                output("`1Da du ein Pferd besitzt bekommst du `^100 Gold`1.`n");
            }else if($session['user']['tier_pferd'] > 1) {
                $session['user']['gold'] += ($session['user']['tier_pferd']*100);
                output("`1Da du ".$session['user']['tier_pferd']." Pferde besitzt bekommst du `^".($session['user']['tier_pferd']*100)." Gold`1.`n");
            }

            if($session['user']['tier_schwein'] == 1) {
                $session['user']['gold'] += 150;
                output("`1Da du ein Schwein besitzt bekommst du `^150 Gold`1.`n");
            }else if($session['user']['tier_schwein'] > 1) {
                $session['user']['gold'] += ($session['user']['tier_schwein']*150);
                output("`1Da du ".$session['user']['tier_schwein']." Schweine besitzt bekommst du `^".($session['user']['tier_schwein']*150)." Gold`1.`n");
            }

            if($session['user']['tier_huhn'] == 1){
                $session['user']['gold'] += 50;
                output("`1Da du ein Huhn besitzt bekommst du `^50 Gold`1.`n");
            }else if($session['user']['tier_huhn'] > 1) {
                $session['user']['gold'] += ($session['user']['tier_huhn']*50);
                output("`1Da du ".$session['user']['tier_huhn']." Hühner besitzt bekommst du `^".($session['user']['tier_schwein']*50)." Gold`1.`n");
            }
        }*/

        if($session['user']['alive']!=true){
            $session['user']['resurrections']++;
            output("`@Du bist wiedererweckt worden! Dies ist der Tag deiner ".ordinal($session['user']['resurrections'])." Wiederauferstehung.`0`n");
            $session['user']['alive']=true;
        }
        $session['user']['age']++;
        //$session['user']['seenmaster']=0;
                  $session['user']['kondom'] = 0;
        if ($session['user']['god']>0){
            $session['user']['god']--;
        }
        output("`n`n`2Runden: `^$turnsperday`n");


        if($session[user][goldinbank]<0 && abs($session[user][goldinbank])<(int)getsetting("maxinbank",10000)){
            output("`2Heutiger Zinssatz: `^".(($interestrate-1)*100)."% `n");
            output("`2Zinsen für Schulden: `^".-(int)($session['user']['goldinbank']*($interestrate-1))."`9 Gold.`n");
        }else if($session[user][goldinbank]<0 && abs($session[user][goldinbank])>=(int)getsetting("maxinbank",10000)){
            output("`4Die Bank erlässt dir deine Zinsen, da du schon hoch genug verschuldet bist.`n");
            $interestrate=1;
        }else if($session[user][goldinbank]>=0 && $session[user][goldinbank]>=(int)getsetting("maxinbank",10000) && $session['user']['turns']<=getsetting("fightsforinterest",4)){
            $interestrate=1;
            output("`4Die Bank kann dir heute keinen Zinsen zahlen. Sie würde früher oder später an dir pleite gehen.`n");
        }else if($session[user][goldinbank]>=0 && $session[user][goldinbank]<(int)getsetting("maxinbank",10000) && $session['user']['turns']<=getsetting("fightsforinterest",4)){
            output("`2Heutiger Zinssatz: `^".(($interestrate-1)*100)."% `n");
            output("`2Durch Zinsen verdientes Gold: `^".(int)($session['user']['goldinbank']*($interestrate-1))."`n");
        }else{
            $interestrate=1;
            output("`2Dein heutiger Zinssatz beträgt `^0% (Die Bank gibt nur den Leuten Zinsen, die dafür arbeiten)`n");
        }

        output("`2Deine Gesundheit wurde wiederhergestellt auf `^".$session['user']['maxhitpoints']."`n");
        $skills = array(
                                1=>'Dunkle Künste',
                                   'Mystische Kräfte',
                                   'Diebeskünste',
                                   'Feuermagie',
                                   'Schwertkünste',
                                   'Naturmagie',
                                   'Wassermagie',
                                   'Windmagie',
                                   'Chaosmagie'
                          );

        $sb = getsetting("specialtybonus",1);
        output("`n`1Für dein Spezialgebiet `7".$skills[$session['user']['specialty']]."`1, erhältst du zusätzlich $sb Anwendung(en) in `7".$skills[$session['user']['specialty']]."`1 für heute.`n");
        $session['user']['darkartuses'] = (int)($session['user']['darkarts']/3) + ($session['user']['specialty']==1?$sb:0);
        $session['user']['magicuses'] = (int)($session['user']['magic']/3) + ($session['user']['specialty']==2?$sb:0);
        $session['user']['thieveryuses'] = (int)($session['user']['thievery']/3) + ($session['user']['specialty']==3?$sb:0);
        $session['user']['fireuses'] = (int)($session['user']['fire']/3) + ($session['user']['specialty']==4?$sb:0);
        $session['user']['sworduses'] = (int)($session['user']['sword']/3) + ($session['user']['specialty']==5?$sb:0);
        $session['user']['natureuses'] = (int)($session['user']['nature']/3) + ($session['user']['specialty']==6?$sb:0);
        $session['user']['wateruses'] = (int)($session['user']['water']/3) + ($session['user']['specialty']==7?$sb:0);
        $session['user']['winduses'] = (int)($session['user']['wind']/3) + ($session['user']['specialty']==8?$sb:0);
        $session['user']['chaosuses'] = (int)($session['user']['chaos']/3) + ($session['user']['specialty']==9?$sb:0);
        
        //clear all standard buffs
        $tempbuf = unserialize($session['user']['bufflist']);
        $session['user']['bufflist']="";
        $session['bufflist']=array();
        while(list($key,$val)=@each($tempbuff)){
            if($val['survivenewday']==1){
                $session['bufflist'][$key]=$val;
                output("{$val['newdaymessage']}`n");
            }
        }
        
        //Kindersystem
        if($session['user']['sstatus'] >= 1){
            if($session['user']['scounttoday'] == 1){
                if($session['user']['scounttodaydate'] != date("d.m.")){
                    $session['user']['scounttoday'] = 0;
                }
            }
            
            if($session['user']['scounttoday'] == 0){
                $session['user']['smonat']--;
                $session['user']['scounttoday'] = 1;
                $session['user']['scounttodaydate'] = date("d.m.");
            }
            
            if($session['user']['smonat'] <= getsetting("schwanger_dauer",40)){
                if($session['user']['smonat'] > 0){
                    output("`n`!`b`iDu bist schwanger... Also pass auf dich auf`n`b`i");
                    $session['bufflist']['schwanger'] = array("name"=>"`&Schwangerschaft","rounds"=>1000000,"wearoff"=>"Irgendwas stimmt nicht mehr.","defmod"=>0,"roundmsg"=>"`9Du versucht deinen Bauch zu schützen und nimmst so jeden anderen Treffer in kauf.","activate"=>"offense");
                    output("`1Noch `7".$session['user']['smonat']." `1Tage`n`n");
                }else{
                    $sql_partner = "SELECT name FROM accounts WHERE acctid=".$session['user']['serzeug'];
                    $result_partner = db_query($sql_partner);
                    $row_partner = db_fetch_assoc($result_partner);
                
                    if($session['user']['sstatus'] == 2){
                        $session['user']['sstatus'] = 0;
                        $geschlechta = e_rand()%2;
                        $geschlechtb = e_rand()%2;
                        
                        output("`!`b`iDu bist bist heute Mutter geworden... Es sind Zwillinge! Vergiss nicht die neuen Erdenbürger in der Kappelle zu taufen, sonst wird niemals jemand wissen das es ihn gibt und das wäre doch traurig!`n`n`b`i");

                        if($geschlechta == $geschlechtb && $geschlechtb == 1){
                            $t = "`7Es sind zwei Mädchen!`n";
                        }else if($geschlechta == $geschlechtb && $geschlechtb == 0){
                            $t = "`7Es sind zwei Jungs!`n";
                        }else{
                            $t = "`7Es ist ein Mädchen und ein Junge!`n";
                        }
                        
                        output($t);

                        systemmail($session['user']['serzeug'],"`%Du bist Vater!`0","`&Du bist seit heute Vater. ".$session['user']['name']." `&hat heute ein zwei wunderschöne Babies zur Welt gebracht, vergesst nicht sie in der Kapelle zu taufen. ".$t);
                        systemmail($session['user']['acctid'],"`%Du bist Mutter!`0","`&Du hast heute zwei wunderschöne Babies zur Welt gebracht, vergesst nicht sie in der Kapelle zu taufen. ".$t);
                        
                        addnews($session['user']['name']." & ".$row_partner['name'] . "sind heute Eltern geworden.");
                            
                        if($session['user']['serzeug'] != $session['user']['marriedto']){
                            $unehelich = 1;
                        }else{
                            $unehelich = 0;
                        }
                        
                        $sqlkind = "INSERT INTO kinder VALUES ('','".$session['user']['acctid']."','".$session['user']['serzeug']."','','','".$geschlechta."','".getgamedate()."',$unehelich,'','',0,0);";
                        db_query($sqlkind) or die(db_error(LINK));
                            
                        $sqlkind = "INSERT INTO kinder VALUES ('','".$session['user']['acctid']."','".$session['user']['serzeug']."','','','".$geschlechtb."','".getgamedate()."',$unehelich,'','',0,0);";
                        db_query($sqlkind) or die(db_error(LINK));
                        
                        $session['user']['serzeug'] = 0;
                    }else{
                        $session['user']['sstatus'] = 0;
                        $geschlecht = e_rand()%2;
                        output("`!`b`iDu bist bist heute Mutter geworden... Vergiss nicht den neuen Erdenbürger in der Kappelle zu taufen, sonst wird niemals jemand wissen, das es ihn gibt und das wäre doch traurig!`n`n`i`b");

                        if($geschlecht == 1){
                            $t = "`7Es ist ein Mädchen!";
                        }else{
                            $t = "`7Es ist ein Junge!";
                        }
                        
                        output($t);

                        systemmail($session['user']['serzeug'],"`%Du bist Vater!`0","`&Du bist seit heute Vater. ".$session['user']['name']." `& hat heute ein wunderschönes Baby zur Welt gebracht, vergesst nicht es in der Kapelle zu taufen. ".$t);
                        systemmail($session['user']['acctid'],"`%Du bist Mutter!`0","`&Du hast heute ein wunderschönes Baby zur Welt gebracht, vergesst nicht es in der Kapelle zu taufen. ".$t);
                        
                        addnews($session['user']['name']." & ".$row_partner['name']." sind heute Eltern geworden.");
                        
                        if($session['user']['serzeug'] != $session['user']['marriedto']){
                            $unehelich = 1;
                        }else{
                            $unehelich = 0;
                        }
                
                        $sqlkind = "INSERT INTO kinder VALUES ('','".$session['user']['acctid']."','".$session['user']['serzeug']."','','','".$geschlecht."','".getgamedate()."',$unehelich,'','',0,0);";
                        db_query($sqlkind) or die(db_error(LINK));
                        
                        $session['user']['serzeug'] = 0;
                    }
                }
            }
        }
        
        reset($session['user']['dragonpoints']);
        $dkff=0;
        while(list($key,$val)=each($session['user']['dragonpoints'])){
            if ($val=="ff"){
                $dkff++;
            }
        }
        
        if ($session['user']['hashorse']){
            $session['bufflist']['mount']=unserialize($playermount['mountbuff']);
        }
        
        if ($dkff>0) output("`n`2Du erhöhst deine Waldkämpfe um `^$dkff`2 durch verteilte Drachenpunkte!"); 
        $r1 = e_rand(-1,1);
        $r2 = e_rand(-1,1);
        $spirits = $r1+$r2;
        
        if ($_GET['resurrection']=="true"){
            addnews("`&{$session['user']['name']}`& wurde von `\$Ramius`& wiedererweckt.");
            $spirits=-6;
            $session['user']['deathpower']-=100;
            $session['user']['restorepage']="village.php?c=1";
        }
        
        if ($_GET['resurrection']=="egg"){
            addnews("`&{$session['user']['name']}`& hat das `^goldene Ei`& benutzt und entkam so dem Schattenreich.");
            $spirits=-6;
            //$session['user']['deathpower']-=100;
            $session['user']['restorepage']="village.php?c=1";
            savesetting("hasegg",stripslashes(0));
        }
        
        $sp = array((-6)=>"Auferstanden",(-2)=>"Sehr schlecht",(-1)=>"Schlecht","0"=>"Normal",1=>"Gut",2=>"Sehr gut");
        output("`n`2Dein Geist und deine Stimmung ist heute `^".$sp[$spirits]."`9!`n");
        if (abs($spirits)>0){
            output("`2Deswegen `^");
            if($spirits>0){
                output("bekommst du zusätzlich ");
            }else{
                output("verlierst du ");
            }
            output(abs($spirits)." Runde/n`2 für heute.`n");
        }
        
        $rp = $session['user']['restorepage'];
        $x = max(strrpos("&",$rp),strrpos("?",$rp));
        if ($x>0) $rp = substr($rp,0,$x);
        if (substr($rp,0,10)=="badnav.php"){
            addnav("Weiter","news.php");
        }else{
            addnav("Weiter",preg_replace("'[?&][c][=].+'","",$rp));
        }
        
        $session['user']['laston'] = date("Y-m-d H:i:s");
        $bgold = $session['user']['goldinbank'];
        $session['user']['goldinbank']*=$interestrate;
        $nbgold = $session['user']['goldinbank'] - $bgold;

        if ($nbgold != 0) {
            //debuglog(($nbgold >= 0 ? "earned " : "paid ") . abs($nbgold) . " gold in interest");
        }
        
        $session['user']['turns']=$turnsperday+$spirits+$dkff;
        if ($session['user']['maxhitpoints']<6) $session['user']['maxhitpoints']=6;
        $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
        $session['user']['spirits'] = $spirits;
        $session['user']['playerfights'] = $dailypvpfights;
        $session['user']['transferredtoday'] = 0;
                $session['user']['pilzsuche']='0';
        $session['user']['amountouttoday'] = 0;
                $session['user']['hellwheel'] = 0;
                $session['user']['deadtreepick']=0;
        
        /**
         *    Wird nun über die c_special_val.php Funktionen geändert.
         *  set_special_val in Kombination mit einem Änerungsarray, das alle Elemente auf 0 zurücksetzt.
         **/
        //$session['user']['seendragon'] = 0;
        //$session['user']['seenmaster'] = 0;
        //$session['user']['seenlover'] = 0;
        //$session['user']['witch'] = 0;
        //$session['user']['trauer'] = 0;
        //$session['user']['usedouthouse'] = 0;
        //$session['user']['seenAcademy'] = 0;
        //$session['user']['gotfreeale'] = 0;
        //$session['user']['fedmount'] = 0;
        $session['user']['waisen2'] = 0;
        
    
        $change_array = array('seendragon',
                              'seenmaster',
                              'seenlover',
                              'seenbard',
                              'witch',
                              'trauer',
                              'usedouthouse',
                              'seenAcademy',
                              'gotfreeale',
                              'fedmount');
        set_special_val($change_array);
                      

        if ($_GET['resurrection']!="true" && $_GET['resurrection']!="egg" ){
            $session['user']['soulpoints']=50 + 5 * $session['user']['level'];
            $session['user']['gravefights']=getsetting("gravefightsperday",10);
            $session['user']['reputation']+=5;
        }
    //    $session['user']['seenbard'] = 0;
                $session['user']['tanz_heute'] = 0;
                $session['user']['seenminx'] = 0;
                $session['user']['wasserfall']= 0;
        
        $session['user']['boughtroomtoday'] = 0;
        $session['user']['lottery'] = 0;
        $session['user']['recentcomments']=$session['user']['lasthit'];
        $session['user']['lasthit'] = date("Y-m-d H:i:s");
        if ($session['user']['drunkenness']>66){
          output("`&Wegen deines schrecklichen Katers wird dir 1 Runde für heute abgezogen.");
            $session['user']['turns']--;
        }
        
// following by talisman & JT
//Set global newdaysemaphore

       $lastnewdaysemaphore = convertgametime(strtotime(getsetting("newdaysemaphore","0000-00-00 00:00:00")));
       $gametoday = gametime();
        
        if (date("Ymd",$gametoday)!=date("Ymd",$lastnewdaysemaphore)){
            $sql = "LOCK TABLES settings WRITE";
            db_query($sql);

           $lastnewdaysemaphore = convertgametime(strtotime(getsetting("newdaysemaphore","0000-00-00 00:00:00")));
                                                                                
            $gametoday = gametime();
            if (date("Ymd",$gametoday)!=date("Ymd",$lastnewdaysemaphore)){
                //we need to run the hook, update the setting, and unlock.
                savesetting("newdaysemaphore",date("Y-m-d H:i:s"));
                $sql = "UNLOCK TABLES";
                db_query($sql);
                                                                                
            require_once "setnewday.php";    

            }else{
                //someone else beat us to it, unlock.
                $sql = "UNLOCK TABLES";
                db_query($sql);
                output("Somebody beat us to it");
            }
        }
    
    // Adventspecial für Merydiâ, der Anfang ist in der setnewday.php, eine Anleitung findet ihr unter www.merydia.de, www.anpera.net oder bei http://www.dai-clan.de/SiliForum/wbb2/

    // Copyright by Leen/Cassandra (cassandra@leensworld.de)
    // SQL: ALTER TABLE `accounts` ADD `specialperday` INT( 11 ) NOT NULL ; <- auch nutzbar für andere Specials die an bestimmten REAL-Tagen stattfinden und man es nicht jeden Tag nutzen darf
    if ($settings['weihnacht'] > '0')
        {
        $datum = getsetting('weihnacht','01-01');
        $adventtag = explode('-',$datum);
        if ($adventtag[1] <= 24 && $adventtag[1] > 0)
            {
            output('`b`$`n`nHeute ist der '.$adventtag[1].'. Dezember! Du darfst heute den Beutel mit der Nummer '.$adventtag[1].' aufmachen, schau schnell was du geschenkt bekommst!`n`0`b');
            if ($session['user']['specialperday'] < $adventtag[1])
                {
                $session['user']['specialperday'] = $adventtag[1];
                $bild = $adventtag[1];
                output('`n`c<img src="images/system/advent/'.$bild.'.gif"  alt="'.$bild.'.12.">`c`n`n',true);
                //Geschenke *sabber*
                switch ($adventtag[1])
                    {
                    case 24:
                    switch (e_rand(1,5))
                        {
                        case 1:
                        if ($session['user']['experience'] < 20000)
                            {
                            $session['user']['experience'] += 4000;
                            $turnsperday += 30;
                            output('`c`@Du öffnest den Beutel und findest `^4000 `@Erfahrungspunkte und Waldkämpfe.`n`bFrohe Weihnachten wünscht das Team von New Orleans.`c`b`n`n');
                            break;
                            }
                        case 2:
                        $gesamtgold = ($session['user']['gold'])+($session['user']['goldinbank']);
                        if ($gesamtgold < 50000)
                            {
                            $session['user']['gold'] += 40000;
                            $turnsperday += 30;
                            output('`c`@Du öffnest den Beutel und findest `^40000 `@Goldstücke und Waldkämpfe.`n`bFrohe Weihnachten wünscht das Team von New Orleans.`c`b`n`n');
                            break;
                            }
                        case 3:
                        if ($session['user']['gems'] < 100)
                            {
                            $session['user']['gems'] += 15;
                            $turnsperday += 30;
                            output('`c`@Du öffnest den Beutel und findest `^15 `@Edelsteine und Waldkämpfe.`n`bFrohe Weihnachten wünscht das Team von New Orleans.`c`b`n`n');
                            break;
                            }
                        case 4:
                        $session['user']['defence'] += 3;
                        $session['user']['attack'] += 3;
                        $turnsperday += 30;
                        output('`c`@Du öffnest den Beutel und findest je `^3 `@Angriffs- und Verteidigungspunkte, sowie Waldkämpfe.`n`bFrohe Weihnachten wünscht das Team von New Orleans.`c`b`n`n');
                        break;
                        case 5:
                        $session['user']['deathpower'] += 200;
                        $turnsperday += 30;
                        output('`c`@Du öffnest den Beutel und findest `^200 `@Gefallen und Waldkämpfe.`n`bFrohe Weihnachten wünscht das Team von New Orleans.`c`b`n`n');
                        break;
                        }
                    break;
                    default:
                    switch (e_rand(1,5))
                        {
                        case 1:
                        if ($session['user']['experience'] < 20000)
                            {
                            $session['user']['experience'] += 500;
                            $turnsperday += 5;
                            output('`c`@Du öffnest den Beutel und findest `^500 `@Erfahrungspunkte und Waldkämpfe.`c`n`n');
                            break;
                            }
                        case 2:
                        $gesamtgold = ($session['user']['gold'])+($session['user']['goldinbank']);
                        if ($gesamtgold < 50000)
                            {
                            $session['user']['gold'] += 5000;
                            $turnsperday += 5;
                            output('`c`@Du öffnest den Beutel und findest `^5000 `@Goldstücke und Waldkämpfe.`c`n`n');
                            break;
                            }
                        case 3:
                        if ($session['user']['gems'] < 100)
                            {
                            $session['user']['gems'] += 5;
                            $turnsperday += 5;
                            output('`c`@Du öffnest den Beutel und findest `^5 `@Edelsteine und Waldkämpfe.`c`n`n');
                            break;
                            }
                        case 4:
                        $session['user']['defence'] += 1;
                        $session['user']['attack'] += 1;
                        $turnsperday += 5;
                        output('`c`@Du öffnest den Beutel und findest je `^1 `@Angriffs- und Verteidigungspunkt, sowie Waldkämpfe.`c`n`n');
                        break;
                        case 5:
                        $session['user']['deathpower'] += 50;
                        $turnsperday += 5;
                        output('`c`@Du öffnest den Beutel und findest `^50 `@Gefallen und Waldkämpfe.`c`n`n');
                        break;
                        }
                    break;
                    }
                }
            else
                {
                output('`b`$`n`nDu hast heute schon deinen Beutel aufgemacht!`n`n`0`b');
                }
            }
        }
    else
        {
        $session['user']['specialperday'] = 0;
        }

    output("`nDer Schmerz in deinen wetterfühligen Knochen sagt dir das heutige Wetter: `6".$settings['weather']."`@.`n");
    if ($_GET['resurrection']==""){
        if ($session['user']['specialty']==1 && $settings['weather']=="Regnerisch"){
            output("`^`nDer Regen schlägt dir aufs Gemüt, aber erweitert deine Dunklen Künste. Du bekommst eine zusätzliche Anwendung.`n");
            $session[user][darkartuses]++;
            }    
        if ($session['user']['specialty']==2 and $settings['weather']=="Gewittersturm"){
            output("`^`nDie Blitze fördern deine Mystischen Kräfte. Du bekommst eine zusätzliche Anwendung.`n");
            $session[user][magicuses]++;
            }    
        if ($session['user']['specialty']==3 and $settings['weather']=="Neblig"){
            output("`^`nDer Nebel bietet Dieben einen zusätzlichen Vorteil. Du bekommst eine zusätzliche Anwendung.`n");
            $session[user][thieveryuses]++;
            }        
    }
    
    //End global newdaysemaphore code and weather mod.

    
        if ($session['user']['hashorse']){
            output(str_replace("{weapon}",$session['user']['weapon'],"`n`&{$playermount['newday']}`n`0"));
            if ($playermount['mountforestfights']>0){
                output("`n`&Weil du ein(e/n) {$playermount['mountname']} `&besitzt, bekommst du `^".((int)$playermount['mountforestfights'])."`& Runden zusätzlich.`n`0");
                $session['user']['turns']+=(int)$playermount['mountforestfights'];
            }
        }else{
            output("`n`&Du schnallst dein(e/n) `%".$session['user']['weapon']."`& auf den Rücken und ziehst los ins Abenteuer.`0");
        }
        if ($session['user']['race']==3) {
            $session['user']['turns']++;
            output("`n`&Weil du ein Mensch bist, bekommst du `^1`& Waldkampf zusätzlich!`n`0");
        }
        $config = unserialize($session['user']['donationconfig']);
        if (!is_array($config['forestfights'])) $config['forestfights']=array();

/* Allmightys Steine - Bonis von: silienta-logd.de */
$result = db_query("SELECT * FROM items WHERE owner='".$session['user']['acctid']."' AND class='Allmightys Stein' LIMIT 1");
$row = db_fetch_assoc($result);
db_free_result($result);
switch($row['name'])
{
case '`$Poker Stein':
output("`n`n`\$Weil du den $row[name] `\$ besitzt , verlierst du einen Waldkampf!`n");
$session['user']['turns']-=1;
break;
case '`^Liebes Stein':
output("`n`n`\$Weil du den {$row[name]} `\$ besitzt , bekommst du einen Charmepunkt!`n");
$session['user']['charm']+=1;
break;
case '`^Freundschafts Stein':
output("`n`n`\$Weil du den {$row[name]} `\$ besitzt , bekommst du einen Waldkampf!`n");
$session['user']['turns']+=1;
break;
case '`#Königs Stein':
output("`n`n`\$Weil du den {$row[name]} `\$ besitzt , bekommst du 500 Gold!`n");
$session['user']['gold']+=500;
break;
case '`#AllMighthys Stein':
output("`n`n`\$Weil du den {$row[name]} `\$ besitzt , bekommst du mehr Angriff`n");
$session['bufflist']['stone'] = unserialize($row['buff']);
break;
case '`#Pegasus Stein':
output("`n`n`\$Weil du den {$row[name]} `\$ besitzt , bekommst du einen Waldkampf!`n");
$session['user']['turns']+=1;
break;
case '`@Aris Stein':
output("`n`n`\$Weil du den {$row[name]} `\$ besitzt , bekommst du mehr Angriff`n");
$session[bufflist][stone] = unserialize($row['buff']);
break;
case '`@Excaliburs Stein':
output("`n`n`\$Weil du den {$row['name']} `\$ besitzt , hast du das Wissen eines Gelehrten`n");
$session['user']['darkartuses']+=6;
$session['user']['magicuses']+=6;
$session['user']['thieveryuses']+=6;
break;
case '`@Lukes Stein':
output("`n`n`\$Weil du den {$row['name']} `\$ besitzt , bekommst du mehr Angriff`n");
$session['user']['darkartuses']+=6;
$session['user']['magicuses']+=6;
$session['user']['thieveryuses']+=6;
break;
case '`#Stein der Königin':
output("`n`n`\$Weil du den {$row['name']} `\$ besitzt , bekommst du 500 Gold!`n");
$session['user']['gold']+=500;
break;
case '`#Stein des Eroberers':
output("`n`n`\$Weil du den {$row['name']} `\$ besitzt , verlierst du einen Waldkampf!`n");
$session['user']['turns']-=1;
break;
case '`!Goldener Stein':
output("`n`n`\$Weil du den {$row['name']} `\$ besitzt , bekommst du 1000 Gold!`n");
$session['user']['gold']+=1000;
break;
case '`%Kraft Stein':
output("`n`n`\$Weil du den {$row['name']} `\$ besitzt , bekommst du mehr Angriff und Verteidigung`n");
$session['bufflist']['stone'] = unserialize($row['buff']);
break;
case '`\$Ramius Stein':
output("`n`n`\$Weil du den {$row['name']} `\$ besitzt , bekommst du die Macht des Waldgottes!`n");
$session['user']['turns']+=10;
break;
case '`#Cedriks Stein':
output("`n`n`\$Weil du den {$row['name']} `\$ besitzt , wirst du stärker!`n");
$session['bufflist']['stone'] = unserialize($row['buff']);
break;
case '`%Baldurs Stein':
output("`n`n`\$Weil du den {$row['name']} `\$ besitzt , bekommst du 2 Waldkämpfe!`n");
$session['user']['turns']+=2;
break;
case '`&Stein der Reinheit':
output("`n`n`\$Weil du den {$row['name']} `\$ besitzt , bekommst du 2 Waldkämpfe!`n");
$session['user']['turns']+=2;
break;
case '`&Stein des Lichts':
output("`n`n`\$Weil du den {$row['name']} `\$ besitzt , hast du das Wissen eines Gelehrten`n");
$session['user']['charm']++;
break;
case '`&Ladys Stein':
output("`n`n`\$Weil du den {$row['name']} `\$ besitzt , bekommst du einen Edelstein`n");
$session['user']['gems']+=1;
break;
}




        reset($config['forestfights']);
        while (list($key,$val)=each($config['forestfights'])){
            $config['forestfights'][$key]['left']--;
            output("`@Du bekommst eine Extrarunde für die Punkte auf `^{$val['bought']}`@.");
            $session['user']['turns']++;
            if ($val['left']>1){
                output(" Du hast `^".($val['left']-1)."`@ Tage von diesem Kauf übrig.`n");
            }else{
                unset($config['forestfights'][$key]);
                output(" Dieser Kauf ist damit abgelaufen.`n");
            }
        }
        if ($config['healer'] > 0) {
            $config['healer']--;
            if ($config['healer'] > 0) {
                output("`n`@Golinda ist bereit, dich noch {$config['healer']} weitere Tage zu behandeln.");
            } else {
                output("`n`@Golinda wird dich nicht länger behandeln.");
                unset($config['healer']);
            }
        }
        if ($config['goldmineday']>0) $config['goldmineday']=0;
        $session['user']['donationconfig']=serialize($config);
        if ($session['user']['hauntedby']>""){
            output("`n`n`)Du wurdest von {$session['user']['hauntedby']}`) heimgesucht und verlierst eine Runde!");
            $session['user']['turns']--;
            $session['user']['hauntedby']="";
        }
        // Ehre & Ansehen
        if ($session['user']['reputation']<=-50){
            $session['user']['reputation']=-50;
            output("`n`8Da du aufgrund deiner Ehrenlosigkeit häufig Steine in den Weg gelegt bekommst, kannst du heute 1 Runden weniger kämpfen. Außerdem sind deine Feinde vor dir gewarnt.`nDu solltest dringend etwas für deine Ehre tun!");
            $session['user']['turns']--;
            $session['user']['playerfights']--;
        }else if ($session['user']['reputation']<=-30){
            output("`n`8Deine Ehrenlosigkeit hat sich herumgesprochen! Deine Feinde sind vor dir gewarnt, weshalb dir heute 1 Spielerkampf weniger gelingen wird.`nDu solltest dringend etwas für deine Ehre tun!");
            $session['user']['playerfights']--;
        }else if ($session['user']['reputation']<-10){
            output("`n`8Da du aufgrund deiner Ehrenlosigkeit häufig Steine in den Weg gelegt bekommst, kannst du heute 1 Runde weniger kämpfen.");
            $session['user']['turns']--;
        }else if ($session['user']['reputation']>=30){
            if ($session['user']['reputation']>50) $session['user']['reputation']=50;
            output("`n`9Da du aufgrund deiner großen Ehrenhaftigkeit das Volk auf deiner Seite hast, kannst du heute 1 Runde und 1 Spielerkampf mehr kämpfen.");
            $session['user']['turns']++;
            $session['user']['playerfights']++;
        }else if ($session['user']['reputation']>10){
            output("`n`9Da du aufgrund deiner großen Ehrenhaftigkeit das Volk auf deiner Seite hast, kannst du heute 1 Runde mehr kämpfen.");
            $session['user']['turns']++;
        }

        $session['user']['drunkenness']=0;
                $session['user']['sueturm']=0;
        $session['user']['bounties']=0;
        
        if($session['user']['blut'] == 5){
            $session['user']['blut']+=0;
        }else{
            $session['user']['blut']+=1;
        }
        /*
           $session['user']['drabru']=0;
           $session['user']['draker']=0;
        $session['user']['drakerp']=0;
        $session['user']['drakers']=0;
        $session['user']['drasch']=0;
        $session['user']['dragru']=0;*/
        set_special_val('drabru', 0);
        set_special_val('draker', 0);
        set_special_val('drakerp', 0);
        set_special_val('drakers', 0);
        set_special_val('drasch', 0);
        set_special_val('dragru', 0);

        //Buffs from items
        $sql="SELECT * FROM items WHERE (class='Fluch' OR class='Geschenk' OR class='Zauber') AND owner=".$session[user][acctid]." ORDER BY id";
        $result=db_query($sql);
        for ($i=0;$i<db_num_rows($result);$i++){
              $row = db_fetch_assoc($result);
            if (strlen($row[buff])>8){
                $row[buff]=unserialize($row[buff]);
                if ($row['class']!='Zauber') $session[bufflist][$row[buff][name]]=$row[buff];
                if ($row['class']=='Fluch') output("`n`G$row[name]`G nagt an dir.");
                if ($row['class']=='Geschenk') output("`n`1$row[name]`1: $row[description]");
            }
            if ($row[hvalue]>0){
                $row[hvalue]--;
                if ($row[hvalue]<=0){
                    db_query("DELETE FROM items WHERE id=$row[id]");
                    if ($row['class']=='Fluch') output(" Aber nur noch heute.");
                    if ($row['class']=='Zauber') output("`n`Q$row[name]`Q hat seine Kraft verloren.");
                }else{
                    $what="hvalue=$row[hvalue]";
                    if ($row['class']=='Zauber') $what.=", value1=$row[value2]";
                    db_query("UPDATE items SET $what WHERE id=$row[id]");
                }
            }
        }        
    }
}


$sql_pen = "SELECT gid,angeklagter,frist,pen_gold,pen_gems FROM gericht WHERE angeklagter=".$session['user']['acctid'];
$result_pen = db_query($sql_pen) or die(db_error(LINK));
while($row_pen = db_fetch_assoc($result_pen)){
    if($row_pen['frist'] > 0 AND ($row_pen['pen_gold'] > 0 OR $row_pen['pen_gems'] > 0)){
        $new_frist = $row_pen['frist'] - 1;
    
        $sql = "UPDATE gericht SET frist=".$new_frist." WHERE gid=".$row_pen['gid']."";
        $result = db_query($sql) or die(db_error(LINK));

        output("`n`n`9Du hast noch ".$new_frist." Tage Zeit um deine Strafe von `^".$row_pen['pen_gold']." `9Gold und `%".$row_pen['pen_gold']." `9 Edelsteinen zu zahlen.");

    }else if($row_pen['frist'] == 0 AND ($row_pen['pen_gold'] > 0 OR $row_pen['pen_gems'] > 0)){
        $session['user']['jailtime'] = 2;
        
        output("`n`n`9Du hast die Frist zum begleichen deiner Strafe nicht eingehalten und kommst für zwei Tage an den Pranger.");
        
        $sql = "UPDATE gericht SET frist=12 WHERE gid=".$row_pen['gid']."";
        $result = db_query($sql) or die(db_error(LINK));
        
        clearnav();
        addnav("weiter","pranger.php");
    }
}

/*
//Pranger & Gefängniss
if ($session['user']['prison'] == 1){
    $session['user']['prisondays']--;
}

if ($session['user']['jailtime'] > 0){
    $session['user']['jailtime']--;
}*/




$session['user']['special_taken'] = 0;


page_footer();

?>

