<?php
///////////////////////////////////////////////////////////////////////////////
// Specialname: Der Bienenstock
// code: Opal
// Idee & Text : Melinda eine Userin von www.Aladrion.de
/////////////////////////////////////////////////////////////////////////////
if (!isset($session)) exit();
$filename = basename(__FILE__);
$fn = "forest.php";
$spi = ($session['user']['specialinc']=$filename);

switch ($_GET['op']){
case "hand":
$session['user']['specialinc']="";
                output("`QMutig steckst du deine Hand in den Bienenstock`n");
                switch(e_rand(1,4)){
                case '1':
            output("Du ziehst die Hand wieder heraus und kostest von dem wohlschmeckenden Honig. Er ist so gut, dass du gar nicht mehr aufhören kannst zu essen und schließlich mit starken Bauchschmerzen zusammenbrichst. Du benötigst 2 Waldkämpfe bis es dir wieder besser geht.");
            $session['user']['turns']-=2;
            break;
            case '2':
            output("Du ziehst die Hand wieder heraus und kostest von dem wohlschmeckenden Honig. Es muss ein besonderer Honig sein, denn plötzlich fühlst du dich so voller Energie. Du wirst heute einen Waldkampf mehr bestreiten können.");
 $session['user']['turns']+=1;

            break;
case '3':
            output("Du greifst hinein und spürst zwischen dem flüssigen Honig etwas Hartes. Du umschließt es mit deiner Hand und holst es heraus. Du findest einen Edelstein und fragst dich wie der wohl hierher gekommen sein mag.");
            $session['user']['gems']+=1;
            break;
case '4':
            output("erade als du deine Hand hineingesteckt hast, hörst du plötzlich ein wildes Summen und Brummen ringsumher. Du bist umzingelt von Bienen die nicht gerade erbaut darüber zu sein scheinen, dass du ihren Honig stiehlst. Deine Hand steckt in dem Bienenstock fest und du kannst nicht fliehen. Du bist tot.");
            $session['user']['hitpoints']=0;
            $session['user']['alive']=false;
                        addnav("Tägliche News","news.php");
            addnews($session['user']['name']." starb den Tod der 1000 Stiche.");
            break;
               }
break;
case "stock":
$session['user']['specialinc']="";
                output("`QDu willst deine kostbare Hand nicht riskieren und nimmst daher einen Stock um in dem Bienenstock herumzustochern.");
                switch(e_rand(1,2)){
                case '1':
            output("Du steckst den Stock hinein und leckst dann den leckeren Honig ab. Es ist Honig aus einer Heilpflanze und du erhältst 2 permanente Lebenspunkte dazu.");
            $session['user']['maxhitpoints']+=2;
            break;
            case '2':
            output("Du steckst den Stock hinein und hast dabei wohl deinen eigen Kraft unterschätzt. Der Bienenstock fällt durch den harten Stoß herunter und du hörst plötzlich ein Summen und Brummen um dich herum. Du nimmst schnell die Beine in die Hand und rennst solange bis du an einen See kommst. Da du weißt das Bienen das Wasser verabscheuen, stürzt du dich hinein. Gerade als du auch mit deinem Kopf untertauchen willst, sticht dich eine Biene mitten in die Nase. Eine auf das doppelte angeschwollenen Nase macht dich nicht unbedingt hübscher und so verlierst du 1 Charmepunkt.");
$session['user']['charm']-=1;
            break;
               }
break;
case "wald":
        $session['user']['specialinc']="";
        output("`3Ängstlich blickst du dich um. Wo ein Bienenstock ist, da sind auch Bienen. Prompt hörst du auch schon ein leises Summen. Du nimmst lieber die Beine in die hand und läufst weg.");
        addnews($session['user']['name']." hatte Angst vor einer kleinen Biene.");

    break;

  case "story":
                $spi;
                output("`QDu näherst dich dem Bienenstock vorsichtig. In der Mitte befindet sich ein etwa handgroßes Loch. Es riecht verführerisch süß nach leckerem Honig. Willst du es wagen eine Hand hineinzustecken und davon zu naschen?");
                addnav("mit einer Hand",$fn."?op=hand");
                addnav("mit einem Stock",$fn."?op=stock");
                  break;

    break;
    default:

    default:
        $spi;
        output("`&Du schlenderst den Weg im Wald entlang und erblickst auf einmal einen Bienenstock, der an einem Baum in deiner Nähe hängt. `n`n");
        addnav("Zum Bienenstock",$fn."?op=story");
        addnav("Wieder im Wald verschwinden",$fn."?op=wald");
    break;
}

?>