
<?php
/*  Such das Geschenk Event
    By Mr edah
    www.edahnien.de
    sdge.php in $allownonnav aufnehmen
    sdge.php in $allowanonymous aufnehmen
    Diesen code 
        include('sdge.php');
        check_event_aktiv();    
    vor
        echo $output."";       
    in der common (funktion page_footer() ) einfÃ¼gen.
*/
/* Settings*/
define('SDGE_EVENTON','true'); # Event an oder aus (true / false)
define('SDGE_MAXCOUNT','2'); # warscheinlichkeit das man ein Geschenk bekommen kann warscheinlichkeit 1 zu ??? 

/** 
    PrÃ¼ft ob ein Geschenk Gesetzt werden darf, und leitet ggf die prozedur ein
*/
function check_event_aktiv() {
    global $session;
    if (SDGE_EVENTON == 'true' && isset($session['user']['acctid'])) {
        //Event an - Geschenk setzen ?
        if (mt_rand(2,SDGE_MAXCOUNT) == 2) {
            //geschenk wird gesetzt
            set_present();
        }
    }
}
/* setzt ein Geschenk an eine beliebige stelle im Spiel*/
function set_present() {
    global $session;
    $token = mt_rand(11111111,99999999);
    $session['sdge']['token'] = $token;
    //das Geschenk Platzieren
    echo"<div id='img_sdge' style='z-index:9999'></div>
        <script>
            var http_request = false;
            if (typeof XMLHttpRequest != \"undefined\") 
            {
                http_request = new XMLHttpRequest();
            }
            if (!http_request) 
            {
                try 
                {
                    http_request = new ActiveXObject(\"Msxml2.XMLHTTP\");
                }
                catch(e) 
                {
                    try 
                    {
                        http_request = new ActiveXObject(\"Microsoft.XMLHTTP\");
                    }
                    catch(e) 
                    {
                        http_request = null;
                    }
                }
            }    
            window.onLoad = setImage();
            function setImage(){
                //user fenstergroesse
                window_Height = window.innerHeight;
                window_Width = window.innerWidth;
                //multiplikator
                mH = window_Height - 200;
                mW = window_Width - 70;
                //Koordinaten des Bildes bestimmen
                var randNum_V = Math.round(Math.random() * mH);
                var randNum_H = Math.round(Math.random() * mW);
                //image erstellen
                var imageContainer = document.querySelector('#img_sdge');
                var img = document.createElement('img');
                img.src = \"/images/sdge.png\";
                img.setAttribute('width', '30');
                img.setAttribute('onclick','open_popup()');
                imageContainer.appendChild(img);
                //div position Ã¤ndern
                imageContainer.style.position = 'absolute';
                imageContainer.style.top = randNum_V + \"px\";
                imageContainer.style.left = randNum_H + \"px\";                
                                
            }
            function open_popup(){
                //schliesen butten einsetzen
                var closeline = document.querySelector('#img_sdge');
                closeline.innerHTML ='';
                var closeimg = document.createElement('input');
                closeimg.setAttribute('onclick','document.getElementById(\'img_sdge\').style.display = \'none\';');
                closeimg.setAttribute('type','button');
                closeimg.setAttribute('class','button');
                closeimg.setAttribute('value','Schliesen');
                var inhalt = document.createElement('div');
                inhalt.setAttribute('id', name);
                var frame = document.createElement('iframe');
                frame.src = './sdge.php?op=present&token=".$token."';
                frame.setAttribute('width', '200');
                frame.setAttribute('height', '75');
                frame.setAttribute('style','background-color:#E0E0E0;');
                frame.setAttribute('frameborder', '0');
                inhalt.appendChild(frame);
                closeline.appendChild(inhalt);
                closeline.appendChild(closeimg);
            }                     
        </script>";
}
if ($_GET['op'] == 'present') {
    //common einbinden
    require_once "common.php";
    //Sicherheitsabfrage
    if ($session['sdge']['token'] == $_GET['token']) {
        //Token lÃ¶schen damit mehrfachabrufe nicht mÃ¶glich sind
        unset($session['sdge']['token']);
        //Hier muss der Teil rein der Passiert wenn man das Geschenk Ã¶ffnet
        switch(mt_rand(0,2)) {
            case 0:
                echo "Ein Gem";
                $session['user']['gems']++;
            break;
            case 1:
                echo "Gold";
                $session['user']['gold']+= 500;
            break;
            case 2:
                echo "Torte";
            break;        
        }
        //speichern und fertig
        saveuser();
    }
    exit();
} 
?>

