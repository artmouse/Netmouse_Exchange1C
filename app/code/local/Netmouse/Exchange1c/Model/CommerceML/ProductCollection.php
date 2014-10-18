<?php

/**
 * @category   Netmouse
 * @package    Netmouse_Exchange1c
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software Licence 3.0 (OSL-3.0)
 * @author     Netmouse <1c@netmouse.com.ua>
 */
class Netmouse_Exchange1c_Model_CommerceML_ProductCollection extends Netmouse_Exchange1c_Model_ORM_Collection
{
    /**
     * Translate price types id to string.
     *
     * @param PriceTypeCollection $priceTypeCollection
     * @return void
     */
    public function attachPriceTypeCollection($priceTypeCollection)
    {
        foreach ($this->fetch() as $product) {
            foreach ($product->price as $id => &$price) {
                $type = $priceTypeCollection->getType($id);
                if ($type) $price['type'] = $type;
            }
        }
    }

    /**
     * Translate properties id to string.
     *
     * @param PropertyCollection $propertyCollection
     * @return void
     */
    public function attachPropertyCollection($propertyCollection)
    {
        foreach ($this->fetch() as $product) {
            foreach($product->properties as $propId => &$value) {
                $value['value'] = $propertyCollection->getValue($propId, $value['valueId']);
                $value['name'] = $propertyCollection->getName($propId);
            }
        }
    }

}
