<?php
// Garlant
// festungsgrotte.php - Ist ein teil eines Specials - gehört zu amulett.php
// Diese wurde aus der dragon.php heraus geschrieben.
// Ich danke Anpera.net für die Hilfe bei der Beantwortung von Fragen ;)
// Falls unerwartet Probleme vorhanden sind, bitte bei Garlant-T@web.de melden,
// oder im Forum melden. http://www.anpera.net/forum/index.php

require_once "common.php";
page_header("In der Festungsgrotte");
$sql = "SELECT * FROM wächter WHERE creaturelevel = ".$session['user']['level'];
$result = db_query($sql);
if (db_num_rows($result) > 0){
	$badguy = db_fetch_assoc($result);
}
if ($_GET['op']==''){
page_header("Festungsruine");
addnav('Festungsgrotte betreten','festungsgrotte.php?op=weiter');
addnav('Das ist mir zu unheimlich','village.php?op=abseits');

output('`b `4Die Festungsruine`b `n`n `$ Du läufst den Weg abseits von Kokoto als vor dir eine alte und überaus große Festungsanlage der Zwerge ist.  Du weißt, das diese Festungsanlage einst Schutz bot während der großen Kriege und auch lange zeit Schutz vor dem Drachen bot. Doch Heute ist sie nur noch eine Ruine, vom Drachen geplündert und Zerstört. Viele Geschichten über diesen Ort hast du schon gehört. Auch, das es hier einen Wächter geben soll, welcher etwas geheimnisvolles bewacht. Das ist alles nur eine Legende glaubst du für dich selbst und gehst ohne die Festungsanlage weiter zu beachten weiter.`n `n Doch als du loslaugen willst, beginnt das Amulett plötzlich wieder zu pulsieren. `n Du bleibst stehen und siehst dich sogleich um. Als du alles genau beobachtest, bemerkst du neben dir eine unauffällige stelle, die die form deines Amulettes hat.`n `n Sofort nimmst du dein Amulett ab und steckst es in das gleichförmige Loch. Ein lautes Geräusch ertönt, welches von der Bewegung der versteckten steineren Tür herkommt, die sich vor dir auftut. Nun beginnst du dich zu fragen, ob an der Legende etwas dran sei und was dich in der Grotte erwarten könnte Was wirst du nun machen? Zurück ins Dorf laufen oder mutig die Grotte betreten und dein Glück herausfordern?`0');
}else if ($_GET['op']=='weiter'){
  			if($session['user']['wächterkills'] < 1) {
				$text = " Du hast keine Ahnung was dich hier erwarten wird und blickst dich neugierung, aber mindestens genauso ehrfürchtig um. `n";
			}
			else {
				switch(e_rand(1, 2)) {
					case 1:
						$text = " Du hast das Gefühl schon einmal hier gewesen zu sein. Du sagst dir dass, das nicht sein kann, wirfst das Gefühl von dir ab gehst weiter.`n";
						break;
					case 2:
						$text = " Schemenhaft erinnerst du dich an diesen Ort. Du glaubst dies hier schon einmal in einem Traum gesehen zu haben. ...`n";
						break;
				}
			}
			output("`\$Kaum hast du den dunklen Eingang der Grotte betreten, entzündet sich eine eine in das Gestein eingelassene Halterung auf welcher eine Fackel steckt. Hastig nimmst du die Fackel aus der Halterung und beginnst dich auf den Weg ins innere zu machen.`n".$text." Nach einem kurzen Fußmarsch, findest du dich vor einer großen steinernen Tür wieder. Du brauchst nicht lange um dich dafürzu entscheiden diese zu öffnen. Schließlich, so glaubst du, wirst du hinter dieser Tür große Reichtümer finden, welche dann natürlich alle dir gehören. Leider ist dem nicht ganz so. Kurz nachdem dein Weg dich durch die Öffnung schreiten lässt, beginnt sich die Tür wieder zu schließen. `n Eine Gestalt in einer prunkvollen Rüstung erscheint vor deinen Augen und beginnt sich auf dich zu stürzen.");
				// setup the badguy
			if ($session['user']['dragonkills']>=20){
			$points = 0;
			foreach($session['user']['dragonpoints'] as $key => $val){
				if ($val=="at" || $val == "de") $points++;
			}
			$points = ceil($points0.75);
			$atkflux = e_rand(0, $points);
			$defflux = e_rand(0,$points$atkflux);
			$hpflux = ($points  ($atkflux$defflux))  5;
						
			$badguy['creaturehealth']+=$hpflux;
			$badguy['creatureattack']+=$atkflux;
			$badguy['creaturedefense']+=$defflux;
}
	$session['user']['badguy']=createstring($badguy);
	addnav('Kämpfe!','festungsgrotte.php?op=fight');
	//$battle=true; 
}else if ($_GET['op']=='wahl'){
addnav('Nimm Gold','festungsgrotte.php?op=gold');
addnav('Nimm Edelsteine','festungsgrotte.php?op=gems');
addnav('Waffe mitnehmen','festungsgrotte.php?op=waffe');
addnav('Rüstung mitnehmen','festungsgrotte.php?op=ruestung');
addnav('Buch durchlesen','festungsgrotte.php?op=buch');
output('`^ Mit letzter Kraft schleift sich der Wächter an eine Wand, um sich daran zu stützen. unmengen von Blut ist auf den Boden zu sehen und mit leter Kraft spricht er zu dir, an er Wandlehnend: `# Ihr habt mich besiegt. Nun möchte ich mit meinen letzten Worten, euch die Geschichte dieser Festung erzählen.`^ Der Wächter mach eine Kurze Pause und beginnt schwer zu atmen. Blut rinnt ihm aus dem Mund und er beginnt kurzzeitig zu husten. Dann spricht er weiter:`# Einst war diese Festungsanlage mächtig. Sie bot allen Schutz uns strotzte gen Himmel. Der Handel blühte und unsere Widersacher wurden zerschmettert. Zu dieser Zeit entstand das Amulett, das `vAmulett der Macht`# wie es von uns genannt wurde. Es sollte unsere macht und unser Wohlergehen darstellen. Doch weckte es nur Neid, Hass und Missgunst bei unseren Nachbarn. Wir mussten in den Krieg ziehen.`^ Für einem Moment ist der Wächter ruhig und spricht nicht weiter. Doch du bist so neugierig und so gespannt, das du Fragst:`2Was ist passiert? Wer hat gewonnen?!`# Niemand hat gewonnen! Viele verloren ihr Leben. Tapfere Krieger, arme Bauern sowie große Könige. Auch die Rüstungen der Geschicken Zwerge boten keinen Schutz vor dem Hass in uns selbst. Unsere Widersacher und Nachbarn waren geschlagen. Wir jedoch hassten weiter. Bald darauf kam ein bösartiges Wesen, das wir Drachen nennen. Es zerstörte die Festungsanlage und fraß jeden den er bekam. Den Rest verbrannte er. Nur sehr wenige Mächtige Magier überlebten dies und schufen diese Grotte, dessen Wächter ich bin und dessen Schlüssel du hast.Ich bitte dich darum, du musst diesen Bann brechen und aufhören zu hassen!`n`n `^Kurz bevor der Wächter dann verstirbt, spricht er mit seinen allerletzten Worten zu dir:`# Mach daraus etwas. Ich schenke euch nun was ihr begehrt, so sucht euch etwas aus.`^ Vor dir siehst du den reichen Schatz der alten Festungsanlage. Es sind Berge von Gold. Neben dir siehst du Wundervoll gearbeitete Waffen und Rüstungen der Zwerge hängen, welche den Ruf haben besonders gut gerarbeitet zu sein. Gleich daneben, steht ein Tisch auf diesem liegen einige Bücher.`0');
}else if ($_GET['op']=='gold'){
		$session['user']['gold']+=5000;
			output('Du nimmst dir so viel Gold wie du Tragen kannst von dem Berg weg.`n `^5000 Gold`0 hast du mitnehmen können!');
			savesetting("hasamulett","0");
			addnav('Grotte verlassen..','village.php?op=abseits');
		}else if ($_GET['op']=='gems'){
		$session['user']['gems']+=5;
			output('Während du dich umsiehst, stoplerst du über etwas. Ehe du dich versiehst, hast du dir`^ 5 Edelsteine`0 eingesteckt!');
			savesetting("hasamulett","0");
			addnav('Grotte verlassen..','village.php?op=abseits');
			}else if ($_GET['op']=='waffe'){
			output('Du schaust dir eine Waffe ganz genau an. Sie gefällt dir mehr als alle anderen, die da hängen und liegen. Aber sie ist schwer, sehr schwer. Doch als du damit ein paar mal ausholst bermekst du, das du dich irgendwie verbessert hast. Du hast nun `^1 Angriffspunkt`0 mehr!');
		$session['user']['weapon'] = 'Wächterlangschwert';
		$session['user']['attack']++;
		$session['user']['weapondmg']++;
			savesetting("hasamulett","0");
			addnav('Grotte verlassen..','village.php?op=abseits');
		}else if ($_GET['op']=='ruestung'){
		$session['user']['defence']++;
		$session['user']['armor'] = 'Wächterharnisch';
		$session['user']['armordef']++;
			output('Schnell findest du eine Rüstung, die dir besonders gut gefällt. Du ziehst diese gleich unter deiner Rüstung an. Nun hast du `^1 Verteidigungspunkt`0 mehr!');
			savesetting("hasamulett","0");
			addnav('Grotte verlassen..','village.php?op=abseits');
		}else if ($_GET['op']=='buch'){
				output("`0Wahlos öffnest du eines der auf dem Tisch liegenden Bücher und blätterst dieses durch.`n");
		switch (mt_rand(1,7)) {
			case 1:
			case 3:
				$exp = e_rand($session['user']['level']55, $session['user']['level']150);
				output("Du hast die alten Chroniken der Festungsanlage gefunden, welche die letzten Worte des Wächters zu ergänzen scheinen. ".
					   "Du nimmst das Wissen vergangener Tage in dir auf und erhälst {$exp} Erfahrungspunkte.`0");
				$session['user']['experience'] += $exp;
				break;
			case 2:
			case 4:
				output("Du hast ein altes Buch über die Kunst der Verführung gefunden. Ob dir dieses Wissen Heute noch etwas bringen wird?");
				$charme = mt_rand(1,4);
				$session['user']['charm'] += $charme;
				break;
			default:
			$session['user']['maxhitpoints']+=3;
			output('Du durchblätterst ein Buch der Elfen. Du erfährst dinge die man lieber nicht Wissen sollte, und die du keinem Erzählen wirst. Es werden dir `^3 Lebenspunkte`0 geschenkt!');
			break;
		}
		
			savesetting("hasamulett","0");
			addnav('Grotte verlassen..','village.php?op=abseits');
		}else if ($_GET['op']=='run'){
	output('Die steinere Tür hat sich geschlossen, du kannst nicht raus!');
	$_GET['op']='fight';
	}else if ($_GET['op']=='fight'){
	$battle=true;
	}else{
		output('Na wie bist du denn hier her gekommen?');
		addnav('zum Dorfplatz','village.php');
		debuglog('hatte eine fehlerhafte navigation in festungsruine.');
	}
		


		if ($battle){
  include("battle.php");
	if ($victory){
		$flawless = 0;
		if ($badguy['diddamage'] != 1) $flawless = 1;
		$badguy=array();
		$session['user']['badguy']='';
		$session['user']['wächterkills']++;
		$session['user']['reputation']+=2;
		output('`&Du parrierst einen gewaltigen Schlag des Wächters und rammst ihm deine Waffe, durch seine starke Rüstung in die Brust. Der Wächter sackt tödlich verwundet zu boden. ...');
		addnews($session['user']['name'].'`& hat den `VWächter der Grotte`& tödlich verwundet!');
		addnav('Weiter','festungsgrotte.php?op=wahl');
	}else{
		if($defeat){
			addnav('Tägliche News','news.php');
			$session['user']['reputation']--;
			addnews("`%".$session['user']['name'].'`5 wurde tödlich verwundet, als '.($session['user']['sex']?"sie":"er").' sich dem `VWächter der Grotte`5 stellte und hat das `vAmulett der Macht`5 verloren!!!  '.($session['user']['sex']?"Ihre":"Seine")." Gebeine haben nun in der Festungsgrotte ihre Ruhe gefunden.");
			

			$session['user']['alive']=false;
			debuglog("lost {$session['user']['gold']} gold when they were slain in grotto");

			$session['user']['gold']=0;
			$session['user']['hitpoints']=0;
			$session['user']['badguy']=array();
			savesetting("hasamulett","0");
			output('`b`%'.$badguy['creaturename'].'`& hat, dich getötet!!!`n`4Du hast dein ganzes Gold verloren!`n `4Du hast das Amulett in der Grotte verloren!`n Du kannst morgen wieder kämpfen.');
		}else{
		  fightnav(true,false);
		}
	}
}
page_footer();
?>