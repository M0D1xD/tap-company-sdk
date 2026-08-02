<?php

declare(strict_types=1);

namespace TapCompany\LaravelSdk\Data;

use ArrayAccess;
use Countable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

/**
 * @implements ArrayAccess<string, mixed>
 * @implements Arrayable<string, mixed>
 */
class TapObject implements ArrayAccess, Arrayable, Countable, Jsonable, JsonSerializable
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function __construct(protected array $attributes = [])
    {
    }

    public function __get(string $key): mixed
    {
        return $this->get($key);
    }

    public function __isset(string $key): bool
    {
        return $this->offsetExists($key);
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $value = $this->attributes[$key] ?? $default;

        if (is_array($value)) {
            return new self($value);
        }

        return $value;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->attributes;
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options) ?: '{}';
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->attributes;
    }

    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists($offset, $this->attributes);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->get((string) $offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($offset === null) {
            $this->attributes[] = $value;

            return;
        }

        $this->attributes[(string) $offset] = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->attributes[(string) $offset]);
    }

    public function count(): int
    {
        return count($this->attributes);
    }

    public function id(): ?string
    {
        $id = $this->attributes['id'] ?? null;

        return is_string($id) ? $id : null;
    }
}
