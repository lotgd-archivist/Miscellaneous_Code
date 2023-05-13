
<?PHP
  $hostname = "localhost";      // Standard
  $database = "db00083623"; // ändern
  $username = "dbo00083623";   // ändern
  $password = "8lrBBoRB";       // ändern
 
  mysql_connect($hostname, $username, $password);
  mysql_query("ALTER DATABASE $database DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");

  $res = mysql_query("SHOW TABLES FROM $database");
  while($row = mysql_fetch_row($res))
  {
      $query = "ALTER TABLE {$database}.`{$row[0]}` CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci";
      mysql_query($query);

      $query = "ALTER TABLE {$database}.`{$row[0]}` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
      mysql_query($query);
  }


