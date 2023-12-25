<?php

namespace Teikun86\Tripay\Entities;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;

abstract class Entity implements Arrayable, ArrayAccess
{
    protected $attributes = [];

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    public function __set(string $key, mixed $value): void
    {
        $this->attributes[$key] = $value;
    }

    public function __get(string $key): mixed
    {
        return $this->attributes[$key];
    }
    public function toArray(): array
    {
        $result = [];

        foreach ($this->getAttributes() as $key => $value) {
            if ($value instanceof Entity) {
                $result[$key] = $value->toArray();
            } elseif (is_array($value)) {
                $subArray = [];
                foreach ($value as $subKey => $subValue) {
                    if ($subValue instanceof Entity) {
                        $subArray[$subKey] = $subValue->toArray();
                    } else {
                        $subArray[$subKey] = $subValue;
                    }
                }
                $result[$key] = $subArray;
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function only(array $keys): array
    {
        return collect($this->toArray())->only($keys)->toArray();
    }

    public function except(array $keys): array
    {
        return collect($this->toArray())->except($keys)->toArray();
    }

    public function fill(array $attributes): self
    {
        $this->attributes = array_merge($this->attributes, $attributes);
        return $this;
    }

    public function isEmpty(): bool
    {
        return empty($this->getAttributes());
    }

    public function offsetExists($key): bool
    {
        return isset($this->attributes[$key]);
    }

    public function offsetGet($key): mixed
    {
        return $this->attributes[$key];
    }

    public function offsetSet($key, $value): void
    {
        if (is_null($key)) {
            $this->attributes[] = $value;
        } else {
            $this->attributes[$key] = $value;
        }
    }

    public function offsetUnset($key): void
    {
        unset($this->attributes[$key]);
    }

    public function __unset(string $key): void
    {
        $this->remove($key);
    }

    public function __isset(string $key): bool
    {
        return $this->has($key);
    }

    public function has(string $key): bool
    {
        return isset($this->attributes[$key]);
    }

    public function remove(string $key): void
    {
        unset($this->attributes[$key]);
    }

}
