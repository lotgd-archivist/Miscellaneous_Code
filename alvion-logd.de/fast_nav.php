
<?php

/**
 * @author MySQL
 * @copyright 2008-2009 ob-games Net.
 */

require_once 'common.php';

$name = 'fast_nav.txt';
$aktiv = array(0 => 'Deaktiviert', 1 => 'Aktiviert');

if(!function_exists('file_put_contents'))
 {
   function file_put_contents($filename,$mixed) 
   {
      $fh = fopen($filename,"wb");
      if(FALSE === $fh) return FALSE;
      if (is_array($mixed))
      {
        $bytes = fwrite($fh,join('', $mixed));
      }else
      {
        $bytes = fwrite($fh,$mixed);
      }
      fclose($fh);
      return $bytes;
   }
}
switch ($_GET['op'])
   {
        case '':
                page_header('Fast Nav \'Test-Editor\'');
                    addnav('Optionen');
                    addnav('Nav hinzufügen','fast_nav.php?op=create');
                    addnav('Nav\'s bearbeiten','fast_nav.php?op=edit');
                    addnav('Sonstiges');
                    addnav('Weltliches','village.pgp');
                    addnav('Admin Grotte','superuser.php');
                    addnav('by MySql');
                    output('Hier mal ein Mod, bassierend auf einer *.txt Datei! :)');
                 rawoutput('<table align="center" border="0" cellpadding="4" cellspacing="2">'.
                           '<tr class="trhead">'.
                           '<td>Zeile / ID</td>'.
                           '<td>Anzeige Name</td>'.
                           '<td>Link</td>'.
                           '<td>Status</td>'.
                           '<td>Löschen</td>'.
                           '<td>Details</td>'.
                           '</tr>');
                 $data = fopen($name,'r');
                 while ($auslesen = fscanf($data, "%s %s %u"))
                    {
                        list($data_title, $data_link, $data_aktiv) = $auslesen;
                             $line++;
                             rawoutput('<tr>'.
                                       '<td>'.$line.'</td>'.
                                       '<td>'.$data_title.'</td>'.
                                       '<td>'.$data_link.'</td>'.
                                       '<td>'.$aktiv[$data_aktiv].'</td>'.
                                       '<td><a href="fast_nav.php?op=delete_nav&line='.$line.'&title='.$data_title.'">Löschen</a></td>'.
                                       '<td><a href="fast_nav.php?op=detail_nav&line='.$line.'">Details</a></td>'.
                                       '</tr>');
                                addnav('','fast_nav.php?op=delete&line='.$line);
                                addnav('','fast_nav.php?op=detail&line='.$line);
                 }
                 fclose($data);
        break;
        case 'delete_nav':
                          page_header($_GET['title'].' löschen');
                              $zeile = file($name);
                                     unset($zeile[$_GET['line']-1]);
                                     file_put_contents($name,$zeile);
                              output('CHMOD 777 true:`n'.
                                     '`iDatei konnte erfolgreich editiert werden `bwenn`b CHMOD auf 777 steht ansonsten gilt der untere Text!`i`n`n'.
                                     'CHMOD 777 false:`n'.
                                     '`iDatei konnte aufgrund des fehlenden Attributes nicht bearbeitet werden!`i');
                              addnav('Optionen');
                              addnav('Weiter','fast_nav.php');
        break;
        case 'detail_nav':
                          $data = fopen($name, 'r');
                          $zeilen = file($name);
                          fclose($data);
                          $convert = $zeilen[$_GET['line']-1];

                          list($title, $link, $active) = sscanf($convert, "%s %s %u");
                          page_header($title);
                          output('Details aus der Zeile '.$_GET['line'].':`n`n'.
                                 '`bName:`b `i'.$title.'`i.`n'.
                                 '`bLink:`b `i'.$link.'`i.`n'.
                                 '`bAktiv:`b `i'.$aktiv[$active].'`i.`n`n'.
                                 '&copy; ob-games Net.',true);
                          addnav('Optionen');
                          addnav('Nav löschen!','fast_nav.php?op=delete_nav&line='.$_GET['line']);
                          addnav('Sonstiges');
                          addnav('Zurück','fast_nav.php');
        break;
        case 'create':
                      page_header('Neuen Nav hinzufügen');
                          output('Hier kannst du neue Navs hinzufügen!`n`n'.
                                 'Leider kann man zwischen den "Namen" bzw. "Titeln" keine Leerzeichen machen. Schreibst daher mit Bindestrich!`n`n'.
                                 'Bsp.:`n`n'.
                                 '`b`$Falsch:`b`0`n'.
                                 'Titel: <input size="15" value="Hall of Fame" readonly="readonly">`n'.
                                 '`b`@Richtig:`0`b`n'.
                                 'Titel: <input size="15" value="Hall-of-Fame" readonly="readonly">`n'.
                                 '`iMan muss nciht unbedingt Bindestriche benutzen, andere Zeichen gehen auch!`i`n`n'.
                                 'Als Aktiv tragt Ihr bitte nur Null (0), oder Eins (1) ein!`n`n'.
                                 'Bsp.:`n`n'.
                                 '`b`$Falsch:`b`0`n'.
                                 'Aktiv: <input size="15" value="3" readonly="readonly">`n'.
                                 '`b`@Richtig:`b`0`n'.
                                 'Aktiv: <input size="15" value="0" readonly="readonly">`n'.
                                 'bzw.`n'.
                                 'Aktiv: <input size="15" value="1" readonly="readonly">`n`n'.
                                 'Wie auch bei den Titel, könnt Ihr keine Leerzeichen im Link haben!`n`n'.
                                 'Bsp.:`n`n'.
                                 '`$`bFalsch:`0`b`n'.
                                 'Link: <input size="15" value="dead see.php" readonly="readonly">`n'.
                                 '`@`bRichtig:`0`b`n'.
                                 'Link: <input size="15" value="dead_see.php" readonly="readonly">`n`n'.
                                 '<form action="fast_nav.php?op=create_fin" method="post">'.
                                 'Title: <input name="title">`n'.
                                 'Link: <input name="link">`n'.
                                 'Aktiv: <input name="active">`n'.
                                 '<input type="submit" value="Nav speichern!">'.
                                 '</form>',true);
                          addnav('','fast_nav.php?op=create_fin');
                          addnav('Optionen');
                          addnav('Zurück','fast_nav.php');
        break;
        case 'create_fin':
                          page_header('Nav speichern');
                              $data = fopen($name, 'a');
                                    if ($_POST['title'] == '' OR $_POST['link'] == '' OR $_POST['active'] == '')
                                     {
                                        output('FEHLER! Fülle bitte das ganze Formular aus!');
                                    }
                                    else {
                                        
                                        if(!$data)
                                         {
                                            output('CHMOD 77 false:`n'.
                                                   '`iDie Datei verfügt über keinerlei Schreibrechte! CHMOD bitte auf 777!`i`n`n');
                                        }
                                        else {
                                             output('Link wurde erfolgreich zugefügt!');
                                             fwrite($name, $_POST['title']. $_POST['link']. $_POST['active']);
                                             fclose($data);
                                        }
                                    }    
                                    addnav('Optionen');
                                    addnav('Weiter','fast_nav.php');
        break;   
        case 'edit':
                    page_header('Nav\'s editieren');
                    $data = fopen($name, 'rb');
                    $inhalt = fread($data, filesize($name));

                    output('Hier kannst du die Nav\'s allesamt editieren!`n`n');
                    output('<form action="fast_nav.php?op=edit_fin" method="post">'.
                           '<textarea cols="70" rows="70" name="edit_file">'.$inhalt.'</textarea>`n'.
                           '<input type="submit" value="Edit!">'.
                           '</form>',true);
                    fclose($data);
                    addnav('','fast_nav.php?op=edit_fin');
                    addnav('Optionen');
                    addnav('Zurück','fast_nav.php');
        break; 
        case 'edit_fin':
                        page_header('Nav\s editiert!');
                        $new_data = $_POST['edit_file'];
                        $data = fopen($name, 'w');
                              fputs($data,$new_data);
                              fclose($data);
                              output('CHMOD 777 true:`n'.
                                     '`iDatei konnte erfolgreich editiert werden `bwenn`b CHMOD auf 777 steht ansonsten gilt der untere Text!`i`n`n'.
                                     'CHMOD 777 false:`n'.
                                     '`iDatei konnte aufgrund des fehlenden Attributes nicht bearbeitet werden!`i');
                              addnav('Optionen');
                              addnav('Weiter','fast_nav.php');
        break;
}
page_footer();
?>

