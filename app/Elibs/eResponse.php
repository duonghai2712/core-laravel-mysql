<?php
namespace App\Elibs;

class eResponse
{
    public static function response($code, $message, $data = null)
    {
        $response['error_code'] = $code;
        $response['message'] = $message;
        $response['data'] = !empty($data) ? $data : [];
        if (config('app.debug')) {
            $debug = debug_backtrace();
            if (isset($debug[1])) {
                if (!isset($debug[1]['file'])) {
                    $debug[1] = $debug[0];
                }
                $file_name = pathinfo($debug[1]['file']);
                $response['DEBUG']['msg'] = 'CODE: #' . $file_name['filename'] . '@' . $debug[1]['line'];
                $request = [
                    '_POST'    => $_POST,
                    '_GET'     => $_GET,
                    '_FILES'   => $_FILES,
                ];
                $response['DEBUG']['request'] = $request;
            }
            unset($debug);
        }

        return response()->json($response);
    }

    public static function responsePagination($code, $message, $data = null)
    {
        $response['error_code'] = $code;
        $response['message'] = $message;
        $response['data'] = !empty($data) ? $data : ['data' => []];
        if (config('app.debug')) {
            $debug = debug_backtrace();
            if (isset($debug[1])) {
                if (!isset($debug[1]['file'])) {
                    $debug[1] = $debug[0];
                }
                $file_name = pathinfo($debug[1]['file']);
                $response['DEBUG']['msg'] = 'CODE: #' . $file_name['filename'] . '@' . $debug[1]['line'];
                $request = [
                    '_POST'    => $_POST,
                    '_GET'     => $_GET,
                    '_FILES'   => $_FILES,
                ];
                $response['DEBUG']['request'] = $request;
            }
            unset($debug);
        }

        return response()->json($response);
    }

    public static function responseError($code, $notification, $field)
    {
        $response['error_code'] = $code;
        $response['field']  = $field;
        $response['notification'] = $notification;
        if (config('app.debug')) {
            $debug = debug_backtrace();
            if (isset($debug[1])) {
                if (!isset($debug[1]['file'])) {
                    $debug[1] = $debug[0];
                }
                $file_name = pathinfo($debug[1]['file']);
                $response['DEBUG']['msg'] = 'CODE: #' . $file_name['filename'] . '@' . $debug[1]['line'];
                $request = [
                    '_POST'    => $_POST,
                    '_GET'     => $_GET,
                    '_FILES'   => $_FILES,
                ];
                $response['DEBUG']['request'] = $request;
            }
            unset($debug);
        }

        return response()->json($response);
    }
}

?>
