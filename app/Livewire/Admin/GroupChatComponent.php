<?php

namespace App\Livewire\Admin;

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

    public function selectProject($projectId)
    {
        $project = Project::with('members')->find($projectId);
        if ($project) {
            $this->selectedProjectId = $project->id;
            $this->selectedProjectName = $project->name;
            $this->members = $project->members;

            $this->dispatch('scroll-chat-to-bottom');
        }
    }

    public function sendMessage()
    {
        $this->validate([
            'messageInput' => 'required|string|max:1000',
        ]);

        if (!$this->selectedProjectId) {
            session()->flash('error', 'Please select a project first.');
            return;
        }

        try {
            GroupMessage::create([
                'project_id' => $this->selectedProjectId,
                'sender_id' => Auth::id(),
                'message' => $this->messageInput,
            ]);

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

        $messages = [];
        if ($this->selectedProjectId) {
            $messages = GroupMessage::with('sender')
                ->where('project_id', $this->selectedProjectId)
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return view('livewire.admin.group-chat-component', [
            'projects' => $projects,
            'messages' => $messages,
        ]);
    }
}
