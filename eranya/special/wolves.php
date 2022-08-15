
<?php

// Knappen können nützlich sein und eine Stufe aufsteigen... oder auch sterben
// By Maris (Maraxxus@gmx.de)

if (!isset($session))
{
    exit();
}

$specialinc_file = "wolves.php";
require_once(LIB_PATH.'disciples.lib.php');

if ($_GET['op'] == "askforhelp")
{
    $sql = "SELECT name,sex FROM disciples WHERE master=".$session['user']['acctid']."";
    $result = db_query($sql) or die(db_error(LINK));
    $rowk = db_fetch_assoc($result);
    $ksex = $rowk['sex'];
    
    output("`n`#Du rufst nach deine".($ksex == 1 ? "r Knappin" : "m Knappen").", und ".$rowk['name']." `#zieht ".($ksex == 1 ? "ihr" : "sein")." Schwert und stellt sich damit
            schützend vor dich.`n
            `#Dann treten plötzlich zwei hochgewachsene Räuber hinter einem Busch hervor... und als der Größte von ihnen das Schwert in ".$rowk['name']."`#s Hand erblickt, zieht er seine
            eigene Waffe und stürzt sich auf deine".($ksex == 1 ? " Knappin" : "n Knappen").".`n`n");
    $session['user']['specialinc'] = "";
    switch (e_rand(1,4))
    {
    case 1 :
    case 2 :
    case 3 :
        output("`#Schon der erste Schlag des Mannes schickt dein".($ksex == 1 ? "e Knappin" : "en Knappen")." zu Boden, und du befürchtest schon das Schlimmste... Doch dann fliegt plötzlich
                etwas durch die Luft und trifft den Mann an der Stirn. Ein Stein. Benommen taumelt der Räuber zurück und reißt im Fallen seinen Begleiter um. Dies sorgt für ausreichend
                Verwirrung, sodass ".$rowk['name']."`# dich aus der Falle befreien und mit dir zusammen das Weite suchen kann.`n`n
                `4Du hast Lebenspunkte eingebüßt.`n
                `@Dein".($ksex == 1 ? "e Knappin" : " Knappe")." steigt durch diese besondere Erfahrung im Kampf eine Stufe auf!`0`n");
        $session['user']['hitpoints'] *= 0.8;
        disciple_levelup();
        
        addnav("Zurück zum Wald","forest.php");
        break;
    case 4 :
        output("`#".($ksex == 1 ? "Deine Begleiterin" : "Dein Begleiter")." hat keine Chance, und du kannst nichts für ".($ksex == 1 ? "sie" : "ihn")." tun.`n
                Der Räuber bringt ".$rowk['name']." `#erst zu Fall und schlägt ".($ksex == 1 ? "sie" : "ihn")." dann bewusstlos. Machtlos siehst du zu, wie der Mann sich
                ".$rowk['name']." `#über die Schulter wirft und sich dann zum Gehen wendet. Der andere Räuber grinst boshaft in deine Richtung, dann folgt er seinem Kumpanen.`n`n
                Du brauchst eine halbe Ewigkeit, um dich zu befreien.`n`n
                `4Du hast fast alle deine Lebenspunkte und 3 Waldkämpfe verloren. Dein".($ksex == 1 ? "e Knappin" : " Knappe")." wurde verschleppt!`0`n");
        $session['user']['hitpoints']=1;
        $session['user']['turns']-=3;
        if ($session['user']['turns']<0)
        {
            $session['user']['turns']=0;
        }
        
        $sql="UPDATE disciples SET state=0 WHERE master=".$session['user']['acctid'];
        db_query($sql);
        
        $sql = "UPDATE account_extra_info SET disciples_spoiled=disciples_spoiled+1 WHERE acctid = ".$session['user']['acctid'];
        db_query($sql);
        
        unset($session['bufflist']['decbuff']);
        
        debuglog("Knappenverlust beim Räuber-Event im Wald.");
        break;
        
    }
}
else if ($_GET['op'] == "sendaway")
{
    $sql = "SELECT name,state,level FROM disciples WHERE master=".$session['user']['acctid']."";
    $result = db_query($sql) or die(db_error(LINK));
    $rowk = db_fetch_assoc($result);
    output("`n`#Du rufst laut: \"`5".$rowk['name']."`5, lauf! Hol Hilfe!`#\"`n");
    output("Dein".($ksex == 1 ? "e Knappin" : " Knappe")." läuft, so schnell ".($ksex == 1 ? "sie" : "er")." kann, fort.`n`n");
    switch (e_rand(1,4))
    {
    case 1 :
    case 2 :
    case 3 :
        output("`#Kaum ist ".($ksex == 1 ? "sie" : "er")." aus deinem Sichtfeld verschwunden, da treten plötzlich fünf hochgewachsene Räuber hinter einem Busch hervor. Zuerst wirken sie
                überrascht, doch dann knurrt einer etwas zu seinen Begleitern und zieht seine Waffe, ehe er sich dir nähert. Du hast gerade noch genug Zeit, um selbst nach deiner Waffe zu
                greifen, da musst du auch schon den ersten Schlag parieren. Als auch der zweite und dritte Schlag von dir abgewehrt werden, ziehen die anderen Männer ebenfalls ihre Waffen
                und verteilen sich um dich herum.`n
                Du machst gedanklich schon deinen Frieden mit dieser Welt, da kommt plötzlich Bewegung ins umliegende Gebüsch. Die Räuber wechseln kurze Blicke, doch dann entscheiden sie
                sich für den Rückzug, als `^".$rowk['name']."`# mit einer größeren Gruppe Feldarbeiter erscheint.`n
                Die kräftigen Männer helfen dir, dich von der Falle zu befreien, und stützen dich auf deinem Weg fort von hier.`n
                `4Du hast fast alle deine Lebenspunkte und 3 Waldkämpfe verloren!`0`n");
        $session['user']['hitpoints']=1;
        $session['user']['turns']-=3;
        if ($session['user']['turns']<0)
        {
            $session['user']['turns']=0;
        }
        $session['user']['specialinc'] = "";
        addnav("Zurück zum Wald","forest.php");
        break;
    case 4 :
        output("`#Doch als ".($ksex == 1 ? "sie" : "er")." mit Hilfe wiederkehrt, findet ".($ksex == 1 ? "er" : "sie")." nur noch deine Leiche bei der Falle wieder.`n`4Du bist tot!`0`n");
        $session['user']['hitpoints']=0;
        $session['user']['specialinc'] = "";
        addnews($session['user']['name']."`@ wurde im Wald von Räubern ermordet!");
        addnav("Weiter","shades.php");
        break;
    }
}
else
{
    output("`n
            `#Du gehst ahnungslos deines Weges, als du plötzlich ein lautes, peitschenähnliches Geräusch direkt unter dir vernimmst. Sofort steigt ein brennender Schmerz dein Bein hinauf und
            deine Knie knicken weg. Halb bewusstlos vor Schmerz erkennst du die scharf gezackten Bügel einer unter Blättern verborgenen, großen Wildfalle, in die du gerade gelaufen bist.`0`n`n");
    
    $sql = "SELECT name,state,level FROM disciples WHERE state>0 AND at_home=0 AND master=".$session['user']['acctid']."";
    $result = db_query($sql) or die(db_error(LINK));
    
    if (db_num_rows($result)>0) {
        $rowk = db_fetch_assoc($result);
    }
    
    if (($rowk['state']>0) || (db_num_rows($result)>0))
    {
        output("`#Zu allem Übel vernimmst du auch schon die Stimmen mehrerer Männer, die stetig lauter werden. In wessen Falle bist du da bloß geraten?`n
                Aber zum Glück ist ja dein".($ksex == 1 ? "e Knappin" : " Knappe")." `^".$rowk['name']."`# in deiner Nähe.`n
                Obwohl ".($ksex == 1 ? "sie" : "er")." fast noch ein Kind ist, könnte ".($ksex == 1 ? "sie" : "er")." dich aus dieser Notlage befreien, allerdings würdest du
                dadurch riskieren, dass ".($ksex == 1 ? "sie" : "er")." ebenfalls in Gefahr gerät. Die Gruppe von Männern hat euch schon fast erreicht...`n`n
                Was tust du?`0");

        $session['user']['specialinc'] = $specialinc_file;
        addnav("r?".$rowk['name']." `0rufen","forest.php?op=askforhelp");
        addnav("f?".$rowk['name']." `0fortschicken","forest.php?op=sendaway");
    }
    else
    {
        output("`#Es dauert eine halbe Ewigkeit, bis du dich aus der Falle befreit hast.`n
                Du greifst dir einen langen Stock als Stütze und humpelst davon.`n
                `4Du hast fast alle deine Lebenspunkte und 3 Waldkämpfe verloren!`0`n");
        $session['user']['hitpoints']=1;
        $session['user']['turns']-=3;
        if ($session['user']['turns']<0)
        {
            $session['user']['turns']=0;
        }
        addnav("Zurück in den Wald","forest.php");
    }
}
?>

