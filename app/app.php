<?php

require_once __DIR__ . '/bootstrap.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Silex\Application;
use Silex\Provider\SecurityServiceProvider;
use Silex\Provider\UrlGeneratorServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Pfmgr\Exception;

$app = new Application();

require __DIR__ . '/../resources/config/dev.php';

// Register logging
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__ . '/../resources/log/app.log',
));

$app->register(new UrlGeneratorServiceProvider());

// Setup sessions
$app->register(new Silex\Provider\SessionServiceProvider());

// General Service Provder for Controllers
$app->register(new Silex\Provider\ServiceControllerServiceProvider());

// Register database handle
$app->register(new DoctrineServiceProvider(), $app['db.options']);

$app->register(new DoctrineOrmServiceProvider, array(
    "orm.proxies_dir" => __DIR__ . "/../orm/proxies",
    "orm.em.options" => array(
        "mappings" => array(
            array(
                "type" => "annotation",
                "namespace" => "Pfmgr\Entity",
                "path" => __DIR__ . "/../src/Pfmgr/Entity",
            ),
        )
    )
));

// Fixtures used by unit tests to populate database for functional tests
$app['orm.fixtures'] = $app->share(function ($app){
    return new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle;
});

// The request body should only be parsed as JSON if the Content-Type header begins with application/json.
$app->before(function (Request $request) use ($app) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
}, Application::EARLY_EVENT);

//Security definition.
$app->register(new SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'login' => array(
            'pattern' => '^/auth/login$',
        ),
        'index' => array(
            'pattern' => '^.*$',
            'form'      => array(
                'login_path' => '/auth/login',
                'check_path' => '/auth/check',
                'username_parameter' => 'username',
                'password_parameter' => 'password',
                // since this is a REST API, never redirect
                // back to last location, fronnt end handles this
                'always_use_default_target_path' => true,
                'default_target_path'            => '/auth/status',
                //'failure_path'        => '/auth/failure'
            ),
            'anonymous' => false,
            // a custom logout route is used in because we do not want a redirect
            //'logout'    => array('logout_path' => '/auth/logout'),
            'users'     => $app->share(function() use ($app) {
                return new Pfmgr\Provider\User($app);
            }),
        ),
    ),
));

// $app['security.authentication.logout_handler._proto'] = $app->protect(function () use ($app) {
//     return $app->share(function () use ($app) {
//         return new Pfmgr\Security\LogoutSuccessHandler(
//             $app['security.http_utils'],
//             '/'
//         );
//     });
// });

// Define a custom encoder for Security/Authentication
$app['security.encoder.digest'] = $app->share(function () {
    // uses the password-compat encryption
    return new BCryptPasswordEncoder(10);
});

// Default error handler
$app->error(function (\Exception $e, $code) use($app) {
    $app['monolog']->addError($e->getMessage());
    $message = 'The server encountered an error.';
    if ($app['debug']===true) {
        $message = $e->getMessage();
    }
    return $app->json($message, 403);
});

$app->get('/auth/login', function() use ($app) {
    return $app->json('Login page', 401);
});

// $app->get('/', function() use ($app) {
//     return $app->json('You must login', 401);
// });

$app->get('/auth/logout', function() use ($app) {
    $app['security']->setToken(null);
    $app['session']->invalidate();
    return $app->json('Logged out', 200);
});

$app->get('/auth/status', function() use ($app) {
    $user = $app['security']->getToken()->getUser();
    return $app->json(array(
        'id' => $user->getId(),
        'username' => $user->getUsername(),
        'roles' => $user->getRoles()
    ));
});

// $app->post('/auth/check', function() use ($app) {
//     return $app->json(array(
//         'username' => 'bob',
//         'roles' => array("ROLE_USER")
//     ));
// });

// $app->get('/auth/status', function() use ($app) {
//     return $app->json(array(
//         'username' => 'bob',
//         'roles' => array("ROLE_USER")
//     ));
// });

// General Service Provder for Controllers
$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app['controller.user'] = $app->share(function() use ($app) {
    return new Pfmgr\Controller\User();
});
$app->get('/user/{id}', "controller.user:fetchAction");
$app->post('/user/create', "controller.user:createAction");

$app['controller.account'] = $app->share(function() use ($app) {
    return new Pfmgr\Controller\Account();
});
$app->post('/account/create', "controller.account:createAction");

$app['controller.currency'] = $app->share(function() use ($app) {
    return new Pfmgr\Controller\Currency();
});
$app->get('/currency', "controller.currency:fetchAllAction");
$app->post('/currency/create', "controller.currency:createAction");

$app['controller.account'] = $app->share(function() use ($app) {
    return new Pfmgr\Controller\Account();
});
$app->post('/account/create', "controller.account:createAction");
$app->get('/account/{id}', "controller.account:fetchAction");
$app->get('/account/user/fetch', "controller.account:fetchByUserAction");

$app['controller.transaction'] = $app->share(function() use ($app) {
    return new Pfmgr\Controller\AccountTransaction();
});
$app->post('/transaction/create', "controller.transaction:createAction");
$app->get('/transaction/{id}', "controller.transaction:fetchAction");
$app->get('/transaction/account/{id}', "controller.transaction:fetchByAccountAction");
$app->get('/transaction/user/fetch', "controller.transaction:fetchByUserAction");

// must return $app for unit testing to work
return $app;