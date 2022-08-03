<?php

//Wald
//by Magier12

if ($HTTP_GET_VARS['op']==""){
    output("DU gehst durch den Wald und findest auf einmal die verlassene Stadt Atlantis. Da du gehört hast das sie ziemlich reich war, beschließt du sie zu Durchsuchen.");
    addnav("Atlantis durchsuchen","forest.php?op=a");
    $session['user']['specialinc']="atlantis.php";

} elseif ($HTTP_GET_VARS['op']=="a"){
    output("Du stehst nun auf dem ehemaligen Dorfplatz und kannst dich nun entscheiden was du machen willst.");
    output("Was machst du als nächstes?");
    addnav("Badehaus anschauen","forest.php?op=b");
    addnav("Schatzkammer anschauen","forest.php?op=c");
    addnav("Curie ansehen","forest.php?op=d");
    addnav("Atlantis verlassen","forest.php?op=e");
    $session['user']['specialinc']="atlantis.php";

} elseif ($HTTP_GET_VARS['op']=="b"){
    output("Du betrittst das Badehaus und siehst sofort die Pracht die es ausstrahlt. Du schätz es auf 20 m lang und 15 m breit und in der Mitte steht eine riesige Statue von Poseidon, dem Schutzgott der Atlanter. Als du ins Wasser langst merkst du das es noch wamr ist. Was wirst du tun?");
    addnav("Das Blattgold von den Wänden kratzen","forest.php?op=f");
    addnav("Das Badehaus und Atlantis verlassen","forest.php?op=e");
    $session['user']['specialinc']="atlantis.php";    

} elseif ($HTTP_GET_VARS['op']=="c"){
    output("Du betrittst die riesige Schatzkammer und merkst,das niemand mit dem Reichtum von Atlantis übertrieben hat. Du schätz die Schatzkammer auf 25 m lang und 10 m breit und in der Mitte steht eine riesige Statue von Poseidon, dem Schutzgott der Atlanter. An den Wänden ist lauter Gold und am Boden liegen Goldmünzen. Was wirst du tun?");
    addnav("Das Blattgold von den Wänden kratzen","forest.php?op=f");
    addnav("Gold vom Boden nehmen","forest.php?op=h");
    addnav("Die Schatzkammer und Atlantis verlassen","forest.php?op=e");
    $session['user']['specialinc']="atlantis.php";    

} elseif ($HTTP_GET_VARS['op']=="d"){
    output("Du betrittst die runde Curie und merkst,das hier nicht von dem sonstigem Reichtum von Atlantis ist. Die Curie ist ein kalter, runder Raum mit Marmor Wänden. Es gibt nur einen richtig schönen Stuhl, den des Senatsvorsitzenden und in der Mitte steht eine riesige Statue von Poseidon, dem Schutzgott der Atlanter.  Was wirst du tun?");
    addnav("Dich auf den Stuhl setzen und träumen","forest.php?op=i");
    addnav("Die Curie und Atlantis verlassen","forest.php?op=e");
    $session['user']['specialinc']="atlantis.php";    

} elseif ($HTTP_GET_VARS['op']=="e"){
    output("Du beschliesst Atlantis zu verlassen weil dir das alles komisch vorkommt. Kaum bist du den nächsten Hügel hochgesticken und blickst zurück siehst du dass ganz atlantis unter einer riesigen Welle begraben wird. Durch diese Lektion hast du viel Erfahrung gewonnen.");
    $session[user][experience]*=1.2;
    addnav("Weitergehen","forest.php");
    addnews("`3".$session['user']['name']." `3 hat die Überflutung Atlantis überlebt`3.");

} elseif ($HTTP_GET_VARS['op']=="f"){
    output("Du holst deine Waffe aus ihrem Futteraal und machst dich ans Werk das Gold von der Wand zu schaben. Auf einmal hörst du eine Stimme: Du bist ein Schänder Atlantis. Zur Strafe musst du so sterben wie die Atlanter gestorben sind.");
    output("Aufeinmal hörst du ein lautes Poltern und im nächsten Moment wirst du von einer riesigen Wasserwelle umgeworfen und ertrinkst.");
    output("Du bist tot");
    addnews("`3".$session['user']['name']." `3wurde auf einer Erkundungstour in Atlantis ertränkt`3.");
    $session['user']['alive']=false;
       $session['user']['hitpoints']=0;
        addnav("Tägliche News","news.php");

} elseif ($HTTP_GET_VARS['op']=="h"){
    output("Du nimmst etwas Gold vom Boden und verlässt Atlantis so schnell wie du kannst.");
    $session['user']['gold']+=$session['user']['level']*110;
    addnav("Weiter","forest.php");

} elseif ($HTTP_GET_VARS['op']=="i"){
    output("Du setzt dich auf den Stuhl und träust das du früher hier gesessen hast und die versammlungen abgehalten hast. Auf einmal fühlst du dich ganz leicht und wirst auf einmal in den Himmel zu den alten Senatoren emporgehoben. Du bist zwar jetzt tot, aber du hast viel Erfahrung gewonnen");
    output("Du bist tot");
    addnews("`3".$session['user']['name']." `3hatte auf einer Erkundungstour in Atlantis zuviel geträumt und ist zu den Senatoren empor gefahren`3.");
    $session['user']['alive']=false;
       $session['user']['hitpoints']=0;
    $session['user']['experience']*=1.3;
        addnav("Tägliche News","news.php");

}

page_footer();
?>