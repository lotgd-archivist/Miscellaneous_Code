<?php

    /*-------------------------------------------
    # Umsetzung by Lunastra                      #
    # Idee: Kazumi                               #
    # Original auf: lunaria-logd.de              #
    #                                            #
    # Bitte lasst mein Kästchen stehen, danke :3 #
    --------------------------------------------*/
    
output("`n`n`c`b`T Keksregen`b`c`n");
 //Navi
switch($_GET['op']) {
  default:
    output("`QDu streifst nichts ahnend durch den Wald, als dich aufeinmal etwas am Kopf trifft. Du schaust dich um doch siehst du
    nichts, also gehst du weiter. Schon wieder trifft dich etwas am Kopf. Du schaust nach oben, als es plötzlich beginnt Kekse
    zu regnen. JA, du siehst richtig, es regnet tatsächlich Kekse. Was für ein verrücktes Wetter soll das denn sein??
    Du hebst einen Keks auf. Was willst du nun damit machen?");
    addnav("Keks essen","forest.php?op=eat");
    addnav("Keks verschenken","forest.php?op=schenken&act=ja");
    $session['user']['specialinc']="keksregen.php";
  break;
  case'eat':
    $zahl=e_rand(1,10);
    switch($zahl){
      case 1:
      case 2:
        output("`qDu beißt in den Keks und merkst wie die ein fürchterlicher Schmerz überkommt. Da war wohl etwas Hartes
        in den Keks eingebacken. Du spuckst ihn wieder aus, und reibst das harte Ding ab. Doch dein schmerzverzerrtes
        Geischt wandelt sich schnell in ein Lächeln, als du plötzlich `#einen Edelstein`q in deinen Händen hälst.");
        addnav("In den Wald","forest.php");
        $session['user']['hitpoints']*=0.9;
        $session['user']['gems']++;
      break;
      case 3:
      case 4:
        output("`qDu beißt in den Keks, eine seltsame Kraft durchströmt deinen Körper. Deine Angriffskraft ist merklich gestiegen.");        
        $session['user']['attack']+=5;
        break;;
      case 5:
      case 6:
        output("`qDu beißt in den Keks, doch plötzlich überkommt dich ein Gefühl von Übelkeit. Du musst dich erst etwas
        erholen und du fühlst dich schwächer.");
        addnav("In den Wald","forest.php");
        if ($session['user']['turns']>=5){
          $session['user']['turns']-=5;
        }else{
          $session['user']['turns']=0;
        }
        if ($session['user']['defence']>=5){
          $session['user']['defence']-=5;
        }else{
          $session['user']['defence']=1;
        }
      break;
      case 7:
      case 8:
        output("`qDu findest einen sehr seltsam leuchtenden Keks. Er hat einen goldenen Schimmer. Nur das beste hoffend,
        beißt du hinein und wartest ab was nun passiert...");
        $keks=e_rand(1,3);
        switch(e_rand(1,3)){
          case 1:
          case 2: 
            output("`^Eine übermenschliche Energie durchströmt deinen Körper. Sie wird dir Kraft verleihen.
            `n`6Du hast die 'Macht des Schokokekses' erhalten!");
            addnav("In den Wald","forest.php");
            $session['bufflist']['segen3'] = array(
              "name"=>"`TMacht des Schokokekses"
              ,"rounds"=>20
              ,"wearoff"=>"`tDie Macht des Schokokekses lässt wieder nach."
              ,"dmgmod"=>2.0
              ,"roundmsg"=>"`tDie Macht des Schokokekses ist mit dir!"
              ,"activate"=>"offense");
            addnews($session['user']['name']." `^hat die Macht des Schokokekses erhalten!");                
          break;
          case 3: 
            output("`^Eine absolut übermenschliche Kraft durchströmt deinen Körper. Du spürst wie deine Kräfte heranwachsen.
            Sie wachsen...und wachsen....und wachsen...`n 
            `\$Auf einmal wird dir schwarz vor Augen und du fällst einfach um.");
            addnav("Tägliche News","news.php");
            addnews($session['user']['name']." `\$konnte die Macht des Schokokekses nicht kontrollieren und leistet nun Rui Gesellschaft.");
            $session['user']['alive']=false;
            $session['user']['hitpoints']=0;
            $session['user']['experience']=$session['user']['experience']*0.95;
        }
      break;
      case 9:
      case 10:
        $gold = e_rand($session['user']['level']*5,$session['user']['level']*50); 
        output("Du zögerst nicht lange und mampfst sofort einen Keks. Leider bekommst du davon einen fürchterlichen
        Ausschlag, der leider sehr häßlich ist und wie verrückt juckt. Als du grade wieder gehen willst, stolperst du über einen 
        kleinen Beutel in dem du `^".$gold." Goldstücke. 'Immerhin etwas...' denkst du und gehst wieder.");
        $sesion['user']['charm']-=2;
        $session['user']['gold']+=$gold;
      break;
    }
  break;
  case 'suche':
    $sql = "SELECT acctid,name FROM accounts WHERE login='".mysql_real_escape_string($_POST['q'])."'";
    $result = db_query($sql);
    if (db_num_rows($result)==1) {
      $row = db_fetch_assoc($result);
      $name = $row['acctid'];
      db_query("INSERT INTO items (name,owner,class,gold,gems,description) VALUES ('`TSchokokeks`0',".$name.",'Geschenk',500,1,'Ein leckerer Keks. Er ist von ".$session['user']['name']."')");
      db_query("UPDATE accounts SET attack=attack+3 WHERE acctid=".$row['acctid']);
      $msg = $session['user']['name'].'`7 hat dir ein Geschenk geschickt. Du öffnest es. Es ist ein `TSchokokeks`7. Wie lecker, du isst ihn gleich und fühlst dich nun etwas stärker!';
      systemmail($name,"`2Geschenk erhalten!`2",$msg);
      output('`7 Du hast den Keks erfolgreich per Bote an '.$row['name'].' verschickt. Er oder sie wird sich bestimmt darüber freuen.');
      addnav('Zurück');
      addnav("Zurück in den Wald","forest.php");
      $session['user']['specialinc'] = '';
    }else{
      output('`$Keine oder zu viele Ergebnisse gefunden`0');
      addnav('Nochmal versuchen','forest.php?op=schenken&act=ja');
      $session['user']['specialinc'] = 'keksregen.php';
    }
  break;
  case 'schenken':
    switch ($_GET['act']) {
      case 'ja':
        output('`TDu beschließt den Keks zu verschenken, doch wer soll ihn jetzt erhalten?`nKleiner Hinweis: Du musst den Login-Namen eingeben.`n`0
        <form action=\'forest.php?op=suche\' method=\'POST\'>Wem möchtest du den Keks schenken: <input name=\'q\' id=\'q\'><input type=\'submit\' class=\'button\'></form>
        <script language=\'JavaScript\'>document.getElementById(\'q\').focus();</script>',true);
        addnav('','forest.php?op=suche');
        addnav('Zurück','forest.php');
        $session['user']['specialinc'] = 'keksregen.php';
      break;
    }
  break;
}
 
?>