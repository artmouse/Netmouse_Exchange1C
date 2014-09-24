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
class Netmouse_Exchange1c_Model_cml2 extends Mage_Core_Model_Abstract
{
    protected $_dir = 'var/importexport';

    protected function _construct()
    {
        $this->_init('exchange-one-s/cml');
    }

    public function catalogInit()
    {
        $tempFiles = glob($this->_dir . '*.*');
        if (false !== $tempFiles) {
            foreach ($tempFiles as $file) {
                unlink($file);
            }
        }

        // TODO session based progress reset
    }

    public function catalogFile($filename)
    {
        $fh = @fopen(_dir . $filename, 'ab');
        if (false === $fh) {
            return false;
        }

        $wr = fwrite($fh, file_get_contents('php://input'));
        if (false === $wr) {
            return false;
        }

        $cl = fclose($fh);
        return true;
    }

    public function catalogImport($filename)
    {
        if ($filename == 'import.xml') {
            // TODO handle import and progress
        } else if ($filename == 'offers.xml') {
            // TODO handle import and progress
        }

        return true;
    }

    public function saleInit()
    {
        $tempFiles = glob($this->_dir . '*.*');
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
        $fh = fopen($this->_dir . $filename, 'ab');
        fwrite($fh, file_get_contents('php://input'));
        fclose($fh);

        $xml = simplexml_load_file($this->_dir . $filename);

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

                $loadRole = $userRole->load($user->getRoles($user));

                $loginOK = true;
            } else {

                // Invalid login. Authorization failed'
            }
        } catch (Exception $e) {
        }
    }
}
