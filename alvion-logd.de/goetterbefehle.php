
<?php
require_once "common.php";
page_header("Götterbefehle");
addnav("Zurück zur Grotte","superuser.php");
output("`n`n`n`c");
output("User [userlogin] bekommt volle LPs `n");
output("/rpcmd userb;[userlogin];fullife`n`n");
output("User [userlogin] hat nur noch einen LP `n");
output("/rpcmd userb;[userlogin];onehp `n`n");
output("User [userlogin] bekommt [pieces] Gold, kann auch - [pieces] Gold sein `n");
output("/rpcmd userb;[userlogin];gold;[pieces] `n`n");
output("User [userlogin] bekommt [pieces] Edelsteine, kann auch - [pieces] Edelsteine sein `n");
output("/rpcmd userb;[userlogin];gems;[pieces] `n`n");
output("Newseintrag [news] `n");
output("/rpcmd addnews;[news] `n`n");
output("Setzt das Wetter auf [weather] `n");
output("/rpcmd weather;[weather] `n`n");
output("Setzt das Wetter auf [weather] für User Loginname [Loginname_of_a_user] `n");
output("/rpcmd weather;[weather];[Loginname_of_a_user] `n`n");
output("Wiederbeleben von User Loginname [Loginname_of_a_user] `n");
output("/rpcmd rebirth;[Loginname_of_a_user] `n`n");
output("Spieler von User Loginname [Loginname_of_a_User] töten, nur an dem Ort wo er ist `n");
output("/rpcmd die;[Loginname_of_a_user] `n`n");
page_footer();
?>

