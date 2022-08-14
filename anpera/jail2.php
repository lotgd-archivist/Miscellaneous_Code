
ï»¿<?php

// 20160507

/*

LoGD - GefÃ¤ngniserweiterung

19.05.2004

Matthias "Vanion" Strauch



Erweitert, umgebaut, angepaÃŸt:

Chaosmaker <webmaster@chaosonline.de>

http://logd.chaosonline.de



Idee aufgegriffen und mehr oder weniger neu geschrieben: anpera

  Stadtwache bringt Einbrecher automatisch an Pranger. Angeprangerter kann chatten

  und durch LÃ¶sen eines Sudokus freikommen. Er kann mit Items beworfen werden, die

  ihm danach gehÃ¶ren. Items der Klasse "Fauliges" und daneben geworfene Items

  werden gelÃ¶scht.



*/



require_once("common.php");



checkday();



page_header("Der Pranger");

if ($_GET['op'] == "werfen") {

   output("Du kramst in deinen Taschen nach irgendetwas zum Werfen. Was mÃ¶chtest du werfen?`n`n");

   $sql="SELECT id, name FROM items where owner=".$session['user']['acctid']." AND (class='Beute' OR class='Komponente' OR class='Geschenk' OR class='Fauliges')";

   $result = db_query($sql);

   if (db_num_rows($result)>0){

      for ($i=0;$i<db_num_rows($result);$i++){

         $row = db_fetch_assoc($result);

         addnav("","jail2.php?op=werfen2&id=".$row['id']);

     $output.="<a href='jail2.php?op=werfen2&id=".$row['id']."'>";

         output($row['name']);

         $output.="</a>";

         output("`n");

         }

      } else {

         output("`nDu hast nichts zum Werfen.");

      }

   addnav("ZurÃ¼ck","jail2.php");

} else if ($_GET['op'] == "werfen2"){

   output("`&ToDo`n");

   $result = db_query("SELECT name, class FROM items WHERE id=".$_GET['id']);

   $row = db_fetch_assoc($result);

   output("`4Du wirfst `2".$row['name']." `4in Richtung Pranger. Eine einfache MÃ¶glichkeit, unliebsame GegenstÃ¤nde und nutzlose Geschenke loszuwerden, und gleichzeitig etwas Gutes zu tun. Jedenfalls wird es dir gut tun.`n");

   $sql="SELECT name,acctid,sex FROM accounts where jail = 1";

   $result2 = db_query($sql);

   $was = e_rand(0,db_num_rows($result2)+2);

   if ($was>db_num_rows($result2)){

      output("`4Dein `2".$row['name']."`4 fliegt in Richtung Pranger. Leider triffst du nicht. Na egal. Du hast ein Zeichen gesetzt, und `2".$row['name']." `4 war schlieÃŸlich doch noch fÃ¼r etwas gut.");

      $treffer = "nicht";

      db_query("DELETE FROM items WHERE id=".$_GET['id']);

   } else {

      for ($i=0;$i<$was;$i++){

         $row2=db_fetch_assoc($result2);

      }

      $treffer = "`6".$row2['name']."`4";

      output("`4Dein `2".$row['name']."`4 fliegt in Richtung Pranger und trifft `6".$row2['name']."`4. Du hast ein Zeichen gesetzt, und diese".($row2['sex']?"r ":"m ").$row2['name']."so richtig eins reingewÃ¼rgt.");

      if ($row['class'] != 'Fauliges'){

         output("`4Dass `2".$row['name']."`4 jetzt `6".$row2['name']."`4 gehÃ¶rt, stÃ¶rt dich dabei irgendwie gar nicht.");

         db_query("UPDATE items SET owner=".$row2['acctid']." WHERE id=".$_GET['id']);

      } else {

         db_query("DELETE FROM items WHERE id=".$_GET['id']);

      }

   }

   db_query("INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'jail',".$session['user']['acctid'].",'/me `4wirft `2".$row['name']." `4in Richtung Pranger und trifft $treffer!')");

   addnav("ZurÃ¼ck","jail2.php");

} else if ($_GET['op'] == "fliehen"){

   output("`b`#FREI!`b`n`nDu schaffst es tatsÃ¤chlich, dich nach dieser Konzentrationsaufgabe vom Pranger zu befreien.");

   addnav("Ins Dorf","village.php");

   $session['user']['location'] = 0;

   $session['user']['jail'] = 0;

   addnews("`%".$session['user']['name']."`2 hat es irgendwie geschafft, vom Pranger zu fliehen.");  

} else {

   if ($session['user']['jail'] == false) {

      // Wenn man grad freigekommen ist...

      if ($session['user']['location']==9) $session['user']['location']=0;

      addcommentary();

      output("Auf dem Marktplatz ist ein groÃŸer Pranger aufgebaut worden - hier kÃ¶nnen offenbar diejenigen angeprangert werden, die sich im Dorf nicht sonderlich beliebt gemacht haben, oder von den Dorfwachen bei krummen 'GeschÃ¤ften' erwischt wurden.`n");

      output("Die vorbeikommenden Dorfbewohner beschimpfen die Angeprangerten lautstark, gelegentlich fliegt auch mal ein faules Ei oder eine Tomate.`n`n");

      // Auslesen, wer am Pranger steht

      $sql="SELECT name FROM accounts where jail = 1";

      $result = db_query($sql);

      if (db_num_rows($result)>0){

         addnav("Etwas werfen","jail2.php?op=werfen");

         output("Derzeit am Pranger:`n`n");

     for ($i=0;$i<db_num_rows($result);$i++){

            $row = db_fetch_assoc($result);

            output("`6    ".$row['name']."`n");

         }

      } else {

         output("`nDerzeit ist niemand angeprangert.");

      }

      output("`n`n");

      viewcommentary("jail","VerhÃ¶hne die Angeprangerten:",25,"hÃ¶hnt");

      addnav("ZurÃ¼ck zum Dorf","village.php");

   } else {

      $session['user']['location'] = 9;

      if ($_GET['act'] == "logout") {

         redirect("login.php?op=logout");

      }    else {

         $sql = "SELECT * FROM news WHERE 1 ORDER BY newsid DESC LIMIT 1";

         $result = db_query($sql) or die(db_error(LINK));

         $row = db_fetch_assoc($result);

         output("`n`c`i".$row['newstext']."`i`c`n`n");

         output("Du bist noch fÃ¼r den Rest des Tages am Pranger! Die vorbeikommenden Dorfbewohner schimpfen auf dich, und alle mÃ¶glichen GegenstÃ¤nde fliegen gelegentlich in deine Richtung. Du weiÃŸt, dass du die besten davon spÃ¤ter aufheben wirst, aber wie konntest du nur in diese Situation geraten...`n`n");

         output("Dir bleibt eigentlich nichts anderes Ã¼brig, als die Schmach Ã¼ber dich ergehen zu lassen und im Kopf ZahlenrÃ¤tsel zu lÃ¶sen.");

         // Auslesen, wer am Pranger steht

         $sql="SELECT name FROM accounts where jail = 1";

         $result = db_query($sql);

         if (db_num_rows($result)>1){

            output("Derzeit mit dir am Pranger:`n`n");

        for ($i=0;$i<db_num_rows($result);$i++){

               $row = db_fetch_assoc($result);

               output("`6    ".$row['name']."`n");

            }

         } else {

            output("`nDerzeit ist auÃŸer dir niemand angeprangert.");

         }

         output("`n`n");

         addcommentary();

         viewcommentary("jail","Winseln",25,"winselt");

         addnav("Sudoku","sudoku2.php");

         addnav("KÃ¤mpferliste","list.php");

         addnav("Logout","jail2.php?act=logout");

      }

   }

}

if ($session['user']['superuser']>=3) addnav("Admingrotte","superuser.php");

page_footer();

?>

