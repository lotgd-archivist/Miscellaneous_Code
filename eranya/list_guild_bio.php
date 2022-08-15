
<?php
// Gildenbio als Popup
require_once('common.php');
// Gildenbio
include_once('dg_output.php');
include_once(LIB_PATH.'dg_funcs.lib.php');
popup_header('Gildenbiographie');
if(empty($_GET['gid'])) {
    output('`$Fehler! Diese Gilde konnte nicht gefunden werden!');
} else {
    dg_show_guild_bio($_GET['gid'],false,true);
}
popup_footer();
?>

