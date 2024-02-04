<html>
  <head>
    <style>
table {
  border-collapse: collapse;
  margin: 25px auto;
  font-size: 0.9em;
  font-family: sans-serif;
  min-width: 400px;
  -webkit-box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
          box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
}

table thead tr {
  background-color: #009879;
  color: #ffffff;
  text-align: left;
}

th,
td {
  padding: 12px 15px;
}

tbody tr {
  border-bottom: 1px solid #dddddd;
}

tbody tr:nth-of-type(even) {
  background-color: #f3f3f3;
}

tbody tr:last-of-type {
  border-bottom: 2px solid #009879;
}

.subtitle {
  font-size: 0.8em;
}

/*# sourceMappingURL=books.css.map */
</style>
</head>

<body>
<?php

$host = 'db';
$user = 'pete_library';
$pwd = 'nJkcCZrjLX9DW9_KghbQTLmKDoht!m6W';
$dbase = 'pete_library';
$library = new mysqli($host, $user, $pwd, $dbase);
if ($library->connect_errno) {
  echo "Failed to connect to MySQL: " . $library->connect_error;
  exit();
}

function series_number_format($num) {
  return preg_replace("/[.]00/","",$num);
}

function title_format($title,$sub = false) {
  $rv = $title;
  $split = explode("@", $title);
  if (count($split) == 2) {
    $rv = "$split[1] $split[0]";
  }
  if ($sub) {
    $rv .= "<br/><span class=\"subtitle\">$sub</span>";
  }
  return $rv;
}

function author_string($library,$book_id) {
  $query = "SELECT role, given_name, family_name ".
    "FROM person,book_by ".
    "WHERE book_by.book_id = $book_id ".
      "AND person.ID = book_by.person_id ".
      "AND role = 'author' ".
    "ORDER BY book_by.sort_by";
  $result = $library->query($query);

  if ($result == FALSE) {
    return "--- query failed ($query) ---";
  }

  $rv = "";
  while ($person = $result->fetch_assoc()) {
    if ($rv > "") {
      $rv .= " &<br/>";
    }
    $rv .= "$person[given_name] $person[family_name]";
  }
  return $rv;
}

function series_string($library,$book_id) {
  $query = "SELECT series, number, parent ".
    "FROM series,series_book ".
    "WHERE series_book.book_id = $book_id ".
      "AND series.ID = series_book.series_id ".
    "ORDER BY series";
  $result = $library->query($query);

  if ($result == FALSE) {
    return "--- query failed ($query) ---";
  }

  $rv = "";
  while ($series = $result->fetch_assoc()) {
    if ($rv > "") {
      $rv .= " & ";
    }
    $series_name = series_name($library,$series['series'],$series['parent']);
    $rv .= "$series_name";
    if ($series['number']) {
      $rv .= " (#".series_number_format($series['number']).")";
    }
  }
  if ($rv == "") {
    $rv = "---"; 
  }
  return $rv;
}

function series_name($library,$name,$parent) {
  $name = title_format($name);
  if (!$parent) { return $name; }
  $result = $library->query("SELECT series FROM series WHERE ID = $parent");
  $parent = $result->fetch_assoc();
  return title_format($parent['series']) . ": $name";
}

function book_read($library,$book_id) {
  $result = $library->query("SELECT date_started,date_finished FROM book_read WHERE book_id = $book_id");
  $read = $result->fetch_assoc();
  if ($read['date_finished']) {
    return "<span title=\"$read[date_finished]\">✔️</span>";
  }
  if ($read['date_started']) {
    return "<span title=\"Reading now...\">📖</span>";
  }
  return "❌";
}

$table_name = 'book';

$query = "SELECT * FROM $table_name ORDER BY title, author_ident";

$books = $library->query($query);

?>

<table>
  <thead><tr>
    <th>Title</th>
    <th>Author</th>
    <th>Series</th>
    <th>Read</th>
</tr></thead>
<?php
while ($book = $books->fetch_assoc()) {
  echo "<tr>";
  echo "<td>".title_format($book['title'],$book['subtitle'])."</td>";
  echo "<td>".author_string($library,$book['ID'])."</td>";
  echo "<td>".series_string($library,$book['ID'])."</td>";
  echo "<td>".book_read($library,$book['ID'])."</td>";
  echo "</tr>";
}
?>
</table>
</body>
</html>
