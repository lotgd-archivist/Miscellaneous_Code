<?php

/*
 * eigenständiger Teil eines Spielertagebuches deren Idee auf dem Original von :kelko: /******************************************************************** * developed by: * :kelko: * kelko < at > anakrino <.> de * http://kelko.anakrino.de * * * ******************************************************************** Vielen Dank dafür umgeschrieben als eigeneständiges Script und modifiziert und angepasst für Silienta von: Contact: Rikkarda@silienta-logd.de www.silienta-logd.de anperanick: Rikkarda icq: 212 076 731 Entwicklerforum : http://www.dai-clan.de/SiliForum/wbb2/index.php Download des Pakets im Forum oder unter www.anpera.net inkl Einbauanleitung möglich
 */

/**
 * Allow these tags in Silienta sind bestimmte html codes zum einfügen von Bildern und Absätze per Entertaste erlaubt
 */
$allowedTags = '<br><b><h1><h2><h3><h4><i><hr>' . '<img><li><ol><p><strong><table>' . '<tr><td><th><u><ul><div><span><center><p><img>';

/**
 * Disallow these attributes/prefix within a tag (Sicherheitsfix um ausführbare Javascripte zu unterbinden)
 */
$stripAttrib = 'javascript&#058;|onclick|ondblclick|onmousedown|onmouseup|onmouseover|' . 'onmousemove|onmouseout|onkeypress|onkeydown|onkeyup|onabort|' . 'onfocus|onload|onblur|onchange|onerror|onreset|onselect|obsubmit|onunload|style';

/**
 * Strip forbidden tags and delegate tag-source check to removeEvilAttributes()
 *
 * @return string
 * @param
 *            string
 */
function removeEvilTags($source, $iframe_allowed) {
    global $allowedTags;
    if ($iframe_allowed == 1)
        $allowedTags .= "<iframe>";
    $source = strip_tags ( $source, $allowedTags );
    
    return preg_replace ( '/<(.*?)>/ie', "'<'.removeEvilAttributes('\\1').'>'", $source, $allowedTags );
}

/**
 * Strip forbidden attributes from a tag
 *
 * @return string
 * @param
 *            string
 */
function removeEvilAttributes($tagSource) {
    global $stripAttrib;
    return stripslashes ( preg_replace ( "/$stripAttrib/i", 'forbidden', $tagSource ) );
}

require_once "common.php";
checkday ();

$result = db_query ( 'SELECT login,emailaddress,name,level,sex,title,specialty' . ',hashorse,acctid,age,marriedto,charisma,resurrections,bio,dragonkills,pvpflag,race' . ',avatar,housekey,charm, punch' . 'kunst' );

$row = db_fetch_assoc ( $result );

// some 'shortcuts' wir sind faul in Silienta ^^
$row ['login'] = rawurlencode ( $row ['login'] );
$id = $row ['acctid'];
$owner = $row ['name'];
$char = rawurlencode ( $_GET ['char'] );

// check whether i look at my own profile
if ($row ['login'] == rawurlencode ( $session ['user'] ['login'] ))
    $myProf = true;
else
    $myProf = false;

if ($_GET [ret] == "") {
    
    /*
     * //Geändert von Val wg. besserer Listennavigation //source: 1 = Bioliste; 2 = Gildenliste if($_GET[source]!=""){ switch((int)$_GET[source]){ case 1: if($_GET[page] == "")addnav("Zurück zur Bioliste","list2.php"); else addnav("Zurück zur Bioliste","list2.php?page=".$_GET[page]); break; case 2: if($_GET[page] == "")addnav("Zurück zur Gildenliste","liste2.php"); else addnav("Zurück zur Gildenliste","liste2.php?page=".$_GET[page]); break; case 3: if($_GET[page] == "")addnav("Zurück zur Kämpferliste","list.php"); else addnav("Zurück zur Kämpferliste","list.php?page=".$_GET[page]); break; case 4: if($_GET[page] == "")addnav("Zurück zur Gildenauswahl","list.php?op=gilde&ID=".$_GET[ID]); else addnav("Zurück zur Gildenauswahl","list.php?op=gilde&page=".$_GET[page]."&ID=".$_GET[ID]); break; } } if($_GET[source] < 3) addnav("Zur Liste der Krieger","list.php"); //Ende Änderung Val wg. besserer Listennavigation
     */
} else {
    $return = preg_replace ( "'[&?]c=[[ igit:]-]+'", "", $_GET [ret] );
    $return = substr ( $return, strrpos ( $return, "/" ) + 1 );
    addnav ( "Zurück", $return );
}

