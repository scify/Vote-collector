<?php

namespace App\Http\Controllers;

use App\Group;
use App\Member;
use App\Http\Requests;
use App\Http\Requests\MemberRequest;
use \Redirect;
use \Session;


class MembersController extends Controller {

	/**
	 * Display a listing of all members.
	 *
	 * @return Response
	 */
	public function index()
	{
        $members = Member::all();   // get members for the list

        return view('members.index', compact('members'));
	}

	/**
	 * Show the form for creating a new member.
	 *
	 * @return Response
	 */
	public function create()
	{
        $groups = Group::lists('name', 'id');   // Get list of all groups and their ids for the form

		return view('members.create', compact('groups'));
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
		$member = Member::findOrFail($id);      // Find member
        $groups = Group::lists('name', 'id');   // Get list of all groups and their ids for the form

        return view('members.edit', compact('member', 'groups'));
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
        // Find and delete member
        $member = Member::findOrFail($id);
        $member->delete();

        // Redirect
        Session::flash('message', 'Ο/Η βουλευτής διαγράφηκε με επιτυχία!');
        return Redirect::to('members');
	}

    /**
     * Save a new member
     *
     * @param MemberRequest $request
     */
    private function createMember(MemberRequest $request)
    {
        // Make a new member and save it
        $member = new Member;
        $member->first_name = $request->input('first_name');
        $member->last_name = $request->input('last_name');
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
