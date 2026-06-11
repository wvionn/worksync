<?php

namespace App\Livewire\Member;

use Livewire\Component;
use App\Models\Project;
use App\Models\GroupMessage;
use Illuminate\Support\Facades\Auth;

class GroupChatComponent extends Component
{
    public $selectedProjectId = null;
    public $selectedProjectName = null;
    public $messageInput = '';
    public $members = [];

    public function mount()
    {
        // Auto-select first project where member is assigned
        $firstProject = Project::whereHas('members', function ($query) {
            $query->where('users.id', Auth::id());
        })->with('members')->first();
        
        if ($firstProject) {
            $this->selectProject($firstProject->id);
        }
    }

    public function selectProject($projectId)
    {
        $project = Project::with('members')->find($projectId);
        
        // Check if user is member of this project
        if ($project && $project->members->contains(Auth::id())) {
            $this->selectedProjectId = $project->id;
            $this->selectedProjectName = $project->name;
            $this->members = $project->members;

            $this->dispatch('scroll-chat-to-bottom');
        }
    }

    public function sendMessage()
    {
        $this->messageInput = trim($this->messageInput);
        if (empty($this->messageInput) || !$this->selectedProjectId) {
            return;
        }

        GroupMessage::create([
            'project_id' => $this->selectedProjectId,
            'sender_id' => Auth::id(),
            'message' => $this->messageInput,
        ]);

        $this->messageInput = '';
        $this->dispatch('scroll-chat-to-bottom');
    }

    public function render()
    {
        // Get projects where current user is a member
        $projects = Project::whereHas('members', function ($query) {
            $query->where('users.id', Auth::id());
        })->withCount('members')
            ->with('members')
            ->orderBy('name')
            ->get();

        $messages = [];
        if ($this->selectedProjectId) {
            $messages = GroupMessage::with('sender')
                ->where('project_id', $this->selectedProjectId)
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return view('livewire.member.group-chat-component', [
            'projects' => $projects,
            'messages' => $messages,
        ]);
    }
}
