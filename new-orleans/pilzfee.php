
<?php

/*********************************
*                                *
*    Die Pilzfee (pilzfee.php)   *
*        Idee: Veskara           *
*    Programmierung: Linus       *
*   für alvion-logd.de/logd      *
*      im Dezember 2007          *
*                                *
**********************************/

/*
Bitte die Einbauhinweise in der pilzsuche.php beachten!
*/

require_once "common.php";
global $session;
page_header("Die Pilzfee");

$sql="SELECT * FROM `pilze` WHERE `acctid`='".$session['user']['acctid']."'";
$result=db_query($sql) or die(db_error(LINK));
$row=db_fetch_assoc($result);

switch($_GET['op']) {
    case "":
        // Hier der Begrüssungstext
        $out="Du pflückst dir eine der lecker aussehenden Früchte von einem der vielen Obstbäume. Doch kaum willst du einen Bissen probieren, zerplatzt die Frucht auf einmal und zerfällt in glitzernden Staub. ";
        $out.="Nachdem du den ersten Schrecken überwunden hast, öffnest du deine Augen und siehst eine kleine Fee aufgeregt umherschwirren Hast du Pilze? Hast du Pilze für mich? fragt sie mit ihrer glockenhellen Stimme. `n`n";
//        $out.="`c<img src='./images/fee3.jpg'>`c`n`n";

        if($row['hasel']>0 || $row['gift']>0 || $row['feigen']>0 || $row['hasen']>0 || $row['baum']>0 || $row['insekt']>0 || $row['leucht']>0 || $row['alvion']>0 || $row['goetter']>0 || $row['gold']>0){
            $out.="Sie flattert um dich herum herum Jaaa, du hast Pilze für mich.  ruft sie aufgeregt Gib mir deine Pilze und ich werde dich reich belohnen.`n";
            addnav("Pilze geben","pilzfee.php?op=pilze");
        } else {
            $out.="Sie flattert um dich herum und ihr Gesicht überzieht sich mit einem traurigen Schleier Du hast keine Pilze für mich. Ich kann nichts für dich tun.`n";
            $out.="Ihre kleinen Flügelchen tragen sie hinauf zur Krone des Obstbaumes und sie verschwindet aus deinen Augen";
        }
        break;

    case "pilze":
        $pilze = array(
            1=>array("Haselröhrling","hasel"),
            2=>array("Giftmorchel","gift"),
            3=>array("Feigenfiesling","feigen"),
            4=>array("Hasenschwämmchen","hasen"),
            5=>array("Baumfungi","baum"),
            6=>array("Insektentäubling","insekt"),
            7=>array("Leuchtender Nachtpilz","leucht"),
            8=>array("Alvionsteinpilz","alvion"),
            9=>array("Götterwulstling","goetter"),
            10=>array("Goldener Pilz","gold")
        );

        $out="Du zeigst der Fee deine gesammelte Beute und kannst gar nicht so schnell schauen, wie die Fee schon um die Pilze herum schwirrt Gibst du sie mir? Gibst du sie mir? bittet sie überschwinglich und schaut dich dabei mit großen Augen an.  Nun ist es an dir zu entscheiden von welchen Pilzen du dich trennen möchtest.`n";

        switch($_GET['act']){
            case "verkauf":
                $out="";
                $alle=0;
                $kristalle=0;
                $sql="UPDATE `pilze` SET ";
                for($i=1;$i<=10;$i++){
                    if((int)$_POST[$pilze[$i]['1']]>(int)$row[$pilze[$i]['1']]) $_POST[$pilze[$i]['1']]=$row[$pilze[$i]['1']];
                    if((int)$_POST[$pilze[$i]['1']]>0){
                        $out.="Du gibst der Fee ".$_POST[$pilze[$i]['1']]." ".$pilze[$i]['0']."`n";
                        $kristalle+=$_POST[$pilze[$i]['1']]*$i*2;
                        $alle+=$_POST[$pilze[$i]['1']];
                        $sql.="`".$pilze[$i]['1']."`=`".$pilze[$i]['1']."`-".((int)$_POST[$pilze[$i]['1']]).", ";
                    }
                }
                $sql=substr($sql,0,strlen($sql)-2);
                $sql.=" WHERE `acctid`='".$session['user']['acctid']."'";
                if($alle>0){
                    db_query($sql) or die(db_error(LINK));
                    $session['user']['kristalle']+=$kristalle;
                    $out.="`n`n`n`nDu hast alle Pilze der Fee geben und sie gibt dir dafür $kristalle funkelnde Edelkristalle.`n";
                }else {
                    $out.="`n`nDu kannst keine Pilze eintauschen die du nicht besitzt`n";
                }
                break;

            default:
                          $out.='<table><form action="pilzfee.php?op=pilze&act=verkauf" method="POST">';
                for($i=1;$i<=10;$i++){
                                $out.='<tr><td>'.$pilze[$i]['0'].' '
                            .'</td><td><input name="'.$pilze[$i]['1'].'" value="'.(int)$row[$pilze[$i]['1']].'" size="3" maxlength="5"</td><td> max. '.(int)$row[$pilze[$i]['1']].'</td> '
                            .'</tr>';
                        }
                            $out.='<tr><td><input type="submit" class="button" value="Pilze der Fee geben"></td></tr></form></table>';
                addnav("","pilzfee.php?op=pilze&act=verkauf");
                break;

        }

}
output($out,true);

addnav("Zurück");
addnav("Zum Wald","forest.php");

page_footer();

?>

