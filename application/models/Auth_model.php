<?php
class Auth_model extends CI_Model
{
    public function get_new_by_id($id)
    {
        if ($id == 0) {
            $query = $this->db->get('users');
            return $query->result_array();
        }

        $query = $this->db->get_where('users', array('id' => $id));
        return $query->row_array();
    }

    public function edit_profile($post)
    {
        $this->db->where('id', $post['user_id']);
        $this->db->update('users', array('firstname' => $post['firstname'], 'lastname' => $post['lastname']));
        return true;
    }
  
    public function check_login($post)
    {
        $this->db->select('*');
        $this->db->where('email', $post['email']);
        $this->db->where('password', $post['password']);
        $query = $this->db->get('users');
        $userInfo = $query->row();
        $count = $query->num_rows();

        if ($count == 1) {
            if (!md5($post['password'], $userInfo->password)) {
                error_log('Unsuccessful login attempt(' . $post['email'] . ')');
                return false;
            }
        } else {
            error_log('Unsuccessful login attempt(' . $post['email'] . ')');
            return false;
        }

        unset($userInfo->password);
        return $userInfo;
    }

    public function add_user($d)
    {
        $string = array(
            'firstname' => $d['firstname'],
            'lastname' => $d['lastname'],
            'email' => $d['email'],
            'password' => $d['password'],
            'role' => $d['role'],
            'status' => $d['status'],
            'banned_users' => $d['banned_users']
        );
        $q = $this->db->insert_string('users', $string);
        $this->db->query($q);
        return $this->db->insert_id();
    }

    public function is_duplicate($email)
    {
        $this->db->get_where('users', array('email' => $email), 1);
        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    public function logout()
    {
        $this->session->set_userdata(array('id' => '', 'firstname' => '', 'status' => ''));
        $this->session->sess_destroy();
        redirect('');
    }
}
