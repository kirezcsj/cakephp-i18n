<?php
declare(strict_types=1);

namespace ADmad\I18n\Test\View\Widget;

use ADmad\I18n\View\Widget\TimezoneWidget;
use Cake\TestSuite\TestCase;
use Cake\View\StringTemplate;
use DateTimeZone;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;

/**
 * Tests for TimezoneWidget.
 */
class TimezoneWidgetTest extends TestCase
{
    protected $context;

    protected StringTemplate $templates;

    /**
     * setup method.
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $templates = [
            'select' => '<select name="{{name}}"{{attrs}}>{{content}}</select>',
            'selectMultiple' => '<select name="{{name}}[]" multiple="multiple"{{attrs}}>{{content}}</select>',
            'option' => '<option value="{{value}}"{{attrs}}>{{text}}</option>',
            'optgroup' => '<optgroup label="{{label}}"{{attrs}}>{{content}}</optgroup>',
        ];
        $this->context = $this->getMockBuilder('Cake\View\Form\ContextInterface')->getMock();
        $this->templates = new StringTemplate($templates);
    }

    /**
     * test render.
     *
     * @return void
     */
    public function testRender()
    {
        $select = new TimezoneWidget($this->templates);
        $data = [
            'name' => 'timezone',
            'options' => ['UTC' => DateTimeZone::UTC],
        ];
        $result = $select->render($data, $this->context);
        $expected = [
            'select' => ['name' => 'timezone'],
                'option' => ['value' => 'UTC'],
                'UTC',
                '/option',
            '/select',
        ];
        $this->assertHtml($expected, $result);

        $data = [
            'name' => 'timezone',
            'options' => ['UTC' => DateTimeZone::UTC, 'Arctic' => DateTimeZone::ARCTIC],
        ];
        $result = $select->render($data, $this->context);
        $expected = [
            'select' => ['name' => 'timezone'],
                ['optgroup' => ['label' => 'UTC']],
                    ['option' => ['value' => 'UTC']],
                    'UTC',
                    '/option',
                '/optgroup',
                ['optgroup' => ['label' => 'Arctic']],
                    ['option' => ['value' => 'Arctic/Longyearbyen']],
                    'Arctic/Longyearbyen',
                    '/option',
                '/optgroup',
            '/select',
        ];
        $this->assertHtml($expected, $result);
    }

    /**
     * @see https://github.com/ADmad/cakephp-i18n/pull/52
     * @return void
     */
    #[DoesNotPerformAssertions]
    public function testRenderOptionsNull()
    {
        $data = [
            'name' => 'timezone',
            'options' => null,
        ];

        $select = new TimezoneWidget($this->templates);
        $select->render($data, $this->context);
    }
}
