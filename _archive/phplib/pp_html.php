<?php
/*
  html.php
  Copyright (C) 2003 by Peter R Jones
*/

/*
  html_head($title)

  Generates a standard HTML file header, using $title for both the HTML title
  and the initial H1 element.  Opens the HTML and BODY element and two DIV
  elements (for CSS purposes.)  Should be followed by the main content of the
  page, and then calls to html_menu() and html_foot().
*/

function html_head1($title="Home Page of Peter Jones",$area="") {
  global $PP_AREA,$BASE;
  global $PP_TITLE;
  $PP_AREA = $area;
  $PP_TITLE = $title;
  echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\" ?>\n";
  echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/strict.dtd\">\n";
  echo "<html xml:lang=\"en\" lang=\"en\" xmlns=\"http://www.w3.org/1999/xhtml\">\n";
  echo "<head>\n";
  echo "<title>Pete's Place :: $title</title>\n";
  echo "<base href=\"$BASE/index.php\" />\n";
  echo "<meta name=\"author\" content=\"Peter Jones\" />\n";
  echo "<meta name=\"ICBM\" content=\"-27.220, 153.075\" />\n";
  echo "<meta name=\"DC.title\" content=\"Pete's Place\" />\n";
  echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"screen\" href=\"style/001/petesplace.css\" />\n";
  echo "<link rel=\"stylesheet\" type=\"text/css\" media=\"print\" href=\"style/print/print.css\" />\n";
  echo "<link rel=\"shortcut icon\" href=\"favicon.ico\" />\n";
  echo "<link rel=\"home\" href=\"index.php\" />\n";
  }

function html_head2() {
  global $PP_TITLE;
  echo "</head>\n\n";
  echo "<body>\n\n";
  echo "<div id=\"Main\">\n";
  echo "<div id=\"Header\">\n";
  echo "<p>Welcome...</p>\n";
  echo "<h1>$PP_TITLE</h1>\n</div>\n\n";
  echo "<div id=\"Content\">\n";
  }

function html_head($title="Home Page of Peter Jones",$area="") {
  html_head1($title,$area);
  html_head2();
  }

/*
  html_menu([$area])

  Generates a navigation menu from database information.  Should only be
  called once per HTML page, after html_head() and before html_foot().
  This function closes the two DIV elements opened by html_head() for CSS.
*/
function html_menu() {
  global $PP_AREA;
  echo "</div>\n</div>\n\n";
  echo "<div id=\"Sidebar\">\n";
  echo "<div id=\"Menu\">\n";
  echo "<a href=\"\">Home</a><br />\n";
  echo "<a href=\"mapping/\">Mapping</a>\n";
  if ($PP_AREA == "mapping") {
    echo "<ul>\n";
    echo "<li><a href=\"mapping/levels.php\">Levels</a></li>\n";
    echo "<li><a href=\"mapping/tutorials.php\">Tutorials</a></li>\n";
    echo "<li><a href=\"mapping/problems.php\">Problems</a></li>\n";
    echo "</ul>\n";
    }
  else echo "<br />\n";
  echo "<a href=\"coding/\">Coding</a>\n";
  if ($PP_AREA == "coding") {
    echo "<ul>\n";
    echo "<li><a href=\"coding/client_fog.php\">Client Fog</a></li>\n";
    echo "<li><a href=\"coding/no_use_through_walls.php\">Button Fix</a></li>\n";
    echo "</ul>\n";
    }
  else echo "<br />\n";
  echo "<a href=\"pp_writing/\">Writing</a>\n";
  if ($PP_AREA == "writing") {
    echo "<ul>\n";
    echo "<li><a href=\"array_wars/\">Array Wars</a></li>\n";
    echo "<li><a href=\"pp_writing/nanowrimo/\">NaNoWriMo</a></li>\n";
    echo "</ul>\n";
    }
  else echo "<br />\n";
  echo "<a href=\"povray/\">RayTracing</a>\n";
  }

/*
  html_foot()

  Inserts page footer, closes the BODY and HTML elements.
*/
function html_foot() {
  html_menu();
  echo "<div id=\"Footer\">\n";
  echo "<a href=\"http://pete-jones.livejournal.com/\">My LiveJournal</a><br />\n";
  echo "<a href=\"http://www.facebook.com/PeteJones.68\">My Facebook</a><br />\n";
  echo "<a href=\"http://twitter.com/darkphoenix_68\">My Twitter</a><br />\n";
  echo "<a href=\"http://www.nanowrimo.org/user/2467\">My NaNoWriMo</a><br />\n";
  echo "<a href=\"http://darkphoenix.deviantart.com/\">My deviantART</a><br />\n";
  echo "<a href=\"http://www.youtube.com/darkphoenix68\">My YouTube</a><br />\n";
  echo "<br />\n<br />\n";
  echo "<p>Copyright &copy; 2004<br />";
  echo "by <a href=\"contact_pete.php\" title=\"Contact Pete\">Peter Jones</a></p>\n";
  echo "<p><span class=\"note\" title=\"since Feb 6, 2006\">This page viewed ".plural(visit_counter(),"time")."</span></p>\n";
  echo "<p><span align=\"center\"><a href=\"http://www.nanowrimo.org/\"><img src=\"image/nano-2008-winner.png\" alt=\"NaNoWriMo 2008 Winner\" style=\"border: solid black 1px\" /></a></span></p>";
  echo "<p><span align=\"center\"><a href=\"array_wars/\"><img src=\"image/nano_2006_winner_small.gif\" alt=\"NaNoWriMo 2006 Winner\" style=\"border: solid black 1px\" /></a></span></p>";
  echo "<p><span align=\"center\"><a href=\"array_wars/\"><img src=\"image/2005_nanowrimo_winner_small.gif\" alt=\"NaNoWriMo 2005 Winner\" style=\"border: solid black 1px\" /></a></span></p>";
  echo "</div>\n</div>\n\n";
  echo "</body>\n";
  echo "</html>\n";
  }

/*
  html_mailto($email,$name)
  Generates a 'mailto' link.
*/
function html_mailto($email,$name) {
  if ($email != "") echo "<a href=\"mailto:$email\">$name</a>";
  else echo "<span class=\"noemail\">$name</span>";
  }

/*
  html_link($url,$name[,$class])
  Generates a link.
*/
function html_hdr($num,$txt,$class="") {
  echo "\n<h$num".($class?" class=\"$class\"":"").">$txt</h$num>\n\n";
  }

function html_link($url,$name,$class="") {
  echo "<a href=\"$url\"".($class?" class=\"$class\"":"").">$name</a>";
  }

function html_tcaption($txt) {
  echo "<caption>$txt</caption>\n";
  }

function html_thead($list,$class="") {
  if (!is_array($list)) die("Function 'html_thead' parameter must be an array!");
  echo "<thead><tr".($class?" class=\"$class\"":"").">";
  while (list($key,$val) = each($list)) {
    echo "<th>$val</th>";
    }
  echo "</tr></thead>\n";
  }

function html_td($txt,$class="",$default="&nbsp;") {
  echo "<td";
  if ($class) echo " class=\"$class\"";
  echo ">";
  echo $txt?$txt:$default;
  echo "</td>";
  }

function html_pp($echo = FALSE) {
  $rv = "<span class=\"petesplace\">Pete's Place</span>";
  if ($echo) echo $rv;
  return $rv;
  }

?>
