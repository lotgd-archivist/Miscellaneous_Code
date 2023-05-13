
<?php
/********************
Burning Bush
Written by Robert for Maddnets LoGD
This Burning Bush offers pot luck for most
it cost 1 gem and the risks are low
Text changes from Fairy1
*********************/
if (!isset($session)) exit();
if($_GET['op'] == '' || $_GET['op']== 'search'){ 
    output("`@Du bist auf einen `4brennenden Busch `@gestoßen.`n`n`@Du erinnerst dich an die Geschichten der Alten, daß ein `4brennender Busch `@Weisheit geben kann.`n`n`@Der Preis für Weisheit wäre `^1 Edelstein`@.`n`n`@Was tust du?");
    addnav("Suche nach Weisheit","berge.php?op=give");
    addnav("Zurück in die Berge","berge.php?op=dont");
    $session[user][specialinc]="burningbush.php";
}else if ($_GET[op]=="give"){
  if ($session[user][gems]>0){
        output("`@Du zitterst während du auf den `4brennenden Busch `@schaust `@und ihn um Weisheit bittest.`n`n`@ Die Flammen auf dem `4brennenden Busch `@beginnen wütend aufzuflammen  ...");
        $session[user][gems]-=1;
        debuglog("burningbush -1 Gem");
        switch(e_rand(1,9)){
            case 1:
                output("`n`n`6Die verbrennenden Flammen erfüllen dich nicht mit Weisheit, aber mit `bENERGIE`b.`nDu bekommst `^zwei Waldkämpfe!`6`n");
                $session[usr][turns]+=2;
        debuglog("burningbush +2 WK");
                break;
            case 2:
                output("`n`n`6Die Flammen verlöschen sofort wieder und verleihen dir keine Weisheit, aber im letzten Schein siehst du `^2 Edelsteine `6auf dem Boden!`n");
                $session[user][gems]+=2;
        debuglog("burningbush +2 Gem");
                break;
            case 3:
                output("`n`n`6Die Flammen erfüllen dich nicht mit Weisheit, aber verstärken dein Sinne!`n`6Du bemerkst `^3 Edelsteine`6, die verstreut auf dem Boden liegen!");
                $session[user][gems]+=3;
        debuglog("burningbush +3 Gem");
                break;
            case 4:
                output("`n`n`6Die Flammen erfüllen dich nicht mit Weisheit, aber wecken stattdessen leidenschaftliche Gefühle in dir! Im Gedanken bist du bei deiner Liebe.`6`n`nDu bekommst `^2 Charmepunkte`6!");
                $session[user][charm]+=2;
        debuglog("burningbush +2 Charm");
                break;
            case 5:
                output("`n`n`6Die Flammen sind heiss aber du widerstehst ihnen - Deine Kraft versagt nicht!`n`6Deine Lebenspunkte wurden `bpermanent`b um `^1 `6erhöht!");
                $session[user][maxhitpoints]++;
                $session[user][hitpoints]++;
        debuglog("burningbush +1 HP perm");
                break;
            case 6:
                output("`n`n`6Die Flammen sind doch nicht so heiss!`n`6Du bekommst `^10% Erfahrungspunkte`6!`n");
                $session[user][alive]=true;
                $session[user][experience]*=1.1;
        debuglog("burningbush +10% Exp");
                break;
            case 7:
                $gold = e_rand($session[user][level]*20,$session[user][level]*50);
                output("`n`n`6Die Flammen schlagen gegen einen Baum und verbrennen ihn!`n`6Hinter dem Baum findest du `^$gold Gold`6!");
                $gold = e_rand($session[user][level]*20,$session[user][level]*100);
                $session[user][gold]+=$gold;
        debuglog("burningbush +$gold Gold");
                break;
            case 8:
                output("`n`n`6Die Flammen schlagen aus und du fängst Feuer!!`n`6Du erliegst deinen schweren Verbrennungen.`nHoffentlich bist du nun weiser!`n`n`^Du verlierst 10% Erfahrungspunkte`6!`n");
                if ($session[user][gold]>0) output("`n`^Du verlierst all dein Gold!`n");
                $session[user][alive]=false;
                $session[user][hitpoints]=0;
                $session[user][experience]*=0.9;
                $session[user][gold] = 0;
        debuglog("burningbush TOD -10% Exp");
                addnews("`6Wanderer fanden die verbrannten Überreste von `^".($session['user']['name'])."`6.`n");
                addnav("Tägliche News","news.php");
                break;
            case 9:
                output("`n`n`6Die Flammen schlagen aus und deine Hosen fangen Feuer!`n`6Als du verzweifelt versuchst die Flammen zu löschen, verpasst du die Worte der Weisheit!`n`6Du gehst unversehrt weg, aber bist noch immer nicht weiser.");
                break;
        }
    }else{
        output("`n`6Wütend schlagen die Flammen in deine Richtung und deine Hosen fangen Feuer! Du versuchst wegzurennen aber bist von dem Feuerschein geblendet. `6Du schreist um Hilfe doch niemand ist da der helfen könnte.`nDer `4brennende Busch `6wollte einen Edelstein, aber du warst zu arm um ihm einen zu geben. `n Du versuchst wegzurennen und fällst mit dem Gesicht zuerst in ein Sumpfloch!`nDu bist verletzt und kannst heute nicht mehr soviel kämpfen.");
        addnews("`^Manche mögens heiß! `6".($session['user']['name'])." `6hatte eine unangenehme Begegnung mit einem `4brennenden Busch`6.`0`n");
        $session[user][turns]-=2;
        debuglog("burningbush -2 WK");
    }
}else{
    output("`@Da du deine kostbaren Edelsteine mit niemandem teilen willst, ignorierst du den `4brennenden Busch `@und gehst weiter.");
}
?>

