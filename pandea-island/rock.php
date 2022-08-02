<?php
require_once("common.php");
// This idea is Imusade's from lotgd.net
if ($session['user']['dragonkills']>0 || $session['user']['superuser']>1) addcommentary();

checkday();
if ($_GET[op]=="egg"){
    page_header("Das goldene Ei");
    output("`^Du untersuchst das Ei und entdeckst winzige Inschriften:`n`n");
    viewcommentary("goldenegg","Botschaft hinterlassen:",10,"schreibt");
    addnav("Zurück zum Club","rock.php");
}else if($_GET[op]=="dastory"){
    page_header("Am Anfang war das Wort...");
    /*
    $cost=$session['user']['level']*30;
    if ($_GET[subop]=="flowerpower"){
        $sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'pandeas-geschichte',".$session[user][acctid].",'/me `@legt einen Strauß Blumen nieder')";
        db_query($sql) or die(db_error(LINK));
        $bonus=e_rand(1,15);
        if ($bonus==7){
            $session['user']['charm']++;
               output("`@Viele der umstehenden Personen haben deine Tat bemerkt und du erhältst einen Charmepunkt`n");
        }
        output("`@Du legst einen Strauß Blumen nieder`n`n`n");
        $abzug=$session['user']['gold']-$cost;
        $session['user']['gold']=$abzug;
    }
    */
    output("`7Du schlenderst ein wenig im Club der Veteranen herum und kommst an eine steinerne Tür. Was mag nur dahinter sein? Du spannst deine Muskeln an und stemmst die schwere Tür auf, um in einen großen Raum mit einer Tafel in der Mitte zu gelangen. Ein Loch in der Mitte der Decke lässt das einzige Licht in den Raum fallen und umhüllt die Tafel in einer hellen Aura. Auf ihr steht geschrieben:");
    output("`^`c`n`nDie Götter.`n
    Sie sind der Anfang, der Ursprung unserer Welt.`n
    Obwohl nur wenige Hundert, entfachte dennoch ein Streit in ihren Reihen.`n
    Ein Streit, der von einer Meinungsverschiedenheit zu einer Schlacht führte und sich von dieser in einen Krieg wandelte, der Äonen dauern sollte.`n
    Doch selbst in dieser langen Zeit, in der endlose Welten geschaffen und vernichtet worden waren, war kein Ende in Sicht - denn eins haben alle Götter gemein, die Starken wie die Schwachen: Unsterblichkeit! Und so wurde in einem Augenblick des trügerischen Friedens ein Bündnis geschmiedet. Eine neue, letzte Welt sollte über alles entscheiden, das Schicksal der Götter mit dem der Bewohner verknüpft werden, um so über Sieg und Niederlage zu entscheiden.`n
    Zum ersten Mal seit langer Zeit erschufen die Götter wieder gemeinsam eine neue Welt, mit Ausmaßen, die jede Vorstellungskraft überschritt. Sie gaben sich Mühe vieles unterschiedlich zu gestalten, denn Keinem sollte ein Vorteil zu Grunde liegen. Eisiger Frost im Norden, heiße Wüsten im Süden, durchfasert von endlosen Wäldern, getrennt durch Meere unendlicher Tiefe und Breite. Tiere entstanden: Gefährliche und Harmlose, Große und Kleine, die die Welt bevölkern sollten. Nur noch ein letzter Geniestreich fehlte in dieser widernatürlichen Vereinigung göttlicher Kräfte, um die Kampfarena zu perfektionieren: Die Erschaffung der Schiedsrichter, die mit ihrer unendlichen Weisheit über die Besiegten richten sollten:`n
    Die Grünen Drachen! Um ihre Aufgabe als Richter im Kampf der Giganten erfüllen zu können, wurden die riesigen Echsen nicht nur mit überragenden physischen und intellektuellen Kräften, sondern auch mit starken magischen Kräften ausgestattet. Doch um ihre eigentliche Aufgabe erfüllen zu können, verlieh man ihnen eine Kraft, die selbst den Göttern nicht gegeben war: Die Macht Unsterblichkeit zu nehmen! Kurz darauf zerbrach das Bündnis, geschmiedet in den heißen Feuern der Not und der immerwährende Krieg, der nur für die Zeit des Bündnisses ruhte, nahm von neuem seinen Lauf! Weil die Götter jedoch viel zu eitel waren, um selbst das Kriegsbeil in die Hand zu nehmen, erschufen sie Kreaturen, die für sie kämpfen sollten.`n
    Als erste Rasse schuf die eine Seite die Art der Trolle. Große, muskulös gebaute Einzelkämpfer, abgehärtet schon von Kindesbeinen an - da sie die Ersten ihrer Spezies waren - durch das Fehlen der Eltern.`n
    Ihnen entgegengestellt wurden die Zwerge, kleine muskulöse Gestalten, so breit wie hoch. Doch ihre Stärke lag in ihrem Intellekt und der Fähigkeit, sich selbst Waffen zu schmieden, die noch heute Ihresgleichen suchen.`n
    Chancenlos wurden die Trolle niedergeschmettert, die als Einzelkämpfer den überlegenen Kampftaktiken der Zwerge nicht gewachsen waren. In den Untergang folgten ihnen die Götter, die ihre Kräfte den Trollen verliehen, ihr Schicksal mit ihnen verknüpft hatten, gefällt von den Krallen der grünen Lindwürmer. Doch auch die Rasse der Zwerge wurde besiegt.
    Schlanke, beinahe schmächtige Wesen von hohem Wuchs mit Gesichtern wie aus Ebenholz geschnitzt, traten auf das Schlachtfeld. Zerbrechlich in ihrem Aussehen und doch die Beherrscher tödlichster Magien: Elfen! Weitere Götter, die die Zwerge ins Feld geleitet hatten, wurden von den gnadenlosen Klauen der grünen Echsen zerrissen, als die Zwerge der mächtigen Magie der Elfen nicht widerstehen konnten. Doch auch diese Herrschaft war nur von kurzer Dauer.`n
    Menschen, nicht so stark wie die Trolle, nicht so zäh wie die Zwerge und ohne Magie, besiegten sie dennoch die Elfen, allein schon auf Grund ihrer viel höheren Zahl und längerer Ausdauer. Wieder schlugen die Drachen unbarmherzig zu. Weitere neue Rassen wurden geschaffen und in den Krieg geschickt. Rassen, die nicht einmal lange genug überlebten, um in die Erinnerungen der Mächtigen zu gelangen. Zuletzt trafen Echsen, bekannt für ihre hohe Widerstandskraft auf Wesen aus Stein, genannt die Gargoyle. Und die Götter starben zu Dutzenden. Die, die einst die Mächtigsten des Universums genannt wurden, starben - auf beiden Seiten - schneller als viele der Rassen, deren Namen sich zu merken die Mühe nicht wert gewesen wäre. Angst durchlief ihre Reihen, vor einem neuen Feind, einem den sie selbst in ihrer Ignoranz geschaffen hatten. Angst vor den einzigen Wesen, die ihnen Schaden zufügen konnten. Sie befahlen den grünen Drachen sich selbst zu opfern, um der Götter willen, wie sie es mit unzähligen Rassen vorher getan hatten, doch die Drachen in ihrer Weisheit verlachten sie nur spöttisch. Zum zweiten Mal in der Geschichte des ewigen Krieges kam es zu einem Bündnis, um die aufmüpfigen Echsen zu töten. Höchstpersönlich kamen die Götter herab und bildeten eine Armee, um sie zu zerschmettern ... Der neue Krieg währte jedoch nur kurz. Entsetzt flohen die wenigen überlebenden Götter vor der alles vernichtenden Kriegsmaschinerie, genannt Grüner Drache!`n
    Aber auch so gewaltige und mächtige Kreaturen haben ihre Schwachstellen. So hatte sich gezeigt, dass manche Individuen der verschiedenen Rassen das Potenzial besaßen, das zu vollbringen, was die Götter trotz aller ihrer Macht nicht konnten. Ein einzelner Mensch tötete vor ihren erstaunten Augen, den ersten und einen der mächtigsten der Schuppenwesen. Schnell ward einer neuer Plan gefasst und bevor sie zu wenige wurden, veränderten sie die Oberfläche des Planeten. Inmitten des endlosen Ozeans entstand eine Insel. Ein kleines Dorf, um Schutz vor Wind und Wetter zu bieten, umgeben von einem Wald, in dem Kreaturen jeglicher Art ihre Heimat fanden. Weil die Insel auch sicher vor den feinen magischen Sinnen der Drachen sein sollte, ließen die Mächtigen einen Orkan um die Insel wüten - die sich in seinem Auge befand, dem einzigen Pol der Ruhe. Dort sollten die Männer und Frauen, die sich würdig erwiesen hatten, einer Ausbildung zuteil werden, um sich gegen den bevorstehenden Endkampf zu rüsten. Sogar einen jungen grünen Drachen fingen die Götter und verbannten ihn in eine tiefe Höhle.`n
    Sie veränderten die Grundpfeiler der Welt für diese Insel und schließlich zeigte selbst Ramius, der Totengott - und der einzige Unbeteiligte in diesem Krieg - ein einsehen. Der schlimmste Feind eines jeden Kriegers, der Tod, war von der Akademie des Drachenkampfes verbannt worden. Als ersten Bewohner wählten sie den Menschen, der einst den Ersten der Drachen erschlagen hatte. Ein Mann, der fortan König genannt werden sollte und dessen Namen noch heute die Insel ziert. Weitere würdige Krieger folgten ihm nur wenig später auf die Insel der Götter. Noch heute streifen die Götter durch die Welt, auf der Suche nach letzten Überlebenden und würdigen Anwärtern im Kampf der Giganten.`c`n`n");
    /*
    $sql="SELECT * FROM commentary WHERE section='pandeas-geschichte' LIMIT 1,25";
    $result=db_query($sql);
    for($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result) or die(db_error(LINK));
        $benutzer=$row['author'];
        $sqluser="SELECT * FROM accounts WHERE acctid=$benutzer";
        $resultuser=db_query($sqluser);
        $rowuser = db_fetch_assoc($resultuser) or die(db_error(LINK));
        output("$rowuser[name] `@legt einen Strauß Rosen nieder`n");
    }
    if ($session['user']['gold']>=$cost) addnav("Blumen niederlegen ($cost Goldstücke)","rock.php?op=dastory&subop=flowerpower");
    */
    addnav("Zurück zum Club","rock.php");
}else if($_GET[op]=="egg2"){
    page_header("Das goldene Ei");
    $preis=$session[user][level]*60;
    output("`3Du fragst ein paar Leute hier, ob sie wissen, wo sich der Besitzer des legendären goldenen Eies aufhält. Einige lachen dich aus, weil du nach einer Legende suchst, ");
    output("schütteln nur den Kopf. Du willst gerade ".($session[user][sex]?"einen jungen Mann":"eine junge Dame")." ansprechen, als dich eine nervös wirkende Echse zur Seite zieht: ");
    output("\"`#Psssst! Ich weissss wen ihr ssssucht und wo ssssich diesssser Jemand aufhält. Aber wenn ich euch dassss ssssagen ssssoll, müsssst ihr mir einen Gefallen tun. Ich habe ");
    output("Sssschulden in Höhe von `^$preis`# Gold. Helft mir, diesssse losssszzzzuwerden und ich ssssag euch, wassss ich weissss. Anssssonssssten habt ihr mich nie gessssehen.`3\"");
    addnav("G?Zahle `^$preis`0 Gold","rock.php?op=egg3");
    addnav("Zurück zum Club","rock.php");
}else if($_GET[op]=="egg3"){
    page_header("Das goldene Ei");
    $preis=$session[user][level]*60;
    if ($session[user][gold]<$preis){
        output("`3\"`#Von dem bisssschen Gold kann ich meine Sssschulden nicht bezzzzahlen. Vergissss essss!`3\"");
    }else{
        $sql="SELECT acctid,name,location,loggedin,laston,alive,housekey FROM accounts WHERE acctid=".getsetting("hasegg",0);
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        $loggedin=(date("U") - strtotime($row[laston]) < getsetting("LOGINTIMEOUT",900) && $row[loggedin]);
        if ($row[location]==0) $loc=($loggedin?"Online":"in den Feldern");
        if ($row[location]==1) $loc="in einem Zzzzimmer in der Kneipe";
        // part from houses.php
        if ($row[location]==2){
            $sql="SELECT hvalue FROM items WHERE class='Schlüssel' AND owner=$row[acctid] AND hvalue>0";
            $result = db_query($sql) or die(db_error(LINK));
            $row2 = db_fetch_assoc($result);
            $loc="im Haussss Nummer ".($row2[hvalue]?$row2[hvalue]:$row[housekey])."";
        }
        // end houses
        $row[name]=str_replace("s","ssss",$row[name]);
        $row[name]=str_replace("z","zzzz",$row[name]);
        output("`3Hissssa nimmt deine `^$preis`3 Gold, schaut sich nervös um und flüstert dir zu: \"`#$row[name]`# isssst $loc".($row[alive]?" und lebt.":", isssst aber tot!")." Und jetzzzzt lassss mich bitte in Ruhe. Achja: Diesssse Information hasssst du nicht von mir!`3\"");
        $session[user][gold]-=$preis;
    }
    addnav("Zurück zum Club","rock.php");
}else{
    if ($session['user']['dragonkills']>0 || $session['user']['superuser']>1){
        page_header("Club der Veteranen");

        output("`b`c`2Der Club der Veteranen`0`c`b");

        output("`n`n`4Irgendetwas in dir zwingt dich, den merkwürdig aussehenden Felsen zu untersuchen. Irgendeine dunkle Magie, gefangen in uraltem Grauen.");
        output("`n`nAls du am Felsen ankommst, fängt eine alte Narbe an deinem Arm an zu pochen. Das Pochen ist mit einem rätselhaften Licht synchron, ");
        output("das jetzt von dem Felsen zu kommen scheint. Gebannt starrst du auf den schimmernden Felsen, der eine Sinnestäuschung von dir abschüttelt. Du erkennst, daß das mehr ");
        output("als ein Felsbrocken ist. Tatsächlich ist es ein Eingang, über dessen Schwelle du andere wie dich siehst, die auch die selbe Narbe wie du tragen. Sie ");
        output("erinnert dich irgendwie an den Kopf einer dieser riesigen Schlangen aus Legenden. Du hast den Club der Veteranen entdeckt und betrittst dieses unterirdische Gewölbe.");
        output("`n`n");
        if ($session[user][acctid]==getsetting("hasegg",0)){
            output("Da du dich hier zurückziehen kannst, könntest du das `^goldene Ei`4 mal näher untersuchen.`n`nDie Veteranen unterhalten sich:`n");
            addnav("Ei untersuchen","rock.php?op=egg");
        }else if (getsetting("hasegg",0)>0){
            output("Wenn dir hier niemand sagen kann, wo sich der Besitzer des goldenen Eies aufhält, dann wird es dir niemand sagen können.`n`nDie Veteranen unterhalten sich:`n");
            addnav("Nach dem goldenen Ei fragen","rock.php?op=egg2");
        }
        if (!$session['prefs']['nosounds']) {
            switch(e_rand(1,9)){
            case 1:
            output("<embed src=\"media/alf.mid\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);
            break;
            case 2:
            output("<embed src=\"media/babyonemoretime.mid\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);
            break;
            case 3:
            output("<embed src=\"media/entertainer2.mid\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);
            break;
            case 4:
            output("<embed src=\"media/escape.mid\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);
            break;
            case 5:
            output("<embed src=\"media/goldeneye.mid\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);
            break;
            case 6:
            output("<embed src=\"media/layla.mid\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);
            break;
            case 7:
            output("<embed src=\"media/mybonnie.mid\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);
            break;
            case 8:
            output("<embed src=\"media/sandman.mid\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);
            break;
            case 9:
            output("<embed src=\"media/locomo.mid\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);
            break;
            }
        }
        viewcommentary("veterans","Angeben:",30,"prahlt");
        addnav("Schrein des Ramius","shrine.php");
        addnav("Schrein der Erneuerung","rebirth.php");
        addnav("Geschichte Pandeas","rock.php?op=dastory");
    }else{
        page_header("Seltsamer Felsen");
        output("Du näherst dich dem seltsam aussehenden Felsen. Nachdem du ihn eine ganze Weile angestarrt hast, bleibt es auch weiterhin nur ein seltsam aussehnder Felsen.`n`n");
        output("Gelangweilt gehst du zum Dorfplatz zurück.");
    }
}
addnav("Zurück zum Dorf","village.php");

page_footer();
?>