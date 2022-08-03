<?php
require_once "common.php";
page_header("Schiff nach Symia");

    output("Du betrittst das Schiff nach Symia und wartest darauf, daß das Schiff endlich ablegt.");
    addnav ("Nach Symia","necron_hafen.php");
$session[user][gold]-=3;
page_footer();
?> 