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

class Netmouse_Exchange1c_IndexController extends Mage_Core_Controller_Front_Action

{
    public function indexAction()
    {
        $type = $this->getRequest()->getParam('type');
        $mode = $this->getRequest()->getParam('mode');

        switch ($type) {
            case "catalog":
                switch ($mode) {
                    case "checkauth":
                        $this->catalogCheckauthAction();
                        break;
                    case "init":
                        $this->catalogInitAction();
                        break;
                    case "file":
                        $this->catalogFileAction();
                        break;
                    case "import":
                        $this->catalogImportAction();
                        break;
                    default:
                        echo "failure" . PHP_EOL . "Invalid exchange catalog mode parameter";
                        break;
                }

                break;
            case "sale":
                switch ($mode) {
                    case "checkauth":
                        $this->saleCheckauthAction();
                        break;
                    case "init":
                        $this->saleInitAction();
                        break;
                    case "query":
                        $this->saleQueryAction();
                        break;
                    case "success":
                        $this->saleSuccessAction();
                        break;
                    case "file":
                        $this->saleFileAction();
                        break;
                    default:
                        echo "failure" . PHP_EOL . "Invalid exchange sale mode parameter";
                        break;
                }

                break;
            default:
                echo "failure" . PHP_EOL . "Invalid exchange type parameter";
                break;
        }
    }

    protected function _getZipEnabled()
    {
        return "no";
    }

    protected function _getFileLimit()
    {
        return 1024000;
    }

    protected function _isAuthorized()
    {
        $name = Mage::getSingleton('core/session')->getSessionName();

        $value = $this->getRequest()->getCookie($name);
        if (null === $value) {
            $value = $this->getRequest()->getParam($name);
        }

        if (Mage::getSingleton('core/session')->getSessionId() == $value) {
            return true;
        }

        echo "failure" . PHP_EOL;
        echo "Session has expired";
        return false;
    }

    public function catalogCheckauthAction()
    {
        echo "success" . PHP_EOL;
        echo Mage::getSingleton('core/session')->getSessionName() . PHP_EOL;
        echo Mage::getSingleton('core/session')->getSessionId();
    }

    public function catalogInitAction()
    {

    }

    public function catalogFileAction()
    {

    }

    public function catalogImportAction()
    {

    }


    public function saleCheckauthAction()
    {
        echo "success" . PHP_EOL;
        echo Mage::getSingleton('core/session')->getSessionName() . PHP_EOL;
        echo Mage::getSingleton('core/session')->getSessionId();
    }

    public function saleInitAction()
    {

    }


    public function saleQueryAction()
    {

    }

    public function saleSuccessAction()
    {

    }

    public function saleFileAction()
    {

    }

}
