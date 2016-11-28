<?php

class Uecommerce_SecurityRedirect_Test_Unit_Model_ObserverTest extends PHPUnit_Framework_TestCase
{
    public function testCheckAndRedirectIfNotRedirect()
    {
        $observer = new Uecommerce_SecurityRedirect_Model_Observer();
        $varienObserver = $this->createMock(Varien_Event_Observer::class);
        $varienObserver->method('getEvent')->willReturn(new Varien_Event([]));

        $return = $observer->checkAndRedirect($varienObserver);

        $this->assertFalse($return);
    }
}