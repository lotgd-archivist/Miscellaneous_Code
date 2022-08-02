<?php

// 25052004

require_once "common.php";

if ($session['user']['loggedin']){
	page_header("Empfehlungen");
	addnav("H?Zurück zur Hütte","lodge.php");
	output("Du bekommst automatisch 50 Punkte für jeden geworbenen Charakter, der es bis Level 5 schafft.
	`n`n
	Woher weiss die Seite, dass du eine Person geworben hast?`n
  Kleinigkeit! Wenn du Freunden von dieser Seite erzählst, gib ihnen einfach folgenden Link:`n`n`q
  ".getsetting("serverurl","http://".$_SERVER['SERVER_NAME'].dirname($_SERVER['REQUEST_URI']))."/referral.php?r=".$session['user']['acctid']."`n`n
	`0Dadurch wird die Seite wissen, dass du derjenige warst, der ihn hergeschickt hat. Wenn er dann zum ersten Mal Level 5 erreicht, bekommst du deine Punkte!");
	
	$sql = "SELECT name,level,refererawarded FROM accounts WHERE referer={$session['user']['acctid']} ORDER BY dragonkills,level";
	$result = db_query($sql);
 rawoutput("<br /><br />Accounts, die du geworben hast:<br /><table border='0' cellpadding='3' cellspacing='0'><tr><td>Name</td><td>Level</td><td>Ausgezahlt?</td></tr>");
	for ($i=0;$i<db_num_rows($result);$i++){
		$row = db_fetch_assoc($result);
		output("<tr class='".($i2?"trlight":"trdark")."'><td>",true);
		output($row['name']);
		output("</td><td>{$row['level']}</td><td>".($row['refererawarded']?"`@Ja!`0":"`\$Nein!`0")."</td></tr>",true);
	}
	if (db_num_rows($result)==0){
		output("<tr><td colspan='3' align='center'>`iKeine!</td></tr>",true);
	}
	rawoutput('</table>');
	page_footer();
}else{
	$sql = "SELECT name FROM accounts WHERE acctid=".(int)$_GET['r'];
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	page_header("Willkommen bei Legend of the Green Dragon");
	output('`@Legend of the Green Dragon ist ein Remake des klassischen BBS Spiels `$Legend of the Red Dragon`@. Es ist ein Multiplayer Browserspiel, das heisst, 
	es muss keinerlei Programm heruntergeladen oder installiert werden.`n
	Komm rein und nehme an einem Abenteuer teil, das eines der ersten Multiplayer Rollenspiele der Welt darstellte!`n`n
	Hier schlüpfst du in die Rolle eines Kriegers in einer Fantasy-Welt, in der eine Legende von den riesigen Drachen die 
	Bewohner in Angst und Schrecken versetzt. Nunja, zumindest die meisten. Oder wenigstens ein paar.`n`n
	');
	output('`2<ul><li>Kämpfe gegen unzählige böse Kreaturen, die das Dorf bedrohen</li><li>Setze unterschiedliche Waffen ein und kaufe dir bessere Rüstungen</li><li>Erforsche das Dorf und den Wald und unterhalte dich mit anderen Kriegern</li><li>Besiege andere Spieler im Zweikampf - oder heirate sie<li>Finde und vernichte die Drachen, um im Ansehen zu steigen</li><li>Und vieles mehr</li></ul>`n`n',true);
	if ((int)$_GET['r']) output("`@Du wurdest von ".$row['name']."`@ hierher eingeladen, damit ihr gemeinsam gegen das Böse kämpfen könnt.");
	output("`@ Melde dich jetzt kostenlos an und werde Teil dieser Welt.");
	addnav("Navigation");
	addnav("Charakter erstellen","create.php?r=".(int)$_GET['r']);
	addnav("Zum Login","index.php?r=".(int)$_GET['r']);
	addnav('Was macht diesen Server aus?','description.php?r='.(int)$_GET['r']);
	page_footer();
}?>