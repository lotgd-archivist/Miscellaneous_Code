
<?php
/**
* motd-coding.php: Eine Motd - für Codinggeschichten
* @author Jenutan for arathor.de
* mit folgender DB-Tabelle:

CREATE TABLE `motd_coding` (
`id` INT( 7 ) NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'Mit zählende Zahl',
`time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Zeit der Eintragung',
`headline` TEXT NOT NULL COMMENT 'Überschrift',
`body` TEXT NOT NULL COMMENT 'Textkörper',
`acctid` INT( 7 ) NOT NULL COMMENT 'Autor'
) ENGINE = MYISAM COMMENT = 'Coding- MoTD';

ALTER TABLE `motd_coding` ADD `public` BOOL NOT NULL DEFAULT '0' COMMENT 'ob öffentlich...oder nicht';
ALTER TABLE `motd_coding` ADD `type` INT( 2 ) NOT NULL DEFAULT '1' COMMENT 'Welche Art von Typ?' AFTER `time` ;

ALTER TABLE `motd_coding` ADD INDEX ( `type` ) ;
ALTER TABLE `motd_coding` ADD INDEX ( `public` ) ;

*/
require_once 'common.php';
//Einträge pro Seite
$per_page = 10;
$types = array(
        "1"        =>         array(
                                "name"                => "Sonstiges",
                                "shortname"        => "Sonst."
                        ),
        "2"        =>        array(
                                "name"                => "Neue Features",
                                "shortname"        => "NEU"
                        ),
        "3"        =>        array(
                                "name"                => "Fehler (Bugs)",
                                "shortname"        => "BUG"
                        ),
        "4"        =>        array(
                                "name"                => "Rechtschreibfehler",
                                "shortname"        => "Rechtschr."
                        ),
        "5"        =>        array(
                                "name"                => "Navigationsänderungen",
                                "shortname"        => "Nav."
                        ),
  "6"        =>        array(
                                "name"                => "Änderungen",
                                "shortname"        => "Änd."
                        )    
);

//Popup-Header definieren
popup_header(getsetting('townname','Atrahor').': Messages of the Codingteam (MoTC)',true);
// Fenstergröße anpassen
output('<script language="javascript">window.resizeTo(800,600);</script>',true);

//In die $edit schreiben, ob man mehr sehen/editieren darf
if(su_check(SU_RIGHT_MOTD)){
        $edit = true;
}
else
{
        $edit = false;
}

//Links zur MoTD und MoTC (beides Index)
$str_output  = '<center>`X[<a href="motd.php">`hMoTD-Index</a> `X| <a href="motd-coding.php?check=all">`hMoTC-Index</a>`X]</center><br />';
//Links zu den neuen Einträgen der MoTD/MoTC
if($edit) $str_output .= '<center>`X[<a href="motd.php?op=edit">`YMoTD / Umfrage erstellen</a> `X| <a href="motd-coding.php?op=neu">`YMoTC erstellen</a>`X]</center><br />';

