
<?
// idea of gargamel @ www.rabenthal.de
if (!isset($session)) exit();

if ($HTTP_GET_VARS[op]==""){
    output("`nIn Mitten einer kleinen Buchengruppe steht ein altes `QEichenfass`0. Genau
    solche Fässer sind es, in denen Waren transportiert werden. Vielleicht ist dies
    hier einem Kutscher heruntergefallen? Dann lohnt doch ein genauerer Blick...`n`n`0");
    addnav("Fass untersuchen","forest.php?op=examine");
    addnav("weitergehen","forest.php?op=cont");
    $session[user][specialinc] = "barrel.php";
}
else if ($HTTP_GET_VARS[op]=="examine"){   //
    output("`nDu trittst ans Fass heran und nimmst den morschen Deckel ab.`n`n`0");
    $was = e_rand(1,7);
    switch ( $was ) {
        case 1:
        output("Im Fass funkelt ein `^Edelstein`0, den Du gleich einsteckst.`n
        Pfeiffend gehst Du weiter...`n`0");
        $session[user][gems]++;
        break;
        case 2: case 3:
        output("Das Fass ist fast randvoll mit Salz gefüllt. Du wühlst mit Deinen
        Händen drin, um nach etwas wertvollem zu suchen. Leider findest Du nichts.
        Aber die Haut an Deinen Händen ist gerötet. Das Salz muss sie verätzt haben.`n`n
        `$ Du kannst Deine Waffe nicht mehr so sicher halten, was Dich einige Runden
        im Kampf behindert.`0");
        $session[bufflist]['salzfass'] = array("name"=>"`4Salzbrand",
                                        "rounds"=>8,
                                        "wearoff"=>"Deine Hände sind verheilt.",
                                        "defmod"=>1,
                                        "atkmod"=>0.65,
                                        "roundmsg"=>"Deine Hände spüren Salz und keine Waffe.",
                                        "activate"=>"offense");
        break;
        case 4:
        output("Du schaust hinein, siehst aber schlicht gar nichts. `QEin bischen
        enttäuscht gehst Du weiter.`0");
        break;
        case 5: case 6:
        output("Das Fass ist bis oben voll mit Wein. Sofort probierst Du einen
        Schluck. Der Wein ist etwas verwässert. Aber definitiv ein 1001er Rabenthaler Kabinett.`n`n
        Als Bewohner von Rabenthal hat er auf Dich eine ganz besondere Wirkung. `@Du
        nimmst den Weingeist wahr, der Dich einige Runden unterstützt.`0");
        $session[bufflist]['salzfass'] = array("name"=>"`4Weingeist",
                                        "rounds"=>12,
                                        "wearoff"=>"Der Weingeist verflüchtigt sich.",
                                        "defmod"=>1.1,
                                        "atkmod"=>1.25,
                                        "roundmsg"=>"Der Weingeist ist Dir wohl gesonnen.",
                                        "activate"=>"offense");
        break;
        case 7:
        output("Du schaust hinein, kannst aber nichts erkennen. Das Fass scheint
        tiefer zu sein, als es den Anschein hat. Du beugst Dich über den Rand...`n`n
        Dann verlierst Du leider das Gleichgewicht und stürzt kopfüber in das Fass.`n
        `$ Am Boden stößt Du Dir gewaltig den Kopf und ertrinkst in dem Regenwasser, dass
        sich dort angesammelt hat.`n`n
        Du kannst morgen weiter kämpfen.`0");
        $session[user][alive]=false;
        $session[user][gold]=0;
        $session[user][gems]=0;
        $session[user][hitpoints]=0;
        addnews("`^".$session[user][name]."`2 ist in einem Fass ertrunken. `@In einem Fass!");
        addnav("Tägliche News","news.php");
        break;
    }
    $session[user][specialinc]="";
}
else if ($HTTP_GET_VARS[op]=="cont"){   // einfach weitergehen
    output("`n`QDu lässt das Faß stehen und gehst lieber weiter. Ale ist da wohl eh nicht drin...`0");
    $session[user][specialinc]="";
}
?>


