<?php
namespace App\Models;
use Phalcon\Validation;
use Phalcon\Validation\Validator\InclusionIn;
use Phalcon\Validation\Validator\Uniqueness;
use Phalcon\Messages\Message;

class Empuser extends \Phalcon\Mvc\Model
{
    public function validation()
    {
        $validator = new Validation();
        
        $validator->add(
            "firstname",
            new InclusionIn(
                [
                    'message' => 'takahashi ka yamada no dochi ka daro!',
                    'domain' => [
                        'takahashi2',
                        'yamada'
                    ]
                ]
            )
        );

        $validator->add(
            'lastname',
            new Uniqueness(
                [
                    'field'   => 'lastname',
                    'message' => 'The robot name must be unique',
                ]
            )
        );

        if ($this->age < 0) {
            $this->appendMessage(
                new Message('The age cannot be less than zero')
            );
        }

        if ($this->validationHasFailed() === true) {
            return false;
        }
    }
}
