<?php
use Festiv\Publ\Http\Controllers\Admin\CategoryController;
use Festiv\Publ\Http\Controllers\Admin\PostController;
use Festiv\Publ\Http\Controllers\Admin\UserController;
use Festiv\Publ\Http\Controllers\AdminController;
use Festiv\Publ\Http\Controllers\AuthController;
use Festiv\Publ\Http\Controllers\HomeController;
use Festiv\Publ\Http\Middlewares\ValidateAdmin;
use Wandu\Http\Middleware\Responsify;
use Wandu\Http\Middleware\Sessionify;
use Wandu\Router\Router;

return function (Router $router) {
    $router->middleware([
        Sessionify::class,
        Responsify::class,
    ], function (Router $router) {


        $router->get('/', HomeController::class);
        $router->get('/error', HomeController::class, 'error');



        $router->group([
            'prefix' => '/admin',
            'middleware' => ValidateAdmin::class,
        ], function (Router $router) {
            $router->get('/', AdminController::class);

            $router->prefix('/categories', function (Router $router) {
                $router->get('/', CategoryController::class);
                $router->post('/', CategoryController::class, 'create');
                $router->get('/{id}', CategoryController::class, 'show');
                $router->put('/{id}', CategoryController::class, 'update');
                $router->delete('/{id}', CategoryController::class, 'delete');
            });

            $router->prefix('/users', function (Router $router) {
                $router->get('/', UserController::class);
                $router->post('/', UserController::class, 'create');
                $router->get('/{id}', UserController::class, 'show');
                $router->put('/{id}', UserController::class, 'update');
                $router->delete('/{id}', UserController::class, 'delete');
            });

            $router->prefix('/posts', function (Router $router) {
                $router->get('/', PostController::class);
                $router->get('/write', PostController::class, 'write');
                $router->post('/', PostController::class, 'create');
                $router->get('/{id}', PostController::class, 'show');
                $router->put('/{id}', PostController::class, 'update');
                $router->delete('/{id}', PostController::class, 'delete');
            });
        });

        $router->prefix('/auth', function (Router $router) {
            $router->get('/register', AuthController::class, 'register');
            $router->post('/register', AuthController::class, 'registering');

            $router->get('/reset', AuthController::class, 'reset');
            $router->post('/reset', AuthController::class, 'resetting');

            $router->get('/login', AuthController::class, 'login');
            $router->post('/login', AuthController::class, 'logining');

            $router->any('/logout', AuthController::class, 'logouting');
        });
    });
};
