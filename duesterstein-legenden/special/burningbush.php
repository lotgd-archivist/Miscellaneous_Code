
<?
/*
Burning Bush
Written by Robert for Maddnets LoGD
small adjustments by gargamel @ www.rabenthal.de
*/

if (!isset($session)) exit();

if ($HTTP_GET_VARS[op]==""){
    output("`n`@Du bist auf einen `4brennenden Busch `@gestossen.`n`n`@Du erinnerst
    dich an die Geschichten der Alten, daß ein `4brennender Busch `@Weisheit geben
    kann.`n`n`@Der Preis für Weisheit wäre `^1 Edelstein `@- Was tust du?");
    //abschluss intro
    addnav("Suche nach Weisheit","forest.php?op=give");
    addnav("Zurück in den Wald","forest.php?op=dont");
    $session[user][specialinc]="burningbush.php";
}
else if ($HTTP_GET_VARS[op]=="give"){
  if ($session[user][gems]>0){
        output("`n`@Du zitterst, während Du auf den `4brennenden Busch `@schaust `@und
        ihn um Weisheit bittest.`n`n`@ Die Flammen auf dem `4brennenden Busch `@beginnen
        wütend aufzuflammen  ...");
        $session[user][gems]-=1;
        switch(e_rand(1,9)){
            case 1:
                output("`n`n`6Die verbrennenden Flammen erfüllen Dich nicht mit Weisheit,
                aber mit `bENERGIE`b.`nDu bekommst zwei Waldkämpfe!`n`0");
                $session[user][turns]+=2;
                break;
            case 2:
                output("`n`n`6Die Flammen verlöschen sofort wieder und verleihen Dir keine
                Weisheit, aber im letzten Schein siehst Du `^2 Edelsteine `6auf dem Boden!`n`0");
                $session[user][gems]+=2;
                break;
            case 3:
                output("`n`n`6Die Flammen schlagen aus und Deine Hosen fangen Feuer!`n
                `6Als Du verzweifelt versuchst die Flammen zu löschen, verpasst Du die
                Worte der Weisheit!`n`6Du gehst unversehrt weg, aber bist noch immer
                nicht weiser");
                break;
            case 4:
                output("`n`n`6Die Flammen erfüllen Dich nicht mit Weisheit, aber wecken
                stattdessen leidenschaftliche Gefühle in Dir!`n`6In Gedanken bist Du
                bei Deiner Liebe.`6`n`nDu bekommst `^2 Charmepunkte`6!`0");
                $session[user][charm]+=2;
                break;
            case 5:
                output("`n`n`6Die Flammen sind heiss, aber Du widerstehst ihnen - Deine Kraft
                versagt nicht!`n`6Deine Lebenspunkte wurden `bpermanent`b um 1 erhöht!`0");
                $session[user][maxhitpoints]++;
                $session[user][hitpoints]++;
                break;
            case 6:
                output("`n`n`6Die Flammen sind doch nicht so heiss!`n`6Du bekommst
                6% Erfahrungspunkte!`n`0");
                $session[user][experience]=round($session[user][experience]*1.06);
                break;
            case 7:
                $gold = e_rand($session[user][level]*20,$session[user][level]*50);
                output("`n`n`6Die Flammen schlagen gegen einen Baum und verbrennen ihn!`n
                `6Hinter dem Baum findest Du `^$gold Gold`6!`0");
                $session[user][gold]+=$gold;
                break;
            case 8:
                output("`n`n`6Die Flammen schlagen aus und Du fängst Feuer!!`n
                `6Du erliegst deinen schweren Verbrennungen.`nOb Du nun weiser bist?`n`n`
                ^Du wirst es morgen wissen!`n");
                $session[user][alive]=false;
                $session[user][hitpoints]=0;
                $session[user][experience]=round($session[user][experience]*0.95);
                $session[user][gold] = 0;
                addnews("`@Wanderer fanden die verbrannten Überreste von `^".($session['user']['name'])."`@.");
                addnav("Tägliche News","news.php");
                break;
            case 9:
                output("`n`n`6Die Flammen erfüllen Dich nicht mit Weisheit, aber
                verstärken Deine Sinne!`n`6Du bemerkst `^3 Edelsteine`6, die verstreut auf
                dem Boden liegen!`0");
                $session[user][gems]+=3;
                break;
        }
    }
    else {
        output("`n`6Wütend schlagen die Flammen in deine Richtung und deine Hosen fangen
        Feuer! Du versuchst wegzurennen, aber bist von dem Feuerschein geblendet. `6Du
        schreist um Hilfe, doch niemand ist da, der helfen könnte.`n
        Der `4brennende Busch `6wollte einen Edelstein, aber Du warst zu arm um ihm
        einen zu geben. `n Du versuchst wegzurennen und fällst mit dem Gesicht voran
        in ein Sumpfloch!`nDu bist verletzt und kannst heute nicht mehr soviel kämpfen.`0");
        $session[user][turns]-=2;
    }
    $session[user][specialinc]="";
}
else if ($HTTP_GET_VARS[op]=="dont"){
    output("`n`@Da Du Deine kostbaren Edelsteine mit niemandem teilen willst,
    ignorierst du den `4brennenden Busch `@und gehst weiter.");
    $session[user][specialinc]="";
}
?>


