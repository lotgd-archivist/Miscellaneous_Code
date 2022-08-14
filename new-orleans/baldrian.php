
<?

//  13042005
// Jessi's Kräuterladen
// 

require_once "common.php";

page_header("Kräuterladen/Baldrian");

if ($HTTP_GET_VARS[op]=="baldrian"){

                output ("Du nimmst etwas von dem Baldrian du fühlst dich erholt");                
                }
                if ($_GET['op']=="baldrian"){
                  if ($session['user']['gold']<=150){
                output (" Du hast nicht genug Gold");
                   } else {
                output (":)");
                $session[user][hitpoints] = $session[user][maxhitpoints];
                $session['user']['gold']-=150;
    $session['user']['turns']-=1;
                //debuglog:("eat 1 herb");
   }
                addnav("Zurück zum Kräuterladen","kraeuter.php");
}
page_footer();
?> 
