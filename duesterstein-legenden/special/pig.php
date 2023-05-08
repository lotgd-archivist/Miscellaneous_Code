
<?
// by Hadriel of www.hadriel.ch/logd
// small adjustments by gargamel @ www.rabenthal.de

if (!isset($session)) exit();


if ($HTTP_GET_VARS[op]==""){
    output("`n`2Während Du durch den Wald läufst, spürst Du plötzlich einen harten
    Schlag am Oberschenkel. Dann einen stechenden Schmerz. Als Du nach unten schaust,
    siehst Du den Verursacher: Es ist ein `^Wildschwein`2.`n
    Du schlägst mit ".$session[user][weapon]." solange auf das Tier ein, bis es sich
    grunzend verzieht.`n`n`0");
    $session[user][hitpoints]= round($session[user][hitpoints]*0.8);
    output("Du verbindest Dein Bein und überlegst, ob Du dem Tier eine Lektion
    erteilen sollst. Allerdings wird Dich eine Jagd einen Waldkampf kosten.`0");
    //abschluss intro
    addnav("jagen","forest.php?op=huntit");
    addnav("weitergehen","forest.php?op=cont");
    $session[user][specialinc] = "pig.php";
}
else if ($HTTP_GET_VARS[op]=="huntit"){ //jagen
    output("`n`2Du rennst so schnell Du kannst in die ungefähre Richtung, wo das
    Tier verschwunden ist. Dann stösst Du auf einen Weg. Du glaubst links von Dir
    ein Grunzen zu hören, doch Dein Blut rauscht Dir in den Ohren. `n
    `3Wohin wirst Du Dich wenden?`0");
    addnav("Nach links","forest.php?op=left");
    addnav("Nach rechts","forest.php?op=right");
    addnav("Geradeaus ins dichte Gestrüpp","forest.php?op=forward");
    $session[user][specialinc] = "pig.php";
}
else if ($HTTP_GET_VARS[op]=="cont"){   // einfach weitergehen
    output("`n`2Du machst dich mit schmerzendem Oberschenkel auf den Weg zurück
    in den Wald.`0");
    $session[user][specialinc]="";
}
else if ($HTTP_GET_VARS[op]=="right" ||
         $HTTP_GET_VARS[op]=="left" ||
         $HTTP_GET_VARS[op]=="forward") {
    switch($HTTP_GET_VARS[op]) {
        case "left":
        $weg = "nach links und dann so schnell Du kannst `7den Weg`2 entlang.";
        break;
        case "right":
        $weg = "nach rechts und dann so schnell Du kannst `7den Weg`2 entlang.";
        break;
        case "forward":
        $weg = "geradeaus ins dichte `7Gestrüpp`2. Du kommst nur langsam vorwärts,
        aber hier kannst Du Deinen Gegner nicht überhören.";
        break;
    }
    output("`n`2Schnell machst Du Dich an die Verfolgung und rennst $weg`n`n`0");
    $level = $session[user][level];
    $money=e_rand(10,60);
    $gold = (e_rand(1,3)*$money*$session[user][level]);
    switch(e_rand(0,(20-$level))) {
        case 0: case 1: case 2: case 3:
        case 4: case 5:
        output("Du stürzt Dich mit voller Wucht auf das, was Du für das Schwein hälst.
        Ein lautes, erschrecktes Quicken gibt Dir recht, dann hörst Du noch den
        dumpfen `7*Plumps*`^, wie der Körper umfällt. `n`n");
        switch(e_rand(0,5)) {
            case 0: case 1:
            output(" Als Du das Schwein genauer untersuchst, findest du einen Edelstein!
            Das Tier muss ihn wohl mit einem Trüffel verwechselt haben...`0");
            $session[user][gems]++;
            break;
            case 2: case 3: case 4: case 5:
            output(" Als Du das Schwein genauer untersuchst, findest du in seinem
            Magen $gold Gold.`0");
            $session[user][gold]+= $gold;
            break;
        }
        break;

        default:
        output("Du stürzt Dich mit voller Wucht auf das, was Du für das Schwein hälst.
        Als Du schaust, was Du aufgespießt hast, musst Du enttäuscht feststellen,
        dass es wohl nur ein paar Blätter waren.`0");
        break;
    }
    $session[user][specialinc]="";
    $session[user][turns]--;
}
?>


