<?php

namespace App\Livewire\Admin;

use Livewire\Component;

use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class ChatComponent extends Component
{
    public $selectedMemberId = null;
    public $selectedMemberName = null;
    public $messageInput = '';

    public function selectMember($memberId)
    {
        $member = User::find($memberId);
        if ($member) {
            $this->selectedMemberId = $member->id;
            $this->selectedMemberName = $member->name;
            
            // Mark all unread messages from this member as read
            Message::where('sender_id', $this->selectedMemberId)
                ->where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true]);

            $this->dispatch('scroll-chat-to-bottom');
        }
    }

    public function sendMessage()
    {
        $this->messageInput = trim($this->messageInput);
        if (empty($this->messageInput) || !$this->selectedMemberId) {
            return;
        }

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $this->selectedMemberId,
            'message' => $this->messageInput,
            'is_read' => false,
        ]);

        $this->messageInput = '';
        $this->dispatch('scroll-chat-to-bottom');
    }

    public function render()
    {
        $members = User::where('role', 'member')
            ->orderBy('name')
            ->get(['id', 'name']);

        $messages = [];
        if ($this->selectedMemberId) {
            $messages = Message::query()
                ->where(function ($query) {
                    $query->where('sender_id', Auth::id())
                          ->where('receiver_id', $this->selectedMemberId);
                })
                ->orWhere(function ($query) {
                    $query->where('sender_id', $this->selectedMemberId)
                          ->where('receiver_id', Auth::id());
                })
                ->orderBy('created_at', 'asc')
                ->get();

            // Mark new incoming messages as read during render
            Message::where('sender_id', $this->selectedMemberId)
                ->where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        // Count unread messages for each member
        $unreadCounts = Message::query()
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->selectRaw('sender_id, COUNT(*) as count')
            ->groupBy('sender_id')
            ->pluck('count', 'sender_id');

        return view('livewire.admin.chat-component', [
            'members' => $members,
            'messages' => $messages,
            'unreadCounts' => $unreadCounts,
        ]);
    }
}
