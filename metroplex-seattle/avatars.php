
<?php

// 27062004

require_once "common.php";

isnewday(1);

if ($_GET['op']=="block"){
    $sql = "UPDATE accounts SET avatar='' WHERE acctid=$_GET[userid]";
    systemmail($_GET['userid'],"Dein Avatar wurde entfernt","Der Administrator hat beschlossen, dass dein Avatar unangebracht ist, oder nicht funktionierte, und hat ihn entfernt.`n`nWenn du darüber diskutieren willst, benutze bitte den Link zur Hilfeanfrage.");
    db_query($sql);
}
$ppp=25; // Player Per Page to display
if (!$_GET[limit]){
    $page=0;
}else{
    $page=(int)$_GET[limit];
    addnav("Vorherige Seite","avatars.php?limit=".($page-1)."");
}
$limit="".($page*$ppp).",".($ppp+1);
$sql = "SELECT name,acctid,avatar FROM accounts WHERE avatar>'' ORDER BY acctid ASC LIMIT $limit";
$result = db_query($sql);
if (db_num_rows($result)>$ppp) addnav("Nächste Seite","avatars.php?limit=".($page+1)."");
page_header("Spieleravatare");
$session[user][ort]='Administration';
output("`b`&Spieler Avatare - Seite $page:`0`b`n");
for ($i=0;$i<db_num_rows($result);$i++){
    $row = db_fetch_assoc($result);
    output("`![<a href='avatars.php?op=block&userid={$row['acctid']}'>Entfernen</a>]",true);
    addnav("","avatars.php?op=block&userid={$row['acctid']}");
    output("`&{$row['name']}: `^`n");
if(trim($row['avatar']))
  {
   $img = getImageSize($row['avatar']);
   $width = $img[0];
   $height = $img[1];
   if($width > $height && $width > 225)
       {
            $new_width = 225;
            $new_height = 0;
       }
   elseif($width < $height && $height > 225)
       {
            $new_height = 225;
            $new_width = 0;
       }
    elseif($width == $height && $width > 225)
        {
            $new_width = 225;
            $new_height = 0;
        }
    else
    {
            $new_width = 0;
            $new_height = 0;
    }
   output('`n`c
      <img src="'.$row['avatar'].'" '.($new_width > 0 ? 'style="width: '.$new_width.'"' : '').($new_height > 0 ? 'style="height: '.$new_height.'"' : '').'  alt="$row[name]">`c`n', true);
  }

}
db_free_result($result);
addnav("G?Zurück zur Administration","superuser.php");
addnav("Zurück zum Stadtplatz","village.php");
addnav("Aktualisieren","avatars.php");
page_footer();
?>


