<?php

namespace App\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

class MailJsonResponse
{
    /**
     * @param string $msg
     *
     * @return JsonResponse
     */
    public function success(string $msg)
    {
        $response['status'] = true;
        $response['msg']    = $msg;
        
        return new JsonResponse($response);
    }
    
    /**
     * @param array $errors
     *
     * @return JsonResponse
     */
    public function fail($errors)
    {
        $response['status'] = false;
        $response['errors'] = $errors;
        return new JsonResponse($response);
    }
}