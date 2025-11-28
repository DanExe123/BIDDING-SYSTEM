<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AnnouncementsModal;

class AnnouncementPage extends Component
{
    public string $title = '';
    public string $description = '';
    public $date = null;

    public $editId = null; // null = create, holds ID when editing

    // Validation rules
    protected $rules = [
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'date' => 'required|date',
    ];

    public function save()
    {
        $this->validate();

        // Create announcement
        AnnouncementsModal::create([
            'title' => $this->title,
            'description' => $this->description,
            'date' => $this->date,
        ]);

        // Reset form fields (optional)
        $this->reset(['title', 'description', 'date']);

        // Redirect to superadmin dashboard
        return redirect()->route('superadmin-dashboard');
    }

    // ------------------ Edit Functionality ------------------
    public function editAnnouncement($id)
    {
        $announcement = AnnouncementsModal::find($id);
        if ($announcement) {
            $this->editId = $id;
            $this->title = $announcement->title;
            $this->description = $announcement->description;
            $this->date = $announcement->date;
        }
    }

    public function updateAnnouncement()
    {
        $this->validate();

        if ($this->editId) {
            $announcement = AnnouncementsModal::find($this->editId);
            $announcement->update([
                'title' => $this->title,
                'description' => $this->description,
                'date' => $this->date,
            ]);

            // Reset form fields
            $this->reset(['title', 'description', 'date', 'editId']);
            
            session()->flash('message', 'Announcement updated successfully!');
            return redirect()->route('superadmin-dashboard');
        }
    }

            public function cancelEdit()
            {
                $this->reset(['title', 'description', 'date', 'editId']);
                return redirect()->route('superadmin-dashboard');
            }

            public function mount($editId = null)
        {
            if ($editId) {
                $this->editAnnouncement($editId);
            }
        }

    // ------------------ Render ------------------
    public function render()
    {
        return view('livewire.announcement-page', [
            'announcements' => AnnouncementsModal::latest()->get(),
        ]);
    }
}
