<?php
if (!isset($session)) exit();
$session[user][specialinc]="skillmaster.php";
//FÄHIGKEITENMOD BY ANGEL for www.pandea-island.de
/*
switch((int)$session[user][specialty]){
case 1:
        `&="`$";
        break;
case 2:
        `&="`%";
        break;
case 3:
        `&="`^";
        break;
default:
        output("Du wanderst plan- und ziellos durchs Leben. Du solltest eine Rast machen und einige wichtige Entscheidungen für dein weiteres Leben treffen.");
        $session[user][specialinc]="";
        //addnav("Return to the forest", "forest.php");
}
$skills = array(1=>"Dunkle Künste","Mystische Kräfte","Diebeskunst");
*/

if ($_GET[op]=="give"){
        if ($session[user][gems]>0){
                output("`& Du gibst `@Foil`&wench`& einen Edelstein und sie überreicht dir einen Zettel aus Pergament mit Anweisungen, wie du deine Fertigkeiten steigern kannst.`n`n");
                output("Du studierst den Zettel, zerreisst ihn und futterst ihn auf, damit Ungläubige nicht an die Information gelangen können. `n`n`@Foil`&wench`& seufzt... \"`&Du hättest ihn nicht ");
                output("zu essen brauchen... Naja, jetzt verschwinde von hier!`&\"`#");
                increment_specialty();
                $session[user][gems]--;
                //debuglog("gave 1 gem to Foilwench");
        }else{
                output("`& Du überreichst deinen imaginären Edelstein. `@Foil`&wench`& starrt dich verdutzt an. \"`&Komm wieder, wenn du einen `bechten`b Edelstein hast, du Dummkopf.`&\"`n`n");
                output("\"`#Dummkopf?`&\"`n`n");
                output("Damit schmeisst `@Foil`&wench`& dich endgültig raus.");
        }
        $session[user][specialinc]="";
        //addnav("Zurück zum Wald", "forest.php");
}else if($_GET[op]=="dont"){
        output("`& Du informierst `@Foil`&wench`& darüber, dass sie sich ihren Reichtum selbst verdienen sollte. Dann stapfst du davon.");
        $session[user][specialinc]="";
        //addnav("Return to the forest", "forest.php");
}else if ($session[user][skill]==0){
        output("Du wanderst plan- und ziellos durchs Leben. Du solltest eine Rast machen und einige wichtige Entscheidungen für dein weiteres Leben treffen.");
        $session[user][specialinc]="";
}else if($session[user][skill]>0){
        $s="SELECT color, name FROM skills WHERE id=".$session[user][skill]."";
        $r=db_query($s);
        $row = db_fetch_assoc($r);
        output("`& Auf deinen Streifzügen durch den Wald auf Jagd nach Beute stösst du auf eine kleine Hütte. Du gehst hinein und wirst vom grauen Gesicht einer kampferprobten alten Frau empfangen: ");
        output("\"`&Sei gegrüsst, ".$session[user][name].", ich bin `@Foil`&wench`&, Meister in allem.`&\"`n`n\"`#Meister in allem?`&\" fragst du nach.`n`n");
        output("\"`&Ja, Meister in Allem. Es liegt in meiner Macht, alle Fähigkeiten zu kontrollieren und zu lehren.`&\"`n`n\"`#Und zu lehren?`&\" fragst du sie.`n`n");
        output("Die alte Frau seufzt: \"`&Ja, und zu lehren.  Ich werde dir zeigen, wie du deine $row[color]$row[name]`& steigern kannst - unter zwei Bedingungen.`&\"`n`n");
        output("\"`#Zwei Bedingungen?`&\" wiederholst du fragend.`n`n");
        output("\"`&Ja. Zuerst musst du mir einen Edelstein geben und dann musst du aufhören, alles was ich sage als Frage zu wiederholen!`&\"`n`n");
        output("\"`#Ein Edelstein!`&\" sagst du bestimmt.`n`n");
        output("\"`&Nun ... ich glaube das war keine Frage. Und wie sieht es mit dem Edelstein aus?`&\"");
        addnav("Gib ihr einen Edelstein","forest.php?op=give");
        addnav("Gib ihr keinen Edelstein","forest.php?op=dont");
}
?> 