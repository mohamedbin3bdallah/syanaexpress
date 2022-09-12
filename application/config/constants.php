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

define('USERALREADY', "User already registered");
define('USERRAGISTER', "User has been registered successfully. Please check you email.");
define('USERRAGISTER_AR', "تم تسجيل المستخدم بنجاح ");
define('LOGINSUCCESSFULL',"User login successfully");
define('LOGINSUCCESSFULL_AR',"تم الدخول بنجاح  ");
define('LOGINFAIL',"Login fail please check your email id and password");
define('LOGINFAIL_AR',"فشل تسجيل الدخول يرجى التحقق من المعلومات المدخلة");
define('BOOKING_DECLINE', "Booking Decline successfully.");
define('BOOKING_DECLINE_AR', "تم رفض الطلب بنجاح. ");
define('BOOKING_ACCEPTED', "Booking accepted successfully.");
define('BOOKING_ACCEPTED_AR', "تم قبول الطلب بنجاح. ");
define('BOOKING_STARTED_SUCCESSFULLY', "Booking Started successfully.");
define('BOOKING_STARTED_SUCCESSFULLY_AR', "بدأ الطلب بنجاح. ");
define('QUALIFICATION_DELETE', "Qualification deleted successfully.");
define('QUALIFICATION_DELETE_AR', "تم حذف التأهيل بنجاح. ");
define('PRODUCT_DELETE', "Product deleted successfully.");
define('PRODUCT_DELETE_AR', "تم حذف المنتج بنجاح. ");
define('QUALIFICATION_UPDATE', "Qualification updated successfully.");
define('QUALIFICATION_UPDATE_AR', "تم تحديث التأهيل بنجاح. ");
define('GALLERY_IMG_DELETE', "Gallery image deleted successfully.");
define('GALLERY_IMG_DELETE_AR', "تم حذف الصورة بنجاح. ");
define('APPLIED_SUCCESS', "Applied successfully.");
define('APPLIED_SUCCESS_AR', "تم التقديم بنجاح. ");
define('COUPON_NOT_VLID', "Coupon code not valid.");
define('COUPON_NOT_VLID_AR', "رمز الكوبون غير صحيح.");
define('PRODUCT_ADD_CART', "Product Add on your cart successfully.");
define('PRODUCT_ADD_CART_AR', "أضف المنتج إلى عربة التسوق الخاصة بك بنجاح. ");
DEFINE("CART_UPDATE","CART UPDATE");
define("CART_UPDATE_AR","تحديث السلة");

define('FOUND',"Your password updated successfully. Please check your email address.");
define('FOUND_AR',"تم تغيير كلمة المرور الخاصة بك بنجاح.. ");
define('NOTFOUND',"No data found.");
define('NOTFOUND_AR',"لاتوجد بيانات. ");
define('USER_NOT_FOUND',"User not found");
define('USER_NOT_FOUND_AR',"المستخدم ليس موجود ");

define('NOTUPDATE',"Password not update");
define('AVAILABLE',"User available");
define('NOTAVAILABLE',"User not find");
define('NOTAVAILABLE_AR',"لم يتم العثور على البيانات ");


define('EDITFAIL',"Profile has been not updated");

define('USERNOTFOND',"Profile not fount you have now registered as artist");
define('USERNOTFOND_AR',"لم يتم العثور على الملف الشخصي ، لقد قمت بالتسجيل الآن كبطل صيانة ");

define('NOT_ACTIVE',"Your profile is under review. Please contact to Admin.");
define('NOT_ACTIVE_AR',"ملفك الشخصي قيد المراجعة. يرجى التواصل مع الادارة ");

define('PASS_NT_MTCH',"Invalid Password.");
define('PASS_NT_MTCH_AR',"كلمة المرور خاطئة ");
/*Get All Category*/
define('ALL_CAT',"Get all Categories");
define('ALL_CAT_AR',"جميع الاقسام ");

/*Get All Countries*/
define('ALL_CNT',"Get all Countires");
define('ALL_CNT_AR',"جميع الدول ");

/*Get All Countries*/
define('ALL_CITIES',"Get all Cities");
define('ALL_CITIES_AR',"جميع المدن ");


define('APPOINTMENT_COMPLET', "Appointment completed successfully.");
define('APPOINTMENT_COMPLET_AR', "اكتمل الموعد بنجاح.");

