<?php

namespace App\Validator;

use App\Entity\Game;
use App\Enum\GameStatusEnum;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CheckPlayerUpdateValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        $game = $this->context->getObject();

        if (!$game instanceof Game) {
            return;
        }

        $status = GameStatusEnum::NEW;
        if ($game->getStatus() !== $status) {
            $this->context
                ->buildViolation($constraint->message)
                ->atPath('players')
                ->addViolation();
        }
    }
}
