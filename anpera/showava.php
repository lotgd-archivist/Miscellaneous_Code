
ï»¿<?PHP



// 20060302



// anpera



require_once("common.php");

isnewday(2);

if ($_GET['userid']) $row=db_fetch_assoc(db_query("SELECT login,avatar FROM accounts WHERE acctid={$_GET['userid']} LIMIT 1"));

popup_header("Avatar ".$row['login']);

if ($row['avatar']==""){

    output("<Kein Avatarbildchen>");

}else{

    $pic_size = @getimagesize($row['avatar']);

    $pic_width = $pic_size[0];

    $pic_height = $pic_size[1];

    output("<img src=\"{$row['avatar']}\" ",true);

    if ($pic_width > 200) output("width=\"200\" ",true );

    if ($pic_height > 200) output("height=\"200\" ",true );

    output("alt=\"{$row['name']}\">&nbsp;`n`n",true);

}

popup_footer();

?>

