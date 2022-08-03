<?php


/*
  --- Der Polierte Panzer (Kneipe) ---

  Version 1.3

  Script by Lazalantin

  2.1.2006

  Email: dark-pilzkopf@web.de

  http://www.lemuria-legend.de/
  LoGD 0.9.7+jt ext (GER) Mystical Lemuria v1.0

*/

/*
  --- Einbauanleitung ---

  eigentlich einfach nur irgendwo in der village.php verlinken


*/



require_once "common.php";
checkday();
addcommentary();


page_header("Der Polierte Panzer");

// Alkoholkosten Einstellungen (Gold)

    $bier_m=$session['user']['level']*25;    // Kosten Malzbier
    $bier_s=$session['user']['level']*50;    // Kosten Schwarzbier
    $bier_w=$session['user']['level']*50;    // Kosten Weißbier
    $bier_z=$session['user']['level']*75;    // Kosten Zwergenbier

    $sch_g=$session['user']['level']*10;     // Kosten Gin
    $sch_v=$session['user']['level']*12;     // Kosten Vodka
    $sch_t=$session['user']['level']*15;     // Kosten Tequila
    $sch_s=$session['user']['level']*13;     // Kosten Sherry
    $sch_ver=$session['user']['level']*20;   // Kosten Vermouth

    $wein_r1=$session['user']['attack']*30;     // Kosten Rotwein 1
    $wein_r2=$session['user']['level']*65;      // Kosten Rotwein 2
    $wein_r3=$session['user']['level']*100;     // Kosten Rotwein 3
    $wein_blut=$session['user']['level']*29;    // Kosten Blutwein
    $wein_weis=$session['user']['defence']*30;  // Kosten Weißwein

// Trankkosten Einstellungen (Edelsteine)

    $t1 = 2;    // Kosten kleiner LP-Trank
    $t2 = 4;    // Kosten großer LP-Trank
    $t3 = 3;    // Kosten kleiner Regenerationstrank
    $t4 = 5;    // Kosten großer Regenerationstrank
    $t5 = 3;    // Kosten kleiner Ausdauertrank
    $t6 = 6;    // Kosten großer Ausdauertrank

// Andere Einstellungen

    $c = 30;     // Dks um ins Casino zu dürfen
   // $c2 = 250;   // Charmepunkte um ins Casino zu dürfen

    $nc = 30;    // Dks um in den Nachtclub zu dürfen
    //$nc2 = 250;  // Charmepunkte um in den Nachtclub zu dürfen


$gold = getsetting("bargold",0);
$es = getsetting("bares",0);

