<?php
//*------------------------------------------------------------------ *
//|                 Die Grotte der 1000 Träume                        |
//|      Scriptet by °*Amerilion*° comments to www.greenmano@gmx.de   |
//|                first seen at http://anaras.ch/                    |
//| Very big thanks to Hadriel for correction of lot's of mistakes    |
//|         Modifycated by Hadriel @ hadrielnet.ch (04/12/04)         |
//|                    => My mothers birthday <=                      |
//|              more dreams by °*Amerilion*° late insert             |
//|               more modifications by °*Amerilion*°                 |
//|         for his own LoGD--> http://rulina.de/logd/index.php       |
//*------------------------------------------------------------------ *
/*    ---History---
Dezember04
-3 Träume
-Rose verschicken noch nicht möglich
-Rundenverlust noch nicht eingebaut
-Farbliche Gestaltung fehlerhaft, ausserdem mies formatiert
-Statue schon da

Dezember04
-Rose verschicken durch Hadirel eingebaut. Idee war von Amerilion
-Rundenverlust eingebaut. Idee by Fly
-Formatkorrektur

Februar05
-5 Träume
-Farbliche Gestaltung verbessert
-Kommentarfelsen zugefügt

März05
-Auf Sanela zugeschnitten


Sanela-Pack Version 1.1
*/


require_once "common.php";

