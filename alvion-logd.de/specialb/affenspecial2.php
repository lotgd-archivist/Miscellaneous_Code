
<?php
// Grundidee der Affenjäger von logd.de
// Affenjägermodifikation © by Hecki für http://www.cop-logd.de
// Hexenjäger und Diebesjäger © by Hecki für http://www.cop-logd.de
// 27.02.05 - 02.03.05
// Special Modifikationen © by Hecki, danke an Alle Autoren der Original Specials :o)

require_once"common.php";
page_header("Der Affe");

if($_GET['op'] == '' || $_GET['op']== 'search'){ 
$session[user][specialinc]="affenspecial2.php";

        $session[user][specialinc] = "affenspecial2.php";
         if ($session[user][gems]>0){
                $session[user][gems]--;
                addnav("Affen jagen","berge.php?op=attacke");
                addnav("Affen leben lassen","berge.php?op=leave");
                output(" Du spürst einen Ruck an deiner Edelsteinsammlung, und kurz darauf siehst du ein Äffchen mit einem deiner Edelsteine im Bergwald verschwinden.`0");
                output("`^VERFLUCHTER AFFE! Deine Affenjägerinstinkte werden wach, willst du versuchen. ihn zu erwischen?");

        }else{
                output("Du spürst einen Ruck an deiner Edelsteinsammlung, aber glücklicher Weise hast du keine Edelsteine dabei und machst dir darum auch keine Sorgen wegen dem Äffchen, das scheinbar enttäuscht zurück in den Bergwald läuft.`0");
                $session[user][specialinc] = "";


            }
}

else if ($_GET[op]=="attacke"){
        $erfolg=0;
        if ($session[user][attack] > 10)
        {
                if (e_rand(1,3) >1) {$erfolg=1;}
        }
        else
        {        if (e_rand(1,2)==1) {$erfolg=1;}
        
        }
        if ($erfolg==1){ output("`@ Du erwischst den Affen im Gebüsch und erledigst ihn mit deiner Waffe: `\$".$session[user][weapon]."`@!`n");
                            output("`@ Du bekommst deinen Edelstein zurück und findest in der anderen Hand des erschlagenen Affen noch einen weiteren.`n");
                            $session[user][gems]+=2;
        }else{
        output("`@ Leider entkommt dir das Äffchen mit deinem Edelstein.");
        $session[user][specialinc] = "";

         }

}

else if ($_GET[op]=="leave")
{        addnav("Weiter","berge.php");
      output("Du kehrst in die Berge zurück");
      $session[user][specialinc] = "";
      
}

?>

