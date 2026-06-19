<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        if ($request->filled('search')) {
            $search = $request->input('search');
            $cleanSearch = str_replace(['-', ' '], '', $search);
            if (preg_match('/^(?:\d{9}[\dX]|\d{13})$/i', $cleanSearch) || (ctype_digit($cleanSearch) && strlen($cleanSearch) >= 8)) {
                return redirect()->route('books.index', ['search' => $search]);
            }
        }

        $query = \App\Models\Member::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
        }

        $members = $query->latest()->get();
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
