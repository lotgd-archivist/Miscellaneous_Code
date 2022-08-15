
<?php

/*
* Der Diamantgolem (diamantgolem.php)
* written by Darkness
* http://darkness.logd.cwsurf.de/
*/

require_once "common.php";
page_header("Diamantgolem");

if ($_GET['op']=="") {
    output("`n`c`b`#Der Diamantgolem`b`c`n");
    output("`#Auf dich stampft ein gigantisches Wesen zu. Sein Körper besteht nur aus Diamant.`n
            Anfangs sind deine Blicke nur auf seine Beine gerichtet, doch langsam richtest du deinen Kopf auf und musterst seinen Körper. Es ist ein `iDiamantgolem`i, der ein seltsames Amulett um den Hals trägt.`n
            Er spricht zu dir: \"`&Du siehst aus, als wärst du ein".($session['user']['sex'] ? "e Kämpferin, die" : " Kämpfer, der")." gerne etwas aufs Spiel setzt, habe ich da recht?`#\" Du nickst etwas untentschlossen. Der Golem fährt fort: \"`&Ich habe ein Angebot für dich. Du setzt deine permanenten Lebenspunkte ein, dann kämpfst du gegen mich! Hast du Erfolg, verdopple ich deine gesetzten Lebenspunkte - doch verlierst du, dann gehören deine Lebenspunkte mir!`n
            Aber sei vorsichtig. Mit jedem Lebenspunkt, den du setzt, werde ich stärker!`#\"`n`n
            `3Nimmst du seine Herausforderung an?`n`n`n");
    output("<a href='forest.php?op=do'>Ja, ich nehme an.</a>`n`n",true);
    output("<a href='forest.php?op=dont'>Nein, das ist mir zu riskant.</a>`n`n",true);
    addnav("","forest.php?op=do");
    addnav("","forest.php?op=dont");
    addnav("A?Annehmen","forest.php?op=do");
    addnav("l?Ablehnen","forest.php?op=dont");
    $session['user']['specialinc']="diamantgolem.php";

}elseif ($_GET['op']=="dont") {
    output("`n`&Deine schwerverdienten Lebenspunkte sind dir zu wichtig, als dass du sie so leichtfertig aufs Spiels setzen willst.`n
            Du beschließt, lieber zu gehen.");
    $session['user']['specialinc']="";

}elseif ($_GET['op']=="do") {
    output("`n`c`b`#Der Diamantgolem`b`c`n");
    output("`#Der Diamantgolem murmelt etwas und scheint erfreut über deine Entscheidung zu sein.`n
            Er fragt dich, wie viele Lebenspunkte du einsetzen willst. Es sollten mindestens 2 Lebenspunkte sein.`n`n");
    output("<form action='forest.php?op=challenge' method='POST'>",true);
    output("<input type='text' id='zahl' name='zahl' maxlength='2' size='5'> ",true);
    output("<input type='submit' class='button' value='Setzen'></form>",true);
    addnav("","forest.php?op=challenge");
    addnav("Zurück in den Wald","forest.php?op=dont");
    $session['user']['specialinc']="diamantgolem.php";

}elseif ($_GET['op']=="challenge") {
    $session['user']['specialmisc'] = (int)$_POST['zahl'];
    $hp = $session['user']['specialmisc'];
    if ($hp>($session['user']['maxhitpoints']-$session['user']['level']*10) || $hp>15 || $hp>=$session['user']['hitpoints']){
        output("`n`c`b`#Der Diamantgolem`b`c`n");
        output("`#Du denkst daran, dass der Golem gesagt hat, dass er mit jedem Lebenspunkt, den du setzt, stärker wird, und fühlst dich prompt unwohl bei dem Gedanken, so viele Lebenspuntke zu setzen...`n`n");
        addnav("Nochmal versuchen","forest.php?op=do");
        addnav("Zurück in den Wald","forest.php?op=dont");
        $session['user']['specialmisc'] = 0;
    }elseif ($hp<=1){
        output("`n`c`b`#Der Diamantgolem`b`c`n");
        output("`#Der Golem schaut dich grimmig an und teilt dir mit, dass du mindestens 2 Lebenspunkte setzen musst, ehe sich ein Kampf lohnt.`n`n");
        addnav("Nochmal versuchen","forest.php?op=do");
        addnav("Zurück in den Wald","forest.php?op=dont");
        $session['user']['specialmisc'] = 0;
    }else{    
        $lvflux=0;
        if($hp>=4) $lvflux=1;
        if($hp>=7) $lvflux=2;
        if($hp>=10) $lvflux=3;
        if($hp>=13) $lvflux=4;
        $session['user']['maxhitpoints']-=$hp;
        $session['user']['hitpoints']-=$hp;
        $session['user']['turns']--;
        output("`n`c`b`#Der Diamantgolem`b`c`n`n");
        output("`#Das Amulett des Golems leuchtet kurz auf und du fühlst dich schwächer. Deine gesetzten Lebenspunkte wurden dir abgezogen.`n
                Der Golem geht in Kampfstellung und greift dich dann ohne weitere Vorwarnung an!`n`n");
        $badguy = array("creaturename"=>"`3D`#i`3a`#m`3a`#n`3t`#g`3o`#l`3e`#m`0",
                        "creaturelevel"=>$session['user']['level']+$lvflux,
                        "creatureweapon"=>"`3D`#i`3a`#m`3a`#n`3t`#f`3a`#u`3s`#t`0",
                        "creatureattack"=>$session['user']['attack']+$lvflux,
                        "creaturedefense"=>$session['user']['defence']+$lvflux,
                        "creaturehealth"=>round($session['user']['maxhitpoints']+$hp*20),0,
                        "diddamage"=>0);
        $session['user']['badguy']=createstring($badguy);
        $_GET['op']="fight";
    }
    $session['user']['specialinc']="diamantgolem.php";
}
if ($_GET['op']=="run"){
    output("`c`bDeine Lebenspunkte sind dir zu wichtig, du kannst jetzt nicht fliehen!`0`b`c`n`n");
    $battle=true;
}
if ($_GET['op']=="fight"){
    $battle=true;
}
if ($battle) {
    include("battle.php");
    $session['user']['specialinc']="diamantgolem.php";
    $hp = $session['user']['specialmisc'];
    if ($victory){
        $badguy=array();
        $session['user']['badguy']="";
               
        output("`n`n`#Der Golem geht vor dir in die Knie. Du hast den Kampf gewonnen!`n");
        output("`#Du nimmst dem Golem sein Amulett ab. Es glüht in deinen Händen auf und du spürst, dass du ".($hp*2)." permanente Lebenspunkte erhalten hast!`n");
        $session['user']['maxhitpoints']+=$hp*2;
        addnews("`3".$session['user']['name']."`# hat den `3Diamantgolem`# geschlagen und dadurch an Stärke gewonnen!");
        debuglog('gewinnt '.$hp.' permanente LP beim Diamantgolem im Wald.');
        $session['user']['specialinc']="";
        $session['user']['specialmisc'] = 0;
    } elseif ($defeat){
        $badguy=array();
        $session['user']['badguy']="";
        output("`n`n`#Der Golem holt aus und schlägt dich nieder! Sein Amulett leuchtet plötzlich rot auf und der Golem stampft davon.`n");
        output("`#Du hast zwar deine Lebenspunkte verloren, aber bist froh, noch am Leben zu sein. Die Lektion, die du gelernt hast, gleicht jeden Erfahrungsverlust aus!`n");
        $session['user']['hitpoints']=1;
        addnews("`%".$session['user']['name']."`5 wurde vom `%Diamantgolem`5 geschlagen und dadurch nachhaltig geschwächt.");
        debuglog('verliert '.$hp.' permanente LP beim Diamantgolem im Wald.');
        $session['user']['specialinc']="";
        $session['user']['specialmisc'] = 0;
    } else {
        fightnav(true,true);
    }
}


?>

