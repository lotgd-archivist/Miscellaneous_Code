
<?php
// Farbhack-JS-Appoencode
// Generiert aktuelle appoencode() für die Chatpreview mit allen Datenbankfarben
// läuft unabhängig vom LoGD-Rest.
// Keine Garantie für den IE!

header('Content-type: text/javascript;');
# Datenbankverbindungsdetails
Require '../dbconnect.php';

# Commentary-Einstellungen
define('COMMENTARY_USE_COMPABILITY_MODE', true);

if(COMMENTARY_USE_COMPABILITY_MODE) {
    Require '../dbwrapper.php';
    
    # Appoencode: Tags laden.
    function Load_Tags() {
        /* (c) 2005 by Eliwood & Serra */
        global $link;
        $tags = array();
        $res = db_query('SELECT * FROM appoencode', $link);
        while($row = db_Fetch_Assoc($res)) {
            $tags[$row['code']] = $row;
        }
        return $tags;
    }
    
    function getmicrotime() {
        list($usec, $sec) = explode(" ",microtime()); 
        return ((float)$usec + (float)$sec); 
    } 
    
    $link = db_pconnect($DB_HOST, $DB_USER, $DB_PASS);
    db_select_db($DB_NAME);
}
else {
    Require 'dbwrapper.class.php';
    Require 'mysqli.dbwrapper.class.php';
    Require 'mysqlipdo.dbwrapper.class.php';
    
    Require '../dbwrapper.php';
    
    $link = db_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME) or die (db_error());
    define("LINK", false);

    try {
        DBWrapper::factory($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_DRIVER);
        $db = DBWrapper::getInstance();
    }
    catch(DBException $e) {
        echo $e;
        exit;
    }
    
    # Appoencode: Tags laden.
    function Load_Tags() {
        /* (c) 2005 by Eliwood & Serra */
        $tags = array();
        $rows = new Query('SELECT * FROM appoencode');
        foreach($rows as $row) {
            $tags[$row['code']] = $row;
        }
        return $tags;
    }
}

$appoencode = load_Tags();

$includinscript = '';
while(list($key,$val) = each($appoencode)) {
    $includinscript .= '
            case "'.$key.'":
                if(openspan) output = output+"</span>"; else openspan = true;
                output= output + "<span style=\"color: #'.$val['color'].';\">";
                break;
    ';
}
?>
function appoencode(data,talkline, myname, mylchar, thismycolor, thisthirdpersonemote, thisemotecolor) {
    var Fundstelle = -1;
    var tag = '';
    var append = '';
    //var output = '<br />Vorschau: ';
    var output = '';
    var openspan = false;
    var hexopen = false;
    var mecheck = '' ;
    var doppelcheck = '' ;
    var xcheck = '';
    //var mesearch = data.search('/me') ;
    var mesearch = 0 ;

    if(thisemotecolor == '') thisemotecolor = '`&' ;
    if(thismycolor == '') thismycolor = '`#' ;
    if(thisthirdpersonemote == '') thisthirdpersonemote = '`&';
    
    while (myname.match(/##([a-fA-F0-9]{6});|##([a-fA-F0-9]{3});/)) {
        myname = myname.replace(/##([a-fA-F0-9]{6});|##([a-fA-F0-9]{3});/, function(colorValue) {
            var returnValue = '';
            if (openspan) {
                returnValue = returnValue + '</span>';
                openspan = false;
            }
            openspan = true;
            colorValue = colorValue.replace(/##/, '#');
            returnValue = returnValue + '<span style="color:'+colorValue+'">';
            
            return returnValue;
        });
    }
    
    data = data.replace('/mE','/me');
    data = data.replace('/ME','/me');
    data = data.replace('/Me','/me');

    data = data.replace('/Em','/em');
    data = data.replace('/eM','/em');
    data = data.replace('/EM','/em');

    data = data.replace('/mS','/ms');
    data = data.replace('/MS','/ms');
    data = data.replace('/Ms','/ms');

    data = data.replace('/gM','/gm');
    data = data.replace('/GM','/gm');
    data = data.replace('/Gm','/gm');
    
    data = data.replace('/X', '/x');

    var clname = myname.replace('`0','');
    // Emote-Check
    mecheck = data.substr(0,3).toLowerCase() ;
    if(mecheck == '/me') {
        data = data.replace('/me',''+myname+' '+thisemotecolor+'');
        mesearch = 1 ;
    }
    if(mecheck == '/ms') {
        if(mylchar == 's') {
            alert(mylchar) ;
            data = data.replace('/ms',''+clname+'\''+thisemotecolor+' ');
        }
        else {
            data = data.replace('/ms',''+clname+'s'+thisemotecolor+' ');
        }
        mesearch = 1 ;
    }        
    doppelcheck = data.substr(0,1).toLowerCase() ;
    if(doppelcheck == ':') {
        data = data.replace(':',''+myname+' '+thisemotecolor+'');
        mesearch = 1 ;
    }           
    
    // Dritte-Personcheck
    if(mecheck == '/em') { // && myadmin == 1) {
        data = data.replace('/em','`^NSC: '+thisthirdpersonemote+' ');
        data = '`&'+data+'' ;
        mesearch = 1 ;
    }            
    xcheck = data.substr(0,2).toLowerCase();
    
    if(xcheck == '/x') {
        data = data.replace('/x','`^NSC: '+thisthirdpersonemote+' ');
        mesearch = 1 ;
    }
    
    if(xcheck == '/gm') {
        data = data.replace('/gm',thisthirdpersonemote+' ');
        mesearch = 1 ;
    }
    
    // Der Rest
    if(mesearch == 0 && data.length != 0) {
        data = ''+myname+""+thisemotecolor+' '+talkline+': '+thismycolor+'«'+data+''+thismycolor+'»' ;
    }

    while ((Fundstelle = data.search(/`/)) != -1) {
        tag = data.substr(Fundstelle+1, 1);
        append = data.substr(0,Fundstelle);
        output = output+ append;
        if (data.length >= Fundstelle+2) data = data.substring(Fundstelle+2,data.length);
        else data = '';

        switch (tag) {
            case "n":
                if (openspan) output=output+"</br>";
                //   openspan = false;
                break;
            
            case "0":
                if (openspan) output= output+"</span>";
                openspan = false;
                break;
            <?php echo $includinscript ?>

            case "`":
                output= output+"`";
                break;
            
            //case "n": 
                //output= output+"</br>";
                //break;
            
            default:
                output= output+"`"+tag;
        }
    }
    
    while (data.match(/##([a-fA-F0-9]{6});|##([a-fA-F0-9]{3});/)) {
        data = data.replace(/##([a-fA-F0-9]{6});|##([a-fA-F0-9]{3});/, function(colorValue) {
            var returnValue = '';
            if (openspan) {
                returnValue = returnValue + '</span>';
                openspan = false;
            }
            openspan = true;
            colorValue = colorValue.replace(/##/, '#');
            returnValue = returnValue + '<span style="color:'+colorValue+'">';
            
            return returnValue;
        });
    }
    
    output += data;
    
    if (openspan) output += '</span>';

    output = output.replace(/\\n/g, '<br />');
    output = output.replace(/\\\\n/gi, '<br />');

    return output;
}