// Geändert von Val wg. besserer Listennavigation
if ($_GET [source] != "")
    addnav ( "News anzeigen", "bio.php?char=$row[login]&op=shownews&ret=" . $_GET ['ret'] . "&source=" . $_GET [source] . "&page=" . $_GET [page] . "&ID=" . $_GET [ID] );
else
    addnav ( "News anzeigen", "bio.php?char=$row[login]&op=shownews&ret=" . $_GET ['ret'] );
    // Ende Änderung Val wg. besserer Listennavigation
    
// addnav("News anzeigen","bio.php?char=$row[login]&op=shownews&ret=".$_GET['ret']);

page_header ( "Tagebuch & Biografie: " . preg_replace ( "'[`].'", "", $row ['name'] ) );

/*
 * if($_GET['op']==""||$_GET['op']=="long") { $specialty = array(0=>"Unbekannt","Dunkle Künste","Mystische Kräfte","Diebeskunst","Feuer Künste","Wasser Magie","Erden Künste","Eismagie","Windmagie","Verwandlungsmagie","Nebelmagie","Sturmanrufung","Weisse Magie","Weather"); $kampfkunst=array(1=>"Schwertkampf",2=>"Axtkampf",3=>"Lanzenkampf",4=>"Bogenschütze",5=>"Anima-Magie",6=>"Schwarze Magie",7=>"Lichtmagie",8=>"Stabmagie",0=>"`(Unbekannt"); $klasse=array( 1=>"Söldner", 2=>"Myrmidone", 3=>"Kavalier", 4=>"Lord", 5=>"Lord", 6=>"Lord", 7=>"Ritter", 8=>"Bandit", 9=>"Pirat", 10=>"Kämpfer", 11=>"Bogenschütze", 12=>"Nomade", 13=>"Dieb/in", 14=>"Magier/in", 15=>"Schamane", 16=>"Mönch", 17=>"Geistliche/r", 18=>"Troubadour", 19=>"Pegasus-Ritter/in", 20=>"Wyvernritter/in", 101=>"Held", 102=>"Schwertmeister/in", 103=>"Rittmeister/in", 104=>"Edelmann", 105=>"Meister", 106=>"Herrscher", 107=>"General", 108=>"Berserker", 109=>"Berserker", 110=>"Krieger", 111=>"Scharfschütze", 112=>"Nomaden-Soldat", 113=>"Assasine", 114=>"Magier/in", 115=>"Schamane", 116=>"Bischof", 117=>"Bischof", 118=>"Walküre", 119=>"Falken-Ritter/in", 120=>"Wyvern-Lord", 0=>"`(Unbekannt");
 */

output ( "`^Biographie für $row[name]" );
if ($session ['user'] ['loggedin'])
    output ( "<a href=\"mail.php?op=write&to=$row[login]\" target=\"_blank\" onClick=\"" . popup ( "mail.php?op=write&to=$row[login]" ) . ";return false;\"><img src='images/newscroll.GIF' width='16' height='16' " . "alt='Mail schreiben' border='0'></a>", true );

if (getsetting ( "avatare", 0 ) == 1) {
    if ($row ['avatar']) {
        $pic_size = @getimagesize ( $row [avatar] );
        $pic_width = $pic_size [0];
        $pic_height = $pic_size [1];
        output ( "<table><tr><td valign='top'>`n`n<img src=\"$row[avatar]\" ", true );
        if ($pic_width > 200)
            output ( "width=\"200\" ", true );
        if ($pic_height > 200)
            output ( "height=\"200\" ", true );
        output ( "alt=\"" . preg_replace ( "'[`].'", "", $row [name] ) . "\">&nbsp;</td><td valign='top'>", true );
    } else
        output ( "<table><tr><td>(kein Bild)&nbsp;&nbsp;&nbsp;</td><td>", true );
}

