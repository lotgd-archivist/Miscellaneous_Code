
<?php
/*
 * Alternative zum Baum des Lebens
 * inkl. RP-Ort
 */
require_once('common.php');
$str_file = basename(__FILE__);
page_header('Im Sumpf');
addcommentary();
// Textfarben
define('SWAMPCOLORTEXT','`D');
define('SWAMPCOLORBOLD','`8');
define('SWAMPCOLORPICK','`P');
// end
$row = user_get_aei('swamppick');
if ($_GET['op'] == 'flower')
{
    $tout = SWAMPCOLORBOLD.'`c`bDie Sumpfblume`b`c`n'.SWAMPCOLORTEXT.'
             Zwischen einigen dornigen Büschen steht eine Blume, die eine unbeschreiblich leuchtende Farbe hat. Ein wenig seltsam fühlst du dich bei
             dem Anblick - aber traust du dich näher heran zu gehen?';
    addnav('Im Sumpf');
    if ($row['swamppick'] == 0) {addnav('S?Sumpfblume näher betrachten',$str_file.'?op=flower_pick');}
    addnav('Lieber sein lassen',$str_file);
}
elseif ($_GET['op'] == 'flower_pick')
{
    switch(e_rand(1,17))
    {
        case 1:
        case 2:
        case 3:
            $tout = SWAMPCOLORPICK.'Du fasst die Blume an und merkst ... gar nichts. Es ist halt doch nur eine Sumpfblume.';
        break;
        case 4:
        case 5:
            $tout = SWAMPCOLORPICK.'Beim Pflücken der Blume reißt du auch ihre Wurzeln heraus. Dabei kommt ein Edelstein zum Vorschein, den du natürlich sofort
                     einsteckst.';
            $session['user']['gems'] += 1;
        break;
        case 6:
            $tout = SWAMPCOLORPICK.'Du steckst dir die Blume hinters Haar. Damit siehst du so viel besser aus!';
            $session['user']['charm'] += 2;
        break;
        case 7:
        case 8:
            $tout = SWAMPCOLORPICK.'Als du die Blume anfasst, spürst du ein Kribbeln in den Beinen. Ein Segen? .. Igitt! Nur eine Ameise!';
        break;
        case 9:
            $tout = SWAMPCOLORPICK.'Du pflückst die Sumpfblume und nimmst sie mit in die Stadt. Der Händler zahlt dir dafür 200 Gold.';
            $session['user']['gold'] += 200;
        break;
        case 10:
            $tout = SWAMPCOLORPICK.'Die Blüten fühlen sich seltsam an... Du reißt sie ab, um sie näher zu betrachten. Sie entpuppen sich als zwei feine
                     Edelsteine. Juchu!';
            $session['user']['gems'] += 2;
        break;
        case 11:
            $tout = SWAMPCOLORPICK.'Als du näher an die Blume herantrittst, rutscht du aus und schlägst dir den Kopf auf. Alles wird schwarz um dich herum.`n
                     `4Du bist tot, schade.';
            $session['user']['alive'] = 0;
            $session['user']['hitpoints'] = 0;
            addnews($session['user']['name'].' ist beim Blumenpflücken ums Leben gekommen.');
            addnav('Och nö...','shades.php');
        break;
        case 12:
            $tout = SWAMPCOLORPICK.'Die Blume verdorrt, als du sie anfasst - und du gleich mit ihr.`n
                     `4Du bist tot.';
            $session['user']['alive'] = 0;
            $session['user']['hitpoints'] = 0;
            addnews($session['user']['name'].' ist beim Blumen pflücken ums Leben gekommen.');
            addnav('Och nö...','shades.php');
        break;
        case 13:
            $tout = SWAMPCOLORPICK.'Beim Pflücken der Blume reißt du auch ihre Wurzeln heraus. Dabei kommen 3 Edelsteine zum Vorschein, die du natürlich sofort
                     einsteckst.';
            $session['user']['gems'] += 3;
        break;
        case 14:
            $tout = SWAMPCOLORPICK.'Du pflückst die Sumpfblume und nimmst sie mit in die Stadt. Der Händler zahlt dir dafür 100 Gold.';
            $session['user']['gold'] += 100;
        break;
        case 15:
            $tout = SWAMPCOLORPICK.'Ein helles Leuchten umgibt zuerst die Blume und dann dich. Du bemerkst, wie dich zusätzliche Kampfkraft durchfließt.';
            $session['bufflist']['865'] = array("name"=>"`qSumpfblumen-Zauber",
                                                "rounds"=>10,
                                                "wearoff"=>"`qDer Segen der Sumpfblume lässt nach.",
                                                "defmod"=>1,
                                                "atkmod"=>1.5,
                                                "roundmsg"=>"`qDer Segen der Sumpfblume verleiht dir zusätzliche Kraft.",
                                                "activate"=>"offense");
        break;
        case 16:
            $tout = SWAMPCOLORPICK.'Ein zwielichtes Leuchten umgibt zuerst die Blume und dann dich. Du bemerkst, wie deine Kampfkraft plötzlich abrupt nachlässt.';
            $session['bufflist']['865'] = array("name"=>"`ôSumpfblumen-Fluch",
                                                "rounds"=>10,
                                                "wearoff"=>"`ôDeine Kampfkraft normalisiert sich wieder.",
                                                "defmod"=>1,
                                                "atkmod"=>0.5,
                                                "roundmsg"=>"`ôDer Fluch der Sumpfblume stiehlt dir deine Kampfkraft.",
                                                "activate"=>"offense");
        break;
        case 17:
            $tout = SWAMPCOLORPICK.'Es passiert nichts, aber dafür siehst du eine Flasche Ale am Boden liegen, die du gleich hinunterstürzt.';
            $session['user']['drunkenness']=66;
            $session['user']['turns'] -= 10;
        break;
    }
    user_set_aei(array('swamppick'=>1));
    if ($session['user']['alive']==1)
    {
        addnav('Zurück',$str_file);
    }
}
else if ($_GET['op'] == 'swamp_inside')
{
                page_header('Versteckt im Sumpf');
                $tout = SWAMPCOLORBOLD."`c`bAbseits des Weges`b`c`n".SWAMPCOLORTEXT."
                        Weit ragen die knorrigen Zweige zahlreicher Bäume über tiefe Tümpel, listigen Schlick und steinerne Pfade, 
                        die zu erkennen es nicht nur ein wachsames Auge, sondern auch ausreichend Erfahrung benötigt. Hier und da 
                        sind die scheinbar uralten Pflanzen von dichten, saftigen Blätterdächern gekrönt, während gelegentlich sogar 
                        ihre direkten Nachbarn kahl und tot wirken. Die buntesten Schmetterlinge, die diese Breitengrade zu bieten haben, 
                        flattern aufgeregt von Ast zu Ast. Helle Spinnennetz glitzern in irrlichternen Sonnenstrahlen. Neugierige Hasennäschen 
                        schnuppern an einer glasklaren Pfütze - Inmitten jener steigen blank genagte Knochen auf, die sich tief im Schlamm verankert haben.`n`n";
                $com_section = 'swamp_inside';
                addnav('Zurück');
                addnav('Zurück auf sichere Pfade','swamp.php');
}
else
{
    $tout = SWAMPCOLORBOLD.'`c`bDer Sumpf`b`c`n'.SWAMPCOLORTEXT.'
             Du spazierst durch den Wald und bemerkst, dass immer weniger Bäume am Wegesrand wachsen, dafür aber immer mehr Büsche. Die Äste bleiben
             immer wieder an deiner Kleidung hängen, und als du wieder einmal deine Hose von einem hängen gebliebenem Zweig befreien musst, bemerkst
             du, dass du fast knöcheltief im Schlamm stehst. Während du dich jetzt umsiehst, erkennst du, dass du dich schon fast mitten im Sumpf
             befindest. Überall steht das Wasser - und dir wird klar, dass du aufpassen solltest, wo du hintrittst...`n`n';
    $com_section = 'swamp';
    addnav('Im Sumpf');
    addnav('Abseits des Weges','swamp.php?op=swamp_inside');

    addnav('S?Zur Sumpfblume',$str_file.'?op=flower');
    addnav('Zurück');
    addnav('W?Zurück in den Wald','woods.php');
    addnav('S?Zurück in die Stadt','village.php');
}
// Text & Chatfeld
output($tout,true);
if (isset($com_section)) {viewcommentary($com_section,'Sagen',15,'sagt');}
// footer
page_footer();
?>