if ($_GET[op]==""){
    output("`TDu betrittst die kleine Kneipe. In der Luft liegt der Geruch von Alkohol und Zigarrenqualm. Du setzt dich an die Bar, an der der Barkeeper Lazalantin gerade ein Glas putzt. Dann hörst du den anderen Kriegern zu, dir sich hier gefunden haben und im Rausch nur Stuss von sich geben. Hier bekommst du die besten Getränke im ganzen Land und weiter hinten im Laden, nur für Stammgäste natürlich, gibt es auch ein Casino und einen kleinen Nachtclub.");
    output("`n`n`TAn der Wand hängt eine kleine Tabelle. Auf ihr wird das Vermögen von Barkeeper Lazalantin angezeigt. Seine Kunden haben schon für `^ $gold `TGold und `^ $es `TEdelsteine hier getrunken.");
    output("`n`n`2Einige besoffene und unbesoffene Krieger unterhalten sich:`n`n");
    viewcommentary("bar","Unterhalte dich mit den Kneipengästen",15);



    addnav("Die Kneipe");
    addnav("Nach dem Casino fragen","bar.php?op=f1");
    if ($session['user']['rlalter']>=18){
    addnav("Nach dem Nachtclub fragen","bar.php?op=f2");
    }

    addnav("Alkohol");
    addnav("Bier bestellen","bar.php?op=bier");
    addnav("Schnaps bestellen","bar.php?op=schnaps");
    addnav("Wein bestellen","bar.php?op=wein");

    addnav("Anderes");
    addnav("Tränke bestellen","bar.php?op=trank");

    addnav("Wege");
    // addnav("Zurück in die Egelgasse","egelgasse.php");
    // Weg aus meinem Spiel, anpassen oder entfernen ;)
    addnav("Zurück zur Zwergenstadt","zwergenstadt.php");


}elseif ($_GET[op]=="f1"){
    if ($session[user][dragonkills]<$c /*&& $session[user][charme]<$c2*/){
        output("`TLazalantin sieht dich leicht verwirrt an. `\$ \"Sag mal, wie viel hast du heute schon getrunken? In meinem Laden gibt es keine illegalen Spiele! \" `TDann wendet er sich wieder von dir ab.");
        addnav("Zurück","bar.php");

    }else{
        output("`TLazalantin sieht dich von oben bis unten gründlich an und tritt näher an dich ran. Dann nuschelt ihr dir ins Ohr. `\$ \"Ich weiß nicht, von wem du den Tipp bekommen hast, aber das um in mein kleines Casino zu kommen musst du hier gleich hinter der Bar, die erste Tür nehmen. Klopf einmal und sag dem Türsteher das Passwort \"Spielschulden\"... \" `TDann wendet er sich von dir ab und geht seiner Arbeit nach.");
        addnav("Wege");
        addnav("Ins Casino","bar_casino.php");
        addnav("Zurück","bar.php");
    }

}elseif ($_GET[op]=="f2"){
    if ($session[user][dragonkills]<$nc /*&& $session[user][charme]<$nc2*/){
        output("`TLazalantin sieht dich leicht verwirrt an. `\$ \"Sag mal, wie viel hast du heute schon getrunken? In meinem Laden gibts keine Huren! \" `TDann wendet er sich wieder von dir ab.");
        addnav("Zurück","bar.php");

    }else{
        output("`TLazalantin sieht dich von oben bis unten gründlich an und tritt näher and ich ran. Dann nuschelt ihr dir ins Ohr. `\$ \"Ich weiß nicht, von wem du den Tipp bekommen hast, aber zu meinen Mädchen gehts hier gleich um an der Bar um die Ecke, die zweite Tür. Da hast du eine Auswahl der besten Mädchen aus diesem Dorf. Und so teuer sind sie auch wieder nicht. Sag dem Tüsteher, der dir aufmacht das Passwort \"Zwiebelfisch\" und schon kannst du dich an den besten Mädchen bedienen... \" `TDann wendet er sich von dir ab und geht seiner Arbeit nach.");
        addnav("Wege");
        addnav("In den Nachtclub","bar_club.php");
        addnav("Zurück","bar.php");
    }

}elseif ($_GET[op]=="bier"){
    if ($session[user][drunkenness]>60){
        output("`TDu willst dir ein Bier bestellen, doch Lazalantin sieht dich nur kurz an und meint dann: `\$ \"Du hast heute schon genug getrunken. Verzieh dich.\" ");
        addnav("Zurück","bar.php");
    }else{
        output("`TDu willst dir ein Bier bestellen. Lazalantin schiebt dir eine Liste rüber, auf der alle Sorten Bier stehen, die momentan verfügbar sind.");
        addnav("Bier");
        addnav("Malzbier ($bier_m Gold)","bar.php?op=1");
        addnav("Schwarzbier ($bier_s Gold)","bar.php?op=2");
        addnav("Weißbier ($bier_w Gold)","bar.php?op=3");
        if ($session[user][race]==4) addnav("Zwergenbier ($bier_z Gold)","bar.php?op=4");

        addnav("Wege");
        addnav("Zurück","bar.php");
    }


}elseif ($_GET[op]=="schnaps"){
    if ($session[user][drunkenness]>80){
        output("`TDu willst dir ein Schnaps bestellen, doch Lazalantin sieht dich nur kurz an und meint dann: `\$ \"Du hast heute schon genug getrunken. Verzieh dich.\" ");
        addnav("Zurück","bar.php");
    }else{
        output("`TDu willst dir ein Schnaps bestellen. Lazalantin schiebt dir eine Liste rüber, auf der alle Sorten Schnäpse stehen, die momentan verfügbar sind.");
        addnav("Schnaps");
        addnav("Gin ($sch_g Gold)","bar.php?op=5");
        addnav("Vodka ($sch_v Gold)","bar.php?op=6");
        addnav("Tequila ($sch_t Gold)","bar.php?op=7");
        addnav("Sherry ($sch_s Gold)","bar.php?op=8");
        addnav("Vermouth ($sch_ver Gold)","bar.php?op=9");

        addnav("Wege");
        addnav("Zurück","bar.php");
    }



}elseif ($_GET[op]=="wein"){
    if ($session[user][drunkenness]>50){
        output("`TDu willst dir einen Wein bestellen, doch Lazalantin sieht dich nur kurz an und meint dann: `\$ \"Du hast heute schon genug getrunken. Verzieh dich.\" ");
        addnav("Zurück","bar.php");
    }else{
        output("`TDu willst dir einen Wein bestellen. Lazalantin schiebt dir eine Liste rüber, auf der alle Sorten Weine stehen, die momentan verfügbar sind.");
        addnav("Wein");
        addnav("Rotwein (Jahreswein)($wein_r1 Gold)","bar.php?op=10");
        addnav("Rotwein (5 Jahre)($wein_r2 Gold)","bar.php?op=11");
        addnav("Rotwein (20 Jahre)($wein_r3 Gold)","bar.php?op=12");
        // if ($session[user][race]==x) addnav("Blutwein ($wein_blut Gold)","bar.php?op=13");
        // Blutwein nur für Vampire, wobei x die Race sein muss
        addnav("Weißwein ($wein_weis Gold)","bar.php?op=14");

        addnav("Wege");
        addnav("Zurück","bar.php");
    }

}elseif ($_GET[op]=="trank"){
    if ($session[user][gems]<1){
        output("`TDu willst dir einen Trank kaufen, doch Lazalantin bedeutet dir nur, das Tränke Edelsteine kosten, du aber keine hast.");
        addnav("Zurück","bar.php");
    }else{
        output("`TDu willst dir einen Trank kaufen. Lazalantin schiebt dir eine Liste rüber, auf der alle Tränke stehen, die momentan verfügbar sind.");
        addnav("Tränke");
        addnav("Kleiner Lebenstrank ($t1 Edelsteine)","bar.php?op=t1");
        addnav("Großer Lebenstrank ($t2 Edelsteine)","bar.php?op=t2");
        addnav("Kleiner Regenerationstrank ($t3 Edelsteine)","bar.php?op=t3");
        addnav("Großer Regenerationstrank ($t4 Edelsteine)","bar.php?op=t4");
        addnav("Kleiner Ausdauertrank ($t5 Edelsteine)","bar.php?op=t5");
        addnav("Großer Ausdauertrank ($t6 Edelsteine)","bar.php?op=t6");

        addnav("Wege");
        addnav("Zurück","bar.php");
    }


##### Bier #####

}elseif ($_GET[op]=="1"){
    if ($session[user][gold]<$bier_m){
        output("`TKein Geld, kein Bier!");
    }else{
        $session[user][gold]-=$bier_m;
        savesetting("bargold",getsetting("bargold",0)+$bier_m);
        output("`TDu bestellst ein Malzbier und Lazalantin zapft es ab. Er reicht es dir und du gibst ihm das Geld. Ohne abzusetzen haust du es runter und rülpst dann laut.");
        $session[user][drunkenness]+=33;
        $session['bufflist']['201'] = array("name"=>"`#Rausch","rounds"=>10,"wearoff"=>"Dein Rausch verschwindet.","atkmod"=>1.2,"roundmsg"=>"Du hast einen ordentlichen Rausch am laufen.","activate"=>"offense");
        switch (e_rand(1,3)){
            case 1:
            case 2:
            $session[user][hitpoints]+= $session[user][hitpoints]*0.1;
            break;

            case 3:
            $session[user][turns]++;
            break;
        }
        addnav("Zurück","bar.php");
    }


}elseif ($_GET[op]=="2"){
    if ($session[user][gold]<$bier_s){
        output("`TKein Geld, kein Bier!");
    }else{
        $session[user][gold]-=$bier_s;
        savesetting("bargold",getsetting("bargold",0)+$bier_s);
        output("`TDu bestellst ein Schwarzbier und Lazalantin zapft es ab. Er reicht es dir und du gibst ihm das Geld. Ohne abzusetzen haust du es runter und rülpst dann laut.");
        $session[user][drunkenness]+=33;
        $session['bufflist']['201'] = array("name"=>"`#Rausch","rounds"=>12,"wearoff"=>"Dein Rausch verschwindet.","atkmod"=>1.25,"roundmsg"=>"Du hast einen ordentlichen Rausch am laufen.","activate"=>"offense");
        switch (e_rand(1,3)){
            case 1:
            case 2:
            $session[user][hitpoints]+= $session[user][hitpoints]*0.1;
            break;

            case 3:
            $session[user][turns]++;
            break;
        }
        addnav("Zurück","bar.php");
    }


}elseif ($_GET[op]=="3"){
    if ($session[user][gold]<$bier_w){
        output("`TKein Geld, kein Bier!");
    }else{
        $session[user][gold]-=$bier_w;
        savesetting("bargold",getsetting("bargold",0)+$bier_w);
        output("`TDu bestellst ein Weißbier und Lazalantin zapft es ab. Er reicht es dir und du gibst ihm das Geld. Ohne abzusetzen haust du es runter und rülpst dann laut.");
        $session[user][drunkenness]+=33;
        $session['bufflist']['201'] = array("name"=>"`#Rausch","rounds"=>12,"wearoff"=>"Dein Rausch verschwindet.","atkmod"=>1.25,"roundmsg"=>"Du hast einen ordentlichen Rausch am laufen.","activate"=>"offense");
        switch (e_rand(1,3)){
            case 1:
            case 2:
            $session[user][hitpoints]+= $session[user][hitpoints]*0.1;
            break;

            case 3:
            $session[user][turns]++;
            break;
        }
        addnav("Zurück","bar.php");
    }


}elseif ($_GET[op]=="4"){
    if ($session[user][gold]<$bier_z){
        output("`TKein Geld, kein Bier!");
    }else{
        $session[user][gold]-=$bier_z;
        savesetting("bargold",getsetting("bargold",0)+$bier_z);
        output("`TDu bestellst ein Zwergenbier und Lazalantin zapft es ab. Er reicht es dir und du gibst ihm das Geld. Ohne abzusetzen haust du es runter und rülpst dann laut.");
        $session[user][drunkenness]+=33;
        $session['bufflist']['202'] = array("name"=>"`#Zwergenausch","rounds"=>12,"wearoff"=>"Dein Rausch verschwindet.","atkmod"=>1.35,"defmod"=>1.1,"roundmsg"=>"Du hast einen ordentlichen Rausch am laufen.","activate"=>"offense");
        switch (e_rand(1,3)){
            case 1:
            case 2:
            $session[user][hitpoints]+= $session[user][hitpoints]*0.4;
            break;

            case 3:
            $session[user][turns]++;
            break;
        }
        addnav("Zurück","bar.php");
    }


##### Schnaps #####

}elseif ($_GET[op]=="5"){
    if ($session[user][gold]<$sch_g){
        output("`TKein Geld, kein Schnaps!");
    }else{
        $session[user][gold]-=$sch_g;
        savesetting("bargold",getsetting("bargold",0)+$sch_g);
        output("`TDu bestellst ein Gin und Lazalantin schüttet ihn dir ein. Er reicht es dir und du gibst ihm das Geld. Ohne abzusetzen haust du es runter und rülpst dann laut.");
        $session[user][drunkenness]+=20;
        $session['bufflist']['301'] = array("name"=>"`#Ginrausch","rounds"=>12,"wearoff"=>"Dein Rausch verschwindet.","atkmod"=>1.05,"roundmsg"=>"Du hast einen ordentlichen Rausch am laufen.","activate"=>"offense");
        switch (e_rand(1,3)){
            case 1:
            case 2:
            $session[user][hitpoints]+= $session[user][hitpoints]*0.05;
            break;

            case 3:
            $session[user][turns]++;
            break;
        }
        addnav("Zurück","bar.php");
    }

}elseif ($_GET[op]=="6"){
    if ($session[user][gold]<$sch_v){
        output("`TKein Geld, kein Schnaps!");
    }else{
        $session[user][gold]-=$sch_v;
        savesetting("bargold",getsetting("bargold",0)+$sch_v);
        output("`TDu bestellst ein Vodka und Lazalantin schüttet ihn dir ein. Er reicht es dir und du gibst ihm das Geld. Ohne abzusetzen haust du es runter und rülpst dann laut.");
        $session[user][drunkenness]+=30;
        $session['bufflist']['302'] = array("name"=>"`#Vodkarausch","rounds"=>12,"wearoff"=>"Dein Rausch verschwindet.","atkmod"=>1.05,"roundmsg"=>"Du hast einen ordentlichen Rausch am laufen.","activate"=>"offense");
        switch (e_rand(1,3)){
            case 1:
            case 2:
            $session[user][hitpoints]+= $session[user][hitpoints]*0.1;
            break;

            case 3:
            $session[user][turns]++;
            break;
        }
        addnav("Zurück","bar.php");
    }

}elseif ($_GET[op]=="7"){
    if ($session[user][gold]<$sch_t){
        output("`TKein Geld, kein Schnaps!");
    }else{
        $session[user][gold]-=$sch_t;
        savesetting("bargold",getsetting("bargold",0)+$sch_t);
        output("`TDu bestellst ein Tequila und Lazalantin schüttet ihn dir ein. Er reicht es dir und du gibst ihm das Geld. Ohne abzusetzen haust du es runter und rülpst dann laut.");
        $session[user][drunkenness]+=30;
        $session['bufflist']['303'] = array("name"=>"`#Tequilarausch","rounds"=>12,"wearoff"=>"Dein Rausch verschwindet.","atkmod"=>1.15,"roundmsg"=>"Du hast einen ordentlichen Rausch am laufen.","activate"=>"offense");
        switch (e_rand(1,3)){
            case 1:
            case 2:
            $session[user][hitpoints]+= $session[user][hitpoints]*0.15;
            break;

            case 3:
            $session[user][turns]++;
            break;
        }
        addnav("Zurück","bar.php");
    }

}elseif ($_GET[op]=="8"){
    if ($session[user][gold]<$sch_s){
        output("`TKein Geld, kein Schnaps!");
    }else{
        $session[user][gold]-=$sch_s;
        savesetting("bargold",getsetting("bargold",0)+$sch_s);
        output("`TDu bestellst ein Sherry und Lazalantin schüttet ihn dir ein. Er reicht es dir und du gibst ihm das Geld. Ohne abzusetzen haust du es runter und rülpst dann laut.");
        $session[user][drunkenness]+=25;
        $session['bufflist']['304'] = array("name"=>"`#Sherryrausch","rounds"=>12,"wearoff"=>"Dein Rausch verschwindet.","atkmod"=>1.05,"roundmsg"=>"Du hast einen ordentlichen Rausch am laufen.","activate"=>"offense");
        switch (e_rand(1,3)){
            case 1:
            case 2:
            $session[user][hitpoints]+= $session[user][hitpoints]*0.05;
            break;

            case 3:
            $session[user][turns]++;
            break;
        }
        addnav("Zurück","bar.php");
    }

}elseif ($_GET[op]=="9"){
    if ($session[user][gold]<$sch_ver){
        output("`TKein Geld, kein Schnaps!");
    }else{
        $session[user][gold]-=$sch_ver;
        savesetting("bargold",getsetting("bargold",0)+$sch_ver);
        output("`TDu bestellst ein Vermouth und Lazalantin schüttet ihn dir ein. Er reicht es dir und du gibst ihm das Geld. Ohne abzusetzen haust du es runter und rülpst dann laut.");
        $session[user][drunkenness]+=40;
        $session['bufflist']['305'] = array("name"=>"`#Vermouthrausch","rounds"=>12,"wearoff"=>"Dein Rausch verschwindet.","atkmod"=>1.05,"roundmsg"=>"Du hast einen ordentlichen Rausch am laufen.","activate"=>"offense");
        switch (e_rand(1,3)){
            case 1:
            case 2:
            $session[user][hitpoints]+= $session[user][hitpoints]*0.3;
            break;

            case 3:
            $session[user][turns]++;
            break;
        }
        addnav("Zurück","bar.php");
    }


##### Wein #####

}elseif ($_GET[op]=="10"){
    if ($session[user][gold]<$wein_r1){
        output("`TKein Geld, kein Wein!");
    }else{
        $session[user][gold]-=$wein_r1;
        savesetting("bargold",getsetting("bargold",0)+$wein_r1);
        output("`TDu bestellst einen Rotwein und Lazalantin schüttet ihn dir ein. Er reicht es dir und du gibst ihm das Geld. Ohne abzusetzen haust du es runter und rülpst dann laut.");
        $session[user][drunkenness]+=50;
        $session['bufflist']['401'] = array("name"=>"`#Weinrausch","rounds"=>12,"wearoff"=>"Dein Rausch verschwindet.","atkmod"=>1.15,"defmod"=>1.2,"roundmsg"=>"Du hast einen ordentlichen Rausch am laufen.","activate"=>"offense");
        switch (e_rand(1,3)){
            case 1:
            case 2:
            $session[user][hitpoints]+= $session[user][hitpoints]*0.3;
            break;

            case 3:
            $session[user][turns]++;
            break;
        }
        addnav("Zurück","bar.php");
    }

}elseif ($_GET[op]=="11"){
    if ($session[user][gold]<$wein_r2){
        output("`TKein Geld, kein Wein!");
    }else{
        $session[user][gold]-=$wein_r2;
        savesetting("bargold",getsetting("bargold",0)+$wein_r2);
        output("`TDu bestellst einen Rotwein und Lazalantin schüttet ihn dir ein. Er reicht es dir und du gibst ihm das Geld. Ohne abzusetzen haust du es runter und rülpst dann laut.");
        $session[user][drunkenness]+=50;
        $session['bufflist']['401'] = array("name"=>"`#Weinrausch","rounds"=>12,"wearoff"=>"Dein Rausch verschwindet.","defmod"=>1.3,"roundmsg"=>"Du hast einen ordentlichen Rausch am laufen.","activate"=>"offense");
        switch (e_rand(1,3)){
            case 1:
            case 2:
            $session[user][hitpoints]+= $session[user][hitpoints]*0.5;
            break;

            case 3:
            $session[user][turns]++;
            break;
        }
        addnav("Zurück","bar.php");
    }

}elseif ($_GET[op]=="12"){
    if ($session[user][gold]<$wein_r3){
        output("`TKein Geld, kein Wein!");
    }else{
        $session[user][gold]-=$wein_r3;
        savesetting("bargold",getsetting("bargold",0)+$wein_r3);
        output("`TDu bestellst einen Rotwein und Lazalantin schüttet ihn dir ein. Er reicht es dir und du gibst ihm das Geld. Ohne abzusetzen haust du es runter und rülpst dann laut.");
        $session[user][drunkenness]+=70;
        $session['bufflist']['401'] = array("name"=>"`#Weinrausch","rounds"=>15,"wearoff"=>"Dein Rausch verschwindet.","defmod"=>1.4,"roundmsg"=>"Du hast einen ordentlichen Rausch am laufen.","activate"=>"offense");
        switch (e_rand(1,3)){
            case 1:
            case 2:
            $session[user][hitpoints]+= $session[user][hitpoints]*1;
            break;

            case 3:
            $session[user][turns]++;
            break;
        }
        addnav("Zurück","bar.php");
    }

}elseif ($_GET[op]=="13"){
    if ($session[user][gold]<$wein_blut){
        output("`TKein Geld, kein Wein!");
    }else{
        $session[user][gold]-=$wein_blut;
        savesetting("bargold",getsetting("bargold",0)+$wein_blut);
        output("`TDu bestellst einen Blutwein und Lazalantin schüttet ihn dir ein. Er reicht es dir und du gibst ihm das Geld. Ohne abzusetzen haust du es runter und rülpst dann laut.");
        $session[user][drunkenness]+=50;
        $session['bufflist']['402'] = array("name"=>"`#Blutwahn","rounds"=>12,"wearoff"=>"Dein Rausch verschwindet.","atkmod"=>1.35,"roundmsg"=>"Du hast einen ordentlichen Rausch am laufen.","activate"=>"offense");
        switch (e_rand(1,3)){
            case 1:
            case 2:
            $session[user][hitpoints]+= $session[user][hitpoints]*0.3;
            break;

            case 3:
            $session[user][turns]++;
            break;
        }
        addnav("Zurück","bar.php");
    }

}elseif ($_GET[op]=="14"){
    if ($session[user][gold]<$wein_weis){
        output("`TKein Geld, kein Wein!");
    }else{
        $session[user][gold]-=$wein_weis;
        savesetting("bargold",getsetting("bargold",0)+$wein_weis);
        output("`TDu bestellst einen Weißwein und Lazalantin schüttet ihn dir ein. Er reicht es dir und du gibst ihm das Geld. Ohne abzusetzen haust du es runter und rülpst dann laut.");
        $session[user][drunkenness]+=50;
        $session['bufflist']['401'] = array("name"=>"`#Weinrausch","rounds"=>12,"wearoff"=>"Dein Rausch verschwindet.","atkmod"=>1.3,"roundmsg"=>"Du hast einen ordentlichen Rausch am laufen.","activate"=>"offense");
        switch (e_rand(1,3)){
            case 1:
            case 2:
            $session[user][hitpoints]+= $session[user][hitpoints]*0.2;
            break;

            case 3:
            $session[user][turns]++;
            break;
        }
        addnav("Zurück","bar.php");
    }

##### Tränke #####

}elseif ($_GET[op]=="t1"){
    if ($session[user][gems]<$t1){
        output("`TKeine Edelsteine, keine Tränke.");
    }else{
        $session[user][gems]-=$t1;
        savesetting("bares",getsetting("bares",0)+$t1);
        output("`TDu bestellst dir bei Lazalantin einen kleinen Lebenstrank und er holt einem aus dem Regal. Du gibst ihm die Edelsteine, er gibt dir den Trank. Du kippst ihn sofort runter.");
        $session[user][hitpoints]+= $session[user][hitpoints]*0.75;
    }
    addnav("Zurück","bar.php");


}elseif ($_GET[op]=="t2"){
    if ($session[user][gems]<$t2){
        output("`TKeine Edelsteine, keine Tränke.");
    }else{
        $session[user][gems]-=$t2;
        savesetting("bares",getsetting("bares",0)+$t2);
        output("`TDu bestellst dir bei Lazalantin einen großen Lebenstrank und er holt einem aus dem Regal. Du gibst ihm die Edelsteine, er gibt dir den Trank. Du kippst ihn sofort runter.");
        $session[user][hitpoints]+= $session[user][hitpoints]*1.5;
    }
    addnav("Zurück","bar.php");



}elseif ($_GET[op]=="t3"){
    if ($session[user][gems]<$t3){
        output("`TKeine Edelsteine, keine Tränke.");
    }else{
        $session[user][gems]-=$t3;
        savesetting("bares",getsetting("bares",0)+$t3);
        output("`TDu bestellst dir bei Lazalantin einen kleinen Regenerationstrank und er holt einem aus dem Regal. Du gibst ihm die Edelsteine, er gibt dir den Trank. Du kippst ihn sofort runter.");
        $session['bufflist']['900'] = array("name"=>"`^Regeneration","rounds"=>12,"wearoff"=>"Deine Regeneration verschwindet.","regen"=>1,"roundmsg"=>"Du hast noch deine Regeneration am laufen.","activate"=>"offense");

    }
    addnav("Zurück","bar.php");

}elseif ($_GET[op]=="t4"){
    if ($session[user][gems]<$t4){
        output("`TKeine Edelsteine, keine Tränke.");
    }else{
        $session[user][gems]-=$t4;
        savesetting("bares",getsetting("bares",0)+$t4);
        output("`TDu bestellst dir bei Lazalantin einen großen Regenerationstrank und er holt einem aus dem Regal. Du gibst ihm die Edelsteine, er gibt dir den Trank. Du kippst ihn sofort runter.");
        $session['bufflist']['900'] = array("name"=>"`^Regeneration","rounds"=>20,"wearoff"=>"Deine Regeneration verschwindet.","regen"=>1.5,"roundmsg"=>"Du hast noch deine Regeneration am laufen.","activate"=>"offense");

    }
    addnav("Zurück","bar.php");


}elseif ($_GET[op]=="t5"){
    if ($session[user][gems]<$t5){
        output("`TKeine Edelsteine, keine Tränke.");
    }else{
        $session[user][gems]-=$t5;
        savesetting("bares",getsetting("bares",0)+$t5);
        output("`TDu bestellst dir bei Lazalantin einen kleinen Ausdauertrank und er holt einem aus dem Regal. Du gibst ihm die Edelsteine, er gibt dir den Trank. Du kippst ihn sofort runter.");
        $session[user][turns]+=(e_rand(1,4));

    }
    addnav("Zurück","bar.php");

}elseif ($_GET[op]=="t6"){
    if ($session[user][gems]<$t6){
        output("`TKeine Edelsteine, keine Tränke.");
    }else{
        $session[user][gems]-=$t6;
        savesetting("bares",getsetting("bares",0)+$t6);
        output("`TDu bestellst dir bei Lazalantin einen großen Ausdauertrank und er holt einem aus dem Regal. Du gibst ihm die Edelsteine, er gibt dir den Trank. Du kippst ihn sofort runter.");
        $session[user][turns]+=(e_rand(3,8));

    }
    addnav("Zurück","bar.php");

}

page_footer();
?> 