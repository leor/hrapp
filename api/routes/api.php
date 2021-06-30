<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->group(['prefix' => 'departments'], function() use ($router) {
    $router->get('/', ['uses' => 'DepartmentsController@index']);
    $router->get('/{id}', ['uses' => 'DepartmentsController@get']);
    $router->post('/create', ['uses' => 'DepartmentsController@create']);
    $router->post('/{id}', ['uses' => 'DepartmentsController@update']);
    $router->delete('/{id}', ['uses' => 'DepartmentsController@delete']);
});

$router->group(['prefix' => 'employees'], function() use ($router) {
    $router->post('/create', ['uses' => 'EmployeesController@create']);
    $router->post('/{id}', ['uses' => 'EmployeesController@update']);
    $router->delete('/{id}', ['uses' => 'EmployeesController@delete']);
});

$router->group(['prefix' => 'reports'], function() use ($router) {
    $router->get('/highest-salary', ['uses' => 'ReportsController@highestSalary']);
    $router->get('/two-with-50', ['uses' => 'ReportsController@twoWith50']);
});
