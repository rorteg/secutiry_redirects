<?php

class Uecommerce_SecurityRedirect_Test_Integration_Helper_DataTest extends PHPUnit_Framework_TestCase
{
    private $dirMediaCreated = false;
    private $fakeVulnerableFileCreated = false;
    private $mediaDir;
    private $fakeVulnerableFile;
    private $fakeVulnerableFileContent;
    /**
     * @var Uecommerce_SecurityRedirect_Helper_Data
     */
    private $helper;

    protected function setUp()
    {
        $this->helper = Mage::helper('uecommerce_securityredirect');
        $this->mediaDir = BP . '/var/vulnerablefilestest';
        $this->fakeVulnerableFile = $this->mediaDir . '/flex.swf';
        $this->fakeVulnerableFileContent = 'fakeContent';
        $this->createFakeVulnerableFilesIfNotExists();
    }

    public function testCheckVulnerableFilesExists()
    {
        $helper = Mage::helper('uecommerce_securityredirect');
        $result = $helper->checkVulnerableFilesExists([
            '/var/vulnerablefilestest/flex.swf'
        ],[]);

        $this->assertTrue($result);
    }

    public function testCheckVulnerableFilesExistsIsFalse()
    {
        $result = $this->helper->checkVulnerableFilesExists([
            '/var/vulnerablefilestest/flex.swf.dist'
        ],[]);

        $this->assertFalse($result);
    }

    public function testCheckContentInVulnerableFiles()
    {
        $checkFakeFilesContent = [];

        $this->assertTrue($this->helper->checkVulnerableFilesExists([],[
            '/var/vulnerablefilestest/flex.swf.dist' => $this->fakeVulnerableFileContent
        ]));
    }

    private function createFakeVulnerableFilesIfNotExists()
    {
        if (file_exists($this->fakeVulnerableFile)) {
            return false;
        }
        if (!is_dir($this->mediaDir)) {
            $this->dirMediaCreated = true;

            mkdir($this->mediaDir, 0777);
        }

        if (!file_put_contents($this->fakeVulnerableFile, $this->fakeVulnerableFileContent)) {
            throw new Exception('Fake file not created!');
        }
        $this->fakeVulnerableFileCreated = true;
    }

    private function removeFakeVulnerableFilesIfCreated()
    {
        exec("rm -rf {$this->mediaDir}");
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->removeFakeVulnerableFilesIfCreated();
    }

}