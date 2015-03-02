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
namespace SanSessionToolbar\Service;

use SanSessionToolbar\Collector\SessionCollector;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;

/**
 * A class to manage session data
 * @author Abdul Malik Ikhsan <samsonasik@gmail.com>
 */
final class SessionManager implements SessionManagerInterface
{
    /**
     * @var SessionCollector
     */
    private $sessionCollector;

    /**
     * Construct
     * @param SessionCollector $sessionCollector
     */
    public function __construct(SessionCollector $sessionCollector)
    {
        $this->sessionCollector = $sessionCollector;
    }

    /**
     * Get Session Data
     * @return array
     */
    public function getSessionData()
    {
        // re-instantiate for reload session data
        $this->sessionCollector = new SessionCollector();
        $this->sessionCollector->collect(new MvcEvent());

        return $this->sessionCollector->getSessionData();
    }

    /**
     * Set/Unset Session by Container and its key
     * @param string $containerName
     * @param string $keysession
     * @param string $value
     * @param bool   $set
     */
    public function sessionSetting($containerName, $keysession, $value = null, $set = true)
    {
        if (is_string($containerName) && is_string($keysession)) {
            $container = new Container($containerName);
            if ($container->offsetExists($keysession)) {
                if ($set) {
                    $container->offsetSet($keysession, $value);
                } else {
                    $container->offsetUnset($keysession);
                }

                return true;
            }
        }

        return false;
    }

    /**
     * Clear Session
     * @param null|string $byContainer
     */
    public function clearSession($byContainer = null)
    {
        $sessionData = $this->getSessionData();
        foreach ($sessionData as $containerName => $session) {
            if ($byContainer !== null && $containerName !== $byContainer) {
                continue;
            }

            $container = new Container($containerName);
            foreach ($session as $keysession => $rowsession) {
                $container->offsetUnset($keysession);
            }
        }
    }
}
