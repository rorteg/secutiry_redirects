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
class Uecommerce_SecurityRedirect_Helper_Urlpath extends Mage_Core_Helper_Abstract
{
    /** @var string  */
    protected $currentUrl;

    public function __construct()
    {
        $this->currentUrl = $this->hydrateUrl(Mage::app()->getFrontController()->getRequest()->getRequestUri());
    }

    /**
     * Check if current URL is part of the URLs that need to be redirected
     * @param string $group
     * @return bool
     */
    public function urlPathIsValidByConfigGroup($group)
    {
        $currentUrlMatch = array_filter($this->getUrlPaths($group), array($this, 'checkIfCurrentUrl'));
        return (count($currentUrlMatch) > 0);
    }

    /**
     * @param $url
     * @return bool
     */
    protected function checkIfCurrentUrl($url)
    {
        $result = false;
        if (!empty($url)) {
            $regexp = '/' . trim($url, '/') . '/';

            if (@preg_match($regexp, $this->currentUrl)) {
                $result = true;
            }
        }

        return $result;
    }

    /**
     * Get all URLs that need to be redirected if accessed
     * @param string $group
     * @return array
     */
    public function getUrlPaths($group)
    {
        $urlPaths = unserialize(Mage::getStoreConfig(
            Uecommerce_SecurityRedirect_Helper_Data::XML_PATH_UECOMMERCE_SECURITY_REDIRECT_CONFIG .
            '/' . $group . '/url_paths'
        ));

        return $urlPaths['routes'];
    }

    /**
     * @param string $url
     * @return string
     */
    public function hydrateUrl($url)
    {
        if (strlen($url) > 1) {
            if ($url{0} != '/') {
                $url = '/' . $url;
            }
            if (substr($url, -1, 1) != '/') {
                $url = $url . '/';
            }
        }

        return $url;
    }
}
