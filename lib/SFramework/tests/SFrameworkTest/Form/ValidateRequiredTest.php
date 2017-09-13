<?php

namespace SFrameworkTest;

use SFramework\Form\Validator;

class ValidateRequiredTest extends \PHPUnit_Framework_TestCase
{
    public function validRequiredProvider()
    {
        return [
            'valid'       => [
                [
                    'name' => 'field',
                    'spec' => ['required' => true],
                    'data' => ['field' => 'value']
                ],
                'expected' => true
            ],
            'notValid'    => [
                [
                    'name' => 'field',
                    'spec' => ['required' => true],
                    'data' => []
                ],
                'expected' => false
            ],
            'notRequired' => [
                [
                    'name' => 'field',
                    'spec' => ['required' => false],
                    'data' => ['field' => 'value']
                ],
                'expected' => true
            ]
        ];
    }

    /**
     * @dataProvider validRequiredProvider
     */
    public function testValidRequired($data, $expected)
    {
        $validator = new Validator();
        $result = $validator->validRequired($data['name'], $data['spec'],
            $data['data']);
        $this->assertEquals($expected, $result);
    }


}