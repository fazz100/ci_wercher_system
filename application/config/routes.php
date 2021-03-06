<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Main_Controller';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['Dashboard'] = 'Main_Controller/Dashboard';
$route['Employee'] = 'Main_Controller/Employee';
$route['ViewEmployee'] = 'Main_Controller/ViewEmployee';
$route['ModifyEmployee'] = 'Main_Controller/ModifyEmployee';
$route['Applicants'] = 'Main_Controller/V_Applicants';
$route['ApplicantsExpired'] = 'Main_Controller/V_ApplicantsExpired';
$route['NewEmployee'] = 'Main_Controller/NewEmployee';
$route['Admin_List'] = 'Main_Controller/View_Admins';
$route['Clients'] = 'Main_Controller/Clients';
$route['Experimental'] = 'Main_Controller/Experimental';
$route['Archived'] = 'Main_Controller/V_Archived';
$route['Blacklisted'] = 'Main_Controller/V_Blacklisted';
$route['SSS_Table'] = 'Main_Controller/SSS_Table';

$route['Payroll'] = 'Main_Controller/PayrollClients';
$route['ViewClient'] = 'Main_Controller/ViewClient';
$route['Payrollsss'] = 'Main_Controller/Payrollsss';

$route['GenerateIDCard'] = 'Main_Controller/GenerateIDCard';
$route['PrintEmployee'] = 'Main_Controller/PrintEmployee';

$route['Search'] = 'Main_Controller/Search';
$route['Logbook'] = 'Main_Controller/Logbook';
$route['Calendar'] = 'Main_Controller/Calendar';

// LOGIN
$route['LoginValidation'] = 'Login_Controller/LoginValidation';
// CREATE
$route['addNewEmployee'] = 'Add_Controller/addNewEmployee';
$route['Add_NewAdmin'] = 'Add_Controller/Add_NewAdmin';
$route['Add_newClient'] = 'Add_Controller/Add_newClient';
$route['AddSupDoc'] = 'Add_Controller/AddSupDoc';
$route['AddthisSss'] = 'Add_Controller/AddthisSss';




// DELETE
$route['RemoveEmployee'] = 'Delete_Controller/RemoveEmployee';
$route['RemoveAdmin'] = 'Delete_Controller/RemoveAdmin';
$route['RemoveClient'] = 'Delete_Controller/RemoveClient';

// UPDATE
$route['EmployApplicant'] = 'Update_Controller/EmployApplicant';
$route['ExtendContract'] = 'Update_Controller/ExtendContract';
$route['UpdateEmployee'] = 'Update_Controller/UpdateEmployee';
$route['AddNote'] = 'Update_Controller/AddNote';
$route['AddNoteDocuments'] = 'Update_Controller/AddNoteDocuments';
$route['SetReminder'] = 'Update_Controller/SetReminder';
$route['BlacklistEmployee'] = 'Update_Controller/BlacklistEmployee';
$route['RestoreEmployee'] = 'Update_Controller/RestoreEmployee';
$route['SetWeeklyHours'] = 'Update_Controller/SetWeeklyHours';
$route['ViewClientEmployees'] = 'Update_Controller/ViewClientEmployees';
$route['ImportExcel'] = 'Update_Controller/ImportExcel';
$route['UpdateSSSField'] = 'Update_Controller/UpdateSSSField';
$route['Suspend'] = 'Update_Controller/Suspend';
