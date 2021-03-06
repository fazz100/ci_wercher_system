 <?php
 defined('BASEPATH') OR exit('No direct script access allowed');

 class Main_Controller extends CI_Controller {

 	public function __construct() {
 		parent::__construct();
 		$this->load->model('Model_Selects');
		$this->load->model('Model_Updates'); // TODO: Remove after fixing the call belooooow.
		$this->load->model('Model_Inserts'); // TODO: Remove after fixing the call belooooow.
		// echo $_SERVER['REMOTE_ADDR'] . '<br>';
		// echo $_SERVER['HTTP_USER_AGENT'];
		$GetEmployee = $this->Model_Selects->GetEmployee();
		$GetClient = $this->Model_Selects->getClientOption();
		date_default_timezone_set('Asia/Manila');
		$currTime = date('Y-m-d h:i:s A');
		// TODO: Don't call this here. Need a real time checker. Find a better solution than this.
		foreach ($GetEmployee->result_array() as $row) {
			// Assigns a new ID after successfully hiring
			if ($row['Temp_ApplicantID'] != NULL && $row['Temp_ApplicantID'] == $row['ApplicantID']) {
				$this->Model_Updates->UpdateApplicantID($row['Temp_ApplicantID']);
			}
			$ApplicantID = $row['ApplicantID'];
			if ($row['ReminderLocked'] != 'Yes'){
				if (strtotime($row['DateEnds']) < (strtotime($currTime) + strtotime($row['ReminderDate'])) && strtotime($row['DateEnds']) > strtotime($currTime)) {
					$LogbookInsert = $this->Model_Updates->ReminderLocked($ApplicantID);
					// LOGBOOK
					date_default_timezone_set('Asia/Manila');
					$LogbookCurrentTime = date('Y-m-d h:i:s A');
					$LogbookType = 'Reminder';
					$LogbookEvent = 'Employee ' . $ApplicantID . ' is expiring in ' . $row['ReminderDateString'] . '!';
					$LogbookLink = base_url() . 'ViewEmployee?id=' . $ApplicantID;
					$data = array(
						'Time' => $LogbookCurrentTime,
						'Type' => $LogbookType,
						'Event' => $LogbookEvent,
						'Link' => $LogbookLink,
					);
					$LogbookInsert = $this->Model_Inserts->InsertLogbook($data);
				}
			}
			if (strtotime($row['DateEnds']) < strtotime($currTime)) {
				foreach ($GetEmployee->result_array() as $row) {
					foreach ($GetClient->result_array() as $nrow) {
						if ($row['ClientEmployed'] == $nrow['ClientID']) {
							$ClientName = $nrow['Name'];
						}
					}
				}

				if ($ApplicantID == NULL) {
					$this->session->set_flashdata('prompts','<div class="text-center" style="width: 100%;padding: 21px; color: #F52F2F;"><h5><i class="fas fa-times"></i> Something\'s wrong, Please try again!</h5></div>');
				}
				else
				{
					$CheckEmployee = $this->Model_Selects->CheckEmployee($ApplicantID);
					if ($CheckEmployee->num_rows() > 0) {
						$data = array(
							'ApplicantID' => $ApplicantID,
							'PreviousDateStarted' => $row['DateStarted'],
							'PreviousDateEnds' => $row['DateEnds'],
							'Client' => $ClientName,
						);
						$InsertContractHistory = $this->Model_Inserts->InsertContractHistory($data);
						$ApplicantExpired = $this->Model_Updates->ApplicantExpired($ApplicantID);
						if ($ApplicantExpired == TRUE) {
							$this->session->set_flashdata('prompts','<div class="text-center" style="width: 100%;padding: 21px; color: #45C830;"><h5><i class="fas fa-check"></i> Employee ' . $ApplicantID . ' has expired!</h5></div>');
							// LOGBOOK
							date_default_timezone_set('Asia/Manila');
							$LogbookCurrentTime = date('Y-m-d h:i:s A');
							$LogbookType = 'Update';
							$LogbookEvent = 'Employee ' . $ApplicantID . ' has expired!';
							$LogbookLink = base_url() . 'ViewEmployee?id=' . $ApplicantID;
							$data = array(
								'Time' => $LogbookCurrentTime,
								'Type' => $LogbookType,
								'Event' => $LogbookEvent,
								'Link' => $LogbookLink,
							);
							$LogbookInsert = $this->Model_Inserts->InsertLogbook($data);
						}
						else
						{
							$this->session->set_flashdata('prompts','<div class="text-center" style="width: 100%;padding: 21px; color: #F52F2F;"><h5><i class="fas fa-times"></i> Something\'s wrong, Please try agains!</h5></div>');
						}
					}
					else
					{
						$this->session->set_flashdata('prompts','<div class="text-center" style="width: 100%;padding: 21px; color: #F52F2F;"><h5><i class="fas fa-times"></i> Something\'s wrong, Please try againss!</h5></div>');
					}
				}
			}
			if ($row['Suspended'] != '' && strtotime($row['SuspensionEnds']) < strtotime($currTime)) {
				if ($ApplicantID == NULL) {
					$this->session->set_flashdata('prompts','<div class="text-center" style="width: 100%;padding: 21px; color: #F52F2F;"><h5><i class="fas fa-times"></i> Something\'s wrong, Please try again!</h5></div>');
				}
				else
				{
					$CheckEmployee = $this->Model_Selects->CheckEmployee($ApplicantID);
					if ($CheckEmployee->num_rows() > 0) {
						$SuspensionExpires = $this->Model_Updates->SuspensionExpires($ApplicantID);
						if ($SuspensionExpires == TRUE) {
							$this->session->set_flashdata('prompts','<div class="text-center" style="width: 100%;padding: 21px; color: #45C830;"><h5><i class="fas fa-check"></i> Employee ' . $ApplicantID . ' is no longer suspended.</h5></div>');
							// LOGBOOK
							date_default_timezone_set('Asia/Manila');
							$LogbookCurrentTime = date('Y-m-d h:i:s A');
							$LogbookType = 'Update';
							$LogbookEvent = 'Employee ' . $ApplicantID . ' is no longer suspended.';
							$LogbookLink = base_url() . 'ViewEmployee?id=' . $ApplicantID;
							$data = array(
								'Time' => $LogbookCurrentTime,
								'Type' => $LogbookType,
								'Event' => $LogbookEvent,
								'Link' => $LogbookLink,
							);
							$LogbookInsert = $this->Model_Inserts->InsertLogbook($data);
						}
						else
						{
							$this->session->set_flashdata('prompts','<div class="text-center" style="width: 100%;padding: 21px; color: #F52F2F;"><h5><i class="fas fa-times"></i> Something\'s wrong, Please try agains!</h5></div>');
						}
					}
					else
					{
						$this->session->set_flashdata('prompts','<div class="text-center" style="width: 100%;padding: 21px; color: #F52F2F;"><h5><i class="fas fa-times"></i> Something\'s wrong, Please try againss!</h5></div>');
					}
				}
			}
		}

	}
	public function index()
	{
		$this->session->unset_userdata('acadcart');
		// redirect('Dashboard');
		$this->load->view('Login');
	}
	public function CheckUserLogin()
	{
		if (!isset($_SESSION['is_logged_in'])) {
			$p_text = '<div class="text-center" style="width: 100%;padding: 21px; color: #F52F2F;"><i class="fas fa-times fa-fw"></i> User login required!</div>';
			$this->session->set_flashdata('prompt',$p_text);
			redirect('');
			exit();
		}
	}
	public function Dashboard()
	{
		$this->load->model('Model_Deletes');
		unset($_SESSION["acadcart"]);
		unset($_SESSION["emp_cart"]);
		unset($_SESSION["mach_cart"]);

		###	CHECK SESSION
		// $this->CheckUserLogin();

		$header['title'] = 'Dashboard | Wercher Solutions and Resources Workers Cooperative';
		$data['T_Header'] = $this->load->view('_template/users/u_header',$header);

		// CHART
		$result =  $this->Model_Selects->GetApplicantSkills();

		$record = $result->result();
		$data = [];

		// WEEKLY INCREASE
		$CurrentDay = date('Y-m-d h:i:s A');
		$CurrentDayScope = date('Y-m-d h:i:s A', strtotime('-7 days', strtotime($CurrentDay)));
		$data['WeeklyApplicants'] = $this->Model_Selects->GetApplicantsIncrease($CurrentDay, $CurrentDayScope)->num_rows();
		$data['WeeklyEmployees'] = $this->Model_Selects->GetEmployeesIncrease($CurrentDay, $CurrentDayScope)->num_rows();

		foreach($record as $row) {
			$data['label'][] = $row->PositionGroup;
			$data['data'][] = (int) $row->count;
		}
		$data['chart_data'] = json_encode($data);
		$edata = [];
		$GetApplicantSkillsExpired = $this->Model_Selects->GetApplicantSkillsExpired();
		$edata['data'][] = $GetApplicantSkillsExpired->num_rows();
		foreach($GetApplicantSkillsExpired->result_array() as $row) {
			$edata['label'][] = $row['PositionGroup'];
		}
		$data['chart_data_expired'] = json_encode($edata);
		$data['Breadcrumb'] = '
		<nav aria-label="breadcrumb">
		<ol class="breadcrumb" style="background-color: transparent;">
		<li class="breadcrumb-item" aria-current="page"><a class="wercher-breadcrumb-active" href="Dashboard">Dashboard</a></li>
		</ol>
		</nav>';
		// COUNT ADMIN
		$data['result_cadmin'] =  $this->Model_Selects->GetAdmin();
		// COUNT APPLICANTS
		$data['result_capp'] =  $this->Model_Selects->GetTotalApplicants();
		// COUNT EMPLOYEE
		$data['result_cemployee'] =  $this->Model_Selects->GetEmployee();
		// COUNT CLIENT
		$data['result_cclients'] =  $this->Model_Selects->GetClients();
		// LOGBOOK
		$data['GetLogbook'] =  $this->Model_Selects->GetLogbook();
		// COUNT MONTHLY TOTAl
		$CurrentYear = date('Y');
		$Year = $CurrentYear;
		$Month = date('01');
		$data['CurrentYear'] = $CurrentYear;
		if (isset($_GET['Year'])) {
			$Year = $this->input->get('Year');
			$CountMonthlyTotal =  $this->Model_Selects->CountMonthlyTotal();
			if ($CountMonthlyTotal->num_rows() > 144) { // Truncates cache (Database) after 12 years of history
				$this->Model_Deletes->CleanDashboardMonths($CurrentYear);
				for ($i = 0; $i < 12; $i++) {
					$MonthAdd = date('m', strtotime('+' . $i . ' month', strtotime($Month)));
					$sql = array(
						'Year' => $Year,
						'Month' => $MonthAdd,
						'Total' => '0'
					);
					$this->Model_Inserts->InsertDashboardMonths($sql);
					$this->Model_Inserts->InsertToGraph();
					if ($i == 11) {
						$data['result_monthly'] = $this->Model_Selects->GetMonthlyTotal($Year);
						$data['result_monthly_current_year'] =  $this->Model_Selects->GetMonthlyTotal($CurrentYear);
						$data['SelectedYear'] = $Year;
						$CountTotal = 0;
						foreach ($this->Model_Selects->GetMonthlyTotal($CurrentYear)->result_array() as $row) {
							$CountTotal = $CountTotal + $row['Total'];
						}
						$data['CurrentYearTotal'] = $CountTotal;
						$CountTotal = 0;
						foreach ($this->Model_Selects->GetMonthlyTotal($Year)->result_array() as $row) {
							$CountTotal = $CountTotal + $row['Total'];
						}
						$data['SelectedYearTotal'] = $CountTotal;
						$this->load->view('users/u_dashboard',$data);
					}
				}
			} else {
				$YearChecker =  $this->Model_Selects->GetMonthlyTotal($Year);
				if ($YearChecker->num_rows() < 12) { // Loads faster if already on cache (Database)
					for ($i = 0; $i < 12; $i++) {
						$MonthAdd = date('m', strtotime('+' . $i . ' month', strtotime($Month)));
						$sql = array(
							'Year' => $Year,
							'Month' => $MonthAdd,
							'Total' => '0'
						);
						$this->Model_Inserts->InsertDashboardMonths($sql);
						$this->Model_Inserts->InsertToGraph();
						if ($i == 11) {
							$data['result_monthly'] = $this->Model_Selects->GetMonthlyTotal($Year);
							$data['result_monthly_current_year'] =  $this->Model_Selects->GetMonthlyTotal($CurrentYear);
							$data['SelectedYear'] = $Year;
							$CountTotal = 0;
							foreach ($this->Model_Selects->GetMonthlyTotal($CurrentYear)->result_array() as $row) {
								$CountTotal = $CountTotal + $row['Total'];
							}
							$data['CurrentYearTotal'] = $CountTotal;
							$CountTotal = 0;
							foreach ($this->Model_Selects->GetMonthlyTotal($Year)->result_array() as $row) {
								$CountTotal = $CountTotal + $row['Total'];
							}
							$data['SelectedYearTotal'] = $CountTotal;
							$this->load->view('users/u_dashboard',$data);
						}
					}
				} else {
					if ($CountMonthlyTotal->num_rows() > 144) {
						$this->Model_Deletes->CleanDashboardMonths();
					}
					$this->Model_Inserts->InsertToGraph();
					$data['result_monthly'] = $this->Model_Selects->GetMonthlyTotal($Year);
					$data['result_monthly_current_year'] =  $this->Model_Selects->GetMonthlyTotal($CurrentYear);
					$data['SelectedYear'] = $Year;
					$CountTotal = 0;
					foreach ($this->Model_Selects->GetMonthlyTotal($CurrentYear)->result_array() as $row) {
						$CountTotal = $CountTotal + $row['Total'];
					}
					$data['CurrentYearTotal'] = $CountTotal;
					$CountTotal = 0;
					foreach ($this->Model_Selects->GetMonthlyTotal($Year)->result_array() as $row) {
						$CountTotal = $CountTotal + $row['Total'];
					}
					$data['SelectedYearTotal'] = $CountTotal;
					$this->load->view('users/u_dashboard',$data);
				}
			}
		} else {
			$YearChecker =  $this->Model_Selects->GetMonthlyTotal($CurrentYear);
			if ($YearChecker->num_rows() < 12) { // Cache on first Dashboard visit
				for ($i = 0; $i < 12; $i++) {
					$MonthAdd = date('m', strtotime('+' . $i . ' month', strtotime($Month)));
					$sql = array(
						'Year' => $CurrentYear,
						'Month' => $MonthAdd,
						'Total' => '0'
					);
					$this->Model_Inserts->InsertDashboardMonths($sql);
					$this->Model_Inserts->InsertToGraph();
					if ($i == 11) {
						$data['result_monthly'] = $this->Model_Selects->GetMonthlyTotal($CurrentYear);
						$data['result_monthly_current_year'] =  $this->Model_Selects->GetMonthlyTotal($CurrentYear);
						$data['SelectedYear'] = $CurrentYear;
						$CountTotal = 0;
						foreach ($this->Model_Selects->GetMonthlyTotal($CurrentYear)->result_array() as $row) {
							$CountTotal = $CountTotal + $row['Total'];
						}
						$data['CurrentYearTotal'] = $CountTotal;
						$CountTotal = 0;
						foreach ($this->Model_Selects->GetMonthlyTotal($Year)->result_array() as $row) {
							$CountTotal = $CountTotal + $row['Total'];
						}
						$data['SelectedYearTotal'] = $CountTotal;
						$this->load->view('users/u_dashboard',$data);
					}
				}
			} else {
				$data['result_monthly_current_year'] =  $this->Model_Selects->GetMonthlyTotal($CurrentYear);
				$data['SelectedYear'] = $CurrentYear;
				$CountTotal = 0;
				foreach ($this->Model_Selects->GetMonthlyTotal($CurrentYear)->result_array() as $row) {
					$CountTotal = $CountTotal + $row['Total'];
				}
				$data['CurrentYearTotal'] = $CountTotal;
				$data['SelectedYearTotal'] = $CountTotal;
				$this->load->view('users/u_dashboard',$data);
			}
		}
		
	}
	
	public function V_Applicants()
	{
		unset($_SESSION["acadcart"]);
		unset($_SESSION["emp_cart"]);
		unset($_SESSION["mach_cart"]);

		$header['title'] = 'Applicants | Wercher Solutions and Resources Workers Cooperative';
		$data['T_Header'] = $this->load->view('_template/users/u_header',$header);
		$data['Breadcrumb'] = '
		<nav aria-label="breadcrumb">
		<ol class="breadcrumb" style="background-color: transparent;">
		<li class="breadcrumb-item" aria-current="page"><a class="wercher-breadcrumb-active" href="Applicants">Applicants</a></li>
		</ol>
		</nav>';
		$data['get_employee'] = $this->Model_Selects->getApplicant();
		$data['get_ApplicantExpired'] = $this->Model_Selects->getApplicantExpired();
		$data['getClientOption'] = $this->Model_Selects->getClientOption();
		$this->load->view('users/u_applicant',$data);

	}
	public function V_ApplicantsExpired()
	{
		unset($_SESSION["acadcart"]);
		unset($_SESSION["emp_cart"]);
		unset($_SESSION["mach_cart"]);

		$header['title'] = 'Expired Contracts | Wercher Solutions and Resources Workers Cooperative';
		$data['T_Header'] = $this->load->view('_template/users/u_header',$header);
		$data['Breadcrumb'] = '
		<nav aria-label="breadcrumb">
		<ol class="breadcrumb" style="background-color: transparent;">
		<li class="breadcrumb-item" aria-current="page"><a href="Applicants">Applicants</a></li>
		<li class="breadcrumb-item" aria-current="page"><a class="wercher-breadcrumb-active" href="ApplicantsExpired">Expired</a></li>
		</ol>
		</nav>';
		$data['get_employee'] = $this->Model_Selects->getApplicant();
		$data['get_ApplicantExpired'] = $this->Model_Selects->getApplicantExpired();
		$data['getClientOption'] = $this->Model_Selects->getClientOption();
		$this->load->view('users/u_applicantexpired',$data);
	}
	public function V_Archived()
	{
		unset($_SESSION["acadcart"]);
		unset($_SESSION["emp_cart"]);
		unset($_SESSION["mach_cart"]);

		$header['title'] = 'Archived | Wercher Solutions and Resources Workers Cooperative';
		$data['T_Header'] = $this->load->view('_template/users/u_header',$header);
		$data['Breadcrumb'] = '
		<nav aria-label="breadcrumb">
		<ol class="breadcrumb" style="background-color: transparent;">
		<li class="breadcrumb-item" aria-current="page"><a href="Applicants">Applicants</a></li>
		<li class="breadcrumb-item" aria-current="page"><a class="wercher-breadcrumb-active" href="Blacklisted">Blacklisted</a></li>
		</ol>
		</nav>';
		$data['get_employee'] = $this->Model_Selects->getApplicant();
		$data['get_ApplicantExpired'] = $this->Model_Selects->getApplicantExpired();
		$data['GetArchived'] = $this->Model_Selects->GetApplicantArchived();
		$data['getClientOption'] = $this->Model_Selects->getClientOption();
		$this->load->view('users/u_archived',$data);
	}
	public function V_Blacklisted()
	{
		unset($_SESSION["acadcart"]);
		unset($_SESSION["emp_cart"]);
		unset($_SESSION["mach_cart"]);

		$header['title'] = 'Blacklisted | Wercher Solutions and Resources Workers Cooperative';
		$data['T_Header'] = $this->load->view('_template/users/u_header',$header);
		$data['Breadcrumb'] = '
		<nav aria-label="breadcrumb">
		<ol class="breadcrumb" style="background-color: transparent;">
		<li class="breadcrumb-item" aria-current="page"><a href="Applicants">Applicants</a></li>
		<li class="breadcrumb-item" aria-current="page"><a class="wercher-breadcrumb-active" href="Archived">Archived</a></li>
		</ol>
		</nav>';
		$data['get_employee'] = $this->Model_Selects->getApplicant();
		$data['get_ApplicantExpired'] = $this->Model_Selects->getApplicantExpired();
		$data['GetBlacklisted'] = $this->Model_Selects->GetApplicantBlacklisted();
		$data['getClientOption'] = $this->Model_Selects->getClientOption();
		$this->load->view('users/u_blacklisted',$data);
	}
	public function Employee()
	{
		unset($_SESSION["acadcart"]);
		unset($_SESSION["emp_cart"]);
		unset($_SESSION["mach_cart"]);

		$header['title'] = 'Employees | Wercher Solutions and Resources Workers Cooperative';
		$data['T_Header'] = $this->load->view('_template/users/u_header',$header);
		$data['Breadcrumb'] = '
		<nav aria-label="breadcrumb">
		<ol class="breadcrumb" style="background-color: transparent;">
		<li class="breadcrumb-item" aria-current="page"><a class="wercher-breadcrumb-active" href="Employee">Employee</a></li>
		</ol>
		</nav>';
		$data['get_employee'] = $this->Model_Selects->GetEmployee();
		$data['get_ApplicantExpired'] = $this->Model_Selects->getApplicantExpired();
		$data['getClientOption'] = $this->Model_Selects->getClientOption();
		$this->load->view('users/u_users',$data);
	}
	public function ViewEmployee()
	{
		unset($_SESSION["acadcart"]);
		unset($_SESSION["emp_cart"]);
		unset($_SESSION["mach_cart"]);

		if (isset($_GET['id'])) {

			$id = $_GET['id'];

			$header['title'] = 'Information | Wercher Solutions and Resources Workers Cooperative';
			$data['T_Header'] = $this->load->view('_template/users/u_header',$header);

			$GetEmployeeDetails = $this->Model_Selects->GetEmployeeDetails($id);

			if ($GetEmployeeDetails->num_rows() > 0) {
				$ged = $GetEmployeeDetails->row_array();
				$data = array(
					'ApplicantNo' => $ged['ApplicantNo'],
					'ApplicantImage' => $ged['ApplicantImage'],
					'EmployeeID' => $ged['EmployeeID'],
					'ApplicantID' => $ged['ApplicantID'],
					'PositionDesired' => $ged['PositionDesired'],
					'PositionGroup' => $ged['PositionGroup'],
					'SalaryExpected' => $ged['SalaryExpected'],
					'LastName' => $ged['LastName'],
					'FirstName' => $ged['FirstName'],
					'MiddleInitial' => $ged['MiddleInitial'],
					'Gender' => $ged['Gender'],
					'Age' => $ged['Age'],
					'Height' => $ged['Height'],
					'Weight' => $ged['Weight'],
					'Religion' => $ged['Religion'],
					'BirthDate' => $ged['BirthDate'],
					'BirthPlace' => $ged['BirthPlace'],
					'Citizenship' => $ged['Citizenship'],
					'CivilStatus' => $ged['CivilStatus'],
					'No_OfChildren' => $ged['No_OfChildren'],
					'Phone_No' => $ged['Phone_No'],
					'Address_Present' => $ged['Address_Present'],
					'Address_Provincial' => $ged['Address_Provincial'],
					'Address_Manila' => $ged['Address_Manila'],

					'SSS_No' => $ged['SSS_No'],
					'EffectiveDateCoverage' => $ged['EffectiveDateCoverage'],
					'ResidenceCertificateNo' => $ged['ResidenceCertificateNo'],
					'Rcn_At' => $ged['Rcn_At'],
					'Rcn_On' => $ged['Rcn_On'],
					'TIN' => $ged['TIN'],
					'TIN_At' => $ged['TIN_At'],
					'TIN_On' => $ged['TIN_On'],

					'HDMF' => $ged['HDMF'],
					'HDMF_At' => $ged['HDMF_At'],
					'HDMF_On' => $ged['HDMF_On'],

					'PhilHealth' => $ged['PhilHealth'],
					'PhilHealth_At' => $ged['PhilHealth_At'],
					'PhilHealth_On' => $ged['PhilHealth_On'],

					'ATM_No' => $ged['ATM_No'],

					'Status' => $ged['Status'],


					'ClientEmployed' => $ged['ClientEmployed'],
					'DateStarted' => $ged['DateStarted'],
					'DateEnds' => $ged['DateEnds'],
					'AppliedOn' => $ged['AppliedOn'],

					'SuspensionStarted' => $ged['SuspensionStarted'],
					'SuspensionEnds' => $ged['SuspensionEnds'],
					'SuspensionRemarks' => $ged['SuspensionRemarks'],
					'Suspended' => $ged['Suspended'],

					'ReminderDate' => $ged['ReminderDate'],
					'ReminderDateString' => $ged['ReminderDateString'],

					'NameExtension' => $ged['NameExtension'],
					'EmergencyPerson' => $ged['EmergencyPerson'],
					'EmergencyContact' => $ged['EmergencyContact'],
					'Referral' => $ged['Referral'],

				);
				$ApplicantID = $ged['ApplicantID'];
				$data['GetAcadHistory'] = $this->Model_Selects->GetEmployeeAcadhis($ApplicantID);
				$data['employment_record'] = $this->Model_Selects->GetEmploymentDetails($ApplicantID);
				$data['Machine_Operatessss'] = $this->Model_Selects->Machine_Operatessss($ApplicantID);
				$data['get_employee'] = $this->Model_Selects->GetEmployee();
				$data['getClientOption'] = $this->Model_Selects->getClientOption();
				$data['ShowClients'] = $this->Model_Selects->GetClients();
				$data['GetContractHistory'] = $this->Model_Selects->GetContractHistory($ApplicantID);
				$data['GetPreviousContract'] = $this->Model_Selects->GetPreviousContract($ApplicantID);
				$data['GetViolations'] = $this->Model_Selects->GetViolations($ApplicantID);
				$data['GetDocuments'] = $this->Model_Selects->GetDocuments($ApplicantID);
				$data['GetDocumentsViolations'] = $this->Model_Selects->GetDocumentsViolations($ApplicantID);
				$data['GetDocumentsNotes'] = $this->Model_Selects->GetDocumentsNotes($ApplicantID);
				$data['GetEmployeeMatchingClient'] = $this->Model_Selects->GetEmployeeMatchingClient($ApplicantID);
				if ($data['Status'] == 'Employed') {
					$data['Breadcrumb'] = '
					<nav aria-label="breadcrumb">
					<ol class="breadcrumb" style="background-color: transparent;">
					<li class="breadcrumb-item" aria-current="page"><a href="Employee">Employee</a></li>
					<li class="breadcrumb-item" aria-current="page"><a class="wercher-breadcrumb-active" href="ViewEmployee?id=' . $ApplicantID .'">' . $ged['LastName'] . ', ' . $ged['FirstName'] . ' ' . $ged['MiddleInitial'] . '.' . '</a></li>
					</ol>
					</nav>';
				} else {
					$data['Breadcrumb'] = '
					<nav aria-label="breadcrumb">
					<ol class="breadcrumb" style="background-color: transparent;">
					<li class="breadcrumb-item" aria-current="page"><a href="Applicants">Applicants</a></li>
					<li class="breadcrumb-item" aria-current="page"><a class="wercher-breadcrumb-active" href="ViewEmployee?id=' . $ApplicantID .'">' . $ged['LastName'] . ', ' . $ged['FirstName'] . ' ' . $ged['MiddleInitial'] . '.' . '</a></li>
					</ol>
					</nav>';
				}
				$this->load->view('users/u_viewemployee',$data);
			}
			else
			{
				redirect('Employee');
			}
		}
		else
		{
			redirect('Employee');
		}
	}
	public function PrintEmployee()
	{
		unset($_SESSION["acadcart"]);
		unset($_SESSION["emp_cart"]);
		unset($_SESSION["mach_cart"]);

		if (isset($_GET['id'])) {

			$id = $_GET['id'];

			$header['title'] = 'Print Employee | Wercher Solutions and Resources Workers Cooperative';
			$data['T_Header'] = $this->load->view('_template/users/u_header',$header);

			$GetEmployeeDetails = $this->Model_Selects->GetEmployeeDetails($id);

			if ($GetEmployeeDetails->num_rows() > 0) {
				$ged = $GetEmployeeDetails->row_array();
				$data = array(
					'ApplicantNo' => $ged['ApplicantNo'],
					'ApplicantImage' => $ged['ApplicantImage'],
					'EmployeeID' => $ged['EmployeeID'],
					'ApplicantID' => $ged['ApplicantID'],
					'PositionDesired' => $ged['PositionDesired'],
					'PositionGroup' => $ged['PositionGroup'],
					'SalaryExpected' => $ged['SalaryExpected'],
					'LastName' => $ged['LastName'],
					'FirstName' => $ged['FirstName'],
					'MiddleInitial' => $ged['MiddleInitial'],
					'Gender' => $ged['Gender'],
					'Age' => $ged['Age'],
					'Height' => $ged['Height'],
					'Weight' => $ged['Weight'],
					'Religion' => $ged['Religion'],
					'BirthDate' => $ged['BirthDate'],
					'BirthPlace' => $ged['BirthPlace'],
					'Citizenship' => $ged['Citizenship'],
					'CivilStatus' => $ged['CivilStatus'],
					'No_OfChildren' => $ged['No_OfChildren'],
					'Phone_No' => $ged['Phone_No'],
					'Address_Present' => $ged['Address_Present'],
					'Address_Provincial' => $ged['Address_Provincial'],
					'Address_Manila' => $ged['Address_Manila'],

					'SSS_No' => $ged['SSS_No'],
					'EffectiveDateCoverage' => $ged['EffectiveDateCoverage'],
					'ResidenceCertificateNo' => $ged['ResidenceCertificateNo'],
					'Rcn_At' => $ged['Rcn_At'],
					'Rcn_On' => $ged['Rcn_On'],
					'TIN' => $ged['TIN'],
					'TIN_At' => $ged['TIN_At'],
					'TIN_On' => $ged['TIN_On'],

					'HDMF' => $ged['HDMF'],
					'HDMF_At' => $ged['HDMF_At'],
					'HDMF_On' => $ged['HDMF_On'],

					'PhilHealth' => $ged['PhilHealth'],
					'PhilHealth_At' => $ged['PhilHealth_At'],
					'PhilHealth_On' => $ged['PhilHealth_On'],

					'ATM_No' => $ged['ATM_No'],

					'Status' => $ged['Status'],


					'ClientEmployed' => $ged['ClientEmployed'],
					'DateStarted' => $ged['DateStarted'],
					'DateEnds' => $ged['DateEnds'],
					'AppliedOn' => $ged['AppliedOn'],

					'SuspensionStarted' => $ged['SuspensionStarted'],
					'SuspensionEnds' => $ged['SuspensionEnds'],
					'SuspensionRemarks' => $ged['SuspensionRemarks'],
					'Suspended' => $ged['Suspended'],

					'ReminderDate' => $ged['ReminderDate'],
					'ReminderDateString' => $ged['ReminderDateString'],

					'NameExtension' => $ged['NameExtension'],
					'EmergencyPerson' => $ged['EmergencyPerson'],
					'EmergencyContact' => $ged['EmergencyContact'],
					'Referral' => $ged['Referral'],

				);
				$ApplicantID = $ged['ApplicantID'];
				$data['GetAcadHistory'] = $this->Model_Selects->GetEmployeeAcadhis($ApplicantID);
				$data['employment_record'] = $this->Model_Selects->GetEmploymentDetails($ApplicantID);
				$data['Machine_Operatessss'] = $this->Model_Selects->Machine_Operatessss($ApplicantID);
				$data['get_employee'] = $this->Model_Selects->GetEmployee();
				$data['getClientOption'] = $this->Model_Selects->getClientOption();
				$data['ShowClients'] = $this->Model_Selects->GetClients();
				$data['GetContractHistory'] = $this->Model_Selects->GetContractHistory($ApplicantID);
				$data['GetPreviousContract'] = $this->Model_Selects->GetPreviousContract($ApplicantID);
				$data['GetViolations'] = $this->Model_Selects->GetViolations($ApplicantID);
				$data['GetDocuments'] = $this->Model_Selects->GetDocuments($ApplicantID);
				$data['GetDocumentsViolations'] = $this->Model_Selects->GetDocumentsViolations($ApplicantID);
				$data['GetDocumentsNotes'] = $this->Model_Selects->GetDocumentsNotes($ApplicantID);
				$data['GetEmployeeMatchingClient'] = $this->Model_Selects->GetEmployeeMatchingClient($ApplicantID);
				if ($data['Status'] == 'Employed') {
					$data['Breadcrumb'] = '
					<nav aria-label="breadcrumb">
					<ol class="breadcrumb" style="background-color: transparent;">
					<li class="breadcrumb-item" aria-current="page"><a href="Employee">Employee</a></li>
					<li class="breadcrumb-item" aria-current="page"><a href="ViewEmployee?id=' . $ApplicantID .'">' . $ged['LastName'] . ', ' . $ged['FirstName'] . ' ' . $ged['MiddleInitial'] . '.' . '</a></li>
					<li class="breadcrumb-item" aria-current="page"><a class="wercher-breadcrumb-active" href="PrintEmployee?id=' . $ApplicantID .'">Print</a></li>
					</ol>
					</nav>';
				} else {
					$data['Breadcrumb'] = '
					<nav aria-label="breadcrumb">
					<ol class="breadcrumb" style="background-color: transparent;">
					<li class="breadcrumb-item" aria-current="page"><a href="Applicants">Applicants</a></li>
					<li class="breadcrumb-item" aria-current="page"><a href="ViewEmployee?id=' . $ApplicantID .'">' . $ged['LastName'] . ', ' . $ged['FirstName'] . ' ' . $ged['MiddleInitial'] . '.' . '</a></li>
					<li class="breadcrumb-item" aria-current="page"><a class="wercher-breadcrumb-active" href="PrintEmployee?id=' . $ApplicantID .'">Print</a></li>
					</ol>
					</nav>';
				}
				$this->load->view('users/u_printemployee',$data);
			}
			else
			{
				redirect('Employee');
			}
		}
		else
		{
			redirect('Employee');
		}
	}
	public function SSS_Table()
	{
		unset($_SESSION["acadcart"]);
		unset($_SESSION["emp_cart"]);
		unset($_SESSION["mach_cart"]);

		$header['title'] = 'SSS Table | Wercher Solutions and Resources Workers Cooperative';
		$data['T_Header'] = $this->load->view('_template/users/u_header',$header);
		$data['Breadcrumb'] = '
		<nav aria-label="breadcrumb">
		<ol class="breadcrumb" style="background-color: transparent;">
		<li class="breadcrumb-item" aria-current="page"><a class="wercher-breadcrumb-active" href="Applicants">SSS Table</a></li>
		</ol>
		</nav>';
		$data['sss_Contri'] = $this->Model_Selects->sss_Contri();
		$this->load->view('payroll/p_sssPage',$data);

	}
	public function ModifyEmployee()
	{
		unset($_SESSION["acadcart"]);
		unset($_SESSION["emp_cart"]);
		unset($_SESSION["mach_cart"]);

		if (isset($_GET['id'])) {

			$id = $_GET['id'];

			$header['title'] = 'Modify | Wercher Solutions and Resources Workers Cooperative';
			$data['T_Header'] = $this->load->view('_template/users/u_header',$header);

			$GetEmployeeDetails = $this->Model_Selects->GetEmployeeDetails($id);

			if ($GetEmployeeDetails->num_rows() > 0) {
				$ged = $GetEmployeeDetails->row_array();
				$data = array(
					'ApplicantNo' => $ged['ApplicantNo'],
					'ApplicantImage' => $ged['ApplicantImage'],
					'ApplicantID' => $ged['ApplicantID'],
					'EmployeeID' => $ged['EmployeeID'],
					'PositionDesired' => $ged['PositionDesired'],
					'PositionGroup' => $ged['PositionGroup'],
					'SalaryExpected' => $ged['SalaryExpected'],
					'LastName' => $ged['LastName'],
					'FirstName' => $ged['FirstName'],
					'MiddleInitial' => $ged['MiddleInitial'],
					'Gender' => $ged['Gender'],
					'Age' => $ged['Age'],
					'Height' => $ged['Height'],
					'Weight' => $ged['Weight'],
					'Religion' => $ged['Religion'],
					'BirthDate' => $ged['BirthDate'],
					'BirthPlace' => $ged['BirthPlace'],
					'Citizenship' => $ged['Citizenship'],
					'CivilStatus' => $ged['CivilStatus'],
					'No_OfChildren' => $ged['No_OfChildren'],
					'Phone_No' => $ged['Phone_No'],
					'Address_Present' => $ged['Address_Present'],
					'Address_Provincial' => $ged['Address_Provincial'],
					'Address_Manila' => $ged['Address_Manila'],

					'SSS_No' => $ged['SSS_No'],
					'EffectiveDateCoverage' => $ged['EffectiveDateCoverage'],
					'ResidenceCertificateNo' => $ged['ResidenceCertificateNo'],
					'Rcn_At' => $ged['Rcn_At'],
					'Rcn_On' => $ged['Rcn_On'],
					'TIN' => $ged['TIN'],
					'TIN_At' => $ged['TIN_At'],
					'TIN_On' => $ged['TIN_On'],

					'HDMF' => $ged['HDMF'],
					'HDMF_At' => $ged['HDMF_At'],
					'HDMF_On' => $ged['HDMF_On'],

					'PhilHealth' => $ged['PhilHealth'],
					'PhilHealth_At' => $ged['PhilHealth_At'],
					'PhilHealth_On' => $ged['PhilHealth_On'],

					'ATM_No' => $ged['ATM_No'],

					'Status' => $ged['Status'],


					'ClientEmployed' => $ged['ClientEmployed'],
					'DateStarted' => $ged['DateStarted'],
					'DateEnds' => $ged['DateEnds'],
					'AppliedOn' => $ged['AppliedOn'],

					'NameExtension' => $ged['NameExtension'],
					'EmergencyPerson' => $ged['EmergencyPerson'],
					'EmergencyContact' => $ged['EmergencyContact'],
					'Referral' => $ged['Referral'],

				);
				$ApplicantID = $ged['ApplicantID'];
				$data['GetAcadHistory'] = $this->Model_Selects->GetEmployeeAcadhis($ApplicantID);
				$data['employment_record'] = $this->Model_Selects->GetEmploymentDetails($ApplicantID);
				$data['Machine_Operatessss'] = $this->Model_Selects->Machine_Operatessss($ApplicantID);
				$data['get_employee'] = $this->Model_Selects->GetEmployee();
				$data['getClientOption'] = $this->Model_Selects->getClientOption();
				$data['ShowClients'] = $this->Model_Selects->GetClients();
				$data['GetContractHistory'] = $this->Model_Selects->GetContractHistory($ApplicantID);
				if ($data['Status'] == 'Employed') {
					$data['Breadcrumb'] = '
					<nav aria-label="breadcrumb">
					<ol class="breadcrumb" style="background-color: transparent;">
					<li class="breadcrumb-item" aria-current="page"><a href="Employee">Employee</a></li>
					<li class="breadcrumb-item" aria-current="page"><a href="ViewEmployee?id=' . $ApplicantID .'">Details</a></li>
					<li class="breadcrumb-item" aria-current="page"><a class="wercher-breadcrumb-active" href="">Edit</a></li>
					</ol>
					</nav>';
				} else {
					$data['Breadcrumb'] = '
					<nav aria-label="breadcrumb">
					<ol class="breadcrumb" style="background-color: transparent;">
					<li class="breadcrumb-item" aria-current="page"><a href="Applicants">Applicants</a></li>
					<li class="breadcrumb-item" aria-current="page"><a href="ViewEmployee?id=' . $ApplicantID .'">Details</a></li>
					<li class="breadcrumb-item" aria-current="page"><a class="wercher-breadcrumb-active" href="">Edit</a></li>
					</ol>
					</nav>';
				}
				$this->load->view('users/u_modifyemployee',$data);
			}
			else
			{
				redirect('Employee');
			}
		}
		else
		{
			redirect('Employee');
		}
	}
	public function GenerateIDCard()
	{
		unset($_SESSION["acadcart"]);
		unset($_SESSION["emp_cart"]);
		unset($_SESSION["mach_cart"]);

		if (isset($_GET['id'])) {

			$id = $_GET['id'];

			$header['title'] = 'Generate ID Card | Wercher Solutions and Resources Workers Cooperative';
			$data['T_Header'] = $this->load->view('_template/users/u_header',$header);

			$GetEmployeeDetails = $this->Model_Selects->GetEmployeeDetails($id);

			if ($GetEmployeeDetails->num_rows() > 0) {
				$ged = $GetEmployeeDetails->row_array();
				$data = array(
					'ApplicantNo' => $ged['ApplicantNo'],
					'ApplicantImage' => $ged['ApplicantImage'],
					'EmployeeID' => $ged['EmployeeID'],
					'ApplicantID' => $ged['ApplicantID'],
					'PositionDesired' => $ged['PositionDesired'],
					'PositionGroup' => $ged['PositionGroup'],
					'SalaryExpected' => $ged['SalaryExpected'],
					'LastName' => $ged['LastName'],
					'FirstName' => $ged['FirstName'],
					'MiddleInitial' => $ged['MiddleInitial'],
					'Gender' => $ged['Gender'],
					'Age' => $ged['Age'],
					'Height' => $ged['Height'],
					'Weight' => $ged['Weight'],
					'Religion' => $ged['Religion'],
					'BirthDate' => $ged['BirthDate'],
					'BirthPlace' => $ged['BirthPlace'],
					'Citizenship' => $ged['Citizenship'],
					'CivilStatus' => $ged['CivilStatus'],
					'No_OfChildren' => $ged['No_OfChildren'],
					'Phone_No' => $ged['Phone_No'],
					'Address_Present' => $ged['Address_Present'],
					'Address_Provincial' => $ged['Address_Provincial'],
					'Address_Manila' => $ged['Address_Manila'],

					'SSS_No' => $ged['SSS_No'],
					'EffectiveDateCoverage' => $ged['EffectiveDateCoverage'],
					'ResidenceCertificateNo' => $ged['ResidenceCertificateNo'],
					'Rcn_At' => $ged['Rcn_At'],
					'Rcn_On' => $ged['Rcn_On'],
					'TIN' => $ged['TIN'],
					'TIN_At' => $ged['TIN_At'],
					'TIN_On' => $ged['TIN_On'],

					'HDMF' => $ged['HDMF'],
					'HDMF_At' => $ged['HDMF_At'],
					'HDMF_On' => $ged['HDMF_On'],

					'PhilHealth' => $ged['PhilHealth'],
					'PhilHealth_At' => $ged['PhilHealth_At'],
					'PhilHealth_On' => $ged['PhilHealth_On'],

					'ATM_No' => $ged['ATM_No'],

					'Status' => $ged['Status'],


					'ClientEmployed' => $ged['ClientEmployed'],
					'DateStarted' => $ged['DateStarted'],
					'DateEnds' => $ged['DateEnds'],
					'AppliedOn' => $ged['AppliedOn'],

					'ReminderDate' => $ged['ReminderDate'],
					'ReminderDateString' => $ged['ReminderDateString'],

					'NameExtension' => $ged['NameExtension'],
					'EmergencyPerson' => $ged['EmergencyPerson'],
					'EmergencyContact' => $ged['EmergencyContact'],
					'Referral' => $ged['Referral'],

				);
				$ApplicantID = $ged['ApplicantID'];
				if ($data['Status'] == 'Employed') {
					$this->load->view('users/u_generateid',$data);
				} else {
					redirect('Employee');
				}
			}
			else
			{
				redirect('Employee');
			}
		}
		else
		{
			redirect('Employee');
		}
	}
	public function NewEmployee()
	{
		unset($_SESSION["acadcart"]);
		unset($_SESSION["emp_cart"]);
		unset($_SESSION["mach_cart"]);

		$header['title'] = 'New Employee | Wercher Solutions and Resources Workers Cooperative';
		$data['T_Header'] = $this->load->view('_template/users/u_header',$header);
		$data['Breadcrumb'] = '
		<nav aria-label="breadcrumb">
		<ol class="breadcrumb" style="background-color: transparent;">
		<li class="breadcrumb-item" aria-current="page"><a href="Applicants">Applicants</a></li>
		<li class="breadcrumb-item" aria-current="page"><a class="wercher-breadcrumb-active" href="NewEmployee">New</a></li>
		</ol>
		</nav>';
		$this->load->view('users/u_addemployee',$data);
	}
	public function View_Admins()
	{
		unset($_SESSION["acadcart"]);
		unset($_SESSION["emp_cart"]);
		unset($_SESSION["mach_cart"]);

		$header['title'] = 'Administrator | Wercher Solutions and Resources Workers Cooperative';
		$data['T_Header'] = $this->load->view('_template/users/u_header',$header);
		$data['Breadcrumb'] = '
		<nav aria-label="breadcrumb">
		<ol class="breadcrumb" style="background-color: transparent;">
		<li class="breadcrumb-item" aria-current="page"><a class="wercher-breadcrumb-active" href="Admin_List">Admins</a></li>
		</ol>
		</nav>';
		$data['ShowAdmin'] = $this->Model_Selects->GetAdmin();
		$this->load->view('users/u_admins',$data);
	}
	public function Clients()
	{
		unset($_SESSION["acadcart"]);
		unset($_SESSION["emp_cart"]);
		unset($_SESSION["mach_cart"]);

		$header['title'] = 'Clients | Wercher Solutions and Resources Workers Cooperative';
		$data['T_Header'] = $this->load->view('_template/users/u_header',$header);
		$data['Breadcrumb'] = '
		<nav aria-label="breadcrumb">
		<ol class="breadcrumb" style="background-color: transparent;">
		<li class="breadcrumb-item" aria-current="page"><a class="wercher-breadcrumb-active" href="Clients">Clients</a></li>
		</ol>
		</nav>';
		$data['ShowClients'] = $this->Model_Selects->GetClients();
		$this->load->view('users/u_clients',$data);
	}
	public function PayrollClients()
	{
		unset($_SESSION["acadcart"]);
		unset($_SESSION["emp_cart"]);
		unset($_SESSION["mach_cart"]);

		$header['title'] = 'Clients | Wercher Solutions and Resources Workers Cooperative';
		$data['T_Header'] = $this->load->view('_template/users/u_header',$header);
		$data['Breadcrumb'] = '
		<nav aria-label="breadcrumb">
		<ol class="breadcrumb" style="background-color: transparent;">
		<li class="breadcrumb-item" aria-current="page"><a class="wercher-breadcrumb-active" href="Payroll">Payroll</a></li>
		</ol>
		</nav>';
		$data['ShowClients'] = $this->Model_Selects->GetClients();
		$data['GetLogbookLatestHires'] =  $this->Model_Selects->GetLogbookLatestHires();
		$this->load->view('payroll/p_clients',$data);
	}
	public function Payrollsss()
	{
		unset($_SESSION["acadcart"]);
		unset($_SESSION["emp_cart"]);
		unset($_SESSION["mach_cart"]);

		$id = $_GET['id'];

		$header['title'] = 'Client Information | Wercher Solutions and Resources Workers Cooperative';
		$data['T_Header'] = $this->load->view('_template/users/u_header',$header);

		$GetWeeklyList = $this->Model_Selects->GetWeeklyList($id);

		$row = $GetWeeklyList->row_array();
		$data = array(
			'ClientID' => $row['ClientID'],
			'ApplicantID' => $row['ApplicantID'],

		);
		$ApplicantID = $row['ApplicantID'];
		$data['GetWeeklyListEmployee'] = $this->Model_Selects->GetWeeklyListEmployee($id);
		
		$data['get_applicantContri'] = $this->Model_Selects->get_applicantContri($id);
		


		$data['IsFromExcel'] = False;
		$data['Breadcrumb'] = '
		<nav aria-label="breadcrumb">
		<ol class="breadcrumb" style="background-color: transparent;">
		<li class="breadcrumb-item" aria-current="page"><a href="Payroll">Payroll</a></li>
		<li class="breadcrumb-item" aria-current="page"><a class="wercher-breadcrumb-active" href="ViewClient?id=' . $id . '">Details</a></li>
		</ol>
		</nav>';

		// $data['ShowClients'] = $this->Model_Selects->GetClients();
		$data['GetLogbookLatestHires'] =  $this->Model_Selects->GetLogbookLatestHires();
		$this->load->view('payroll/p_payrolls',$data);
	}
	public function ViewClient()
	{
		unset($_SESSION["acadcart"]);
		unset($_SESSION["emp_cart"]);
		unset($_SESSION["mach_cart"]);

		if (isset($_GET['id'])) {

			$id = $_GET['id'];

			$header['title'] = 'Client Information | Wercher Solutions and Resources Workers Cooperative';
			$data['T_Header'] = $this->load->view('_template/users/u_header',$header);

			$GetWeeklyList = $this->Model_Selects->GetWeeklyList($id);

			$row = $GetWeeklyList->row_array();
			$data = array(
				'ClientID' => $row['ClientID'],
				'ApplicantID' => $row['ApplicantID'],

			);
			$ApplicantID = $row['ApplicantID'];
			$data['GetWeeklyList'] = $this->Model_Selects->GetWeeklyList($id);
			$data['GetWeeklyListEmployee'] = $this->Model_Selects->GetWeeklyListEmployee($id);
			// $data['GetWeeklyListEmployeeActive'] = $this->Model_Selects->GetWeeklyListEmployeeActive($id);
			$data['GetClientID'] = $this->Model_Selects->GetClientID($id);
			$data['GetWeeklyDates'] = $this->Model_Selects->GetWeeklyDates();
			// $data['GetWeeklyDatesForEmployee'] = $this->Model_Selects->GetWeeklyDatesForEmployee($row['ApplicantID']);
			$data['IsFromExcel'] = False;
			$data['Breadcrumb'] = '
			<nav aria-label="breadcrumb">
			<ol class="breadcrumb" style="background-color: transparent;">
			<li class="breadcrumb-item" aria-current="page"><a href="Payroll">Payroll</a></li>
			<li class="breadcrumb-item" aria-current="page"><a class="wercher-breadcrumb-active" href="ViewClient?id=' . $id . '">Details</a></li>
			</ol>
			</nav>';
			$this->load->view('payroll/p_viewclient',$data);
		}
		else
		{
			redirect('Clients');
		}
	}
	public function Experimental()
	{
		unset($_SESSION["acadcart"]);
		unset($_SESSION["emp_cart"]);
		unset($_SESSION["mach_cart"]);

		$header['title'] = 'Experimental | Wercher Solutions and Resources Workers Cooperative';
		$data['T_Header'] = $this->load->view('_template/users/u_header',$header);
		$data['Breadcrumb'] = '
		<nav aria-label="breadcrumb">
		<ol class="breadcrumb" style="background-color: transparent;">
		<li class="breadcrumb-item" aria-current="page"><a class="wercher-breadcrumb-active" href="Experimental">Experimental</a></li>
		</ol>
		</nav>';
		$this->load->library('SimpleXLSX');
		$this->load->view('users/u_experimental',$data);
	}

	// ACADEMIC HISTORY
	public function showAcad()
	{

		if (isset($_SESSION['acadcart'])) {
			echo '<hr>';
			echo '<h6 class="ml-2"><i class="fas fa-save"></i> New Record</h6>';
			echo '<table class="table table-bordered">
			<thead>
			<th>Level</th>
			<th>School Name</th>
			<th>School Address
			<th>From Year</th>
			<th>To Year</th>
			<th>Highest Degree / Certificate Attained</th>
			<th>Action</th>
			</thead>
			<tbody>';
			foreach ($_SESSION['acadcart'] as $s_da) {
				echo '
				<tr>
				<td>
				'.$s_da['acadcart']['SchoolLevel'] .'
				</td>
				<td>
				'.$s_da['acadcart']['SchoolName'] .'
				</td>
				<td>
				'.$s_da['acadcart']['SchoolAddress'] .'
				</td>
				<td>
				'.$s_da['acadcart']['FromYearSchool'] .'
				</td>
				<td>
				'.$s_da['acadcart']['ToYearSchool'] .'
				</td>
				<td>
				'.$s_da['acadcart']['H_Attained'] .'
				</td>
				<td>
				<button id="'.$s_da['acadcart']['c_id'].'" class="remoach btn btn-sm btn-danger" type="button"><i class="fas fa-times"></i> Discard</button>
				</td>
				</tr>
				';
			}
			echo '</tbody>
			</table>
			<hr>';
		}
		echo '<button class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#acadFields"><i class="fas fa-plus"></i> Add Data</button>';
	}
	public function add_to_cart()
	{
		$min=10000000;
		$max=99999999;
		$rint = random_int($min,$max);

		$SchoolLevel = $_POST["SchoolLevel"];
		$SchoolName = $_POST["SchoolName"];
		$SchoolAddress = $_POST["SchoolAddress"];
		$FromYearSchool = $_POST["FromYearSchool"];
		$ToYearSchool = $_POST["ToYearSchool"];
		$H_Attained = $_POST["H_Attained"];
		if ($SchoolLevel == NULL || $SchoolName == NULL || $FromYearSchool == NULL || $ToYearSchool == NULL || $SchoolAddress == NULL || $H_Attained == NULL) {
			echo "Error";
		}
		else
		{
			foreach ($_SESSION["acadcart"] as $s_da => $row) {
				if ($row['acadcart']['c_id'] == $rint) {
					exit();
				}
			}
			if (!isset($_SESSION ['acadcart'] )) {
				$_SESSION ['acadcart'] = array ();
			}
			$data['acadcart'] = array(
				'c_id' => $rint,
				'SchoolLevel' => $SchoolLevel,
				'SchoolName' => $SchoolName,
				'SchoolAddress' => $SchoolAddress,
				'FromYearSchool' => $FromYearSchool,
				'ToYearSchool' => $ToYearSchool,
				'H_Attained' => $H_Attained,
			);
			$_SESSION['acadcart'][] = $data;
		}
	}
	public function removeAcad()
	{
		foreach ($_SESSION["acadcart"] as $s_da => $row) {
			if ($row['acadcart']['c_id'] == $_POST['row_id']) {
				unset($_SESSION["acadcart"][$s_da]);
				if(empty($_SESSION["acadcart"]))
					unset($_SESSION["acadcart"]);
			}
		}
	}
	// EMPLOYMENT RECORD
	public function add_toemp()
	{
		$min=10000000;
		$max=99999999;
		$rint = random_int($min,$max);

		$EmployeerName = $_POST["EmployeerName"];
		$emAddress = $_POST["emAddress"];
		$PeriodCovered = $_POST["PeriodCovered"];
		$Position = $_POST["Position"];
		$Salary = $_POST["Salary"];
		$cos = $_POST["cos"];
		if ($EmployeerName == NULL || $emAddress == NULL || $PeriodCovered == NULL || $Position == NULL || $Salary == NULL || $cos == NULL) {
			echo "Error";
		}
		else
		{
			foreach ($_SESSION["emp_cart"] as $s_da => $row) {
				if ($row['emp_cart']['emp_id'] == $rint) {
					exit();
				}
			}
			if (!isset($_SESSION ['emp_cart'] )) {
				$_SESSION ['emp_cart'] = array ();
			}
			$data['emp_cart'] = array(
				'emp_id' => $rint,
				'EmployeerName' => $EmployeerName,
				'emAddress' => $emAddress,
				'PeriodCovered' => $PeriodCovered,
				'Position' => $Position,
				'Salary' => $Salary,
				'cos' => $cos,
			);
			$_SESSION['emp_cart'][] = $data;
		}
	}
	public function showSkills()
	{

		if (isset($_SESSION['emp_cart'])) {
			echo '<hr>';
			echo '<h6 class="ml-2"><i class="fas fa-save"></i> New Record</h6>';
			echo '<table class="table table-bordered">
			<thead>
			<th>Name</th>
			<th>Address</th>
			<th>Period Covered</th>
			<th>Position</th>
			<th>Salary</th>
			<th>Cause of Seperation</th>

			<th>Action</th>
			</thead>
			<tbody>';
			foreach ($_SESSION['emp_cart'] as $s_da) {
				echo '
				<tr>
				<td>
				'.$s_da['emp_cart']['EmployeerName'] .'
				</td>
				<td>
				'.$s_da['emp_cart']['emAddress'] .'
				</td>
				<td>
				'.$s_da['emp_cart']['PeriodCovered'] .'
				</td>
				<td>
				'.$s_da['emp_cart']['Position'] .'
				</td>
				<td>
				'.$s_da['emp_cart']['Salary'] .'
				</td>
				<td>
				'.$s_da['emp_cart']['cos'] .'
				</td>
				<td>
				<button id="'.$s_da['emp_cart']['emp_id'].'" class="remoaemop btn btn-sm btn-danger" type="button"><i class="fas fa-times"></i> Discard</button>
				</td>
				</tr>
				';
			}
			echo '</tbody>
			</table>
			<hr>';
		}
		echo '<button class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#skillsFields"><i class="fas fa-plus"></i> Add Data</button>';
	}
	public function removeemp()
	{
		foreach ($_SESSION["emp_cart"] as $s_da => $row) {
			if ($row['emp_cart']['emp_id'] == $_POST['row_id']) {
				unset($_SESSION["emp_cart"][$s_da]);
				if(empty($_SESSION["emp_cart"]))
					unset($_SESSION["emp_cart"]);
			}
		}
	}
	// MACHINE OPERATED
	public function ShowMachineOperated()
	{

		if (isset($_SESSION['mach_cart'])) {
			echo '<hr>';
			echo '<h6 class="ml-2"><i class="fas fa-save"></i> New Record</h6>';
			echo '<table class="table table-bordered">
			<thead>
			<th>Machine Name</th>
			<th>Action</th>
			</thead>
			<tbody>';
			foreach ($_SESSION['mach_cart'] as $s_da) {
				echo '
				<tr>
				<td>
				'.$s_da['mach_cart']['MachineName'] .'
				</td>
				<td>
				<button id="'.$s_da['mach_cart']['MachID'].'" class="removemachine btn btn-sm btn-danger" type="button"><i class="fas fa-times"></i> Discard</button>
				</td>
				</tr>
				';
			}
			echo '</tbody>
			</table>
			<hr>';
		}
		echo '<button class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#Mach_Operated"><i class="fas fa-plus"></i> Add Data</button>';
	}
	public function Add_MachineOP()
	{
		$min=10000000;
		$max=99999999;
		$rint = random_int($min,$max);

		
		$MachineName = $_POST["MachineName"];
		if ($MachineName == NULL) {
			echo "Error";
		}
		else
		{
			foreach ($_SESSION["mach_cart"] as $s_da => $row) {
				if ($row['mach_cart']['emp_id'] == $rint) {
					exit();
				}
			}
			if (!isset($_SESSION ['mach_cart'] )) {
				$_SESSION ['mach_cart'] = array ();
			}
			$data['mach_cart'] = array(
				'MachID' => $rint,
				'MachineName' => $MachineName,
				
			);
			$_SESSION['mach_cart'][] = $data;
		}
	}
	public function remomanchine()
	{
		foreach ($_SESSION["mach_cart"] as $s_da => $row) {
			if ($row['mach_cart']['MachID'] == $_POST['row_id']) {
				unset($_SESSION["mach_cart"][$s_da]);
				if(empty($_SESSION["mach_cart"]))
					unset($_SESSION["mach_cart"]);
			}
		}
	}
	public function Search()
	{
		unset($_SESSION["acadcart"]);
		unset($_SESSION["emp_cart"]);
		unset($_SESSION["mach_cart"]);

		$header['title'] = 'Search | Wercher Solutions and Resources Workers Cooperative';
		$data['T_Header'] = $this->load->view('_template/users/u_header',$header);
		$data['Breadcrumb'] = '
		<nav aria-label="breadcrumb">
		<ol class="breadcrumb" style="background-color: transparent;">
		<li class="breadcrumb-item" aria-current="page"><a class="wercher-breadcrumb-active" href="Search">Search</a></li>
		</ol>
		</nav>';
		if(isset($_GET['query'])) {
			$query = $_GET['query'];
			$data['query'] = $query;
			$data['SearchApplicantID'] = $this->Model_Selects->SearchApplicantID($query);
			$data['SearchEmployeeID'] = $this->Model_Selects->SearchEmployeeID($query);
			$data['SearchPeople'] = $this->Model_Selects->SearchPeople($query);
			$data['SearchClients'] = $this->Model_Selects->SearchClients($query);
			$data['SearchPositionGroups'] = $this->Model_Selects->SearchPositionGroups($query);
			$data['SearchPositionSpecific'] = $this->Model_Selects->SearchPositionSpecific($query);
			$data['SearchAdmins'] = $this->Model_Selects->SearchAdmins($query);
		}
		$this->load->view('users/u_search',$data);
	}
	public function Logbook()
	{
		unset($_SESSION["acadcart"]);
		unset($_SESSION["emp_cart"]);
		unset($_SESSION["mach_cart"]);

		$header['title'] = 'Logbook | Wercher Solutions and Resources Workers Cooperative';
		$data['T_Header'] = $this->load->view('_template/users/u_header',$header);
		$data['Breadcrumb'] = '
		<nav aria-label="breadcrumb">
		<ol class="breadcrumb" style="background-color: transparent;">
		<li class="breadcrumb-item" aria-current="page"><a class="wercher-breadcrumb-active" href="Logbook">Logbook</a></li>
		</ol>
		</nav>';
		$this->load->view('users/u_logbook',$data);
	}
	public function Calendar()
	{
		unset($_SESSION["acadcart"]);
		unset($_SESSION["emp_cart"]);
		unset($_SESSION["mach_cart"]);

		$header['title'] = 'Calendar | Wercher Solutions and Resources Workers Cooperative';
		$data['T_Header'] = $this->load->view('_template/users/u_header',$header);
		$data['Breadcrumb'] = '
		<nav aria-label="breadcrumb">
		<ol class="breadcrumb" style="background-color: transparent;">
		<li class="breadcrumb-item" aria-current="page"><a class="wercher-breadcrumb-active" href="Calendar">Calendar</a></li>
		</ol>
		</nav>';
		$this->load->view('users/u_calendar',$data);
	}
	// // Relatives
	// public function ShowRelatives()
	// {
	// 	if (!isset($_SESSION['rela_cart'])) {
	// 		echo '<div class="pb-3"><h5><i class="fas fa-stream"></i> Relatives Empty</h5></div>';
	// 	}

	// 	if (isset($_SESSION['rela_cart'])) {
	// 		echo '<div class="p-3"><h5><i class="fas fa-stream"></i> Relatives </h5></div>';
	// 		echo '<table class="table table-bordered">
	// 		<thead>
	// 		<th>Relation</th>
	// 		<th>Name</th>
	// 		<th>Occupation</th>
	// 		<th>Action</th>
	// 		</thead>
	// 		<tbody>';
	// 		foreach ($_SESSION['rela_cart'] as $s_da) {
	// 			echo '
	// 			<tr>
	// 			<td>
	// 			'.$s_da['rela_cart']['Relation'] .'
	// 			</td>
	// 			<td>
	// 			'.$s_da['rela_cart']['rName'] .'
	// 			</td>
	// 			<td>
	// 			'.$s_da['rela_cart']['rOccupation'] .'
	// 			</td>
	// 			<td>
	// 			<button id="'.$s_da['rela_cart']['relaID'].'" class="removeRela btn-tr" type="button"><i class="fas fa-trash"></i></button>
	// 			</td>
	// 			</tr>
	// 			';
	// 		}
	// 		echo '</tbody>
	// 		</table>';
	// 	}
	// 	echo '<button class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#Relatives_Modal"><i class="fas fa-plus"></i> Add Data</button>';
	// }
	// public function Add_Relatives()
	// {
	// 	$min=10000000;
	// 	$max=99999999;
	// 	$rint = random_int($min,$max);

	// 	$Relation = $_POST["Relation"];
	// 	$rName = $_POST["rName"];
	// 	$rOccupation = $_POST["rOccupation"];

	// 	if ($Relation == NULL || $rName == NULL || $rOccupation == NULL) {
	// 		echo "Error";
	// 	}
	// 	else
	// 	{
	// 		foreach ($_SESSION["rela_cart"] as $s_da => $row) {
	// 			if ($row['rela_cart']['emp_id'] == $rint) {
	// 				exit();
	// 			}
	// 		}
	// 		if (!isset($_SESSION ['rela_cart'] )) {
	// 			$_SESSION ['rela_cart'] = array ();
	// 		}
	// 		$data['rela_cart'] = array(
	// 			'relaID' => $rint,
	// 			'Relation' => $Relation,
	// 			'rName' => $rName,
	// 			'rOccupation' => $rOccupation,

	// 		);
	// 		$_SESSION['rela_cart'][] = $data;
	// 	}
	// }
	// public function RemoveRelativs()
	// {
	// 	foreach ($_SESSION["rela_cart"] as $s_da => $row) {
	// 		if ($row['rela_cart']['relaID'] == $_POST['row_id']) {
	// 			unset($_SESSION["rela_cart"][$s_da]);
	// 			if(empty($_SESSION["rela_cart"]))
	// 				unset($_SESSION["rela_cart"]);
	// 		}
	// 	}
	// }
	// // BENIFICIARIES
	// public function ShowBene()
	// {
	// 	if (!isset($_SESSION['beneCart'])) {
	// 		echo '<div class="pb-3"><h5><i class="fas fa-stream"></i> Beneficiaries Empty</h5></div>';
	// 	}

	// 	if (isset($_SESSION['beneCart'])) {
	// 		echo '<div class="p-3"><h5><i class="fas fa-stream"></i> Beneficiaries </h5></div>';
	// 		echo '<table class="table table-bordered">
	// 		<thead>
	// 		<th>Name</th>
	// 		<th>Relationship</th>
	// 		<th>Action</th>
	// 		</thead>
	// 		<tbody>';
	// 		foreach ($_SESSION['beneCart'] as $s_da) {
	// 			echo '
	// 			<tr>
	// 			<td>
	// 			'.$s_da['beneCart']['BeneName'] .'
	// 			</td>
	// 			<td>
	// 			'.$s_da['beneCart']['BeneRelationship'] .'
	// 			</td>
	// 			<td>
	// 			<button id="'.$s_da['beneCart']['beneID'].'" class="removeBENEEE btn-tr" type="button"><i class="fas fa-trash"></i></button>
	// 			</td>
	// 			</tr>
	// 			';
	// 		}
	// 		echo '</tbody>
	// 		</table>';
	// 	}
	// 	echo '<button class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#Bene_Modal"><i class="fas fa-plus"></i> Add Data</button>';
	// }
	// public function Add_Bene()
	// {
	// 	$min=10000000;
	// 	$max=99999999;
	// 	$rint = random_int($min,$max);

	// 	$bName = $_POST["bName"];
	// 	$bRelationship = $_POST["bRelationship"];

	// 	if ($bName == NULL || $bRelationship == NULL) {
	// 		echo "Error";
	// 	}
	// 	else
	// 	{
	// 		foreach ($_SESSION["beneCart"] as $s_da => $row) {
	// 			if ($row['beneCart']['beneID'] == $rint) {
	// 				exit();
	// 			}
	// 		}
	// 		if (!isset($_SESSION ['beneCart'] )) {
	// 			$_SESSION ['beneCart'] = array ();
	// 		}
	// 		$data['beneCart'] = array(
	// 			'beneID' => $rint,
	// 			'BeneName' => $bName,
	// 			'BeneRelationship' => $bRelationship,

	// 		);
	// 		$_SESSION['beneCart'][] = $data;
	// 	}
	// }
	// public function RemoveBene()
	// {
	// 	foreach ($_SESSION["beneCart"] as $s_da => $row) {
	// 		if ($row['beneCart']['beneID'] == $_POST['row_id']) {
	// 			unset($_SESSION["beneCart"][$s_da]);
	// 			if(empty($_SESSION["beneCart"]))
	// 				unset($_SESSION["beneCart"]);
	// 		}
	// 	}
	// }

	public function Logout()
	{
		session_destroy();
	}
}
