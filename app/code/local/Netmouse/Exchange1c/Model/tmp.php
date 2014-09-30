<?php

Mage::log('$CID Start - '.$PID, null, 'exchange_1c.log', true);
$category = Mage::getModel('catalog/category')->loadByAttribute('ex_1c_id', $element->Ид);
if (!$category) {
    $data = array();
    $data[] = array(
        '_root' => 'Default Category',
        '_category' => $this->getCategoryTree((String)$element->Наименование, $PID),
        'name' => (String)$element->Наименование,
        'description' => 'Description ' . (String)$element->Наименование,
        'is_active' => 'yes',
        'include_in_menu' => 'yes',
        'meta_description' => 'Meta Test',
        'available_sort_by' => 'position',
        'default_sort_by' => 'position',
        'ex_1c_id' => (String)$element->Ид

    );
    //Mage::log('Создание', null, 'exchange_1c.log', true);
    //Mage::log($CID, null, 'exchange_1c.log', true);
    //Mage::log('Добавление', null, 'exchange_1c.log', true);

    $this->add($data);
    $CID = (String)$element->Ид;

    Mage::log("добавление - " . (String)$element->Наименование . " - " . (String)$element->Ид , null, 'exchange_1c.log', true);
    Mage::log('$CID add   - '.$CID, null, 'exchange_1c.log', true);
    Mage::log('----------------------------------------', null, 'exchange_1c.log', true);

} else {
    //Mage::log($category->getName() ." = ". $element->Наименование, null, 'exchange_1c.log', true);
    //Mage::log($category->getEx_1c_id() ." = ". $element->Ид, null, 'exchange_1c.log', true);
    if ($category->getName() != $element->Наименование ||
        $category->getEx_1c_id() != $element->Ид
    ) {
        $data = array();
        $data[] = array(

            '_root' => 'Default Category',
            '_category' => $this->getCategoryTree((String)$element->Наименование, $PID),
            'name' => (String)$element->Наименование,
            'description' => 'Description ' . (String)$element->Наименование,
            'is_active' => 'yes',
            'include_in_menu' => 'yes',
            'meta_description' => 'Meta Test',
            'available_sort_by' => 'position',
            'default_sort_by' => 'position',
            'ex_1c_id' => (String)$element->Ид
        );
        //Mage::log($data, null, 'exchange_1c.log', true);
        //Mage::log('Обновление', null, 'exchange_1c.log', true);
        //Mage::log((String)$element->Наименование . " - " . (String)$element->Ид , null, 'exchange_1c.log', true);
        $this->update($data);
    }
    $CID = $category->getEx_1c_id();
    Mage::log('$CID update - '.$CID, null, 'exchange_1c.log', true);
    //$CID = $element->Ид;
}
//var_dump($element->Ид);
//var_dump($element->Наименование);
//var_dump("Родитель: ".$PID);
//Mage::log($element->Ид, null, 'exchange_1c.log', true);
//Mage::log($element->Наименование, null, 'exchange_1c.log', true);
//Mage::log("Родитель: ".$PID, null, 'exchange_1c.log', true);
if (count($element->Группы->Группа)) {
    foreach ($element->Группы->Группа as $item)
        $this->parceGroup($item, $CID);
}