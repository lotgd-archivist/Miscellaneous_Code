
<?php
// idea of gargamel @ www.rabenthal.de
if (!isset($session)) exit();

if ($_GET[op]==""){
    output("Inmitten einer kleinen Buchengruppe steht ein altes `QEichenfass`0. Genau
    solche Fässer sind es, in denen Waren transportiert werden. Vielleicht ist dies
    hier einem Kutscher herunter gefallen? Dann lohnt doch ein genauerer Blick...`n`n`0");
    addnav("Fass untersuchen","berge.php?op=examine");
    addnav("Weitergehen","berge.php?op=cont");
    $session[user][specialinc] = "barrel.php";
}
else if ($_GET[op]=="examine"){   //
    output("Du trittst ans Fass heran und nimmst den morschen Deckel ab.`n`n`0");
    $was = e_rand(1,7);
    switch ( $was ) {
        case 1:
        output("Im Fass funkelt ein `^Edelstein`0, den du gleich einsteckst.`n
        Pfeifend gehst du weiter...`n`0");
        $session[user][gems]++;
        break;
        case 2: case 3:
        output("Das Fass ist fast randvoll mit Salz gefüllt. Du wühlst mit deinen
        Händen drin, um nach etwas Wertvollem zu suchen. Leider findest du Nichts.
        Aber die Haut an deinen Händen ist gerötet. Das Salz muss sie verätzt haben.`n`n
        `$ Du kannst deine Waffe nicht mehr so sicher halten, was dich einige Runden
        im Kampf behindert.`0");
        $session[bufflist]['salzfass'] = array("name"=>"`4Salzbrand",
                                        "rounds"=>8,
                                        "wearoff"=>"Deine Hände sind verheilt.",
                                        "defmod"=>1,
                                        "atkmod"=>0.65,
                                        "roundmsg"=>"Deine Hände spüren Salz und keine Waffe.",
                                        "activate"=>"offense");
        break;
        case 4:
        output("Du schaust hinein, siehst aber schlicht gar nichts.`n`n`QEin bisschen
        enttäuscht gehst du weiter.`0");
        break;
        case 5: case 6:
        output("Das Fass ist bis oben voll mit Wein. Sofort probierst du einen
        Schluck. Der Wein ist etwas verwässert. Aber definitiv ist das ein einheimischer Wein.`n`n
        Als Bewohner von Alvion hat er auf dich eine ganz besondere Wirkung.`n`n`QDu
        nimmst den Weingeist wahr, der dich einige Runden unterstützt.`0");
        $session[bufflist]['salzfass'] = array("name"=>"`4Weingeist",
                                        "rounds"=>12,
                                        "wearoff"=>"Der Weingeist verflüchtigt sich.",
                                        "defmod"=>1.1,
                                        "atkmod"=>1.25,
                                        "roundmsg"=>"Der Weingeist ist dir wohl gesonnen.",
                                        "activate"=>"offense");
        break;
        case 7:
        output("Du schaust hinein, kannst aber nichts erkennen. Das Fass scheint
        tiefer zu sein, als es den Anschein hat. Du beugst dich über den Rand...`n`n
        Leider verlierst du das Gleichgewicht und stürzt kopfüber in das Fass.`n
        `$ Am Boden stößt du dir gewaltig den Kopf und ertrinkst in dem Regenwasser, das
        sich dort angesammelt hat.`n`n
        Du kannst morgen weiter kämpfen.`0");
        $session[user][alive]=false;
        $session[user][gold]=0;
        $session[user][hitpoints]=0;
        addnews("`^".$session[user][name]."`2 ist in einem Fass ertrunken. `@In einem Fass!");
        addnav("Tägliche News","news.php");
        break;
    }
    $session[user][specialinc]="";
}
else if ($_GET[op]=="cont"){   // einfach weitergehen
    output("`QDu lässt das Fass stehen und gehst lieber weiter. Ale ist da wohl eh nicht drin...`0");
    $session[user][specialinc]="";
}
?>

