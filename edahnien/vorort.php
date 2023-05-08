<?php
/*Vor dem Stadttor
By Mr.Edah
www.edahnien.de
*/
require_once "common.php";
require_once "./lib/controll.php";

addcommentary();
page_header("Stadttor");
$out ='';
switch($_GET['op']){
     case '':
     //Navigation
        addnav("Stadttor");
        addnav("E?Nach Edahnien","village.php");
        addnav("t?Stadtwall","waldlichtung.php?op=stadtwall");
        $array = getdate();
        if ( ( $array["mon"] == 11 && $array["mday"] > 14 ) || $array["mon"] == 12){
        addnav("Besonderes");
        addnav("i?`@We`5ih`^na`�ch`Mts`wwa`\$ld","xmasforest.php");
        }
                addnav("Wege");
        addnav("N?Norden - Ins Gebirge","gebirge.php");
        addnav("O?Osten - An die K�ste","hafen.php");
        addnav("S?S�den - Die W�ste","wuestenstadt.php");
        addnav("W?Westen - Der Wald","forest.php");
        addnav("Sonstiges");
        addnav("#?In die Felder (Logout)","login.php?op=logout",true,false,false);
        $aktiv = getsetting("angriff","0");
        if ($aktiv==1) {
                $anzahl = getsetting("dangreifer","0");
        }
        if ($aktiv==1 && $session['user']['rpchar']==0) output("<font size=6> `\$ Das Dorf wird belagert!`n`n`n</font>",true);

        $out .="`c`b`)Das Stadttor Edahniens`0`b`c`n`n";

        $out .="<center><table><tr><td><img src=images/Edahnien3.4.jpg></td></tr></table></center>`n`n`n";
        $out .="`)Du stehst vor den Toren der imposanten Stadt ".COLOREDAHNIEN."".OEFFENTLICHESCOLORSTANDART."`).
                Erstaunt gehst du einige Schritte zur�ck um dir einen �berblick zu verschaffen, au�erdem hast du Respekt vor den grimmig dreinblickenden Wachen die das Tor sch�tzen.
                Du erkennst, dass die Stadt Mittelpunkt eines sehr vielf�ltigen Gebietes zu sein scheint.
                Im Norden entdeckst du schneebedeckte Berggipfel, die fast bis in den Himmel zu reichen scheinen,
                w�hrend sich im S�den eine endlose, sandige Ebene befindet, �ber der die Hitze ein Flimmern erzeugt.
                Aus dem Osten glaubst du das Rauschen von Meereswellen und das Gekreisch von M�wen zu vernehmen,
                und westlich erstreckt sich unendlicher Wald, der dir beinah eine G�nsehaut verursacht.
                Dich deucht, dass es hier tats�chlich mehr zu erkunden gibt, als du zuerst dachtest, und nun
                musst du dich wohl entscheiden, wohin dein Weg dich f�hren wird. W�hlst du eine der vier Himmelsrichtungen,
                oder erkundest du zun�chst die Stadt selbst mit ihren vielschichtigen Attraktionen?`n`n`n";
        $schreibfeld = 'vorort';

   break;
}
output("$out",true);
if ($schreibfeld != '') {
      viewcommentary("$schreibfeld","Hinzuf�gen",7);
}



if ($session['user']['prefs']['ooc']){
output("`n `n`n`n`\$Edahnischer OOC Bereich `n `n");
viewcommentary("ooc","Hinzuf�gen",15);
}



page_footer();
?>
        
        