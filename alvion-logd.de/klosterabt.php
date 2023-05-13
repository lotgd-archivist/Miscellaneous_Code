
<?php
// Idee und Umsetzung
// Morpheus aka Apollon
// 2006 für Morpheus.Lotgd(LoGD 0.9.7 +jt ext (GER) 3)
// Mail to Morpheus@magic.ms or Apollon@magic.ms
// gewidmet meiner über alles geliebten Blume
require_once "common.php";
require_once "func/titles.php";
page_header("Das Büro des Abts");
$session['user']['standort']="Das Kloster";
if($_GET['op']==""){
    output("`7`b`cDas Büro des Sekretärs`c`b");
    output("`3`n`nDu gehst durch die rechte Tür neben der Treppe in das Büro des Abtes und gelangst in einen Vorraum, in dem sonst der Sekretär des Abtes sitzt, der im Moment nicht anwesend ist.");
    output("`3Der Raum besitzt kein Fenster und wird durch Fackeln an den Wänden erhellt und Kerzen, die auf dem Schreibtisch des Sekretärs stehen, auf dem du auch allerlei Pergamente sehen kannst, sowie eine Spendendose.");
    output("`3An der rechten Wand steht ein Regal mit vielen Büchern, an der Linken eines mit vielen Fächern, in die Pergamente einsortiert sind.");
    output("`3An der Wand gegenüber liegt die Tür, die zum Büro des Abtes führt. Du schließt die Tür hinter dir und überlegst, was du machen willst.");
    addnav("Die Papiere auf dem Schreibtisch ansehen","klosterabt.php?op=tisch");
    addnav("Die Spendendose leeren","klosterabt.php?op=dose");
    addnav("Weiter zum Büro des Abts","klosterabt.php?op=tuer");
    addnav("Zurück zur Halle","klosterhaus.php?op=halle");
}
if($_GET['op']=="buero"){
    output("`7`b`cDas Büro des Sekretärs`c`b");
    output("`3Du stehst wieder mitten im Raum und überlegst, was du machen willst.");
    addnav("Die Papiere auf dem Schreibtisch ansehen","klosterabt.php?op=tisch");
    addnav("Die Spendendose leeren","klosterabt.php?op=dose");
    addnav("Weiter zum Büro des Abts","klosterabt.php?op=tuer");
    addnav("Zurück zur Halle","klosterhaus.php?op=halle");
}
if($_GET['op']=="tisch"){
    output("`3Du nutzt die Gelegenheit, stellst dich vor den Schreibtisch und durchstöberst die Papiere, die auf dem Schreibtisch des Sekretärs liegen.");
        switch(e_rand(1,5)){
            case 1:
            case 2:
            case 3:
            output("`3Du findest Berichte über die Geschäfte der Mönche und die Lage in Alvion, insgesammt nichts Aufregendes.");
            addnav("Zurück zum Büro","klosterabt.php?op=buero");
            break;
            case 4:
            output("`3Du findest Berichte über die Geschäfte der Mönche und die Lage in Alvion, insgesammt nichts Aufregendes.");
            output("`3Plötzlich entdeckst du die Rezeptur für eine Creme, um die Haut zu pflegen.");
            output("`3Schnell schreibst du sie ab, denn die Anwendung der Creme wird dich noch schöner machen, was dir einen Charmepunkt bringt.");
            $session[user][charm]+=1;
            addnav("Zurück zum Büro","klosterabt.php?op=buero");
            break;
            case 5:
            output("`3Du findest Berichte über die Geschäfte der Mönche und die Lage in Alvion, insgesammt nichts Aufregendes.");
            output("`3Plötzlich öffnet sich die Tür und der Sekretär kommt herein");
            output("`3Schnell läßt du alles fallen und hoffst, er hat nicht bemerkt, dass du geschnüffelt hast.");
            output("`3Du hast Glück, er merkt es nicht, aber vor lauter Scham verlierst du 2 Charmpunkte.");
            $session[user][charm]-=2;
            addnav("Zum Büro des Abts","klosterabt.php?op=tuer");
            break;
        }
}
if($_GET['op']=="dose"){
    output("`3Du gehst zum Schreibtisch, hebst die Spendendose an und öffnest sie.");
    output("`3Wenn sie schon ihre Spendendose alleine lassen, kannst du dich auch ein wenig daran bedienen, selbst Schuld, die Mönche.");
        switch(e_rand(1,12)){
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
            case 6:
            case 7:
            case 8:
            case 9:
            case 10:
            case 11:
                output("`3Grade willst du das Gold herausnehmen, als du ein Geräusch an der Tür zum Büro des Abtes hörst.");
                output("`3Du wirst ganz starr vor Schreck, als sich die Klinke langsam nach unten bewegt und schaffst es nicht, die Dose zurück zu stellen.");
                output("`3DIe Tür öffnet sich und heraus tritt der Sekretär, der dich auf frischer Tat beim versuchten Diebstahl ertappt!!");
                output("`3Er ruft nach der Klosterwache, die dich ergreift und aus dem Kloster wirft, dein Gold wird einbehalten und du erhälst den Titel Klosterräuber.");
//                $session['user']['attack']-=$session['user']['weapondmg'];
                $session['user']['gold']=0;
//                $session['user']['weapon']=Fists;
//                $session['user']['weapondmg']=0;
//                $session['user']['weaponvalue']=0;
//                $session['user']['defence']-=$session['user']['armordef'];
//                $session['user']['armor']=TShirt;
//                $session['user']['armordef']=0;
//                    $session['user']['armorvalue']=0;
                $name=$session['user']['name'];
                addnews("$name `7wollte im Kloster stehlen und hat nun den Titel Klosterräuber!");
                $newtitle="Klosterräuber";
                if(strpos($session['user']['name'],"Klosterräuber")===FALSE){
                    $regname = $session['user']['name'];
                    if($session['user']['ctitle']=='') {
                        $neu=str_replace($session['user']['title'],'',$session['user']['name']);
                        if($session['user']['rp_only']==0) $session['user']['name'] = $newtitle.$neu;
                        else $session['user']['name'] = $newtitle." ".$neu;
                        $session['user']['title'] = $newtitle;
                    } else {
                        $session['user']['name'] = $newtitle." ".$session['user']['name'];
                        $session['user']['title'] = $newtitle;
                    }
                }

                addnav("Weiter","village.php");
                break;
            case 12:
                output("`3Grade willst du das Gold herausnehmen, als du ein Geräusch an der Tür zum Büro des Abtes hörst.");
                output("`3Du wirst ganz starr vor Schreck, als sich die Klinke langsam nach unten bewegt.");
                output("`3Schnell stellst du die Dose wieder auf ihren Platz und siehst zu, dass du zurück in die Halle kommst.");
                output("`3Dein Herz schlägt bis zum Hals und dir wird bewusst, was du da machen wolltest! Vor lauter Scham verlierst Du 5 Charmpunkte.");
                $session[user][charm]-=5;
                addnav("Zurück zur Halle","klosterhaus.php?op=halle");
                break;
        }
}
if($_GET['op']=="tuer"){
    output("`7`b`cDas Büro des Abtes`c`b");
    output("`3Du klopfst höflich an die Tür des Büros und hörst eine Stimme, die dich herein bittet.");
    output("`3Als du den Raum betrittst, siehst du den Abt hinter seinem Schreibtisch sitzen und in Papieren lesen.");
    output("`3Sein Büro ist hell mit großen Fenstern, an den Seiten stehen große Regale mit vielen Büchern und vor seinem Schreibtisch stehen 2 Stühle.`n");
    output("`3Der Abt blickt von seinen Papieren auf, begrüßt dich freundlich, bietet Dir einen Platz an und fragt dich, was er für dich tun kann.");
    addnav("Auskunft über");
    addnav("die Tempel","klosterabt.php?op=atempel");
    addnav("die Gärten","klosterabt.php?op=agarten");
    addnav("den Glockenturm","klosterabt.php?op=aturm");
    addnav("die Schenke","klosterabt.php?op=aschenke");
    addnav("die Schmiede","klosterabt.php?op=aschmiede");
    addnav("die Schlafsäle","klosterabt.php?op=asaal");
    if (substr($session['user']['name'],0,13)=='Klosterräuber'){
        addnav("Um Gnade bitten","klosterabt.php?op=gnade");
    }
    addnav("Zurück zur Halle","klosterabt.php?op=buero1");
}
if($_GET['op']=="abt"){
    output("`2Nun, kann ich dir sonst noch irgendwie helfen?.");
    addnav("Auskunft über");
    addnav("die Tempel","klosterabt.php?op=atempel");
    addnav("die Gärten","klosterabt.php?op=agarten");
    addnav("den Glockenturm","klosterabt.php?op=aturm");
    addnav("die Schenke","klosterabt.php?op=aschenke");
    addnav("die Schmiede","klosterabt.php?op=aschmiede");
    addnav("die Schlafsäle","klosterabt.php?op=asaal");
    if (substr($session['user']['name'],0,13)=='Klosterräuber'){
        addnav("Um Gnade bitten","klosterabt.php?op=gnade");
    }
    addnav("Zurück zur Halle","klosterabt.php?op=buero1");
}
if($_GET['op']=="atempel"){
    output("`2In den Tempeln kannst du den Göttern huldigen und Opfer bringen, die natürlich von den Göttern belohnt werden, denn sie sind gute und gütige Götter.");
    output("`2Jeder Gott hat seinen eigenen Tempel, der nach einer anderen Himmelsrichtung ausgerichtet ist.");
    addnav("Weiter","klosterabt.php?op=abt");
}
if($_GET['op']=="agarten"){
    output("`2In den Gärten bauen wir Obst an, das allerdings nur für Bewohner des Klosters zum Verzehr gedacht ist.");
    output("`2Die Bäume werden von den Göttern höchstselbst bewacht, und wenn sich jemand, der kein Bewohner des Klosters ist, daran vergreift, so wird er von den Göttern dafür bestraft.");
    addnav("Weiter","klosterabt.php?op=abt");
}
if($_GET['op']=="aturm"){
    output("`2Vom Turm aus haben wir einen wundervollen Blick auf Alvion, den umgebenden Wald und die Berge, an schönen Tagen kann man sogar bis nach Eythgim blicken.");
    output("`2Doch der Turm ist auch ein magischer Ort: Die Legende besagt, wenn ein Krieger, der kurz davor steht, den `@GRÜNEN DRACHEN `2aufzusuchen den Turm betritt, so öffnet sich ihm eine magische Kammer, in der er göttliche Hilfe findet.");
    addnav("Weiter","klosterabt.php?op=abt");
}
if($_GET['op']=="aschenke"){
    output("`2In der Schenke kannst du deinen Hunger und Durst stillen, wir betreiben sie selbst.");
    output("`2Du findest dort die besten Speisen und Getränke weit und breit, sei es unser selbst gebrautes Bier, unser Wein oder das Wasser aus unserem Brunnen.");
    addnav("Weiter","klosterabt.php?op=abt");
}
if($_GET['op']=="aschmiede"){
    output("`2Oh, in unserer Schmiede entstehen die besten Waffen weit und breit, wir liefern sie in alle Läden unseres Landes aus.");
    output("`2Algrim, der Schmied, versteht sein Handwerk auf das Feinste und kann aus jeder Waffe und Rüstung noch etwas besonderes machen. Du solltest ihn einmal besuchen.");
    addnav("Weiter","klosterabt.php?op=abt");
}
if($_GET['op']=="asaal"){
    $sql = "SELECT * FROM items WHERE owner=".$session[user][acctid]." AND class='Schmuck' AND name='Zugangsmünze'";
    $result = db_query($sql);
    if (db_num_rows($result)>0){
        output("`2Oh, du hast doch schon eine Zugangsmünze gekauft, wie aus meinen Unterlagen hervorgeht, ich wüsste nicht, was ich dir noch sagen könnte.");
        output("`2Dein Bett wird in deinem Raum immer für dich bereit stehen, damit du in Ruhe und Frieden dort schlummern kannst.");
        addnav("Weiter","klosterabt.php?op=abt");
        }else{
        output("`2Nun, in unseren Schlafsälen ruhen wir und so mancher Gast uns aus, bewacht von den Göttern und der Klosterwache.");
        output("`2 Wenn du willst, kannst auch du hier bei uns jederzeit übernachten, ohne dich fürchten zu müssen, überfallen und beraubt zu werden. Hättest du daran Interesse?");
    addnav("Ja, gerne","klosterabt.php?op=muenze");
    addnav("Nein, vielen Dank","klosterabt.php?op=abt");
    }
}
if($_GET['op']=="muenze"){
    output("`2Um hier übernachtenzu können, benötigst du eine Zugangsmünze.");
    output("`2Mit dieser Münze wirst du zu einem Bewohner des Klosters, allerdings ohne weitere Pflichten, sie kostet dich nur 30 Edelsteine. Möchtest du eine erwerben?.");
    addnav("Ja, gerne","klosterabt.php?op=muka");
    addnav("Nein, vielen Dank","klosterabt.php?op=abt");
}
if($_GET['op']=="muka"){
    if ($session[user][gems] >29){
            $session[user][gems]-=30;
                 output(" `3Du greifst zu deinem Beutel und überreichst dem Abt `@30 Edelsteine`3, die er dankend entgegen nimmt und sie in einer Truhe verstaut, die hinter seinem Schreibtisch steht.");
                 output(" `3Dabei nimmt er auch eine kleine Münze aus der Truhe, die er dir aushändigt mit den Worten, gut auf sie zu achten, da sie sehr wertvoll sei und begrüßt dich als neuen Bewohner des Klosters von Alvion.");
                 output(" `3Du nimmst die Münze, verstaust sie sorgfältig und dankst ihm.`n");
        $sql = "INSERT INTO items (name,owner,class,gems,description) VALUES ('Zugangsmünze',".$session[user][acctid].",'Münze',10,'Sie weißt Dich als Bewohner des Klosters aus')";
        db_query($sql);
    }else{
        output("`3Der Abt schüttelt lächelnd den Kopf:`2 Ich fürchte, deine Edelsteine werden nicht ausreichen, die Münze zu kaufen.");
        }
         addnav("Zurück","klosterabt.php?op=abt");
}
if($_GET['op']=="busse"){
    if($session['user']['gems']>=($session['user']['rp_only']?'5':'25')) {
        output("`2Willst du dieses Opfer wirklich bringen um deinen Titel rein zu waschen?");
        addnav("Ja, ".($session['user']['rp_only']?'5':'25')." Edelsteine bezahlen","klosterabt.php?op=busse2");
    } else {
        output("`2Du hast nicht genügend Edelsteine bei dir!");
        addnav("Zurück","klosterabt.php?op=abt");
    }
}if($_GET['op']=="busse2"){
    $cost=($session['user']['rp_only']?5:25);
    $session['user']['gems']-=$cost;
    output("`2Du zahlst die ".($session['user']['rp_only']?'5':'25')." Edelsteine um deinen Titel rein zu waschen");
    $session['user']['name']=str_replace('Klosterräuber ','',$session['user']['name']);
    if($session['user']['ctitle']!='' || $session['user']['rp_only']>0){
        $session['user']['title']='';
    } elseif ($session['user']['title']=='Klosterräuber') {
        $newtitle=$titles[$session[user][dragonkills]][$session[user][sex]];
        if ($newtitle==""){
            $newtitle = ($session[user][sex]?"Ikone":"Ikone");
        }
        $session['user']['title']=$newtitle;
        $session['user']['name']=$newtitle." ".$session['user']['name'];
    }
    addnav("Zurück","klosterabt.php?op=abt");
}
if($_GET['op']=="gnade"){
    output("`2Du hast zur Strafe diesen Titel erhalten und er wird dich begleiten, bis du den Göttern ein entsprechendes Opfer gebracht hast, das ".($session['user']['rp_only']?'5':'25')." Edelsteine beträgt.");
//    output("`2Wenn Du genug Gold hast, so sende ihnen eine offizielle Taube, daß Du Buße tun willst.");
    output("`2willst du dieses Opfer  bringen?");
    addnav("Ja, ich will das Opfer bringen","klosterabt.php?op=busse");
    addnav("Nein","klosterabt.php?op=abt");
}
if($_GET['op']=="buero1"){
    output("`3Da nun deine Neugierde gestillt ist, machst du dich auf den Weg zurück in die Halle, wobei du zuerst wieder in das Büro des Sekretärs musst, der nun an seinem Platz sitzt.");
    output("`3 Du grüßt ihn freundlich und verlässt sein Büro in Richtung Klostervorraum.");
    addnav("Weiter","klosterhaus.php?op=halle");
}
page_footer();
?>

