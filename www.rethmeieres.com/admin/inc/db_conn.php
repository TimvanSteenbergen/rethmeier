<?php
include ("globals.php");

$dbcnx = @mysqli_connect("84.241.129.5", "pidea", "!!P-idea!#"); 
if (!$dbcnx) { 
   echo( "<p>Unable to connect to the database server at this time.</p>" ); 
exit(); 
} 
mysqli_select_db("rethmeier", $dbcnx);
if (! @mysqli_select_db("rethmeier") ) {  
   echo( "<p>Unable to locate the database at this time.</p>" ); 
exit();  
} 
?>