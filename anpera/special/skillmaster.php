
ï»¿<?php



// 20060323



if (!isset($session)) exit();



$session['user']['specialinc']="skillmaster.php";

$skillcol = array(1=>"`\$",2=>"`%",3=>"`^",4=>"`q",5=>"`#",6=>"`@");

$c=$skillcol[$session['user']['specialty']];

if ($c==""){

    output("Du wanderst plan- und ziellos durchs Leben. Du solltest eine Rast machen und einige wichtige Entscheidungen fÃ¼r dein weiteres Leben treffen.");

    $session['user']['specialinc']="";

}



if ($_GET['op']=="give"){

    if ($session['user']['gems']>0){

        output("$c Du gibst `@Foil`&wench$c einen Edelstein und sie Ã¼berreicht dir einen Zettel aus Pergament mit Anweisungen, wie du deine Fertigkeiten steigern kannst.`n`n");

        output("Du studierst den Zettel, zerreisst ihn und futterst ihn auf, damit UnglÃ¤ubige nicht an die Information gelangen kÃ¶nnen. `n`n`@Foil`&wench$c seufzt... \"`&Du hÃ¤ttest ihn nicht ");

        output("zu essen brauchen... Naja, jetzt verschwinde von hier!$c\"`#");

        increment_specialty();

        $session['user']['gems']--;

    }else{

        output("$c Du Ã¼berreichst deinen imaginÃ¤ren Edelstein. `@Foil`&wench$c starrt dich verdutzt an. \"`&Komm wieder, wenn du einen `bechten`b Edelstein hast, du Dummkopf.$c\"`n`n");

        output("\"`#Dummkopf?$c\"`n`n");

        output("Damit schmeisst `@Foil`&wench$c dich endgÃ¼ltig raus.");

    }

    $session['user']['specialinc']="";



}else if($_GET['op']=="dont"){

    output("$c Du informierst `@Foil`&wench$c darÃ¼ber, dass sie sich ihren Reichtum selbst verdienen sollte. Dann stampfst du davon.");

    $session['user']['specialinc']="";



}else if($session['user']['specialty']>0){

    output("$c Auf deinen StreifzÃ¼gen durch den Wald auf Jagd nach Beute stÃ¶sst du auf eine kleine HÃ¼tte. Du gehst hinein und wirst vom grauen Gesicht einer kampferprobten alten Frau empfangen: ");

    output("\"`&Sei gegrÃ¼sst, ".$session['user']['name']."`&, ich bin `@Foil`&wench$c, Meister in allem.$c\"`n`n\"`#Meister in allem?$c\" fragst du nach.`n`n");

    output("\"`&Ja, Meister in Allem. Es liegt in meiner Macht, alle FÃ¤higkeiten zu kontrollieren und zu lehren.$c\"`n`n\"`#Und zu lehren?$c\" fragst du sie.`n`n");

    output("Die alte Frau seufzt: \"`&Ja, und zu lehren.  Ich werde dir zeigen, wie du deine ".$specialty[$session['user']['specialty']]." steigern kannst - unter zwei Bedingungen.$c\"`n`n");

    output("\"`#Zwei Bedingungen?$c\" wiederholst du fragend.`n`n");

    output("\"`&Ja. Zuerst musst du mir einen Edelstein geben und dann musst du aufhÃ¶ren, alles was ich sage als Frage zu wiederholen!$c\"`n`n");

    output("\"`#Ein Edelstein!$c\" sagst du bestimmt.`n`n");

    output("\"`&Nun ... ich glaube das war keine Frage. Und wie sieht es mit dem Edelstein aus?$c\"");

    addnav("Gib ihr einen Edelstein","forest.php?op=give");

    addnav("Gib ihr keinen Edelstein","forest.php?op=dont");

}

?>