page_header("Die Grotte der 1000 Träume...");
$session['user']['sanela']=unserialize($session['user']['sanela']);
if($_GET[op]==""){
    output("`n`c`b`gAnokis Grotte`b`c`n`n");
    
        output("`7Du bemerkst in einer Seitengasse eine Treppe, die nach unten führt. Neugierig");
        output("folgst du ihr und gelangst in einen dunklen, durch wenige Fackeln erhellten Gang.");
        output("Die Wände sind aus schwarzem Gestein und mit kleinen, wie Sternen funkelnden");
        output("`2Smaragden`7, `^Bernstein`7 und `4Rubinen`7 besetzt. Nach einiger Zeit bemerkst");
        output("du, dass sich der Gang ausdehnt und zu einer grossen Grotte wird. Du stellst mit");
        output("Erstaunen fest, das alle Farben des Regenbogens auf den von Wasser nassen Wänden funkeln.");
        output("Du erblickst in der Mitte der Höhle einen Teich auf dessen spiegelglatter Oberfläche einige Seerosen in `9hellblau");
        output("`rrosa `&weiss`7 und selbst in `~schwarz`7 schwimmen. Auf einer Insel aus Steinen ist die Statue");
        output("einer wunderschönen `rRosen`9prinzessin `7errichtet worden, deren Augen aus Sternen zu bestehen scheinen.");
        output("Du stehst noch am Durchgang und staunst, als du plötzlich eine Stimme vernimmst, die in angenehmem");
        output("Tonfall mit dir spricht.`n`#\"Hallo ich bin `gAnoki`#, der Herr über den Schlaf und die Träume.");
        if($session['user']['sanela']['grotte']==0){
        if($session['user']['turns']>=1){
            output("Ich gebe dir die Wahl zwischen drei Träumen. Wähle mit bedacht!\"`n`n`@Du brauchst mindestens");
            output("einen Waldkampf um träumen zu können.`n`n`7 Welchen wählst du?`nDen  <a href='grotte.php?op=traume'>",true);
            output("`2Smaragd-Kissentraum`7</a>?,`n oder den<a href='grotte.php?op=traumz'> `^Bernstein-Kissentraum`7</a>,`n",true);
            output("oder aber den <a href='grotte.php?op=traumd'>`4Rubin-Kissentraum`7?", true);
            addnav("","grotte.php?op=traume");
            addnav("","grotte.php?op=traumz");
            addnav("","grotte.php?op=traumd");
            addnav("Smaragd-Traumkissen.","grotte.php?op=traume");
            addnav("Bernstein-Traumkissen","grotte.php?op=traumz");
            addnav("Rubin-Traumkissen","grotte.php?op=traumd");
        } else {
            output("Ich sehe, dass du dich leider heute schon zu sehr verausgabt hast. Komm morgen frisch und erholt wieder zurück");
            output("und ich werde dir das Geheinmis meiner Träume offenbaren.");
        }
        addnav("Betritt den Tempel","grotte.php?op=statue");
        addnav("Seltsamer Fels","grotte.php?op=fels");
        addnav("Nach Zylyma","zylyma.php");
    }else{
        output("Du versuchst erneut an diesem Tag in die Grotte der Träume einzudringen. Doch als du der Treppe ein Stück in die Tiefe gefolgt bist, hält dich ein Gitter auf.");
        addnav("Betritt den Tempel","grotte.php?op=statue");
        addnav("Seltsamer Fels","grotte.php?op=fels");
        addnav("Nach Zylyma","zylyma.php");
    }
}
if($_GET[op]=="traume"){
    $session['user']['sanela']['grotte']=1;
    $session['user']['turns']--;
    output("Du Entscheidest dich für Traumkissen in `2Smaragdfarbe`7 und entschlummerst sanft unter den grünen Licht der `2Smaragde`7. Dein letzter Blick gilt einer `9hellblauen`7 Seerose.`n`n");
    switch(e_rand(1,2)){
        case 1:
        output("Du träumst von einer wundschönen `@Sommerwiese`7 auf der `9hellblaue `7 Blumen wachsen, die sich inneinander verschlingen und fühlst dich so glücklich wie lange nicht mehr.");
        output("Der Grüne Drache und das dumme Gerede auf den Dorfplatz scheinen meilenweit entfernt zu sein, an diesem Ort der Stille und der Einkehr.");
        output("Nachdem du einige Zeit auf der Wiese verbracht hast holt dich `gEratiel`7 wieder in die Welt der Lebenden zurück.");
        output("`nDoch als du in der Grotte aufwachst...`n`n");
        break;
        case 2:
        output("Du träumst von einer kleinen Hütte. In dieser sitzt eine ärmlich gekleidete Elfe mit ihren kleinen Kind. Du vermagst nicht");
        output("ihr Alter zu schätzen, doch sie sieht dich mit solch müden Augen an, dass du denkst, dass sie schon jahrhunderte alt sein muss.");
        output("Leise seufzt sie, streicht dem Kind noch einmal sanft über die güldenen Locken und legt es auf ein kleines Kissen.");
        output("Dann sieht sie dich an und spricht leise:`#`n\"Nun denn... Es schläft... Genießt seine Träume, welcher Art auch immer sie");
        output("sein mögen. Wisset das es ein besonderes Kind ist... Es ist ein Symbol für die Macht der Träume, denn ob ihr glaubt oder");
        output("nicht, dieses \"Kind\" ist schon sehr alt, älter als ich... Doch da es immer schläft bleibt es ewiglich jung, und");
        output("wohl auch ewiglich wird es vom Glück erfüllt sein...\"`n`7 Du antwortest nicht, sondern siehst dir das Kind an, das");
        output("immer noch ruhig schläft. Du meinst, ein Lächeln zu erkennen und dich erfüllt die Sehnsucht auch ewig zu schlafen, so dass");
        output("du fast schon aufbegehrst als `gEratiel`7 dich schließlich wieder in die Welt der Lebenden holt.`n Doch als du wieder aufwachst...`n`n");
        break;
    }
    switch(e_rand(1,8)){
        case 1:
        output(" bemerkst du, dass du viel gelernt hast.");
        $session['user']['experience']+=1000;
        addnav("Nach Zylyma","zylyma.php");
        break;
        case 2:
        case 3:
        case 4:
        output(" liegt ein Edelstein neben dir. Du nimmst ihn freudig mit und verlässt die Grotte.");
        $session[user][gems]++;
        addnav("Nach Zylyma","zylyma.php");
        break;
        case 5:
        case 6:
        case 7:
        output(" bemerkst du eine `9hellblaue `7Wasserrose.`n Dir fällt sicher jemand ein dem du sie schicken kannst, oder?");
        addnav("Nein, lieber Nach Zylyma","zylyma.php");
        addnav("Wasserrose verschicken","grotte.php?op=verschick");
        break;
        case 8:
        output(" bemerkst du dass du dich erholt hast.");
        $session['user']['turns']+=2;
        addnav("Nach Zylyma","zylyma.php");
        break;
    }
}
if($_GET[op]=="traumz"){
    $session['user']['sanela']['grotte']=1;
    $session['user']['turns']--;
    output("Du Entscheidest dich für Traumkissen in `6Bernsteinfarbe `7und entschlummerst sanft unter den gelben Licht der `6Bernsteine`7. Dein letzter Blick gilt einer `rrosanen`7 Seerose.");
    switch(e_rand(1,2)){
        case 1:
        output("Du träumst von einem wundschönen `6Kornfeld`7, auf dem `rrosa `7 Mohn wächst, der sich inneinander verschlingt und fühlst dich so glücklich wie lange nicht mehr.");
        output("Der Grüne Drache und das dumme Gerede auf den Dorfplatz scheinen meilenweit entfernt zu sein an diesem Ort der Stille und der Einkehr.");
        output("Nachdem du einige Zeit auf der Wiese verbrachtest holt dich `gEratiel`7 wieder in die Welt der Lebenden zurück.");
        output("`nDoch als du in der Grotte aufwachst...`n`n");
        break;
        case 2:
        output("Du träumst von einer Abendämmerung in der du alleine in Richtung Wald spazierst. Eine kleine `&weiße`7 Eule fliegt neben");
        output("dir her und lotst dich in Richtung eines kleinen Pfades. Du folgst ihr, gradezu magisch von ihrer Schönheit angezogen.");
        output("Sie scheint immer zu warten wenn du mal zu langsam für sie bist. Der Weg steigt stetig an und endet schließlich an einer");
        output("Felswand. Du gehst ein Stück an ihr entlang. Da die Eule verschwunden ist steigt leichtes Unbehagen in dir auf.");
        output("Vor dir öffnet sich der Wald zu einer kleinen Lichtung und du vernimmst ein leises Rauschen, welches scheinbar von einem");
        output("Wasserfall kommt. Nach einigen Schritten öffnet sich der Wald vollends und du wirst vom Licht des Mondes schon fast geblendet.");
        output("Ein kleiner Teich, mit `9Seerosen`7 und einer kleinen Steininsel in der Mitte liegt vor dir. Durch den Wasserfall ausgelöst schlagen");
        output("Wellen an das Ufer und als du dir die Insel genauer ansiehst endeckst du darauf eine kleine, mit Blütenkelchen spielende");
        output("Fee. Sie sieht dich an und kichert leise.`n`3\"Hallo... Ich soll dir von deiner großen Liebe schöne Grüße bestellen... Ihr werdet sie");
        output("bald treffen...\"`7`nDu siehst sie dankbar an und schlenderst noch eine Zeit lang barfuss im Teich umher, wobei du darauf achtest");
        output("die `9Rosen`7 nicht zu zertretten.`nSchließlich holt dich `gEratiel`7 wieder in die Welt der Lebenden zurück, doch als du aufwachst...`n`n");
        break;
    }
    switch(e_rand(1,8)){
        case 1:
        output(" bemerkst du, dass du viel gelernt hast.");
        $session['user']['experience']+=1000;
        addnav("Nach Zylyma","zylyma.php");
        break;
        case 2:
        case 3:
        case 4:
        output(" liegt ein Edelstein neben dir. Du nimmst ihn freudig mit und verlässt die Grotte.");
        $session['user']['gems']++;
        addnav("Zurück Nach Zylyma","zylyma.php");
        break;
        case 5:
        case 6:
        case 7:
        output(" bemerkst du eine `9hellblaue `7Wasserrose.`n Dir fällt sicher jemand ein, dem du sie schicken kannst, oder?");
        addnav("Nein, lieber Nach Zylyma","zylyma.php");
        addnav("Wasserrose verschicken","grotte.php?op=verschick");
        break;
        case 8:
        output(" bemerkst du, dass du dich erholt hast.");
        $session['user']['turns']+=2;
        addnav("Nach Zylyma","zylyma.php");
        break;
    }
}
if($_GET[op]=="traumd"){
    $session['user']['sanela']['grotte']=1;
    $session['user']['turns']--;
    output("Du entscheidest dich für Traumkissen in `4Rubinfarbe`7 und entschlummerst sanft unter dem rotem Licht der `4Rubine`7.`n Dein letzter Blick gilt einer `9schwarzen`7 Seerose.");
    output("Du träumst von einer wundschönen `4Rubinroten Ebene`7, auf der `~schwarze`7 Blumen wachsen, die sich inneinander verschlingen und fühlst dich so glücklich wie lange nicht mehr.");
    output("Der Grüne Drache und das dumme Gerede auf den Dorfplatz scheinen meilenweit entfernt zu sein`n an diesem Ort der Stille und der Einkehr.");
    output("Nachdem du einige Zeit auf der Ebene verbrachtest holt dich `gEratiel`7 wieder in die Welt der Lebenden zurück.");
    output("`nDoch als du in der Grotte aufwachst...`n`n");
    switch(e_rand(1,8)){
        case 1:
        output(" bemerkst du, dasd du viel gelernt hast.");
        $session['user']['experience']+=1000;
        addnav("Nach Zylyma","zylyma.php");
        break;
        case 2:
        case 3:
        case 4:
        output(" liegt ein Edelstein neben dir. Du nimmst ihn freudig mit und verlässt die Grotte.");
        $session['user']['gems']++;
        addnav("Zurück Nach Zylyma","zylyma.php");
        break;
        case 5:
        case 6:
        case 7:
        output(" bemerkst du eine `9hellblaue `7Wasserrose.`n Dir fällt sicher jemand ein, dem du sie schicken kannst, oder?");
        addnav("Nein, lieber Nach Zylyma","zylyma.php");
        addnav("Wasserrose verschicken","grotte.php?op=verschick");
        break;
        case 8:
        output(" bemerkst du, dass du dich erholt hast.");
        $session['user']['turns']+=2;
        addnav("Nach Zylyma","zylyma.php");
        break;
    }
}

