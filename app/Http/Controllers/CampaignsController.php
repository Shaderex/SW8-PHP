<?php

namespace DataCollection\Http\Controllers;

use DataCollection\Campaign;
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
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $campaign = $this->saveCampaign($request->all());


        return redirect('/')->with(compact('campaign'));
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

        foreach ($attributes->get('sensors') as $sensor) {
            
        }

        return $campaign;
    }
}
