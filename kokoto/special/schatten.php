<?php

//Schatten Special
//Idee von Sinthoras
//umgesetzt von Tweety
//16.06.2005
//Verschönert, angepasst by Tidus (www.Kokoto.de)
if (!isset($session)) exit();

if ($_GET['op']=='ja'){
    switch (mt_rand(1,3)){
            case '1':
            output('Der Schatten entpuppt sich als junger Hundewelpe und kuschelt sich an dich. Och wie süß!`n`^Du bekommst 3 Charmepunkte`n`$Du verlierst einen Waldkampf.');
            $session['user']['charm']+=3;
            $session['user']['turns']--;
            $session['user']['specialinc'] = '';
            addnav('weiter','forest.php');
            break;
            case '2':
            output('Der Schatten war wohl nur reine Einbildung ... Was für eine Zeitverschwendung!`n`$Du verlierst 2 Waldkämpfe!');
            $session['user']['turns']-=2;
            $session['user']['specialinc'] = '';
            addnav('weiter','forest.php');
            break;
            case '3':
            output('Der Schatten scheint immer schneller zu werden und du überlegst ob du ihm weiterfolgen sollst.');
            addnav('weiter Folgen?');
            addnav('Ja','forest.php?op=folgen');
            addnav('Nein','forest.php?op=lassen');
            $session['user']['specialinc'] = 'schatten.php';
            break;
    }
}else if ($_GET['op']=='folgen'){
    switch (mt_rand(1,3)){
            case '1':
            output('Nach Stunden fängst du das kleine Einhornbaby und verkaufst es an Tyrion der dir aber verspricht sich gut um es zu kümmern. `n`1 Du bekommst 2000 Gold und 3 Edelsteine!');
            $session['user']['gold']+=2000;
            $session['user']['gems']+=3;
            addnav('weiter','forest.php');
            $session['user']['specialinc'] = '';
            break;
            case '2':
            output('Auch nach Stunden hast du den Schatten nicht einholen können und fällst müde um. Du hast deine Lektion gelernt.. `n`$Du verlierst alle deine Runden!`n`^Bekommst aber 10% Erfahrung!');
            $session['user']['experience']*=1.10;
            $session['user']['turns']=0;
            addnav('weiter','forest.php');
            $session['user']['specialinc'] = '';
            break;
            case'3':
            output('Der Schatten ist schnell, aber du bist schneller. Als du den Sensenmann eingeholt hast zuckst du kurz als seine Sense dich halbiert und ins Reich der Toten schickt. `n`$Du bist gestorben und hast alles gold verloren, wenigstens hast du etwas daraus gelernt und bekommst etwas Erfahrung.');
            addnews($session['user']['name'].' wurde im Wald durch eine Sense halbiert.');
            addnav('Tägliche news','news.php');
            $session['user']['gold']=0; 
            $session['user']['hitpoints']=0;
            $session['user']['alive']=false;
            $session['user']['specialinc'] = '';
            $session['user']['experience']*=1.05;
            break;
    }
}else if ($_GET['op']=='lassen'){
$session['user']['specialinc'] = '';
        output('Du suchst den Weg zurück und verläufst dich.`n`$Als du endlich wieder den Weg erreichst hast du 2 Waldkämpfe vertrödelt!');
                addnav('Wald','forest.php');
                $session['user']['turns']-=2;
                
            }else if ($_GET['op']=='nein'){
    output('Durch dein Nachdenken, was der Schatten wohl gewesen sein könnte stolperst du und verlierst 5% deines Goldes.');
    $session['user']['gold']*=0.95;
    addnav('In den Wald','forest.php');
}else{
    output('Im Wald siehst du vor dir einen Schatten ins Dickicht huschen!
    Du könntest ihn verfolgen, aber du weißt, dass du dadurch Zeit verlieren wirst. `n`n`q`bFolgen?`0`b');
    
    addnav('Folgen?');
    addnav('Ja','forest.php?op=ja');
    addnav('Nein','forest.php?op=nein');
    $session['user']['specialinc'] = 'schatten.php';
}
page_footer();
?>
