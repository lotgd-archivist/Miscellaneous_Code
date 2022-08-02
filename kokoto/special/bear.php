<?php
//*-------------------------*
//| bear.php                |
//| Scriptet by             |
//| °*Amerilion*°           |
//| steffenmischnick@gmx.de |
//*-------------------------*
//gesäubert und angepasst by Tidus (www.kokoto.de)
if (!isset($session)) exit();

if ($_GET['op']=='hineingehen'){
    output('`n`n`n`c`JDu b`)etr`7ittst die Höhle und be`)mer`Jkst,`n`Jdas`)s si`7e vor kurzer Zeit noch von einem wilden Bären bewohnt sein `)mus`Jste.`n`JDu z`)uck`7st mit den Schultern und rollst dich in einer Ecke zu`)sam`Jmen.`c');
    switch(mt_rand(1,3)){
        case '1':
        case '2':
            output('`n`n`c`7Nac`)h ei`Jnem erholsamen Schlaf wachst du auf und gehst munter wieder in den Wald `)zur`7ück.`c`0');
            $session['user']['turns']+=3;
            addnews('`7'.$session['user']['name'].' `7 hat wie ein Höhlenmensch in einer Höhle geschlafen!');
            $session['user']['specialinc']='';
            addnav('Wald','forest.php');
        break;
        case '3':
            output('`n`n`c`7Du w`)ach`Jst von einem strengen Geruch auf und öffnest langsam di`)e Au`7gen.`n `7Du b`)lic`Jkst direkt auf einen riesige`)n Bä`7ren.`n`7Bev`)or d`Ju deine Waffe auch nur ziehen `)kan`7nst,`n`7hol`)t er`J mit seiner Tatze aus und verletzt dich t`)ödl`7ich. `n`n`4Luzifer `)findet deinen `$Tod `7recht amüsant und ihm `igefällt`i`7 deine Tierliebe.`c');
            $session['user']['alive']=false;
            $session['user']['deathpower']+=15;
            $session['user']['hitpoints']=0;
            $session['user']['gold']=0;
            $session['user']['experience']0.97;
            addnews('`7'.$session['user']['name'].' `7 hat mit einem `tBär`7 gekuschelt!');
            addnav('Tägliche News','news.php');
            $session['user']['specialinc']=''; 
       break;
        
    }
}else if ($_GET['op']=='weitergehen'){
    output('`c`7Du u`)mru`Jndest den Hügel,');
   
    switch(mt_rand(1,3)){
        case '1':
            output(' findest aber nichts was dich weiter intere`)ssi`7ert.`c`0');
            $session['user']['specialinc']='';
            addnav('Wald','forest.php');
        break;
        case '2':
        case '3':
            output(' wo du zwischen einigen Steinen einen `#Edelstein `)find`7est!`c`0');
            $session['user']['gems']++;
            addnav('Wald','forest.php');
            $session['user']['specialinc']='';
        break;
        
    }
}else{
    output('`n`n`n`c`b`JD`)i`7e Höh`)l`Je`b`c`n `c`JWäh`)ren`7d deiner täglichen Streifzüge durch den Wald kommst du an eine`)n Hü`Jgel.`n `JDu g`)ehs`7t ein wenig um ihn herum und siehst, dass eine kleine Höhle hinei`)nfü`Jhrt.`n `JDu b`)eme`7rkst, dass du ein wenig Erholung brauchen kö`)nnt`Jest.`n`JIn d`)er H`7öhle wärst du wohl für einige Zeit gut aufg`)eho`Jben.`c`n');
    addnav('Die Höhle');
    $session['user']['specialinc']='bear.php';
    addnav('Hineingehen','forest.php?op=hineingehen');
    addnav('Weitergehen','forest.php?op=weitergehen');
}
?>