if($_GET[op]=="statue"){
    output("`c`1Es `!is`9t ein seltsamer Tempel, gar nicht so, wie die der anderen Gö`!tt`1er.`n
An`!ok`9i selbst soll den Ort erwählt haben und es ist eine einfache H`!öh`1le,`n
we`!lc`9he mit Seidentüchern  ein wenig heimeliger gemacht w`!ur`1de.`n
St`!el`9len welche freien Himmels sind, werden mit ihnen auch abged`!ec`1kt.`n
Es `!gi`9bt mehrere Abzweigungen welche zu den Zimmern der Prieste`!r u`1nd`n
de`!n Z`9ellen jener Besucher führen, welche sich zum Z`!we`1ck`n
ei`!ne`9r Traumdeutung hier aufha`!lt`1en.`n
`n
Im `!Ha`9uptraum befindet sich das Abbild des Go`!tt`1es,`n
vo`!r w`9elchem die Opfergaben von Weihrauch oder anderem Räucher`!we`1rk`n
da`!rg`9eboten we!rd`1en.`n
De`!r g`9esamte Tempel wird außerdem von einem Qualmwolke ausgef`!ül`1lt,`n
we`!lch`9e durch die vielzähligen Wasserpfeifen erzeugt `!wi`1rd.`n");
    addcommentary();
    viewcommentary("anoki","Hinzufügen",10);
    addnav("Zurück","grotte.php");
}
if($_GET[op]=="fels"){
    output("Auf einem seltsamen Felsen in einer Nische haben Abenteurer ihre Gedanken hinterlassen.`n`n`n");
    addcommentary();
    viewcommentary("grotte","Hinzufügen",10);
    addnav("Zurück","grotte.php");
}
if($_GET[op]=="verschick"){
    addnav("Doch nicht","village.php");
    output("<form action='grotte.php?op=verschick2' method='POST'>",true);
    addnav("","grotte.php?op=verschick2");
    output("`v`nAn wen willst du die Rose schicken schicken?`n <input name='name' id='name'> <input type='submit' class='button' value='Suchen'>",true);
    output("</form>",true);
    output("<script language='JavaScript'>document.getElementById('name').focus()</script>",true);
}
if($_GET[op]=="verschick2"){
    $string="%";
    for ($x=0;$x<strlen($_POST['name']);$x++){
        $string .= substr($_POST['name'],$x,1)."%";
    }
    $sql = "SELECT * FROM accounts WHERE name LIKE '".addslashes($string)."' AND locked=0 ORDER BY level,login";
    $result = db_query($sql);
    if (db_num_rows($result)<=0){
        output("Du kannst niemanden mit einem solchen Namen finden...`@");
    }elseif(db_num_rows($result)>100){
        output("Du solltest die Zahl derer, die du stärken willst etwas einschränken.");
        output("<form action='grotte.php?op=verschick2' method='POST'>",true);
        addnav("","grotte.php?op=verschick2");
        output("Wem willst du die Rose schicken? `n<input name='name' id='name'> <input type='submit' class='button' value='Suchen'>",true);
        output("</form>",true);
        output("<script language='JavaScript'>document.getElementById('name').focus()</script>",true);
    }else{
        output("Du kannst folgenden Leuten dein Geschenk schicken:`n");
        output("<table cellpadding='3' cellspacing='0' border='0'>",true);
        output("<tr class='trhead'><td>Name</td><td>Level</td></tr>",true);
        for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            output("<tr class='".($i%2?"trlight":"trdark")."'><td><a href='grotte.php?op=verschick3&acctid=".HTMLEntities($row['acctid'])."'>",true);
            output($row['name']);
            output("</a></td><td>",true);
            output($row['level']);
            output("</td></tr>",true);
            addnav("","grotte.php?op=verschick3&acctid=".HTMLEntities($row['acctid']));
        }
        output("</table>",true);
    }
    addnav("Doch nicht","village.php");
}
if($_GET[op]=="verschick3"){
    output("Möchtest du noch eine Karte beilegen?");
    output("<form action='grotte.php?op=verschick4&card=yes&acctid=".$_GET[acctid]."' method='POST'>Folgenden Text schicken: <input name='cardtext' value='$_POST[cardtext]'><input type='submit' class='button' value='Senden'></form>",true);
    addnav("Keine Karte","grotte.php?op=verschick4&card=no&acctid=".$_GET[acctid]."");
    addnav("","grotte.php?op=verschick4&card=yes&acctid=".$_GET[acctid]."");
}
if($_GET[op]=="verschick4"){
    $sql="INSERT INTO items(name,class,owner,value1,gold,gems,description) VALUES ('`9Wasserose','Beute',".$_GET[acctid].",0,100,0,'Eine wundervoll glänzende Wasserrose')";
    db_query($sql);
    $gift="eine wundervolle, `vblaue Wasserrose `0";
    $mailmessage=$session[user][name];
    $mailmessage.="`7 hat dir ein Paket geschickt. Du öffnest es. Es ist `6";
    $mailmessage.=$gift;
    //you can change the following the match what you name your gift shop
    $mailmessage.="`7 von der Grotte der 1000 Träume.`n".$effekt;
    if($_GET[card]=="yes"){
        $mailmessage.="`7Es liegt eine Karte mit folgendem Text bei: `n`n";
        $mailmessage.= $_POST[cardtext];
        $mailmessage.="`n";
    }
    if($_GET[card]=="yes" || $_GET[card]=="no"){
        systemmail($_GET[acctid],"`2Geschenk erhalten!`2",$mailmessage);
        output("`rDein Geschenk wurde verschickt!");
        addnav("Weiter","grotte.php");
    }
}
$session['user']['sanela']=serialize($session['user']['sanela']);
page_footer();
?>