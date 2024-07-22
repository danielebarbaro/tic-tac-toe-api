<?php

namespace App\Dto;

use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

final class PlayerUpdateDto
{
    #[Groups(['game:write', 'game:read'])]
    #[Assert\NotBlank(groups: ['game:write'])]
    #[Assert\Range(min: 1, max: 2, groups: ['game:write'])]
    public int $players;
}
