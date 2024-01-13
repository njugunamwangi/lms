<?php

namespace App\Livewire;

use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class Register extends Component implements HasForms, HasActions
{
    use InteractsWithForms;
    use InteractsWithActions;

    public function render()
    {
        return view('livewire.register');
    }

    public function registerSchool(): Action
    {
        return Action::make('registerSchool')
            ->form([
                TextInput::make('name'),
                TextInput::make('location'),
            ])
            ->action(function() {

            });
    }

    public function registerTutor(): Action
    {
        return Action::make('registerTutor')
            ->form([
                TextInput::make('name')
            ])
            ->action(function() {

            });
    }
}
