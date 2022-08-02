<?php 
/* Adler 
 by LonelyUnicorn 
 nach einer Idee von Zinsis 
 small modifikations by Tidus for www.kokoto.de
 */

if(!isset($session)) exit();

switch($_GET['op']){
default:
    output('`n`RDu kommst im Wald auf eine kleine sonnendurchflutete Lichtung. Ein wirklich schöner Platz, um sich auszuruhen.`n
            Als du gerade dein/e '.$session['user']['weapon'].'`R ablegen willst, hörst du von irgendwo ein leises Wimmern.`n`n
            `^Du kannst nachsehen oder dich ausruhen. Was möchtest du tun?'); 
    addnav('c?Nachsehen','forest.php?op=cry'); 
    addnav('Ausruhen','forest.php?op=relax'); 
    $session['user']['specialinc']='adler.php';
break;    
case 'cry':
    output('`n`RDieses herzzerreißende Wimmern lässt dich nicht zur Ruhe kommen, und du beschliesst, dem Ganzen auf den Grund zu gehen.`n
            `RVorsichtig näherst du dich der Geräuschquelle, und als du endlich da bist, siehst du einen verletzen Adler im Gebüsch sitzen.`n
            `REr schaut dich mit seinen großen braunen Augen kummervoll an ...`n`n
            `^Hilfst du dem armen Vogel, was dich sicherlich eine Runde kostet, oder wirst du lieber deinen Weg weitergehen?`0'); 
    addnav('Dem Adler helfen','forest.php?op=help'); 
    addnav('Zurück in den Wald','forest.php?op=back'); 
    $session['user']['specialinc']='adler.php';  
break;
case 'help':
    output('`n`RDu könntest das arme Tier niemals da liegen lassen und versuchst ihm zu helfen.`n`n'); 
    $rand = (mt_rand(1,4)); 
    $session['user']['specialinc'] = ''; 
    $session['user']['turns']--; 
    switch($rand){ 
        case 1: 
        $gold = e_rand($session['user']['level']10,$session['user']['level']50); 
        output("`RDu hebst den Adler hoch und trägst ihn so schnell du kannst zum Heiler, der ihm einen Heiltrank gibt.
                `RDer Adler scheint zu lächeln.`n`n `n`nAls Dank lässt er dich auf seinen Rücken klettern und fliegt mit dir zu seinem Nest.`n`n
                `RDu findest `^$gold Goldstücke"); 
        $session['user']['gold']+=$gold; 

        break; 
        case 2: 
        output('`RDu hebst den Adler hoch und trägst ihn so schnell du kannst zum Heiler, der ihm einen Heiltrank gibt.`n `n`n`^Zum Dank zeigt dir der Adler ein Versteck, in dem du 2 Edelsteine findest!'); 
        $session['user']['gems']+=2; 
        break; 
        case 3: 
        output('`RDu hebst den Adler hoch und trägst ihn so schnell du kannst zum Heiler, der ihm einen Heiltrank gibt.`n`n`n`nZum Dank umkreist der Adler dich anerkennend und verschwindet am Horizont!'); 
        break; 
        case 4: 
        output("`RDu willst den Vogel gerade anheben, um ihn zum Heiler zu tragen, doch kaum bist du mit deinen Fingern in der Nähe,`n springt hinter dem Vogel ein Gnom hervor und beißt dir in den Finger. Durch den grausamen Schmerz (du solltest einen Heiler aufsuchen)`N bekommst du nicht mit, wie der Gnom `4dein ganzes Gold `Rstiehlt.`n`n `4Ein Wimmern zieht durch den Wald, doch es ist nicht mehr das des Adlers."); 
        //donationpoints als Entschaedigung 
        if ($session['user']['gold']>1000){ 
            $session['user']['donation']+= 5; 
        }else if ($session['user']['gold']>100){ 
            $session['user']['donation']+=3; 
        }else{ 
            $session['user']['donation']+=1; 
        } 
        $session['user']['hitpoints']=1; 
        $session['user']['gold']=0; 
        break; 
         
    }
break;
case 'back':
    output('`n`RDu gehst - Wie kann man nur so herzlos sein? `%Du verlierst einen Charmepunkt!`R`n');
    addnews("`^".$session['user']['name']." `3war zu herzlos um einem verletzten Tier zu helfen! ");
    $session['user']['charm']--; 
    $seesion['user']['specialinc'] = ''; 
break; 
case 'relax':
    output('`n`RDu ignorierst das Wimmern. Nach einer Weile hörst du es kaum noch und kannst entspannen.`n
            `^Du bist erholt und konntest vollständig regenerieren.'); 
    if ($session['user']['hitpoints']<$session['user']['maxhitpoints']) $session['user']['hitpoints']=$session['user']['maxhitpoints']; 
    $seesion['user']['specialinc'] = ''; 
    addnav("Zurück in den Wald","forest.php"); 
break;
    }
?> 