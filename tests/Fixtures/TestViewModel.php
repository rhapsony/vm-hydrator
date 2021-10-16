<?php

declare(strict_types=1);

namespace Rhapsony\ViewModelHydrator\Tests\Fixtures;

use DateTime;
use Rhapsony\ViewModelHydrator\Tests\AbstractTestCase;

/**
 * @todo after the release of Symfony 5.4: remove initial property values
 */
class TestViewModel
{
    private string $string = '';
    private int $int = 0;
    private float $float = 0;
    private bool $bool = false;
    private DateTime $dateTime;
    private TestNestedViewModel $nestedViewModel;
    /**
     * @var TestNestedViewModel[]
     */
    private array $simpleArray = [];

    public function __construct()
    {
        $this->dateTime = new DateTime(AbstractTestCase::DATETIME_INITIAL);
        $this->nestedViewModel = new TestNestedViewModel();
    }

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

    /**
     * @return DateTime
     */
    public function getDateTime(): DateTime
    {
        return $this->dateTime;
    }

    /**
     * @param DateTime $dateTime
     * @return $this
     */
    public function setDateTime(DateTime $dateTime): self
    {
        $this->dateTime = $dateTime;
        return $this;
    }

    /**
     * @return TestNestedViewModel
     */
    public function getNestedViewModel(): TestNestedViewModel
    {
        return $this->nestedViewModel;
    }

    /**
     * @return TestNestedViewModel[]
     */
    public function getSimpleArray(): array
    {
        return $this->simpleArray;
    }

    /**
     * @param TestNestedViewModel[] $simpleArray
     * @return $this
     */
    public function setSimpleArray(array $simpleArray): self
    {
        $this->simpleArray = $simpleArray;
        return $this;
    }
}