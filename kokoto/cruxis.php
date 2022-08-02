<?php
 
/*_____________________________________________________________
  |Überlebenskampf, Idee aus Tales of Symphonia               |
  |von Lord Eliwood                                           |
  |Kampf der reiter.php entnommen                             |
  |Version 1.0                                                |
  |Grundgerüst und Fehler beheboben                           |
  |Version 1.1                                                |
  |Belohung nach Kampfende bei einem merkwürdigen Mann        |
  |mit zufälligen Folgen                                      |
  |Version 1.2                                                |
  |Bugs aus Version 1.1 behoben von Skye      		      |
  |Getestet und Funktioniert auf www.funnetix.de/game/        |
  |Version 1.3                                                |
  |Nun stirbt man wirklich.....                               |
  |Version 1.4                                                |
  |Nun ohne SQL Befehl                                        |
  |___________________________________________________________|
*/
/*___________________________________________________________________________________________
  |EINBAUANLEITUNG:                                                                         |
  |Füge an einer Beliebigen Stelle im Spiel, am besten direkt unter dem Wald, folgendes ein:|
  |if ($session['user']['level']==15)  {                                                    |
  |addnav("Seltsame Lichtung","cruxis.php");  }                                             |
  |IN SQL DATENBANK EINFÜGEN:                                                               |
  |ALTER TABLE `accounts` ADD `engel` INT( 11 ) UNSIGNED DEFAULT '0' NOT NULL ;             |
  |_________________________________________________________________________________________|
*/


require_once "common.php";
page_header("Seltsame Lichtung");

if ($_GET['op']==''){
if ( $session['user']['cruxis']==0){
         $session['user']['cruxis']=1;  
	$session['engel']=0;
	output('Du siehst ein wenig abseits der Stadt einen leuchtenden Schein möchtest dir das mal genauer ansehen.');
	addnav('Weiter','cruxis.php?op=go');

	  $badguy = array(
        "creaturename"=>"`6Engel`0"
        ,"creaturelevel"=>16
        ,"creatureweapon"=>"Engelsschwert"
        ,"creatureattack"=>25
        ,"creaturedefense"=>20
        ,"creaturehealth"=>150
	  ,"diddamage"=>0);



	$session['user']['badguy']=createstring($badguy);
	$atkflux = e_rand(0,$session['user']['dragonkills']2);
	$defflux = e_rand(0,($session['user']['dragonkills']2$atkflux));
	$hpflux = ($session['user']['dragonkills']2  ($atkflux$defflux))  5;
	$badguy['creatureattack']+=$atkflux;
	$badguy['creaturedefense']+=$defflux;
	$badguy['creaturehealth']+=$hpflux;

}else{
output('Du kannst diese Wiese nur einmal pro Drachenkill erreichen, also töte einen Drachen und versuchs dann nochmal.');
addnav('Zurück zum Dorf','village.php');
}
}else if ($_GET['op']=='go')
{
	output('Du betrittst die Lichtung und siehst dich um. Nichts anderes ist hier, kein Laut ist zu hören, der Himmel ist dunkel Du fühlst dich von dem Schein angezogen und siehst eine Weile hin, ob nicht doch etwas passiert.`n Plötzlich erschrickst du. Ein Elf mit Flügel erscheint wie aus dem nichts. Du denkst, dass das nur ein Engel sein kann. Der Engel zieht hinter seinem Rücken ein Schwert hervor, und du weisst nun, das er nicht freundlich gesinnt ist.');
	addnav('Kämpfe','cruxis.php?op=fight');
	addnav('Flüchte in Furcht','village.php');
}
if ($_GET['op']=='fight')
{
	$battle=true;
}

