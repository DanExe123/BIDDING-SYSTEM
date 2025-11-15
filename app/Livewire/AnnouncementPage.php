<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\AnnouncementsModal;

class AnnouncementPage extends Component
{
    public string $title = '';
    public string $description = '';
    public $date = null;

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

    public function render()
    {
        return view('livewire.announcement-page');
    }
}