switch ($_GET['op']){
        //Standard
        case "":
                $d = (isset($_GET['d']) ? $_GET['d'] : 'none');
                $bool_hotmotc = false;
                // Falls neue MoTC dabei war:
                if($session['user']['lastmotc'] == '0000-00-00 00:00:00') {
                        $bool_hotmotc = true;
                }
                //Alles, oder nichts?
                switch($_GET['check']){
                        case "all":
                                //Die ganzen Typen zählen...
                                $max = count($types);
                                //...und dann in die POST Variable true setzen
                                for($i=1;$i<=$max;$i++){
                                        $_POST['view'][$i] = true;
                                }
                        break;
                        case "none":
                                //Dann besser keine POST-View-Variable mehr...
                                unset($_POST['view']);
                                saveuser();
                        break;
                }

                //Auswahlknöpfe erzeugen
                $str_output .= '<script type="text/javascript">
                        function switchlayer(Layer_Name,Plumi_Name)
                        {
                          //location.href = "motd-coding.php?check=all&d=" + (document.getElementById(Layer_Name).style.display == "none" ? "block" : "none");
                          $("#"+Layer_Name).toggle("slow");

                          var which_src = (document.images[Plumi_Name].src.indexOf("images/plus.png") != -1) ? "images/minus.png" : "images/plus.png";
                          document.images[Plumi_Name].src = which_src;
                        }
                        </script>';
                $str_output .= '<form action="motd-coding.php?d=block" method="post">
                        <a href="#" onClick="javascript:switchlayer(\'motc\',\'plumi\');return false;"><img id="plumi" src="images/'.($d == 'none' ? 'plus' : 'minus').'.png" alt=""> `hSuchkriterien definieren:`0</a>`n
                        <div id="motc" style="display: '.$d.';">
                        `X[<a href="motd-coding.php?check=all&d=block">`YAlle auswählen</a> `X| <a href="motd-coding.php?check=none&d=block">`YKeine auswählen</a>`X]`n';
                foreach($types AS $key => $val){
                        $str_output .= '<input type="checkbox" name="view['.$key.']" value="1" onclick="this.form.submit();"'.($_POST['view'][$key]?' checked="checked"':'').'>`o'.$val['name'].'`n';
                }
                if(!is_null_or_empty($_POST['text'])) {
                        $str_search = str_replace('"','',stripslashes($_POST['text']));
                        
                        $str_search = db_real_escape_string($str_search);
                } else {
                        $str_search = false;
                }
                //Nur eingeloggte dürfen suchen
                if($session['user']['loggedin'])
                {
                        $str_output .= '`YNach Stichwort suchen: <input name="text" class="input" value="'.$str_search.'">`n';
                }
                $str_output .= '</div> <hr />';

                //eine WHERE-Bedingung definieren, was denn nun angezeigt werden soll...!
                $where = "WHERE '0'='1'"; //eine Fake ;)
                if(isset($_POST['view'])){
                        $where .= ' OR ( "0"="1"';
                        foreach($_POST['view'] AS $key => $val){
                                if($val){
                                        //Alle Typen die selected sind...
                                        $where .= " OR `m`.`type` = '".$key."'";
                                        //...aber nicht die evtl. nicht public sind!                                        
                                }
                        }
                        $where .= ')';
                }
                
                //Textkörpersuche
                $where .= (!is_null_or_empty($str_search))?' AND `m`.`body` LIKE "%'.$str_search.'%" OR `m`.`headline` LIKE "%'.$str_search.'%"':'';
                //Superuser sehen auch private einträge
                if(!$edit) 
                {
                        $where .= " AND `m`.`public` = '1' ";
                }

                //Alle Einträge zählen, die man sehen darf
                $sql = "
                        SELECT
                                COUNT(*) AS `anzahl`
                        FROM
                                `motd_coding` `m`
                        ".$where."
                ";
                $result = db_query($sql);
                $nr = db_fetch_assoc($result);

                if(!$nr['anzahl']){
                        //wenn noch nichts eingetragen ist...
                        $str_output .= "Nichts gefunden...";
                }
                else{
                        //Seiten - Knopf - START
                        $pagecount = ceil($nr['anzahl']/$per_page);
                        $page = ($_POST['page'])?$_POST['page']:1;
                        $from = ($page-1) * $per_page;
                        $select = '<center>-&#8212; MoTC-Archiv: <select name="page" size="1" onChange="this.form.submit();">';

                        for ($i=1; $i<=$pagecount; $i++){
                                $select .= '<option value="'.$i.'" '.(($page==$i)?'selected="selected"':'').'>Seite '.$i.'</option>';
                        }
                        $select .= '</select>  -&#8212;<input type="hidden" name="search" value="'.$search.'"></form></center>';
                        //Seiten - Knopf - ENDE

                        //Einträge aus der DB holen
                        $sql = "
                                SELECT
                                        `m`.*,
                                        `a`.`login`,
                                        UNIX_TIMESTAMP(`m`.`time`) AS `time`,
                                        `m`.`time` AS `datetime`
                                FROM
                                        `motd_coding` `m`
                                LEFT JOIN `accounts` `a` ON
                                        `a`.`acctid`         = `m`.`acctid`
                                ".$where."
                                ORDER BY
                                        `m`.`time`         DESC
                                LIMIT
                                        ".$from.",".$per_page."
                        ";

                        $result = db_query($sql);

                        //Einträge schreiben
                        
                        while($row = db_fetch_assoc($result)) {
                                if($bool_hotmotc) {
                                        $session['user']['lastmotc'] = $row['datetime'];
                                        $bool_hotmotc = false;
                                }
                                $str_output .= '`0<table cellpadding="0" width="100%"><tr><td align="left">';
                                //wenn acctid = 0, dann steht da Team als Autor + Autor schreiben
                                if(!$row['acctid']) $row['login'] = "Coding-Team";
                                $str_output .= "`&".$row['login'].": ";
                                
                                //Überschrift schreiben
                                $str_output .= '`b'.$row['headline'].'`b `0</td><td align="right">`t'.$types[$row['type']]['shortname'];

                                // Vermerken, falls nicht öffentlich (talion)
                                if(!$row['public']) {
                                        $str_output .= '`4 - Geheim`0';
                                }

                                //Edit- & Delete - Knöpfe
                                if($edit) $str_output .= ' `7[<a href="motd-coding.php?op=edit&id='.$row['id'].'">`&Edit</a>`7|<a href="motd-coding.php?op=save&act=del&id='.$row['id'].'" onClick="return confirm(\'Bist du sicher, dass dieser Eintrag gelöscht werden soll?\');">`&Del</a>`7]';

                                //Zeit schreiben
                                $str_output .= "`0</td></tr></table>`I`i[".strftime("%A, %e. %B %Y, %H:%M",$row['time'])."]`i<br />";

                                //Text ausgeben + Trennlinie schreiben
                                $str_output .= nl2br( stripslashes($row['body']) );
                                // Bei Public-Einträgen: Gesonderte Team-Info
                                if(su_lvl_check(1) && $row['public'] && !empty($row['body_team']))
                                {
                                        $str_output .= '`n`n`i`Y(Zusatzinfo fürs Team: '.nl2br( stripslashes($row['body_team']) ).')`i';
                                }
                                $str_output .= "`0<hr />\n";
                        }

                        //Zum Schluss noch den Seiten-Knopf dazu schreiben...
                        $str_output .= $select;
                }
        break;
        //Falls etwas neues eingetragen werden soll
        case "neu":
                //Hackerschutz - START
                if(!$edit){
                        if($session['user']['loggedin']){
                                killcheater();
                        }
                        exit;
                }
                //Hackerschutz - ENDE

                //Formular aufsetzen
                $str_output .= '
                <form action="motd-coding.php?op=save&act=new" method="post">
                        <table>
                                <tr>
                                        <td>Name:</td>
                                        <td>
                                                <select name="acctid" size="1">
                                                        <option value="0">0: Coding-Team</option>
                                                        <option value="'.$session['user']['acctid'].'" selected="selected">'.$session['user']['acctid'].': '.$session['user']['login'].'</option>
                                                </select>
                                        </td>
                                </tr>
                                <tr>
                                        <td>Überschrift:</td>
                                        <td><input type="text" name="headline" /></td>
                                </tr>
                                <tr>
                                        <td valign="top">Text:</td>
                                        <td><textarea name="body" class="input" cols="35" rows="8"></textarea></td>
                                </tr>
                                <tr>
                                        <td valign="top">Geheime Zusatzinfos fürs Team, falls öffentlicher Eintrag:</td>
                                        <td><input name="body_team" class="input" size="50" /></td>
                                </tr>
                                <tr>
                                        <td>Öffentlich?</td>
                                        <td><input type="checkbox" name="public" value="1" checked="checked" /></td>
                                </tr>
                                <tr>
                                        <td>Typ:</td>
                                        <td>
                                                <select name="type" size="1">';
                                        foreach($types AS $key => $val){
                                                $str_output .= '
                                                        <option value="'.$key.'" '.($key==="1"?'selected="selected"':'').'>'.$key.': '.$val['name'].'</option>';
                                        }
                $str_output .='
                                                </select>
                                        </td>
                                </tr>
                                <tr>
                                        <td></td>
                                        <td><input type="submit" value=" Absenden " /></td>
                                </tr>
                        </table>
                </form>
                `n
                `n
                `)Regenbogen-Färbung (Überschrift & Text):`n
                `LL `)& `MM`U &nbsp;~&nbsp; `{{ `)& `]]`U &nbsp;~&nbsp; `ww `)& `99`U &nbsp;~&nbsp; `pp `)& `GG`U &nbsp;~&nbsp; `hh `)& `FF`U &nbsp;~&nbsp; `qq `)& `QQ`U &nbsp;~&nbsp; `-- `)& `ôô`n`n
                `)Für nicht-öffentl. Einträge:`n
                `&& `)& `oo`)
                `n`n';
        break;
        //Wenn etwas editiert werden soll
        case "edit":
                //Hackerschutz - START
                if(!$edit){
                        if($session['user']['loggedin']){
                                killcheater();
                        }
                        exit;
                }
                //Hackerschutz - ENDE

                //Vollständige Daten abholen
                $sql = "
                        SELECT
                                `motd_coding`.*,
                                `accounts`.`login`
                        FROM
                                `motd_coding`
                        LEFT JOIN `accounts` ON
                                `accounts`.`acctid`         = `motd_coding`.`acctid`
                        WHERE
                                `motd_coding`.`id` = '".$_GET['id']."'
                ";
                $result = db_query($sql);
                $row = db_fetch_assoc($result);

                //Formular aufsetzen
                output($str_output.'
                        <form action="motd-coding.php?op=save&act=edit&id='.$row['id'].'" method="post">
                                <table>
                                        <tr>
                                                Editierung des Eintrags #'.$row['id'].'
                                        </tr>
                                        <tr>
                                                <td>Name:</td>
                                                <td>
                                                        <select name="acctid" size="1">
                                                                <option value="0"'.($row['acctid']?'':' selected="selected"').'>0: Coding-Team'.($row['acctid']?'':' (unverändert!)').'</option>
                                                                '.($row['acctid'] != $session['user']['acctid']?'<option value="'.$session['user']['acctid'].'">'.$session['user']['acctid'].': '.$session['user']['login'].'</option>':'').'
                                                                '.($row['acctid']?'<option value="-1" selected="selected">'.$row['acctid'].': '.$row['login'].' (unverändert!)</option>':'').'
                                                        </select>
                                                </td>
                                        </tr>
                                        <tr>
                                                <td>Zeit:</td>
                                                <td>'.$row['time'].' - Aktualisieren? <input type="checkbox" name="time" value="1" /></td>
                                        </tr>
                                        <tr>
                                                <td>Überschrift:</td>
                                                <td><input name="headline" value="');
                rawoutput($row['headline']);
                output('" /></td>
                                        </tr>
                                        <tr>
                                                <td valign="top">Text:</td>
                                                <td><textarea name="body" class="input" cols="35" rows="8">');
                rawoutput(stripslashes($row['body']));
                output('</textarea></td>
                                        </tr>
                                        <tr>
                                        <td valign="top">Geheime Zusatzinfos fürs Team, falls öffentlicher Eintrag:</td>
                                                <td><input name="body_team" class="input" size="50" maxlength="65535" value="'.$row['body_team'].'" /></td>
                                        </tr>
                                        <tr>
                                                <td>Öffentlich?</td>
                                                <td><input type="checkbox" name="public" value="1" '.($row['public']?'checked="checked"':'').' />
                                        </tr>
                                        <tr>
                                                <td>Type:</td>
                                                <td>
                                                        <select name="type" size="1">');
                foreach($types AS $key => $val){
                        output('<option value="'.$key.'" '.($row['type']== $key?'selected="selected"':'').'>'.$key.': '.$val['name'].'</option>');
                }
                output('
                                                        </select>
                                                </td>
                                        </tr>
                                        <tr>
                                                <td></td>
                                                <td><input type="submit" value=" Absenden " /></td>
                                        </tr>
                                </table>
                        </form>
                ');
                // Variable leeren, wird in diesem case nicht gebraucht
                $str_output = '';
        break;
        //Wenn etwas gespeichert werden soll
        case "save":
                //Hackschutz - START
                if(!$edit){
                        if($session['user']['loggedin']){
                                killcheater();
                        }
                        exit;
                }
                //Hackschutz - ENDE

                //Was genau soll gespeichert werden?
                switch($_GET['act']){
                        //Etwas neues!
                        case "new":

                                //Überschrift & Text vor Query-Manipulationen absichern
                                $_POST['headline'] = db_real_escape_string($_POST['headline']);
                                $_POST['body'] = db_real_escape_string($_POST['body']);
                                $_POST['body_team'] = db_real_escape_string($_POST['body_team']);

                                //public-Variable korrigieren ;)
                                $_POST['public'] == "1" ? $_POST['public'] = 1 : $_POST['public'] = 0;

                                //DB-Query verfassen
                                $sql = "
                                        INSERT INTO
                                                `motd_coding`
                                        SET
                                                `time`                = NOW(),
                                                `type`                = '".$_POST['type']."',
                                                `headline`        = '".$_POST['headline']."',
                                                `body`                = '".$_POST['body']."',
                                                `body_team`        = '".$_POST['body_team']."',
                                                `acctid`        = '".$_POST['acctid']."',
                                                `public`        = '".$_POST['public']."'
                                ";
                                if($_POST['public']) {
                                        $sql2 = "UPDATE accounts SET lastmotc = '0000-00-00 00:00:00' WHERE acctid!=".$session['user']['acctid'];
                                }
                        break;
                        //Etwas Editiertes
                        case "edit":

                                //Überschrift und Text vor Query-Manipulationen absichern
                                $_POST['headline'] = db_real_escape_string($_POST['headline']);
                                $_POST['body'] = db_real_escape_string($_POST['body']);
                                $_POST['body_team'] = db_real_escape_string($_POST['body_team']);

                                //public-, time- und acctid-Variable korrigieren, bzw. unsetten ;)
                                $_POST['public'] == "1" ? $_POST['public'] = 1 : $_POST['public'] = 0;
                                //if($_POST['time'] != "on")unset($_POST['time']);
                                if($_POST['acctid'] == (-1))unset($_POST['acctid']);

                                //DB-Query verfassen
                                $sql = "
                                        UPDATE
                                                `motd_coding`
                                        SET
                                                ".($_POST['time'] ?"`time` = NOW() ,":"")."
                                                `type`                = '".$_POST['type']."',
                                                `headline`         = '".$_POST['headline']."',
                                                `body`                = '".$_POST['body']."',
                                                `body_team` = '".$_POST['body_team']."',
                                                ".(isset($_POST['acctid'])?"`acctid` = ".$_POST['acctid']." ,":"")."
                                                `public`        = '".$_POST['public']."'
                                        WHERE
                                                `id`                = '".$_GET['id']."'
                                ";
                                if($_POST['time'] && $_POST['public']) {
                                        $sql2 = "UPDATE accounts SET lastmotc = '0000-00-00 00:00:00' WHERE acctid!=".$session['user']['acctid'];
                                }
                        break;
                        //Etwas löschen, bitte
                        case "del":

                                //DB-Query verfassen
                                $sql = "
                                        DELETE FROM
                                                `motd_coding`
                                        WHERE
                                                `id` = '".$_GET['id']."'
                                ";
                        break;
                        //Falls doch was schief gelaufen ist...
                        default:
                                die("Tjoar, hier stimmt wohl was nicht ;)");
                }//ENDE switch ($_GET['act'])

                //DB-Query ausführen
                db_query($sql);
                if(strlen($sql2) > 3) {
                        db_query($sql2);
                }

                //Weiterleiten, wenn alles erledigt ist...
                header('Location:motd-coding.php?check=all');
                exit;
        break;
}//ENDE switch($_GET['op'])

//gesammelte Zeichenkette ausgeben
output($str_output,true);

//und zum krönenden Abschluss noch den Pagefooter... fertisch <:P
popup_footer();
?>

