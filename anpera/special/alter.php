
ï»¿<?php



// 20060323



if (!isset($session)) exit();



if ($_GET['op']=="dagger"){

    $session['user']['turns']--;

    if (e_rand(0,1)==0){

        output("`#Du nimmst den Dolch von seinem Platz. Doch er lÃ¶st sich in deinen HÃ¤nden in Luft auf und du fÃ¼hlst eine Welle von Energie in deinen KÃ¶rper strÃ¶men!`n`n  `&Du erhÃ¤ltst 10 zusÃ¤tzliche Anwendungen in DiebeskÃ¼nsten.`n`n`#Aber du bist auch etwas traurig, denn diese Kraft wird morgen wieder verschwunden sein.");

        $session['user']['thieveryuses']=$session['user']['thieveryuses']+10;

    }else{

        output("`#Du nimmst den Dolch von seinem Platz. Doch er lÃ¶st sich in deinen HÃ¤nden in Luft auf und du fÃ¼hlst eine Welle von Energie in deinen KÃ¶rper strÃ¶men!`n`n  `&Du erhÃ¤ltst 3 Level in DiebeskÃ¼nsten!");

        $session['user']['thievery']=$session['user']['thievery']+3;

        $session['user']['thieveryuses']++;

    }

    addnav("ZurÃ¼ck in den Wald","forest.php");

    $session['user']['specialinc']="";



}else if ($_GET['op']=="skull"){

    $session['user']['turns']--;

    if (e_rand(0,1)==0){

        output("`#Du greifst nach dem SchÃ¤del. Vor deinen Augen lÃ¶st sich der SchÃ¤del auf und du fÃ¼hlst eine Energiewelle in deinen KÃ¶rper fahren!`n`n  `&Du erhÃ¤ltst 10 zusÃ¤tzliche Anwendungen der Dunklen KÃ¼nste.`n`n`#Aber du bist auch etwas traurig, denn diese Kraft wird morgen wieder verschwunden sein.");

        $session['user']['darkartuses']=$session['user']['darkartuses']+10;

    }else{

        output("`#Du greifst nach dem SchÃ¤del. Vor deinen Augen lÃ¶st sich der SchÃ¤del auf und du fÃ¼hlst eine Energiewelle in deinen KÃ¶rper fahren!`n`n  `&Du erhÃ¤ltst 3 Level in Dunklen KÃ¼nsten!");

        $session['user']['darkarts']=$session['user']['darkarts']+3;

        $session['user']['darkartuses']++;

    }

    addnav("ZurÃ¼ck in den Wald","forest.php");

    $session['user']['specialinc']="";



}else if ($_GET['op']=="wand"){

    $session['user']['turns']--;

    if (e_rand(0,1)==0){

        output("`#Du hebst den Stab von seinem Platz auf. In einem Lichtblitz verschwindet er und eine seltsame Kraft durchstrÃ¶mt deinen KÃ¶rper!`n`n  `&Du erhÃ¤ltst 10 zusÃ¤tzliche Anwendungen in Mystischen KrÃ¤ften.`n`n`#Aber du bist auch etwas traurig, denn diese Kraft wird morgen wieder verschwunden sein.");

        $session['user']['magicuses']=$session['user']['magicuses']+10;

    }else{

        output("`#Du hebst den Stab von seinem Platz auf. In einem Lichtblitz verschwindet er und eine seltsame Kraft durchstrÃ¶mt deinen KÃ¶rper!`n`n  `&Du erhÃ¤ltst 3 Levels in Mystischen KrÃ¤ften!");

        $session['user']['magic']=$session['user']['magic']+3;

        $session['user']['magicuses']++;

    }

    addnav("ZurÃ¼ck in den Wald","forest.php");

    $session['user']['specialinc']="";



}elseif ($_GET['op']=="schwert"){

    $session['user']['turns']--;

    if (e_rand(0,1)==0){

        output("`#Du greifst nach dem Schwert. In deinen starken HÃ¤nden zerbricht das Schwert. Dabei geht dessen Kraft auf dich Ã¼ber!`n`n  `&Du erhÃ¤ltst 10 zusÃ¤tzliche Anwendungen in Kampfkunst.`n`n`#Aber du bist auch etwas traurig, denn diese Kraft wird morgen wieder verschwunden sein.");

        $session['user']['warriorsartuses']+=10;

    }else{

        output("`#Du greifst nach dem Schwert. In deinen starken HÃ¤nden zerbricht das Schwert. Dabei geht dessen Kraft auf dich Ã¼ber!!`n`n  `&Du erhÃ¤ltst 3 Level in Kampfkunst!");

        $session['user']['warriorsart']+=3;

        $session['user']['warriorsartuses']++;

    }

    addnav("ZurÃ¼ck in den Wald","forest.php");

    $session['user']['specialinc']="";



}else if ($_GET['op']=="amulett"){

    $session['user']['turns']--;

    if (e_rand(0,1)==0){

        output("`#Du greifst nach dem Amulett. Als du das Amulett um deinen Hals hÃ¤ngen willst, fÃ¤hrt ein Blitz aus dem Himmel herab. Das Amulett ist zerstÃ¶rt, doch seine Kraft scheint auf dich Ã¼ber gegangen zu sein!`n`n  `&Du erhÃ¤ltst 10 zusÃ¤tzliche Anwendungen der Spirituellen KrÃ¤fte.`n`n`#Aber du bist auch etwas traurig, denn diese Kraft wird morgen wieder verschwunden sein.");

        $session['user']['priestsartuses']+=10;

    }else{

        output("`#Du greifst nach dem Amulett. Als du das Amulett um deinen Hals hÃ¤ngen willst, fÃ¤hrt ein Blitz aus dem Himmel herab. Das Amulett ist zerstÃ¶rt, doch seine Kraft scheint auf dich Ã¼ber gegangen zu sein!`n`n  `&Du erhÃ¤ltst 3 Level in Spirituelle KrÃ¤ften!");

        $session['user']['priestsart']+=3;

        $session['user']['priestsartuses']++;

    }

    addnav("ZurÃ¼ck in den Wald","forest.php");

    $session['user']['specialinc']="";



}else if ($_GET['op']=="blume"){

    $session['user']['turns']--;

    if (e_rand(0,1)==0){

        output("`#Du greifst nach der Blume. Sie verwelkt und vertrocknet augenblicklich. Ihre Kraft geht auf dich Ã¼ber.`n`n  `&Du erhÃ¤ltst 10 zusÃ¤tzliche Anwendungen der Naturkraft.`n`n`#Aber du bist auch etwas traurig, denn diese Kraft wird morgen wieder verschwunden sein.");

        $session['user']['rangersartuses']+=10;

    }else{

        output("`#Du greifst nach der Blume. Sie verwelkt und vertrocknet augenblicklich. Ihre Kraft geht auf dich Ã¼ber!`n`n  `&Du erhÃ¤ltst 3 Level in Dunklen KÃ¼nsten!");

        $session['user']['rangersart']+=3;

        $session['user']['rangersartuses']++;

    }

    addnav("ZurÃ¼ck in den Wald","forest.php");

    $session['user']['specialinc']="";



}else if ($_GET['op']=="abacus"){

    $session['user']['turns']--;

    if (e_rand(0,1)==0){

        $gold=e_rand($session['user']['level']*30,$session['user']['level']*90);

        $gems=e_rand(1,4);

        output("`#Du nimmst das Rechenbrett von seinem Platz.  Das Rechenbrett verwandelt sich in einen Beutel voller Gold und Edelsteine!`n`n Du bekommst $gold GoldstÃ¼cke und $gems Edelsteine!");

        $session['user']['gold']+=$gold;

        $session['user']['gems']+=$gems;

    }else{

        $gold=$session['user']['gold']+($session['user']['level']*20);

        output("`@`#Du nimmst das Rechenbrett von seinem Platz.  Das Rechenbrett verwandelt sich in einen Beutel voller Gold!`n`n Du bekommst $gold GoldstÃ¼cke!");

        $session['user']['gold']+=$gold;

    }

    addnav("ZurÃ¼ck in den Wald","forest.php");

    $session['user']['specialinc']="";



}else if ($_GET['op']=="book"){

    $session['user']['turns']--;

    if (e_rand(0,1)==0){

        $exp=$session['user']['experience']*0.15;

        output("`#Du nimmst das Buch und beginnst darin zu lesen. Das Wissen in diesem Buch hilft dir viel weiter und du legst es an seinen Platz zurÃ¼ck, damit ein anderer auch noch davon profitieren kann.`n`nDu bekommst $exp Erfahrungspunkte!");

        $session['user']['experience']+=$exp;

    }else{

        $ffights=e_rand(1,5);

        output("`@`#Du nimmst das Buch und beginnst darin zu lesen.  Das Buch enthÃ¤lt ein Geheimnis, wie du deine heutigen StreifzÃ¼ge durch den Wald profitabler gestalten kannst.  Du legst das Buch an seinen Platz zurÃ¼ck, damit ein anderer auch noch davon profitieren kann.`n`nDu bekommst $ffights zusÃ¤tzliche WaldkÃ¤mpfe!");

        $session['user']['turns']+=$ffights;

    }

    addnav("ZurÃ¼ck in den Wald","forest.php");

    $session['user']['specialinc']="";



}else if ($_GET['op']=="bolt"){

    $session['user']['turns']--;

    $bchance=e_rand(0,7);

    if ($bchance==0){

            output("`#Du greifst nach dem Kristallblitz.  Der Blitz verschwindet aus deinen HÃ¤nden und erscheint wieder auf dem Altar. Nach einigen Versuchen, den Blitz zu bekommen, hast du keine Lust mehr, noch mehr Zeit damit zu vergeuden. Du fÃ¼rchtest auch, die GÃ¶tter dadurch herauszufordern.");

            addnav("ZurÃ¼ck in den Wald","forest.php");

        }elseif ($bchance==1){

            output("`#Du greifst nach dem Kristallblitz. Als du den Blitz gerade berÃ¼hrst, wirst du rÃ¼ckwÃ¤rts auf den Boden geschleudert. Du kommst schnell wieder auf die Beine und fÃ¼hlst dich sehr mÃ¤chtig!`n`nDu bekommst 10 Anwendungen in allen Fertigkeiten! Leider spÃ¼rst du, daÃŸ diese Macht nicht einmal bis zum nÃ¤chsten Morgen halten wird.");

            $session['user']['thieveryuses']+=9;

            $session['user']['darkartuses']+=9;

            $session['user']['magicuses']+=9;

            $session['user']['warriorsartuses']+=9;

            $session['user']['priestsartuses']+=9;

            $session['user']['rangersartuses']+=9;

            addnav("ZurÃ¼ck in den Wald","forest.php");

        }elseif($bchance==2){

            output("`#Du greifst nach dem Kristallblitz. Als du den Blitz gerade berÃ¼hrst, wirst du rÃ¼ckwÃ¤rts auf den Boden geschleudert. Du kommst schnell wieder auf die Beine und fÃ¼hlst dich sehr mÃ¤chtig!`n`nDu steigst in jeder Fertigkeit 3 Level auf!");

            $session['user']['thievery']+=3;

            $session['user']['darkarts']+=3;

            $session['user']['magic']+=3;

            $session['user']['thieveryuses']++;

            $session['user']['darkartuses']++;

            $session['user']['magicuses']++;

            $session['user']['warriorsart']+=3;

            $session['user']['priestsart']+=3;

            $session['user']['rangersart']+=3;

            $session['user']['warriorsartuses']++;

            $session['user']['priestsartuses']++;

            $session['user']['rangersartuses']++;

            addnav("ZurÃ¼ck in den Wald","forest.php");

        }elseif($bchance==3){

            output("`#Du greifst nach dem Kristallblitz. Als du den Blitz gerade berÃ¼hrst, wirst du rÃ¼ckwÃ¤rts auf den Boden geschleudert. Du kommst schnell wieder auf die Beine und fÃ¼hlst dich sehr mÃ¤chtig!`n`nDu bekommst 5 zusÃ¤tzliche Lebenspunkte!");

            $session['user']['maxhitpoints']+=5;

            $session['user']['hitpoints']+=5;

            addnav("ZurÃ¼ck in den Wald","forest.php");

        }elseif($bchance==4){

            output("`#Du greifst nach dem Kristallblitz. Als du den Blitz gerade berÃ¼hrst, wirst du rÃ¼ckwÃ¤rts auf den Boden geschleudert. Du kommst schnell wieder auf die Beine und fÃ¼hlst dich sehr mÃ¤chtig!`n`nDu bekommst 2 Angriffspunkte und 2 Verteidigungspunkte dazu!");

            $session['user']['attack']+=2;

            $session['user']['defence']+=2;

            addnav("ZurÃ¼ck in den Wald","forest.php");

        }elseif($bchance==5){

            $exp=round($session['user']['experience']*0.2);

            output("`#Du greifst nach dem Kristallblitz. Als du den Blitz gerade berÃ¼hrst, wirst du rÃ¼ckwÃ¤rts auf den Boden geschleudert. Du kommst schnell wieder auf die Beine und fÃ¼hlst dich sehr mÃ¤chtig!`n`nDu bekommst $exp Erfahrungspunkte!");

            $session['user']['experience']+=$exp;

            addnav("ZurÃ¼ck in den Wald","forest.php");

        }elseif($bchance==6){

            $exp=round($session['user']['experience']*.2);

            output("`#Deine Hand nÃ¤hert sich dem Kristallblitz, als der Himmel plÃ¶tzlich vor Wolken Ã¼berkocht. Du fÃ¼rchtest, die GÃ¶tter verÃ¤rgert zu haben und beginnst zu rennen. Doch noch bevor du die Lichtung verlassen kannst, wirst du von einem Blitz getroffen.`n`nDu fÃ¼hlst dich dÃ¼mmer!  Du verlierst $exp Erfahrungspunkte!");

            $session['user']['experience']-=$exp;

            addnav("ZurÃ¼ck in den Wald","forest.php");

        }else{

            output("`#Deine Hand nÃ¤hert sich dem Kristallblitz, als der Himmel plÃ¶tzlich vor Wolken Ã¼berkocht. Du fÃ¼rchtest, die GÃ¶tter verÃ¤rgert zu haben und beginnst zu rennen. Doch noch bevor du die Lichtung verlassen kannst, wirst du von einem Blitz getroffen.`n`nDu bist tot!");

            output("Du verlierst 5% deiner Erfahrungspunkte und all dein Gold!`n`n");

            output("Du kannst morgen wieder spielen.");

            $session['user']['alive']=0;

            $session['user']['hitpoints']=0;

            $session['user']['gold']=0;

            $session['user']['experience']=round($session['user']['experience']*0.95);

            addnav("TÃ¤gliche News","news.php");

            addnews($session['user']['name']." wurde von den GÃ¶ttern niedergeschmettert, da ".($session['user']['sex']?"sie":"er")." von Gier zerfressen war!");

        }

    $session['user']['specialinc']="";



}else if ($_GET['op']=="forgetit"){

    output("`@Du beschlieÃŸt, das Schicksal lieber nicht herauszufordern und dadurch womÃ¶glich die GÃ¶tter zu verÃ¤rgern. Du lÃ¤ÃŸt den Altar in Ruhe.");

    output("Als du die Lichtung gerade verlassen willst, stolperst du Ã¼ber ein Beutelchen mit einem Edelstein! Die GÃ¶tter mÃ¼ssen dir wohlgesonnen sein!");

    $session['user']['gems']+=1;

    $session['user']['specialinc']="";



}else{

      output("`2Du stolperst Ã¼ber eine Lichtung und bemerkst einen Altar mit 8 Seiten vor dir. Auf jeder Seite liegt ein anderer Gegenstand. Du siehst `^einen Dolch, `\$einen SchÃ¤del,`% einen juwelenbesetzten Stab, `qein Schwert, `#ein Amulett, `@eine wunderschÃ¶ne Blume, `6ein Rechenbrett `7und ein schlicht aussehendes Buch. `2In der Mitte Ã¼ber dem Altar befindet sich ein `&Kristallblitz.`n`n");

    output("  `2Du weiÃŸt, daÃŸ es dich Zeit fÃ¼r einen ganzen Waldkampf kosten wird, einen der GegenstÃ¤nde nÃ¤her zu untersuchen.`n`n`n");

    addnav("Nimm den Dolch","forest.php?op=dagger");

    addnav("Nimm den SchÃ¤del","forest.php?op=skull");

    addnav("Nimm den Stab","forest.php?op=wand");

    addnav("Nimm das Schwert","forest.php?op=schwert");

    addnav("Nimm das Amulett","forest.php?op=amulett");

    addnav("Nimm die Blume","forest.php?op=blume");

    addnav("Nimm das Rechenbrett","forest.php?op=abacus");

    addnav("Nimm das Buch","forest.php?op=book");

    addnav("Nimm den Kristallblitz","forest.php?op=bolt");

    addnav("Verlasse den Altar unberÃ¼hrt","forest.php?op=forgetit");

    $session['user']['specialinc']="alter.php";

}

?>

