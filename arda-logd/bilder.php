<?php

require_once "common.php";
isnewday ( 2 );

$url = 'http://arda-logd.com/avatare/neu/';

$dir = str_replace ( "\\", "/", dirname ( $url ) . "/avatare/neu/" );
$subdir = str_replace ( "\\", "/", dirname ( $url ) . "/" );
$legal_dirs = array (
        $subdir . "" => 1 
);
$legal_files = array ();
$illegal_files = array ();

echo "<div align='center'><table border='1'>";

while ( list ( $key, $val ) = each ( $legal_dirs ) ) {
    $skey = substr ( $key, strlen ( $subdir ) );
    if ($key == dirname ( $url ))
        $skey = "";
    $d = dir ( "./avatare" );
    if (substr ( $key, 0, 2 ) == "//")
        $key = substr ( $key, 1 );
    if ($key == "//")
        $key = "/";
    while ( false !== ($entry = $d->read ()) ) {
        echo "<tr>";
        if (substr ( $entry, strrpos ( $entry, "." ) ) == ".gif") {
            echo "<td>$skey$entry <img src='$url$skey$entry' width='100' height='100' border='0'></td>";
        }
        if (substr ( $entry, strrpos ( $entry, "." ) ) == ".jpg") {
            echo "<td>$skey$entry <img src='$url$skey$entry' width='100' height='100' border='0'></td>";
        }
        if (substr ( $entry, strrpos ( $entry, "." ) ) == ".png") {
            echo "<td>$skey$entry <img src='$url$skey$entry' width='100' height='100' border='0'></td>";
        }
        echo "</tr>";
    }
    $d->close ();
}
echo "</table></div>";

$page_name = substr ( $url, strlen ( $subdir ) - 1 );
if (substr ( $page_name, 0, 1 ) == "/")
    $page_name = substr ( $page_name, 1 );

?> 