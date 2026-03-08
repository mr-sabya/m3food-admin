<?php

namespace App\Livewire\Page;

use App\Models\Page;
use Livewire\Component;

class Show extends Component
{
    public $page;

    public function mount($pageId)
    {
        // Fetch the page with all design relations
        // Only show if status is published
        $this->page = Page::with(['theme', 'header', 'footer'])
            ->where('id', $pageId)
            ->where('status', 'published')
            ->firstOrFail();
    }

    public function render()
    {
        // Passes the page to the layout for dynamic SEO (Meta tags)
        return view('livewire.page-show', [
            'page' => $this->page
        ]);
    }
}
