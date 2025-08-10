<?php

namespace Hexlet\Code;

use Valitron\Validator;

class UrlValidator
{
    public function validateUrl(array $data): array
    {
        $errors = [];

        $validator = new Validator($data);
        $validator->rule('required', 'url.name')->message('URL не должен быть пустым');
        $validator->rule('url', 'url.name')->message('Некорректный URL');

        if (!$validator->validate()) {
            $errorsList = $validator->errors();
            $errors['url'] = $errorsList['url.name'][0] ?? '';
        }

        return $errors;
    }
}