define('APPOINTMENT_DECLINE', "Appointment decline successfully.");
define('APPOINTMENT_DECLINE_AR', "تم رفض الموعد بنجاح. ");

define('EARNING', "Get my earning");
define('EARNING_AR', "احصل على أرباحي  ");

define('GET_MY_CART', "Get my Cart.");
define('GET_MY_CART_AR', "احصل على عربة التسوق الخاصة بي. ");

/*No data*/
define('NO_DATA',"No data found.");
define('NO_DATA_AR',"لاتوجد بيانات. ");

/*Validation Error*/
define('VALIDATION_ERR',"Validation Error.");
define('VALIDATION_ERR_AR',"لم يتم ارسال البيانات المطلوبة");

/*Addresses */
define('ADDRESS_ADDED',"Address added successfully.");
define('ADDRESS_ADDED_AR',"تم اضافة العنوان بنجاح");
define('UNEXPECTED_ERR', "Un-expected Error");
define('UNEXPECTED_ERR_AR', "خطأ غير متوقع");
define('USER_ADDRESSES', "User Addresses List");
define('ALL_Payment_Methods', "All Payment Methods");
define('ALL_Payment_Methods_AR', "قائمة طرق الدفع");
define('ORDER_ADDED',"Order added successfully.");
define('ORDER_ADDED_AR',"تم اضافة الطلب بنجاح");


define('LOCATION_REQUIRED',"Country ID and City ID are required.");
define('LOCATION_REQUIRED_AR',"لابد من ادخال الدولة والمدينة");

define('RECROD_DELETED',"Record Deleted Successfully");
define('RECROD_DELETED_AR',"تم حذف السجل بنجاح");

/*Get All Category*/
define('ALL_SKILLS',"Get all Skills");
define('ALL_SKILLS_AR',"كل المهارات  ");

define('ALL_DATA',"All Data");
define('ALL_DATA_AR',"كل البيانات  ");

/*Get All Category*/
define('ALL_ARTISTS',"Get all vendors");
define('ALL_ARTISTS_AR',"احصل على جميع ابطال الصيانة ");

define("GET_APPROVAL_STATUS","Get Approval status");
define("GET_APPROVAL_STATUS_AR","الحالة");

/*Artist Update*/
define('ARTIST_UPDATE',"Vendor updates successfully.");
define('ARTIST_UPDATE_AR',"تم تحديث بطل الصيانة بنجاح. ");

/*Something went to wrong. Please try again later.*/
define('TRY_AGAIN',"Opps it seems that server is under manitaince please wait for while.");
define('TRY_AGAIN_AR',"عذرا ، يبدو أن الخادم تحت الصيانة ، يرجى الانتظار لبعض الوقت. ");

/*Product Added successfully.*/
define('PRODUCT_ADD',"Product Added successfully.");
define('PRODUCT_ADD_AR',"تمت إضافة المنتج بنجاح. ");

/*Qualification Added successfully.*/
define('QUALIFICATION_ADD',"Qualification Added successfully.");
define('QUALIFICATION_ADD_AR',"تمت إضافة التأهيل بنجاح. ");

/*Comment Added successfully.*/
define('ADD_COMMENT',"Comment Added successfully.");
define('ADD_COMMENT_AR',"تمت إضافة التعليق بنجاح. ");

/*Please Check you Email*/
define('CHECK_MAIL',"Please Check you Email and active your account by email verification. Thank you");

/*Gallery image added successfully.*/
define('ADD_GALLERY',"Gallery image added successfully.");
define('ADD_GALLERY_AR',"تمت إضافة الصورة بنجاح. ");

/*Appointment booked successfully.*/
define('BOOK_APP',"Booking confirmed successfully.");
define('BOOK_APP_AR',"تم تأكيد الطلب بنجاح. ");

define('NO_APPOINTMENT_FOUND', "No appointment found.");
define('NO_APPOINTMENT_FOUND_AR', "لم يتم العثور على موعد. ");

define('NO_JOB_FOUNT', "No job found in starting state. Please try after sometime.");
define('NO_JOB_FOUNT_AR', "لم يتم العثور على صفقة . يرجى المحاولة بعد مرور بعض الوقت. ");

