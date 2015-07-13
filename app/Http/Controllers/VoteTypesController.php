<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\VoteType;
use App\Http\Requests\VoteTypeRequest;
use App\VoteTypeAnswer;
use \Redirect;
use \Session;

use Illuminate\Http\Request;

/**
 * Class VoteTypesController
 * @package App\Http\Controllers
 */
class VoteTypesController extends Controller {

	/**
	 * Display a listing of the vote types
	 *
	 * @return Response
	 */
	public function index()
	{
        $voteTypes = VoteType::all();   // Get vote types for the list

		return view('votetypes.index', compact('voteTypes'));
	}

	/**
	 * Show the form for creating a new vote type
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('votetypes.create');
	}

	/**
	 * Store a newly created vote type in storage.
	 *
	 * @return Response
	 */
	public function store(VoteTypeRequest $request)
	{
        $this->createVoteType($request);

        // Redirect
        Session::flash('message', 'Ο τύπος ψηφοφορίας δημιουργήθηκε με επιτυχία!');
        return Redirect::to('/votetypes');
	}

    /**
     * Save a new vote type and its answers to the database
     *
     * @param VoteTypeRequest $request
     */
    public function createVoteType(VoteTypeRequest $request) {
        // Make new vote type and save it
        $vt = new VoteType;
        $vt->title = $request->input('title');
        $vt->save();

        // Save the answers of the vote type
        foreach($request->input('answers') as $answer) {
            $this->saveVoteTypeAnswer($answer, $vt->id);
        }
    }

	/**
	 * Display the specified vote type
	 *
	 * @param  int  $id The id of the vote type to display
	 * @return Response
	 */
	public function show($id)
	{
		$vt = VoteType::findOrFail($id);    // Find the vote type to display

        return view('votetypes.show', compact('vt'));
	}

	/**
	 * Show the form for editing the specified vote type
	 *
	 * @param  int  $id The id of the vote type to edit
	 * @return Response
	 */
	public function edit($id)
	{
		// Find vote type
        $votetype = VoteType::findOrFail($id);

        // Get answers of this vote type to show in the form
        $answers = VoteTypeAnswer::where('type', '=', $id)->get()->toArray();

        return view('votetypes.edit', compact('votetype', 'answers'));
	}

    /**
     * Update the specified vote type in storage.
     *
     * @param VoteTypeRequest $request
     * @param  int $id The id of the vote type to update
     * @return Response
     */
	public function update(VoteTypeRequest $request, $id)
	{
        // Find vote type, and update the title
        $vt = VoteType::findOrFail($id);
        $vt->title = $request->input('title');
        $vt->save();

        // Get previous answers of this vote type from the database, and delete them
        $prevAnswers = VoteTypeAnswer::where('type', '=', $vt->id)->get();
        foreach($prevAnswers as $answer) {
            $answer->delete();
        }

        // Get new answers of this vote type from the form, and save them to the database
        $answers = $request->input('answers');
        foreach($answers as $answer) {
            $this->saveVoteTypeAnswer($answer, $vt->id);
        }

        // Redirect
        Session::flash('message', 'Ο τύπος ψηφοφορίας αποθηκεύτηκε με επιτυχία!');
        return Redirect::to('votetypes');
	}

	/**
	 * Remove the specified vote type from storage.
	 *
	 * @param  int  $id The id of the vote type to delete
	 * @return Response
	 */
	public function destroy($id)
	{
        // Find and delete vote type
        $vt = VoteType::findOrFail($id);
        $vt->delete();

        // Redirect
        Session::flash('message', 'Ο τύπος ψηφοφορίας διαγράφηκε με επιτυχία!');
        return Redirect::to('votetypes');
	}

    /**
     * Saves an answer to the database, for the specified vote type
     *
     * @param $answer   The answer to save to database
     * @param $vtid     ID of the vote type that this answer belongs to
     */
    public function saveVoteTypeAnswer($answer, $vtid)
    {
        if ($answer != '') {
            VoteTypeAnswer::create([
                'type' => $vtid,
                'answer' => $answer
            ]);
        }
    }

}
