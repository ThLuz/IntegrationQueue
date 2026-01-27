<?php

namespace App\Exceptions;

use Exception;

class IntegrationException extends Exception
{
    public static function invalidCpf(): self
    {
        return new self('CPF inválido. Deve conter 11 dígitos.');
    }

    public static function missingExternalId(): self
    {
        return new self('external_id é obrigatório.');
    }

    public static function externalSystemError(): self
    {
        return new self('Erro ao integrar com sistema externo.');
    }
}