define('JOB_FINISHED', "Job finished successfully.");
define('JOB_FINISHED_AR', "انتهى العمل بنجاح. ");

define('All_Booking_Orders', "All Booking Orders.");
define('All_Booking_Orders_AR', "كل سجلات الطلبات");



/*Get all Appointments*/
define('GET_APP',"Get all Appointments");
define('GET_APP_AR',"احصل على جميع المواعيد ");

define('NOT_ACT',"Your profile is under review. Please contact to Admin");
define('NOT_ACT_AR',"ملفك الشخصي قيد المراجعة. يرجى الاتصال بالادارة ");

define('IN_USER',"Invalid user key.");
define('IN_USER_AR',"مفتاح المستخدم غير صالح. ");

/*CURRENCY TYPE*/
define('CURRENCY_TYPE',"USD");

/*Booking end successfully*/
define('BOOKING_END',"Booking end successfully. Please go inside the invoice section and check the unpaid invoice for amount confirmation and get the paid soon.");
define('BOOKING_END_AR',"انتهى الطلب بنجاح. يرجى الدخول إلى قسم الفواتير والتحقق من الفاتورة غير المدفوعة لتأكيد المبلغ والحصول على المبلغ قريبًا. ");

/*"Get my current booking."*/
define('CURRENT_BOOKING',"Get my current booking.");
define('CURRENT_BOOKING_AR',"احصل على طلبي الحالي  ");

/*Get my invoice.*/
define('MY_INVOICE',"Get my invoice.");
define('MY_INVOICE_AR',"احصل على فاتورتي. ");

/*Payment Confirm successfully*/
define('PAYMENT_CONFIRM',"Payment Confirm successfully.");
define('PAYMENT_CONFIRM_AR',"تم الدفع بنجاح. ");
/*Appointment declined successfully*/
define('APP_DECLINE',"Appointment declined successfully.");
define('APP_DECLINE_AR',"تم رفض الطلب بنجاح. ");

/*Registration Email Subject*/
define('REG_SUB',"FabArtist Registration");

/*Password Email Subject*/
define('PWD_SUB',"FabArtist Password");

/*Invoice Email Subject*/
define('IVE_SUB','FabArtist Invoice');

/*Database Tables*/
/*appointment table*/
define('APP_TBL','appointment');
/*user table*/
define('USR_TBL','user');
/*artist table*/
define('ART_TBL','artist');
/*artist table*/
define('CAT_TBL','category');
/*artist_booking table*/
define('ABK_TBL','artist_booking');
/*booking_invoice*/
define('IVC_TBL','booking_invoice');
/*Chat table*/
define('CHT_TBL','chat');
/*discount_coupon table*/
define('DCP_TBL','discount_coupon');
/*gallery table*/
define('GLY_TBL','gallery');
/*notifications table*/
define('NTS_TBL','notifications');

/*notifications table*/
define('AJB_TBL','applied_job');

/*countries table*/
define('CNT_TBL','countries');

/*cities table*/
define('CITIES_TBL','cities');


/*NOtification Type*/
define('BOOK_ARTIST_NOTIFICATION','10001');
define('DECLINE_BOOKING_ARTIST_NOTIFICATION','10002');
define('START_BOOKING_ARTIST_NOTIFICATION','10003');
define('END_BOOKING_ARTIST_NOTIFICATION','10004');
define('CANCEL_BOOKING_ARTIST_NOTIFICATION','10005');
define('ACCEPT_BOOKING_ARTIST_NOTIFICATION','10006');
define('CHAT_NOTIFICATION','10007');
define('USER_BLOCK_NOTIFICATION','1008');
define('TICKET_COMMENT_NOTIFICATION','10009');
define('WALLET_NOTIFICATION','10010');
define('JOB_NOTIFICATION','10011');
define('JOB_APPLY_NOTIFICATION','10012');
define('DELETE_JOB_NOTIFICATION','10013');
define('BRODCAST_NOTIFICATION','10014');
define('TICKET_STATUS_NOTIFICATION','10015');
define('ADMIN_NOTIFICATION','10016');

/*Subscription Type*/
define('FREE', 'Free');
define('MONTHLY', 'Monthly');
define('QUARTERLY', 'Quarterly');
define('HALFYEARLY', 'Half Yearly');
define('YEARLY', 'Yearly');

