
<?php
///////////////////////////////////////////////////////////////////////////////
// Specialname: Der Regenbogen
// code: Opal
// Idee & Text : Awon eine Userin von www.Aladrion.de
/////////////////////////////////////////////////////////////////////////////
if (!isset($session)) exit();
$filename = basename(__FILE__);
$fn = "forest.php";
$spi = ($session['user']['specialinc']=$filename);

switch ($_GET['op']){
case "truhe":
$session['user']['specialinc']="";
                output("`Q`n`nNatürlich willst du nun den Schatz sehen und haben, der sich dort drin befinden mag und so schlägst du ohne zu zögern mit deiner Waffe das Schloss ab und öffnest die Truhe.");
                switch(e_rand(1,5)){
                case '1':
            output("In der Truhe, zu der dich der Regenbogen führte, befindet sich ein wahrer Schatz. Du findest 7 Edelsteine und 300 Gold! ");
            $session['user']['gold']+=300;
            $session['user']['gems']+=7;
            addnews($session['user']['name']." fand am Ende des Regenbogens einen Schatz");
            break;
                case '2':
            output("In der Truhe, zu der dich der Regenbogen führte, befindet sich ein wahrer Schatz. Du findest 7 Edelsteine und 1300 Gold! ");
            $session['user']['gold']+=1300;
            $session['user']['gems']+=7;
            addnews($session['user']['name']." fand am Ende des Regenbogens einen Schatz");
            break;
                case '3':
            output("In der Truhe, zu der dich der Regenbogen führte, befindet sich ein wahrer Schatz. Du findest 8 Edelsteine und 2300 Gold! ");
            $session['user']['gold']+=2300;
            $session['user']['gems']+=8;
            addnews($session['user']['name']." fand am Ende des Regenbogens einen Schatz");
            break;
            case '4':
            output("Plötzlich springt ein grün gekleideter Kobold mit rotem Bart aus der Truhe und greift dich obszön fluchend mit irischer Butter an. Du verlierst fast alle deine Lebendspunkte.");
$session['user']['hitpoints']=1;
addnews($session['user']['name']." wurde für einen Diebstahl an seinem Schatz von einem Kobold bestraft. „Tja, das geschieht dir jetzt aber Recht!“, spottet der Kobold.");
            break;
            case '5':
            output("Plötzlich springt ein grün gekleideter Kobold mit rotem Bart aus der Truhe und greift dich obszön fluchend mit irischer Butter an. Du verlierst alle Edelsteine die du bei dir hast.");
$session['user']['gems']=0;
addnews($session['user']['name']." wurde für einen Diebstahl an seinem Schatz von einem Kobold bestraft. „Tja, das geschieht dir jetzt aber Recht!“, spottet der Kobold.");
            break;
               }
break;

case "wald":
        $session['user']['specialinc']="";
        output("`3Du schenkst dem Regenbogen nicht viel Aufmerksamkeit da du wegen dem Regen leise vor dich hin fluchst und bald schon hast du den Regenbogen hinter dir gelassen und widmest dich anderen Aufgaben.");
    break;
case "fliehen":
        $session['user']['specialinc']="";
        output("`4Da du nun das Ende des Regenbogens erblickst wird dir doch recht mulmig, da du stets geglaubt hast man könne das Ende niemals erreichen. Durch dein Misstrauen skeptisch geworden drehst du dich um und verschwindest im Wald. Allerdings verlierst du drei Waldkämpfe, da dein Weg hierher lang war!");
        addnews($session['user']['name']." flieht vor einem Regenbogen im Wald");
            if ($session['user']['turns']>=3){
            $session['user']['turns']-3;
        }else{
            $session['user']['turns']==0;
        }
    break;
case "lassen":
        $session['user']['specialinc']="";
        output("`4Nachdem du dir das Schloss angesehen hast schüttelst du deprimiert den Kopf und gehst von dannen. Ein andermal, sagst du dir.Drehst du dich um und verschwindest im Wald. Allerdings verlierst du dfünf Waldkämpfe, da dein Weg hierher lang war!");
        addnews($session['user']['name']." lässt es eine Schatztruhe am Ende des Regenbogens zu öffnen");
            if ($session['user']['turns']>=5){
            $session['user']['turns']-5;
        }else{
            $session['user']['turns']==0;
        }
    break;
case "graben":
                $spi;
                output("`QDu überwindest deine Vorsicht und fängst fast augenblicklich an zu graben. Du verlierst ganze vier Waldkämpfe, da dein Weg bis zum Ende des Regenbogens lang und es noch länger dauerte den Schatz zu finden!`nDu willst schon fast aufgeben als plötzlich ein dumpfes „Klong“ erklingt. Voll von frischem Elan gräbst du weiter und ziehst die schwere ebenholzfarbene Truhe aus dem Loch bei den Wurzeln dieses Baumes. Du springst jubelnd auf und freust dich, dass es dir  geglückt ist den Schatz zu finden.");
                addnav("Schatztruhe öffnen",$fn."?op=truhe");
                addnav("Es Lassen",$fn."?op=lassen");
                  break;
  case "story":
                $spi;
                output("`QDa die Neugier gepaart mit Endeckungsgeist dich packt begibst du dich auf die Suche nach dem Ende des Regenbogens. Nach einer geraumen Zeit erreichst du tatsächlich das sehnlich erwartete Ende des Farbenspiels.");
                addnav("Graben",$fn."?op=graben");
                addnav("zurück in den Wald",$fn."?op=fliehen");
                  break;

    break;
    default:

    default:
        $spi;
        output("`&Ein leichter Nieselregen begleitet deine Schritte durch den Wald und als du deinen Blick gen Himmel richtest bemerkst du wie die Wolken trotz des beständigen Regens aufbrechen und die Sonne sich zaghaft hervorwagt. Als ihre Strahlen sich mit den Regentropfen vereinen entsteht ein Regenbogen.
Da auch du schon mal davon gehört hast, dass am Ende eines Regenbogens ein Schatz vergraben liegt überlegst du, ob du dich auf die Suche danach machen solltest.`n`n");
        addnav("Suchen",$fn."?op=story");
        addnav("zurück in den Wald",$fn."?op=wald");
    break;
}

?>