output ( "`^Rasse: `@{$races[$row['race']]}`n" );
output ( "`^Alter: `@" . getpref ( "alter", "Unbekannt", $row ['acctid'] ) . "`n" );
// output("`^Herkunft: `@".getpref("herkunft","Unbekannt",$row['acctid'])."`n");
output ( "`^Geschlecht: `@" . ($row [sex] ? "Weiblich" : "Männlich") . "`n`n" );

output ( "`^Spezialgebiet: `@" . $specialty [$row [specialty]] . "`n" );
// output("`^Kunst: `@".$kampfkunst[$row[kunst]]."`n");
// output("`^Klasse: `@".$klasse[$row[klasse]]."`n");
// output("`^Waffe: `@".getpref("weaponname","Unbekannt",$row['acctid'])."`n");
// output("`^Rüstung: `@".getpref("armorname","Unbekannt",$row['acctid'])."`n`n");
// Guilds/Clans Change
/*
 * if ($row['guildID']!=0) { Require_once("guildclanfuncs.php"); $ThisGuild=$session['guilds'][$row['guildID']]; $GuildName=$ThisGuild['Name']; $PublicText=$ThisGuild['PublicText']; $PublicPic=$ThisGuild['PublicPic']; $sql2="select DisplayTitle from lotbd_guildranks where RankID='".$row['guildRank']."'"; $result2=db_query($sql2); $row2 = db_fetch_assoc($result2); $Rank=$row2['DisplayTitle']; output("`^Gilde: `@".$GuildName."`n",true); output("`^Rang: `@".$Rank."`n",true); output("`^Motto: `@".$PublicText."`n"); output("`^Banner:`n <img src=\"$ThisGuild[PublicPic]\" `n`n",true); } if ($row['clanID']!=0) { Require_once("guildclanfuncs.php"); $ThisClan=$session['guilds'][$row['clanID']]; $ClanName=$ThisClan['Name']; $PublicText=$ThisClan['PublicText']; $sql2="select DisplayTitle from lotbd_guildranks where RankID='".$row['guildRank']."'"; $result2=db_query($sql2); $row2 = db_fetch_assoc($result2); $Rank=$row2['DisplayTitle']; output("`^Clan: `@".$ClanName."`n",true); output("`^Rang: `@".$Rank."`n",true); output("`^Motto: `@".removeEvilTags($PublicText)."`n",true); } // End Guilds/Clans Change
 */

if ($row ['pvpflag'] == "5013-10-06 00:42:00")
    output ( "`4`iSteht unter besonderem Schutz`i" );
if (getsetting ( "avatare", 0 ) == 1)
    output ( "</td></tr></table>", true );
    /*
 * if ($row[herotattoo]) { output("`^Tätowierungen: "); for($i=1; $i<=$row[herotattoo];$i++){ output("`@$ghosts[$i]"); if ($i<$row[herotattoo]) output(", "); else output(""); } } //beginn tattoobilder idea by DOM von www.logd.gloth.org.de umgesetzt von Rikkarda@silienta-logd.de www.silienta-logd.de switch($row[herotattoo]) { case 12: rawoutput("<p><IMG SRC=\"images/pics/DEINBILD.gif\" align=\"left\"></p>"); case 11: rawoutput("<p><IMG SRC=\"images/pics/DEINBILD.gif\" align=\"left\"></p>"); case 10: rawoutput("<p><IMG SRC=\"images/pics/DEINBILD.gif\" align=\"left\"></p>"); case 9: rawoutput("<p><IMG SRC=\"images/pics/DEINBILD.gif\" align=\"left\"></p>"); case 8: rawoutput("<p><IMG SRC=\"images/pics/DEINBILD.gif\" align=\"left\"></p>"); case 7: rawoutput("<p><IMG SRC=\"images/pics/DEINBILD.gif\" align=\"left\"></p>"); case 6: rawoutput("<p><IMG SRC=\"images/pics/DEINBILD.gif\" align=\"left\"></p>"); case 5: rawoutput("<p><IMG SRC=\"images/pics/DEINBILD.gif\" align=\"left\"></p>"); case 4: rawoutput("<p><IMG SRC=\"images/pics/DEINBILD.gif\" align=\"left\"></p>"); case 3: rawoutput("</p><p><IMG SRC=\"images/pics/DEINBILD.gif\" align=\"left\"></p>"); case 2: rawoutput("</p><p><IMG SRC=\"images/pics/DEINBILD.gif\" align=\"left\"></p>"); case 1: rawoutput("<p><IMG SRC=\"images/pics/DEINBILD.gif\" align=\"left\"></p>"); break; } //end tattoobilder
 */

