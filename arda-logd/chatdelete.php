<?php
// 05.08.2007 von Talcyr, editieren und löschen der Posts... Änderungen in der common.php notwendig (oder wo auch immer eure viewcommentary ist)
//ermöglichst das Editieren und Löschen der Posts bzw. des letzten Posts... 

//Sonderbehandlung für die RP Orte von Kamui... blödes Ding ><
//Wenn die folgende Funktion von Auric (NPC System) noch nicht vorhanden ist, entkommentieren

function beginsWith($in,$with) {
        // Kleine Funktion für netteren Code by Auric
    return (strtolower(substr($in,0,strlen($with)))===$with?true:false);
}

require_once 'common.php';

//$nichterlaubt = array("ooc"=>"1","hunterlodge"=>"1","jail"=>"1","superuser"=>"1","todoliststand"=>"1");

switch($_GET['op'])
{
case 'delete':
    $ergeb = db_query("SELECT `commentid`, `comment`, `section` FROM `commentary` WHERE `author`=".$session['user']['acctid']." AND `section`='".$_GET['section']."' ORDER BY `postdate` DESC LIMIT 1");
    $zahl = db_num_rows($ergeb);
    if($zahl>=1)
    {
        $row = db_fetch_assoc($ergeb);
        /*if($session['user']['rpchar']!=0 && $nichterlaubt[$row['section']]!=1)
        {
            $anzahl = floor((strlen($row['comment']))/100);
            $session['user']['donation']-=$anzahl;
        }
*/
        db_query("DELETE FROM `commentary` WHERE `commentid` = ".$row['commentid']." AND `author` = ".$session['user']['acctid']." LIMIT 1");
        if(preg_match('/^Ort_/',$_GET['section'])==1) $return = $_GET['restore']."&id=".$_GET['id'];
        else $return = $_GET['restore'];
        redirect ($return);
    }else{
    page_header('Löschen');
    require_once 'common.php';
        output('Du hast bisher hier noch keinen Beitrag geschrieben.');
        if(preg_match('/^Ort_/',$_GET['section'])==1) $return = $_GET['restore']."&id=".$_GET['id'];
        else $return = $_GET['restore'];
        addnav('Zurück',$return);
    page_footer();
    }
break;
case 'edit':
page_header('Post Editieren');
    $result = db_query("SELECT `comment`,`commentid` FROM `commentary` WHERE `author`=".$session['user']['acctid']." AND `section`='".$_GET['section']."' ORDER BY `postdate` DESC LIMIT 1");
    $row = db_fetch_assoc($result);
    $zahl = db_num_rows($result);
    if($zahl>=1)
    {
        if(!isset($_POST['message'])){
            $message=$row[comment];
                if(preg_match('/^Ort_/',$_GET['section'])==1) $return = $_GET['restore']."&id=".$_GET['id'];
                else $return = $_GET['restore'];        
                rawoutput("<form action=\"$REQUEST_URI?op=edit&restore=".$return."&section=".$_GET['section']."\" method='POST'>");
                rawoutput("<span id='chatpreview'></span></br></br><textarea cols='40' rows='3' class='input' name='message' 
                onkeyup=\"document.getElementById('chatpreview').innerHTML = appoencode(this.value); \" 
                style='width: 400px;'>$row[comment]</textarea>");
            output("<br><br><input type='submit' class='button' value='Hinzufügen'>`n</form>",true);    
            addnav('',$REQUEST_URI."?op=edit&restore=".$return."&section=".$_GET['section']);
            addnav('Zurück',$return);
        }else{
            $message = strip_tags(trim($_POST['message']));
 //           $message = str_replace("&amp;","&",$message);
            if($message=='')
            {
            unset($_POST);
            if(preg_match('/^Ort_/',$_GET['section'])==1) $return = $_GET['restore']."&id=".$_GET['id'];
            else $return = $_GET['restore']; 
            redirect ($return);
            }else{
            /*    if($session['user']['rpchar']!=0 && $nichterlaubt[$row['section']]!=1)
                {
                    $anzahl = floor((strlen($row['comment']))/100);
                    $session['user']['donation']-=$anzahl;
                    $anzahl = floor((strlen($message))/100);
                    $session['user']['donation']+=$anzahl;
                }
                */
                $result = db_query("SHOW COLUMNS FROM `commentary`");
                if (mysql_num_rows($result) > 0) {
                    $i=0;
                    while ($field = db_fetch_assoc($result)) {
                       $field_array[$i]=$field;
                       $i++;
                    }
                }
                /*if (!in_array('emote', $field_array)){
                
                    if(beginsWith($message,'/me') ){
                    $message = substr($message,3);
                    db_query("UPDATE `commentary` SET `comment`='".$message."', `emote`=1 WHERE `commentid`=".$row['commentid']." AND `author`=".$session['user']['acctid']);
                    }
                    elseif(beginsWith($message,'::')){
                    $message = substr($message,2);
                    db_query("UPDATE `commentary` SET `comment`='".$message."' ,`emote`=1 WHERE `commentid`=".$row['commentid']." AND `author`=".$session['user']['acctid']);
                    }
                    elseif(beginsWith($message,':')){
                    $message = substr($message,1);
                    db_query("UPDATE `commentary` SET `comment`='".$message."', `emote`=1 WHERE `commentid`=".$row['commentid']." AND `author`=".$session['user']['acctid']);
                    }
                    elseif(beginsWith($message,'/em')){
                    $message = substr($message,3);
                    db_query("UPDATE `commentary` SET `comment`='".$message."', `emote`=2 WHERE `commentid`=".$row['commentid']." AND `author`=".$session['user']['acctid']);
                    }
                    elseif(beginsWith($message,'/x')){
                    $message = substr($message,2);
                    db_query("UPDATE `commentary` SET `comment`='".$message."', `emote`=2 WHERE `commentid`=".$row['commentid']." AND `author`=".$session['user']['acctid']);
                    }
                    elseif(beginsWith($message,'/ms')){
                    $message = substr($message,3);
                    db_query("UPDATE `commentary` SET `comment`='".$message."', `emote`=3 WHERE `commentid`=".$row['commentid']." AND `author`=".$session['user']['acctid']);
                    }
                    else db_query("UPDATE `commentary` SET `comment`='".$message."', `emote`=0 WHERE `commentid`=".$row['commentid']." AND `author`=".$session['user']['acctid']);
                }
                else */db_query("UPDATE `commentary` SET `comment`='".$message."' WHERE `commentid`=".$row['commentid']." AND `author`=".$session['user']['acctid']);
        
                if(preg_match('/^Ort_/',$_GET['section'])==1) $return = $_GET['restore']."&id=".$_GET['id'];
                else $return = $_GET['restore']; 
                redirect($return);
            }
        }
    }else{
    output('`$Du hast hier noch keinen Post geschrieben!');
        if(preg_match('/^Ort_/',$_GET['section'])==1) $return = $_GET['restore']."&id=".$_GET['id'];
        else $return = $_GET['restore'];   
        addnav('Zurück',$return);
    }
    page_footer();
break;
}

?>