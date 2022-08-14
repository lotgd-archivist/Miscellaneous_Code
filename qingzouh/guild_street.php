
<?php


/* 

Projekt: Saint Omar Gilden
Version: 0.1
Author: Aramus

Dateibeschreibung: Gildenstraße -> Ausgabe aller Gilden, erstellen, bewerben, etc.

*/


require_once 'common.php';
require_once 'guild_settings.php';

page_header("Die Gildenstraße");

loadguildnew($session['user']['guild_new_ID']);

switch($_GET['op']){
    
    case '':
                if ($session['user']['guild_new_active'] == 1 )
                {
                    addnav('Gilde betreten','guild_n_main.php');
                }
                    
                    
                    
                    
        addnav('Grotte','su_superuser.php');
            
        
        
        // QUERY * Alle Gilden anzeigen lassen
            $sql = "SELECT g_leader_name, g_prefix, g_name, g_member_count FROM guild_n_main ORDER BY g_id DESC";
            $result= db_query($sql) or die(db_error(LINK));

                output("    
                    <table border='0' cellpadding='2' cellspacing='2' align='center' bgcolor='#444455'>
                    <tr class='trhead'><td colspan='4'>`c`bGilden`b`c</td></tr>
                    <tr class='trhead'><td>`c`bGilde`b`c</td><td>`c`bPrefix`b`c</td><td>`c`bGildenleiter`b`c</td><td>`c`bMitglieder`b`c</td><td>`c`b`iOPS`i`b`c</td></tr>
                ",true);
                
                
                    if(db_num_rows($result) == 0){
                        output("<tr class='trdark'><td colspan='4'>`c`ibisher keine Gilden in der Stadt`i`c</td></tr>",true);
                    } else {
                        $i = 0;
            while($row = db_fetch_assoc($result)){
                        output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
                        output(" ".$row['g_name']."`i`c");        
                        output("</td><td>",true);            
                        output("".$row['g_prefix']."");
                        output("</td><td>",true);
                        output("".$row['g_leader_name']."");
                        output("</td><td>",true);
                        output("".$row['g_member_count']."");
                        
                        
                        output("</td></tr>",true);
                $i++;
                }
                }
            
                            output('</table>`n`n`n',true);
    
    break;



}


checkday();
page_footer();

?>

