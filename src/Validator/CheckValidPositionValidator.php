<?php

namespace App\Validator;

use App\Entity\Move;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CheckValidPositionValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        $move = $this->context->getObject();

        $game = $move->getGame();
        $board = $game->getBoard();

        if (!$move instanceof Move) {
            return;
        }

        if ($board[$value - 1] !== 0) {
            $this->context
                ->buildViolation($constraint->message)
                ->atPath('position')
                ->addViolation();
        }
    }
}