define('ALL_PACKAGES', "Get all Packages.");
define('ALL_PACKAGES_AR', "احصل على جميع الباقات. ");

define('PKG_NOT', "Package not found.");
define('PKG_NOT_AR', "الحزمة غير موجودة. ");


define('SUB_SUCCESS', 'Subscription successfully.');
define('SUB_SUCCESS_AR', 'تم الاشتراك بنجاح. ');

define('ALRAEDY_SUB', 'Already Subscribed.');
define('ALRAEDY_SUB_AR', 'مشترك بالفعل. ');

define('SUB_HISTORY', 'Subscription history found.');
define('SUB_HISTORY_AR', 'تم العثور على سجل الاشتراك. ');

define('MY_SUB', 'Get my Subscription.');
define('MY_SUB_AR', 'احصل على اشتراكي. ');

define('NOT_SUB', 'You are not Subscribed user.');
define('NOT_SUB_AR', 'أنت لست مستخدم مشترك. ');
/*Get All Category*/
define('ALL_FIELD_MANDATORY',"All Fields are mandatory.");
define('ALL_FIELD_MANDATORY_AR',"جميع الحقول إلزامية. ");

define('PLZ_UP_PRF', "Please update your profile. Then applied on a job.");
define('PLZ_UP_PRF_AR', "يرجى تحديث ملف التعريف الخاص بك. ثم تقدم على وظيفة. ");



define('DTL_UPLD', "Your Detail Uploaded successfully.");
define('DTL_UPLD_AR', "تم تحميل التفاصيل الخاصة بك بنجاح. ");

define('FILL_PAY', "Please fill your palpay detail.");
define('FILL_PAY_AR', "يرجى ملء التفاصيل الخاصة بك palpay.");

define('GET_MYPAY', "Get my paypal detail.");
define('GET_MYPAY_AR', "احصل على تفاصيل باي بال الخاصة بي. ");

/*Coupon Screen text*/
define('COUPON_TEXT',"Get extra credit for you and your friend when you invite your friends to download the application and use your code when registering.");

define('COUPON_TEXT_AR',"احصل على رصيد اضافي لك ولصديقك عند دعوة اصدقائك لتحميل التطبيق واستخدام الكود الخاص بك عند التسجيل");

/*notifications table*/
define('SENDER_EMAIL','samyotechindore@gmail.com');

define('CHT_NEW_TBL','chat_new');
/*discount_coupon table*/
define('CRYSET_TBL','currency_setting');

define('PYL_DTS_TBL','paypal_deatils');

define('APP_NAME','FabArtist');
define('APP_NAME_AR','FabArtist');
define('TOPICS_FOR_ARTIST','Artist');
define('TOPICS_FOR_CUSTOMER','Customer');

define('TOPICS_FOR_ARTISTS','/topics/Artist');
define('TOPICS_FOR_CUSTOMERS','/topics/Customer');


define('ENTER_VALID_COUPON_CODE', "Please enter valid coupon code.");
define('ENTER_VALID_COUPON_CODE_AR', "الرجاء إدخال كود الكوبون بشكل صحيح ");

/*Firebase notifications Key for user*/
define('USER_FIREBASE_KEY','AAAAIgNxz_g:APA91bGgeYJkwlKmgqq4CT8bYSb6qCCR9jD60GOj_Ts1981HTs7sokQ8hwn7-vLO2UgjLkvFSB2QwfbyRy8eWp38tUSBHfyA8frS4GASyXQN_MtWbW9xVTgttKZEDgpACWl7Wuxy6dHZpBQJkJ0_-E7MEMZEgj886A');

/*Firebase notifications Key for Artist*/
define('ARTIST_FIREBASE_KEY','AAAA42W1QqI:APA91bFUmU8VWCnNVIWEdHkNRgyIDPgjam0ztrGSu2KtQESWMskUfb74S2VZumCbslmhQ6bV6oJBNqkbVxwtHrrrs5GmhLdGwxr-d3_i7KjCl6HH56BAPoCQUXZXY0BG1wACEsw2Q5wl');

define('SENDER_EMAILL','info@samyotech.com');
define('MSG_AUTH_KEY',"205521Ay0uGpRMiR5da996d7");
define('MOBILE_CODE',"966");

