<?
/**
* Schachspiel für LotgD 0.9.7
* written by Salator (salator@gmx.de) based on webchess 0.8.4 (http://webchess.sourceforge.net)
* Installation siehe chess/docs/install.txt
*/

/* server settings */
$CFG_EXPIREGAME = 14;    /* number of days before untouched games expire */
$str_extpath='chess/'; //Unterverzeichnis für Zusatz-Scripte und Grafiken
$dateformat="d.m.y, H:i"; //Ausgabeformat für Zeitangaben
define ("CHESS_HOF", 1);    /* eine Kategorie in der Ruhmeshalle führen? */

define ("EMPTY", 0);    /* 0000 0000 */
define ("PAWN", 1);        /* 0000 0001 */
define ("KNIGHT", 2);    /* 0000 0010 */
define ("BISHOP", 4);    /* 0000 0100 */
define ("ROOK", 8);        /* 0000 1000 */
define ("QUEEN", 16);    /* 0001 0000 */
define ("KING", 32);    /* 0010 0000 */
define ("BLACK", 128);    /* 1000 0000 */
define ("WHITE", 0);
define ("COLOR_MASK", 127);    /* 0111 1111 */

/* Ende Settings */

$str_filename=basename(__FILE__);
require_once('common.php');
require($str_extpath.'newgame.php');
require($str_extpath.'chessdb.php');
require($str_extpath.'chessutils.php');

page_header('Schach');

