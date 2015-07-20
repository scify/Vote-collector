<?php

namespace App\Http\Controllers;

use App\Group;
use App\Member;
use App\Http\Requests;
use App\Http\Requests\MemberRequest;
use App\Perifereia;
use \Redirect;
use \Response;
use \Session;
use \Input;


class MembersController extends Controller {

	/**
	 * Display a listing of all members.
	 *
	 * @return Response
	 */
	public function index()
	{
        $members = Member::orderBy('order', 'ASC')->get();  // get members for the list (sorted based on order)

        return view('members.index', compact('members'));
	}

	/**
	 * Show the form for creating a new member.
	 *
	 * @return Response
	 */
	public function create()
	{
        $groups = Group::lists('name', 'id');           // Get list of all groups and their ids for the form
        $perifereies = Perifereia::lists('name', 'id'); // Get list of all perifereies

		return view('members.create', compact('groups', 'perifereies'));
	}

    /**
     * Store a newly created member in storage.
     *
     * @param MemberRequest $request
     * @return Response
     */
	public function store(MemberRequest $request)
	{
        $this->createMember($request);

        // Redirect
        Session::flash('message', 'Ο/Η βουλευτής δημιουργήθηκε με επιτυχία!');
        return Redirect::to('members');
	}

	/**
	 * Display the specified member.
	 *
	 * @param  int  $id The id of the member to display
	 * @return Response
	 */
	public function show($id)
	{
		$member = Member::findOrFail($id);  // Find member
        $groups = $member->groups;          // Find groups that the member is in

        return view('members.show', compact('member', 'groups'));
	}

	/**
	 * Show the form for editing the specified member.
	 *
	 * @param  int  $id The id of the member to edit
	 * @return Response
	 */
	public function edit($id)
	{
		$member = Member::findOrFail($id);              // Find member
        $groups = Group::lists('name', 'id');           // Get list of all groups and their ids for the form
        $perifereies = Perifereia::lists('name', 'id'); // Get list of all perifereies

        return view('members.edit', compact('member', 'groups', 'perifereies'));
	}

    /**
     * Update the specified member in storage.
     *
     * @param MemberRequest $request
     * @param  int $id  The id of the member to update
     * @return Response
     */
	public function update(MemberRequest $request, $id)
	{
		// Find member, change first/last name & save
        $member = Member::findOrFail($id);
        $member->first_name = $request->input('first_name');
        $member->last_name = $request->input('last_name');
        $member->save();

        // Sync the selected groups in database
        $groups = $request->input('group_list');    // Get groups that the user selected
        if (count($groups) > 0) {
            $this->syncGroups($member, $groups);    // If there are any, sync them
        } else {
            $member->groups()->detach();            // If no groups are selected, remove all of them
        }

        // Redirect
        Session::flash('message', 'Ο/Η βουλευτής αποθηκεύτηκε με επιτυχία!');
        return Redirect::to('members');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id The id of the member to delete
	 * @return Response
	 */
	public function destroy($id)
	{
        // Find member
        $member = Member::findOrFail($id);

        // Find members of the same perifereia with order bigger than this member's and fix them
        $membersToFix = Member::where('perifereia', '=', $member->perifereia)
                            ->where('order', '>', $member->order)->get();
        foreach($membersToFix as $m) {
            $m->order = $m->order - 1;
            $m->save();
        }

        // Delete the member
        $member->delete();

        // Redirect
        Session::flash('message', 'Ο/Η βουλευτής διαγράφηκε με επιτυχία!');
        return Redirect::to('members');
	}

    /**
     * Get new order of members from post data and save it to database
     *
     * @return mixed
     */
    public function changeOrder()
    {   //todo: fix it for perifereies!!!!!
        // Get id/order pairs
        $newOrders = Input::get('data');

        // Change each member's order to the new one
        foreach($newOrders as $newOrder) {
            $member = Member::findOrFail($newOrder['id']);
            $member->order = $newOrder['order'];
            $member->save();
        }

        // Return success json
        return Response::json('success', 200);
    }

    /**
     * Save a new member
     *
     * @param MemberRequest $request
     */
    private function createMember(MemberRequest $request)
    {
        // Find order of the new member based on perifereia (to add the member at the end of their perifereia)
        $perifereia = Perifereia::findOrFail($request->input('perifereia'));
        $order = $perifereia->members->count() + 1;


        // Make a new member and save it
        $member = new Member;
        $member->first_name = $request->input('first_name');
        $member->last_name = $request->input('last_name');
        $member->perifereia = $request->input('perifereia');
        $member->order = $order;
        $member->save();

        // Get selected members and save them (if there are any)
        $groups = $request->input('group_list');
        if (count($groups) > 0) {
            $this->syncGroups($member, $groups);
        }
    }

    /**
     * Sync list of groups in the database
     *
     * @param Member $member    The member to sync groups with
     * @param array $groups     The groups that the member will be in
     */
    private function syncGroups(Member $member, array $groups)
    {
        $member->groups()->sync($groups);
    }
}
