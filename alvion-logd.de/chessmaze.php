
<?php
/**
* @desc Ein kleines Schachbrettspiel um die Langeweile in Wien zur EM 2008 zu überstehen
* @author Dragonslayer
* @copyright Dragonslayer for Atrahor.de
*/

require_once 'common.php';

/**
* Goldbild hier definieren, bitte darauf achten dass es 60x60 groß ist, sonst wirds verzerrt.
*/
define('CHESS_PIC_1','./images/chessmaze/goldmuenze');
/**
* Silberbild hier definieren, bite darauf achten dass es 60x60 groß ist, sonst wirds verzerrt.
*/
define('CHESS_PIC_2','./images/chessmaze/silbermuenze');

/**
* Nennt die Datei doch wie ihr wollt :-)
*/
define('FILENAME',basename(__FILE__));
// define('FILENAME',$SCRIPT_NAME);

/**
* Wohin solls zurück gehen
*/
define('LINKBACK','shades_spiele.php');

/**
* Welcher Navtext gilt für den Zurücklink
*/
define('LINKBACK_TEXT','Zurück zur Spieleauswahl');
define('BACK_TEXT','Zurück');

/**
* Ausgabe des benötigten CSS
*
* @return string
*/
function get_css()
{
   $str_out .= '
   <style type="text/css">
      #chessboard
      {
         margin:auto;
         width:480px;
         height:480px;
      }
      .chess_bg1
      {
         background-color:red;
      }
      .chess_bg2
      {
         background-color:transparent;
      }
   </style>
   ';
   return $str_out;
}

/**
* Erstelle ein neues Spielfeld
*
* @return array des Spielfelds
*/
function chess_create_chessboard()
{
   global $session;
   $arr_fields = array();
   //Hier kann man die Komplexität einrichten.
   //Wenn der Wert zu hoch ist wirds zu langweilig,
   //bei einem Wert nahe Null ebenso.
   //3 ist ein guter Wert
   $int_difficulty = 2;
   for($int_i = 0;$int_i <8 ;$int_i++)
   {
      $arr_field = array();
      for($int_j = 0;$int_j <8 ;$int_j++)
      {
         $arr_field[] = (mt_rand(0,$int_difficulty)>0?0:1);
      }
      $arr_fields[] = $arr_field;
   }
   $arr_chess_maze = array();
   $arr_chess_maze['fields'] = $arr_fields;

   $arr_chess_maze['goal_row'] = mt_rand(0,7);
   $arr_chess_maze['goal_column'] = mt_rand(0,7);

   $arr_chess_maze['turns'] = 0;
   $arr_chess_maze['cheated'] = false;

   $session['user']['specialmisc'] = serialize($arr_chess_maze);
   return $arr_fields;
}

/**
* Gib das Spielfeld aus
*
* @return string
*/
function chess_print_chessboard()
{
   global $session;
   $arr_chess = unserialize($session['user']['specialmisc']);
   $arr_fields = $arr_chess['fields'];

   $int_row = $arr_chess['goal_row'];
   $int_column = $arr_chess['goal_column'];

   $str_out = '<div id="chessboard">';

   $str_act = '';
   if($_GET['op'] == 'cheat')
   {
      $str_act = '&act=cheat';
   }

   for($int_i = 0;$int_i <8 ;$int_i++)
   {
      for($int_j = 0;$int_j <8 ;$int_j++)
      {
         $str_class = ($int_i === $int_row || $int_j === $int_column)? '_rot.png':'.png';
         $str_out .= '<a href="'.FILENAME.'?op=switch'.$str_act.'&row='.$int_i.'&column='.$int_j.'" class="'.$str_class.'"><img src="'.($arr_fields[$int_i][$int_j] == true ? CHESS_PIC_1.$str_class:CHESS_PIC_2.$str_class).'" border="0" width="60" height="60" /></a>';
         addnav('',FILENAME.'?op=switch'.$str_act.'&row='.$int_i.'&column='.$int_j);
      }
   }

   $str_out .= '</div>';
   return $str_out;
}