switch($_GET['op'])
{
case 'InvitePlayer': // Spiel beginnen
{
    if($_POST['name']>'') //Namenssuche abgeschickt
    {
        $name=str_create_search_string($_POST['name']);
        $sql='SELECT login,acctid
            FROM accounts
            WHERE name LIKE "'.$name.'"
            AND acctid != '.$session['user']['acctid'].'
            ORDER BY login="'.mysql_real_escape_string($_POST['name']).'" DESC, login ASC
            LIMIT 50';
        $result=db_query($sql);
        $rows=db_num_rows($result);
        
        if($rows==0)
        {
            $str_out.='Es gibt niemanden, der auf diesen Namen hört. Versuche es noch einmal!';
            addnav('Neue Suche',$str_filename.'?op=InvitePlayer');
        }
        elseif($rows>99)
        {
            $str_out.='Es treffen zu viele Namen auf diese Suche zu. Nur die ersten 50 werden angezeigt.';
            addnav('Neue Suche',$str_filename.'?op=InvitePlayer');
        }
        if($rows>0)
        {
            $str_out.='<form action="'.$str_filename.'" method="post">
            <input type="hidden" name="ToDo" value="InvitePlayer">
            <table>
            <tr>
            <td>Spieleinsatz:</td>
            <td><select name="bet">
            <option value=0>Nichts</option>
            <option value=100>100 Gold</option>
            <option value=500>500 Gold</option>
            <option value=1000>1000 Gold</option>
            <option value=1>1 Edelstein</option>
            <option value=2>2 Edelsteine</option>
            </select></td>
            </tr>
            <tr>
            <td>Spiel gegen:</td>
            <td>
                <select name="opponent">';
            while($row=db_fetch_assoc($result))
            {
                $str_out.='<option value="'.$row['acctid'].'"> '.$row['login'].'</option>';
            }
            $str_out.='</select>
            </td>
            </tr>

            <tr valign="top">
            <td>Deine Farbe:</td>
            <td>
                <input type="radio" name="color" value="random" checked> zufällig
                <br>
                <input type="radio" name="color" value="white"> Weiß
                <br>
                <input type="radio" name="color" value="black"> Schwarz
            </td>
        </tr>

        <tr>
            <td colspan="2">
                <input type="submit" class="button" value="Herausfordern">
            </td>
        </tr>
        </table>
        </form>
        `n`nHinweis zum Spieleinsatz:
        `nBeide Spieler zahlen diesen Betrag in den Pot, welchen der Gewinner der Partie bekommt.
        `nEine Rückgabe der Beträge beim Zurückziehen der Herausforderung ist nicht möglich.'; //sonst könnte man auf die Idee kommen, vor dem DK ein paar Karteileichen herauszufordern
        addnav('',$str_filename);
        }
    }
    else
    {
        $str_out.='Wen willst du zu einer Partie Schach herausfordern?
        <form action="'.$str_filename.'?op=InvitePlayer" method="POST">
        <input type="text" name="name" id="name">
        <input type="submit" class="button" value="Suchen">
        </form>';
        addnav('',$str_filename.'?op=InvitePlayer');
    }
    addnav('Zurück');
addnav('Zur Übersicht',$str_filename);
    break;
}

case 'viewboard': //Spiel, Brettansicht
{
    if(!$_SESSION['gameID'] && !isset($_POST['gameID']))
        redirect ($str_filename);
    require $str_extpath.'gui.php';
    require $str_extpath.'move.php';
    require $str_extpath.'undo.php';

    /* check if loading game */
    if (isset($_POST['gameID']))
        $_SESSION['gameID'] = $_POST['gameID'];
    
    /* debug flag */
    define ("DEBUG", 0);

    /* load game */
    $isInCheck = ($_POST['isInCheck'] == 'true');
    $isCheckMate = false;
    $isPromoting = false;
    $isUndoing = false;
    loadHistory();
    loadGame();
    processMessages();

    if ($isUndoing)
    {
        doUndo();
        saveGame();
    }
    elseif (($_POST['promotion'] != "") && ($_POST['toRow'] != "") && ($_POST['toCol'] != ""))
    {
        savePromotion();
        $board[$_POST['toRow']][$_POST['toCol']] = $_POST['promotion'] | ($board[$_POST['toRow']][$_POST['toCol']] & BLACK);
        saveGame();
    }
    elseif (($_POST['fromRow'] != "") && ($_POST['fromCol'] != "") && ($_POST['toRow'] != "") && ($_POST['toCol'] != ""))
    {
        /* ensure it's the current player moving                 */
        /* NOTE: if not, this will currently ignore the command...               */
        /*       perhaps the status should be instead?                           */
        /*       (Could be confusing to player if they double-click or something */
        $tmpIsValid = true;
        if (($numMoves == -1) || ($numMoves % 2 == 1))
        {
            /* White's move... ensure that piece being moved is white */
            if ((($board[$_POST['fromRow']][$_POST['fromCol']] & BLACK) != 0) || ($board[$_POST['fromRow']][$_POST['fromCol']] == 0))
                /* invalid move */
                $tmpIsValid = false;
        }
        else
        {
            /* Black's move... ensure that piece being moved is black */
            if ((($board[$_POST['fromRow']][$_POST['fromCol']] & BLACK) != BLACK) || ($board[$_POST['fromRow']][$_POST['fromCol']] == 0))
                /* invalid move */
                $tmpIsValid = false;
        }
        
        if ($tmpIsValid)
        {
            saveHistory();
            doMove();
            saveGame();
        }
    }

    /* find out if it's the current player's turn */
    if (( (($numMoves == -1) || (($numMoves % 2) == 1)) && ($playersColor == "white"))
            || ((($numMoves % 2) == 0) && ($playersColor == "black")))
        $isPlayersTurn = true;
    else
        $isPlayersTurn = false;
    
    if ($_SESSION['isSharedPC'])
        $str_out.="`c`bDrachenschach`b`c`n";
    else if ($isPlayersTurn)
        $str_out.="`c`bDrachenschach - Dein Zug`b`c`n";
    else
        $str_out.="`c`bDrachenschach - Warte auf Zug des Gegners`b`c`n";
    
    //echo("<meta HTTP-EQUIV='Pragma' CONTENT='no-cache'>\n");
    
    $str_out.='<script type="text/javascript">';
    /* transfer board data to javacripts */
    $str_out.=writeJSboard();
    $str_out.=writeJShistory();

    $str_out.='if (DEBUG)
    alert("Game initilization complete!");
</script>

<script type="text/javascript" src="'.$str_extpath.'javascript/chessutils.js">
 /* these are utility functions used by other functions */
</script>

<script type="text/javascript" src="'.$str_extpath.'javascript/commands.js">
// these functions interact with the server
</script>

<script type="text/javascript" src="'.$str_extpath.'javascript/validation.js">
// these functions are used to test the validity of moves
</script>

<script type="text/javascript" src="'.$str_extpath.'javascript/isCheckMate.js">
// these functions are used to test the validity of moves
</script>

<script type="text/javascript" src="'.$str_extpath.'javascript/squareclicked.js">
// this is the main function that interacts with the user everytime they click on a square
</script>

<table border="0">
<tr valign="top"><td>

    `c<form name="gamedata" method="post" action="'.$str_filename.'?op=viewboard">';

        if ($isPromoting)
            $str_out.=writePromotion();

        if ($isUndoRequested)
            $str_out.=writeUndoRequest();

        if ($isDrawRequested)
            $str_out.=writeDrawRequest();

    $str_out.=drawboard();

    $str_out.='<table border="0">
    <tr><td nowrap>
    <input type="button" name="btnReload" value="Reload" onClick="window.open(\'chess.php?op=viewboard\', \'_self\')">
    <input type="button" name="btnUndo" value="Erbitte Undo"'.(isBoardDisabled()?"disabled='yes'":"onClick='undo()'").'>
    <input type="button" name="btnDraw" value="Remis anbieten"'.(isBoardDisabled()?"disabled='yes'":"onClick='draw()'").'>
    <input type="button" name="btnResign" value="Aufgeben"'.(isBoardDisabled()?"disabled='yes'":"onClick='resigngame()'").'>
    <!--input type="button" name="btnMainMenu" value="Startseite" onClick="window.open(\'chess.php\', \'_self\')"-->
    <!--input type="button" name="btnLogout" value="Logout" onClick="logout()"-->
    <input type="hidden" name="ToDo" value="Logout">    <!-- NOTE: this field is only used to Logout -->
    </td></tr>
    </table>

    <input type="hidden" name="requestUndo" value="no">
    <input type="hidden" name="requestDraw" value="no">
    <input type="hidden" name="resign" value="no">
    <input type="hidden" name="fromRow" value="'.(isPromoting?$_POST['fromRow']:'').'">
    <input type="hidden" name="fromCol" value="'.(isPromoting?$_POST['fromCol']:'').'">
    <input type="hidden" name="toRow" value="'.(isPromoting?$_POST['toRow']:'').'">
    <input type="hidden" name="toCol" value="'.(isPromoting?$_POST['toCol']:'').'">
    <input type="hidden" name="isInCheck" value="false">
    <input type="hidden" name="isCheckMate" value="false">
    </form>`c

    <p>Hinweise:
    `nFür eine Rochade muss nur der König gezogen werden, der Turm zieht automatisch.
    '.($bet>0?'`nDer Gewinner dieses Spiels erhält '.($bet*2).($bet<10?' Edelsteine.':' Gold.'):'').'</p>
    </td>

    <td width="50">&nbsp;</td>

    <td>'.writeStatus().'
        <br>'.writeHistory().'
    </td></tr>
    </table>';

    if($session['user']['prefs']['chesschat']>0)
    {
        //require('lib/commentary.lib.php');
        addcommentary();
        output($str_out.'`n`n',true);
        $str_out='';
$chat = 'chess_'.min($session['user']['acctid'],$board['opponent']).'_'.max($session['user']['acctid'],$board['opponent']);
        viewcommentary($chat,'Hinzufügen',(int)$session['user']['prefs']['chesschat'],'sagt',false,true,false,0,false,false,2);
    }

    /* if it's not the player's turn, enable auto-refresh */
    //Reload-Interval startet bei 15s und erhöht sich jeweils um 2s. Abbruch, wenn 60s erreicht sind (tritt nach 14 Minuten ein)
    if (!$isPlayersTurn && !isBoardDisabled() && $_GET['rs']<60 && $session['user']['prefs']['chessreload']!='no')
    {
        $countDownInterval=(isset($_GET['rs'])?min(60,(int)$_GET['rs']):15);
        $link=$str_filename.'?op=viewboard&rs='.($countDownInterval+2);
        addnav('',$link);
        $str_out.='<script type="text/javascript">
var countDownInterval='.$countDownInterval.';
var countDownTime=countDownInterval;

function countDown()
{
    --countDownTime;
    if (countDownTime < 0)
    {
        countDownTime=countDownInterval;
    }
    document.gamedata.btnReload.value = "Reload (" + countDownTime + ")";
    setTimeout("countDown()", 1000);
    if (countDownTime == 0)
    {
        location.href="'.$link.'";
    }
}
countDown();
</script>
';
    }

    addnav('Aktualisieren',$str_filename.'?op=viewboard');
    addnav('Zurück');
    addnav('Zur Übersicht',$str_filename);
    addnav('',$str_filename);
    addnav('',$str_filename.'?op=viewboard');
    break;
}

default: //Start- und Übersichtsseite
{
    /* cleanup dead games */
    if(!$_SESSION['gameID']) //DB-Zugriffe reduzieren, nur prüfen wenn man neu reinkommt
    {
        /* determine threshold for oldest game permitted */
        $targetDate = date("Y-m-d", mktime(0,0,0, date('m'), date('d') - $CFG_EXPIREGAME, date('Y')));

        /* find out which games are older */
        $sql = "SELECT gameID FROM chessgames WHERE lastMove < '".$targetDate."'";
        $tmpOldGames = db_query($sql);

        /* for each older game... */
        while($tmpOldGame = db_fetch_assoc($tmpOldGames))
        {
            /* ... clear the history... */
            db_query("DELETE FROM chesshistory WHERE gameID = ".$tmpOldGame['gameID']);

            /* ... and the board... */
            //db_query("DELETE FROM pieces WHERE gameID = ".$tmpOldGame['gameID']);
            
            /* ... and the messages... */
            db_query("DELETE FROM chessmessages WHERE gameID = ".$tmpOldGame['gameID']);
            
            /* ... and finally the game itself from the database */
            db_query("DELETE FROM chessgames WHERE gameID = ".$tmpOldGame['gameID']);
        }
    }

    switch($_POST['ToDo'])
    {
    case 'InvitePlayer':
        $bet=(int)$_POST['bet'];
        //Spieler zahlungsfähig?
        
        $what=($bet<10?'gems':'gold');
        if($session['user'][$what]>=$bet)
        {
            /* prevent multiple pending requests between two players with the same originator */
            $tmpQuery = "SELECT gameID FROM chessgames WHERE gameMessage = 'playerInvited'";
            $tmpQuery .= " AND ((messageFrom = 'white' AND whitePlayer = ".$session['user']['acctid']." AND blackPlayer = ".$_POST['opponent'].")";
            $tmpQuery .= " OR (messageFrom = 'black' AND whitePlayer = ".$_POST['opponent']." AND blackPlayer = ".$session['user']['acctid']."))";
            $tmpExistingRequests = db_query($tmpQuery);
            
            if (db_num_rows($tmpExistingRequests) == 0)
            {
                $bet=(int)$_POST['bet'];
                $opp=db_fetch_assoc(db_query('SELECT login FROM accounts WHERE acctid='.$_POST['opponent']));
                if ($_POST['color'] == 'random')
                    $tmpColor = (e_rand(0,1) == 1) ? "white" : "black";
                else
                    $tmpColor = $_POST['color'];

                $tmpQuery = 'INSERT INTO chessgames (whitePlayer, whiteName, blackPlayer, blackName, gameMessage, messageFrom, dateCreated, lastMove, pieces, bet) VALUES (';
                if ($tmpColor == 'white')
                    $tmpQuery .= $session['user']['acctid'].', "'.addslashes($session['user']['login']).'", '.$_POST['opponent'].', "'.addslashes($opp['login']);
                else
                    $tmpQuery .= $_POST['opponent'].', "'.addslashes($opp['login']).'", '.$session['user']['acctid'].', "'.addslashes($session['user']['login']);
            
                $tmpQuery .= '", "playerInvited", "'.$tmpColor.'", NOW(), NOW(), "", '.$bet.')';
                db_query($tmpQuery);
                
                $mailtext='`0Du wurdest von '.$session['user']['name'];

                if(CHESS_HOF)
                {
                    $result=db_query('SELECT count(*) AS c
                        FROM accounts
                        WHERE chessgameswon !=0
                            AND chessgameswon>(SELECT chessgameswon FROM accounts WHERE acctid='.$session['user']['acctid'].')');
                    $chessrank = mysql_result($result,0) +1;
                    $mailtext.=(' `6(in der Rangliste etwa Nummer '.$chessrank.')');
                }
                
                $mailtext.='`0 zu einer Partie Schach herausgefordert. '
                .($bet>0?'`nDer Spieleinsatz wurde auf '.$bet.($bet<10?' Edelsteine':' Gold').' festgelegt.':'')
                .'`n'.$session['user']['login'].' wartet in der Schenke auf Dich.';
                
                $session['user'][$what]-=$bet;
                systemmail($_POST['opponent'],'Herausforderung Schach', $mailtext);
            }
        }
        else
        {
            $str_out.='`$Leider kannst du den Spieleinsatz nicht bezahlen`0';
        }
        break;

        case 'ResponseToInvite':
            $gameID=(int)$_POST['gameID'];
            if ($_POST['response'] == 'accepted')
            {
                $tmpGame=db_fetch_assoc(db_query('SELECT * FROM chessgames WHERE gameID='.$gameID));
                $what=($tmpGame['bet']<10?'gems':'gold');
                if($session['user'][$what]>=$tmpGame['bet'])
                {
                    /* update game data */
                    $tmpQuery = "UPDATE chessgames SET gameMessage = '', messageFrom = '' WHERE gameID = ".$gameID;
                    db_query($tmpQuery);

                    /* setup new board */
                    $_SESSION['gameID'] = $gameID;
                    createNewGame($gameID); //newgame.php
                    saveGame(); //chessdb.php
                    $session['user'][$what]-=$tmpGame['bet'];
                }
                else
                {
                    $str_out.='`$Leider kannst du den Spieleinsatz nicht bezahlen`0';
                }
            }
            else
            {
                $tmpQuery = "UPDATE chessgames SET gameMessage = 'inviteDeclined', messageFrom = '".$_POST['messageFrom']."' WHERE gameID = ".$gameID;
                db_query($tmpQuery);
            }
            
            break;

        case 'WithdrawRequest':
            
            /* get opponent's player ID */
            $result = db_query("SELECT whitePlayer,blackPlayer,gameMessage FROM chessgames WHERE gameID = ".$_POST['gameID']);
            if (db_num_rows($result) > 0)
            {
                $row=db_fetch_assoc($result);

                $opponentID=($row['whitePlayer']==$session['user']['acctid']?$row['blackPlayer']:$row['whitePlayer']);
            
                $tmpQuery = "DELETE FROM chessgames WHERE gameID = ".$_POST['gameID'];
                db_query($tmpQuery);
                
                if($row['gameMessage']=='playerInvited')
                    systemmail($opponentID,'Herausforderung zurückgezogen',$session['user']['name'].' hat '.($session['user']['sex']?'ihre':'seine').' Herausforderung zum Schachspiel zurückgezogen.');
            }
            break;

        case 'UpdatePrefs':
            
            if($_POST['rdoReload']=='no')
                $session['user']['prefs']['chessreload']='no';
            else
                unset($session['user']['prefs']['chessreload']);
            
            if($_POST['rdoHistory']=='pgn')
                $session['user']['prefs']['chesshistory']='pgn';
            else
                unset($session['user']['prefs']['chesshistory']);
            
            if($_POST['rdoChat']>0)
            {
                $session['user']['prefs']['chesschat']=(int)$_POST['rdoChat'];
                $session['user']['prefs']['chessreload']='no'; //Seitenreload ist ganz schlecht während man tippt
            }
            else
                unset($session['user']['prefs']['chesschat']);
            
            break;
    }

    $str_out.='`c`b`nDrachenschach - Übersicht und Einstellungen`0`b`c`n
    <script type="text/javascript">
        function validatePersonalInfo()
        {
            if (document.PersonalInfo.txtFirstName.value == ""
                || document.PersonalInfo.txtLastName.value == ""
                            || document.PersonalInfo.pwdOldPassword.value == ""
                || document.PersonalInfo.pwdPassword.value == "")
            {
                alert("Sorry, all personal info fields are required and must be filled out.");
                return;
            }

            if (document.PersonalInfo.pwdPassword.value == document.PersonalInfo.pwdPassword2.value)
                document.PersonalInfo.submit();
            else
                alert("Sorry, the two password fields don\'t match.  Please try again.");
        }
        
        function sendResponse(responseType, messageFrom, gameID)
        {
            document.responseToInvite.response.value = responseType;
            document.responseToInvite.messageFrom.value = messageFrom;
            document.responseToInvite.gameID.value = gameID;
            document.responseToInvite.submit();
        }

        function loadGame(gameID)
        {
            /*if (document.existingGames.rdoShare[0].checked)
                document.existingGames.action = "opponentspassword.php";*/

            document.existingGames.gameID.value = gameID;
            document.existingGames.submit();
        }

        function withdrawRequest(gameID)
        {
            document.withdrawRequestForm.gameID.value = gameID;
            document.withdrawRequestForm.submit();
        }

        function loadEndedGame(gameID)
        {
            document.existingGames.gameID.value = gameID;
            document.existingGames.submit();
        }

    </script>

    Deine offenen Herausforderungen`n`n
    <form name="withdrawRequestForm" action="'.$str_filename.'" method="post">
    <table border="1" width="550" align="center">

    <tr class="trhead">
        <th>Gegner</th>
        <th>Deine&nbsp;Farbe</th>
        <th>Einsatz</th>
        <th>Status</th>
        <th>zurückziehen</th>
    </tr>
    ';
    addnav('',$str_filename);
    /* if game is marked playerInvited and the invite is from the current player */
    $sql = "SELECT *
    FROM chessgames
    WHERE (gameMessage = 'playerInvited'
        AND ((whitePlayer = ".$session['user']['acctid']." AND messageFrom = 'white')
            OR (blackPlayer = ".$session['user']['acctid']." AND messageFrom = 'black'))";
    
    /* OR game is marked inviteDeclined and the response is from the opponent */
    $sql .= ")
        OR (gameMessage = 'inviteDeclined'
        AND ((whitePlayer = ".$session['user']['acctid']." AND messageFrom = 'black')
            OR (blackPlayer = ".$session['user']['acctid']." AND messageFrom = 'white')))
    ORDER BY dateCreated";
    $result=db_query($sql);
    
    if (db_num_rows($result) == 0)
    {
        $str_out.="<tr><td colspan='5'>Du hast im Moment niemand eingeladen</td></tr>\n";
    }
    else
    {
        while($tmpGame = db_fetch_assoc($result))
        {
            /* Opponent */
            $str_out.="<tr><td>";
            /* get opponent's nick */
            if ($tmpGame['whitePlayer'] == $session['user']['acctid'])
                $opponent=$tmpGame['blackName'];
            else
                $opponent=$tmpGame['whiteName'];
            $str_out.=$opponent;

            /* Your Color */
            $str_out.="</td><td>";
            if ($tmpGame['whitePlayer'] == $session['user']['acctid'])
                $str_out.="Weiß";
            else
                $str_out.="Schwarz";

            //Einsatz
            $str_out.='</td><td>'.$tmpGame['bet'].($tmpGame['bet']==0?'':($tmpGame['bet']<10?' ES':' Gold')).'</td>';

            /* Status */
            $str_out.="<td>";
            if ($tmpGame['gameMessage'] == 'playerInvited')
                $str_out.="Warte auf Antwort";
            else if ($tmpGame['gameMessage'] == 'inviteDeclined')
                $str_out.="Abgelehnt";
            
            /* Withdraw Request */
            $str_out.="</td><td align='center'>
            <input type='button' value='Zurückziehen' onclick=\"withdrawRequest(".$tmpGame['gameID'].")\">
            </td></tr>\n";
        }
    }
    $str_out.='</table>
    <input type="hidden" name="gameID" value="">
    <input type="hidden" name="ToDo" value="WithdrawRequest">
    </form>
    <br>
    Offene Herausforderungen anderer Spieler an dich`n`n
    <form name="responseToInvite" action="'.$str_filename.'" method="post">
    <table border="1" width="550" align="center">
    <tr class="trhead">
        <th>Gegner</th>
        <th>Deine Farbe</th>
        <th>Einsatz</th>
        <th>Aktion</th>
    </tr>
';
    addnav('',$str_filename);
    $sql = "SELECT *
    FROM chessgames
    WHERE gameMessage = 'playerInvited'
        AND ((whitePlayer = ".$session['user']['acctid']." AND messageFrom = 'black')
        OR (blackPlayer = ".$session['user']['acctid']." AND messageFrom = 'white'))
    ORDER BY dateCreated";
    $result = db_query($sql);
    
    if (db_num_rows($result) == 0)
        $str_out.="<tr><td colspan='4'>Du hast im Moment keine Herausforderungen zu einer Partie</td></tr>\n";
    else
        while($tmpGame = db_fetch_assoc($result))
        {
            /* Opponent */
            $str_out.="<tr><td>";
            /* get opponent's nick */
            if ($tmpGame['whitePlayer'] == $session['user']['acctid'])
                $opponent=$tmpGame['blackName'];
            else
                $opponent=$tmpGame['whiteName'];
            $str_out.=$opponent;

            /* Your Color */
            $str_out.="</td><td>";
            if ($tmpGame['whitePlayer'] == $session['user']['acctid'])
            {
                $str_out.="Weiß";
                $tmpFrom = "white";
            }
            else
            {
                $str_out.="Schwarz";
                $tmpFrom = "black";
            }

            //Einsatz
            $str_out.='</td><td>'.$tmpGame['bet'].($tmpGame['bet']==0?'':($tmpGame['bet']<10?' ES':' Gold')).'</td>';

            /* Response */
            $str_out.="</td><td align='center'>
            <input type='button' value='Annehmen' onclick=\"sendResponse('accepted', '".$tmpFrom."', ".$tmpGame['gameID'].")\">
            <input type='button' value='Ablehnen' onclick=\"sendResponse('declined', '".$tmpFrom."', ".$tmpGame['gameID'].")\">
            </td></tr>\n";
        }
    $str_out.='</table>
    <input type="hidden" name="response" value="">
    <input type="hidden" name="messageFrom" value="">
    <input type="hidden" name="gameID" value="">
    <input type="hidden" name="ToDo" value="ResponseToInvite">
    </form>
    <br>
    Partie fortsetzen:`n`n

    <form name="existingGames" action="'.$str_filename.'?op=viewboard" method="post">
    <table border="1" width="550" align="center">
    <tr class="trhead">
        <th>Gegner</th>
        <th>Deine&nbsp;Farbe</th>
        <th>Am&nbsp;Zug</th>
        <th>Startzeit</th>
        <th>Letzter&nbsp;Zug</th>
    </tr>
';
    addnav('',$str_filename.'?op=viewboard');
    $sql="SELECT *
    FROM chessgames
    WHERE gameMessage = ''
        AND (whitePlayer = ".$session['user']['acctid']."
        OR blackPlayer = ".$session['user']['acctid'].")
    ORDER BY dateCreated";
    
    $result=db_Query($sql);
    if (db_num_rows($result) == 0)
        $str_out.="<tr><td colspan='6'>Du hast derzeit kein angefangenes Spiel</td></tr>\n";
    else
    {
        while($tmpGame = db_fetch_assoc($result))
        {
            /* Opponent */
            $str_out.="<tr><td>";
            /* get opponent's nick */
            if ($tmpGame['whitePlayer'] == $session['user']['acctid'])
                $opponent=$tmpGame['blackName'];
            else
                $opponent=$tmpGame['whiteName'];
            
            $str_out.="<a href='javascript:loadGame(".$tmpGame['gameID'].")'>".$opponent."</a>";

            /* Your Color */
            $str_out.="</td><td>";
            if ($tmpGame['whitePlayer'] == $session['user']['acctid'])
            {
                $str_out.="Weiß";
                $tmpColor = "white";
            }
            else
            {
                $str_out.="Schwarz";
                $tmpColor = "black";
            }

            /* Current Turn */
            $str_out.="</td><td>";
            /* get number of moves from history */
            $tmpNumMoves = db_query("SELECT COUNT(gameID) FROM chesshistory WHERE gameID = ".$tmpGame['gameID']);
            $numMoves = mysql_result($tmpNumMoves,0);

            /* based on number of moves, output current color's turn */
            if (($numMoves % 2) == 0)
                $tmpCurMove = "white";
            else
                $tmpCurMove = "black";

            if ($tmpCurMove == $tmpColor)
                $str_out.='`$DU`0';
            else
                $str_out.="`2".$opponent."`0";

            /* Start Date */
            $str_out.="</td><td>".date($dateformat,strtotime($tmpGame['dateCreated']));

            /* Last Move */
            $str_out.="</td><td>".date($dateformat,strtotime($tmpGame['lastMove']))."</td></tr>\n";
        }
    }
    $str_out.='</table>

        <input type="hidden" name="gameID" value="">
        <input type="hidden" name="sharePC" value="no">
    </form>
    <br>
    Beendete Partien ansehen:`n`n

    <form name="endedGames" action="'.$str_filename.'?op=viewboard" method="post">
    <table border="1" width="550" align="center">
    <tr class="trhead">
        <th>Gegner</th>
        <th>Deine&nbsp;Farbe</th>
        <th>Status</th>
        <th>Startzeit</th>
        <th>Letzter&nbsp;Zug</th>
    </tr>';
    addnav('',$str_filename.'?op=viewboard');
    $result = db_query("SELECT *
    FROM chessgames
    WHERE (gameMessage <> '' AND gameMessage <> 'playerInvited' AND gameMessage <> 'inviteDeclined')
        AND (whitePlayer = ".$session['user']['acctid']."
        OR blackPlayer = ".$session['user']['acctid'].")
    ORDER BY lastMove DESC");
    
    if (db_num_rows($result) == 0)
        $str_out.="<tr><td colspan='6'>Für dich sind keine beendeten Partien gespeichert</td></tr>\n";
    else
    {
        while($tmpGame = db_fetch_assoc($result))
        {
            /* Opponent */
            $str_out.="<tr><td>";
            /* get opponent's nick */
            if ($tmpGame['whitePlayer'] == $session['user']['acctid'])
                $opponent=$tmpGame['blackName'];
            else
                $opponent=$tmpGame['whiteName'];
            
            $str_out.="<a href='javascript:loadEndedGame(".$tmpGame['gameID'].")'>".$opponent."</a>";

            /* Your Color */
            $str_out.="</td><td>";
            if ($tmpGame['whitePlayer'] == $session['user']['acctid'])
            {
                $str_out.="Weiß";
                $tmpColor = "white";
            }
            else
            {
                $str_out.="Schwarz";
                $tmpColor = "black";
            }

            /* Status */
            if (is_null($tmpGame['gameMessage']))
                $str_out.="</td><td>&nbsp;";
            else
            {
                if ($tmpGame['gameMessage'] == "draw")
                    $str_out.="</td><td>Ended in draw";
                else if ($tmpGame['gameMessage'] == "playerResigned")
                    $str_out.="</td><td>".ucfirst(translate($tmpGame['messageFrom']))." gab auf";
                else if (($tmpGame['gameMessage'] == "checkMate") && ($tmpGame['messageFrom'] == $tmpColor))
                    $str_out.="</td><td>Schachmatt,<br><font color=\"green\">gewonnen!</font>";
                else if ($tmpGame['gameMessage'] == "checkMate")
                    $str_out.="</td><td>Schachmatt,<br><font color=\"red\">verloren!</font>";
                else
                    $str_out.="</td><td>&nbsp;";
            }
            
            /* Start Date */
            $str_out.="</td><td>".date($dateformat,strtotime($tmpGame['dateCreated']));

            /* Last Move */
            $str_out.="</td><td>".date($dateformat,strtotime($tmpGame['lastMove']))."</td></tr>\n";
        }
    }
    $str_out.='</table>

        <input type="hidden" name="gameID" value="">
        <input type="hidden" name="sharePC" value="no">
    </form>
    <br>
    <b>Achtung!</b>
    <br>
    Spiele werden OHNE BENACHRICHTIGUNG gelöscht, wenn der letzte Zug länger als '.$CFG_EXPIREGAME.' Tage her ist!
    `n`n
    
    <form name="preferences" action="'.$str_filename.'" method="post">
    <table border="1" width="450">
        <tr class="trhead">
            <th colspan="2">aktuelle Einstellungen</th>
        </tr>

        <tr>
            <td valign="top">Brett aktualisieren:</td>
            <td>
                <input name="rdoReload" type="radio" value="yes" '.($session['user']['prefs']['chessreload']=='no'?'':'checked').'> automatischer Reload
                <br>
                <input name="rdoReload" type="radio" value="no" '.($session['user']['prefs']['chessreload']=='no'?'checked':'').'> manuell
            </td>
        </tr>
        <tr>
            <td valign="top">Darstellung Verlauf:</td>
            <td>
                <input name="rdoHistory" type="radio" value="verbous" '.($session['user']['prefs']['chesshistory']=='pgn'?'':'checked').'> ausführlicher Text
                <br>
                <input name="rdoHistory" type="radio" value="pgn" '.($session['user']['prefs']['chesshistory']=='pgn'?'checked':'').'> figurine Notation
            </td>
        </tr>
        <tr>
            <td valign="top">Chat-Sektion:</td>
            <td>
                <input name="rdoChat" type="radio" value="0" '.($session['user']['prefs']['chesschat']?'':'checked').'> ausblenden
                <br>
                <input name="rdoChat" type="radio" value="5" '.($session['user']['prefs']['chesschat']=='5'?'checked':'').'> anzeigen (nicht empfohlen)
            </td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input type="submit" class="button" value="Einstellungen speichern">
            </td>
        </tr>
    </table>

    <input type="hidden" name="ToDo" value="UpdatePrefs">
    </form>
';
    addnav('Spieler herausfordern',$str_filename.'?op=InvitePlayer');
    addnav('Aktualisieren',$str_filename);
    addnav('Zurück');
}
}

output($str_out,true);

if($session['user']['alive'])
{
    addnav('In die Schenke','inn.php');
    addnav('D?Zum Dorf','village.php');
}
else
{
    addnav('Zu den Schatten','shades.php');
}
page_footer();
?>