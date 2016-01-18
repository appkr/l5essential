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
        $params = config('project.params');
        $filters = implode(',', array_keys(config('project.filters.article')));

        return [
            $params['filter'] => "in:{$filters}",                      // Query scope filter
            $params['limit']  => 'size:1,10',                          // PerPage
            $params['sort']   => 'in:created_at,view_count,created',   // Sort: Age(created_at), View(view_count)
            $params['order']  => 'in:asc,desc',                        // Direction: Ascending or Descending
            $params['search'] => 'alpha_dash',                         // Search query
            $params['page']   => '',                                   // Page number
        ];
    }
}
