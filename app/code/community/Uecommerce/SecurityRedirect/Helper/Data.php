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
class Uecommerce_SecurityRedirect_Helper_Data extends Mage_Core_Helper_Abstract
{
    const XML_PATH_UECOMMERCE_SECURITY_REDIRECT_CONFIG = 'dev';
    const XML_PATH_UECOMMERCE_DEV_ALLOW_IPS = 'dev/uecommerce_securityredirect/allow_ips';

    /**
     * Get extension version
     */
    public function getExtensionVersion()
    {
        return (string) Mage::getConfig()->getNode()->modules->Uecommerce_SecurityRedirect->version;
    }

    public function isIpAllowed($storeId=null)
    {
        $allow = true;

        $allowedIps = Mage::getStoreConfig(self::XML_PATH_UECOMMERCE_DEV_ALLOW_IPS, $storeId);
        $remoteAddr = Mage::helper('core/http')->getRemoteAddr();
        if (!empty($allowedIps) && !empty($remoteAddr)) {
            $allowedIps = preg_split('#\s*,\s*#', $allowedIps, null, PREG_SPLIT_NO_EMPTY);
            if (array_search($remoteAddr, $allowedIps) === false
                && array_search(Mage::helper('core/http')->getHttpHost(), $allowedIps) === false) {
                $allow = false;
            }
        } else {
            $allow = false;
        }

        return $allow;
    }
}