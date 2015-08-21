<?php

/**
* class FormHandler v1.5.95
*
* This class generates the form and also saves the data when required.
*
* Made By: T. Heimans (formhandler@cyberpoint.nl)
*
* For the currect version, examples and a manual look at http://www.formhandler.nl
*
*/

// counter for unique form names
$_COUNTER = 1;

// make some variables global when the version < 4.1.0
if(intval(str_replace('.', '', phpversion())) < 410) {
    define('_global', true);
    $_GET =& $HTTP_GET_VARS;
    $_POST =& $HTTP_POST_VARS;
    $_FILES =& $HTTP_POST_FILES;
    $_SERVER =& $HTTP_SERVER_VARS;
} else {
    define('_global', false);
}

// Error handler function
// (NOTE: you may remove this funcion and the "set_error_handler" at line
//  +/- 250 if you have your own error handler! )
function &catchErrors() {
    static $errors = array();

    // Error event has been passed
    if (func_num_args()==5) { 
        $errors[] = array(
          'err_no' => func_get_arg(0),
          'err_text' => func_get_arg(1),
          'err_file' => func_get_arg(2),
          'err_line' => func_get_arg(3),
          'err_vars'=>func_get_arg(4)
        );
    }
    // call for the errors. Return the reference
    if (func_num_args()==0) { 
        return $errors;
    }
}

class FormHandler {
    var $editForm;          // boolean: is the form an editform ?
    var $useDB;             // boolean: is the database option used ?
    var $form;              // string: in this variabele is the HTML 'saved'
    var $editId;            // integer: when a editform, this is the id of the record which we are editing
    var $dbName;            // string: when using the database option, the name of the database
    var $dbTable;           // string: when using the database option, the name of the table
    var $dbPrKey;           // array: when using the database option, the primary key(s) of the table stand in here
    var $dbPrKeyName;       // string: the name of the prKey how it is given in the URL (default "id")
    var $dbFields;          // array: when using the database option, contains the names and values of the table-fields
    var $dbGotData;         // boolean: if the real data is collected from the database
    var $fieldNames;        // array: contains the names of the fields of the form
    var $titles;            // array: contains the titles of the fields, needed for a comfirmation form
    var $arrayValues;       // array: contains the array's witch are the values of some fields (needed for the confirm form)
    var $setValues;         // array: contains the "default" values of some fields (inserted by the user)
    var $addValues;         // array: contains the data which has to be saved into de db (inserted by the user)
    var $ignoreFields;      // array: contains the names of the fields which not have to be saved
    var $offOnEmpty;        // array: contains the names of the fields which value has to be "off" when the have no value (like a checkbox)
    var $OnCorrect;         // string: contains the name of the function which handle the script after the form is CORRECT
    var $OnSaved;           // string: contains the name of the function which handle the script after the data is saved
    var $uploadFields;      // array: contains the names of the upload fields
    var $maxUploadSize;     // integer: the maximum size of an image which can be uploaded
    var $ListFields;        // array: name of the listfields
    var $posted;            // boolean: is the form posted ?
    var $mask;              // string: mask that is used for the fields
    var $maskCounter;       // integer: an counter which can be used within the mask function
    var $cache;             // array: contains the values of the fields after the are requested for the first time
    var $SQLFields;         // array: contains the fieldnames which values are a SQL function
    var $dateFields;        // array: contains the fieldnames of the date fields so the date can be returned in the right format
    var $PassFields;        // array: contains the names of the passfields so that these arn't showed in the confirm form
    var $focusField;        // string: the name of the field which get's the focus
    var $tableSettings;     // array: contains the configuration data of the table where the form is placed in
    var $formName;          // string: name of the form
    var $formAction;        // string: script where the form is send to (to itsself!!!)
    var $formExtra;         // string: some extra code for the form (like javascript/css)
    var $formSettings;      // array: contains the configuration data of the form
    var $formErrors;        // boolean: true if a field in the form is not correct
    var $classError;        // string which contains a class error (when the record isn't found)
    var $defaultUploadCfg;  // array: default upload configuration data
    var $fieldSetCounter;   // integer: counts the number of fieldsets
    var $returnArray;       // array: contains the fields which value has to be an array when returned
    var $validations;       // array: contains the names of the validation functions
    var $fckFile;           // string: the path+name of the fckeditor.html file
    var $mapRights;         // integer: the CHMOD mode of the dir where the images are uploaded to
    var $imageResize;       // array: files which have to be resized
    var $resizePrefix;      // string: prefix for the renamed file
    var $GD;                // boolean: indicates if GD can be used (for thumbnails)
    var $permitEdit;        // boolean: indicates if data may be edited
    var $confirm;           // array: contains the data of the confirmation page
    var $hidden;            // string: contains the hidden fields
    var $recordId;          // integer: contains the id of the record where the data is saved
    var $orgErrorHandler;   // string: name of the original error handler
    var $passSize;          // integer: needed size of the password
    
    // messages and other
    var $_save;             // string: default caption of the submit button (can be overwrited)
    var $_reset;            // string: default caption of the reset button (can be overwrited)
    var $_cancel;           // string: default caption of the cancel button (can be overwrited)
    var $_password;         // string: message witch comes above the passwords fields 
    var $_wrongPassword;    // string: message when the password is wrong
    var $_error;            // string: general message when a value of a field is wrong
    var $_email;            // string: message when a e-mail adres is wrong
    var $_postcode;         // string: message when a zipcode is wrong (for a DUTCH function!)
    var $_numeric;          // string: message when a value is not numeric!
    var $_phone;            // string: message when a value is not a valid phone number (dutch phone number like 0123-3456784)
    var $_date;             // string: message when the date is not correct (day-month-year combination impossible)
    var $_uploadSize;       // string: message when uploadsize exeeded
    var $_uploadType;       // string: message when the type is wrong of the uploaded file
    var $_uploadExists;     // string: message when the file allready exists (only when configured in the upload settings)
    var $_upload;           // string: general message when uploading failed
    var $_file;             // string: message to alert that entering some value into the field will overwrite the current value
    var $_notFound;         // string: message when the requested record is not found
    var $_back;             // string: message to hit the back button
    var $_confirm;          // string: message when the data is showed for confirmation
    var $_months;           // array: contains the names of the months
    var $_requiredColor;    // string: color of the star (*) when a field is required

