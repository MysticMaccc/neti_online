<?php

use App\Http\Livewire\LoginOtp;
use App\Mail\SendZoomCredentials;
use App\Http\Livewire\Landing\Faq;
use App\Http\Livewire\LandingPage;
use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Landing\Contact;
use App\Http\Livewire\Admin\Pde\PdeStatus;
use App\Http\Livewire\Landing\CoursesList;
use App\Http\Livewire\Landing\DataPrivacy;
use App\Http\Livewire\Admin\Pde\PdeReports;
use App\Http\Livewire\Admin\Pde\RequestPde;
use App\Http\Livewire\Login\TLoginComponents;
use App\Http\Livewire\Registration\VerifyOtp;
use App\Http\Livewire\Admin\Pde\PdeMaintenance;
use App\Http\Livewire\Admin\ADashboardComponent;
use App\Http\Livewire\Admin\Pde\PdeRequirements;
use App\Http\Livewire\Landing\ThankyouComponent;
use App\Http\Livewire\Trainee\LMS\TLmsComponent;
use App\Http\Livewire\Admin\Admin\AdminComponent;
use App\Http\Livewire\Admin\Billing\ABillingDrop;
use App\Http\Livewire\Admin\Pde\PdeReportHistory;
use App\Http\Livewire\Company\EditCompanyProfile;
use App\Http\Livewire\Trainee\Enroll\TEnrollCards;
use App\Http\Livewire\Trainee\TDashboardComponent;
use App\Http\Livewire\Admin\Pde\PdeReportDashboard;
use App\Http\Livewire\Admin\Pde\PdeReportAssessment;
use App\Http\Livewire\Login\TPasswordResetComponent;
use App\Http\Livewire\Admin\Admin\AdminEditComponent;
use App\Http\Livewire\Admin\Pde\PdeReportCertificate;
use App\Http\Livewire\Instructor\TPER\ITperComponent;
use App\Http\Livewire\Login\TForgetPasswordComponent;
use App\Http\Livewire\Admin\Pde\GeneratePdeAssessment;
use App\Http\Livewire\Admin\Trainee\ATraineeComponent;
use App\Http\Livewire\Registration\RRegisterComponent;
use App\Http\Livewire\Trainee\Enroll\TEnrollComponent;
use App\Http\Livewire\Trainee\LMS\TLmsPeopleComponent;
use App\Http\Livewire\Admin\Admin\AssignRolesComponent;
use App\Http\Livewire\Admin\Pde\GeneratePdeCertificate;
use App\Http\Livewire\Admin\Trainee\ATHistoryComponent;
use App\Http\Livewire\Notification\NotificationHistory;
use App\Http\Livewire\Admin\Enrollment\AEnrollComponent;
use App\Http\Livewire\Admin\Remedial\ARemedialComponent;
use App\Http\Livewire\Trainee\Courses\TCoursesComponent;
use App\Http\Livewire\Trainee\Handout\THandoutComponent;
use App\Http\Livewire\Trainee\LMS\TLmsSyllabusComponent;
use App\Http\Livewire\Admin\Courses\CoursesListComponent;
use App\Http\Livewire\Admin\Pde\PdeAssessmentMaintenance;
use App\Http\Livewire\Admin\Pde\PdeMaintenanceAssessment;
use App\Http\Livewire\Admin\TCROA\ADisplayTcroaComponent;
use App\Http\Livewire\Company\CDashboardCompanyComponent;
use App\Http\Livewire\Dormitory\DormitoryNoshowComponent;
use App\Http\Livewire\Admin\Billing\ABillingDropComponent;
use App\Http\Livewire\Admin\Billing\ABillingViewComponent;
use App\Http\Livewire\Admin\Billing\APriceMatrixComponent;
use App\Http\Livewire\Admin\Maintenance\Faq\FaqComponents;
use App\Http\Livewire\Admin\Pde\PdeMaintenanceCertificate;
use App\Http\Livewire\Dormitory\DormitoryCheckInComponent;
use App\Http\Livewire\Registration\RPersonalInfoComponent;
use App\Http\Livewire\Trainee\LMS\TLmsCourseInfoComponent;
use App\Http\Livewire\Admin\Trainee\ATEditProfileComponent;
use App\Http\Livewire\Dormitory\DormitoryCheckOutComponent;
use App\Http\Livewire\Dormitory\DormitoryReservedComponent;
use App\Http\Livewire\Admin\Admin\AdminManageUsersComponent;
use App\Http\Livewire\Admin\Instructor\IInstructorComponent;
use App\Http\Livewire\Admin\Maintenance\Rank\RankComponents;
use App\Http\Livewire\Admin\Maintenance\Room\RoomComponents;
use App\Http\Livewire\Admin\Maintenance\Smtp\SmtpComponents;
use App\Http\Livewire\Company\Enroll\CCalendarShowComponent;
use App\Http\Livewire\Company\Enroll\CViewTraineesComponent;
use App\Http\Livewire\Trainee\Profile\TEditProfileComponent;
use App\Http\Livewire\Admin\Dormitory\WaiverAmmenitiesReport;
use App\Http\Livewire\Admin\Maintenance\Roles\RolesComponent;
use App\Http\Livewire\Admin\Reports\Batch\ViewBatchComponent;
use App\Http\Livewire\Admin\Trainee\ATEditSecurityeComponent;
use App\Http\Livewire\Chat\Parent\IndexConversationComponent;
use App\Http\Livewire\Trainee\Profile\TEditSecurityComponent;
use App\Http\Livewire\Admin\GenerateDocs\AGenerateSDComponent;
use App\Http\Livewire\Trainee\Courses\TCourseDetailsComponent;
use App\Http\Livewire\Admin\Enrollment\AConfirmEnrollComponent;
use App\Http\Livewire\Admin\Enrollment\AEnrollmentLogComponent;
use App\Http\Livewire\Admin\GenerateDocs\AGenerateCcrComponent;
use App\Http\Livewire\Admin\Instructor\IGenerateTrainingReport;
use App\Http\Livewire\Admin\Remedial\AViewRAttendanceComponent;
use App\Http\Livewire\Admin\Reports\AReportsDashboardComponent;
use App\Http\Livewire\Company\Enroll\CCalendarDetailsComponent;
use App\Http\Livewire\Dormitory\DormitoryCheckOutListComponent;
use App\Http\Livewire\Instructor\Dashboard\IDashboardComponent;
use App\Http\Livewire\Instructor\TPER\ITperSecondPageComponent;
use App\Http\Livewire\Admin\Billing\ABillingMonitoringComponent;
use App\Http\Livewire\Admin\GenerateDocs\AGenerateAdmissionSlip;
use App\Http\Livewire\Admin\GenerateDocs\AGeneratePDOSComponent;
use App\Http\Livewire\Admin\GenerateDocs\AGenerateTperComponent;
use App\Http\Livewire\Admin\Pde\GeneratePdeReportExcelComponent;
use App\Http\Livewire\Admin\Pde\UploadPdeRequirementsComponents;
use App\Http\Livewire\Trainee\Certificate\TCertificateComponent;
use App\Http\Livewire\Trainee\Enroll\TGenerateEnrolAtdComponent;
use App\Http\Livewire\Trainee\Enroll\TProcessingEnrollComponent;
use App\Http\Livewire\Admin\Maintenance\Handout\HandoutComponent;
use App\Http\Livewire\Admin\Pde\GeneratePdeReportAnnex1Component;
use App\Http\Livewire\Admin\Pde\GeneratePdeReportAnnex2Component;
use App\Http\Livewire\Company\Billing\CClientBillingViewSchedule;
use App\Http\Livewire\Company\Billing\CClientBillingViewTrainees;
use App\Http\Livewire\Company\Enroll\CConfirmEnrollmentComponent;
use App\Http\Livewire\Dormitory\DormitoryWaiverGenerateComponent;
use App\Http\Livewire\ImportQueries\HashTraineePasswordComponent;
use App\Http\Livewire\Technical\Dashboard\TechDashboardComponent;
use App\Http\Livewire\Admin\Billing\ABillingViewTraineesComponent;
use App\Http\Livewire\Admin\GenerateDocs\GenerateBillingStatement;
use App\Http\Livewire\Admin\Instructor\InstructorHistoryComponent;
use App\Http\Livewire\Admin\Billing\BankAccountManagementComponent;
use App\Http\Livewire\Admin\Billing\GeneratedDocs\BillingStatement;
use App\Http\Livewire\Admin\Dormitory\DailyWeeklyReportsComponents;
use App\Http\Livewire\Trainee\Enroll\TGenerateSalaryDeducComponent;
use App\Http\Livewire\Admin\Pde\GenerateAssessmentTemplateComponent;
use App\Http\Livewire\Admin\Certificate\ACertificateHistoryComponent;
use App\Http\Livewire\Admin\Maintenance\Handout\ViewHandoutComponent;
use App\Http\Livewire\Admin\GenerateDocs\AGenerateAttendanceComponent;
use App\Http\Livewire\Admin\GenerateDocs\AGenerateCertificateComponent;
use App\Http\Livewire\Admin\Maintenance\MaintenanceDashboardComponents;
use App\Http\Livewire\Dormitory\DormitoryRoomPriceMaintenanceComponent;
use App\Http\Livewire\Trainee\Certificate\TCertificateDetailsComponent;
use App\Http\Livewire\Admin\Communication\Inquiries\InquiriesComponents;
use App\Http\Livewire\Admin\Communication\Textblast\TextblastComponents;
use App\Http\Livewire\Admin\Reports\Batch\AGenerateBatchReportComponent;
use App\Http\Livewire\Admin\TrainingCalendar\ATrainingCalendarComponent;
use App\Http\Livewire\Company\Billing\CClientBillingStatementMonitoring;
use App\Http\Livewire\Admin\Billing\Child\ClientInfo\UpdateInfoComponent;

