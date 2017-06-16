<?php


Route::get('/','Login@index');

//Route::get('auth/login', 'Front@login');

//Route::post('auth/login', 'Front@authenticate');

Route::post('auth/login', [
    'middleware' => 'LoginField',
    'uses' => 'Login@auth'
]);

Route::get('auth/logout', 'Login@logout');
Route::get('admin/user-list', 'Dashboard@user_list');

// Registration routes...
Route::post('/register', 'Front@register');
Route::get('/checkout', [
    'middleware' => 'auth',
    'uses' => 'Front@checkout'
]);


Route::get('admin/create-user', [
    'middleware' => ['auth', 'CheckAdmin'],
    'uses' => 'Dashboard@create_user'
]);

Route::get('admin/edit-user/{id}', [
    'middleware' => ['auth', 'CheckAdmin'],
    'uses' => 'Dashboard@edit_user'
]);


Route::group(['middleware' => ['web', 'auth', 'CreateUser']], function () {
    Route::post('admin/create_user/', 'UserController@create');
    Route::post('admin/edit-user/{id}', 'UserController@edit_user');
});

Route::get('programmer/create-project', [
    'middleware' => ['auth', 'CheckRole'],
    'uses' => 'Dashboard@create_program'
]);


Route::get('/dashboard', [
    'middleware' => 'auth',
    'uses' => 'Dashboard@index'
]);

Route::post('programmer/save-project-details',  [
    'middleware' => ['auth', 'CheckRole'] ,
    'uses' => 'UserController@save_details'
]);

Route::post('programmer/delete-project-details',  [
    'middleware' => ['auth'] ,
    'uses' => 'UserController@remove_project'
]);

Route::post('programmer/save-project',  [
    'middleware' => ['auth'] ,
    'uses' => 'UserController@save_project'
]);
Route::post('programmer/save-edit-project',  [
    'middleware' => ['auth'] ,
    'uses' => 'Project@edit'
]);

Route::get('projects',  [
    'middleware' => ['auth'] ,
    'uses' => 'Dashboard@project_listing'
]);

Route::get('project/edit-project/{id}',  [
    'middleware' => ['auth', 'CheckProject',  "CheckProjectSuccess" , "check_project_success"] ,
    'uses' => 'Dashboard@edit_project_form'
]);

Route::get('project/view-project/{id}', [
    'middleware' => ['auth', 'CheckProject'],
    'uses' => 'Dashboard@view_project'
]);

Route::post('project/save_edited_project/{id}', [
    'middleware' => ['auth', 'CheckProjectExist', 'CheckProjectSuccess'],
    'uses' => 'Project@save_edited_project'
]);

Route::get('project/approve-request/{id}', [
    'middleware' => ['auth', 'CheckProjectExist', 'CheckProjectApproval', "CheckProjectSuccess"],
    'uses' => 'Project@project_approval'
]);

Route::get('project/cancel-request/{id}', [
    'middleware' => ['auth', 'CheckProjectExist', 'CheckProjectApproval', "CheckProjectSuccess"],
    'uses' => 'Project@project_cancel'
]);

Route::get('project/approve-projects', [
    'middleware' => ['auth', 'CheckAdmin'],
    'uses' => 'Dashboard@project_for_approval'
]);

Route::get('project/ongoing-projects', [
    'middleware' => ['auth'],
    'uses' => 'Dashboard@ongoing_projects'
]);


Route::post('project/save-complete-project-details', [
    'middleware' => ['auth', 'check_project_complete', 'CheckAdmin'],
    'uses' => 'Project@save_complete_details'
]);

Route::get('project/delete-project/{id}', [
    'middleware' => ['auth' , 'CheckAdmin', 'CheckProjectSuccess'],
    'uses' => 'Project@delete_project'
]);



Route::get('project/create-csv', [
    'middleware' => ['auth', 'CheckAdmin'],
    'uses' => 'Dashboard@create_excel'
]);

