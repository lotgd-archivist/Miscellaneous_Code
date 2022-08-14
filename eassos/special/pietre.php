
<?php
/*
/ pietre.php - Magic Stones V0.2.1
/ Originally by Excalibur (www.ogsi.it)
/ English cleanup by Talisman (dragonprime.cawsquad.net)
/ Original concept from Aris (www.ogsi.it)
/ May 2004
/ deutsch by theKlaus
/ Änderungen der Steine auf das konfortablere Item-System von Eliwood
... Um dem Transferbug zu entgehen xD
Ach ja, bevor ichs vergess, den Code hab ich auch noch verschönert *gg*

----install-instructions--------------------------------------------------
DIFFICULTY SCALE: easy

Forest Event for LotGD 0.9.7
Drop into your "Specials" folder
--------------------------------------------------------------------------

SQL-Modifikationen... In dieser abgeänderten Version: KEINE xD

Dafür aber SQL-Inserts... ;D

INSERT INTO `items` VALUES ('', '`%Kraft Stein', 'Allmightys Stein', 0, 0, 0, 0, 0, 'Einer von Allmightys Steinen', 0, 'a:7:{s:4:"name";s:11:"Kraft Stein";s:8:"roundmsg";s:22:"Der Stein stärkt dich!";s:7:"wearoff";s:40:"Die Kraft des Steins höhrt auf zu Wirken";s:6:"rounds";s:4:"1000";s:6:"atkmod";s:3:"1.5";s:6:"defmod";s:3:"1.5";s:8:"activate";s:15:"offense,defense";}');
INSERT INTO `items` VALUES ('', '`&Ladys Stein', 'Allmightys Stein', 0, 0, 0, 0, 0, 'Einer von Allmightys Steinen', 0, '');
INSERT INTO `items` VALUES ('', '`&Stein des Lichts', 'Allmightys Stein', 0, 0, 0, 0, 0, 'Einer von Allmightys Steinen', 0, '');
INSERT INTO `items` VALUES ('', '`&Stein der Reinheit', 'Allmightys Stein', 0, 0, 0, 0, 0, 'Einer von Allmightys Steinen', 0, 'a:0:{}');
INSERT INTO `items` VALUES ('', '`%Baldurs Stein', 'Allmightys Stein', 0, 0, 0, 0, 0, 'Einer von Allmightys Steinen', 0, '');
INSERT INTO `items` VALUES ('', '`#Cedriks Stein', 'Allmightys Stein', 0, 0, 0, 0, 0, 'Einer von Allmightys Steinen', 0, 'a:6:{s:4:"name";s:16:"Allmightys Stein";s:8:"roundmsg";s:22:"Der Stein stärkt dich!";s:7:"wearoff";s:40:"Die Kraft des Steins höhrt auf zu Wirken";s:6:"rounds";s:3:"500";s:6:"atkmod";s:3:"1.5";s:8:"activate";s:7:"offense";}');
INSERT INTO `items` VALUES ('', '`$Ramius Stein', 'Allmightys Stein', 0, 0, 0, 0, 0, 'Einer von Allmightys Steinen', 0, '');
INSERT INTO `items` VALUES ('', '`!Goldener Stein', 'Allmightys Stein', 0, 0, 0, 0, 0, 'Einer von Allmightys Steinen', 0, '');
INSERT INTO `items` VALUES ('', '`#Stein des Eroberers', 'Allmightys Stein', 0, 0, 0, 0, 0, 'Einer von Allmightys Steinen', 0, '');
INSERT INTO `items` VALUES ('', '`#Stein der Königin', 'Allmightys Stein', 48, 0, 0, 0, 0, 'Einer von Allmightys Steinen', 0, '');
INSERT INTO `items` VALUES ('', '`&Stein der Unschuld', 'Allmightys Stein', 0, 0, 0, 0, 0, 'Einer von Allmightys Steinen', 0, '');
INSERT INTO `items` VALUES ('', '`@Lukes Stein', 'Allmightys Stein', 0, 0, 0, 0, 0, 'Einer von Allmightys Steinen', 0, '');
INSERT INTO `items` VALUES ('', '`@Excaliburs Stein', 'Allmightys Stein', 0, 0, 0, 0, 0, 'Einer von Allmightys Steinen', 0, '');
INSERT INTO `items` VALUES ('', '`@Aris Stein', 'Allmightys Stein', 0, 0, 0, 0, 0, 'Einer von Allmightys Steinen', 0, 'a:6:{s:4:"name";s:10:"Aris Stein";s:8:"roundmsg";s:22:"Der Stein stärkt dich!";s:7:"wearoff";s:40:"Die Kraft des Steins höhrt auf zu Wirken";s:6:"rounds";s:4:"1000";s:6:"atkmod";s:3:"1.5";s:8:"activate";s:7:"offense";}');
INSERT INTO `items` VALUES ('', '`#Pegasus Stein', 'Allmightys Stein', 0, 0, 0, 0, 0, 'Einer von Allmightys Steinen', 0, '');
INSERT INTO `items` VALUES ('', '`#AllMighthys Stein', 'Allmightys Stein', 0, 0, 0, 0, 0, 'Einer von Allmightys Steinen', 0, 'a:6:{s:4:"name";s:16:"Allmightys Stein";s:8:"roundmsg";s:22:"Der Stein stärkt dich!";s:7:"wearoff";s:40:"Die Kraft des Steins höhrt auf zu Wirken";s:6:"rounds";s:4:"1000";s:6:"defmod";s:3:"1.5";s:8:"activate";s:7:"defense";}');
INSERT INTO `items` VALUES ('', '`#Königs Stein', 'Allmightys Stein', 0, 0, 0, 0, 0, 'Einer von Allmightys Steinen', 0, '');
INSERT INTO `items` VALUES ('', '`^Freundschafts Stein', 'Allmightys Stein', 0, 0, 0, 0, 0, 'Einer von Allmightys Steinen', 0, '');
INSERT INTO `items` VALUES ('', '`^Liebes Stein', 'Allmightys Stein', 0, 0, 0, 0, 0, 'Einer von Allmightys Steinen', 0, '');
INSERT INTO `items` VALUES ('', '`$Poker Stein', 'Allmightys Stein', 0, 0, 0, 0, 0, 'Einer von Allmightys Steinen', 0, '');


ALTER TABLE `items` CHANGE `class` `class` VARCHAR( 50 ) NOT NULL
--------------------------------------------------------------------------
----- In File:
newday.php

----- Find:
$config = unserialize($session['user']['donationconfig']);

----- Add before:
//Modification for pietre.php
        $result = db_query("SELECT * FROM items WHERE owner='".$session['user']['acctid']."' AND class='Allmightys Stein' LIMIT 1");
    $row = db_fetch_assoc($result);
    db_free_result($result);
    switch($row['name'])
    {
      case '`$Poker Stein':
        output("`n`n`\$Weil du den $row[name] `\$ besitzt , verlierst du einen Waldkampf!`n");
        $session['user']['turns']-=1;
        break;
      case '`^Liebes Stein':
        output("`n`n`\$Weil du den {$row[name]} `\$ besitzt , bekommst du einen Charmepunkt!`n");
        $session['user']['charm']+=1;
        break;
      case '`^Freundschafts Stein':
        output("`n`n`\$Weil du den {$row[name]} `\$ besitzt , bekommst du einen Waldkampf!`n");
        $session['user']['turns']+=1;
        break;
      case '`#Königs Stein':
        output("`n`n`\$Weil du den {$row[name]} `\$ besitzt , bekommst du 500 Gold!`n");
        $session['user']['gold']+=500;
        break;
      case '`#AllMighthys Stein':
        output("`n`n`\$Weil du den {$row[name]} `\$ besitzt , bekommst du mehr Angriff`n");
        $session['bufflist']['stone'] = unserialize($row['buff']);
        break;
      case '`#Pegasus Stein':
        output("`n`n`\$Weil du den {$row[name]} `\$ besitzt , bekommst du einen Waldkampf!`n");
        $session['user']['turns']+=1;
        break;
      case '`@Aris Stein':
        output("`n`n`\$Weil du den {$row[name]} `\$ besitzt , bekommst du mehr Angriff`n");
        $session[bufflist][stone] =  unserialize($row['buff']);
        break;
      case '`@Excaliburs Stein':
        output("`n`n`\$Weil du den {$row['name']} `\$ besitzt , hast du das Wissen eines Gelehrten`n");
        $session['user']['specialtyuses']['darkartuses']+=6;
        $session['user']['specialtyuses']['magicuses']+=6;
        $session['user']['specialtyuses']['thieveryuses']+=6;
        //$session['user']['specialtyuses']['fireuses']+=6;
        //$session['user']['specialtyuses']['wmagieuses']+=6;
        //$session['user']['specialtyuses']['emagieuses']+=6;
        break;
      case '`@Lukes Stein':
        output("`n`n`\$Weil du den {$row['name']} `\$ besitzt , bekommst du mehr Angriff`n");
        $session['user']['specialtyuses']['darkartuses']+=6;
        $session['user']['specialtyuses']['magicuses']+=6;
        $session['user']['specialtyuses']['thieveryuses']+=6;
        //$session['user']['specialtyuses']['fireuses']+=6;
        //$session['user']['specialtyuses']['wmagieuses']+=6;
        //$session['user']['specialtyuses']['emagieuses']+=6;
        break;
      case '`#Stein der Königin':
        output("`n`n`\$Weil du den {$row['name']} `\$ besitzt , bekommst du 500 Gold!`n");
        $session['user']['gold']+=500;
        break;
      case '`#Stein des Eroberers':
        output("`n`n`\$Weil du den {$row['name']} `\$ besitzt , verlierst du einen Waldkampf!`n");
        $session['user']['turns']-=1;
        break;
      case '`!Goldener Stein':
        output("`n`n`\$Weil du den {$row['name']} `\$ besitzt , bekommst du 1000 Gold!`n");
        $session['user']['gold']+=1000;
        break;
      case '`%Kraft Stein':
        output("`n`n`\$Weil du den {$row['name']} `\$ besitzt , bekommst du mehr Angriff und Verteidigung`n");
        $session['bufflist']['stone'] =  unserialize($row['buff']);
        break;
      case '`\$Ramius Stein':
        output("`n`n`\$Weil du den {$row['name']} `\$ besitzt , bekommst du die Macht des Waldgottes!`n");
        $session['user']['turns']+=10;
        break;
      case '`#Cedriks Stein':
        output("`n`n`\$Weil du den {$row['name']} `\$ besitzt , wirst du stärker!`n");
        $session['bufflist']['stone'] =  unserialize($row['buff']);
        break;
      case '`%Baldurs Stein':
        output("`n`n`\$Weil du den {$row['name']} `\$ besitzt , bekommst du 2 Waldkämpfe!`n");
        $session['user']['turns']+=2;
        break;
      case '`&Stein der Reinheit':
        output("`n`n`\$Weil du den {$row['name']} `\$ besitzt , bekommst du 2 Waldkämpfe!`n");
        $session['user']['turns']+=2;
        break;
      case '`&Stein des Lichts':
        output("`n`n`\$Weil du den {$row['name']} `\$ besitzt , hast du das Wissen eines Gelehrten`n");
        $session['user']['charm']++;
        break;
      case '`&Ladys Stein':
        output("`n`n`\$Weil du den {$row['name']} `\$ besitzt , bekommst du einen Edelstein`n");
        $session['user']['gems']+=1;
        break;
    }
//end pietre.php modification

--------------------------------------------------------------------------

Drop the code of monpietre.php where you like ... You can put in in hof.php (the new version from 0.9.Cool
or you use it "as is" giving a link from village.

Version History:
Ver. Alpha Created by Excalibur (www.ogsi.it)
Original Version posted to DragonPrime

// -Originally by: Excalibur
// -Contributors: Excalibur, Talisman
// May 2004
*/

