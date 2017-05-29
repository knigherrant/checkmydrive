<?php

/**
 * @version     1.0.0
 * @package     checkmydrive
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Aloud Media Ltd <info@aloud.ie> - http://aloud.ie
 */


/**
 * Methods supporting a list of Checkmydrive records.
 */
class Jusers_model extends CI_Model {

    var $name;
    
    public function __construct() {
        parent::__construct();
	if (empty($this->name)) {
            $r = null;
            if (!preg_match('/(.*)\_model/i', get_class($this), $r))
            {
                    throw new Exception(Checkmydrive::_('JLIB_APPLICATION_ERROR_MODEL_GET_NAME'), 500);
            }
            $this->name = strtolower($r[1]);
        }
        $this->populateState();
    }
    
    public function populateState() {
        // Initialise variables.
        // Load the filter state.
        $input = Checkmydrive::input();
        $search = Checkmydrive::getStateFromRequest($this->name . '.filter.search', 'filter_search');
        $this->setState('filter.search', $search);

        $published = Checkmydrive::getStateFromRequest($this->name . '.filter.status', 'filter_status');
        $this->setState('filter.status', $published);

        $users = Checkmydrive::getStateFromRequest($this->name . '.filter.users', 'filter_users');
        $this->setState('filter.users', $users);
        
        $orderCol = Checkmydrive::getStateFromRequest($this->name . '.list.ordering', 'filter_order','a.id');
        $this->setState('list.ordering',$orderCol);
        
        $orderDirn = Checkmydrive::getStateFromRequest($this->name . '.list.direction', 'filter_order_Dir', 'desc');
        $this->setState('list.direction',$orderDirn);
        // Load the parameters.
        $params = Checkmydrive::getConfigs();
        $this->setState('list.start', isset(Checkmydrive::rsegments()[Checkmydrive::$pagination])? Checkmydrive::rsegments()[Checkmydrive::$pagination] : 0);
        $this->setState('list.limit',$params->limit);
        $this->setState('params', $params);
        // List state information.
    }
    
    
    
    public function setState($key, $value){
        Checkmydrive::setState($this->name . $key, $value);
    }


    public function getState($key, $default = ''){
        return Checkmydrive::getState($this->name . $key, $default);
    }
    
    
    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
    public function getListQuery() {
        // Create a new query object.
        $query = Checkmydrive::getDbo(true);

        // Select the required fields from the table.
        $query->select(
            $this->getState(
                'list.select', 'a.*'
            )
        );
        $query->from('`users` AS a');

       
        // Filter by published state
        $status = $this->getState('filter.status');
        if (is_numeric($status)) {
            $query->where('a.banned = '.(int) $status);
        } else if ($status === '') {
            $query->where('(a.banned IN (0, 1))');
        }

       // Filter by user type
        $type = $this->getState('filter.users');
        if (is_numeric($type)) {
            $query->where('a.user_level = '.(int) $type);
        } else if ($status === '') {
            $query->where('(a.user_level IN (0,1,2,3))');
        }

        
       
        // Filter by search in title
        $search = $this->getState('filter.search');
        if (!empty($search)) {
            if (stripos($search, 'id:') === 0) {
                $query->where('a.id = ' . (int) substr($search, 3));
            } else {
                $query->like('a.name',$search);
                $query->or_like('a.username',$search);
                $query->or_like('a.email',$search);
            }
        }
        // Add the list ordering clause.
        $orderCol = $this->getState('list.ordering');
        $orderDirn = $this->getState('list.direction');
        if ($orderCol && $orderDirn) {
            $query->order_by($orderCol,$orderDirn);
        }
        return  $query;
    }

    public function getTotal() {
        return $this->getListQuery()->get()->num_rows();
    }
    
    public function getItems() {
        $start = $this->getState('list.start', 0);
        $limit = $this->getState('list.limit', 20);
        $query = $this->getListQuery()->limit($limit, $start)->get();
        if($items =$query->result_object()){

        }
        return $items;
    }
    
    public function getItem($id = null) {
        if(!$id) $id = Checkmydrive::uri ()->id;
        if(!$id){
            $fields = Checkmydrive::getDbo(true)->list_fields('users');
            $item = new stdClass();
            foreach ($fields as $f) $item->$f = null;
            return $item;
        }
        $query = Checkmydrive::getDbo(true);
        $retult = $query->query('SELECT * FROM users WHERE id=' . (int)$id);
        if($item = $retult->row()){
            $item->password= null;
        }
        return $item;
    }
}
