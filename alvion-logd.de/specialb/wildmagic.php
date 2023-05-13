
<?php
/*------------------------------------------------------------
/ Wild Magic
/
/ 2004-04-11 The only original thing BlackEdgeMine ever wrote
/            (With thanks to Mortimer & Strider's Druid Special)
/            Coded for www.tqfgames.com
Translated by ???
------------------------------------------------------------*/
if (!isset($session)) exit();
if ($_GET[op]==""){
    output("`7Als du durch die Berge gehst, siehst du in einiger Entfernung ein `^grelles Licht`7.`n");
    output("Du gehst über einen Bergkamm und näherst dich vorsichtig dem Licht.`n");
    output("Plötzlich kommt ein starker Wind auf und `&wirft`7 dich zu Boden!`n`n");
    output("Eine schwarze `b`&Spalte`7`b, umgeben von einem rasenden, `!F`@A`#R`^B`%I`!G`@E`#N`7 Wirbelwind erscheint vor deinen Augen.`n`n");
    output("Du hast nur einen Moment dich zu entscheiden. `b`&Was tust du?`b");
    addnav("Geh in den Wirbel","berge.php?op=enter");
    addnav("Schau in die Spalte","berge.php?op=stare");
    addnav("Flieh in Sicherheit","berge.php?op=flee");
    $session[user][specialinc]="wildmagic.php";
}else if ($_GET[op]=="enter"){
        output("`n`7Nichts Böses fürchtend gehst du in den Wirbelwind..`n`n");
        switch(e_rand(1,5)){
            case 1:
                output(" `6Du beginnst zu zittern und fällst zu Boden. Dir wird schwarz vor den Augen..`n");
                $session[bufflist]['Wilde Magie'] = array("name"=>"`#Scharfe Kälte","rounds"=>20,"wearoff"=>"Du fühlst dich wieder stärker.","atkmod"=>0.8,"roundmsg"=>"`#Dir läuft es eiskalt den Rücken runter.","activate"=>"offense");
                break;
            case 2:
                output(" `6Ohne erkennbaren Grund, fühlst du dich auf einmal glücklich. Dich überkommt die Freud und deine Augen füllen sich mit Tränen.`n");
                $session[bufflist]['Wilde Magie'] = array("name"=>"`#Mit Freude verzaubert","rounds"=>30,"wearoff"=>"Du fühlst dich wieder normal.","atkmod"=>1.2,"roundmsg"=>"`#Du kämpfst mit einem Glücksgefühl im Herzen!","activate"=>"offense");
                break;
            case 3:
                output(" `6Nach ein paar Minuten in dem Wirbel wird dir langweilig..`n");
                break;
            case 4:
                output(" `6Du kannst nichts erkennen. Ziemlich schnell wird dir langweilig und du verlässt den Wirbel.`n");

                output(" `#Du bekommst nicht mit, daß dir etwas aus dem Wirbel folgt...`n");
                $session[bufflist]['Wilde Magie'] = array("name"=>"`#Wirbel Günstling","rounds"=>30,"wearoff"=>"Der Günstling verschwindet genauso mysteriös wie er erschien.","atkmod"=>1.1,"minioncount"=>1,"minbadguydamage"=>1,"maxbadguydamage"=>10,"effectmsg"=>"Ein Wirbel trifft deinen Gegner mit {damage} Schaden!","activate"=>"offense");
                break;
            case 5:
                output(" `6Nachdem du nichts erkennen kannst wird dir schnell langweilig und du gehst.`n");
                output(" `#Du bekommst nicht mit, daß dir etwas aus dem Wirbel folgt...`n");
                $session[bufflist]['Wilde Magie'] = array("name"=>"`#Wirbel Günstling","rounds"=>10,"wearoff"=>"Der Günstling verschwindet genauso mysteriös wie er erschien.","atkmod"=>1.1,"minioncount"=>1,"mingoodguydamage"=>1,"maxgoodguydamage"=>$session['user']['level'],"effectmsg"=>"Ein Wirbel trifft deinen Gegner mit {damage} Schaden!","activate"=>"roundstart");
                break;
        }
}else if ($_GET[op]=="stare"){
        output("`n`7Du starrst in die Spalte..`n`n");
        switch(e_rand(1,4)){
            case 1:
                output(" `6Die `bSPALTE`b starrt ZURÜCK!`n");
                output(" `6Du verlierst Waldkämpfe!`n");
                if ($session['user']['turns']<4) {$session['user']['turns']=0;}
                else {$session['user']['turns']-=4;}
                break;
            case 2:
                output(" `6Die `bSPALTE`b flüchtet vor deinem harten Blick!`n");
                output(" `6Du bekommst Waldkämpfe!`n");
                $session['user']['turns']+=4;
                break;
            case 3:
                output(" `6Du kannst nichts erkennen. Ziemlich schnell wird dir langweilig und du verlässt den Wirbel.`n");
                output(" `#Du bekommst nicht mit, daß dir etwas aus dem Wirbel folgt...`n");
                $session[bufflist]['Wilde Magie'] = array("name"=>"`#Wirbel Günstling","rounds"=>30,"wearoff"=>"Der Günstling verschwindet genauso mysteriös wie er erschien.","atkmod"=>1.1,"minioncount"=>1,"minbadguydamage"=>1,"maxbadguydamage"=>10,"effectmsg"=>"Der Günstling verschwindet genauso mysteriös wie er erschien.","atkmod"=>1.1,"minioncount"=>1,"minbadguydamage"=>1,"maxbadguydamage"=>10,"effectmsg"=>"Ein Wirbel trifft deinen Gegner mit {damage} Schaden!","activate"=>"offense");
                break;
            case 4:
                output(" `6Du kannst nichts erkennen. Ziemlich schnell wird dir langweilig und du verlässt den Wirbel.`n");
                output(" `#Du bekommst nicht mit, daß dir etwas aus dem Wirbel folgt...`n");
                $session[bufflist]['Wilde Magie'] = array("name"=>"`#Wirbel Günstling","rounds"=>10,"wearoff"=>"Der Günstling verschwindet genauso mysteriös wie er erschien.","atkmod"=>1.1,"minioncount"=>1,"mingoodguydamage"=>1,"maxgoodguydamage"=>$session['user']['level'],"effectmsg"=>"Der Günstling verschwindet genauso mysteriös wie er erschien.","atkmod"=>1.1,"minioncount"=>1,"minbadguydamage"=>1,"maxbadguydamage"=>10,"effectmsg"=>"Ein Wirbel trifft deinen Gegner mit {damage} Schaden!","activate"=>"roundstart");
                break;
        }
} else {
    output("`n`@Du fliehst vor dem Wirbel!`n");
}

?>

