
ï»¿<?php



/* ----------------------------------------------------------------------------

 * email.php mod (ver 1.0.0)

 *

 * written -entirely- by thegleek (thegleek@thegleek.com) on 25 June 2004

 * with a few changes to fit the needs for LoGD by anpera on July, 16th 2004

 *

 * The latest version is always available at: http://dragonprime.cawsquad.net/

 *

 * ----------------------------------------------------------------------------

 * DISCLAIMER

 *

 * - use this script at your own risk! i am not responsible for anything you

 *   do using this program. realize, that if you have 1,000's of users this

 *   script will try to email every and each on of them, regardless if their

 *   email address is valid or not. also realize the implications this can

 *   have on your host if they consider this type of action as spamming and/or

 *   too much load on their server.

 *

 * - this was made with the intention and use by experienced admins who are

 *   running their OWN server off their OWN bandwidth...

 *

 * ----------------------------------------------------------------------------

 * DESCRIPTION

 *

 * - i made this mod for admins who wish to send out email to one or all of

 *   their players...

 *

 * ----------------------------------------------------------------------------

 * INSTALLATION

 *

 * - unzip the mass-email_v1.0.0.zip and put the email.php in the main logd dir

 *

 * - superuser.php

 *

 *   FIND:

 *        addnav("Stats","stats.php");

 *

 *   INSERT AFTER:

 *

 *        if ($session[user][superuser]>=3) addnav("Mass Email","email.php");

 *

 *

 * ------------------------------------------------------------------------

 *

 */



$email_ver="1.0.0";

require_once "common.php";

isnewday(3);

page_header("Gleek's Mass Emailer $email_ver");

output("`c`b`&Gleek's Mass Emailer $email_ver`0`b`c`n");



$search=" AND emailaddress<>''";

// $search= " AND acctid<100";



$sql="SELECT * FROM accounts WHERE locked=0 $search ORDER BY acctid ASC";

$result=db_query($sql) or die(sql_error($sql));

$max=db_num_rows($result);



$admin=getsetting("gameadminemail","postmaster@localhost");



// javascript functions

rawoutput("<script language='JavaScript'>

function set(n) {

    temp=document.main.elements.length ;

     for (i=0; i<temp; i++) { document.main.elements[i].checked=n; }

}

function Invers() {

    temp=document.main.elements.length;

    for (i=0; i<temp; i++) {

        if (document.main.elements[i].checked==1) { document.main.elements[i].checked=0; }

         else { document.main.elements[i].checked=1; }

    }

}

</script>");

// end script



output("<table border='0' width='98%'>",true);

addnav("Return to Grotto","superuser.php");



if ($HTTP_GET_VARS[op]=="") {

    output("<tr><td>`&`bSend mass email to all players`b</td></tr><tr><td>

               <form action='email.php?op=sendaway' method='post' name='main'>

            <input type='hidden' name='admin' value='$admin'>

                   <table>

            <tr><td><b>From: </b>$admin</td></tr>

                   <tr><td><b>Subject: </b><input type='text' name='subject' size=30></td></tr>

                   <tr><td>&nbsp;</td></tr>

                   <tr><td><b>Message:</b><br><textarea cols='60' rows='5' name='desc' wrap='soft'></textarea></td></tr>

                   <tr><td><div align='center'><input type='submit' value='Send Out Your Message'></div></td></tr>

                </table>

               </td></tr><tr><td>&nbsp;</td></tr>",true);

    output("<tr align='center'><td align='center'>",true);

    output("`&`bTotal Warriors in the Realm: $max`b`n`n");

    output("<table border='0' cellpadding='2' cellspacing='1' bgcolor='#777777'>",true);

    output("<tr class='trhead'><th><b>Acct ID</b></th><th><b>Name</b></th><th><b>Email Address</b></th>",true);

    output("<th><b>Send?</b></th></tr>",true);



    for($i=0;$i<$max;$i++){

                $row=db_fetch_assoc($result);

        $x=$i+1;

                output("<tr class='".($i%2?"trdark":"trlight")."'><td align='right'>",true);

                output("$row[acctid]</td><td align='right'>",true);

                output("".(htmlentities($row[login]))."</td><td align='right'>",true);

                output("".(htmlentities($row[emailaddress]))."</td>",true);

        output("<td align='center'><input type='checkbox' name='chbox[$x]' value='$row[acctid]'></td></tr>",true);

    }



    output("<div align='center'><input name='button' onclick=set(1) type='button' value='Select All'>

           <input name='button' onclick=Invers() type='button' value=' Invert '>

           <input name='button' onclick=set(0) type='button' value=' Reset '></div></form>",true);

    output("</table>`n</td></tr><tr><td>&nbsp;</td></tr></table>",true);

    addnav("","email.php?op=sendaway");

}



if ($HTTP_GET_VARS[op]=="sendaway") {

    output("</table>`n",true);

    $email =& $_POST[chbox];

    $qty=count($email);

    $y=0;

    $mailheaders    = "MIME-Version: 1.0\r\n";

    $mailheaders   .= "Content-type: text/html; charset=iso-8859-1\r\n";

    $desc        = $_POST[desc];

    $desc           .= "\n\n---------------------------------------------\nSent by LoGD server: ".$_SERVER['SERVER_NAME']."\nUsing Gleek's Mass Emailer v".$email_ver."\n\n";

    $subject    = "[LoGD] ".$_POST[subject];

    $mailheaders   .= "From: ".$_POST[admin]."\r\n";

    output("Mailheaders: $mailheaders`n`n");

    output("Subject: $subject`n`n");

    output("Message: $desc`n`n`n");

    output("`bStatus:`b`n");

    for ($z=1;$z<$max+1;$z++) {

        $row=db_fetch_assoc($result);

        if ($email[$z]) {

            $y++;

            output("`&Email #$y out of $qty ($row[emailaddress])...: ");

            if(mail($row[emailaddress],$subject,$desc,$mailheaders)?$status="`@SENT`0":$status="`\$FAILED`0");

            output("$status`n");

        }

    }

    $_GET[op]="";

}



output("`n`n`\$Mass Emailer: Copyright 2004, `&thegleek.com");

page_footer();



?>

