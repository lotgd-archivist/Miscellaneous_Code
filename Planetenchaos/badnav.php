
<?php

require_once "common.php";

if ($session['user']['loggedin'] && $session['loggedin']){

    if (strpos_c($session['output'],"<!--CheckNewDay()-->")){

        checkday();

    }

    while (list($key,$val)=each($session['allowednavs'])){

        if (

            trim($key)=="" ||

            $key===0 ||

            substr_c($key,0,8)=="motd.php" ||

            substr_c($key,0,8)=="mail.php"

        ) unset($session['allowednavs'][$key]);

    }

    if (!is_array($session['allowednavs']) || count($session['allowednavs'])==0 || $session['output']=="") {

        $session['allowednavs']=array();

        addnav("","village.php");

        echo "<a href='village.php'>Deine erlaubten Navs waren beschÃ¤digt. ZurÃ¼ck zum Dorf.</a>";

    }

    echo $session['output'];

    $session['debug']="";

    $session['user']['allowednavs']=$session['allowednavs'];

    saveuser();

}else{

    $session=array();

    redirect("index.php");

}

?>

