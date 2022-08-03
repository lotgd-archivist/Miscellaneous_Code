<?php
require_once "common.php";

$picpfad = 'avatare/neu/';

$URL = 'http://arda-logd.com/avatare/neu';

$erlaubte_uploads = 1;

$max_upl_size = 2000;

$arr_erlaube_dateityp = array(jpg, gif, png);

echo $output = preg_replace("/<__(\w.+?)__>/e", "\$\\1", tparser("uploadheader.html"));

if (!isset($_POST['PICUPLOAD'])) {

    uploadformausgabe();
}

else {

    $erlaubte_extensionen = join(', ', $arr_erlaube_dateityp);

    for ($i = 0; $i < count($_FILES['UPFILE']['name']); $i++) {

        if ($_FILES['UPFILE']['name'][$i] == '') {

            continue;
        }

        $File = strtr(stripslashes($_FILES['UPFILE']['name'][$i]), '\'" |\\/?!*:#', '___________');

        $punkt = strrpos($File, '.');

        $laenge = strlen($File);

        $endung = strtolower(substr($File, -($laenge - $punkt - 1)));

        $dname = ("avatar-" . $session[user][login] . ".");

        if (!in_array($endung, $arr_erlaube_dateityp)) {

            fehlerausgabe("Die Dateiendung <b>$endung</b> ist nicht erlaubt");

            continue;
        }

        if ($_FILES['UPFILE']['size'][$i] > ($max_upl_size * 10000)) {

            $zugross = round($_FILES['UPFILE']['size'][$i] / 10000, 2);

            fehlerausgabe("Datei zu gro&szlig;");

            continue;
        }

        if (file_exists("avatare/neu/avatar-" . $session[user][login] . ".jpg")) {
            unlink("avatare/neu/avatar-" . $session[user][login] . ".jpg");
        }
        elseif (file_exists("avatare/neu/avatar-" . $session[user][login] . ".gif")) {
            unlink("avatare/neu/avatar-" . $session[user][login] . ".gif");
        }
        elseif (file_exists("avatare/neu/avatar-" . $session[user][login] . ".png")) {
            unlink("avatare/neu/avatar-" . $session[user][login] . ".png");
        }

        if (file_exists("avatare/avatar-" . $session[user][login] . ".jpg")) {
            db_query("UPDATE accounts set avatar = '' WHERE acctid='" . $session[user][acctid] . "'");
            unlink("avatare/avatar-" . $session[user][login] . ".jpg");
        }
        elseif (file_exists("avatare/avatar-" . $session[user][login] . ".gif")) {
            db_query("UPDATE accounts set avatar = '' WHERE acctid='" . $session[user][acctid] . "'");
            unlink("avatare/avatar-" . $session[user][login] . ".gif");
        }
        elseif (file_exists("avatare/avatar-" . $session[user][login] . ".png")) {
            db_query("UPDATE accounts set avatar = '' WHERE acctid='" . $session[user][acctid] . "'");
            unlink("avatare/avatar-" . $session[user][login] . ".png");
        }

        switch ($_FILES ['UPFILE'] ['error'] [$i]) {

            case 0 :
                break;

            case 1 :
                fehlerausgabe("Die Datei ist zu gross");

                continue 2;

                break;

            case 2 :
                fehlerausgabe("Die Dateigr&ouml;&szlig;e  &uuml;bersteigt das erlaubte Limit von $max_upl_size KB");

                continue 2;

                break;
        }

        $dateipfad_name = $picpfad . $File;

        $rand_value = randomstring($File);

        $newfilenamekompl = "$dname$endung";

        $savename = $picpfad . $newfilenamekompl;

        if (@move_uploaded_file($_FILES['UPFILE']['tmp_name'][$i], $savename)) {

            if (isset($arr_erlaube_dateityp) and in_array($endung, $arr_erlaube_dateityp)) {

                $show_uploadokfiles .= "<b>$File</b> gespeichert als: <br><a href=\"$URL/$newfilenamekompl\" target=\"_blank\">$newfilenamekompl</a><br>UPDATE accounts set avatar = '' WHERE acctid='" . $session[user][acctid] . "'";
            }

            chmod($savename, 0777);
            $sql = "INSERT INTO petitions (author,date,body,pageinfo) VALUES (" . ( int )$session[user][acctid] . ",now(),\"Avatar bitte pruefen\",\"-\")";
            db_query($sql);
        }
        else {

            fehlerausgabe("Kann Datei nicht speichern");
        }
    }

    if (isset($errors) and count($errors) > 0) {

        Uploadfehler($errors);
    }

    if ($show_uploadokfiles != '') {

        echo preg_replace("/<__(\w.+?)__>/e", "\$\\1", tparser("uploadok.html"));
    }

    if (!file_exists($picpfad)) {

        echo "<b>Zielverzeichnis $picpfad nicht gefunden</b><br>";
    }
    elseif (!is_writable($picpfad)) {

        echo "<b>Zielverzeichnis $picpfad nicht beschreibar</b><br>";

        clearstatcache();
    }
    else {
    }
}

echo $output = preg_replace("/<__(\w.+?)__>/e", "\$\\1"/*, tparser("uploadfooter.html")*/);
function randomstring($dateiname) {
    $dateiname = strtolower(substr(md5(microtime()), 0, 25));

    return $dateiname;
}

function uploadformausgabe() {
    global $erlaubte_uploads, $zeige_uploadrechte, $max_upl_size;

    if ($_SERVER['QUERY_STRING'] !== '') {

        $action = $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
    }
    else {

        $action = $_SERVER['PHP_SELF'];
    }

    $max_upl_size_out = $max_upl_size * 10000;

    $dateicount = 0;

    for ($i = 0; $i < $erlaubte_uploads; $i++) {

        $dateicount++;

        $uploadrowout .= preg_replace("/<__(\w.+?)__>/e", "\$\\1", tparser("uploadfilerow.html"));
    }

    $show_uploadlimitationen = zeige_uploadlimits();

    $uploadformhtml = preg_replace("/<__(\w.+?)__>/e", "\$\\1", tparser("uploadtable.html"));

    echo $uploadformhtml;
}

function Uploadfehler($errors) {
    foreach ($errors as $file => $message) {

        $show_noupload .= "<li> $file: $message.<br>";
    }

    $show_uploadlimitationen = zeige_uploadlimits();

    echo preg_replace("/<__(\w.+?)__>/e", "\$\\1", tparser("uploadfehler.html"));
}

function zeige_uploadlimits() {
    global $arr_erlaube_dateityp, $max_upl_size;

    $erlaubte_extensionen = join(', ', $arr_erlaube_dateityp);

    if ($max_upl_size > 10000) {

        $maximum = round(($max_upl_size / 10000), 2) . "MB";
    }
    else {

        $maximum = "$max_upl_size KB";
    }

    return "<b>Dateigr&ouml;&szlig;enlimit</b>: $maximum<br><b>Erlaubte Dateiendungen</b>: $erlaubte_extensionen";
}

function fehlerausgabe($message, $delete = false) {
    global $errors, $File, $dateipfad_name;

    $errors[$File] = $message;

    if ($delete == true) {

        unlink($dateipfad_name);
    }
}

?> 