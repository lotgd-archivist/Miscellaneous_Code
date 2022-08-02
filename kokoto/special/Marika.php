<?php

//Wald
//by Magier12
// überarbeitet von Tidus www.kokoto.de
if ($_GET['op']=='a'){
	output('Du stehst nun auf dem ehemaligen Dorfplatz und kannst dich nun entscheiden was du machen willst. Was machst du als nächstes?');
	addnav('Badehaus anschauen','forest.php?op=b');
	addnav('Schatzkammer anschauen','forest.php?op=c');
	addnav('Curie ansehen','forest.php?op=d');
	addnav('Marika verlassen','forest.php?op=e');
	$session['user']['specialinc']='Marika.php';

} elseif ($_GET['op']=='b'){
	output('Du betrittst das Badehaus und siehst sofort die Pracht die es ausstrahlt. Du schätz es auf 20 m lang und 15 m breit und in der Mitte steht eine riesige Statue von Poseidon, dem Schutzgott der Atlanter. Als du ins Wasser langst merkst du das es noch wamr ist. Was wirst du tun?');
	addnav('Das Blattgold von den Wänden kratzen','forest.php?op=f');
	addnav('Das Badehaus und Marika verlassen','forest.php?op=e');
        $session['user']['specialinc']='Marika.php';
        
} elseif ($_GET['op']=='c'){
	output('Du betrittst die riesige Schatzkammer und merkst,das niemand mit dem Reichtum von Marika übertrieben hat. Du schätz die Schatzkammer auf 25 m lang und 10 m breit und in der Mitte steht eine riesige Statue von Poseidon, dem Schutzgott der Atlanter. An den Wänden ist lauter Gold und am Boden liegen Goldmünzen. Was wirst du tun?');
	addnav('Das Blattgold von den Wänden kratzen','forest.php?op=f');
	addnav('Gold vom Boden nehmen','forest.php?op=h');
	addnav('Die Schatzkammer und Marika verlassen','forest.php?op=e');
        $session['user']['specialinc']='Marika.php';
        

} elseif ($_GET['op']=='d'){
	output('Du betrittst die runde Curie und merkst,das hier nicht von dem sonstigem Reichtum von Marika ist. Die Curie ist ein kalter, runder Raum mit Marmor Wänden. Es gibt nur einen richtig schönen Stuhl, den des Senatsvorsitzenden und in der Mitte steht eine riesige Statue von Poseidon, dem Schutzgott der Atlanter.  Was wirst du tun?');
	addnav('Dich auf den Stuhl setzen und träumen','forest.php?op=i');
	addnav('Die Curie und Marika verlassen','forest.php?op=e');
        $session['user']['specialinc']='Marika.php';
        
} elseif ($_GET['op']=='e'){
	output('Du beschliesst Marika zu verlassen weil dir das alles komisch vorkommt. Kaum bist du den nächsten Hügel hochgesticken und blickst zurück siehst du dass ganz Marika unter einer riesigen Welle begraben wird. Durch diese Lektion hast du viel Erfahrung gewonnen.');
	$session['user']['experience']*=1.05;
	addnav('Weitergehen','forest.php');
	addnews("`3".$session['user']['name']." `3 hat die Überflutung Marika überlebt`3.");
        $session['user']['specialinc']='';

} elseif ($_GET['op']=='f'){
$session['user']['specialinc']='';
if (mt_rand(1,3)==1){
	output('Du holst deine Waffe aus ihrem Futteraal und machst dich ans Werk das Gold von der Wand zu schaben. Auf einmal hörst du eine Stimme: Du bist ein Schänder Marikas. Diesmal lasse ich dich ziehn, doch nächstesmal wirst du nicht so leicht davon kommen...`nDu rennst so schnell du kannst vor diesem Ort weg ohne etwas von dem Blattgold mitzunehmen.');
}else{
	output('Du holst deine Waffe aus ihrem Futteraal und machst dich ans Werk das Gold von der Wand zu schaben. Auf einmal hörst du eine Stimme: Du bist ein Schänder Marikas. Zur Strafe musst du Sterben. Aufeinmal hörst du ein lautes Poltern und im nächsten Moment wirst du von einer riesigen Wasserwelle umgeworfen und ertrinkst. Du bist tot');
	addnews("`3".$session['user']['name']." `3wurde auf einer Erkundungstour in Marika ertränkt`3.");
	$session['user']['alive']=false;
   	$session['user']['hitpoints']=0;
    	addnav('Tägliche News','news.php');
}
} elseif ($_GET['op']=='h'){
$session['user']['specialinc']='';
if (mt_rand(1,3)==1){
$gold = $session['user']['level']250;
	output('Du nimmst etwas Gold vom Boden und verlässt Marika so schnell wie du kannst. Aber da du dich so beerilt hast sind es nur: `^'.$gold.' Goldmünzen.');
	$session['user']['gold']+=$gold;
	addnav('Weiter','forest.php');
}else{
	output('Während du fleißig einen kleinen teil des Goldes auf einen eigenen Haufen packst um ihn dann in deine Tasche zu stechen... hörst du eine Stimme: Du bist ein Schänder Marikas. Zur Strafe musst du Sterben. Aufeinmal hörst du ein lautes Poltern und im nächsten Moment wirst du von einer riesigen Wasserwelle umgeworfen und ertrinkst. Du bist tot');
	addnews("`3".$session['user']['name']." `3wurde auf einer Erkundungstour in Marika ertränkt`3.");
	$session['user']['alive']=false;
   	$session['user']['hitpoints']=0;
    	addnav('Tägliche News','news.php');
}
} elseif ($_GET['op']=='i'){
$session['user']['specialinc']='';
	output('Du setzt dich auf den Stuhl und träumst das du früher hier gesessen hast und die versammlungen abgehalten hast. Auf einmal fühlst du dich ganz leicht und wirst auf einmal in den Himmel zu den alten Senatoren emporgehoben. Du bist zwar jetzt tot, aber du hast Erfahrung gewonnen.');
        $session['user']['specialinc']='';
	addnews("`3".$session['user']['name']." `3hatte auf einer Erkundungstour in Marika zuviel geträumt und ist zu den Senatoren empor gefahren`3.");
	$session['user']['alive']=false;
   	$session['user']['hitpoints']=0;
	$session['user']['experience']*=1.1;
    	addnav('Tägliche News','news.php');

}else{
	output('Du gehst durch den Wald und merkst aufgrund der Feuchtigkeit des Bodens das hier vor nicht all zu langer zeit viel wasser war. DU frägst dich wo es wohl hingverschwunden ist. ALs du deinen Blick in die Ferne gleiten lässt entdeckst du eine verlassene Stadt. Im Laufschritt näherst du dich der Stadt. Als du davor ankommst siehst du einen Mann, der wie Poseidon aussieht auf der Mauer stehen. Er ruft dir zu: Edler Held, ich gewähre dir Zutritt zur vergessenen Stadt Marika. Da du gehört hast das Marika sehr reich war, beschließt du sie zu Durchsuchen.');
	addnav('Marika durchsuchen','forest.php?op=a');
	$session['user']['specialinc']='Marika.php';

}
page_footer();
?>