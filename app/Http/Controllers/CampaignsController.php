<?php

namespace DataCollection\Http\Controllers;

use DataCollection\Campaign;
use DataCollection\Http\Requests\StoreCampaignRequest;
use DataCollection\Sensor;
use Illuminate\Http\Request;

class CampaignsController extends Controller
{
    /**
     * Gets the create view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('campaign.create');
    }

    /**
     * Stores a campaign and redirects to ???
     *
     * @param StoreCampaignRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCampaignRequest $request)
    {
        $campaign = $this->saveCampaign($request->all());
        return redirect('/');
    }

    public function show($id)
    {
        $campaign = Campaign::findOrFail($id);
        return view('campaign.show', compact('campaign'));
    }

    /**
     * Saves a campaign from an array of attributes
     *
     * @param array $attributes
     * @return Campaign
     */
    private function saveCampaign(array $attributes)
    {
        $campaign = Campaign::create($attributes);


        if (array_has($attributes, 'sensors')) {
            foreach ($attributes['sensors'] as $sensor) {
                $sensorObj = Sensor::firstOrCreate(['name' => $sensor]);
                $campaign->sensors()->attach($sensorObj->id);
            }
        }

        return $campaign;
    }
}
