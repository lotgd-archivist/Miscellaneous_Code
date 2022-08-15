
<?php
require_once('common.php');
function get_names($names,$amount)
{
        $names_count = count($names);
        if($names_count < $amount || !is_array($names)) return false;
        else
        {
                if($amount > 1)
                {
                        for($i=0;$i<$amount;$i++)
                        {
                                $key = e_rand(1,$names_count);
                                $key -= 1;
                                if(isset($names[$key])) $arr_names[$i] = $names[$key];
                                else $i--;
                                unset($names[$key]);
                        }
                }
                else
                {
                        $key = e_rand(1,count($names));
                        $key -= 1;
                        $arr_names[0] = $names[$key];
                }
                return $arr_names;
        }
}
page_header('Auslosung');
$str_tout = '';
$str_names = getsetting('losung_teilnehmer','');
$op = (isset($_GET['op']) ? $_GET['op'] : '');
switch($op)
{
        case '':
                $str_tout .= '`h`bAuslosung`b`n
                              `n
                              `^Trage die Login-Namen derjenigen, die an der Auslosung teilnehmen, in das folgende Feld ein.`n
                              Getrennt wird mit einem Querstrich, also: `I`iLogin|Login|Login|...`i`n
                              `n
                              <form action="losung.php?op=save" method="post">
                              <textarea name="str_newnames" class="input" rows="5" cols="60">'.$str_names.'</textarea>`n
                              <input type="submit" class="button" value="Speichern">
                              </form>';
                addnav('','losung.php?op=save');
                addnav('Losung');
                addnav('N?Namen ziehen','losung.php?op=getnames');
                addnav('Zurück');
                addnav('G?Zur Grotte','superuser.php');
        break;
        case 'save':
                $str_newnames = $_POST['str_newnames'];
                savesetting('losung_teilnehmer',$str_newnames);
                redirect('losung.php');
        break;
        case 'getnames':
                $act = (isset($_GET['act']) ? $_GET['act'] : '');
                if($act == 'run')
                {
                        $arr_names = explode('|',$str_names);
                        $amount = (int)$_POST['amount'];
                        $arr_chosennames = get_names($arr_names,max(1,$amount));
                        if($arr_chosennames == false)
                        {
                                $str_tout .= '`$Fehler! `hDu kannst nicht mehr Personen ziehen lassen, als es Teilnehmer gibt!';
                        }
                        else
                        {
                                $str_chosennames = implode(', ',$arr_chosennames);
                                $str_tout .= '`h`bAuslosung`b`n
                                              `n
                                              `^Die gelosten Namen sind:`n
                                              `n`I`i'.$str_chosennames.'`i';
                                debuglog('`@Bei der Lotterie gezogene Namen: '.$str_chosennames.';`n`0(Teilnehmer: '.$str_names.')');
                        }
                }
                else
                {
                        $str_tout .= '`h`bAuslosung`b`n
                                      `n
                                      `^Wie viele Namen sollen gezogen werden?`n
                                      `n
                                      <form action="losung.php?op=getnames&act=run" method="post">
                                      <input type="text" name="amount" size="5" value="1"> <input type="submit" class="button" value="Senden">
                                      </form>';
                        addnav('','losung.php?op=getnames&act=run');
                }
                addnav('Zurück');
                addnav('Z?Zur Startseite','losung.php');
                addnav('G?Zur Grotte','superuser.php');
        break;
        default:
                $str_tout .= 'fehlende op: '.$op;
                addnav('Zurück','superuser.php');
        break;
}
if(strlen($str_tout) > 3) output($str_tout);
page_footer();
?>

