
<?
require_once "common.php";


if ($HTTP_POST_VARS[name]!=""){
    if ($session[loggedin]){
        redirect("badnav.php");
    }else{
        if(0){
        }else{
            $sql = "SELECT * FROM accounts WHERE login = '$HTTP_POST_VARS[name]' AND password='$HTTP_POST_VARS[password]' AND locked=0";
            $result = db_query($sql);
            if (db_num_rows($result)==1){
                $session[user]=db_fetch_assoc($result);
                $sql2="SELECT laston,loggedin FROM accounts WHERE locked=0 AND loggedin=1 AND laston>'".date("Y-m-d H:i:s",strtotime("-".getsetting("LOGINTIMEOUT",900)." seconds"))."' ORDER BY level DESC"; 
                $result2 = db_query($sql2) or die(sql_error($sql2)); 
                for ($onlinecount=0;$onlinecount<db_num_rows($result2);$onlinecount++); 
                db_free_result($result2); 
                if (($onlinecount>=getsetting("maxonline",10) || getsetting("maxonline",10)==0)
                    && ($session[user][superuser]<=2 && $session[user][prayer]==0)){
                    $session[message]="`4Der Server ist leider voll, bitte versuche es später nochmal`0";
                    redirect("index.php");
                }else{
                    //$session[user]=db_fetch_assoc($result);
                    //debuglog("Punkt 1:".date("Y-m-d H:i:s")."");
                    //echo "Ooga Booga";
                    //flush();
                    //exit();
                    checkban($session[user][login]); //check if this account is banned
                    checkban(); //check if this computer is banned
                    //debuglog("Punkt 2:".date("Y-m-d H:i:s")."");
                    if (strpos($_SERVER['SERVER_NAME'],"logd.mightye.org")!==false && $session['user']['superuser']<1){
                        if (date("H")<2 || date("H")>17){
                        $session[message]="`\$The dev server shuts down in the
                            evening now so that I may partake of my home
                            bandwidth.  This server is only available from
                            2am to 5pm from now on.";
                        //echo $session[message];
                        header("Location: index.php");
                        exit();
                        //redirect("index.php");
                        }
                    }
                    if ($session[user][emailvalidation]!="" && substr($session['user']['emailvalidation'],0,1)!="x"){
                        $session[user]=array();
                        $session[message]="`4Du mußt erst Deine eMail Adresse bestätigen, bevor Du starten kannst.";
                        echo $session[message];
                        //header("Location: index.php");
                        exit();
                    }else{
                        //debuglog("Punkt 3:".date("Y-m-d H:i:s")."");
                        //loaduser($session['user']);
                        $session[loggedin]=true;
                        $session[output]=$session[user][output];
                        $session['lastlogoff']=$session['user']['laston'];
                        $session['petitions'] = array();
                        $session['todolist'] = array();
                        $session[laston]=date("Y-m-d H:i:s");
                        $session[sentnotice]=0;
                                    if ($_SERVER['HTTP_X_FORWARDED_FOR']) {
                                        if ($_SERVER['HTTP_CLIENT_IP']) {
                                                $proxy = $_SERVER['HTTP_CLIENT_IP'];
                                        } else {
                                                $proxy = $_SERVER['REMOTE_ADDR'];
                                        }
                                        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                                    } else {
                                        if ($_SERVER['HTTP_CLIENT_IP']) {
                                            $ip = $_SERVER['HTTP_CLIENT_IP'];
                                        } else {
                                                $ip = $_SERVER['REMOTE_ADDR'];
                                        }
                                    }
                                    if (isset($proxy)) {
                                        $ip .= " through Proxy: ".$proxy;
                                    }
                                    debuglog("Client connected from IP: ".$ip);
                        $session[user][dragonpoints]=unserialize($session[user][dragonpoints]);
                        $session[user][prefs]=unserialize($session[user][prefs]);
                        $session['bufflist']=unserialize($session['user']['bufflist']);
                        if (!is_array($session[user][dragonpoints])) $session[user][dragonpoints]=array();
                        //debuglog("Punkt 4:".date("Y-m-d H:i:s")."");
                        if ($session[user][loggedin]){
                            $session[allowednavs]=unserialize($session[user][allowednavs]);
                            saveuser();
                            header("Location: {$session['user']['restorepage']}");
                            exit();
                            //redirect($session['user']['page']);//"badnav.php");
                        }
                        //debuglog("Punkt 5:".date("Y-m-d H:i:s")."");
                        db_query("UPDATE accounts SET loggedin=".true.", location=0 WHERE acctid = ".$session[user][acctid]);
                        $session[user][loggedin]=true;
                        $location = $session[user][location];
                        $session[user][location]=0;
                        if ($session[user][alive]==0 && $session[user][slainby]!=""){
                            //they're not really dead, they were killed in pvp.
                            $session[user][alive]=true;
                        }
                        //debuglog("Punkt 6:".date("Y-m-d H:i:s")."");
                        if (getsetting("logdnet",0)){
                            //register with LoGDnet
                            @file(getsetting("logdnetserver","http://lotgd.net/")."logdnet.php?addy=".URLEncode(getsetting("serverurl","http://".$_SERVER['SERVER_NAME'].dirname($_SERVER['REQUEST_URI'])))."&desc=".URLEncode(getsetting("serverdesc","Another LoGD Server"))."");
                        }
                        //debuglog("Punkt 7:".date("Y-m-d H:i:s")."");
                                    $logfacts = "LP ".$session[user][hitpoints]." MLP ".$session[user][maxhitpoints].
                                                " EXP ".$session[user][experience]." GEM ".$session[user][gems].
                                                " Gold ".$session[user][gold];
                        if ($location==0){
                                        if ($session[user][alive]) {
                                                debuglog("Login Fields with ".$logfacts);
                                        } else {
                                                debuglog("Login Ramius with ".$logfacts);
                                        }
                            redirect("news.php");
                        }else if($location==1){
                                        debuglog("Login Inn with ".$logfacts);
                            redirect("inn.php?op=strolldown");
                                    }else if($location==2){
                                        debuglog("Login House with ".$logfacts);
                                        redirect("houses.php?op=newday");
                        }else{
                                        debuglog("Login with ".$logfacts);
                            saveuser();
                            header("Location: {$session['user']['restorepage']}");
                            exit();
                        }
                    }
                }
            }else{
                $session[message]="`4Deine Login Daten waren nicht korrekt`0";
                //now we'll log the failed attempt and begin to issue bans if there are too many, plus notify the admins.
                $sql = "DELETE FROM faillog WHERE date<'".date("Y-m-d H:i:s",strtotime("-".(getsetting("expirecontent",180)/4)." days"))."'";
                checkban();
                db_query($sql);
                $sql = "SELECT acctid FROM accounts WHERE login='{$_POST['name']}'";
                $result = db_query($sql);
                if (db_num_rows($result)>0){ // just in case there manage to be multiple accounts on this name.
                    while ($row=db_fetch_assoc($result)){
                        $sql = "INSERT INTO faillog VALUES (0,now(),'".addslashes(serialize($_POST))."','{$_SERVER['REMOTE_ADDR']}','{$row['acctid']}','{$_COOKIE['lgi']}')";
                        db_query($sql);
                        $sql = "SELECT faillog.*,accounts.superuser,name,login FROM faillog INNER JOIN accounts ON accounts.acctid=faillog.acctid WHERE ip='{$_SERVER['REMOTE_ADDR']}' AND date>'".date("Y-m-d H:i:s",strtotime("-1 day"))."'";
                        $result2 = db_query($sql);
                        $c=0;
                        $alert="";
                        $su=false;
                        while ($row2=db_fetch_assoc($result2)){
                            if ($row2['superuser']>0) {$c+=1; $su=true;}
                            $c+=1;
                            $alert.="`3{$row2['date']}`7: Failed attempt from `&{$row2['ip']}`7 [`3{$row2['id']}`7] to log on to `^{$row2['login']}`7 ({$row2['name']}`7)`n";
                        }
                        if ($c>=10){ // 5 failed attempts for superuser, 10 for regular user
                            $sql = "INSERT INTO bans VALUES ('{$_SERVER['REMOTE_ADDR']}','','".date("Y-m-d H:i:s",strtotime("+".($c*3)." hours"))."','Automatic System Ban: Too many failed login attempts.')";
                            db_query($sql);
                            if ($su){ // send a system message to admins regarding this failed attempt if it includes superusers.
                                $sql = "SELECT acctid FROM accounts WHERE superuser>=3";
                                $result2 = db_query($sql);
                                $subj = "`#{$_SERVER['REMOTE_ADDR']} failed to log in too many times!";
                                for ($i=0;$i<db_num_rows($result2);$i++){
                                    $row2 = db_fetch_assoc($result2);
                                    //delete old messages that 
                                    $sql = "DELETE FROM mail WHERE msgto={$row2['acctid']} AND msgfrom=0 AND subject = '$subj' AND seen=0";
                                    db_query($sql);
                                    if (db_affected_rows()>0) $noemail = true; else $noemail = false;
                                    systemmail($row2['acctid'],"$subj","This message is generated as a result of one or more of the accounts having been a superuser account.  Log Follows:`n`n$alert",0,$noemail);
                                }//end for
                            }//end if($su)
                        }//end if($c>=10)
                    }//end while
                }else{
                    
            }//end if (db_num_rows)
            redirect("index.php");
            }
        }
    }
}else if ($HTTP_GET_VARS[op]=="logout"){
    if ($session[user][loggedin]){
      debuglog("logged out in the fields ");
      $sql = "UPDATE accounts SET loggedin=0, locate=0 WHERE acctid = ".$session[user][acctid];
        db_query($sql) or die(sql_error($sql));
    }
    $session=array();
    redirect("index.php");
}
else if ($HTTP_GET_VARS[op]=="mainlogout"){
    if ($session[user][loggedin]){
        $wo = $HTTP_GET_VARS[wo];
        // 0 = fields
        // 1 = inn
        // 2 = house
        // 9 = Pranger
        $logtext = "Logout ";
        switch ( $wo ) {
            case 0:
            if ($session[user][alive]) {
                $logtext = $logtext."Fields with ";
            } else {
                $logtext = $logtext."Ramius with ";
            }
            break;
            case 2:
            $logtext = $logtext."House with ";
            if ($session[user][housekey]!=$session[housekey]){
                $sql = "UPDATE items SET hvalue=".$session[housekey]." WHERE value1=".(int)$session[housekey]." AND owner=".$session[user][acctid]." AND class='Schlüssel'";
                db_query($sql) or die(sql_error($sql));
            }
            break;

            case 9: $logtext = $logtext."Pranger with "; break;
        }
        $logfacts = "LP ".$session[user][hitpoints]." MLP ".$session[user][maxhitpoints].
                " EXP ".$session[user][experience]." GEM ".$session[user][gems].
                " Gold ".$session[user][gold];
                            
        $sql = "UPDATE accounts SET loggedin=0, location=".$wo." WHERE acctid = ".$session[user][acctid];
        db_query($sql) or die(sql_error($sql));

        debuglog($logtext.$logfacts);
    }
    $session=array();
    redirect("index.php");
}
// If you enter an empty username, don't just say oops.. do something useful.
$session=array();
$session[message]="`4Deine Login Daten sind nicht korrekt`0";
redirect("index.php");
?>


