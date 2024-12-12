<?php

/*
 * This file is part of the IdeaToLife package.
 *
 * (c) Youssef Jradeh <youssef.jradeh@ideatolife.me>
 *
 */

namespace App\Idea\Types;

use App\Idea\Base\BaseResponse;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

/**
 * ExceptionType
 */
trait ExceptionType
{

    /**
     * Description: The following method is used to throw the respective validation exception
     *
     * @author Shuja Ahmed - I2L
     *
     * @param null $validator
     * @param string $message
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     * @throws \Illuminate\Validation\ValidationException
     */
    private function raiseValidationException($validator = null, $message = 'input_validation_error')
    {
        if (!$validator) {
            $this->raiseHttpResponseException($message);
        }


        $response = new BaseResponse();
        throw new ValidationException(
            $validator,
            $response->error($message, $this->getAllErrorMessages($validator->errors()->getMessages()))
        );
    }

    /**
     * Description: The following method is used to throw the respective response exception
     *
     * @author Youssef - I2L
     *
     * @param null $message
     * @param null $data
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    private function raiseHttpResponseException($message = null, $data = null)
    {
        $response = new BaseResponse();

        throw new HttpResponseException($response->failed($message, $data));
    }

    /**
     * Description: the following is used to raise invalid request type exception
     * @author Shuja Ahmed - I2L
     * @param null $message
     * @param null $data
     */
    private function raiseInvalidRequestException($message = null, $data = null)
    {
        $response = new BaseResponse();

        throw new HttpResponseException($response->failedWithErrors($message, $data));
    }


    /**
     * Description: The following method is used to throw the respective for Invalid JSON Request
     *
     * @author Youssef - I2L
     *
     * @param null $message
     * @param null $data
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    private function raiseInvalidJsonException($message = null, $data = null)
    {
        $response = new BaseResponse();

        throw new HttpResponseException($response->error($message, $data));
    }


    /**
     * Description: The following method is used to throw the respective for Invalid device Access
     *
     * @author Youssef - I2L
     *
     * @param null $message
     * @param null $data
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    private function raiseAuthorizationException($message = null, $data = null)
    {
        $response = new BaseResponse();

        throw new HttpResponseException($response->unauthorizedWithError($message, $data));
    }

    /**
     * Description: The following method is used to get all the input validation error messages in errors array
     * @author Shuja Ahmed - I2L
     * @param $errorsArray
     * @return array
     */
    private function getAllErrorMessages($errorsArray)
    {
        $response = [];
        foreach ($errorsArray as $errors) {
            foreach ($errors as $error) {
                array_push($response, $error);
            }
        }
        return $response;
    }
}
