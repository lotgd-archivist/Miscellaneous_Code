
<?php
require_once "common.php";
if ($session['user']['loggedin'] && $session['loggedin']){
    if (strpos($session[output],"<!--CheckNewDay()-->")){
        checkday();
    }
    if(is_array($session['allowednavs'])) {
        foreach($session['allowednavs'] as $key => $val){
            if     (
                    trim($key)=="" ||
                    $key===0 ||
                    substr($key,0,8)=="motd.php" ||
                    substr($key,0,8)=="mail.php"
                )
            {
                unset($session['allowednavs'][$key]);
            }
        }    
    }
    if (!is_array($session['allowednavs']) || count($session['allowednavs'])==0) {
        $session['allowednavs']=array();
        addnav("","village.php");
        echo "<a href='village.php'>Deine erlaubten Navs waren beschädigt. Zurück zur Stadt.</a>";
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

