
<?php
/* *******************
by Fly
on 09/18/2004

little modyfications by Hadriel
******************* */
if (!isset($session)) exit();
if ($_GET['op']=="")
{
    output("`3 Während deiner Suche siehst du einen alten `q ledernen Stiefel `3 unter einer Wurzel.");
    output("`nWillst du ihn untersuchen?`n");

    addnav("Untersuchen","forest.php?op=try");
    addnav("Weitergehen","forest.php?op=back");
    $session['user']['specialinc']="stiefel.php";
}
else if  ($_GET['op']=="back")
{
    output("`3 Du gehst zurück in den Wald");
//    addnav("Zurück in den Wald","forest.php");
    $session['user']['specialinc']="";
}
else  if ($_GET['op']=="try")
{
    switch (e_rand(1,5))
    {
        case 1:
        case 2:
        case 3:
        output("`3 Im Stiefel befindet sich eine alte stinkende Socke.`n");
        output("Der Gestank treibt dir Tränen in die Augen. Trotzdem gibst du die Hoffnung nicht auf, noch was zu finden`n`n");
        $session['bufflist']['augen'] = array("name"=>"`4tränende Augen",
                "rounds"=>20,
                "wearoff"=>"Du kannst wieder klar sehen!",
                "defmod"=>0.96,
                "atkmod"=>0.92,
                "roundmsg"=>"Deine tränenden Augen behindern dich",
                "activate"=>"defense");
        break;
        case 4:
        case 5:
        output("`3 Du greifst in den Stiefel`n`n");
        break;
    }
    switch (e_rand(1,7))
    {
        case 1:
        case 2:
        case 3:
            $win = e_rand(1,2)*$session['user']['level']*10;
            output("`3und Du findest `^$win Gold!`3.");
            $session['user']['gold']+= $win;
//            addnav("Zurück in den Wald","forest.php");
            $session['user']['specialinc']="";
            $gold = e_rand(1,10)*5;
            $gr = e_rand(15,50);
            $text = "`3der Größe $gr";
            output("`3`n`n Du nimmst den Stiefel mit und gehst zurück in den Wald.");
            
            $item['tpl_name'] = '`qAlter Stiefel';
            $item['tpl_gold'] = $gold;
            $item['tpl_description'] = $text;
            
            item_add($session['user']['acctid'],'beutdummy',$item);
            

        break;
        case 4:
        case 5:
            output("`3und du findest `^einen Edelstein!`3.");
            $session['user']['gems']++;
//            addnav("Zurück in den Wald","forest.php");
            $session['user']['specialinc']="";
            $gold = e_rand(1,10)*5;
            $gr = e_rand(15,50);
            $text = "`3der Größe $gr";
            output("`3`n`n Du nimmst den Stiefel mit und gehst zurück in den Wald.");
            $item['tpl_name'] = '`qAlter Stiefel';
            $item['tpl_gold'] = $gold;
            $item['tpl_description'] = $text;
            
            item_add($session['user']['acctid'],'beutdummy',$item);
        break;
        case 6:
            output("`3und du findest nix!`3.");
//            addnav("Zurück in den Wald","forest.php");
            $session['user']['specialinc']="";
            $gold = e_rand(1,10)*5;
            $gr = e_rand(15,50);
            $text = "`3der Größe $gr";
            $item['tpl_name'] = '`qAlter Stiefel';
            $item['tpl_gold'] = $gold;
            $item['tpl_description'] = $text;
            
            item_add($session['user']['acctid'],'beutdummy',$item);
        break;
        case 7:
            output("`3Du findest ein Stück Gold! Als du die Reinheit mit einem Biss in das Stück feststellen willst, vergiftet dich ein Pfeil eines Räubers! `n Du bist tot!");
            $session['user']['alive']=false;
            $session['user']['hitpoints']=0;
            $session['user']['gold']=0;
            addnews($session['user']['name']."`0 wurde mit einem Pfeil im Rücken aufgefunden.");
            addnav("Tägliche News","news.php");
        break;

    }
}
?>

