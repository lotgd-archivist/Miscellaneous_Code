<?php

/**
 * Rasseneditor by MySQL [ Rassenverteilung per Datei ]
 * 20.06.2009
**/

require_once 'common.php';

define('File',basename(__FILE__));

switch( $_GET['editorStep'] ) {
    
    case 'show':
        
        page_header('Der Rasseneditor - Rassenauflistung');
            
            if( $_GET['deaktivate'] == 'Yes' ) {
                
                $newName = substr($_GET['file'],2);
                $newName = '0_'.$newName;
                rename('Races/'.$_GET['file'],'Races/'.$newName);
            
            }
            
            if( $_GET['aktivate'] == 'Yes' ) {
                
                $newName = substr($_GET['file'],2);
                $newName = '1_'.$newName;
                rename('Races/'.$_GET['file'],'Races/'.$newName);
            
            }
            
            if( $_GET['delete'] == 'Yes' ) {
                
                unlink('Races/'.$_GET['file']);
            
            }
            
            output('`c<h1>`b`qDer Rasseneditor - Rassenauflistung!`0`b</h1>`c`n'.
                   '`qIn folgender Tabelle werden alle Rassen aufgelistet, die Momentan `@`baktiv`b`q sind.`0`n`n'.
                   '<table border=0 cellpadding=2 cellspacing=1 bgcolor="#999999" align="center">'.
                   '<tr class="trhead">'.
                   '<td align="center">Name / Plural [ohne Farbe]</td>'.
                   '<td align="center">Name / Plural [mit Farbe]</td>'.
                   '<td>Mind. Phoenixkills</td>'.
                   '<td>Bonus [Feld]</td>'.
                   '<td>Bonus</td>'.
                   '<td align="center">Erstellt von</td>'.
                   '<td align="center">Datei</td>'.
                   '<td align="center">Deaktivieren</td>'.
                   '<td align="center">Bearbeiten</td>'.
                   '</tr>',true);
            
            $d = dir('Races');
                
            while( false !== ($e = $d->read()) ) {
                    
                if( strpos($e, '.race') === false ) continue;
                if( substr($e,0,1) == '.' ) continue;
                    
                $mySpliter = explode('_',$e);
                    
                    
                if( $mySpliter[0] ) {
                    
                    $i++;
                    $myData[$i] = unserialize(file_get_contents('Races/'.$e));
                    $myFiles[$i] = $e;
                        
                }
                
                if( !$mySpliter[0] ) {
                    
                    $y++;
                    $myDData[$y] = unserialize(file_get_contents('Races/'.$e));
                    $myFFiles[$y] = $e;
                
                }
                    
            }
            
            $countA = count($myData);
            
            $i = 0;
            
            while( $i < $countA ) {
                
                $i++;
                
                output('<tr class="'.($i%2?'trdark':'trlight').'">'.
                       '<td>'.$myData[$i]['Name'].' / '.$myData[$i]['Plural'].'</td>'.
                       '<td>'.$myData[$i]['cName'].' / '.$myData[$i]['cPlural'].'</td>'.
                       '<td>'.$myData[$i]['Dk'].'</td>'.
                       '<td>'.$myData[$i]['Bonus_Feld'].'</td>'.
                       '<td>'.$myData[$i]['Bonus'].'</td>'.
                       '<td>'.$myData[$i]['Autor'].'</td>'.
                       '<td>'.$myFiles[$i].'</td>'.
                       '<td>[<a href="'.File.'?editorStep=show&deaktivate=Yes&file='.$myFiles[$i].'">`4Deaktivieren`0</a>]</td>'.
                       '<td>[<a href="'.File.'?editorStep=edit&file='.$myFiles[$i].'">`@Bearbeiten`0</a>]</td>'.
                       '</tr>',true);
                addnav('',File.'?editorStep=show&deaktivate=Yes&file='.$myFiles[$i]);
                addnav('',File.'?editorStep=edit&file='.$myFiles[$i]);
                      
            }
            
            output('</table>`n`n',true);
            
            output('`qIn folgender Tabelle werden alle Rassen aufgelistet, die Momentan `4`bdeaktiv`b`q sind.`0`n`n'.
                   '<table border=0 cellpadding=2 cellspacing=1 bgcolor="#999999" align="center">'.
                   '<tr class="trhead">'.
                   '<td align="center">Name / Plural [ohne Farbe]</td>'.
                   '<td align="center">Name / Plural [mit Farbe]</td>'.
                   '<td>Mind. Phoenixkills</td>'.
                   '<td>Bonus [Feld]</td>'.
                   '<td>Bonus</td>'.
                   '<td align="center">Erstellt von</td>'.
                   '<td align="center">Datei</td>'.
                   '<td align="center">Aktivieren</td>'.
                   '<td align="center">Bearbeiten</td>'.
                   '<td align="center">Löschen</td>'.
                   '</tr>',true);
            
            $countB = count($myDData);
            
            $y = 0;
            
            while( $y < $countB ) {
                
                $y++;
                
                output('<tr class="'.($y%2?'trdark':'trlight').'">'.
                       '<td>'.$myDData[$y]['Name'].' / '.$myDData[$y]['Plural'].'</td>'.
                       '<td>'.$myDData[$y]['cName'].' / '.$myDData[$y]['cPlural'].'</td>'.
                       '<td>'.$myDData[$y]['Dk'].'</td>'.
                       '<td>'.$myDData[$y]['Bonus_Feld'].'</td>'.
                       '<td>'.$myDData[$y]['Bonus'].'</td>'.
                       '<td>'.$myDData[$y]['Autor'].'</td>'.
                       '<td>'.$myFFiles[$y].'</td>'.
                       '<td>[<a href="'.File.'?editorStep=show&aktivate=Yes&file='.$myFFiles[$y].'">`@Aktivieren`0</a>]</td>'.
                       '<td>[<a href="'.File.'?editorStep=edit&file='.$myFFiles[$y].'">`@Bearbeiten`0</a>]</td>'.
                       '<td>[<a href="'.File.'?editorStep=show&delete=Yes&file='.$myFFiles[$y].'">`4Löschen`0</a>]</td>'.
                       '</tr>',true);
                addnav('',File.'?editorStep=show&aktivate=Yes&file='.$myFFiles[$y]);
                addnav('',File.'?editorStep=edit&file='.$myFFiles[$y]);
                addnav('',File.'?editorStep=show&delete=Yes&file='.$myFFiles[$y]);
                
            }        

            addnav('Aktionen');
            addnav('Eine Rasse erstellen',File.'?editorStep=create');
            addnav('Aktualisieren',File.'?editorStep=show');
            addnav('Sonstiges');
            addnav('Zur Admingrotte','superuser.php');
            addnav('Zum Dorfplatz','village.php');
    
    break;
    
    case 'edit':
    
        $myData = unserialize(file_get_contents('Races/'.$_GET['file']));
        
        page_header('Rasseneditor - Rasse bearbeiten');
        
            output('`c<h1>`b`qDer Rasseneditor - Rasse editieren ['.$myData['Plural'].']!`0`b</h1>`c`n`n'.
                   '<form action="'.File.'?editorStep=saveEdit&file='.$_GET['file'].'" method="POST">'.
                   '<table border=0 cellpadding=0 cellspacing=0 bgcolor="#999999" align="center" width="50%">'.
                   '<tr class="trhead"><td colspan=2>`b`(`cAllgemeine Daten der Rasse '.$myData['Plural'].'!`c`0`b</td></tr>',true);
            rawoutput('<tr class="trdark"><td>Name der Rasse [ohne Farbe]</td><td><input type="text" name="name" value="'.$myData['Name'].'"></td></tr>'.
                      '<tr class="trlight"><td>Name der Rasse [in Farbe]</td><td><input type="text" name="cname" value="'.$myData['cName'].'"></td></tr>'.
                      '<tr class="trdark"><td>Mehrzahl der Rasse [ohne Farbe]</td><td><input type="text" name="plural" value="'.$myData['Plural'].'"></td></tr>'.
                      '<tr class="trlight"><td>Mehrzahl der Rasse [mit Farbe]</td><td><input type="text" name="cplural" value="'.$myData['cPlural'].'"></td></tr>'.
                      '<tr class="trdark"><td>Verfügbar ab [Phoenixkill]</td><td><input type="text" name="dk" value="'.$myData['Dk'].'"></td></tr>'.
                      '<tr class="trlight"><td>Bonus [Feld]</td><td><input type="text" name="bonus_feld" value="'.$myData['Bonus_Feld'].'"></td></tr>'.
                      '<tr class="trdark"><td>Bonus</td><td><input type="text" name="bonus" value="'.$myData['Bonus'].'"></td></tr>'.
                      '<tr class="trlight"><td>Autor</td><td><input type="text" name="autor" value="'.$myData['Autor'].'"></td></tr>'.
                      '<tr class="trdark"><td>Beschreibung der Rasse</td><td><textarea name="desc" cols=35 rows=10>'.$myData['Desc'].'</textarea></td></tr>'.
                      '<tr class="trlight"><td>Finaler Text der Rasse</td><td><textarea name="final_desc" cols=35 rows=10>'.$myData['Final_Desc'].'</textarea></td></tr>'.
                      '<tr class="trhead"><td colspan=2><center><input type="submit" class="button" value="Änderungen speichern!"></center></td></tr>'.
                      '</table></form>');
            addnav('',File.'?editorStep=saveEdit&file='.$_GET['file']);
            addnav('Aktionen');
            addnav('Abbrechen',File.'?editorStep=show');
        
    break;
    
    case 'saveEdit':
        
        page_header('Rasseneditor - Änderungen speichern');
        
            if( is_writeable('Races/'.$_GET['file']) ) {
                
                $arr = array(
                             'Name' => $_POST['name'],
                             'cName' => $_POST['cname'],
                             'Plural' => $_POST['plural'],
                             'cPlural' => $_POST['cplural'],
                             'Dk' => $_POST['dk'],
                             'Bonus_Feld' => $_POST['bonus_feld'],
                             'Bonus' => $_POST['bonus'],
                             'Autor' => $_POST['autor'],
                             'Desc' => $_POST['desc'],
                             'Final_Desc' => $_POST['final_desc'],
                             );
                $arr = serialize($arr);
                
                $f = fopen('Races/'.$_GET['file'],'w+');
                fwrite($f,$arr);
                fclose($f);
                
                output('Änderungen erfolgreich vorgenommen!');
                addnav('Aktionen');
                addnav('Zum Editor',File.'?editorStep=show');
            
            } else {
                
                output('Konnte Datei nicht beschreiben..');
                addnav('Aktionen');
                addnav('Zum Editor',File.'?editorStep=show');
            
            }
    
    break;
    
    case 'create':
    
    page_header('Rasseneditor - Rasse bearbeiten');
        
            output('`c<h1>`b`qDer Rasseneditor - Rasse erstellen!`0`b</h1>`c`n`n'.
                   '<form action="'.File.'?editorStep=save" method="POST">'.
                   '<table border=0 cellpadding=0 cellspacing=0 bgcolor="#999999" align="center" width="50%">'.
                   '<tr class="trhead"><td colspan=2>`b`(`cAllgemeine Daten der Rasse!`c`0`b</td></tr>',true);
            rawoutput('<tr class="trdark"><td>Name der Rasse [ohne Farbe]</td><td><input type="text" name="name"></td></tr>'.
                      '<tr class="trlight"><td>Name der Rasse [in Farbe]</td><td><input type="text" name="cname"></td></tr>'.
                      '<tr class="trdark"><td>Mehrzahl der Rasse [ohne Farbe]</td><td><input type="text" name="plural"></td></tr>'.
                      '<tr class="trlight"><td>Mehrzahl der Rasse [mit Farbe]</td><td><input type="text" name="cplural"></td></tr>'.
                      '<tr class="trdark"><td>Verfügbar ab [Phoenixkill]</td><td><input type="text" name="dk"></td></tr>'.
                      '<tr class="trlight"><td>Bonus [Feld]</td><td><input type="text" name="bonus_feld"></td></tr>'.
                      '<tr class="trdark"><td>Bonus</td><td><input type="text" name="bonus"></td></tr>'.
                      '<tr class="trlight"><td>Autor</td><td><input type="text" name="autor"></td></tr>'.
                      '<tr class="trdark"><td>Beschreibung der Rasse</td><td><textarea name="desc" cols=35 rows=10>Mit <LINK> bestimmst Du, wann der Link im Text endet.</textarea></td></tr>'.
                      '<tr class="trlight"><td>Finaler Text der Rasse</td><td><textarea name="final_desc" cols=35 rows=10></textarea></td></tr>'.
                      '<tr class="trhead"><td colspan=2><center><input type="submit" class="button" value="Änderungen speichern!"></center></td></tr>'.
                      '</table></form>');
            addnav('',File.'?editorStep=save');
            addnav('Aktionen');
            addnav('Abbrechen',File.'?editorStep=show');
        
    break;
    
    case 'save':
        
        page_header('Rasseneditor - Rasse erstellen');
        
            if( is_writeable('Races/') && realpath('Races/') ) {
                
                $arr = array(
                             'Name' => $_POST['name'],
                             'cName' => $_POST['cname'],
                             'Plural' => $_POST['plural'],
                             'cPlural' => $_POST['cplural'],
                             'Dk' => $_POST['dk'],
                             'Bonus_Feld' => $_POST['bonus_feld'],
                             'Bonus' => $_POST['bonus'],
                             'Autor' => $_POST['autor'],
                             'Desc' => $_POST['desc'],
                             'Final_Desc' => $_POST['final_desc'],
                             );
                $arr = serialize($arr);
                
                $f = fopen('Races/0_'.$_POST['plural'].'.race','w+');
                fwrite($f,$arr);
                fclose($f);
                
                output('Die Rasse wurde erfolgreich erstellt!');
                addnav('Aktionen');
                addnav('Zum Editor',File.'?editorStep=show');
            
            } else {
                
                output('Konnte Datei nicht beschreiben..');
                addnav('Aktionen');
                addnav('Zum Editor',File.'?editorStep=show');
            
            }
    
    break;

}

page_footer();

?> 