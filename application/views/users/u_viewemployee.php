<?php $T_Header;?>
<body>
<style>
	.rounded-circle {
		border: 4px solid white;
	}
</style>
	<div class="wrapper wercher-background-lowpoly">
		<?php $this->load->view('_template/users/u_sidebar'); ?>
		<div id="content" class="ncontent">
			<div class="container-fluid">
				<?php $this->load->view('_template/users/u_notifications'); ?>
				<div class="d-none d-sm-block">
					<div class="row">
						<!-- <div class="col-6 col-sm-6 col-md-6 mb-5 PrintExclude">
							<a href="
							<?php if ($Status == 'Employed') {
								echo base_url() . 'Employee';
							} else {
								echo base_url() . 'Applicants';
							} ?>" class="btn btn-primary"><i class="fas fa-chevron-left"></i> Back </a>
						</div>
							<div class="col-6 col-sm-6 col-md-6 text-right PrintExclude dropdown">
								<?php if ($Status == 'Employed'): ?> 
									<?php if ($ReminderDate == NULL): ?> 
										<button id="<?php echo $ApplicantID; ?>" class="btn btn-warning" data-toggle="modal" data-target="#ReminderModal"><i class="fas fa-exclamation"></i> No reminder set</button>
									<?php else: ?>
										<button id="<?php echo $ApplicantID; ?>" class="btn btn-success" data-toggle="modal" data-target="#ReminderModal"><i class="fas fa-check"></i> You will be notified TEST months before expiring</button>
									<?php endif; ?>
								<?php endif; ?>
								<button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fas fa-cog px-1" style="margin-right: -1px;"></i>
								</button>
								<div class="dropdown-menu w-50" aria-labelledby="dropdownMenuButton">
									<a href="<?=base_url()?>ModifyEmployee?id=<?=$ApplicantID?>" class="dropdown-item"><i class="fas fa-edit"></i> Edit</a>
									<?php if ($Status == 'Employed'): ?> 
										<button id="<?php echo $ApplicantID; ?>" class="dropdown-item ReminderButton" data-toggle="modal" data-target="#ReminderModal"><i class="fas fa-stopwatch"></i> Set a Reminder</button>
									<?php endif; ?>
									<button onClick="printContent('PrintOut')" type="button" class="dropdown-item"><i class="fas fa-print"></i> Print</button>
									<div class="dropdown-divider"></div>
									<a href="<?=base_url()?>BlacklistEmployee?id=<?=$ApplicantID?>" class="dropdown-item"><i class="fas fa-times"></i> Blacklist</a>
								</div>
							</div> -->
							<div class="row employee-container">
								<div class="col-10 employee-tabs">
									<ul>
										<li id="TabPersonalBtn" class="employee-tabs-select employee-tabs-active"><a href="#Personal" onclick="">Personal</a></li>
										<li id="TabContractBtn" class="employee-tabs-select"><a href="#Contract" onclick="">Contract</a></li>
										<?php if($Status == 'Employed' || $Status == 'Blacklisted' || $Status == 'Expired'): ?>
											<li id="TabDocumentsBtn" class="employee-tabs-select"><a href="#Documents" onclick="">Documents</a></li>
										<?php endif; ?>
										<li id="TabAcademicBtn" class="employee-tabs-select"><a href="#Academic" onclick="">Academic</a></li>
										<li id="TabEmploymentsBtn" class="employee-tabs-select"><a href="#Employments" onclick="">Employments</a></li>
										<li id="TabMachineBtn" class="employee-tabs-select"><a href="#Machine" onclick="">Machine</a></li>
										<li><a href="<?=base_url()?>PrintEmployee?id=<?=$ApplicantID?>" target="_blank" type="button" data-toggle="tooltip" data-placement="top" data-html="true" title="Print Employee"><i class="fas fa-print" style="margin-right: -1px;"></i> </a></li>
										<li id="TabEditBtn"><a href="<?=base_url()?>ModifyEmployee?id=<?=$ApplicantID?>" onclick="" target="_blank" target="_blank" type="button" data-toggle="tooltip" data-placement="top" data-html="true" title="Edit"><i class="fas fa-edit" style="margin-right: -1px;"></i></a></li>
									</ul>
								</div>
								<div class="col-2 mb-5 employee-image">
									<img class="rounded-circle" src="<?php echo $ApplicantImage; ?>">
								</div>
							</div>
							<div class="row w-100 rcontent employee-content">
								<div class="col-2 employee-static mt-5 d-none d-sm-block">
									<div class="col-sm-12">
										<?php echo $LastName; ?> , <?php echo $FirstName; ?>  <?php echo $MiddleInitial; ?>.
									</div>
									<hr>
									<div class="col-sm-12 employee-static-item">
										<i class="fas fa-phone"></i> <?php echo $Phone_No; ?>
									</div>
									<div class="col-sm-12 employee-static-item">
										<i class="fas fa-map-marker-alt"></i> <?php echo $Address_Present; ?>
									</div>
									<hr>
									<div class="col-sm-12">
										<?php if($Status == 'Employed'): ?>
											<?php if($EmployeeID != NULL): ?>
												<i class="fas fa-user-tie"></i> <?php echo $EmployeeID; ?>
											<?php else: ?>
												<i class="fas fa-user-tie"></i> <a href="<?php echo base_url() ?>ModifyEmployee?id=<?php echo $ApplicantID; ?>">Employee ID not assigned</a>
											<?php endif; ?>
										<?php else: ?>
											<i class="fas fa-user"></i> <?php echo $ApplicantID; ?>
										<?php endif; ?>
									</div>
									<div class="col-sm-12 mt-2">
										<?php if ($Status == 'Employed') { ?>
											<i class="fas fa-square PrintExclude" style="color: #1BDB07;"></i> Employed
										<?php } elseif ($Status == 'Applicant') { ?>
											<i class="fas fa-square PrintExclude" style="color: #DB3E07;"></i> Applicant
										<?php } elseif ($Status == 'Expired') { ?>
											<i class="fas fa-square PrintExclude" style="color: #0721DB;"></i> Applicant (Expired)
										<?php } elseif ($Status == 'Blacklisted') { ?>
											<i class="fas fa-square PrintExclude" style="color: #000000;"></i> Blacklisted
										<?php } else { ?>
											<i class="fas fa-square PrintExclude" style="color: #DB3E07;"></i> Unknown
										<?php } ?>
									</div>
									<?php if ($Status == 'Blacklisted'): ?>
									<div class="row ml-auto mr-auto pb-5 pt-5 w-100">
										<div class="col-sm-12 col-mb-12 w-100 text-center blacklisted-notice">
											<div class="col-sm-12 pb-2 pt-4">
												<h5>
													<i class="fas fa-exclamation-triangle"></i><b> Notice </b><i class="fas fa-exclamation-triangle"></i>
												</h5>
											</div>
											<div class="col-sm-12 pb-2">
												This individual has been marked as <b>Blacklisted</b>
											</div>
											<div class="col-sm-12 col-mb-12 pb-2">
												<a href="#Documents" class="btn btn-danger"><i class="far fa-eye"></i> Documents</a>
											</div>
										</div>
									</div>
									<?php endif; ?>
								</div>
								<div class="col-10">
									<div id="TabPersonal">
										<div class="employee-tabs-group-content">
											<div class="employee-content-header">
												<div class="ml-1 row">
													<?php if ($Status == 'Employed'): ?> 
														<?php if ($ReminderDate == NULL): ?> 
															<button id="<?php echo $ApplicantID; ?>" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#ReminderModal"><i class="fas fa-exclamation"></i> No reminder set</button>
														<?php else: ?>
															<button id="<?php echo $ApplicantID; ?>" class="btn btn-success btn-sm" data-toggle="modal" data-target="#ReminderModal"><i class="fas fa-check"></i> You will be notified <?php echo $ReminderDateString; ?> before expiring</button>
														<?php endif; ?>
														<div class="ml-auto">
															<a href="GenerateIDCard?id=<?php echo $ApplicantID; ?>" class="btn btn-primary btn-sm" target="_blank"><i class="fas fa-id-card"></i> Generate ID Card</a>
														</div>
													<?php endif; ?>
												</div>
											</div>
											<hr>
											<div class="row mt-3">
												<div class="col-sm-4 employee-dynamic-header">
													<b>Present Address</b>
												</div>
												<div class="col-sm-4 employee-dynamic-header">
													<b>Provincial Address</b>
												</div>
												<div class="col-sm-4 employee-dynamic-header">
													<b>Manila</b>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-4 employee-dynamic-item">
													<?php echo $Address_Present; ?>
												</div>
												<div class="col-sm-4 employee-dynamic-item">
													<?php echo $Address_Provincial; ?>
												</div>
												<div class="col-sm-4 employee-dynamic-item">
													<?php echo $Address_Manila; ?>
												</div>
											</div>
											<!-- ------------------ -->
											<div class="row mt-4">
												<div class="col-sm-2 employee-dynamic-header">
													<b>Sex</b>
												</div>
												<div class="col-sm-2 employee-dynamic-header">
													<b>Age</b>
												</div>
												<div class="col-sm-2 employee-dynamic-header">
													<b>Height</b>
												</div>
												<div class="col-sm-2 employee-dynamic-header">
													<b>Weight</b>
												</div>
												<div class="col-sm-2 employee-dynamic-header">
													<b>Religion</b>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-2 employee-dynamic-item">
													<?php echo $Gender; ?>
												</div>
												<div class="col-sm-2 employee-dynamic-item">
													<?php echo $Age; ?>
												</div>
												<div class="col-sm-2 employee-dynamic-item">
													<?php echo $Height; ?>
												</div>
												<div class="col-sm-2 employee-dynamic-item">
													<?php echo $Weight; ?>
												</div>
												<div class="col-sm-2 employee-dynamic-item">
													<?php echo $Religion; ?>
												</div>
											</div>
											<!-- ------------------ -->
											<div class="row mt-3">
												<div class="col-sm-2 employee-dynamic-header">
													<b>Birth Place</b>
												</div>
												<div class="col-sm-2 employee-dynamic-header">
													<b>Birth Date</b>
												</div>
												<div class="col-sm-2 employee-dynamic-header">
													<b>Citizenship</b>
												</div>
												<div class="col-sm-2 employee-dynamic-header">
													<b>Civil Status</b>
												</div>
												<div class="col-sm-2 employee-dynamic-header">
													<b>No. of Children</b>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-2 employee-dynamic-item">
													<?php echo $BirthPlace; ?>
												</div>
												<div class="col-sm-2 employee-dynamic-item">
													<?php echo $BirthDate; ?>
												</div>
												<div class="col-sm-2 employee-dynamic-item">
													<?php echo $Citizenship; ?>
												</div>
												<div class="col-sm-2 employee-dynamic-item">
													<?php echo $CivilStatus; ?>
												</div>
												<div class="col-sm-2 employee-dynamic-item">
													<?php echo $No_OfChildren; ?>
												</div>
											</div>
											<hr class="mt-5 mb-3">
											<div class="row employee-personal-row">
												<div class="col-sm-3 employee-dynamic-header">
													<b>S.S.S. No.</b>
												</div>
												<div class="col-sm-3 employee-dynamic-item">
													<?php echo $SSS_No; ?>
												</div>
												<div class="col-sm-3 employee-dynamic-header">
													<b>HDMF</b>
												</div>
												<div class="col-sm-3 employee-dynamic-item">
													<?php echo $HDMF; ?>
												</div>
											</div>
											<div class="row employee-personal-row mt-4">
												<div class="col-sm-3 employee-dynamic-header">
													<b>Residence Certificate No.</b>
												</div>
												<div class="col-sm-3 employee-dynamic-item">
													<?php echo $ResidenceCertificateNo; ?>
												</div>
												<div class="col-sm-3 employee-dynamic-header">
													<b>PHILHEALTH</b>
												</div>
												<div class="col-sm-3 employee-dynamic-item">
													<?php echo $PhilHealth; ?>
												</div>
											</div>
											<div class="row employee-personal-row mt-4">
												<div class="col-sm-3 employee-dynamic-header">
													<b>Tax Identification No.</b>
												</div>
												<div class="col-sm-3 employee-dynamic-item">
													<?php echo $TIN; ?>
												</div>
												<div class="col-sm-3 employee-dynamic-header">
													<b>ATM No.</b>
												</div>
												<div class="col-sm-3 employee-dynamic-item">
													<?php echo $ATM_No; ?>
												</div>
											</div>
											<hr>
											<div class="row mt-3">
												<div class="col-sm-6 employee-dynamic-header">
													<b>Person to notify in case of emergency</b>
												</div>
												<div class="col-sm-4 employee-dynamic-header">
													<b>Their Contact Number</b>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-6 employee-dynamic-item">
													<?php echo $EmergencyPerson; ?>
												</div>
												<div class="col-sm-4 employee-dynamic-item">
													<?php echo $EmergencyContact; ?>
												</div>
											</div>
										</div>
									</div>
									<div id="TabContract">
										<div class="employee-tabs-group-content">
											<?php if ($Status == 'Employed'): ?>
											<div class="employee-content-header">
												<div class="ml-1 row">
													<button id="<?php echo $ApplicantID; ?>" data-dismiss="modal" type="button" class="btn btn-primary btn-sm ExtendButton" data-toggle="modal" data-target="#ExtendContractModal"><i class="fas fa-plus"></i> Extend Contract</button>
													<button class="btn btn-primary btn-sm ml-1" data-toggle="modal" data-target="#EmpContractHistory"><i class="fas fa-book"></i> Contract History</button>
													<div class="ml-auto">
														<button id="<?php echo $ApplicantID; ?>" class="btn btn-danger btn-sm SuspendButton" data-toggle="modal" data-target="#SuspendModal" type="button"><i class="fas fa-exclamation-triangle"></i> Suspend</button>
													</div>
												</div>
											</div>
											<hr>
											<?php if($Suspended == 'Yes'): ?>
												<div class="employee-suspension-container">
													<div class="col-sm-12 col-md-12 mt-2 employee-dynamic-header text-center">
														<b>
															<span style="color: #dc3545"><i class="fas fa-exclamation-triangle"></i> Suspended For</span>
														</b>
													</div>
													<div class="col-sm-12 col-md-12 text-center">
														<p style="font-size: 18px; font-weight: bold;">
															<?php

																$scurrTime = time();
																$SuspendedString = "";
																$sstrDateEnds = strtotime($SuspensionEnds);
																$sstrDateStarted = strtotime($SuspensionStarted);
																// PERCENTAGE
																$SuspensionPercentage = (($sstrDateEnds - $scurrTime) * 100) / ($sstrDateEnds - $sstrDateStarted);
																$SuspensionPercentage = round($SuspensionPercentage);
																// DAYS REMAINING
																$sdateTimeZone = new DateTimeZone("Asia/Manila");
																$sdatetime1 = new DateTime('@' . $scurrTime, $sdateTimeZone);
																$sdatetime2 = new DateTime('@' . $sstrDateEnds, $sdateTimeZone);
																$sinterval = $sdatetime1->diff($sdatetime2);
																if($sinterval->format('%y years, %m months, %d days') == '0 years, 0 months, 0 days') {
																	if($sinterval->format('%H') == '1') {
																		$SuspendedString = $sinterval->format('%H hour');
																		if($sinterval->format('%I') != NULL || $sinterval->format('%S') != NULL) {
																			$SuspendedString = $SuspendedString . ', ';
																		}
																	} else {
																		$SuspendedString = $sinterval->format('%H hours');
																		if($sinterval->format('%I') != NULL || $sinterval->format('%S') != NULL) {
																			$SuspendedString = $SuspendedString . ', ';
																		}
																	}
																	if($sinterval->format('%I') == '1') {
																		$SuspendedString = $SuspendedString . $sinterval->format('%I minute');
																		if($sinterval->format('%S') != NULL) {
																			$SuspendedString = $SuspendedString . ', ';
																		}
																	} else {
																		$SuspendedString = $SuspendedString . $sinterval->format('%I minutes');
																		if($sinterval->format('%S') != NULL) {
																			$SuspendedString = $SuspendedString . ', ';
																		}
																	}
																	if($sinterval->format('%S') == '1') {
																		$SuspendedString = $SuspendedString . $sinterval->format('%S second');
																	} else {
																		$SuspendedString = $SuspendedString . $sinterval->format('%S seconds');
																	}
																	echo $SuspendedString;
																} else {
																	if($sinterval->format('%y') == '1') {
																		$SuspendedString = $sinterval->format('%y year');
																		if($sinterval->format('%m') != NULL || $sinterval->format('%d') != NULL) {
																			$SuspendedString = $SuspendedString . ', ';
																		}
																	} else {
																		$SuspendedString = $sinterval->format('%y years');
																		if($sinterval->format('%m') != NULL || $sinterval->format('%d') != NULL) {
																			$SuspendedString = $SuspendedString . ', ';
																		}
																	}
																	if($sinterval->format('%m') == '1') {
																		$SuspendedString = $SuspendedString . $sinterval->format('%m month');
																		if($sinterval->format('%d') != NULL) {
																			$SuspendedString = $SuspendedString . ', ';
																		}
																	} else {
																		$SuspendedString = $SuspendedString . $sinterval->format('%m months');
																		if($sinterval->format('%d') != NULL) {
																			$SuspendedString = $SuspendedString . ', ';
																		}
																	}
																	if($sinterval->format('%d') == '1') {
																		$SuspendedString = $SuspendedString . $sinterval->format('%d day');
																	} else {
																		$SuspendedString = $SuspendedString . $sinterval->format('%d days');
																	}
																	echo $SuspendedString;
																}
															?>
															<input type="hidden" id="SuspensionTimeLeft" value="<?php echo $SuspensionPercentage;?>">
														</p>
													</div>
													<!-- <div class="col-sm-12 col-md-12 PrintExclude">
														<div class="progressBar">
															<div class="progressBarTitle SuspensionRemainingTitle">Time Left</div>
															<div class="progress SuspensionRemaining"></div>
															<div class="SuspensionValue">45%</div>
														</div>
													</div> -->
													<div class="col-sm-12 col-md-12 text-center PrintExclude">
														<span style="color: #dc3545"><b>Remarks:</b></span>
													</div>
													<div class="col-sm-12 col-md-12 text-center mb-5 PrintExclude">
														<?php echo $SuspensionRemarks; ?>
													</div>
												</div>
											<?php endif; ?>
											<div class="col-sm-12 col-md-12 employee-dynamic-header text-center">
												<b>
													Days Remaining on Contract
												</b>
											</div>
											<div class="col-sm-12 col-md-12 text-center">
												<p>
													<?php

														$currTime = time();
														$TimeString = "";
														$strDateEnds = strtotime($DateEnds);
														$strDateStarted = strtotime($DateStarted);
														// PERCENTAGE
														$rPercentage = (($strDateEnds - $currTime) * 100) / ($strDateEnds - $strDateStarted);
														$rPercentage = round($rPercentage);
														// DAYS REMAINING
														$dateTimeZone = new DateTimeZone("Asia/Manila");
														$datetime1 = new DateTime('@' . $currTime, $dateTimeZone);
														$datetime2 = new DateTime('@' . $strDateEnds, $dateTimeZone);
														$interval = $datetime1->diff($datetime2);
														if($interval->format('%y years, %m months, %d days') == '0 years, 0 months, 0 days') {
															if($interval->format('%H') == '1') {
																$TimeString = $interval->format('%H hour');
																if($interval->format('%I') != NULL || $interval->format('%S') != NULL) {
																	$TimeString = $TimeString . ', ';
																}
															} else {
																$TimeString = $interval->format('%H hours');
																if($interval->format('%I') != NULL || $interval->format('%S') != NULL) {
																	$TimeString = $TimeString . ', ';
																}
															}
															if($interval->format('%I') == '1') {
																$TimeString = $TimeString . $interval->format('%I minute');
																if($interval->format('%S') != NULL) {
																	$TimeString = $TimeString . ', ';
																}
															} else {
																$TimeString = $TimeString . $interval->format('%I minutes');
																if($interval->format('%S') != NULL) {
																	$TimeString = $TimeString . ', ';
																}
															}
															if($interval->format('%S') == '1') {
																$TimeString = $TimeString . $interval->format('%S second');
															} else {
																$TimeString = $TimeString . $interval->format('%S seconds');
															}
															echo $TimeString;
														} else {
															if($interval->format('%y') == '1') {
																$TimeString = $interval->format('%y year');
																if($interval->format('%m') != NULL || $interval->format('%d') != NULL) {
																	$TimeString = $TimeString . ', ';
																}
															} else {
																$TimeString = $interval->format('%y years');
																if($interval->format('%m') != NULL || $interval->format('%d') != NULL) {
																	$TimeString = $TimeString . ', ';
																}
															}
															if($interval->format('%m') == '1') {
																$TimeString = $TimeString . $interval->format('%m month');
																if($interval->format('%d') != NULL) {
																	$TimeString = $TimeString . ', ';
																}
															} else {
																$TimeString = $TimeString . $interval->format('%m months');
																if($interval->format('%d') != NULL) {
																	$TimeString = $TimeString . ', ';
																}
															}
															if($interval->format('%d') == '1') {
																$TimeString = $TimeString . $interval->format('%d day');
															} else {
																$TimeString = $TimeString . $interval->format('%d days');
															}
															echo $TimeString;
														}
													?>
													<input type="hidden" id="TimeLeft" value="<?php echo $rPercentage;?>">
												</p>
											</div>
											<div class="col-sm-12 col-md-12 PrintExclude">
												<div class="progressBar">
													<div class="progressBarTitle progressRemainingColor">Time Left</div>
													<div class="progress progressRemaining"></div>
													<div class="progress_value">45%</div>
												</div>
											</div>
											<!-- <div class="col-sm-6 employee-contract-container">
												<div class="col-sm-12 employee-contract-header-title">
													Client
												</div>
												<div class="col-sm-12 employee-contract-header-desc">
													<?php
													// TODO: Find a better solution than this.
													$found = false;
													foreach ($get_employee->result_array() as $row) {
														foreach ($getClientOption->result_array() as $nrow) {
															if ($row['ClientEmployed'] == $nrow['ClientID'] && $found == false) {
																$found = true;
																echo $nrow['Name'];
															}
														}
													}?>
												</div>
											</div> -->
											<div class="row">
												<div class="col-sm-4">
													<div class="card mb-3" style="max-width: 18rem;">
														<div class="card-header employee-dynamic-header text-center"><b><i class="fas fa-user-tag"></i> Client</b></div>
														<div class="card-body text-dark">
															<h5 class="card-title text-center wercher-card-title">
																<?php
																	foreach($GetEmployeeMatchingClient->result_array() as $row) {
																		echo $row['Name'];
																	};
																?>
															</h5>
															<p class="card-text">
																<div class="col-sm-12 employee-static-item text-center mt-3">
																	<div class="col-sm-12 employee-dynamic-header">
																		<b>Contact</b>
																	</div>
																	<div class="col-sm-12">
																		<?php
																			foreach($GetEmployeeMatchingClient->result_array() as $row) {
																				echo $row['ContactNumber'];
																			};
																		?>
																	</div>
																</div>
																<div class="col-sm-12 employee-static-item text-center">
																	<div class="col-sm-12 employee-dynamic-header">
																		<b>Address</b>
																	</div>
																	<div class="col-sm-12">
																		<?php
																			foreach($GetEmployeeMatchingClient->result_array() as $row) {
																				echo $row['Address'];
																			};
																		?>
																	</div>
																</div>
															</p>
														</div>
													</div>
												</div>
												<div class="col-sm-4">
													<div class="card mb-3" style="max-width: 18rem;">
														<div class="card-header employee-dynamic-header text-center"><b><i class="fas fa-user-tie"></i> Position</b></div>
														<div class="card-body text-dark">
															<h5 class="card-title text-center wercher-card-title"><?php echo $PositionDesired; ?></h5>
															<p class="card-text">
																<div class="col-sm-12 employee-static-item text-center mt-3">
																	<div class="col-sm-12 employee-dynamic-header">
																		<b>Contract Started</b>
																	</div>
																	<div class="col-sm-12">
																		<?php echo $DateStarted; ?>
																	</div>
																</div>
																<div class="col-sm-12 employee-static-item text-center">
																	<div class="col-sm-12 employee-dynamic-header">
																		<b>Contract Ends</b>
																	</div>
																	<div class="col-sm-12">
																		<?php echo $DateEnds; ?>
																	</div>
																</div>
															</p>
														</div>
													</div>
												</div>
												<div class="col-sm-4">
													<div class="card mb-3" style="max-width: 18rem;">
														<div class="card-header employee-dynamic-header text-center"><b><i class="fas fa-book"></i> Salary Expected</b></div>
														<div class="card-body text-dark">
															<h5 class="card-title text-center wercher-card-title">₱ <?php echo $SalaryExpected; ?></h5>
															<p class="card-text">
																<div class="col-sm-12 employee-static-item text-center mt-3">
																	<div class="col-sm-12 employee-dynamic-header">
																		<b>Documents (<?php echo $GetDocuments->num_rows(); ?>)</b>
																	</div>
																	<div class="col-sm-12">
																		<a href="#Documents" class="btn-sm btn btn-primary"><i class="far fa-eye"></i> View</a>
																	</div>
																</div>
																<div class="col-sm-12 employee-static-item text-center">
																	<div class="col-sm-12 employee-dynamic-header">
																		<b>Violations (<?php echo $GetDocumentsViolations->num_rows(); ?>)</b>
																	</div>
																	<div class="col-sm-12">
																		<a href="#Documents" class="btn-sm btn btn-danger"><i class="far fa-eye"></i> View</a>
																	</div>
																</div>
															</p>
														</div>
													</div>
												</div>
											</div>
											<?php else: ?>
											<div class="employee-content-header">
												<button id="<?php echo $ApplicantID; ?>" data-dismiss="modal" type="button" class="btn btn-primary btn-sm mr-auto ModalHire" data-toggle="modal" data-target="#hirthis"><i class="fas fa-plus"></i> New Contract</button>
												<button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#EmpContractHistory"><i class="fas fa-book"></i> Contract History</button>
											</div>
											<hr>
											<div class="row mt-4">
												<div class="col-sm-12">
													No available contract to show.
												</div>
											</div>
											<?php endif; ?>
										</div>
									</div>
									<div id="TabDocuments">
										<div class="employee-tabs-group-content">
											<div class="employee-content-header">
												<button id="<?php echo $ApplicantID; ?>" class="btn btn-primary btn-sm doc_btn" data-toggle="modal" data-target="#AddSuppDoc"><i class="fas fa-file-upload"></i> Upload Documents</button>
											</div>
											<hr>
											<div class="row">
												<div class="col-sm-8">
													<div class="row ml-3">
														<div class="col-sm-12">
															<span class="folder-button"><i class="fas fa-folder-open"></i> Documents (<?php echo $GetDocuments->num_rows(); ?>)</span>
														</div>
														<div id="FolderDocuments" class="folder-documents folder-active col-sm-12 mt-4 ml-5">
														<?php if ($GetDocuments->num_rows() > 0) { ?>
															<?php foreach ($GetDocuments->result_array() as $row): ?>
																	<div class="mb-3">
																		<div class="folder-documents-icon"><i class="fas fa-file-pdf"></i></div>
																		<div class="col-sm-12 ml-3">
																			<a class="ml-2" href="<?php echo $row['Doc_File'];?>" target="_blank">
																			<b><?php echo $row['Doc_FileName']; ?></b></a>
																		</div>
																		<div class="folder-documents-info col-sm-12 ml-4">
																			<?php echo $row['Subject']; ?> | Created by <?php echo $row['DateAdded']; ?>  (0MB)
																		</div>
																	</div>
															<?php endforeach ?>
														<?php } else { ?>
															No documents available.
														<?php } ?>
														</div>
													</div>
													<div class="row mt-4 ml-3">
														<div class="col-sm-12">
															<span class="folder-button"><i class="fas fa-folder"></i> Violations (<?php echo $GetDocumentsViolations->num_rows(); ?>)</span>
														</div>
														<div id="FolderViolations" class="folder-documents col-sm-12 mt-4 ml-5">
														<?php if ($GetDocumentsViolations->num_rows() > 0) { ?>
															<?php foreach ($GetDocumentsViolations->result_array() as $row): ?>
																	<div class="mb-3">
																		<div class="folder-documents-icon"><i class="fas fa-file-pdf"></i></div>
																		<div class="col-sm-12 ml-3">
																			<a class="ml-2" href="<?php echo $row['Doc_File'];?>" target="_blank">
																			<b>													<?php
																					if ($row['Type'] == 'Blacklist') {
																						echo '[BLACKLIST] - ' . $row['Doc_FileName'];
																					} else {
																						echo $row['Doc_FileName'];
																					}
																				?>		
																			</b></a>
																		</div>
																		<div class="folder-documents-info col-sm-12 ml-4">
																			<?php echo $row['Subject']; ?> | Created by <?php echo $row['DateAdded']; ?> (0MB)
																		</div>
																	</div>
															<?php endforeach ?>
														<?php } else { ?>
															No documents available.
														<?php } ?>
														</div>
													</div>
												</div>
												<div class="col-sm-4 employee-documents-notes">
													<div class="row mt-3 employee-documents-title">
														<div class="col-sm-10">
															<i class="fas fa-list"></i> Notes
														</div>
														<div class="col-sm-2 text-right">
															<button id="AddNoteBtn" applicant-id="<?php echo $ApplicantID; ?>" class="btn btn-primary btn-sm" type="button" data-toggle="modal" data-target="#AddNote"><i class="fas fa-plus" style="margin-right: -1px;"></i></button>
														</div>
													</div>
													<div class="row mt-2">
														<div class="col-sm-12">
															<table id="ListNotes" class="table table-borderless" style="width: 100%;">
																<thead>
																</thead>
																<tbody>
																	<?php
																	$RowCount = 0;
																	if ($GetDocumentsNotes->num_rows() > 0):
																		foreach ($GetDocumentsNotes->result_array() as $row):
																			$RowCount++;?>
																			<tr>
																				<td style="width: 8px;">
																					<?php echo $RowCount . '.'; ?>
																				</td>
																				<td>
																					<?php echo $row['Note'] ; ?>
																				</td>
																			</tr>
																		<?php endforeach;
																	else: ?>
																		<div class="mt-2">
																			No notes found.
																		</div>
																	<?php endif; ?>
																</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div id="TabAcademic">
										<div class="employee-tabs-group-content" id="TabAcademic">
											<div class="row">
												<div class="col-sm-12">
													<div class="table-responsive">
														<table class="table table-condensed">
															<thead class="employee-table-header">
																<th>Level</th>
																<th>School Name</th>
																<th>School Address</th>
																<th>From Year</th>
																<th>To Year</th>
																<th>Highest Degree / Certificate Attained</th>
															</thead>
															<tbody>
																<?php if ($GetAcadHistory->num_rows() > 0) { ?>
																	<?php foreach ($GetAcadHistory->result_array() as $row): ?>
																		<?php if ($ApplicantID == $row['ApplicantID']) { ?>
																			<tr>
																				<td><?php echo $row['Level'];?></td>
																				<td><?php echo $row['SchoolName'];?></td>
																				<td><?php echo $row['SchoolAddress'];?></td>
																				<td><?php echo $row['DateStarted'];?></td>
																				<td><?php echo $row['DateEnds'];?></td>
																				<td><?php echo $row['HighDegree'];?></td>
																			</tr>
																		<?php } ?>
																	<?php endforeach ?>
																<?php } else { ?>
																	<tr class="w-100 text-center">
																		<td colspan="6">
																			<h5>
																				No Data
																			</h5>
																		</td>
																	</tr>
																<?php } ?>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div id="TabEmployments">
										<div class="employee-tabs-group-content" id="TabEmployments">
											<div class="row">
												<div class="col-sm-12">
													<div class="table-responsive">
														<table class="table table-condensed">
															<thead class="employee-table-header">
																<th>Name</th>
																<th>Address</th>
																<th>Period Covered</th>
																<th>Position</th>
																<th>Salary</th>
																<th>Cause of Separation</th>
															</thead>
															<tbody>
																<?php if ($employment_record->num_rows() > 0) { ?>
																	<?php foreach ($employment_record->result_array() as $row): ?>
																		<?php if ($ApplicantID == $row['ApplicantID']) { ?>
																			<tr>
																				<td><?php echo $row['Name'];?></td>
																				<td><?php echo $row['Address'];?></td>
																				<td><?php echo $row['PeriodCovered'];?></td>
																				<td><?php echo $row['Position'];?></td>
																				<td><?php echo $row['Salary'];?></td>
																				<td><?php echo $row['CauseOfSeparation'];?></td>
																			</tr>
																		<?php } ?>
																	<?php endforeach ?>
																<?php } else { ?>
																	<tr class="w-100 text-center">
																		<td colspan="6">
																			<h5>
																				No Data
																			</h5>
																		</td>
																	</tr>
																<?php } ?>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div id="TabMachine">
										<div class="employee-tabs-group-content" id="TabMachine">
											<div class="row">
												<div class="col-sm-12">
													<div class="table-responsive">
														<table class="table table-condensed">
															<thead class="employee-table-header">
																<th>Machine Name</th>
																
															</thead>
															<tbody>
																<?php if ($Machine_Operatessss->num_rows() > 0) { ?>
																	<?php foreach ($Machine_Operatessss->result_array() as $row): ?>
																		<?php if ($ApplicantID == $row['ApplicantID']) { ?>
																			<tr>
																				<td><?php echo $row['MachineName'];?></td>
																			</tr>
																		<?php } ?>
																	<?php endforeach ?>
																<?php } else { ?>
																	<tr class="w-100 text-center">
																		<td>
																			<h5>
																				No Data
																			</h5>
																		</td>
																	</tr>
																<?php } ?>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- MOBILE VIEW -->
					<div class="d-block d-sm-none">
						<?php $this->load->view('users/u_viewemployee_mobile'); ?>
					</div>
				</div>
			</div>
		</div>
		<!-- EMPLOYED MODAL -->          
		<?php if($Status == 'Employed') { ?>          
		<div class="modal fade" id="EmpContractModal">
			<div class="modal-dialog modal-xl">
				<div class="modal-content">

				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title">Contract Report for <?=$LastName?>, <?=$FirstName?> <?=$MiddleInitial?>.</h4>
					<div class="text-right">
						<button onClick="printContent('PrintOutModal')" type="button" class="btn btn-primary mr-auto"><i class="fas fa-print"></i> Print</button>
						<button type="button" class="close d-none d-sm-block" data-dismiss="modal">&times;</button>
					</div>
				</div>

				<!-- Modal body -->
				<div class="modal-body">
					<div class="row rcontent">
						<?php
							$currTime = time();
							$strDateEnds = strtotime($DateEnds);
							$strDateStarted = strtotime($DateStarted);
							// PERCENTAGE
							$rPercentage = (($strDateEnds - $currTime) * 100) / ($strDateEnds - $strDateStarted);
							$rPercentage = round($rPercentage);
							// DAYS REMAINING
							$dateTimeZone = new DateTimeZone("Asia/Manila");
							$datetime1 = new DateTime('@' . $currTime, $dateTimeZone);
							$datetime2 = new DateTime('@' . $strDateEnds, $dateTimeZone);
							$interval = $datetime1->diff($datetime2);
							if($interval->format('%y years, %m months, %d days') == '0 years, 0 months, 0 days') {
								echo $interval->format('%H hours, %I minutes, %S seconds');
							} else {
								echo $interval->format('%y years, %m months, %d days');
							}
						?>
					</div>
				</div>

				<!-- Modal footer -->
				<div class="modal-footer">
					<button id="<?php echo $ApplicantID; ?>" data-dismiss="modal" type="button" class="btn btn-primary mr-auto ExtendButton" data-toggle="modal" data-target="#ExtendContractModal"><i class="fas fa-plus"></i> Extend Contract</button>
					<button type="button" class="btn btn-danger ml-auto" data-dismiss="modal">Close</button>
				</div>

				</div>
			</div>
		</div>
		<?php } ?>
		<!-- EXPIRED MODAL -->
		<?php if($Status == 'Expired') { ?>          
		<div class="modal fade" id="EmpContractModal">
			<div class="modal-dialog modal-xl">
				<div class="modal-content">

				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title">Previous Contract Report for <?=$LastName?>, <?=$FirstName?> <?=$MiddleInitial?>.</h4>
					<div class="text-right">
						<button onClick="printContent('PrintOutModalExpired')" type="button" class="btn btn-primary mr-auto"><i class="fas fa-print"></i> Print</button>
						<button type="button" class="close d-none d-sm-block" data-dismiss="modal">&times;</button>
					</div>
				</div>

				<!-- Modal body -->
				<div class="modal-body">
					<div class="row rcontent">
					</div>
				</div>

				<!-- Modal footer -->
				<div class="modal-footer">
					<button type="button" class="btn btn-danger ml-auto" data-dismiss="modal">Close</button>
				</div>

				</div>
			</div>
		</div>
		<?php } ?>
		<!-- CLIENT HIRE MODAL -->
		<?php $this->load->view('_template/modals/m_clienthire'); ?>
		<!-- CONTRACT HISTORY MODAL -->
		<?php $this->load->view('_template/modals/m_contracthistory'); ?>
		<!-- EXTEND CONTRACT MODAL -->
		<?php $this->load->view('_template/modals/m_extendcontract'); ?>
		<!-- SET A REMINDER MODAL -->
		<?php $this->load->view('_template/modals/m_setreminder'); ?>
		<!-- DOCUMENT MODAL -->
		<?php $this->load->view('_template/modals/m_documents'); ?>
		<!-- DOCUMENTS NOTE MODAL -->
		<?php $this->load->view('_template/modals/m_addnote_documents'); ?>
		<!-- ADD DOCUMENTS MODAL -->
		<?php $this->load->view('_template/modals/m_adddocuments'); ?>
		<!-- GENERATE ID CARD MODAL -->
		<?php $this->load->view('_template/modals/m_generateid'); ?>
		<!-- SUSPEND MODAL -->
		<?php $this->load->view('_template/modals/m_suspend'); ?>
	</body>
	<?php $this->load->view('_template/users/u_scripts');?>
	<script type="text/javascript">
		$(document).ready(function () {
			$('[data-toggle="tooltip"]').tooltip();
			$('#ClientSelect').on('change', function() {
				<?php foreach ($getClientOption->result_array() as $row): ?>
				<?php
				// Count how many employees are on the client
				$CountEmployees = $this->Model_Selects->GetClientsEmployed($row['ClientID'])->num_rows();
				$CountEmployees++;
				$CountEmployees = str_pad($CountEmployees,4,0,STR_PAD_LEFT);
				// Get the current year
				$Year = date('Y');
				$Year = substr($Year, 2);
				// Concatenate them all together
				$EmployeeID = 'WC' . $row['EmployeeIDSuffix'] . '-' . $CountEmployees . '-' . $Year;
				?>
				if ($(this).val() == '<?php echo $row['ClientID']; ?>') {
					$(this).closest('#ClientModal').find('#EmployeeID').val('<?php echo $EmployeeID; ?>');
				}
				<?php endforeach; ?>
			});
			$("#Type").change(function(){
				$('#ViolationNotice').hide();
				$('#BlacklistNotice').hide();
				if ( $(this).val() == "Violation" ) { 
					$("#ViolationNotice").fadeIn();
			    }
			    if ( $(this).val() == "Blacklist" ) { 
					$("#BlacklistNotice").fadeIn();
			    }
			});
			$('.doc_btn').on('click', function () {
				$('#Pass_ID').val($(this).attr('id'));
			});
			$('.employee-tabs-select').removeClass('employee-tabs-active');
			$('.employee-tabs-group-content').hide();
			var hashValue = window.location.hash.substr(1);
			if (hashValue == 'Personal') {
				$('#TabPersonal').children('.employee-tabs-group-content').show();
				$('#TabPersonalBtn').addClass('employee-tabs-active');
			}
			else if (hashValue == 'Contract') {
				$('#TabContract').children('.employee-tabs-group-content').show();
				$('#TabContractBtn').addClass('employee-tabs-active');
			}
			else if (hashValue == 'Documents') {
				$('#TabDocuments').children('.employee-tabs-group-content').show();
				$('#TabDocumentsBtn').addClass('employee-tabs-active');
			}
			else if (hashValue == 'Academic') {
				$('#TabAcademic').children('.employee-tabs-group-content').show();
				$('#TabAcademicBtn').addClass('employee-tabs-active');
			}
			else if (hashValue == 'Employments') {
				$('#TabEmployments').children('.employee-tabs-group-content').show();
				$('#TabEmploymentsBtn').addClass('employee-tabs-active');
			}
			else if (hashValue == 'Machine') {
				$('#TabMachine').children('.employee-tabs-group-content').show();
				$('#TabMachineBtn').addClass('employee-tabs-active');
			} else {
				$('#TabPersonal').children('.employee-tabs-group-content').show();
				$('#TabPersonalBtn').addClass('employee-tabs-active');
			}
			$(window).on('hashchange',function(){ 
				$('.employee-tabs-select').removeClass('employee-tabs-active');
				$('.employee-tabs-group-content').hide();
				var hashValue = window.location.hash.substr(1);
				if (hashValue == 'Personal') {
					$('#TabPersonal').children('.employee-tabs-group-content').show();
					$('#TabPersonalBtn').addClass('employee-tabs-active');
				}
				else if (hashValue == 'Contract') {
					$('#TabContract').children('.employee-tabs-group-content').show();
					$('#TabContractBtn').addClass('employee-tabs-active');
				}
				else if (hashValue == 'Documents') {
					$('#TabDocuments').children('.employee-tabs-group-content').show();
					$('#TabDocumentsBtn').addClass('employee-tabs-active');
				}
				else if (hashValue == 'Academic') {
					$('#TabAcademic').children('.employee-tabs-group-content').show();
					$('#TabAcademicBtn').addClass('employee-tabs-active');
				}
				else if (hashValue == 'Employments') {
					$('#TabEmployments').children('.employee-tabs-group-content').show();
					$('#TabEmploymentsBtn').addClass('employee-tabs-active');
				}
				else if (hashValue == 'Machine') {
					$('#TabMachine').children('.employee-tabs-group-content').show();
					$('#TabMachineBtn').addClass('employee-tabs-active');
				} else {
					$('#TabPersonal').children('.employee-tabs-group-content').show();
					$('#TabPersonalBtn').addClass('employee-tabs-active');
				}
			});
			$('#AddNoteBtn').on('click', function () {
				$('#AddNote_ApplicantID').val($(this).attr('applicant-id'));
			});
			$('.folder-button').on('click', function () {
				$(this).children('i').toggleClass('fa-folder');
				$(this).children('i').toggleClass('fa-folder-open');
				$(this).closest('.row').find('.folder-documents').toggleClass('folder-active');
			});
			// $('.employee-tabs-select').on('click', function () {
			// 	$('.employee-tabs-select').removeClass('employee-tabs-active');
			// 	$(this).addClass('employee-tabs-active');
			// 	$('.employee-tabs-group-content').hide();
			// });
			$('#TabDocumentsBtnAlt').on('click', function () {
				$('.employee-tabs-select').removeClass('employee-tabs-active');
				$('#TabDocumentsBtn').addClass('employee-tabs-active');
				$('.employee-tabs-group-content').hide();
			});
			$('#TabPersonalBtn').on('click', function () {
				$('#TabPersonal').children('.employee-tabs-group-content').fadeIn(100);
			});
			$('#TabContractBtn').on('click', function () {
				$('#TabContract').children('.employee-tabs-group-content').fadeIn(100);
			});
			$('#TabDocumentsBtn, #TabDocumentsBtnAlt').on('click', function () {
				$('#TabDocuments').children('.employee-tabs-group-content').fadeIn(100);
			});
			$('#TabAcademicBtn').on('click', function () {
				$('#TabAcademic').children('.employee-tabs-group-content').fadeIn(100);
			});
			$('#TabEmploymentsBtn').on('click', function () {
				$('#TabEmployments').children('.employee-tabs-group-content').fadeIn(100);
			});
			$('#TabMachineBtn').on('click', function () {
				$('#TabMachine').children('.employee-tabs-group-content').fadeIn(100);
			});
			$('#TabNotesBtn').on('click', function () {
				$('#TabNotes').children('.employee-tabs-group-content').fadeIn(100);
			});
			if (localStorage.getItem('SidebarVisible') == 'true') {
				$('#sidebar').addClass('active');
				$('.ncontent').addClass('shContent');
			} else {
				$('#sidebar').css('transition', 'all 0.3s');
				$('#content').css('transition', 'all 0.3s');
			}
			$('#sidebarCollapse').on('click', function () {
				if (localStorage.getItem('SidebarVisible') == 'false') {
					$('#sidebar').addClass('active');
					$('.ncontent').addClass('shContent');
					$('#sidebar').css('transition', 'all 0.3s');
					$('#content').css('transition', 'all 0.3s');
			    	localStorage.setItem('SidebarVisible', 'true');
				} else {
					$('#sidebar').removeClass('active');
					$('.ncontent').removeClass('shContent');
					$('#sidebar').css('transition', 'all 0.3s');
					$('#content').css('transition', 'all 0.3s');
			    	localStorage.setItem('SidebarVisible', 'false');
				}
			});
			$('.ModalHire').on('click', function () {
				$('#idToHire').val($(this).attr('id'));
				console.log($('#idToHire').val());
			});
			$('.ExtendButton').on('click', function () {
				$('#ExtendID').val($(this).attr('id'));
				console.log($('#ExtendID').val());
				console.log($('#ExtendDate').val());
			});
			$('.SuspendButton').on('click', function () {
				$('#SuspendID').val($(this).attr('id'));
				console.log($('#SuspendID').val());
			});
			$('.ReminderButton').on('click', function () {
				$('#ReminderID').val($(this).attr('id'));
				console.log($('#ReminderID').val());
			});
			$('#ListContractHistory').DataTable();
			$('#ListViolations').DataTable();
			// Contract Bar
			var rPercentage = $("#TimeLeft").val();
			var SuspensionPercentage = $("#SuspensionTimeLeft").val();
			// if (rPercentage > 100) {
			// 	rPercentage = 100;
			// }
			$('.progressRemaining').animate({width:rPercentage + "%"},1500);
			$('.SuspensionRemaining').animate({width:SuspensionPercentage + "%"},1500);
			$('.progress_value').text(rPercentage + "%");
			$('.SuspensionValue').text(SuspensionPercentage + "%");
			$('.a_eImage').on('click', function () {
				var src1 = $(this).attr('id');
				$("#enlargeImage_doc").attr("src", src1);
			});

		});
		function hideModal() {
			$("#EmpContractModal").modal('hide');
		}
	</script>
	<style>
		.dropdown-item:hover {
			background-color: rgba(235, 235, 235, 1.0);
		}
		.blacklisted-notice {
			border-radius: 6px;
			background-color: rgba(255, 50, 50, 0.25);
		}
	</style>
	<textarea id="text" style="display: none;"></textarea>
	</html>