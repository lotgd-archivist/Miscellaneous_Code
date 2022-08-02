<?php
//von lotgd-zanarkand.com

require_once "common.php";
popup_header("Farben");

            $sql = "SELECT code FROM appoencode WHERE allowed = '1' AND color IS NOT NULL ORDER BY id";
                                $result = db_query($sql);
                                output("<table bgcolor='#000000' cellspacing='1' cellpadding='2' align='center'><tr class='trhead'><td>Code</td><td>Farbe</td></tr>",true);
                                $i = 0;
                                while ($row = db_fetch_assoc($result)) {
                                        $i++;
                                        $code = "``$row[code]";
                                        $color = "`$row[code]"."So sieht das dann aus";
                                        $bgcolor = ($i % 2 == 1 ? "trlight" : "trdark");
                                       output("<tr class='$bgcolor'><td>$code</td><td>$color</td>",true);
                                }
                                output("</table>",true);

popup_footer();
?> 