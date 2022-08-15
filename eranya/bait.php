
<?php

/*********************************************
Lots of Code from: lonnyl69 - Thanks Lonny !
Also Thanks to Excalibur @ dragonprime for your help.
By: Kevin Hatfield - Arune v1.0
06-19-04 - Public Release
Written for Fishing Add-On - Poseidon Pool
Translation and simple modifications by deZent deZent@onetimepad.de
********************************************/

// Bugfix&Modification by Maris (Maraxxus@gmx.de)

// Texte von Syïela

require_once "common.php";

define('MAX_ITEMS',100);

checkday();

page_header("Kerras Anglerbedarf");

output("`=`c`bKerras Anglerbedarf`b`c`n");

$sql = "SELECT worms,minnows,boatcoupons FROM account_extra_info WHERE acctid=".$session['user']['acctid']."";
$result = db_query($sql) or die(db_error(LINK));
$rowf = db_fetch_assoc($result);

if($session['user']['dragonkills'] == 0) {
    
    output('`òKerra mustert Dich und meint dann: `I"Du solltest erst mehr Erfahrung sammeln, ehe Du dich an die Herausforderung des Angelns machst!"`n');
    addnav('Angeln!','fish.php');
    addnav('Zurück zum Weiher','pool.php');
    
}
else {

    $inventory=$rowf['worms'];
    $inventory+=$rowf['minnows'];
    $inventory+=$rowf['boatcoupons'];
    
    $space= max(MAX_ITEMS - $inventory,0);
    
    $cost = array();
    $max = array();
    
    $cost['worms'] = 5;
    $cost['minnows'] = 6;
    $cost['boatcoupons'] = 10;
    $max['worms'] = min( floor($session['user']['gold'] / $cost['worms']) , $space );
    $max['minnows'] = min( floor($session['user']['gold'] / $cost['minnows']) , $space );
    $max['boatcoupons'] = min( floor($session['user']['gold'] / $cost['boatcoupons']) , $space );
    
    $op = ($_GET['op']) ? $_GET['op'] : '';
    
    switch($op) {
        
        case '':
            
            output('`òIm Inneren des kleines Hauses setzt sich der Eindruck von außen fort. Die Möbel scheinen selbstgezimmert zu sein und das von nicht allzu
                    kundigen Händen. Und doch sieht dich die Besitzerin mit mehr als stolzem Blick an, als du eintrittst.`n
                    `I"Mein Name ist Kerra, was kann ich für dich tun?"`ò, fragt sie dich mit einem Lächeln und deutet auf ihre Waren. Köder, in erster Linie.
                    `I"Was immer dein Anglerherz sich wünscht, findest du hier!"`ò, verspricht sie dir und schiebt sich dabei eine dunkle Locke aus dem Gesicht,
                    die eigentlich gar nicht gestört haben kann.`n`n');
        
            output('`òIn deinem Beutel siehst Du:`n`n
                    `h'.$rowf['minnows'].' Fliegen`ò,`n`G'.$rowf['worms'].' Würmer `òund`n`ó'.$rowf['boatcoupons'].' Coupons für ein Ruderboot`ò.`n`n');
            if ($inventory > MAX_ITEMS) {
                output('`4Du bemerkst, dass dein Beutel schon voll ist.`n`n');
            }
            else {
                output('`òDu hast noch für '.$space.' Dinge Platz im Beutel.`n`n');
                        
                output('`òWas möchtest Du kaufen?`n');
                            
                output('`nFliegen zum Preis von `^'.$cost['minnows'].' Gold `òdas Stück.');
                output('<form method="POST" action="bait.php?op=trade&what=minnows">',true);
                output('<input type="text" name="count" value="'.$max['minnows'].'"><input type="submit" value="Fliegen kaufen"></form>',true);
                addnav('','bait.php?op=trade&what=minnows');
                
                if($session['user']['dragonkills'] >= 2) {
                
                    output('`nWürmer zum Preis von `^'.$cost['worms'].' Gold `òdas Stück.');
                    output('<form method="POST" action="bait.php?op=trade&what=worms">',true);
                    output('<input type="text" name="count" value="'.$max['worms'].'"><input type="submit" value="Würmer kaufen"></form>',true);
                    addnav('','bait.php?op=trade&what=worms');
                    
                }
                else
                {
                  output("`òWenn du erfahrender wärst, könntest du mit Würmern angeln...");
                }
                if($session['user']['dragonkills'] >= 10)
                {
                    output('`nBootscoupons zum Preis von `^'.$cost['boatcoupons'].' Gold `òdas Stück.');
                    output('<form method="POST" action="bait.php?op=trade&what=boatcoupons">',true);
                    output('<input type="text" name="count" value="'.$max['boatcoupons'].'"><input type="submit" value="Bootscoupons kaufen"></form>',true);
                    addnav('','bait.php?op=trade&what=boatcoupons');
                    
                }
                else
                {
                  output("`òWenn du noch erfahrender wärst, könntest du mit einem Boot auf den Weiher hinaus rudern...");
                }
            }
            
            addnav('Zurück zum Weiher','pool.php');
            
            break;
                    
        case 'trade':
        
        $sql = "SELECT worms,minnows,boatcoupons FROM account_extra_info WHERE acctid=".$session['user']['acctid']."";
$result = db_query($sql) or die(db_error(LINK));
$rowf = db_fetch_assoc($result);
            
            $what = $_GET['what'];
            $count = min($max[$what],$_POST['count']);
            $cost = $cost[$what] * $count;
            $totalcount=$rowf[$what];
            $totalcount+=$count;
            
            if ($what=='minnows')  { $bname='Fliegen'; }
            elseif ($what=='worms')  { $bname='Würmer'; }
            elseif ($what=='boatcoupons')  { $bname='Bootscoupons'; }
            
  $sql = "UPDATE account_extra_info SET $what=$totalcount WHERE acctid=".$session['user']['acctid']."";
  db_query($sql);

            $session['user']['gold'] -= $cost;            
                            
            output('`òDu kaufst `&'.$count.' '.$bname.'`ò für `^'.$cost.'`ò Gold!`n
                    Kerra schiebt dir einen kleinen Beutel herüber, nimmt das Gold entgegegen und schaut dich abwartend an.');
            
            addnav('Noch mehr kaufen','bait.php');
            addnav('Auf zum Angeln!','fish.php');
            addnav('Zurück zum Weiher','pool.php');
            
            break;
        
        
    }
}

page_footer();
?> 
