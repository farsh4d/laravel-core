<?php
/**
 * Written by Farshad Hassani
 */

namespace Modules\Core\Http\Traits;

use Symfony\Component\HttpFoundation\Response;


trait SimpleResponse
{
    public function ok($tag, $message = null, $data = [])
    {
        return $this->status(self::STATUS_OK)
            ->tag($tag)
            ->message($message)
            ->data($data)
            ->response()
        ;
    }

    public function notFound($tag, $message = null, $data = [])
    {
        return $this->status(self::STATUS_FAIL)
            ->tag($tag)
            ->message($message)
            ->response(Response::HTTP_NOT_FOUND)
        ;
    }

    public function bad($tag, $message = null, $data = [])
    {
        return $this->status(self::STATUS_OK)
            ->tag($tag)
            ->message($message)
            ->data($data)
            ->response(Response::HTTP_BAD_REQUEST)
        ;
    }

    public function unauthorized($tag, $message = null, $data = [])
    {
        return $this->status(self::STATUS_FAIL)
            ->tag($tag)
            ->message($message)
            ->data($data)
            ->response(Response::HTTP_UNAUTHORIZED)
        ;
    }

    public function noContent()
    {
        return $this->response(Response::HTTP_NO_CONTENT);
    }
}