function chess_cheat()
{
   global $session;
   $arr_chess = unserialize($session['user']['specialmisc']);
   $arr_fields = $arr_chess['fields'];

   $int_row = (int)$_GET['row'];
   $int_column = (int)$_GET['column'];

   $arr_chess['turns']++;

   $arr_fields[$int_row][$int_column] = ($arr_fields[$int_row][$int_column] == 0) ? 1 : 0;
   $arr_chess['fields'] = $arr_fields;
   $session['user']['specialmisc'] = serialize($arr_chess);
   $int_gems = $arr_chess['turns'] >10 ? 2 : 1 ;

   $arr_chess['cheated'] = true;

   $session['user']['gems'] = max(0,$session['user']['gems'] - $int_gems);

   return $arr_fields;
}

/**
* Dreht alle Felder um die sich in der gleichen Zeile und Spalte wie das angeklickte Element befinden
*
* @return array Die Felder
*/
function chess_switch()
{
   global $session;
   $arr_chess = unserialize($session['user']['specialmisc']);
   $arr_fields = $arr_chess['fields'];

   $int_row = (int)$_GET['row'];
   $int_column = (int)$_GET['column'];

   $arr_chess['turns']++;

   if($_GET['act'] == 'cheat')
   {
      $arr_fields[$int_row][$int_column] = ($arr_fields[$int_row][$int_column] == 0) ? 1 : 0;
      $arr_chess['fields'] = $arr_fields;
      $session['user']['specialmisc'] = serialize($arr_chess);

      $int_gems = $arr_chess['turns'] >=5 ? 2 : 1 ;

      $arr_chess['cheated'] = true;

      $session['user']['gems'] = max(0,$session['user']['gems'] - $int_gems);
      return $arr_fields;
   }

   for($int_i = 0;$int_i <8 ;$int_i++)
   {
      for($int_j = 0;$int_j <8 ;$int_j++)
      {
         $bool_rowcheck = false;
         if($int_i == $int_row)
         {
            $arr_fields[$int_i][$int_j] = ($arr_fields[$int_i][$int_j] == 0) ? 1 : 0;
            $bool_rowcheck = true;
         }

         if($int_j == $int_column && !$bool_rowcheck)
         {
            $arr_fields[$int_i][$int_j] = ($arr_fields[$int_i][$int_j] == 0) ? 1 : 0;
         }
      }
   }
   $arr_chess['fields'] = $arr_fields;
   $session['user']['specialmisc'] = serialize($arr_chess);
   return $arr_fields;
}

function chess_check($int_row=null,$int_column=null)
{
   global $session;

   $arr_chess = unserialize($session['user']['specialmisc']);
   $arr_fields = $arr_chess['fields'];

   $int_row = $arr_chess['goal_row'];
   $int_column = $arr_chess['goal_column'];

   //Es kann nur über eine Zeile und eine Spalte gesucht werden,
   //sonst wirds zu schwer
   if($int_column === null && $int_row === null)
   {
      return false;
   }

   $check_row = true;
   $check_column = true;

   for($int_i = 0;$int_i <8 ;$int_i++)
   {
      for($int_j = 0;$int_j <8 ;$int_j++)
      {
         //Wenn die Zeile mit der zu überprüfenden Zeile übereinstimmt und das aktuell
         //betrachtete Feld dieser Zeile den Wert true hat, dann ist die Überprüfung wahr
         if($int_i === $int_row && $arr_fields[$int_i][$int_j] == false)
         {
            $check_row = false;
            continue;
         }

         //Wenn die Spalte mit der zu überprüfenden Spalte übereinstimmt und das aktuell
         //betrachtete Feld dieser Spalte den Wert true hat, dann ist die Überprüfung wahr
         if($int_j === $int_column && $arr_fields[$int_i][$int_j] == false)
         {
            $check_column = false;
            continue;
         }
      }
   }
   return $check_row && $check_column;
}


///
/// Sodele, ab hier geht der Seitenaufbau los
///
page_header('Chessmaze');
addnav('Aktionen');

$str_out .= get_css();

