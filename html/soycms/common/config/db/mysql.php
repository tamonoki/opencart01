<?php // DO NOT delete this line.

/* CHANGE MySQL configuration below and RENAME this file to "mysql.php". */

// mysql configuration - start
define("ADMIN_DB_DSN","mysql:host=localhost;port=3306;dbname=soycms");
define("ADMIN_DB_USER","root");
define("ADMIN_DB_PASS","itopj");
// mysql configuration - end


/* DO NOT change the lines below. */
define("ADMIN_DB_EXISTS",file_exists(SOY2::RootDir()."db/cms.db"));
define("CMS_FILE_DB",ADMIN_DB_DSN);
define("CMS_FILE_DB_EXISTS",file_exists(SOY2::RootDir()."db/file.db"));
