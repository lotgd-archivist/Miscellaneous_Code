
<?phprequire_once "common.php";require_once "func/fightnav.php";if(isset($_GET['meister'])){    $_SESSION['tmp']['meister']=$_GET['meister'];}$badguys = array(18=> array("creaturename"=>"Akapo, Meister des Dämonenreichs"                            ,"creaturelevel"=>78                            ,"creatureweapon"=>"böse Dämonen"                            ,"creatureattack"=>99                            ,"creaturedefense"=>99                            ,"creaturehealth"=>1850                            ,"diddamage"=>0),                    19=> array("creaturename"=>"Dorlema, Meisterin des Engelreiches"                            ,"creaturelevel"=>82                            ,"creatureweapon"=>"Engelsflügel"                            ,"creatureattack"=>115                            ,"creaturedefense"=>100                            ,"creaturehealth"=>2250                            ,"diddamage"=>0),                    20=> array("creaturename"=>"Mhad Aban, Meister des Untergangs"                            ,"creaturelevel"=>84                            ,"creatureweapon"=>"Apokalypse"                            ,"creatureattack"=>115                            ,"creaturedefense"=>120                            ,"creaturehealth"=>2450                            ,"diddamage"=>0),                    21=> array("creaturename"=>"Ithilma, Meisterin der Auferstehung"                            ,"creaturelevel"=>88                            ,"creatureweapon"=>"Lebenselixier"                            ,"creatureattack"=>135                            ,"creaturedefense"=>155                            ,"creaturehealth"=>2840                            ,"diddamage"=>0));$mfarben=array(0=>"", 1=>"`4", 2=>"`$", 3=>"`Q", 4=>"`q", 5=>"`X", 6=>"`_", 7=>"`&",                    8=>"`Á", 9=>"`D", 10=>"`(", 11=>"`9", 12=>"`m", 13=>"`M", 14=>"`é",                    15=>"`x", 16=>"`Z", 17=>"`]", 18=>"`=", 19=>"`8", 20=>"`g", 21=>"`2",                    22=>"`B", 23=>"`C", 24=>"`z");                    page_header($badguys[$_SESSION['tmp']['meister']]['creaturename']);if($_SESSION['tmp']['meister']<18 ||$_SESSION['tmp']['meister']>21){    output('Es ist ein Fehler aufgetreten, benachrichtige bitte einen Admin und füge die folgende Fehlermeldung an deine Nachricht an: '            .'`n(meister2.php, '.$_SESSION['tmp']['meister'].', '.$session['user']['acctid'].', '.date('d.m.Y, H:i:s').')');    addnav("Zurück","man.php");    page_footer();    unset($_SESSION['tmp']['meister']);    exit();}$badguy=$badguys[$_SESSION['tmp']['meister']];$badguy['creaturename']=$mfarben[$_SESSION['tmp']['meister']].$badguy['creaturename'];//if($_GET['init']=='true') $session['user']['badguy']=createstring($badguy);$session['user']['badguy']=createstring($badguy);/*    $atkflux = e_rand(0,$session['user']['dragonkills']*2);    $defflux = e_rand(0,($session['user']['dragonkills']*2-$atkflux));    $hpflux = ($session['user']['dragonkills']*2 - ($atkflux+$defflux)) * 5;    $badguy['creatureattack']+=$atkflux;    $badguy['creaturedefense']+=$defflux;    $badguy['creaturehealth']+=$hpflux;*/$battle=true;if ($battle){    include ("battle.php");    if ($victory){            output("`n`^Du hast `^".$mfarben[$_SESSION['tmp']['meister']].$badguys[$_SESSION['tmp']['meister']]['creaturename']."`^ besiegt.`n`#Du erhältst einen Orden!");            $badguy=array();            $session['user']['badguy']="";            if($session['user']['orden']<$_SESSION['tmp']['meister']) $session['user']['orden']+=1;            if($_SESSION['tmp']['meister']==18 || $_SESSION['tmp']['meister']==20){                addnav('Verlasse den Raum','meister2.php?meister='.($_SESSION['tmp']['meister']+1));            } else {                addnav("Weiter","man.php");            }            unset($_SESSION['tmp']['meister']);    }elseif($defeat){        output("Du wurdest von deinem Meister besiegt! Du bist tot!");        $session[user][hitpoints]=0;        $session[user][specialinc]="";        $session[user][reputation]--;        unset($_SESSION['tmp']['meister']);        addnav("Tägliche News","news.php");    } else {        fightnav();    }}page_footer();?>
