<?php

namespace DataCollection\Http\Controllers;

use DataCollection\Campaign;
use DataCollection\Http\Requests\AddQuestionRequest;
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

    public function store($id, AddQuestionRequest $request)
    {
        $campaign = Campaign::findOrFail($id);

        $question = new Question();
        $question->fill($request->all());

        $campaign->questions()->save($question);

        return redirect("/campaigns/{$id}");
    }

    public function changeOrder($id, Request $request)
    {
        $orderedIds = $request->get('order');

        foreach ($orderedIds as $order => $value) {
            $question = Question::find($value);
            $question->order = $order;

            $question->save();
        }

        return redirect("/campaigns/{$id}");
    }
}
