<?php
/*_____________________________________________________________
  |Zeus' Thron                                                |
  |von Lord Eliwood                                           |
  |Mit letzter Hilfe von Hecki                                |
  |___________________________________________________________|
*/
//SQL
//ALTER TABLE `accounts` ADD `zeus` INT( 10 ) UNSIGNED NOT NULL ;
require_once "common.php";
page_header("Zeus Thron");
//////////////////////////////////////////////////////////////////////////////////////////////
output("`c`b`vZeus' Trohn`c`b`n`n");
//////////////////////////////////////////////////////////////////////////////////////////////
if($_GET['op']=="")
{    
    addnav("Kneifen","olymp.php");
    if($session['user']['zeus']==0)
    {
    output("Du gehst dem Weg zu Zeus' Trohn entlang und und triffst endlich auf Zeus, den Göttervater.");
    output("Er mustert dich mit Erfahrenem Blick und sagt dann: \"`Vich hab schon viel von dir gehört.");
    output("Ich gewähre dir eine Überraschung.\"`0.`n`nDu kannst dich nun entscheiden. Nimmst du das Angebot an, oder kneifst du?");
    addnav("Überraschung","zeus.php?op=we");
    }
    else
    {
    output("Du bist heute schon einmal bei Zeus gewesen und du willst dein Glück nicht nochmal herausfordern");
    }
}
//////////////////////////////////////////////////////////////////////////////////////////////
if($_GET['op']=="we")
{
    ///////////////////
    switch(e_rand(1,11))
    {
            case 1:
            output("Zeus nimmt einer seiner Blitze und wirft ihn auf dich. Du erschrickst und rennst davon.`n");
            output("Wegen deiner Flucht verlierst du an Ehrenhaftigkeit.");
            addnav("Zurück zum Olymp","olymp.php");
            $session['user']['reputation']-=5;
            $session['user']['zeus'] = 1;
            break;
            ///////////////
            case 2:
            case 3:
            output("Zeus nimmt einer seiner Blitze und wirft ihn auf dich. Mutig bleibst du stehen und der Blitz schlägt neben dir in den Boden ein.`n");
            output("Dank deinem Mut gewinnst du an Ehrenhaftigkeit.");
            addnav("Zurück zum Olymp","olymp.php");
            $session['user']['reputation']+=5;
            $session['user']['zeus']= 1;
            break;
            //////////////
            case 4:
            case 5:
            case 6:
            output("Zeus schlägt dich, doch du fühlst dich noch immer wie vor dem Schlag. Zeus erklärt dir, dass du die Wirkung des Schlags");
            output("erst in der Jägerhütte merken würdest. Was er wohl damit gemeint hat?");
            addnav("Zurück zum Olymp","olymp.php");
            $session['user']['donation']+=100;
            $session['user']['zeus']= 1;
            break;
            //////////////
            case 7:
            case 8:
            case 9:
            output("Zeus gibt dir einen Trank, den du sofort leerst. Erst nach einer weile merkst du, dass du einen Lebenspunkt dazu gewonnen hast.");
            addnav("Zurück zum Olymp","olymp.php");
            $session['user']['maxhitpoints']+=1;
            $session['user']['zeus']= 1;
            break;
            /////////////
            case 10:
            case 11:
            output("Zeus gibt dir einen Trank, den du sofort leerst. Erst nach einer weile merkst du, dass du einen Lebenspunkt verloren hast.");
            addnav("Zurück zum Olymp","olymp.php");
            $session['user']['maxhitpoints']-=1;
            $session['user']['zeus'] = 1;
            break;
    }
}
//////////////////////////////////////////////////////////////////////////

page_footer();
?>