<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $members = \App\Models\Member::latest()->get();
        return view('members.index', compact('members'));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:members',
            'phone' => 'nullable|string|max:20',
            'membership_date' => 'required|date',
        ]);

        Member::create($validated);

        return redirect()->route('members.index')->with('success', 'Member added successfully!');
    }

    public function show(Member $member)
    {
        $member->load('borrowRecords');
        return view('members.show', compact('member'));
    }

    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:members,email,' . $member->id,
            'phone' => 'nullable|string|max:20',
            'membership_date' => 'required|date',
        ]);

        $member->update($validated);

        return redirect()->route('members.index')->with('success', 'Member updated successfully!');
    }

    public function destroy(Member $member)
    {
        $member->delete();

        return redirect()->route('members.index')->with('success', 'Member deleted successfully!');
    }
}
