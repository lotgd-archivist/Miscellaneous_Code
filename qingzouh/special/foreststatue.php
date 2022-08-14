
<?php

//translator ready
//addnews ready
//alignment ready

// Statue, Version für logd 0.98
//
// Du kommst an einer Statue vorbei und kannst hineingreifen.
// Was wird wohl passieren?
//
// Idee und Skript von Vaan.
// Wenn einer das special einbaut, wär es nett wenn er mir bescheidsagt MC_Vaan@hotmail.com
// Erstmals erschienen auf meinem Home-Server *gg*
//
//   04.12.2004 (0.9.7 Version)
//   0.9.8 Version vom 16.03.2005 

if ($_GET['op']=="download"){
    $dl=join("",file("foreststatue.php"));
    echo $dl;
}else{

require_once("lib/commentary.php");

function foreststatue_getmoduleinfo(){
    $info = array(
        "name"=>"Statue",
        "version"=>"1.0",
        "author"=>"`QV`\$a`4a`qn",
        "category"=>"Forest Specials",
        "download"=>"modules/foreststatue.php?op=download",
    );
    return $info;
}

function foreststatue_install(){
    module_addeventhook("forest", "return 100;");
    module_addhook("validatesettings");
    return true;
}

function foreststatue_uninstall(){
    return true;
}

function foreststatue_runevent($type)
{
    global $session;
    $op = httpget('op');

    $from = "forest.php?";
    $session['user']['specialinc'] = "module:foreststatue";


if ($op=="" || $op=="search"){

    output("Als du so deinen Weg entlang gehst kommst du an einer riesigen Statue vorbei an der ein großes Schild angelehnt ist. Du versuchst zu entziffern was auf dem alten Schild steht."
          ."`nDu liest: \"`6In mir ist etwas verborgen, in mir ist was versteckt, in mir ist etwas gutes oder etwas böses! Wenn du es herausfinden willst was es ist guck in mich hinein.`0\"" 
          ."`nWas willst du machen?"); 
    addnav("","forest.php?op=such");
    addnav("","forest.php?op=gehe");
    addnav("I?In die Statue kriechen und nach irgend einem Gegenstand suchen", $from . "op=such");
    addnav("E?Einfach weiter gehen", $from . "op=gehe");

}elseif ($op=="gehe"){

    output("Mit schnellen Schritten verlässt du den Ort.");
    $session['user']['specialinc']="";
    //addnav("Zurück","forest.php");

}elseif ($op=="such"){

    output("Du fängst an dem Einstieg  der Statue an zu suchen. Nach einiger Zeit findest du ein Loch. Du steckst deinen Arm durch das Loch und bekommst etwas zu fassen.");
    switch(e_rand(1,14)){
        case 1:
        case 2:
        output("Es scheint so als ob der Gegenstand festgebunden wär. Es dauert eine Ewigkeit bis du den Gegenstand hinaus bekommen hast."
              ."`nDa du so lange gebraucht hast verlierst du für heute einen Waldkampf."
              ."`nDoch jetzt liegt er in deiner Hand. Du schaust dir den kleinen Gegenstand an und fühlst dich gestärkt.");
        $session['user']['turns']-=1; 
        $session['user']['attack']+=3; 
        $session['user']['specialinc']=""; 
        //addnav("Zurück in den Wald","forest.php"); 
               break;
        case 3:
        case 4:
        output("Es scheint so als ob der Gegenstand festgebunden wär. Es dauert eine Ewigkeit bis du den Gegenstand hinaus bekommen hast."
              ."`nDa du so lange gebraucht hast verlierst du für heute einen Waldkampf" 
              ."`nDoch jetzt liegt er in deiner Hand. Du schaust dir den kleinen Gegenstand an und fühlst dich gestärkt."); 
        $session['user']['turns']-=1; 
        $session['user']['defense']+=3; 
        $session['user']['specialinc']=""; 
        //addnav("Zurück in den Wald","forest.php"); 
            break;
        case 5:
        case 6:
        output("Du ziehst deinen Arm samt Gegenstand aus dem Loch und schaust ihn dir an."
              ."`nEin stechender Schmerz, der von der Hand kommt in dem der kleine Gegenstand liegt kommt, lässt dich zusammen sacken. Als du wieder aufwachst fühlst du dich geschwächt."); 
        $session['user']['attack']-=3; 
        $session['user']['specialinc']=""; 
        //addnav("Zurück in den Wald","forest.php"); 
            break;
        case 7:
        case 8:
        output("Du ziehst deinen Arm samt Gegenstand aus dem Loch und schaust ihn dir an."
              ."`nEin stechender Schmerz, der von der Hand kommt in dem der kleine Gegenstand liegt kommt, lässt dich zusammen sacken. Als du wieder aufwachst fühlst du dich geschwächt."); 
        $session['user']['defense']-=3; 
        $session['user']['specialinc']=""; 
        //addnav("Zurück in den Wald","forest.php"); 
            break;
        case 9:
        case 10:
        output("Als du dir das kleine Ding in deiner Hand anschust bekommst du aus irgendeinem grund Glücksgefühle und willst kämpfen."
              ."`nDu erhälst eien zusätzlichen Waldkampf."); 
        $session['user']['turns']+=1; 
        $session['user']['specialinc']=""; 
        //addnav("Zurück in den Wald","forest.php"); 
            break;
        case 11:
        case 12:
        output("Du ziehst und ziehst und ziehst aber das kleine Ding in der Statue will einfach nicht raus kommen."
              ."Du verlierst einen Waldkampf." 
              ."Wütend gehst du zurück in den Wald."); 
        $session['user']['turns']-=1; 
        $session['user']['specialinc']=""; 
        //addnav("Zurück in den Wald","forest.php");
            break;
        case 13:
        case 14:
        output("Grade als du den Gegenstand aus der Statue rausziehen willst spürst du, dass du von etwas gebissen worden bist." 
              ."`n`\$Du bist am Gift einer Giftigenschlange gestorben."); 
        $session['user']['alive']=false; 
        $session['user']['hitpoints']=0; 
        addnav("Tägliche News","news.php");
        addnav("","news.php");
        $session['user']['specialinc']=""; 
        addnews("`\$".$session[user][name]." `0starb durch eine Giftschlange"); 
            break;
    }
    
}
function foreststatue_run(){
}
}
}
?>

