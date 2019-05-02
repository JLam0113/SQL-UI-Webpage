<!DOCTYPE html>
<html>
<head>
<title> CPS510 Assignment 9/10</title>
<link rel="stylesheet" type="text/css" href="style_cps510.css">
<style>
input[type=submit] {
        background: #0066A2;
        color: white;
        border-style: outset;
        border-color: #0066A2;
        height: 50px;
        width: 100%;
        font: bold 15px arial, sans-serif;
        text-shadow:none;
}
textarea {
        width: 100%;
        -webkit-box-sizing: border-box; /* Safari/Chrome, other WebKit */
        -moz-box-sizing: border-box;    /* Firefox, other Gecko */
        box-sizing: border-box;
        height: 160px;
        border: 3px solid #cccccc;
        padding: 5px;
        font-family: Tahoma, sans-serif;
        background-image: url(bg.gif);
        background-position: bottom right;
        background-repeat: no-repeat;
        resize: none;
}
</style>
</head>
<body>

  <?php
  $conn = oci_connect('username','MMDDXXXX','(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(Host=oracle.scs.ryerson.ca)(Port=1521))(CONNECT_DATA=(SID=orcl)))');
  if (!$conn) {
      $e = oci_error();
      trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
  }
  ?>

<div class="relative">
    <img class="title" src="./code_img.jpg" alt="code" style="width:100%">
    <div class="text-block">
      <h1 class="title">CPS 510</h1>
      <br>
      <h2>Section 3 | Group 3</h2>
    </div>

    <div class="text-block-lower">
      <h1>Oracle Database GUI</h1>
      <br>
      <br>
      <hr style="margin-left: 30%; margin-right:30%;">
      <br>

    </div>
  </div>


  <div class="card">

    <div style="height:20%; margin: 6%">
    <div style="float:left; width: 30%">
      <br>
      <br>
      <img src="https://static.thenounproject.com/png/1218875-200.png" style="width: 100%" alt="edit">
    </div>

    <div style="float:right; width: 65%; margin: 2%">
      <h3> Enter the SQL commands to either CREATE/DELETE a table, or INSERT data into a table.</h3>
      <br>
      <form method="POST" id="usrform">
      <textarea rows="6" cols="50" name="table" form="usrform" placeholder="Enter text here..."></textarea><br>
      <input type="submit" name="sTable"></form><br>
    </div>
    </div>

<div class="space" style="clear:left"></div>
<hr style="margin-left: 10%; margin-right:10%;">

    <div style="height:20%; margin: 6%">
    <div style="float:left; width: 30%">
      <br>
      <br>
      <img src="https://static.thenounproject.com/png/2057262-200.png" style="width: 100%" alt="edit">
    </div>

    <div style="float:right; width: 65%; margin: 2%">
      <h3> Enter the SQL Query you would like to run.</h3>
      <br>
      <form method="POST" id="usrform2">
      <textarea rows="6" cols="50" name="queries" form="usrform2" placeholder="Enter text here..."></textarea><br>
      <input type="submit" name="sQuery"></form><br>
    </div>
    </div>

    <div class="smallspace" style="clear:left"></div>
    <hr style="margin-left: 10%; margin-right:10%;">
    <div class="smallspace" style="clear:left"></div>

    <div style="margin-left:20%; margin-right:20%">
    <form method="POST">
    <h3 style="text-align: center"> Click the button below to show existing tables.</h3><br>
    <input type="submit" name="show" value="Show Tables"></form><br>
  </div>

  <div class="smallspace" style="clear:left"></div>
  <hr style="margin-left: 10%; margin-right:10%;">
  <div class="smallspace" style="clear:left"></div>

  <div style="margin-left:20%; margin-right:20%">
  <form method="POST">
  <h3 style="text-align: center"> Enter the table name to view all the existing data in that table.</h3><br>
  <input type="text" name="table2" style="width:100%; height:50px; font-size: 1.6em">
  <input type="submit" name="view" value="Submit">
  </form><br>
  </div>

  <div class="smallspace" style="clear:left"></div>
  <hr style="margin-left: 10%; margin-right:10%;">
  <div class="smallspace" style="clear:left"></div>

  <div style="text-align:center">

