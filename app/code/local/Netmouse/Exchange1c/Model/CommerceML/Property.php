<?php

/**
 * @category   Netmouse
 * @package    Netmouse_Exchange1c
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software Licence 3.0 (OSL-3.0)
 * @author     Netmouse <1c@netmouse.com.ua>
 */
class Netmouse_Exchange1c_Model_CommerceML_Property extends Netmouse_Exchange1c_Model_ORM_Model
{
    /**
     * @var string $id
     */
    public $id;

    /**
     * @var string $name
     */
    public $name;

    /**
     * @var array $values
     */
    public $values = array();

    /**
     * @param SimpleXMLElement|null $importXml
     * @return \Zenwalker\CommerceML\Model\Property
     */
    public function __construct($importXml = null)
    {
        if (! is_null($importXml)) {
            $this->loadImport($importXml);
        }
    }

    /**
     * @param SimpleXMLElement $xml
     * @return void
     */
    public function loadImport($xml)
    {
        $this->id = (string) $xml->Ид;

        $this->name = (string) $xml->Наименование;

        $valueType = (string) $xml->ТипЗначений;

        if ($valueType == 'Справочник' && $xml->ВариантыЗначений) {
            foreach ($xml->ВариантыЗначений->Справочник as $value) {
                $id = (string) $value->ИдЗначения;
                $this->values[$id] = (string) $value->Значение;
            }
        }
    }

    /**
     * @param string $valueId
     * @return null|string
     */
    public function getValue($valueId)
    {
        if (isset($this->values[$valueId])) {
            return $this->values[$valueId];
        }

        return null;
    }
}