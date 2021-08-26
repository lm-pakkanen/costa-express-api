<?PHP

namespace Src;

use Error;

use Src\helpers\DotEnv;

use Src\models\APIResponse;
use Src\routes\Router;

require_once __DIR__ . '/vendor/autoload.php';

header("Access-Control-Allow-Origin: https://costaexpress.fi");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Max-Age: -1");
header("Cache-Control: no-store");
header("Content-Security-Policy: frame-ancestors 'none'");
header("Content-Type: application/json; charset=UTF-8");
header("X-Content-Type-Options: nosniff");
header("X-Powered-By: Express");

try {
    DotEnv::load(__DIR__ . '/.env');
} catch (Error $error) {
    http_response_code(500);
    echo json_encode('Could not load environment variables.');
    die();
}

if (!(getenv('ENVIRONMENT') === 'development' || getenv('ENVIRONMENT') === 'staging')) {
    header("Strict-Transport-Security");
}

$router = new Router();

$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];
$params = !empty($_POST) ? $_POST : json_decode(file_get_contents('php://input'), true);

try {

    $response = $router->handleRequest($url, $method, $params);

} catch (Error $error) {

    http_response_code($error->getCode());

    if ($error->getMessage()) {
        echo json_encode($error->getMessage());
    }

    die();

}

if ($response instanceof APIResponse) {

    http_response_code($response->getStatusCode());
    echo json_encode($response->getMessage());

} else {

    http_response_code(500);
    echo json_encode('API response could not be interpreted');

}

die();