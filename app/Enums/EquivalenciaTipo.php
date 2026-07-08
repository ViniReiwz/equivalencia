<?php

namespace App\Enums;

enum EquivalenciaTipo: string
{
    case AUTOMATICA_EQUIVALENTE = 'automatica_equivalente';
    case AUTOMATICA_DESEQUIVALENTE = 'automatica_desequivalente';
    case SOLICITADA = 'solicitada';

    public function label(): string
    {
        return match ($this) {
            self::AUTOMATICA_EQUIVALENTE => 'Automática equivalente',
            self::AUTOMATICA_DESEQUIVALENTE => 'Automática não equivalente',
            self::SOLICITADA => 'Solicitação do aluno',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::AUTOMATICA_EQUIVALENTE => 'success',
            self::AUTOMATICA_DESEQUIVALENTE => 'danger',
            self::SOLICITADA => 'warning',
        };
    }

    public function isAutomatica(): bool
    {
        return in_array($this, self::automaticas(), true);
    }

    public function isAutomaticaEquivalente(): bool
    {
        return $this === self::AUTOMATICA_EQUIVALENTE;
    }

    public static function automaticas(): array
    {
        return [
            self::AUTOMATICA_EQUIVALENTE,
            self::AUTOMATICA_DESEQUIVALENTE,
        ];
    }

    public static function valoresAutomaticos(): array
    {
        return array_map(fn (self $tipo) => $tipo->value, self::automaticas());
    }

    public static function automaticoPorEquivalencia(bool $equivalente): self
    {
        return $equivalente ? self::AUTOMATICA_EQUIVALENTE : self::AUTOMATICA_DESEQUIVALENTE;
    }
}
