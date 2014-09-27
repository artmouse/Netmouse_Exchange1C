<?php

/**
 * @category   Netmouse
 * @package    Netmouse_Exchange1c
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software Licence 3.0 (OSL-3.0)
 * @author     Netmouse <1c@netmouse.com.ua>
 */

class Netmouse_Exchange1c_Model_System_Config_Source_Product_Attribute_Image
{
    public static function toOptionArray()
    {
        $options = array(array('value' => '', 'label' => ''));

        $attributes = Mage::getResourceModel('catalog/product_attribute_collection')
            ->addVisibleFilter()
            ->addFieldToFilter('frontend_input', 'media_image')
            ->addFieldToFilter('attribute_code', array('nin' => array('image', 'small_image', 'thumbnail')));


        foreach ($attributes as $attribute) {
            /** @var Mage_Catalog_Model_Entity_Attribute */
            $options[] = array(
                'value' => $attribute->getAttributeCode(),
                'label' => $attribute->getFrontendLabel(),
            );
        }
        
        return $options;
    }
}
