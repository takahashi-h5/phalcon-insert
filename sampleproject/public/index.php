<?php
declare(strict_types=1);

error_reporting(E_ALL);
// オートローダー設定
$loader = new Phalcon\Loader();
$loader->registerNamespaces(
    [
        'App\Models' => __DIR__ . '/models/',
    ]
);
$loader->register();

// DB接続情報
$container = new Phalcon\Di\FactoryDefault();
$container->set(
    'db',
    function () {
        return new Phalcon\Db\Adapter\Pdo\Postgresql(
            [
                'host'        => 'localhost',
                'port'        => 5432,
                'username'    => 'postgres',
                'password'    => 'confrage',
                'dbname'      => 'postgres'
            ]
        );
    }
);

$app = new \Phalcon\Mvc\Micro($container); // DB情報を引数で渡す必要がある
$app->post(
    '/api/v2/createRecod',
    function () use ($app) {
        $body = $app->request->getJsonRawBody(); // リクエストボディを受け取る
        $status = $app
        ->modelsManager
        ->executeQuery(
            'INSERT INTO App\Models\Empuser (id, firstname, lastname, age) VALUES ' .
            '(:id:, :firstname:, :lastname:, :age:) ',
            [
                'id' => $body->id,
                'firstname' => $body->firstname,
                'lastname' => $body->lastname,
                'age' => $body->age
            ]
            );
        $response = new Phalcon\Http\Response();

        if ($status->success() === true) {
            $response->setStatusCode(201, 'Created');
            $response->setJsonContent(
                [
                    'status' => 'OK',
                    'data'   => ''
                ]
            );
        } else {
            $response->setStatusCode(409, 'Conflict');
            $errors = [];
            foreach ($status->getMessages() as $message) {
                $errors[] = $message->getMessage();
            }
            $response->setJsonContent(
                [
                    'status'   => 'ERROR',
                    'messages' => $errors,
                ]
            );
        }
        return $response;
    }
);

$app->handle(
    $_SERVER["REQUEST_URI"]
);