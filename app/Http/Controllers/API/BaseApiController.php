<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use \Illuminate\Http\Response as Res;
use Response;
class BaseApiController extends Controller
{
        /**
     * @var int
     */
    protected $statusCode = Res::HTTP_OK;
    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }
    /**
     * @param $message
     * @return json response
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

        /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result, $message)
    {
    	$response = [
            'status'        => 'success_responsed',
            'status_code'   => Res::HTTP_CREATED,
            'message'       => $message,
            'data'          => $result,
        ];
        return response()->json($response, 200);
    }

    public function createResponse($result, $message)
    {
    	$response = [
            'status'        => 'success_created',
            'status_code'   => Res::HTTP_CREATED,
            'message'       => $message,
            'data'          => $result,
        ];
        return response()->json($response, 200);
    }
    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
    	$response = [
            'success' => false,
            'message' => $error,
        ];
        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }
        /**
     * @param Paginator $paginate
     * @param $data
     * @return mixed
     */
    protected function respondWithPagination(Paginator $paginate, $data, $message){
        $data = array_merge($data, [
            'paginator' => [
                'total_count'   => $paginate->total(),
                'total_pages'   => ceil($paginate->total() / $paginate->perPage()),
                'current_page'  => $paginate->currentPage(),
                'limit'         => $paginate->perPage(),
            ]
        ]);
        return $this->respond([
            'status'        => 'success',
            'status_code'   => Res::HTTP_OK,
            'message'       => $message,
            'data'          => $data
        ]);
    }
    public function respondNotFound($message = 'Not Found!'){
        return $this->respond([
            'status'        => 'error',
            'status_code'   => Res::HTTP_NOT_FOUND,
            'message'       => $message,
        ]);
    }
    public function respondInternalError($message){
        return $this->respond([
            'status'        => 'error',
            'status_code'   => Res::HTTP_INTERNAL_SERVER_ERROR,
            'message'       => $message,
        ]);
    }
    public function respondValidationError($message, $errors){
        return $this->respond([
            'status'        => 'error',
            'status_code'   => Res::HTTP_UNPROCESSABLE_ENTITY,
            'message'       => $message,
            'data'          => $errors
        ]);
    }
    public function respond($data, $headers = ['Accept: application/json']){
        return Response::json($data, $this->getStatusCode(), $headers);
    }
    public function respondWithError($message){
        return $this->respond([
            'status'        => 'error',
            'status_code'   => Res::HTTP_UNAUTHORIZED,
            'message'       => $message,
        ]);
    }
    public function customApiResponse($exception)
    {
        if (method_exists($exception, 'getStatusCode')) {
            $statusCode = $exception->getStatusCode();
        } else {
            $statusCode = 500;
        }

        $response = [];

        switch ($statusCode) {
            case 401:
                $response['message'] = 'Unauthorized';
                break;
            case 403:
                $response['message'] = 'Forbidden';
                break;
            case 404:
                $response['message'] = 'Not Found';
                break;
            case 405:
                $response['message'] = 'Method Not Allowed';
                break;
            case 422:
                $response['message'] = $exception->original['message'];
                $response['errors'] = $exception->original['errors'];
                break;
            default:
                $response['message'] = ($statusCode == 500) ? 'Whoops, looks like something went wrong' : $exception->getMessage();
                break;
        }

        if (config('app.debug')) {
            $response['trace'] = $exception->getTrace();
            $response['code'] = $exception->getCode();
        }

        $response['status'] = $statusCode;

        return response()->json($response, $statusCode);
    }
}
