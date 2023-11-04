<?php

namespace App\Livewire;

use Livewire\Component;

class Wizzard extends Component
{
    public function render()
    {
        return view('livewire.wizzard')->extends('partials.layout');
    }

    public function submitForm()
    {
        return redirect()->route('test');
    }
}
