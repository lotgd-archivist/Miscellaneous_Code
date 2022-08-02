<?php

// 22062004

/* Random Green Dragon Encounter v1 by Timothy Drescher (Voratus)
Current version can be found at Domarr's Keep (lgd.tod-online.com)
This is a simple "forest special" which helps to keep the main idea in mind, by giving any player an
encounter with the Green Dragon, and the results could be deadly.
The following names/locations are server-specific and should be changed:
	Plains of Al'Khadar (and reference to "plains")
	Domarr's Keep (the main city)

Version History
1.0 original version

german translation by anpera
some changes for my game - may not work with other versions!
// überarbeitet von Tidus www.kokoto.de
*/

if (!isset($session)) exit();
$session['user']['specialinc']='randdragon.php';
if ($_GET['count']==3) {
	output('`tDer `xDrachen`t hat genug von deinem Geschwafel. Er bläst dich mit einem Feuerstoß weg!`n`nDu fragst dich noch, was schlimmer ist, der Schmerz, oder der Gestank deines verbrennenden Fleischs. Aber das spielt keine Rolle. Das Reich der Schatten empfängt dich.`n`n`4Du wurdest vom `@Grünen Drachen`4 gegrillt!`nDu verlierst 5% deiner Erfahrung und alles Gold.');
	addnews("`%".$session['user']['name'].'`t wurde bei einer zufälligen Begegnung im Wald von einem `xDrachen`t getötet!');
	$session['user']['gold']=0;
	$session['user']['experience']=round($session['user']['experience'].95,0);
	$session['user']['alive']=false;
	$session['user']['hitpoints']=0;
	$session['user']['specialinc']='';
	addnav('Tägliche News','news.php');
} else {
	switch($_GET['op']){
		default:
			output('`tBei deinem Streifzug durch die Wälder hörst du plötzlich ein lautes Brüllen. Das Geräusch lässt das Blut in deinen Adern gefrieren.`nEin tiefes Stampfen ist hinter dir zu hören. Starr vor Schreck fühlst du einen Stoß heißen Atem in deinem Nacken. Langsam drehst du dich um - und siehst einen riesigen `xDrachen`t vor dir stehen. `n`nDas könnte Ärger geben...');
			addnav('Angreifen!','forest.php?op=slay');
			addnav('Um Gnade winseln','forest.php?op=cower');
			addnav('Rede dich raus','forest.php?op=talk');
			addnav('Lauf weg!','forest.php?op=flee');
			$session['user']['specialinc']='randdragon.php';
			break;	
		case 'slay':
			output('`tDu hältst deine Waffe fest im Griff und bereitest dich auf den Angriff auf diese gewaltige Kreatur vor.`n`nDu brüllst einen Kampfschrei und springst auf den Drachen zu!`nDoch bevor deine Waffe den Drachen berührt, schlägt er sie dir mit seinem Schwanz aus der Hand und spuckt dir seinen Feueratem entgegen. ');
			if ($session['user']['level'] < 15) {
				output('`n`nDer Strahl wirft dich zu Boden. Du kannst fühlen, wie sich durch die große Hitze schwere Blasen auf deiner Haut bilden. `nGeschwächst schaust du zum `xDrachen`t auf, der auf dich zu stolziert. ');
				if (rand(1,4)==1) {
					output('Er beugt sich gerade zu dir herunter, um dich zu verschlingen, als plötzlich ein Pfeil  scheinbar aus dem Nichts im Kopf des Drachen einschlägt.`nMit einem entsetzlichen Brüllen fliegt der Drachen davon.`nDu kannst gerade noch einen Elfen auf dich zurennen sehen, dann wird dir schwarz vor Augen.`n`nEinige Zeit später erwachst du auf einer Lichtung. Deine  Wunden wurden geheilt, aber nichts kann die Verletzungen, die Drachenatem verursacht, wirklich vollständig beseitigen. `nDu verlierst zwei Charmepunkte durch die Verbrennungen!');
					addnews("`%".$session['user']['name'].'`t hat irgendwie eine zufällige Begegnung mit einem `xDrachen`t überlebt.');
					$session['user']['charm']-=2;
					$session['user']['turns']-=2;
					if ($session['user']['turns'] < 0) $session['user']['turns']=0;
					$session['user']['reputation']++;
					$session['user']['hitpoints']=$session['user']['maxhitpoints'];
					$session['user']['specialinc']='';
				} else {
					output('`nDas ist das Letzte, was du siehst, bevor du in die ewige Dunkelheit gleitest.`n`n`4Du wurdest vom `@Drachen`4 gefressen!Du verlierst 10% deiner Erfahrung und alles Gold.');
					addnews("`%".$session['user']['name'].'`t wurde bei einer zufälligen Begegnung im Wald von einem `xDrachen`t getötet!');
					$session['user']['gold']=0;
					$session['user']['experience']=round($session['user']['experience'].9,0);
					$session['user']['alive']=false;
					$session['user']['hitpoints']=0;
					$session['user']['specialinc']="";
					addnav('Tägliche News','news.php');
				}
			} else {
				output('`nDu schaffst es im letzten Moment, dem Feuerstoß aus dem Weg zu stolpern, um dich kurz darauf Auge in Auge mit diesem gewaltigen Biest zu finden. Spöttisch sagt er zu dir: "`5Nich hier. Nicht jetzt.`t"`nMit diesen Worten hebt der Drachen ab und steigt in die Lüfte davon. Du bist wieder alleine mit deinen Gedanken.');
				$session['user']['specialinc']='';
			}
			break;
		case 'cower':
			output('`tDu kauerst dich vor dem `xDrachen`t zusammen und flehst um dein Leben. Der Drachen schnaubt dir erneut seinen heißen Atem entgegen. "`5An jemandem, der so erbärmlich jammert, würde ich mir sicher nur den Magen verderben. `n`5Hau schon ab.`@"`nDu beschließt, dass es das Beste ist, den Anweisungen der Kreatur zu folgen, und so hoppelst du verängstigt davon.');

			$session['user']['specialinc']='';
			$session['user']['reputation']-=2;
			break;
		case 'talk':
			output('`tDu bist der Meinung, dass du diese Begegnung überleben könntest, wenn es dir gelingt, den `xDrachen`t in ein Gespräch zu verwickeln Jetzt brauchst du nur noch etwas, worüber ihr reden könntet.`n');
			addnav('Themen');
			addnav('W?Das Wetter','forest.php?op=weather&count=0');
			addnav('D?Drachen','forest.php?op=dragon&count=0');
			addnav('Ivalaine','forest.php?op=Ivalaine&count=0');
			addnav('Tharagon','forest.php?op=Tharagon&count=0');
			addnav('Thalion','forest.php?op=Thalion&count=0');
			addnav('Kokoto','forest.php?op=city&count=0');
			addnav('Stottere unkontrolliert','forest.php?op=stutter&count=0');
			break;
		case 'weather':
			$count=$_GET['count'];
			$count++;
			output('`t"`qAlso '.getsetting("weather","Wetter").', was hältst du von diesem Wetter?`t"`nDer Drachen legt den Kopf schief und schaut dich an. Ein kurzes Schnauben schlägt dir heiße, dampfende Luft entgegen.`n`nVielleicht interessiert den Drachen etwas anderes mehr?');
			addnav('Themen');
			addnav('D?Drachen',"forest.php?op=dragon&count={$count}");
			addnav('Ivalaine',"forest.php?op=Ivalaine&count={$count}");
			addnav('Tharagon',"forest.php?op=Tharagon&count={$count}");
			addnav('Thalion',"forest.php?op=Thalion&count={$count}");
			addnav('Kokoto',"forest.php?op=city&count={$count}");
			addnav('Stottere unkontrolliert',"forest.php?op=stutter&count={$count}");
			break;
		case 'dragon':
			$count=$_GET['count'];
			$count++;
			output('`t"`qDu bist also einer der Drachen, hä?`t"`nDer Drachen gibt ein ohrenbetäubendes Brüllen von sich und leckt sich dann die Lippen. Vielleicht wäre ein anderes Thema besser zur Unterhaltung geeignet.');
			addnav('Themen');
			addnav('W?Das Wetter',"forest.php?op=weather&count={$count}");
			addnav('Ivalaine',"forest.php?op=Ivalaine&count={$count}");
			addnav('Tharagon',"forest.php?op=Tharagon&count={$count}");
			addnav('Thalion',"forest.php?op=Thalion&count={$count}");
			addnav('Kokoto',"forest.php?op=city&count={$count}");
			addnav('Stottere unkontrolliert',"forest.php?op=stutter&count={$count}");
			break;
		case 'Ivalaine':
			$count=$_GET['count'];
			$count++;
			output('`t"`qDiese Ivalaine ist ganz schön süss, was?`t"`nDer Drachen nickt. "`5Ein schmackhafter, süsser Happen.. es ist eine Schande, dass sie niemals die Schenke verlässt. Aber vielleicht wirst du meinen Hunger solang stillen?.`t"`n Du solltest dir etwas anderes ausdenken. Schnell!');
			addnav('Themen');
			addnav('W?Das Wetter',"forest.php?op=weather&count={$count}");
			addnav('D?Drachen',"forest.php?op=dragon&count={$count}");
			addnav('Tharagon',"forest.php?op=Tharagon&count={$count}");
			addnav('Thalion',"forest.php?op=Thalion&count={$count}");
			addnav('Kokoto',"forest.php?op=city&count={$count}");
			addnav('Stottere unkontrolliert',"forest.php?op=stutter&count={$count}");
			break;
		case 'Tharagon':
			$count=$_GET['count'];
			$count++;
			output('`t"`qTharagon ist ein netter Kerl, stimmts?`t"`nDer Drachen dreht den Kopf in Gedanken.`n "`5Ein bisschen schwer zu schlucken, würde ich wetten, aber er verlässt ja nie die Schenke. Du dagegen hast es getan. `t"`nDer Drachen schaut dich hungrig an. Zeit für einen Themenwechsel!');
			addnav('Themen');
			addnav('W?Das Wetter',"forest.php?op=weather&count={$count}");
			addnav('D?Drachen',"forest.php?op=dragon&count={$count}");
			addnav('Ivalaine',"forest.php?op=Ivalaine&count={$count}");
			addnav('Thalion',"forest.php?op=Thalion&count={$count}");
			addnav('Kokoto',"forest.php?op=city&count={$count}");
			addnav('Stottere unkontrolliert',"forest.php?op=stutter&count={$count}");
			break;
		case 'Thalion':
			$count=$_GET['count'];
			$count++;
			output('`t"`qThalion ist ein mürrischer alter Kerl, meinst du nicht auch?`t"`nDer Drachen blinzelt langsam. "`5Dieser Sterbliche interessiet mich nicht. Aber du bietest dich mir doch geradezu an.`t"`nMan braucht keinen Gedankenleser, um zu erfahren, was dieses Ding denkt. Und du solltest seine Gedanken schnellcin eine andere Richtung lenken.');
			addnav('Themen');
			addnav('W?Das Wetter',"forest.php?op=weather&count={$count}");
			addnav('D?Drachen',"forest.php?op=dragon&count={$count}");
			addnav('Ivalaine',"forest.php?op=Ivalaine&count={$count}");
			addnav('Tharagon',"forest.php?op=Tharagon&count={$count}");
			addnav('Kokoto',"forest.php?op=city&count={$count}");
			addnav('Stottere unkontrolliert',"forest.php?op=stutter&count={$count}");
			break;
		case 'city':
			$count=$H_GET['count'];
			$count++;
			output('`t"`qWusstest du, dass das Dorf Kokoto heißt? Ist ein ziemlich beeindruckendes Örtchen!`t"`nDer Drachen gröhlt laut.`n"`5Diese stinkende Stadt ist mir ein Dorn im Auge, weiter nichts! Ich sollte ihre schwachen Mauern niederreißen und die Stadt nieerbrennen! Alle sollten `bmeinen`b Namen kennen und mich fürchten!`t"`nNun, es scheint so, als ob du die Kreatur verärgert hast. Vielleicht hilft ein Themenwechsel.');
			addnav('Themen');
			addnav('W?Das Wetter',"forest.php?op=weather&count={$count}");
			addnav('D?Drachen',"forest.php?op=dragon&count={$count}");
			addnav('Ivalaine',"forest.php?op=Ivalaine&count={$count}");
			addnav('Tharagon',"forest.php?op=Tharagon&count={$count}");
			addnav('Thalion',"forest.php?op=Thalion&count={$count}");
			addnav('Name?',"forest.php?op=name&count={$count}");
			addnav('Stottere unkontrolliert',"forest.php?op=stutter&count={$count}");
			break;
		case 'stutter':
			$count=$_GET['count'];
			$count++;
			output('`tDu versuchst, ein intelligentes Thema zu finden, aber stattdessen stotterst du nur unkontrolliert vor dich hin. Der Drachen rollt dramatisch mit den Augen. Er schlägt dich mit seinem Schwanz auf den Hinterkopf und du wirst ohnmächtig.`n`nEinige Zeit später wachst du mit einer gewaltigen Beule am Kopf wieder auf.`n');
			if ($session['user']['hitpoints'] > $session['user']['maxhitpoints'].1) {
				$session['user']['hitpoints']=round($session['user']['maxhitpoints'].1);
			} else {
				$session['user']['hitpoints']=1;
			}		
			$session['user']['turns']--;
			if ($session['user']['turns'] < 0) $session['user']['turns']=0;
			addnews("`%".$session['user']['name'].'`t hat irgendwie eine zufällige Begegnung mit einem `xDrachen`t überlebt.');
			$session['user']['reputation']++;
			$session['user']['specialinc']='';
			break;
		case 'name':
			output('`t"`qWie ist dein Name, oh mächtiger Drachen?`t"`nDer Drachen betrachtet dich ernst. "`5Du wärst nicht in der Lage, ihn richtig auszusprechen. Es sind nur wenige in dieser Welt übrig, die das können, denn es verlangt die Sprachfertigkeit eines ausgewachsenen Drachen, von denen es nur noch wenige gibt. Unsere einst große und stolze Rasse wurde von den niederen Rassen zu Aasfressern reduziert, aus Angst, wir könnten sie alle vernichten.`t"`n Der Drachen schaut einen Moment weg, dann wendet er sich dir erneut zu. "`5Drachen haben nur getötet, was wir als Nahrung brauchten. Jetzt töten wir, um zu überleben.`t"`n`n"`5Weiche von mir, bevor ich mich entschließe, dich ohne Grund zu töten.`t"');
			output('`nDu hältst es für eine gute Idee, dich zu beeilen, bevor es sich der Drache anders überlegt und einen Snack aus dir macht.');
			addnews("`%".$session['user']['name'].'`t hat irgendwie eine zufällige Begegnung mit einem `xDrachen`t überlebt.');
			$session['user']['specialinc']='';
			break;
		case 'flee':
			$results=rand(1,4);
			if ($results==1) {
				output('`tDu drehst dich um und flüchtest so schnell du kannst vor der Macht des Drachens. Du glaubst, du schaffst es, denn du hörst keinen Verfolger hinter dir.`nDu hältst an, um dich umzudrehen. Keine Spur vom Drachen!`n Du hast wirklich Glück gehabt.');
				addnews("`%".$session['user']['name'].'`t hat irgendwie eine zufällige Begegnung mit einem `xDrachen`t überlebt.');
				$session['user']['specialinc']='';
				$session['user']['reputation']--;
			} elseif ($results==4) {
				output('`tDu drehst dich um und flüchtest so schnell du kannst vor der Macht des Drachens. Du glaubst, du schaffst es, denn du hörst keinen Verfolger hinter dir.`nDu hältst an, um dich umzudrehen. Keine Spur vom Drachen!`n Du glaubst schon, du bist dem Biest entkommen und drehst dich wieder um. Und da steht der Drachen direkt vor dir und sein weit aufgerissene Maul rast auf dich zu.`nBevor du Zeit hast, zu reagieren, hat dich der Drachen verspeist.. `4Du bist gestorben!`nDu verlierst 10% deiner Erfahrung und all dein Gold.');
				addnews("`%".$session['user']['name'].'`t wurde bei einer zufälligen Begegnung im Wald von einwm `@ Drachen`t getötet!');
				$session['user']['gold']=0;
				$session['user']['experience']=round($session['user']['experience'].9,0);
				$session['user']['alive']=false;
				$session['user']['hitpoints']=0;
				$session['user']['specialinc']='';
				addnav('Tägliche News','news.php');
			} else {
				output('`tDu drehst dich um um vor der Macht des Drachen zu fliehen. Während du rennst, fühlst du plötzlich, wie du von einer  Welle der Hitze umschlossen wirst. Der Drachen hat dich mit einem Feuerstoß erwischt!');
				$damage=mt_rand(round($session['user']['maxhitpoints'].5,0),$session['user']['maxhitpoints']);
				output("Du verlierst $damage Lebenspunkte durch diesen Treffer!`n");
				$session['user']['hitpoints']-=$damage;
				if ($session['user']['hitpoints'] < 1) {
					$session['user']['hitpoints']=0;
					output('`4Du bist gestorben!`nDu verlierst 10% deiner Erfahrungspunkte und alles Gold!');
					addnews("`%".$session['user']['name'].'`t wurde bei einer zufälligen Begegnung im Wald von einem `xDrachen`t getötet!');
					$session['user']['gold']=0;
					$session['user']['experience']=round($session['user']['experience'].9,0);
					$session['user']['alive']=false;
					$session['user']['specialinc']='';
					addnav('Tägliche News','news.php');
				} else {
					output('Du rollst dich auf dem Boden, um das Feuer zu löschen. Nachdem du festgestellt hast, dass du nicht tot bist, blickst du dich nach dem Drachen um. Doch der ist verschwunden. Hat er dich absichtlich nicht getötet? Oder war er bloß der Meinung, dass du jetzt verkocht bist?`nDie Antwort wirst du nie erfahren, so machst du dich wieder auf den Weg. Ein Besuch beim Heiler dürfte jetzt erstmal an der Reihe sein.');
					addnews("`%".$session['user']['name'].'`t hat irgendwie eine zufällige Begegnung mit einem `xDrachen`t überlebt.');
					$session['user']['specialinc']='';
				}
			}
			break;
	}
}
//output("</span>",true); // what's that good for?
?>