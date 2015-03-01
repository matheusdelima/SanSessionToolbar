<?php
/**
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license.
 */
namespace SanSessionToolbarTest\Factory\Controller;

use PHPUnit_Framework_TestCase;
use SanSessionToolbar\Factory\Controller\SessionToolbarControllerFactory;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * This class tests SessionToolbarControllerFactory class
 * @author Abdul Malik Ikhsan <samsonasik@gmail.com>
 */
class SessionToolbarControllerFactoryTest extends PHPUnit_Framework_TestCase
{
    /** @var SessionToolbarControllerFactory */
    protected $factory;

    /** @var ControllerManager */
    protected $controllerManager;

    /** @var ServiceLocatorInterface */
    protected $serviceLocator;

    protected function setUp()
    {
        /** @var ControllerManager $controllerManager */
        $controllerManager = $this->getMock('Zend\Mvc\Controller\ControllerManager');
        $this->controllerManager = $controllerManager;

        /** @var ServiceLocatorInterface $serviceLocator */
        $serviceLocator = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');
        $this->serviceLocator = $serviceLocator;

        $controllerManager->expects($this->any())
                          ->method('getServiceLocator')
                          ->willReturn($serviceLocator);

        $factory = new SessionToolbarControllerFactory();
        $this->factory = $factory;
    }

    /**
     * @covers SanSessionToolbar\Factory\Controller\SessionToolbarControllerFactory::createService
     */
    public function testCreateService()
    {
        $mockViewRenderer = $this->getMock('Zend\View\Renderer\PhpRenderer');
        $this->serviceLocator->expects($this->at(0))
                             ->method('get')
                             ->with('ViewRenderer')
                             ->willReturn($mockViewRenderer);

        $result = $this->factory->createService($this->controllerManager);
        $this->assertInstanceOf('SanSessionToolbar\Controller\SessionToolbarController', $result);
    }
}