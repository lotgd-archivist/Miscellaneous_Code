
<!--angelegt für eranya.de von Silva-->
<!--!header-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//DE">
<html>
<head>
  <title>Die Legenden von Eranya - LoGD</title>
  <link href="templates/imagine2.css" rel="stylesheet" type="text/css">
</head>
<body>
  <table id="table1">
    <tr>
      <td>
        <table id="table3">
          <tr>
            <td id="nav">
              <table id="table4" cellpadding="0" cellspacing="0">
                <tr>
                  <td class='motd_nav_stats_frame'>
                    <img src="templates/imagine/nav_frame_top.png" alt="">
                  </td>
                </tr>
                <tr>
                  <td id="title">
                    Die Legenden von Eranya
                  </td>
                </tr>
                <tr>
                  <td class='motd_nav_stats_frame'>
                    <img src="templates/imagine/nav_frame_bot.png" alt="">
                  </td>
                </tr>
              </table>
            </td>
            <td class='content_td'>
              <table cellpadding='0' cellspacing='0' class='cframe'>
                <tr>
                  <td id='cframe_topleft1'>
                    &nbsp;
                  </td>
                  <td>
                    <table cellpadding='0' cellspacing='0' class='cframe'>
                      <tr>
                        <td id='cframe_topleft2'>
                          <img src='templates/imagine/cframe_topright2_trans.png' alt=''>
                        </td>
                        <td id='cframe_top'>
                          <img src='templates/imagine/banner2.png' alt='Eranya Banner'>
                        </td>
                        <td id='cframe_topright2'>
                          <img src='templates/imagine/cframe_topright2_trans.png' alt=''>
                        </td>
                      </tr>
                    </table>
                  </td>
                  <td id='cframe_topright1'>
                    &nbsp;
                  </td>
                </tr>
                <tr>
                  <td id='cframe_left'>
                    &nbsp;
                  </td>
                  <td id='content' style='color: #fbf9cd;'>
                    <h2 style='color: #FBEC5D;'>Aktualisierungssperre!</h2>
                    Um die Belastung des Servers nicht unnötig zu steigern und damit mehr Nutzern einen störungsfreien Spielgenuss zu garantieren, kann die Seite
                    im ausgeloggten Zustand nur alle <b> <?=RELOAD_STOP_TIME ?> Sekunden</b> aktualisiert werden.<br>
                    Wir bitten um euer Verständnis, die Maßnahme ist leider notwendig. Letztendlich kommt es euch zugute, da mehr Spieler gleichzeitig online sein
                    können!<br>
                    Weiter geht's mit einem Klick auf den aktiven Button:<br>
                    <br>
                    <div align="center"><input id="ok_button" class="button" type="button" value="Weiter!" onclick='document.location="index.php"'></div>
                    <script type="text/javascript" language="JavaScript">
                        var count = <? echo (RELOAD_STOP_TIME-$timediff); ?>;
                        counter();
                        function counter () {
                            if(count == 0) {
                                document.getElementById("ok_button").value = "Weiter!";
                                document.getElementById("ok_button").disabled = false;
                            }
                            else {
                                document.getElementById("ok_button").value = "Weiter! (noch "+count+" Sekunden)";
                                document.getElementById("ok_button").disabled = true;
                                count--;
                                setTimeout("counter()",1000);
                            }
                        }
                    </script>
                  </td>
                  <td id='cframe_right'>
                    &nbsp;
                  </td>
                </tr>
                <tr>
                  <td>
                    <img src='templates/imagine/cframe_botleft2_1.png' alt=''>
                  </td>
                  <td>
                    <table cellpadding='0' cellspacing='0' class='cframe'>
                      <tr>
                        <td id='cframe_botleft2'>
                          <img src='templates/imagine/cframe_botleft2_2.png' alt='cframe_botleft'>
                        </td>
                        <td id='cframe_bottom'>
                          &nbsp;
                        </td>
                        <td id='cframe_botright2'>
                          <img src='templates/imagine/cframe_botright2_2.png' alt='cframe_botright'>
                        </td>
                      </tr>
                    </table>
                  </td>
                  <td>
                    <img src='templates/imagine/cframe_botright2_1.png' alt=''>
                  </td>
                </tr>
              </table>
            </td>
            <td class='vitalinfo'>
              <table cellpadding="0" cellspacing="0" class='vitalinfo'>
                <tr>
                  <td class='motd_nav_stats_frame'>
                    <img src="templates/imagine/stats_frame_top.png" alt="">
                  </td>
                </tr>
                <tr>
                  <td id='motd'>
                    <a href="motd.php" target="_blank" onClick="window.open('motd.php','motdphp','scrollbars=yes,resizable=yes,width=550,height=300');return false;" class="motd"><b>MoTD</b></a> <b>|</b> <a href="motd-coding.php?check=all" target="_blank" onClick="window.open('motd-coding.php?check=all','motdcodingphpcheckall','scrollbars=yes,resizable=yes,width=550,height=300');return false;" class="motd"><b>MoTC</b></a><br>
                    <br>
                    <a href="petition.php?op=faq" target="_blank" class="motd" onClick="window.open('petition.php?op=faq','petitionphpopfaq','scrollbars=yes,resizable=yes,width=550,height=300');return false;"><b>Regeln</b> und FAQ</a><br>
                    <a href="petition.php" target="_blank" onClick="window.open('petition.php','petitionphp','scrollbars=yes,resizable=yes,width=550,height=300');return false;" class="motd">Anfrage verfassen</a><br>
                    <a href="http://forum.eranya.de/" target="_blank" class="motd">Forum</a><br>
                    <br>
                  </td>
                </tr>
                <tr>
                  <td class='motd_nav_stats_frame'>
                    <img src="templates/imagine/stats_frame_bot.png" alt="">
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
      <td id="footer">
        Version: 0.9.7+jt ext(GER) DS/V2.5 Eranya Edition <font color="fffaca">&bull;</font> Copyright 2010-<?php echo date('Y'); ?> eranya.de, Inspiriert von Eric Stevens & Dragonslayer <font color="fffaca">&bull;</font> <a href="source.php" target="_blank">Source</a><br>
        Skin by Silva; stock: background by <a href='http://brujo.deviantart.com/' target='_blank'>~brujo</a>, frame by <a href='http://sassacyber.deviantart.com/' target='_blank'>~SassaCYber</a>
      </td>
    </tr>
  </table>
</body>
</html>
