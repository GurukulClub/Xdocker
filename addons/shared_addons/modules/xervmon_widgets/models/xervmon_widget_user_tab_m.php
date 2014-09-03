<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @author  Xervmon Dev Team
 * @package Xervmon\Core\Modules\rackaccount\Models
 */

 class Xervmon_Widget_User_Tab_m extends MY_Model
{
	protected $_table = 'xervmon_widgets_user_tabs';

	public function get_all()
	{
		$this->db
			->select($this->_table.'.*')
			->select('users.username, profiles.display_name')
			->join('profiles', 'profiles.user_id = '.$this->_table.'.user_id', 'left')
			->join('users', $this->_table.'.user_id = users.id', 'left')
			->order_by('created_on', 'DESC');

		return $this->db->get($this->_table)->result();
	}

	public function get($id)
	{
		return $this->db
			->select($this->_table.'.*')
			->select('users.username, profiles.display_name')
			->join('profiles', 'profiles.user_id = '.$this->_table.'.user_id', 'left')
			->join('users', $this->_table.'.user_id = users.id', 'left')
			->where($this->_table.'.id', $id)
			->get($this->_table)
			->row();
	}

    public function get_by($key = null, $value = null)
    {
        $this->db
            ->select($this->_table .'.*, users.username, profiles.display_name')
            ->join('profiles', 'profiles.user_id = '.$this->_table.'.user_id', 'left')
            ->join('users', $this->_table.'.user_id = users.id', 'left');

        if (is_array($key))
        {
            $this->db->where($key);
        }
        else
        {
            $this->db->where($key, $value);
        }

        return $this->db->get($this->_table)->row();
    }

    public function get_multiple_by($key = null, $value = null)
    {
        $this->db
            ->select($this->_table .'.*, users.username, profiles.display_name')
            ->join('profiles', 'profiles.user_id = '.$this->_table.'.user_id', 'left')
            ->join('users', $this->_table.'.user_id = users.id', 'left');

        if (is_array($key))
        {
            $this->db->where($key);
        }
        else
        {
            $this->db->where($key, $value);
        }

        return $this->db->get($this->_table)->result();
    }

	public function get_many_by($params = array())
	{
		if ( ! empty($params))
		{
			$this->db
				->or_like($this->_table.'.title', trim($params))
				->or_like($this->_table.'.user_id', trim($params));
		}

		return $this->get_all();
	}



    public function insert($post, $skip_validation = false)
    {
        $post->created_on = now();
        $post->created_by = $this->current_user->id;
        $this->db->insert($this->_table, $post);
        return $this->db->insert_id();
    }

    public function update($id, $array, $skip_validation = false)
    {
        $array['updated_on'] = time();
        $array['updated_by'] = $this->current_user->id;
        return $this->db->where('id', $id)->update($this->_table, $array);
    }


	public function count_by($params = array())
	{
		return $this->db->count_all_results($this->_table);
	}


	public function check_exists($field, $value = '', $id = 0)
	{
		if (is_array($field))
		{
			$params = $field;
			$id = $value;
		}
		else
		{
			$params[$field] = $value;
		}
		$params['id !='] = (int)$id;

		return parent::count_by($params) == 0;
	}


    public function get_tabs()
    {
        return $this->get_multiple_by($this->_table.".user_id", $this->current_user->id);
    }

    public function add($tabName)
    {
        $data = new stdClass();
        $data -> title = $tabName;
        $data -> user_id = $this->current_user->id;
        return $this->insert($data);
    }

    public function delete($id)
    {
         return $this->db->where('id', $id)->delete($this->_table);
    }

}