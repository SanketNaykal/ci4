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

        $rules = [
            'name'            => 'required|min_length[3]',
            'email'           => 'required|valid_email',
            'password'        => 'required|min_length[6]',
            'passwordcom'     => 'required|matches[password]',
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
            // Query user from DB
            $query = $db->query('SELECT name, email FROM users WHERE email = ?', [$email]);
            $user = $query->getRow();

            if (! $user || ($password!=$passwordconfirm)) {
                return redirect()->back()->withInput()->with('error', 'Invalid login credentials');
            }

            // Set session
            /* session()->set([
                'isLoggedIn' => true,
                'user_id'    => $user->id,
                'username'   => $user->name,
            ]); */
            $query1 = $db->query('INSERT INTO users (name, email, password) VALUES (?, ?, ?)', [$username, $email, password_hash($password, PASSWORD_BCRYPT)]);

            return redirect()->to('/dashboard');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Database error: ' . $e->getMessage());
        }
    }
}