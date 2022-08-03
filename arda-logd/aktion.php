<?php
// Aktionsscript fuer Inquisitoren, Richter,Scharfrichter


require_once "common.php";

page_header("Vollstrecken");


switch ($_GET['op'])
{
  //Täuschungsritual

       //Rundenspende
 case 'rounds':

    $sql="SELECT acctid, name,race,tarn,gold,login, level FROM accounts WHERE acctid = '$_GET[id]'";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    output("`n`n`^Du hast".$row['name']."`y 5 Runden geschenkt.",true);
    //systemmail($_GET['id'],"`^Wandlung!`0",$session['user']['name']." hat dir ein neues Leben als Elf geschenkt. Hoffentlich weist du dies gut zu nutzen. !",$session['user']['acctid']);
    db_query("UPDATE accounts SET  turns=turns+5 WHERE acctid = '$_GET[id]'");
          $session[user][turns]-=5;

    addnav("Sonstiges");
    addnav("Zurück","bio.php?id=$_GET[id]&char=$_GET[char]");
    break;














  //Ab an den Sklavenpranger
    case 'sklavenpranger':
    $sql="SELECT * FROM accounts WHERE acctid = '$_GET[id]'";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);

    output("`n`n`7Du hast ".$row['name']."`7 an den Zwischenpranger gestellt.",true);
    systemmail($_GET['id'],"`^Sklavenpranger!`0"," Du wurdest an den Sklavenpranger gebracht. Die Endstation für Verbotene. Dich hier freizukaufen würde Dich und Deinen Eigentümer alles kosten was bisjetzt erkauft oder erspielt wurde. Dir bleibt noch die wahl nach einer Hexe schicken zu lassen, die dich vom Los der Verbotenen befreit !");
              systemmail($row['master'],"`^Sklavenpranger!`0"," Dein Sklave wurde an den Slavenpranger gebracht. Ihn von dort zu befreien würde Dich und deinen Sklaven alles kosten was ihr bisher erspielt habt. Allerdings gibt es noch die Hexen, die deinen Sklaven vom Los der Verbotenen befreien können."  );

    db_query("UPDATE accounts SET pranger=2 WHERE acctid = '$_GET[id]'");
    //db_query("UPDATE account_extra_info SET sentence=sentence+3 WHERE acctid = '$_GET[id]'");

    //Newseintrag
    addnews("{$row[name]}`0 wurde an den Sklavenpranger gebracht. Arme Sau!!!");


    addnav("Sonstiges");
    addnav("Zurück","bio.php?id=$_GET[id]&char=$_GET[char]");
    break;






    //Verhör im Dunklen Turm


    //enteignung
case 'enteignenja':

    $sql="SELECT acctid, name,enteig,login,house, master, level FROM accounts WHERE acctid = '$_GET[id]'";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
      $sql="SELECT houseid FROM houses WHERE owner='$_GET[id]'";
            $result=db_query($sql) or die(db_error(LINK));
            $how=db_fetch_assoc($result);

$sql="SELECT * FROM keylist WHERE owner='$_GET[id]'";
            $result=db_query($sql) or die(db_error(LINK));
            $key=db_fetch_assoc($result);

    output("`n`n`7Du hast ".$row['name']."`7 enteignet.",true);
    systemmail($row['master'],"`^Enteignung!`0"," `& Dein Sklave wurde enteignet. Es scheint als hast du dein Eigentum nicht unter Kontrolle. Du solltest dich schnellstmöglichst bei der Inquisition ( Anfrage) melden, ehe dein Sklave an den Sklavenpranger kommt.");

    systemmail($_GET['id'],"`^Enteignung!`0"," `& Die Inquisition hat Dich enteignet! Du solltest dich schnellstmöglich mit der Inquisition ( schreibe Anfrage) in Verbindung setzen um den Sklavenpranger zu verhindern.");
     db_query("UPDATE keylist SET owner=0 WHERE owner = '$_GET[id]'");
    db_query("UPDATE accounts SET enteig=1, house=0, housekey=0,turns=5,title='Sklave',ctitle='Sklave', fuerst=fuerst+1 WHERE acctid = '$_GET[id]'");
     db_query("UPDATE account_extra_info SET ctitle='Sklave' WHERE acctid = '$_GET[id]'");
        db_query("UPDATE houses SET owner=0, status=2 WHERE owner = '$_GET[id]'");
    //Newseintrag
    addnews("{$row[name]}`&wurde heute von der Inquisition enteignet!!!");


    addnav("Sonstiges");
    addnav("Zurück","bio.php?id=$_GET[id]&char=$_GET[char]");
    break;


    //Erlaubnissschreiben
