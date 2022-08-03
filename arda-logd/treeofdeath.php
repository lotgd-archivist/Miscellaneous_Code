<?
require_once "common.php";
page_header("Der Baum des Todes");
output("`c `$ Der Baum des Todes`c`n`n");
//--------------------------------------------------------------------------------------------------------
//| Written by:  Hecki and Kisa
//| Version:    1.1 - 01/18/2005
//|
//| SQL: ALTER TABLE `accounts` ADD `deadtreepick` INT( 11 ) UNSIGNED DEFAULT '0' NOT NULL ;
//| in newday add $session['user']['deadtreepick']=0;
//--------------------------------------------------------------------------------------------------------
switch($HTTP_GET_VARS[op])
    {

case "":
output("`c`9Du streifst durch die Hölle und deine Glieder zittern. Irgendwie fühlst du dich garnicht wohl in deiner Haut und du suchst verzweifelt einen Ausweg aus deiner misslichen Lage. `n`n

Als du um eine Ecke biegst, erblickst du vor dir einen riesigen schwarzen Baum, der dich vor Angst erschauern lässt! Von deiner unbezwingbaren Neugier getrieben, schaust du ihn dir, nach einigen Sekunden des Schreckens, vorsichtig etwas genauer an.`n`n


Er ist riesig, höher als dein Blick je reichen könnte, schwarz und düster, einfach angsteinflößend und mehr als nur unheimlich. Gänsehaut übersäht deinen Körper, doch als du seine mit Spinnweben behängten Äste musterst, fällt dir auf, dass hinter seiner schaurigen Fassade doch noch irgendwo Leben in ihm zu stecken scheint.`n`n

Ein schwarzer Schatten huscht an dir vorüber und wieder vor Angst erstarrt hörst du seinen Worten bangend zu:`n `$ Diesem Baum kann nicht einmal der Tod etwas anhaben, es scheint er verbirgt magische Kräfte in sich, jedoch ist sein Wesen vom Tod getrübt, also sei vorsichtig wenn du dich an seinen Früchten bedienst.`n`n

`^ Was wirst du tun??`c");
addnav("Optionen");
addnav("Nimm was vom Baum","treeofdeath.php?op=pickfruit");
addnav("Zurück zur Hölle","thehell.php");
break;

case "pickfruit":
    if ($session[user][deadtreepick] <1){
    output("`n`n`9Du versuchst etwas vom `$ Baum des Todes `9zu nehmen und findest....`n`n");
        $rand1 = e_rand(1,13);
        switch ($rand1){
        case 1:
            output("`^keine einzige Frucht, die man auch nur annähend als reif bezeichnen könnte.`n`n `$ Deprimiert wendest du dich wieder der Hölle zu!!");
            break;
        case 2:
            output("`^ einen kleinen Beutel, der sich in einem Ast verschlungen hat.`n`n Als du den Beutel öffnest funkelt dir ein `$ Edelstein `^ entgegen!!");
            $session[user][gems]+=1;
            $session[user][deadtreepick]++;
            break;
        case 3:
            //output("`^ einen gefallenen Engel, welcher zwischen zwei Ästen feststeckt. Hilfsbereit une Ehrenhaft wie du bist, befreist du den gefallenen Engel mit ein paar geschickten Handgriffen und er ist dir für deine ruhmvolle Tat sehr dankbar. `n`nEr schwingt seinen Stab und du stellst fest, dass dich diese Tat noch `^charmanter `^ gemacht hat!");
          //  $session[user][charm]+=2;
            //$session[user][deadtreepick]++;
            //break;
        case 4:
            output("`$ Nichts!`n`n `^ Du verfluchst den gefräßigen Ramius und gehst zurück zur Hölle.");
            break;
        case 5:
             output("`^ eine Frucht, die fürchterlich nach Schwefel stinkt, aber da du nichts besseres hast schlingst du die Frucht hinunter!`n `n `$ Du verlierst 15 Gefallen.");
                        $session[user][deathpower]-=15;
                        $session[user][deadtreepick]++;
            break;
        case 6:
            output("`^ eine Frucht, die mit ihrem komischen Geruch und leicht fauligem Aussehen nicht gerade sehr einladend wirkt.`n`n Da du aber nichts besseres finden kannst, schlingst du die Frucht hinunter!`n`n `$ Du bekommst 15 Gefallen!!");
                        $session[user][deathpower]+=15;
                        $session[user][deadtreepick]++;
            break;
             case 7:
            output("`$ Nichts!`n`n  `^ Du verfluchst das dreiköpfige Eichhörnchen und gehst zurück in die Hölle.");
            break;
            case 8:
            output("`^ Ramius der auf dem Baum sitzt und genüsslich eine Phoenixfrucht verschlingt!!!`n`n");
            output("`^ Ramius schmatzt vor sich hin: `@ Hmm yamm, Hey tu ta mmmh schdör misch nisch beim essen hier geh ein paar seelen quälen!!!`n`n");
            output("`$ Du bekommst 2 Grabkämpfe!!!");
                        $session[user][gravefights]+=2;
                        $session[user][deadtreepick]++;
            break;
            case 9:
            output("`$ Nichts!`n`n `^ Du verfluchst die fünfköpfige Schlange und gehst zurück in die Hölle");
            break;
            case 10:
             output("`^ Ramius der auf dem Baum sitzt und genüsslich eine Phoenixfrucht verschlingt!!!`n`n");
            output("`^ Ramius schmatzt vor sich hin: `@ Hmm yamm, Hey tu ta mmmh schdör misch nisch beim essen!!!!`n`n");
            output("`$ Ramius schubbst dich vom Baum und du bist für 2 Grabkämpfe bewusstlos!!!!");
                        $session[user][gravefights]-=2;
                        $session[user][deadtreepick]++;
            break;
           case 11:
        output("`^ den größten, schönsten und leckersten Apfel, den du in deinem ganzen Leben jemals gesehen hast.`n`n Gierig schlingst du ihn hinunter und du fühlst dich sofort viel, viel kräftiger als vorher aber auch träge, da es wirklich ein RIESEN Apfel war und dein Bauch bis an den Rand gefüllt ist. `n`n `$ Du bekommst einen Attackepunkt und 10 permanente Lebenspunkte dazu, jedoch verlierst du einen Verteidigungspunkt!");
       $session[user][attack]++;
       $session[user][maxhitpoints]+=10;
       $session[user][defence]--;
       $session[user][deadtreepick]++;
      break;
      case 12:
          output("`^ einige Früchte, welche dir alle zu Füßen fallen, als du eine Frucht nur ganz leicht berührst.  \"Die Früchte sehen etwas seltsam aus\", denkst du dir. Aber da dein Magen bei diesem Anblick umgehend anfängt zu knurren beschliesst du, dass es es die Konsequenzen wert sind, welche auch immer das sein mögen!!!`n`n");
          output("`$ Du bist jetzt einer der wenigen besoffenen Toten *hic*!!!");
          $session[user][drunkenness]=66;
          //$session[user][turns]-10;
           $session[user][deadtreepick]++;
          break;
          case 13:
           output("`^ auf den ersten Blick nichts, was dich dazu verleitet den Baum hochzuklettern. Dennoch hoffst du etwas zu finden und kletterst weiter und weiter und weiter, bis du den obersten Ast des Baumes erreichst. Dort findest du eine pechschwarze Frucht, welche dir ein paar `\$Seelenpunkte `^bringt!");
      $session[user][soulpoints]+=50;
       $session[user][deadtreepick]++;
      break;
        }
       // if ($session[user][deadtreepick]==1)
    addnav("Zurück zur Hölle","thehell.php");
    }else{
        output("`@Du beschliesst den andern Seelen auch noch etwas von der kostbaren Frucht des Baumes übrig zu lassen...");
        addnav("Zurück in die Hölle","thehell.php");
    }

break;

}
page_footer();
?>