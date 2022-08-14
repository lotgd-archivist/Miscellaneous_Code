
ï»¿<?php



// 07092005



$translate_page = $_SERVER['PHP_SELF'];

$translate_page = substr($translate_page,strrpos($translate_page,"/")+1);

function translate($input){

    global $translate_page;

    switch ($translate_page){

    case "logdnet.php":

        $replace = array(

        "Unknown"=>"Unbekannt"

        );

        break;

    }

    return replacer($input,$replace);

}



?>

