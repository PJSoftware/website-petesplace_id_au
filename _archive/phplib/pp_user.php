<?php
/*
  user.php
  Copyright (C) 2003 by Peter R Jones
  user class
  
  ************************************************************************
  
  March 2005, the decision was made that I should be the only valid user.
  Decisions change, so most of the code remains intact, only the register()
  and activate() functions have been disabled...
  
  If this decision becomes permanent, much of this code could be streamlined!
  
  Any CSS-changing code should therefore require cookies to store preferences.
*/

class User {
  // Values from database
  var $username = "guest";      // indexed column of database
  var $name = "Guest";          // user's real name, information only
  var $CSS = "001";             // which css file does the user prefer?
  var $admin = -1;              // administrative level
  var $admindesc = "";          // admin level description
  var $lastlogin = FALSE;       // date of last login
  var $lastaccess = FALSE;      // date of last access
  var $vis = 'Visible';                 // visibility
  // Values derived from database or otherwise determined
  var $known = FALSE;           // is user a known member?
  var $readonly = TRUE;         // FALSE for browsing (current logged in) user, TRUE for all others
  
  /* User constructor -- determine whether a user is logged in */
  function User($user=FALSE) {
    global $_COOKIE;
    global $PPDB;
    if ($user) {
      $this->readonly = TRUE;
      $this->username = $user;
      }
    else {
      $this->readonly = FALSE;
      if ($_COOKIE["userdata"]) {
        list($username,$userhash) = preg_split("/:/",$_COOKIE["userdata"]);
        $this->username = $username;
        if ($userhash == $this->hash()) $this->known = TRUE;
        else $this->logout();
        }
      }
    if ($this->username == "guest" && !$this->readonly) $this->logout();
    else $this->initialise();
    }
  
  /* Retrieve details of known user, update last access details */
  function initialise($login=FALSE) {
    global $PPDB;
    if (!$this->readonly) {
      if ($login) $PPDB->query("UPDATE user SET last_login = now() WHERE username = '$this->username'");
      $ip = $_SERVER["REMOTE_ADDR"];
      $ua = $_SERVER["HTTP_USER_AGENT"];
      $PPDB->query("UPDATE user SET last_access = now(),ip = '$ip',ua = '$ua' WHERE username = '$this->username'");
      }
    $qr = $PPDB->query("SELECT username,name,css,admin,last_login,last_access,visibility FROM user ".
      "WHERE username = '$this->username'");
    if (mysql_num_rows($qr)==0) $this->known = FALSE;
    else {
      list($this->username,$this->name,$this->CSS,$this->admin,$this->lastlogin,$this->lastaccess,$this->vis) 
        = mysql_fetch_row($qr);
      $this->known = TRUE;
      $qr = $PPDB->query("SELECT description FROM admin WHERE admin = $this->admin");
      list($this->admindesc) = mysql_fetch_row($qr);
      }
    }
  
  /* Login attempt */
  function login($username,$password) {
    global $PPDB;
    if ($this->readonly) { echo "Severe code error!"; die(-1); }        //  should not be attempting this?? 
    $sql = "SELECT admin FROM user WHERE username = '$username' AND password = password('$password')";
    $qr = $PPDB->query($sql);
    if (list($admin) = mysql_fetch_row($qr)) {
      if ($admin >= 0) {
        $rv = 0;
        $this->username = $username;
        $this->known = TRUE;
        $cstr = $username . ":" . $this->hash();
        setcookie("userdata",$cstr,0,"/");
        $this->initialise(TRUE);
        }
      else $rv = -1;    //  known user, access blocked 
      }
    else $rv = -2;      //  unknown user/password 
    if ($rv < 0) $this->logout();
    return $rv;
    }
  
  /* logout user */
  function logout() {
    if ($this->readonly) { echo "Severe code error!"; die(-1); }        //  should not be attempting this?? 
    $this->username = "guest";
    $this->name = "Guest";
    $this->CSS = "001";
    $this->admin = -1;
    $this->known = FALSE;
    setcookie("userdata","",time()-3600,"/");
    }
  
  /* handle user registration attempt */
  /* DISABLED.  If ever reinstated, change $PPDB to $PPDB!
  
  function register($user,$pass,$pass2,$name,$email) {
    global $PPDB;
    global $_SERVER;
    if ($this->readonly) { echo "Severe code error!"; die(-1); }        //  should not be attempting this?? 
    if ($user == "") return -2;         //  no username specified 
    if (strlen($pass) < 6) return -3;   //  password specified is too short 
    if ($pass != $pass2) return -4;     //  password not confirmed 
    if ($name == "") return -5;         //  no name specified 
    if ($email == "") return -6;        //  no email specified 
    $qr = $PPDB->query("SELECT username FROM user WHERE username = '$user'");
    if (mysql_num_rows($qr) == 0) {
      $salt1 = date("H");
      $salt2 = date("i");
      $salt3 = date("s");
      $code = crypt($user,$salt1).crypt($name,$salt2).crypt($email,$salt3);
      $code .= crypt("*$salt1$salt2$salt3*","pp");
      $code = preg_replace("/\./","d",$code);
      $code = preg_replace("/\//","s",$code);
      $code = preg_replace("/\\\\/","b",$code);
      $code = preg_replace("/\?/","q",$code);
      $code = preg_replace("/\&/","a",$code);
      include('user.activationletter.php');
      if (activationletter($email,$user,$pass,$name,$code)) {
        $ip = $_SERVER["REMOTE_ADDR"];
        $ua = $_SERVER["HTTP_USER_AGENT"];
        $sql = "INSERT user (username,password,name,email,admin,ip,ua) " .
          "VALUES ('$user',password('$pass'),'$name','$email',-1,'$ip','$ua')";
        $PPDB->query($sql);
        $PPDB->query("INSERT pending (user,registered,code) VALUES ('$user',now(),'$code')");
        }
      else $rv = -7;    //  error sending activation letter 
      }
    else $rv = -1;      //  User already exists in the system 
    return $rv;
    }
  */
  
  /* activate new account */
  /* DISABLED.  If ever reinstated, change $PPDB to $PPDB!
  
  function activate($user,$code) {
    global $PPDB;
    if ($this->readonly) { echo "Severe code error!"; die(-1); }        //  should not be attempting this?? 
    $qr = $PPDB->query("SELECT code FROM pending WHERE user = '$user'");
    if (mysql_num_rows($qr) == 1) {
      list($ucode) = mysql_fetch_row($qr);
      if ($ucode == $code) {
        $rv = 0;        //  Account activated 
        $PPDB->query("UPDATE user SET admin = 0 WHERE username = '$user'");
        $PPDB->query("DELETE FROM pending WHERE user = '$user'");
        }
      else $rv = -2;    //  Incorrect code supplied 
      }
    else {
      $qr = $PPDB->query("SELECT name FROM user WHERE username = '$user'");
      if (mysql_num_rows($qr) == 1) $rv = -1;   //  Account already active 
      else $rv = -3;    //  Unknown account 
      }
    return $rv;
    }
  */
  
  /* Internal use only: hash username for storage in cookie */
  function hash() {
    return crypt($this->username,"PP");
    }
  
  /* return fully styled username HTML/link */
  function alink($print=FALSE) {
    $rv = "<span class=\"username\" title=\"$this->name\">";
    $rv .= "<a href=\"user/";
    if ($this->readonly) $rv .= "?showuser=$this->username";
    $rv .= "\">$this->username</a>";
    $rv .= "</span>";
    if ($print) echo $rv;
    return $rv;
    }
  
  }

?>
