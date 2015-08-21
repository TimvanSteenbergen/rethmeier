<?php
// include the class
//include("includes/classes/class.FormHandler.php");
include("style.php");
 

// make a new $form object
$form = new FormHandler;

// permit editing
$form->PermitEdit();
echo "<span class=\"pageHeading\">Administratie gebruiker toevoegen</span><BR><BR>";
$form->TableSettings('100%', 0, 1, 2, 'font-family:Verdana;font-size:10px;border: 1px solid grey');

//$form->AddHTML($myhtml);

// set the database data (data from another file or so ? ) 
$form->UseDatabase(DB_DATABASE, "admin_users");
$form->textfield("Naam", "USER_NAME", "" , "50");
$form->textfield("Loginnaam", "USER_LOGIN", "" , "50");
$form->textfield("Wachtwoord", "USER_PASS", "" , "50");
$form->textfield("E-mail", "USER_EMAIL", "" , "50");
$form->hiddenfield("USER_IP", $_SERVER['REMOTE_ADDR']);

// button beneath it (submit, reset and cancel!)
$form->SubmitBtn("Opslaan", false, "Annuleren");

// what to do after saving ?
$form->OnSaved("doRun");

// flush the form
$form->FlushForm();

// 'commit after form' function 
function doRun($id, $data) {
	return "<META HTTP-EQUIV=\"Refresh\" Content= \"0; URL=index.php?page=adminusers\">";
}

?>
 
