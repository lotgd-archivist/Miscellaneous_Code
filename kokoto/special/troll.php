<?php 
/*
Idee von Tidus und Lori
Umsetzung Lori und Tidus (www.kokoto.de)
Man darf dieses Special nur verwenden, wenn der Großteil der Source wie auch dieses Special selbst sichtbar sind.

Das Copyright darf nicht entfernt werden, bei der Suche nach Specials in Sourcen anderer Server ist kopieren strengstens erwünscht oder die Orginalversion bei anpera.net herunterladen (http://anpera.homeip.net/phpbb3/viewtopic.php?f=43&t=5169). Ergänzungen und Werte anpassen etc. gerne, aber Copyright muss auch dann erhalten bleiben! Bei Änderungen oder Ergänzungen, sowie Bugs und Anregungen steht es euch frei und wäre es nett, wenn ihr in dem Thread auf anpera.net euren Vorschlag einbringt, für uns und andere die daran Interesse haben könnten.
Gegenstück zu: spinne.php
*/

if (!isset($session)) exit();

output("`n`n`^`c`bTroll`b`c`0`n`n");

if ($_GET['op']=='flechten'){
		output('Leise schleichst du zum Kopf des Trolls und beginnst in aller Seelenruhe kleine Zöpfchen zu flechten. Zum Glück oder Unglück des Trolls findest du noch ein paar bunte Schleifen in deiner Tasche. geschwind werden die noch an den Zöpfchen befestigt. nach getaner Arbeit betrachtest du dein Werk und beginnst leise zu kichern. Durch dein Lachen wacht der Troll auf und staunt nicht schlecht, eine lachenden Wurm vor sich zu sehen. Durch deinen Handzeig kommt er, trotz begrenztem IQ, schnell darauf, dass du ihm diese Zopfpracht verpasst hast. Du bist schon der Meinung, dass dein Kopf gleich etwas matschiger ist, ');
		if ($session['user']['sex'] == 1){
			output('doch irgendwie erscheint ein Lächeln auf dem Gesicht des Trolls. Mit einer etwas höheren Stimme, scheinbar ein Trollmädchen, bedankt sich diese bei dir und steckt dir ein paar Goldmünzen zu. Verwundert bedankst du dich und setzt deinen Weg fort.');
			$gold = mt_rand(50,100);
			addnews('`v'.$session['user']['name'].' `vbekam Münzen für eine Frisierstunde bei einem Trollmädchen.`0');
			}else{
			output('womit du auch gar nicht so falsch liegst. Mit einem donnernden Schlag trifft die Keule des Trolls deinen Kopf. Laut schimpft er noch etwas, bevor deine Sinne schwinden. ');
			if (mt_rand(1,5) == 3)
				{
				output('Nachdem du aus deiner Ohnmacht erwacht bist, rappelst du dich langsam auf. Dabei fallen dir ein paar Goldstücke zwischen den Schleifen auf. Die muss wohl der Troll in seiner Rage verloren haben, als er die Zöpfe wieder aufgemacht hat. Nur schnell weg von hier, ehe er wiederkommt - beschließt du, während du die Münzen aufsammest. Schnell bist du auch schon wieder im Wald verschwunden.');
				$gold = mt_rand(1,10);
				addnews('`v'.$session['user']['name'].' `vbekam Münzen und eine Tracht Prügel für eine Frisierstunde bei einem Troll.`0');
			}else{
				output('Nachdem du aus deiner Ohnmacht erwacht bist, rappelst du dich langsam auf. Nur schnell weg von hier, ehe er wiederkommt - beschließt du. Schnell bist du auch schon wieder im Wald verschwunden.');
				$gold = 0;
				addnews('`v'.$session['user']['name'].' `vbekam eine Tracht Prügel für eine Frisierstunde bei einem Troll.`0');
				}
			}
		$session['user']['gold'] += $gold;
		$session['user']['turns'] --;
		$session['user']['specialinc'] = 'troll.php';
		addnav('In den Wald','forest.php?op=wald');
	
}elseif ($_GET['op']=='abhauen'){
   		output('`nDir ist die ganze Sache doch nicht ganz geheuer, also nimmst du lieber deine Beine in die Hand und rennst, was das Zeug hält. Leider hast du keine Ahnung wohin und verbrauchst die Zeit, die du auch für einen Kampf hättest aufbringen können.');
		$session['user']['turns'] --;
		$session['user']['specialinc'] = '';
}else if ($_GET['op']=='wald'){
		output('`nNoch immer den Kopf schüttelnd verschwindest du hinter einem Busch. Deine Langeweile ist vorbei, nun wird es wieder Zeit für einen richtigen Kampf.');
		$session['user']['specialinc'] = '';


}else{

		output('Bei deinen Streifzügen durch den Wald wird dir langsam langweilig. Nach einiger Zeit kommst du auf eine dunkle Lichtung. Dort findest du einen schlafenden Troll. Dir sitzt der Schalk im Nacken, also stellst du dir vor, wie der Troll wohl aussehen würde, wenn du ihm kleine Zöpfe flechtest. Da er sehr tief schläft könntest du die Gelegenheit wahrnehmen und dies ausprobieren.');
		addnav('Zöpfe flechten','forest.php?op=flechten');
		addnav('Abhauen','forest.php?op=abhauen');
		$session['user']['specialinc'] = 'troll.php';
		
}

?>
