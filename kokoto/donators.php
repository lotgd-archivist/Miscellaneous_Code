<?
require_once "common.php";
isnewday(4);

page_header("Spenderseite");
addnav("G?Zurück zur Grotte","superuser.php");
addnav("W?Zurück zum Weltlichen","village.php");

output("<form action='donators.php?op=add1' method='POST'>`bDonationpoints vergeben:`b`nCharakter: <input name='name'> `nPunkte: <input name='amt' size='4'>`n<input type='submit' class='button' value='Donation hinzufügen'></form>",true);
allownav('donators.php?op=add1');


if ($_GET['op']==''){
	$sql = "SELECT name,donation,donationspent FROM accounts WHERE donation>0 ORDER BY donation DESC";
	$result = db_query($sql);
	
	output("<table border='0' cellpadding='5' cellspacing='0'><tr><td>Name</td><td>Punkte</td><td>Ausgegeben</td></tr>",true);
	for ($i=0;$i<db_num_rows($result);$i++){
		$row = db_fetch_assoc($result);
		output("<tr class='".($i2?"trlight":"trdark")."'>",true);
		rawoutput('<td>');
		output("`^{$row['name']}`0",true);
		rawoutput('</td><td>');
		output("`@".number_format($row['donation'])."`0</td><td>`%".number_format($row['donationspent'])."`0</td></tr>",true);
	}
	rawoutput('</table>');
}else if ($_GET['op']=='add1'){
	$search="%";
	for ($i=0;$i<strlen_c($_POST['name']);$i++){
		$search.=substr_c($_POST['name'],$i,1)."%";
	}
	$sql = "SELECT name,acctid,donation,donationspent FROM accounts WHERE login LIKE '$search'";
	$result = db_query($sql);
	output("Bestätige {$_POST['amt']} Punkte an:`n`n");
	for ($i=0;$i<db_num_rows($result);$i++){
		$row = db_fetch_assoc($result);
		rawoutput("<a href='donators.php?op=add2&id={$row['acctid']}&amt={$_POST['amt']}'>");
		output($row['name']." ({$row['donation']}/{$row['donationspent']})");
		rawoutput('</a><br />');
		allownav("donators.php?op=add2&id={$row['acctid']}&amt={$_POST['amt']}");
	}
}else if ($_GET['op']=='add2'){
	if ($_GET['id']==$session['user']['acctid']){
		$session['user']['donation']+=(int)$_GET['amt'];
	}else{

	$sql = "UPDATE accounts SET donation=donation+'".(int)$_GET['amt']."' WHERE acctid='".(int)$_GET['id']."'";
	db_query($sql);
systemmail((int)$_GET['id'],"`^Donationpoints!!`0","`&{$session['user']['name']}`6 hat dir `^".(int)$_GET['amt']."`6 Donationpoints gegeben.");
}
	$_GET['op']='';
}
$session['user']['standort'] = "Geheime Grotte";
page_footer();
?>