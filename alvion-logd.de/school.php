
<?php////////////////////////////////////////// copyright: 26022006 by Syntheka   //// www.leensworld.de// mailto: syka@uni.de               /////////////////////////////////////////require_once "common.php";require_once "func/systemmail.php";//addcommentary();checkday();//$session[user][whereuser]=0;$op == '';page_header("Die Schule von Alvion");////ERSTE KLASSE////////////////////////if ($_GET[op] == "firstclass") {   output("`v`c`bDie erste Klasse`b`c`n");   if ($_GET[op1] == "exam"){   output("Wo befindest du dich momentan?`n`n");   output("<a href='school.php?op=firstclass&op1=pc'>Vor meinem PC</a>`n",true);   addnav("","school.php?op=firstclass&op1=pc");   output("<a href='school.php?op=firstclass&op1=school'>In der Schule von Alvion</a>`n",true);   addnav("","school.php?op=firstclass&op1=school");   output("<a href='school.php?op=firstclass&op1=dunno'>Keine Ahnung, ist mir egal</a>`n",true);   addnav("","school.php?op=firstclass&op1=dunno");   addnav("1.Klasse");   addnav("Klasse wiederholen","school.php?op=firstclass");   }   elseif ($_GET[op1] == "pc"){          output("Der Prüfer schüttelt den Kopf, als er deine Antwort hört.`n`n");          output("Das war wohl nichts... Du wirst die Klasse wiederholen müssen");          addnav("1.Klasse");          addnav("Zurück zur Klasse","school.php?op=firstclass");   }   elseif ($_GET[op1] == "school"){          output("Der Prüfer nickt dir lächelnd zu, als er deine Antwort hört.`n`n");          output("Du hast die erste Klasse abgeschlossen und wirst versetzt.");          addnav("1.Klasse");          addnav("Versetzung","school.php");          $session[user][schoolprocess]++;   }   elseif ($_GET[op1] == "dunno"){          output("Der Prüfer wirft dir einen bösen Blick zu, als er deine freche Antwort hört.`n`n");          output("\"Wenn dir egal ist, was hier gelehrt wird, solltest du vielleicht eine andere Stadt aufsuchen\"");          addnav("1.Klasse");          addnav("Zurück zur Klasse","school.php?op=firstclass");   }   else {   output("`b`c`2A`ol`@vi`oo`2n`W, `gein Dorf welches zwischen Bäumen versteckt ist.`c`b`n");   output("`gEin ruhiges Dörfchen, zwischen Wäldern und Wasser.`n");   output("`gErschaffen von den Gründern, welche eine Lichtung entdeckten und so ihren Traum verwirklichten.");   output("`gJeder Tag der harten Arbeit hatte sich gelohnt, denn so entstand das Dorf `2A`ol`@vi`oo`2n.`n");   output("`gIn `2A`ol`@vi`oo`2n`g gibt es viele verschiedene Wesen, die sich ihrem Charakter verschrieben haben. ");   output("`gDiese Wesen leben und interagieren miteinander, sie können heiraten oder gegeneinander kämpfen. ");   output("`gDas Leben ist spannend, und der Tod gehört dazu.");   addnav("1.Klasse");   addnav("Prüfen lassen","school.php?op=firstclass&op1=exam");   addnav("Schule schwänzen");   addnav("LogOut","login.php?op=logout",true);   }}////ZWEITE KLASSE/////////////////////////elseif ($_GET[op] == "secondclass") {   output("`v`c`bDie zweite Klasse`b`c`n");   if ($_GET[op1] == "exam"){   output("Welche Sprache ist in Alvion erlaubt?`n`n");   output("<a href='school.php?op=secondclass&op1=chatstars'>Chatsprache und Sternchensprache</a>`n",true);   addnav("","school.php?op=secondclass&op1=chatstars");   output("<a href='school.php?op=secondclass&op1=english'>Englisch oder andere Sprachen</a>`n",true);   addnav("","school.php?op=secondclass&op1=english");   output("<a href='school.php?op=secondclass&op1=german'>Hochdeutsch</a>`n",true);   addnav("","school.php?op=secondclass&op1=german");   output("<a href='school.php?op=secondclass&op1=slang'>Slang</a>`n",true);   addnav("","school.php?op=secondclass&op1=slang");   addnav("2.Klasse");   addnav("Klasse wiederholen","school.php?op=secondclass");   }   elseif ($_GET[op1] == "chatstars"){          output("Der Prüfer schüttelt den Kopf, als er deine Antwort hört.`n`n");          output("Das war wohl nichts... Du wirst die Klasse wiederholen müssen");          addnav("2.Klasse");          addnav("Zurück zur Klasse","school.php?op=secondclass");   }   elseif ($_GET[op1] == "english"){          output("Der Prüfer schüttelt den Kopf, als er deine Antwort hört.`n`n");          output("Das war wohl nichts... Du wirst die Klasse wiederholen müssen");          addnav("2.Klasse");          addnav("Zurück zur Klasse","school.php?op=secondclass");   }   elseif ($_GET[op1] == "german"){          output("Der Prüfer nickt dir lächelnd zu, als er deine Antwort hört.`n`n");          output("Du hast die zweite Klasse abgeschlossen und wirst versetzt.");          addnav("2.Klasse");          addnav("Versetzung","school.php");          $session[user][schoolprocess]++;   }   elseif ($_GET[op1] == "slang"){          output("Der Prüfer schüttelt den Kopf, als er deine Antwort hört.`n`n");          output("Das war wohl nichts... Du wirst die Klasse wiederholen müssen");          addnav("2.Klasse");          addnav("Zurück zur Klasse","school.php?op=secondclass");   }   else {        output("Alvion befindet sich in einer Epoche, die dem irdischen Mittelalter ähnelt.`n");        output("Das bedeutet natürlich, dass in Alvion nur eine Sprache gesprochen und verstanden wird:`n");        output("`bHochdeutsch`b.`n");        output("Um dem Rollenspiel gerecht zu werden, muss jeder diese Sprache nutzen.`n");        output("Was bedeutet das genau?`n`n");        output("`b- Englisch und andere Sprachen sind völlig unbekannt und damit verboten`n");        output("- Chatsprache ist unverständlich und damit verboten`n");        output("- Sternchensprache ist unnötig wegen dem /me-Befehl`n");        output("- Slang ist unverständlich und verboten (z.B. cool, wow, geil .. )`n`n`b");        output("Beispiel:`n`n");        output("`^\"Hey Leute, das Spiel ist echt geil, das rockt total *lol*\"");        output("Dies wird von der hiesigen Bevölkerung nicht verstanden, richtig wäre:`n");        output("\"Ich grüße euch, diese Welt ist wirklich toll, da möchte man gar nicht mehr weggehen\"");        output("\"me lächelt oder lacht - Du lächelst, du lachst\"`n`n`&");        output("Solltet ihr doch einmal über technische Dinge reden wollen oder euch \"normal\" miteinander ");        output("unterhalten, so tut dies über das Botensystem (Bote), denn diese Nachrichten (Briefe) können ");        output("nur du und der Angeschriebene lesen.");        addnav("2.Klasse");        addnav("Prüfen lassen","school.php?op=secondclass&op1=exam");        addnav("Schule schwänzen");        addnav("LogOut","login.php?op=logout",true);   }}////DRITTE KLASSE/////////////////////////elseif ($_GET[op] == "thirdclass") {   output("`v`c`bDie dritte Klasse`b`c`n");   if ($_GET[op1] == "exam"){   output("Wie sollst du dich in Alvion verhalten?`n`n");   output("<a href='school.php?op=thirdclass&op1=helpful'>Höflich und zuvorkommend</a>`n",true);   addnav("","school.php?op=thirdclass&op1=helpful");   output("<a href='school.php?op=thirdclass&op1=dontcare'>Mir sind die Regeln egal</a>`n",true);   addnav("","school.php?op=thirdclass&op1=dontcare");   output("<a href='school.php?op=thirdclass&op1=asilike'>So, wie ich es für richtig halte</a>`n",true);   addnav("","school.php?op=thirdclass&op1=asilike");   output("<a href='school.php?op=thirdclass&op1=dunno'>Keine Ahnung</a>`n",true);   addnav("","school.php?op=thirdclass&op1=dunno");   addnav("3.Klasse");   addnav("Klasse wiederholen","school.php?op=thirdclass");   }   elseif ($_GET[op1] == "dontcare"){          output("Der Prüfer schüttelt wütend den Kopf, als er deine Antwort hört.`n`n");          output("Das war wohl nichts... Du wirst die Klasse wiederholen müssen");          addnav("3.Klasse");          addnav("Zurück zur Klasse","school.php?op=thirdclass");   }   elseif ($_GET[op1] == "asilike"){          output("Der Prüfer schüttelt wütend den Kopf, als er deine Antwort hört.`n`n");          output("Du musst die Klasse wiederholen");          addnav("3.Klasse");          addnav("Zurück zur Klasse","school.php?op=thirdclass");   }   elseif ($_GET[op1] == "helpful"){          output("Der Prüfer nickt dir lächelnd zu, als er deine Antwort hört.`n`n");          output("Du hast die dritte Klasse abgeschlossen und wirst versetzt.");          addnav("3.Klasse");          addnav("Versetzung","school.php");          $session[user][schoolprocess]++;   }   elseif ($_GET[op1] == "dunno"){          output("Der Prüfer schüttelt wütend den Kopf, als er deine Antwort hört.`n`n");          output("Das war wohl nichts... Du musst die Klasse wiederholen");          addnav("3.Klasse");          addnav("Zurück zur Klasse","school.php?op=thirdclass");   }   else {        output("`gSo, wie auf die Sprache in dieser Welt geachtet wird, so gilt das Gleiche auch für das Verhalten.`n");        output("`gDa du neu ins Dorf kommst, kennst du die Bewohner nicht. Daher wird es ");        output("`gnicht gern gesehen, wenn man in Alvion einfach geduzt wird (im schlimmsten Falle ");        output("`gkann dies zur Verwarnung führen ). Daher wird hier ein `4*Du*`0 ");        output("`gnicht toleriert, denn dies ist sehr unhöflich, und da die Höflichkeit an erster Stelle steht, bitten wir darum, dies zu beachten.`n`n");        output("`gDaher sollte man einen Post mit \"`^Ich grüße euch`g\" oder auch \"`^Seid gegrüßt`g\" anfangen.`n");        output("`gDie Bürger werden im Normalfall gesiezt, d.h. mit \"`9Sie`g\", \"`9Ihr`g\", \"`9Euch`g\" oder auch \"`9Euer`g\" und so weiter angesprochen.`n`n");        output("`gBeleidigungen sind nur so erlaubt, dass sie dem Mittelalter entsprechen, jedoch nicht übertrieben ");        output("`gformuliert sind. Genau so sollte die Reaktion auf solch eine Beleidigung dem typischen Ton von ");        output("Alvion entsprechen, selbst wenn es einen Kampf in der Arena zur Folge hat.");        addnav("3.Klasse");        addnav("Prüfen lassen","school.php?op=thirdclass&op1=exam");        addnav("Schule schwänzen");        addnav("LogOut","login.php?op=logout",true);   }}/// LETZTE KLASSE///////////////////////elseif ($_GET[op] == "lastclass") {   output("`v`c`bDie letzte Klasse`b`c`n");   ///EXAMEN ERSTE FRAGE//////////////   if ($_GET[op1] == "exam"){   output("Wo befindest du dich momentan?`n`n");   output("<a href='school.php?op=lastclass&op1=pc'>Vor meinem PC</a>`n",true);   addnav("","school.php?op=lastclass&op1=pc");   output("<a href='school.php?op=lastclass&op1=school'>In der Schule von Alvion</a>`n",true);   addnav("","school.php?op=lastclass&op1=school");   output("<a href='school.php?op=lastclass&op1=dunno'>Keine Ahnung, ist mir egal</a>`n",true);   addnav("","school.php?op=lastclass&op1=dunno");   addnav("4.Klasse");   addnav("Absichtlich durchfallen","school.php?op=lastclass");   }   elseif ($_GET[op1] == "pc"){          output("Der Prüfer schüttelt enttäuscht den Kopf, als er deine Antwort hört.`n`n");          output("Mit einem traurigen Blick zerreißt er deinen Prüfungsbogen und versetzt dich ");          output("zurück in die 1. Klasse");          $session[user][schoolprocess]=0;          addnav("Durchgefallen");          addnav("Zurück zur Schule","school.php");   }   elseif ($_GET[op1] == "school"){          ////EXAMEN ZWEITE FRAGE///////////////          if ($_GET[op2]==chatstars){               output("Der Prüfer schüttelt enttäuscht den Kopf, als er deine Antwort hört.`n`n");               output("Mit einem traurigen Blick zerreißt er deinen Prüfungsbogen und versetzt dich ");               output("zurück in die 1. Klasse");               $session[user][schoolprocess]=0;               addnav("Durchgefallen");               addnav("Zurück zur Schule","school.php");          }          elseif ($_GET[op2]==english){               output("Der Prüfer schüttelt enttäuscht den Kopf, als er deine Antwort hört.`n`n");               output("Mit einem traurigen Blick zerreißt er deinen Prüfungsbogen und versetzt dich ");               output("zurück in die 1. Klasse");               $session[user][schoolprocess]=0;               addnav("Durchgefallen");               addnav("Zurück zur Schule","school.php");          }          elseif ($_GET[op2]==german){                  /////EXAMEN DRITTE FRAGE//////////////                  if ($_GET[op3]=="helpful"){                                 /////EXAMEN VIERTE FRAGE//////////////                                 if ($_GET[op4]=="dontcare"){                                       output("Der Prüfer schüttelt wütend den Kopf, als er deine Antwort hört.`n`n");                                       output("Er zerreißt deinen Prüfungsbogen und versetzt dich ");                                       output("zurück in die 1. Klasse");                                       $session[user][schoolprocess]=0;                                       addnav("Durchgefallen");                                       addnav("Zurück zur Schule","school.php");                                 }                                 elseif ($_GET[op4]=="bib"){                                       output("Der Prüfer streckt dir gratulierend die Hand entgegen.`n`n");                                       output("\"Du hast alle Fragen richtig beantwortet!\"`n");                                       output("Willkommen in Alvion, ".$session[user][name]."`n`n");                                       output("Bevor du die Schule verlässt, macht dich der Prüfer noch darauf aufmerksam, ");                                       output("dass du dich bitte in den ");                                       output("<a href='http://alvionforum.de/wbb/' target='_blank'>`bSchrifthallen von Alvion`b </a>`n",true);                                       addnav("","http://alvionforum.de/wbb/");                                       output(" anmelden sollst, da dort viele ");                                       output("interessante und wichtige Diskussionen statt finden.`n`n");                                       $session[user][schoolprocess]++;                                       addnav("Hinaus auf den Marktplatz","village.php");                                       addnews("`\$".$session[user][name]." `&hat die Schule von Alvion erfolgreich abgeschlossen.");                                       systemmail($session['user']['acctid'],"Herzlich willkommen","Herzlich willkommen in Alvion ".$session['user']['name'].".`n`nWir freuen uns sehr dich hier in Alvion begrüßen zu dürfen. Lass dir ruhig Zeit dabei, dir alles ganz genau anzuschauen und andere Spieler kennen zu lernen. Sollte es Fragen oder Probleme geben, kannst du dich selbstverständlich an das Team wenden, oder du schickst einfach eine Hilfsanfrage. Nimm dir doch ruhig auch einen kurzen Augenblick Zeit, um ins Forum zu schauen. Neben Tipps und Hinweisen, die du dort findest, kannst du dort auch jederzeit Ideen und Anregungen einbringen, damit das Dorf noch schöner gestaltet werden kann. Ansonsten wünschen wir dir hier viel Spaß und hoffen, dass du dich hier wohl fühlst. Gruß Das Alvion-Team");                                 }                                 ////ENDE EXAMEN VIERTE FRAGE////////////////////                                 else {                                       output("Wo kannst du dich weiterhin über die Regeln in Alvion informieren?`n`n");                                       output("<a href='school.php?op=lastclass&op1=school&op2=german&op3=helpful&op4=bib'>In der Bibliothek</a>`n",true);                                       addnav("","school.php?op=lastclass&op1=school&op2=german&op3=helpful&op4=bib");                                       output("<a href='school.php?op=lastclass&op1=school&op2=german&op3=helpful&op4=dontcare'>Die Regeln interessieren mich nicht</a>`n",true);                                       addnav("","school.php?op=lastclass&op1=school&op2=german&op3=helpful&op4=dontcare");                                       addnav("4.Klasse");                                       addnav("Absichtlich durchfallen","school.php?op=lastclass");                                 }               }               elseif ($_GET[op3]=="dontcare"){                      output("Der Prüfer schüttelt wütend den Kopf, als er deine Antwort hört.`n`n");                      output("Er zerreißt deinen Prüfungsbogen und versetzt dich ");                      output("zurück in die 1. Klasse");                      $session[user][schoolprocess]=0;                      addnav("Durchgefallen");                      addnav("Zurück zur Schule","school.php");               }               elseif ($_GET[op3]=="asilike"){                      output("Der Prüfer schüttelt wütend den Kopf, als er deine Antwort hört.`n`n");                      output("Er zerreißt deinen Prüfungsbogen und versetzt dich ");                      output("zurück in die 1. Klasse");                      $session[user][schoolprocess]=0;                      addnav("Durchgefallen");                      addnav("Zurück zur Schule","school.php");               }               elseif ($_GET[op3]=="dunno"){                      output("Der Prüfer schüttelt enttäuscht den Kopf, als er deine Antwort hört.`n`n");                      output("Mit einem traurigen Blick zerreißt er deinen Prüfungsbogen und versetzt dich ");                      output("zurück in die 1. Klasse");                      $session[user][schoolprocess]=0;                      addnav("Durchgefallen");                      addnav("Zurück zur Schule","school.php");               }               //////////ENDE EXAMEN DRITTE FRAGE/////////////////////               else {                      output("Wie sollst du dich in Alvion verhalten?`n`n");                      output("<a href='school.php?op=lastclass&op1=school&op2=german&op3=helpful'>Höflich und zuvorkommend</a>`n",true);                      addnav("","school.php?op=lastclass&op1=school&op2=german&op3=helpful");                      output("<a href='school.php?op=lastclass&op1=school&op2=german&op3=dontcare'>Mir sind die Regeln egal</a>`n",true);                      addnav("","school.php?op=lastclass&op1=school&op2=german&op3=dontcare");                      output("<a href='school.php?op=lastclass&op1=school&op2=german&op3=asilike'>So, wie ich es für richtig halte</a>`n",true);                      addnav("","school.php?op=lastclass&op1=school&op2=german&op3=asilike");                      output("<a href='school.php?op=lastclass&op1=school&op2=german&op3=dunno'>Keine Ahnung</a>`n",true);                      addnav("","school.php?op=lastclass&op1=school&op2=german&op3=dunno");                      addnav("4.Klasse");                      addnav("Absichtlich durchfallen","school.php?op=lastclass");               }          }          elseif ($_GET[op2]==slang){               output("Der Prüfer schüttelt wütend den Kopf, als er deine Antwort hört.`n`n");               output("Er zerreißt deinen Prüfungsbogen und versetzt dich ");               output("zurück in die 1. Klasse");               $session[user][schoolprocess]=0;               addnav("Durchgefallen");               addnav("Zurück zur Schule","school.php");          }          /////////ENDE EXAMEN ZWEITE FRAGE/////////////////          else{              output("Welche Sprache ist in Alvion erlaubt?`n`n");              output("<a href='school.php?op=lastclass&op1=school&op2=chatstars'>Chatsprache und Sternchensprache</a>`n",true);              addnav("","school.php?op=lastclass&op1=school&op2=chatstars");              output("<a href='school.php?op=lastclass&op1=school&op2=english'>Englisch oder andere Sprachen</a>`n",true);              addnav("","school.php?op=lastclass&op1=school&op2=english");              output("<a href='school.php?op=lastclass&op1=school&op2=german'>Hochdeutsch</a>`n",true);              addnav("","school.php?op=lastclass&op1=school&op2=german");              output("<a href='school.php?op=lastclass&op1=school&op2=slang'>Slang</a>`n",true);              addnav("","school.php?op=lastclass&op1=school&op2=slang");              addnav("4.Klasse");              addnav("Absichtlich durchfallen","school.php?op=lastclass");          }   }   elseif ($_GET[op1] == "dunno"){          output("Der Prüfer schüttelt wütend den Kopf, als er deine Antwort hört.`n`n");          output("Er zerreißt deinen Prüfungsbogen und versetzt dich ");          output("zurück in die 1. Klasse");          $session[user][schoolprocess]=0;          addnav("Durchgefallen");          addnav("Zurück zur Schule","school.php");   }   ////ENDE EXAMEN ERSTE FRAGE////////////   else{        output("Wie du in den vorigen Klassen gelernt hast, ist Alvion ein Ort mit vielen ");        output("Regeln und Richtlinien, aber auch ein Platz der Freude und des Spaßes, wenn ");        output("die Regeln eingehalten werden.`n");        output("Solltest du dir mal unsicher sein bzgl. dem Leben in Alvion kannst du dich jederzeit ");        output("in der Bibliothek umsehen oder einen anderen Bewohner ansprechen. Sie werden dir sicher ");        output("gerne weiterhelfen!`n`n");        output("Du hast nun die Abschlussklasse der Schule von Alvion erreicht, in der nochmal das erlernte ");        output("Wissen abgefragt wird. Solltest du das Examen nicht schaffen, wirst du die Schullaufbahn erneut ");        output("verfolgen müssen. Also überlege dir deine Antworten gut und handle nicht vorschnell!`n`n");        output("Die Gründer von Alvion wünschen dir viel Glück und hoffen, dass du das Examen genauso mit Bravur ");        output("bestehst wie die bisherhigen Prüfungen, und dass auch du bald Teil von Alvion bist!`n`n");        output("Viel Erfolg...");        addnav("4.Klasse");        addnav("Prüfen lassen","school.php?op=lastclass&op1=exam");        addnav("Schule schwänzen");        addnav("LogOut","login.php?op=logout",true);   }}///STARTTEXT UND NAVIGATIONelse {output("`c`bDie Schule von Alvion`b`c`n`n");output("Alvion ist ein Rollenspieldorf, welches auf Legend of the Green Dragon (Lo(t)GD) basiert.`n");output("LotGD heißt, es gibt ein Dorf oder eine Stadt, eine Umgebung (Wald) und den Drachen. ");output("Die meisten Geschäfte, Plätze und Begebenheiten sind die Arbeit von vielen Spielern und Entwicklern auf der ganzen Welt. ");output("Doch das Aussehen, die Namen und viele Orte sind erschaffen von den hier residierenden Gründern.`n`n");output("In dieser Schule sollt ihr nun in `b4 Klassen`b die grundlegenden Spielregeln erlernen, welche das Leben in Alvion ");output("vereinfachen und dem Spiel seinen Charme geben.");if ($session[user][schoolprocess] == 0) {   addnav("1. Klasse");   addnav("Am Unterricht teilnehmen","school.php?op=firstclass");}elseif ($session[user][schoolprocess] == 1) {   addnav("1. Klasse");   addnav("Bestanden","");   addnav("2. Klasse");   addnav("Am Unterricht teilnehmen","school.php?op=secondclass");}elseif ($session[user][schoolprocess] == 2) {   addnav("1. Klasse");   addnav("Bestanden","");   addnav("2. Klasse");   addnav("Bestanden","");   addnav("3. Klasse");   addnav("Am Unterricht teilnehmen","school.php?op=thirdclass");}elseif ($session[user][schoolprocess] == 3) {   addnav("1. Klasse");   addnav("Bestanden","");   addnav("2. Klasse");   addnav("Bestanden","");   addnav("3. Klasse");   addnav("Bestanden","");   addnav("4. Klasse");   addnav("Am Unterricht teilnehmen","school.php?op=lastclass");}   addnav("Schule schwänzen");   addnav("LogOut","login.php?op=logout",true);}//////////////LÖSCHEN WENN FERTIG//////////////*addnav("Admin-Funktionen");addnav("Schulprozess (".$session[user][schoolprocess].") nullen","school.php?op5=reset");if ($_GET[op5]=="reset"){   $session[user][schoolprocess]=0;   redirect("school.php");}addnav("Zurück zur Grotte","superuser.php");*//////////////////////////////////////////////page_footer();?>

