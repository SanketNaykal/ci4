<?php

namespace App\Controllers;
use App\Controllers\BaseController;

class Auth extends BaseController
{
    // Show login form
    public function login(): string
    {
        return view('LoginPage');
    }

    // Handle login POST
    public function attemptLogin()
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/login');
        }

        $rules = [
            'name'     => 'required|min_length[3]',
            'password' => 'required|min_length[4]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $username = $this->request->getPost('name');
        $email = $this->request->getPost('name');
        $password = $this->request->getPost('password');

        try {
            // Query user from DB
            $query = $db->query('SELECT id, name, password FROM users WHERE email = ?', [$email]);
            $user = $query->getRow();

            if (! $user || ! password_verify($password, $user->password)) {
                return redirect()->back()->withInput()->with('error', 'Invalid login credentials');
            }

            // Set session
            session()->set([
                'isLoggedIn' => true,
                'user_id'    => $user->id,
                'username'   => $user->name,
            ]);

            return redirect()->to('/dashboard');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
        }
    }

    // Logout
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    // Show signup form
    public function signup(): string
    {
        return view('SignupPage');
    }

    // Handle signup POST
    public function register()
    {
        if ($this->request->getMethod() !== 'post') {
            return redirect()->to('/signup');
        }

        $rules = [
            'name'            => 'required|min_length[3]',
            'email'           => 'required|valid_email',
            'password'        => 'required|min_length[6]',
            'password_confirm'=> 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $db = \Config\Database::connect();
        $username = $this->request->getPost('name');
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $passwordconfirm = $this->request->getPost('passwordcom');

        console.log($db, $username, $email, $password, $passwordconfirm);
        try {
            $builder = $db->table('users');

            // Check username
            $exists = $builder->where('name', $username)->get()->getRow();
            if ($exists) {
                return redirect()->back()->withInput()->with('error', 'Username already taken');
            }

            // Check email (use fresh builder)
            $builder = $db->table('users');
            $exists = $builder->where('email', $email)->get()->getRow();
            if ($exists) {
                return redirect()->back()->withInput()->with('error', 'Email already registered');
            }

            // Insert user inside transaction
            $db->transStart();

            $data = [
                'name'   => $username,
                'email'      => $email,
                'password'   => password_hash($password, PASSWORD_DEFAULT),
            ];

            $db->table('users')->insert($data);

            $db->transComplete();

            if (! $db->transStatus()) {
                return redirect()->back()->withInput()->with('error', 'Failed to create account. Try again.');
            }

            return redirect()->to('/login')->with('message', 'Account created successfully');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Database error: ' . $e->getMessage());
        }
    }
}