output ( "`n`n" );

// show the diary

output ( "`n`^Ausführliches Tagebuch & Biografie von $row[name]:" );

if ($myProf) {
    // i can add a chapter to my diary
    
    output ( "<a href='biodiary.php?op=newChapter&char=$char&ret=$_GET[ret]'>[Neuen Abschnitt]</a>", true );
    
    addnav ( "", "biodiary.php?op=newChapter&char=" . $char . "&ret=" . $_GET [ret] );
}

if ($session ['user'] ['superuser'] >= 3 || $myProf) {
    // me and the admins can delete the whole diary
    
    output ( "<a href='biodiary.php?op=wipe&char=$char&ret=$_GET[ret]'>`$[Leeren]</a>`n`n", true );
    
    addnav ( "", "biodiary.php?op=wipe&char=" . $char . "&ret=" . $_GET [ret] );
}

output ( "`n`n" );

$sql = "SELECT * FROM `diary` WHERE `acctid`='$id' ORDER BY `diaryID` ASC";

$bio_res = db_query ( $sql );

// showing each chapter
for($i = 0; $i < db_num_rows ( $bio_res ); $i ++) {
    
    $bio_row = db_fetch_assoc ( $bio_res );
    
    // the table is used for better centralized texts
    // no 'reorganizing' (shifting right) of the text when the paypal-icons end
    output ( "<table width='100%'><tr><td width='5%'></td><td width='90%'>", true );
    
    output ( "`c`!$bio_row[title]`0" );
    
    if ($session ['user'] ['superuser'] >= 3 || $myProf) {
        // admins and me may
        
        // edit this particular chapter
        output ( "<a href='biodiary.php?op=editChapter&no=$bio_row[diaryID]&char=" . $char . "&ret=" . $_GET [ret] . "'>[Bearbeiten]</a>", true );
        
        addnav ( "", "biodiary.php?op=editChapter&no=$bio_row[diaryID]&char=" . $char . "&ret=" . $_GET [ret] );
        
        // delete this particular chapter
        output ( "<a href='biodiary.php?op=delChapter&no=$bio_row[diaryID]&char=" . $char . "&ret=" . $_GET [ret] . "'>`$[Löschen]</a>", true );
        
        addnav ( "", "biodiary.php?op=delChapter&no=$bio_row[diaryID]&char=" . $char . "&ret=" . $_GET [ret] );
    }
    
    // expand the macro "/me" to the actual name
    
    $body = str_replace ( "/me", $owner, $bio_row ['body'] );
    
    // $body = .CloseTags(removeEvilTags(soap(nl2br($bio_row['body'])),$row['frame'])),true);
    
    output ( "`c`n" );
    
    // show this chapter
    output ( removeEvilTags ( soap ( nl2br ( $body ) ), "`c`b" ), true );
    
    output ( "</td><td width='5%'></td></tr></table>", true );
    
    output ( "`n`n" );
}

