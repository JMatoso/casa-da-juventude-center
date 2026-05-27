<?php

declare(strict_types=1);

namespace App\Helpers;

final class Validator
{
    /** @var array<string, string> */
    private array $errors = [];

    /** @param array<string, mixed> $data */
    public function validate(array $data, array $rules): bool
    {
        $this->errors = [];

        foreach ($rules as $field => $ruleString) {
            $value = $data[$field] ?? null;
            $ruleList = explode('|', $ruleString);

            foreach ($ruleList as $rule) {
                if ($rule === 'required' && ($value === null || $value === '')) {
                    $this->errors[$field] = 'Este campo é obrigatório.';
                    break;
                }
                if (str_starts_with($rule, 'max:') && is_string($value)) {
                    $max = (int) substr($rule, 4);
                    if (mb_strlen($value) > $max) {
                        $this->errors[$field] = "Máximo de {$max} caracteres.";
                        break;
                    }
                }
                if ($rule === 'email' && $value !== null && $value !== '' && !filter_var((string) $value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field] = 'Email inválido.';
                    break;
                }
                if ($rule === 'numeric' && $value !== null && $value !== '' && !is_numeric($value)) {
                    $this->errors[$field] = 'Deve ser um número válido.';
                    break;
                }
                if ($rule === 'date' && $value !== null && $value !== '') {
                    $d = \DateTime::createFromFormat('Y-m-d', (string) $value);
                    if (!$d || $d->format('Y-m-d') !== $value) {
                        $this->errors[$field] = 'Data inválida.';
                        break;
                    }
                }
            }
        }

        return $this->errors === [];
    }

    /** @return array<string, string> */
    public function errors(): array
    {
        return $this->errors;
    }

    public function firstError(): string
    {
        return reset($this->errors) ?: 'Dados inválidos.';
    }
}
