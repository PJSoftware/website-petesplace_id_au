<?php
/*
  database.php
  Copyright (C) 2003 by Peter R Jones
  Database class
*/

class Database {
  var $dbLink;
  
  function Database($db) {
    $HOST = "localhost";
    switch($db) {
      case "petesplace" : $USER = "ppaccess"; $PWD = "pp.access.1154"; break;
      default : die("Database::new error, unknown database '$db'");
      }
    $this->dbLink = mysql_connect($HOST,"pete_$USER",$PWD) or die("Database error :: Connecting");
    mysql_select_db("pete_$db") or die("Database error :: Selecting '$db'");
    }
  
  function query($sql,$debug=FALSE) {
    if ($debug) echo "<p class=\"debug\"><strong>DEBUG :: </strong>$sql</p>";
    $qr = mysql_query($sql,$this->dbLink) or die("<p class=\"error\"><strong>MySQL Query failed:</strong><br />$sql</p>");
    return $qr;
    }
  
  }

?>