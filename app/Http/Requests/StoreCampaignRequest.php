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
            'snapshot_length' => 'required|numeric|min:1',
            'sample_duration' => 'required|numeric|min:1|lte:snapshot_length',
            'sample_frequency' => 'required|numeric|min:1|lte:sample_duration',
            'measurement_frequency' => 'required|numeric|min:1|lte:sample_frequency',
        ];
    }
}
