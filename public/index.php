<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__ . '/../vendor/autoload.php';

session_start();

// Database
require __DIR__ . '/../src/database.php';

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/../src/dependencies.php';

// Register middleware
require __DIR__ . '/../src/middleware/middleware.php';

// Models
//require __DIR__ . '/../model/pdo.class.php';
require __DIR__ . '/../model/cidades.class.php';
require __DIR__ . '/../model/pessoas.class.php';
require __DIR__ . '/../model/profissionais.class.php';

// Register routes
//require __DIR__ . '/../src/routes/routes.php';
require __DIR__ . '/../src/routes/cidades.php';
require __DIR__ . '/../src/routes/profissionais.php';

// Run app
$app->run();

/*
require_once "../vendor/autoload.php";

$config = [
    'settings' => [
        'displayErrorDetails' => true
    ]
];

$app = new \Slim\App($config);

// Models
require_once "../model/pdo.class.php";
$pdo = Database::conexao();
$stmt = $pdo->prepare('SELECT * FROM CIDADE');
$stmt->execute();
echo "Teste". $stmt->rowCount();

//require_once "../model/Cidades.php";

// Routes
//require_once "../routes/cidades/get.php";

$app->run();
*/