<?php

namespace RedLink\Tests\Util;


/**
 * <p>ClassHelper Tests</p>
 *
 * @author Antonio David Pérez Morales <adperezmorales@gmail.com>
 * @covers RedLink\Util\ClassHelper
 */
class ClassHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @covers RedLink\Util\ClassHelper::getClassName
     */
    public function testClassName() {
        $className = \RedLink\Util\ClassHelper::getClassName($this);
        $this->assertEquals("ClassHelperTest", $className);
    }
}

?>
