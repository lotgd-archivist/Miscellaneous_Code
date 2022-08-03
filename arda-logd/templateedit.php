<?

// WRITTEN BY Hadriel
// Black Eye RP-LOGD
// 26-02-2006

require_once "common.php";
isnewday(3);

page_header("Template Editor");

addnav("G?Zurück zur Grotte","superuser.php");
addnav("W?Zurück zum Weltlichen","village.php");
addnav("Refresh","templateedit.php");
addnav("Einstellungen Editieren","templateedit.php?op=cfash");
addnav("Neue Tpl's &uuml;berprüfen","templateedit.php?op=check",true);
$ausgabe = "";
  switch($_GET['op']){
    default: 
    
    break;
    case "check":
      if ($handle = @opendir("templates")){
        $filename = array();
                 while (false !== ($file = @readdir($handle))){
            if (strpos($file,".htm")>0 && $file!='index.html'){
                array_push($filename,$file);
            }
                  }
                if (count($filename)==0){
             $ausgabe.="`b`@<h1>Keine Templates-- keine Einstellungen... So ist das Leben.</h1>`n";
        }else{
            output("`7<b>Templates Einstellungen:</b><br>",true);
        
        // eingetragene templates auslesen
            $sql="SELECT tsrc FROM templates";
            $result=mysql_query($sql) or die(mysql_error(LINK));
            $anzahl=mysql_num_rows($result);
            // in array speichern
            while($row=mysql_fetch_assoc($result)){
              $files[$row[tsrc]]='yupp';
            }
            // checken
            $i=0;
            while (list($key,$val)=each($filename)){
              if ($files[$val]!='yupp'){
                $sql="INSERT INTO templates (templatename,tsrc,freefor) VALUES ('Kein Name','".$val."','0')";
                mysql_query($sql) or die(mysql_error(LINK));
                $i++;
              }
              else{
                $files[$val]='alt';
              }
            }
            if ($i) $ausgabe.="<h3>Es wurden <b><u>$i</u></b> neue Templates eingetragen. Diese können jetzt angepasst werden</h3><br>";
                }
    }else{
         $ausgabe.="`c`b`\$FEHLER!!!`b`c`&Kann den Ordner mit den Templates nicht finden. Bitte benachrichtige den Admin!! Du bist der Admin?!?... Ja... das könnte sich zum Problem entwickeln";
    }

        // gelöschte Waldspecials aus DB löschen
        $j=0;

        if (count($files)){
           reset($files);
           while (list($key,$val)=each($files)){
            if ($val!='alt'){
               $sql="DELETE FROM templates WHERE tsrc='$key'";
               mysql_query($sql) or die(mysql_error(LINK));
               $ausgabe.="$sql <br>";
               $j++;
            }
          }
        }

        if ($j) $ausgabe.="<h3>Es wurden <b><u>$j</u></b> Templates aus der Datenbank gelöscht</h3><br>";

        if ($ausgabe=='') $ausgabe='<h2>Es gibt keine Veränderungen im templates-Ordner... </h2>';
    break;
    case "cfash":
      $sql="SELECT * FROM templates ORDER BY tsrc";
    $result=mysql_query($sql);
    $anzahl=mysql_num_rows($result);
 if ($anzahl){
    $coname[0] = 'Hadriel';
    $namen[0] ='deZent';
    $namen[1] ='draKarr';
    $namen[2] ='Kwaen';
    $namen[3] = 'Chaosmaker';
    shuffle ($namen);
    $name=$coname[0].' und Vorlage von '.$namen[0].' / '.$namen[1].' / '.$namen[2].' / '.$namen[3];
    $ausgabe.="
    `n`n
    Templates Editor by $name`n`n
     <form action='templateedit.php?op=save' method='POST'>";
    addnav("","templateedit.php?op=save");
    $ausgabe.="<table width='600px'>";
    $ausgabe.="<tr>
               <td>Nummer</td>
               <td>file-Name</td>
               <td>Name</td>
               <td>Free for</td>
               <td>Onlineedit</td>
             </tr>";
     $i=0;
    while($row=mysql_fetch_assoc($result)){
       $ausgabe.='<tr>';
       $ausgabe.="<td>".($i+1)."</td>";
       $ausgabe.="<td><font size=+1 color=black>$row[tsrc]</font></td>";
       $ausgabe.="<td><input type='text' name='data[".$i."][templatename]' value='$row[templatename]' size='30'>
                 </td>";
       $ausgabe.="<td><select name='data[".$i."][freefor]'>
                        <option value='0' ".($row[freefor]=='0'?"selected":"").">Für alle sichtbar</option>
                        <option value='1' ".($row[freefor]=='1'?"selected":"").">Für SU >=1 sichtbar</option>
                        <option value='2' ".($row[freefor]=='2'?"selected":"").">Für SU >=2 sichtbar</option>
                        <option value='3' ".($row[freefor]=='3'?"selected":"").">Für SU >=3 sichtbar</option>
                       </select>
                 </td>";
       $ausgabe.="<td><a href='templateedit.php?op=onedit&tsrc=".str_replace('.htm','',$row[tsrc])."'>Edit</a></td>";
       addnav("","templateedit.php?op=onedit&tsrc=".str_replace('.htm','',$row[tsrc]));
       $ausgabe.="<input type='hidden' name='data[".$i."][tsrc]' value='$row[tsrc]'>";
       $ausgabe.="<input type='hidden' name='data[".$i."][tid]' value='$row[tid]'>";
       $ausgabe.='</tr>';
    $i++;
    }

    $ausgabe.="</table><br>";
    $ausgabe.="<input type='submit' name='s1' value='Einstellungen speichern'></form>";
 } // ende check ob was in DB steht
 else{  // steht nix in DB
   $ausgabe.='<h1>Du solltest erstmal ein paar Templates importieren!</h1>';
 }
    break;
    case "save":
    for ($i=0;$i<count($_POST[data]);$i++){

    $sql='UPDATE templates SET freefor="'.$_POST[data][$i][freefor].'", templatename = "'.$_POST[data][$i][templatename].'" WHERE tid = '.$_POST[data][$i][tid].';' ;
    mysql_query($sql);
    //$ausgabe.=$_POST[data][$i][filename].'--> "'.$sql.'" <br><br>';
    $check= mysql_error();
    if ($check!='')  $ausgabe.='<br><b>'.$check.'</b><br>';
    $ausgabe.=$_POST[data][$i][tsrc].'-> <strong>Ok</strong><br />';
  }
    break;
    case "onedit":
      if(file_exists('./templates/'.$_GET['tsrc'].'.htm')){
        $con = file_get_contents('./templates/'.$_GET['tsrc'].'.htm');
        output("Edit it: `n`n");
        $raw = '<form action="templateedit.php?op=save2" method="post">
        <input type="hidden" name="file" value="'.$_GET["tsrc"].'" /><br />
        <textarea name="content" cols="100" rows="20" style="white-space:nowrap;">'.$con.'</textarea>
        <br /><input type="submit" value="speichern" />
        </form>';
        addnav("","templateedit.php?op=save2");
        output($raw,true);
      }
    break;
    case "save2":
    $_POST['content'] = stripslashes($_POST['content']);
    $fp       = fopen('./templates/'.$_POST[file].'.htm','w+');
        fwrite($fp, $_POST['content']);
        fclose($fp);
        chmod('./templates/'.$_POST[file].'.htm',0777);
        //include('./templates/'.$_POST[file].'.htm');
        output("`6Template erfolgreich geändert!`n`n");
    break;
  }
  output ($ausgabe,true);
page_footer();
?>