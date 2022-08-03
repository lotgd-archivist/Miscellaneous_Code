<?
require_once 'common.php';
page_header('Massenmailer');
/*
  +-----------------------------------+
  |      BOX NICHT ENTFERNEN!         |
  +-----------------------------------+
  | made by: Draza´ar                 |
  | idea by: Lynera                   |
  | http://logd.legend-of-vinestra.de |
  | drazaar@legend-of-vinestra.de     |
  +-----------------------------------+
  |      massmail.php V.1.0.3         |
  +-----------------------------------+

Einbau:
  Öffne: 
    superuser.php
  
  Suche:
    addnav("Statistiken","stats.php");
  
  Füge darunter ein:
    if($session['user']['superuser']>=3) addnav('Massenmails','massmail.php');
    
  SPEICHERN, SCHLIESSEN & HOCHLADEN!
  
*/





isnewday(2);                    # Hier Superusergrad verändern, falls erwünscht!

function MyNavs($NavName=false,$NavLink=false,$NavHeader=false){
        if($NavHeader!=false) addnav($NavHeader);
        if($NavName!=false && $NavLink!=false) addnav($NavName,$NavLink);
        addnav('Umkehren');
        addnav('Zurück zum Weltlichen','village.php');
        addnav('Zurück zur Grotte','superuser.php');
}

switch($_GET['op']){
        case '':
                $out .= '<form action="massmail.php?op=posted" method="POST">
                         <table border="0" bgcolor="#999999" cellspacing="1" cellpadding="3">
                           
                           <tr class="trhead">
                             <td colspan="2" align="center"><b>Massenmail verschicken</b></td>
                           </tr>
                           
                           <tr class="trlight">
                             <td><b>Mailart:</b></td>
                             <td><select name="what">
                                   <option selected value="1">YoM</option>
                                   <option value="2">E-Mail</option>
                                 </select></td>
                           </tr>
                           
                           <tr class="trlight">
                             <td><b>An User</b></td>
                             <td><select name="users">
                                   <option selected value="1">Alle User</option>
                                   <option value="2">Keine Teammitglieder</option>
                                   <option value="3">Nur Superuser>=1</option>
                                   <option value="4">Nur Superuser>=2</option>
                                   <option value="5">Nur Superuser>=3</option>
                   <option value="6">Nur Superuser>=4</option>
                                 </select></td>
                           </tr>
                           
                           <tr class="trdark">
                             <td><b>Betreff</b></td>
                             <td><input name="subject"></td>
                           </tr>
                           
                           <tr class="trdark">
                             <td><b>Text</b></td>
                             <td><textarea name="mail" class="input" cols="60" rows="8"></textarea></td>
                           </tr>
                           
                           <tr class="trlight">
                             <td colspan="2" align="center"><input type="submit" class="button" value="Abschicken"></td>
                           </tr>
                           
                         </table>';
                            
              /*$out .= '<form action="massmail.php?op=posted" method="POST">
                      Betreff: <input name="title"><br />
                      Text: <textarea cols="50" rows="8" class="input" name="mail"></textarea> 
                      <input type="submit" class="button" value="Abschicken">';*/
              addnav('','massmail.php?op=posted');
              MyNavs();
        break;
        case 'posted':
              if(empty($_POST['mail']) || empty($_POST['subject'])){
                      $out .= 'Du musst ALLE Felder ausfüllen!';
                      MyNavs('Zurück','massmail.php','Erneut eingeben');
              }
              else{
                      $what = $_POST['what'];
                      $subject = $_POST['subject'];
                      $mail = $_POST['mail'];
                      $users = $_POST['users'];
                      
                      switch($users):
                              case 1:
                                      $sqladd = '';
                              break;
                              case 2:
                                      $sqladd = ' AND `superuser` < "1"';
                              break;
                              case 3:
                                      $sqladd = ' AND `superuser` > "0"';
                              break;
                              case 4:
                                      $sqladd = ' AND `superuser` > "1"';
                              break;
                              case 5:
                                      $sqladd = ' AND `superuser` > "2"';
                              break;
                  case 6:
                                      $sqladd = ' AND `superuser` > "3"';
                              break;
                      endswitch;
                      
                      switch($what):
                              case 1:
                                      $result = db_query('SELECT `acctid` FROM `accounts` WHERE `locked` = "0"'.$sqladd.'');
                                      if(db_num_rows($result)==0){
                                              $out .= 'Massenmails sind dazu da um an eine MASSE von Spielern geschickt zu werden. Du hast aber von den ausgewählten Spielern keine Masse...nicht einmal einen :/ ...';
                                              MyNavs('Zurück','massmail.php','Erneut eingeben');
                                      }
                                      else{
                                              $players = db_num_rows($result);
                                              for($i=0;$i<db_num_rows($result);$i++){
                                                      $row = db_fetch_assoc($result);
                                                      systemmail($row['acctid'],'`^Massenmail: `0'.$subject,$mail);
                                              }
                                              $out .= 'YoM an '.$players.' Spieler geschickt!';
                                      }
                              break;
                              case 2:
                                      $result = db_query('SELECT `acctid`, `emailaddress` FROM `accounts` WHERE `locked` = "0" AND `emailaddress`>""'.$sqladd.'');
                                      if(db_num_rows($result)==0){
                                              $out .= 'Massenmails sind dazu da um an eine MASSE von Spielern geschickt zu werden. Du hast aber von den ausgewählten Spielern keine Masse...nicht einmal einen :/ ...';
                                              MyNavs('Zurück','massmail.php','Erneut eingeben');
                                      }
                                      else{
                                              $players = db_num_rows($result);
                                              for($i=0;$i<db_num_rows($result);$i++){
                                                      $row = db_fetch_assoc($result);
                                                      mail($row['emailaddress'],'Arda-LoGD: '.$subject,$mail);
                                              }
                                              $out .= 'E-mail an '.$players.' Spieler geschickt!';
                                      }
                              break;
                      endswitch; 
                      MyNavs('Neue Massenmail','massmail.php','Weiter');
              }      
        break;
}
rawoutput($out);
page_footer();
?>