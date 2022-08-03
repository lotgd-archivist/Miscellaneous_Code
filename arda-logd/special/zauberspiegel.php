<?php
///////////////////////////////////////////////////////////////////////////////
// Specialname: Der Zauberspiegel
// code: Opal
// Idee & Text : Awon eine Userin von www.Aladrion.de
/////////////////////////////////////////////////////////////////////////////
if (!isset($session)) exit();
$filename = basename(__FILE__);
$fn = "forest.php";
$spi = ($session['user']['specialinc']=$filename);

switch ($_GET['op']){

case "spiegel":
$session['user']['specialinc']="";
                output("`QDu trittst näher an die Spiegel herantreten, und schaust hinein");
                switch(e_rand(1,3)){
                case '1':
            output("und siehst dich selbst. Mit gerunzelter Stirn betrachtest du dich, da du glaubtest, dies sei ein verzauberter Spiegel. Doch nichts weißt darauf hin, dass Magie gewirkt wird. Kurz verschwimmt das Bild vor deinen Augen und als es wieder klar wird kannst siehst du dich weiterhin selbst an, doch du bist deutlich schöner geworden. Du bekommst Charme geschenkt!");
            $session['user']['charm']+=10;
            addnews($session['user']['name']." wurde schöner durch den blick in einen Zauberspiegel");
            break;
            case '2':
            output("Der Spiegel schenkt dir ein Bild von der Zukunft. Nicht irgendeiner Zukunft, deiner Eigenen! Neugierig trittst du näher an die Oberfläche des Spiegels heran und musterst das lebensecht wirkende Geschehen was sich dir nun bietet: Du siehst dich mit deinem offensichtlichen Lebenspartner und in deinen Armen hältst du ein kleines Kind, das dich mit einem strahlenden Lächeln anblickt und mit deinen Haaren spielen will. Von dieser Vorstellung überwältigt taumelst du ein paar Schritte zurück und fühlst dich plötzlich deutlich gesünder. Um diese Zukunft zu erreichen, die sich dir im Spiegelbild geboten hat, wurde dir ein permanenter Lebenspunkt gewährt!");
            $session['user']['hitpoints']+=1;
            $session['user']['maxhitpoints']+=1;
            addnews($session['user']['name']." gewinnt einen permanenten Lebenspunkt hinzu , da er seine Zukunft sah");
            break;
                        case '3':
            output("Du trittst an den letzten der drei Spiegel heran und blinzelst verwundert als du jemanden hinter dir stehen siehst. Schnell drehst du dich um, doch dort ist niemand! Verwirrt schaust du erneut in den Spiegel und erneut ist die Gestalt zu sehen, die am Fuße eines Baumes hinter dir ein Loch gräbt. Erneut schaust du hinter dich und schüttelst verwirrt den Kopf. Als dein Blick ein weiteres Mal auf das Bild im Spiegel fällt kannst du sehen wie die Person eine schwere Truhe in dem Loch verbuddelt. Von Neugier gepackt wendest du dich vom Spiegel ab und gehst zu dem Baum herüber. Du verlierst drei Waldkämpfe bis du die Truhe schließlich ausgebuddelt hast, die sich wider Erwarten dort befindet. Ein wahrer Schatz befindet sich in der schweren Truhe: Du findest 6666 Gold und 6 Edelsteine!");
            $session['user']['gold']+=6666;
            $session['user']['gems']+=6;
            addnews($session['user']['name']." findet in einer Truhe einen großen Schatz.");
            break;
               }
  break;

case "wald":
        $session['user']['specialinc']="";
        output("`3`nDa du wirklich nicht wissen willst welch falscher Zauber dafür verantwortlich ist und du nicht in Schwierigkeiten geraten magst drehst du dich rasch um und verschwindest im Wald.Du verlierst durch Trödeln einen Waldkampf!");
        addnews($session['user']['name']." fürchtete sich vor seinem eigenen Spiegelbild! Was ein Feigling!");
        if ($session['user']['turns']>1){
            $session['user']['turns']--;
        }else{
            $session['user']['turns']=0;
        }
    break;
   break;
    default:
default:
$spi;
        output("`&`n`nDu läufst wie immer durch den Wald als du plötzlich ein merkwürdiges Leuchten durch die Baumstämme hindurch bemerkst. Es scheint zu flackern.`nNeugierig wie du nun mal bist folgst du dem merkwürdigen Leuchten und erblickst schließlich drei in der Luft schwebende Spiegel von denen das flackernde Licht auszugehen scheint.`n`n");
        addnav("zum Spiegel",$fn."?op=spiegel");
        addnav("zurück in den Wald",$fn."?op=wald");
    break;

}
?> 