<?php

declare(strict_types=1);

namespace Rhapsony\ViewModelHydrator;

use Rhapsony\ViewModelHydrator\Exception\UnsupportedFetchModeException;
use Rhapsony\ViewModelHydrator\Exception\ViewModelNotInitializedException;

/**
 * @template T
 */
interface ViewModelHydratorInterface
{
    public const FETCH_COLLECTION = 'collection';
    public const FETCH_COLUMN = 'column';
    public const FETCH_ROW = 'row';

    /**
     * Starts a new hydration process from a view model class string.
     *
     * @param class-string<T> $viewModelClass
     * @return $this
     */
    public function start(string $viewModelClass): self;

    /**
     * Starts a new hydration process for an existing view model object.
     *
     * @param object|T $viewModel
     * @return $this
     */
    public function startWith(object $viewModel): self;

    /**
     * Add another step in the hydration process.
     *
     * @return $this
     * @throws ViewModelNotInitializedException
     */
    public function next(): self;

    /**
     * Sets the SQL query to use.
     *
     * @param string|null $query
     * @return $this
     */
    public function query(?string $query = null): self;

    /**
     * Sets the fetch mode for the SQL query.
     *
     * @param string $mode See class constants FETCH_*
     * @return $this
     * @throws UnsupportedFetchModeException
     */
    public function fetchMode(string $mode = self::FETCH_ROW): self;

    /**
     * Bind a parameter to the SQL query.
     *
     * @param string $param
     * @param $value
     * @return $this
     */
    public function param(string $param, $value): self;

    /**
     * Binds parameters to the SQL query.
     *
     * @param array $params
     * @return $this
     */
    public function params(array $params): self;

    /**
     * Sets a parameter type for the SQL query.
     *
     * @param string $param
     * @param $type
     * @return $this
     */
    public function type(string $param, $type): self;

    /**
     * Sets the parameter types for the SQL query.
     *
     * @param array $types
     * @return $this
     */
    public function types(array $types): self;

    /**
     * Adds custom data to the hydration process.
     *
     * @param array $data
     * @return $this
     */
    public function data(array $data): self;

    /**
     * Sets the data for a property path directly, without hydration.
     *
     * @param string $propertyPath
     * @param mixed $data
     * @return $this
     */
    public function set(string $propertyPath, $data): self;

    /**
     * Sets the property path of the view model as target of the hydration.
     *
     * @param string|null $propertyPath
     * @return $this
     */
    public function propertyPath(?string $propertyPath = null): self;

    /**
     * Hydrates all existing data into the view model object.
     *
     * @return T
     * @throws ViewModelNotInitializedException
     */
    public function finish(): object;
}