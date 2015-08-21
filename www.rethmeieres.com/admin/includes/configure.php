<?php
// Define the webserver and path parameters
// * DIR_FS_* = Filesystem directories (local/physical)
// * DIR_WS_* = Webserver directories (virtual/URL)
  	define('HTTP_SERVER', 'http://'.$_SERVER["HTTP_HOST"]); // eg, http://localhost - should not be empty for productive servers
  	define('HTTP_CATALOG_SERVER', 'http://'.$_SERVER["HTTP_HOST"]);
  	define('HTTPS_CATALOG_SERVER', '');
  	define('ENABLE_SSL_CATALOG', 'false'); // secure webserver for catalog module
  	define('DIR_FS_DOCUMENT_ROOT', '/'.$_SERVER["HTTP_HOST"].'/www/'); // where the pages are located on the server
  	define('DIR_WS_ADMIN', '/admin/'); // absolute path required
  	define('DIR_WS_CATALOG', '/'); // absolute path required  	
  	
//  	define('DIR_FS_ADMIN', 'W:/filmclub.odmedia.nl/www/admin/'); // absolute pate required
  	define('DIR_FS_ADMIN', '/storage/mijndomein/users/143904/public/sites/www.rethmeieres.com/'); // absolute pate required
//  	define('DIR_FS_CATALOG', 'W:/filmclub.odmedia.nl/www/'); // absolute path required
  	define('DIR_FS_CATALOG', '/storage/mijndomein/users/143904/public/sites/www.rethmeieres.com/'); // absolute path required
  	
  	define('DIR_WS_IMAGES', 'images/');
   	define('DIR_WS_ICONS', DIR_WS_IMAGES . 'icons/');
  	define('DIR_WS_CATALOG_IMAGES', DIR_WS_CATALOG . 'images/');
  	define('DIR_WS_INCLUDES', 'includes/');
  	define('DIR_WS_BOXES', DIR_WS_INCLUDES . 'boxes/');
  	define('DIR_WS_FUNCTIONS', DIR_WS_INCLUDES . 'functions/');
  	define('DIR_WS_CLASSES', DIR_WS_INCLUDES . 'classes/');
  	define('DIR_WS_MODULES', DIR_WS_INCLUDES . 'modules/');
  	define('DIR_WS_LANGUAGES', DIR_WS_INCLUDES . 'languages/');
  	define('DIR_WS_CATALOG_LANGUAGES', DIR_WS_CATALOG . 'includes/languages/');
  	define('DIR_FS_CATALOG_LANGUAGES', DIR_FS_CATALOG . 'includes/languages/');
  	define('DIR_FS_CATALOG_IMAGES', DIR_FS_CATALOG . 'images/');
  	define('DIR_FS_CATALOG_MODULES', DIR_FS_CATALOG . 'includes/modules/');
  	define('DIR_FS_BACKUP', DIR_FS_ADMIN . 'backups/');
// 	define our database connection
	define('DB_PORTAL', 'Rethmeier Executive Search Portal');
	
	define('DB_SERVER', 'db.rethmeieres.com'); // eg, localhost - should not be empty for productive servers
	define('DB_SERVER_USERNAME', 'md143904db126857');
	define('DB_SERVER_PASSWORD', 'jBkQxqxm');
	define('DB_DATABASE', 'md143904db126857');



?>