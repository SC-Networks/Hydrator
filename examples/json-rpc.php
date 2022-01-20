<?php

declare(strict_types=1);

use Scn\Hydrator\Configuration\ExtractorConfigInterface;
use Scn\Hydrator\Configuration\HydratorConfigInterface;
use Scn\Hydrator\Hydrator;

require_once __DIR__.'/assets.php';

class JsonRpcRequest
{
    private null|int|string $id;

    private string $method;

    private null|array|stdClass $params;

    private bool $isNotification = false;
}

class JsonRpcRequestHydratorConfig implements HydratorConfigInterface, ExtractorConfigInterface
{

    public function getHydratorProperties(): array
    {
        return [
            'id' => function ($value): void {
                if (!is_string($value) && !is_int($value) && !is_null($value)) {
                    throw new InvalidArgumentException('Invalid request id');
                }

                $this->id = $value;
                $this->isNotification = $this->id === null;
            },
            'jsonrpc' => function (string $value): void {
                if ($value !== '2.0') {
                    throw new InvalidArgumentException('Invalid json-rpc version string');
                }
            },
            'method' => function (string $value): void {
                if ($value === '') {
                    throw new InvalidArgumentException('Invalid method');
                }

                $this->method = $value;
            },
            'params' => function ($value): void {
                if (!is_array($value) && !is_object($value) && !is_null($value)) {
                    throw new InvalidArgumentException('Invalid params');
                }

                $this->params = $value;
            }
        ];
    }

    public function getExtractorProperties(): array
    {
        return [
            'id' => fn (): null|int|string => $this->id,
            'jsonrpc' => fn (): string => '2.0',
            'method' => fn (): string => $this->method,
            'params' => fn (): null|array|stdClass => $this->params,
        ];
    }
}


$request = '{"id":123,"jsonrpc":"2.0","method":"add","params":[4,2]}';

$config = new JsonRpcRequestHydratorConfig();
$hydrator = new Hydrator();
$requestObject = new JsonRpcRequest();

$hydrator->hydrate($config, $requestObject, json_decode($request, true));

$extractionResult = json_encode($hydrator->extract($config, $requestObject));

var_dump(
    $requestObject,
    $extractionResult,
    assert($request === $extractionResult)
);

/*
class JsonRpcRequest#4 (4) {
  private string|int|null $id =>
  int(123)
  private string $method =>
  string(3) "add"
  private stdClass|array|null $params =>
  array(2) {
    [0] =>
    int(4)
    [1] =>
    int(2)
  }
  private bool $isNotification =>
  bool(false)
}
/home/awuehr/dev/Hydrator/examples/json-rpc.php:83:
string(56) "{"id":123,"jsonrpc":"2.0","method":"add","params":[4,2]}"
/home/awuehr/dev/Hydrator/examples/json-rpc.php:83:
bool(true)
*/