
<?php/* *******************Die Arena Ställeby Flyemail: easykamikaze@lycos.de******************* */require_once "common.php";page_header("Arena Ställe");if ($_GET[op]=="in"){     output("`6Du streichelst Dein(e/n) {$playermount['mountname']}`6 und überreichst es dann Eilinel.");     $session['savemount']=$session['bufflist']['mount']['rounds'];     $session['bufflist']['mount']['rounds']=0;     }else if ($_GET[op]=="out"){     output("`6Eilinel überreicht Dir Dein(e/n) {$playermount['mountname']}`6 und Du bist froh es wieder bei Dir zu haben.");     $session['bufflist']['mount']=unserialize($playermount['mountbuff']);     $session['bufflist']['mount']['rounds']=$session['savemount'];     $session['savemount']=0;     }else    {    output("`|Neb`+en d`òem K`µol`²oss`2eum befinde`²n si`µch e`òini`+ge k`|lei`+ne S`òtälle. Eili`µnel `²füt`2ter`²t ge`µrad`òe ei`+nig`|e Ti`+ere`ò. Als`µ sie`² dic`2h si`²eht,`µ kom`òmt s`+ie a`|uf d`+ich`ò zu u`µnd f`²rag`2t di`²ch, w`µas s`òie f`+ür d`|ic`+h tu`òn ka`µnn. ");    if ($session['user']['hashorse']>0 && $session['bufflist']['mount']['rounds']>0){addnav("{$playermount['mountname']}`0 unterstellen","arenastables.php?op=in");}    else if ($session['user']['hashorse']>0 && $session['savemount']>0) {addnav("{$playermount['mountname']}`0 abholen","arenastables.php?op=out"); }    else if ($session['user']['hashorse']>0){output("`n`^Du solltest Dein(e/n) {$playermount['mountname']}`^ stärken, bevor Du es abgeben kannst.");}    else {output("`n`^ Du besitzt kein Tier und kannst es somit auch nicht abgeben!");}    }addnav("Zurück","pvparena.php");page_footer();?>

