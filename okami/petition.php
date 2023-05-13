
<?php
/**
 * Diese Datei erstellt ein Interface über das die Spieler Anfragen an das Team senden können
 * @version DS V3
 */


require_once('common.php');

$str_filename = basename(__FILE__);

if($_GET['op'] == 'help' || $_GET['op']=='faq') {

    $str_default = 'faq_start';

    popup_header(getsetting('townname','Atrahor').' - Hilfe / FAQ');

    $str_page = (empty($_GET['page']) ? $str_default : $_GET['page']);

    $str_txt = get_extended_text($str_page,'rules_faq',false,false);

    output('<script language="javascript">window.resizeTo(600,550);</script>',true);

    if (false !== $str_txt) {

        output($str_txt,true);

    }
    else {

        output('`$Seite konnte nicht gefunden werden!');

    }

}
else if($_GET['op']=="rules_short")
{
    popup_header("Kurzfassung der Regeln");
    output('
    <a href="petition.php?op=faq">Inhaltsverzeichnis</a>`n`n
    '.get_extended_text('rules_short'),true);

}
else
{
    $BOOL_JSLIB_PLU_MI = true;
    $demouser_acctid=(int)getsetting('demouser_acctid',0);
    popup_header("Anfrage für Hilfe",true);

    if($_GET['op'] == 'submit')
    {
        if (count($_POST)>0){

            if(empty($_POST['description'])) {
                $str_output .='`$Das Nichts ist zweifelsohne eine erhabene Tatsache, nur wird die Administration mit einer leeren Anfrage
                        wohl weniger als nichts anfangen können.`n`0';
            }
            elseif( (empty($_POST['email']) || !is_email(stripslashes($_POST['email']))) && (!$session['user']['loggedin'] || $session['user']['acctid']==$demouser_acctid)) {
                $str_output .='`$Wie willst du denn eine Antwort auf diese Anfrage erhalten, wenn du keine gültige EMail-Adresse angibst?`n`0';
            }
            else
            {
                if($_GET['subop'] == 'mediator')
                {
                    $mailbody = strip_appoencode($_POST['description']).'

                    Charname: '.$_POST['charname'].'
                    Charmail: '.$_POST['email'].'
                    ';
                    $mailbody = nl2br($mailbody);
                    if(send_mail(getsetting('mediator_email','admin@localhost'),'Mediation erwünscht von '.$_POST['charname'],$mailbody))
                    {
                        $str_output .= 'Dein Hilfegesuch wurde erfolgreich per Mail versand, '.getsetting('mediator_name','Admin').' wird sich bald bei dir melden.';
                    }
                    else
                    {
                        $str_output .= 'Leider ist beim Senden der Mail ein Fehler aufgetreten...';
                    }
                }
                elseif($_GET['subop'] == 'petition')
                {
                    $p = $session['user']['password'];
                    unset($session['user']['password']);

                    $array_petitionbans = getsetting("petitionbans", serialize(array()));
                    $array_petitionbans = unserialize($array_petitionbans);
                    
                    if (($session['user']['loggedin']==true) && ((int)$_POST['kat']!=7 && $_GET['petitionban']!="override") && (getsetting("petitionban_enabled",1)==1)){
                    //Wenn eingeloggt, keine wichtige Anfrage und Setting aktiv (also nicht Hilferuf), Anfragenblock überprüfen
                        $bool_petitionban_active=false;
                        reset($array_petitionbans);
                        while (list($key, $value) = each ($array_petitionbans)) {
                            if ($array_petitionbans[$key][0]==$session['user']['acctid']){
                                if ((time()-$array_petitionbans[$key][2])<=($array_petitionbans[$key][3]*60)){
                                    $bool_petitionban_active=true;
                                    $array_petitionbans[$key][4]=time();
                                    $array_petitionbans[$key][5]++;
                                    $int_sekunden=($array_petitionbans[$key][3]*60)-(time()-$array_petitionbans[$key][2]);
                                    $int_wartestunden=floor($int_sekunden/3600);
                                    $int_sekunden-=$int_wartestunden*3600;
                                    $int_warteminuten=floor($int_sekunden/60);
                                    $int_sekunden-=$int_warteminuten*60;
                                    $str_wartezeit=$int_wartestunden." Stunden, ".$int_warteminuten." Minuten und ".$int_sekunden." Sekunden";
                                }
                                break;
                            }
                        }
                    }

                    if ($bool_petitionban_active==false || $_GET['petitionban']=="override"){
                        if(!$session['user']['loggedin'] || $session['user']['acctid']==$demouser_acctid)
                        {
                            $sql = 'SELECT login,acctid,uniqueid,lastip FROM accounts WHERE lastip = "'.addslashes($session['lastip']).'" OR uniqueid = "'.addslashes($session['uniqueid']).'" ORDER BY login, acctid';
                            $res = db_query($sql);
    
                            $sec_info = '';
    
                            while($r = db_fetch_assoc($res) ) {
    
                                $sec_info .= '`n'.$r['login'].' (AcctID '.$r['acctid'].', IP '.$r['lastip'].', ID '.$r['uniqueid'].')';
    
                            }
                        }

                        if ($session['user']['loggedin']==true){ //Wenn eingeloggt, erfolgreiche Anfrage registrieren
                        reset($array_petitionbans);
                        while (list($key, $value) = each ($array_petitionbans)) {
                            if ($array_petitionbans[$key][0]==$session['user']['acctid']){
                                $array_petitionbans[$key][2]=time();
                                break;
                            }
                        }
                        }    

                        db_insert('petitions',
                                    array(
                                            'author'     => ($session['user']['acctid']==$demouser_acctid ? 0 : (int)$session['user']['acctid']),
                                            'date'        => array('sql'=>true,'value'=>'NOW()'),
                                            'body'        => $_POST['description'],
                                            'email'        => $_POST['email'],
                                            'charname'    => $_POST['charname'],
                                            'pageinfo'    => output_array($session,"Session:"),
                                            'lastact'    => array('sql'=>true,'value'=>'NOW()'),
                                            'IP'        => $session['lastip'],
                                            'ID'        => $session['uniqueid'],
                                            'connected'    => $sec_info,
                                            'kat'        => (int)$_POST['kat']
                                        )
                                    );
    
                        $session['user']['password']=$p;
                        if($session['user']['acctid']>0) {
                            user_set_stats(array('petitions'=>'petitions+1'));
                        }
                        $str_output .= "Deine Anfrage wurde an die Admins gesendet. Bitte hab etwas Geduld, Antworten und Reaktionen können eine Weile dauern, wir bemühen uns aber alles so schnell wie möglich zu beantworten.";

                    } else {
                        $session['user']['password']=$p;
                        addnav("","petition.php?op=submit&subop=petition&petitionban=override");
                        $str_output .= "<form action='petition.php?op=submit&subop=petition&petitionban=override' method='POST'><input type='hidden' name='email' value='".$_POST['email']."'><input type='hidden' name='charname' value='".$_POST['charname']."'><input type='hidden' name='kat' value='".((int)$_POST['kat'])."'>";
                        $str_output .= str_replace("{notwendiges textfeld}", "<textarea name='description' class='input' rows='3' cols='50' readonly>".$_POST['description']."</textarea>", str_replace("{wartezeit}", $str_wartezeit, get_extended_text("petitionban_hinweis")));
                        $str_output .= '`n
                            `c<input id="ok_button" type="submit" value="Ja das ist wichtig, Absenden!">`c
                        
                                <script type="text/javascript" language="JavaScript">
                                    var count = 30;
                                    counter();
                                    function counter () {
                                        if(count == 0) {
                                            document.getElementById("ok_button").value = "Ja, das ist wichtig, Absenden!";
                                            document.getElementById("ok_button").disabled = false;
                                        }
                                        else {
                                            document.getElementById("ok_button").value = "Ja, das ist wichtig, Absenden! (noch "+count+" Sekunden)";
                                            document.getElementById("ok_button").disabled = true;
                                            count--;
                                            setTimeout("counter()",1000);
                                        }
                                    }
                                </script></form>
                            ';

                    }
                    
                    savesetting("petitionbans", serialize($array_petitionbans));

                }
            }
            output($str_output,true);
            popup_footer(false);
        }
    }

    //
    // Formular für die Anfragen
    //
    $str_kat_enum = 'enum';

    $arr_categories = $ARR_PETITION_KATS;
    //Namensänderung wird separat behandelt
    //$arr_categories = array_remove_val("Namensänderung",$arr_categories);

    foreach($arr_categories as $id => $kat) {

        $str_kat_enum .= ','.$id.','.$kat;

    }

    $arr_data_anfrage = array('charname'=>$session['user']['login'],
                        'email'=>$session['user']['emailaddress'],
                        'description'=>stripslashes($_POST['description'])
                        );
    $arr_form_anfrage = array('charname'=>'Name deiner Spielfigur:',
                        'email'=>'Deine E-Mail Adresse:',
                        'kat'=>'Art der Anfrage:,'.$str_kat_enum,
                        'description'=>'Beschreibe dein Problem:`n,textarea,35,8');

    $str_output_petition  = '<script language="javascript">window.resizeTo(600,550);</script>';
    $str_output_petition .= '`c`b`&Anfrage an die Administration`&`b`c`n`n';
    $str_output_petition .= get_extended_text('anfrage_beschreibung','*',false,false).'
                            <form action="petition.php?op=submit&subop=petition" method="POST">';
    $str_output_petition .= generateform($arr_form_anfrage,$arr_data_anfrage,false,'Absenden!');
    $str_output_petition .= '</form>';

    //
    // Anzeige der eigenen Anfragen mit einer kleinen Statusübersicht
    //

    if($session['user']['loggedin'] == 1)
    {
        $statuses=array(0=>"`bUngelesen`b","Gelesen.","Geschlossen/Abgearbeitet");

        $str_sql_my_petitions ='

        SELECT         p.petitionid,p.prio,p.date, p.status,p.lastact,p.kat,p.p_for,p.commentcount,p.body
                    FROM         petitions p
                    WHERE        p.author = '.$session['user']['acctid'].'
                    ORDER BY     p.petitionid DESC';

        $db_res = db_query($str_sql_my_petitions,false);
        $int_count = db_num_rows($db_res);

        //Wenn der Nutzer anfragen gestellt hat, dann stelle den Bearbeitungsstatus dar
        if($int_count>0)
        {
            $str_output_petition .= '
            <hr>
            `c`bDeine Anfragen`b`c
            <table width="100%">
                <tr class="trhead">
                    <td>Anfragedatum</td>
                    <td>Status der Anfrage</td>
                    <td>#Kommentare</td>
                    <td>Zuordnung</td>
                </tr>
            ';
            while($arr_result = db_fetch_assoc($db_res))
            {
                $str_class = $str_class == 'trlight'?'trdark':'trlight';
                $str_output_petition .= '
                <tr class="'.$str_class.'">
                    <td>'.date('d.m.y H:i:s',strtotime($arr_result['date'])).'</td>
                    <td>'.$statuses[$arr_result['status']].'</td>
                    <td align="center">'.$arr_result['commentcount'].'</td>
                    <td>'.$ARR_PETITION_KATS[$arr_result['kat']];
                    //Einkommentieren wenn die Zuordnung an einen bestimmten User auch dargestellt werden soll
                    //(!empty($arr_result['p_for']) ? '`nFür: '.$arr_result['p_for'].'`0' : '').'
                $str_output_petition .= '</td>
                </tr>
                <tr class="'.$str_class.'">
                    <td colspan="3">'.plu_mi('petition_'.$arr_result['petitionid'],0,false).' Anfragetext: '.substr(htmlentities($arr_result['body']),0,80).'...
                                            <div id="'.plu_mi_unique_id('petition_'.$arr_result['petitionid']).'" style="display:none;padding-left: 25px;">
                                                '.htmlentities($arr_result['body']).'
                                            </div>
                    <td>
                </tr>';
            }
            $str_output_petition .= '
            </table>';
        }
    }

    //
    //Formular für die Mediatoren
    //
    $arr_data_mediator = array('charname'=>$session['user']['login'],
                        'email'=>$session['user']['emailaddress'],
                        'description'=>stripslashes($_POST['description'])
                        );
    $arr_form_mediator = array('charname'=>'(Optional)Name deiner Spielfigur:',
                        'email'=>'Deine E-Mail Adresse:|?Eine Mailadresse unter der du erreichbar bist. Die Kommunikation erfolgt ausschließlich per Mail',
                        'description'=>'Beschreibe dein Problem:`n,textarea,35,8');



    $str_output_mediator .= '`c`b`&Schlichtung eines Streits`&`b`c`n`n';
    $str_output_mediator .= get_extended_text('mediation_beschreibung','*',false,false).'
            <form action="petition.php?op=submit&subop=mediator" method="POST">';
    $str_output_mediator .= generateform($arr_form_mediator,$arr_data_mediator,false,'Absenden!');
    $str_output_mediator .= '</form>';

    //
    // Formular für eine Namensänderung wird nur ausgegeben, wenn der User eingelogged ist
    //

    $arr_flipped = array_keys($ARR_PETITION_KATS,'Namensänderung');
    $int_kat = $arr_flipped[0];

    $arr_data_namechange = array('charname'=>$session['user']['login'],
                        'email'=>$session['user']['emailaddress'],
                        'kat'=>$int_kat,
                        'description'=>stripslashes($_POST['description'])
                        );
    $arr_form_namechange = array('charname'=>'Name deiner Spielfigur:',
                        'email'=>'Deine E-Mail Adresse:',
                        'kat'=>',hidden',
                        'description'=>'Grund für die Änderung:`n,textarea,35,8');

    $str_output_namechange = '`c`b`&Namensänderung beantragen`&`b`c`n`n';
    $str_output_namechange .= get_extended_text('namensaenderung_beschreibung','*',false,false).'
            <form action="petition.php?op=submit&subop=petition" method="POST">';
    $str_output_namechange .= generateform($arr_form_namechange,$arr_data_namechange,false,'Absenden!');
    $str_output_namechange .= '</form>';

    $arr_mainform_namechange = array('Namensänderung,title','change_name'=>'Quelltext für eine Namensänderung,html');
    $arr_maindata_namechange = array('change_name'=>appoencode($str_output_namechange));

    //
    // Ausgabe des Formulars
    //
    $arr_mainform = array(
    'Anfrage,title',
    'petition'=>'Quelltext für eine Anfrage an die Administration,html',
    'Streitschlichtung,title',
    'mediation'=>'Quelltext für eine Anfrage an die Mediatoren,html',
    );
    $arr_maindata = array(
    'petition'=>appoencode($str_output_petition),
    'mediation'=>appoencode($str_output_mediator)
    );

    if($session['user']['loggedin'] == true)
    {
        $arr_mainform = array_merge($arr_mainform,$arr_mainform_namechange);
        $arr_maindata = array_merge($arr_maindata,$arr_maindata_namechange);
    }

    showform($arr_mainform,$arr_maindata,true);
}
popup_footer(false);
?>