define('MOBILE_EXIST',"Mobile Exist");
define('MOBILE_EXIST_AR',"هذا الرقم مسجل مسبقا");

define('INVALID_REQUEST', "Invalid Request");
define('INVALID_REQUEST_AR', "طلب غير صحيح ");

define('NO_APPOINT_FOUND_STARTING', "No Appointments found in starting state. Please try after sometime.");
define('NO_APPOINT_FOUND_STARTING_AR', "لم يتم العثور على مواعيد حاليا. يرجى المحاولة بعد مرور بعض الوقت. ");


define('MOBILE_EMPTY','Mobile empty');
define('MOBILE_EMPTY_AR','فضلا ادخل رقم الجوال');

define("GET_ARTIST_DETAIL","Get artist detail.");
define("GET_ARTIST_DETAIL_AR","معلومات بطل الصيانة");

define("TICKET_GENERATED_SUCCESSFULLY","Ticket generated successfully.");
define("TICKET_GENERATED_SUCCESSFULLY_AR","تم انشاء تذكرتك بنجاح");

define("GET_MY_TICKETS","Get my tickets");
define("GET_MY_TICKETS_AR","الحصول على تذكرتي");


define("NOT_YET_ANY_TICKETS","Not yet any tickets.");
define("NOT_YET_ANY_TICKETS_AR","لا سوجد تذاكر");

define("THANKS_FOR_THE_REVIEW","Thanks for the review");
define("THANKS_FOR_THE_REVIEW_AR","شكرا لتقييمك");

define("GET_TICKET_COMMENTS","Get ticket comments.");
define("GET_TICKET_COMMENTS_AR","الحصول على تعليق التذكرة");

define("GET_MY_NOTIFICATIONS","Get my notifications.");
define("GET_MY_NOTIFICATIONS_AR","اشعارتي");
define('LOCATION_UPDT', "Location updated successfully.");
define('LOCATION_UPDT_AR', "تم تحديث الموقع بنجاح. ");
define('NEW_JOB_AVAILABLE', "Hey, New job available for you. Please check job section.");
define('NEW_JOB_AVAILABLE_AR', "مرحبًا ، صفقة جديدة متاحة لك. يرجى مراجعة قسم الصفقات ");
define('JOB_ADD', "Job added successfully.");
define('JOB_ADD_AR', "تمت إضافة الصفقة بنجاح. ");
define('JOB_UPDT', "Job Updated Successfully.");
define('JOB_UPDT_AR', "تم تحديث الصفقة بنجاح. ");
define('USER_NOT_REGISTER', "User not register");
define('USER_NOT_REGISTER_AR', "المستخدم غير مسجل ");
define('JOB_ID_NOT_AVAILABLE', "Job id not available.");
define('JOB_ID_NOT_AVAILABLE_AR', "معرّف الصفقة غير متوفر. ");
define('NO_JOBS_AVAILABLE', "No jobs available.");
define('NO_JOBS_AVAILABLE_AR', "لا توجد صفقات متاحة. ");
define("ALL_JOBS_FOUND","All Jobs Found");
define("ALL_JOBS_FOUND_AR","جميع الطلبات");
define('JOB_CONFIRM_SUCCESSFULLY', "Job confirm successfully.");
define('JOB_CONFIRM_SUCCESSFULLY_AR', "تم تأكيد الصفقة بنجاح. ");
define("YOUR_REQUEST_IS_CONFIRM","Your request is confirm.");
define("YOUR_REQUEST_IS_CONFIRM_AR","تم تعميد طلبك");

define('JOB_REJECTED', "Job Rejected successfully.");
define('JOB_REJECTED_AR', "تم رفض الصفقة بنجاح. ");

