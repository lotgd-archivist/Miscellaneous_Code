
<?php

/*

    ~Geschrieben von Eliwood~

    Nutzungsbedinnungen:

      Um diesen Markt nutzen zu dÃ¼rfen, verlange ich, dass, als Teil des Copyright,

      die Speziell markierten Stellen NICHT gelÃ¶scht werden.

      Ebenso darf an diesen Stellen KEIN Text GEÃ„NDERT werden (=> Ãœbersetzungen in andere Sprachen

      nur mit Absprache mit mir).

      

    <basilius PUNKT sauter !AT! hispeed [PUNKT} ch>



    SQL:

      ALTER TABLE `items` CHANGE `owner` `owner` INT( 11 ) NOT NULL DEFAULT '0'

      

      CREATE TABLE `itemtransfer` (

      `itemid` INT( 11 ) UNSIGNED NOT NULL ,

      `seller` INT( 11 ) UNSIGNED NOT NULL ,

      `buyer` INT( 11 ) UNSIGNED NOT NULL ,

      `gold` INT( 11 ) UNSIGNED NOT NULL ,

      `gems` INT( 11 ) UNSIGNED NOT NULL ,

      `entrydate` DATETIME NOT NULL ,

      PRIMARY KEY ( `itemid` )

      ) TYPE = MYISAM ;



    newday.php, Ã„nderungen:

    

    ******************************************************

    Nach:

    ******************************************************

      $session['user']['drunkenness']=0;

          $session['user']['bounties']=0;

          

    ******************************************************

    Setze:

    ******************************************************

      // Markt - Transferbugloses verkaufen

          Require_once './lib/markt-funktionen.php';

      define('ACCTID',$session['user']['acctid']);



      $selleditems = markt_query_selleditems();



      if($selleditems['rows'] > 0)

      {

        $goldplus = 0;

        $gemsplus = 0;



        while($row = db_fetch_assoc($selleditems['result']))

        {

          $body = '`5'.$row['buyername'].'`3 hat auf dem Markt dein Item `^'.$row['name'].'`3 gesehen und hat es fÃ¼r `^'

            .$row['gold'].' Gold`3 und `5'.$row['gems'].' Edelsteine`3 gekauft. Du bist glÃ¼cklich Ã¼ber dein Verdientes Geld.';



          systemmail(ACCTID,'`^Item verkauft!',$body,-1);



          markt_delete_selleditem($row['itemid']);



          $goldplus+= $row['gold'];

          $gemsplus+= $row['gems'];

          unset($row);

        }



        $session['user']['gold']+=$goldplus;

        $session['user']['gems']+=$gemsplus;

        output('`n`n`3Du hast heute `^'.$goldplus.' Gold`3 und `5'.$gemsplus.' Edelsteine`3 durch den Verkauf von GegenstÃ¤nden in der Halle verdient!`0');

      }

      

      unset($goldplus,$gemsplus,$selleditems);



  ******************************************************

  Ã–ffne invhandler.php, Suche (Auf Klammern achten!):

  ******************************************************



  }else if ($_GET['op']=="house") {



    if (db_num_rows(db_query("SELECT id FROM items WHERE name='".stripslashes($item[name])."' AND class='MÃ¶bel' AND owner=$item[owner]"))>0){

        db_query("DELETE FROM items WHERE name='".stripslashes($item[name])."' AND class='MÃ¶bel' AND owner=$item[owner]");

        output("Du hast `q$item[name]`Q schon im Haus. Kurzerhand fliegt `q$item[name]`Q raus und wird duch das neuere StÃ¼ck ersetzt.");

    }else{

        output("`QDu suchst fÃ¼r `q$item[name]`Q einen Ehrenplatz in deinem Haus, an dem `q$item[name]`Q von jetzt an den Staub fangen wird.");

    }

    db_query("UPDATE items SET class='MÃ¶bel',gold=1,gems=0,value1=".$session[user][house]." WHERE id=$_GET[id]");

    

    ******************************************************

    FÃ¼ge danach ein:

    ******************************************************

    }else if ($_GET['op']=="house2") {



    if (db_num_rows(db_query("SELECT id FROM items WHERE name='".stripslashes($item[name])."' AND class='MÃ¶bel' AND owner=$item[owner]"))>1){

        db_query("DELETE FROM items WHERE name='".stripslashes($item[name])."' AND class='MÃ¶bel' AND owner=$item[owner]");

        output("Du hast `q$item[name]`Q schon im Haus. Kurzerhand fliegt `q$item[name]`Q raus und wird duch das neuere StÃ¼ck ersetzt.");

    }else{

        output("`QDu suchst fÃ¼r `q$item[name]`Q einen Ehrenplatz in deinem Haus, an dem `q$item[name]`Q von jetzt an den Staub fangen wird.");

    }

    db_query("UPDATE items SET class='MÃ¶bel',gold=1,gems=0,value1=".$session[user][house]." WHERE id=$_GET[id]");



    ******************************************************



*/

Require_once "common.php";

Require_once "./lib/markt-funktionen.php";



$filename = basename(__FILE__);



page_header('Der grosse Markt');

checkday();



output("`c`b`#Der Grosse Markt`0`b`c");



// Kostanten

define('ACCTID',$session['user']['acctid']);

define('MARKT_ADMIN',($session['user']['superuser']>=3?true:false));

define('MARKT_DEFAULT_ITEMCLASS','MÃ¶bel');



switch($_GET['op']){

  case '':

    Include './includes/markt-main.inc.php';

    break;

    

  case 'showitems':

    Include './includes/markt-showitems.inc.php';

    

    break;



  case 'buy':

    Include './includes/markt-buy.inc.php';

    break;



  case 'sellitems':

    Include './includes/markt-sellitems.inc.php';

    break;



  case 'sellitems2':

    Include './includes/markt-sellitems2.inc.php';

    break;



  case 'sellitems3':

    Include './includes/markt-sellitems3.inc.php';

    break;



  case 'tafel':

    Include './includes/markt-tafel.inc.php';

    break;



  // Hier kommt man eigentlich nur hin, wenn man als Admin nen Fehler gemacht hat.

  // Naja, um den User keine Fehlermeldungen oder weisse Seiten zumuten zu lassen,

  //    leiten wir ihn zurÃ¼ck in die Markthalle...

  default:

    redirect($filename);

    break;

}



addnav('MarktstÃ¤nde');

addnav('MÃ¶bel',$filename.'?op=showitems&itemclass=moebel');

addnav('Schmuck',$filename.'?op=showitems&itemclass=schmuck');

addnav('Beute',$filename.'?op=showitems&itemclass=beute');



addnav('Aktionen');

addnav('MÃ¶bel anbieten',$filename.'?op=sellitems&itemclass=moebel');

addnav('Schmuck anbieten',$filename.'?op=sellitems&itemclass=schmuck');

addnav('Beute anbieten',$filename.'?op=sellitems&itemclass=beute');



// Achtung! Dies ist ein Teil der Nutzungsbedinnungen, weshalb das entfernen der Links nicht gestattet ist!!

addnav('Sonstiges');

addnav('Die Gedenktafel',$filename.'?op=tafel');

// Ende



addnav('ZurÃ¼ck');

if($back === true) addnav('`$ZurÃ¼ck`0',$backlink);

addnav('ZurÃ¼ck zum Dorf','village.php');







page_footer();

?>



