<?php

namespace SFrameworkTest;

use SFramework\Form\Validator;

class ValidateValueAllowedTest extends \PHPUnit_Framework_TestCase
{
    public function validateAllowedProvider()
    {
        return [
            'valid'       => [
                [
                    'name' => 'field',
                    'spec' => ['allowedValues' => ['a']],
                    'data' => ['field' => 'a']
                ],
                'expected' => true
            ],
            'notValid'    => [
                [
                    'name' => 'field',
                    'spec' => ['allowedValues' => ['a']],
                    'data' => ['field' => 'b']
                ],
                'expected' => false
            ],
            'notRequired' => [
                [
                    'name' => 'field',
                    'spec' => [],
                    'data' => ['field' => 'value']
                ],
                'expected' => true
            ],
            'valueNotSet' => [
                [
                    'name' => 'field',
                    'spec' => [],
                    'data' => []
                ],
                'expected' => true
            ]
        ];
    }

    /**
     * @dataProvider validateAllowedProvider
     */
    public function testValidateAllowedValues($data, $expected)
    {
        $validator = new Validator();
        $result = $validator->isValueAllowed($data['name'], $data['spec'],
            $data['data']);
        $this->assertEquals($expected, $result);
    }


}