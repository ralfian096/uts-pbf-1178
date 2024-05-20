<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class BaseAPI extends Controller
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var array
     */
    protected $payload;

    /**
     * @var array
     */
    protected $payloadRules;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;

        $this->payload = $request->all();
    }

    /**
     * @return self
     */
    public function index($id = null, Request $request, Response $response)
    {
        $args = func_get_args();

        return $this->payloadValidation($this->payload ?? [], fn ($payload) => $this->main(...$args));
    }

    protected function payloadValidation(array $payload = [], callable $success)
    {
        if (count($this->payloadRules) <= 0 || count($payload) <= 0) {
            return $success($payload);
        }

        $validator = Validator::make($payload, $this->payloadRules);

        if ($validator->fails()) {
            return $this->errorResponse('invalid payload', $validator->errors(), 400);
        }

        return $success($payload);
    }

    /**
     * @return string
     */
    protected function errorResponse(string $message, $errors = [], int $statusCode = 400)
    {
        return response()->json([
            'code' => $statusCode,
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }

    /**
     * @return string
     */
    protected function successResponse(string $message, $data = [], int $statusCode = 200)
    {
        return response()->json([
            'code' => $statusCode,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }
}
