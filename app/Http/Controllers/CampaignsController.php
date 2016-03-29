<?php

namespace DataCollection\Http\Controllers;

use DataCollection\Campaign;
use DataCollection\Http\Requests\StoreCampaignRequest;
use DataCollection\Participant;
use DataCollection\Sensor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CampaignsController extends Controller
{

    public function index()
    {
        return Campaign::whereIsPrivate(false)->get();
    }


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
        $this->saveCampaign($request->all());
        return redirect('/');
    }

    public function show($id)
    {
        $campaign = Campaign::findOrFail($id);
        return view('campaign.show', compact('campaign'));
    }

    public function joinCampaign(Request $request)
    {
        $participant = Participant::firstOrCreate([
            'device_id' => $request->get('device_id'),
        ]);

        $participant->campaigns()->attach($request->get('campaign_id'));

        return response()->json(['message' => 'success'], 200);
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
