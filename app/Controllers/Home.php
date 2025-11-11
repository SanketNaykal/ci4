<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('LoginPage');
    }
    public function login()
    {
        if ($this->request->getMethod() === 'post') {
            // Validate inputs
            $rules = [
                'name' => 'required|min_length[3]',
                'password' => 'required|min_length[4]'
            ];

            if ($this->validate($rules)) {
                $model = model('UserModel');
                $user = $model->where('username', $this->request->getPost('name'))->first();

                if ($user && password_verify($this->request->getPost('password'), $user['password'])) {
                    $session = session();
                    $session->set([
                        'user_id' => $user['id'],
                        'username' => $user['username'],
                        'logged_in' => true
                    ]);
                    return redirect()->to('/dashboard');
                }

                return redirect()->back()->with('error', 'Invalid login credentials');
            }
        }

        return view('LoginPage');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