use App\Http\Livewire\Admin\Certificate\ACertificateMaintenanceComponent;
use App\Http\Livewire\Admin\Billing\APriceMatrixCoursesSettingsComponents;
use App\Http\Livewire\Admin\GenerateDocs\AGenerateInstructorListComponent;
use App\Http\Livewire\Admin\GenerateDocs\AGenerateUrlCertificateComponent;
use App\Http\Livewire\Admin\Reports\TraineeBatch\APendingTraineeComponent;

use App\Http\Livewire\OutsideFunctions\RecordTraineeConfirmationComponent;
use App\Http\Livewire\Admin\GenerateDocs\AGeneratePendingTraineesComponent;
use App\Http\Livewire\Admin\Certificate\ACertificateHistoryDetailsComponent;
use App\Http\Livewire\Admin\Communication\Textblast\TextblastLogsComponents;
use App\Http\Livewire\Admin\Cronjob\InstructorAttachmentExpirationComponent;
use App\Http\Livewire\Admin\GenerateDocs\AGenerateEnrollmentReportComponent;
use App\Http\Livewire\Admin\GenerateDocs\GenerateInstructorHistoryComponent;

use App\Http\Livewire\Admin\Maintenance\Announcement\AnnouncementComponents;
use App\Http\Livewire\Admin\TrainingCalendar\ATrainingCalendarShowComponent;
use App\Http\Livewire\Admin\Certificate\ACertificateShowMaintenanceComponent;
use App\Http\Livewire\Admin\Communication\Inquiries\InquiriesReplyComponents;

