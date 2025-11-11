<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../app/Core/Config.php";
require_once __DIR__ . "/../app/Core/Router.php";

spl_autoload_register(function ($class) {
    $prefix = "App\\";
    $base_dir = __DIR__ . "/../app/";

    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = $base_dir . str_replace("\\", "/", $relativeClass) . ".php";

    if (file_exists($file)) {
        require $file;
    }
});

use App\Core\Router;

$router = new Router();

// Home
$router->get('/', 'Home\\Controllers\\HomeController@index');

// Usuários
$router->get('/admin/usuarios', 'User\\Controllers\\UserController@index');
$router->get('/admin/usuario/criar', 'User\\Controllers\\UserController@create');
$router->post('/admin/usuario/criar', 'User\\Controllers\\UserController@create');
$router->get('/admin/usuario/{id}', 'User\\Controllers\\UserController@profile');
$router->get('/admin/usuario/{id}/editar', 'User\\Controllers\\UserController@edit');
$router->post('/admin/usuario/{id}/editar', 'User\\Controllers\\UserController@edit');
$router->get('/admin/usuario/{id}/deletar', 'User\\Controllers\\UserController@delete');

// Autenticação
$router->get('/admin/login', 'Auth\\Controllers\\AuthController@login');
$router->post('/admin/login', 'Auth\\Controllers\\AuthController@login');
$router->get('/admin/logout', 'Auth\\Controllers\\AuthController@logout');

// Dashboard
$router->get('/admin', 'Dashboard\\Controllers\\DashboardController@index');

// Mídias
$router->get('/admin/midia', 'Media\\Controllers\\MediaController@index');
$router->post('/admin/midia/criar', 'Media\\Controllers\\MediaController@create');
$router->get('/admin/midia/{id}/deletar', 'Media\\Controllers\\MediaController@delete');

// Eventos
$router->get('/admin/eventos', 'Event\\Controllers\\EventController@index');
$router->get('/admin/evento/criar', 'Event\\Controllers\\EventController@create');
$router->post('/admin/evento/criar', 'Event\\Controllers\\EventController@create');
$router->get('/admin/evento/{id}', 'Event\\Controllers\\EventController@show');
$router->get('/admin/evento/{id}/editar', 'Event\\Controllers\\EventController@edit');
$router->post('/admin/evento/{id}/editar', 'Event\\Controllers\\EventController@edit');
$router->get('/admin/evento/{id}/deletar', 'Event\\Controllers\\EventController@delete');
$router->get('/eventos', 'Event\\Controllers\\PublicEventController@index');
$router->get('/evento/{id}', 'Event\\Controllers\\PublicEventController@show');

// Publicações
$router->get('/admin/publicacoes', 'Publication\\Controllers\\PublicationController@index');
$router->get('/admin/publicacao/criar', 'Publication\\Controllers\\PublicationController@create');
$router->post('/admin/publicacao/criar', 'Publication\\Controllers\\PublicationController@create');
$router->get('/admin/publicacao/{id}', 'Publication\\Controllers\\PublicationController@show');
$router->get('/admin/publicacao/{id}/editar', 'Publication\\Controllers\\PublicationController@edit');
$router->post('/admin/publicacao/{id}/editar', 'Publication\\Controllers\\PublicationController@edit');
$router->get('/admin/publicacao/{id}/deletar', 'Publication\\Controllers\\PublicationController@delete');
$router->get('/publicacoes', 'Publication\\Controllers\\PublicPublicationController@index');
$router->get('/publicacao/{id}', 'Publication\\Controllers\\PublicPublicationController@show');

// Sermões
$router->get('/admin/sermoes', 'Sermon\\Controllers\\SermonController@index');
$router->get('/admin/sermao/criar', 'Sermon\\Controllers\\SermonController@create');
$router->post('/admin/sermao/criar', 'Sermon\\Controllers\\SermonController@create');
$router->get('/admin/sermao/{id}', 'Sermon\\Controllers\\SermonController@show');
$router->get('/admin/sermao/{id}/editar', 'Sermon\\Controllers\\SermonController@edit');
$router->post('/admin/sermao/{id}/editar', 'Sermon\\Controllers\\SermonController@edit');
$router->get('/admin/sermao/{id}/deletar', 'Sermon\\Controllers\\SermonController@delete');
$router->get('/sermoes', 'Sermon\\Controllers\\PublicSermonController@index');
$router->get('/sermao/{id}', 'Sermon\\Controllers\\PublicSermonController@show');

$router->dispatch();