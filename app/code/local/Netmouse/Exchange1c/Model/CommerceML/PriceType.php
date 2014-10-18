<?php

/**
 * @category   Netmouse
 * @package    Netmouse_Exchange1c
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software Licence 3.0 (OSL-3.0)
 * @author     Netmouse <1c@netmouse.com.ua>
 */
class Netmouse_Exchange1c_Model_CommerceML_PriceType extends Netmouse_Exchange1c_Model_ORM_Model
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $currency;

    /**
     * @param SimpleXMLElement [$xmlPriceType]
     * @return Netmouse_Exchange1c_Model_ORM_Model/PriceType
     */
    public function __construct($xmlPriceType = null)
    {
        if (! is_null($xmlPriceType)) {
            $this->loadImport($xmlPriceType);
        }
    }

    /**
     * @param SimpleXMLElement [$xmlPriceType]
     * @return void
     */
    private function loadImport($xmlPriceType)
    {
        $this->id = (string) $xmlPriceType->Ид;

        $this->type = (string) $xmlPriceType->Наименование;

        $this->currency = (string) $xmlPriceType->Валюта;
    }
}