define("NOT_YET_ANY_NOTIFICATIONS","Not yet any notifications");
define("NOT_YET_ANY_NOTIFICATIONS_AR","لا يوجد اشعارات");
define('GET_MY_PROF_STATUS', "Get my profile status.");
define('GET_MY_PROF_STATUS_AR', "احصل على حالة ملفي الشخصي. ");
define('NOT_RESPONDING', "Server not responding");
define('NOT_RESPONDING_AR', "الخادم لا يستجيب  ");
define('NOT_CONVER', "Not yet any conversation.");
define('NOT_CONVER_AR', "لا توجد اي محادثة");
define('LOGOUTARTIST',"Artist logout successfully.");
define('LOGOUTARTIST_AR',"تم تسجيل الخروج بنجاح. ");
define('MSG_SENT_SUCCESS', "Message sent successfully");
define('MSG_SENT_SUCCESS_AR', "تم ارسال الرسالة بنجاح ");
define('GET_CONVER', "Get my conversation.");
define('GET_CONVER_AR', "احصل على محادثتي. ");
define('GET_CAHT', "Get chat history.");
define('GET_CAHT_AR', "احصل على سجل الدردشة.");

define("REMOVE_CART","REMOVE CART");
define("REMOVE_CART_AR","حذف السلة");

define("PASSWORD_UPDATED","Password Updated");
define("PASSWORD_UPDATED_AR","تم تحديث كلمة المرور");

define("PASSWORD_DOES_NOT_MATCH","Password does not match");
define("PASSWORD_DOES_NOT_MATCH_AR","كلمة المرور غير متطابقة");

define("PASSWORD_FIELD_CAN","Password field can't be empty");
define("PASSWORD_FIELD_CAN_AR","لا يمكن ترك خانة كلمة المرور فارغة");

define("SOMETHING_IS_WRONG_TRY_AGAIN_SOMETIME","Something is wrong try again sometime.");
define("SOMETHING_IS_WRONG_TRY_AGAIN_SOMETIME_AR","هناك خطاء ما نرجو المحاولة لاحقا");

define("YOU_RECENTLY_REQUESTED_A_PASSWORD"," You recently requested a password reset for your account. To complete the process, click the link below.");
define("YOU_RECENTLY_REQUESTED_A_PASSWORD_AR"," لاتمام عملية ضبط كلمة المرور انقر على الرابط ادناه");

define("FOR_RESET_PASSWORD_CLICK","For reset password click the link below");
define("FOR_RESET_PASSWORD_CLICK_AR","لاعادة ضبط كلمة المرور انقر الرابط ادناه");

define('ARTIST_ONLINE', "Artist online successfully.");
define('ARTIST_ONLINE_AR', "بطل الصيانة متوفر. ");

define('ARTIST_OFFLINE', "Artist offline successfully.");
define('ARTIST_OFFLINE_AR', "بطل الصيانة غير متصل  ");

define('EMAIL_EXIST', "Email id Already Exists.");
define('EMAIL_EXIST_AR', "معرف البريد الإلكتروني موجود بالفعل. ");

define('EDITSUCCESSFULL',"Profile has been updated successfully");
define('EDITSUCCESSFULL_AR',"تم تحديث الملف الشخصي بنجاح ");

define('OLD_PASS', "Old Password does not matched.");
define('OLD_PASS_AR', "كلمة المرور القديمة غير متطابقة. ");

define("DEBIT_IN_YOUR_WALLET_BY_BOOKING"," debit in your wallet by booking.");
define("DEBIT_IN_YOUR_WALLET_BY_BOOKING_AR"," عمولة طلبات");

define("CREDIT_IN_YOUR_WALLET_BY_BOOKING","credit in your wallet by booking.");
define("CREDIT_IN_YOUR_WALLET_BY_BOOKING_AR","رصيد طلبات");

define('INITIATE_PAYMENT',"Initiate payment.");
define('INITIATE_PAYMENT_AR',"بدء الدفع. ");

/*Something went to wrong. Please try again later.*/


define("HAS_UPDATED_PLEASE_CHANGES",": has updated. Please check the changes .");
define("HAS_UPDATED_PLEASE_CHANGES_AR",": تم التحديث بنجاح");

define("HAS_DELETED_BY_USER",": has deleted by user.");
define("HAS_DELETED_BY_USER_AR",": تم الحذف من قبل المستخدم");

define('CURRENT_ARTIST_WORK', "Currently artist working on this job.");
define('CURRENT_ARTIST_WORK_AR', "حاليا بطل الصيانة يعمل في هذه الصفقة. ");

