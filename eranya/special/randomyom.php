
<?php
/**
Kleines sinnloses Waldevent:
-Ein blindes Hörnchen liefert eine Nachricht an irgendeinen zufälligen Spieler aus,
 der gerade eingeloggt ist
-Man bekommt auch noch ein paar Erfahrungspunkte
by Maris (Maraxxus@gmx.de)
**/

$session['user']['specialinc']='randomyom.php';
if ($_GET['op']=='')
{
    output('`n`5Als du durch das Dickicht schleichst, entdeckst du plötzlich eine freie Fläche, auf der zahlreiche Nüsse verstreut sind.`nDir fallen auch einige `^Posthörnchen`5 auf, die emsig damit beschäftigt sind, diese Nüsse aufzusammeln.`nEbenso erblickst du ein scheinbar blindes Hörnchen am Rand, das schon recht ausgemärgelt ist und immer wieder versucht eine Haselnuss zu erhaschen.`nDoch jedesmal tritt es aus Versehen dagegen. Außerdem kommen ihr ständig andere Posthörnchen zuvor und schnappen ihr das Futter vor der Nase weg.`n`nIrgendwie empfindest du Mitleid für das arme Tier.`nWas willst du tun?`n');

    addnav('Das blinde Hörnchen füttern','forest.php?op=feed');
    addnav('Alle Posthörnchen verjagen','forest.php?op=scare');
}
else
{
    if ($_GET['op']=='feed')
    {
        output('`5Du scheuchst ein paar der Posthörnchen mit der Hand auf Seite und sammelst einige Nüsse auf, um sie dem blinden Hörnchen zu geben.`nDankbar und ausgehungert stürzt es sich auf das Futter.`n');
        //Wieviele User sind online?
        $result = db_fetch_assoc(db_query("SELECT COUNT(DISTINCT lastip) AS onlinecount FROM accounts WHERE locked=0 AND acctid!=".getsetting('demouser_acctid',5)." AND acctid != ".$session['user']['acctid']." AND ".user_get_online() ));
        $onlinecount = $result['onlinecount'];
        if($onlinecount > 0) {
            $link = 'forest.php?op=write';
            addnav('',$link);
            output('Es ist dir dafür so dankbar, dass es eine Nachricht für dich übermitteln wird:`n`n');
            output("<form action='".$link."' method='POST'>
                    Dein Brief: <input type='text' name='message' size='100' maxlength='500'>`n`n
                    <input type='submit' class='button' value='Abschicken!'></form>",true);
        } else {
            $gain=round($session['user']['experience']*0.01);
            output('Du siehst ihm eine Weile zu, dann wendest du dich wieder um und setzt deinen Streifzug durch den Wald fort.'.
                   ($gain > 0 ? '`nDiese Tat hat dich ein wenig klüger gemacht.`n`^Du erhältst '.$gain.' Punkte Erfahrung!`n' : ''));
            $session['user']['experience']+=$gain;
            $session['user']['specialinc']='';
        }
    }
    elseif ($_GET['op']=='write')
    {
        $message = $_POST['message'];
        if (strlen($message) < 5)
        {
            $link = 'forest.php?op=write';
            addnav('',$link);
            output('`&Du kannst keine Nachricht mit weniger als 5 Zeichen verschicken!`n`n');
            output("<form action='".$link."' method='POST'>
                    Dein Brief: <input type='text' name='message' size='100' maxlength='500'>`n`n
                    <input type='submit' class='button' value='Abschicken!'></form>",true);
        }
        else
        {
            $sql = 'SELECT acctid FROM accounts WHERE '.user_get_online().' AND acctid != '.$session['user']['acctid'].' ORDER BY RAND() LIMIT 1';
            $result = db_query($sql) or die(db_error(LINK));
            $amount = db_num_rows($result);
            for($i=0;$i<$amount;$i++)
            {
                $row=db_fetch_assoc($result);
                if($row['acctid'] == $session['user']['acctid']) {
                    continue;
                } else {
                    systemmail($row['acctid'],'`^Blindes Posthörnchen!`0','`&Ein blindes Posthörnchen krallt sich nach einem wackeligen Sprung an deiner Schulter fest.`nEs hat folgende Nachricht bei sich, die '.$session['user']['name'].' `& geschrieben haben muss:`n`n`5'.$message);
                    break;
                }
            }
            $gain=round($session['user']['experience']*0.01);
            output('`5Als du die Nachricht geschrieben hast, springt das Posthörnchen von deiner Schulter und flitzt davon.`n
                Du fragst dich wer deinen Brief erhalten wird, und ob dieser Nager es überhaupt fertig bringt irgendwem irgendetwas auszuliefern...
                '.($gain > 0 ? '`nDennoch hat dich diese Tat ein wenig klüger gemacht.`n`^Du erhältst '.$gain.' Punkte Erfahrung!`n' : '')
                );
            $session['user']['experience']+=$gain;
            $session['user']['specialinc']='';
        }
    }
    elseif ($_GET['op']=='scare')
    {
        output('`5So wie du es schon in deiner Kindheit geliebt hast, rennst du mit lautem Geschrei und wild rudernden Armen über den Platz.`nDie Posthörnchen keckern aufgeschreckt und flitzen rasch in alle Richtungen.`nDu fühlst dich... irgendwie beobachtet...');
        addnews($session['user']['name'].'`# wurde dabei gesehen wie '.($session['user']['sex']?'sie':'er').' Posthörnchen verjagt hat. Man munkelt nun, '.($session['user']['sex']?'sie':'er').' sei dafür verantwortlich, dass manche Nachrichten nicht ankommen!');
        $session['user']['specialinc']='';
    }
}
?>

