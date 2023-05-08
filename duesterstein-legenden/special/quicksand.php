
<?
// idea of gargamel @ www.rabenthal.de
if (!isset($session)) exit();

if ($HTTP_GET_VARS[op]==""){
    output("`n`8Auf Deinem Weg durch den Wald kommst du an einen kleinen Fluss, denn Du
    überqueren musst.`0 Du folgst dem Ufer ein Stück, bis Du plötzlich eine Sandbank
    entdeckst. Hier willst Du versuchen, mit trockenen Füßen auf die andere Uferseite
    zu gelangen. Beherzt springst Du auf die Sandbank...`0`n`n");

    switch(e_rand(1,10)){
    case 1:
    case 2:
    case 3:
    case 4:
    $gold=$session[user][level] * e_rand(2,10);
    output("Du läufst über die Sandbank auf die andere Seite und springst von dort
    an das gegenüberliegende Ufer des Flusses. Du kommst dort wohlbehalten an und
    setzt Deinen Weg fort. `n`0");
    If ( $session[user][gold] == 0 ) {
        output("Du bist froh, dass Du gar kein Gold dabei hast, dann das
        wäre Dir bei Deinen wilden Sprüngen womöglich aus der Tasche gefallen...`0");
    }
    else if ( $session[user][gold] <= $gold ) {
        output("Plötzlich stellst Du fest, dass Dir bei Deinen Sprüngen wohl
        `9all Dein Gold`0 aus der Tasche gefallen ist.`0");
        $session[user][gold]=0;
    }
    else {
        output("Plötzlich stellst Du fest, dass Dir bei Deinen Sprüngen wohl
        `9$gold Goldstücke`0 aus der Tasche gefallen sind.`0");
        $session[user][gold]-= $gold;
    }
    $session[user][specialinc]="";
    break;
    
    case 5:    case 6:    case 7:
    case 8:    case 9:    case 10:
    output("Du läufst über die Sandbank auf die andere Seite, als Du ganz plötzlich
    einsinkst. `%Du bist in Treibsand geraten!`0 Nun ist guter Rat teuer - aber es geht
    hier nicht um Gold, sondern um Dein Leben! `n`nWas wirst Du tun?`0");
    addnav("auf Hilfe warten","forest.php?op=wait");
    addnav("Dich selbst befreien","forest.php?op=free");
    $session[user][specialinc] = "quicksand.php";
    break;
    }
}
else if ($HTTP_GET_VARS[op]=="wait"){   // auf Hilfe warten
     output("`nDu weisst, dass Du hier ohne fremde Hilfe nicht herauskommst und siehst
     Dich um.`0");
     if ( $session[user][turns] == 0 ) {
         output("`n`nDa Du aber wirklich arm dran bist und noch nicht einmal mehr in
         den Wald gehen und kämpfen kannst, haben die Götter ein einsehen.`nVoller Mitleid
         befreien Sie Dich aus Deiner mißlichen Lage - Du kannst gehen!`0");
         $session[user][specialinc]="";
     }
     else {
     switch(e_rand(0,1)){
         case 0:
         output("`n`nErfreut bemerkst Du, dass ein anderer Bewohner nur ein kleines
         Stück weiter durch die Gegend streift. Du rufst laut um `$\"Hilfe!\"`0 und wirst
         gehört. `nDir wird ein stabiler Ast gereicht, an dem Du Dich gut festhalten
         kannst, dann wirst Du aus Deiner mißlichen Lage herausgezogen.`0");
         output("`n`n`3Du musstest etwas auf Hilfe warten und verlierst deswegen einen
         Waldkampf.`0 Trotzdem bist Du sehr dankbar und ziehst mit noch etwas wackeligen
         Beinen weiter.`0");
         $session[user][turns]-=1;
         $session[user][specialinc]="";
         break;
         
         case 1:
         output("`n`nHektisch schaust Du Dich um, ob Du einen anderen Bewohner entdeckst,
         der Dir zu Hilfe eilen könnte.`0");
         output("`n`n`3Du wartest, und wartest, ...... und wartest. `$\"Wo bleiben die denn
         alle?\"`0 fragst Du Dich und vermutest, dass die Taverne wieder mal bis auf den
         letzten Platz gefüllt ist. `nTja... `n`3Du hast nun schon einen Waldkampf verpasst.`0 Wie
         soll es weitergehen?`0");
         $session[user][turns]-=1;
         addnav("auf Hilfe warten","forest.php?op=wait");
         addnav("Dich selbst befreien","forest.php?op=free");
         $session[user][specialinc] = "quicksand.php";
         break;
     }
     }
}
else if ($HTTP_GET_VARS[op]=="free"){   // selbst befreien
     output("`n`6Du versuchst Dich selbst zu befreien.`0 Du legst zunächst Deine Rüstung
     und Deine Waffe ab und strampelst, um wieder frei zu kommen. Aber Du steckst fest.
     Mit aller Kraft stemmst Du Dich gegen den weichen Sand, doch Du findest keinen
     Halt und versinkst langsam immer weiter.`0");
     $bis = 2;
     if ( $session[user][gold] > 0 ) $bis = 1;
     switch(e_rand(1,$bis)) {
         case 1:
         $exp = $session[user][experience] * 0.15;
         output("`n`nDann plötzlich spürst Du ein Steinblock unter Deinem Fuß, an dem
         Du Dich abstossen kannst und kommst frei. `n`n`QDir wird bewusst, dass Du Dich
         völlig falsch verhalten hast, weil Dich Deine armseligen Befreiungsversuche nur
         noch tiefer in den Sand gezogen haben. Beinahe wärst Du hier umgekommen!
         `nDu verlierst daher 15% Deiner Erfahrung!`0");
         $session[user][experience]-= $exp;
         $session[user][specialinc]="";
         break;
         
         case 2:
         output("`n`nLangsam verlässt Dich Deine Kraft. Du überlegst fieberhaft, wie Du Dich
         hier allein befreien kannst. Dann blickst Du auf Deine Goldbörse. Du siehst keine
         andere Möglichkeit, als dieses zusätzliche Gewicht loszuwerden. `QAls letzte Hoffnung
         wirst wirfst Du Deine Goldbörse weg - und tatsächlich, Du versinkst nun nicht weiter
         und kannst Dich langsam befreien.`0");
         output("`n`nAuch wenn Du all Dein Gold verloren hast, so hast Du doch Dein Leben
         behalten. `QDas Du einen Waldkampf vertrödelt hast, ist Dir egal.`0");
         $session[user][gold] = 0;
         $session[user][turns]-=1;
         $session[user][specialinc]="";
         break;
     }
     output("`n`nDu legst Deine Rüstung wieder an, nimmst Deine Waffe und ziehst weiter.`0");
}
?>


