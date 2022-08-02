<?php /* Neuer Tanzsaal (2.2) */
/**
 * Tanzsaal für LotgD 0.97
 * by Auric @ http://www.tharesia.de
 * webmaster@blood-reaver.de
 * Version 2.2
 * Last Change: So, 04.03.09 19:45
 * Abzug von Runden-Exploit entfernt
 * Veröffentlicht unter GNU GPL
 */
/*

#In jedem Falle muss jedoch dieser Datenbankbefehl ausgeführt werden:
ALTER TABLE `accounts`
	ADD `tanz_mode` ENUM( 'nichts', 'hat', 'wurde' ) NOT NULL ,
	ADD `tanz_partner` INT NOT NULL DEFAULT '0',
	ADD `tanz_heute` INT( 1 ) NOT NULL DEFAULT '0';

*/
/* Prekonfiguration */
require_once ("common.php");
page_header("Tanzsaal");
addcommentary();
checkday();
$charmebonus = 2; // Hier einstellen, wie viele Charmepunkte man bekommt


/* Funktionen */
function checkdance() {
	global $session;
	if ($session['user']['tanz_mode'] != 'nichts')
		return $session['user']['tanz_partner'];
	else
		return false;
}

function partner() {
	global $session;
	$sql = "SELECT `acctid`, `name`, `login`, `sex`, `turns` FROM `accounts` WHERE `acctid` = ".$session['user']['tanz_partner']." LIMIT 1";
	$result = db_query($sql);
	return db_fetch_assoc($result);
}

