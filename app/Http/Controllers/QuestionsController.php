<?php

namespace DataCollection\Http\Controllers;

use DataCollection\Campaign;
use DataCollection\Question;
use Illuminate\Http\Request;

use DataCollection\Http\Requests;

class QuestionsController extends Controller
{
    public function add($id)
    {
        $campaign = Campaign::findOrFail($id);

        return view('question.add', compact('campaign'));
    }

    public function store($id, Request $request)
    {
        $campaign = Campaign::findOrFail($id);
        $question = new Question();

        $question->fill($request->all());

        $campaign->questions()->save($question);

        return redirect("/campaigns/{$id}");
    }
}