if ($_GET ['op'] == 'editChapter') {
    // edit a chapter
    
    output ( "<form action='biodiary.php?char=$char&op=progress&act=editChapter&ID=$_GET[no]&ret=$_GET[ret]' method='POST'>", true );
    
    $form = array (
            
            "Neues Kapitel,title",
            
            "diaryID" => "ID ,veryhidden",
            
            "title" => "Titel",
            
            "body" => "Inhalt,textarea,70,30" 
    );
    
    $bio_res = db_query ( "SELECT * FROM `diary` WHERE `diaryID`='" . $_GET ['no'] . "'" );
    
    $bio_row = db_fetch_assoc ( $bio_res );
    
    $prefs ['title'] = $bio_row ['title'];
    
    $prefs ['body'] = $bio_row ['body'];
    
    $prefs ['diaryID'] = $bio_row ['diaryID'];
    
    showform ( $form, $prefs );
    
    output ( "</form>", true );
    
    addnav ( "", "biodiary.php?char=$char&op=progress&act=editChapter&ID=$_GET[no]&ret=$_GET[ret]" );
} elseif ($_GET ['op'] == 'delChapter') {
    // delete a chapter
    
    $sql = "DELETE FROM `diary` WHERE `diaryID`='" . $_GET ['no'] . "'";
    
    db_query ( $sql );
    
    redirect ( "biodiary.php?op=long&char=$char&ret=$_GET[ret]" );
} elseif ($_GET ['op'] == 'newChapter') {
    // create a new chapter
    
    output ( "<form action='biodiary.php?op=progress&act=newChapter&char=$char&ret=$_GET[ret]' method='POST'>", true );
    
    $form = array (
            
            "Neues Kapitel,title",
            
            "title" => "Titel",
            
            "body" => "Inhalt,textarea,70,30" 
    );
    
    $prefs ['title'] = "";
    
    $prefs ['body'] = "";
    
    showform ( $form, $prefs );
    
    output ( "</form>", true );
    
    addnav ( "", "biodiary.php?op=progress&act=newChapter&char=" . $char . "&ret=" . $_GET [ret] );
} elseif ($_GET ['op'] == 'wipe') {
    // delete all chapters
    
    $sql = "DELETE FROM `diary` WHERE `acctid`='$id'";
    
    db_query ( $sql );
    
    redirect ( "biodiary.php?op=long&char=$char&ret=$_GET[ret]" );
    
    // end of the diary
} elseif ($_GET ['op'] == 'progress') {
    // saving all the changes
    // most of them and centralizing them by :kelko:
    
    if ($_GET ['act'] == 'editChapter') {
        // editing a chapter
        
        /*
         * $body = str_replace("\'","\\'", $_POST[body]); $body = str_replace("'","\\'", $_POST[body]); $title = str_replace("'","\\'", $_POST[title]); $title = str_replace("\'","\\'", $_POST[title]);
         */
        $body = mysql_real_escape_string ( stripslashes ( $_POST ['body'] ) );
        $title = mysql_real_escape_string ( stripslashes ( $_POST ['title'] ) );
        
        db_query ( "UPDATE diary SET title='$title', body='$body' WHERE diaryID='" . $_GET [ID] . "'" );
        
        output ( "Kapitel geändert" );
        redirect ( "biodiary.php?op=long&char=$char&ret=$_GET[ret]" );
    } else if ($_GET ['act'] == 'newChapter') {
        // creating a chapter
        
        /*
         * $body = str_replace("\'","\\'", $_POST[body]); $body = str_replace("'","\\'", $_POST[body]); $title = str_replace("'","\\'", $_POST[title]); $title = str_replace("\'","\\'", $_POST[title]);
         */
        $body = mysql_real_escape_string ( stripslashes ( $_POST ['body'] ) );
        $title = mysql_real_escape_string ( stripslashes ( $_POST ['title'] ) );
        
        db_query ( "INSERT INTO `diary`(`acctid`,`title`,`body`,`date`( VALUES('" . $session [user] [acctid] . "','$title','$body',now())" );
        
        output ( "Kapitel gespeichert" );
        redirect ( "biodiary.php?op=long&char=$char&ret=$_GET[ret]" );
    }
}
page_footer ();

?>