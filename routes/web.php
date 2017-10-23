<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Auth::routes();


Route::get('/', function (){
    return redirect('/contacts');
});

Route::get('/contacts', ['as' => 'contacts', 'uses' => 'ContactController@index']);
Route::get('/contacts/departments/{departmentId}', ['as' => 'contacts.department', 'uses' => 'ContactController@showDepartment'])->where('departmentId', '[0-9]+');

Route::get('/contacts/departments/{departmentId}#contact{contactId}', ['as' => 'contacts.view', 'uses' => 'ContactController@show'])->where('contactId', '[0-9]+');
Route::get('/contacts/#contact{contactId}', ['as' => 'contacts.view.department', 'uses' => 'ContactController@show'])->where('contactId', '[0-9]+');

Route::any('/contacts/show', ['as' => 'contacts.restShow', 'uses' => 'ContactController@restShow']);
Route::any('/contacts/task/{contactId}', ['as' => 'contacts.task', 'uses' => 'ContactController@task'])->where('contactId', '[0-9]+');
Route::any('/contacts/task/completed/{taskId}', ['as' => 'contacts.taskCompleted', 'uses' => 'ContactController@taskCompleted'])->where('taskId', '[0-9]+');

Route::any('/contacts/store/{contactId?}', ['as' => 'contacts.store', 'uses' => 'ContactController@store']);
Route::get('/contacts/activity', ['as' => 'contacts.activity', 'uses' => 'ContactController@activity']);
Route::post('/contacts/comment{contactId}', ['as' => 'contacts.addComment', 'uses' => 'ContactController@addComment'])->where('contactId', '[0-9]+');
Route::post('/contacts/addContact', ['as' => 'contacts.addContact', 'uses' => 'ContactController@addContact']);
Route::post('/contacts/addLead/{contactId}', ['as' => 'contacts.addLead', 'uses' => 'ContactController@addLead'])->where('contactId', '[0-9]+');
Route::post('/contacts/updatePayment', ['as' => 'contacts.updatePayment', 'uses' => 'ContactController@updatePayment']);
Route::post('/contacts/taskDelete/{taskId}', ['as' => 'contacts.taskDelete', 'uses' => 'ContactController@taskDelete'])->where('contactId', '[0-9]+');

Route::get('/leads', ['as' => 'leads', 'uses' => 'LeadController@index']);
Route::get('/leads/departments/{departmentId}', ['as' => 'leads.department', 'uses' => 'LeadController@showDepartment'])->where('departmentId', '[0-9]+');
Route::get('/leads/{leadId}', ['as' => 'leads.view', 'uses' => 'LeadController@show'])->where('leadId', '[0-9]+');
Route::any('/leads/store/{leadId?}', ['as' => 'leads.store', 'uses' => 'LeadController@store']);
Route::get('/leads/activity', ['as' => 'leads.activity', 'uses' => 'LeadController@activity']);
Route::post('/leads/comment{leadId}', ['as' => 'leads.addComment', 'uses' => 'LeadController@addComment'])->where('leadId', '[0-9]+');
Route::get('/leads/statusUpdate', ['as' => 'leads.statusUpdate', 'uses' => 'LeadController@statusUpdate']);
Route::any('/leads/delete/{leadId}', ['as' => 'leads.delete', 'uses' => 'LeadController@delete']);

Route::any('/folders/create/{contactId}', ['as' => 'folders.create', 'uses' => 'FolderController@create']);
Route::any('/folders/delete/{contactId}', ['as' => 'folders.delete', 'uses' => 'FolderController@delete']);
Route::any('/folders/upload/{contactId}', ['as' => 'folders.upload', 'uses' => 'FolderController@upload']);


Route::resource('settings', 'SettingController');
Route::any('/settings/user/store/{contactId?}', ['as' => 'setting.user.store', 'uses' => 'SettingController@userStore']);
Route::any('/settings/department/store/{departmentId?}', ['as' => 'setting.department.store', 'uses' => 'SettingController@departmentStore']);
Route::any('/settings/teams/store/{teamId?}', ['as' => 'setting.teams.store', 'uses' => 'SettingController@teamStore']);
Route::any('/settings/service/store/{serviceId?}', ['as' => 'setting.service.store', 'uses' => 'SettingController@serviceStore']);
Route::any('/settings/status/store/{statusId?}', ['as' => 'setting.status.store', 'uses' => 'SettingController@statusStore']);
Route::any('/settings/origin/store/{originId?}', ['as' => 'setting.origin.store', 'uses' => 'SettingController@originStore']);

