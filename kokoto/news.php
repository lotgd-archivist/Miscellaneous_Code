<?php
require_once "common.php";
if ((int)$_GET['r']) $r="?r=".(int)$_GET['r'];
if ($session['user']['loggedin']){
if ($session['user']['namecheck']<=2){
  
   redirect("enter.php");
}
}
if ($session['user']['slainby']!=''){
	page_header("Du wurdest besiegt!");
		output("`\$Du wurdest in ".$session['user']['killedin']."`\$ von `%".$session['user']['slainby']."`\$ besiegt und um alles Gold beraubt, das du bei dir hattest. Das kostet dich 5% deiner Erfahrung. Meinst du nicht es ist Zeit für Rache?");
	addnav("Weiter",$REQUEST_URI);
	$session['user']['slainby']='';
	page_footer();
}else{
	
	if ($session['user']['loggedin']) checkday();


	$newsperpage=50;
		
	page_header("LoGD News");
	if ($session['user']['superuser']>=4){
		output("`0<form action=\"news.php\" method='POST'>[Admin] Meldung manuell eingeben? <input name='meldung' size='40'> <input type='submit' class='button' value='Eintragen'>`n`n",true);
		allownav("news.php");
		if ($_POST['meldung']){
			$sql = "INSERT INTO news(newstext,newsdate,accountid) VALUES ('".mysql_real_escape_string($_POST['meldung'])."',NOW(),0)";
			db_query($sql);
			$_POST['meldung']='';
		}
		allownav('news.php');
	}
	if (!$session['user']['loggedin']) {
		addnav('Login', 'index.php'.$r);
	} else if ($session['user']['alive']){
		addnav('Dorfplatz','village.php');
	}else{
		if($session['user']['sex'] == 1) {
			addnav('Du bist tot, Jane!');
		} else {
			addnav('Du bist tot, Jim!');
		}
		addnav('Profil','prefs.php');
		addnav('Land der Schatten','shades.php');
		addnav('Log out','login.php?op=logout');

	}
	if ($session['user']['superuser']>=4){
		addnav("Neuer Tag","newday.php");
	}
	$offset = (int)$_GET['offset'];
	$timestamp=strtotime((0$offset)." days");
	$sql = "SELECT count(newsid) AS c FROM news WHERE newsdate='".date("Y-m-d",$timestamp)."'";
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	$totaltoday=$row['c'];
	$pageoffset = (int)$_GET['page'];
	if ($pageoffset>0) $pageoffset--;
	$pageoffset*=$newsperpage;
	$sql = "SELECT * FROM news WHERE newsdate='".date("Y-m-d",$timestamp)."' ORDER BY newsid DESC LIMIT $pageoffset,$newsperpage";
	$result = db_query($sql);
	$date=date("D, M j, Y",$timestamp);
	
	output("`c`b`!News für $date".($totaltoday>$newsperpage?" (Meldungen ".($pageoffset1)." - ".min($pageoffset$newsperpage,$totaltoday)." von $totaltoday)":"")."`c`b`0");
	
	for ($i=0;$i<db_num_rows($result);$i++){
		$row = db_fetch_assoc($result);
		output("`c`2-=-`@=-=`2-=-`@=-=`2-=-`@=-=`2-=-`0`c");
		if ($session['user']['superuser']>=4){
			output("[ <a href='superuser.php?op=newsdelete&newsid=".$row['newsid']."&return=".URLEncode($_SERVER['REQUEST_URI'])."'>Del</a> ]&nbsp;",true);
			allownav("superuser.php?op=newsdelete&newsid=".$row['newsid']."&return=".URLEncode($_SERVER['REQUEST_URI']));
		}
		output("{$row['newstext']}`0`n");
	}
	if (db_num_rows($result)==0){
		output("`c`2-=-`@=-=`2-=-`@=-=`2-=-`@=-=`2-=-`0`c");
		output("`1`b`c Es ist nichts erwähnenswertes passiert. Alles in allem bisher ein langweiliger Tag. `c`b`0");
	}
	output("`c`2-=-`@=-=`2-=-`@=-=`2-=-`@=-=`2-=-`0`c");
	if ($totaltoday>$newsperpage){
		addnav("Heutige News");
		for ($i=0;$i<$totaltoday;$i+=$newsperpage){
			addnav("Seite ".($i$newsperpage1),"news.php?offset=$offset&page=".($i$newsperpage1));
		}
	}
if ($session['user']['loggedin']) {
	addnav("Vorherige News","news.php?offset=".($offset1));
	if ($offset>0){
		addnav("Nächste News","news.php?offset=".($offset1));
	}
}
	page_footer();
}
?>