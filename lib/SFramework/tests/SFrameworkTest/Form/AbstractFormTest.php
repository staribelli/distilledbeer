<?php

namespace SFrameworkTest;

use SFramework\Form\AbstractForm;
use SFramework\Form\ValidatorInterface;

class AbstractFormTest extends \PHPUnit_Framework_TestCase
{
    public function validateProvider()
    {
        return [
            'noRules'              => [
                [],
                []
            ],
            'requiredValid'        => [],
            'requiredNotValid'     => [],
            'valueAllowedValid'    => [],
            'valueAllowedNotValid' => [],
            'regexValid'           => [],
            'regexNotValid'        => [],
            'allValid'             => [],
            'allNotValid'          => [],
        ];
    }

    public function testValidateNoRules()
    {
        $validatorMock = $this->getMockBuilder(ValidatorInterface::class)
            ->getMock();
        $mock = $this->getMockBuilder(AbstractForm::class)
            ->setConstructorArgs([$validatorMock])
            ->getMockForAbstractClass();
        $mock->expects($this->once())
            ->method('rules')
            ->willReturn([]);
        /** @var AbstractForm $mock */
        $mock->validate();
        $this->assertEquals([], $mock->getErrors());
    }

    public function testValidateRequiredValid()
    {
        $validatorMock = $this->getMockBuilder(ValidatorInterface::class)
            ->getMock();
        $validatorMock->expects($this->once())
            ->method('validRequired')
            ->willReturn(true);
        $mock = $this->getMockBuilder(AbstractForm::class)
            ->setConstructorArgs([$validatorMock])
            ->getMockForAbstractClass();
        $mock->expects($this->once())
            ->method('rules')
            ->willReturn(['name' => ['spec']]);
        $validatorMock->expects($this->once())
            ->method('isValueAllowed')
            ->willReturn(true);
        $validatorMock->expects($this->once())
            ->method('matchesRegex')
            ->willReturn(true);
        /** @var AbstractForm $mock */
        $mock->validate();
        $this->assertEquals([], $mock->getErrors());
    }

    public function testValidateRequiredNotValid()
    {
        $validatorMock = $this->getMockBuilder(ValidatorInterface::class)
            ->getMock();
        $validatorMock->expects($this->once())
            ->method('validRequired')
            ->willReturn(false);
        $mock = $this->getMockBuilder(AbstractForm::class)
            ->setConstructorArgs([$validatorMock])
            ->getMockForAbstractClass();
        $mock->expects($this->once())
            ->method('rules')
            ->willReturn(['name' => ['spec']]);
        /** @var AbstractForm $mock */
        $mock->validate();
        $expected = ['name' => 'name is required'];
        $this->assertEquals($expected, $mock->getErrors());
    }

    public function testValidateAllowedNotValid()
    {
        $validatorMock = $this->getMockBuilder(ValidatorInterface::class)
            ->getMock();
        $validatorMock->expects($this->once())
            ->method('validRequired')
            ->willReturn(true);
        $mock = $this->getMockBuilder(AbstractForm::class)
            ->setConstructorArgs([$validatorMock])
            ->getMockForAbstractClass();
        $mock->expects($this->exactly(2))
            ->method('rules')
            ->willReturn(['name' => ['spec']]);
        $validatorMock->expects($this->once())
            ->method('isValueAllowed')
            ->willReturn(false);
        $validatorMock->expects($this->once())
            ->method('matchesRegex')
            ->willReturn(true);
        /** @var AbstractForm $mock */
        $mock->setData(['name' => 'value']);
        $mock->validate();
        $expected = ['name' => 'Value value for field name is not allowed'];
        $this->assertEquals($expected, $mock->getErrors());
    }

    public function testValidateMatchesRegexNotValid()
    {
        $validatorMock = $this->getMockBuilder(ValidatorInterface::class)
            ->getMock();
        $validatorMock->expects($this->once())
            ->method('validRequired')
            ->willReturn(true);
        $mock = $this->getMockBuilder(AbstractForm::class)
            ->setConstructorArgs([$validatorMock])
            ->getMockForAbstractClass();
        $mock->expects($this->exactly(2))
            ->method('rules')
            ->willReturn(['name' => ['spec']]);
        $validatorMock->expects($this->once())
            ->method('isValueAllowed')
            ->willReturn(true);
        $validatorMock->expects($this->once())
            ->method('matchesRegex')
            ->willReturn(false);
        /** @var AbstractForm $mock */
        $mock->setData(['name' => 'value']);
        $mock->validate();
        $expected = ['name' => 'Value value for field name is not valid'];
        $this->assertEquals($expected, $mock->getErrors());
    }

    public function testValidateMatchesRegexAllowedNotValid()
    {
        $validatorMock = $this->getMockBuilder(ValidatorInterface::class)
            ->getMock();
        $validatorMock->expects($this->once())
            ->method('validRequired')
            ->willReturn(true);
        $mock = $this->getMockBuilder(AbstractForm::class)
            ->setConstructorArgs([$validatorMock])
            ->getMockForAbstractClass();
        $mock->expects($this->exactly(2))
            ->method('rules')
            ->willReturn(['name' => ['spec']]);
        $validatorMock->expects($this->once())
            ->method('isValueAllowed')
            ->willReturn(false);
        $validatorMock->expects($this->once())
            ->method('matchesRegex')
            ->willReturn(false);
        /** @var AbstractForm $mock */
        $mock->setData(['name' => 'value']);
        $mock->validate();
        $expected = ['name' => 'Value value for field name is not valid'];
        $this->assertEquals($expected, $mock->getErrors());
    }
}
