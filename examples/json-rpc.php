<?php

use Scn\Hydrator\Configuration\ExtractorConfigInterface;
use Scn\Hydrator\Configuration\HydratorConfigInterface;
use Scn\Hydrator\Hydrator;

require_once __DIR__.'/assets.php';

class JsonRpcRequest
{

    /**
     * @var string|int|null
     */
    private $id;

    /**
     * @var string
     */
    private $method;

    /**
     * @var \stdClass|null
     */
    private $params;

    /**
     * @var bool
     */
    private $isNotification = false;
}

class JsonRpcRequestHydratorConfig implements HydratorConfigInterface, ExtractorConfigInterface
{

    public function getHydratorProperties()
    {
        return [
            'id' => function ($value) {
                if (!is_string($value) && !is_int($value) && !is_null($value)) {
                    throw new InvalidArgumentException('Invalid request id');
                }

                $this->id = $value;
                $this->isNotification = $this->id === null;
            },
            'jsonrpc' => function ($value) {
                if ($value !== '2.0') {
                    throw new InvalidArgumentException('Invalid json-rpc version string');
                }
            },
            'method' => function ($value) {
                if ($value === '') {
                    throw new InvalidArgumentException('Invalid method');
                }

                $this->method = $value;
            },
            'params' => function ($value) {
                if (!is_array($value) && !is_object($value) && !is_null($value)) {
                    throw new InvalidArgumentException('Invalid params');
                }

                $this->params = $value;
            }
        ];
    }

    public function getExtractorProperties()
    {
        return [
            'id' => function () {
                return $this->id;
            },
            'jsonrpc' => function () {
                return '2.0';
            },
            'method' => function () {
                return $this->method;
            },
            'params' => function () {
                return $this->params;
            }
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
object(JsonRpcRequest)#4 (4) {
["id":"JsonRpcRequest":private]=>
  int(123)
  ["method":"JsonRpcRequest":private]=>
  string(3) "add"
["params":"JsonRpcRequest":private]=>
  array(2) {
    [0]=>
    int(4)
    [1]=>
    int(2)
  }
  ["isNotification":"JsonRpcRequest":private]=>
  bool(false)
}
string(56) "{"id":123,"jsonrpc":"2.0","method":"add","params":[4,2]}"
bool(true)
*/
