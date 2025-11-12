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
    public function dbtest(){
        $db = \Config\Database::connect();
        try {
            $query = $db->query('SELECT version() AS version;');
            $result = $query->getRow();
            return "✅ Connected to PostgreSQL!<br>Version: " . $result->version;
        } catch (\Exception $e) {
            return "❌ Database connection failed: " . $e->getMessage();
        }
        try {
            $query1 = $db->query('SELECT * FROM users;');
            $results = $query1->getResultArray();
            $output = "<h2>Users Table Data:</h2><ul>";
            foreach ($results as $row) {
                $output .= "<li>" . implode(", ", $row) . "</li>";
            }
            $output .= "</ul>";
            return $output;
        } catch (\Exception $e) {
            return "❌ Query failed: " . $e->getMessage();
        }
    }
}
