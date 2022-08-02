<?php

// idea with ape by manweru
// coding by anpera
if ($_GET['op']=='' || $_GET['op']=='search'){
switch(mt_rand(1,3)){
	case 1:
	output('`^Als du so durch den Wald schlenderst findest einen Edelstein! du wusstest schon am frühen Morgen als du ein Goldstück gefunden hast das das Glück heute wohl auf deiner Seite ist!`0');
	$session['user']['gems']++;
	//debuglog("found 1 gem in the forest");
	break;
	case 2:
	output('`^Du hörst ein lautes Kreischen und spürst einen leichten Ruck in der Nähe deiner Edelsteinsammlung. Irritiert schaust du dich um,');
	if ($session['user']['gems']>0){
		$session['user']['gems']--;
		//debuglog("lost 1 gem in the forest");
		output(' kurz darauf siehst du einen Affen mit einem deiner Edelsteine im Wald verschwinden. Du Rennst ihm noch hinterher und versuchst es zu kriegen aber es ist einfach zu schnell.`0');
	}else{
		output(' Glücklicherweise hast du keine Edelsteine dabei und machst dir darum auch keine Sorgen wegen dem Äffchen, das scheinbar enttäuscht zurück in den Wald läuft. Es tut dir schon irgendwie Leid aber Edelsteine sind eben teuer..`0');
	}
	break;
	case 3:
	output('`^Ein kleines Äffchen wirft dir einen Edelstein an den Kopf und verschwindet im Wald. Du ärgerst dich denn du denkst er wirft mit einem Stein nach dir dann bemerkst du das es ein Edelstein ist. Du verlierst ein paar Lebenspunkte, aber der Edelstein lässt dich den Ärger darüber vergessen.`0');
	$session['user']['gems']++;
	//debuglog("found 1 gem in the forest");
	$session['user']['hitpoints']*=0.9;
	break;
}}
?>