    // the constructor (initializes the variables and there data)
    function FormHandler($dbName = false, $dbTable = false, $dbPrKey = false) {
        global $_COUNTER;
        if(_global) global $_SERVER, $_POST, $_GET;
        
        /*********************************************
         *      These variables can be changed!      *
         *********************************************/
        $this->permitEdit     = false; // default configuration for permitEdit! (default false)
        $this->formName       = 'AutoGeneratedForm' . $_COUNTER++; //  formcounter for unique form-names
        $this->formAction     = $this->URL(); // the current URL
        $this->fckFile        = "http://".$_SERVER["HTTP_HOST"]."/admin/fckeditor.html";  // path to the FCK file!!!!!
        $this->resizePrefix   = 'thumb_';
        $this->dbPrKeyName    = 'id';  // prKey name how it is given in the URL (default 'id')
        $this->passSize       = 6;
        $this->_edit          = 'You are not allowed to edit this record!';
        $this->_save          = 'Save';
        $this->_reset         = 'Reset';
        $this->_cancel        = 'Cancel';
        $this->_password      = 'Enter these fields if you want to change your password';
        $this->_wrongPassword = 'The password you entered is not correct (at least '.$this->passSize.' caracters)!';
        $this->_error         = "You didn't enter a correct value for this field!";
        $this->_email         = 'The e-mail address you have entered is not correct!';
        $this->_postcode      = "You didn't enter a correct zipcode"; // needed for a dutch function
        $this->_numeric       = 'The value you have entered is not numeric!';
        $this->_phone         = "You didn't enter a correct phone number!";
        $this->_date          = 'The day-month-year combination is impossible!';
        $this->_uploadSize    = 'Maximum file size of %s kb exceeded';
        $this->_uploadType    = 'Only the following extensions are possible: %s. ';
        $this->_uploadExists  = 'The file you tried to upload already exists!';
        $this->_upload        = 'Something went wrong. Try again!';
        $this->_file          = 'Enter this field only if you want to overwrite the current value.';
        $this->_notFound      = 'The record has not been found! It could have been deleted!';
        $this->_back          = 'Click here to go back';
        $this->_confirm       = 'The following data is received. If not correct, hit the back button.';
        $this->_requiredColor = 'red';
        $this->_months        = array(
          'Januari', 'Februari', 'Maart',
          'April', 'Mei', 'Juni',
          'Juli', 'Augustus', 'September',
          'Oktober', 'November', 'December'
        );
        // default table settings
        $this->tableSettings = array(
          'width'       => 0,
          'border'      => 0,
          'cellspacing' => 2,
          'cellpadding' => 2,
          'style'       => '',
          'extra'       => ''
        );
        // default settings for uploads... (are overwritten by the user!)
        $this->defaultUploadCfg = array (
          "path"         => "/",
          "type"         => "jpg jpeg png gif doc txt bmp tif tiff pdf",
          "size"         => floor(intval(ini_get('upload_max_filesize')) *1024*1024),
          "name"         => "", // <-- keep the original name
          "error_size"   => $this->_uploadSize,
          "error_type"   => $this->_uploadType,
          "error"        => $this->_upload,
          "required"     => false,
          "exists"       => "alert"
        );
        // default settings for the editor
        $this->defaultEditorCfg = array(
          "upload"  => false,
          "browse"  => false,
          "toolbar" => "default"
        );
        // CHMOD of the dir where the uploads are saved (default 0666)
        $this->mapRights  = 0666;

        // ESCAPE this line if you have your own error handler!
        $this->orgErrorHandler = set_error_handler("catchErrors");


        /**************************************************
         *         This variables may NOT changed!        *
         **************************************************/

        $this->form            = '';
        $this->hidden          = '';
        $this->formExtra       = '';
        $this->orgErrorHandler = null;
        $this->maskCounter     = 0;
        $this->fieldSetCounter = 0;
        $this->maxUploadSize   = 0;
        $this->confirm         = false;
        $this->focusField      = false;
        $this->formErrors      = false;
        $this->classError      = false;
        $this->mask            = false;
        $this->OnCorrect       = false;
		$this->dbGotData       = false;
        $this->ListFields      = array();
        $this->dbFields        = array();
        $this->returnArray     = array();
        $this->SQLFields       = array();
        $this->setValues       = array();
        $this->arrayValues     = array();
        $this->cache           = array();
        $this->titles          = array();
        $this->addValues       = array();
        $this->fieldNames      = array();
        $this->offOnEmpty      = array();
        $this->uploadFields    = array();
        $this->ignoreFields    = array();
        $this->imageResize     = array();
        $this->PassFields      = array();
        $this->validations     = array(
          "checkemail",
          "checkpostcode",
          "numeric",
          "checktel",
          "checkphone",
          "notempty"
        );

        // for uploading of big files, extend the timeout
        if(!ini_get('safe_mode')) {
            @set_time_limit(0);
            @ini_set('max_execution_time', 0);
        }

        // check if GD is installed
        $this->GD = (function_exists('imagetypes') && @imagetypes() > 0);

        // deprecated (way to set the database data)
        if($dbName && $dbTable) {
            $this->error("As of version 1.5.9 you have to enter the database data which the function ".
            "<a href='http://www.formhandler.nl/?pg=3&id=32' target='_blank'>UseDatabase</a>.", E_USER_NOTICE, __FILE__, __LINE__);

            $this->UseDatabase($dbName, $dbTable, $dbPrKey);
        } 
        
        // set the type of form
        $this->editForm = !empty($_GET[$this->dbPrKeyName]);
        
        // is the form posted?
        $this->posted = ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST[$this->formName.'_submit'])) ? true : false;
        
        // get the record id if exists, otherwise 0
        $this->editId = !empty($_GET[$this->dbPrKeyName]) ? $_GET[$this->dbPrKeyName] : 0;
    }

    // function to set the database data
    function UseDatabase($dbName, $table, $prKey = false) {
        // set the tabel data
        $this->useDB  = true;
        $this->dbName  = $dbName;
        $this->dbTable = $table;

        // get the table fields if not got allready
        if(!count($this->dbFields)) {
            $this->getTableFields();
        }
        // set the primary key
        if($prKey) {
            if($this->arrayKeyExists($prKey, $this->dbFields)) {
                $this->dbPrKey[] = $prKey;
            } else {
                $this->error("The primary key '$prKey' does not exists in the table '$table'!", E_USER_WARNING, __FILE__, __LINE__);
            }
        } else {
            list($field, $v) = each($this->dbFields);
            $this->dbPrKey[] = $field;
        }

        // are there more prKey's ?
        if( ($numargs = func_num_args()) > 3) {
            for($i = 3; $i < $numargs; $i++ ) {
                $this->dbPrKey[] = func_get_arg ($i);
            }
        }
    }

    // function to indicate that the records may be edited
    function PermitEdit($permission = true) {
        $this->permitEdit = $permission;
    }

    // Private: function to display errors
    function Error($msg, $type, $file, $line) {
        trigger_error(basename($file) ."($line): ". $msg, $type);
    }

    // Private: function to collect the fieldnames
    function GetTableFields($needData = false) {
        if($needData && !$this->dbGotData) {
            // make a query and run it
            $query = "SELECT * FROM $this->dbTable WHERE ". $this->getWhereClause();
            $sql = $this->query($query, __FILE__, __LINE__);

            // is the record found ?
            if(mysql_num_rows($sql) == 1) {
                $this->dbGotData = true;

                $row = mysql_fetch_array($sql, MYSQL_ASSOC);

                // strip data first, if needed
                if(get_magic_quotes_runtime()) {
                    foreach($row as $fld => $val) {
                        $this->dbFields[$fld] = stripSlashes($row);
                    }
                } else {
                    $this->dbFields = $row;
                }
            } else {
                // if the data isn't found, giva an error...
                $this->classError = $this->_notFound .
                "\n<p><a href='javascript:history.back()'>$this->_back</a></p>\n";
            }
        } else {
            // collect the field names
            $fields = mysql_list_fields($this->dbName, $this->dbTable);

            // put the fieldnames in the array
            if(!$fields) {
                if(mysql_error() == '') {
                      $this->error('Use of the MySQL option but no connection available!', E_USER_WARNING, __FILE__, __LINE__);
                } else {
                    $this->error('MySQL error: ('.mysql_errno() .') '. mysql_error(), E_USER_WARNING, __FILE__, __LINE__);
                }
                   $this->useDB = false;
            } else {
                  for ($i = 0; $i < mysql_num_fields($fields); $i++) {
                       $this->dbFields[mysql_field_name($fields, $i)] = null;
                }
            }
        }
    }

    // Private: function which wil run the query's
    function Query($query, $file = 'unknown', $line = 0) {
        $sql = mysql_query($query) or
        $this->error('MySQL Error: ('.mysql_errno().')'. mysql_error() ." in $file at line $line<br />\n<br />\nQuery: $query", E_USER_ERROR, __FILE__, __LINE__);

        return $sql;
    }

    // Private: function which looks if an array key exists (for older php versions)
    function ArrayKeyExists($key, $array) {
        static $exists = null;

        if(is_null($exists)) $exists = function_exists('array_key_exists');

        if(!is_array($array)) return false;
        return $exists ? array_key_exists($key, $array) : in_array($key, array_keys($array));
    }

    // function which returns the value of an field
    // - is called with the fieldname
    function Value($fieldName, $makeSave = false) {
        if(_global) global $_POST, $_FILES;

        // strip []
        $fieldName = eregi_replace('^(.*)\[\]$', "\\1", $fieldName);

        // do we have the value in the cache array ? (if true, return array)
        if($this->arrayKeyExists($fieldName, $this->cache)) {
            $result = $this->cache[$fieldName];
            return ($makeSave) ? $this->makeSave($result) : $result;
        }
        
        // if the form is posted, get the data of the posted values ($_FILES, $_POST);
        if($this->posted) {
            // look if there are any upload fields
            if($this->arrayKeyExists($fieldName, $_FILES)) {
                $result = $_FILES[$fieldName];
            }
            // look if the file exitst in the $_POST array
            elseif($this->arrayKeyExists($fieldName, $_POST)) {
                $result = $_POST[$fieldName];
            }
            // is it an ListField ?
            elseif( $this->arrayKeyExists($fieldName .'_Value', $_POST) &&
                    in_array($fieldName, $this->ListFields)) {
                $result = explode(',', $_POST[$fieldName . '_Value']);
                $result = array_map('trim', $result);
                $this->removeEmptyItems($result);
            }
            // is the field a DateField ?
            elseif( $this->arrayKeyExists($fieldName .'_dag', $_POST) &&
                    $this->arrayKeyExists($fieldName .'_maand', $_POST) &&
                    $this->arrayKeyExists($fieldName .'_jaar', $_POST) &&
                    $this->arrayKeyExists($fieldName .'_format', $_POST)) {
                $result = str_replace(
                  array('y', 'm', 'd'),
                  array($_POST[$fieldName .'_jaar'], $_POST[$fieldName .'_maand'], $_POST[$fieldName .'_dag']),
                   strToLower($_POST[$fieldName .'_format'])
                );
            }
            // is the field an TimeField ?
            elseif( $this->arrayKeyExists($fieldName .'_hour', $_POST) &&
                    $this->arrayKeyExists($fieldName .'_minute', $_POST)) {
                $result = $_POST[$fieldName .'_hour'].':'.$_POST[$fieldName .'_minute'];
            }
            
            // when the value is found, escape the value when needed
            if(isset($result) && get_magic_quotes_gpc() && !$this->arrayKeyExists($fieldName, $_FILES)) {
                $result = $this->makeSave($result, 'stripslashes');
            }

        // the form is not posted...
        } else {
            // is the form an add-form
            if(!$this->editForm) {
                // return a empty value, of the value is not set by the user
                $result = $this->arrayKeyExists($fieldName, $this->setValues) ? $this->setValues[$fieldName][0] : "";
                
            // the form is an edit form, but the db option has to be used
            } elseif($this->useDB) {
                // look if we've got the record info.. if not, get it
                if(!$this->dbGotData) {
                    $this->getTableFields(true);
                }

                // look if the field exitst in the table
                if($this->arrayKeyExists($fieldName, $this->dbFields)) {
                    // return the correct value
                    $result = (in_array($fieldName, $this->offOnEmpty) && $this->dbFields[$fieldName] == 'Ja') ? 'on' :
                    ($this->arrayKeyExists($fieldName, $this->setValues) && $this->setValues[$fieldName][1]) ? $this->setValues[$fieldName][0] : $this->dbFields[$fieldName];
                } elseif($this->arrayKeyExists($fieldName, $this->setValues)) {
                    // the field does not exitst in the table.. is the value set by the user ?
                    $result = $this->setValues[$fieldName][0];
                }
            }
        }

        // if we don't have a value and its not an checkbox or an radio field (offOnEmpty)
        // we return an empty value ("")
        if(!isset($result) && in_array($fieldName, $this->offOnEmpty)) {
            $result = 'off';
        } elseif(isset($result) && in_array($fieldName, $this->returnArray)) {
            if(!is_array($result) && in_array($fieldName, $this->ListFields)) {
                $result = explode(",", $result);
                $result = array_map("trim", $result);
                $this->removeEmptyItems($result);
            } else {
                $result = !is_array($result) ? array($result) : $result;
            }
        } elseif(!isset($result)) {
            $result = '';
        } 

        // put it into the cache array and return the value
        $this->cache[$fieldName] = $result;
        return ($makeSave) ? $this->makeSave($result) : $result;
    }

    // Private: function to make data save (can also be used for other purposes)
    function makeSave($value, $function = 'HTMLSpecialChars') {
        if(is_array($value)) {
            foreach($value as $key => $var) {
                $value[$key] = $function($var);
            }
        } else {
            $value = $function($value);
        }
        return $value;
    }

    // Private: function to remove empty items of an array
    function RemoveEmptyItems(&$array) {
        foreach($array as $key => $value)
            if(trim($value) != '')
                $array[trim($key)] = trim($value);
            else 
                unset($array[trim($key)]);
    }

    // Private: get the current url
    function URL() {
        if(_global) global $_SERVER;

        return $_SERVER["PHP_SELF"]. (!empty($_SERVER["QUERY_STRING"])?"?".$_SERVER["QUERY_STRING"]:"") ."#error";
    }

    // Private: ean alias for call_user_funcen only this one excepts existing functions
    function CallUserFunction($function, $value, $secondValue = null) {
        static $f = false;

        // if the form isn't posted yet or no function is given, return true (value currect)
        if(!$function || !$this->posted || is_bool($function)) {
            return true;
        }

        // because empty and isset are language constructors (those wont work in function_exitst)
        // we check them first
        $reverse = !(strPos($function, '!') === false);
        if($reverse)
            $function = str_replace('!', '', $function);
            
        if(is_string($value)) $value = trim($value);
        switch (strToLower($function)) {
            case 'empty':   $output = empty($value);   break;
            case 'isset':   $output = isset($value);   break;
        }
        
        // when isset of empty, return the value
        if(isset($output)) {
            return ($reverse) ? !$output : $output;
        } else {
            // get the functions if we haven't got them allready
	        if(!$f) $f = get_defined_functions();

            // look if the function is an Private PHP function. If so, call it
            if(in_array($function, $f['internal']) ) {
                if (function_exists($function)) {
                    return call_user_func($function, $value, $secWaarde);
                } else {
                     $this->error('Cannot call PHP function with call_user_func: '.$function, E_USER_WARNING, __FILE__, __LINE__);
                 }
            // user defined function ?
               } elseif(in_array(strToLower($function), $f['user'])) {
                return call_user_func($function, $value, $secondValue);
            // inside validation function ?
            } elseif(in_array(strtolower($function), $this->validations))  {
                   return $this->$function($value, $secondValue);
            } else {
                $this->error("The function '$function' does not exist!", E_USER_ERROR, __FILE__, __LINE__);
            }
        }
    }

    // function to add html to the form
    function AddHTML($html) {
        $this->form .= $html;
    }

    // dutch alias for BorderStart
    function KaderStart($legend = "") {
        $this->BorderStart($legend);
    }

    // dutch alias for BorderStop
    function KaderStop() {
        $this->BorderStop();
    }

    // function to start a border
    function BorderStart($caption = "") {
        if($this->fieldSetCounter == null) $this->fieldSetCounter = 0;
        $this->form .=
        " <tr>\n".
        "  <td valign='top' colspan='3'>\n".
        "   <br />\n".
        "   <fieldset>\n".
        "    <legend><B><i>". $caption ."</i></B></legend>\n".
        "    <table cellspacing='0' cellpadding='4' id='fieldset".$this->fieldSetCounter++."'>\n";
    }

    // function to end a border
    function BorderStop() {
        if($this->fieldSetCounter > 0) {
            $this->form .=
            "     </table>\n".
            "    </fieldset>\n".
            "   </td>\n".
            " </tr>\n";
            $this->fieldSetCounter--;
        }
    }

    // function to set a confirmation page
    function Confirm($text = false, $submit = true, $cancel = true, $cancelPage = 'javascript:history.back(1)') {
        // set the text above the form
        $this->confirm = array(
          ($text === false) ?  $this->_confirm : $text,
          $submit,
          $cancel,
          $cancelPage
        );
    }

    // function to set the focus at a field
    function Focus($field) {
        $this->focusField = $field;
    }

    // function to set a HTML mask
    function SetMask($mask = false, $counter = 1) {
        $org = is_null($this->mask)?false:$this->mask;
        $this->mask = (!empty($mask) || $mask === false) ?$mask:$this->mask;
        $this->maskCounter = $counter;
        return $org;
    }
    
    // Private: look if the browser is compatible for an FCK editor
    function FCKCompatible() {
        if(_global) global $_SERVER;

        $sAgent = $_SERVER['HTTP_USER_AGENT'] ;
        if (is_integer( strPos($sAgent, 'MSIE'))
         && is_integer( strPos($sAgent, 'Windows'))
         && !is_integer( strPos($sAgent, 'Opera'))) {
            $iVersion = (int)subStr($sAgent, strPos($sAgent, 'MSIE') + 5, 1) ;
            return ($iVersion >= 5) ;
        } else {
            return false;
        }
    }

    // validation function to check if the value is not empty
    function NotEmpty($value) {
        $value = trim($value);
        return !empty($value);
    }

    // validation function to check if the e-mail adres is correct
    function CheckEmail($email) {
        $regex = '/^([a-zA-Z0-9_\-\.,]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/';
        return preg_match($regex, $email) ? true : $this->_email;
    }

    // dutch postal code validation
    function CheckPostcode($postcode) {
        return preg_match('/^[0-9]{4}([ ]*)[a-zA-Z]{2}$/', $postcode) ? true : $this->_postcode;
    }

    // validate function which checks if the value is numeric
    function Numeric($value) {
        $regex = '/(^-?\d\d*\.\d*$)|(^-?\d\d*$)|(^-?\.\d\d*$)/';
        return preg_match($regex, str_replace(',', '.', $value)) ? true : $this->_numeric;
    }

    // dutch alias for CheckPhone
    function CheckTel($tel) {
         return $this->CheckPhone($tel);
    }

    // fuction to check an telephone number
    function CheckPhone($number) {
        $regex = '^[0-9]{2,4}[-]?[0-9]{6,8}$';
        $number = str_replace(array(' ', '-'), array('', ''), $number);
        return (strLen($number) == 10 && eregi($regex, $number)) ? true : $this->_phone;
    }

    // function which resizes the images
    function DoResize($path, $filename, $cfg) {
        ClearStatCache();

        // change the memory limit
        ini_set('memory_limit', 25000000);

        // get the path and extension of the original file
        $path = $this->CorrectPath($path);
        $ext  = $this->GetExtension($filename);

        // get the name and path of the new file
        $newPath = $this->CorrectPath($cfg['path']);
        $newFile =  $newPath . $this->resizePrefix . $filename;

        // get the original file
        if($ext == 'jpg' || $ext == 'jpeg') {
            $original = ImageCreateFromJPEG($path . $filename);
        } elseif($ext == 'png') {
            $original = ImageCreateFromPNG($path . $filename);
        } else {
            return null;
        }

        if($original) {
            // get the size of the image
            $size = GetImageSize ($path . $filename);
            $imgH = $size[1];
            $imgW = $size[0];

            // is the image to big (get new sizes)?
            if ($imgW > $imgH) {
                  $width = ($imgW > $cfg['size']) ? $cfg['size'] : $imgW;
                  $scale  = (($width / $imgW) * 100);
                  $height = ($imgH / 100) * $scale;
            } else {
                $height = ($imgH > $cfg['size']) ? $cfg['size'] : $imgH;
                  $scale = (($height / $imgH) * 100);
                  $width = ($imgW / 100) * $scale;
            }

            // generate the image
            if(function_exists("imagecopyresampled")){
                $imgResized = ImageCreateTrueColor($width,$height);
                ImageCopyResampled ($imgResized, $original, 0, 0, 0, 0, $width, $height, $imgW, $imgH);
            } else {
                $imgResized = ImageCreate($width,$height);
                ImageCopyResized($imgResized, $original, 0, 0, 0, 0, $width, $height, $imgW, $imgH);
            }

            if($ext == 'jpg' || $ext == 'jpeg') {
                $original = ImageJPEG($imgResized,$newFile,$cfg['quality']);
            } elseif($ext == 'png') {
                $original = ImagePNG($imgResized,$newFile);
            }
            

            // clean up
            ImageDestroy($original);
            ImageDestroy($imgResized);

            return $this->resizePrefix . str_replace(".$ext", '.jpg', $filename);
        }
    }

    // function to set the settings for an image resize
    function ImageResize($name, $savePath = '/', $maxSize = 180, $quality = 100) {
        if($this->GD) {
            $this->imageResize[$name] = array(
              'path'    => $savePath,
              'size'    => $maxSize,
              'quality' => $quality
            );
        } else {
            $this->error("To use this function you must have GD libary installed!", E_USER_NOTICE, __FILE__, __LINE__);
        }
    }

    // Private: retuns the path in a correct format (like home/dir/ ) and makes it
    function CorrectPath($path) {
        if($this->recordId) $path = str_replace('[id]', $this->recordId, $path);

        $path = str_replace('\\', '/', $path);
        if(substr($path, 0, 1) != '/') $path = '/'.$path;
        if(substr($path, -1) == '.')   $path = substr($path, 0, -1);
        if(substr($path, -1) != '/')   $path .= '/';

        return $this->ForceDir($path);
    }

    // Private: function which makes the given directory
    function ForceDir( $path, $current = '' ) {
        if ( strlen( $path) == 0) {
            if(empty($current)) $current = '.';
            $path = realpath($current.'/.');
            return str_replace('\\', '/', $path) . '/';
        }

        // remove empty strings
        $path = explode("/", $path);
        while($this->ArrayKeyExists(0, $path) && empty($path[0]) ) {
            array_shift($path);
        }

        if(!is_array($path)) {
            var_dump($path);
            echo "\n<br> <-- Geen array!";
            exit;
        }

        // start with root dir or not ?
        if(!isset($path[0])) $path[0] = '';
        if(empty($current)) {
            $tmp = realpath($path[0]);
            if(empty($tmp)) {
                $current = realpath('.');
                $main = realpath($current.'/'.$path[0].'/.');
            } else {
                $current = $path[0];
                $main = 'blaat';
            }
        } else {
            $main = realpath($current.'/'.$path[0].'/.');
        }

        // make the dir if not exists
        if(empty($main)) {
            mkdir($current."/".$path[0], $this->mapRights);
        }
        $current .= !empty($tmp) ? '' : '/'.$path[0];
        array_shift($path);

        $path = implode('/', $path);

        return $this->ForceDir($path, $current);
    }

    // Private: returns the extension of a file
    function GetExtension($filename) {
        $fp = explode(".",$filename);
        return strToLower($fp[count($fp)-1]);
    }

    // Private: returns the name for the upload
    function GetFilename($file, $config) {
        if(is_array($file)) {
            $file = $file['name'];
            if(!isset($file["error"]) || $file["tmp_name"] == "none") {
                return false;
            }
        } elseif(substr($file, 0, 5) == "file:") {
            list(, $file) = split(':', $file, 2);
        }

        // get the extension
        $extension = $this->GetExtension($file);

        // filename like we're going to save it
        $file = empty($config['name']) ? $file : $config['name'].".".$extension;
        $file = ereg_replace("[^a-z0-9._]", "",
          str_replace(
            array(' ', "%20",'.'.$extension),
            array('_','_',''),
            strToLower($file)
          )
        );

        // rename when wanted
        if($config["exists"] == "rename") {
            // get the path where we are going to save the file
            $path = $this->CorrectPath($config['path']);

            $copy = "";
            $i = 1;
            while (file_exists($path.$file.$copy.".".$extension)) {
                $copy = "(".$i++ .")";
            }
            return $file.$copy.".".$extension;
        } else {
            return $file.".".$extension;
        }
    }

    // Private: function which uploads the files
    function DoUpload($file, $config) {
        // "uploading" from the tmp dir if a confirm page is wanted
        if(is_string($file) && substr($file, 0, 5) == "file:" && $this->Value("tmp_dir") != "") {
            list(, $file) = split(':', $file, 2);

            $currpath = $this->CorrectPath($this->Value("tmp_dir"));

            // get the extension
            $extension = $this->GetExtension($file);

            // filename like we're going to save it
            $filename = $this->GetFilename($file, $config);

            // make the dir if not exists
            $path = $this->CorrectPath( $config['path'] );

            // the file like we are going to save it
            $upload = $path . $filename;

            // if exists, try to delete
            if($config["exists"] == "overwrite" && file_exists($path . $filename.".".$extension)) {
                // try to delete the existing file
                @chmod($upload,0777);
                @unlink($upload);
            } 

            if (!rename($currpath . $file, $upload)) {
                @unlink($currpath . $file);
                $this->error("Error while moving file ". basename($upload)."!", E_USER_ERROR, __FILE__, __LINE__);
                return false;
            }
            @chmod ($path, $this->mapRights);
            return basename($upload);
            
        } elseif(is_array($file) && is_uploaded_file($file['tmp_name'])) {
            
            // get the extension
            $extension = $this->GetExtension($file['name']);

            // get the correct filename
            $filename = $this->GetFilename($file["name"], $config);
            
            // make the dir if not exists
            $path = $this->CorrectPath( $config['path'] );

            // the file like where going to upload it
            $upload = $path . $filename;

            // uploading
            if (!move_uploaded_file($file['tmp_name'],$upload)) {
                @unlink($file['tmp_name']);
                $this->error("Error while uploading file ". basename($upload)."!", E_USER_ERROR, __FILE__, __LINE__);
                return false;
            }
            @chmod ($path, $this->mapRights);
            return basename($upload);
        } 
    }
    
    // Private: function which puts the HTML of the fields to the form and check's for error's
    function GenerateField($title, $field, $name, $error = true) {
        // is the error an boolean..
        if(is_bool($error)) {
            // is true, no error occured (else default error message)
            if($error) {
                $errorMsg = false;
            } else {
                $errorMsg = $this->_error;
                $this->formErrors = true;
            }
        } else {
            // if the error is an string (and not empty), display the error
            if( trim($error) != '' ) {
                $errorMsg = $error;
                $this->formErrors = true;
            } else {
                $errorMsg = false;
            }
        }

        // put the name and title of the field in there array's
        $this->fieldNames[]  = $name;
        $this->titles[$name] = $title;

        // make field, title, error and counter ready
        $title = str_replace('*', '<font color="red" style="color:'.$this->_requiredColor.'">*</font>', $title);
        $errorMsg = ($errorMsg) ? '<div class="errormsg">'. $errorMsg .'</div>' : '';

        // put the html with the elements to the form
        if($this->mask) {
            $output = str_replace('%counter%', $this->maskCounter, $this->mask);
            $output = str_replace('%teller%',  $this->maskCounter, $output); // dutch version of counter
            $output = str_replace('%veld%',  $field, $output);   // dutch verion of field
            $output = str_replace('%titel%', $title, $output);   // dutch version of title
            $output = str_replace('%title%', $title, $output);
            $output = str_replace('%error%', $errorMsg, $output);
            $this->form .= str_replace('%field%',  $field, $output);
        } else {
            $this->form .=
            " <tr>\n".
            "  <td valign='top' nowrap='nowrap' width='10%'><a name='error'></a>$title</td>\n".
            "  <td valign='top'>". (!$title ? "" :":")."</td>\n".
            "  <td valign='top'>$field $errorMsg</td>\n".
            " </tr>\n";
        }
        $this->maskCounter++;
    }

    // function to set the name, the action and some js/css to the form
    function FormSettings($name = false, $action = false, $extra = "") {
        #won't work correct! //if($name) $this->formName = $name;
        if($action) $this->formAction = $action;
        if(!empty($extra)) $this->formExtra = trim($extra);

    }
    // function to set the settings of the table
    function TableSettings($width = 0, $border = 0, $cellspacing = 2, $cellpadding = 2, $style = '', $extra = '') {
        $this->tableSettings = array(
          'width'       => $width,
          'border'         => $border,
          'cellspacing' => $cellspacing,
          'cellpadding' => $cellpadding,
          'style'       => trim($style),
          'extra'       => trim($extra)
          );
    }

    // function to generate a textfield
    function TextField($title, $name, $callback = false, $size = 20, $maxLength = 0, $extra = "") {
        // get the vars needed
        $size = ($size == 0) ? 20 : $size;
        $maxLength = ($maxLength != 0) ? " maxlength='$maxLength'":'';

        // make the field
        $field = "<input type='text' name='$name' value=\"".$this->Value($name, true)."\" size='$size' $maxLength $extra />";
        $this->generateField($title, $field, $name, $this->callUserFunction($callback, $this->value($name) ) );
    }

    // function to generate a hidden field
    function HiddenField($name, $value, $callback = false, $extra = "") {
        $this->hidden($name, $value, $extra);
        
        $this->fieldNames[]  = $name;

        //$this->GenerateField('', '', $name, $this->callUserFunction($callback, $this->value($name)) );
    }

    // function to generate a passfield
    function PassField($title, $name, $callback = false, $size = 20, $maxLength = 0, $extra = "", $pwCheck = false) {
        // set the type to passfield (not to show in the cornfirm page!)
        $this->PassFields[] = $name;

        // get the needed vars
        $size = ($size == 0) ? 20 : $size;
        $maxLength = ($maxLength != 0) ? " maxlength='$maxLength'":'';

        // make the field
        $field = "<input type='password' name='$name' size='$size' $maxLength $extra />";

        // when a password check is wanted...
        if($pwCheck) {
            // add a message above the fields
            if($this->editForm)
                $this->AddHTML("<tr><td></td><td></td><td><small style='color: red'>".$this->_password."</small></td></tr>\n");

            // only check if password is submitted
            if($this->posted) {
                $ww1 = $this->Value($name);
                $ww2 = $this->Value($pwCheck);
                // are they equal ?
                if($ww1 == $ww2) {
                    // if they are empty and the form is an edit-form, let them keep there values
                    if(empty($ww1) && $this->editForm) {
                        $this->ignoreFields[] = $name;
                        $this->ignoreFields[] = $pwCheck;
                    } elseif(strLen($ww1) < $this->passSize) {
                        // passwords which are smaller then < PassSize characters are false...
                        $error = $this->_wrongPassword;
                    }
                } else {
                    $error = $this->_wrongPassword;
                }
            }
        }
        if($callback) $error = $this->callUserFunction($callback, $this->value($name));
          if(!isset($error)) $error = true;

        // het veld bij de HTML output zetten
        $this->generateField($title, $field, $name, $error);
    }

    // function to generate a ListField
    function ListField($title, $name, $values, $useKeyAsValue = true, $callback = false, $leftTitle = "", $rightTitle = "", $size = 4, $extra="") {
        // capture the values for the confirm form
        $this->arrayValues[$name] = array($values, true);

        // remove the comma's and white spaces
        $tmp = array();
        foreach($values as $key => $value) {
            $key   = str_replace(',', '', $key);
            $value = str_replace(',', '', $value);
            $tmp[trim($key)] = trim($value);
        }
        $values =& $tmp;

        // set a flag so that the javascript wil be added to the form
        $this->ListFields[] = $name;

        // we always want an array as result:
        $this->returnArray[] = $name;

        // get selected values
        $selected = $this->value($name, true);
        if(!is_array($selected)) {
            $selected = explode(",", $selected);
            $this->RemoveEmptyItems($selected);
        }

        // generate the field
        $field =
        "\n".
        "   <input type='hidden' name='{$name}_Value' value=\"". implode(", ", $selected)."\" />\n".
        "   <table border='0' cellspacing='0' cellpadding='0'>\n".
        "    <tr>\n".
        "     <td align='center'><b>$leftTitle</b></td>\n".
        "     <td align='center'><b> - </b></td>\n".
        "     <td align='center'><b>$rightTitle</b></td>\n".
        "    </tr>\n".
        "    <tr>\n".
        "     <td rowspan='2' align='right'>\n".
        "      <select name='{$name}_ListOn' size='$size' $extra>\n";

        // show the selected values
        $counter = 0;
        foreach($selected as $text) {
            if(!empty($text)) {
                $counter++;
                $field .=  "\t   <option value=\"".HTMLSpecialChars($text)."\">". HTMLSpecialChars( $useKeyAsValue ? $values[$text] : $text ) ."</option>\n";
            }
        }
        if($counter == 0) $field .= "\t   <option></option>\n";

        $field .=
        "      </select>\n".
        "     </td>\n".
        "     <td width='30' align='center' valign='middle'>\n".
        "      <input type='button' value=' &gt; ' onclick=\"changeValue('$name', false)\" />\n".
        "     </td>\n".
        "     <td rowspan='2'>\n".
        "      <select name='{$name}_ListOff' size='$size' $extra>\n";

        // the not selected values...
        $counter = 0;
        foreach($values as $key => $text) {
            if(!in_array($key, $selected)) {
                $field .=  "\t   <option value=\"".HTMLSpecialChars($useKeyAsValue ? $key : $text )."\">". HTMLSpecialChars($text) ."</option>\n";
                $counter++;
            }
        }
        if($counter == 0) $field .= "\t   <option></option>\n";

        $field .=
        "      </select>\n".
        "     </td>\n".
        "    </tr>\n".
        "    <tr>\n".
        "     <td align='center' valign='middle'>\n".
        "      <input type='button' value=' &lt; ' onclick=\"changeValue('$name', true)\" />\n".
        "     </td>\n".
        "    </tr>\n".
        "   </table>\n ";

        // put the field in the form
        $this->generateField($title, $field, $name,  $this->callUserFunction($callback, $this->value($name)) );
    }

    // function to generate a TextArea
    function TextArea($title, $name, $callback = false, $cols = 40, $rows = 7, $extra = "") {
        $this->generateField(
          $title,
          "<textarea name='$name' cols='$cols' rows='$rows' $extra>".
            $this->value($name, true).
          "</textarea>\n",
          $name,
          $this->callUserFunction($callback, $this->value($name))
        );
    }

    // function to generate a checkbox
    function CheckBox($title, $name, $values = "on", $useKeyAsValue = true, $callback = false, $extra = "", $glue = "<br />\n") {
        static $counter = 1;

        // capture the values for the confirm form
        if(is_array($values)) $this->arrayValues[$name] = array($values, $useKeyAsValue);

        // when the value is empty, the value will be "on"
        if($values == "") $values = "on";

        // escape the name
        $name = eregi_replace('^(.*)\[\]$', "\\1", $name);

        // set the value to "off" when nothing is selected
        $this->offOnEmpty[] = $name;

        // get the value
        $value = $this->value($name);

        // if the given value(s) is/are an array
        $field = '';
        if(is_array($values)) {
            // if the value come's out of the database, make an array
            if(!is_array($value)) {
                  $value = explode(", ", $value);
            }
            $this->removeEmptyItems($value);


            foreach($values as $key => $val) {
                $key = ($useKeyAsValue ? $key : $val);
                $field .=
                "<input type='checkbox' name='".$name."[]' id='checkbox".$counter."'".(in_array($key, $value)?' checked="checked"':'').
                " value=\"$key\" $extra /> <label for='checkbox".$counter++."'>$val</label>". ((count($values) == 1)?str_replace('<br />', '', $glue):$glue);
            }
        } else {
            $key = ($values == $value) ? ' checked="checked"' : '';
            $field .= "<input type='checkbox' name='$name' id='checkbox".$counter."' value=\"$values\" $extra $key />".
            " <label for='checkbox".$counter++."'>".(($values == "on") ? '' : $values) ."</label>".str_replace('<br />', '', $glue);
        }

        // put the field in the form
        $this->generateField($title, $field, $name, $this->callUserFunction($callback, $value) );
    }

    // function to generate a radio button
    function RadioButton($title, $name, $values = "on", $useKeyAsValue = true, $callback = false, $extra = "", $glue = "<br />\n") {
        static $counter = 1;

        // capture the values for the confirm form
        if(is_array($values)) $this->arrayValues[$name] = array($values, $useKeyAsValue);

        // escape the name
        $name = eregi_replace('^(.*)\[\]$', "\\1", $name);

        // set the value to "off" when nothing is selected
        $this->offOnEmpty[] = $name;

        // get the value
        $value = $this->value($name);

        // if the given value(s) is/are an array
        $field = '';
        if(is_array($values)) {
            foreach($values as $key => $val) {
                $key = ($useKeyAsValue ? $key : $val);

                $field .=
                "<input type='radio' name='$name' id='radio".$counter."'".(($key == $value)?' checked="checked"':'').
                " value=\"$key\" $extra /> <label for='radio".$counter++."'>$val</label>". ((count($values) == 1)?str_replace('<br />', '', $glue):$glue);
            }
        } else {
            $field .= "<input type='radio' name='$name' id='radio".$counter."' value=\"$values\" $extra".(($values == $value) ? ' checked="checked"' : '')." />".
            " <label for='radio".$counter++."'>".(($values=='on') ? '' : $values) ."</label>".str_replace('<br />', '', $glue);
        }

        // put the field in the form
        $this->generateField($title, $field, $name, $this->callUserFunction($callback, $value) );
    }

    // function to generate a selecfield
    function SelectField ($title, $name, $values, $useKeyAsValue = true, $callback = false, $size = 1, $extra = "") {
        // check if the type of the given values is a array
        if(!is_array($values)) {
             $this->error("You have to give an array as value by the SelectField '$name'!", E_USER_ERROR, __FILE__, __LINE__);
        }

        // capture the values for the confirm form
        $this->arrayValues[$name] = array($values, $useKeyAsValue);

        // get the value of the field
        $value = $this->value($name);

        // generate the field
        $field = "<select name='$name' size='$size' $extra>";

        // walk trough the values
        $label = false;
        foreach($values as $key => $val ) {
            if(ereg("LABEL", $key)) {
                $field .= "\n\t<optgroup label=\"". HTMLSpecialChars($val)."\">";
            } else {
                $key = HTMLSpecialChars($useKeyAsValue ? $key : $val);
                $selected = ($key == $value) ? " selected" : "";
                $field .= "\n\t<option value=\"".$key.'"'.$selected.'>'. HTMLSpecialChars($val) .'</option>';
            }
        }
        $field .= "\n  </select>\n";

        // put the field in the form
        $this->generateField($title, $field, $name, $this->callUserFunction($callback, $value) );
    }

    // function to generate a upload field
    function UploadField($title, $name, $config = array(), $callback = false, $extra = "", $overwriteMessage = false) {
        static $iniSize = false;

        // look if we are authorized to use an upload field
        if(!ini_get('file_uploads')) {
            $this->error('The configuration on this server does not allow files to be uploaded! You cannot use the upload field.', E_USER_ERROR, __FILE__, __LINE__);
            return false;
        }

        // get the maximum allowed upload size (in bytes)
        if(!$iniSize) {
            $post = intval(ini_get('post_max_size')) * 1024 *1024;
            $upl  = intval(ini_get('upload_max_filesize')) * 1024 * 1024;
            $iniSize = ($post < $upl) ? $post : $upl;
        }

        // get lower case key's
        $config = !is_array($config) ? array() : $this->makeSave($config, "strToLower");

        // de config waarden langslopen (wanneer niet bestaat, de standaard instellingen pakken)
        foreach($this->defaultUploadCfg as $key => $value) {
            if(!$this->arrayKeyExists($key, $config) || empty($config[$key])) {
                 $config[$key] = $this->defaultUploadCfg[$key];
            } elseif($key == 'size' && $config['size'] > $iniSize) {
                $this->error("The declared maximum uploadsize (".$config['size']." bytes) of field '$name' is bigger then the maximum size in de php.ini ($iniSize bytes)!\n".
                "The maximum uploadsize which you declared is overruled.", E_USER_NOTICE, __FILE__, __LINE__);
                $config['size'] = $iniSize;
            }
        }

        // set the javascript upload checker
        if(preg_match("/onchange([ ]+)=([ ]+)('|\")(.*)$/i", $extra, $match)) {
            // put the function into a onchange tag if set
            $msg = str_replace($match[3], (($match[3]=="'")?'"':"'"), "checkUpload(this, '".$config['type']."');");
            $extra = preg_replace("/onchange([ ]+)=([ ]+)('|\")(.*)$/i", "onchange=\\3$msg\\4", $extra);
        } else {
            $extra = "onchange=\"checkUpload(this, '".$config['type']."')\" ".$extra;
        }

        // set the maximum upload size
        if($config['size'] > $this->maxUploadSize)
            $this->maxUploadSize = $config['size'];

        // save the configuration of the file
        $this->uploadFields[$name] = $config;

        // alias for the file data
        $FILE = $this->value($name);

        // when the form is posted
        if($this->posted && !$callback) {
            // detect error if php version is oder then 4.2.0
            if($FILE["tmp_name"] == "none" && empty($FILE["name"]))      $FILE['error'] = 4;
            elseif($FILE["tmp_name"] == "none" && !empty($FILE["name"])) $FILE['error'] = 2;
            elseif(!isset($FILE["error"]))                               $FILE['error'] = 4;

            // look if the file allready exists
            if($config["exists"] == "alert" && $FILE['error'] != 4) {
                // get the path
                $path = $this->CorrectPath($config['path']);

                // get the extension
                $extension = $this->GetExtension($FILE['name']);

                // filename like we're going to save it
                $filename = empty($config['name']) ? $FILE['name'] : $config['name'].".".$extension;
                $filename = ereg_replace("[^a-z0-9._]", "", str_replace(array(' ', "%20",'.'.$extension), array('_','_',''), strtolower($filename)));

                if(file_exists("$path$filename.$extension")) {
                    $error = $this->_uploadExists;
                }
            }

            //  check if the field is required
            if(is_bool($config['required']) && $config['required'] && $FILE['error'] == 4 ) {
                $error = false;
            } elseif(StrToLower(trim($config['required'])) == "true" && $FILE['error'] == 4 ) {
                $error = false;

                // if the field not empty is
            } elseif($FILE['error'] != 4) {

                // is the file not too big?
                if(isset($FILE['error']) && ($FILE['error'] == 1 || $FILE["error"] == 2 || $FILE['size'] > $config['size'])) {
                    $error = sprintf($config['error_size'], round($config['size'] / 1000, 2));
                }
                // is the extension correct ?
                $fp = explode(".",$FILE['name']);
                if(!in_array(strToLower($fp[count($fp)-1]), explode(' ', strToLower($config['type'])))) {
                    $error = sprintf($config['error_type'], $config['type']);
                }

                // if an error occured..
                if($FILE['error'] == 3) {
                    $error = $config['error'];
                }
            }

        } elseif(!empty($callback)) {
            // check the file which a user function if wanted
            $error = $this->callUserFunction($callback, $FILE);
        }
        // hide the error when confirming.. check has allready been "done"
        if(!isset($error) || $this->Value("__confirmation__") != '') $error = true;

        // make the field
        $msg = ($this->editForm && $overwriteMessage && (is_string($FILE) && $FILE != "")) ?"<small style='color: red'>$this->_file</small><br />\n\t":'';
        $field = $msg ."<input type='file' name='$name' $extra />";
        $this->generateField($title, $field, $name, $error);
    }

    // function to generate a timefield
    function TimeField ($title, $name, $callback = false, $extra = '') {
        // get the current value
        list($hour, $min) = split(':', ($this->value($name) == '') ? date("H:i") : $this->value($name));

        // generate the field
        $field = " <select name='{$name}_hour' $extra>";
        for($i = 0; $i <= 23; $i++ ) {
            $field .= "\n\t<option value=\"".sprintf("%02d", $i)."\"".
            (($hour == $i)?" selected='selected'":"").">". sprintf("%02d", $i)."</option>";
          }
        $field .=
        "\n\t</select><b>:</b><select name='{$name}_minute' $extra>";
        for($i = 0; $i <= 59; $i++ ) {
            $field .= "\n\t<option value=\"".sprintf("%02d", $i)."\"".
            (($min == $i)?" selected='selected'":"").">". sprintf("%02d", $i)."</option>";
        }
        $field .= "\n</select>";

        // put the field into the form
        $this->generateField($title, $field, $name, $this->callUserFunction($callback, $this->value($name)) );
    }

    // function to generate a datefield
    function DateField ($title, $name, $interval = "90:90", $callback = false, $format = 'd-m-y', $extra = '') {
        //get the current value of the field
        $value = $this->value($name);

        // get the year interval for the dates in the field
        if(strpos($interval, ':')) {
             list($start, $end) = split(':', $interval, 2);
        } elseif(is_string($interval) || is_integer($interval) && !empty($interval)) {
            $start = $interval;
            $end = 90;
        } else {
            $start = 90;
            $end = 90;
        }

        // if the form is posted, get the date in a correct format ?
        if($this->posted) {
            $fmt = str_replace(array('y', 'm', 'd'), array('^', '^', '^'), strToLower($format));
            $fmt = explode('^', $fmt);
            $tmp = $value;

            foreach($fmt as $splitter) {
                if($splitter == '') continue;
                $pos = strpos($tmp, $splitter);
                if ($pos !== false) {
                    $parts[] = substr($tmp, 0, $pos);
                    $tmp = substr($tmp, $pos + strLen($splitter));
                }
            }
            $parts[] = $tmp;

            $i = $teller = 0;
            $value = strToLower($format);

            for($i = 0; $i < strLen($value); $i++ ) {
                $c = substr($value, $i, 1);
                if(in_array( $c , array('y', 'm', 'd')) && $this->ArrayKeyExists($teller, $parts)) {
                    switch($c) {
                      case 'y': $jaar  = $parts[$teller++]; break;
                      case 'm': $maand = $parts[$teller++]; break;
                      case 'd': $dag   = $parts[$teller++]; break;
                    }
                }
            }
            if(!isset($dag))   $dag   = '';
            if(!isset($maand)) $maand = '';
            if(!isset($jaar))  $jaar  = '';
        } else {
            if(ereg('^([0-9]{4})[-]([0-9]{1,2})[-]([0-9]{1,2})$', $value)) {
                list($jaar, $maand, $dag) = explode("-", $value);
            } else {
                list($dag, $maand, $jaar) = explode('-', (($value == '') ? date("j-n-Y") : $value));
            }
        }

        // generate the day field
        $field = "<select name='{$name}_dag' $extra>";
        for($i = 1; $i <= 31; $i++) {
            $selected = ($dag == $i) ? ' selected="selected"' : "";
            $field .= "\n\t<option value=\"". sprintf("%02d", $i) ."\"$selected>$i</option>";
        }
        $field .=
        "\n    </select>\n".
        "    <select name='{$name}_maand' $extra>";

        // generate the month field
        $maanden = $this->_months;
        for($i = 0; $i < count($maanden); $i++) {
            $selected = ($maand==$i+1) ? ' selected="selected"' : "";
            $field .= "\n\t<option value=\"". sprintf("%02d", $i+1) ."\"{$selected}>". $maanden[$i]."</option>";
        }
        $field .=
        "\n    </select>\n".
        "    <select name='{$name}_jaar' $extra>";

        // generate the year field
        // for($i = date("Y") + intval($end); $i >= date("Y") - intval($start);  $i--) {
        // for($i = (date("Y") - intval($start)); $i < (date("Y") + intval($end)); $i++){
		date_default_timezone_set('UTC');
        for($i = (date("Y") - 90); $i < (date("Y") + 90); $i++){	
        	
            $selected = ($i==$jaar) ? ' selected="selected"': "";
            $field .= "\n\t<option value=\"".$i."\"".$selected.">".$i."</option>";
        }
        $field .=
        "\n    </select>\n".
        "      <input type='hidden' size='5' value='$format' name='{$name}_format' />\n";

        // check the value of the field
        $error = $this->callUserFunction($callback, $value);
        if($this->posted)
            $error = checkDate($maand, $dag, $jaar) ? $error :  "$dag - $maand - $jaar: ". $this->_date;

        // put the field in the form
        $this->generateField($title, $field, $name,  $error);
    }

    // function to generate a FCK editor
    function Editor($title, $name, $config = array(), $callback = false, $pxWidth = 720, $pxHeight = 400) {
        // get lower case array key's
        $config = !is_array($config) ? array() : $this->makeSave($config, "strToLower");

        // get the full config
        foreach($this->defaultEditorCfg as $key => $value) {
            if(!$this->arrayKeyExists($key, $config) || empty($config[$key])) {
                $config[$key] = $this->defaultEditorCfg[$key];
            }
            if(is_bool($config[$key])) $config[$key] = $config[$key] ? "true":"false";
        }

        // value of the field(s)
        $value = $this->Value($name, true);
        $bgcolor = $this->value($name."_bgcolor");

        // make a editor if the browser is compatible
        if(!$this->FCKCompatible()) {
            $this->TextArea($title, $name);
            return;
        } else {
            $field = "<iframe src=\"$this->fckFile?FormName=$this->formName&FieldName=$name&Toolbar=".UCFirst($config["toolbar"])."&Browse=".($config["browse"]?"true":"false")."&Upload=".($config["upload"]?"true":"false")."\" width=\"$pxWidth\" height=\"$pxHeight\" frameborder=\"no\" scrolling=\"no\"></iframe>\n" ;
            $field .= "<input type=\"hidden\" name=\"$name\" value=\"$value\">\n";
            $field .= "<input type=\"hidden\" name=\"{$name}_bgcolor\" value=\"".
            (empty($bgcolor)?"#FFFFFF":$bgcolor)."\" />\n";
        }
        $this->fieldNames[] = "{$name}_bgcolor";
        $this->generateField($title, $field, $name, $this->callUserFunction($callback, $value));
    }

    // alias for SubmitBtn
    function SubmitButton() {
        $arg = func_get_args();
        call_user_func_array(array(&$this, "SubmitBtn"), $arg);
    }

    // function to generate the buttons
    function SubmitBtn($submit = true, $reset = true, $cancel = true, $cancelPage = 'javascript:history.back(1)') {
        // if the user hasn't "installed" a mask, set our own
        if(!$this->mask) {
            $originalMask = $this->setMask(
            " <tr>\n".
            "  <td colspan='3'>\n".
            "    %veld%\n".
            "   </td>\n".
            " </tr>\n");
        }

        // make the field
        $field = '';
        if($submit) {
             $submit = ($submit == '' || is_bool($submit)) ? $this->_save : $submit;
            $field .= "   <input type='submit' value=\"$submit\" title=\"$submit\" />\n";
        }
        if($reset) {
            $reset = ($reset == '' || is_bool($reset)) ? $this->_reset : $reset;
            $field .= "   <input type='reset' value=\"$reset\" title=\"$reset\" />\n";
        }
        if($cancel) {
            $cancel = ($cancel == '' || is_bool($cancel)) ? $this->_cancel : $cancel;
            $field .= "   <input type='button' onclick=\"".
            (eregi("^javascript:", $cancelPage)?$cancelPage:"location.href='$cancelPage'").
            "\" value=\"$cancel\" title=\"$cancel\" />\n";
        }

        // put the field in the form
        $this->generateField('', $field, '');

        // close the mask.. if opend
        if(isset($originalMask)) {
            $this->setMask($originalMask);
        }
    }

    // alias for ImageBtn
    function ImageButton() {
        $arg = func_get_args();
        call_user_func_array(array(&$this, "SubmitBtn"), $arg);
    }

    // function to add a image button in the form
    function ImageBtn($img, $title = '', $extra = '') {
        // if the user hasn't "installed" a mask, set our own
        if(!$this->mask) {
            $originalMask = $this->setMask(
            " <tr>\n".
            "  <td colspan='3'>\n".
            "    %veld%\n".
            "   </td>\n".
            " </tr>\n");
        }

        // put the field in the form
        $field = "   <input type='image' src='$img' title=\"$title\" $extra />\n";
        $this->generateField('', $field, '');

        // close the mask.. if opend
        if(isset($originalMask)) {
            $this->setMask($originalMask);
        }
    }

    // function to set a default value of a field
    function SetValue($field, $value, $makeSave = true, $overwriteCurrentValue = false) {
        if($makeSave) $value = $this->MakeSave($value);
        $this->setValues[$field] = array($value, $overwriteCurrentValue);
    }

    // function to "manualy" set a value into the (database) values
    function AddValue ($field, $value, $MySQL = false, $makeSave = true) {
        if($MySQL) {
            $this->SQLFields[] = $field;
        } else {
            $key = array_search($field, $this->SQLFields);
            if($key) unset($this->SQLFields[$key]);
        }

        $this->addValues[$field] = (!$makeSave) ? $value : HTMLSpecialChars($value);
    }

    // function to set the onCorrect function
    function OnCorrect($callback) {
        $this->OnCorrect = $callback;
    }

    // function to set the onSaved function
    function OnSaved($callback) {
        $this->OnSaved = $callback;
    }

    // Private: function to "make" a hidden field
    function Hidden ($name, $value, $extra = "") {
        $this->hidden .= "<input type='hidden' name=\"$name\" value=\"$value\" $extra />\n";
    }

    // Private: generate "where" clause
    function GetWhereClause() {
        if(!is_array($this->editId) && sizeof($this->dbPrKey) == 1) {
            return $this->dbPrKey[0] ." = '".mysql_escape_string($this->editId)."'";
        } elseif(is_array($this->editId) && count($this->dbPrKey) <= count($this->editId)) {
            // notice if to many paramaters are given
            if(count($this->dbPrKey) < count($this->editId)) {
                $this->error("To many parameters given!", E_USER_NOTICE, __FILE__, __LINE__);
            }

            $where = '';
            for($i = 0; $i < count($this->dbPrKey); $i++) {
                $where .= $this->dbPrKey[$i] ." = '". $this->editId[$i] ."' AND \n";
            }
            return substr($where, 0, -6);
        } else {
            $this->error("To few parameters given for editing a record!", E_USER_ERROR, __FILE__, __LINE__);
        }
    }

    // Private: upload the files
    function UploadFiles($fieldValues) {
        static $done = false;

        if(!$done) {
    		foreach($this->uploadFields as $field => $config) {
                if(($fn = $this->doUpload($this->Value($field), $config))) {
                    // need a resize ?
                    if($this->arrayKeyExists($field, $this->imageResize)) {
                        $this->doResize($config['path'], $fn, $this->imageResize[$field]);
    				}
                    if($fn != $fieldValues[$field]) {
                        $this->error("Upload error! $fn is niet gelijk aan ". $fieldValues[$field]."!", E_USER_WARNING, __FILE__, __LINE__);
                    }
    			}
    		}
            $done = true;
        }
    }

    // Private: function to return the HTML Form and table
    function GetForm($fields, $hidden = "", $extra = "") {
        if($this->maxUploadSize)
        $this->hidden('MAX_FILE_SIZE', $this->maxUploadSize);
        $this->hidden($this->formName.'_submit', 1);

        return
        "<!-- \n".
        "  NOTE: This form is automaticly generated by FormHandler.\n".
        "  See for more info: http://www.FormHandler.nl\n".
        "-->\n".
        "<form name='$this->formName' action='{$this->formAction}' ".
        "method='post' ". (count($this->uploadFields) ? 'enctype="multipart/form-data"' : '') . " {$this->formExtra} />\r\n".
        $this->hidden .
        "<table ".
        (intval($this->tableSettings['width']) ? "width='". $this->tableSettings['width']."' " : '').
        "cellspacing='".$this->tableSettings['cellspacing']."' ".
        "border='".        $this->tableSettings['border']."' ".
        "cellpadding='".$this->tableSettings['cellpadding']."' ".
        (empty($this->tableSettings['style'])?'':
        (eregi('^style', $this->tableSettings['style'])?$this->tableSettings['style']:
        "style=\"".str_replace('"', "'", $this->tableSettings['style'])."\""))." ".
        (empty($this->tableSettings['extra'])?'':$this->tableSettings['extra']).
        " >\n" . $fields . "</table>\n</form>\n";
    }

    // Private: function to make a confirmation form
    function ConfirmForm($returnTheForm) {
        // look if the fieldnames are known, otherwise, get them...
        if(!count($this->dbFields) && $this->useDB) {
            $this->getTableFields();
        }

        // before we generate the confirm form, make a array with the values
        $this->form = "";
        $this->hidden('__confirmation__', 1);
        $values = array();
        $upload_tmp_dir = false;
        foreach( $this->fieldNames as $field ) {
            // get the value of the field (even when it's a array)
            $orgValue = $value = $this->Value($field, true);
            if($this->ArrayKeyExists($field, $this->arrayValues)) {
                $value = is_array($value) ? $value : explode(",", str_replace(' ', '', $value));

                $newValue = array();
                foreach($value as $v) {
                    if(!empty($v))
                    $newValue[] = $this->arrayValues[$field][1] ? $this->arrayValues[$field][0][$v] : $v;
                }
                $value = implode("<br>\n", $newValue);

            } elseif($this->ArrayKeyExists($field, $this->uploadFields)) {

                // show the name of the original file if the field is an upload field
                if(!isset($value['error'])) $value['error'] = 4;
                if($value['tmp_name'] == 'none' || $value['tmp_name'] == '' || $value['error'] == 3 || $value['error'] == 4) {
                    $value = "-";
                } else {
                    // get the temp upload dir
                    if(!$upload_tmp_dir) {
                        $this->hidden('tmp_dir', dirname($value['tmp_name']));
                        $upload_tmp_dir = true;
                    }

                    // upload the file, only to the temp dir...
                    $config           = $this->uploadFields[$field];
                    $config['path']   = dirname($value['tmp_name']);
                    $config['exists'] = 'overwrite';
                    if(($fn = $this->doUpload($value, $config))) {
                        $value = $value["name"];
                        $this->hidden($field, "file:$fn");
                    }
                }
            } elseif(in_array($field, $this->PassFields)) {
                // hide the value if the field is a passfield
            } else {
                $value = nl2br($value);
            }

            // check if db is used, if so, only show the fields which exists in the table
            if(!empty($field) && !in_array($field, $this->ignoreFields)) {
                // put the field within the hidden fields (even when it's a array)
                if(is_array($orgValue)) {
                    if(!$this->ArrayKeyExists($field, $this->uploadFields)) {
                        foreach($orgValue as $key => $val) {
                            $this->hidden($field."[$key]", $val);
                        }
                    }
                } else {
                    $this->hidden($field, $orgValue);
                }

                // make a preview of the submitted data
                if($this->useDB) {
                    if($this->ArrayKeyExists($field, $this->dbFields)) {
                        $this->generateField($this->titles[$field], $value, $field);
                    }
                } else {
                    $this->generateField($this->titles[$field], $value, $field);
                }
            }
        }

        // button below the confirm form
        $this->SubmitBtn($this->confirm[1], false, $this->confirm[2], $this->confirm[3]);

        // temp
        if($returnTheForm) {
            return $this->confirm[0] . "\n\n". $this->getForm($this->form);
        } else {
           echo $this->confirm[0] . "\n\n". $this->getForm($this->form);
        }
    }
    
    // function which "generates" the form
    function FlushForm($returnTheForm = false) {
        $handle = null;   // output handler

        // is there a oncorrect or onsaved function ?
        if(!$this->OnCorrect && !$this->OnSaved) {
            $this->error("You didn't specify a 'commit after form' function!", E_USER_ERROR, __FILE__, __LINE__);
            
         } elseif(!$this->OnCorrect && !$this->useDB) {
            $this->error("You are using the function OnSaved but you don't use the database option! Use OnCorrect instead!", E_USER_ERROR, __FILE__, __LINE__);
        }

        // errors welke zijn voorgekomen in een var zetten
        $errors = &catchErrors();
        $errmsg = '';
        foreach($errors as $error) {
            $errmsg .= "<b>Error:</b> (".basename($error['err_file']) .":".$error['err_line'].") ". $error['err_text'] ."<br />\n";
        }

        // look if there are class errors 
        if($this->classError) {
            $this->form = "<h3>Error!</h3>\n". $this->classError;
            $handle = false;
        // is the form a editform ? (if so, is the user authorised??)
        } elseif($this->editForm && !$this->permitEdit) {
            $this->form =
            "<h3>Error!</h3>\n". $this->_edit .
            "<br /><br /><a href='javascript:history.back(1)'>".$this->_back."</a>\n";
            $handle = false;
        } else {
            // if the form is posted and there are no errors
            if(!$this->formErrors && $this->posted) {
                // is a confirmation needed and not posted yet ?
                if(is_array($this->confirm) && $this->Value("__confirmation__") == '') {
                    return $this->ConfirmForm($returnTheForm);
                } 

                 // close all borders
                while($this->fieldSetCounter > 0) {
                    $this->BorderStop();
                }

                // look if the fieldnames are known, otherwise, get them...
                if(!count($this->dbFields) && $this->useDB) {
                    $this->getTableFields();
                }

                $fieldValues = array();
                // before we generate a query, make an array with the fields and there values
                foreach( $this->fieldNames as $field ) {
                    if(!empty($field) && !in_array($field, $this->ignoreFields)) {
                         $fieldValues[$field] = $this->value($field);

                    }
                }

                // save the filename, but do not upload yet.. first save data..
                foreach($this->uploadFields as $field => $config) {
                    if(($fn = $this->GetFilename($this->Value($field), $config))) {
                        $this->AddValue($field, $fn);
                    } else {
                        $this->IgnoreFields[] = $field;
                    }
                }

                // but the values enterd by the user (by using addvalue) into the array
                foreach($this->addValues as $field => $value) {
                    $fieldValues[$field] = $value;
                }

                // call the oncorrect function
                if($this->OnCorrect) {
                    if(!$this->OnSaved) $this->uploadFiles($fieldValues);
                    $handle = $this->callUserFunction($this->OnCorrect, $fieldValues);
                }

                // again, put the values enterd by the user (by using addvalue) into the array
                // (it's possible the user entered some values in the oncorrect function)
                foreach($this->addValues as $field => $value) {
                    $fieldValues[$field] = $value;
                }

                // make the values ready for the query
                if(is_array($fieldValues)) {
                    foreach($fieldValues as $field => $value) {
                        // if the field is not an upload field ... (the value can be an manual entered value!)
                        if(!$this->arrayKeyExists($field, $this->uploadFields) || $this->arrayKeyExists($field, $this->addValues)) {
                            $value = is_array($value) ? implode(", ", $value) : $value;
                            $queryValues[$field] = !in_array($field, $this->SQLFields)? "'". mysql_escape_string($value) ."'": $value;
                        }
                    }
                }

                // make the query (update or insert) but check if there are values
                if($this->editForm && isset($queryValues)) {
                    // make the update query
                    $query = "UPDATE $this->dbTable SET \n";
                    foreach($queryValues as $field => $value) {
                        // check if the field exists in the table
                        if($this->arrayKeyExists($field, $this->dbFields)) {
                            $query .= "$field = $value, \n";
                        }
                    }

                    // remove the last ", \n" ans put the WHERE part at the end
                    $query = substr($query, 0, -3) . " WHERE " . $this->getWhereClause();

                } elseif(isset($queryValues)) {
                    $fields = '';
                    $values = '';
                    foreach($queryValues as $field => $value) {
                        // check if the field exists in the table
                        if($this->arrayKeyExists($field, $this->dbFields)) {
                            $fields .= "$field, \n";
                            $values .= "$value, \n";
                        }
                    }
                    if(!strlen($fields) && !strlen($values)) {
                        $query = false;
                    } else {
                        // generate the query
                        $query = "INSERT INTO $this->dbTable (\n".
                        substr($fields, 0, -3) .
                        ") VALUES (\n".
                        substr($values, 0, -3) . ")";
                    }
                } else {
                    $query = false;
                }

                // run the query
                //die($query); // <-- for debugging
                if($query)
                    $sql = $this->query($query, __FILE__, __LINE__);

                // get the record id
                $this->recordId = ($this->editForm) ? $this->editId[0] : ((isset($query) && $this->useDB) ? mysql_insert_id() : "Database functions are not used...");

                // run the onSaved function
                if($this->OnSaved) {
                    $this->uploadFiles($fieldValues);
                    if($query === false || !$this->useDB) {
                        $this->error("You are using the function OnSaved but you don't use the database option! Use OnCorrect instead!", E_USER_WARNING, __FILE__, __LINE__);
                    } else {
                        $handle = $this->callUserFunction($this->OnSaved, $this->recordId, $fieldValues);
                    }
                }

            // if there are errors or the form isnt posted yet, show the form
            } else {
                $handle = false;
            }

            // get the form and table tags...
            $this->form = $this->getForm($this->form);

            // set the forcus
            if($this->focusField) {
                $field = str_replace('[]', '', $this->focusField);
                if(in_array($field, $this->fieldNames)) {
                    $this->form .=
                    "<script type=\"text/javascript\">\n".
                    "<!-- // hide javascript for older browsers \n".
                    "document.forms['$this->formName'].elements['$field'].focus();\n".
                    " //-->\n".
                    "</script>\n";
                } else {
                    $this->error("Can't put focus on the field $field because it's unknown!", E_USER_WARNING, __FILE__, __LINE__);
                }
            }

            // javascript for the listfields
            $javascript = '';
            if(count($this->ListFields) > 0) {
                $javascript .=
                "function changeValue(prefix, install) {\n".
                "    // set the fields\n".
                "    var FromField = document.forms['$this->formName'].elements[prefix+(install?\"_ListOff\":\"_ListOn\")];\n".
                "    var ToField   = document.forms['$this->formName'].elements[prefix+(install?\"_ListOn\":\"_ListOff\")];\n\n".
                "    // is a value selected?\n".
                "    if(FromField.value != \"\") {\n".
                "        // get the number of values from the selected list\n".
                "        var len = ToField.options.length;\n\n".
                "        // remove empty options\n".
                "        for(i = 0; i < len; i++ ) {\n".
                "            if(ToField.options[i].value == '') ToField.options[i] = null\n".
                "         }\n".
                "        // add the new option\n".
                "        len = ToField.options.length;\n".
                "        ToField.options[len] = new Option(FromField.options[FromField.selectedIndex].text);\n".
                "        ToField.options[len].value = FromField.options[FromField.selectedIndex].value;\n".
                "        ToField.options[len].selected = true;\n\n".
                "        // delete the option from the 'old' list\n".
                "        FromField.options[FromField.selectedIndex] = null;\n".
                "        FromField.focus();\n".
                "    }\n\n".
                "    // update the hidden field which contains the selected values\n".
                "    var InstalledVars = \" \";\n".
                "    var Installed = document.forms['$this->formName'].elements[prefix+'_ListOn'];\n\n".
                "    for(i = 0; i < Installed.options.length; i++) {\n".
                "        InstalledVars += Installed.options[i].value + \", \";\n".
                "    }\n".
                "    document.forms['$this->formName'].elements[prefix+'_Value'].value = InstalledVars;\n".
                "}\n";
            }
            // if a upload field is used, put the javascript in the form
            if(count($this->uploadFields)) {
                $javascript .=
                "function checkUpload(elem, ext) {\n".
                "    var types = ext.split(' ');\n".
                "    var fp = elem.value.split('.');\n".
                "    var extension = fp[fp.length-1].toLowerCase();\n".
                "    for(var i = 0; i < types.length; i++ ) {\n".
                "        if(types[i] == extension) return true;\n".
                "    }\n".
                "    var message = \"".HTMLSpecialChars($this->_uploadType)."\"\n".
                "    message = message.replace('%s', ext);\n".
                "    alert(message);\n".
                "    return false;\n".
                "}\n";
            }

            // if isset some javascript, put it into these tags (javascript open and close tags)
            $javascript = !empty($javascript) ?
            "\n".
              "<!-- \n".
             "  NOTE: This form is automaticly generated by FormHandler.\n".
            "  See for more info: http://www.FormHandler.nl\n".
            "-->\n".
            "<!-- required javascript for the form -->\n".
            "<script type=\"text/javascript\">\n".
            "<!-- // hide javascript for older browsers \n".
            $javascript .
            " //-->\n".
            "</script>\n".
            "<!--  /required javascript for the form -->\n\n":"";
        }
        
        // reset the original error_handler
        if(!is_null($this->orgErrorHandler))
            set_error_handler($this->orgErrorHandler);

        // return or print the form...
        if(is_null($handle)) $handle = true;
        if(!$handle) {
            if(!isset($javascript)) $javascript = '';
            if($returnTheForm) {
                return $errmsg . $javascript . $this->form;
            } else {
                echo $errmsg . $javascript . $this->form;
            }
        } else {
            $handle = !is_string($handle)?'':$handle;
            if($returnTheForm) {
                return $errmsg . $handle;
            } else {
                echo $errmsg . $handle;
            }
        }
    }
}

?>
