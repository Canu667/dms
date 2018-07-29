<?php

namespace App\Form\Util;

use Symfony\Component\Form\FormInterface;

class FormHelper
{
    public function convertErrors(FormInterface $form): string
    {
        return $this->convertErrorsAsString($form);
    }

    private function convertErrorsAsString(FormInterface $form): string
    {
        $errors = [];
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $child) {
            $error = $this->convertErrorsAsString($child);
            if (\strlen($error) > 0) {
                $errors[] = $error;
            }
        }

        return implode(',', $errors);
    }
}
