<?php
require_once "common.php";
addcommentary();
   switch($_GET['op'])

{

    case 'Gasse':

    {

        page_header("Blutbar");

        output("`c`b`wBlu`4tb`W`ear `4„B`wizz“`c`b`n`n 

            `c`wDunke`4l und still war die Gasse in der Du nun s`wtehst,`n

            `wkein`4e Seele ist zu sehen und die Häuser wirken b`wedrohlich.`n 

            `wSolltes`4t du dch weiter wagen wird Dir irgendwann ein vier Stockwerke hohes Haus a`wuffallen,`n

            `wbis a`4uf seine Größe wirkt es fast norma`wl, fast.`n

            `wDiese`4s Haus hat nicht ein einziges Fenster und neben der breiten Doppeltür hängt ein o`wffenes`n

            `wGebis`4s welches aussieht als würde es gleich zuschnappen w`wollen.`n

            `wWen`4n man nicht weiß was man hier sucht wird man hier auch nichts f`winden. `n

            `n

            `wKlopfen`4d, öffneten zwei Männer dir die Tür und mustern D`wich,`n

            `wden`4n Zutritt erhält hier nicht j`weder. `n

            `n

            `wSolltes`4t du passieren dürfen, betrachtet Dich ein blinder Mann und erkennt sofort deine R`wasse.`n

            `wDiese`4r entscheidet ob Du hinein gelassen wirst oder draußen bleiben m`wusst.`n

            `n

            `wWar d`4ieses Haus mehr für die Bestimmt die sich am Blut ergötzten, es t`wrinken.`n

            `wFür W`4esen wie Menschen war hier kein P`wlatz.`n

            `n

            `wUnd j`4e nachdem welches deine Belange und deine Rass`we sind,`n

            `wwirs`4t du hinein gelassen. Diskretion ist hier oberstes G`webot, `n

            `wdeshal`4b kannst Du dich entscheiden ob du in die „V`worbar“ `n

            `wgehe`4n möchtest oder doch lieber gleich ungesehen zu einem „B`wlutwirt“ `n

            `wvorgelasse`4n werden m`wöchtest. `c`n

            `n`n`0");                      



           viewcommentary("blutvor","sagt:",10);

        addnav("Betreten","blutbar.php");  

        addnav("Lieber wieder gehen","marktplatz.php");

        

        break;

    }

        case 'Blutwirt':

    {

        page_header("Blutbar");

        output("`c`b`wBlu`4tb`W`ear `4„B`wizz“`c`b`n`n 

            `c`RHat ma`Gn sich nun ein „Wesen“ a`Rusgewählt, `n

            `Rwird man aus dem Vo`Grraum begleitet von diesem oder von einer W`Rache zu dieser geführt.`n

            `REs folgt die Be`Gzahlung, mit genauer Besprechung was gema`Rcht werden darf,`n

            `Reher ma`Gn in einen der vielen Räume gef`Rührt wird.`n

            `n

            `RSind di`Gese aufgebaut wie ein gemütlicher W`Rohnraum, `n

            `Rim Kamin k`Gistert ein Feuer welches angenehme Wärme a`Russtrahlt. `n

            `RVor di`Gesem liegt ein großes weiches und einladend`Res Fell.`n

            `RNeben einem Sofa m`Git Tisch, befindet sich ebenso ein breites Dopp`Relbett in diesem Raum,`n

            `Rwelches mi`Gt Seide überzogen ist und eine Flut aus Kis`Rsen bietet.`n

            `n

            `RParallel zu deiner T`Gür durch welche du kommst, kommt der Blut`Rwirt aus der anderen.`n

            `R( We`Gnn du nicht begleitet wi`Rrst. )`n

            `RAuch v`Gerlasst ihr genauso die Räume w`Rieder.`n

            `n

            `i`wJedoc`4h werden Dir auch die Regeln e`wrklärt:`n

            `n

            `wVer`4boten s`wind:`n

            `w- T`4öt`wen`n

            `w- Blu`4tleer tri`wnken`n

            `w- Sex o`4hne Erlau`wbnis`n

            `w- Schw`4erwiegende Verletz`wungen`n

            `w- im allg`4emeinen nur das was der `wWirt zulässt`n

            `n

            `wSollt`4e es zur Bewusstlosigkeit k`4ommen, `n

            `weinfac`4h den Raum verlassen es wird sich um Diesen g`wekümmert.`i`n`c

            `n`n`0

            `c`n");                      



        viewcommentary("Blutwirt","sagt:",25);      

        addnav("Zurück in die Bar","blutbar.php");    

        addnav("Verlassen","blutbar.php?op=Gasse");

        

        break;

    }

    default:

    {

                                             

        page_header("Blutbar");           



        output("`c`b`wBlu`4tb`W`ear `4„B`wizz“`c`b`n`n 

            `c `RDas ganz`Ge Ambiente ist sehr edel und dunkel g`Rehalten.`n

            `REs gibt T`Gische und einen Theke hinter der ein junger Mann steht und Getränk`R ausschüttet, `n

            `Rwelch`Ge mit Blut versetz`Rt sind. `n

            `RHinter d`Giesem hängt ein Schild auf welche`Rm das Blut`n

            `Rvon Dä`Gmonen, Elfen und andere Wesen angebote`Rn wird, `n

            `Rda di`Geses magische Wirkstoffe e`Rnthält.`n

            `RDa Qua`Glität ihren Preis hat, ist dies dementsprechend au`Rch teurer,`n

            `R aber a`Guf Nachfrage ist auch mehr m`Röglich, `n

            `RDies h`Gängt jedoch mit den Frauen/Männern z`Rusammen, `n

            `Rwelche e`Gntscheiden was noch zugelass`Ren wird.`n

            `RDer jung`Ge Mann berät dich gern und gibt genauere A`Ruskunft.`n

            `n

            `RAnsehnlich`Ge Wesen treiben sich hier herum und leisten, den möglichen Kunden g`Resellschaft.`n

            `RDies i`Gst kein Bordell, hier geht es einzig und alleine nur u`Rm Blut.`n

            `n    

            `REine we`Gitere Tür geht von dem Raum ab, jedoch stehen dort zwei bewaffnete Männe`Rr welche`n `RLeute n`Gur in Begleitung einer der „Blutwirte“ hindurc`Rh lassen.`n

            `n

            `REine ande`Gre Möglichkeit ist natürlich noch aus den edleren „Spendern“ zu wählen, di`Re nicht`n `Ranwesen`Gd, aber auch wesentlich teu`Rrer sind. `n`c

            `n`n`0");                     





        viewcommentary("blutbar","sagt",25);      



        addnav("Schnell raus hier","blutbar.php?op=Gasse");

        addnav("In ein Einzelzimmer","blutbar.php?op=Blutwirt");



        

        break;

     }

    

}                                 

page_footer();



?> 