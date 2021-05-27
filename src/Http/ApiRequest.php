<?php

namespace Modules\Core\Http;


use Illuminate\Http\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\Finder\Exception\AccessDeniedException;

/**
 * Class ApiRequest
 * 
 * @package Modules\Backend\Http\Requests\Api
 */
abstract class ApiRequest extends FormRequest
{
    /**
     * @param Validator $validator
     *
     * @return void
     * @throws ValidationException
     */
    public function failedValidation(Validator $validator)
    {
        throw (new ValidationException($validator, $this->response(
            $this->formatErrors($validator)
        )));
    }


    /**
     * Format the errors from the given Validator instance.
     *
     * @param  \Illuminate\Contracts\Validation\Validator $validator
     *
     * @return array
     */
    protected function formatErrors(Validator $validator)
    {
        return $validator->getMessageBag()->toArray();
    }


    /**
     * @param array $errors
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function response(array $errors)
    {
        $response = new ApiResponse();

        return $response
            ->status($response::STATUS_ERROR)
            ->tag('badRequest')
            ->message('tr:core::messages.bad_request')
            ->data($errors)
            ->response(Response::HTTP_BAD_REQUEST)
        ;
    }


    /**
     * @throws AccessDeniedException
     */
    public function failedAuthorization()
    {
        throw new AccessDeniedException('AccessDenied');
    }
}