<?php
  if(isset($_POST['sTable'])){
  $sql = $_POST['table'];
  $stid = oci_parse($conn, $sql);
  $execute = oci_execute($stid);
  if($execute){
  oci_commit($conn);
  echo "\"";
  echo $sql;
  echo "\" was successful!";
  }else{
  oci_rollback($conn);
  $e = oci_error($stid);
  echo "<p>Error [".$e['message']."]</p>";
  }
  oci_close($conn);
  }
  if(isset($_POST['sQuery'])){
  $query = $_POST['queries'];
  $stid = oci_parse($conn, $query);
  if (!$stid) {
      $e = oci_error($conn);
      trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
  }
  $execute = oci_execute($stid);
  if(!$execute){
     $e = oci_error($stid);
      trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
  }
  echo $sql;
  $header = false;
  print "<table border=\'1\' style=\"border-style: solid; border-collapse: collapse; box-shadow: -3px 3px 10px 1px #C0C0C0;\" align=\'center\'>\n";
  while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
  if ($header == false) {
          print '<thead><tr>';
          foreach (array_keys($row) as $key) {
              print '<th>'.($key !== null ? htmlentities($key, ENT_QUOTES) :
                      '').'</th>';
          }
          print '</tr></thead>';
          $header = true;
      }
      print "<tr>\n";
      foreach ($row as $item) {
          print "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
      }
      print "</tr>\n";
  }
  print "</table>\n";
  oci_free_statement($stid);
  oci_close($conn);
  }
  if(isset($_POST['show'])){
  $show = "SELECT table_name FROM user_tables";
  $stid = oci_parse($conn, $show);
  if (!$stid) {
      $e = oci_error($conn);
      trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
  }
  $execute = oci_execute($stid);
  if(!$execute){
     $e = oci_error($stid);
      trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
  }
      echo "<h3 style=\"text-align:center\">Here is the list of tables that currently exist in the database.</h3>";
      echo "<div class=\"smallspace\" style=\"clear:left\"></div>";
  while (($row = oci_fetch_row($stid)) != false) {
      echo "<h4 style=\"color:#444\">" . $row[0] . " " . $row[1] . "</h4>" . "<br>\n";
  }
  oci_free_statement($stid);
  oci_close($conn);
  }
  if(isset($_POST['view'])){
  $tableName = $_POST['table2'];
  $sql = "SELECT * FROM ".$tableName."";
  $stid = oci_parse($conn, $sql);
  if (!$stid) {
      $e = oci_error($conn);
      trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
  }
  $execute = oci_execute($stid);
  if(!$execute){
     $e = oci_error($stid);
      trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
  }
  echo "<h3>Here is the data from table: <h3>";
  echo "<h3>".$tableName."<h3>";
  echo "<div class=\"smallspace\" style=\"clear:left\"></div>";
  $header = false;
  print "<table border=\'1\' style=\"font-size: 0.7em; border-style: solid; border-collapse: collapse; box-shadow: -3px 3px 10px 1px #C0C0C0; margin-right: auto; margin-left: auto\" align=\'center\'>\n";
  while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
  if ($header == false) {
          print '<thead><tr>';
          foreach (array_keys($row) as $key) {
              print '<th>'.($key !== null ? htmlentities($key, ENT_QUOTES) :
                      '').'</th>';
          }
          print '</tr></thead>';
          $header = true;
      }
      print "<tr>\n";
      foreach ($row as $item) {
          print "    <td>" . ($item !== null ? htmlentities($item, ENT_QUOTES) : "&nbsp;") . "</td>\n";
      }
      print "</tr>\n";
  }
  print "</table>\n";
  oci_free_statement($stid);
  oci_close($conn);
  }
?>
</div>
</div>

<div class="relative">
    <img class="bottom" src="./code_img.jpg" alt="code" style="width:100%;">
    <div class="text-block-bottom">
      <h4 class="credits">Created by: Jessye Lam, Christopher Seow, <br>Danny Wang & Christopher Gruca </h4>
      <br>
    </div>
  </div>

</body>
</html>