use App\Http\Livewire\Admin\Maintenance\CompanyMaintenance\CompanyComponents;
use App\Http\Livewire\Admin\GenerateDocs\AGenerateTraineeBatchReportComponent;
use App\Http\Livewire\Admin\Instructor\EditInstructor\EditInstructorComponent;
use App\Http\Livewire\CronJob\SendEnrollmentConfirmationNotificationComponent;

use App\Http\Livewire\Admin\Communication\Inquiries\InquiriesDetailsComponents;
use App\Http\Livewire\Admin\Instructor\EditInstructor\EditCertificateComponent;
use App\Http\Livewire\Admin\Reports\AvailableCourse\AAvailableCoursesComponent;
use App\Http\Livewire\Admin\Admin\AdminAttachmentEmailNotificationAssignComponent;
use App\Http\Livewire\Admin\Billing\Child\Bank\UpdateBankComponent;
use App\Http\Livewire\Admin\GenerateDocs\AGenerateInstructorAttachmentsComponent;
use App\Http\Livewire\Admin\GenerateDocs\AGenerateWeeklyTrainingScheduleComponent;
use App\Http\Livewire\Admin\Reports\TraineeBatch\AViewTraineeBatchReportComponent;
use App\Http\Livewire\Dormitory\DormitoryRoomAssignmentDailyWeeklyReportComponent;
use App\Http\Livewire\Admin\Maintenance\CourseDepartment\CourseDepartmentComponents;
use App\Http\Livewire\Admin\Maintenance\LandingPageCover\LandingPageCoverComponents;
use App\Http\Livewire\Admin\GenerateDocs\IGenerateIntructorInformationSheetComponent;

use App\Http\Livewire\Admin\Maintenance\CompanyMaintenance\AddRatePerCourseComponent;
use App\Http\Livewire\Admin\Reports\TrainingSchedule\AWeeklyTrainingScheduleComponent;
use App\Http\Livewire\Admin\Instructor\EditInstructor\ViewArchivesCertificateComponent;
use App\Http\Livewire\Admin\Reports\AvailableCourse\AGenerateAvailableCoursesComponent;
use App\Http\Livewire\Admin\TrainingCalendar\Special\ATrainingSpecialCalendarComponent;
use App\Http\Livewire\Admin\Instructor\EditInstructor\EditCertificatesLicensesComponent;
use App\Http\Livewire\Admin\Reports\Grades\AExcelGradeComponent;
use App\Http\Livewire\Admin\Reports\TraineeBatch\AExcelTraineeBatchReport;
use App\Http\Livewire\Admin\Reports\TrainingSchedule\AExcelWeeklyTrainingScheduleComponent;
use App\Http\Livewire\Admin\TrainingCalendar\Special\ATrainingSpecialCalendarShowComponent;
use App\Http\Livewire\Dormitory\DormitoryEmergencyCheckInComponent;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', LandingPage::class)->name('landing');
Route::get('/contact-us', Contact::class)->name('contact');
Route::get('/data-privacy', DataPrivacy::class)->name('dataprivacy');
Route::get('/FAQ', Faq::class)->name('faq');
Route::get('/Courses-List/{hashid}', CoursesList::class)->name('courseslist');

