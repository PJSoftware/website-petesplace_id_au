<?php
/*
  misc.php
  Copyright (C) 2003 by Peter R Jones
*/

function plural($num,$txt,$ptxt="") {
  if ($num==1) $rv = "1 $txt";
  elseif ($ptxt) $rv = "$num $ptxt";
  else $rv = "$num $txt"."s";
  return $rv;
  }

function emoticon($str) {
  switch ($str) {
    case ":-)" : $title = "Smile"; break;
    case ";-)" : $title = "Wink"; break;
    }
  echo "<span class=\"emoticon\" title=\"$title\">$str</span>";
  }

function download_counter($file) {
  global $PPDB;
  $qr = $PPDB->query("SELECT count(*) FROM download_log WHERE file = '$file'");
  list($num) = mysql_fetch_row($qr);
  return $num;
  }

function visit_counter() {
  global $PPDB;
  $uri = $_SERVER["SCRIPT_NAME"];
  $qr = $PPDB->query("SELECT num FROM visit_counter WHERE uri = '$uri'");
  if (mysql_num_rows($qr)==0) {
    $PPDB->query("INSERT visit_counter VALUES ('$uri',1)");
    $num = 1;
    }
  else {
    list($num) = mysql_fetch_row($qr);
    $PPDB->query("UPDATE visit_counter SET num = num+1 WHERE uri = '$uri'");
    $num++;
    }
  return $num;
  }

function download_link($dir,$file,$ext,$issig=FALSE,$ismd5=FALSE,$isTbl=FALSE) {
  $key = "$dir/$file.$ext";
  if ($isTbl) {
    $td1 = "<td>";
    $td2 = "</td>";
    }
  else {
    $td1 = "";
    $td2 = " ";
    }
  $str = "$td1<a href=\"download/$key\">$file.$ext</a>$td2";
  $str .= "$td1<strong>(".plural(download_counter($key),"download").")</strong>$td2";
  if ($issig || $ismd5) {
    $str .= $td1."[";
    if ($issig) { $str .= "<a href=\"download/$dir/$file.sig\">sig</a>"; }
    if ($ismd5) {
      if ($issig) { $str .= "|"; }
      $str .= "<a href=\"download/$dir/$file.md5\">md5</a>";
      }
    $str .= "]$td2";
    }
  return $str;
  }

?>
