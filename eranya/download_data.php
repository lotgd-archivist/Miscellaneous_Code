
<?php
/* Spiel spass und freude beim userdata file download by Mr edah */
    require_once("common.php");
    function get_data() {
        
        global $session;
        
        $str_data = "\r\n*SAMMLUNG ALLER GESPEICHERTER PERSONENBEZOGENER DATEN - NUTZER-LOGIN: ".$session['user']['login']."*\r\n
\r\n";
        // Daten aus accounts sammeln
        $str_data .= "~E-Mail-Adresse: ".$session['user']['emailaddress']."\r\n
~IP: ".$session['user']['lastip']."\r\n\r\n";
        // end
        // Daten aus Kommentaren sammeln
        $str_data .= "~Chat-Kommentare~\r\n";
        $sql_c = db_query("SELECT comment, section, postdate FROM commentary WHERE author = ".$session['user']['acctid']." ORDER BY section ASC, postdate ASC");
        $z = db_num_rows($sql_c);
        if($z == 0) {
            $str_data .= "(keine Chat-Kommentare vorhanden)";            
        } else {
            for($i=0;$i<$z;$i++) {
                $row_c = db_fetch_assoc($sql_c);
                $str_data .= "(".$row_c['postdate'].", ".$row_c['section'].") - ".$row_c['comment']."\r\n";            
            }
        }
        // end
        // Daten aus Mails sammeln
        $str_data .= "\r\n~Versandte Posthoernchen~\r\n";
        $sql_m = db_query("SELECT subject, body, sent FROM mail WHERE msgfrom = ".$session['user']['acctid']." ORDER BY sent ASC");
        $z = db_num_rows($sql_m);
        if($z == 0) {
            $str_data .= "(keine Posthoernchen-Daten vorhanden)\r\n";            
        } else {
            for($i=0;$i<$z;$i++) {
                $row_m = db_fetch_assoc($sql_m);
                $str_data .= "(".$row_m['sent']."; Betreff: ".(strlen($row_m['subject']) > 0 ? $row_m['subject'] : "keiner").") - ".$row_m['body']."\r\n";            
            }
        }
        // end
        // Daten aus Bios sammeln
        $row_a = db_fetch_assoc(db_query("SELECT bio, rp_infos, together_with, charrace, charclass,
                                                 mount_category, mount_rp_infos, mountbio,
                                                 disc_rp_infos, discbio,
                                                 xchar_category, xchar_rp_infos, xcharbio,
                                                 addbio,
                                                 d.name AS discname
                                          FROM account_bios ab
                                                LEFT JOIN disciples d ON d.master = acctid
                                                     WHERE acctid = ".$session['user']['acctid']));
        $str_data .= "\r\n~Bio-Texte~\r\n";
        $str_data .= "(Biographie des Hauptcharakters) ".$row_a['bio']."\r\n
(Hauptcharakter-Details) ".implode(' --- ',mb_unserialize($row_a['rp_infos']))."\r\n
(Hauptcharakter-Rasse) ".$row_a['charrace']."\r\n
(Hauptcharakter-Klasse) ".$row_a['charclass']."\r\n";
        $str_data .= "(Biographie des Knappen) ".$row_a['discname'].": ".$row_a['discbio']."\r\n
(Knappendetails) ".implode(' --- ',mb_unserialize($row_a['disc_rp_infos']))."\r\n";
        $str_data .= "(Biographie des 1. Zusatz-Charakters) ".$row_a['mount_category'].": ".$row_a['mountbio']."\r\n
(Details des 1. Zusatz-Charakters) ".implode(' --- ',mb_unserialize($row_a['mount_rp_infos']))."\r\n";
        $str_data .= "(Biographie des 2. Zusatz-Charakters) ".$row_a['xchar_category'].": ".$row_a['xcharbio']."\r\n
(Details des 2. Zusatz-Charakters) ".implode(' --- ',mb_unserialize($row_a['xchar_rp_infos']))."\r\n";
        $str_data .= "(Testfeld-Inhalt) ".$row_a['addbio']."\r\n
\r\n";
        $str_data .= "(Beziehungsstatus aller Charaktere) ";
        $arr_tgw = mb_unserialize($row_a['together_with']);
        foreach($arr_tgw AS $k => $v) {
            if(strpos($k,'together_type') !== false) {
                $str_data .= $v.": ";
            } elseif(strpos($k,'together_who') !== false) {
                $str_data .= $v." ";
            } elseif(strpos($k,'together_login') !== false) {
                $str_data .= "(".$v.")";
            }
            $str_data .= "\r\n";    
        }
        // end
        // Daten aus Anfragen sammeln
        $str_data .= "\r\n\r\n~Anfragen~\r\n";
        $sql_p = db_query("SELECT petitionid, date, title, body
                           FROM petitions
                                 WHERE author = ".$session['user']['acctid']);
        $z = db_num_rows($sql_p);
        if($z == 0) {
            $str_data .= "(keine Anfragen-Daten vorhanden)";            
        } else {
            for($i=0;$i<$z;$i++) {
                $row_p = db_fetch_assoc($sql_p);
                $str_data .= "(Datum: ".$row_p['date']." - Titel: ".$row_p['title'].") ".$row_p['body']."\r\n";
                // ggf. Anfragen-Mails dazuholen
                $sql_pm = db_query("SELECT subject, body AS pmbody, sent
                                    FROM petitionmail 
                                          WHERE msgfrom = ".$session['user']['acctid']." AND petitionid = ".$row_p['petitionid']);
                $z2 = db_num_rows($sql_pm);
                if($z2 > 0) {
                    $str_data .= "ZugehÃ¶rige Nachrichten:\r\n";
                    for($j=0;$j<$z2;$j++) {
                        $row_pm = db_fetch_assoc($sql_pm);
                        $str_data .= $row_pm['subjebt'].": ".$row_pm['pmbody']." (Datum: ".$row_pm['sent'].")\r\n";
                    }
                }
            }
        }
        // end
        $str_data .= "\r\n\r\n*ENDE*";
        return $str_data;    
    }
    $handle = fopen("data.txt", "w");
    fwrite($handle, get_data());
    fclose($handle);
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename('data.txt'));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize('data.txt'));
    readfile('data.txt');
    exit;
    //TODO delete my file
?>

