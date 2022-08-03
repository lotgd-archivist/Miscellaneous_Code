<?php
//*-------------------------*
//|           Sanela        |
//|        by Amerilion     |
//|       first seen on     |
//|      mekkelon.de.vu     |
//*-------------------------*

//Sanela-Pack Version 1.1

require_once "common.php";

if (getsetting("automaster",1) && $session['user']['seenmaster']!=1){
//if (getsetting("automaster",1) && $session['user']['seenmaster']!=2){
        //masters hunt down truant students
        $exparray=array(1=>100,400,1002,1912,3140,4707,6641,8985,11795,15143,19121,23840,29437,36071,43930,55000);
        while (list($key,$val)=each($exparray)){
                $exparray[$key]= round(
                        $val + ($session['user']['dragonkills']/4) * $session['user']['level'] * 100
                        ,0);
        }
        $expreqd=$exparray[$session['user']['level']+1];
        if ($session['user']['experience']>$expreqd && $session['user']['level']<15){
                redirect("train.php?op=autochallenge");
        }else if ($session['user']['experience']>$expreqd && $session['user']['level']>=15){
                redirect("dragon.php?op=autochallenge");
        }
}

page_header("Symia");
$session['user']['sanela']=unserialize($session['user']['sanela']);
checkday();
addcommentary();
if($_GET['op']==""){

        output(" `c`@S`Fy`om`fi`Oa`n`n`n`c

`c`SSt`sun`Pden bist du durch den Wald geirrt und jetzt endlich ha`sst `Sdu`n
`@S`Fy`om`fi`Oa`n
`Ser`srei`Pcht, die sagenumwobene Stadt der Elfen. `sDo`Sch`n
`Ssi`sch`Per bist du dir nicht, daß du wirklich richtig bist, denn du si`seh`Sst`n
`Ske`sin`Pe Häuser oder Elfen, nur riesige Bäume, die versuchen, `sde`Sn`n
`Sne`srv`Penden kleinen fliegenden Flawierda zu entkommen. Du m`sus`Sst`n
`Sau`sfp`Passen, nicht von einem herab sausenden Ast oder einer Wu`srz`Sel`n
`Sge`str`Poffen zu werden oder unter einen der Bäume zu geraten, die `ssi`Sch`n
`Sei`sn r`Puhigeres Plätzchen suchen wollen. Du bist dir nicht si`sch`Ser,`n
`Sob `sdu `Phier überhaupt richtig bist, da siehst du in den Wipfeln e`sin`Ses`n
`Sde`sr B`Päume, der sich gerade vor dir in die Tiefe beugt, um mit e`sin`Sem`n
`SAs`st n`Pach einem Flawierda zu schlagen, ein paar filigrane Hä`sus`Ser.`n
`SDi`sr s`Ptockt der Atem und du wendest deinen Blick nach `sob`Sen.`n
`SFa`ssz`Piniert und völlig überwältigt bleibst du stehen, so daß du j`set`Szt`n
`Sse`slb`Pst zum Opfer wirst. Schnell rennst du auf einen der Bäu`sme `Szu,`n
`San`s de`Pm eine Art Leiter befestigt ist und kletterst diese `sho`Sch.`n
`SOb`sen `Pangekommen kannst du es gar nicht glauben, was du si`seh`Sst.`n
`SEs `sis`Pt noch viel überwältigender, als du es dir je erträumt hät`ste`Sst,`n
`Ser`sst`Preckt sich die Stadt über mehrere Eb`sen`Sen.`n
`SAl`sle`Ps glitzert und funkelt in allen möglichen und unmögli`sch`Sen`n
`SFa`srb`Pen und Formen. Von hier kannst du auch den Marktplatz s`seh`Sen,`n
`Sde`sr s`Pich ein wenig abseits auf dem Boden befi`snd`Set,`n
`Swo`shl `Pum auch andere Händler anzulo`sck`Sen.`n`n`n`n`c");
        //output("und einige Markstände.`n`n");
        addnav("Symia");
    //addnav("Brunnen","sanelabrunnen.php");
    addnav("Marktplatz","center.php");
        //addnav("See","sanelasee.php");
        addnav("Kirche","kirche.php");

        addnav("Strickleiter","elfengarten.php");
       // addnav("Göttertempel","tempelgott.php"); //Erstmal raus bis der Tempel fertig ist
 if($session['user']['sanela']['haganirschmiede']==0){
                switch(e_rand(1,4)){
                        case 1:
                        output("Heute ist nicht viel los.");
                        break;
                        case 2:
                        output("Ausser einem streunenden Hund siehst du keinen Einwohner.");
                        break;
                        case 3:
                        case 4:
                        $session['user']['sanela']['haganir']=1;
                        break;
                }
                $session['user']['sanela']['haganirschmiede']=1;
        }
    if ($session['user']['weapondmg']<67||$session['user']['armordef']<67){        
    if($session['user']['sanela']['haganir']==1){
                output("Heute ist viel los, selbst Haganir hat seine Schmiede geöffnet.`n`n");
                addnav("Haganirs Schmiede","sanelaschmiede.php");
        }
    }else{
                output("Nichts Besonderes halt.`n`n");
        }

//        addnav("Wanderweg","wanderweg.php"); Narjana
        addnav("zurück");
        addnav('Ebene der Fantasie','orte.php');
        addnav("Wegkreuzung","kreuzung.php");
        addnav("Kleine Hafenmole","necron_hafen.php");
        addnav("Die Hütte der Heilerin","healer.php");
        addnav("In den Wald","forest.php");
        addnav("Waldlichtung","waldlichtung.php");
        addnav("Landreise","landreise.php");

        /*if ($session['user']['rlalter']>=18){
addnav("~ Ü18 ~");
addnav("Ferienhaus","frdn.php");
}*/


        //addnav("Universität","uni.php");
        addnav("Weg zum Strand","sanelastrand.php");

        viewcommentary("sanela","Hinzufügen",15);
}
page_footer();
?>