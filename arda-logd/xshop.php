<?php
// Rüstungs- und Waffen schmiede
// 230505
// erstellt by tweety 
// Idee von Des
// Hilfe vom xshop script vom lotgd.Phoenixserver.de geholt
// und als Grundlage benutzt

require_once "common.php";

if ($_GET[op]=="") {
    addcommentary();
    checkday();
    page_header("Die Schmiede");
    output("`c`^Du betrittst die Schmiede es ist heiß. Dem Schmied läuft der Schweiß nur so von der Stirn. Er Graviert ein Schwert nach dem anderen. Zwischendruch verziert er auch mal eine Rüstung. Du schaust ihm interessiert zu und erblickst noch andere Leute die es dir gleich tun.`c");

    viewcommentary("schmiede","Hinzufügen",15);


            addnav("Deine Waffe gravieren lassen","xshop.php?op=name");
            addnav("Deine Rüstung verzieren lassen","xshop.php?op=rname");
            if ($session['user']['gems']>=1){
            if ($session['user']['gold']>=10000){
            addnav("Beschlagen lassen","schlag.php");
            }
            }
            addnav("Zurück","markt.php");
            
    }
        if ($_GET['op']=="name") {
        page_header("Eine Waffe benennen");
            output("`bEine Waffe benennen`b`n`n");
        
        output("`n`nDer Name deiner Waffe darf 50 Zeichen lang sein und Farbcodes enthalten.`n`n");
          $n = $session['user'][weapon];
    
        output("Deine Waffe heißt bisher : `n");
        $output.=$n;
        output("`n`n`0Wie soll deine Waffe heißen ?`n");
        $output.="<form action='xshop.php?op=namepreview' method='POST'><input name='newname' value=\"".HTMLEntities($newname)."\" size=\"30\" maxlength=\"50\"> <input type='submit' value='Vorschau'></form>";
        addnav("","xshop.php?op=namepreview");
addnav("Zurück","markt.php");

}elseif ($_GET['op']=="namepreview"){
        $n = $session[user][name];
   
        $_POST['newname']=str_replace("`0","",$_POST['newname']);
       
        if (strlen($_POST['newname'])>50) $msg.="Der neuer Name ist zu lang, inklusive Farbcodes darf er nicht länger als 50 Zeichen sein.`n";
        $colorcount=0;
        for ($x=0;$x<strlen($_POST['newname']);$x++){
            if (substr($_POST['newname'],$x,1)=="`"){
                        $x++;
                        $colorcount++;
                }
        }
        if ($colorcount>getsetting("maxcolors",10)){
                $msg.="Du hast zu viele Farben im Namen benutzt. Du kannst maximal ".getsetting("maxcolors",10)." Farbcodes benutzen.`n";
        }
        if ($msg==""){
        page_header("TEST");
                output("Deine Waffe wird so heißen: {$_POST['newname']}`n`n`0Ist es das was du willst?`n`n");
                $p = 10;
                $output.="<form action=\"xshop.php?op=changename\" method='POST'><input type='hidden' name='name' value=\"".HTMLEntities($_POST['newname'])."\"><input type='submit' value='Ja' class='button'>, meine Waffe heißt nun ".appoencode("{$_POST['newname']}`0")." für 2000 Gold.</form>";
                addnav("Noch mal versuchen","xshop.php?op=name");
                addnav("Ich lass es lieber sein. Schnell raus hier!!!","markt.php");
                addnav("","xshop.php?op=changename");
        }else{
                output("`bFalscher Name`b`n$msg");
                output("`n`nDeine Waffe heißt bisher : ");
                $output.=$n;
                output("`0, und wird so aussehen $newname");
                output("`n`nWie soll deine Waffe heißen?`n");
                $output.="<form action='lodge.php?op=namepreview' method='POST'><input name='newname' value=\"".HTMLEntities($regname)."\"size=\"30\" maxlength=\"50\"> <input type='submit' value='Vorschau'></form>";
                addnav("","xshop.php?op=namepreview");
        }
} else
if ($_GET['op']=="changename"){
page_header("Namenswechsel");
if($session['user']['gold']<1999) {
    output("Du hast nich genug Gold also verschwinde");
    }else{
    output("Gratulation, deine Waffe wurde von dem Schmied Graviert`0!`n`n");
             $session['user']['weapon']=$_POST['name'];
             $session['user']['gold']-=2000;
             //debuglog:("sell 2000 gold");
             
    }
        addnav("Zurück zum Marktplatz","markt.php");
}
        if ($_GET['op']=="rname") {
        page_header("Eine Rüstung benennen");
            output("`bEine Rüstung benennen`b`n`n");
        
        output("`n`nDer Name deiner Rüstung darf 50 Zeichen lang sein und Farbcodes enthalten.`n`n");
          $n = $session['user'][armor];
    
        output("Deine Rüstung heißt bisher : `n");
        $output.=$n;
        output("`n`n`0Wie soll deine Rüstung heißen ?`n");
        $output.="<form action='xshop.php?op=rnamepreview' method='POST'><input name='newname' value=\"".HTMLEntities($newname)."\" size=\"30\" maxlength=\"50\"> <input type='submit' value='Vorschau'></form>";
        addnav("","xshop.php?op=rnamepreview");
addnav("Zurück","markt.php");

}elseif ($_GET['op']=="rnamepreview"){
        $n = $session[user][name];
   
        $_POST['newname']=str_replace("`0","",$_POST['newname']);
       
        if (strlen($_POST['newname'])>50) $msg.="Der neuer Name ist zu lang, inklusive Farbcodes darf er nicht länger als 50 Zeichen sein.`n";
        $colorcount=0;
        for ($x=0;$x<strlen($_POST['newname']);$x++){
            if (substr($_POST['newname'],$x,1)=="`"){
                        $x++;
                        $colorcount++;
                }
        }
        if ($colorcount>getsetting("rmaxcolors",10)){
                $msg.="Du hast zu viele Farben im Namen benutzt. Du kannst maximal ".getsetting("maxcolors",10)." Farbcodes benutzen.`n";
        }
        if ($msg==""){
        page_header("TEST");
                output("Deine Rüstung wird so heißen: {$_POST['newname']}`n`n`0Ist es das was du willst?`n`n");
                $p = 10;
                $output.="<form action=\"xshop.php?op=rchangename\" method='POST'><input type='hidden' name='name' value=\"".HTMLEntities($_POST['newname'])."\"><input type='submit' value='Ja' class='button'>, meine Rüstung heißt nun ".appoencode("{$_POST['newname']}`0")." für 2000 Gold.</form>";
                addnav("Noch mal versuchen","xshop.php?op=rname");
                addnav("Ich lass es lieber sein. Schnell raus hier!!!","markt.php");
                addnav("","xshop.php?op=rchangename");
        }else{
                output("`bFalscher Name`b`n$msg");
                output("`n`nDeine Rüstung heißt bisher : ");
                $output.=$n;
                output("`0, und wird so aussehen $newname");
                output("`n`nWie soll deine Rüstung heißen?`n");
                $output.="<form action='lodge.php?op=rnamepreview' method='POST'><input name='newname' value=\"".HTMLEntities($regname)."\"size=\"30\" maxlength=\"50\"> <input type='submit' value='Vorschau'></form>";
                addnav("","xshop.php?op=rnamepreview");
        }
} else
if ($_GET['op']=="rchangename"){
page_header("Namenswechsel");
if($session['user']['gold']<1999) {
    output("Du hast nich genug Gold also verschwinde");
    }else{
    output("Gratulation, deine Rüstung wurde von dem Schmied Graviert`0!`n`n");
             $session['user']['armor']=$_POST['name'];
             $session['user']['gold']-=2000;
             //debuglog:("sell 2000 gold");
             
    }
        addnav("Zurück zum Marktplatz","markt.php");
}
page_footer();
?> 