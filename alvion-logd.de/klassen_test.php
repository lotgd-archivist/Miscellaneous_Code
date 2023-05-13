
<?php

require_once "common.php";
require_once "func/kampfkunst.php";
page_header("linus sein Klassentest");

$klasse=array(
    1=>"Söldner",
    2=>"Myrmidone",
    3=>"Kavalier",
    4=>"Lord",
    5=>"Lord",
    6=>"Lord",
    7=>"Ritter",
    8=>"Bandit",
    9=>"Pirat",
    10=>"Kämpfer",
    11=>"Bogenschütze",
    12=>"Nomade",
    13=>"Dieb",
    14=>"Magier",
    15=>"Druide",
    16=>"Mönch",
    17=>"Geistlicher",
    18=>"Schamane",
    19=>"Pegasus-Ritter",
    20=>"Wyvernritter",
    101=>"Held",
    102=>"Schwertmeister",
    103=>"Rittmeister",
    104=>"Edelmann",
    105=>"Meister",
    106=>"Herrscher",
    107=>"General",
    108=>"Berserker",
    109=>"Berserker",
    110=>"Krieger",
    111=>"Scharfschütze",
    112=>"Nomade",
    113=>"Assassine",
    114=>"Magier",
    115=>"Druide",
    116=>"Bischof",
    117=>"Bischof",
    118=>"Schamane",
    119=>"Pegasus-Lord",
    120=>"Wyvern-Lord",
    0=>"`)Unbekannt");

/*    
if (strlen($row1['klasse'])>1){
    output("`^Klasse: `@".$row1[klasse]."`n");
}else if(strlen($row['klasse'])>1){
    output("`^Klasse: `@".$klasse[$row[klasse]]."`n");
}
*/


$i=0;
$j=0;
$k=0;

if ($_GET[op]=="go"){
    $result = db_query("SELECT acctid, login, klasse FROM accounts WHERE klasse > ''");
    while($row = db_fetch_assoc($result)){
        $i++;
        if(strlen($row['klasse'])>0){
            $sqlb = 'SELECT `acctid` FROM `bio` WHERE `acctid` = '.$row['acctid'].';';
            $resultb = db_query( $sqlb );
            $rowb = db_fetch_assoc($resultb);
            if ( empty( $rowb['acctid'] ) ) {
                $sql = "INSERT INTO bio (acctid, login) VALUES (".$row['acctid'].",'".$row['login']."')";
                db_query($sql);
                $k++;
            }
            db_query("UPDATE bio SET klasse='".$klasse[$row[klasse]]."' WHERE acctid=".$row[acctid]."");
            $j++;
        }
    
    }
    $out="$i Accounts, $k Bios angelegt, $j Klassen übertragen. ;)`n`n`n";
}else{
    $out="Klassen übertragen?`n`n`n";
    addnav('Mach hinne','klassen_test.php?op=go');
}

output($out,true);

addnav('Zurück','superuser.php');

page_footer();

