
ï»¿<?php



// 02022006



if (!isset($session)) exit();



if ($_GET['op']=="weg"){

    $session['user']['specialinc']="";

    forest();

}elseif ($_GET['op']=="ja"){

    $session['user']['specialinc']="";

    output("`GDu steckst die Karte ein, sprichst ein kurzes Gebet an Ramius Ã¼ber den Toten und machst Dich dann wieder auf den Weg. ");

    db_query("INSERT INTO items (class,owner,name,description) VALUES ('Questitem',{$session['user']['acctid']},'Karte des HÃ¤ndlers','Diese magische Karte zeigt Wege zu versteckten Orten.')");

}elseif ($_GET['op']=="weggeben"){

    db_query("DELETE FROM items WHERE class='Questitem' AND owner={$session['user']['acctid']} AND name='Karte des HÃ¤ndlers'");

    output("`GDu gibst dem Mann seine Karte zurÃ¼ck, ohne dir Ã¼ber die Konsequenzen im Klaren zu sein. Erst spÃ¤ter stellst du fest, dass du ja jetzt wieder vÃ¶llig planlos durch die Gegend rennst und vermutlich einige der Orte nie wieder finden wirst.`nDein Ansehen und dein Charme steigen etwas.");

    $session['user']['reputation']++;

    $session['user']['charm']+=2;

    $session['user']['specialinc']="";

}else{

    output("`GAuf Deinem Streifzug entdeckst Du eine Schleifspur, die von deinem Weg wegfÃ¼hrt. Du hÃ¤ltst inne und betrachtest die niedergedrÃ¼ckten GrÃ¤ser.`n");

    if (db_num_rows(db_query("SELECT name FROM items WHERE class='Questitem' AND owner={$session['user']['acctid']} AND name='Karte des HÃ¤ndlers'"))<=0){

        addnav("Karte mitnehmen","forest.php?op=ja");

        output("An einigen von ihnen klebt frisches Blut.");

        output("Vermutlich hat sich ein verletztes Tier hinter die BÃ¼sche gezogen. Du ziehst Deine Waffe und schleichend folgst Du dieser Spur.");

        output("Nicht weit vom Weg findest Du einen Menschen am Boden liegen. Die `\$Blutlache`G ist groÃŸ. Du nÃ¤hrst Dich und drehst ihn langsam um. Mit einem Blick siehst Du dass seine Verletzungen tÃ¶dlich waren, doch fÃ¤llt ein StÃ¼ck Papier dabei aus seinen HÃ¤nden.");

        output("Blut klebt an seinen RÃ¤ndern und nur vorsichtig Ã¶ffnest Du es. Es ist eine Karte, welche den Wald und das Dorf zeigt. Doch darÃ¼ber hinaus finden sich noch andere Punkte und Namen, welche Dir bisher unbekannt waren. Das bereits getrocknete Blut blÃ¤ttert von den RÃ¤ndern ab und die Karte sieht aus, als sei sie niemals schmutzig gewesen â€“ wenn das nicht mal was mit Magie zu tun hat.`n"); 

    }else{

        $dorf=db_fetch_assoc(db_query("SELECT name FROM villages WHERE 1 ORDER BY rand(".e_rand().") LIMIT 1"));

        output("`GAn einigen von ihnen klebt altes Blut. Vermutlich hat sich ein verletztes Tier hinter die BÃ¼sche gezogen. Du ziehst Deine Waffe und schleichend folgst Du dieser Spur. Nicht weit vom Weg findest Du einen Menschen am Boden sitzen.");

        output("\"Guten Tag, ich bin keine Gefahr.\"`nDer Mann sieht verletzt aus â€“ zumindest hat er VerbÃ¤nde angelegt. Du nickst ihm knapp zu und steckst Deine Waffe langsam weg.`n");

        output("\"Ihr werdet nicht glauben was mir passiert ist. Ich wollte nach ".$dorf['name']." und traf auf dem Weg dort hin auf zwei RÃ¤uber. Sie hatten es wohl auf meine magische Karte abgesehen, doch als einer der Beiden schwer genug verletzt war, zogen sie sich zurÃ¼ck.");

        output("Mich hatte es aber auch schwer erwischt und ich schleppte mich hinter die BÃ¼sche fÃ¼r den Fall dass sie zurÃ¼ckkommen wÃ¼rden. Dann wurde es schwarz um mich. Als ich wieder zu mir kam, war da ein Mann in einer schwarzen Robe der mir etwas von Gefallen und so Zeug erzÃ¤hlte.");

        output("Nun ich Ã¶ffnete die Augen und er war weg und ich war da. Meine Karte war auch noch weg, die RÃ¤uber sind bestimmt noch mal zurÃ¼ckgekommen.\"`n");

        output("Ihr unterhaltet Euch noch etwas mit dem Mann und macht Euch dann wieder auf den Weg.");

        addnav("Karte zurÃ¼ckgeben","forest.php?op=weggeben");

    }

    addnav("Nix wie weg","forest.php?op=weg");

    $session['user']['specialinc']="findmap.php";

}

?>

