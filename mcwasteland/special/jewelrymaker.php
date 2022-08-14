
<?php
/*
* jewelrymaker.php - die seltsame Elfenkunst
* 
* coded by Warchild ( warchild@gmx.org )
* based on the items-table introduced by anpera
* 6/2004
* Version 0.91a dt
* Letzte Ã„nderungen: 
* 
*/

if ($_GET['op']==""){
    output("`n`@Du schlenderst auf Deinem Weg an einem riesigen Baumstamm vorbei. Sprossen fÃ¼hren am Stamm wie eine Leiter direkt nach oben und eine `&weisse Kordel `@baumelt daneben. Du ziehst daran - irgendwo in dem Wipfel Ã¼ber Dir lÃ¤utet eine Glocke und eine Stimme ruft: `#\"Oh, Kundschaft! Klettere nur herauf!\"`@`nDu weisst mittlererweile, dass allerhand seltsame Gestalten im Wald hausen - willst Du hinaufklettern?");
    addnav("Zum Baumhaus klettern","forest.php?op=climbtree");
    addnav("Den Ort verlassen","forest.php?op=notree");
    $session['user']['specialinc']="jewelrymaker.php";
}elseif($_GET['op']=="climbtree"){
    $session['user']['specialinc']="jewelrymaker.php";
    output("`@Sprosse fÃ¼r Sprosse erklimmst Du den Baum und stehst bald auf einer Art Plattform, wo Dich ein hagerer `2Elf`@ - der ein braunes Gewand trÃ¤gt und seine `6goldblonden Haare`@ zu einem Pferdeschwanz nach hinten gebunden hat - begrÃ¼ÃŸt.`n`#\"Willkommen in `!Feinfingers`# - meinem - Hause! Meine Profession ist die SchÃ¶nheit, mein Leben die Ã„sthetik! Ich kann Dir aus Deinem `6Gold ein Kunstwerk`# schaffen, was seinesgleichen sucht. Du musst mir nur `^all Dein Gold `#geben und ich schaffe Dir etwas Unvergleichliches, etwas, das noch kein Auge je erblickt hat! MÃ¶chtest Du das?\"`n`@Du zÃ¶gerst. Dein ganzes Gold?");
    addnav("Alles Gold hergeben!", "forest.php?op=givegold");
    addnav("Nix is! Ich geh!", "forest.php?op=noway");
}elseif($_GET['op']=="givegold"){
    // User hat schon ein "Kunstwerk" ?
    $sql = "SELECT * FROM items WHERE owner='".$session['user']['acctid']."' AND class='Schmuck' AND name='Elfenkunst'";
    $result = db_query($sql);
    if (db_num_rows($result) >0){ // User hat schon Schmuck
        $session['user']['specialinc']="jewelrymaker.php";
        output("`@Der Elf mustert Dich mit moosgrÃ¼nen Augen durchdringend.`n`#\"Hm... ich hab doch fÃ¼r Dich schon ein unsterbliches Kunstwerk geschaffen! So etwas kann ich nicht zweimal tun! Ich muss Dich bitten zu gehen!\"");
        addnav("Schade!","forest.php?op=noway");
    }else{        
        if ($session['user']['gold'] > 0){
            $session['user']['specialinc']="jewelrymaker.php";
            output("`@Der Elf nimmt all Dein Gold und spricht einen Zauber darÃ¼ber. Es verwandelt sich...`n`n`6in ein wunderschÃ¶nes `&Etwas `6was Du leider nicht identifizieren kannst. Aber schÃ¶n ist es. Irgendwie.`n`n`@Du nimmst das Gebilde und staunst eine Weile darÃ¼ber. Dann steckst Du es ein. Vielleicht gibt Dir ja ein HÃ¤ndler was dafÃ¼r...");
            // Goldwert randomisieren und Edelsteinwert randomisieren
            $goldvalue = e_rand(1, $session['user']['gold'] * 2);
            $gemvalue = e_rand(0,2);
            $sql = "INSERT INTO items (name,owner,class,gold,gems,description) VALUES ('Elfenkunst','".$session['user']['acctid']."','Schmuck','".$goldvalue."','".$gemvalue."','Ein wunderschÃ¶nes nutzloses Dings')";
            db_query($sql);
            //if (db_affected_rows('LINK')<=0){
            //    output("`\$Fehler`^: Dein Inventar konnte nicht aktualisiert werden! Bitte benachrichtige den Admin.");
            //}else{ // Alles ok, Gold auf 0 setzen
                $session['user']['gold'] = 0;
            //}
            addnav("Danke! Auf Wiedersehen!","forest.php?op=noway");
        }else{ // User pleite
            $session['user']['specialinc']="";
            output("`@Du willst dem Elfen gerade Deine Taschen ausleeren, da fÃ¤llt Dir auf, dass Du gar kein Gold mit hast! Da Dir das peinlich ist wartest Du, bis er sich umdreht, dann flÃ¼chtest Du in den Wald zurÃ¼ck...");
        }
    }
}elseif($_GET['op']=="noway"){
    $session['user']['specialinc']="";
    output("`@Du machst Dich wieder auf den Weg nach unten und verschwindest im GrÃ¼n des Waldes, diesen seltsamen Elfen hast Du bald vergessen...");  
}else{
    $session['user']['specialinc']="";
    output("`n`@Du hast keine Lust, mÃ¼hsam nach oben zu kraxeln. Was eine Zeitverschwendung! Du gehst lieber zum Monsterkillen zurÃ¼ck in den Wald...");
}
?>

