<?php

declare(strict_types=1);

namespace Rhapsony\ViewModelHydrator\Tests\Fixtures;

/**
 * @todo after the release of Symfony 5.4: remove initial property values
 */
class TestNestedViewModel
{
    private string $string = '';
    private int $int = 0;
    private float $float = 0;
    private bool $bool = false;

    /**
     * @return string
     */
    public function getString(): string
    {
        return $this->string;
    }

    /**
     * @param string $string
     * @return $this
     */
    public function setString(string $string): self
    {
        $this->string = $string;
        return $this;
    }

    /**
     * @return int
     */
    public function getInt(): int
    {
        return $this->int;
    }

    /**
     * @param int $int
     * @return $this
     */
    public function setInt(int $int): self
    {
        $this->int = $int;
        return $this;
    }

    /**
     * @return float
     */
    public function getFloat(): float
    {
        return $this->float;
    }

    /**
     * @param float $float
     * @return $this
     */
    public function setFloat(float $float): self
    {
        $this->float = $float;
        return $this;
    }

    /**
     * @return bool
     */
    public function isBool(): bool
    {
        return $this->bool;
    }

    /**
     * @param bool $bool
     * @return $this
     */
    public function setBool(bool $bool): self
    {
        $this->bool = $bool;
        return $this;
    }
}