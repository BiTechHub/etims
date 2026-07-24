<?php

use App\Http\Controllers\Admin\AddUserController;
use App\Http\Controllers\Admin\HostelController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\master\FeedbackController;
use App\Http\Controllers\Admin\NominationController;
use App\Http\Controllers\Admin\ProgrammeManageController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\TrainingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Agency\AgencyAuthController;
use App\Http\Controllers\API\LocationApiController;
use App\Http\Controllers\BugetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\Faculty\FacultyExamController;
use App\Http\Controllers\Faculty\FacultySessionCon;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\Master\AgencyController;
use App\Http\Controllers\Master\AgencygroupController;
use App\Http\Controllers\Master\AgencyTypeController;
use App\Http\Controllers\Master\ClassController;
use App\Http\Controllers\Master\DepartmentController;
use App\Http\Controllers\Master\DesignationController;
use App\Http\Controllers\Master\FaculityController;
use App\Http\Controllers\Master\GropController;
use App\Http\Controllers\Master\ItemsController;
use App\Http\Controllers\Master\ProgrammeController;
use App\Http\Controllers\Master\SessionController;
use App\Http\Controllers\Master\SponsorController;
use App\Http\Controllers\ParticiapntController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StaticsDashboardController;
use App\Http\Controllers\TranslationController;
use App\Models\Agency;
use App\Models\AgencyType;
use App\Models\Faculity;
use App\Models\Nomination;
use App\Models\Participant;
use App\Models\Question;
// ----------------------- Faculty Session Controller -------------------------
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::middleware(['clean.input'])->group(function () {

    Route::get('/clear-cache', function () {
        Artisan::call('optimize:clear');

        return 'All caches cleared (route, config, view, app).';
    });

    Route::get('/create-migration', function () {
        Artisan::call('make:model Model_name -m');

        return 'Model and migration created successfully !';
    });

    Route::get('/', function () {
        if (Auth::guard('agency')->check()) {
            return redirect()->route('dashboard');
        }

        return view('welcome');
    })->name('welcome');

    // Agency Registration OutSide of Website
    Route::get('agency-registration', [AgencyController::class, 'agency_registration'])->name('agency-registration');
    Route::post('create-agency', [AgencyController::class, 'create_agency'])->name('create-agency');

    Route::post('/ccavenue/initiate', [PaymentController::class, 'initiate'])->name('ccavenue.initiate');
    Route::post('/ccavenue/callback', [PaymentController::class, 'callback'])->name('ccavenue.callback');
    Route::post('/ccavenue/cancel', [PaymentController::class, 'cancel'])->name('ccavenue.cancel');

    // ============================================= Statics Dashboard ============================================
    Route::get('/statics-dashboard', [StaticsDashboardController::class, 'index'])->name('statics-dashboard');

    // ================================= Hostel login ====================================
    // Route::get('hostel/login', [AdminAuthController::class, 'showLogin'])
    //     ->name('hostel.login');
    Route::prefix('admin')->group(function () {

        // Route::get('/login/form', [AdminAuthController::class, 'showLogin'])->name('admin.login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
        Route::get('/encrypt_token', [AdminAuthController::class, 'encrypt_token'])->name('encrypt_token');

        Route::middleware(['admin.auth', 'prevent'])->group(function () {
            Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
            Route::get('/admin/static-dashboard', [AdminController::class, 'staticDashboard'])
                ->name('admin.static.dashboard');
            // Main Dashboard
            Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
            Route::get('get-admin-dashboard-info', [AdminController::class, 'getAdminDashboardInfo'])->name('admin.getAdminDashboardInfo');

            Route::get('/agency/group', [AgencygroupController::class, 'index'])->name('admin.agencygroup');
            Route::get('/agency/group/get-data', [AgencygroupController::class, 'getData'])->name('agency.group.getData');

            Route::post('/agencygroup/store', [AgencygroupController::class, 'store'])->name('agency.group.store');

            Route::delete('/agency/group/{id}', [AgencygroupController::class, 'destroy'])->name('agency.group.destroy');
            Route::post('/agency-group/toggle-status/{id}', [AgencygroupController::class, 'toggleStatus'])->name('agency.group.toggle-status');
            // routes/web.php
            Route::post('/agency-group/edit/{id}', [AgencygroupController::class, 'update'])->name('agency.group.update');
            Route::post('/agency-group/delete/{id}', [AgencygroupController::class, 'destroy'])->name('agency.group.delete');

            // all the route related to agencytype

            Route::get('/agency/type/create', [AgencyTypeController::class, 'index'])->name('agency.type.create');
            Route::post('/agency/type/store', [AgencyTypeController::class, 'store'])->name('agency.type.store');
            Route::get('/agency/type/get-data', [AgencyTypeController::class, 'getData'])->name('agency.type.getData');
            Route::post('/agency-type/delete/{id}', [AgencyTypeController::class, 'destroy'])->name('agency.type.delete');
            Route::post('/agency-type/edit/{id}', [AgencyTypeController::class, 'update'])->name('agency.type.update');
            Route::post('/agency-type/toggle-status/{id}', [AgencyTypeController::class, 'toggleStatus'])->name('agency.type.toggle-status');

            // all the route relate to agency
            Route::get('agency/create', [AgencyController::class, 'index'])->name('agency.create');
            Route::post('/agency/store', [AgencyController::class, 'store'])->name('agency.store');
            Route::get('/agency/get-data', [AgencyController::class, 'getData'])->name('agency.getData');
            Route::post('/agency/delete/{id}', [AgencyController::class, 'destroy'])->name('agency.delete');
            Route::post('/agency/edit/{id}', [AgencyController::class, 'update'])->name('agency.update');
            Route::post('/agency/toggle-status/{id}', [AgencyController::class, 'toggleStatus'])->name('agency.toggle-status');

            Route::post('/agency/toggle-status/{id}', [AgencyController::class, 'toggleStatus'])->name('agency.toggle-status');

            // route related to session
            Route::get('/sessions', [SessionController::class, 'index'])->name('session.index');
            Route::get('/session/get-data', [SessionController::class, 'getData'])->name('session.getData');

            Route::post('/sessions', [SessionController::class, 'store'])->name('session.store');

            Route::post('sessions/edit/{id}', [SessionController::class, 'update'])->name('session.update');
            Route::post('/sessions/delete/{id}', [SessionController::class, 'destroy'])->name('session.destroy');

            // ROUTE RELATED TO CLASS ROOM
            Route::get('/class/room', [ClassController::class, 'index'])->name('index.class');
            Route::get('/class/get-data', [ClassController::class, 'getData'])->name('class.getData');

            Route::post('/class/store', [ClassController::class, 'store'])->name('class.store');

            Route::post('/class/delete/{id}', [ClassController::class, 'destroy'])->name('class.destroy');
            Route::post('/class/toggle-status/{id}', [ClassController::class, 'toggleStatus'])->name('class.toggle-status');
            // routes/web.php
            Route::post('/class/edit/{id}', [ClassController::class, 'update'])->name('class.update');

            // route related to department
            Route::get('/department/group', [DepartmentController::class, 'index'])->name('admin.department');
            Route::get('/department/get-data', [DepartmentController::class, 'getData'])->name('department.getData');

            Route::post('/department/store', [DepartmentController::class, 'store'])->name('department.store');

            Route::delete('/department/{id}', [DepartmentController::class, 'destroy'])->name('department.destroy');
            Route::post('department/toggle-status/{id}', [DepartmentController::class, 'toggleStatus'])->name('department.toggle-status');
            // routes/web.php
            Route::post('department/edit/{id}', [DepartmentController::class, 'update'])->name('department.update');
            Route::post('department/delete/{id}', [DepartmentController::class, 'destroy'])->name('department.delete');
            // all route related to designation
            Route::get('/designation/group', [DesignationController::class, 'index'])->name('admin.designation');
            Route::get('/designation/get-data', [DesignationController::class, 'getData'])->name('designation.getData');

            Route::post('/designation/store', [DesignationController::class, 'store'])->name('designation.store');

            Route::delete('/ddesignation/{id}', [DesignationController::class, 'destroy'])->name('designation.destroy');
            Route::post('designation/toggle-status/{id}', [DesignationController::class, 'toggleStatus'])->name('designation.toggle-status');

            Route::post('designation/edit/{id}', [DesignationController::class, 'update'])->name('designation.update');
            Route::post('designation/delete/{id}', [DesignationController::class, 'destroy'])->name('designation.delete');

            // route related to group
            Route::get('/group/group', [GropController::class, 'index'])->name('admin.group');
            Route::get('/group/get-data', [GropController::class, 'getData'])->name('group.getData');

            Route::post('/group/store', [GropController::class, 'store'])->name('group.store');

            Route::delete('/dgroup/{id}', [GropController::class, 'destroy'])->name('group.destroy');
            Route::post('group/toggle-status/{id}', [GropController::class, 'toggleStatus'])->name('group.toggle-status');

            Route::post('group/edit/{id}', [GropController::class, 'update'])->name('group.update');
            Route::post('group/delete/{id}', [GropController::class, 'destroy'])->name('group.delete');

            // route related to item head

            Route::get('/item/group', [ItemsController::class, 'index'])->name('admin.item');
            Route::get('/item/get-data', [ItemsController::class, 'getData'])->name('item.getData');

            Route::post('/item/store', [ItemsController::class, 'store'])->name('item.store');

            Route::delete('/item/{id}', [ItemsController::class, 'destroy'])->name('item.destroy');
            Route::post('item/toggle-status/{id}', [ItemsController::class, 'toggleStatus'])->name('item.toggle-status');

            Route::post('item/edit/{id}', [ItemsController::class, 'update'])->name('item.update');
            Route::post('item/delete/{id}', [ItemsController::class, 'destroy'])->name('item.delete');

            // route related to programm

            Route::get('/programme/create', [ProgrammeController::class, 'create'])->name('admin.programme');
            Route::get('/programme/get-data', [ProgrammeController::class, 'getData'])->name('programme.getData');

            Route::post('/programme/store', [ProgrammeController::class, 'store'])->name('programme.store');

            Route::delete('/programme/{id}', [ProgrammeController::class, 'destroy'])->name('programme.destroy');
            Route::post('programme/toggle-status/{id}', [ProgrammeController::class, 'toggleStatus'])->name('programme.toggle-status');

            Route::post('/programme/edit/{id}', [ProgrammeController::class, 'update'])->name('programme.update');

            Route::post('/programme/delete/{id}', [ProgrammeController::class, 'destroy'])->name('programme.delete');

            // sponor type
            Route::get('/sponsor/create', [SponsorController::class, 'index'])->name('admin.sponsor');
            Route::post('/sponsor/store', [SponsorController::class, 'store'])->name('sponsor.store');
            Route::get('/sponsor/get-data', [SponsorController::class, 'getData'])->name('sponsor.getData');
            Route::post('/sponsor/delete/{id}', [SponsorController::class, 'destroy'])->name('sponsor.destroy');
            Route::post('/sponsor/edit/{id}', [SponsorController::class, 'update'])->name('sponsor.update');
            Route::post('/sponsor/toggle-status/{id}', [SponsorController::class, 'toggleStatus'])->name('sponsor.toggle-status');

            // List + DataTable (faculty  controller)

            Route::get('/faculty/programme-management-list', [FacultySessionCon::class, 'programmeManagementList'])->name('ff.programmeManagement-list');
            Route::get('/faculty/programme-management/data', [FacultySessionCon::class, 'programmeManagementData'])->name('ff.programmeManagement.getData');

            // Actions — admin ke hi controller methods reuse
            Route::get('/faculty/programme-management/edit/{id}', [ProgrammeManageController::class, 'pro_management_edit'])->name('ff.programmeManagement.edit');
            Route::post('/faculty/programme-management/update/{id}', [ProgrammeManageController::class, 'pro_management_update'])->name('ff.programmeManagement.update');
            Route::post('/faculty/programme-management/delete/{id}', [ProgrammeManageController::class, 'destroy'])->name('ff.programmeManagement.delete');

            Route::get('/faculty/session-report/{id}', [ProgrammeManageController::class, 'session_report'])->name('ff.session_report');
            Route::get('/faculty/poster/{id}', [ProgrammeManageController::class, 'poster'])->name('ff.poster');

            Route::get('/faculty/announcement/generate/{id}', [ProgrammeManageController::class, 'generate'])->name('ff.announcement.generate');
            Route::post('/faculty/announcement/store/{id}', [ProgrammeManageController::class, 'store'])->name('ff.announcement.store');

            Route::get('/faculty/announcement/show/{id}', [ProgrammeManageController::class, 'showAnnouncement'])->name('ff.announcement.show');
            Route::post('/faculty/announcement/edit-store/{id}', [ProgrammeManageController::class, 'store_edit_annoucement'])->name('ff.announcement.edit.store');

            Route::get('/faculty/programme/approve/{id}', [ProgrammeManageController::class, 'approve'])->name('ff.programme.approve');
            Route::post('/faculty/programme/toggle-status/{id}', [ProgrammeManageController::class, 'toggleStatus'])->name('ff.programme.toggle-status');

            // faculity
            Route::get('/faculity/create', [FaculityController::class, 'index'])->name('admin.faculity');
            Route::post('/faculity/store', [FaculityController::class, 'store'])->name('faculity.store');
            Route::get('/faculity/get-data', [FaculityController::class, 'getData'])->name('faculity.getData');
            Route::post('/faculity/delete/{id}', [FaculityController::class, 'destroy'])->name('faculity.destroy');
            Route::post('/faculity/edit/{id}', [FaculityController::class, 'update'])->name('faculity.update');
            Route::post('/faculity/toggle-status/{id}', [FaculityController::class, 'toggleStatus'])->name('faculity.toggle-status');

            // adduser route to AddUserController
            Route::get('/user/adduser/permissions/{id}/view', [AddUserController::class, 'showPermissionsPage'])->name('user.adduser.permissions.page');
            Route::get('/user/adduser/permissions/{id}', [AddUserController::class, 'getPermissions'])->name('user.adduser.permissions');
            Route::post('/user/adduser/permissions/{id}', [AddUserController::class, 'updatePermissions'])->name('user.adduser.permissions.update');
            Route::get('/user/adduser', [AddUserController::class, 'index'])->name('user.adduser');
            Route::post('/user/adduser/store', [AddUserController::class, 'store'])->name('user.adduser.store');
            Route::get('/user/adduser/get-data', [AddUserController::class, 'getData'])->name('user.adduser.getData');
            Route::post('/user/adduser/edit/{id}', [AddUserController::class, 'update'])->name('user.adduser.update');
            Route::post('/user/adduser/delete/{id}', [AddUserController::class, 'destroy'])->name('user.adduser.delete');

            // route related to UserController
            Route::get('/user/adduser', [AddUserController::class, 'index'])->name('user.adduser');
            Route::get('/user/index', [UserController::class, 'index'])->name('user.index');
            Route::post('/user/store', [UserController::class, 'store'])->name('user.store');
            Route::get('/user/get-data', [UserController::class, 'getData'])->name('user.getData');
            Route::post('/user/toggle-status/{id}', [UserController::class, 'toggleStatus'])->name('user.toggle-status');
            Route::post('/user/delete/{id}', [UserController::class, 'destroy'])->name('user.delete');
            Route::post('/user/edit/{id}', [UserController::class, 'update'])->name('user.update');

            // training program
            Route::resource('training', TrainingController::class)
                ->names('admin.training');

            // route related to programmesub menu
            // programme

            Route::get('/programme-calender/index', [ProgrammeManageController::class, 'programmeCalender'])->name('programmeCalender.index');
            Route::post('/programme-calender/store', [ProgrammeManageController::class, 'procalstore'])->name('programmeCalender.store');
            Route::get('/programme-calender/get-data', [ProgrammeManageController::class, 'getData'])->name('cal.getData');
            Route::post('/programme-calender/delete/{id}', [ProgrammeManageController::class, 'destroy'])->name('cal.delete');

            Route::post('/programme/toggle-status/{id}', [ProgrammeManageController::class, 'toggleStatus'])->name('programme.toggle-status');
            // route related to the programme management
            Route::get('/programme-management/list', [ProgrammeManageController::class, 'list'])->name('programmeManagement.list');
            Route::get('/programme-management/pro_management_index', [ProgrammeManageController::class, 'pro_management_index'])->name('programmeManagement.index');
            Route::post('/programme-management/pro_management_store', [ProgrammeManageController::class, 'pro_management_store'])->name('programmeManagement.store');
            Route::post('/programme-management/update/{id}', [ProgrammeManageController::class, 'pro_management_update'])->name('programmeManagement.update');

            Route::post('/programme-management/delete/{id}', [ProgrammeManageController::class, 'destroy'])->name('programmeManagement.delete');
            Route::get('/programme/view/{id}', [ProgrammeManageController::class, 'view'])->name('programme.view');
            Route::get('/paq/index', [ProgrammeManageController::class, 'paqindex'])->name('admin.paq');
            Route::get('/programme-management/get-data', [ProgrammeManageController::class, 'getData'])->name('programmeManagement.getData')->withoutMiddleware('admin.auth');
            Route::get('/programme-management/edit/{id}', [ProgrammeManageController::class, 'pro_management_edit'])->name('programmeManagement.edit');
            Route::post('/paq/store', [ProgrammeManageController::class, 'storepaq'])->name('paq.store');
            Route::get('/programme-management/get-data/active', [DashboardController::class, 'getData'])->name('programmeManagement.getData.active');
            // routes/web.php

            Route::get('/faculty', [DashboardController::class, 'view_faculty'])->name('view_faculty');
            Route::get('/prog-list', [DashboardController::class, 'view_prog_list'])->name('view_prog_list');

            Route::delete('/agency/group/{id}', [AgencygroupController::class, 'destroy'])->name('agency.group.destroy');
            Route::post('/paq/toggle-status/{id}', [ProgrammeManageController::class, 'paqtoggleStatus'])->name('paq.toggle-status');
            // routes/web.php
            Route::post('/paq/edit/{id}', [ProgrammeManageController::class, 'paqupdate'])->name('paq.update');
            Route::post('/paq/delete/{id}', [ProgrammeManageController::class, 'paqdestroy'])->name('paq.delete');
            Route::get('/programme/announcement/{id}', [ProgrammeManageController::class, 'showAnnouncement'])->name('admin.programme.announcement');

            // Save Announcement Content
            Route::post('/programme/save-announcement/{id}', [ProgrammeManageController::class, 'saveAnnouncement'])->name('admin.programme.save-announcement');

            // route for the session

            Route::get('/programme/faculity/session', [ProgrammeManageController::class, 'facultySession'])->name('faculity.session');
            Route::post('/programme/faculty/session/store', [ProgrammeManageController::class, 'storeSession'])->name('faculty.session.store');
            Route::get('/programme-management/get-data/subtopic', [ProgrammeManageController::class, 'getDataSubtopic'])->name('programmeManagement.getData.subtopic');

            Route::post('/programme-sessions/update/{programmeId?}', [ProgrammeManageController::class, 'updateSession'])->name('programme.sessions.update');
            Route::post('/programme-sessions/delete/{id?}', [ProgrammeManageController::class, 'destroySubtopic'])->name('programme.session.delete');

            Route::get('programme-management/get-data/subtopic/filter', [ProgrammeManageController::class, 'getDataSubtopicfilter'])->name('programmeManagement.getData.subtopic.fliter');

            Route::get('/get-subtopic-dates', [ProgrammeManageController::class, 'getSubtopicDates'])->name('programmeManagement.getSubtopicDates');
            Route::get('/get-programme-dates', [ProgrammeManageController::class, 'getProgrammeDates'])->name('get.programme.dates');
            // routes/web.php
            Route::get('/subtopics/by-programme', [ProgrammeManageController::class, 'getSubtopicsByProgramme'])->name('get.subtopics.by.programme');
            Route::get('/programme/subtopics', [ProgrammeManageController::class, 'getProgrammeSubtopics'])->name('get.programme.subtopics');

            // -----------------------------------Day to day session----------------------------------------------
            Route::get('/day-to-day-session', [ProgrammeManageController::class, 'DayToDaySession'])->name('day-to-day-session');

            Route::get('view-day-to-day-session', [ProgrammeManageController::class, 'viewDayToDaySession'])->name('view-day-to-day-session');

            Route::get('/session-report/{id}', [ProgrammeManageController::class, 'session_report'])->name('programme.session.report');
            Route::get('/announcement/generate/{id}', [ProgrammeManageController::class, 'generate'])->name('announcement.generate');
            Route::post('/announcement/store/{id}', [ProgrammeManageController::class, 'store'])->name('announcement.store');
            Route::post('/programme/{id}/update-invitation', [ProgrammeManageController::class, 'updateInvitation'])->name('programme.updateInvitation');
            Route::post('/announcement/edit/store/{id}', [ProgrammeManageController::class, 'store_edit_annoucement'])->name('announcement.edit.store');
            Route::get('/programme/approve/{id}', [ProgrammeManageController::class, 'approve'])->name('programme.approve');

            // route related to login
            Route::get('/forget-password', [LoginController::class, 'index'])->name('admin.forget');
            Route::post('/change-password', [LoginController::class, 'changePassword'])->name('admin.change-password');

            // route related to the guest
            Route::get('/guest/index', [GuestController::class, 'index'])->name('admin.guest');
            Route::post('/guest/store', [GuestController::class, 'store'])->name('guest.store');
            Route::get('/guest/get-data', [GuestController::class, 'getData'])->name('guest.getData');
            Route::post('/guest/edit/{id}', [GuestController::class, 'update'])->name('guest.update');
            Route::post('/guest/delete/{id}', [GuestController::class, 'destroy'])->name('guest.delete');

            // route related to the nomination menu
            Route::get('/nomination/list', [NominationController::class, 'list'])->name('nomination.list');
            Route::get('/nomination/manage', [NominationController::class, 'manage_nomination'])->name('nomination.add');
            Route::get('/get-programmes-by-year', [NominationController::class, 'getProgrammesByYear'])->name('admin.getProgrammesByYear');
            Route::get('/get-agencies-by-type', [NominationController::class, 'getAgenciesByType'])->name('admin.getAgenciesByType');
            Route::post('/nomination/store', [NominationController::class, 'store'])->name('nomination.store');
            Route::get('/nomination/get-data', [NominationController::class, 'getNominationsData'])->name('nomination.getData');
            Route::get('/nomination/edit/{id}', [NominationController::class, 'edit'])->name('nomination.edit.form');
            Route::post('/nominations/update/{nomination}', [NominationController::class, 'update'])->name('nominations.update');
            Route::post('/nominations/delete/{id}', [NominationController::class, 'destroy'])->name('nominations.delete');
            Route::get('nominations/search', [NominationController::class, 'view_nomination'])
                ->name('nominations.search');
            // web.php
            Route::get('/nominations/data', [NominationController::class, 'getData'])
                ->name('admin.nominations.getData');

            // Temporary test route
            Route::get(
                '/nominations/by-programme',
                [NominationController::class, 'getByProgramme']
            )->name('admin.nominations.byProgramme');

            Route::get('/get/participant/by/programme', [ReportController::class, 'getByProgramme'])->name('get.participant.report');
            Route::get(
                '/nominations/by-programme/confirm',
                [NominationController::class, 'getByProgrammeconfirm']
            )->name('admin.nominations.byProgramme.confirm');

            Route::get('/current/participant/prog/{id}', [NominationController::class, 'part_currenty_prog'])->name('participant.current.prog');
            Route::get('/last/participant/prog/{id}', [NominationController::class, 'part_last_prog'])->name('participant.last.prog');
            Route::get('/detail/participant/prog/{id}', [NominationController::class, 'participants_details'])->name('participant.detail.prog');

            Route::post('/nominations/update-status', [NominationController::class, 'updateStatus'])->name('admin.nominations.updateStatus');

            Route::post('/nominations/update-attendence', [NominationController::class, 'updateAttendence'])->name('admin.nominations.updateAttendance');

            Route::get('hostel/attendence', [HostelController::class, 'view_attendence'])
                ->name('hostel.attendence');

            Route::post('/hostel/update-attendence', [HostelController::class, 'updateAttendence'])->name('admin.hostel.updateAttendence');

            Route::post('participants/update-datetime', [HostelController::class, 'updateDatetime'])
                ->name('admin.participants.updateDatetime');

            Route::get('get-check-in-participants', [HostelController::class, 'getCheckInParticipants'])->name('admin.getCheckInParticipants');

            // Exam
            Route::get('/addQuestion', [ExamController::class, 'addQuestion'])->name('admin.addQuestion');
            Route::get('/Question_get_data', [ExamController::class, 'Question_get_data'])->name('admin.Question_get_data');
            Route::post('/questionAdd', [ExamController::class, 'questionAdd'])->name('admin.questionAdd');

            // Route related to the hostel
            Route::get('room/management', [HostelController::class, 'room'])->name('room');

            Route::get('room/list', [HostelController::class, 'room_list'])->name('room.list');
            Route::post('room/store', [HostelController::class, 'store_room'])->name('hostel.room.store');

            // Route::post('/rooms/store', [HostelController::class, 'store_room'])->name('rooms.store');

            Route::get('/room/get-data', [HostelController::class, 'getData'])->name('room.getData');
            Route::post('/room/delete/{id}', [HostelController::class, 'destroy'])->name('room.delete');

            Route::get('/room/edit/{id}', [HostelController::class, 'room_edit'])->name('room.edit');

            Route::post('room/update', [HostelController::class, 'update'])->name('room.update');

            Route::get('/manage/room', [HostelController::class, 'view_beds'])->name('beds.management');

            Route::get('/hostel/block', [HostelController::class, 'block'])->name('rooms.block');

            Route::post('/block/store', [HostelController::class, 'store_block'])->name('block.store');
            Route::get('/hostel/getBlocks', [HostelController::class, 'getBlocks']);

            Route::post('beds/store', [HostelController::class, 'store_bed'])->name('beds.store');

            Route::get('/hostel/getParticipants/{programme_id}', [HostelController::class, 'getParticipants']);
            Route::get('/hostel/getAvailableUnits', [HostelController::class, 'getAvailableUnits'])->name('admin.hostel.available-units');
            Route::get('/room/type', [HostelController::class, 'typeOfRooms'])->name('rooms.type');
            Route::post('/type/store', [HostelController::class, 'store_type'])->name('type.store');
            Route::get('/hostel/getBeds/{room_id}', [HostelController::class, 'getBeds'])->name('hostel.getBeds');

            Route::post('hostel/allot', [HostelController::class, 'store_participant_room']);

            Route::get('hostel/get-allot-rooms/', [HostelController::class, 'getAllotRooms'])->name('admin.get-allot-rooms');
            Route::post('hostel/allot-room-save', [HostelController::class, 'saveAllotRooms'])->name('admin.save-allot-rooms');

            Route::get('/api/bed-types', [HostelController::class, 'getAllBedTypes']);
            Route::get('/hostel/getRoomTypesByBlock', [HostelController::class, 'getRoomTypesByBlock']);

            Route::post('/rooms/update-room-number-ajax', [HostelController::class, 'updateRoomNumberAjax'])->name('rooms.update_room_number_ajax');
            Route::get('/room/allotment/assign', [HostelController::class, 'showAllotmentForm'])->name('room.assign');
            Route::get('/room/allotment', [HostelController::class, 'showRoomNumberAllotment'])->name('rooms.assignNumber');

            Route::get('/block/get-data', [HostelController::class, 'getDataBlock'])->name('block.getData');
            Route::get('/type/get-data', [HostelController::class, 'getDataType'])->name('type.getData');

            Route::post('/block/delete/{id}', [HostelController::class, 'destroyBlock'])->name('block.delete');

            Route::post('/room/toggle-status/{id}', [HostelController::class, 'toggleStatus'])->name('room.toggle-status');

            Route::get('/budget/expenditure', [BugetController::class, 'create'])->name('budget.expenditure.view');

            Route::post('/menus/store', [BugetController::class, 'store_expenditure'])->name('menus.store');

            // route related to the budget
            Route::delete('/budgets/{id}', [BugetController::class, 'destroy'])->name('admin.budgets.destroy');

            Route::get('/menu-selection-multiple', [BugetController::class, 'selectMultiple'])->name('menus.selectMultiple');
            Route::get('/menus/{id}/submenus', [BugetController::class, 'getSubmenusWithMenuName']);
            Route::post('/budgets/store', [BugetController::class, 'storeBudget'])->name('admin.budgets.store');
            Route::get('/programmes/{id}/details', [BugetController::class, 'getProgrammeDetails']);

            // route related to reports

            Route::get('/admin/reports', [ReportController::class, 'index'])
                ->middleware('module.access:reports,view')
                ->name('admin.reports');

            Route::get('participants/report', [ReportController::class, 'participant_report'])
                ->name('participants.reports');

            Route::get('rating/report', [ReportController::class, 'rating_report'])
                ->name('rating.reports');

            Route::get('feedback/byProgramme', [ReportController::class, 'getFeedbackByProgramme'])->name('admin.feedback.byProgramme');

            Route::get('feedback/download/{programme_id}', [ReportController::class, 'downloadFeedback'])->name('admin.feedback.download');
            Route::get('/nominations/download/{programme}', [ReportController::class, 'download'])->name('admin.nominations.download');

            // route for the feedback

            Route::get('/feedback/master', [FeedbackController::class, 'master'])->name('feedback.master');

            Route::post('/feedback/store', [FeedbackController::class, 'store'])->name('master.feedback.store');

            // route related  to question
            Route::get('/set-paper/view', [ExamController::class, 'addquestionview'])->name('setpaper.view');
            Route::get('/get-department-data', [ExamController::class, 'getquestion'])->name('get.paper.data');
            Route::post('/process-questions', [ExamController::class, 'processQuestions'])
                ->name('process.questions');
            Route::post('/question/delete/{id}', [ExamController::class, 'destroy'])->name('question.delete');

            // ========================================================= Report =============================================================
            Route::prefix('report')->group(function () {

                Route::get('session-wise-feedback-report', [ReportController::class, 'session_wise_feedback_report'])->name('session-wise-feedback-report');

                Route::get('session-feedback-report', [ReportController::class, 'session_feedback_report'])->name('session-feedback-report');

            });

            Route::get('/marks/report', [ReportController::class, 'marksReportView'])->name('marks.reports');
            Route::get('/attendence/report', [ReportController::class, 'attendenceView'])->name('attendence.reports');
            Route::get('/marks/report/exist', [ReportController::class, 'marksReportViewExist'])->name('marks.exits.reports');
            Route::get('/feedback/report/view', [ReportController::class, 'marksReportViewfeedback'])->name('view.feedback.reports');
            Route::post('/get-programmes', [ReportController::class, 'getProgrammesByYear'])->name('getProgrammesByYear');
            Route::post('/get-participants', [ReportController::class, 'getParticipantsAndMarks'])->name('getParticipantsAndMarks');
            Route::post('/get-participantsexist', [ReportController::class, 'getParticipantsAndMarksexist'])->name('getParticipantsAndMarksexist');

            Route::post('/get-feedback', [ReportController::class, 'getfeedback'])->name('getfeedback');

            Route::get('/feedback/thankyou', [ParticiapntController::class, 'feedbackthanku'])->name('feedback.thankyou');

            Route::post('/get-attendence', [ReportController::class, 'attendenceReport'])->name('attendenceReport');
            Route::get('/marks/export/downlode/{id}', [ReportController::class, 'download_entry_excel'])->name('entry.test.downlode');
            Route::get('/marks/exist/downlode/{id}', [ReportController::class, 'download_exist_excel'])->name('exist.test.downlode');

            Route::get('/attendence/downlode/{id}', [ReportController::class, 'download_attendence_excel'])->name('attendence.downlode');
            Route::get('/selected/question/master', [ExamController::class, 'view_selected_question'])->name('view.selected.question');
            Route::post('/get-selected-question', [ExamController::class, 'getselectedquestion'])->name('getselectedquestion');

            Route::delete('/questions/{id}', [ExamController::class, 'destroy_selected_question'])->name('questions.destroy');

            // ===================================Exam Routes ======================================
            Route::get('/get-programmes-by-department', [ExamController::class, 'getProgrammesByDepartment'])
                ->name('get.programmes.by.department');

            Route::get('preview-queston-paper', [ExamController::class, 'previewQuestionPaper'])->name('preview.question.paper');

            Route::get('get-question-paper-by-programme/{programme_id}', [ExamController::class, 'getQuestionPaperByProgramme'])->name('get.question.paper.by.programme');

            Route::get('test-qr-code', [ExamController::class, 'testQrCode'])->name('test.qr.code');

            Route::get('get-programmes-by-years', [ExamController::class, 'getProgrammesByYears'])->name('admin.getProgrammesByYears');

            Route::post('update-question-paper-basic-details', [ExamController::class, 'updateQuestionPaperBasicDetails'])->name('admin.update-question-paper-basic-details');

            // ----------------------------------------- Qr code genrator -----------------------------------------
            Route::get('programme-exam-qr-codes', [ExamController::class, 'ProgrammeExamQrCodes'])->name('admin.programme-exam-qr-codes');

            Route::get('generate-qr-code', [ExamController::class, 'generateQrCode'])->name('admin.generate-qr-code');

            Route::get('/get/room/reports', [ReportController::class, 'view_room'])->name('room.occupancy.report');

            Route::get('/blocks/{block}/details', [ReportController::class, 'getBlockDetails'])->name('admin.blocks.details');

            Route::get('/room-occupancy/data', [ReportController::class, 'getRoomOccupancyData'])->name('admin.room-occupancy.data');

            // here is the all route related to the dashboard
            Route::get('/programme/list/dash', [DashboardController::class, 'view_prog_list'])->name('dashboard.programme.list');
            Route::get('/active-programme/list-dash', [DashboardController::class, 'active_programme'])->name('dashboard.active.programme.list');
            Route::get('dash/nomination/{id}', [DashboardController::class, 'view_nomination'])->name('dashboard.view.nomination');
            Route::get('/faculty/list/', [DashboardController::class, 'view_faculty'])->name('dashboard.faculty.list');
            // Approve Agency
            Route::post('/agency/approve/{id}', [AgencyController::class, 'approve'])->name('agency.approve');

            // Reject Agency
            Route::post('/agency/reject/{id}', [AgencyController::class, 'reject'])->name('agency.reject');

            Route::get('/poster/{data}', [ProgrammeManageController::class, 'poster'])->name('programme.poster');

            // =============================== Feedback ======================================
            Route::get('/feedback', [FeedbackController::class, 'index'])->name('admin.feedback');

            Route::post('add-feedback', [FeedbackController::class, 'addFeedback'])->name('admin.feedback.add');

            Route::get('get-feedback-data', [FeedbackController::class, 'getFeedbackData'])->name('admin.feedback.getData');

            Route::post('update-feedback', [FeedbackController::class, 'updateFeedback'])->name('admin.feedback.update');

            Route::get('view-full-feedback-page', [FeedbackController::class, 'viewFullFeedbackPage'])->name('admin.feedback.view');

            // ==========================================Participant Feedback Response ======================================

            Route::get('feedback-response', [FeedbackController::class, 'feedbackResponse'])->name('admin.feedback.response');

            Route::get('show-participant-on-program', [FeedbackController::class, 'ShowParticipantOnProgram'])->name('admin.feedback.show-participant-on-program');

            Route::get('feedback-response-by-participant', [FeedbackController::class, 'feedbackResponseByParticipant'])->name('admin.feedback.response-by-participant');

        });

        // this is a blank area write all the code above........

    });

    Route::get('/agency-panel', [AgencyAuthController::class, 'showLogin'])->name('agency.login.form');
    Route::post('/logout', [AgencyAuthController::class, 'logout'])->name('agency.logout');
    Route::post('agency-panel/login', [AgencyAuthController::class, 'login'])->name('agency.check');
    Route::middleware(['agency.auth', 'prevent'])->group(function () {

        Route::post('agency-panel/logout', [AgencyAuthController::class, 'logout'])->name('logout');

        Route::get('agency-panel/dashboard', [AgencyAuthController::class, 'dashboard'])->name('dashboard');
        Route::get('agency-panel/programmes', [AgencyAuthController::class, 'programmes'])->name('programmes');
        Route::get('agency-panel/programmes-data', [AgencyAuthController::class, 'getData']);
        Route::get('agency-panel/add-nomination/{id}', [AgencyAuthController::class, 'add_nomination']);

        // view the nomination list

        Route::get('agency-panel/nomination-list', [AgencyAuthController::class, 'nomination_list'])->name('nomination.list.show');
        Route::post('agency-panel/nomination-store', [AgencyAuthController::class, 'store']);
        Route::get('agency-panel/nomination-data', [AgencyAuthController::class, 'getNominationsData']);

        // route for the nomination payment

        Route::get('agency-panel/payment', [AgencyAuthController::class, 'showPaymentPage'])->name('Agency.Nomination.payment');
        Route::get('agency/payment/{nominationId}', [AgencyAuthController::class, 'pay'])->name('agency.pay');

        Route::post('/agency/payment/{nominationId}', [AgencyAuthController::class, 'processPayment'])->name('agency.payment.process');
        Route::get('/agency/nomination/edit/{id}', [AgencyAuthController::class, 'edit'])->name('agency.nomination.edit.form');
        Route::post('/agency/nominations/update/{nomination}', [AgencyAuthController::class, 'update'])->name('agency.nominations.update');
        Route::post('/agency/nominations/delete/{id}', [AgencyAuthController::class, 'destroy'])->name('agency.nominations.delete');

    });

    // Route::get('agency-panel/logout', [AgencyAuthController::class, 'logout']);
    Route::get('exam_login/{id}', [ExamController::class, 'exam_login'])->name('exam_login');
    Route::post('exam_check', [ExamController::class, 'exam_check'])->name('exam_check');

    Route::get('exam/{id}', [ExamController::class, 'AttempTest'])->name('admin.AttempTest');
    Route::get('exist/{id}', [ExamController::class, 'AttempTestexist'])->name('admin.exist.AttempTest');
    Route::get('thankyou', [ExamController::class, 'thankyou'])->name('admin.thankyou');

    Route::get('participant/feedback', [FeedbackController::class, 'particpate_feedback'])->name('participant.feedback');

    Route::post('participant/feedback/submit', [FeedbackController::class, 'submitFeedback'])->name('participant.feedback.submit');

    Route::post('/onlinetest', [ExamController::class, 'onlinetest'])->name('admin.onlinetest');
    Route::post('/onlinetest/exist', [ExamController::class, 'onlinetestexist'])->name('admin.exist.onlinetest');

    // Route::get('feedback', [ExamController::class, 'thankyou'])->name('admin.feedback');
    Route::get('participant/login', [ParticiapntController::class, 'view'])->name('participant.login');
    Route::post('participant/submit', [ParticiapntController::class, 'submit'])->name('participant.login.submit');

    // route related to the queries

    Route::get('participants/queries', [HostelController::class, 'queries'])->name('participant.queries');

    Route::post('participants/hostel/queries/store', [HostelController::class, 'store_queries'])->name('participant.hostel.queries');

    Route::post('participant/hostel/feedback', [HostelController::class, 'store_feedback'])->name('hostel.feedback.store');

    Route::get('participant/feedback/form/{id}', [HostelController::class, 'feedback_form'])->name('hostel.feedback.form');

    Route::post('/participant/response/store', [ParticiapntController::class, 'storeResponse'])->name('feedback.response.store');

    // route for the api

    Route::get('api_send_data', [ProgrammeManageController::class, 'api_send_data'])->name('session.api_send_data');
    Route::get('/search_api_send_data/{year?}/{month?}/{department?}/{programType?}', [ProgrammeManageController::class, 'search_api_send_data']);

    Route::get('api_archieve_data', [ProgrammeManageController::class, 'api_archieve_data'])->name('session.api_archieve_data');

    // route for the state and their districts
    Route::get('/states', [LocationApiController::class, 'getStates']);
    Route::get('/states/{state_id}/districts', [LocationApiController::class, 'getDistricts']);

    Route::get('/get-programme-by-code/{code}', [ProgrammeManageController::class, 'getByCode']);
    // routes/web.php
    Route::post('/get-programme-by-code', [ProgrammeManageController::class, 'getProgrammeByCode']);
    Route::post('/get-programme-unique-id', [ProgrammeManageController::class, 'getUniqueId']);

    // route related to the faculty]

    Route::get('/faculty/login', [FacultyController::class, 'login'])->name('faculty.login.form');
    Route::post('/faculty/login/submit', [FacultyController::class, 'login_submit'])->name('faculty.submit');
    Route::post('/faculty/logout', [FacultyController::class, 'logout'])->name('faculty.logout');
    Route::middleware(['faculty.auth', 'prevent'])->group(function () {

        Route::get('/faculty/dash', [FacultyController::class, 'dash'])->name('faculty.dash');
        Route::get('/faculty/assigned/programme', [FacultyController::class, 'view_assign_programme'])->name('faculty.assigned.programme');
        Route::get('/faculty/active/programme', [FacultyController::class, 'view_active_assign_programme'])->name('faculty.active.programme');
        Route::get('/faculty/view/nomination/{id}', [FacultyController::class, 'view_nomination'])->name('faculty.vew.nomination');

        Route::prefix('faculty')->group(function () {
            // route for the session
            Route::get('/programme-management-list', [FacultySessionCon::class, 'programmeManagementList'])->name('ff.programmeManagement-list');
            Route::get('/programme/faculity/session', [FacultySessionCon::class, 'facultySession'])->name('ff.faculity.session');
            Route::post('/programme/faculty/session/store', [FacultySessionCon::class, 'storeSession'])->name('ff.faculty.session.store');
            Route::get('/programme-management/get-data/subtopic', [FacultySessionCon::class, 'getDataSubtopic'])->name('ff.programmeManagement.getData.subtopic');
            Route::get('/programme-management/get-data', [FacultySessionCon::class, 'getData'])
                ->name('ff.session.getData');
            Route::post('/programme-sessions/update/{programmeId?}', [FacultySessionCon::class, 'updateSession'])->name('ff.programme.sessions.update');
            Route::post('/programme-sessions/delete/{id?}', [FacultySessionCon::class, 'destroySubtopic'])->name('ff.programme.session.delete');

            Route::get('programme-management/get-data/subtopic/filter', [FacultySessionCon::class, 'getDataSubtopicfilter'])->name('ff.programmeManagement.getData.subtopic.fliter');

            Route::get('/get-subtopic-dates', [FacultySessionCon::class, 'getSubtopicDates'])->name('ff.programmeManagement.getSubtopicDates');
            Route::get('/get-programme-dates', [FacultySessionCon::class, 'getProgrammeDates'])->name('ff.get.programme.dates');
            // routes/web.php
            Route::get('/subtopics/by-programme', [FacultySessionCon::class, 'getSubtopicsByProgramme'])->name('ff.get.subtopics.by.programme');
            Route::get('/programme/subtopics', [FacultySessionCon::class, 'getProgrammeSubtopics'])->name('ff.get.programme.subtopics');

            // -----------------------------------Day to day session----------------------------------------------
            Route::get('/day-to-day-session', [FacultySessionCon::class, 'DayToDaySession'])->name('ff.day-to-day-session');

            Route::get('view-day-to-day-session', [FacultySessionCon::class, 'viewDayToDaySession'])->name('ff.view-day-to-day-session');

            Route::get('/session-report/{id}', [FacultySessionCon::class, 'session_report'])->name('ff.programme.session.report');
            Route::get('/announcement/generate/{id}', [FacultySessionCon::class, 'generate'])->name('ff.announcement.generate');
            Route::post('/announcement/store/{id}', [FacultySessionCon::class, 'store'])->name('ff.announcement.store');
            Route::post('/programme/{id}/update-invitation', [FacultySessionCon::class, 'updateInvitation'])->name('ff.programme.updateInvitation');
            Route::post('/announcement/edit/store/{id}', [FacultySessionCon::class, 'store_edit_annoucement'])->name('ff.announcement.edit.store');
            Route::get('/programme/approve/{id}', [FacultySessionCon::class, 'approve'])->name('ff.programme.approve');

            // ==================================== Question Management ======================================

            // Exam
            Route::get('/faculty/programme-management/data',
                [FacultyExamController::class, 'getData']
            )->name('ff.programmeManagement.getData');
            Route::get('/addQuestion', [FacultyExamController::class, 'addQuestion'])->name('ff.addQuestion');
            Route::get('/Question_get_data', [FacultyExamController::class, 'Question_get_data'])->name('ff.Question_get_data');
            Route::post('/questionAdd', [FacultyExamController::class, 'questionAdd'])->name('ff.questionAdd');
            // route related  to question
            Route::get('/set-paper/view', [FacultyExamController::class, 'addquestionview'])->name('ff.setpaper.view');
            Route::get('/get-department-data', [FacultyExamController::class, 'getquestion'])->name('ff.get.paper.data');
            Route::post('/process-questions', [FacultyExamController::class, 'processQuestions'])
                ->name('ff.process.questions');
            Route::post('/question/delete/{id}', [FacultyExamController::class, 'destroy'])->name('ff.question.delete');

            Route::get('/get-programmes-by-department', [FacultyExamController::class, 'getProgrammesByDepartment'])
                ->name('ff.get.programmes.by.department');

            Route::get('preview-queston-paper', [FacultyExamController::class, 'previewQuestionPaper'])->name('ff.preview.question.paper');

            Route::get('get-question-paper-by-programme/{programme_id}', [FacultyExamController::class, 'getQuestionPaperByProgramme'])->name('ff.get.question.paper.by.programme');

            Route::get('test-qr-code', [FacultyExamController::class, 'testQrCode'])->name('ff.test.qr.code');

            Route::get('get-programmes-by-years', [FacultyExamController::class, 'getProgrammesByYears'])->name('ff.getProgrammesByYears');

            Route::post('update-question-paper-basic-details', [FacultyExamController::class, 'updateQuestionPaperBasicDetails'])->name('ff.update-question-paper-basic-details');

            // ----------------------------------------- Qr code genrator -----------------------------------------
            Route::get('programme-exam-qr-codes', [FacultyExamController::class, 'ProgrammeExamQrCodes'])->name('ff.programme-exam-qr-codes');

            Route::get('generate-qr-code', [FacultyExamController::class, 'generateQrCode'])->name('ff.generate-qr-code');

            Route::get('/selected/question/master', [FacultyExamController::class, 'view_selected_question'])->name('ff.view.selected.question');
            Route::post('/get-selected-question', [FacultyExamController::class, 'getselectedquestion'])->name('ff.getselectedquestion');

            Route::delete('/questions/{id}', [FacultyExamController::class, 'destroy_selected_question'])->name('ff.questions.destroy');

            Route::post('update-question-paper-basic-details', [FacultyExamController::class, 'updateQuestionPaperBasicDetails'])->name('ff.update-question-paper-basic-details');

        });

    });

    Route::get('/agency/registration/form', function () {
        $agencyTypes = AgencyType::where('is_deleted', 0)->get();

        return view('agencyreg', compact('agencyTypes'));
    })->name('out.agency.form');

    Route::post('/agency/store/reg', [AgencyController::class, 'store_out_agencyreg'])->name('out.agency.store');
    Route::get('/resume', function () {

        return view('resume');
    });

    Route::post('/translate-to-hindi', [TranslationController::class, 'translate']);

});
