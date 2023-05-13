
<?php
/**
 * referral.php: Anwerbungen
 * @author LOGD-Core
 * @version DS-E V/2
*/

require_once('common.php');

if(getsetting('enable_referral_system',1)==0 && $session['user']['loggedin'])
{
    redirect('village.php','Das Referalsystem ist abgeschaltet');
}
elseif (getsetting('enable_referral_system',1)==1 && $session['user']['loggedin'])
{
    page_header('Empfehlungen');

    output(get_title('`&Empfehlungen'));

    addnav('Zurück');
    addnav('D?zum Dorf','village.php');
    addnav('J?zur Jägerhütte','lodge.php');

    output('`&Du bekommst automatisch '.getsetting('refererdp',50).' Punkte für jeden geworbenen Charakter, der folgenden Status erreicht:`n`n
            Level `b'.getsetting('refererminlvl',5).'`b`n
            '.(getsetting('referermindk',0) > 0 ? 'Heldentat(en) `b'.getsetting('referermindk',0).'`b`n' : '').'`n
            `n(`4Achtung: Eigene Accounts anzuwerben ist verboten und wird mit Verbannung bestraft!`0)
            `n`n
            Woher weiß '.getsetting('townname','Atrahor').', dass du eine Person geworben hast?`n
            Kleinigkeit! Wenn du Freunden von dieser Seite erzählst, gib ihnen einfach folgenden Link:`n`n`q
            '.getsetting('server_address','http://'.$_SERVER['SERVER_NAME'].dirname($_SERVER['REQUEST_URI'])).'referral.php?r='. rawurlencode($session['user']['login']).'`n`n
            `&Dadurch wird die Seite wissen, dass du derjenige warst, der ihn hergeschickt hat. Wenn er dann zum ersten Mal oben angeführten Status erreicht, bekommst du deine Punkte!`n`n
            ');

    $sql = 'SELECT name,level,refererawarded,dragonkills FROM accounts
            LEFT JOIN account_extra_info USING(acctid)
            WHERE referer='.$session['user']['acctid'].' ORDER BY dragonkills,level';
    $result = db_query($sql);
    output('`n`nAccounts, die du geworben hast:`n`0
            <table border="0" cellpadding="3" cellspacing="0">
                <tr class="trhead">
                    <th width="100">Name</th>
                    <th width="80">Level</th>
                    <th width="80">DKs</th>
                    <th width="80">Ausgezahlt?</th>
                </tr>',true);

    $int_count = db_num_rows($result);

    if ($int_count==0)
    {
        output('<tr><td colspan="4" align="center">`iKeine!`i</td></tr>',true);
    }

    for ($i=0;$i<$int_count;$i++)
    {
        $row = db_fetch_assoc($result);
        output('<tr class="'.($i%2?'trlight':'trdark').'"><td>',true);
        output($row['name']);
        output('</td><td>'.$row['level'].'</td><td>'.$row['dragonkills'].'</td><td>'.($row['refererawarded']?'`@Ja!`0':'`$Nein!`0').'</td></tr>',true);
    }

    output('</table>',true);
    page_footer();
}
elseif (getsetting('enable_referral_system',1)==1)
{
    page_header('Willkommen in '.getsetting('townname','Atrahor'));

    output(get_extended_text('referal_welcome_text'));
    if (!empty($_GET['r']))
    {
        $str_login = stripslashes(rawurldecode($_GET['r']));
        $sql = 'SELECT login,acctid FROM accounts WHERE login="'.addslashes($str_login).'"';
        $arr_user = db_fetch_assoc(db_query($sql));
        if(!empty($arr_user))
        {
            output('`@Du wurdest von `b'.$arr_user['login'].'`b hierher eingeladen, damit ihr gemeinsam gegen das Böse kämpfen könnt.');
        }
    }
    output('`@ Melde dich jetzt kostenlos an und werde Teil dieser Welt.');
    addnav('Navigation');
    addnav('C?Neuen Charakter erstellen','create_rules.php'.(!empty($arr_user) ? '?r='.$arr_user['acctid'] : ''));
    addnav('F.A.Q.','petition.php?op=faq',false,true);
    addnav('L?Zum Login','index.php'.(!empty($arr_user) ? '?r='.$arr_user['acctid'] : ''));
    page_footer();
}
else
{
    redirect('create_rules.php');
}
?>


