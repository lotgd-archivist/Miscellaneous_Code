<?php
/*
 * basierend auf Eliwood's ShowForm (auf anpera.net) von Dweo Dao für palanparth.de kontakt unter questbraxel@web.de
 */
Require 'lib/showform.class.php';
require_once "common.php";
$userid = $session ['user'] ['acctid'];

if ($_GET ['op2'] == "save") {
    popup_header ( "Dein Charakter" );
    
    $geburtsname = $_POST [geburtsname];
    $geburtstag = $_POST [geburtstag];
    $geburtsort = $_POST [geburtsort];
    $familie = $_POST [familie];
    $jahre = $_POST [jahre];
    $groese = $_POST [groese];
    $gewicht = $_POST [gewicht];
    $haarfarbe = $_POST [haarfarbe];
    $augenfarbe = $_POST [augenfarbe];
    $merkmale = $_POST [merkmale];
    $lfarbe = $_POST [lfarbe];
    $lzahl = $_POST [lzahl];
    $lbuch = $_POST [lbuch];
    $lspeise = $_POST [lspeise];
    $lgetraenk = $_POST [lgetraenk];
    $lwaffe = $_POST [lwaffe];
    $totfeind = $_POST [totfeind];
    $lebensmotto = $_POST [lebensmotto];
    $hname = $_POST [hname];
    $hart = $_POST [hart];
    $hbesonderes = $_POST [hbesonderes];
    db_query ( "UPDATE `charabogen` SET `geburtsname` = '$geburtsname' WHERE `id` = '$userid';" );
    db_query ( "UPDATE `charabogen` SET `geburtstag` = '$geburtstag' WHERE `id` = '$userid';" );
    db_query ( "UPDATE `charabogen` SET `geburtsort` = '$geburtsort' WHERE `id` = '$userid';" );
    db_query ( "UPDATE `charabogen` SET `familie` = '$familie' WHERE `id` = '$userid';" );
    db_query ( "UPDATE `charabogen` SET `jahre` = '$jahre' WHERE `id` = '$userid';" );
    db_query ( "UPDATE `charabogen` SET `groese` = '$groese' WHERE `id` = '$userid';" );
    db_query ( "UPDATE `charabogen` SET `gewicht` = '$gewicht' WHERE `id` = '$userid';" );
    db_query ( "UPDATE `charabogen` SET `haarfarbe` = '$haarfarbe' WHERE `id` = '$userid';" );
    db_query ( "UPDATE `charabogen` SET `augenfarbe` = '$augenfarbe' WHERE `id` = '$userid';" );
    db_query ( "UPDATE `charabogen` SET `merkmale` = '$merkmale' WHERE `id` = '$userid';" );
    db_query ( "UPDATE `charabogen` SET `lfarbe` = '$lfarbe' WHERE `id` = '$userid';" );
    db_query ( "UPDATE `charabogen` SET `lzahl` = '$lzahl' WHERE `id` = '$userid';" );
    db_query ( "UPDATE `charabogen` SET `lbuch` = '$lbuch' WHERE `id` = '$userid';" );
    db_query ( "UPDATE `charabogen` SET `lspeise` = '$lspeise' WHERE `id` = '$userid';" );
    db_query ( "UPDATE `charabogen` SET `lgetraenk` = '$lgetraenk' WHERE `id` = '$userid';" );
    db_query ( "UPDATE `charabogen` SET `lwaffe` = '$lwaffe' WHERE `id` = '$userid';" );
    db_query ( "UPDATE `charabogen` SET `totfeind` = '$totfeind' WHERE `id` = '$userid';" );
    db_query ( "UPDATE `charabogen` SET `lebensmotto` = '$lebensmotto' WHERE `id` = '$userid';" );
    db_query ( "UPDATE `charabogen` SET `hname` = '$hname' WHERE `id` = '$userid';" );
    db_query ( "UPDATE `charabogen` SET `hart` = '$hart' WHERE `id` = '$userid';" );
    db_query ( "UPDATE `charabogen` SET `hbesonderes` = '$hbesonderes' WHERE `id` = '$userid';" );
    reset ( $_POST );
}