define('YOUR_REQUEST_PENDING', "You request is already pending. Admin will approval your request.");
define('YOUR_REQUEST_PENDING_AR', "طلبك معلق بالفعل. برجاء الانتظار حتى تتم الموافقة على طلبك ");
define('THNX_FOR_REQUEST', "Thanks for the request. We will approved your request shortly");
define('THNX_FOR_REQUEST_AR', "شكرا على الطلب. سنوافق على طلبك بعد قليل  ");
define('AMOUNT_ADD', "Amount added successfully. Please check your wallet.");
define('AMOUNT_ADD_AR', "تمت إضافة المبلغ بنجاح. يرجى التحقق من محفظتك. ");

define("HAS_ACCEPTED_YOUR_APPOINTMENT",": has accepted your appointment.");
define("HAS_ACCEPTED_YOUR_APPOINTMENT_AR",": تم قبول طلبك");

define('APPOINTMENT_ACCEPTED_SUCCESSFULLY', "Appointment accepted successfully.");
define('APPOINTMENT_ACCEPTED_SUCCESSFULLY_AR', "تم قبول الموعد بنجاح.");

define('APPOINTMENT_REJECT', "Appointment rejected successfully.");
define('APPOINTMENT_REJECT_AR', "تم رفض الموعد بنجاح. ");

define('ALREADY_FAVORITES', "Already Favorites.");
define('ALREADY_FAVORITES_AR', "مضاف الى المفضلة مسبقاً ");

define('ADD_FAVORITES_SUCCESSFULLY', "Add Favorites Successfully.");
define('ADD_FAVORITES_SUCCESSFULLY_AR', "تم اضافتة الى المفضلة.");
define('REMOVE_FAVORITES_SUCCESSFULLY', "Remove Favorites Successfully.");
define('REMOVE_FAVORITES_SUCCESSFULLY_AR', "تمت إلازالة ");
define("FAILED","Failed");
define("FAILED_AR","فشل فى الحفظ");
define('ALLREADY_APPLIED', "Allready applied");
define('ALLREADY_APPLIED_AR', "تم التطبيق مسبقًا ");

define('CONFIRM_JOB', "Please confirm job first");
define('CONFIRM_JOB_AR', "الرجاء تأكيد الصفقة أولا ");
define('JOB_COMPLETE_SUCCESSFULLY', "Job Complete successfully.");
define('JOB_COMPLETE_SUCCESSFULLY_AR', "اكتملت الصفقة بنجاح. ");

define('JOB_DELETE', "Job deleted successfully.");
define('JOB_DELETE_AR', "تم حذف الصفقة بنجاح. ");
define('COMMISSION_TYPE_CHANGE', "Commission type change successfully.");
define('COMMISSION_TYPE_CHANGE_AR', "تم تغيير نوع العمولة بنجاح.");
define('ARTIST_BUSY_ANOTHER_CLIENT', "Artist Busy with another client. Please try after sometime.");
define('ARTIST_BUSY_ANOTHER_CLIENT_AR', "بطل الصيانة مشغول مع عميل آخر. يرجى المحاولة بعد مرور بعض الوقت. ");
define("OTP_SEND","OTP Send");
define("OTP_SEND_AR","OTP Send");
define('PROFILE_IMG_DELETE', "Profile image deleted successfully.");
define('PROFILE_IMG_DELETE_AR', "تم حذف صورة الملف الشخصي بنجاح. ");
define("YOU_ALREADY_GIVE","you already give review this artist.");
define("YOU_ALREADY_GIVE_AR","لقد قمت بتقيم هذا البطل من قبل");

define("UNDER_REVIEW","Your Profile Under Review");
define("UNDER_REVIEW_AR","حسابك تحت المراجعة");

define('JOB_APPLIED_SUCCESSFULLY', "Job applied successfully.");
define('JOB_APPLIED_SUCCESSFULLY_AR', "تم تقديم الصفقة بنجاح. ");


define("NOT_ADDED","Not added");
define("NOT_ADDED_AR","لم يتم الاضافة");

define("GET_FILTER_ITEMS","Get filter items");
define("GET_FILTER_ITEMS_AR","تصفية");

define('WALLET_HISTORY', "Get my wallet history.");
define('WALLET_HISTORY_AR', "احصل على ارشيف محفظتي. ");

define('WALLET', "Get my wallet");
define('WALLET_AR', "محفظتي  ");

define("GET_MY_POINTS","Get my points");
define("GET_MY_POINTS_AR","نقاطي");

