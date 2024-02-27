<?php

namespace App\Builder;

use App\ArmorType\ArmorType;
use App\ArmorType\IceBlockType;
use App\ArmorType\LeatherArmorType;
use App\ArmorType\ShieldType;
use App\AttackType\AttackType;
use App\AttackType\BowType;
use App\AttackType\FireBoltType;
use App\AttackType\MultiAttackType;
use App\AttackType\TwoHandedSwordType;
use App\Character\Character;
use http\Exception\RuntimeException;

class CharacterBuilder
{
    private int $maxHealth;
    private int $baseDamage;
    /**
     * @var string[] $attackTypes
     */
    private array $attackTypes;
    /**
     * @var string $armorType
     */
    private string $armorType;

    public function setMaxHealth(int $maxHealth): self
    {
        $this->maxHealth = $maxHealth;
        return $this;
    }

    public function setBaseDamage(int $baseDamage): self
    {
        $this->baseDamage = $baseDamage;
        return $this;
    }

    public function setAttackType(string ...$attackTypes): self
    {
        $this->attackTypes = $attackTypes;
        return $this;
    }

    public function setArmorType(string $armorType): self
    {
        $this->armorType = $armorType;
        return $this;
    }

    public function buildCharacter(): Character
    {
        $attackTypes = array_map(fn(string $at) => $this->createAttackType($at), $this->attackTypes);

        if(count($attackTypes) === 1){
            $attackType = $attackTypes[0];
        }else{
            $attackType = new MultiAttackType($attackTypes);
        }

        return new Character(
            $this->maxHealth,
            $this->baseDamage,
            $this->createArmorType(),
            $attackType
        );
    }

    private function createAttackType(string $attackType): AttackType
    {

        return match ($attackType){
            "bow" => new BowType(),
            "fire_bolt" => new FireBoltType(),
            "sword" => new TwoHandedSwordType(),
            default => throw new RuntimeException("Invalid attack type given")
        };
    }

    private function createArmorType(): ArmorType
    {
        return match ($this->armorType){
            "ice_block" => new IceBlockType(),
            "shield" => new ShieldType(),
            "leather" => new LeatherArmorType(),
            default => throw new RuntimeException("Invalid armor type given")
        };
    }
}