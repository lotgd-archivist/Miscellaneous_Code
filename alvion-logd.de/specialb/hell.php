
<?php
/*
Todeshöhle 0.00000000000000000000000000001 *lol*
by Hadriel
abgekuckt(oder auch verändert) in stonehenge.php
Leichte Balance und Textverbesserungen by Blanidur
*/
if ($_GET[op]=="" || $_GET[op]=="search"){
  output("`#Du wanderst, auf der Suche nach etwas zum Bekämpfen, ziellos durch die Berge. Plötzlich stehst du mitten vor einem großen Felsen mit einem Eingang.");
    output("Um diesen Eingang liegen überall schrecklich zugerichtete Leichen. Ist dies die legendäre");
    output("Todeshöhle? Du hast die Leute in Alvion über diesen mystischen Ort reden hören, aber");
    output(" du hast nicht einmal im Traum daran geglaubt, dass sie wirklich existiert. Es heisst, die Höhle habe große magische ");
    output("Kräfte und dass diese Kräfte unberechenbar seien. Was wirst du tun?");
    output("`n`n<a href='berge.php?op=hell'>Betrete die Höhle</a>`n<a href='berge.php?op=leavehell'>Lasse sie in Ruhe</a>",true);
    addnav("H?Betrete die Höhle","berge.php?op=hell");
    addnav("Lasse sie in Ruhe","berge.php?op=leavehell");
    addnav("","berge.php?op=hell");
    addnav("","berge.php?op=leavehell");
    $session[user][specialinc]="hell.php";
}else{
  $session[user][specialinc]="";
    if ($_GET[op]=="hell"){
      $rand = e_rand(1,22);
        output("`#Obwohl du weißt, daß die Kräfte der Höhle unvorhersagbar wirken, nimmst du diese Chance wahr. Du ");
        output("läufst in das Innere der legendären Höhle und bist bereit, die fantastischen Kräfte dieses Ortes zu erfahren. ");
        output("Als du die Mitte erreichst, gleicht die Decke einem Himmel bei schwarzer, sternenklarer Nacht. Du bemerkst, dass der Boden unter ");
        output("deinen Füßen in einem schwachen lila Licht zu glühen scheint, fast so, als ob sich der Boden selbst in Nebel ");
        output("verwandeln will. Du fühlst ein Kitzeln, das sich durch deinen gesamten Körper ausbreitet. Plötzlich umgibt ein ");
        output("helles, intensives Licht die Höhle und dich. Als das Licht verschwindet, ");
        switch ($rand){
          case 1:
          case 2:
                output("bist du nicht mehr länger in der Höhle.`n`nÜberall um dich herum sind die Seelen derer, die ");
                output("in alten Schlachten und bei bedauerlichen Unfällen umgekommen sind. ");
                output("Jede trägt Anzeichen der Niedertracht, durch welche sie ihr Ende gefunden haben. Du bemerkst mit steigender Verzweiflung, dass ");
                output("die Höhle dich direkt ins Land der Toten transportiert hat!");
                output("`n`n`^Du wurdest aufgrund deiner dümmlichen Entscheidung in die Unterwelt geschickt.`n");
                output("Da du physisch dorthin transportiert worden bist, hast du noch dein ganzes Gold.`n");
                output("Du verlierst aber 5% deiner Erfahrung.`n");
                output("Du kannst morgen wieder spielen.");
                $session[user][alive]=false;
                $session[user][hitpoints]=0;
                $session[user][experience]*=0.95;
                addnav("Tägliche News","news.php");
                addnews($session[user][name]." `#liegt in den Bergen und könnte nun mit Hackfleisch verwechselt werden.");
                break;
            case 3:
                     output(" `#liegt dort nur noch der Körper eines Kriegers, der die Kräfte der Höhle herausgefordert hat.");
                output("`n`n`^Dein Geist wurde aus deinem Körper gerissen!`n");
                output("Da dein Körper in der Höhle liegt, verlierst du all dein Gold.`n");
                output("Du verlierst 10% deiner Erfahrung.`n");
                output("Du kannst morgen wieder spielen.");
                $session[user][alive]=false;
                $session[user][hitpoints]=0;
                $session[user][experience]*=0.9;
                $session['user']['donation']+=1;
                $session[user][gold] = 0;
                addnav("Tägliche News","news.php");
                addnews($session[user][name]." wurde gepfählt im Gebirge gefunden!");
                break;
            case 4:
            case 5:
            case 6:
                output("fühlst du eine zerrende Energie durch deinen Körper zucken, als ob deine Muskeln verbrennen würden. Als der schreckliche Schmerz nachlässt, bemerkst du, dass deine Muskeln VIEL grösser geworden sind.");
                  $reward = round($session[user][experience] * 0.3);
                output("`n`n`^Du bekommst `7$reward`^ Erfahrungspunkte!");
                $session[user][experience] += $reward;
                break;
            case 7:
            case 8:
            case 9:
            case 10:
                $reward = e_rand(1, 3);         // original value: 1,4
                 if ($reward == 3) $rewardn = "DREI`^ Edelsteine";
                else if ($reward == 2) $rewardn = "ZWEI`^ Edelsteine";
                else if ($reward == 1) $rewardn = "EINEN`^ Edelstein";
                output("...`n`n`^bemerkst du `%$rewardn vor deinen Füßen!`n`n");
                $session[user][gems]+=$reward;
                //debuglog("found gems from Stonehenge");  // said 3 gems ... can be less!!
                break;

            case 11:
            case 12:
            case 13:
                output("hast du viel mehr Vertrauen in deine eigenen Fähigkeiten.`n`n");
//                output("`^You gain four charm!");    // whoooohaa ... slow down a bit ;)
    output("`^Dein Charme steigt!");
                $session[user][charm] += 2;
                break;
            case 14:
            case 15:
            case 16:
            case 17:
            case 18:
                output("fühlst du dich plötzlich extrem gesund.");
                output("`n`n`^Deine Lebenspunkte wurden vollständig aufgefüllt.");
                if ($session[user][hitpoints]<$session[user][maxhitpoints]) $session[user][hitpoints]=$session[user][maxhitpoints];
                break;
            case 19:
            case 20:
              output("fühlst du deine Ausdauer in die Höhe schiessen!");
             //     $reward = $session[user][maxhitpoints] * 0.5;
                  $reward = 1;
                output("`n`n`^Deine Lebenspunkte wurden `bpermanent`b um `7$reward `^erhöht!");
                $session[user][maxhitpoints] += $reward;
                $session[user][hitpoints] = $session[user][maxhitpoints];
                break;
            case 21:
            case 22:
                $prevTurns = $session[user][turns];
                if ($prevTurns >= 5) $session[user][turns]-=5;    // original value 5 - but i only offer 20 ff a day
                else if ($prevTurns < 5) $session[user][turns]=0;
                $currentTurns = $session[user][turns];
                $lostTurns = $prevTurns - $currentTurns;

                output("ist der Tag vergangen. Es scheint, als hätte die Höhle dich für die meiste Zeit des Tages in der Zeit eingefroren.`n");
                output("Mit dem Ergebnis, daß du leider $lostTurns Waldkämpfe verlierst!");
                break;
        }
    }else{
      output("`#Du fürchtest die unglaublichen Kräfte dieses Ortes und beschließt, die kalten Gänge der Höhle lieber in Ruhe zu lassen. Du gehst zurück in den Wald.");
    }
}
?>

