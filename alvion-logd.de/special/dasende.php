
<?php/*####################################################### Das Ende                                             ## by Ajuba und Mr edah unter Mithilfe von Beck's       ## www.lottr.de/lodg                                    ## www.edahnien.de                                      ##                                                      ## Fehlerkorrekturen, Anpassungen und Buffs             ## für www.alvion-logd.de von Linus in 2008             #######################################################*/page_header("Das Ende");if ($_GET['op']==""){    //der Anfang    $session['user']['specialinc']="dasende.php";    output('`nDu triffst einen dunkel gekleideten Mann der dir mit lauter Stimme verkündet `Q"Hier ist das Ende!"`0');    addnav("Ausfragen","forest.php?op=ausf");    addnav("Ihn verwirrt ansehen","forest.php?op=wirr");    addnav("Flüchten","forest.php?op=flue");} elseif ($_GET['op']=="lass"){    $session['user']['specialinc']='';    $session['user']['reputation']+=2;    $session['user']['gems']++;    output('`n`^Das war aber mal eine gute Tat. `0Dein Ansehen steigt und fröhlich gehst du deiner Wege. Doch dein Frohsinn nimmt mit jedem Schritt ab        und dein Ärger über dich selbst steigt. `&Den Topf mit dem Gold hätte ich so gut gebrauchen können, und was soll dieser wundersame Gnom        schon damit anfangen können! `0regst du dich auf. Der Edelstein den du noch findest kann deine Laune nicht maßgeblich bessern und so wird dein        Ärger dich noch eine Weile begleiten!');        $buff = array( "name" => "`4Ärger","roundmsg" => "`&Du bist so sauer auf dich selbst dass es dir schwer fällt deinen Gegner zu treffen!`0","wearoff" => "Du kommst wieder runter.","rounds" => "15","atkmod" => "0.7","activate" => "offense");        $session['bufflist']['Aerger']=$buff;        addnews($session['user']['name']."`= hat ein großes Herz aber wenig Verstand!");} elseif ($_GET['op']=="flue"){    $session['user']['specialinc']='';    $session['user']['reputation']--;    output('`n`Q"Schade, aber wenn du feige davon läufst ..."`0 ruft dunkel gekleidete Mann dir nach `Q"... dann wird ein Anderer den Schatz finden!"        `0`n`n`nDurch deine feige Flucht hast du Ansehen verloren');    addnews($session['user']['name']."`= flüchtete feige vor einem `àdunkel gekleideten Mann.");    addnav("Zurück in den Wald","forest.php");    } elseif ($_GET['op']=="ausf"){    output('`nDu willst ihn gerade fragen `&"Welches Ende?"`0 als er lachend davon läuft. Kopfschüttelnd überlegst du, was du nun tun solltest.');    $session['user']['specialinc']="dasende.php";    //Spass muss sein    addnav("Bleiben","forest.php?op=bleib");    addnav("Nur weg hier","forest.php?op=geh");} elseif ($_GET['op']=="wirr"){    $verlust=mt_rand(2,3);        output("`nDu bis so verwirrt das du noch einige Zeit stehen bleibst. Der Mann ist längst verschwunden. Du vertrödelst {$verlust} Waldkämpfe");        $session['user'][turns]-=$verlust;        addnews("".$session['user']['name']." steht verwirrt im Wald.");} elseif ($_GET['op']=="geh"){    output('`nDu rennst so schnell du kannst in den Wald zurück, stolperst über einen Ast und landest mit der Stirn auf einem spitz zugeschliffenen Edelstein. Ist das das Ende von dem er sprach? Nein, das ist es wohl nicht da du schwer verletzt überlebt hast und bohrende Schmerzen deinen Kopf peinigen. Aber du freust dich über den gefundenen Edelstein!');    $session['user']['gems']++;    $session['user']['hitpoints']=ceil($session['user']['hitpoints']/10);        $session['user']['specialinc']='';        $buff = array( "name" => "`#Kopfschmerzen","roundmsg" => "`#Deine Kopfschmerzen sind so stark dass es dir schwer fällt dich auf deinen Gegner zu konzentrieren!`0","wearoff" => "Dein Kopf ist wieder klar.","rounds" => "15","atkmod" => "0.75","activate" => "offense");    $session['bufflist']['Kopfschmerz']=$buff;} elseif ($_GET['op']=="bleib"){    $session['user']['specialinc']="dasende.php";    output('`nDu hast dich entschieden hier zu bleiben, unsicher schaust du dich um und suchst nach dem vermeindlichen Ende.        Du kannst jedoch nur einen Farbverlauf erkennen der sich erst beim dritten Hinsehen als Regenbogen zu erkennen gibt.        In alten Geschichten hast du gehört, das am Ende eines Regenbogens eigentlich immer ein Topf mit Gold zu finden ist.        Ohne lange zu überlegen stürmst du los.`n Kaum bist du angekommen stellst du auch schon fest das du nicht alleine bist.        Von irgendwoher vernimmst du ein leises Winseln. Du willst gerade weitergehen, doch stösst du mit dem Knie gegen etwas ...        das sofort schmerzhaft aufstöhnt. Du schaust zu Boden und siehst einen kleinen Gnom vor dir sitzen, der wie gebannt in einen        Topf starrt und leise flüstert: `tMein Schatz!`0 Eigentlich ist er dir gleich und so schaust du dich weiter nach dem Topf mit Gold um,        gerade noch rechtzeitig fällt dir ein das du ihn bereits gefunden hast ... er wird von dem Gnom umklammert. Was willst du nun tun?');    addnav("Lass ihm dem Topf","forest.php?op=lass");    addnav("Topf klauen","forest.php?op=topfkl");    addnav("Dein Schatz?","forest.php?op=schagno");} elseif ($_GET['op']=="topfkl"){    $session['user']['specialinc']="";    output('`nDu reisst dem Gnom den Topf aus den Händen. Er ist nicht sehr erfreut darüber und so schlägt er wie wild auf dich ein wobei er hysterisch        `t"Mein Schatz ... mein Schatz!" `0schreit.');    switch(mt_rand(1,3)){        case 1:        case 2: // Titeländerung entfernt und durch Buff ersetzt - Linus in 2008            output("Du kannst mit dem Gold nicht verschwinden, kommst aber mit einem blauen Auge davon.");            addnews("".$session['user']['name']."`9 hat nun ein blaues Auge.");              $buff = array( "name" => "`9Blaues Auge","roundmsg" => "`9Dein blaues Auge behindert dich!`0","wearoff" => "Du kannst wieder richtig gucken","rounds" => "15","atkmod" => "0.8","defmod" => "0.8","activate" => "offense,defense");            $session['bufflist']['Blauauge']=$buff;//            addnav("Abhauen","forest.php");        break;        case 3:            $gewinn=1000+($session['user']['level']*mt_rand(50,150));            output("Du trittst dem Gnom vor die Schienbeine worauf dieser jaulend und zeternd davon rennt. In dem Topf findest du `^$gewinn `0Goldstücke");            $session['user'][gold]+=$gewinn;//            addnav("Abhauen","forest.php");        break;    }} elseif ($_GET['op']=="schagno"){    $session['user']['specialinc']='dasende.php';    output("`nWütend schaust du den Gnom an. `^Dein Schatz? Nicht mehr lange!`0 Sagst du und ziehst deine Waffe");    //kampf    $health = $session['user']['maxhitpoints']*1.25;    $att = $session['user']['attack']+2;    $def = $session['user']['defence']+1;    $badguy = array(        "creaturename"=>"`&Gollum`0"        ,"creaturelevel"=>$session['user']['level']+1        ,"creatureweapon"=>"`7Stinkender Fisch`0"        ,"creatureattack"=>$att        ,"creaturedefense"=>$def        ,"creaturehealth"=>$health        ,"diddamage"=>0);    $session['user']['badguy']=createstring($badguy);    $battle=true;    $_GET['op']="fight";}elseif ($_GET['op']=="run"){    output("n`b`\$Es gelingt dir nicht zu entkommen.`0`n");        $battle=true;//        $_GET['op']="fight";        $session['user']['specialinc']='dasende.php';} elseif ($_GET['op']=="fight"){    $battle=true;    $session['user']['specialinc']='dasende.php';}if ($battle) {    include("battle.php");        if ($victory){        $badguy=array();        $session['user']['badguy']="";        $session['user']['specialinc']="";        $expbonus = round($session['user']['experience']*0.1);        $session['user']['experience']+=$expbonus;        $session['user']['gold']+=5000;        $session['user']['gems']+=2;        output("`n`0Du triffst den Gnom mit einem vernichtenden Schlag. Woraufhin dieser zu Boden geht. Glücklich schnappst du dir den Topf mit`^ 5000 `0Goldstücken und 2 Edelsteinen und gehst zurück in den Wald.`n`nFür diesen Sieg hast du `4{$expbonus} `0Erfahrung erhalten!`n");        addnews($session['user']['name']."`q hat im Wald einen harmlosen Gnomen erschlagen.");    } elseif($defeat){        $expbonus = round($session['user']['experience']*0.05);        output("`n`0Du wurdest von ".$badguy['creaturename']."`0 besiegt! Du verlierst all dein Gold und 5% deiner Erfahrung.`n`n`4Du bist tot!`n`0");        $badguy=array();        $session['user']['badguy']="";        $session['user']['experience']-=$expbonus;        $session['user'][alive]=false;        $session['user']['hitpoints']=0;        $session['user']['gold']=0;        addnews($session['user']['name']."`q wurde im Wald mit einem stinkenden Fisch erschlagen.");                addnav("Tägliche News","news.php");    }else{        fightnav(true,true);    }}?>

