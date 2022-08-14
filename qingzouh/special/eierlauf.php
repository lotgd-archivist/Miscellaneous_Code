
<?
#####################################
#                                   #
#            Osterspezial           #
#            für den Wald           #
#            von Midgar             #
#  http://www.logd-midgar.de/logd/  #
#        von Sefan Freihagen        #
#       mit Unterstützung von       #
#     Laserian, Amon Chan und mfs   #
#          Texte von Charina        #
#            Ostern  2008           #
#            Frohe Ostern           #
#                                   #
#####################################
require_once "common.php";
page_header("Eierlauf");
if (!isset($session)) exit();

if ($_GET[op]==""){
      output("`n`n `@Auf deinem Weg durch die Wiese, siehst du eine kleine Hasenschar.
   Du gehst langsam und leise weiter, schliesslich willst du die Hasen nicht
   erschrecken. Als du nur noch fünf Meter von ihnen entfernt bist, dreht sich
   der grösste der Hasen zu dir um und sagt zu dir mit piepsender Stimme:
   \"`&Ah, wir hatten schon auf dich gewartet.`@\" Wie auf dich gewartet?
   Du siehst ihn verwirrt an und fragst: \"`&Wie, auf mich gewartet? Was wollt
   ihr von mir?`@\" Nun dreht sich der nächst kleinere Hase zu dir um und sagt:
   \"`&Ja, wir machen hier einen kleinen Wettstreit und brauchen dich, um ihn
   austragen zu können.`@\"`n`n<a href='forest.php?op=mitmachen'>`@Machst du bei
   dem Wettstreit mit</a> `@oder <a href='forest.php?op=nichtmm'>`@lässt du es
   lieber bleiben?</a>`n`n",true);
   $_SESSION['tmp']['runde']=0;
   addnav("","forest.php?op=mitmachen");
   addnav("","forest.php?op=nichtmm");
   addnav("Mitmachen","forest.php?op=mitmachen");
   addnav("Nicht mitmachen","forest.php?op=nichtmm");
   $session[user][specialinc] = "eierlauf.php";
}

if ($_GET[op]=="mitmachen"){
   output("`n`n `@Nun gehst du näher an die Hasengruppe heran und sagst wie
   selbstverständlich: \"`&Na klar, mache ich mit. Wie soll der Wettstreit
   ablaufen?`@\" `&Der kleinere Hase erklärt dir kurz die Regeln: \"Jeder der
   beiden Teilnehmer nimmt einen Löffel in eine Hand und legt ein rohes
   Ei darauf. Dann laufen beide bis zu der Eiche dort hinten, umrunden sie
   und laufen dann wieder hier her zurück.`@\" Du denkst, das das nicht weiter
   schwer sein dürfte und nickst ihm zu.`n`n");
   addnav("Weiter","forest.php?op=mitmachen2");
   $session[user][specialinc] = "eierlauf.php";
}

if ($_GET[op]=="nichtmm"){
   output("`n`n`@ Du schüttelst mit deinem Kopf und antwortest ohne Umschweife:
   \"`&Nein, dafür habe ich keine Zeit. Ich hab noch viel zu tun.`@\" Der grosse
   Hase blickt dich traurig an. \"`&Wir hatten geglaubt, dass du ein ehrenwerter
   Kämpfer bist.`@\" Willst du es dir nicht doch noch mal überlegen?`n`n");
   addnav("Doch mitmachen","forest.php?op=mitmachen");
   addnav("Nicht mitmachen","forest.php?op=blubb");
   $session[user][specialinc] = "eierlauf.php";
}

if ($_GET[op]=="mitmachen2"){
   $session[user][specialinc] = "eierlauf.php";
   output("`n`n `@Er reicht dir einen Löffel und ein rohes Ei. Währenddessen
   siehst du wie der grosse Hase ebenfalls einen Löffel und ein Ei in seine
   Pfote nimmt. Du überlegst, ob das so eine gute Idee war, diesen Wettstreit
   anzunehmen. Immerhin ist der Hase bestimmt schneller als du. Ihr legt beide
   Eure Eier auf den Löffel und stellt euch an die Startposition. Der kleinere
   Hase sagt noch schnell: \"`&Bei drei, lauft ihr los. Wer zuerst wieder hier
   am Start ist, wobei das Ei noch immer heil ist, hat gewonnen.`@\"`n`n Einen
   Moment später ruft er: \"`&Eins, zwei, drei!`@\" Du fängst an zu laufen, der
   grosse Hase ebenfalls.`n`n");
   addnav("Los laufen","forest.php?op=laufen");
}

if ($_GET[op]=="nichtmm2"){
   $verlust1=e_rand(1,3);
   $verlust2=($verlust1*4);
   output("`n`n `@Du bist dir sicher, dass du nicht machen möchtest und
   gehst lieber von der Hasengruppe weg. Du hörst die Hasen noch im
   Weggehen etwas sagen, aber was sie genau sagen verstehst du jedoch
   nicht. Als du endlich weit genug von den Hasen entfernt bist, stellst
   du fest, dass sich irgendetwas an dir verändert hat. Nur was?`n`n");
   $session['user']['charm']-=$verlust2;
   $session[user][specialinc] = "";
   $_SESSION['tmp']['runde']=0;
   addnav("Zurück zum Wald","forest.php");
}

if ($_GET[op]=="laufen"){
   $_SESSION['tmp']['runde']++;
   output("`n`n `@Der Hase ist bereits mit einem kleinen Vorsprung vor dir,
   doch du holst sofort wieder auf. Kaum bist du auf gleicher Höhe mit
   dem Hasen, ");
   $zufall=e_rand(1,10);
   switch($zufall) {
           case 1:
           case 2:
           case 3:
                   output("`@da fällt dir dein Ei vom Löffel runter und zerbricht mit einem
                   \"knacks\" auf einem Stein. Dir bleibt nichts anderes übrig, als zurück
                   zum Start zu gehen und dort auf den Hasen zu warten. Du hast leider
                   verloren. Vielleicht gewinnst du das nächste Mal.`n`n");
                   $session[user][specialinc] = "";
                   $_SESSION['tmp']['runde']=0;
                   addnav("Zurück zum Wald","forest.php");
           break;
           case 4:
           case 5:
           case 6:
           case 7:
           case 8:
                   output("`@blickt der Hase zu dir herüber. Dadurch verliert er an Geschwindigkeit
                   und du bist zuerst an der Eiche. Du umrundest sie und schaffst es gerade noch
                   so, vor dem Hasen wieder am Start zu sein. Du hast gewonnen.`n`n");
                   $session[user][specialinc] = "eierlauf.php";
                   addnav("Siegerehrung","forest.php?op=ehrung");
           break;
           case 9:
           case 10:
                   output("`@weicht der Hase dir nicht mehr von der Seite. Egal wie schnell du auch
                   läufst, der Hase bleibt mit dir auf gleicher Höhe. So kommt ihr beide an der
                   Eiche an und umrundet gleichzeitig die Eiche. Ebenso kommt ihr auch beide
                   gleichzeitig am Start wieder an. Du hast weder verloren, noch gewonnen.
                   Der Hase sagt zu dir: \"`&Nochmal?`@\"`n`n
                   <a href='forest.php?op=mitmachen3'>`&Ja, klar. Noch einmal!</a>`n`n
                   <a href='forest.php?op=ende'>`&Nein, ich mag nicht mehr.</a>`n`n",true);
                   addnav("","forest.php?op=mitmachen3");
                   addnav("","forest.php?op=ende");
                   addnav("Ja, nochmal","forest.php?op=mitmachen3");
                   addnav("Nein, danke","forest.php?op=ende");
                   $session[user][specialinc] = "eierlauf.php";
           break;
   }
}

if ($_GET[op]=="mitmachen3"){
   if ($_SESSION['tmp']['runde'] < 3) {
   output("`n`n `@Ihr stellt euch wieder an den Start und der kleine Hase ruft
   erneut: \"`&Eins, zwei, drei.`@\" Bei drei lauft ihr wieder gemeinsam los.`n`n");
   // $_SESSION['tmp']['runde']=0;
   $session[user][specialinc] = "eierlauf.php";
   addnav("Los laufen","forest.php?op=laufen");
   } else {
   output("`@Der Hase blickt dich traurig an. \"`&Dreimal am Tag reicht, wir wollen
   es doch nicht übertreiben.`@\" Mit diesen Worten, gehen die Hasen zurück in den
   Wald und du stehst verlassen und allein in der Wiese. Dir bleibt nichts anderes
   übrig und gehst wieder zurück zum kämpfen.");
   $session[user][specialinc] = "";
   $_SESSION['tmp']['runde']=0;
   addnav("Zurück zum Wald","forest.php");
   }
}
if ($_GET[op]=="ehrung"){
   output("`n`n `b`^Du hast gewonnen!`b`n`n
   `@Alle Hasen tanzen um dich herum und der grosse Hase hält dir einen kleinen Beutel
   hin, der, als du ihn entgegen nimmst, nicht gerade leicht ist. Du blickst hinein
   und stellst fest, dass du 1000 Gold gewonnen hast. Du bedankst dich bei den Hasen
   und gehst wieder zurück zum Wald. Als du die Hasen bereits einige Meter
   hinter dir gelassen hast, stellst du noch etwas fest: Der Sieg hat dir auch
   ein etwas mehr Charme gebracht.`n`n");
   $session['user']['gold']+=1000;
   $session['user']['charm']+=2;
   addnews($session[user][name]." `@hat `&bei `^einem `@Eierlauf `&gegen `^einen `@Hasen, `&1000 `^Gold`@
   gewonnen!");
   $session[user][specialinc] = "";
   $_SESSION['tmp']['runde']=0;
   addnav("Zurück zum Wald","forest.php");
}

if ($_GET[op]=="ende"){
        $session[user][specialinc] = "eierlauf.php";
        $_SESSION['tmp']['runde']=0;
        output("`@Du flüchtest vor dem Hasen und kehrst zurück zum Wald.`n");
        addnav("Zurück");
        addnav("zum Wald","forest.php");
}
if ($_GET[op]=="blubb"){
   output("`n`n`@Der Hase schaut dich mit funkelnden Augen an und knurrt  \"`&Dann geh doch weg. Wir spielen auch ohne
   dich weiter!`@\" Das lässt du dir nicht zwei mal sagen. So nimmst du deine Beine in die Hand um schnellstens von diesem
   verrückten Geviehchs weg zu kommen.");
   addnav("Blos weg hier","forest.php");
 }

$copyright ="<div align='center'><a href=http://www.logd-midgar.de/logd/ target='_blank'>&copy;`^Mi`&dg`@ar`0</a></div>";
output("`n`n`n`n$copyright`n ",true);

?>

