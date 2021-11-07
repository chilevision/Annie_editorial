<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Usermodal extends Component
{
    protected $rules = [
        'name'          => 'required|string|max:255|unique:users',
        'email'         => 'required|string|email|max:255|unique:users',
        'password'      => 'confirmed|min:6'
    ];
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $admin;
    
    public $header      = 'settings.create-user';
    public $submit_btn  = 'settings.create';
    public $form_action = 'createUser';
    

    public function render()
    {
        return view('livewire.user-modal');
    }

    public function createUser()
    {
        $validatedData = $this->validate();
        User::create([
            'name'      => $validatedData['name'],
            'email'     => $validatedData['email'],
            'password'  => Hash::make($validatedData['password']),
            'admin'     => $this->admin
        ]);
        $this->reset('name', 'email', 'password', 'password_confirmation', 'admin');
        $this->emit('refresh_users');
    }

    public function resetModal()
    {
        $this->reset('name', 'email', 'password', 'password_confirmation', 'admin');
    }
    public function testa()
    {
        $this->emit('refresh_users');
    }
}
