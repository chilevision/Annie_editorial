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
    public $userId;
    
    public $header      = 'settings.create-user';
    public $submit_btn  = 'settings.create';
    public $form_action = 'createUser';

    protected $listeners = ['editUser' => 'editUser'];
    

    public function render()
    {
        return view('livewire.user-modal');
    }

    public function createUser()
    {
        $validatedData = $this->validate();
        if ($this->admin == null) $this->admin = 0;
        User::create([
            'name'      => $validatedData['name'],
            'email'     => $validatedData['email'],
            'password'  => Hash::make($validatedData['password']),
            'admin'     => $this->admin
        ]);
        $this->resetModal();
    }

    public function updateUser()
    {
        if ($this->userId != ''){
            $rules = [
                'name'      => 'required|string|max:255|unique:users,name,'.$this->userId,
                'email'     => 'required|string|email|max:255|unique:users,email,'.$this->userId,
            ];
            $this->validate($rules);
            if ($this->password !=''){
                $rules = ['password' => 'confirmed|min:6'];
                $this->validate($rules);
                User::where('id', $this->userId)->update([
                    'name'      => $this->name,
                    'email'     => $this->email,
                    'password'  => Hash::make($this->password),
                    'admin'     => $this->admin
                ]);
            }
            else{
                User::where('id', $this->userId)->update([
                    'name'      => $this->name,
                    'email'     => $this->email,
                    'admin'     => $this->admin
                ]);
            }
        }
        $this->resetModal();
    }

    public function resetModal()
    {
        $this->reset('userId', 'name', 'email', 'password', 'password_confirmation', 'admin');
        $this->header      = 'settings.create-user';
        $this->submit_btn  = 'settings.create';
        $this->form_action = 'createUser';
        $this->resetErrorBag();
        $this->resetValidation();
        $this->emit('refresh_users');
    }

    public function editUser($id)
    {
        $user = User::where('id', $id)->first();
        $this->userId       = $id;
        $this->name         = $user->name;
        $this->email        = $user->email;
        $this->admin        = $user->admin;

        $this->header       = 'settings.edit-user';
        $this->submit_btn   = 'settings.update';
        $this->form_action  = 'updateUser';
    }
}
