
<?phprequire_once "common.php";page_header("Der Mann");$maxmeister=24;$maxeinzelmeister=17;$maxdoppelmeister=21;$meister=array(1=>"Zulan", 2=>"Elwus", 3=>"Reanda", 4=>"Gaya", 5=>"Genbu", 6=>"Olah", 7=>"Durlok",                    8=>"Lavariel", 9=>"Xeron", 10=>"Alamar", 11=>"Adastrea", 12=>"Pandora", 13=>"Salazar", 14=>"Telesto",                    15=>"Tiara", 16=>"Kapari", 17=>"Arkadon", 18=>"Akapo", 19=>"Dorlema", 20=>"Mhad Aban", 21=>"Ihtilma",                    22=>"Sorano", 23=>"Helio", 24=>"Lyrmia");                    $orden=array(1=>"Orden des Wassers", 2=>"Orden des Feuers", 3=>"Orden des Windes", 4=>"Orden der Erde", 5=>"Orden des Schattens",                    6=>"Orden des Lichtes", 7=>"Orden des Todes", 8=>"Orden des Lebens", 9=>"Orden des Äthers", 10=>"Orden des Phoenix",                    11=>"Orden der Liebe", 12=>"Orden des Glücks", 13=>"Orden der Zuversicht", 14=>"Orden der Treue",15=>"Orden der Morgenröte",                    16=>"Orden des Raureifs", 17=>"Orden der Drachen", 18=>"Orden des Dämonenreichs und Orden des Engelreiches",                    19=>"Orden des Untergangs und Orden der Auferstehung", 20=>"Orden der Vergangenheit, Orden der Zukunft und Orden der Gegenwart");                    $mfarben=array(0=>"", 1=>"`4", 2=>"`$", 3=>"`Q", 4=>"`q", 5=>"`X", 6=>"`_", 7=>"`&",                    8=>"`Á", 9=>"`D", 10=>"`(", 11=>"`9", 12=>"`m", 13=>"`M", 14=>"`é",                    15=>"`x", 16=>"`Z", 17=>"`]", 18=>"`=", 19=>"`8", 20=>"`g", 21=>"`2",                    22=>"`B", 23=>"`C", 24=>"`z");$ausgabe="`$`c`bDer Mann`c`b`nDu gehst auf den Mann im Gewand zu. Er schaut dich an und sagt dann:`n`^Ahh...{$session[user][name]} `^ich habe dich schon erwartet. Ich bin Zunog, derjenige der die Krieger zu ihren richtigen Meistern führt. ";$ausgabe.="`n`$ Er schaut auf ein Pergament und sagt dann:`n`n";if($session['user']['orden']<$maxeinzelmeister){        addnav("Dein Meister");//    addnav($mfarben[$session['user']['orden']+1].$meister[$session['user']['orden']+1],'meister.php?meister='.($session['user']['orden']+1).'&init=true');    addnav($mfarben[$session['user']['orden']+1].$meister[$session['user']['orden']+1],'meister.php?meister='.($session['user']['orden']+1).'');    $ausgabe.="`^Dein nächster Meister ist ".$mfarben[$session['user']['orden']+1].$meister[$session['user']['orden']+1]."`^, ".$mfarben[$session['user']['orden']+1].$orden[$session['user']['orden']+1]."`^.`n";}elseif($session['user']['orden']<$maxdoppelmeister){        addnav("Deine Meister");    if($session['user']['orden']<19){        addnav($mfarben[18].$meister[18].'`0 und '.$mfarben[19].$meister[19],'meister2.php?meister=18');        $ausgabe.='`^Deine nächsten Meister sind '.$mfarben[18].$meister[18].'`^ und '.$mfarben[19].$meister[19].'`^.`n';    }elseif($session['user']['orden']<21){        addnav($mfarben[20].$meister[20].'`0 und '.$mfarben[21].$meister[21],'meister2.php?meister=20');        $ausgabe.='`^Deine nächsten Meister sind '.$mfarben[20].$meister[20].'`^ und '.$mfarben[21].$meister[21].'`^.`n';    }} elseif($session['user']['orden']<$maxmeister){        addnav($mfarben[22].$meister[22].'`0, '.$mfarben[23].$meister[23].'`0 und '.$mfarben[24].$meister[24],'meister3.php?meister=22');        $ausgabe.='`^Deine nächsten Meister sind '.$mfarben[22].$meister[22].'`^ und '.$mfarben[23].$meister[23].'`^ und '.$mfarben[24].$meister[24].'`^.`n';}else {    $ausgabe.="`^Wie es aussieht hast du alle Meister besiegt! Glückwunsch!";}if($session[user][orden]<10){    $ausgabe.="`^`nWenn du 10 Meister besiegt hast, wird dir der Zugang zum Turm der Götter zustehen. Aber das ist nicht so einfach. Die Meister hier sind extrem stark. Nur wirklich mächtige Krieger können sie besiegen. Und auf eins muss ich dich hinweisen. Solltest du gegen einen deiner Meister verlieren gibt es kein zurück. Sie haben keine Gnade. Du wirst also um Leben und Tod kämpfen!";    $ausgabe.="`$`nDu schaust ihn noch einmal an und schaust dann auf die Tür deines nächsten Meisters.";} else {    $ausgabe.="`^`n`nDu hast zehn oder mehr Meister besiegt, und kannst nun über das Portalfeld in der Eingangshalle zum Turm der Götter gelangen!`n";}$ausgabe.="`n`n`5Du besitzt momentan `^{$session['user']['orden']} `5Orden!";output($ausgabe);addnav("Wege");addnav("Zurück zum Eingang","turm.php");addnav("Zurück zum Dorf","village.php");page_footer();?>
