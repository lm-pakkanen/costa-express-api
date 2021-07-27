<?PHP

namespace Src;

use Error;

use Src\models\APIResponse;
use Src\routes\Router;

require_once __DIR__ . '/vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$router = new Router();

$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];
$params = !empty($_POST) ? $_POST : json_decode(file_get_contents('php://input'), true);

$response = $router->handleRequest($url, $method, $params);

if ($response instanceof APIResponse) {

    http_response_code($response->getStatusCode());
    echo json_encode($response->getMessage());

    die();

} else if ($response instanceof Error) {

    http_response_code($response->getCode());

    if ($response->getMessage()) {
        echo json_encode($response->getMessage());
    }

    die();

} else {

    http_response_code(500);
    echo json_encode('API response could not be interpreted');

    die();

}