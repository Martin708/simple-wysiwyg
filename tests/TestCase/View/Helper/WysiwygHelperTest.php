<?php
namespace Wysiwyg\Test\TestCase\View\Helper;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use Wysiwyg\View\Helper\WysiwygHelper;

/**
 * Wysiwyg\View\Helper\WysiwygHelper Test Case
 */
class WysiwygHelperTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Wysiwyg\View\Helper\WysiwygHelper
     */
    public $Wysiwyg;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $view = new View();
        $this->Wysiwyg = new WysiwygHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Wysiwyg);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
