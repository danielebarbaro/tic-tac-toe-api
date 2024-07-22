<?php

namespace App\Validator;

use App\Entity\Move;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class CheckMovesValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        $move = $this->context->getObject();

        $game = $move->getGame();

        $lastMove = $game->getMoves()->last();

        if (!$move instanceof Move) {
            return;
        }

        if ($lastMove !== false && $lastMove->getPlayer() === $move->getPlayer()) {
            $this->context
                ->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
