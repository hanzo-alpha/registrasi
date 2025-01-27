<?php

namespace App\Livewire\Frontend;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.frontend.home')->layout('components.layouts.unio');
    }
}
