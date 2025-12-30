<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Attribute\AdditionallyAttr;
use Inilim\Tool\VD;
use Inilim\Tool\Arr;
use Inilim\Tool\LarArr;
use Inilim\Tool\Str;

#[\AllowDynamicProperties]
abstract class DynamicEntityAbstract
{
    private const _S = ',';
    /**
     */
    private string $__set_set_props = '';

    function hasProp(string $name): bool
    {
        return \str_contains(
            Str::wrap($this->__set_set_props, self::_S),
            Str::wrap($name, self::_S)
        );
    }

    /**
     */
    function setProp(string $name, mixed $value): static
    {
        $this->$name = $value;
        return $this->setSetProp($name);
    }

    /**
     * @param string|string[] $names
     */
    function removeProps(string|array $names): static
    {
        if (\is_string($names)) {
            $names = [$names];
        }
        foreach ($names as $name) {
            if ($this->hasProp($name)) {
                $this->$name = null;
                $this->removeSetProp($name);
            }
        }
        return $this;
    }

    private function removeSetProp(string $name): static
    {
        $str = $this->__set_set_props;
        $str = Str::wrap($str, self::_S);
        $name = Str::wrap($name, self::_S);
        $str = \strtr($str, [$name => '']);
        $str = \trim($str, self::_S);
        $this->__set_set_props = $str;
        return $this;
    }

    private function setSetProp(string $name): static
    {
        if (!$this->hasProp($name)) {
            $str = $this->__set_set_props;
            if ($str === '') {
                $str = $name;
            } else {
                $str .= self::_S . $name;
            }
            $this->__set_set_props = $str;
        }
        return $this;
    }

    /**
     * @param array<array<string, mixed>> $entities
     * @return static[]
     */
    static function fromArrayAll(array &$entities): array
    {
        if (!$entities) {
            return [];
        }

        // $names = \array_keys(\get_class_vars(static::class));

        $names = Arr::mapFilter(
            new \ReflectionClass(static::class)->getProperties(),
            static function ($prop): ?string {
                if (!$prop->getAttributes(AdditionallyAttr::class)) {
                    return $prop->getName();
                }
                return null;
            }
        );
        /** @var string[] $names */

        $results = [];
        foreach ($entities as $entity) {
            /** @phpstan-ignore-next-line */
            $new = new static;
            $ss = [];
            foreach (LarArr::only($entity, $names) as $name => $value) {
                $new->$name = $value;
                $ss[] = $name;
            }
            if (!$ss) {
                throw new \InvalidArgumentException(\sprintf('No valid properties found in array for entity %s', static::class));
            }
            $new->__set_set_props = \implode(self::_S, $ss);
            $results[] = $new;
        }

        return $results;
    }

    /**
     * @param array<string, mixed> $entity
     * @return static
     */
    static function fromArray(array $entity): static
    {
        $arr = [$entity];
        return static::fromArrayAll($arr)[0];
    }
}
