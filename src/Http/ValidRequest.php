<?php

namespace Touch\Http;

use Valitron\Validator;

trait ValidRequest
{
    protected Validator $valitron;
    protected function validate(array|object $data, array $rules): bool
    {
        $data = is_array($data) ? $data : get_object_vars($data);
        $this->valitron = new Validator($data);
        foreach ($rules as $field => $rule) {
            $this->valitron->mapFieldRules($field, $rule);
        }
        return $this->valitron->validate();
    }
}
