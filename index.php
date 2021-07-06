<?PHP
require_once 'vendor/autoload.php';
require_once 'src/routes/Router.php';
require_once 'src/models/APIResponse.php';

header("Access-Control-Allow-Origin: *");
//header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$router = new Router();

$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$response = $router->handleRequest($url, 'GET');

if ($response instanceof Error) {

    http_response_code($response->getCode());

    if ($response->getMessage()) {
        echo $response->getMessage();
    }

    die();
} else if ($response instanceof APIResponse) {

    http_response_code($response->getStatusCode());
    echo $response->getMessage();

    die();

} else {

    http_response_code(500);
    echo 'API response could not be interpreted';

    die();

}