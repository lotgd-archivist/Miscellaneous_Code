
<?php

/*

*   Neues Inventar

*   Â© 2005 by Eliwood

*   Bugs, WÃ¼nsche, Anregungen an:

*   basilius.sauter@hispeed.ch

*   FÃ¼r jede Itemklasse beliebig Ã¤nderbar

*/

/* Herzdatei einbinden */

require_once "common.php";



$navigation = array(

  "Waffen & RÃ¼stungen"=>false,

  "Waffen"=>"Waffe",

  "RÃ¼stungen"=>"RÃ¼stung",

  "Zaubereien"=>false,

  "Zauber"=>"Zauber",

  "HeiltrÃ¤nke"=>"Heiltrank",

  "HauszubehÃ¶hr"=>false,

  "MÃ¶bel"=>"MÃ¶bel",

  "HausschlÃ¼ssel"=>"SchlÃ¼ssel",

  "Sonstiges"=>false,

  "Artefakte"=>"Artefakt",

  "Beute"=>"Beute",

  "Geschenke"=>"Geschenk",

  "Schmuck"=>"Schmuck",

  "Sonstiges"=>false,

  "Alles andere"=>"other",

);



/* Funktionen einbinden */

require_once "lib/inventory.php";

//require_once "lib/pherae_funcs.php";



/* Neuer Tag? Ja? Dann mach es auch! */

checkday();



/* Seitentitel setzen */

page_header("Inventar");



/* Sortieren... */

if ($_GET['sorti']=="") $_GET['sorti']="class ASC, name ASC, id";



/* Kategorie festlegen */

if(!isset($_GET['class'])) $class = RawURLEncode('SchlÃ¼ssel');

else $class = RawURLEncode($_GET['class']);



/* Sortierung festlegen */

if(!isset($_GET['sort'])) $_GET['sort'] = "ASC";



/* Items holen */

if($class == 'other')

{

  while(list($key,$val) = each($navigation))

  {

    $whereclouse2[] = " `class` != '".RawURLDecode($val)."'";

  }

  $whereclouse = "AND( ".implode(" AND ",$whereclouse2)." )";

}

else $whereclouse = "AND class='".RawURLDecode($class)."' ";



$lines = db_query(

"SELECT * FROM items "

."WHERE owner=".$session['user']['acctid']." "

.$whereclouse

."ORDER BY $_GET[sorti] $_GET[sort]"

.page("id","items","inventory.php?class=".$class."","WHERE owner=".$session['user']['acctid']." ".$whereclouse,25)."");



/* Navigation */

addnav("Inventar");

addnav(" ","");

/*

    Navigation | FÃ¼r Erweiterung folgende Syntax:

    "Titel des Navs"=>"Klassenname in der Datenbank"

    FÃ¼r Navtitel anstatt Klassennamen einfach false verwenden

*/



inventorynavs($navigation);



addnav("ZurÃ¼ck");

