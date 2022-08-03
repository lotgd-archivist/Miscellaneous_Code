<?php 
//°-------------------------°
//|      erdspalte.php      |
//|        Script by        |
//|        xitachix         |
//|      mcitachi@web.de    |
//°-------------------------°
//http://logd.macjan.de/
//Sql Befehl
//ALTER TABLE accounts ADD eisenstange int(11) NOT NULL default '0'; 
//änderungen (inn.php)
//Suche: addnav("S?Rede mit dem Barden Seth","inn.php?op=seth");
//Füge davor/danach ein: addnav("Eisenstange kaufen","inn.php?op=eisen");
//Füge noch an gegebener Stelle in der Schenke ein:
//if ($_GET['op']=="eisen"){
//                  if ($session['user']['gems']>5){
//            $session['user']['gems']-=5;
//            $session['user']['eisenstange']++;
//            output("`n`3Du gibst Cedrik 5 Edelsteine und er gibt dir eine Eisenstange. Doch was willst du eigentlich damit?");
//                   } else {
//            output("`n`3Du hast leider keine 5 Edelsteine, die du für die Stange bezahlen musst.");
//     }
//}

if (!isset($session)) exit();
if ($_GET['op']==""){
output("`n`c`&Die Erdspalte`c`n`n");
output("`n`^Du gehst durch den Wald und plötzlich fängt der Boden unter dir zu Wackeln. 
    `nDie Erde bebt regelrecht. Du überlegst schon, was du tun sollst, als du plötzlich einen Siedler in der Spalte stecken siehst.
    `nWirst du ihn versuchen zu retten? Oder wirst du dich davon machen?");
$session['user']['specialinc']="erdspalte.php";
addnav("Ihm die Hand reichen","forest.php?op=hand");
addnav("Einen nahgelegenen Baumstumpf verwenden","forest.php?op=stumpf");
addnav("Eine Eisenstange verwenden","forest.php?op=eisen");
addnav("Wegrennen","forest.php?op=z");
}

if ($_GET['op']=="hand"){
output("`n`^Du überlegst, was du tun sollst und schaust dich um.
    `nSofort rennst du zu ihm hin und reichst ihm deine Hand um ihm zu helfen");
$session['user']['specialinc']="erdspalte.php";
        switch(e_rand(1,4)){ 
            case 1: 
            case 2: 
            output("`n`3Du rutscht mit in die Erdspalte, was wirst du nun tun?");
            addnav("Um Hilfe schreien","forest.php?op=hilfe");
            addnav("Versuchen sich herauszuwinden","forest.php?op=winden");
            addnav("Dem Siedler helfen","forest.php?op=siedler");
            break; 
            case 3: 
            case 4: 
            $edel=(e_rand(1,3));
            $session['user']['gems']+=$edel;
            output("`n`3Du schaffst es den Siedler zu retten und er bedankt sich herlich bei dir
        `nAusserdem gibt er dir $edel Edelsteine");
            addnews("`#".$session['user']['name']." `0 hat einen Einsiedler vor dem Tod bewahrt.");
            break;
            }
      }
if ($_GET['op']=="stumpf"){
output("`n`^Du überlegst, was du tun sollst und schaust dich um.
    `nSofort rennst du zu einem Baumstumpf, der in der Nähe liegt und versuchst die Erdspalte damit zu stopfen");
$session['user']['specialinc']="erdspalte.php";
        switch(e_rand(1,4)){ 
            case 1: 
            case 2: 
            output("`n`3Du rutscht mit in die Erdspalte, was wirst du nun tun?");
            addnav("Um Hilfe schreien","forest.php?op=hilfe");
            addnav("Versuchen sich herauszuwinden","forest.php?op=winden");
            addnav("Dem Siedler helfen","forest.php?op=siedler");
            break; 
            case 3: 
            case 4: 
            $gold=(e_rand(300,1000));
            $session['user']['gold']+=$gold;
            output("`n`3Du schaffst es den Siedler zu retten und er bedankt sich herlich bei dir
        `nAusserdem gibt er dir $gold Gold");
            addnews("`#".$session['user']['name']." `0 hat einen Einsiedler vor dem Tod bewahrt.");
            break;
            }
      }
if ($_GET['op']=="eisen"){
output("`n`^Du überlegst, was du tun sollst und schaust dich um.
    `nPlötzlich fällt dir ein, dass du vor kurzem eine Eisenstangen in der Schenke zum Kauf gesehen hast.");
$session['user']['specialinc']="erdspalte.php";
                  if ($session['user']['eisenstange']>0){
        switch(e_rand(1,5)){ 
            case 1: 
            output("`n`3Du rutscht mit in die Erdspalte, was wirst du nun tun?");
            addnav("Um Hilfe schreien","forest.php?op=hilfe");
            addnav("Versuchen sich herauszuwinden","forest.php?op=winden");
            addnav("Dem Siedler helfen","forest.php?op=siedler");
            break; 
            case 2: 
            case 3: 
            case 4: 
            case 5: 
            $gold1=(e_rand(200,500));
            $edel1=(e_rand(1,3));
            $session['user']['gold']+=$gold1;
            $session['user']['gems']+=$edel1;
            $session['user']['eisenstange']--;
            output("`n`3Du schaffst es den Siedler mit hilfe einer Eisenstange zu retten und er bedankt sich herlzich bei dir.
        `nEr gibt dir $gold1 Gold
        `nUnd $edel1 Edelsteine");
            addnews("`#".$session['user']['name']." `0 hat einen Einsiedler vor dem Tod bewahrt.");
            break;
            }
                   } else {
            output("`n`3Du hast leider vergessen die Eisenstange zu kaufen und rutscht mit in die Erdspalte, was wirst du nun tun?");
            addnav("Um Hilfe schreien","forest.php?op=hilfe");
            addnav("Versuchen sich herauszuwinden","forest.php?op=winden");
            addnav("Dem Siedler helfen","forest.php?op=siedler");
        }
}

