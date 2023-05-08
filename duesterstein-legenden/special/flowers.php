
<?
// idea of gargamel @ www.rabenthal.de
if (!isset($session)) exit();

if ($HTTP_GET_VARS[op]==""){
    output("`nAn einem kleinen Bach blühen einige wunderschöne Waldblumen. Sofort
    trittst Du näher, um Dich des Anblicks zu erfreuen.`n
    Ob Du Dir eine Blume pflücken solltest?`0");
    //abschluss intro
    addnav("pflücken","forest.php?op=pick");
    addnav("weitergehen","forest.php?op=cont");
    $session[user][specialinc] = "flowers.php";
}
else if ($HTTP_GET_VARS[op]=="pick"){   // pflücken
    if(e_rand(1,100)>50){
        output("`nDu pflückst Dir eine `8blaue`0 Blume und riechst verträumt dran.`n`n
        Plötzlich wirst du müde...`n
        `8Du schläfst eine Runde.`0");
        $session[user][turns]--;
    }
    else {
        output("`nDu pflückst Dir eine `^gelbe`0 Blume und riechst verträumt dran.`n`n
        Ein süsser Duft steigt Dir in die Nase und `^Du gehst voller Energie in einen
        extra Kampf.`0");
        $session[user][turns]++;
    }
    $session[user][specialinc]="";
}
else if ($HTTP_GET_VARS[op]=="cont"){   // einfach weitergehen
    output("`n`5Du schaust nochmal und gehst dann weiter. Blumen pflücken ist eh
    vom Ranger verboten...");
    $session[user][specialinc]="";
}
?>


