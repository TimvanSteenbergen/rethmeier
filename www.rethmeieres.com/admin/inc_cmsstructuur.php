<?php

if (isset($_POST['save'])){

	$array = explode("|",$_POST['destItems']);

	for ($x=1;$x<count($array)-1;$x++){

		$query = "UPDATE tbl_contents SET content_seq = ".$x." WHERE CONTENT_ID = ".$array[$x];

		$save = mysqli_query($query);		

	}

	$to = "cmsartikelen.php";

	if ($_POST['id']) { 

		$to .= "?id=".$_POST['id'];

	}


	echo '<META HTTP-EQUIV="refresh" content="0;URL='.$to.'">';

}

?>



<?php

  if ($action == 'edit' || $action == 'update') {

?>

<script language="javascript"><!--



function check_form() {

  var error = 0;

  var error_message = "<?php echo JS_ERROR; ?>";



  var customers_firstname = document.customers.customers_firstname.value;

  var customers_lastname = document.customers.customers_lastname.value;

<?php if (ACCOUNT_COMPANY == 'true') echo 'var entry_company = document.customers.entry_company.value;' . "\n"; ?>

<?php if (ACCOUNT_DOB == 'true') echo 'var customers_dob = document.customers.customers_dob.value;' . "\n"; ?>

  var customers_email_address = document.customers.customers_email_address.value;

  var entry_street_address = document.customers.entry_street_address.value;

  var entry_postcode = document.customers.entry_postcode.value;

  var entry_city = document.customers.entry_city.value;

  var customers_telephone = document.customers.customers_telephone.value;



<?php if (ACCOUNT_GENDER == 'true') { ?>

  if (document.customers.customers_gender[0].checked || document.customers.customers_gender[1].checked) {

  } else {

    error_message = error_message + "<?php echo JS_GENDER; ?>";

    error = 1;

  }

<?php } ?>



  if (customers_firstname == "" || customers_firstname.length < <?php echo ENTRY_FIRST_NAME_MIN_LENGTH; ?>) {

    error_message = error_message + "<?php echo JS_FIRST_NAME; ?>";

    error = 1;

  }



  if (customers_lastname == "" || customers_lastname.length < <?php echo ENTRY_LAST_NAME_MIN_LENGTH; ?>) {

    error_message = error_message + "<?php echo JS_LAST_NAME; ?>";

    error = 1;

  }



<?php if (ACCOUNT_DOB == 'true') { ?>

  if (customers_dob == "" || customers_dob.length < <?php echo ENTRY_DOB_MIN_LENGTH; ?>) {

    error_message = error_message + "<?php echo JS_DOB; ?>";

    error = 1;

  }

<?php } ?>



  if (customers_email_address == "" || customers_email_address.length < <?php echo ENTRY_EMAIL_ADDRESS_MIN_LENGTH; ?>) {

    error_message = error_message + "<?php echo JS_EMAIL_ADDRESS; ?>";

    error = 1;

  }



  if (entry_street_address == "" || entry_street_address.length < <?php echo ENTRY_STREET_ADDRESS_MIN_LENGTH; ?>) {

    error_message = error_message + "<?php echo JS_ADDRESS; ?>";

    error = 1;

  }



  if (entry_postcode == "" || entry_postcode.length < <?php echo ENTRY_POSTCODE_MIN_LENGTH; ?>) {

    error_message = error_message + "<?php echo JS_POST_CODE; ?>";

    error = 1;

  }



  if (entry_city == "" || entry_city.length < <?php echo ENTRY_CITY_MIN_LENGTH; ?>) {

    error_message = error_message + "<?php echo JS_CITY; ?>";

    error = 1;

  }



<?php

  if (ACCOUNT_STATE == 'true') {

?>

  if (document.customers.elements['entry_state'].type != "hidden") {

    if (document.customers.entry_state.value == '' || document.customers.entry_state.value.length < <?php echo ENTRY_STATE_MIN_LENGTH; ?> ) {

       error_message = error_message + "<?php echo JS_STATE; ?>";

       error = 1;

    }

  }

<?php

  }

?>



  if (document.customers.elements['entry_country_id'].type != "hidden") {

    if (document.customers.entry_country_id.value == 0) {

      error_message = error_message + "<?php echo JS_COUNTRY; ?>";

      error = 1;

    }

  }



  if (customers_telephone == "" || customers_telephone.length < <?php echo ENTRY_TELEPHONE_MIN_LENGTH; ?>) {

    error_message = error_message + "<?php echo JS_TELEPHONE; ?>";

    error = 1;

  }



  if (error == 1) {

    alert(error_message);

    return false;

  } else {

    return true;

  }

}

//--></script>

<?php

  }

?>


<!-- header //-->

<?php require(DIR_WS_INCLUDES . 'header.php'); ?>

<!-- header_eof //-->



<!-- body //-->

<table border="0" width="100%" cellspacing="2" cellpadding="2">

  <tr>

<!-- body_text //-->



    <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">



<?

include ("inc_cmsvolgorde.php");




?>

