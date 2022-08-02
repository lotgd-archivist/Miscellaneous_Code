<?php
#######  #####    ######   ######
#        #    #   #    #   #
###      # # #    #    #   ######
#        #   #    #    #        #
######   #     #  ######   ######
#################################
# Schlangengrube                #
# Idee Ajuba                    #
# Texte Rhebanna & Ajuba        #
# Umsetzung Ajuba & Rhebanna    #
# v0.2                          #
# v.1.0 von Tidus www.kokoto.de #
#################################


page_header("Schlangengrube");
output('`b`c`n`2S`@ch`2l`2a`@ng`2e`2n`@gr`2u`@be`b`c`n`n');
if ($_GET['op']=='schwert'){

        output('`GHastig ziehst du dein `SS`)c`7h`&w`7e`)r`St `Gaus der Scheide, schlägst damit nach den
        `&S`pch`El`ya`Eng`pe`&n `Gund versuchst dir einen Weg aus der `)G`Brub`)e `G zu kämpfen.');
 switch(e_rand(1,3)){
case 1:

     output('`GViele der `&gi`pftig`Ee`yn `EBie`pst`&er `Gkannst du töten, aber einige überleben und können ihre
     `&Z`7ä`)h`7n`&e `Güberall in dich schlagen. Du hast keine Chance, und stirbst an dem `pGif`pt.');


     addnews($session['user']['name']."`@ dachte, gegen Giftschlangen etwas ausrichten zu
     können und starb schmählich.");
     $session['user']['alive']=false;
     $session['user']['hitpoints']=0;
     $session['user']['gold']=0;
        $session['user']['specialinc']='';

     addnav('Zu den News','news.php');
break;

case 2:
case 3:
      output('`GDir ist klar, dass du so nicht alle `&S`pch`El`ya`Eng`pe`&n `Gvernichten kannst, und so
      schlägst du dir eine `qBr`Besc`qhe `Gfrei, durch die du um Haaresbreite dem `&T`So`&d
      `Gentgehen kannst. Auf dem Weg findest du drei Edelsteine');
      $session['user']['specialinc']='';
     $session['user']['gems']+=3;
      addnav('weiter','forest.php');
      break;
}



}else if ($_GET['op']=='fackel'){
        output('`GVorsichtig tastest du nach deiner `TFa`Qcke`Tl`G. Du versuchst jede hastige
        Bewegung, die die `&S`pch`El`ya`Eng`pe`&n `Gprovozieren könnte, zu vermeiden. Du willst die
        aggressiv zischenden `&S`pch`El`ya`Eng`pe`&n `Gvor dir nicht aus den Augen lassen, so tastest
        du  blind drauf los und hoffst, nicht in ein geöffnetes `&S`pch`El`ya`Fnge`yn`Ema`pu`&l
        `Gzu greifen. Immer näher kommen die `&S`pch`El`ya`Eng`pe`&n`G, immer bedrohlicher wird die
        Situation, als du die `TFa`Qcke`Tl `Gerwischst und sie anzündest. Jetzt flüchtest du
        panisch und beim Hochkrabbeln kommst du der `4F`kl`Qa`^m`Qm`ke `Gzu nah, sie entflammt
        deine `)H`Sa`ua`Sr`)e`G.');

        addnews($session['user']['name']." `@wurde gesehen, wie ".($session['user']['sex']?"sie":"er")." mit flammenden Haaren durch
        den Wald rannte");
        $session['user']['charm']-=5;
        $session['user']['specialinc']='';
        addnav('in den Wald','forest.php');

}else if ($_GET['op']=='flucht'){

     output('`GPanisch schaust du dich um, von `&S`pch`El`ya`Eng`pe`&n `Gumzingelt, die du mit einem
     `&A`Fug`&e `Gimmer im Blick hast, und suchst nach einem Fluchtweg. Eine Seite der `)Gr`Bu`)be
     `Gkommt dir weniger steil vor, und du machst dich daran, dort hoch zu kraxeln. Doch da kannst
     du dich nicht mehr halten, rutschst aus und kannst nur noch zusehen, wie die
     `&S`pch`El`ya`Eng`pe`&n `Gnach dir schnappen und dich beißen.');

switch(mt_rand(1,3)){
case 1:

     output('`IDu spürst deutlich, wie sich das `pGif`pt `Gschmerzhaft in deinem Körper verteilt, bevor
     dir langsam das `tBe`qwu`Bsst`qse`tin `Gschwindet und du weißt, dass du gleich sterben wirst.');


     addnews($session['user']['name']."`@ ist mal wieder zu Besuch bei Luzifer");
     $session['user']['alive']=false;
     $session['user']['hitpoints']=0;
     $session['user']['specialinc']='';
     $session['user']['gold']=0;

     addnav('Zu den News','news.php');
break;

case 2:
case 3:
      output('`GÜberrascht stellst du fest, dass deine `7R`)ü`Sstu`)n`7g `Gstark genug ist und die
      `pGif`ptzä`3hn`pe `Gvon deinem Körper fern gehalten hat. Unversehrt kannst du dich aus
      der `)Gr`Bu`)be `Gretten.');
      $session['user']['specialinc']='';
      addnav('weiter','forest.php');
	  break;
}

}else if ($_GET['op']=='sterben'){
        output('`GStarr vor Schreck siehst du in die `&A`7u`)g`7e`&n `Gder `&S`pch`Elan`pg`&e `Gvor dir.
        Ein kurzer Schrei kommt dir noch über die Lippen, bevor deine Ophiophobie dein Herz
        stillstehen lässt und du tot zusammen sackst. Als du vor `&Ra`kmiu`&s `Gstehst, sagt
        dieser: "`4Nein, so einfach kannst du dich nicht verdrücken!`G" Kaum hat er seine Worte
        beendet stehst du wieder lebendig in der `)Gr`Bu`)be `Ivoller `&S`pch`El`ya`Eng`pe`&n`G.');
        $session['user']['specialinc']="schlangengrube.php";
        addnav("beschämt dein Leben fortsetzen","forest.php");
}else{
        output('`GDu wanderst ein wenig gedankenverloren durch den `2W`@al`2d`G, als auf einmal
        der `TBo`Bd`Ten `Gunter dir nachgibt und du in eine `)G`Bru`Bb`)e `Gfällst. Leider landest
        du dabei auf dem Kopf, nach kurzer Zeit hast du dich von deiner Benommenheit erholt und
        hörst du um dich herum ein vielstimmiges `eZ`Gi`gs`@c`gh`Ge`en. `GIn diesem Moment kriecht
        dir etwas `dK`Da`&lt`de`ds `Güber deinen Fuß. Du schaust dich um und erkennst lauter
        `&S`pch`El`ya`Eng`pe`&n `Gum dich herum. ');

        addnav('mit dem Schwert nach den Schlangen schlagen','forest.php?op=schwert');
        addnav('deine Fackel suchen','forest.php?op=fackel');
        addnav('panisch versuchen zu flüchten','forest.php?op=flucht');
        addnav('vor Schreck sterben','forest.php?op=sterben');
        $session['user']['specialinc']='schlangengrube.php';
}

?>
