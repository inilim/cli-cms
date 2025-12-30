<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Attribute\AdditionallyAttr;
use Inilim\Tool\VD;
use Inilim\Tool\Arr;
use Inilim\Tool\Refl;
use Inilim\Tool\LarArr;
use Inilim\Tool\Str;

#[\AllowDynamicProperties]
abstract class DynamicEntityAbstract
{
    /**
     */
    private string $__set_set_props = '';

    function hasProp(string $name): bool
    {
        return \str_contains(
            Str::wrap($this->__set_set_props, ','),
            Str::wrap($name, ',')
        );
    }

    /**
     */
    protected function setProp(string $name, mixed $value): static
    {
        $this->$name = $value;
        return $this->setSetProp($name);
    }

    private function removeSetProp(string $name): static
    {
        $t = $this->__set_set_props;
        $t = Str::wrap($t, ',');
        $name = Str::wrap($name, ',');
        $t = \trim(\strtr($t, [$name => '']), ',');
        $this->__set_set_props = $t;
        return $this;
    }

    private function setSetProp(string $name): static
    {
        if (!$this->hasProp($name)) {
            $str = $this->__set_set_props;
            if ($str === '') {
                $str = $name;
            } else {
                $str .= ',' . $name;
            }
            $this->__set_set_props = $str;
        }
        return $this;
    }

    /**
     */
    function removeProp(string $name): static
    {
        $this->$name = null;
        return $this->removeSetProp($name);
    }

    /**
     * @return static[]
     */
    static function fromArrayAll(array &$records): array
    {
        if (!$records) {
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
        foreach ($records as $record) {
            $new = new static;
            $ss = [];
            foreach (LarArr::only($record, $names) as $name => $value) {
                $new->$name = $value;
                $ss[] = $name;
            }
            if (!$ss) {
                throw new \InvalidArgumentException(\sprintf('No valid properties found in record for entity %s', static::class));
            }
            $new->__set_set_props = \implode(',', $ss);
            $results[] = $new;
        }

        return $results;
    }

    static function fromArray(array $record): static
    {
        $arr = [$record];
        return static::fromArrayAll($arr)[0];
    }
}
