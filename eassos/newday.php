
<?php

// 24072004

require_once "common.php";

if($_GET['act'] == 'admin')
    debuglog('Neuen Tag gewÃ¤hrt.');

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

if(count($session['user']['dragonpoints'])<$session['user']['dragonkills'] && $_GET['dk']!="ignore"){
    page_header("Drachenpunkte");
    addnav("Max Lebenspunkte +5","newday.php?dk=hp$resline");
    addnav("WaldkÃ¤mpfe +1","newday.php?dk=ff$resline");
    addnav("Angriff + 1","newday.php?dk=at$resline");
    addnav("Verteidigung + 1","newday.php?dk=de$resline");
    output("`@Du hast noch `^".($session['user']['dragonkills']-count($session['user']['dragonpoints']))."`@  Drachenpunkte Ã¼brig. Wie willst du sie einsetzen?`n`n");
    output("Du bekommst 1 Drachenpunkt pro getÃ¶tetem Drachen. Die Ã„nderungen der Eigenschaften durch Drachenpunkte sind permanent.");
    }else if ((int)$session['user']['specialty']==0){
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
            page_header("Ein wenig Ã¼ber deine Vorgeschichte");

            output("Du erinnerst dich, dass du als Kind:`n`n");
            output("<a href='newday.php?setspecialty=1$resline'>viele Kreaturen des Waldes getÃ¶tet hast (`ÂµD`Gu`Ãn`Ã“k`Ãšl`Ã‰e K`ÃšÃ¼`Ã“n`Ãs`Gt`Âµe`0)</a>`n",true);
            output("<a href='newday.php?setspecialty=2$resline'>mit mystischen KrÃ¤ften experimentiert hast (`5M`Oy`Js`%t`li`Ms`Rche `MK`lr`%Ã¤`Jf`Ot`5e`0)</a>`n",true);
            output("<a href='newday.php?setspecialty=4$resline'>gelernt hast mit dem Feuer umzugehen (`Ã®F`Ã”e`ÃŸu`se`4r`om`Qa`qg`6i`^e`0)</a>`n",true);
            output("<a href='newday.php?setspecialty=6$resline'>schon immer mit der Natur verbunden warst (`Ã›N`ÃŽa`yt`Ã´u`Ãºr`Ã m`Ã´a`yg`ÃŽi`Ã›e`0)</a>`n",true);
            output("<a href='newday.php?setspecialty=7$resline'>immer etwas mit Wasser zu tun hattest (`1W`!a`9s`Ã¹s`Ã¬e`jr`Ã¬m`Ã¹a`9g`!i`1e`0)</a>`n",true);
            output("<a href='newday.php?setspecialty=8$resline'>von StÃ¼rmen fasziniert warst (`IW`fi`|n`Nd`3m`Wa`=g`#i`Ã„e`0)</a>`n",true);
            output("<a href='newday.php?setspecialty=9$resline'>das dÃ¼stere Chaos gemocht hast ( `Ã‚C`Ã¼h`Ta`Ã¢o`ÃŠs`Ãªm`~agie`0)</a>`n",true);
            output("<a href='newday.php?setspecialty=3$resline'>von den Reichen gestohlen und es dir selbst gegeben hast (`EDi`De`_be`(skÃ¼`_n`Ds`Ete`0)</a>`n",true);
            output("<a href='newday.php?setspecialty=5$resline'>oft mit dem Schwert gespielt hast (`AS`?c`*h`aw`+ertkÃ¼`an`*s`?t`Ae`0)</a>`n",true);


            addnav("Magie");            
            addnav("`ÂµD`Gu`Ãn`Ã“k`Ãšl`Ã‰e K`ÃšÃ¼`Ã“n`Ãs`Gt`Âµe`0","newday.php?setspecialty=1$resline");
            addnav("`5M`Oy`Js`%t`li`Ms`Rche `MK`lr`%Ã¤`Jf`Ot`5e`0","newday.php?setspecialty=2$resline");
            addnav("`Ã®F`Ã”e`ÃŸu`se`4r`om`Qa`qg`6i`^e`0","newday.php?setspecialty=4$resline");
            addnav("`Ã›N`ÃŽa`yt`Ã´u`Ãºr`Ã m`Ã´a`yg`ÃŽi`Ã›e`0","newday.php?setspecialty=6$resline");
            addnav("`1W`!a`9s`Ã¹s`Ã¬e`jr`Ã¬m`Ã¹a`9g`!i`1e`0","newday.php?setspecialty=7$resline");
            addnav("`IW`fi`|n`Nd`3m`Wa`=g`#i`Ã„e`0","newday.php?setspecialty=8$resline");
            addnav("`Ã‚C`Ã¼h`Ta`Ã¢o`ÃŠs`Ãªm`~agie`0","newday.php?setspecialty=9$resline");
            addnav("KÃ¼nste");
            addnav("`EDi`De`_be`(skÃ¼`_n`Ds`Ete`0","newday.php?setspecialty=3$resline");
            addnav("`AS`?c`*h`aw`+ertkÃ¼`an`*s`?t`Ae`0","newday.php?setspecialty=5$resline");
        }else{ 
            addnav("Weiter","newday.php?continue=1$resline");
                switch($_GET['setspecialty']){
                    case 1:
                        page_header("Dunkle KÃ¼nste");
                                                output("`c`n`n`n`n`b`ÂµD`Gu`Ãn`Ã“k`Ãšl`Ã‰e K`ÃšÃ¼`Ã“n`Ãs`Gt`Âµe `b`0`n`n`n`n");
                        output("`7Du erinnerst dich, dass du damit aufgewachsen bist, viele kleine Waldkreaturen zu tÃ¶ten, weil du davon Ã¼berzeugt warst, sie haben sich gegen dich verschworen. ");
                        output("Deine Eltern haben dir einen idiotischen Zweig gekauft, weil sie besorgt darÃ¼ber waren, dass du die Kreaturen des Waldes mit bloÃŸen HÃ¤nden tÃ¶ten musst. ");
                        output("Noch vor deinem Teenageralter hast du damit begonnen, finstere Rituale mit und an den Kreaturen durchzufÃ¼hren, wobei du am Ende oft tagelang im Wald verschwunden bist. ");
                        output("Niemand auÃŸer dir wusste damals wirklich, was die Ursache fÃ¼r die seltsamen GerÃ¤usche aus dem Wald war...`c");
                    break;
                    
                    case 2:
                        page_header("Mystische KrÃ¤fte");
                                                output("`c`n`n`n`n`b`5M`Oy`Js`%t`li`Ms`Rche `MK`lr`%Ã¤`Jf`Ot`5e `b`0`n`n`n`n");
                        output("`7Du hast schon als Kind gewusst, dass diese Welt mehr als das Physische bietet, woran du herumspielen konntest. ");
                        output("Du hast erkannt, dass du mit etwas Training deinen Geist selbst in eine Waffe verwandeln kannst. ");
                        output("Mit der Zeit hast du gelernt, die Gedanken kleiner Kreaturen zu kontrollieren und ihnen deinen Willen aufzuzwingen. ");
                        output("Du bist auch auf die mystische Kraft namens Mana gestossen, die du in die Form von Feuer, Wasser, Eis, Erde, Wind bringen und sogar als Waffe gegen deine Feinde einsetzen kannst.`c");
                    break;
                    
                    case 3:
                        page_header("DiebeskÃ¼nste");
                                                output("`c`n`n`n`n`b`EDi`De`_be`(skÃ¼`_n`Ds`Ete `b`0`n`n`n`n");
                        output("`7Du hast schon sehr frÃ¼h bemerkt, dass ein gewÃ¶hnlicher Rempler im GedrÃ¤nge dir das Gold eines vom GlÃ¼ck bevorzugteren Menschen einbringen kann. ");
                        output("AuÃŸerdem hast du entdeckt, dass der RÃ¼cken deiner Feinde anfÃ¤lliger gegen kleine Klingen ist, als deren Vorderseite gegen mÃ¤chtige Waffen.`c");
                    break;
                    
                    case 4:
                        page_header("Feuermagie");
                                                output("`c`n`n`n`n`b`Ã®F`Ã”e`ÃŸu`se`4r`om`Qa`qg`6i`^e `b`0`n`n`n`n");
                        output("`7FrÃ¼h hast du gemerkt, dass das Feuer dein Lieblingselement ist.`c");
                    break;
                    
                    case 5:
                        page_header("SchwertkÃ¼nste");
                                                output("`c`n`n`n`n`b`AS`?c`*h`aw`+ertkÃ¼`an`*s`?t`Ae`b`0`n`n`n`n");
                        output("`7FrÃ¼h hast du gemerkt , dass du sehr gut mit dem Schwert umgehen kannst.`c");
                    break;
                    
                    case 6:
                        page_header("Naturmagie");
                                                output("`c`n`n`n`n`b`Ã›N`ÃŽa`yt`Ã´u`Ãºr`Ã m`Ã´a`yg`ÃŽi`Ã›e `b`0`n`n`n`n");
                        output("`7FrÃ¼h hast du gemerkt , dass du der Natur sehr verbunden bist.`c");
                    break;
                    
                    case 7:
                        page_header("Wassermagie");
                                                output("`c`n`n`n`n`b`1W`!a`9s`Ã¹s`Ã¬e`jr`Ã¬m`Ã¹a`9g`!i`1e `b`0`n`n`n`n");
                        output("`7FrÃ¼h hast du gemerkt , dass du dem Element des Wassers sehr verbunden bist.`c");
                    break;
                    
                    case 8:
                        page_header("Windmagie");
                                                output("`c`n`n`n`n`b`IW`fi`|n`Nd`3m`Wa`=g`#i`Ã„e`b`0`n`n`n`n");
                        output("`7FrÃ¼h hast du gemerkt , dass du dem Element des Windes sehr verbunden bist.");
                    break;
                    
                    case 9:
                        page_header("Chaosmagie");
                                                output("`c`n`n`n`n`b`Ã‚C`Ã¼h`Ta`Ã¢o`ÃŠs`Ãªm`~agie`b`0`n`n`n`n");
                        output("`7FrÃ¼h hast du gemerkt , dass deine negative Energie groÃŸen Schaden anrichten kann.`c");
                    break;
                }
            $session['user']['specialty']=$_GET['setspecialty'];
            }
        }
