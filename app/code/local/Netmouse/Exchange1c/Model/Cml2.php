<?php

/**
 * @category   Netmouse
 * @package    Netmouse_Exchange1c
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software Licence 3.0 (OSL-3.0)
 * @author     Netmouse <1c@netmouse.com.ua>
 */

class Netmouse_Exchange1c_Model_Cml2 extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('netmouse_exchange1c/cml2');
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
     * loading xml file to $this->xml variable
     * uses simple xml extension
     * @param type $filename
     * @return boolean
     */
    protected function _readXmlFile($filename)
    {
        if (file_exists($this->_getWorkingDir() . $filename) && is_file($this->_getWorkingDir() . $filename))
            return simplexml_load_file($this->_getWorkingDir() . $filename);
        return false;
    }

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

    public function catalogImport($filename)
    {
        $category = array();
        $xml = $this->_readXmlFile($filename);

        if ($filename == 'import.xml') {

            $test = $this->groups_create($xml->Классификатор, $category, 0);
            Mage::log($test, null, 'exchange_1c.log', true);

            // TODO handle import and progress

        } else if ($filename == 'offers.xml') {
            // TODO handle import and progress
        }

        return true;
    }

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
    public function groups_create($xml, $category, $owner)
    {

        if (!isset($xml->Группы)) {
            return $category;
        }
        foreach ($xml->Группы->Группа as $category_data) {
            $name = (string)$category_data->Наименование;
            $cat_id = (string)$category_data->Ид;
            $category [$cat_id] ['name'] = $name;
            $category [$cat_id] ['owner'] = $owner;
            $category [$cat_id] ['category_id'] = $cat_id;
            $category = $this->groups_create($category_data, $category, $category [$cat_id] ['category_id']);
        }
        return $category;
    }

}
