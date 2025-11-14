<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('LoginPage');
    }
    public function dbtest(){
        $db = \Config\Database::connect();
        $output = '';
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

        return view('dbtestView', ['output' => $output]);
    }
}
// DELETE FROM users WHERE name = 'Sanket Naykal2';
//insert into users (name, email) values ('Sanket Naykal2','sanketnaykal@djhd.om');