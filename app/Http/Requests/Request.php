<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

abstract class Request extends FormRequest
{
    /**
     * Determine if the request is update
     *
     * @return bool
     */
    protected function isUpdate()
    {
        $needle = ['put', 'patch'];

        return in_array(strtolower($this->input('_method')), $needle)
            or in_array(strtolower($this->header('x-http-method-override')), $needle);
    }

    /**
     * Determine if the request is delete
     *
     * @return bool
     */
    protected function isDelete()
    {
        $needle = ['delete'];

        return in_array(strtolower($this->input('_method')), $needle)
            or in_array(strtolower($this->header('x-http-method-override')), $needle);
    }
}
