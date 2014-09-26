<?php

/**
 * Netmouse
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@netmouse.com.ua so we can send you a copy immediately.
 *
 *
 * @category    Netmouse
 * @package     Exchange1c
 * @copyright   Copyright (c) 2014 Netmouse http://netmouse.com.ua
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
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
        $art = "";
        $cost = 0;
        $arr = array();

        $xml = new XMLReader();
        $xml->open($this->_getWorkingDir() . $filename);
        while ($xml->read()) {
            switch ($xml->nodeType) {
                case (XMLREADER::ELEMENT):
                    if ($xml->localName == "Наименование") {
                        $xml->read();
                        $art = $xml->value;
                        if ($art != "" && $cost != 0) $arr[$art] = $cost;
                    }
                    if ($xml->localName == "ЦенаЗаЕдиницу") {
                        $xml->read();
                        $cost = $xml->value;
                        if ($art != "" && $cost != 0) $arr[$art] = $cost;
                    }
            }
        }

// посмотрим что получилось
        $i = 0;
        foreach ($arr as $name => $val) {
            Mage::log($i . " - " .$name . " = " . $val . " грн.", null, 'exchange_1c.log', true);
            $i++;
            //echo $name . " = " . $val . "";

            // сюда добавлю код по обновлению цен в БД
        }


        if ($filename == 'import.xml') {
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

}