if ($battle)
{
	include ("battle.php");
	if ($victory)
	{		
			output("`nDu hast `^".$badguy['creaturename']." geschlagen.");
			$badguy=array();
			$session['user']['badguy']='';
			$session['engel']+=1;

	}

	elseif($defeat)
	{
		output('Du wurdest vom Engel getötet, niemand anderes war Zeuge dieses Unfairen Kampfes');
		addnews($session['user']['name'].'s Leiche wurde auf einer Lichtung gefunden');
		output('`n`4Du bist tot.`n Du verlierst 10% deiner Erfahrung und alles Gold.`n Du kannst morgen weiterspielen.');
		$session['user']['gold']=0;
		$session['user']['experience']=round($session['user']['experience'].9,0); 
                $session['user']['alive']=false;
		$session['user']['hitpoints']=0;
		$session['user']['reputation']--;
		addnav('Tägliche News','news.php');
	}
	else
	{
		fightnav();
	}
}
if($session['engel']==1){
$session['engel']+=1;
addnav('Weiter','cruxis.php?op=goa');
}
if($session['engel']==3){
$session['engel']+=1;
addnav('Weiter','cruxis.php?op=end');
}
if($session['engel']==5){
$session['engel']+=1;
addnav('Weiter','cruxis.php?op=vil');
}

if ($_GET['op']=='goa')
{
	output('Du betrittst den Lichtschein und wartest ab. Du spürst ein ziehen an deinen Füssen und es vergeht keine weitere Sekunde, bis du verschwindest und dir Schwarz vor Augen wird.`n Nach einer Weile kommst du wieder zu dir und stehst in einem Raum, der leer ist, und die Luft ist dünn.');
	addnav('Weiter','cruxis.php?op=gob');
}	
if ($_GET['op']=='gob')
{
page_header("Derris-Kharlan");
	switch(e_rand(1,3))
	{
		case 1:
			output('Du gehst unvorsichtig weiter und trittst in eine Falle.`n Du verlierst einige Lebenspunkte, doch gewinnst du an Erfahrung, da du nun weisst, wo du deine Schritte hinsetzetn musst.');
			$session['user']['hitpoints']*=0.9;
			$session['user']['experience']*=1.01;
			addnav('Weiter','cruxis.php?op=goc');
		break;
		case 2:
			output('Du siehst rechtzeitig eine Falle und weichst ihr gekonnt aus.`n`n Du weisst, dass dieser Ort voller Fallen ist und gewinnst an Erfahrung');
			$session['user']['experience']*=1.01;
			addnav('Weiter','cruxis.php?op=goc');
		break;
		case 3:
			output('Du siehst einen Brunnen in der Nähe und trinkst einen Schluck davon.`n`n Du fühlst dich von dem Wasser erfrischt und bist voller Energie.');
			$session['user']['hitpoints'] = $session['user']['maxhitpoints'];
			addnav('Weiter','cruxis.php?op=goc');
		break;
	}
}
if ($_GET['op']=='goc')
{
	page_header("Die Halle");
	output('Du betritts eine Halle, die von Federn überseht ist. Du weisst, dass dies das Hauptquartier der Engel sein muss und hörst Glockenschläge Plötzlich siehst du eine ganze Armee von Engeln, die sich auf sich stürzen. Was willst du nun tun?');
	addnav('Schlage dich durch','cruxis.php?op=f2');
	addnav('Flüchte','cruxis.php?op=tot1');
}
if ($_GET['op']=='f2')
{
	output('Du schlägst dich durch die Masse und die Engel ergreifen die Flucht.`n Du merkst es schon fast zu spät, dass ein Engel stehen geblieben ist. Es hält eine Waffe in der einen Hand, einen Schild in der anderen und sieht dich an. Dann beginnt er zu sprechen.`n`n "Wer bist du, der du es wagst, die Cruxis heraus zu fordern? Wir, das höchste Geschlecht von allen, die Engel, sollten gegen einen der niederen Rasse verlieren? Nein, wir, das höchste Geschlecht verlieren nicht. KÄMPFE!!"');
	addnav('Kämpfe','cruxis.php?op=fight');

	  $badguy = array(
        "creaturename"=>"`6Engels Kommandeur`0"
        ,"creaturelevel"=>17
        ,"creatureweapon"=>"Heilige Klinge"
        ,"creatureattack"=>30
        ,"creaturedefense"=>27
        ,"creaturehealth"=>200
	  ,"diddamage"=>0);


	$session['user']['badguy']=createstring($badguy);
	$atkflux = e_rand(0,$session['user']['dragonkills']2);
	$defflux = e_rand(0,($session['user']['dragonkills']2$atkflux));
	$hpflux = ($session['user']['dragonkills']2  ($atkflux$defflux))  5;
	$badguy['creatureattack']+=$atkflux;
	$badguy['creaturedefense']+=$defflux;
	$badguy['creaturehealth']+=$hpflux;
	
}

