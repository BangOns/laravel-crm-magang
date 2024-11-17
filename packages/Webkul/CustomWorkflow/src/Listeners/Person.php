<?php

namespace Webkul\CustomWorkflow\Listeners;

use Webkul\CustomWorkflow\Contact\CustomWorkflow;

class Person
{
    public function update($person)
    {
        Person::queue(new CustomWorkflow($person->user));
    }
}
