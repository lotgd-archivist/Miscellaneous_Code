<?php
/*Ort Katakomben*/

require_once "common.php";
addcommentary();                     
checkday();
                                       
   switch($_GET['op'])
{ 
    case 'Feuer': 
    {
        page_header("Hölle");
        output("`c`b`WF`ee`Qu`qerfl`Qu`es`Ws`b`n`n

`qD`Qi`ee `WHöhle in die du gelangst, ist heiß und von Feuerschein erfü`el`Ql`qt.`n
`qM`Qi`et`Wten hindurch verläuft ein breiter Graben, gefüllt mit reinem, fließenden Fe`eu`Qe`qr,`n
`qu`Qn`ed `Wbald fällt dir auf, dass neben dem Rauschen der Flammen noch etwas zu hören `ei`Qs`qt.`n
`qS`Qc`eh`Wreie. Leiden, Klagen, Schmerzen, Reue. Schrilles Kreischen, langes Heu`el`Qe`qn.`n
`qE`Qs `es`Wcheint weit weg, aber jetzt wo du es bemerkt hast, unmöglich zu ignorie`er`Qe`qn.`n
`qE`Qr`es`Wt auf den zweiten Blick ist die Brücke zu se`eh`Qe`qn,`n
`qh`Qö`el`Wzern, rußgeschwärzt und so beschädigt, dass man sich fragt wie sie überhaupt noch st`ee`Qh`qt,`n
`qu`Qn`ed `Wwie viele bei dem Versuch, sie zu überqueren, schon in die Flammen stürz`et`Qe`qn.`n
`qW`Qe`er `Wlange genug durch die Flammen starrt, kann die andere Seite se`eh`Qe`qn,`n
`qu`Qn`ed `Wdort vielleicht sogar Gestalten erken`en`Qe`qn.`n
`qM`Qö`eg`Wlicherweise sogar den einen oder anderen, der sich auf die Brücke gewagt `eh`Qa`qt,`n
`qd`Qo`ec`Wh ob es je einer auf die andere Seite geschafft `eh`Qa`qt,`n
`qi`Qs`et `Wbei den hochschlagenden Flammen schwer vorzustel`el`Qe`qn.`n
`qA`Qu`ef `Wder Seite, von der man nicht zu fliehen braucht, führen zwei Gänge aus der Hö`eh`Ql`qe.`n
`qI`Qm `ee`Winen herrscht klamme Finster`en`Qi`qs,`n
`qd`Qe`er `Wandere ist von einem stetigen, kühlen Glühen erfü`el`Ql`qt.`n
`qM`Qa`en `Wkönnte es natürlich auch mit der Brücke versuc`eh`Qe`qn.`n`c`n");                      

        viewcommentary("Feuer","sagt:",25);  
        if ($session['user']['alive'])
        {
        addnav("Katakomben","katakomp.php");
        addnav("difuses Leuchten","moor_unten.php?op=mus");
    //    addnav("über den Fluss","katakomp.php?op=sicher");
        break;
     
        }
        else{
        addnav("zurück","thehell.php");
//        addnav("über den Fluss","katakomp.php?op=Fluss");
        break;
        }


    }
    case 'Fluss':
    {
        page_header("Hölle");
        output("`b`c`WF`ee`Qu`qerfl`Qu`es`Ws`b`n`n

");
        addnav("zurück","katakomp.php?op=Feuer");
        break;
        }

    case 'Grab':
    {
        page_header("Katakomben");
        output("`c    Hölle`c");
    
        viewcommentary("Grab","sagt:",25);
        addnav("zurück");
        addnav("zu den Kornfeldern","kreuzung.php?op=korn");
        break;
    }
    default:
    {
                                             
        page_header("Katakomben");           

        output("`c`b`&K`7a`Wt`wakom`Wb`7e`&n`b`n`n

`WD`we`Rr `GGrund unter deinen Füßen ist mehr als uneben - es kostet M`Rü`wh`We,`n
`Wü`wb`Re`Grhaupt auf den Beinen zu blei`Rb`we`Wn.`n
`WE`wr`Rs`Gt auf den zweiten Blick wird dir klar, dass das, was da unter deinem Gewicht knirs`Rc`wh`Wt,`n
`WK`wn`Ro`Gchen s`Ri`wn`Wd.`n
`WK`wn`Ro`Gchen, die vermutlich mal einen Teil der Wand gebildet ha`Rb`we`Wn.`n
`WS`wo `Rw`Gie die Reihen um Reihen von Schädeln dir gegenü`Rb`we`Wr,`n
`Ws`wo `Rw`Geit du sehen kannst den Gang entl`Ra`wn`Wg.`n
`WL`wi`Rn`Gks findest nur eine Sackga`Rs`ws`We,`n
`We`wi`Rn`Gen Altar überhäuft mit Schädeln, und Ansammlungen von kleineren Knoc`Rh`we`Wn,`n
`WF`wi`Rn`Ggergliedern vielleicht, in den Ec`Rk`we`Wn.`n
`WR`we`Rc`Ghts geht der Gang wei`Rt`we`Wr,`n
`Wu`wn`Rd `Ges scheint, als würde er von einem `wro`Wte`en Glü`Whe`wn `Gbeleuch`Rt`we`Wt.`n`c
");                     


        viewcommentary("katakomp","sagt",25);      

//        addnav("Grabräuberei","katakomp.php?op=Grab");        
        addnav("Feuerfluss","katakomp.php?op=Feuer");
        addnav("Mausoleum","moor.php?op=mauso");
        
        break;
     }
    
}                                 
page_footer();

?>