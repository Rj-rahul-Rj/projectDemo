<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
// custome constant
defined('token_expire')      OR define('token_expire', '+5 hours'); // seconds, hours, minutes
defined('now')      OR define('now',strtotime(date('d-m-Y H:i:s'))); // 
defined('default_profile')      OR define('default_profile',''); // 
defined('store_default_img')      OR define('store_default_img','https://alphaxtech.net/ecity/default/store_default.png'); // 
defined('product_default_img')      OR define('product_default_img','https://alphaxtech.net/ecity/default/store_default.png'); // 

defined('email')         OR define('email',"mmf.phpdeveloper2@gmail.com"); 
defined('gpassword')      OR define('gpassword',"Gouri@MMF.980#"); // 
defined('mailfrom')      OR define('mailfrom',"mmf.phpdeveloper2@gmail.com"); // 
defined('mailfromname')      OR define('mailfromname',"Ecity"); // 
defined('replyto')       OR define('replyto',"mmf.phpdeveloper2@gmail.com"); // 
defined('replytoname')   OR define('replytoname',"Ecity Reply"); // 
 
defined('cinetPayapikey')   OR define('cinetPayapikey',"19031599135ff441c7b70865.48289010"); // 
defined('cinetPaysiteId')   OR define('cinetPaysiteId',"551054"); // 
defined('cinetPayurl')   OR define('cinetPayurl',"https://secure.cinetpay.com"); // 
defined('cinetPaysignatureUrl')   OR define('cinetPaysignatureUrl',"https://api.cinetpay.com/v1/?method=getSignatureByPost"); // 

//  payout details
defined('APIpassword')   OR define('APIpassword','a}T\,!E#8"S[9\'j'); // 
defined('payoutTokenURL')   OR define('payoutTokenURL',"https://client.cinetpay.com/v1/auth/login?lang=fr"); // 



defined('FCM_KEY')   OR define('FCM_KEY',"AAAAqYTjDiE:APA91bEfioC6UHFk8mdbedNQwFgIeMzbFmEItr71ikjLhYVa6uA7vCKVn0xiGkAirjmi8wAZFqjVvkPoG2yePjFl3A4zX9fqE1mawEA_IXah9RgEhGnO00O3xx49awiiZSeYSDFr-3O5"); 
//// fcm configration
//defined('FCM_KEY')   OR define('FCM_KEY',"AAAA9Ys41bI:APA91bHx0vB_I3YmaVTy3OfMW2J3qd8vCnGDyyLNi4j9qr0hXmkzS5BBR1-MNdKpdrc1-UKeDPybDAb-RUmvVlwwWsTf0L8uy5hKT9QAgI6pK_zWLOKgRCBYFzV_jLx58XJgADibTgH3"); // 
