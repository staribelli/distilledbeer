<?php

namespace SFrameworkTest\Config\Loader;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use SFramework\Config\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocatorInterface;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser;

class YamlFileLoaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var  vfsStreamDirectory
     */
    private $root;

    public function setUp()
    {
        $this->root = vfsStream::setup('root');
    }

    public function testLoadSuccess()
    {
        $expected = ['key' => 'value'];
        $fileName = 'file.txt';
        $this->setUpFile($fileName, "key: value");

        $locatorMock = $this->getLocatorMock($fileName);
        $parserMock = $this->getMockBuilder(Parser::class)
            ->getMock();
        $parserMock->expects($this->once())
            ->method('parse')
            ->willReturn($expected);
        $loader = new YamlFileLoader($locatorMock, $parserMock);
        $result = $loader->load($fileName);
        $this->assertTrue(is_array($result));
        $this->assertEquals($expected, $result);
    }

    public function testLoadFileDoesNotExist()
    {
        $fileName = 'doesnotexist.txt';
        $locatorMock = $this->getMockBuilder(FileLocatorInterface::class)
            ->getMock();
        $locatorMock->expects($this->once())
            ->method('locate')
            ->willReturn(null);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('File "" not found.');
        $loader = new YamlFileLoader($locatorMock);
        $result = $loader->load($fileName);
        $this->assertTrue(is_array($result));
        $this->assertEmpty($result);
    }

    public function testLoadInvalidYaml()
    {
        $fileName = 'file.txt';
        $this->setUpFile($fileName, "key: value");

        $locatorMock = $this->getLocatorMock($fileName);
        $parserMock = $this->getMockBuilder(Parser::class)
            ->getMock();
        $parserMock
            ->expects($this->once())
            ->method('parse')
            ->withAnyParameters()
            ->willThrowException(new ParseException(''));
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The file "vfs://root/file.txt" does not contain valid YAML.');

        $loader = new YamlFileLoader($locatorMock, $parserMock);
        $result = $loader->load($fileName);
        $this->assertTrue(is_array($result));
        $this->assertEmpty($result);
    }

    public function testLoadEmptyFile()
    {
        $fileName = 'file.txt';
        $this->setUpFile($fileName, "");

        $locatorMock = $this->getLocatorMock($fileName);
        $parserMock = $this->getMockBuilder(Parser::class)
            ->getMock();
        $parserMock->expects($this->once())
            ->method('parse')
            ->willReturn(null);
        $loader = new YamlFileLoader($locatorMock, $parserMock);
        $result = $loader->load($fileName);
        $this->assertTrue(is_array($result));
        $this->assertEquals([], $result);
    }

    public function testLoadNotAnArray()
    {
        $fileName = 'file.txt';
        $this->setUpFile($fileName, "");

        $locatorMock = $this->getLocatorMock($fileName);
        $parserMock = $this->getMockBuilder(Parser::class)
            ->getMock();
        $parserMock->expects($this->once())
            ->method('parse')
            ->willReturn("");
        $this->expectExceptionMessage('The file "vfs://root/file.txt" must contain a YAML array.');
        $this->expectException(\InvalidArgumentException::class);
        $loader = new YamlFileLoader($locatorMock, $parserMock);
        $result = $loader->load($fileName);
        $this->assertTrue(is_array($result));
        $this->assertEquals([], $result);
    }

    private function setUpFile($fileName, $content)
    {
        vfsStream::newFile($fileName)
            ->at($this->root)
            ->setContent($content);
    }

    /**
     * @param $fileName
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getLocatorMock($fileName)
    {
        $locatorMock = $this->getMockBuilder(FileLocatorInterface::class)
            ->getMock();
        $locatorMock->expects($this->once())
            ->method('locate')
            ->willReturn(vfsStream::url('root/' . $fileName));

        return $locatorMock;
    }

}