<?php
/*Nickeys Laden
Kräuterladen + Spielereigentum von Nikita
Einzufügen in Ardas Hafen
von Narjana von Arda*/

require_once "common.php";
addcommentary();
checkday();

switch ($_GET['op'])
{
        case "zimmer":
        if ($_GET[op]=="zimmer")
        {
                page_header ("Kräuterladen");
                output("`c`b`PKrä`suterla`Pden`b`c`n`n
                `n`n`PMit freundlichen Worten wurdest du gebeten der Besitzerin des Ladens zu folgen, welche dich durch eine fast unscheinbare Tür ins Hinterzimmer des Ladens führt. Die Mitarbeiter der jungen Frau wissen von diesem Laden doch betreten sie diesen nicht da sie ihn als Ort für spezielle Dinge sehen, die nur die junge Frau und ihre Kunden gedacht sind. Auch hier sind verschiedene Pflanzen, Kräuter aber auch merkwürdige Behälter mit verschiedenen Dingen zu finden. Freundlich aber ernst wirst du gefragt wie die junge Frau dir behilflich sein kann, den in diesem Zimmer werden spezielle Dienste angeboten. Von Heilung bis hin zur Herstellung spezieller Mixturen, je nachdem was dein Herz begehren mag und in der Macht der jungen Frau steht.");

                viewcommentary("zimmer","reden",15);

                addnav("zurück","kraut.php");


        break;
        }

        case "waren":
        if ($_GET[op]=="waren")
        {
                page_header ("Kräuterladen");
                output("`c`b`PKrä`suterla`Pden`b`c`n`n
                (coming soon)`n`n");


                addnav("zurück","kraut.php");


        break;
        }

    default:
if ($_GET[op]=="")    {

        page_header("Kräuterladen");

        output("`c`b`PKrä`suterla`Pden`b`c`n`n

                `sDraußen am Hafen herrscht reges Treiben. Hier und da werden verschiedene Sachen geschrien um den Lärm des Hafens zu übertönen, doch von alldem ist nichts mehr zu merken. Kaum das du den fast unscheinbaren Laden betreten hast, verstummt der Lärm und Ruhe umfängt dich. Hier und da kannst du verschiedene Töpfe mit außergewöhnlichen Pflanzen sehen aber auch Kräuter die man im heimischen Wald findet. Mit einem freundlichen Lächeln auf den Lippen wirst du im Laden begrüsst. Freundlich wird dir von der jungen Besitzerin des Ladens oder aber auch von einer ihrer Mitarbeiter Hilfe angeboten aber dir auch die Zeit gegeben dich einfach umzublicken, die verschiedenen Pflanzen und Düfte auf dich wirken zu lassen.`n`n");


        viewcommentary("kraut","sagt",15);

                //addnav("Waren ansehen","kraut.php?op=waren");
                addnav("Hinterzimmer","kraut.php?op=zimmer");
                addnav("raus","hafen.php");


        break;
    }

}
page_footer();
?> 