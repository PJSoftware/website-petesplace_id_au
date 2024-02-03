<?php
/*
  petesplace.php
  Copyright (C) 2003 by Peter R Jones
  
  Usage:  Files should begin with the following code:
    ini_set('include_path','/home/pete/phplib');
    include('petesplace.php');
    html_head('Title Text');
  and end with:
    html_menu();
    html_foot();
*/

$BASE = "http://".$_SERVER["HTTP_HOST"];
if (!preg_match("/\./",$BASE)) $BASE .= "/petesplace";

include('pp_database.php');
//include('pp_user.php');

include('pp_html.php');
include('pp_misc.php');

$PPDB = new Database("petesplace");
//$USER = new User;

?>