<?php
// Der Zopf
// Idee by Zinsis (was würde ich nur ohne dich machen?), umgesetzt von LonelyUnicorn
// Namen geändert und überarbeitet von Tidus für www.kokoto.de
if (!isset($session)) exit();

if ($_GET['op']=='back'){   // zurück in den Wald 
    output('`nZöpfe im Wald? Ne ne, das gibt es nicht. Du ignorierst ihn und gehst an ihm vorbei.`0'); 
    $session['user']['specialinc']='';
}else if ($_GET['op']=='zopf'){

    $rand = mt_rand(1,8);
    output('`nDu greifst den Zopf und ziehst mit einem kräftigen Ruck daran.`n`n`0');
    switch ($rand) { 
        case 1: 
        $gem = mt_rand(2,4);
        output("`nIm dichten Laub des Baumes erscheint ein zartes Gesicht. Eine leise Stimme flüstert: `R\"Nalia mein Name. Seit Tagen sitze ich schon in diesem Baum fest. Dein Ziehen hat meinen Fuß aus den Ästen befreit.\" `0Sie lächelt dich an.`n`n Als Dankeschön gibt sie dir $gem `^ Edelsteine!`0");
        $session['user']['gems']+=$gem;
		$session['user']['specialinc']='';
        break;
        case 2:
        case 3:
        $gold = mt_rand($session['user']['level']50,$session['user']['level']100);
        output("`nDurch das dichte Laub des Baumes erscheint ein zartes Gesicht. Eine leise Stimme flüstert: `R\"Nalia mein Name. Seit Tagen sitze ich schon in diesem Baum fest. Dein Ziehen hat meinen Fuß aus den Ästen befreit.\" `0Sie lächelt dich an.`n`n Als Dankeschön gibt sie dir `^$gold Gold!`0");
        $session['user']['gold']+=$gold;
		$session['user']['specialinc']='';
        break;
        case 4:
        case 5:
        output('Du ziehst und ziehst, doch außer ein paar Blättern rührt sich nichts. Du setzt deinen Weg fort.');
		$session['user']['specialinc']='';
        break;
        case 6:
        case 7:
        output('Du ziehst und ziehst, doch nur ein kleiner Ast löst sich. Er fällt dir direkt ins Auge.`n
        `QDu verlierst Lebenspunkte!`0'); 
		$session['user']['specialinc']='';
        $session['user']['hitpoints']= round($session['user']['hitpoints']0.75);
        break;
        case 8:
        output('Vom Baum fällt ein Bergtroll, der sich verwirrt umschaut. Den Zopf noch in der Hand haltend, erkennst 
        du, dass es sein Bart ist, an dem du gezogen hast.`n`n Als der Bergtroll dich mit seinem Bart in der Hand erblickt, blitzen seine Augen auf, und er stürzt sich auf dich.');    
        $badguy = array( 
                "creaturename"=>"`\$Bergtroll`0", 
                "creaturelevel"=>$session['user']['level']1, 
                "creatureweapon"=>"Steinkamm", 
                "creatureattack"=>$session['user']['attack']2, 
                "creaturedefense"=>$session['user']['defence']2, 
                "creaturehealth"=>round($session['user']['maxhitpoints']1.25,0), 
                "diddamage"=>0); 
        $session['user']['badguy']=createstring($badguy); 
        $session['user']['specialinc']='zopf.php'; 
        $battle=true; 
        break;
  }
}else if ($_GET['op']=='run'){   // Flucht
    if (e_rand()3 == 0){
        output ('`c`b`&Du konntest dem Bergtroll entkommen!`0`b`c`n');
        $_GET['op']='';
    }else{
        output("`c`b`\$Du konntest dem Bergtroll nicht entkommen!`0`b`c");
        $battle=true;
        $session['user']['specialinc']='';
    }
}else if ($_GET['op']=='fight'){   // Kampf
    $battle=true;
    $session['user']['specialinc']='zopf.php';
}else{
    output('`^Als du seelenruhig durch den Wald spazierst, entdeckst du einen langen Zopf,
    der aus einer mächtigen Laubkrone eines Baumes herunterhängt. Du kannst nicht sehen,
    was an seinem Ende ist und müsstest schon daran ziehen, um es herauszufinden. `0');
    addnav('Am Zopf ziehen','forest.php?op=zopf');
    addnav('einfach weitergehen','forest.php?op=back');
    $session['user']['specialinc'] = 'zopf.php';
}
if ($battle) {
    include("battle.php");
        if ($victory){
            $badguy=array();
            $session['user']['badguy']='';
            output('`n`7Du konntest nach einem harten Kampf den Bergtroll besiegen!');
            //Navigation
            addnav('Zurück in den Wald','forest.php');
            if (mt_rand(1,2)==1) {
                $gem_gain = mt_rand(2,3);
                $gold_gain = mt_rand($session['user']['level']10,$session['user']['level']20);
                output(" Als Du Dich noch einmal umdrehst findest Du $gem_gain Edelsteine 
                und $gold_gain Goldstücke.`n`n");
            }
            $exp = round($session['user']['experience']0.08);
            output("Durch diesen Kampf steigt Deine Erfahrung um $exp Punkte.`n`n");
            $session['user']['experience']+=$exp;
            $session['user']['gold']+=$gold_gain;
            $session['user']['gems']+=$gem_gain;
            $session['user']['specialinc']='';
        } elseif ($defeat){
            $badguy=array();
            $session['user']['badguy']='';
            output('`n`7Der Bergtroll war stärker!`n`nDu verlierst 6% Deiner Erfahrung.`0 `nTrolle können nichts mit Reichtum anfangen. Du kannst morgen wieder kämpfen!`0');
            addnav("Tägliche News","news.php");
            addnews("`Q ".$session['user']['name']." `Qsollte nicht an Zöpfen ziehen!");
            $session['user']['alive']=false;
            $session['user']['hitpoints']=0;
            $session['user']['experience']=round($session['user']['experience'].94,0);
            $session['user']['specialinc']='';
        } else {
            fightnav(true,true);
        }
}
?>
