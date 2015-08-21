<?
echo "<span class=\"pageHeading\">Tekst animaties</span><BR><BR>";

$_GET['id'] = 1;
$form = new FormHandler;
$form->PermitEdit();
$form->TableSettings('100%', 0, 1, 2, 'font-family:Verdana;font-size:10px;');

//$form->AddHTML($myhtml);

// set the database data (data from another file or so ? ) 
$form->UseDatabase(DB_DATABASE, "teksten");

//$products = $GLOBALS["products"]->contents();

//$form->AddHTML("Nederlands<br/><br/>");

$form->textfield("Nederlandse Promotekst 1", "TEXT1", "", "90");

$form->textfield("Nederlandse Promotekst 2", "TEXT2", "", "90");

$form->textfield("Nederlandse Promotekst 3", "TEXT3", "", "90");

$form->textfield("Nederlandse Promotekst 4", "TEXT4", "", "90");

$form->textfield("Nederlandse Promotekst 5", "TEXT5", "", "90");

$form->textfield("Nederlandse Promotekst 6", "TEXT6", "", "90");

$form->textfield("Nederlandse Promotekst 7", "TEXT7", "", "90");

$form->textfield("Nederlandse Promotekst 8", "TEXT8", "", "90");

$form->textfield("Nederlandse Promotekst 9", "TEXT9", "", "90");

$form->textfield("Nederlandse Promotekst 10", "TEXT10", "", "90");

//$form->AddHTML("Engels<br/><br/>");

$form->textfield("Engelse Promotekst 1", "EN1", "", "90");

$form->textfield("Engelse Promotekst 2", "EN2", "", "90");

$form->textfield("Engelse Promotekst 3", "EN3", "", "90");

$form->textfield("Engelse Promotekst 4", "EN4", "", "90");

$form->textfield("Engelse Promotekst 5", "EN5", "", "90");

$form->textfield("Engelse Promotekst 6", "EN6", "", "90");

$form->textfield("Engelse Promotekst 7", "EN7", "", "90");

$form->textfield("Engelse Promotekst 8", "EN8", "", "90");

$form->textfield("Engelse Promotekst 9", "EN9", "", "90");

$form->textfield("Engelse Promotekst 10", "EN10", "", "90");




$form->SubmitBtn("Opslaan", false, "Annuleren");

$form->OnSaved("doRun");

// flush the form
$form->FlushForm();


function doRun($id, $data) 
{

   
	return "<META HTTP-EQUIV=\"Refresh\" Content= \"0; URL=\"index.php?page=teksten&selected_box=CMS"."\">";

}
?>