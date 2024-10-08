<?php

namespace App\DBAL\Types;

use App\Enum\GameLevelEnum;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class GameLevelEnumType extends Type
{
    public const NAME = 'game_level_enum';

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return 'VARCHAR(255)';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?GameLevelEnum
    {
        return !empty($value) ? GameLevelEnum::from($value) : null;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof GameLevelEnum ? $value->value : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
