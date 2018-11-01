<?php
session_start();

//standaard taal is:
$default_language = "nl";

include "functions/selection.php";
include "translation/inc_translation.php";
echo('érvoor'.$_SESSION['language']);
include "config/inc_language.php";
echo('erna'.$_SESSION['language']);
include "config/inc_config.php";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>Rethmeier Executive Search</TITLE>
<META NAME="Author" CONTENT="Martin Wallink - siibae TV BV">
<link rel="stylesheet" type="text/css" href="style.css">
<script language="javascript">AC_FL_RunContent = 0;</script>
<script src="js/AC_RunActiveContent.js" language="javascript"></script>
<script src="js/ajax.js" language="javascript"></script>
<meta name="verify-v1" content="xOtCGhpraynaIxHC3+Hs2E7PtKfRfYUWErNqvkheDTY=" />
</HEAD>
<BODY>
<div id="back">
</div>
<div id="wrapper">
	<div id="refresh">
		<?php
			include 'ajax/inc_refresh.php';
		?>
	</div>
	<div id="right">
		<?php
			include 'inc_right.php';
		?>
	</div>

</div>

</BODY>
</HTML>