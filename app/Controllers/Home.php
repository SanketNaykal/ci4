<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('SighupPage');
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
    public function signup(): string
    {
        return view('SignupPage'); // ensure Views/SignupPage.php exists (or change name to match your view)
    }
    public function dbtest(){
        $db = \Config\Database::connect();
        $output = '';
        $output2 = '';

        try {
            $query = $db->query('SELECT version() AS version;');
            $result = $query->getRow();
            $ver = is_object($result) && isset($result->version) ? $result->version : json_encode($result);
            $output .= "Connected to DB!<br>Version: " . $ver;
        } catch (\Exception $e) {
            return "❌ Database connection failed: " . $e->getMessage();
        }

        try {
            $query1 = $db->query('SELECT * FROM users;');
            $results = $query1->getResultArray();
            $output .= "<h2>Users Table Data:</h2><ul>";
            foreach ($results as $row) {
                $output .= "<li>" . implode(", ", $row) . "</li>";
            }
            $output .= "</ul>";
        } catch (\Exception $e) {
            $output .= "<br>❌ Query failed: " . $e->getMessage();
        }

        /* try{
            $insRefresh = $db->query("INSERT into users (name, email) values ('Sanket Naykal2','sanketnaykal@djhd.om');");
            $insRefreshResult = $insRefresh->get_result();
            $deleRefresh = $db->query("DELETE FROM users WHERE name = 'Sanket Naykal2';");
            $deleRefreshResult = $deleRefresh->get_result();
        }catch (\Exception $e){
            $output2 .= "<br>❌ Query failed: " . $e->getMessage();
        } */

        return view('DbTestView', ['output' => $output, 'ouput2' => $output2]);
    }
}
// DELETE FROM users WHERE name = 'Sanket Naykal2';
//insert into users (name, email) values ('Sanket Naykal2','sanketnaykal@djhd.om');