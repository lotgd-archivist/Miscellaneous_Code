
<?php

// 15082004

define('LISTCOLORTEXT','`7');
define('LISTCOLORBOLD','`s');

require_once 'common.php';
popup_header('Einwohnerliste');
output("<script language='javascript'>window.resizeTo(800,650);</script>",true);

if($_GET['op'] == 'rpchange') {
        $session['user']['rpbulb'] = (int)$_POST['rpstatus'];
        header('Location: list_popup.php');
} elseif($_GET['op'] == 'rescue' && su_check(SU_RIGHT_GROTTO)) {
        $id = (int)$_GET['id'];
        $sql = "UPDATE accounts SET allowednavs='',output='',restorepage='' WHERE acctid=".$id;
        saveuser();
        db_query($sql);
        debuglog("`&hat Benutzer mit AcctID ".$id."`& einen Rettungsnav geschickt.");
        if($id == $session['user']['acctid']) {
                output('<script type="Text/JavaScript">
                        <!--
                        opener.parent.location.href="village.php";
                        self.close();
                        //-->
                        </script>');
                addnav('','village.php');
        } else {
                header('Location: list_popup.php');
        }
} else {
        // RP-Status-Wechsel über Radio Buttons
        output("`n".LISTCOLORTEXT."<u id='seitenanfang'>Eigener RP-Status:</u>`n
                <form action='list_popup.php?op=rpchange' method='post'>
                  <table><tr>
                    <td>
                      <input type='radio' id='rponnow' name='rpstatus' onchange='this.form.submit();' value='1' ".($session['user']['rpbulb'] == 1 ? "checked" : "")."><label for='rponnow'><img src='images/green.png' alt='Suche RP für ab sofort'>&nbsp;".LISTCOLORTEXT."Suche ein RP für ab sofort.</label>`n
                      <input type='radio' id='rponlater' name='rpstatus' onchange='this.form.submit();' value='3' ".($session['user']['rpbulb'] == 3 ? "checked" : "")."><label for='rponlater'><img src='images/blue.png' alt='Suche RP für später'>&nbsp;".LISTCOLORTEXT."Suche ein RP für einen späteren Zeitpunkt.</label>
                    </td>
                    <td style='padding-left: 1.5em; vertical-align: top;'>
                      <input type='radio' id='rppublic' name='rpstatus' onchange='this.form.submit();' value='2' ".($session['user']['rpbulb'] == 2 ? "checked" : "")."><label for='rppublic'><img src='images/yellow.png' alt='Im öffentlichen RP'>&nbsp;".LISTCOLORTEXT."Im öffentlichen RP - schreib dich dazu!</label>`n
                      <input type='radio' id='norp' name='rpstatus' onchange='this.form.submit();' value='0' ".($session['user']['rpbulb'] == 0 ? "checked" : "")."><label for='norp'><img src='images/red.png' alt='Suche kein RP'>&nbsp;".LISTCOLORTEXT."Nicht auf der Suche nach einem RP</label>
                    </td>
                    <td style='padding-left: 1.5em; vertical-align: top;'>
                      <noscript><input type='submit' class='button' value='Ändern'></noscript>
                    </td>
                  </tr></table>
                </form>",true);
        // end RP-Status-Wechsel
        output("`n`n",true);
        // Order the list by level, dragonkills, name so that the ordering is total!
        // Without this, some users would show up on multiple pages and some users
        // wouldn't show up
        output('<style type="text/css">
                 a { color: #ca9446; }
                 a.refresh {
                       padding: 4px 1px 0px 1px;
                       border: 1px dotted #ccb61c;
                 }
                 a:active.refresh {
                       border: none;
                       top: 3px;
                       position: relative;
                       padding: 5px 3px 1px 3px;
                 }
                </style>
                `c<a href="list_popup.php#chatende" class="refresh"><img src="images/to_bottom.png" alt="to_bottom"></a>
                  &ensp;<a href="list_popup.php" class="refresh"><img src="images/refresh.png" alt="refresh"></a> &nbsp;&nbsp;'.LISTCOLORBOLD.'`bFolgende Bürger '.getsetting('townname','Eranya').'s sind gerade online:`b &nbsp;&nbsp;`c`n'.LISTCOLORTEXT);
        user_show_list_popup(user_get_online(),'level DESC, dragonkills DESC, login ASC');
        output(LISTCOLORTEXT.'`n`n`nOOC:`n`n');
        popup_addcommentary('OOC');
        viewcommentary('OOC','Tippen',35,'tippt',false,true,false,false,false,true,1,true);
        output('<a href="list_popup.php#seitenanfang" class="refresh"><img src="images/to_top.png" alt="to_top"></a>');
}
popup_footer();

?>

