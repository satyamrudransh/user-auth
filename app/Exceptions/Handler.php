<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use League\Flysystem\FileNotFoundException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Log;
 
/*
* This is added for GuzzleHttp client exception handler and
* use api responser for error object response
*/
use App\Traits\ApiResponser;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\ConnectException;
class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
      if ($exception instanceof ClientException) {
        Log::debug("ClientException");
        $response = $exception->getResponse();
        $statusCode = $response->getStatusCode();

        $clientExceptionBody=$response->getBody()->getContents();
        $clientExceptionBody=json_decode($clientExceptionBody,true);
        // Check if 'errors' key exists
        $checkErrorMessageStatus = isset($clientExceptionBody['errors']) ? true : false;

        $message="Unknown error";
        if($checkErrorMessageStatus){
          $message=$clientExceptionBody['errors'];
        }else{
          $data['0']['code']='0014-0016';
          $data['0']['status']='503'; //same code as in responser()->json() method;
          $data['0']['title']='Unknown error';
          $data['0']['detail']='The request was not completed. Unknown error.';
          $message=$data;
        }
        return $this->errorResponse($message, $statusCode);
    }

      if ($exception instanceof ConnectException) {

        $data['0']['code']='0014-0002';
        $data['0']['status']='503'; //same code as in responser()->json() method;
        $data['0']['title']='Service Unavailable';
        $data['0']['detail']='The request was not completed. The server is temporarily overloading or down.';

        return $this->errorResponse($data, 503);
      }

      if ($exception instanceof NotFoundHttpException) {
        $data['0']['code']='0014-0003';
        $data['0']['status']='404'; //same code as in responser()->json() method;
        $data['0']['title']='Page not found';
        $data['0']['detail']='The page you are looking for is not found.';

        return $this->errorResponse($data, 404);
      }

      if ($exception instanceof MethodNotAllowedHttpException) {
        $data['0']['code']='0014-0004';
        $data['0']['status']='405'; //same code as in responser()->json() method;
        $data['0']['title']='Method Not Allowed';
        $data['0']['detail']='The method is not supported for the requested resource';

        return $this->errorResponse($data, 405);
      }

      if ($exception instanceof ValidationException) {
        if ($exception->validator->messages()->isEmpty()) {
          // Customize the response for ValidationException without specific error messages
          $data['0']['code'] = '0014-0005';
          $data['0']['status'] = '422';
          $data['0']['title'] = 'Invalid data';
          $data['0']['detail'] = 'The request was well-formed but was unable to be followed due to semantic errors';
      } else {
          // Get the validation error messages
          $errors = $exception->validator->errors()->messages();

          // Initialize error data array
          $data = [];

          // Loop through error messages and format them
          foreach ($errors as $field => $errorMessages) {
              foreach ($errorMessages as $errorMessage) {
                  $data[] = [
                      'code' => '0014-0005',
                      'status' => '422',
                      'title' => $errorMessage,
                      'detail' => $errorMessage
                  ];
              }
          }
      }

        // $data['0']['code']='0014-0005';
        // $data['0']['status']='422'; //  same code as in responser()->json() method;
        // $data['0']['title']='Invalid data';
        // $data['0']['detail']='The request was well-formed but was unable to be followed due to semantic errors';
        // return $this->errorResponse($data, 422);

      // Return a JSON response with the error data
      return response()->json(['errors' => $data], 422);
    }



      if ($exception instanceof FileNotFoundException) {

        $data['0']['code']='0014-0007';
        $data['0']['status']='503'; //same code as in responser()->json() method;
        $data['0']['title']='File not found';
        $data['0']['detail']='The request was resource not found';

        return $this->errorResponse($data, 503);
      }

      if ($exception instanceof ModelNotFoundException) {
 
        $data['0']['code']='0014-0008';
        $data['0']['status']='404'; 

        if($exception->getMessage()){
          
          $data['0']['title']=$exception->getMessage();
          $data['0']['detail']=$exception->getMessage();
        }
        else{
          $data['0']['code']='0014-0008';
          $data['0']['status']='404'; //same code as in responser()->json() method;
          $data['0']['title']='Not found: Record.';
          $data['0']['detail']='The record you are looking for cannot be found.';
   
        }
       
        return $this->errorResponse($data, 404);
      }
 
      // if ($exception instanceof AuthenticationException) {
      //   $data['0']['code']='0014-0009';
      //   $data['0']['status']='401'; //same code as in responser()->json() method;
      //   $data['0']['title']='Unauthorised request';
      //   $data['0']['detail']='These credentials do not match our records';
      //   return response()->json(['errors'=>$data], 401);
      // }

      if ($exception instanceof AuthorizationException)
      {
        $data['0']['code']='0014-0009';
        $data['0']['status']='401'; 

        if($exception->getMessage()){
          
          $data['0']['title']=$exception->getMessage();
          $data['0']['detail']=$exception->getMessage();
        }

        return $this->errorResponse($data, 401);
      }

      if ($exception instanceof PDOException) {
        $data['0']['code']='0002-0010';
        $data['0']['status']='503'; //same code as in responser()->json() method;
        $data['0']['title']='Service Unavailable';
        $data['0']['detail']='The request was not completed. The server is temporarily overloading or down.';

        return $this->errorResponse($data, 503);
      }

      if ($exception instanceof ServerException) {
        $data['0']['code']='0002-0011';
        $data['0']['status']='500'; //same code as in responser()->json() method;
        $data['0']['title']='Service Unavailable';
        $data['0']['detail']='The request was not completed. The server is temporarily overloading or down.';

        return $this->errorResponse($data, 503);
      }

        return parent::render($request, $exception);
    }
}