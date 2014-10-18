<?php

/**
 * @category   Netmouse
 * @package    Netmouse_Exchange1c
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software Licence 3.0 (OSL-3.0)
 * @author     Netmouse <1c@netmouse.com.ua>
 */
abstract class Netmouse_Exchange1c_Model_ORM_Model
{
    /**
     * Primary key.
     *
     * @var string $id
     */
    public $id;

    /**
     * Load model data form array.
     *
     * @param array $arr
     * @return void
     */
    public static function loadArray($arr)
    {
        $self = new static();
        
        foreach ($arr as $key => $val) {
            if (property_exists($self, $key)) {
                $self->{$key} = $val;
            }
        }
    }

    /**
     * Convert object to array.
     *
     * @param array $fields
     * @return array
     */
    public function toArray($fields = array())
    {
        $props = get_object_vars($this);
        
        if ( ! empty($fields)) {
            $result = array();
            foreach($props as $key => $val) {
                if (in_array($key, $fields)) {
                    $result[$key] = $val;
                }
            }

            return $result;
        }

        return $props;
    }

    /**
     * Get object value.
     *
     * @param string $key
     * @return mixed|null
     */
    public function get($key)
    {
        if (property_exists($this, $key)) {
            return $this->{$key};
        }

        return null;
    }

    /**
     * Set object value.
     *
     * @param string $key
     * @param mixed $val
     * @return bool
     */
    public function set($key, $val) 
    {
        if (property_exists($this, $key)) {
            $this->{$key} = $val;
            return true;
        }

        return false;
    }
}
