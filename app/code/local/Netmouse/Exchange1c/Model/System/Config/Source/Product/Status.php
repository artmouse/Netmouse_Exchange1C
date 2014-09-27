<?php

/**
 * @category   Netmouse
 * @package    Netmouse_Exchange1c
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software Licence 3.0 (OSL-3.0)
 * @author     Netmouse <1c@netmouse.com.ua>
 */

class Netmouse_Exchange1c_Model_System_Config_Source_Product_Status
{
    public static function toOptionArray()
    {
        $options = array(array('value' => '', 'label' => ''));
        foreach (Mage_Catalog_Model_Product_Status::getOptionArray() as $value => $label)
        {
            $options[] = array(
                'value' => $value,
                'label' => $label,
            );
        }
        return $options;
    }
}