/* Haupt-Script */
if ($_GET['op'] == "absagen") {
	$partner = partner();
	/* Daten übermitteln und zurücksetzen */
	systemmail($partner['acctid'], "`4Aufforderung abgelehnt", "`$ ".$session['user']['name']." `4hat deine Aufforderung zum Tanz leider abgelhnt. `nVieleicht findest du ja einen anderen Partner.");
	$session['user']['tanz_mode'] = 'nichts';
	$session['user']['tanz_partner'] = 0;
	$sql = "UPDATE `accounts` SET `tanz_mode` = 'nichts', `tanz_partner` = 0 WHERE `acctid` = ".$partner['acctid']." LIMIT 1";
	db_query($sql);
	
	/* Auswirkungen */
	switch (mt_rand(1,5)) {
		case 2:
			output('`6Zwar hast du`$ '.$partner['name'].' `6die eiskalte Schulter gezeigt, aber so wirklich gut fühlst du dich trotzdem nicht.`n `%Dir vergeht die Laune und du möchtest heute nicht mehr tanzen.');
			
			$session['user']['tanz_heute'] = 1;
			break;
		case 3:
			output('`^Du sagst`$ '.$partner['name'].' `^ab, doch anscheinend wirst du dabei von einigen anderen Tänzern gesehen, die sofort zu lästern beginnen.`n `5Du verlierst daher an Charme');
			$session['user']['charm']--;
			addnav('Zurück zur Tanzfläche', 'tanzsaal.php');
			break;
		case 4:
			output('`9Du willst`$ '.$partner['name'].' `9absagen doch bringst es nicht übers Herz. Stattdessen geht ihr gemeinsam etwas trinken, wobei du es dann doch über dich bringst. '.$partner['name'].' `#ist zwar ein wenig enttäuscht, bedankt sich aber für deine Höflichkeit. Sogleich fühlst du dich charmanter!');
			addnav('Zurück zur Tanzfläche', 'tanzsaal.php');
			break;
		default:
			output('`@Du sagst '.$partner['name'].'`@ ab und machst dich statdessen auf die Suche nach einem anderen Tanzpartner`n`n');
			addnav('Zurück zur Tanzfläche', 'tanzsaal.php');
			break;
	}
} elseif ($_GET['op'] == 'auffordern') {
	$session['user']['tanz_partner'] = (int)$_GET['id'];
	$partner = partner();
	output('`^Du nimmst deinen Mut zusammen und gehst auf `@'. $partner['name'] .' `^ zu, um '. ($partner['sex'] ? 'sie' : 'ihn') .' zum Tanz aufzufordern.`n');
	if ($session['user']['turns'] < 1 || $session['user']['tanz_heute'] == 1) {
		output('Aber schon als du auf '. ($partner['sex'] ? 'sie' : 'ihn') .' zu gehst, merkst du, das du heute einfach nicht mehr die kondition dafür hast, noch einmal zu tanzen.');
		$session['user']['tanz_partner'] = 0;
	} else {
		$sql = "UPDATE `accounts` SET `tanz_mode` = 'wurde', `tanz_partner` = " . $session['user']['acctid'] . " WHERE `acctid` = " . (int)$_GET['id'] . " LIMIT 1";
		db_query($sql);
		$session['user']['tanz_mode'] = 'hat';
		output('`6Nachdem du '. $partner['name'] .' `6nun tatsächlich angesprochen hast, musst du nur noch abwarten, ob '.($partner['sex'] ? 'sie' : 'er').' an nimmt.');
		systemmail($partner['acctid'], "`^Tanzaufforderung`0", "`$ " . $session['user']['name'] . " `%hat dich zu einem Tanz im Saal aufgefordert!`nNun ist deine Entscheidung vonnöten!");
	}
	
	addnav("Zurück in den Saal", "tanzsaal.php");
} elseif ($_GET['op'] == 'suchen') {
	output('`gDu gehst umher und suchst nach einem passenden Tanzpartner`n');
	if (isset($_POST['search']) || $_GET['search'] > '') {
		if ($_GET['search'] > '')
			$_POST['search'] = $_GET['search'];
		$search = "%";
		for($x = 0; $x < strlen_c($_POST['search']); $x++) {
			$search .= substr_c($_POST['search'], $x, 1) . "%";
		}
		$search = "name LIKE '" . $search . "' AND ";
	} else {
		$search = '';
	}
	$ppp = 20; // Player Per Page to display
	if (!$_GET['limit']) {
		$page = 0;
	} else {
		$page = (int) $_GET['limit'];
		addnav('Vorherige Seite', "tanzsaal.php?op=suchen&limit=".($page1)."&search=".$_POST['search']);
	}
	$limit = "".($page  $ppp).",".($ppp  1);
	output("Für wen entscheidest du dich?`n`n");
	rawoutput("<form action='tanzsaal.php?op=suchen' method='POST'>Nach Name suchen: <input name='search' value='".$_POST['search']."'><input type='submit' class='button' value='Suchen'></form>");
	allownav('tanzsaal.php?op=suchen');
	
	$sql = "SELECT acctid,name,sex,level,race,login,marriedto,charisma,tanz_mode FROM accounts WHERE $search (acctid <> " . $session['user']['acctid'] . ") AND sex=". ($session['user']['sex'] ? '0' : '1') ." AND(laston > '" . date("Y-m-d H:i:s", strtotime(date("c") . "-346000 sec")) . "') ORDER BY charm DESC LIMIT $limit";
	$result = db_query($sql);
	rawoutput('<table border="0" cellpadding="3" cellspacing="4"> <tr><td><b>Name</b></td><td><b>Rasse</b></td><td><b>Status</b></td><td><b>Ops</b></td></tr>');
	if (db_num_rows($result) > $ppp)
		addnav('Nächste Seite', "tanzsaal.php?op=suchen&limit=".($page  1)."&search=".$_POST['search']);
	for($i = 0; $i < db_num_rows($result); $i++) {
		$row = db_fetch_assoc($result);
		if ($session['user']['prefs']['biopop']==1){
		$biolink = "biopop.php?id=".$row['acctid']."' target=\"_blank\" onClick=\"".popup("biopop.php?id=".$row['acctid']."").";return false;\" ";
		}else{
		$biolink = "bio.php?id=".$row['acctid']."&ret=".urlencode($_SERVER['REQUEST_URI']);
		}
		$tanzlink = 'tanzsaal.php?op=auffordern&id='.$row['acctid'];
		allownav($biolink);
		allownav($tanzlink);
		output("<tr class=" . ($i  2 ? "trlight" : "trdark") . "'><td>".$row['name']."</td><td>".$colraces[$row['race']]."</td>", true);
		if ($row['tanz_mode'] == 'hat')
			rawoutput('<td>Hat jemanden aufgefordert</td>');
		elseif ($row['tanz_mode'] == 'wurde')
			rawoutput('<td>Wurde aufgefordert</td>');
		else
			rawoutput("<td><i>Ist noch frei</i></td><td>[ <a href='".$biolink."'>Bio</a>".($row['tanz_mode'] == 'nichts' ? "| <a href='".$tanzlink."'>Tanzen</a>" : "") . "]</td></tr>");
	}
	rawoutput('</table>');
	addnav('zurück zum Saal', 'tanzsaal.php');
	addnav('Nochmal sehen', 'tanzsaal.php?op=suchen');
} elseif ($_GET['op'] == 'tanzen') {
	$partner = partner();
	if ($partner['turns'] < 0) {
		output('`^Gerade willst du mit '. $partner['name'] .' `^zur Tanzfläche schreiten, als du bemerkst, das '. ($partner['sex'] ? 'sie' : 'er') .' nach all dem Kämpfen im Walde schon viel zu erschöpft ist.`n Daher berschließt ihr euren Tanz auf später zu verschieben.');
	} else {
		output('`%Du und '.$partner['name'] .' `%habt nun endlich Zeit für einen Tanz gefunden und begebt euch gemeinsam auf die Tanzfläche. Ihr beginnt euch zur Musik zu bewegen und werdet dabei immer schneller. Du fühlst dich außerordentlich gut dabei.`n doch in der Freude geht alles so schnell vorbei, das du dich und '. $partner['name'] .'`% schon bald etwas erschöpft am Rand wiederfindest. `nZufrieden blickst du '. $partner['name'] .'`% an und bedankst dich. Ihr versprecht euch, bald wieder einmal gemeinsam zu tanzen und geht dann.');
		addnav('Zurück un den Saal', 'tanzsaal.php');
		$session['user']['turns']--;
		$session['user']['charm'] += $charmebonus;
		$session['user']['tanz_heute'] = 1;
		$session['user']['tanz_mode'] = 'nichts';
		$session['user']['tanz_partner'] = 0;
		$sql = "UPDATE `accounts` SET `charm` = `charm` + 1, `turns` = `turns` -1, `tanz_mode` = 'nichts', `tanz_partner` = 0 WHERE acctid = " . $partner['acctid'] . " LIMIT 1";
		db_query($sql);
		systemmail($partner['acctid'], "`^Aufforderung angenommen", "`^ ".$session['user']['name']." `6hat deine Aufforderung zum Tanz angenommen und ihr habt eine schöne Zeit gehabt ihr versprecht euch, bald wieder einmal tanzen zu gehen. Vielleicht wird da ja mehr draus?");
	}
} else {
	/* Eingang */
	output('`gDu betrittst den großen Tanzsaal. Überall siehst die verliebte oder befreundete Pärchen, die sich auf dem Parkett bewegen oder an einigen Tischen weiter hinten im Saal sitzten.`n Etwas erhöht auf einer Bühne siehst du eine Hand voll Musiker, die ihren Instumenten die Klänge entlocken, zu denen getanzt wird.`n`n Alles wirkt sehr festlich und dir wird bewusst, das du dich hier besser benehmen solltest.`n`n');
	if (checkdance() === false) {
		if ($session['user']['sex'] == 1) {
		output('`QMöchtest du nicht vieleicht nach einem hübschen Herren als Tanzpartner ausschau halten?`nOder traust du dich nicht und willst lieber wieder gehen?`n');
		} else {
		output('`6Viele hübsche junge Damen laufen hier herum, möchtest du eine von ihnen zum Tanz auffordern, oder lieber verschwinden, ehe dich jemand sieht?`n');
		}
		addnav('`4Einen Tanzpartner suchen', 'tanzsaal.php?op=suchen');
	} elseif ($session['user']['tanz_mode'] == 'wurde') {
		/* Tanzpartner hat User aufgefordert */
		$partner = partner();
		output('`9Als du dich gerade ein wenig umsiehst, bemerkst du, das jemand auf dich zukommt, den du als `@'. $partner['name'] .'`9 erkennst.`n'.($partner['sex'] ? 'Sie' : 'Er').' begrüßt dich höflich und fordert dich galant zu einem Tanz auf.');
		if ($session['user']['tanz_heute'] == 1 || $session['user']['turns'] < 1) {
			output('`n`i`^Doch du bemerkst, dass du heute schon viel zu erschöpft bist, um noch einmal zu tanzen.`i');
		} else {
			addnav('Annehmen', 'tanzsaal.php?op=tanzen');
		}
		addnav('Absagen', 'tanzsaal.php?op=absagen');
	} else {
		/* User hat Tanzpartner aufgefordert */
		$partner = partner();
		output('`^Gerade in diesem Moment fällt dir ein, das du ja noch eine Verabredung mit `@'. $partner['name'] .'`^ ausstehen hast. Allerdings scheint '. ($partner['sex'] ? "sie" : "er") .' noch immer nicht hier zu sein. Kurz überlegst du, dir einen anderen Tanzpartner zu suchen...');
		addnav('Aktionen');
		addnav('`^'.$partner['login'].'`0 absagen', 'tanzsaal.php?op=absagen');
	} // Ende checkdance-Prüfung
	rawoutput('<br />');
	viewcommentary("Tanzsaal", "Mit anderen Tänzern unterhalten", 20);
	
}
addnav('Ausgang');
addnav('Zurück (Vergnügunsviertel)', 'village.php?op=vergnueg');
addnav('Nach draußen ins Dorf', 'village.php');
page_footer();
?>