if(!isset($session['return'])){

  addnav("ZurÃ¼ck zum Dorfplatz","village.php");

}else{

  addnav("`%ZurÃ¼ck",$session['return']);

}

  /* Nach Name sortieren */

  allownav("inventory.php?class=$class&sorti=name&sort=".givesort($_GET['sort'])."&page=$_GET[page]");

  /* Nach Itemklasse sortieren */

  allownav("inventory.php?class=$class&sorti=class&psort=".givesort($_GET['sort'])."&age=$_GET[page]");

  /* Nach Edelsteinen sortieren */

  allownav("inventory.php?class=$class&sorti=gems&sort=".givesort($_GET['sort'])."&page=$_GET[page]");

  /* Nach Wert 1 Sortieren */

  allownav("inventory.php?class=$class&sorti=value1&sort=".givesort($_GET['sort'])."&page=$_GET[page]");

  /* Nach Wert 2 sortieren */

  allownav("inventory.php?class=$class&sorti=value2&sort=".givesort($_GET['sort'])."&page=$_GET[page]");

  

  output("`c`bDie BesitztÃ¼mer von ".$session['user']['name']."`b`c`n`n");

  output("<table cellspacing='1' cellpadding='2' align='center'><tr>"

        ."<td>`b"

        ."<a href='inventory.php?class=$class&sorti=name&sort=".givesort($_GET['sort'])."&page=$_GET[page]'>"

        ."Itemname"

        ."</a>`b</td>"

        ."<td>`b"

        ."<a href='inventory.php?class=$class&sorti=class&sort=".givesort($_GET['sort'])."&page=$_GET[page]'>"

        ."Klasse</a>"

        ."`b</td>"

        ."<td>`b"

        ."<a href='inventory.php?class=$class&sorti=value1&sort=".givesort($_GET['sort'])."&page=$_GET[page]'>"

        ."Wert 1</a>"

        ."`b</td>"

        ."<td>`b"

        ."<a href='inventory.php?class=$class&sorti=value2&sort=".givesort($_GET['sort'])."&page=$_GET[page]'>"

        ."Wert 2</a>"

        ."`b</td>"

        ."<td>`b"

        ."<a href='inventory.php?class=$class&sorti=gems&sort=".givesort($_GET['sort'])."&page=$_GET[page]'>"

        ."Verkaufswert</a>"

        ."`b</td>"

        ."<td>`bAktion`b</td></tr>",true);

  $i = 0;

  if($class == UrlEnCode("Artefakt")){

    if (getsetting("hasegg",0)==$session['user']['acctid']){

      output("<tr class='trdark'><td>`^Das goldene Ei`0</td><td></td><td></td><td></td><td>`4UnverkÃ¤uflich`0</td></tr>",true);

    }

    //$sql_stone = ("SELECT SQL_NO_CACHE * FROM `items` WHERE `class`='Allmightys Stein' AND `owner`='".$session['user']['acctid']."'");

    //$result_stone = db_query($sql_stone) or die("A Chleeene Fehlerchen, findest schon.");

    //if(db_num_rows($result_stone)==1)

    //{

      //$stone = db_Fetch_Assoc($result_stone);

      /*output("<tr class='trdark'>"

              ."<td>`&".$stone['name']."`0</td>"

              ."<td>`!".$stone['class']."`0</td>"

              ."<td align='right'>".$stone['value1']."</td>"

              ."<td align='right'>".$stone['value2']."</td>"

              ."<td>`4UnverkÃ¤uflich</td><td></td></tr>"

              ."</tr>"

              ."<tr class='trdark'>"

              ."<td align='left'>Beschreibung:</td>"

              ."<td colspan=5>".$stone['description']."</td>"

              ."</tr>"

              ,true);*/

    //}

  }

  while($item = db_fetch_assoc($lines)){

    $i++;

    $bgcolor=($i%2==1?"trlight":"trdark");

    output("<tr class='$bgcolor'>"

          ."<td>`&$item[name]`0</td>"

          ."<td>`9$item[class]`0</td>"

          ."<td align='right'>$item[value1]</td>"

          ."<td align='right'>$item[value2]</td>"

          ."<td>",true);

    if ($item[gold]==0 && $item[gems]==0){

      output("`4UnverkÃ¤uflich`0");

    }else{

      output("`^$item[gold]`0 Gold, `#$item[gems]`0 Edelsteine");

    }

        output("</td>"

          ."<td>[",true);

    if ($item['class']=="Waffe" || $item['class']=="RÃ¼stung"){ //|| $item['class']=="Ring" || $item['class']=="Schmuck")

      if($session['user']['weaponvalue']<0 && $item['class']=="Waffe"){

      }elseif($session['user']['armorvalue']<0 && $item['class']=="RÃ¼stung"){

      }else{

        output("<a href='invhandler.php?op=fit&id=$item[id]&back=$back'>AusrÃ¼sten</a>",true);

        addnav("","invhandler.php?op=fit&id=$item[id]&back=$back");

      }

      output("<a href='invhandler.php?op=throw&id=$item[id]&back=$back'>Wegwerfen</a>",true);

      addnav("","invhandler.php?op=throw&id=$item[id]&back=$back");

    }elseif ($item['class']=="Geschenk"){

      output("<a href='invhandler.php?op=throw&id=$item[id]&back=$back'>Wegwerfen</a>",true);

      addnav("","invhandler.php?op=throw&id=$item[id]&back=$back");

      if ($session[user][housekey]>0 && $session[user][house]==$session[user][housekey]){

        output(" | <a href='invhandler.php?op=house&id=$item[id]&back=$back'>Einlagern</a>",true);

        addnav("","invhandler.php?op=house&id=$item[id]&back=$back");

      }

    }elseif ($item['class'] == "MÃ¶bel" && $session['user']['housekey'] > 0 && $session['user']['house'] == $session['user']['housekey'] && $item['value1'] != $session['user']['house']){

      output("<a href='invhandler.php?op=throw&id=$item[id]&back=$back'>Wegwerfen</a>",true);

      addnav("","invhandler.php?op=throw&id=$item[id]&back=$back");

      output(" | <a href='invhandler.php?op=house&id=$item[id]&back=$back'>Einlagern</a>",true);

      addnav("","invhandler.php?op=house2&id=$item[id]&back=$back");

    }elseif ($item['class']!="Artefakt"){

      output("<a href='invhandler.php?op=throw&id=$item[id]&back=$back'>Wegwerfen</a>",true);

      addnav("","invhandler.php?op=throw&id=$item[id]&back=$back");

    }

    output("]</td>"

          ."</tr>"

          ."<tr class='$bgcolor'>"

          ."<td align='left'>Beschreibung:</td>"

          ."<td colspan=5>$item[description]</td>"

          ."</tr>",true);

   }

  output("</table>",true);

page_footer();

?>