//Notifications
Route::get('/notification', InstructorAttachmentExpirationComponent::class)->name('a.notification');

Route::get('/successfully-created', ThankyouComponent::class)->name('thankyou');

Route::get('/sign-up', RRegisterComponent::class)->name('registration');
Route::get('/verify-otp', VerifyOtp::class)->name('verify.otp');
Route::get('/trainee/login', TLoginComponents::class)->name('t.login');
Route::get('/trainee/forget-password', TForgetPasswordComponent::class)->name('t.forget-password');
Route::get('/confirm-password/{token}', TPasswordResetComponent::class)->name('t.confirm-password');



Route::middleware(['verifyOTPForRegistration'])->group(function () {
    Route::get('/registration', RPersonalInfoComponent::class)->name('personal.info');
});

Route::get('/login-otp', LoginOtp::class)->name('login.otp');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::middleware(['authadmin'])->group(function () {
        Route::middleware(['verifyOTPForLogin'])->group(function () {
            Route::get('/admin/dashboard', ADashboardComponent::class)->name('a.dashboard');


            //Dormitory
            Route::get('/dormitory/checkin', DormitoryCheckInComponent::class)->name('d.checkin');
            Route::get('/dormitory/checkout', DormitoryCheckOutComponent::class)->name('d.checkout');
            Route::get('/dormitory/noshow', DormitoryNoshowComponent::class)->name('d.noshow');
            Route::get('/dormitory/report/dailyweeklyreports', DormitoryRoomAssignmentDailyWeeklyReportComponent::class)->name('d.reportsdailyweekly');
            Route::get('/dormitory/checkoutlist', DormitoryCheckOutListComponent::class)->name('d.checkoutlist');
            Route::get('/dormitory/roompricemaintenance', DormitoryRoomPriceMaintenanceComponent::class)->name('d.roompricemaintenance');
            Route::get('/dormitory/waiverammenities', DormitoryWaiverGenerateComponent::class)->name('d.waiverammenitiesreport');
            Route::get('/dormitory/emergencycheckin', DormitoryEmergencyCheckInComponent::class)->name('d.emcheckin');
            Route::get('/dormitory/reservations', DormitoryReservedComponent::class)->name('d.reserve');

            //Trainee
            Route::get('/admin/trainee-account', ATraineeComponent::class)->name('a.trainee');
            Route::get('/admin/edit-trainee/{traineeid}', ATEditProfileComponent::class)->name('a.editprofile');
            Route::get('/admin/edit-security/{traineeid}', ATEditSecurityeComponent::class)->name('a.editsecurity');
            Route::get('/admin/view-history/{traineeid}', ATHistoryComponent::class)->name('a.history');            

            //Instructor
            Route::get('/admin/instructor-account', IInstructorComponent::class)->name('a.instructor');
            Route::get('/admin/instructor-history', InstructorHistoryComponent::class)->name('a.instructor-history');
            Route::get('/admin/email-notifications', AdminAttachmentEmailNotificationAssignComponent::class)->name('a.email-notifications');
            Route::get('/admin/instructor-attachment-export', [AGenerateInstructorAttachmentsComponent::class, 'generatePDF'])->name('a.generateinstructorattachmentsummary');

            //Edit Instructor
            Route::get('/admin/edit-instructor/{hashid}', EditInstructorComponent::class)->name('a.edit-instructor');
            Route::get('/admin/edit-certificate/{hashid}', EditCertificateComponent::class)->name('a.edit-certificate');
            Route::get('/admin/edit-certificatelicenses/{hashid}', EditCertificatesLicensesComponent::class)->name('a.edit-ocertificatelicenses');
            Route::get('/admin/view-archives/{hashid}', ViewArchivesCertificateComponent::class)->name('a.view-archives');

            //Instructor PDF export
            //Instructor Information Sheet
            Route::get('admin/reports/instructor/info-sheet/{hashid}', [IGenerateIntructorInformationSheetComponent::class, 'viewPdf'])->name('a.information-sheets');

            //Instructor List
            Route::get('admin/reports/instructor/exportinstructorlist', [AGenerateInstructorListComponent::class, 'generatePdf'])->name('a.instructor-list');
            // Route::get('admin/reports/instructor/exportinstructorlist', [AGenerateInstructorListComponent::class])->name('a.instructor-list');

            // Dormitory Report Daily/Weekly Generation
            Route::get('/admin/dormitory/dailyweeklyreports', [DailyWeeklyReportsComponents::class, 'generatePdf'])->name('a.dormitorydailyweeklyreports');
            Route::get('/admin/dormitory/ammenitieswaiver', [WaiverAmmenitiesReport::class, 'generatePdf'])->name('a.dormitorywaiverammenities');
            // Route::get('/admin/dormitory/dailyweeklyreports/{reporttype}', DailyWeeklyReportsComponents::class)->name('a.dormitorydailyweeklyreports');

            //Admin
            Route::get('/admin/admin-account', AdminComponent::class)->name('a.admin');
            Route::get('/admin/admin-user-account', AdminManageUsersComponent::class)->name('a.adminusers');
            Route::get('/admin/assignroles' , AssignRolesComponent::class)->name('a.assign-roles');

            //Enrollment
            Route::get('/admin/enrollment/confirm-enroll', AConfirmEnrollComponent::class)->name('a.confirmenroll');
            Route::get('/admin/enrollment/generate/admission-slip/{enrol_id}', [AGenerateAdmissionSlip::class, 'generatePdf'])->name('a.viewadmission');
            Route::get('/admin/enrollment/enroll-crew', AEnrollComponent::class)->name('a.enroll');
            Route::get('/admin/enrollment/logs', AEnrollmentLogComponent::class)->name('a.enrollog');


            //Courses
            Route::get('/admin/courses',CoursesListComponent::class)->name('a.courses');

            //Maintenance
            Route::get('/admin/training-calendar', ATrainingCalendarComponent::class)->name('a.trainingcalendar');
            Route::get('/admin/training-calendar/{course_id}', ATrainingCalendarShowComponent::class)->name('a.calendarshow');
            Route::get('/admin/special-class', ATrainingSpecialCalendarComponent::class)->name('a.specialcalendar');
            Route::get('/admin/special-class/{course_id}', ATrainingSpecialCalendarShowComponent::class)->name('a.specialcalendarshow');

            //Maintenance
            Route::get('admin/maintenance', MaintenanceDashboardComponents::class)->name('a.maintenance');
            Route::get('admin/faq-maintenance', FaqComponents::class)->name('a.faq');
            Route::get('admin/room-maintenance', RoomComponents::class)->name('a.room');
            Route::get('admin/announcement-maintenance', AnnouncementComponents::class)->name('a.announcement');
            Route::get('admin/rank-maintenance', RankComponents::class)->name('a.rank');
            Route::get('admin/course-department-maintenance', CourseDepartmentComponents::class)->name('a.coursedepartment');
            Route::get('admin/smtp-maintenance', SmtpComponents::class)->name('a.smtp');
            Route::get('admin/landing-page-maintenance', LandingPageCoverComponents::class)->name('a.landingcover');
            Route::get('admin/handout-maintenance' , HandoutComponent::class)->name('a.handout');
            Route::get('admin/view-handout' , ViewHandoutComponent::class)->name('a.view-handout');
            Route::get('admin/roles' , RolesComponent::class)->name('a.roles');

            //Communication
            Route::get('admin/communication/inquiries', InquiriesComponents::class)->name('a.inquiries');
            Route::get('communication/inquiry-view/{hash_id}', InquiriesDetailsComponents::class)->name('a.inquiry-view');
            Route::get('admin/communication/inquiriy-reply/{emailinquiryid}', InquiriesReplyComponents::class)->name('a.inquiry-reply');

            //TextBlast
            Route::get('admin/communication/textblast', TextblastComponents::class)->name('a.textblast');
            Route::get('admin/communication/textblast-logs', TextblastLogsComponents::class)->name('a.textblastlogs');

            //PDE
            Route::get('admin/pde/pde-request', RequestPde::class)->name('a.requestpde');
            Route::get('admin/pde/pde-status', PdeStatus::class)->name('a.pdestatus');
            Route::get('admin/pde/pde-report', PdeReportDashboard::class)->name('a.pdereport');
            Route::get('admin/pde/pde-report-assessment', PdeReportAssessment::class)->name('a.pdereportassessment');
            Route::get('admin/pde/pde-report-generate-assessment/{pdeid}', [GeneratePdeAssessment::class, 'viewPDF'])->name('a.pdereportgenerateassessment');
            Route::get('admin/pde/pde-report-certificate', PdeReportCertificate::class)->name('a.pdereportcertificate');
            Route::get('admin/pde/pde-report-generate-certificate/{pdeid}', [GeneratePdeCertificate::class, 'viewPDF'])->name('a.pdereportgeneratecertificate');
            Route::get('admin/pde/pde-report-history',PdeReportHistory::class)->name('a.pdereporthistory');
            Route::get('admin/pde/pde-maintenance',PdeMaintenance::class)->name('a.pdemaintenance');
            Route::get('pde-assessment-maintenance/{rankid}',PdeAssessmentMaintenance::class)->name('a.pdeassessmaint');
            Route::get('pde-assessment-template/preview/{rankid}',[GenerateAssessmentTemplateComponent::class, 'viewPDF'])->name('a.massessmenttemplate');
            Route::get('pde-certificate-maintenance',PdeMaintenanceCertificate::class)->name('a.pdemaintenancertificate');
            Route::get('pde-reports',PdeReports::class)->name('a.pdereports');
            Route::get('pde-reports-annex-1/{datefrom}/{dateto}', [GeneratePdeReportAnnex1Component::class, 'printPDF'])->name('a.printpdeannex1');
            Route::get('pde-reports-annex-2/{datefrom}/{dateto}', [GeneratePdeReportAnnex2Component::class, 'printPDF'])->name('a.printpdeannex2');
            Route::get('pde-reports-excel/{datefrom}/{dateto}', [GeneratePdeReportExcelComponent::class, 'exportPdeExcel'])->name('a.exportPdeExcel');
            Route::get('admin/pde/upload-requirements/{pdeid}', UploadPdeRequirementsComponents::class)->name('a.uploadpderequirements');



            //Reports
            Route::get('admin/reports', AReportsDashboardComponent::class)->name('a.report-dashboard');
            Route::get('admin/reports/batch-report', AGenerateBatchReportComponent::class)->name('a.report-batch');
            Route::get('admin/reports/weekly-training-schedule', AWeeklyTrainingScheduleComponent::class)->name('a.view-training-schedule');
            Route::get('admin/reports/weekly-training-schedule/{selected_batch}', [AGenerateWeeklyTrainingScheduleComponent::class, 'generatePdf'])->name('a.view-training-schedule-pdf');
            Route::get('admin/reports/e-weekly-training-schedule/{selected_batch}', [AExcelWeeklyTrainingScheduleComponent::class, 'export'])->name('a.view-training-schedule-excel');
            Route::get('admin/reports/trainee-batch', AViewTraineeBatchReportComponent::class)->name('a.view-trainee-batch');
            Route::get('admin/reports/trainee-batch/{selected_batch}', [AGenerateTraineeBatchReportComponent::class, 'generatePdf'])->name('a.view-trainee-batch-pdf');
            Route::get('admin/reports/trainee-batch/ex/{selected_batch}', [AExcelTraineeBatchReport::class, 'export'])->name('a.view-trainee-batch-excel');
            Route::get('admin/reports/pending-trainee', APendingTraineeComponent::class)->name('a.pending-trainee');
            Route::get('admin/reports/pending-trainee/{selected_batch}', [AGeneratePendingTraineesComponent::class, 'generatePdf'])->name('a.pending-trainee-pdf');
            Route::get('admin/reports/available-courses', AAvailableCoursesComponent::class)->name('a.avail-course');
            Route::get('admin/reports/available-courses/{selected_batch}', [AGenerateAvailableCoursesComponent::class, 'generatePdf'])->name('a.avail-course-generate-pdf');
            Route::get('admin/reports/instructor-history-report', [GenerateInstructorHistoryComponent::class, 'generatePdf'])->name('a.reportinstructorhistory');
            Route::get('admin/reports/{training_id}', ViewBatchComponent::class)->name('a.view-batch');



            //attendance
            Route::get('admin/reports/batch-report/attendance/{scheduleid}', [AGenerateAttendanceComponent::class, 'generatePdf'])->name('a.viewattendance');

            //certificates
            Route::get('admin/reports/batch-report/certificates/{scheduleid}', [AGenerateCertificateComponent::class, 'viewPdf'])->name('a.certificates');
            Route::get('admin/certificate-maintenance/previe-pdos/{scheduleid}', [AGeneratePDOSComponent::class, 'viewPdf'])->name('a.pdoscertificates');
            Route::get('admin/view-certificates/t', [AGenerateCertificateComponent::class, 'viewSoloPdf'])->name('a.solocertificates');

            //ccr
            Route::get('admin/reports/batch-report/ccr/{scheduleid}', [AGenerateCcrComponent::class, 'viewPdf'])->name('a.ccr');

            //ER
            Route::get('admin/reports/batch-report/er/{scheduleid}', [AGenerateEnrollmentReportComponent::class, 'viewPdf'])->name('a.er');

            // TPER
            Route::get('admin/tper/{training_id}' , [AGenerateTperComponent::class , 'generateAllTper'])->name('a.tper')->middleware(['authadmin']);

            //certificates maintenance
            Route::get('admin/certificate-maintenance}', ACertificateMaintenanceComponent::class)->name('a.certmain');
            Route::get('admin/certificate-maintenance/{course_id}', ACertificateShowMaintenanceComponent::class)->name('a.certificatesmainshow');
            Route::get('admin/certificate-maintenance/preview/{course_id}', [AGenerateCertificateComponent::class, 'previewPdf'])->name('a.mcertificates');
            Route::get('admin/certificate-maintenance/previe-pdos/{course_id}', [AGeneratePDOSComponent::class, 'previewPdf'])->name('a.mpdoscertificates');

            //certificate history
            Route::get('admin/certificate-history', ACertificateHistoryComponent::class)->name('a.cert-history');
            Route::get('admin/certificate-history/view', ACertificateHistoryDetailsComponent::class)->name('a.cert-history-details');


            //Billing Monitoring
            Route::get('admin/company', CompanyComponents::class)->name('a.company');
            Route::get('admin/addratecompanycourse', AddRatePerCourseComponent::class)->name('a.companyratepercourse');
            Route::get('admin/billing', ABillingMonitoringComponent::class)->name('a.billing-monitoring');
            Route::get('admin/billingCompanies', ABillingViewComponent::class)->name('a.billing-view');
            Route::get('admin/billingViewtrainees', ABillingViewTraineesComponent::class)->name('a.billing-viewtrainees');
            Route::get('admin/billingPricematrix', APriceMatrixComponent::class)->name('a.billing-pricematrix');
            Route::get('admin/billingCoursesPriceMatrix', APriceMatrixCoursesSettingsComponents::class)->name('a.course-pricematrix');
            Route::get('admin/billingStatement', [GenerateBillingStatement::class, 'generatePdf'])->name('a.billing-statement');
            Route::get('admin/billingDrop', ABillingDropComponent::class)->name('a.billing-drop');
            Route::get('admin/ClientInfo', UpdateInfoComponent::class)->name('a.client-info');
            Route::get('admin/BankInfo', UpdateBankComponent::class)->name('a.bank-info');

            Route::get('admin/bankAccountManagement', BankAccountManagementComponent::class)->name('a.bank-management');
            
            //Notification History
            Route::get('notification/notification-history', NotificationHistory::class)->name('a.notification-history');
            
            //ATD/SLAF
            Route::get('admin/reports/preview-atd/{scheduleid}', [AGenerateSDComponent::class, 'viewPdf'])->name('a.atd-slaf');


            //TCROA
            Route::get('admin/reports/tcroa/0', [ADisplayTcroaComponent::class,'export'])->name('a.tcroa');
            Route::get('admin/reports/e-grades/0', [AExcelGradeComponent::class,'export'])->name('a.grades');

            

            //Remedial
            Route::get('admin/remedial', ARemedialComponent::class)->name('a.remedial');
            Route::get('admin/remedial/attendance/{enrol_id}', [AViewRAttendanceComponent::class, 'generatePdf'])->name('a.r.attendance');
            Route::get('admin/remedial/ccr/{enrol_id}', [AGenerateCcrComponent::class, 'viewSoloPdf'])->name('a.solo-ccr-pdf');
            Route::get('admin/remedial/tcroa/{enrol_id}', [ADisplayTcroaComponent::class,'remedial_export'])->name('a.tcroa-solo');
            Route::get('admin/remedial/certificate/{enrol_id}', [AGenerateCertificateComponent::class,'remedial_certificate'])->name('a.cert-solo');


        });
    });

    Route::middleware(['authcompany'])->group(function () {
        Route::middleware(['verifyOTPForLogin'])->group(function () {
            Route::get('company/dashboard', CDashboardCompanyComponent::class)->name('c.dashboard');
            Route::get('company/training-calendar/{course_id}', CCalendarShowComponent::class)->name('c.calendarshow');
            Route::get('company/schedule/{schedule_id}', CCalendarDetailsComponent::class)->name('c.calendardetails');
            Route::get('company/client-billing-monitoring', CClientBillingStatementMonitoring::class)->name('c.client-billing-monitoring');
            Route::get('company/client-billing-view-schedule', CClientBillingViewSchedule::class)->name('c.client-billing-view-schedule');
            Route::get('company/client-billing-view-trainees', CClientBillingViewTrainees::class)->name('c.client-billing-view-trainees');
            Route::get('company/billingStatement', [GenerateBillingStatement::class, 'generatePdf'])->name('c.client-billing-statement');
            Route::get('company/edit/{companyid}', EditCompanyProfile::class)->name('c.edit-company');
            Route::get('company/confirm-enroll/', CConfirmEnrollmentComponent::class)->name('c.confirm-enroll');
            Route::get('company/view-trainees/', CViewTraineesComponent::class)->name('c.view-trainees');
            Route::get('company/pde/pde-request', RequestPde::class)->name('c.requestpde');
            Route::get('company/pde/pde-status', PdeStatus::class)->name('c.pdestatus');


        });
    });


    Route::middleware(['authinstructor'])->group(function () {
        Route::middleware(['verifyOTPForLogin'])->group(function () {
            Route::get('instructor/dashboard', IDashboardComponent::class)->name('i.dashboard');
            Route::get('instructor/course-schedule/attendance/{scheduleid}', [AGenerateAttendanceComponent::class, 'generatePdf'])->name('i.viewattendance');
            Route::get('instructor/course-schedule/{training_id}', ViewBatchComponent::class)->name('i.view-batch');
            Route::get('instructor/reports/tcroa/0', [ADisplayTcroaComponent::class,'export'])->name('i.tcroa');
            Route::get('instructor/training-reports', [IGenerateTrainingReport::class, 'generatePDF'])->name('i.training-report');
            Route::get('instructor/edit-instructor/{hashid}', EditInstructorComponent::class)->name('i.edit-instructor');
            Route::get('instructor/tper-form' , ITperComponent::class)->name('i.t_per');
            Route::get('instructor/tper-second-form' , ITperSecondPageComponent::class)->name('i.t_per_2');
            // generate TPER
            Route::get('admin/tper/{training_id}' , [AGenerateTperComponent::class , 'generateAllTper'])->name('a.tper');

        });
    });

    Route::middleware(['authtech'])->group(function () {
        Route::middleware(['verifyOTPForLogin'])->group(function () {
            Route::get('technical/dashboard', TechDashboardComponent::class)->name('te.dashboard');
        });
    });

});


