<?php

/**
 * @category   Netmouse
 * @package    Netmouse_Exchange1c
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software Licence 3.0 (OSL-3.0)
 * @author     Netmouse <1c@netmouse.com.ua>
 */
class Netmouse_Exchange1c_Model_CommerceML_PropertyCollection extends Netmouse_Exchange1c_Model_ORM_Collection
{
    /**
     * @param string $propId
     * @param string $valueId
     * @return null|string
     */
    public function getValue($propId, $valueId)
    {
        if (! is_null($prop = $this->get($propId))) {
            return $prop->getValue($valueId);
        }

        return null;
    }

    /**
     * @param string $propId
     * @return null|string
     */
    public function getName($propId)
    {
        if (! is_null($prop = $this->get($propId))) {
            return $prop->name;
        }

        return null;
    }
}