if ($_GET['op']=='tot1')
{
	output('Die Armee schneidet dir den Weg ab und du stirbst.`n`n `c`$ Du bist Tod. Du verlierst dein ganzes Gold, 20% deiner Erfahrung und kannst morgen weiter spielen`c`0');
	$session['user']['gold']=0;
	$session['user']['experience']*=0.8;
	$session['user']['alive']=false;
	$session['user']['hitpoints']=0;
	addnews($session['user']['name'].'s Leiche wurde auf einer Lichtung gefunden');
	addnav('Tägliche News','news.php');
}

if ($_GET['op']=='tot11')
{
	output('Die Armee schneidet dir den Weg ab und du stirbst.`n`n `c`$ Du bist Tod. Du verlierst dein ganzes Gold, 20% deiner Erfahrung und kannst morgen weiter spielen`c`0');
	$session['user']['gold']=0;
	$session['user']['experience']*=0.8;
	$session['user']['alive']=false;
	$session['user']['hitpoints']=0;
	
	addnav('Tägliche News','news.php');
}


if ($_GET['op']=='end')
{
	rawoutput("<embed src=\"media/mts.mid\" width='10' height='10' autostart='true' loop='false' hidden='true' volume='100'>");
	output('Du betritts einen Raum, in dessen hinterem Teil ein Thron steht. Du trittst ein und hörst eine wütendene Stimme: "Wer wagt es, die Machenschaften von Cruxis zu stören? Niemand hatte auch nur annähernd eine Chance gegen MEINE Soldaten! Doch nun bist zu zu weit gegangen, mein Lieber. STIRB!"');
	addnav('Kämpfe','cruxis.php?op=fight');
	addnav('Stirb lieber','cruxis.php?op=tot2');

	  $badguy = array(
        "creaturename"=>"`6Anführer der Cruxis`0"
        ,"creaturelevel"=>20
        ,"creatureweapon"=>"Judgement"
        ,"creatureattack"=>50
        ,"creaturedefense"=>50
        ,"creaturehealth"=>500
	  ,"diddamage"=>0);


	$session['user']['badguy']=createstring($badguy);
	$atkflux = e_rand(0,$session['user']['dragonkills']2);
	$defflux = e_rand(0,($session['user']['dragonkills']2$atkflux));
	$hpflux = ($session['user']['dragonkills']2  ($atkflux$defflux))  5;
	$badguy['creatureattack']+=$atkflux;
	$badguy['creaturedefense']+=$defflux;
	$badguy['creaturehealth']+=$hpflux;
}

if ($_GET['op']=='tot2')
{
	output('Du kehrst der merkwürdigen Person den Rücken und fliehst, so schnell du kannst, doch du hast dich zu früh gefreut.`n`n "Judgement!", schreit die Person. Du wirst von Energie aus reinem Licht niedergerissen und wirst fast bewusstlos, bis die Person sagt:`n "Hehehehe. Nur ein Narr wagt es, mir den Rücken zu kehren. Nun stibst du einen Tod, der nicht gerade Ehrenhaft ist."`n`n`n `c`$Du bist Tod. Du verlierst all dein Gold und 30% deiner Erfahrung. Du kannst morgen weiter spielen.`c`n`n');
	$session['user']['gold']=0;
	$session['user']['experience']*=0.8;
	$session['user']['alive']=false;
	$session['user']['hitpoints']=0;
        addnews($session['user']['name'].'s Leiche wurde auf einer Lichtung gefunden');
	
	addnav('Tägliche News','news.php');
}

if ($_GET['op']=='run'){
	if (e_rand(0,3)==1){
		output ('`c`b`&Du bist erfolgreich vor deinem Gegner geflohen!`0`b`c`n');
		addnews($session['user']['name'].' ist vor den Engeln Geflohen wie ein Feigling');
                addnav('Dorfplatz','village.php');                
	}else{
        output ('`c`b`&Du glaubst erfolgreich vor deinem Gegner geflohen zu sein und versuchst so schnell wie möglich zum Dorfplatz zu rennen!`0`b`c`n');
        addnews($session['user']['name'].' ist beim versuch vor den Engeln zu fliehen von selbigen getötet worden');
        addnav('Renn zum Dorfplatz','cruxis.php?op=tot11');
        }
        }