Route::middleware(['authtrainee'])->group(function () {
    Route::middleware(['verifyOTPForLogin'])->group(function () {
        Route::get('/trainee/dashboard', TDashboardComponent::class)->name('t.dashboard');
        Route::get('/trainee/enroll', TEnrollCards::class)->name('t.enroll-cards');
        Route::get('/trainee/enroll/{course_id}', TEnrollComponent::class)->name('t.enroll');
        Route::get('/processing-enrol', TProcessingEnrollComponent::class)->name('t.processing');
        Route::get('/view-atd/{registration}', [TGenerateEnrolAtdComponent::class, 'viewPdf'])->name('view-atd');
        Route::get('/view-sd/{registration}', [TGenerateSalaryDeducComponent::class, 'viewPdf'])->name('view-sd');
        Route::get('/trainee/my-courses', TCoursesComponent::class)->name('t.courses');
        Route::get('/trainee/my-courses/course-details/{regis}', TCourseDetailsComponent::class)->name('t.coursedetails');
        Route::get('/trainee/enrollment/generate/admission-slip/{enrol_id}', [AGenerateAdmissionSlip::class, 'generatePdf'])->name('t.viewadmission');
        Route::get('/edit-profile', TEditProfileComponent::class)->name('t.editprofile');
        Route::get('/edit-security', TEditSecurityComponent::class)->name('t.editsecurity');
        Route::get('/trainee-handout' , THandoutComponent::class)->name('t.handout');
        Route::get('/lms/home' , TLmsComponent::class)->name('t.lms-home');
        Route::get('/lms/courseInfo' , TLmsCourseInfoComponent::class)->name('t.lms-courseinfo');
        Route::get('/lms/syllabus' , TLmsSyllabusComponent::class)->name('t.lms-syllabus');
        Route::get('/lms/people' , TLmsPeopleComponent::class)->name('t.lms-people');
        // Route::get('/certificates', TCertificateComponent::class)->name('t.certificates');
        // Route::get('/certificates/history/', TCertificateDetailsComponent::class)->name('t.cert-history-details');
        // Route::get('/certificates/t/', [AGenerateCertificateComponent::class, 'viewSoloPdf'])->name('t.solocertificates');

        Route::prefix('messenger')->as('messenger.')->group(function () {
                Route::get('index', IndexConversationComponent::class)->name('index');
        });
    });
});


//CRONJOBS
Route::get('/cronJob/SendEnrollmentConfirmationNotification' , [SendEnrollmentConfirmationNotificationComponent::class , 'send'])->name('c.send-enrollment-confirmation');

//Record trainee confirmation
Route::get('/recordConfirmation/{enroledid}/{attendingid}' , RecordTraineeConfirmationComponent::class)->name('r.record-trainee-confirmation');

Route::get('/mobile/{registration}', [TGenerateEnrolAtdComponent::class, 'viewPdf'])->name('m.view-atd');
Route::get('/mobile/salary/{registration}', [TGenerateSalaryDeducComponent::class, 'viewPdf'])->name('m.view-sd');

Route::get('/email/sendZoom' , [SendZoomCredentials::class , 'build'])->name('e.send_zoom');



// IMPORT QUERIES
Route::get('/ImportQueries/HashTraineePassword' , [HashTraineePasswordComponent::class , 'hashPassword']);//hash Trainee Password and Hash_id
Route::get('/ImportQueries/HashAdminPassword' , [HashTraineePasswordComponent::class , 'hashAdminPassword']);//hash Admin password and hash_id
Route::get('/certificates/qr/{hash_id}', AGenerateUrlCertificateComponent::class)->name('qr.code');