if ($_GET['op']=="hilfe"){
output("`n`^Wie ein kleiner Feigling schreist du um Hilfe. Doch wird auch welche kommen?");
$session['user']['specialinc']="erdspalte.php";
        switch(e_rand(1,5)){ 
            case 1: 
            case 2: 
            case 3: 
            output("`n`3Du schreist und schreist, doch will niemand kommen. Die Erdspalte verschluckt dich.");
            output("`n`$ Du bist tot!");
            $session['user']['alive']=false;
            $session['user']['hitpoints']=0;
            addnav("Tägliche News","news.php");
            addnews("`#".$session['user']['name']." `0 wurde von einer Erdspalte verschluckt.");
            break; 
            case 4: 
            case 5: 
            $gold2=(e_rand(100,200));
            $edel2=(e_rand(1,2));
            $session['user']['gold']-=$gold2;
            $session['user']['gems']-=$edel2;
            output("`n`3Du schaffst es dich zu retten, doch der Einsiedler greift wütend nach deinem Beutel.
        `nDu verlierst $gold2 Gold und $edel2 Edelsteine.");
            addnews("`#".$session['user']['name']." `0 hat wie ein kleiner Feigling um Hilfe gerufen.");
            break;
            }
      }
if ($_GET['op']=="winden"){
output("`n`^Du windest dich in der Erdspalte und versuchst so zu entkommen");
$session['user']['specialinc']="erdspalte.php";
        switch(e_rand(1,3)){ 
            case 1: 
            case 2: 
            case 3: 
            output("`n`3Du windest dich, doch nichts passiert. Die Erdspalte verschluckt dich.");
            output("`n`$ Du bist tot!");
            $session['user']['alive']=false;
            $session['user']['hitpoints']=0;
            addnav("Tägliche News","news.php");
            addnews("`#".$session['user']['name']." `0 wurde von einer Erdspalte verschluckt.");
            break; 
            case 4: 
            output("`n`3Du schaffst es dich zu retten, indem du dich herauswindest. Der Einsiedler stirbt jedoch.");
            addnews("`#".$session['user']['name']." `0 hat irgendwie eine Erdspalte überlebt.");
            break;
            }
      }
if ($_GET['op']=="siedler"){
output("`n`^Ehrenmütig wie du bist, rettest du den Siedler und entscheidest dich so sein Leben zu retten, anstatt dich selbst zu retten");
$session['user']['specialinc']="erdspalte.php";
        switch(e_rand(1,3)){ 
            case 1: 
            case 2: 
            $golds=(e_rand(500,1000));
            output("`n`3Du rettest den Siedler und er überweist dir $golds Gold auf die Bank.");
            output("`n`$ Du bist tot!");
            $session['user']['alive']=false;
            $session['user']['hitpoints']=0;
            $session['user']['goldinbank']+=$golds;
            addnav("Tägliche News","news.php");
            addnews("`#".$session['user']['name']." `0 hat sein Leben geopfert um einen Einsiedler zu retten");
            break; 
            case 3: 
            output("`n`3Du rettest den Siedler, bemerkst aber dass er ein Betrüger ist und lachend wegrennt");
            output("`n`$ Du bist tot!");
            $session['user']['alive']=false;
            $session['user']['hitpoints']=0;
            addnav("Tägliche News","news.php");
            addnews("`#".$session['user']['name']." `0 ist das Opfer eines Betruges geworden.");
            break;
            }
      }
if ($_GET['op']=="z"){
                  if ($session['user']['gems']>2){
output("Du rennst davon und verlierst bei deiner Flucht 3 Edelsteine.");
            $edels=$session['user']['gems']-=3;
                   } else {
output("Du rennst davon und verlierst bei deiner Flucht alle Edelsteine");
            $edels=$session['user']['gems']=0;
      }
}
?>