
<?php

require_once "common.php";
    
if ($session['user']['loggedin'] && $session['loggedin']) {
    $session['user']['standort'] = "`NBadnav";

    if (strpos($session[output], "<!--CheckNewDay()-->")) {
        checkday();
    }

    $session['user']['allowednavs'] = '';
    $session['user']['restorepage'] = '';
    $session['user']['output'] = '';

    while (list($key, $val) = each($session['allowednavs'])) {
        if (
                trim($key) == "" ||
                $key === 0 ||
                substr($key, 0, 8) == "motd.php" ||
                substr($key, 0, 8) == "mail.php" ||
                substr($key, 0, 13) == "popup_bio.php" ||
                substr($key, 0, 12) == "popup_petition.php"
        )
            unset($session['allowednavs'][$key]);
    }

    if (!is_array($session['allowednavs']) || count($session['allowednavs']) == 0 || $session['user']['output'] == "") {
        $session['allowednavs'] = array();
        addnav("nach Astaros", "village.php");
    
        //echo "<a href='village.php'>Deine erlaubten Navs waren beschï¿½digt. Zurï¿½ck zur Stadt.</a>";
        if ($session['user']['acctid'] == 0)
            $session['admin_msg'] .= 'Weitergeleitet von Badnav';

        //header("Location: village.php");
    }
    $allowedNavs = $session['allowednavs'];
    reset($allowedNavs);
    $villageNav = key($allowedNavs);

    $session['user']['output'] = "<a href='" . $villageNav . "'>Klicken zum Befreien</a>";

    echo $session['user']['output'];
    $session['debug']                 = "";
    
    $session['user']['allowednavs']     = $session['allowednavs'];
    saveuser();
}else {
    $session = array();
    redirect("index.php");
}
?>

