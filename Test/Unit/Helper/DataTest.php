<?php
namespace Lusiweb\Botman\Test\Unit\Helper;

use PHPUnit_Framework_MockObject_MockObject;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Lusiweb\Botman\Test\Unit\AbstractTestUnit;
use Lusiweb\Botman\Helper\Data;

/**
 * Class AbstractTestUnit
 * @package Lusiweb\Botman\Test\Unit
 */
class DataTest extends AbstractTestUnit
{
    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $contextMock;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $scopeConfigMock;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    protected $helperMock;

    /**
     * {{@inheritdoc}}
     */
    public function setup()
    {
        $this->contextMock = $this->getMockBuilder(Context::class)
            ->disableOriginalConstructor()
            ->setMethods(["getScopeConfig"])
            ->getMock();

        $this->scopeConfigMock = $this->getMockBuilder(ScopeConfigInterface::class)
            ->disableOriginalConstructor()
            ->setMethods(['isSetFlag', 'getValue'])
            ->getMock();

        $this->contextMock
            ->expects($this->any())
            ->method('getScopeConfig')
            ->willReturn($this->scopeConfigMock);

        $this->helperMock = (new ObjectManager($this))
            ->getObject(Data::class, ['context' => $this->contextMock]);
    }

    /**
     * @test
     * @covers Data::isEnabled()
     */
    public function isEnabledTrue()
    {
        $this->scopeConfigMock
            ->expects($this->atLeastOnce())
            ->method('getValue')
            ->with(
                Data::XML_BOT_ENABLED
            )->willReturn($expected = true);

        $this->assertEquals(
            $expected,
            $this->helperMock->isEnabled()
        );
    }

    /**
     * @test
     * @covers Data::isEnabled()
     */
    public function isEnabledFalse()
    {
        $this->scopeConfigMock
            ->expects($this->atLeastOnce())
            ->method('getValue')
            ->with(
                Data::XML_BOT_ENABLED
            )->willReturn($expected = false);

        $this->assertFalse(
            $this->helperMock->isEnabled()
        );
    }

    /**
     * @test
     * @covers Data::getBotToken()
     */
    public function getBotToken()
    {
        $this->scopeConfigMock
            ->expects($this->atLeastOnce())
            ->method('getValue')
            ->with(
                Data::XML_BOT_TOKEN
            )->willReturn($expected = "bot-token");

        $this->assertEquals(
            $expected,
            $this->helperMock->getBotToken()
        );
    }

    /**
     * @test
     * @covers Data::getBotToken()
     */
    public function getBotTokenFalseWithSpaces()
    {
        $this->scopeConfigMock
            ->expects($this->atLeastOnce())
            ->method('getValue')
            ->with(
                Data::XML_BOT_TOKEN
            )->willReturn("  ");

        $this->assertFalse(
            $this->helperMock->getBotToken()
        );
    }

    /**
     * @test
     * @covers Data::getBotToken()
     */
    public function getBotTokenFalseEmpty()
    {
        $this->scopeConfigMock
            ->expects($this->atLeastOnce())
            ->method('getValue')
            ->with(
                Data::XML_BOT_TOKEN
            )->willReturn("");

        $this->assertFalse(
            $this->helperMock->getBotToken()
        );
    }
}
