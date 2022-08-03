<?php
///////////////////////////////////////////////////////////////////////////////
// Specialname: Der kleine Junge
// code: Opal
// Idee & Text : Awon eine Userin von www.Aladrion.de
/////////////////////////////////////////////////////////////////////////////
if (!isset($session)) exit();
$filename = basename(__FILE__);
$fn = "forest.php";
$spi = ($session['user']['specialinc']=$filename);

switch ($_GET['op']){
case "kind":
$session['user']['specialinc']="";
                output("`QDein Herz erweicht bei dem jämmerlichen Anblick, den der Junge dir bietet und so näherst du dich vorsichtig dem Kind.");
                switch(e_rand(1,3)){
                case '1':
            output("Ein hoffnungsvolles Lächeln breitet sich auf dem Gesicht des kleinen Jungen aus, als du zu ihm trittst und ihn ohne zu zögern zurück ins Dorf bringst, wo sich jemand gleich dem Fuß des Kindes annimmt. Als Lohn bekommst du von seinem Großvater 3 Edelsteine geschenkt. ");
            $session['user']['gems']+=3;
            addnews($session['user']['name']." bekommt 3 Edelsteine geschenkt weil er eine Kind im Wald rettet.");
            break;
            case '2':
            output("Ein teuflisches Grinsen verzerrt das Antlitz des Kindes, dessen Augen plötzlich rot zu leuchten beginnen. Erschrocken blickst du in das einst so hilflose Gesicht des kleinen Jungen, aus welchem jetzt die pure Boshaftigkeit spricht. Du willst zurückweichen und fliehen, doch zu spät!
Die zuvor flehend ausgestreckte Hand schließt sich langsam und dir wird die Luft abgeschnürt. Bereits als dein Körper zu Boden sinkt beginnt dein Leben zu schwinden. Doch du hast Glück im Unglück gehabt: du verlierst zwar fast alle deiner Lebenspunkte, doch immerhin überlebst du das Zusammentreffen mit dem kleinen Jungen. ");

             if ($session['user']['hitpoints']>3){
            $session['user']['hitpoints']=3;
            addnews($session['user']['name']." verliert fast sein Leben beim versuch ein Kind im Wald zu retten.");
        }else{
            $session['user']['hitpoints']=0;
            $session['user']['alive']=false;
                        addnav("Tägliche News","news.php");
            addnews($session['user']['name']." verliert sein Leben beim versuch ein Kind im Wald zu retten.");
        }
            break;
case '3':
            output("Ein hoffnungsvolles Lächeln breitet sich auf dem Gesicht des kleinen Jungen aus, als du zu ihm trittst und ihn ohne zu zögern zurück ins Dorf bringst, wo sich jemand gleich dem Fuß des Kindes annimmt. Als Lohn bekommst du von seinem Großvater 500 Goldstücke geschenkt. ");
            $session['user']['gold']+=500;
            addnews($session['user']['name']." bekommt 500 Goldstücke geschenkt weil er eine Kind im Wald rettet.");
            break;
               }
break;

case "wald":
        $session['user']['specialinc']="";
        output("`3So schnell es deine Füße vermögen tragen sie dich fort und schon bald hast du den Hilfeschrei vergessen.");
        addnews($session['user']['name']." flieht vor einem Schrei im Wald.");
        if ($session['user']['turns']>1){
            $session['user']['turns']--;
        }else{
            $session['user']['turns']=0;
        }
    break;
case "fliehen":
        $session['user']['specialinc']="";
        output("`4Rasch drehst du dich um und versuchst das völlig verzweifelte Gesicht des Kindes zu vergessen. Bald schon verschwendest du keinen Gedanken mehr an den kleinen Jungen und stolperst. Du verlierst all dein Gold!");
        addnews($session['user']['name']." flieht vor einem Kind im Wald.");
            $session['user']['gold']=0;
    break;

  case "story":
                $spi;
                output("`QDu trittst auf die Lichtung und erblickst einen kleinen Jungen, der dich mit großen, vor Verzweiflung geweiteten Augen anstarrt und mit einer erbärmlichen Geste die Hand hebt und nach dir ausstreckt. Sein Fuß scheint verstaucht.");
                addnav("zu dem Jungen gehen",$fn."?op=kind");
                addnav("die letzte Chance nutzen und fliehen",$fn."?op=fliehen");
                  break;

    break;
    default:

    default:
        $spi;
        output("`&Ein Schrei,`n `7der einem schier die Tränen in die Augen treiben will, erschallt im Wald. Du stehst vor der Wahl ob du dem Ruf folgst oder du schleunigst verschwindest.`n`n");
        addnav("Dem Schrei folgen",$fn."?op=story");
        addnav("Wieder im Wald verschwinden",$fn."?op=wald");
    break;
}
?>