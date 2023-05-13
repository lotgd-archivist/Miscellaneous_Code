
<?phprequire_once "common.php";addcommentary();checkday();if ($session['user']['alive']) redirect("village.php");page_header("Zeitvertreib");$session['user']['standort']="`4Zeitvertreib`0";output("`v`c`bSpielauswahl`b`c`n`n");addnav("C?ChessMaze","chessmaze.php");addnav("H?Hangman","hangman.php");addnav("D?DragonMind","dragonmind.php");//addnav("S?Sudoku","sudoku2.php");//addnav("T?Tic Tac Toe","tictactoe.php");addnav("Z?Zurück zu den Schatten","shades.php");viewcommentary("Zeitvertreib","Hinzufügen",25,"sagt",1,1);page_footer();?>

