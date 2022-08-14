
<?php

require_once("common.php");



page_header("Verteidigt das Dorf!");



if ($_GET['op']=="kampf"){

    if ($session['user']['turns']==0){

        output("Du schleppst dich Richtung SchlachtgetÃ¼mmel - und schlÃ¤fst ein. Vielleicht ist es besser, wenn du fÃ¼r heute auf weitere KÃ¤mpfe verzichtest.");

        

        addnav("ZurÃ¼ck zum Dorf","village.php");

    }else{

        $session['user']['turns']--;

        $battle=true;

        if (e_rand(0,2)==1){

            $plev = (e_rand(1,5)==1?1:0);

            $nlev = (e_rand(1,3)==1?1:0);

        }else{

            $plev=0;

            $nlev=0;

        }



        $targetlevel=($session['user']['level']+$plev-$nlev);

        if ($targetlevel<1) $targetlevel=1;

        $sql = "SELECT * FROM creatures WHERE creaturelevel = $targetlevel ORDER BY rand(".e_rand().") LIMIT 1";

        $result = db_query($sql) or die(db_error(LINK));

        $badguy = db_fetch_assoc($result);

        $badguy['creaturename']=substr(getsetting("angreifername","Kekse"), 0, -1);

        $badguy['creatureweapon']=substr(getsetting("angreiferwaffe","KrÃ¼mel"), 0, -2);

        $expflux = round($badguy['creatureexp']/10,0);

        $expflux = e_rand(-$expflux,$expflux);

        $badguy['creatureexp']+=$expflux;

        //make badguys get harder as you advance in dragon kills.

        $badguy['playerstarthp']=$session['user']['hitpoints'];

        $dk = 0;

        while(list($key, $val)=each($session['user']['dragonpoints'])) {

            if ($val=="at" || $val=="de") $dk++;

        }

        $dk+=(int)(($session['user']['maxhitpoints']-($session['user']['level']*10))/5);

        $atkflux = e_rand(0, $dk);

        $defflux = e_rand(0, ($dk-$atkflux));

        $hpflux = ($dk - ($atkflux+$defflux)) * 5;

        $badguy['creatureattack']+=$atkflux;

        $badguy['creaturedefense']+=$defflux;

        $badguy['creaturehealth']+=$hpflux;

        $badguy['creaturehealth']=rand($badguy['creaturehealth']/4,$badguy['creaturehealth']);

        if ($session['user']['race']==4) $badguy['creaturegold']*=1.2;

        $badguy['diddamage']=0;

        $session['user']['badguy']=createstring($badguy);

    }

}elseif ($_GET['op']=="statistik"){

    $angreifer=(int)getsetting("angreiferzahl",0);



    addnav("Aktualisieren","dorfangriff.php?op=statistik");

    addnav("ZurÃ¼ck","dorfangriff.php");

    

    output("Bei diesem Angriff der `%".getsetting("angreifername","Kekse")."`0 starben bisher `%".getsetting("angreiferopfer",0)."`0 tapfere Krieger.`n

    Es sind noch `%".$angreifer."`0 Angreifer Ã¼brig.`n

    Dein Dorf braucht dich!`n`n

    `5Die letzten Meldungen von der Front:`0`n`n");

    

    $sql = "SELECT * FROM dorfangriff ORDER BY newsid DESC LIMIT 100";

    $result = db_query($sql) or die(db_error(LINK));

    

    for ($i=0;$i<db_num_rows($result);$i++){

        $row = db_fetch_assoc($result);       

        output($row['newstext']."`n");

    }

    if (db_num_rows($result)==0){

        output("Es ist nichts erwÃ¤hnenswertes passiert.`0");

    }

}elseif ($_GET['op']=="over"){

    addnav("ZurÃ¼ck","friedhof.php");

    

    output("Beim letzten Angriff der `%".getsetting("angreifername","Kekse")."`0 starben `%".getsetting("angreiferopfer",0)."`0 tapfere Krieger.`n`n

    `%Auf den Gedenktafeln liest du:`0`n`n");

    

    $sql = "SELECT * FROM dorfangriff ORDER BY newsid DESC LIMIT 100";

    $result = db_query($sql) or die(db_error(LINK));

    

    for ($i=0;$i<db_num_rows($result);$i++){

        $row = db_fetch_assoc($result);       

        output($row['newstext']."`n");

    }

    if (db_num_rows($result)==0){

        output("Du kannst nichts erkennen. Die Schrift ist zu verwittert.`0");

    }

}elseif ($_GET['op']=="run"){



    output("`c`b`\$Du konntest vor Deinem Feind nicht fliehen!`0`b`c");

    $battle=true;



}elseif ($_GET['op']=="fight"){

    $battle=true;



}else{

    output("`%".$angreifer." ".getsetting("angreifername","Kekse")."`0 stÃ¼rmen auf das Dorf ein.`n

    Du kÃ¤mpfst mit dir selbst. Denn einerseits hÃ¤ltst du es fÃ¼r deine Pflicht, das Dorf zu verteidigen - und auf dem Schlachtfeld warten Ruhm und Reichtum auf dich. Doch andererseits wartet dort auch `\$Ramius`0 und du weiÃŸt, dass du aus dieser Schlacht wohl nicht lebendig zurÃ¼ckkehren wirst.");

    

    addnav("In die Schlacht","dorfangriff.php?op=kampf");

    addnav("Bisheriger Verlauf","dorfangriff.php?op=statistik");

    addnav("ZurÃ¼ck zum Dorf","village.php");

}



if ($battle){

    include("battle.php");

    if ($victory){

        $angreifer=(int)getsetting("angreiferzahl",0);

        $session['user']['dorfangriff']++;

        $session['angreiferzahl']--;

        $angreifer--;

        savesetting("angreiferzahl",$angreifer);

        

        if (getsetting("dropmingold",0)){

            $badguy['creaturegold']=e_rand($badguy['creaturegold']/4,3*$badguy['creaturegold']/4);

        }else{

            $badguy['creaturegold']=e_rand(0,$badguy['creaturegold']);

        }

        $expbonus=round(($badguy['creatureexp']*(1+.25*($badguy['creaturelevel']-$session['user']['level'])))-$badguy['creatureexp'],0);

        

        output("`0`nDu hast `%".$badguy['creaturename']."`0 besiegt.`0`n");

        output("`2Du bekommst `^{$badguy['creaturegold']}`2 Gold fÃ¼r deine Leistungen auf dem Schlachtfeld!`n`\$Ramius `2bedankt sich fÃ¼r diese Seele.`n`n");

        //output("Du erhÃ¤ltst insgesamt `^".($badguy['creatureexp']+$expbonus)."`# Erfahrungspunkte!`n`0");

        $session['user']['gold']+=$badguy['creaturegold'];

        $session['user']['experience']+=($badguy['creatureexp']+$expbonus);

        $session['user']['reputation']++;

        $session['user']['soulpoints']+=10;

        $badguyname=$badguy['creaturename'];

        $badguy=array();

        

        if ($angreifer>0){

            output("`b`\$Es stÃ¼rmen noch ".$angreifer." ".$badguy['creaturename']." auf das Dorf ein!`0`b`nDu kannst unmÃ¶glich zurÃ¼ck. Du MUSST weiterkÃ¤mpfen!");



            addnav("Weiter","dorfangriff.php?op=kampf");

        }else{

            $gold = (e_rand(10000,20000));

            $gems = (e_rand(10,20));

            $exp = (e_rand(1000,2000));

            $charm = (e_rand(3,8));

            

            $session['user']['charm']+=$charm;

            $session['user']['experience']+=$exp;

            $session['user']['gold']+=$gold;

            $session['user']['gems']+=$gems;

            output("`n`n`n `qDu hast den letzten Angreifer erledigt, und bringst den Frieden zurÃ¼ck in die Stadt.`n DafÃ¼r verehrt dich die gesamte Stadt.");

            

            adddorfangriffnews($session['user']['name']." `2Ã¼berlebte die Verteidigung des Dorfes gegen `%".getsetting("angreifername","Kekse")."`2, nachdem ".($session['user']['sex']?"sie":"er")." `%".$session['user']['dorfangriff']."`2 Gegner niedermetzelte.");

            //addnews($session['user']['name']." `2Ã¼berlebte die Verteidigung des Dorfes gegen `%".getsetting("angreifername","Kekse")."`2, nachdem ".($session['user']['sex']?"sie":"er")." `%".$session['user']['dorfangriff']."`2 Gegner niedermetzelte.");

            addnews("`&".$row['name']."`^ erledigte den letzten Angreifer und ".($row['sex']?"ihr":"ihm")." gebÃ¼hrt der Ruhm, denn ".($row['sex']?"sie":"er")." brachte den Frieden zurÃ¼ck!");

            

            $session['user']['dorfangriff']=0;

            $sql = "UPDATE settings SET value='0' WHERE setting='angreiferzahl'";

            db_query($sql) or die(db_error(LINK));

            

            addnav("ZurÃ¼ck zum Dorf","village.php");

        }

    }elseif ($defeat){

        addnav("TÃ¤gliche News","news.php");

        $angriffsopfer=getsetting("angreifertote",0);

        $angriffsopfer++;

        savesetting("angriffsopfer",$angriffsopfer);

        adddorfangriffnews($session['user']['name']." `4starb bei der Verteidigung des Dorfes gegen `%".getsetting("angreifername","Kekse")."`4, nachdem ".($session['user']['sex']?"sie":"er")." `%".$session['user']['dorfangriff']."`4 Gegner niedermetzelte.");

        addnews($session['user']['name']." `4starb bei der Verteidigung des Dorfes gegen `%".getsetting("angreifername","Kekse")."`4, nachdem ".($session['user']['sex']?"sie":"er")." `%".$session['user']['dorfangriff']."`4 Gegner niedermetzelte.");

        $session['user']['alive']=0;

        $session['user']['hitpoints']=0;

        $session['user']['reputation']++;

        $session['user']['badguy']="";

        $session['user']['dorfangriff']=0;

        output("Du wurdest von einer Armee der `%".getsetting("angreifername","Kekse")."`0 geschlagen.`n");

        output("FÃ¼r den Tod auf dem Schlachtfeld steigt dein Ansehen im Dorf.");

    }else{

        fightnav(true,true);

    }

}



page_footer();



?>