switch ($_GET['op'])
{
   default:
      {
         $str_out .= '`c`b`yDas Wendespiel`b`c`n`n`tAn einem der vielen kleinen Spieltische sitzt eine rothäutige Frau, mit langen, kunstvoll geflochtenen, schwarzen Haaren. Ihre Mandelförmigen, dunklen Augen geben ihrer grazilen Gestalt ihren letzten Schliff, so dass du nicht umhin kannst, als sie bereits für ihr Aussehen zu '.($session['user']['sex']? 'vergöttern':'beneiden').'. Kaum weniger interessant ist das kleine Denkspiel, welches sie hier feil bietet. Es handelt sich um eine Knobelaufgabe, welche auf einem Schachbrett gespielt wird. Der Einsatz kostet dich nur 100 Goldstücke, doch du mußt 1000 Goldstücke bei dir haben denn je länger du das Spiel spielst, desto teurer wird es. Wagst du ein neues Spiel?';
         if($session['user']['gold']>=1000)
         {
            addnav('Neues Spiel beginnen (100G)',FILENAME.'?op=new');
         }
         elseif($session['user']['goldinbank']>=2000)
         {
         $str_out .= '`n`n`n`yDu hast nicht genug Gold bei dir um an diesem Spiel teilnehmen zu können! Doch Ramius ist gnädig und bietet dir die Möglichkeit den Spieleinsatz von deinem Bankkonto zu nehmen. `n`n`t`bSpieleinsatz von der Bank in deinen Goldbeutel überweisen?`b';
        addnav('Gold von der Bank holen (2000G)',FILENAME.'?op=bank&gold=2000');
        addnav('Gold von der Bank holen (1000G)',FILENAME.'?op=bank&gold=1000');
         }
         elseif($session['user']['goldinbank']>=1000)
         {
         $str_out .= '`n`n`n`yDu hast nicht genug Gold bei dir um an diesem Spiel teilnehmen zu können! Doch Ramius ist gnädig und bietet dir die Möglichkeit den Spieleinsatz von deinem Bankkonto zu nehmen. `n`n`t`bSpieleinsatz von der Bank in deinen Goldbeutel überweisen?`b';
        addnav('Gold von der Bank holen (1000G)',FILENAME.'?op=bank&gold=1000');
         }
         else
         {
             $str_out .= '`n`n`n`yDu hast leider nicht genug Gold um an diesem Spiel teilnehmen zu können!';
         }
         addnav('Die Regeln',FILENAME.'?op=rules');
      }
      break;
   case 'new':
      {
         chess_create_chessboard();
         $session['user']['gold'] -= 100;
         $str_out .= '`c`bDas Schachbrett`b`c`n`n`tKlicke auf das Feld, dass du umdrehen möchtest. Alle Felder in der gleichen Zeile und Spalte werden ebenfalls umgedreht. Versuche so alle Felder der rot hinterlegten Zeile und Spalte auf die Farbe Gold zu wenden.<hr />';
         $str_out .= chess_print_chessboard();
      }
      break;
   case 'win':
      {
         $arr_chess = unserialize($session['user']['specialmisc']);
         $str_out .= '`c`b`yGewonnen`b`c`n`n`tHerzlichen Glückwunsch, du hast das Spiel gewonnen. ';
         $str_out .= 'Als Belohnung erhälst du die versprochenen 1000 Goldstücke. Du bedankst dich artig und wärst nun bereit für ein neues Spiel.';
        if($arr_chess['turns']<4){
                 $str_out .= ' Und da du es in weniger als 4 Spielzügen geschafft hast schenkt Ramius dir 3 Gefallen!';
            $session['user']['deathpower'] += 3;
        }
        elseif($arr_chess['turns']<6){
                 $str_out .= ' Und da du es in weniger als 6 Spielzügen geschafft hast schenkt Ramius dir 2 Gefallen!';
            $session['user']['deathpower'] += 2;
        }
         $session['user']['gold'] += 1000;
         $session['user']['specialmisc'] = '';
      }
      addnav(BACK_TEXT,FILENAME.'?op=zurueck');
      break;
   case 'loose':
      {
         $str_out .= '`c`b`yNicht gewonnen`b`c`n`n`tSchade, du hast es leider nicht geschafft das Rätsel zu lösen';
         $session['user']['specialmisc'] = '';
      }
      addnav(BACK_TEXT,FILENAME.'?op=zurueck');
      break;
   case 'rules':
      {
         $str_out .= '`c`b`yDie Regeln`b`c`n`n`tDas Wendespiel wird auf einem Schachbrett gespielt. Es existieren somit acht Zeilen und Spalten, also 64 Felder. Jedes dieser Felder hat zwei Seiten, eine silberne und eine goldene.`n
         `bDer Spielbeginn:`b`n
         Zu Beginn eines neuen Spiels werden die Seiten zufällig auf dem Spielfeld verteilt und je eine Zeile und Spalte zufällig ausgewählt (rot hinterlegt).`n
         `bZiel des Spiels:`b`n
         Ziel des Spiels ist es die hinterlegte Zeile und Spalte mit der goldenen Seite zu befüllen. Hierzu wählst du in jedem Zug eines der Felder aus. Nun werden alle Felder der gleichen Zeile und Spalte umgedreht. Aus gold wird silber und aus silber wird gold.`n
         `bEinsatz:`b`n
         In diesem Spiel geht es um Gold. Für jeden Zug den Du machst zahlst du, 100 Goldstücke. Wenn du das Spiel gewinnst erhälst du 1000 Goldstücke. Wenn Du das Spiel nicht innerhalb von 10 Zügen beendest, hast du verloren.`n
         `bSchummeln`b:`n
         In diesem Spiel darfst du schummeln! Das kostet dich jedoch Edelsteine. Einen Edelstein wenn du noch weniger als 5 Züge gemacht hast, sonst zwei!
         ';
         if($session['user']['gold']>1000)
         {
            addnav('Neues Spiel beginnen (100G)',FILENAME.'?op=new');
         }
      }
      break;
   case 'switch':
      {
         $session['user']['gold'] -= 100;
         $arr_chess = unserialize($session['user']['specialmisc']);
         $str_out .= '`c`bDas Schachbrett`b`c`n`n`tKlicke auf das Feld, dass du umdrehen möchtest. Alle Felder in der gleichen Zeile und Spalte werden ebenfalls umgedreht. Versuche so alle Felder der rot hinterlegten Zeile und Spalte auf die Farbe Gold zu wenden.`n`cDu hast bereits `4'.($arr_chess['turns']+1).' `t'.($arr_chess['turns']? 'Züge':'Zug').' gemacht!`c<hr />';

         if($_GET['act'] == 'cheat')
         {
            chess_cheat();
         }
         else
         {
            chess_switch();
         }

         $arr_chess = unserialize($session['user']['specialmisc']);
         $int_gems = $arr_chess['turns'] >=5 ? 2 : 1 ;

         if($session['user']['gems'] > $int_gems)
         {
            addnav('Mit '.$int_gems.' ES bestechen',FILENAME.'?op=cheat');
         }

         if(chess_check())
         {
            redirect(FILENAME.'?op=win');
         }
         if($session['user']['gold'] < 100 || $arr_chess['turns']>=10)
         {
            redirect(FILENAME.'?op=loose');
         }
         addnav('Aufgeben',FILENAME.'?op=loose');

         $str_out .= chess_print_chessboard();
      }
      break;
   case 'cheat':
      {
         $str_out .= '`c`bSchummelei`b`c`n`n`tDu bezahlst einen Edelstein und darfst nun ein Feld auswählen das du einfach so umdrehen darfst.<hr />';
         $str_out .= chess_print_chessboard();
      }
      break;
   case 'end':
      {
         $session['user']['specialmisc'] = '';
         redirect(LINKBACK);
      }
      break;
   case 'zurueck':
      {
         $session['user']['specialmisc'] = '';
         redirect(FILENAME);
      }
      break;
   case 'bank':
    {
         $session['user']['gold'] += (int)$_GET['gold'];
         $session['user']['goldinbank'] -= (int)$_GET['gold'];
         redirect(FILENAME);
      }
      break;
}

//Specialmisc in jedem Fall löschen
//addnav(BACK_TEXT,FILENAME.'?op=zurueck');
addnav(LINKBACK_TEXT,FILENAME.'?op=end');

output($str_out,true);
page_footer();

?>

