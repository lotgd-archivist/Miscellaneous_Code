<?php
header('Content-Type: text/html; charset=utf-8');
// 21072004
// 11092017 changes for php 7.1

//do some cleanup here to make sure magic_quotes_gpc is ON, and magic_quotes_runtime is OFF, and error reporting is all but notice.
error_reporting (E_ALL ^ E_NOTICE);

// compability for old style global vars
$HTTP_GET_VARS = &$_GET;
$HTTP_POST_VARS = &$_POST;
$HTTP_COOKIE_VARS = &$_COOKIE;
$HTTP_SESSION_VARS = &$_SESSION;

if (!get_magic_quotes_gpc()){
    set_magic_quotes($_GET);
    set_magic_quotes($_POST);
    set_magic_quotes($_SESSION);
    set_magic_quotes($_COOKIE);
    ini_set("magic_quotes_gpc",1);
}

function set_magic_quotes(&$vars) {
    //eval("\$vars_val =& \$GLOBALS[$vars]$suffix;");
    if (is_array($vars)) {
        reset($vars);
        while (list($key,$val) = each($vars))
            set_magic_quotes($vars[$key]);
    }else{
        $vars = addslashes($vars);
        //eval("\$GLOBALS$suffix = \$vars_val;");
    }
}

define('DBTYPE',"mysqli");

$dbqueriesthishit=0;
$dbtimethishit = 0;

function db_error($link){
    $fname = DBTYPE."_error";
    $r = $fname($link);
    return $r;
}

function db_fetch_assoc($result){
    global $dbtimethishit;
    $dbtimethishit -= getmicrotime();
    $fname = DBTYPE."_fetch_assoc";
    $r = $fname($result);
    $dbtimethishit += getmicrotime();
    return $r;
}

function db_num_rows($result){
    global $dbtimethishit;
    $dbtimethishit -= getmicrotime();
    $fname = DBTYPE."_num_rows";
    $r = $fname($result);
    $dbtimethishit += getmicrotime();
    return $r;
}

function db_affected_rows($link=false){
    global $dbtimethishit,$global_mysqli_link;
    $dbtimethishit -= getmicrotime();
    $fname = DBTYPE."_affected_rows";
    if ($link===false) {
        $r = $fname($global_mysqli_link);
    }else{
        $r = $fname($link);
    }
    $dbtimethishit += getmicrotime();
    return $r;
}

function db_pconnect($host,$user,$pass){
    global $dbtimethishit, $global_mysqli_link;
    $fconcharset = DBTYPE . '_set_charset';
    $fconcharset($global_mysqli_link, 'UTF-8');
    $dbtimethishit -= getmicrotime();
    $fname = DBTYPE."_connect";
    $r = $fname($host,$user,$pass);
    $global_mysqli_link = $r;
    $dbtimethishit += getmicrotime();
    return $r;
}

function db_select_db($link, $dbname){
    global $dbtimethishit;
    $dbtimethishit -= getmicrotime();
    $fname = DBTYPE."_select_db";
    $r = $fname($link, $dbname);
    $dbtimethishit += getmicrotime();
    return $r;
}
function db_free_result($result){
    global $dbtimethishit;
    $dbtimethishit -= getmicrotime();
    $fname = DBTYPE."_free_result";
    $r = $fname($result);
    $dbtimethishit += getmicrotime();
    return $r;
}
?> 