//Anfang Klasse auswÃ¤hlen
else if((int)$session['user']['rp_char'] == 0){{
    page_header("WÃ¤hle eine Klasse");
    if ($_GET['setadmin']!=""){
        $char_class = (int)($_GET['setadmin']);
        /*if($char_class == 1){
            $session['user']['rp_char'] = 2;
            
            $sql_lehrer = "SELECT acctid FROM accounts WHERE superuser=2 LIMIT 1";
            $result_lehrer = db_query($sql_lehrer) or die(db_error(LINK));
            while ($row_lehrer = db_fetch_assoc($result_lehrer)) {
                systemmail($row_lehrer['acctid'],"`@Neue RP-Bewerbung",$session['user']['name']." `^hat sich als RP-SchÃ¼ler beworben.");
            }
            
            $session['user']['rpschueler'] = 1;
            
            $sql_in_school = "INSERT INTO `rpbewerb` (id, name) VALUES ('".$session['user']['acctid']."','".$session['user']['name']."')";
            db_query($sql_in_school) or die (db_error(LINK));            
            
        }else{
            $session['user']['rp_char'] = $char_class;
        }*/
        
        $session['user']['rp_char'] = $char_class;
        
        switch($_GET['setadmin']){
            case "1":
                output("`i`b`c#4a4a4aR#676767P#858585-#949494C#a2a2a2h#b1b1b1a#c0c0c0r`0`0`i`b`c`n");
                //output("Diese Chars haben keinen Wald und sind ausschlieslich am RPG interessiert.`n`n`4Der Antrag auf zum RP-Char ernannt zu werden, wurde der RP-Schule geschickt. Bitte hab ein wenig Geduld bis man sich bei dir meldet und weiter entschieden wird.`0");
                output("Diese Chars haben keinen Wald und sind ausschlieslich am RPG interessiert.`0");
            break;
            
            case "2":
                output("`i`b`c#c0c0c0M#b1b1b1i#a2a2a2x#949494-#949494C#7b7b7bh#626262a#4a4a4ar`0`0`i`b`c`n");
                output("Diese Chars kÃ¶nnen in den Wald kÃ¤mpfen und sind auch am RPG interessiert, doch haben diese gewisse EinschrÃ¤nkungen.`0");
            break;
            
            case "3":
                output("`i`b`c#c0c0c0L#a2a2a2e#949494v#6f6f6fe#4a4a4al#4a4a4a-#6f6f6fC#949494h#949494a#b1b1b1r`0`i`b`c`n");
                output("Diese Chars Leveln ausschlieslich und sind Ã¼berhaupt nicht am RPG interessiert.");
            break;
        }        
    }else{
        output("Was ist deine Klasse?`n`n");
        //output("<a href='newday.php?setadmin=1$resline'>#4a4a4aR#676767P#858585-#949494C#a2a2a2h#b1b1b1a#c0c0c0r`0 `7sind ausschlieslich am RPG interessiert und haben keinen Wald. `b`4Achtung! Dies ist lediglich eine Bewerbung fÃ¼r diese Char-Klasse, bei der Wahl dieser wird eine Anfrage an die RP-Schule gestellt. Der Char wird bis zu einer Entscheidung vorerst unter Mix-Char gefÃ¼hrt!`0`b</a>`n`n",true);
        output("<a href='newday.php?setadmin=1$resline'>#4a4a4aR#676767P#858585-#949494C#a2a2a2h#b1b1b1a#c0c0c0r`0 `7sind ausschlieslich am RPG interessiert und haben keinen Wald.`0</a>`n`n", true);
        output("<a href='newday.php?setadmin=2$resline'>#c0c0c0M#b1b1b1i#a2a2a2x#949494-#949494C#7b7b7bh#626262a#4a4a4ar`0 `7sind Accounts die Leveln und RPG'n kÃ¶nnen, aber dafÃ¼r eingeschrÃ¤nkt.</a>`n`n",true);
        output("<a href='newday.php?setadmin=3$resline'>#c0c0c0L#a2a2a2e#949494v#6f6f6fe#4a4a4al#4a4a4a-#6f6f6fC#949494h#949494a#b1b1b1r`0 `7sind Accounts die nur Leveln und kein RPG machen.</a>`n`n",true);

        addnav("WÃ¤hle deine Klasse");
        addnav("RP-Char","newday.php?setadmin=1$resline");
        addnav("Mix-Char","newday.php?setadmin=2$resline");
        addnav("KÃ¤mpfer-Char","newday.php?setadmin=3$resline");
        
        addnav("","newday.php?setadmin=1$resline");
        addnav("","newday.php?setadmin=2$resline");
        addnav("","newday.php?setadmin=3$resline");
    }
}

    if($session['user']['rp_char'] > 0){
        addnav("Weiter","newday.php?continue=1$resline");
    }

/* Gesinnung by Horus */


}else if ((int)$session['user']['gesinnung']==0){
if ($HTTP_GET_VARS['setgesinnung']===NULL){

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

output("Zwischen Gut und BÃ¶se herrscht schon seit langer Zeit Krieg. Aber welcher Gesinnung gehÃ¶rst `$ du`0 an?`n`n");
output("`7Ein kleiner Hinweis noch am Rande: Gesinnungen sind gÃ¤nzlich Freiwillig und kÃ¶nnen genutzt werden. Man kann die Gesinnung spÃ¤ter
nach belieben in der Bio anpassen. Wer keine Gesinnung angeben mÃ¶chte, oder eine eigene Interpretation hÃ¤tte, kann auch die Option `iKeine Angabe`i
auswÃ¤hlen. Falls jemand ne Idee braucht, was einzelne Gesinnungen bedeuten kÃ¶nnen, findet man nachfolgend ein paar Beispiele.`0`n`n");

output("<a href='newday.php?setgesinnung=1$resline'><b>Du bist Rechschaffen Gut</b></a>
<br><br>ErklÃ¤rung: <i>Paladine sind immer rechtschaffen gute Chars. Sie sind die Ritter des Rechts, die VorkÃ¤mpfer fÃ¼r das Gute und Reine. 
Die Gesetze und die Gedanken hinter den Gesetzen sind dabei ihre Richtschnur, um das BÃ¶se in jedweder Form zu bekÃ¤mpfen. Ein Rechtschaffen 
guter Char hilft alten Damen Ã¼ber die Strasse, versorgt Bettler, lÃ¤sst Verwundete nie zurÃ¼ck und ist, ganz allgemein gesprochen, der strahlende 
Held seiner gesamten Nachbarschaft. Ein Polizist ist ein gutes Beispiel fÃ¼r einen rechtschaffen guten Char.</i><br><br>
`n",true);

output("<a href='newday.php?setgesinnung=2$resline'><b>Du bist Neutral Gut</b></a>
<br><br>ErklÃ¤rung: <i>Neutral gute Chars sind bemÃ¼ht, das Beste fÃ¼r alle zu erreichen. Die Gesetze eines Landes kÃ¼mmern sie dabei weniger, 
denn sie kÃ¶nnen das Leid nicht mit ansehen. Er oder sie wÃ¼rde nicht gegen das Gesetz verstoÃŸen, wenn es nicht sein muss, aber wenn die Gesetze 
das Leid begrÃ¼nden - beispielsweise durch ein ungerechtes Kastensystem - dann kÃ¼mmert diesen Char nicht, ob er mit seiner Hilfe dagegen handelt. 
Mutter Theresa ist ein gutes Beispiel fÃ¼r einen neutral guten Char. </i><br><br>
`n",true);

output("<a href='newday.php?setgesinnung=3$resline'><b>Du bist Chaotisch Gut</b></a>
<br><br>ErklÃ¤rung: <i>Chaotisch gute Chars sind darauf aus, die Situation fÃ¼r alle zu verbessern. Dabei sehen sie die Gesetze oftmals als etwas 
an, das dem entgegen steht. Robin Hood ist ein gutes Beispiel fÃ¼r einen Chaotisch guten Char. FÃ¼r sich selbst will ein chaotisch guter Char 
nichts.</i><br><br>
`n",true);

output("<a href='newday.php?setgesinnung=4$resline'><b>Du bist Rechtschaffen Neutral</b></a>
<br><br>ErklÃ¤rung: <i>Rechtschaffen neutrale Chars halten sich an die Gesetze, und alles andere kÃ¼mmert sie kaum, sei es, weil es sie schlicht 
nicht interessiert oder weil sie der restliche Welt so weit entrÃ¼ckt sind, daÃŸ sie nichts davon wahrnehmen. Neutrale Chars haben auch die 
MÃ¶glichkeit, einmal altruistisch und freigiebig, und dann wieder egoistisch und selbstbezogen zu handeln - solange sich ihr Vorgehen dabei die 
Waage hÃ¤lt. Ein Anwalt ist ein gutes Beispiel fÃ¼r einen rechtschaffen neutralen Char.
</i><br><br>
`n",true);


output("<a href='newday.php?setgesinnung=5$resline'><b>Du bist (Absolut) Neutral</b></a>
<br><br>ErklÃ¤rung: <i>Wahrhaft neutrale Chars stehen zwischen allem, sowohl zwischen Gut und BÃ¶se wie auch zwischen Gesetz und Chaos. Sie mÃ¼ssen ein kompliziertes Gleichgewicht aller vier 
Faktoren halten, um wahrhaft neutral zu bleiben. Am einfachsten ginge das natÃ¼rlich, wenn man sich nie und nirgends einmischt - aber in der 
realen Welt ist dies natÃ¼rlich nicht mÃ¶glich. Ein gutes Beispiel fÃ¼r einen wahrhaft neutralen Char ist ein Einsiedler, der irgendwo ganz alleine lebt.</i><br><br>
`n",true);

output("<a href='newday.php?setgesinnung=6$resline'><b>Du bist Chaotisch Neutral</b></a>
<br><br>ErklÃ¤rung: <i>Chaotisch neutrale Chars versuchen, sich selbst und andere nicht zu kurz kommen zu lassen. Ob das gegen das Gesetz verstÃ¶sst ist ihnen dabei 
schnurz. Chaotisch neutrale Chars handeln natÃ¼rlich dennoch logisch und nachvollziehbar, und werden sich nicht ohne Grund irgendwo Ã„rger suchen. 
Oft sehen sich chaotisch neutrale Chars auch als FreischÃ¤rler und Rebellen fÃ¼r das Gute, doch ihre Ziele zeigen zumeist, das das mit dem Guten 
entweder nur halbherzig gemeint ist oder auch persÃ¶nliche Bereicherung zu den Zielen des Chars gehÃ¶rt. Ein SektenfÃ¼hrer, der das Geld seiner 
GlÃ¤ubigen nimmt und ihnen dafÃ¼r das Paradies verspricht, ist ein Beispiel fÃ¼r einen chaotisch neutralen Char.</i><br><br>
`n",true);

output("<a href='newday.php?setgesinnung=7$resline'><b>Du bist Rechtschaffen BÃ¶se</b></a>
<br><br>ErklÃ¤rung: <i>Rechtschaffen bÃ¶se Chars nutzen den Buchstaben des Gesetzes bis aufs letzte Jota aus, um sich den grÃ¶sstmÃ¶glichen Vorteil 
zu verschaffen. OberflÃ¤chlich betrachtet sind sie dabei EhrenmÃ¤nner mit weiÃŸer Weste. Ein Beispiel fÃ¼r einen rechtschaffen bÃ¶sen Char wÃ¤re ein 
Geldhai, der fÃ¼r Wucherzinsen Geld verleiht, oder ein Bordellbesitzer.</i><br><br>
`n",true);

output("<a href='newday.php?setgesinnung=8$resline'><b>Du bist Neutral BÃ¶se</b></a>
<br><br>ErklÃ¤rung: <i>Neutral bÃ¶se Chars wollen alles fÃ¼r sich. Sie sind skrupellos und gehen Ã¼ber Leichen, ohne mit der Wimper zu zucken. 
Dennoch ist es ihnen zumeist mÃ¶glich, mit anderen zu kooperieren, Kompromisse zu schliessen, um selbst mehr Macht zu bekommen. Ein MafiaboÃŸ ist 
ein gutes Beispiel fÃ¼r einen neutral bÃ¶sen Char.</i><br><br>
`n",true);

output("<a href='newday.php?setgesinnung=9$resline'><b>Du bist Chaotisch BÃ¶se</b></a>
<br><br>ErklÃ¤rung: <i>Chaotisch bÃ¶se Chars wollen alles fÃ¼r sich, und alles, was sie nicht haben kÃ¶nnen, zerstÃ¶ren. Sie enden zumeist im Wahnsinn. 
Da sie fÃ¼rchten, daÃŸ ihnen jedweder andere irgendetwas wegnehmen kÃ¶nnte oder sie sich mit ihm/ihr arrangieren mÃ¼ssten, arbeiten sie zumeist 
allein. Chaotisch BÃ¶se Chars kÃ¶nnte man mit BankrÃ¤ubern oder Erpressern vergleichen, die nicht einer kriminellen Organisation zugehÃ¶rig sind.</i><br><br>
`n",true);

output("<a href='newday.php?setgesinnung=10$resline'><b>Keine Angabe</b></a>
<br><br>ErklÃ¤rung: <i>Du mÃ¶chtest keine Aussage treffen, oder du keine der Angaben treffen fÃ¼r deinen Char zu.</i><br><br><br><br><br>
<font size=1pt>Beschreibungen gefunden auf: http://rollenspiel.doogle.de/cms/front_content.php?idart=804</font> 
`n",true);



addnav("Rechschaffen Gut","newday.php?setgesinnung=1$resline");
addnav("Neutral Gut","newday.php?setgesinnung=2$resline");
addnav("Chaotisch Gut","newday.php?setgesinnung=3$resline");
addnav("Rechtschaffen Neutral","newday.php?setgesinnung=4$resline");
addnav("(Absolut) Neutral","newday.php?setgesinnung=5$resline");
addnav("Chaotisch Neutral","newday.php?setgesinnung=6$resline");
addnav("Rechtschaffen BÃ¶se","newday.php?setgesinnung=7$resline");
addnav("Neutral BÃ¶se","newday.php?setgesinnung=8$resline");
addnav("Chaotisch BÃ¶se","newday.php?setgesinnung=9$resline");
addnav("Keine Angabe","newday.php?setgesinnung=10$resline");


}


else


{


addnav("Weiter","newday.php?continue=1$resline");
        switch($HTTP_GET_VARS['setgesinnung']){



case 1:

              page_header("Rechschaffen Gut");

output("`@Du hast dich fÃ¼r `bRechschaffen Gut`b gut entschieden.");

                


                break;

case 2:

              page_header("Neutral Gut");

output("`2Du hast dich fÃ¼r `bNeutral Gut`b entschieden.");
                


                break;

case 3:

              page_header("Chaotisch Gut");

output("`7Du hast dich fÃ¼r `bChaotisch Gut`b entschieden.");
                


                break;

case 4:

              page_header("Rechtschaffen Neutral");

output("`4Du hast dich fÃ¼r `bRechtschaffen Neutral`b entschieden.");
                


                break;

case 5:

              page_header("(Absolut)Neutral");

output("`$Du hast dich fÃ¼r `b(Absolut)Neutral`b entschieden.");
                


                break;

case 6:

              page_header("Chaotisch Neutral");

output("`2Du hast dich fÃ¼r `bChaotisch Neutral`b entschieden.");
                


                break;

case 7:

              page_header("Rechtschaffen BÃ¶se");

output("`7Du hast dich fÃ¼r `bRechtschaffen BÃ¶se`b entschieden.");
                


                break;

case 8:

              page_header("Neutral BÃ¶se");

output("`4Du hast dich fÃ¼r `bNeutral BÃ¶se`b entschieden.");
                


                break;

case 9:

              page_header("Chaotisch BÃ¶se");

output("`$Du hast dich fÃ¼r `bChaotisch BÃ¶se`b entschieden.");
                


                break;

case 10:

              page_header("Keine Angabe");

output("`$Du hast dich fÃ¼r `bKeine Angabe`b entschieden.");
                


                break;


}
        $session['user']['gesinnung']=$HTTP_GET_VARS['setgesinnung'];
    }



 

}else{
  if($session['user']['slainby']!=""){
        page_header("Du wurdest umgebracht!");
        output("`\$Im ".$session['user']['killedin']." hat dich `%".$session['user']['slainby']."`\$ getÃ¶tet und dein Gold genommen. Ausserdem hast du 5% deiner Erfahrungspunkte verloren. Meinst du nicht auch, es ist Zeit fÃ¼r Rache?");
        addnav("Weiter","newday.php?continue=1$resline");
      $session['user']['slainby']="";
    }else{
        page_header("Neuer Tagesabschnitt!");
        $interestrate = e_rand($mininterest*100,$maxinterest*100)/(float)100;

        if(getgametime() >= "00:00" AND getgametime() < "03:00"){
            output("`c<font size='+4'>`b`rEs ist Mitternacht!`0`b</font>`c",true);
            output("`n`c`9Die Glocken in Astaros lÃ¤uten den neuen Tag ein! Es ist Mitternacht, die Zeit der Geister und dunklen Rituale.`0`c");
        }else if(getgametime() >= "03:00" AND getgametime() < "06:00"){
            output("`c<font size='+4'>`b`rEs ist spÃ¤te Nacht!`0`b</font>`c",true);
            output("`n`c`9Es ist drei Uhr in der FrÃ¼he, die letzten SchenkengÃ¤nger machen sich auf den Heimweg.`0`c");
        }else if(getgametime() >= "06:00" AND getgametime() < "09:00"){
            output("`c<font size='+4'>`b`rEs ist Morgengrauen!`0`b</font>`c",true);
            output("`n`c`9Es ist noch sehr frÃ¼h am Morgen, langsam verdrÃ¤ngen die ersten Sonnenstrahlen die finstere Nacht und die ersten Wesen verlassen ihre HÃ¤user.`0`c");
        }else if(getgametime() >= "09:00" AND getgametime() < "12:00"){
            output("`c<font size='+4'>`b`rEs ist Vormittag!`0`b</font>`c",true);
            output("`n`c`9Es herrscht bereits geschÃ¤ftiges Treiben auf den StraÃŸen von Eassos und auf den Stadt- und MarktplÃ¤tzen bieten die HÃ¤ndler ihre Waren feil.`0`c");
        }else if(getgametime() >= "12:00" AND getgametime() < "15:00"){
            output("`c<font size='+4'>`b`rEs ist Mittagsstunde!`0`b</font>`c",true);
            output("`n`c`9Die Sonne steht am hÃ¶chsten Punkt und erhellt das Land Eassos. Vielleicht wÃ¤re es an der Zeit das Mittagsmahl einzunehmen?`0`c");
        }else if(getgametime() >= "15:00" AND getgametime() < "18:00"){
            output("`c<font size='+4'>`b`rEs ist Nachmittag!`0`b</font>`c",true);
            output("`n`c`9Langsam geht es auf den Abend zu, doch es ist noch nicht zu spÃ¤t um einen Spaziergang zu machen oder wichtige GeschÃ¤fte zu erledigen.`0`c");
        }else if(getgametime() >= "18:00" AND getgametime() < "21:00"){
            output("`c<font size='+4'>`b`rEs ist frÃ¼her Abend!`0`b</font>`c",true);
            output("`n`c`9Ganz langsam neigt sich die Sonne dem Horizont entgegen, vielleicht der perfekte Zeitpunkt, um noch schnell etwas zu erledigen oder sich auf den Heimweg zu machen.`0`c");
        }else if(getgametime() >= "21:00" AND getgametime() < "24:00"){
            output("`c<font size='+4'>`b`rEs ist Abend!`0`b</font>`c",true);
            output("`n`c`9Die Nacht ist Ã¼ber Eassos hereingebrochen, langsam leeren sich die StraÃŸen und die HÃ¤user und Schenken fÃ¼llen sich. Sei auf der Hut! Die Dunkelheit Ã¶ffnet nicht nur den schÃ¶nen Dingen ihre Pforten, auch die dunklen Kreaturen erwachen.`0`c");
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
                output("`1Da du ".$session['user']['tier_kuh']." KÃ¼he besitzt bekommst du `^".($session['user']['tier_kuh']*200)." Gold`1.`n");
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
                output("`1Da du ".$session['user']['tier_huhn']." HÃ¼hner besitzt bekommst du `^".($session['user']['tier_schwein']*50)." Gold`1.`n");
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
            output("`2Zinsen fÃ¼r Schulden: `^".-(int)($session['user']['goldinbank']*($interestrate-1))."`9 Gold.`n");
        }else if($session[user][goldinbank]<0 && abs($session[user][goldinbank])>=(int)getsetting("maxinbank",10000)){
            output("`4Die Bank erlÃ¤sst dir deine Zinsen, da du schon hoch genug verschuldet bist.`n");
            $interestrate=1;
        }else if($session[user][goldinbank]>=0 && $session[user][goldinbank]>=(int)getsetting("maxinbank",10000) && $session['user']['turns']<=getsetting("fightsforinterest",4)){
            $interestrate=1;
            output("`4Die Bank kann dir heute keinen Zinsen zahlen. Sie wÃ¼rde frÃ¼her oder spÃ¤ter an dir pleite gehen.`n");
        }else if($session[user][goldinbank]>=0 && $session[user][goldinbank]<(int)getsetting("maxinbank",10000) && $session['user']['turns']<=getsetting("fightsforinterest",4)){
            output("`2Heutiger Zinssatz: `^".(($interestrate-1)*100)."% `n");
            output("`2Durch Zinsen verdientes Gold: `^".(int)($session['user']['goldinbank']*($interestrate-1))."`n");
        }else{
            $interestrate=1;
            output("`2Dein heutiger Zinssatz betrÃ¤gt `^0% (Die Bank gibt nur den Leuten Zinsen, die dafÃ¼r arbeiten)`n");
        }

        output("`2Deine Gesundheit wurde wiederhergestellt auf `^".$session['user']['maxhitpoints']."`n");
        $skills = array(
                                1=>'Dunkle KÃ¼nste',
                                   'Mystische KrÃ¤fte',
                                   'DiebeskÃ¼nste',
                                   'Feuermagie',
                                   'SchwertkÃ¼nste',
                                   'Naturmagie',
                                   'Wassermagie',
                                   'Windmagie',
                                   'Chaosmagie'
                          );

        $sb = getsetting("specialtybonus",1);
        output("`n`1FÃ¼r dein Spezialgebiet `7".$skills[$session['user']['specialty']]."`1, erhÃ¤ltst du zusÃ¤tzlich $sb Anwendung(en) in `7".$skills[$session['user']['specialty']]."`1 fÃ¼r heute.`n");
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
                    $session['bufflist']['schwanger'] = array("name"=>"`&Schwangerschaft","rounds"=>1000000,"wearoff"=>"Irgendwas stimmt nicht mehr.","defmod"=>0,"roundmsg"=>"`9Du versucht deinen Bauch zu schÃ¼tzen und nimmst so jeden anderen Treffer in kauf.","activate"=>"offense");
                    output("`1Noch `7".$session['user']['smonat']." `1Tage`n`n");
                }else{
                    $sql_partner = "SELECT name FROM accounts WHERE acctid=".$session['user']['serzeug'];
                    $result_partner = db_query($sql_partner);
                    $row_partner = db_fetch_assoc($result_partner);
                
                    if($session['user']['sstatus'] == 2){
                        $session['user']['sstatus'] = 0;
                        $geschlechta = e_rand()%2;
                        $geschlechtb = e_rand()%2;
                        
                        output("`!`b`iDu bist bist heute Mutter geworden... Es sind Zwillinge! Vergiss nicht die neuen ErdenbÃ¼rger in der Kappelle zu taufen, sonst wird niemals jemand wissen das es ihn gibt und das wÃ¤re doch traurig!`n`n`b`i");

                        if($geschlechta == $geschlechtb && $geschlechtb == 1){
                            $t = "`7Es sind zwei MÃ¤dchen!`n";
                        }else if($geschlechta == $geschlechtb && $geschlechtb == 0){
                            $t = "`7Es sind zwei Jungs!`n";
                        }else{
                            $t = "`7Es ist ein MÃ¤dchen und ein Junge!`n";
                        }
                        
                        output($t);

                        systemmail($session['user']['serzeug'],"`%Du bist Vater!`0","`&Du bist seit heute Vater. ".$session['user']['name']." `&hat heute ein zwei wunderschÃ¶ne Babies zur Welt gebracht, vergesst nicht sie in der Kapelle zu taufen. ".$t);
                        systemmail($session['user']['acctid'],"`%Du bist Mutter!`0","`&Du hast heute zwei wunderschÃ¶ne Babies zur Welt gebracht, vergesst nicht sie in der Kapelle zu taufen. ".$t);
                        
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
                        output("`!`b`iDu bist bist heute Mutter geworden... Vergiss nicht den neuen ErdenbÃ¼rger in der Kappelle zu taufen, sonst wird niemals jemand wissen, das es ihn gibt und das wÃ¤re doch traurig!`n`n`i`b");

                        if($geschlecht == 1){
                            $t = "`7Es ist ein MÃ¤dchen!";
                        }else{
                            $t = "`7Es ist ein Junge!";
                        }
                        
                        output($t);

                        systemmail($session['user']['serzeug'],"`%Du bist Vater!`0","`&Du bist seit heute Vater. ".$session['user']['name']." `& hat heute ein wunderschÃ¶nes Baby zur Welt gebracht, vergesst nicht es in der Kapelle zu taufen. ".$t);
                        systemmail($session['user']['acctid'],"`%Du bist Mutter!`0","`&Du hast heute ein wunderschÃ¶nes Baby zur Welt gebracht, vergesst nicht es in der Kapelle zu taufen. ".$t);
                        
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
        
        if ($dkff>0) output("`n`2Du erhÃ¶hst deine WaldkÃ¤mpfe um `^$dkff`2 durch verteilte Drachenpunkte!"); 
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
                output("bekommst du zusÃ¤tzlich ");
            }else{
                output("verlierst du ");
            }
            output(abs($spirits)." Runde/n`2 fÃ¼r heute.`n");
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
         *    Wird nun Ã¼ber die c_special_val.php Funktionen geÃ¤ndert.
         *  set_special_val in Kombination mit einem Ã„nerungsarray, das alle Elemente auf 0 zurÃ¼cksetzt.
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
          output("`&Wegen deines schrecklichen Katers wird dir 1 Runde fÃ¼r heute abgezogen.");
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
    
    // Adventspecial fÃ¼r MerydiÃ¢, der Anfang ist in der setnewday.php, eine Anleitung findet ihr unter www.merydia.de, www.anpera.net oder bei http://www.dai-clan.de/SiliForum/wbb2/

    // Copyright by Leen/Cassandra (cassandra@leensworld.de)
    // SQL: ALTER TABLE `accounts` ADD `specialperday` INT( 11 ) NOT NULL ; <- auch nutzbar fÃ¼r andere Specials die an bestimmten REAL-Tagen stattfinden und man es nicht jeden Tag nutzen darf
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
                            output('`c`@Du Ã¶ffnest den Beutel und findest `^4000 `@Erfahrungspunkte und WaldkÃ¤mpfe.`n`bFrohe Weihnachten wÃ¼nscht das Team von Eassos.`c`b`n`n');
                            break;
                            }
                        case 2:
                        $gesamtgold = ($session['user']['gold'])+($session['user']['goldinbank']);
                        if ($gesamtgold < 50000)
                            {
                            $session['user']['gold'] += 40000;
                            $turnsperday += 30;
                            output('`c`@Du Ã¶ffnest den Beutel und findest `^40000 `@GoldstÃ¼cke und WaldkÃ¤mpfe.`n`bFrohe Weihnachten wÃ¼nscht das Team von Eassos.`c`b`n`n');
                            break;
                            }
                        case 3:
                        if ($session['user']['gems'] < 100)
                            {
                            $session['user']['gems'] += 15;
                            $turnsperday += 30;
                            output('`c`@Du Ã¶ffnest den Beutel und findest `^15 `@Edelsteine und WaldkÃ¤mpfe.`n`bFrohe Weihnachten wÃ¼nscht das Team von Eassos.`c`b`n`n');
                            break;
                            }
                        case 4:
                        $session['user']['defence'] += 3;
                        $session['user']['attack'] += 3;
                        $turnsperday += 30;
                        output('`c`@Du Ã¶ffnest den Beutel und findest je `^3 `@Angriffs- und Verteidigungspunkte, sowie WaldkÃ¤mpfe.`n`bFrohe Weihnachten wÃ¼nscht das Team von Eassos.`c`b`n`n');
                        break;
                        case 5:
                        $session['user']['deathpower'] += 200;
                        $turnsperday += 30;
                        output('`c`@Du Ã¶ffnest den Beutel und findest `^200 `@Gefallen und WaldkÃ¤mpfe.`n`bFrohe Weihnachten wÃ¼nscht das Team von Eassos.`c`b`n`n');
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
                            output('`c`@Du Ã¶ffnest den Beutel und findest `^500 `@Erfahrungspunkte und WaldkÃ¤mpfe.`c`n`n');
                            break;
                            }
                        case 2:
                        $gesamtgold = ($session['user']['gold'])+($session['user']['goldinbank']);
                        if ($gesamtgold < 50000)
                            {
                            $session['user']['gold'] += 5000;
                            $turnsperday += 5;
                            output('`c`@Du Ã¶ffnest den Beutel und findest `^5000 `@GoldstÃ¼cke und WaldkÃ¤mpfe.`c`n`n');
                            break;
                            }
                        case 3:
                        if ($session['user']['gems'] < 100)
                            {
                            $session['user']['gems'] += 5;
                            $turnsperday += 5;
                            output('`c`@Du Ã¶ffnest den Beutel und findest `^5 `@Edelsteine und WaldkÃ¤mpfe.`c`n`n');
                            break;
                            }
                        case 4:
                        $session['user']['defence'] += 1;
                        $session['user']['attack'] += 1;
                        $turnsperday += 5;
                        output('`c`@Du Ã¶ffnest den Beutel und findest je `^1 `@Angriffs- und Verteidigungspunkt, sowie WaldkÃ¤mpfe.`c`n`n');
                        break;
                        case 5:
                        $session['user']['deathpower'] += 50;
                        $turnsperday += 5;
                        output('`c`@Du Ã¶ffnest den Beutel und findest `^50 `@Gefallen und WaldkÃ¤mpfe.`c`n`n');
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

    output("`nDer Schmerz in deinen wetterfÃ¼hligen Knochen sagt dir das heutige Wetter: `6".$settings['weather']."`@.`n");
    if ($_GET['resurrection']==""){
        if ($session['user']['specialty']==1 && $settings['weather']=="Regnerisch"){
            output("`^`nDer Regen schlÃ¤gt dir aufs GemÃ¼t, aber erweitert deine Dunklen KÃ¼nste. Du bekommst eine zusÃ¤tzliche Anwendung.`n");
            $session[user][darkartuses]++;
            }    
        if ($session['user']['specialty']==2 and $settings['weather']=="Gewittersturm"){
            output("`^`nDie Blitze fÃ¶rdern deine Mystischen KrÃ¤fte. Du bekommst eine zusÃ¤tzliche Anwendung.`n");
            $session[user][magicuses]++;
            }    
        if ($session['user']['specialty']==3 and $settings['weather']=="Neblig"){
            output("`^`nDer Nebel bietet Dieben einen zusÃ¤tzlichen Vorteil. Du bekommst eine zusÃ¤tzliche Anwendung.`n");
            $session[user][thieveryuses]++;
            }        
    }
    
    //End global newdaysemaphore code and weather mod.

    
        if ($session['user']['hashorse']){
            output(str_replace("{weapon}",$session['user']['weapon'],"`n`&{$playermount['newday']}`n`0"));
            if ($playermount['mountforestfights']>0){
                output("`n`&Weil du ein(e/n) {$playermount['mountname']} `&besitzt, bekommst du `^".((int)$playermount['mountforestfights'])."`& Runden zusÃ¤tzlich.`n`0");
                $session['user']['turns']+=(int)$playermount['mountforestfights'];
            }
        }else{
            output("`n`&Du schnallst dein(e/n) `%".$session['user']['weapon']."`& auf den RÃ¼cken und ziehst los ins Abenteuer.`0");
        }
        if ($session['user']['race']==3) {
            $session['user']['turns']++;
            output("`n`&Weil du ein Mensch bist, bekommst du `^1`& Waldkampf zusÃ¤tzlich!`n`0");
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
case '`#KÃ¶nigs Stein':
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
case '`#Stein der KÃ¶nigin':
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
output("`n`n`\$Weil du den {$row['name']} `\$ besitzt , wirst du stÃ¤rker!`n");
$session['bufflist']['stone'] = unserialize($row['buff']);
break;
case '`%Baldurs Stein':
output("`n`n`\$Weil du den {$row['name']} `\$ besitzt , bekommst du 2 WaldkÃ¤mpfe!`n");
$session['user']['turns']+=2;
break;
case '`&Stein der Reinheit':
output("`n`n`\$Weil du den {$row['name']} `\$ besitzt , bekommst du 2 WaldkÃ¤mpfe!`n");
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
            output("`@Du bekommst eine Extrarunde fÃ¼r die Punkte auf `^{$val['bought']}`@.");
            $session['user']['turns']++;
            if ($val['left']>1){
                output(" Du hast `^".($val['left']-1)."`@ Tage von diesem Kauf Ã¼brig.`n");
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
                output("`n`@Golinda wird dich nicht lÃ¤nger behandeln.");
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
            output("`n`8Da du aufgrund deiner Ehrenlosigkeit hÃ¤ufig Steine in den Weg gelegt bekommst, kannst du heute 1 Runden weniger kÃ¤mpfen. AuÃŸerdem sind deine Feinde vor dir gewarnt.`nDu solltest dringend etwas fÃ¼r deine Ehre tun!");
            $session['user']['turns']--;
            $session['user']['playerfights']--;
        }else if ($session['user']['reputation']<=-30){
            output("`n`8Deine Ehrenlosigkeit hat sich herumgesprochen! Deine Feinde sind vor dir gewarnt, weshalb dir heute 1 Spielerkampf weniger gelingen wird.`nDu solltest dringend etwas fÃ¼r deine Ehre tun!");
            $session['user']['playerfights']--;
        }else if ($session['user']['reputation']<-10){
            output("`n`8Da du aufgrund deiner Ehrenlosigkeit hÃ¤ufig Steine in den Weg gelegt bekommst, kannst du heute 1 Runde weniger kÃ¤mpfen.");
            $session['user']['turns']--;
        }else if ($session['user']['reputation']>=30){
            if ($session['user']['reputation']>50) $session['user']['reputation']=50;
            output("`n`9Da du aufgrund deiner groÃŸen Ehrenhaftigkeit das Volk auf deiner Seite hast, kannst du heute 1 Runde und 1 Spielerkampf mehr kÃ¤mpfen.");
            $session['user']['turns']++;
            $session['user']['playerfights']++;
        }else if ($session['user']['reputation']>10){
            output("`n`9Da du aufgrund deiner groÃŸen Ehrenhaftigkeit das Volk auf deiner Seite hast, kannst du heute 1 Runde mehr kÃ¤mpfen.");
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
        
        output("`n`n`9Du hast die Frist zum begleichen deiner Strafe nicht eingehalten und kommst fÃ¼r zwei Tage an den Pranger.");
        
        $sql = "UPDATE gericht SET frist=12 WHERE gid=".$row_pen['gid']."";
        $result = db_query($sql) or die(db_error(LINK));
        
        clearnav();
        addnav("weiter","pranger.php");
    }
}

/*
//Pranger & GefÃ¤ngniss
if ($session['user']['prison'] == 1){
    $session['user']['prisondays']--;
}

if ($session['user']['jailtime'] > 0){
    $session['user']['jailtime']--;
}*/




$session['user']['special_taken'] = 0;


page_footer();

?>

