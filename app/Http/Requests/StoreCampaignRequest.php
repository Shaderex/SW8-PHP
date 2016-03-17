<?php

namespace DataCollection\Http\Requests;

use DataCollection\Http\Requests\Request;

class StoreCampaignRequest extends Request
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
            'name' => 'required',
            'snapshot_length' => 'numeric|min:1',
            'sample_duration' => 'numeric|min:1',
            'sample_frequency' => 'numeric|min:1',
            'measurement_frequency' => 'numeric|min:1',
        ];
    }
}
