<?php

/**
 * @category   Netmouse
 * @package    Netmouse_Exchange1c
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software Licence 3.0 (OSL-3.0)
 * @author     Netmouse <1c@netmouse.com.ua>
 */
class Netmouse_Exchange1c_Model_CommerceML extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('exchange1c/commerceML');
    }

    protected function _getWorkingDir()
    {
        $dir = Mage::getBaseDir('var') . DS . '1c_exchange' . DS;

        if ((!empty($dir)) && (!is_dir($dir))) {
            if (!@mkdir($dir, 0755, true)) {
                return false;
            }
        }
        return $dir;
    }


    /**
     * Add XML files.
     *
     * @param string|bool $importXml
     * @param string|bool $offersXml
     */
    public function addXml($importXml = false, $offersXml = false)
    {
        $buffer = array();

        if ($importXml) {
            $importXml = $this->loadXml($importXml);

            if ($importXml->Каталог->Товары) {
                foreach($importXml->Каталог->Товары->Товар as $product) {
                    $productId = (string) $product->Ид;
                    $buffer['products'][$productId]['import'] = $product;
                }
            }

            $this->parseCategories($importXml);
            $this->parseProperties($importXml);
        }

        if ($offersXml) {
            $offersXml = $this->loadXml($offersXml);

            if ($offersXml->ПакетПредложений->Предложения) {
                foreach ($offersXml->ПакетПредложений->Предложения->Предложение as $offer) {
                    $productId = (string) $offer->Ид;
                    $buffer['products'][$productId]['offer'] = $offer;
                }
            }

            $this->parsePriceTypes($offersXml);
        }

        $this->parseProducts($buffer);
    }

    /**
     * loading xml file to $this->xml variable
     * uses simple xml extension
     * @param type $filename
     * @return boolean
     */
    /**
    protected function _readXmlFile($filename)
    {
        if (file_exists($this->_getWorkingDir() . $filename) && is_file($this->_getWorkingDir() . $filename))
            return simplexml_load_file($this->_getWorkingDir() . $filename);
        return false;
    }*/

    public function catalogInit()
    {
        $tempFiles = glob($this->_getWorkingDir() . '*.*');
        if (false !== $tempFiles) {
            foreach ($tempFiles as $file) {
                unlink($file);
            }
        }

        // TODO session based progress reset
    }

    public function catalogFile($filename)
    {
        $ar = explode('/', $filename);
        if (count($ar) > 1) {
            for ($i = 0; $i <= (count($ar) - 2); $i++) {
                $temp = implode("/", array_slice($ar, 0, $i + 1));
                @mkdir($this->_getWorkingDir() . $temp);
            };
        };

        $fh = @fopen($this->_getWorkingDir() . $filename, 'ab');
        fwrite($fh, file_get_contents('php://input') . "\r\n");
        fclose($fh);

        return true;
    }

    /**
    public function catalogImport($filename)
    {
        //$category = array();
        $xml = $this->_readXmlFile($filename);

        if ($filename == 'import.xml') {

            if ($xml->Классификатор->Группы && $xml->Классификатор->Группы->Группа) {
                foreach ($xml->Классификатор->Группы->Группа as $item) {
                    $this->parceGroup($item, 0);
                }
            }

            //$test = $this->get1cGroups($xml->Классификатор, $category, 0);
            //$test = $this->parceGroup($xml->Классификатор, $category, 0);
            //Mage::log($test, null, 'exchange_1c.log', true);

            // TODO handle import and progress

        } else if ($filename == 'offers.xml') {
            // TODO handle import and progress
        }

        return true;
    }*/

    public function saleInit()
    {
        $tempFiles = glob($this->_getWorkingDir() . '*.*');
        if (false !== $tempFiles) {
            foreach ($tempFiles as $file) {
                unlink($file);
            }
        }
    }

    public function saleQuery()
    {
        $header = '<?xml version="1.0" encoding="utf-8"?>';
        $header .= '<КоммерческаяИнформация ВерсияСхемы="2.04" ДатаФормирования="' . date('Y-m-d') . '"></КоммерческаяИнформация>';
        $xml = new SimpleXMLElement ($header);

        // TODO Generate XML structure

        return $xml->asXML();
    }

    public function saleFile($filename)
    {
        $fh = fopen($this->_getWorkingDir() . $filename, 'ab');
        fwrite($fh, file_get_contents('php://input'));
        fclose($fh);

        $xml = simplexml_load_file($this->_getWorkingDir() . $filename);

        foreach ($xml->{'Документ'} as $xmlOrder) {
            // TODO
        }

        return true;
    }

    public function setSaleLastExportDate()
    {
        // TODO
    }

    public function checkAuthorization()
    {
        $username = $_REQUEST ['username'];
        $password = $_REQUEST ['password'];

        try {

            $user = Mage::getSingleton('admin/user');
            $userRole = Mage::getSingleton('admin/mysql4_acl_role');

            try {

                $temp = $user->authenticate($username, $password);
            } catch (Exception $e) {

                $details = $user->loadByUsername($username);

                if ($details->getIsActive()) {
                    $temp = Mage::helper('core')->validateHash($password, $details->getPassword());
                }
            }

            if ($temp) {

                //$loadRole = $userRole->load($user->getRoles($user));

                //$loginOK = true;
            } else {

                // Invalid login. Authorization failed'
            }
        } catch (Exception $e) {
        }
    }



    # Обход дерева групп полученных из 1С
    /**
     * @param $xml
     * @param $category
     * @param $owner
     * @return mixed
     */
    /**
     * public function get1cGroups($xml, $category, $owner)
     * {
     *
     * if (!isset($xml->Группы)) {
     * return $category;
     * }
     * foreach ($xml->Группы->Группа as $category_data) {
     * $name = (string)$category_data->Наименование;
     * $cat_id = (string)$category_data->Ид;
     * $category [$cat_id] ['name'] = $name;
     * $category [$cat_id] ['owner_id'] = $owner;
     * $category [$cat_id] ['category_id'] = $cat_id;
     * $category = $this->get1cGroups($category_data, $category, $category [$cat_id] ['category_id']);
     * }
     * return $category;
     * }
     */


    /**
     * Parse products.
     *
     * @param array $buffer
     * @return void
     */
    public function parseProducts($buffer)
    {
        foreach ($buffer['products'] as $item) {
            $import = $item['import'];
            $offer = isset($item['offer']) ? $item['offer'] : null;
            $product = new Product($import, $offer);
            $this->collections['product']->add($product);
        }
    }

    /**
     * Parse categories.
     *
     * @param SimpleXMLElement $importXml
     * @param SimpleXMLElement [$parent]
     * @return void
     */
    public function parseCategories($importXml, $parent = null)
    {
        $xmlCategories = ($importXml->Классификатор->Группы)
            ? $importXml->Классификатор->Группы
            : $xmlCategories = $importXml;
        foreach ($xmlCategories->Группа as $xmlCategory) {
            $category = new NetmouseCategory($xmlCategory);
            if (!is_null($parent)) {
                $parent->addChild($category);
            }
            $this->collections['category']->add($category);
            if ($xmlCategory->Группы) {
                $this->parseCategories($xmlCategory->Группы, $category);
            }
        }
    }

    /**
     * Parse price types.
     *
     * @param SimpleXMLElement $offersXml
     * @return void
     */
    public function parsePriceTypes($offersXml)
    {
        if ($offersXml->ПакетПредложений->ТипыЦен) {
            foreach ($offersXml->ПакетПредложений->ТипыЦен->ТипЦены as $xmlPriceType) {
                $priceType = new PriceType($xmlPriceType);
                $this->collections['priceType']->add($priceType);
            }
        }
    }
    /**
     * @param SimpleXMLElement $importXml
     * @return void
     */
    public function parseProperties($importXml)
    {
        if ($importXml->Классификатор->Свойства) {
            foreach ($importXml->Классификатор->Свойства->Свойство as $xmlProperty) {
                $property = new Property($xmlProperty);
                $this->collections['property']->add($property);
            }
        }
    }

    public function add($fields)
    {

        /** @var $import AvS_FastSimpleImport_Model_Import */
        $import = Mage::getModel('fastsimpleimport/import');
        try {
            $import->processCategoryImport($fields);
        } catch (Exception $e) {
            print_r($import->getErrorMessages());
        }

    }

    public function update($fields)
    {

        /** @var $import AvS_FastSimpleImport_Model_Import */
        $import = Mage::getModel('fastsimpleimport/import');
        try {
            $import->processCategoryImport($fields);
        } catch (Exception $e) {
            print_r($import->getErrorMessages());
        }

    }


    public function getCategoryTree($name, $parentCategory)
    {

    }

    /**
     * Load XML form file or string.
     *
     * @param string $xml
     * @return SimpleXMLElement
     */
    public function loadXml($xml)
    {
        return is_file($this->_getWorkingDir() . $xml)
            ? simplexml_load_file($this->_getWorkingDir() . $xml)
            : simplexml_load_string($this->_getWorkingDir() . $xml);
    }

}