// db_query("UPDATE items SET class='Allmightys Stein' WHERE class='Allmightys Steine'");
page_header("Allmightys Quelle");
output("<font size='+1'>`c`b`!Allmightys Quelle`b`c`n</font>",true);
$session['user']['specialinc']="pietre.php";

$result = db_query("SELECT name,class,id,owner,hvalue FROM items WHERE owner='".$session['user']['acctid']."' AND class='Allmightys Stein'");
if (db_num_rows($result)==0)
{
  switch($_GET[op])
  {
    case "":
    case "search":
      page_header("Die Quelle");
      output("`@Auf deinem Weg durch den Wald, auf der Suche nach Abenteuern, findest du eine klare Quelle, die einen übernatürlichen Schein ausstrahlt. Du bist durch Zufall über `&AllMightys magische Quelle`@ gestolpert, `nbenannt nach der wandelnden Sage, die sie entdeckt hat.");
      output("`n`nEs ist nur sehr wenig über sie bekannt. AllMighty hat ein paar ihrer Geheimnisse gelüftet, `nwelche in einem geheimen Buch aufgeschrieben hat. `nEr entdeckte unter anderem, dass die Steine, die man dort findet eine einzigartige Kraft entwickeln. Sie können `ndie Energie des Besitzers erhöhen und schenken ihm jeden Tag einen zusätzlichen Waldkampf.`n");
      output("Die Anzahl der Steine ist begrenzt, und jeder Stein kann nur von einem Krieger zur gleichen Zeit besessen werden. `nMit etwas Glück kannst du einen dieser Steine besitzen.`n`n");
      output("Du bemerkst einen Knopf, der in den Felsen nahe der Quelle eingelassen ist. Er ist mit magische Symbolen beschriftet. `n");
      output("Du verstehst deren Sinn nicht - sind sie eine Einladung? Oder...vielleicht...eine Warnung?");
      addnav("`\$Verlasse die Quelle","forest.php?op=lascia");
      addnav("`^Drücke den Knopf","forest.php?op=premi");
      break;
    case "premi":
      output("`@Deine Hand hält über dem Knopf kurz inne, als du die Stärke der magischen Kraft fühlen kannst, `ndie von AllMightys Quelle und seinen versteckten Schätzen ausgeht.`n");
      output("Du beginnst darüber nachzudenken, ob die Legenden wahr sind, oder ob du einen tödlichen Fehler begehst.`nAls du dir Aura der Energie fühlst, die von dem Stein ausgeht, schliesst du die Augen und drückst auf den Knopf. `n");
      output("Als der Knopf unter dem Druck nachgibt, hörst du mechanische Geräusche im Inneren des Felsen. `nAls du deine Augen wieder öffnest, siehst du was die Quelle dir offenbart. `nEin goldenes Glitzern im Wasser lässt dich glauben, dass der Erdgott dir einen Gefallen tun wird .... `n`n");
      $session['user']['specialinc']="";
      addnav("`@Zurück ins Dorf","village.php");
      addnav("`\$Zurück in den Wald","forest.php");

      $sql = "SELECT id,name,class,hvalue,owner FROM items WHERE class = 'Allmightys Stein' ORDER BY RAND(".e_rand().") LIMIT 1";
      $result = db_query($sql) or die(db_error(LINK));
      $row = db_fetch_assoc($result);
      if($row['owner'] == 0)
      {
        // The stone is available
        output("`#... du hörst im Inneren des Felsen etwas rollen und einer der sagenhaften Steine erscheint in der Quelle!!`n`nEr hat einige eingravierte Zeichen, ");
        if ('`\$Poker Stein' == $row['name'])
        {
          output("und du bemerkst mit Schrecken, dass dies der ".$row['name']." `#ist!!!`n Der Besitz dieses verfluchten Steins kostet dich jeden Tag einen Waldkampf. `nDeine einzige Hoffnung ist, dass ein anderer unglücklicher Krieger über `&Allmightys Quelle`# stolpert und den Stein von dir übernimmt. ");
          $session['user']['turns']-=1;
          $sql="UPDATE items SET owner='".$session['user']['acctid']."' WHERE id='".$row['id']."'";
          db_query($sql);
          addnews("`6{$session[user][name]} `#hat den `5{$row[name]}`# gefunden!!!");
        }
        else
        {
          output("und du bemerkst mit Freude, dass es der ".$row['name']."`# ist!! `nDer Besitz dieses Steines gibt dir jeden Tag eine extra Waldkampf. `nHeute ist ein glücklicher Tag, ".$session['user']['name']."`#!!!`n");
          $session['user']['turns']+=1;
          $sql="UPDATE items SET owner='".$session['user']['acctid']."' WHERE id='".$row['id']."'";
          db_query($sql);
          addnews("`6{$session[user][name]} `#hat den `5{$row[name]}`# gefunden!!!");
        }
      }
      else
      {
        output("`# du hörst ein pfeifendes Geräusch, das schnell an Intensität gewinnt, bis es in ein fürchterliches Jammern mündet, und plötzlich hört es auf, wie es begann. `nEine tiefe, angenehme Stimme spricht: `n`n\"");
        $caso = e_rand(0,1);
        $sqlz = "SELECT name,acctid FROM accounts WHERE acctid = '".$row['owner']."'";
        $resultz = db_query($sqlz) or die(db_error(LINK));
        $rowz = db_fetch_assoc($resultz);
        if ($row['name']=='`\$Poker Stein') $switch = 1;
        if ($caso==0)
        {
          output("`%".($switch?"Du Glückspilz":"Du Pechvogel").", {$session[user][name]}`%. Der {$row[name]}`% ist das Eigentum von `@{$rowz[name]}`%. `nEs liegt nicht in meinem Wesen, ihn ihm wegzunehmen. `nAls Ausgleich wirst du mit `^`b5`b`% zusätzlichen Waldkämpfen belohnt, die ich dir sofort zuteile.`#\" `n`nEin Strom von Energie durchfliesst deinen Körper, und du weisst, dass die Stimme ihr Versprechen gehalten hat!!! `n");
          $session['user']['turns']+=5;
        }
        else
        {
          output("`^Der Stein, der für dich ausgewählt wurde, gehört `@{$rowz[name]}`^. Da er bei mir an Ansehen verloren hat, `nhabe ich beschlossen, dass er ihn nicht mehr verdient hat und überlasse den Stein deiner Obhut.`#\". `n`nDu siehst einen wundervollen Stein im Quellwasser erscheinen, `nund nimmst ihn an dich.");
          if ($pietra != '`\$Poker Stein')
          {
            output("Du bewunderst den {$row[name]}`#, in dem Wissen, dass du ab jetzt jeden Tag einen zusätzlichen Waldkampf erhältst. `n");
            $session['user']['turns']+=1;
          }
          else
          {
            output("uDu bemerkst mit Schrecken, dass dies der {$row[name]} `# ist!!!`n Der Besitz dieses verfluchten Steins kostet dich jeden Tag einen Waldkampf. `nDeine einzige Hoffnung ist, dass ein anderer unglücklicher Krieger über `&Allmightys Quelle`# stolpert und den Stein von dir übernimmt.");
            $session['user']['turns']-=1;
          }
          $sqlp="UPDATE items SET owner='".$session['user']['acctid']."' WHERE id='".$row['id']."'";
          db_query($sqlp);
          $mailmessage = "`@{$session['user']['name']} `@hat `&AllMightys Quelle`@ entdeckt und der Erdgott hat beschlossen, ihm deinen {$row[name]} zu geben`@!! Es ist ein ".($switch?"":"un")."glücklicher Tag für dich.";
          systemmail($rowz['acctid'],"`2Deinen Stein besitzt jetzt {$session['user']['name']} `2",$mailmessage);
          addnews("`@{$session['user']['name']} `@hat `&AllMightys Quelle`@ entdeckt und dein Stein {$row['name']}`@ erhalten.");
        }
      }
      break;
    case "lascia":
      $session['user']['specialinc']="";
      $perdita=intval($session[user][maxhitpoints]*0.3);
      $session[user][hitpoints]-=$perdita;
      if ($session[user][hitpoints] < 1)
      {
        $perdita += $session[user][hitpoints];
        $session[user][hitpoints] = 1;
      }
      output("`6Erschrocken durch die Macht der Quelle, beschließt du, dein Schicksal nicht herauszufordern. `nDu wendest dich ab in Richtung Wald und fühlst dich in einer trügerischen Sicherheit. Während du dich abwendest, hörst du ein blubberndes Geräusch `naus der Quelle. `n`n`^Ein Strahl Wasser trifft deinen Hinterkopf wie ein Hammerschlag `nund wirft dich zu Boden!`n`n `\$`bDu verlierst $perdita Lebenspunkte durch den Sturz!!!!`b");
      addnav("`\$Zurück in den Wald","forest.php");
      break;
  } //chiusura switch
}
else
{ //chiusura if iniziale
  $row = db_fetch_assoc($result);
  $session['user']['specialinc']="";
  output("`@Während du auf der Suche nach neuen Abenteuern durch den Wald streifst, findest du eine magische Quelle, von der`n ein mystisches Glühen ausgeht. Du bist durch Zufall über `&AllMightys magische Quelle`@ gestolpert, `nbenannt nach der wandelnden Sage, die sie entdeckt hat.");
  output("`nAber diese Quelle ist dir nicht unbekannt und du weisst sehr wohl über ihre magischen Kräfte bescheid, `nda du ja schon den $row[name] besitzt. `n`nSei nicht gierig und lasse andere Krieger auch von den magischen Kräften profitieren. `nWährend deines Aufenthalts an der Quelle trinkst du von dem klaren Wasser und fühlst die erfrischende Wirkung.`n`n`%Du verlierst einen Waldkampf, aber wirst komplett geheilt.");
  $session[user][turns]-=1;
  if ($session[user][hitpoints]<$session[user][maxhitpoints]) $session[user][hitpoints]=$session[user][maxhitpoints];
  addnav("`\$Zurück in den Wald","forest.php");
}
page_footer();
?>


