<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\VoteObjectiveRequest;
use App\VoteObjective;
use \Session;
use \Redirect;

use Illuminate\Http\Request;

class VoteObjectivesController extends Controller {

	/**
	 * Display a listing of all vote objectives
	 *
	 * @return Response
	 */
	public function index()
	{
        $voteObjectives = VoteObjective::all();     // Get all vote objectives to give to the list

		return view('voteobjectives.index', compact('voteObjectives'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        return view('voteobjectives.create');
	}

    /**
     * Store a newly created vote objective in storage.
     *
     * @param VoteObjectiveRequest $request
     * @return Response
     */
	public function store(VoteObjectiveRequest $request)
	{
		// Create the vote objective
        VoteObjective::create([
            'title' => $request->input('title'),
            'description' => $request->input('description')
        ]);

        // Redirect
        Session::flash('message', 'Vote objective created successfully!');
        return Redirect::to('voteobjectives');
	}

	/**
	 * Display the specified vote objetive
	 *
	 * @param  int  $id The id of the objective the view should show
	 * @return Response
	 */
	public function show($id)
	{
		$vo = VoteObjective::findOrFail($id);   // Find objective to show

        return view('voteobjectives.show', compact('vo'));
	}

	/**
	 * Show the form for editing the specified vote objective.
	 *
	 * @param  int  $id The id of the vote objective to edit
	 * @return Response
	 */
	public function edit($id)
	{
        $vo = VoteObjective::findOrFail($id);   // Find objective to show

        return view('voteobjectives.edit', compact('vo'));
	}

    /**
     * Update the specified vote objective in storage.
     *
     * @param VoteObjectiveRequest $request
     * @param  int $id The id of the vote objective to update
     * @return Response
     */
	public function update(VoteObjectiveRequest $request, $id)
	{
		// Find objective, update properties and save
        $vo = VoteObjective::findOrFail($id);
        $vo->title = $request->input('title');
        $vo->description = $request->input('description');
        $vo->save();

        // Redirect
        Session::flash('message', 'Vote objective updated successfully!');
        return Redirect::to('voteobjectives');
	}

	/**
	 * Remove the specified vote objective from storage.
	 *
	 * @param  int  $id The id of the objective to delete
	 * @return Response
	 */
	public function destroy($id)
	{
		// Find and delete objective
        $vo = VoteObjective::findOrFail($id);
        $vo->delete();

        // Redirect
        Session::flash('message', 'Vote objective deleted successfully!');
        return Redirect::to('voteobjectives');
	}

}