if ($_GET['op']=='vil')
{
	$session['engel']=0;
	output('Du wirst im Dorf von allen Jubelnd empfangen. Du bist ihr Held und feiern dich. Nach einer Nacht voller Freude und Festen gehst du gebückt ins Wohnviertel.`n`n Auf dem Weg nach Hause biegst du, so betrunken wie du bist, in eine Gasse ein, die dir gänzlich unbekannt ist. Ein kalter Wind fegst durch die Gassen, und du frierst. Du setzt deinen Weg fort, dein Tunnelblick lässt dich nicht mehr viel erkennen. Plötzlich stürzt du.`n "`7Wer haben wird denn hier?`0", hörst eine schwache, alte Stimme rufen. "`7Wenn das nicht der Held der Stadt ist. Komm mal her, mein Junge`0"`n Du gehst näher zu ihm und kannst gerade noch seine Umrisse erkennen. Dein Bewusstsein schwindet und schwindet...`n "`7Jaa, du bist es tatsächlich", hörst du ihn sagen. "Ich muss dir danken.`0"`n "`4Für was denn?`0", fragst du ängstlich, die Hand an die Stirn gepresst.`n "`7Für das du den Anführer von Cruxis erledigt hast, mein Sohn."`n "`4Sie... wissen von... dieser Organisation?`0"`n "`7Aber natürlich. Ich bin dir sehr dankbar. Nun kann ich die Führung übernehmen. Als Belohnung kannst du dir hier was aussuchen.`0"');
	addnav('Eine kleine Schatulle','cruxis.php?op=b1');
	addnav('Eine mittlere Schatulle','cruxis.php?op=b2');
	addnav('Eine grosse Schatulle','cruxis.php?op=b3');
        
       	addnews("`5Es wird für ".$session['user']['name']." `5ein grosses Fest gefeiert, da ".($session['user']['sex']?"sie":"er")." die 
			Welt vor Cruxis beschützt hat und deren Angführer erledigt hat.");
}

if ($_GET['op']=='b1')
{
	switch(e_rand(1,7))
		{
			case 1:
			case 2:
			output('Du entscheidest dich für die kleine Schatulle und öffnest sie.`n "`7Ah ja,`0", murmelt der Mann, "`7du bekommst ein paar Donationpunkte. Viel Spass damit.`0"`n`n Mit diesen Worten lösst sich der Mann auf und du brichst zusammen.');
			addnav('Du kommst wieder zu dir','village.php');
			$session['user']['donation']+=30;
			break;
			case 3:
			case 4:
			case 5:
			output('Du entscheidest dich für die kleine Schatulle und öffnest sie.`n "`7Ah ja,", murmelt der Mann, "`7du bekommst Gold. Kauf dir was schönes."`n`n Mit diesen Worten lösst sich der Mann auf und du brichst zusammen.');
			addnav('Du kommst wieder zu dir','village.php');
			$session['user']['gold']+=1000;
			break;
			case 6:
			case 7:
			output('Du entscheidest dich für die kleine Schatulle und öffnest sie.`n "`7Hahahaha,", lacht der Mann und schlägt dich nieder, "`7Du bist mir auf den Leim gegangen."`n`n Du brichst zusammen und merkst, als du wieder aufwachst, dass dir alles Gold gestohlen wurde.');
			addnav('Weiter','village.php');
			$session['user']['gold']=0;
			$session['user']['experience']*=1.05;
			break;
		}
}
if ($_GET['op']=='b2')
{
	switch(mt_rand(1,7))
		{
			case 1:
			output('Du entscheidest dich für die mittlere Schatulle und öffnest sie. Im innern ist ein Fläschchen, das du öffnest und tinkst.`n "`7Hahahaha,`0", lacht der Mann und schlägt dich nieder, "`7Du bist mir auf den Leim gegangen.`0"`n`n Du merkst, dass du schwächer wie vorher bist und brichst dann bewusstlos zusammen. Du hast an Angriff verloren.');
			addnav('Du kommst wieder zu dir','village.php');
			$session['user']['attack']*=0.95;
			break;
			case 2:
			output('Du entscheidest dich für die mittlere Schatulle und öffnest sie. Im innern ist ein Fläschchen, das du öffnest und tinkst.`n "`7Ah, der Trank der Stärke,`0", spricht der alte Mann, "`7Dies ist eines meiner besten Stücke. Nun gut, jetzt bist du stärker`0"`n`n Du merkst, dass du stärker bist, brichst aber trotzdem bewusstlos zusammen. Du hast an Angriff dazugewonnen.');
			addnav('Du kommst wieder zu dir","village.php');
			$session['user']['attack']*=1.05;
			break;
			case 3:
			case 4:
			output('Du entscheidest dich für die mittlere Schatulle und öffnest sie. Im innern ist ein Stück Pergament.`n "`7Ah, der Gutschein der Jägerhütte,`0", seuzt der alte Mann, "`7Du bekommst wohl oder übel 70 Punkte gutgeschrieben.`0"`n`n Du freust dich über die Punkte und brichst dann bewusstlos zusammen.');
			addnav('Du kommst wieder zu dir','village.php');
			$session['user']['donation']+=70;
			break;
			case 5:
			case 6:
			case 7:
			output('Du entscheidest dich für die mittlere Schatulle und öffnest sie. Im innern ist ein Stück Pergament.`n "`7Ah, das Papier der Edelsteine,`0", seuzt der alte Mann, "`7Du hast Glück.`0"`n`n Du fragst dich, was das soll und brichst zusammen. Am nächsten Tag wachst du auf und findest ein Säckchen mit Edelsteine!');
			addnav('Du kommst wieder zu dir','village.php');
			$session['user']['gems']+=10;
			break;
		}
}
if ($_GET['op']=='b3')
{
	switch(mt_rand(1,7))
		{
			case 1:
			output('Du entscheidest dich für die große Schatulle und öffnest sie. Im innern ist ein Fläschchen, das du öffnest und tinkst.`n "`7Hahahaha,`0", lacht der Mann und schlägt dich nieder, "Du bist mir auf den Leim gegangen.`0"`n`n Du merkst, dass du schwächer wie vorher bist und brichst dann bewusstlos zusammen. Du hast an Verteidung verloren.');
			addnav('Du kommst wieder zu dir','village.php');
			$session['user']['defence']*=0.95;
			break;
			case 2:
			output('Du entscheidest dich für die große Schatulle und öffnest sie. Im innern ist ein Fläschchen, das du öffnest und tinkst.`n "`7Ah, der Trank des Schildes,`0", spricht der alte Mann, "`7Dies ist eines meiner besten Stücke. Nun gut, jetzt bist du stärker`0"`n`n Du merkst, dass du widerstandfähiger bist, brichst aber trotzdem bewusstlos zusammen. Du hast an Verteidigung dazugewonnen.');
			addnav('Du kommst wieder zu dir','village.php');
			$session['user']['defence']*=1.05;
			break;
			case 3:
			case 4:
			output('Du entscheidest dich für die großemittlere Schatulle und öffnest sie. Im innern ist ein Stück Pergament.`n "`7Ah, der Gutschein der Jägerhütte,`0", seuzt der alte Mann, "`7Du bekommst wohl oder übel 100 Punkte gutgeschrieben.`0"`n`n Du freust dich über die Punkte und brichst dann bewusstlos zusammen.');
			addnav('Du kommst wieder zu dir','village.php');
			$session['user']['donation']+=100;
			break;
			case 5:
			case 6:
			case 7:
			output('Du entscheidest dich für die große Schatulle und öffnest sie. Im innern ist ein Stück Pergament.`n "`7Ah, das Papier der Edelsteine,`0", seuzt der alte Mann, "`7Du hast Glück.`0"`n`n Du fragst dich, was das soll und brichst zusammen. Am nächsten Tag wachst du auf und findest ein Säckchen mit Edelsteine!');
			addnav('Du kommst wieder zu dir','village.php');
			$session['user']['gems']+=10;
			break;
		}
}

page_footer();
?>