define('EMPTY_MOBILE',"empty mobile");
define('EMPTY_MOBILE_AR',"حذف الجوالe");

define('APPLIED_JOBS_FOUND','Applied Jobs Found');
define('APPLIED_JOBS_FOUND_AR','صفقات مقبولة');

define("GET_CURRENCY_TYPE","Get currency type");
define("GET_CURRENCY_TYPE_AR","نوع العملة");

define("BOOKED_YOU_ON",'booked you on');
define("BOOKED_YOU_ON_AR",'طل في');

define('BOOK_APPOINTMENT','Book Appointment');
define('BOOK_APPOINTMENT_AR','حجز موعد');

define("IS_DECLINE_YOUR_APPOINTMENT","is decline your appointment.");
define("IS_DECLINE_YOUR_APPOINTMENT_AR","قام برفض طلبك");

define('DECLINE_APPOINTMENT','Decline Appointment');
define('DECLINE_APPOINTMENT_AR','قام برفض حجزك');

define('YOUR_BOOKING_STARTED_SUCCESSFULLY','Your booking started successfully.');
define('YOUR_BOOKING_STARTED_SUCCESSFULLY_AR','تم بدء طلبك بنجاح');

define("START_BOOKING","Start Booking");
define("START_BOOKING_AR","بدء الحجز");

define("APPLIED_SUCCESSFULLY","Applied successfully.");
define("APPLIED_SUCCESSFULLY_AR","تم التقديم بنجاح");

define("GET_MY_EARNING","get my earning");
define("GET_MY_EARNING_AR","ارباحي");

define("GET_MY_REFERRAL_CODE","Get my Referral Code.");
define("GET_MY_REFERRAL_CODE_AR","كود الدعوة");

define("HAS_REJECTED_YOUR_APPOINTMENT_AR","قام برفض موعدك");
define("HAS_REJECTED_YOUR_APPOINTMENT","has rejected your appointment");
define("HAS_DECLINE_YOUR_APPOINTMENT","has decline your appointment");
define("HAS_DECLINE_YOUR_APPOINTMENT_AR","قام برفض الموعد");

define("HAS_CHANGED_YOUR_BOOKING","has changed your booking.");
define("HAS_CHANGED_YOUR_BOOKING_AR","قا م بتغير طلبك");

define("NO_JOB_AVAILABLE","No job available");
define("NO_JOB_AVAILABLE_AR","No job available");

define("END_BOOKING","End Booking");
define("END_BOOKING_AR","End Booking");

define("FOR_SERVICE"," .Invoice For following services: ");
define("FOR_SERVICE_AR"," الخدمات ");

define("RECORD_ADDED_SUCCESSFULLY","Record Added Successfully");
define("RECORD_ADDED_SUCCESSFULLY_AR","تمت إضافة السجل بنجاح");

define("SOMTHING_WRONG_HAPPENED","Somthing Wrong Happened");
define("SOMTHING_WRONG_HAPPENED_AR","حدث شيء خطأ");

define("AMOUNT_LARGER_THAN_WALLET","Amount Larger Than Wallet");
define("AMOUNT_LARGER_THAN_WALLET_AR","المبلغ أكبر من المحفظة");

define("NO_ARTIST_WALLET_RECORD","No Artist Wallet Record");
define("NO_ARTIST_WALLET_RECORD_AR","لا توجد محفظة");

define("NO_ARTIST_POINTS_RECORD","No Artist Points Record");
define("NO_ARTIST_POINTS_RECORD_AR","لا توجد نقاط");

define("REWARD_LARGER_THAN_ARTIST_POINTS","Reward Larger Than Artist Points");
define("REWARD_LARGER_THAN_ARTIST_POINTS_AR","المكاقاة اكبر من النقاط");

define("ACTION_DONE_SUCCESSFULLY","Action Done Successfully");
define("ACTION_DONE_SUCCESSFULLY_AR","تمت العملية بنجاح");

define("LOGIN","Please Enter a valid Token");
define("LOGIN_AR","من فضلك تحقق من صلاحية رمز التحقق");

define("USER_ADDRESS_NOT_VALID","Please Enter a valid User address");
define("USER_ADDRESS_NOT_VALID_AR","من فضلك أدخل User Address صحيح");