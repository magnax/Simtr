<?php defined('SYSPATH') or die('No direct script access.');

class OHM extends Model {
    
    //"database" object (RedisDB class)
    protected $_redis = null;
    
    protected $_config = array();

    //columns
    protected $_columns = array();
    
    //relationships
    protected $_belongs_to = array();
    protected $_has_many = array();

    //related objects (like event->params)
    protected $_related = array();

    protected $_object_name = null;
    
    protected $_object = null;

    protected $_loaded = false;
    
    //columns changed (not saved)
    protected $_changed = array();
    
    protected $_original_values = array();

    protected $_primary_key = 'id';
    protected $_primary_key_value = null;
    
    //object valid?
    protected $_valid = false;
    
    //object saved?
    protected $_saved = false;
    
    protected static $_init_cache = array();
            
    public function __construct($id = NULL) {
        
        $this->_initialize();        
        if ($id !== NULL) {
            $this->find($id);
        }
        
    }
    
    public function add($alias, $objects) {
        
        if (is_array($objects)) {
            $this->_related[$alias][] = $objects;
        } else {
            echo 'adding existed object';
        }
        
    }

    public function as_array() {
        
        return $this->_object;
        
    }

    public function create(Validation $validation = NULL) {
     
        $data = array();
        foreach ($this->_changed as $column) {
            // Generate list of column => values
            $data[$column] = $this->_object[$column];
        }
        
        $main_key = ucfirst($this->_object_name).':';
        
        $id = $this->_redis->incr($main_key.'ID');
        foreach ($this->_columns as $column) {
            $this->_redis->hset($main_key.$id, $column, isset($data[$column]) ? $data[$column] : null);
        }
        $this->_object[$this->_primary_key] = $this->_primary_key_value = $id;
        
        if (!empty($this->_related)) {
            //save related objects
            $foreign_key = $this->_object_name.'_id';

            foreach ($this->_related as $alias => $related) {
                
                $related_class_name = $this->_has_many[$alias]['model'];
                $related_class = 'Model_'.$related_class_name;
                foreach ($related as $related_object) {
                    
                    $related = new $related_class();
                    $related->values($related_object);
                    $related->{$foreign_key} = $id;
                    $related->save();
                    $this->_redis->sadd("{$related_class_name}:indices:{$foreign_key}:{$id}", $related->pk());
                    
                }
                
            }
        }
        
        // Object is now loaded and saved
        $this->_loaded = $this->_saved = TRUE;

        // All changes have been saved
        $this->_changed = array();
        $this->_original_values = $this->_object;

        return $this;
        
    }

    public function delete() {
        
        return $this->_redis->del(ucfirst($this->_object_name).":".$this->id);
        
    }
    
    protected function find($id) {
        
        if ($this->_loaded) {
            throw new Kohana_Exception('Method find() cannot be called on loaded objects');
        }
        
        $key = ucfirst($this->_object_name).':'.$id;
        
        if ($this->_redis->exists($key)) {
            
            $hash = $this->_redis->hkeys($key);
            $this->_object['id'] = $id;
            
            foreach ($hash as $k) {
                $this->_object[$k] = $this->_redis->hget($key, $k);
            }
            
            if (!empty($this->_belongs_to)) {
                //load related parent model
                foreach ($this->_belongs_to as $belongs_to) {
                    $class = 'Model_'.ucfirst($belongs_to);
                    $foreign_key = $belongs_to.'_id';
                    $this->_object[$belongs_to] = new $class($this->_object[$foreign_key]);
                }
            }
            
            $this->_loaded = $this->_valid = true;
            $this->_primary_key_value = $this->_object[$this->_primary_key];
            
        } else {
            $this->_loaded = false;
        }
        
    }
    
    public function __get($column) {
        return $this->get($column);
    }
    
    public function get($column) {
        
        if (array_key_exists($column, $this->_object)) {
            
            return $this->_object[$column];
            
        } elseif (isset($this->_has_many[$column])) {
            
            $class = 'Model_'.$this->_has_many[$column]['model'];
            $model = new $class();
            $col = strtolower($this->_object_name).'_id';
            $val = $this->pk();
            
            return $model->where($col, $val);
            
        } else {
            
            throw new Kohana_Exception(
                'The :property property does not exist in the :class class',
                array(
                    ':property' => $column, 
                    ':class' => get_class($this)
                )
            );
            
        }
        
    }
    
