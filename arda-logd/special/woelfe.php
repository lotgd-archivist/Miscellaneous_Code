<?php
///////////////////////////////////////////////////////////////////////////////
// Specialname: Die Wolfshoele
// code: Opal
// Idee & Text : Awon eine Userin von www.Aladrion.de
/////////////////////////////////////////////////////////////////////////////
if (!isset($session)) exit();
$filename = basename(__FILE__);
$fn = "forest.php";
$spi = ($session['user']['specialinc']=$filename);

switch ($_GET['op']){
case "stehen":
$spi;

                output("`QAus der Höhle in die du noch vor wenigen Momenten treten wolltest und auch aus dem Schatten der Bäume kommen etliche Wölfe. Erschrocken und doch von verzweifeltem Mut ergriffen willst du deine Waffe erheben und dein Leben die Graupelze verteidigen. Doch dann tritt eines der Geschöpfe vor und erhebt seine Stimme: „Senkt Eure Waffe, Menschlein!“");
addnav("Waffe senken",$fn."?op=senken");
addnav("Höhle betreten",$fn."?op=angriff");

break;
case "hoele":
$session['user']['specialinc']="";
switch(e_rand(1,2)){
 case '1':
                output("`QRaschen Schrittest verbirgst du dich im Innern der Höhle und wartest gespannt und mit laut klopfendem Herzen darauf was nun passiert. Wie erwartet erscheinen Wölfe an der Stelle, an welcher du zuvor noch gestanden hast, sie schnuppern und wissen, dass du dich in der Höhle befindest, doch sie greifen nicht an und ziehen sich wieder zurück. Du kommst mit dem Leben noch mal davon und erhältst einen Verteidigungs-Punkt.");
              addnews($session[user][name]." kam gerade so mit seinem Leben davon als er eine Höhle betrat.");
 if ($session['user']['hitpoints']>=3){
            $session['user']['hitpoints']=3;
        }else{
            $session['user']['hitpoints']==0;
        }
            $session['user']['defence']++;

break;
case '2':
                output("`QSo schnell du kannst rennst du in die Höhle und versteckst dich zitternd hinter einem Felsen. Der Panik nahe zuckst du zusammen als du die Wölfe siehst, die sich am Eingang der Höhle versammeln und wie wild knurren. Doch nicht ein einziger Graupelz betritt das Innere der Höhle. Schließlich sind sie verschwunden und erleichtert atmest du auf. Du willst dich erheben, doch da spürst du heißen Atem in deinem Nacken. Ein Schrei will sich aus deiner Kehle lösen, doch nur ein Gurgeln entwich deinen Lippen. Der Bewohner dieser Höhle, ein Höhlenbär, war nicht sehr begeistert davon dich in seinem Zuhause anzutreffen und hat dich getötet. ");
addnews("Man fand die, von einem Höhlenbären zerfleischten, Überreste von ".$session[user][name]." in einer Höhle.");
$session['user']['hitpoints']=0;
$session['user']['alive']=false;
                        addnav("Tägliche News","news.php");
break;
}
break;
case "senken":
        $session['user']['specialinc']="";
        output("`3Nach langem Zögern, das bei den Wölfen bewirkt, dass sie ziemlich unruhig werden, entschließt du dich deine Waffe zu senken und den Worten der Vierbeiner zu lauschen. Die Wölfe bitten dich darum ihrer verletzten Alphawölfin zu helfen, da diese eine schwere Wunde an der Seite hat und die Wölfe allein ihr nicht das Leben retten können, da sich die Wunde entzündete. Du verlierst alle verbliebenen Waldkämpfe, da du um das Leben des Vierbeiners kämpfst, doch als es schließlich geschafft ist gewinnst du stattdessen Charme");
 addnav("Zurück in den Wald","forest.php");
        addnews($session[user][name]." Rettete die verletzte Alphawölfin des Wolfsrudels. „Ich bin Euch wahrhaft zu Dank verpflichtet“, raunt die Wölfin.");
        $session['user']['charm']+=100;
        $session['user']['turns']=0;
    break;


case "angriff":
       $session['user']['specialinc']="";
        output("`3Du greifst die Wölfe an, doch die Tiere fliehen so schnell sie ihre Pfoten nur tragen und du verlierst an Charme.");
        addnews($session[user][name]." verscheucht ein Rudel Wölfe im Wald");
         if ($session['user']['charm']>=100){
            $session['user']['hitpoints']-=99;
        }else{
            $session['user']['hitpoints']==0;
        }
      addnav("Zurück in den Wald","forest.php");
    break;



case "wald":
        $session['user']['specialinc']="";
        output("`3So schnell es deine Füße vermögen tragen sie dich fort und schon bald hast du das durchdringende Heulen vergessen.");
        addnews($session[user][name]." flieht vor einem durchdringendes Heulen im Wald.");
    break;


    break;
    default:

    default:
        $spi;
        output("`&Ein durchdringendes Heulen erklingt nahe bei dir und wird von anderen Stimmen erwidert. Fast panisch schaust du dich um, doch nirgends kannst du die Verursacher dieses schauerlichen Konzertes erblicken. Du erblickst ganz in der Nähe einer Höhle und nun kannst du dich entscheiden, willst du dich in der Höhle vor den möglichen Angreifern verstecken oder dich ihnen mutig entgegen stellen?!`n`n");
        addnav("Stehen bleiben",$fn."?op=stehen");
        addnav("Höhle betreten",$fn."?op=hoele");
        addnav("Wieder im Wald verschwinden",$fn."?op=wald");
    break;
}

?> 