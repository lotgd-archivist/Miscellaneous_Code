<?php
/*Minayas Tempel
Von Narjana für Arda
Kunstschule*/

require_once "common.php";
addcommentary();
checkday();

switch ($_GET['op'])
{

        case "gar":
        if ($_GET[op]=="gar")
        {
                page_header ("Kunstschule");
                output("`c`bKreativgärten`b`c`n`n
                Blumen und Frische Luft erwarten dich hier draußen wo Schüler versuchen das Kreative Gärtnern, aber auch Bildhauern und dergleichen zu vervollkommnen.`n`n");

                viewcommentary("gar","reden",15);

                addnav("zurück","kunst.php");


        break;
        }

        case "musik":
        if ($_GET[op]=="musik")
        {
                page_header ("Kunstschule");
                output("`c`bMusikraum`b`c`n`n
                Du betrittst die Schule all derer, die die Muse suchen (coming soon)`n`n");

                viewcommentary("halle","reden",15);

                addnav("Schreibstube","kunst.php?op=stube");
                addnav("Arbeitsraum","kunst.php?op=raum");
                addnav("zurück","kunst.php?op=halle");


        break;
        }

        case "stube":
        if ($_GET[op]=="stube")
        {
                page_header ("Kunstschule");
                output("`c`bSchreibstube`b`c`n`n
                Du betrittst die Schule all derer, die die Muse suchen (coming soon)`n`n");

                viewcommentary("halle","reden",15);

                addnav("Musikraum","kunst.php?op=musik");
                addnav("Arbeitsraum","kunst.php?op=raum");
                addnav("zurück","kunst.php?op=halle");


        break;
        }
        case "raum":
        if ($_GET[op]=="raum")
        {
                page_header ("Kunstschule");
                output("`c`bArbeitsraum`b`c`n`n
                Du betrittst die Schule all derer, die die Muse suchen (coming soon)`n`n");

                viewcommentary("halle","reden",15);

                addnav("Musikraum","kunst.php?op=musik");
                addnav("Schreibstube","kunst.php?op=stube");
                addnav("zurück","kunst.php?op=halle");


        break;
        }
        case "halle":
        if ($_GET[op]=="halle")
        {
                page_header ("Kunstschule");
                output("`c`bKunstschule`b`c`n`n
                Du betrittst die Schule all derer, die die Muse suchen. Du kannst von hier aus mehrere Räume betreten, oder sogar einen Auftrag für einen
                Avatar oder ein Kunstwerk für dein Heim aufgeben (süäter zumindest)`n`n");


        /*          if (db_num_rows($result)<=0){
                output("`^Die Auftragsliste ist momentan völlig leer");
        }else{
                output("`^Am schwarzen Brett neben der Tür flattern einige Nachrichten im Luftzug:");
                for ($i=0;$i<db_num_rows($result);$i++){
                        $row = db_fetch_assoc($result);
                        output("`n`n<a href=\"mail.php?op=write&to=".rawurlencode($row['login'])."\" target=\"_blank\" onClick=\"".popup("mail.php?op=write&to=".rawurlencode($row['login'])."").";return false;\"><img src='images/newscroll.GIF' width='16' height='16' alt='Mail schreiben' border='0'></a>",true);
                        output("`& $row[name]`&:`n`^$row[message]`0");
                        if ($row[acctid]==$session[user][acctid]){
                                output("[<a href='kunst.php?op=msgboard&act=del'>entfernen</a>]",true);
                                addnav("","kunst.php?op=msgboard&act=del");
                        }
                }
        }*/



                viewcommentary("halle","reden",15);


                addnav("Musikraum","kunst.php?op=musik");
                addnav("Schreibstube","kunst.php?op=stube");
                addnav("Arbeitsraum","kunst.php?op=raum");
        //        addnav("Einen Auftrag geben","kunst.php?op=msgboard");
                addnav("zurück","kunst.php");


        break;
        }

        /*        case "msgboard":
        if ($_GET[act]=="del"){
                $session[user][message]="";
                $session[user][msgdate]="0000-00-00 00:00:00";
                output("`QDu reisst deine eigene Nachricht vom schwarzen Brett ab. Der Fall hat sich für dich erledigt.");
                addnav("Neue Nachricht","kunst.php?op=msgboard");
        }else if ($_GET[act]=="add1"){
                $msgprice=$session[user][level]*6*(int)$_GET[amt];
                output("`QCedrik kramt einen Zettel und einen Stift unter der Theke hervor und schaut dich fragend an, was er für dich schreiben soll. Offenbar ");
                output("`Qsind viele seiner Kunden der Kunst des Schreibens nicht mächtig. \"`@Das macht dann `^$msgprice`@ Gold. Wie soll die Nachricht lauten?`0\"`n`n");
                output("<form action=\"kunst.php?op=msgboard&act=add2&amt=$_GET[amt]\" method='POST'>",true);
                output("`n`^Gib deine Nachricht ein:`n<input name='msg' maxlength='250' size='50'>`n",true);
                output("<input type='submit' class='button' value='Ans schwarze Brett'>",true);
                addnav("","kunst.php?op=msgboard&act=add2&amt=$_GET[amt]");
        }else if ($_GET[act]=="add2"){
                $msgprice=$session[user][level]*6*(int)$_GET[amt];
                $msgdate=date("Y-m-d H:i:s",strtotime(date("r")."+$_GET[amt] days"));
                if ($session[user][gold]<$msgprice){
                        output("`QAls Cedrik bemerkt, dass du offensichtlich nicht genug Gold hast, schnauzt er dich an: \"`CSo kommen wir nicht ins Geschäft, Kleine".($session[user][sex]?"":"r").". Sieh zu, dass du Land gewinnst. Oder im Lotto.`0\"");
                }else{
                        output("`QMürrisch nimmt Cedrik dein Gold, schreibt deinen Text auf den Zettel und ohne ihn nochmal durchzulesen, heftet er ihn zu den anderen an das schwarze Brett neben der Eingangstür.");
                        $session[user][message]=stripslashes($_POST[msg]);
                        $session[user][msgdate]=$msgdate;
                        $session[user][gold]-=$msgprice;
                }
        }else{
                $msgprice=$session[user][level]*6;
                $msgdays=(int)getsetting("daysperday",4);
                output("\"`@Du möchtest eine Nachricht am schwarzen Brett hinterlassen, ja? Wie lang soll die Nachricht denn dort zu sehen sein?`Q\" fragt dich Cedrik fordernd und nennt die Preise.");
                addnav("$msgdays Tage (`^$msgprice`0 Gold)","kunst.php?op=msgboard&act=add1&amt=1");
                addnav("".($msgdays*3)." Tage (`^".($msgprice*3)."`0 Gold)","kunst.php?op=msgboard&act=add1&amt=3");
                addnav("".($msgdays*10)." Tage (`^".($msgprice*10)."`0 Gold)","kunst.php?op=msgboard&act=add1&amt=10");
                if ($session[user][message]>"") output("`n`QEr macht dich noch darauf aufmerksam, dass er deine alte Nachricht entfernen wird, wenn du jetzt eine neue anbringen willst.");
        }
        break;*/


    default:
if ($_GET[op]=="")    {

        page_header("Kunstschule");

        output("`c`bKunstschule`b`c`n`n

                Beschreibung folgt`n`n");


        viewcommentary("kunst","sagt",15);

                addnav("Hinein","kunst.php?op=halle");
                addnav("Kreativgärten","kunst.php?op=gar");
                addnav("zurück","univier.php");


        break;
    }

}
page_footer();
?> 