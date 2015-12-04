<?php

namespace App\Http\Requests;

class FilterArticlesRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'f' => 'in:nocomment,notsolved',   // filter
            's' => 'in:created_at,view_count', // Sort: Age(created_at), View(view_count)
            'd' => 'in:asc,desc',              // Direction: Ascending or Descending
            'q' => 'alpha_dash',               // Search query
        ];
    }
}
