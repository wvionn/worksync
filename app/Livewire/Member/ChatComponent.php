<?php

namespace App\Livewire\Member;

use Livewire\Component;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class ChatComponent extends Component
{
    public $selectedAdminId = null;
    public $selectedAdminName = null;
    public $messageInput = '';

    public function mount()
    {
        // Auto-select admin "ais" when component loads
        $admin = User::where('role', 'admin')
            ->where('name', 'like', '%ais%')
            ->first();
        
        if ($admin) {
            $this->selectAdmin($admin->id);
        } else {
            // Fallback: select first admin if "ais" not found
            $firstAdmin = User::where('role', 'admin')->first();
            if ($firstAdmin) {
                $this->selectAdmin($firstAdmin->id);
            }
        }
    }

    public function selectAdmin($adminId)
    {
        $admin = User::find($adminId);
        if ($admin) {
            $this->selectedAdminId = $admin->id;
            $this->selectedAdminName = $admin->name;
            
            // Mark all unread messages from this admin as read
            Message::where('sender_id', $this->selectedAdminId)
                ->where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true]);

            $this->dispatch('scroll-chat-to-bottom');
        }
    }

    public function sendMessage()
    {
        $this->messageInput = trim($this->messageInput);
        if (empty($this->messageInput) || !$this->selectedAdminId) {
            return;
        }

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $this->selectedAdminId,
            'message' => $this->messageInput,
            'is_read' => false,
        ]);

        $this->messageInput = '';
        $this->dispatch('scroll-chat-to-bottom');
    }

    public function render()
    {
        // Get all admins to chat with
        $admins = User::where('role', 'admin')
            ->orderBy('name')
            ->get(['id', 'name']);

        $messages = [];
        if ($this->selectedAdminId) {
            $messages = Message::query()
                ->where(function ($query) {
                    $query->where('sender_id', Auth::id())
                          ->where('receiver_id', $this->selectedAdminId);
                })
                ->orWhere(function ($query) {
                    $query->where('sender_id', $this->selectedAdminId)
                          ->where('receiver_id', Auth::id());
                })
                ->orderBy('created_at', 'asc')
                ->get();

            // Mark new incoming messages as read during render
            Message::where('sender_id', $this->selectedAdminId)
                ->where('receiver_id', Auth::id())
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        // Count unread messages for each admin
        $unreadCounts = Message::query()
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->selectRaw('sender_id, COUNT(*) as count')
            ->groupBy('sender_id')
            ->pluck('count', 'sender_id');

        return view('livewire.member.chat-component', [
            'admins' => $admins,
            'messages' => $messages,
            'unreadCounts' => $unreadCounts,
        ]);
    }
}
