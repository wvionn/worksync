<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Project;
use App\Models\User;
use App\Models\GroupMessage;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class TeamChatComponent extends Component
{
    public $chatType = 'group'; // 'group' or 'individual'
    public $selectedId = null;
    public $selectedName = null;
    public $messageInput = '';
    public $members = [];

    public function mount()
    {
        // Auto-select first project for group chat
        $firstProject = Project::withCount('members')
            ->having('members_count', '>', 0)
            ->with('members')
            ->first();
        
        if ($firstProject) {
            $this->selectGroup($firstProject->id);
        }
    }

    public function selectGroup($projectId)
    {
        $project = Project::with('members')->find($projectId);
        
        if ($project) {
            $this->chatType = 'group';
            $this->selectedId = $project->id;
            $this->selectedName = $project->name;
            $this->members = $project->members;
            $this->dispatch('scroll-chat-to-bottom');
        }
    }

    public function selectMember($memberId)
    {
        $member = User::find($memberId);
        
        if ($member) {
            $this->chatType = 'individual';
            $this->selectedId = $member->id;
            $this->selectedName = $member->name;
            $this->members = collect();
            
            // Mark messages as read
            Message::where('sender_id', $this->selectedId)
                ->where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true]);
            
            $this->dispatch('scroll-chat-to-bottom');
        }
    }

    public function sendMessage()
    {
        $this->validate([
            'messageInput' => 'required|string|max:1000',
        ]);

        if (!$this->selectedId) {
            session()->flash('error', 'Please select a chat first.');
            return;
        }

        try {
            if ($this->chatType === 'group') {
                // Send group message
                GroupMessage::create([
                    'project_id' => $this->selectedId,
                    'sender_id' => Auth::id(),
                    'message' => $this->messageInput,
                ]);
            } else {
                // Send individual message
                Message::create([
                    'sender_id' => Auth::id(),
                    'receiver_id' => $this->selectedId,
                    'message' => $this->messageInput,
                    'is_read' => false,
                ]);
            }

            $this->messageInput = '';
            $this->dispatch('scroll-chat-to-bottom');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to send message: ' . $e->getMessage());
        }
    }

    public function render()
    {
        // Get all projects with members
        $projects = Project::withCount('members')
            ->having('members_count', '>', 0)
            ->with('members')
            ->orderBy('name')
            ->get();

        // Get all members (exclude self)
        $teamMembers = User::where('role', 'member')
            ->where('id', '!=', Auth::id())
            ->select('id', 'name', 'email', 'role', 'created_at')
            ->orderBy('name')
            ->get();

        $messages = [];
        if ($this->selectedId) {
            if ($this->chatType === 'group') {
                $messages = GroupMessage::with('sender')
                    ->where('project_id', $this->selectedId)
                    ->orderBy('created_at', 'asc')
                    ->get();
            } else {
                $messages = Message::with('sender', 'receiver')
                    ->where(function ($query) {
                        $query->where('sender_id', Auth::id())
                              ->where('receiver_id', $this->selectedId);
                    })
                    ->orWhere(function ($query) {
                        $query->where('sender_id', $this->selectedId)
                              ->where('receiver_id', Auth::id());
                    })
                    ->orderBy('created_at', 'asc')
                    ->get();
            }
        }

        // Count unread messages per member
        $unreadCounts = Message::query()
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->selectRaw('sender_id, COUNT(*) as count')
            ->groupBy('sender_id')
            ->pluck('count', 'sender_id');

        return view('livewire.admin.team-chat-component', [
            'projects' => $projects,
            'teamMembers' => $teamMembers,
            'messages' => $messages,
            'unreadCounts' => $unreadCounts,
        ]);
    }
}
