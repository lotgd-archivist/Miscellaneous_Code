<?php
require_once("common.php");
// This idea is Imusade's from lotgd.net
addcommentary();

checkday();
if ($_GET['op']=="egg"){
	page_header("Das goldene Ei");
	output("`^Du untersuchst das Ei und entdeckst winzige Inschriften:`n`n");
	viewcommentary("goldenegg","Botschaft hinterlassen:",10,"schreibt");
	addnav("Zurück zum Club","rock.php");
}else if($_GET['op']=="egg2"){
	page_header("Das goldene Ei");
	$preis=$session['user']['level']60;
	output("`3Du fragst ein paar Leute hier, ob sie wissen, wo sich der Besitzer des legendären goldenen Eis aufhält. Einige lachen dich aus, weil du nach einer Legende suchst, schütteln nur den Kopf. Du willst gerade ".($session['user']['sex']?"einen jungen Mann":"eine junge Dame")." ansprechen, als dich eine nervös wirkende Echse zur Seite zieht:  \"`#Psssst! Ich weissss wen ihr ssssucht und wo ssssich diesssser Jemand aufhält. Aber wenn ich euch dassss ssssagen ssssoll, müsssst ihr mir einen Gefallen tun. Ich habe Sssschulden in Höhe von `^$preis`# Gold. Helft mir, diesssse losssszzzzuwerden und ich ssssag euch, wassss ich weissss. Anssssonssssten habt ihr mich nie gessssehen.`3\"");
	addnav("G?Zahle `^$preis`0 Gold","rock.php?op=egg3");
	addnav("Zurück zum Club","rock.php");
}else if($_GET['op']=="egg3"){
	page_header("Das goldene Ei");
	$preis=$session['user']['level']60;
	if ($session['user']['gold']<$preis){
		output("`3\"`#Von dem bisssschen Gold kann ich meine Sssschulden nicht bezzzzahlen. Vergissss essss!`3\"");
	}else{
		$sql="SELECT acctid,name,location,loggedin,laston,alive,housekey FROM accounts WHERE acctid=".getsetting("hasegg",0);
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
		$loggedin=(date("U")  strtotime($row['laston']) < getsetting("logintimeout",900) && $row['loggedin']);
		if ($row['location']==0) $loc=($loggedin?"Online":"in den Feldern");
		if ($row['location']==1) $loc="in einem Zzzzimmer in der Kneipe";
		// part from houses.php
		if ($row['location']==2){
			$sql="SELECT hvalue FROM items WHERE class='Schlüssel' AND owner=$row[acctid] AND hvalue>0";
			$result = db_query($sql);
			$row2 = db_fetch_assoc($result);
			$loc="im Haussss Nummer ".($row2['hvalue']?$row2['hvalue']:$row['housekey'])."";
		}
		// end houses
		$row['name']=str_replace_c("s","ssss",$row['name']);
		$row['name']=str_replace_c("z","zzzz",$row['name']);
		output("`3Hissssa nimmt deine `^$preis`3 Gold, schaut sich nervös um und flüstert dir zu: \"`#$row[name]`# isssst $loc ".($row['alive']?"und lebt.":", isssst aber tot!")." Und jetzzzzt lassss mich bitte in Ruhe. Achja: Diesssse Information hasssst du nicht von mir!`3\"");
		$session['user']['gold']-=$preis;
	}
	addnav("Zurück zum Club","rock.php");
}else{
	if ($session['user']['dragonkills']>0 || $session['user']['superuser']>=4){
		page_header("Club der Veteranen");
	
		output('`b`c`2Der Club der Veteranen`0`c`b `n`n`4Irgendetwas in dir zwingt dich, den merkwürdig aussehenden Felsen zu untersuchen. Irgendeine dunkle Magie, gefangen in uraltem Grauen. `n`nAls du am Felsen ankommst, fängt eine alte Narbe an deinem Arm an zu pochen. Das Pochen ist mit einem rätselhaften Licht synchron, das jetzt von dem Felsen zu kommen scheint. Gebannt starrst du auf den schimmernden Felsen, der eine Sinnestäuschung von dir abschüttelt. Du erkennst, daß das mehr als ein Felsbrocken ist. Tatsächlich ist es ein Eingang, über dessen Schwelle du andere wie dich siehst, die auch die selbe Narbe wie du tragen. Sie erinnert dich irgendwie an den Kopf einer dieser riesigen Schlangen aus Legenden. Du hast den Club der Veteranen entdeckt und betrittst dieses unterirdische Gewölbe. `n`n');
		if ($session['user']['acctid']==getsetting("hasegg",0)){
			output("Da du dich hier zurückziehen kannst, könntest du das `^goldene Ei`4 mal näher untersuchen.`n`nDie Veteranen unterhalten sich:`n");
			addnav("Ei untersuchen","rock.php?op=egg");
		}else if (getsetting("hasegg",0)>0){
			output("Wenn dir hier niemand sagen kann, wo sich der Besitzer des goldenen Eis aufhält, dann wird es dir niemand sagen können.`n`nDie Veteranen unterhalten sich:`n");
			addnav("Nach dem goldenen Ei fragen","rock.php?op=egg2");
		}

		viewcommentary("veterans","Angeben:",10,"prahlt");
		addnav("Schrein des Luzifer","shrine.php");
		addnav("Schrein der Erneuerung","rebirth.php");
	}else{
		page_header("Seltsamer Felsen");
		output("Du näherst dich dem seltsam aussehenden Felsen. Nachdem du ihn eine ganze Weile angestarrt hast, bleibt es auch weiterhin nur ein seltsam aussehnder Felsen.`n`n");
		output("Gelangweilt gehst du zum Dorfplatz zurück.");
	}
}
addnav("Der Bettelstein","beggar.php");
addnav("Zurück zud Klingengasse","village.php");

page_footer();
?>