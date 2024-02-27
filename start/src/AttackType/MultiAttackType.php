<?php

namespace App\AttackType;

class MultiAttackType implements AttackType
{

    /**
     * @param AttackType[] $attackType
     */
    public function __construct(private array $attackType)
    {
    }

    public function performAttack(int $baseDamage): int
    {
        $type = $this->attackType[array_rand($this->attackType)];

        return $type->performAttack($baseDamage);
    }

}