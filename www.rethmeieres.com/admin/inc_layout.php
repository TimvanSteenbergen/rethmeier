<?

include("fckeditor/fckeditor.php") ;

$oFCKeditor = new FCKeditor('content') ;

$oFCKeditor->Value = stripslashes('test');
//$oFCKeditor1->BasePath = '';
//$oFCKeditor1->CreateFCKeditor('content', '90%', 200 ) ;

$oFCKeditor->Create() ;

?>