
<?php
/**
 * Darstellung des LOGD Net
 */

require_once 'common.php';

if ($_GET['op']=='')
{
    $sql = 'SELECT lastupdate,serverid FROM logdnet WHERE address="'.$_GET['addy'].'"';
    $result = db_query($sql);
    $row = db_fetch_assoc($result);

    if (db_num_rows($result)>0)
    {
        if (strtotime($row['lastupdate'])<strtotime(date('r').'-1 minutes'))
        {
            $sql = 'UPDATE logdnet SET priority=priority*0.99';
            db_query($sql);
            //use PHP server time for lastupdate in case mysql server and PHP server have different times.
            $sql = "UPDATE logdnet SET priority=priority+1,description='".soap($_GET['desc'])."',lastupdate='".date("Y-m-d H:i:s")."' WHERE serverid=".$row['serverid'];
            //echo $sql;
            db_query($sql);
            echo 'Ok - upgedated';
        }
        else
        {
            echo 'Ok - noch zu früh für ein Update';
        }
    }
    else
    {
        $sql = 'INSERT INTO logdnet (address,description,lastupdate) VALUES ("'.$_GET['addy'].',"'.soap($_GET['desc']).'",now())';
        $result = db_query($sql);
        echo 'Ok - hinzugefügt';
    }
}
elseif ($_GET['op']=='net')
{
    $sql = 'SELECT address,description FROM logdnet WHERE lastupdate > "'.date('Y-m-d H:i:s',strtotime(date('r').'-7 days')).'" ORDER BY priority DESC';
    $result=db_query($sql);
    $int_i = db_num_rows($result);
    for ($i=0;$i<$int_i;$i++)
    {
        $row = db_fetch_assoc($result);
        $row = serialize($row);
        echo $row."\n";
    }
}
else
{
    page_header('LoGD Netz in '.getsetting('townname','Atrahor'));
    
    if ($session['user']['loggedin']==0)
    {
        addnav('Zurück zum '.getsetting('townname','Atrahor').' Login','index.php?r='.intval($_GET['r']));
    }
    else
    {
        addnav('Zurück zum Dorfplatz','village.php');
    }
        
    addnav('Über '.getsetting('townname','Atrahor'),'about.php?r='.intval($_GET['r']));
    $str_out .= get_title('Das LoTGD Netz in '.getsetting('townname','Atrahor'));
    $str_out .= '`c`tHier findet Ihr eine Liste mit anderen LoGD Servern, die im LoGD-Netz registriert sind.`0`c`n`n';
    $str_out .= '<center>
    <table>
        <tr class="trhead">
            <th>`tServername und Link`0</th>
            <th width="130">`tVersion`0</th>
        </tr>';

    if(function_exists('curl_init'))
    {
        $url=(getsetting("logdnetserver","http://lotgd.net/")."logdnet.php?op=net");
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $resulturl=trim(curl_exec($ch));
        curl_close($ch);
        $servers=explode("\n", $resulturl);
    }
    //Ansonsten file
    elseif(ini_get('allow_url_fopen') == '1')
    {
        $servers=file(getsetting("logdnetserver","http://lotgd.net/")."logdnet.php?op=net");
    }
    if(is_array($servers))
    {
        foreach ($servers as $key => $val)
        {
            $row=unserialize($val);
            if (trim($row['description'])=='')
            {
                $row['description']='Another LoGD Server';
            }
            if (substr($row['address'],0,7)=='http://')
            {
                $str_class = ($str_class == 'trlight')?'trdark':'trlight';
                $str_out .= "
                <tr class='".$str_class."'>
                    <td valign='top'>
                        <a href='".HTMLEntities($row['address'])."' target='_blank'>".stripslashes(str_replace('`&amp;','`&',HTMLEntities($row['description'])))."`0</a>
                    </td>
                    <td valign='top' width='130'>".HTMLEntities($row['version'])."</td>
                </tr>";
            }
        }
    }
    else
    {
        $str_out .= "
        <tr>
            <td valign='top' colspan='2'>Hier ging jetzt irgendwas schief...</td>
        </tr>";
    }

    $str_out .= '</table></center>';
    output($str_out);
    page_footer();
}
?>

