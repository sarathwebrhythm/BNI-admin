<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Services\MemberService;
use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use Illuminate\Http\Request;
use App\Models\Member;

class MemberController extends Controller
{
    protected $memberService;

    public function __construct(MemberService $memberService)
    {
        $this->memberService = $memberService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $filters = [
            'status' => $request->input('status'),
            'chapter' => $request->input('chapter'),
        ];

        $members = $this->memberService->listMembers(15, $search, $filters);
        
        // Get list of chapters for filters dropdown
        $chapters = Member::whereNotNull('chapter')
            ->where('chapter', '!=', '')
            ->distinct()
            ->orderBy('chapter')
            ->pluck('chapter')
            ->toArray();

        return view('members.index', compact('members', 'chapters', 'search', 'filters'));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(StoreMemberRequest $request)
    {
        $this->memberService->createMember($request->validated());
        return redirect()->route('admin.members.index')->with('success', 'Member created successfully.');
    }

    public function show($id)
    {
        $member = $this->memberService->getMemberById($id);
        return view('members.show', compact('member'));
    }

    public function edit($id)
    {
        $member = $this->memberService->getMemberById($id);
        return view('members.edit', compact('member'));
    }

    public function update(UpdateMemberRequest $request, $id)
    {
        $this->memberService->updateMember($id, $request->validated());
        return redirect()->route('admin.members.index')->with('success', 'Member updated successfully.');
    }

    public function destroy($id)
    {
        $this->memberService->deleteMember($id);
        return redirect()->route('admin.members.index')->with('success', 'Member deleted successfully.');
    }
}