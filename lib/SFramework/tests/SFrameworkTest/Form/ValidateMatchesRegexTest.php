<?php

namespace SFrameworkTest;

use SFramework\Form\Validator;

class ValidateMatchesRegexTest extends \PHPUnit_Framework_TestCase
{
    public function validateRegexProvider()
    {
        return [
            'valid'       => [
                [
                    'name' => 'field',
                    'spec' => ['regex' => '/[a-b]/i'],
                    'data' => ['field' => 'a']
                ],
                'expected' => true
            ],
            'notValid'    => [
                [
                    'name' => 'field',
                    'spec' => ['regex' => '/[a-b]/i'],
                    'data' => ['field' => '9']
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
            ]
        ];
    }

    /**
     * @dataProvider validateRegexProvider
     */
    public function testValidateRegex($data, $expected)
    {
        $validator = new Validator();
        $result = $validator->matchesRegex($data['name'], $data['spec'],
            $data['data']);
        $this->assertEquals($expected, $result);
    }


}