case 'erlaubnissja':

    $sql="SELECT acctid, name,enteig,slave, master,slave,login,erlaubniss, level FROM accounts WHERE acctid = '$_GET[id]'";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);

    output("`n`n`7Du hast ".$row['name']."`7 ein erlaubnissschreiben für die nächsten 7 Tage ausgestellt. Das war sehr Edel von Dir..",true);
    $session['user']['gold']-=5000;
    $session['user']['gems']-=5;
    systemmail($_GET['id'],"`^Erlaubnissschreiben!`0",$session['user']['name']." `&Hat Dir für 7 Tage eine Ausgangserlaubniss ausgestellt! Mach damit lieber keine Dummheiten ",$session['user']['acctid']);

    db_query("UPDATE accounts SET erlaubniss=erlaubniss+7 WHERE acctid = '$_GET[id]'");



    addnav("Sonstiges");
    addnav("Zurück","bio.php?id=$_GET[id]&char=$_GET[char]");
    break;




    //Mastertool
    if ($session[user][slave]>1)
    {

    output("`n`n`nVollstreckung`n`n`y Du kannst hier deines Amtes als Sklavenhalter walten. Was möchtest du mit `@$_GET[char]`y tun? `n`n`n`n `n`n");
    $result = db_query("SELECT * FROM accounts WHERE acctid=$_GET[id]");
    $row = db_fetch_assoc($result);
    $a=$session[user][acctid];
         if($row[master]==$a){
        output("`c`n<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);

        output("<tr class='trhead'><td>`^Was willst du jetzt mit `@$_GET[char] `^tun?</td>",true);

        output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
        output("`n<a href='aktion.php?op=erlaubnissja&id=$_GET[id]&char=$_GET[char]'><li> `^Erlaubnissschein</a>",true);
        addnav("","aktion.php?op=erlaubnissja&id=$_GET[id]&char=$_GET[char]");
        output("`y`n Erteile `@$_GET[char] `ydie Erlaubniss frei zu wandeln. Das kostet jedoch 5000 Gold und 5 edelsteine");
        output("<hr>`n");
        output("`n<a href='aktion.php?op=rounds&id=$_GET[id]&char=$_GET[char]'><li> `^Rundenspende</a>",true);
        addnav("","aktion.php?op=rounds&id=$_GET[id]&char=$_GET[char]");
        output("`y`n Schenke `@$_GET[char] `y5 Runden.");
        output("<hr>`n");
         output("`n<a href='aktion.php?op=sklavenpranger&id=$_GET[id]&char=$_GET[char]'><li> `^Sklavenpranger</a>",true);
     addnav("","aktion.php?op=sklavenpranger&id=$_GET[id]&char=$_GET[char]");
        output("`y`n Bringe `@$_GET[char] `yan den Sklavenpranger. ACHTUNG Deinen Sklaven von dort zu befreien, kostet Dich und Deinen Sklaven alles was bisher erspielt wurde.");
        output("<hr>");
        output("<hr>`n");
        output("</table>`c",true);
               }

        addnav("Sonstiges");
        addnav("Zurück","bio.php?id=$_GET[id]&char=$_GET[char]");
}



}
page_footer();
?> 