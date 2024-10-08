<?php

namespace App\DBAL\Types;

use App\Enum\GameStatusEnum;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class GameStatusEnumType extends Type
{
    public const NAME = 'game_status_enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return 'VARCHAR(255)';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?GameStatusEnum
    {
        return !empty($value) ? GameStatusEnum::from($value) : null;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof GameStatusEnum ? $value->value : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
