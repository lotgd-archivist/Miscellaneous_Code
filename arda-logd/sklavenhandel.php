<?
//****************************************//
// Der Sklavenhandel            //
// Idee: Onepeople/Barra        //
// Umsetzung: Barra /Yasu            //
//www.nebelstadt.de        //
//****************************************//



require_once "common.php";
page_header("Der Sklavenmarkt");
switch ($_GET['op'])
{



case 'anbieten':
    $sql="SELECT * FROM accounts WHERE acctid ='{$session[user][slave]}'";
        $result = db_query($sql);
        $partner = db_fetch_assoc($result);
  addnav('weg hier','sklavenhandel.php');
output("`n `t `kDer `JSk`Mla`-ve`Mnm`Jarkt`n`n   `1 $partner[name]`1  wurde langsam langweilig. Zeit für etwas Neues. Du schleppst $partner[name] zurück zum Sklavenmarkt, wo du zumindest noch etwas vom Kaufpreis zurückbekommst Willst du es gleich in einen neuen Sklaven investieren? Der Händler erinnert dich noch daran, dass ein Verkauf endgültig ist und du $partner[name] auch nicht wieder zurückkaufen kannst, bist du dir sicher?<a href='sklavenhandel.php?op=ja'>`cJa ich will $partner[name] verkaufen`c</a>`n<a href='sklavenhandel.php'> `cNein lieber Doch nicht`c</a>");
                  addnav('','sklavenhandel.php?op=ja');
                  addnav('','sklavenhandel.php');
break;


  case 'ja':
          $sql="SELECT * FROM accounts WHERE acctid ='{$session[user][slave]}'";
        $result = db_query($sql);
        $partner = db_fetch_assoc($result);
                   $right=$partner[rppunkte];
        $false=10;
        $points=($right*$false );
                 $akt=$partner[kriegerlevel];
                 $val=($points * $akt);
                 $preis = floor($points);

                 output("`n`1 Dein Sklave $partner[name]`1 hat ein Aktivitätslevel von $partner[rppunkte].`1 Das heißt, dein Sklave ist $preis Edelsteine und $val  Goldstücke wert");

                 $session[user][gold]+=$val;
                $session[user][gems]+=$preis;
                $session[user][master]=0;
                 $session[user][slave]=0;
                        $sql = "UPDATE accounts SET master='Bastor Tychos' WHERE acctid = '{$partner[acctid]}'";
           db_query($sql) or die(sql_error($sql));
          $sql = "INSERT INTO bastor Values ('','$partner[acctid]','$partner[name]','$partner[login]','$partner[rppunkte]','$partner[kriegerlevel]')";
          db_query($sql) or die(sql_error($sql));
          systemmail($partner['acctid'],"`^Verkauft!`0","`@{$session['user']['name']}
           `& hat Dich für $preis Edelsteine und $val Gold verkauft. Du gehörst nun dem Sklavenhändler Bastor Tychos");
           addnav("weg hier","sklavenhandel.php");
break;


 case 'kauf':
          $sql="SELECT * FROM bastor  ";
        $result = db_query($sql);
        $sklaven = db_fetch_assoc($result);
                   $right=$sklaven[gempreis];
        $false=10;
        $points=($right+$false );
                 $akt=$sklaven[goldpreis];
                 $val=($points * $akt);
                 $preis = floor($points);
               output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
        output("<tr class='trhead'><td><b>Name</b></td><td><b>Gempreis</b></td><td><b>Goldpreis</b></td>",true);
          output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
            output("<a href='sklavenhandel.php?op=kauf2&char=".rawurlencode($sklaven[sid])."' >`^$sklaven[sname]</a>`0",true);
                 addnav("","sklavenhandel.php?op=kauf2&char=".rawurlencode($sklaven[sid])."");
               output("</td><td>",true);
                output("`^$preis Edelsteine`0");
               output("</td><td>",true);
                     output("`^$val Gold`0");
               output("</td><td>",true);

        output("</table>",true);
           addnav("Weg","sklavenhandel.php");
break;


 case 'kauf2':
          $sql="SELECT * FROM bastor INNER JOIN accounts WHERE acctid=sid";
        $result = db_query($sql);
        $sklaven = db_fetch_assoc($result);
                   $right=$sklaven[gempreis];
        $false=10;
        $points=($right+$false );
                 $akt=$sklaven[goldpreis];
                 $val=($points * $akt);
                 $preis = floor($points);

                 if($session[user][gold]<$val ||$session[user][gems]<$preis){
                 output(" `n`n`1 Das kannst du dir wohl nicht leisten");
                 }
                 else{
               $result = db_query("SELECT * FROM accounts INNER JOIN bastor WHERE sid=acctid");
    $row = db_fetch_assoc($result);

$idmaster=$session[user][acctid];
$idslave=$row[acctid];

output("`1Nach langem Überlegen hast du dich schließlich für $row[name] entschieden. Der Händler macht $row[name] los und reicht dir noch gratis ein halsbandund eine Leine hinzu, dir viel Vergnügen mit deinem neuen Besitz wünschend.");
  $session[user][gold]-=$val;
    $session[user][gems]-=$preis;
        $session[user][master]=1;
         $session[user][slave]=$idslave;

   systemmail($row[acctid],"`^Gekauft!`0","`@{$session[user][name]}
            `1 hat dich vom Slavenhändler Bastor Tychos befreit. Du bist damit dessen offizielles Eigentum ! Zum Zeichen deiner Sklavschaft wurde dir von der Inquisition ein Mal am Nacken gegeben, sowie ein Armband, welches verhindert, daß du deinen Gebieter angreifst. Es ist Dir fortan verboten, die offenen Plätze ohne die Anwesenheit deines Eigentümers zu betreten, das Serum hat keinerlei Wirkung mehr auf Dich, sodaß Inquisuitoren dich sofort bannen werden, sobald du gegen deine Auflagen verstöst.");
 $sql = "UPDATE accounts SET slave=1,master=$idmaster,erlaubniss=1 WHERE acctid = ".$row[acctid]."";
            db_query($sql) or die(sql_error($sql));
            $sql2 = "DELETE FROM `bastor`  WHERE `bastor`.`sid` = '$row[acctid]'";
            db_query($sql2) or die(sql_error($sql2));
    }
        //savesetting("amtskasse" ,getsetting("amtskasse",0)+ $val);
           addnav("Hehe","sklavenhandel.php");
break;

case 'beginn':

     $sql="SELECT * FROM accounts  WHERE acctid='{$session[user][acctid]}'";
        $result = db_query($sql);
        $one = db_fetch_assoc($result);

                 output(" `1Du hast dich entschlossen, ein Leben in Sklavschaft in Arda zu leben. Diese Entscheidung ist, falls du sie hier bestätigst, eine Entscheidung, die nicht mehr rückgängig gemacht werden kann.`n`n Bis ein Eigentümer für dich gefunden wurde, oder  du es dir leisten kannst, eine Ausgangserlaubnis zu erwerben, kannst du das Sklavenviertel nicht verlassen. Sobald du erworben wurdest,ist Dein Herr/Herrin für Dich verantwortlich. Jener verteilt Ausgangserlaubnis oder Rundenspenden, etc.`n`n Bist du dir wirklich sicher daß du ein Leben in Sklavschaft wählst?`n`n`n");
                 addnav(" Ich bin sicher","sklavenhandel.php?op=sklaveja");
                 addnav(" Lieber doch nicht","sklavenhandel.php?op=sklavenein");

                 break;


case 'sklaveja':

   $sql="SELECT * FROM accounts  WHERE acctid='{$session[user][acctid]}'";
        $result = db_query($sql);
        $one = db_fetch_assoc($result);
output("`n`n`1 Du hast Dich für ein Leben in Sklavschaft entschieden. Du fühlst dich älter als noch zu Beginn. Du gehörst, bis dich jemand erwirbt, dem Sklavenhändler Bastor Tychos. Er ist ein grausamer Herr und führt sein Regiment über die Sklaven mit harter Hand. ");
 $sql1 = "INSERT INTO bastor Values ('','$one[acctid]','$one[name]','$one[login]','$one[rppunkte]','$one[kriegerlevel]')";
          db_query($sql1) or die(sql_error($sql1));
                 $session[user][age]++;
          addnav(" Na großartig","sklavenhandel.php");

          break;


          case 'sklavenein':
   $session[user][slave]=0;
   $session[user][master]=0;

   output(" `n`n`1 Du hast dich entschlossen, dein Leben nicht in Sklavschaft zu beginnen und machst dich schleunigst aus dem Staub. ");
  /* if($session[user][verbot]==1){
   output("`n`n`1Da du aber ein verbotenes Wesen laut Inquisition bist, musst Du immer damit rechnen, daß man dein wahres ICH erkennt und Dich verrät.`nDu bekommst eine Tarnung von 5 Tagen, danach musst du Dich selbst tarnen. Im Wohnviertel auf dem Schwarzmarkt findest du Dina, welche die Seren verkauft, welche Dein Leben bisweilen retten können.");
   $session[user][tarn]=5;
   }*/
   addnav(" Nichts wie weg","marktplatz.php");
   break;

   case 'sklavenviertel':
   page_header(" Der Sklavenmarkt");
   //$session[user][ort]='6';
   //$session[user][standort]='`X Das Sklavenviertel';
$sql = "SELECT name, sex FROM accounts";

$result = db_query($sql);

$row = db_fetch_assoc($result);

   output(" `c`kDer `JSk`Mla`-ve`Mnm`Jarkt.`n`n
`JLo`kck`1er gehst du an den Wachen vo`krb`Jei.`n
`JLa`kng`1e musstest du arbeiten, bis du deinen Namen hattest, doch jetzt kennt und achtet dich j`ked`Jer.`n");
output("`MGuten morgen , ".$session['user']['name']." !`n");
output("`Jbe`kgr`1üßt dich eine der Wachen. Doch achtest du nicht auf ihn und gehst stumm an ihm vo`krb`Jei.`n
`JMi`kt d`1iesem minderwertigen Volk gibst du dich schon lange nicht me`khr `Jab.`n
`JNu`kr a`1us einem Grund betrittst du diese`kn O`Jrt.`n
`JEn`kts`1pannt schlenderst du durch die Reihen der Sklaven und beurteilst ihre Nutzbar`kke`Jit.`n
\"`MEin schwarzes Schaf suchst du hier vergeblich\"`n
`Jsp`kri`1cht dich ein kräftiger Zwerg an. Narben zieren sein Ges`kic`Jht.`n
`JVo`kll`1er Abscheu spuckt er dem Sklaven neben dir ins Ges`kic`Jht.`n
\"`MWenn du ein Exemplar gefunden hast, wende dich an mich!\"`n
`JUn`kd s`1chon verschwindet er wieder, während die Ware angstvoll vor ihm zurückwe`kic`Jht.`n`n`c");
   addnav("Bastor Tychoss Langhaus","sklavenhandel.php?op=Bastor Tychos");
   addnav(" Gemeinschaftsbaraken","sklavenhandel.php?op=schlafen");
   addnav(" Sklavenviertel","sklavenhandel.php");
      if($session[user][slave]!=1 || $session[user][erlaubniss]!=0){
      addnav("Ins Dorf","marktplatz.php");
      }
    addcommentary();
viewcommentary('sklavenviertel','Hinzufügen',25);
    page_footer();
    break;


    case 'Bastor Tychos':
    page_header(" Bastor Tychoss langhaus");

    output("`n`n`n`SLanghaus von Bastor Tychos     `n`n`1Der Händler führt dich zu seiner Ware. Ein wenig kommst du dir vor wie auf einem Viehmarkt. Das Innere des schon recht baufälligen Gebäudes ist mit Stroh ausgestreut und die Sklaven sitzen in Reihen, streng nach Geschlecht getrennt und an Hand- und Fußgelenken gefesselt. Sie wirken schmutzig scheinen jedoch in halbwegs annehmbarer Gesundheit. \"`MDas sind die feinsten Arbeitssklaven\"`1, lobt der Händler sich selbst und seine Ware. \"`MAusdauernd, beständig. Brauchen wenig Futter und Pflege.\" `1Er führt dich weiter in einen Nebenraum. Dieser ist weitaus sauberer und nur wenige Sklaven finden sich hier, diemal aber vollkommen nackt und an Pfähle gebunden, eindeutig für die fleischlichen Genüße bestimmt. Wenn dir danach ist, darfst du sie nach Erlaubnis des Händlers sogar anfassen.");
 if($session[user][master]==1){

  addnav("Sklaven verkaufen","sklavenhandel.php?op=anbieten");
  }
  if($session[user][master]==0 && $session[user][slave]==0){
  addnav("Sklaven kaufen", "sklavenhandel.php?op=kauf");
  addnav("Sklave werden","sklavenhandel.php?op=beginn");
  }
  if($session[user][master]=="Bastor Tychos" &&  $session[user][gold]>499){
  addnav(" Ausgangserlaubniss einholen","sklavenhandel.php?op=erlaubniss");
  }

  addnav(" Nichts..","sklavenhandel.php?op=sklavenviertel");
     page_footer();
     break;

   case 'erlaubniss':
          output("`n`y Du kaufst dir für 500 Gold eine Ausgangserlaubniss für einen Tag.`n`n");
          $session[user][erlaubniss]=1;
          $session[user][gold]-=500;
          break;
case'schlafen':
page_header("Baracke");
output(" `n`n`y Die Gemeinschaftsbaracke der Sklaven. Hier essen, schlafen und bisweilen sterben die Waren des Händlers.`n`n");
 addcommentary();
viewcommentary('gemein','Hinzufügen',25);
addnav("L?Einschlafen (Log Out)","login.php?op=logout");
addnav(" Zurück","sklavenhandel.php?=op=sklavenviertel");
   page_footer();
  break;






default:
page_header("Der Sklavenmarkt");
output("`c`kDer `JSk`Mla`-ve`Mnm`Jarkt`n`n`n
`JDü`kst`1er und dreckig ist es `khi`Jer`n
`Jwi`ke ü`1berall auf der I`kns`Jel.`n
`JRa`ktt`1en und Mäuse scheinen hier ein- und auszug`keh`Jen.`n
`JDi`kes`1er Ort unterscheidet sich nur durch zwei Wa`kch`Jen,`n
`Jdi`ke s`1cheinbar gelangweilt an einer Wand le`khn`Jen,`n
`Jvo`kn d`1en anderen Plä`ktz`Jen.`n
`JWe`kr n`1icht weiß, was er s`kuc`Jht,`n
`Jwi`krd `1es hier nicht verm`kut`Jen.`c`n`n`n`n`n`n`n");

  addcommentary();
viewcommentary('handel_main','Hinzufügen',25);

  addnav("Zum Sklavenviertel","sklavenhandel.php?op=sklavenviertel");
addnav("Zum Dorf","marktplatz.php");

output("<a href='http://www.nebelstadt.de'>©by Barra von Nebelstadt</a>",true);

  //$session[user][standort]='`X Sklavenmarkt';
  //$session[user][ort]='6';
 }


page_footer();
?>