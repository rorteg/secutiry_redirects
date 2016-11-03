<?php
/**
 * Uecommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Uecommerce EULA.
 * It is also available through the world-wide-web at this URL:
 * http://www.uecommerce.com.br/
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade the extension
 * to newer versions in the future. If you wish to customize the extension
 * for your needs please refer to http://www.uecommerce.com.br/ for more information
 *
 * @category   Uecommerce
 * @package    Uecommerce_SecurityRedirect
 * @copyright  Copyright (c) 2016 Uecommerce (http://www.uecommerce.com.br/)
 * @license    http://www.uecommerce.com.br/
 * @author     Uecommerce Dev Team
 */

class Uecommerce_SecurityRedirect_Model_Adminhtml_System_Config_Backend_Serialized extends Mage_Core_Model_Config_Data {

    protected function _afterLoad() {
        if (!is_array($this->getValue())) {
            $value = $this->getValue();
            $this->setValue(empty($value) ? false : @unserialize($value));
        }
    }

    protected function _beforeSave() {
        $values = $this->getValue();
        if (is_array($values)) {
            $newValue = array();
            foreach ($values as $key => $value) {
                $formated = array();
                foreach ($value as $val) {
                    if ($val != '/' && $val != '') {
                        $formated[] = Mage::helper('uecommerce_securityredirect/urlpath')->hidrateUrl($val);
                    } else {
                        $formated[] = '';
                    }
                }
                $newValue[$key] = $formated;
            }
            $this->setValue(serialize($newValue));
        }
    }
}
