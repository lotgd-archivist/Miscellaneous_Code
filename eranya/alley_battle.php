
<?php

// Kampf gegen anderen Schatzsucher
// gehört zur alley.php
// Autor: Silva
// erstellt für: Eranya (http://eranya.de/)

require_once 'common.php';

page_header('Verlassenes Haus');
// Kampf
if ($_GET['op'] == "fight")
{
        $battle = true;
}
if ($battle)
{
        include("battle.php");
        if ($victory)
        {
                output("`n`4Du hast ".$badguy['creaturename']." `4besiegt.`n
                         `^".$badguy['creaturename']." `^nimmt die Beine in die Hand
                         und sucht schnell das Weite.`n");
                $badguy = array();
                $session['user']['badguy'] = "";
                $experience = $session['user']['level']*e_rand(37,80);
                output("`#Du erhälst `6".$experience." `#Erfahrung!`n");
                $session['user']['experience'] += $experience;
                addnav('Weiter','alley.php?op=abandoned_house&act=look3');
        }
        elseif ($defeat)
        {
                output("`4Du wurdest von ".$badguy['creaturename']." `4besiegt.`n
                         `&Du verlierst 10% deiner Erfahrung und all dein Gold.");
                addnews($session['user']['name']." `4war einem Schatzsucher unterlegen.");
                $sql = "UPDATE account_extra_info SET alleypick=1 WHERE acctid = ".$session['user']['acctid'];
                db_query($sql) or die(sql_error($sql));
                killplayer(100,10);
        }
        else
        {
                fightnav(true,false);
        }
}

page_footer();
?>

