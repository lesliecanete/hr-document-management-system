<?php

namespace App\View\Components;

use App\Models\Document;
use Carbon\Carbon;
use Illuminate\View\Component;

class DocumentStatusBadge extends Component
{
    public $document;
    public $showDays;
    public $compact;
    
    /**
     * Create a new component instance.
     */
    public function __construct(Document $document, bool $showDays = true, bool $compact = false)
    {
        $this->document = $document;
        $this->showDays = $showDays;
        $this->compact = $compact;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.document-status-badge');
    }
}