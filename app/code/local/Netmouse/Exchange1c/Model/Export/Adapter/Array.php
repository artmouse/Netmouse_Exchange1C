<?php


class Netmouse_Exchange1c_Model_Export_Adapter_Array extends Netmouse_Exchange1c_Model_Export_Adapter_Abstract
{
    protected  $_content = array();
    public function writeRow(array $rowData)
    {
        $this->_content[] = $rowData;
    }

    public function getContents()
    {
        return $this->_content;
    }
}