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
            'campaign_length' => 'required|numeric|min:1',
            'samples_per_snapshot' => 'required|numeric|min:1',
            'sample_delay' => 'required|numeric|min:1',
            'measurement_per_sample' => 'required|numeric|min:1',
            'measurement_frequency' => 'required|numeric|min:1',
        ];
    }
}
