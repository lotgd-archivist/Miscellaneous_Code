
<?php

define('DISKUSCOLORTEXT','`w');
define('OOCCOLORTEXT','`9');
define('OOCCOLORCOMMENTARY','`e');

require_once('common.php');

checkday();
$int_diskusmarker = getsetting('diskusmarker',0);

page_header('Diskussionsraum');

/*if($_GET['act'] == 'diskusmarker') {
    $int_diskusmarker = ((int)$int_diskusmarker ? '0' : '1');
    savesetting('diskusmarker',$int_diskusmarker);
    redirect('diskus.php'.($_GET['op'] == 'list' ? '?op=list' : ''));
} else*/if($_GET['act'] == 'rules') {
    output(DISKUSCOLORTEXT."`c`bVerhaltensregeln für den Diskussionsraum`b`c`n
            ".get_extended_text('diskus_rules')."`n");
    addnav('Zurück');
    addnav('Zum Diskussionsraum','diskus.php'.($_GET['op'] == 'list' ? '?op=list' : ''));
} else {
    output(DISKUSCOLORTEXT."Der Diskussionsraum liegt vor dir!`n
            Hier bekommt das Volk Gehör und die Admins hören sich Wünsche, Anregungen und Beschwerden an.`n`n");
    addcommentary(false);
    viewcommentary("Diskussionsraum","Sagen",30,"sagt");

    // Nur für Admins:
    if(su_check(SU_RIGHT_GROTTO)) {
        //addnav('SU');
        //addnav('Hinweis in Einwohnerliste '.((int)$int_diskusmarker ? '`@an' : '`Caus').'`0','diskus.php?act=diskusmarker'.($_GET['op'] == 'list' ? '&op=list' : ''));
        addnav('! Noch nicht freigeschaltet !');
        addnav('Verhaltensregeln','diskus.php?act=rules'.($_GET['op'] == 'list' ? '&op=list' : ''));
    }
    addnav('Verlassen');
    if($session['user']['alive']) {
        if($_GET['op'] == 'list') {
                addnav("Zur Einwohnerliste","list.php");
        } else {
                addnav("Zum Stadtamt","dorfamt.php");
        }
    } else {
        if($_GET['op'] == 'list') {
                addnav("Zur Einwohnerliste","list.php");
        } else {
                addnav("Zu den Schatten","shades.php");
        }
    }
}
    
page_footer();
?>

