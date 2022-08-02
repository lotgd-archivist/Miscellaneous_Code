<?php

//| Original by schnappt-sephi.de
//| Modifycations by Hadriel@ hadrielnet.ch
//| small Modifikations (fight a Basilisk)/Bugfixes and Textchanges by Tidus www.kokoto.de
if (!isset($session)) exit();

if ($_GET['op']=='follow'){
        output('`n`2Du entschließt dich, den Ranken zu folgen. Ein langer Weg beginnt für dich. Immer tiefer gehst du in den Tunnel hinein. Der Weg ist weit, du fühlst dich schwächer, du spürst, dass du bereits einen Waldkampf verloren hast. Trotzdem gehst du weiter.');
        $session['user']['turns']--;
        switch(mt_rand(1,7)){
       case '1':
        output('`n`nDu erreichst eine harte Felswand, gerade als du wieder zurück gehen willst, bemerkst du etwas an der Wand hängen... ein Edelsein. Du steckst dir den Edelsten in die Tasche und gehst wieder.');
        $session['user']['gems']++;
        $session['user']['specialinc']='';
        addnav('Zurück in den Wald','forest.php');
         break;
       case '2':
        output('`n`nWie du so durch die Gegend schleichst, findest du rein gar nichts, du beschließt wieder zurück zu gehen. Auf deinem Rückweg findest du ein Säckchen mit ein wenig Gold.');
        $session['user']['gold']+=200;
       $session['user']['specialinc']='';
         break;
       case '3':
        output('`n`nDu triffst auf die Gargantula-Bahn, du steigst in die Gondel und fährst mit ihr zurück.  Das hat Spaß gemacht! Du erhälst 3 zusätzliche Waldkämpfe!');
        $session['user']['turns']+=3;
       $session['user']['specialinc']='';
         break;
       case '4':
        output('`n`nWie du so weiter gehst, vernimmst du merkwürdige Geräusche. Was kann das sein? Vor dir baut sich ein riesiger, weißer Königsbasilisk auf. Mit einem Schlag seines Schwanzes erwischt er dich. Du bist tot!');
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        addnews($session['user']['name'].'`& ist von einem weißen Königsbasilisken getötet worden.');
        addnav('Tägliche News','news.php');
       $session['user']['specialinc']='';
          break;
      case '5':
        output('`n`n Wie du so weiter gehst, vernimmst du merkwürdige Geräusche. Was kann das sein? `n`n Vor dir baut sich ein riesiger, grüner Basilisk auf. Er trifft dich mit einem Schlag seines mächtigen Schwanzes! Du verlierst einige Lebenspunkte');
        $session['user']['hitpoints']=1;
      $session['user']['specialinc']='';
break;
      case '6':
      case '7':
        output('`n`n`&Plötzlich stehst du vor einem großen weißen Königsbasilisken der dich zum Kampf zwingt!');
      $badguy = array(
                "creaturename"=>"`&Königsbasilisk`0",
                "creaturelevel"=>$session['user']['level']3,
                "creatureweapon"=>"`&harter Schwanzhieb`0",
                "creatureattack"=>$session['user']['attack']5,
                "creaturedefense"=>$session['user']['defence']3,
                "creaturehealth"=>round($session['user']['maxhitpoints']1.25,0),
                "diddamage"=>0);
        $session['user']['badguy']=createstring($badguy);
        $session['user']['specialinc']='gargantula.php';
        $battle=true;
      break;
    }
    }else if ($_GET['op']=='run'){   // Flucht
    if (e_rand()3 == 0){
    $session['user']['specialinc']='';
        output ('`c`b`&Du hast es geschafft vor dem Basilisk abzuhauen!!`0`b`c`n');
      addnews($session['user']['name'].'`& ist vor einem Basilisken davongelaufen!');
        $_GET['op']='';
    }else{
        output('`c`b`&Der Basiliks lässt dich nicht fliehen!`0`b`c');
        $battle=true;
    }
}else if ($_GET['op']=='fight'){   // Kampf
    $battle=true;
    $session['user']['specialinc']='gargantula.php';
}else if ($_GET['op']=='return'){
         output('So ein Tunnel scheint gefährlich zu sein. Du entscheidest dich, lieber wieder zu gehen.');
         addnav('Zurück in den Wald','forest.php');
         $session['user']['specialinc']='';
    }else{
        page_header("Gargantula Tunnel");
        output('`c`b`VGargantula Tunnel`b`c`n`n `n`2Du erreichst eine kleine Höhle in einem Felsen und schleichst dich hinein. Als du dich umschaust, erblickst du den Gargantula-Tunnel. Du hast schon viel von der Gargantula gehört, die es ermöglicht, mit Hilfe eines Korbes an ihrem Rücken weite Strecken an Pflanzenranken zurückzulegen.`n Was möchtest du als nächstes tun?');
        addnav('Den Ranken folgen','forest.php?op=follow');
        addnav('Zurück in den Wald','forest.php?op=return');
        $session['user']['specialinc']='gargantula.php';
}
if ($battle) {
    include("battle.php");
    $session['user']['specialinc']='gargantula.php';
        if ($victory){
            $badguy=array();
            $session['user']['badguy']='';
            output('`n`&Du hast es geschafft! JA DU, du hast einen weißen Königsbasilisken getöet!!');
            addnews($session['user']['name'].'`& hat einen weißen Königsbasilisken besiegt!!!');
            //Navigation
            addnav('Zurück in den Wald','forest.php');
            if (rand(1,2)==1) {
                $gem_gain = rand(2,3);
                $gold_gain = rand($session['user']['level']10,$session['user']['level']20);
                output(" Als Du Dich noch genau umsiehst, erkennst du im Dunkeln gerade noch so $gem_gain Edelsteine und $gold_gain Goldstücke.`n`n");
            $session['user']['gems']+=$gem_gain;
            $session['user']['gold']+=$gold_gain;
            }
            $exp = round($session['user']['experience']0.08);
            output("`&Durch diesen Kampf steigt Deine Erfahrung um $exp Punkte.`n`n");
            $session['user']['experience']+=$exp;
            $session['user']['specialinc']='';
        } elseif ($defeat){
            $badguy=array();
            $session['user']['badguy']='';
            output('`n`&Du konntest gegen den übermächtigen weißen Königsbasilisken einfach nichts ausrichten..`n`nDu verlierst 6% Deiner Erfahrung.`0');
            addnav('Tägliche News','news.php');
            addnews($session['user']['name'].'`& wurde von einem mächtigen Königsbasilisken plattgemacht...');
            $session['user']['alive']=false;
            $session['user']['hitpoints']=0;
            $session['user']['experience']=round($session['user']['experience'].94,0);
            $session['user']['specialinc']='';
        } else {
            fightnav(true,true);
        }
        }
?>