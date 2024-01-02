<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
set_time_limit(0);

Route::get('/', function () {
  return view('auth.login');
 //route('login');
})->name('welcome');

Auth::routes();

Route::get('home', 'HomeController@index')->name('home');
Route::get('dashboard', 'HomeController@index')->name('home');
Route::get('pricing', 'ExamplePagesController@pricing')->name('page.pricing');
Route::get('lock', 'ExamplePagesController@lock')->name('page.lock');
Route::get('error', ['as' => 'page.error', 'uses' => 'ExamplePagesController@error']);

Route::group(['middleware' => 'auth'], function () {
		Route::resource('category', 'CategoryController', ['except' => ['show']]);
		Route::resource('term', 'TermController', ['except' => ['show']]);
		Route::resource('item', 'ItemController', ['except' => ['show']]);
		Route::resource('mythesis', 'MythesisController', ['except' => ['show']]);
		Route::resource('meetinglogs', 'MeetingLogsController', ['except' => ['destroy']]);
		Route::resource('role', 'RoleController', ['except' => ['show', 'destroy']]);
		Route::resource('timeline', 'ThesisTimelineController', ['except' => ['show']]);
		Route::resource('user', 'UserController', ['except' => ['show']]);
		Route::post('user/behalf', ['as' => 'user.behalf', 'uses' => 'UserController@accessOnbehalfSomeone']);
		Route::get('item/{id}/detail', ['as' => 'item.detail', 'uses' => 'ItemController@detail']);		
		Route::get('item/{id}/allocation', ['as' => 'item.allocation', 'uses' => 'ItemController@showthesisAllocation']);
		Route::get('item/student-thesis', ['as' => 'item.student-thesis', 'uses' => 'ItemController@studentThesisCreation']);
		Route::post('item/addstudent-thesis', ['as' => 'item.addstudent-thesis', 'uses' => 'ItemController@storeStudentthesis']);
		Route::get('item/{id}/editstudent-thesis', ['as' => 'item.editstudent-thesis', 'uses' => 'ItemController@editStudentthesis']);
		Route::post('item/update-student-thesis', ['as' => 'item.update-student-thesis', 'uses' => 'ItemController@updateStudentThesis']);
		Route::get('item/archive', ['as' => 'item.archive', 'uses' => 'ItemController@archiveThesissListing']);
		Route::get('item/archive-detail', ['as' => 'item.archive-detail', 'uses' => 'ItemController@vewArchiveThesisDetails']);
		Route::post('mythesis/approve-termfile', ['as' => 'mythesis.approve-termfile', 'uses' => 'MyThesisController@approveTermsFileSubmission']);
		Route::post('item/{id}/update-request', ['as' => 'item.update-request', 'uses' => 'ItemController@updateAllocationRequest']);
		Route::get('item/{id}/approve', ['as' => 'item.approve', 'uses' => 'ItemController@approveAllocationRequest']);
		Route::get('item/previous-thesis', ['as' => 'item.previous-thesis', 'uses' => 'ItemController@allottedItemsIndex']);
		Route::post('item/prepare-rubric', ['as' => 'item.prepare-rubric', 'uses' => 'ThesisRubricDetailsController@prepareThesisRubrics']);
		Route::post('item/store-rubric', ['as' => 'item.store-rubric', 'uses' => 'ThesisRubricDetailsController@storeThesisRubrics']);
		Route::post('item/view-rubric', ['as' => 'item.view-rubric', 'uses' => 'ThesisRubricDetailsController@viewThesisRubrics']);
		Route::post('item/update-msgviewed', ['as' => 'item.update-msgviewed', 'uses' => 'ItemController@updateMessageViewInformation']);
		Route::get('mythesis/detail', ['as' => 'mythesis.detail', 'uses' => 'MythesisController@vewThesisDetails']);
		Route::get('mythesis/assigned', ['as' => 'mythesis.assigned', 'uses' => 'MythesisController@vewAssignedThesisDetails']);
		Route::get('mythesis/examine', ['as' => 'mythesis.examine', 'uses' => 'MythesisController@vewExamineThesisDetails']);
		Route::post('mythesis/{id}/request-approve', ['as' => 'mythesis.request-approve', 'uses' => 'MythesisController@updateThesisRequestApprovalStatus']);
		Route::post('mythesis/{id}/request-accept', ['as' => 'mythesis.request-accept', 'uses' => 'MythesisController@updateThesisRequestAcceptStatus']);
		Route::post('mythesis/{id}/comment-update', ['as' => 'mythesis.comment-update', 'uses' => 'MythesisController@updateThesisComments']);
		Route::post('mythesis/{id}/progress-update', ['as' => 'mythesis.progress-update', 'uses' => 'MythesisController@updateThesisProgressStatus']);
		Route::get('download/{id}/viewfile', ['as' => 'download.viewfile', 'uses' => 'DownloadController@download']);
		Route::get('templates/view-folders-files', ['as' => 'templates.view-folders-files', 'uses' => 'DownloadController@viewDocumentFoldersFiles']);
		Route::get('templates/create-folder-files', ['as' => 'templates.create-folder-files', 'uses' => 'DownloadController@DocumentFoldersFilesCreate']);	
		Route::post('templates/store-folder-files', ['as' => 'templates.store-folder-files', 'uses' => 'DownloadController@DocumentFoldersFilesStore']);
		Route::post('templates/delete-folder-files', ['as' => 'templates.delete-folder-files', 'uses' => 'DownloadController@DocumentFoldersFilesDelete']);
		Route::get('timeline/create-thesis-timeline', ['as' => 'timeline.create-thesis-timeline', 'uses' => 'ThesisTimelineController@create']);
		Route::get('timeline/view-thesis-timeline', ['as' => 'timeline.view-thesis-timeline', 'uses' => 'ThesisTimelineController@index']);
		Route::get('item/sendemail', ['as' => 'item.sendemail', 'uses' => 'ItemController@testSendEmail']);
		Route::post('tag/addkeyword', ['as' => 'tag.addkeyword', 'uses' => 'TagController@ajaxStore']);
		Route::post('mythesis/{id}/prepare-meeting-minutes', ['as' => 'mythesis.prepare-meeting-minutes', 'uses' => 'MeetingLogsController@prepareMeetingMinutes']);
		Route::post('mythesis/{id}/view-meeting-minutes', ['as' => 'mythesis.view-meeting-minutes', 'uses' => 'MeetingLogsController@viewMeetingMinutes']);
		
		Route::get('profile', ['as' => 'profile.edit', 'uses' => 'ProfileController@edit']);
		Route::put('profile', ['as' => 'profile.update', 'uses' => 'ProfileController@update']);
		Route::put('profile/password', ['as' => 'profile.password', 'uses' => 'ProfileController@password']);
		
		Route::get('rtl-support', ['as' => 'page.rtl-support', 'uses' => 'ExamplePagesController@rtlSupport']);
		Route::get('timeline', ['as' => 'page.timeline', 'uses' => 'ExamplePagesController@timeline']);
		Route::get('widgets', ['as' => 'page.widgets', 'uses' => 'ExamplePagesController@widgets']);
		Route::get('charts', ['as' => 'page.charts', 'uses' => 'ExamplePagesController@charts']);
		Route::get('calendar', ['as' => 'page.calendar', 'uses' => 'ExamplePagesController@calendar']);

		Route::get('buttons', ['as' => 'page.buttons', 'uses' => 'ComponentPagesController@buttons']);
		Route::get('grid-system', ['as' => 'page.grid', 'uses' => 'ComponentPagesController@grid']);
		Route::get('panels', ['as' => 'page.panels', 'uses' => 'ComponentPagesController@panels']);
		Route::get('sweet-alert', ['as' => 'page.sweet-alert', 'uses' => 'ComponentPagesController@sweetAlert']);
		Route::get('notifications', ['as' => 'page.notifications', 'uses' => 'ComponentPagesController@notifications']);
		Route::get('icons', ['as' => 'page.icons', 'uses' => 'ComponentPagesController@icons']);
		Route::get('typography', ['as' => 'page.typography', 'uses' => 'ComponentPagesController@typography']);
		
		Route::get('regular-tables', ['as' => 'page.regular_tables', 'uses' => 'TablePagesController@regularTables']);
		Route::get('extended-tables', ['as' => 'page.extended_tables', 'uses' => 'TablePagesController@extendedTables']);
		Route::get('datatable-tables', ['as' => 'page.datatable_tables', 'uses' => 'TablePagesController@datatableTables']);

		Route::get('regular-form', ['as' => 'page.regular_forms', 'uses' => 'FormPagesController@regularForms']);
		Route::get('extended-form', ['as' => 'page.extended_forms', 'uses' => 'FormPagesController@extendedForms']);
		Route::get('validation-form', ['as' => 'page.validation_forms', 'uses' => 'FormPagesController@validationForms']);
		Route::get('wizard-form', ['as' => 'page.wizard_forms', 'uses' => 'FormPagesController@wizardForms']);

		Route::get('google-maps', ['as' => 'page.google_maps', 'uses' => 'MapPagesController@googleMaps']);
		Route::get('fullscreen-maps', ['as' => 'page.fullscreen_maps', 'uses' => 'MapPagesController@fullscreenMaps']);
		Route::get('vector-maps', ['as' => 'page.vector_maps', 'uses' => 'MapPagesController@vectorMaps']);
	});