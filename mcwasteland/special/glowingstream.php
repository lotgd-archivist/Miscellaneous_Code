
<?php
if ($_GET['op']==""){
    output("`#Du entdeckst einen schmalen Strom schwach glÃ¼henden Wassers, das Ã¼ber runde, glatte, weiÃŸe Steine blubbert. Du kannst eine magische Kraft in diesem Wasser fÃ¼hlen. Es zu trinken, kÃ¶nnte ungeahnte KrÃ¤fte in dir freisetzen - oder es kÃ¶nnte dich zum vÃ¶lligen KrÃ¼ppel machen. Wagst du es, von dem Wasser zu trinken?");
    output("`n`n<a href='forest.php?op=drink'>Trinken</a>`n<a href='forest.php?op=nodrink'>Nicht trinken</a>",true);
    addnav("Trinken","forest.php?op=drink");
    addnav("Nicht Trinken","forest.php?op=nodrink");
    addnav("","forest.php?op=drink");
    addnav("","forest.php?op=nodrink");
    $session['user']['specialinc']="glowingstream.php";
}else{
    $session['user']['specialinc']="";
    if ($_GET['op']=="drink"){
      $rand = e_rand(1,10);
        output("`#Im Wissen, daÃŸ dieses Wasser dich auch umbringen kÃ¶nnte, willst du trotzdem die Chance wahrnehmen. Du kniest dich am Rand des Stroms nieder und nimmst einen langen, krÃ¤ftigen Schluck von diesem kalten Wasser. Du fÃ¼hlst WÃ¤rme von deiner Brust heraufziehen, ");
        switch ($rand){
            case 1:
                output("`igefolgt von einer bedrohlichen, beklemmenden KÃ¤lte`i. Du taumelst und greifst dir an die Brust. Du fÃ¼hlst das, was du dir als die Hand des Sensenmanns vorstellst, der seinen gnadenlosen Griff um dein Herz legt.`n`nDu brichst am Rande des Stroms zusammen. Dabei erkennst du erst jetzt gerade noch, daÃŸ die Steine, die dir aufgefallen sind die blanken SchÃ¤del anderer Abenteurer sind, die genauso viel Pech hatten wie du.`n`nDunkelheit umfÃ¤ngt dich, wÃ¤hrend du da liegst und in die BÃ¤ume starrst Dein Atem wird dÃ¼nner und immer unregelmÃ¤ÃŸiger. Warmer Sonnenschein strahlt dir ins Gesicht, als scharfer Kontrast zu der Leere, die von deinem Herzen Besitz ergreift.`n`n`^Du bist an den dunklen KrÃ¤ften des Stroms gestorben.`nDa die Waldkreaturen die Gefahr dieses Platzes kennen, meiden sie ihn und deinen KÃ¶rper als Nahrungsquelle. Du behÃ¤ltst dein Gold.`nDie Lektion, die du heute gelernt hast, gleicht jeden Erfahrungsverlust aus.`nDu kannst morgen wieder kÃ¤mpfen.");
                $session['user']['alive']=false;
                $session['user']['hitpoints']=0;
                addnav("TÃ¤gliche News","news.php");
                addnews($session['user']['name']." hat seltsame KrÃ¤fte im Wald entdeckt und wurde nie wieder gesehen.");
            break;
            case 2:
                output("`igefolgt von einer bedrohlichen, beklemmenden KÃ¤lte`i. Du taumelst und greifst dir an die Brust. Du fÃ¼hlst das, was du dir als die Hand des Sensenmanns vorstellst, der seinen gnadenlosen Griff um dein Herz legt.`n`nDu brichst am Rande des Stroms zusammen. Dabei erkennst du erst jetzt gerade noch, daÃŸ die Steine, die dir aufgefallen sind die blanken SchÃ¤del anderer Abenteurer sind, die genauso viel Pech hatten wie du.`n`nDunkelheit umfÃ¤ngt dich, wÃ¤hrend du da liegst und in die BÃ¤ume starrst. Dein Atem wird dÃ¼nner und immer unregelmÃ¤ÃŸiger. Warmer Sonnenschein strahlt dir ins Gesicht, als scharfer Kontrast zu der Leere, die von deinem Herzen Besitz ergreift.`n`nAls du deinen letzten Atem aushauchst, hÃ¶rst du ein entferntes leises Kichern. Du findest die Kraft, die Augen zu Ã¶ffnen und siehst eine kleine Fee Ã¼ber deinem Gesicht schweben, die unachtsam ihren Feenstaub Ã¼berall Ã¼ber dich verstreut. Dieser gibt dir genug Kraft, dich wieder aufzurappeln. Dein abruptes Aufstehen erschreckt die Fee, und noch bevor du die MÃ¶glichkeit hast, ihr zu danken, fliegt sie davon.`n`n`^Du bist dem Tod knapp entkommen! Du hast einen Waldkampf und die meisten deiner Lebenspunkte verloren.");
                if ($session['user']['turns']>0) $session['user']['turns']--;
                if ($session['user']['hitpoints']>($session['user']['hitpoints']*.1)) $session['user']['hitpoints']=round($session['user']['hitpoints']*.1,0);
            break;
            case 3:
                output("du fÃ¼hlst dich GESTÃ„RKT!`n`n`^Deine Lebenspunkte wurden aufgefÃ¼llt und du spÃ¼rst die Kraft fÃ¼r einen weiteren Waldkampf.");
                if ($session['user']['hitpoints']<$session['user']['maxhitpoints']) $session['user']['hitpoints']=$session['user']['maxhitpoints'];
                $session['user']['turns']++;
                break;
            case 4:
                output("du fÃ¼hlst deine SINNE GESCHÃ„RFT! Du bemerkst unter den Kieselsteinen am Bach etwas glitzern.`n`n`^Du findest einen EDELSTEIN!");
                $session['user']['gems']++;
                //debuglog("found 1 gem by the stream");
                break;
            case 5:
            case 6:
            case 7:
                output("du fÃ¼hlst dich VOLLER ENERGIE!`n`n`^Du bekommst einen zusÃ¤tzlichen Waldkampf!");
                $session['user']['turns']++;
                break;
            default:
                output("du fÃ¼hlst dich GESUND!`n`n`^Deine Lebenspunkte wurden vollstÃ¤ndig aufgefÃ¼llt.");
                if ($session['user']['hitpoints']<$session['user']['maxhitpoints']) $session['user']['hitpoints']=$session['user']['maxhitpoints'];
        }
    }else{
        output("`#Weil du die verhÃ¤ngnisvollen KrÃ¤fte in diesem Wasser fÃ¼rchtest, entschlieÃŸt du dich, es nicht zu trinken und gehst zurÃ¼ck in den Wald.");
    }
}
?>


