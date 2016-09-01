<?php
namespace Validator;

class ValidatorTest extends \PHPUnit_Framework_TestCase
{

    public function testEmailFieldPasses()
    {
        $fields['email'] = 'example@typerocket.com';

        $validator = new \TypeRocket\Utility\Validator([
            'email' => 'email'
        ], $fields);

        $this->assertTrue( $validator->passed() );
    }

    public function testDeepEmailFieldPasses()
    {
        $fields['person']['email'] = 'example@typerocket.com';

        $validator = new \TypeRocket\Utility\Validator([
            'person.email' => 'email'
        ], $fields);

        $this->assertTrue( $validator->passed() );
    }

    public function testDeepMultipleEmailsFieldPasses()
    {
        $fields['person'][1]['email'] = 'example@typerocket.com';
        $fields['person'][2]['email'] = 'example2.1@typerocket.com';

        $validator = new \TypeRocket\Utility\Validator([
            'person.*.email' => 'email'
        ], $fields);

        $this->assertEquals(2, count($validator->getPasses()) );
    }

    public function testDeepMultipleEmailsFieldFailing()
    {
        $fields['person'][1]['email'] = 'example@typerocket';
        $fields['person'][2]['email'] = 'e@example2.1@typerocket.com';
        $fields['person'][3]['email'] = 'example2.1typerocket.com';

        $validator = new \TypeRocket\Utility\Validator([
            'person.*.email' => 'email'
        ], $fields);

        $this->assertEquals(3, count($validator->getErrors()) );
    }

    public function testDeepCallbackFieldPassing()
    {
        $fields['person'][1]['email'] = 'example@typerocket';
        $fields['person'][2]['email'] = 'e@example2.1@typerocket.com';
        $fields['person'][3]['email'] = 'example2.1typerocket.com';

        function checkValidatorCallback($validator, $field, $option2)
        {
            return ['success' => $field . ' is good'];
        }

        $validator = new \TypeRocket\Utility\Validator([
            'person.*.email' => 'callback:\Validator\checkValidatorCallback:option'
        ], $fields);

        $this->assertEquals(3, count($validator->getPasses()) );
    }

    public function testDeepCallbackFieldFailing()
    {
        $fields['person'][1]['email'] = 'example@typerocket';
        $fields['person'][2]['email'] = 'e@example2.1@typerocket.com';
        $fields['person'][3]['email'] = 'example2.1typerocket.com';

        function checkValidatorCallbackError($validator, $field, $option2)
        {
            return ['error' => $field . ' is good'];
        }

        $validator = new \TypeRocket\Utility\Validator([
            'person.*.email' => 'callback:\Validator\checkValidatorCallbackError:option'
        ], $fields);

        $this->assertEquals(3, count($validator->getErrors()) );
    }

}