if ($_GET ['op'] == "change") {
    popup_header ( "Dein Charakter" );
    addnav ( "Dein Charakter" );
    addnav ( "Zurück", "outtime.php" );
    
    $sql = "SELECT * FROM charabogen WHERE `id` = '$userid'";
    db_query ( $sql );
    $result = db_query ( $sql );
    $row = db_fetch_assoc ( $result );
    $cname = $session ['user'] ['name'];
    
    $form = array (
            'Herkunft',
            'geburtsname' => array (
                    'Geburtsname',
                    'text',
                    'default' => $row ['geburtsname'] 
            ),
            'geburtstag' => array (
                    'Geburtstag',
                    'text',
                    'default' => $row ['geburtstag'] 
            ),
            'geburtsort' => array (
                    'Geburtsort',
                    'text',
                    'default' => $row ['geburtsort'] 
            ),
            'familie' => array (
                    'Familienstand',
                    'text',
                    'default' => $row ['familie'] 
            ),
            'Aussehen',
            'jahre' => array (
                    'Alter',
                    'text',
                    'default' => $row ['jahre'] 
            ),
            'groese' => array (
                    'Größe',
                    'text',
                    'default' => $row ['groese'] 
            ),
            'gewicht' => array (
                    'Gewicht',
                    'text',
                    'default' => $row ['gewicht'] 
            ),
            'haarfarbe' => array (
                    'Haarfarbe',
                    'text',
                    'default' => $row ['haarfarbe'] 
            ),
            'augenfarbe' => array (
                    'Augenfarbe',
                    'text',
                    'default' => $row ['augenfarbe'] 
            ),
            'merkmale' => array (
                    'Besondere Merkmale',
                    'text',
                    'default' => $row ['merkmale'] 
            ),
            'Vorlieben',
            'lfarbe' => array (
                    'Liebste Farbe',
                    'text',
                    'default' => $row ['lfarbe'] 
            ),
            'lzahl' => array (
                    'Liebste Zahl',
                    'text',
                    'default' => $row ['lzahl'] 
            ),
            'lbuch' => array (
                    'Liebstes Buch',
                    'text',
                    'default' => $row ['lbuch'] 
            ),
            'lspeise' => array (
                    'Liebste Speiße',
                    'text',
                    'default' => $row ['lspeise'] 
            ),
            'lgetraenk' => array (
                    'Liebstes Getränk',
                    'text',
                    'default' => $row ['lgetraenk'] 
            ),
            'lwaffe' => array (
                    'Bevorzugte Waffe',
                    'text',
                    'default' => $row ['lwaffe'] 
            ),
            'Haustier',
            'hname' => array (
                    'Name des Haustiers',
                    'text',
                    'default' => $row ['hname'] 
            ),
            'hart' => array (
                    'Art des Haustiers',
                    'text',
                    'default' => $row ['hart'] 
            ),
            'hbesonderes' => array (
                    'Besonderheiten des Haustiers',
                    'text',
                    'default' => $row ['hbesonderes'] 
            ),
            'Besonderes',
            'totfeind' => array (
                    'Totfeind',
                    'text',
                    'default' => $row ['totfeind'] 
            ),
            'lebensmotto' => array (
                    'Lebensmotto',
                    'text',
                    'default' => $row ['lebensmotto'] 
            ) 
    );
    
    $Showform = new Showform ( 'Dein Charakter', $form, ($i = ($row = array ())) );
    $Showform->enableSave ();
    output ( "<form action=\"charabogen.php?op=change&op2=save\" method=\"post\">", true );
    addnav ( "", "charabogen.php?op=change&op2=save" );
    output ( "`c`b`2$cname`b`c`n" );
    unset ( $Showform );
    output ( '</form>', true );
} elseif ($_GET ['op'] == "show") {
    popup_header ( "Ein fremder Held" );
    
    $result = db_query ( "SELECT acctid,name FROM accounts WHERE login='$_GET[char]'" );
    $row = db_fetch_assoc ( $result );
    $row [login] = rawurlencode ( $row [login] );
    $fremdid = $row ['acctid'];
    $fname = $row ['name'];
    
    $sql = "SELECT * FROM charabogen WHERE `id` = '$fremdid'";
    db_query ( $sql );
    $result = db_query ( $sql );
    $row = db_fetch_assoc ( $result );
    
    $form = array (
            'Herkunft',
            'geburtsname' => array (
                    'Geburtsname',
                    'hidden',
                    'default' => $row ['geburtsname'] 
            ),
            'geburtstag' => array (
                    'Geburtstag',
                    'hidden',
                    'default' => $row ['geburtstag'] 
            ),
            'geburtsort' => array (
                    'Geburtsort',
                    'hidden',
                    'default' => $row ['geburtsort'] 
            ),
            'familie' => array (
                    'Familienstand',
                    'hidden',
                    'default' => $row ['familie'] 
            ),
            'Aussehen',
            'jahre' => array (
                    'Alter',
                    'hidden',
                    'default' => $row ['jahre'] 
            ),
            'groese' => array (
                    'Größe',
                    'hidden',
                    'default' => $row ['groese'] 
            ),
            'gewicht' => array (
                    'Gewicht',
                    'hidden',
                    'default' => $row ['gewicht'] 
            ),
            'haarfarbe' => array (
                    'Haarfarbe',
                    'hidden',
                    'default' => $row ['haarfarbe'] 
            ),
            'augenfarbe' => array (
                    'Augenfarbe',
                    'hidden',
                    'default' => $row ['augenfarbe'] 
            ),
            'merkmale' => array (
                    'Besondere Merkmale',
                    'hidden',
                    'default' => $row ['merkmale'] 
            ),
            'Vorlieben',
            'lfarbe' => array (
                    'Liebste Farbe',
                    'hidden',
                    'default' => $row ['lfarbe'] 
            ),
            'lzahl' => array (
                    'Liebste Zahl',
                    'hidden',
                    'default' => $row ['lzahl'] 
            ),
            'lbuch' => array (
                    'Liebstes Buch',
                    'hidden',
                    'default' => $row ['lbuch'] 
            ),
            'lspeise' => array (
                    'Liebste Speiße',
                    'hidden',
                    'default' => $row ['lspeise'] 
            ),
            'lgetraenk' => array (
                    'Liebstes Getränk',
                    'hidden',
                    'default' => $row ['lgetraenk'] 
            ),
            'lwaffe' => array (
                    'Bevorzugte Waffe',
                    'hidden',
                    'default' => $row ['lwaffe'] 
            ),
            'Haustier',
            'hname' => array (
                    'Name des Haustiers',
                    'hidden',
                    'default' => $row ['hname'] 
            ),
            'hart' => array (
                    'Art des Haustiers',
                    'hidden',
                    'default' => $row ['hart'] 
            ),
            'hbesonderes' => array (
                    'Besonderheiten des Haustiers',
                    'hidden',
                    'default' => $row ['hbesonderes'] 
            ),
            'Besonderes',
            'totfeind' => array (
                    'Totfeind',
                    'hidden',
                    'default' => $row ['totfeind'] 
            ),
            'lebensmotto' => array (
                    'Lebensmotto',
                    'hidden',
                    'default' => $row ['lebensmotto'] 
            ) 
    );
    
    $Showform = new Showform ( 'Ein fremder Held', $form, ($i = ($row = array ())) );
    // $Showform->enableSave();
    output ( "`c`b`2$fname`b`c`n" );
    unset ( $Showform );
}
if ($_GET [ret] == "") {
    addnav ( "Zur Liste der Krieger", "list.php" );
} else {
    $return = preg_replace ( "'[&?]c=[[:digit:]-]+'", "", $_GET [ret] );
    $return = substr ( $return, strrpos ( $return, "/" ) + 1 );
    addnav ( "Zurück", $return );
}

popup_footer ();
?> 