    protected function _initialize() {
        
        if (!$this->_object_name) {
            // Set the object name and plural name
            $this->_object_name = strtolower(substr(get_class($this), 6));
        }
    
        if ( ! $init = Arr::get(OHM::$_init_cache, $this->_object_name, FALSE)) {
            if ( ! is_object($this->_redis)) {
                // Get database instance
                $init['_redis'] = RedisDB::instance();
            }
        }
        
        // Assign initialized properties to the current object
        foreach ($init as $property => $value) {
            $this->{$property} = $value;
        }
        
        $this->_object['id'] = null;
        foreach ($this->_columns as $column) {
            $this->_object[$column] = null;
        }
        
        if (!empty($this->_has_many)) {
           
            foreach ($this->_has_many as $k => $v) {
                $this->_related[$k] = array();
            }
            
        }
        

    }
    
    public function loaded() {
        return $this->_loaded;
    }
    
    public function pk() {
        return $this->_primary_key_value;
    }

    public function __set($column, $value) {
        $this->set($column, $value);
    }

    public function set($column, $value) {
        
        if (array_key_exists($column, $this->_object)) {
            
            //see if the data really changed
            if ($value !== $this->_object[$column]) {
                
                $this->_object[$column] = $value;

                // Data has changed
                $this->_changed[$column] = $column;

                // Object is no longer saved or valid
                $this->_saved = $this->_valid = FALSE;
            }
            
        } else {
            
            throw new Kohana_Exception('The :property: property does not exist in the :class: class',
                array(':property:' => $column, ':class:' => get_class($this)));
            
        }
        
    }

    public function save(Validation $validation = NULL) {
        return $this->loaded() ? $this->update($validation) : $this->create($validation);
    }
    
    public function update(Validation $validation = NULL) {
        
        if ( ! $this->_loaded) {
            throw new Kohana_Exception('Cannot update :model model because it is not loaded.', 
                    array(':model' => $this->_object_name));
        }
        
        if (empty($this->_changed)) {
            // Nothing to update
            return $this;
        }

        // Use primary key value
        $id = $this->pk();
        
        //main key
        $main_key = ucfirst($this->_object_name).":$id";
        
        foreach ($this->_changed as $column) {
            $this->_redis->hset($main_key, $column, $this->_object[$column]);
        }

        // Object has been saved
        $this->_saved = TRUE;

        // All changes have been saved
        $this->_changed = array();
        $this->_original_values = $this->_object;

        return $this;
        
    }
    
    public function values(array $values, array $expected = NULL) {
        
        // Default to expecting everything except the primary key
        if ($expected === NULL) {
            
            $expected = $this->_columns;
            
            // Don't set the primary key by default
            unset($values[$this->_primary_key]);
        }

        foreach ($expected as $key) {

            // isset() fails when the value is NULL (we want it to pass)
            if (!array_key_exists($key, $values)) {
                continue;
            }

            // Try to set values to a related model
            $this->{$key} = $values[$key];
            
        }
    
        return $this;
        
    }
    
    public function where($column, $value) {
        
        $key = ucfirst($this->_object_name);
        $class = get_class($this);
        
        //get all members first
        $members = $this->_redis->smembers("{$key}:indices:{$column}:{$value}");

        $returned = array();
        foreach ($members as $id) {
            $returned[] = new $class($id);
        }
        return $returned;
        
    }
    
    public function clear() {
        
        // Create an array with all the columns set to NULL
        $values = array_combine(array_keys($this->_columns), array_fill(0, count($this->_columns), NULL));

        // Replace the object and reset the object status
        $this->_object = $this->_changed = $this->_original_values = array();

        // Replace the current object with an empty one
        $this->_load_values($values);

        // Reset primary key
        $this->_primary_key_value = NULL;

        // Reset the loaded state
        $this->_loaded = FALSE;

        return $this;
    }
    
    public function _load_values($values) {
        
        foreach ($values as $column => $value) {
            
            // Load the value to this model
            $this->_object[$column] = $value;

        }
        
    }
    
    
}

?>
