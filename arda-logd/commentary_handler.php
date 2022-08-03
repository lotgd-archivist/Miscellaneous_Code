<?php
/**
 * Handler for editing and deleting posts
 * 
 * @author        Basilius Sauter
 * @copyright    2006-2009
 * @version        2.99
 * @package        commentary
 */
 
Require_once 'common.php';

# Wichtige Annahme für diese Datei: Alle Links auf diese Datei sind rechtens.
# Das heisst, dass Kommentare bearbeit- und löschbar sind, egal, wer der eigentliche
# Autor ist. Beim verlinken auf diese Datei muss darauf geachtet werden, dass der Link
# nur in die "allowednavs" kommt, wenn der User den Kommentar rechtmässig bearbeiten
# darf.

$header = 'Commentary handler';

if(isset($_GET['return'])) {
    $returnlink = Commentary::decodeStringForURL($_GET['return']);
}
else {
    $returnlink = false;
    $_GET['return'] = Commentary::encodeStringForURL('village.php');
}

if(isset($_GET['id'])) {
    $commentid = $_GET['id'];
}
else {
    $commentid = false;
}

$noreturn = false;

if(COMMENTARY_USE_COMPABILITY_MODE) {
    $user =& $session['user'];
}

switch($_GET['q']) {
    # Kommentar editieren
    case 'edit': {
        $header = 'Post editieren';
        if($commentid !== false) {
            # Kommentar holen
            $sql = 'SELECT author, emote, comment FROM commentary WHERE commentid = :id AND locked = 0';
            $args = array(':id' => $commentid);
            
            if(COMMENTARY_USE_COMPABILITY_MODE) {
                $res = db_query_secure($sql, $args);
                
                if(db_num_rows($res) > 0) {
                    $row = db_Fetch_Assoc($res);
                }
                else {
                    $row = false;
                }
            }
            else {
                $res = new Query($sql, $args);
                
                if(count($res) > 0) {
                    $row = $res[0];
                }
                else {
                    $row = false;
                }
            }
            
            # Kommentar gefunden
            if($row !== false) {
                # Kommentar präparieren
                switch($row['emote']) {
                    case 0:
                        $comment = $row['comment'];
                        break;
                    
                    case 1:
                        $comment = '/me '.$row['comment'];
                        break;
                    
                    case 2:
                        $comment = '/X '.$row['comment'];
                        break;
                    
                    case 3:
                        $comment = '/ms '.$row['comment'];
                        break;            
                }
                
                # Feld anzeigen
                $commentary = new Commentary(&$user, 'nosection');
                $commentary->Field('commentary_handler.php?q=saveedit&id='.$commentid.'&return='.$_GET['return'], $comment);
            }
        }
        
        # Für den Fall, dass es keinen Kommentar gibt
        if($commentid === false OR $row === false) {
            rawoutput('<p>Der gewünschte Kommentar wurde nicht gefunden.</p>');
        }
        break;
    }
    
    case 'saveedit': {
        $commentary = new Commentary(&$user, 'nosection');
        $commentary->edit($_GET['id'], $_POST['commentary']);
        
        if(COMMENTARY_USE_COMPABILITY_MODE) {
            redirect($returnlink);
        }
        else {
            $session->redirect($returnlink);
        }
        break;
    }
        
    case 'delete': {
        $sql = "DELETE FROM commentary WHERE commentid=:id AND locked = 0";
        $args = array(':id' => $_GET['id']);

        if(COMMENTARY_USE_COMPABILITY_MODE) {
            db_query_secure($sql, $args);
            redirect($returnlink);
        }
        else {
            $res = new Query($sql, $args);
            $session->redirect($returnlink);
        }
        break;
    }
        
    default: {
        $header = 'Verirrt?';
        rawoutput('<p>Wie bist du hier hin gekommen?</p>');
        addnav('Zum Dorf');
        addnav('village.php');
        $noreturn = true;
        break;
    }
}

if(!$noreturn) {
    if($returnlink === false) {
        addnav('Zum Dorf');
        addnav('village.php');
    }
    else {
        addnav('Zurück');
        addnav('Zurück', $returnlink);
    }
}

page_header($header);
page_footer();
?> 