Route::get('/tasks', ['as' => 'tasks', 'uses' => 'TaskController@index']);
Route::get('/tasks/types/{typeId}', ['as' => 'tasks.type', 'uses' => 'TaskController@showType'])->where('typeId', '[0-9]+');
Route::get('/tasks/{taskId}', ['as' => 'tasks.view', 'uses' => 'TaskController@show'])->where('taskId', '[0-9]+');
Route::any('/tasks/store/{taskId?}', ['as' => 'tasks.store', 'uses' => 'TaskController@store'])->where('taskId', '[0-9]+');
Route::get('/tasks/activity', ['as' => 'tasks.activity', 'uses' => 'TaskController@activity']);
Route::post('/tasks/comment{taskId}', ['as' => 'tasks.addComment', 'uses' => 'TaskController@addComment'])->where('taskId', '[0-9]+');
Route::post('/tasks/addNotification/{taskId}', ['as' => 'tasks.addNotification', 'uses' => 'TaskController@addNotification'])->where('taskId', '[0-9]+');
Route::any('/tasks/completed/{taskId?}', ['as' => 'tasks.completed', 'uses' => 'TaskController@completed'])->where('taskId', '[0-9]+');
Route::any('/tasks/request/{taskId?}', ['as' => 'tasks.getNewRequest', 'uses' => 'TaskController@getNewRequest'])->where('taskId', '[0-9]+');
Route::any('/tasks/remove/{taskId}', ['as' => 'tasks.remove', 'uses' => 'TaskController@remove'])->where('taskId', '[0-9]+');
Route::any('/tasks/getId/{Id}', ['as' => 'getId', 'uses' => 'ContactController@getIdFromJs']);

Route::resource('analytics', 'AnalyticsController');


Route::get('/messages', ['as' => 'messages', 'uses' => 'MessageController@index']);
Route::get('/messages/{messageId}', ['as' => 'messages.view', 'uses' => 'MessageController@show'])->where('messageId', '[0-9]+');
Route::any('/messages/store/{messageId?}', ['as' => 'messages.store', 'uses' => 'MessageController@store'])->where('messageId', '[0-9]+');
Route::any('/messages/send/{messageId?}', ['as' => 'messages.send', 'uses' => 'MessageController@send'])->where('messageId', '[0-9]+');
Route::any('/messages/contacts/{messageId?}', ['as' => 'messages.contacts', 'uses' => 'MessageController@contacts'])->where('messageId', '[0-9]+');
Route::any('/messages/contactDelete/{messageId?}', ['as' => 'messages.contactDelete', 'uses' => 'MessageController@contactDelete'])->where('messageId', '[0-9]+');
Route::any('/messages/contactClean/{messageId?}', ['as' => 'messages.contactClean', 'uses' => 'MessageController@contactClean'])->where('messageId', '[0-9]+');


Route::get('/expenses', ['as' => 'expenses', 'uses' => 'ExpenseController@index']);
Route::get('/expenses/{expenseID}', ['as' => 'expenses.view', 'uses' => 'ExpenseController@show'])->where('expenseID', '[0-9]+');
Route::any('/expenses/store/{expenseID?}', ['as' => 'expenses.store', 'uses' => 'ExpenseController@store']);


Route::get('/users/{userId?}', ['as' => 'users.view', 'uses' => 'UserController@view'])->where('userId', '[0-9]+');


Route::get('/integration/{depId}/lead', ['as' => 'integration', 'uses' => 'IntegrationController@integration'])->where('depId', '[0-9]+');
Route::get('/integration/{depId}/widget.js', ['as' => 'integrationWidget', 'uses' => 'IntegrationController@integrationWidget'])->where('depId', '[0-9]+');
Route::any('/integration/{depId}/callbackkiller', ['as' => 'integrationCallbackkiller', 'uses' => 'IntegrationController@callbackkiller'])->where('depId', '[0-9]+');


