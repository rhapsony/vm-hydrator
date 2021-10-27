<?php

declare(strict_types=1);

namespace Rhapsony\ViewModelHydrator;

use Rhapsony\ViewModelHydrator\Exception\PropertyPathNotSetException;
use Rhapsony\ViewModelHydrator\Exception\UnsupportedFetchModeException;
use Rhapsony\ViewModelHydrator\Exception\ViewModelNotInitializedException;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * @template T
 * @template-implements ViewModelHydratorInterface<T>
 */
class ViewModelHydrator implements ViewModelHydratorInterface
{
    private QueryExecutorInterface $connection;
    private string $sqlDirectory;

    /**
     * @var object|T
     */
    private ?object $viewModel = null;
    private ?string $query = null;
    private string $fetchMode = self::FETCH_ROW;
    private array $params = [];
    private array $types = [];
    private array $data = [];
    private array $set = [];
    private ?string $propertyPath = null;
    private DenormalizerInterface $denormalizer;

    private $done = [];

    public function __construct(QueryExecutorInterface $connection, DenormalizerInterface $denormalizer, string $sqlDirectory)
    {
        $this->connection = $connection;
        $this->denormalizer = $denormalizer;

        $this->sqlDirectory = $sqlDirectory;
    }

    /**
     * @inheritDoc
     */
    public function start(string $viewModelClass): self
    {
        return $this->startWith(new $viewModelClass);
    }

    /**
     * @inheritDoc
     */
    public function startWith(object $viewModel): self
    {
        $this->viewModel = $viewModel;
        $this->query = null;
        $this->fetchMode = self::FETCH_ROW;
        $this->params = [];
        $this->types = [];
        $this->data = [];
        $this->propertyPath = null;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function next(): self
    {
        return $this->retrieveData()->startWith($this->viewModel);
    }

    /**
     * @inheritDoc
     */
    public function query(?string $query = null): self
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function fetchMode(string $mode = self::FETCH_ROW): self
    {
        $valid = [
            self::FETCH_COLLECTION,
            self::FETCH_COLUMN,
            self::FETCH_ROW
        ];
        if (!in_array($mode, $valid)) {
            throw new UnsupportedFetchModeException('Unsupported fetch mode.');
        }

        $this->fetchMode = $mode;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function param(string $param, $value): self
    {
        $this->params[$param] = $value;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function params(array $params): self
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function type(string $param, $type): self
    {
        $this->types[$param] = $type;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function types(array $types): self
    {
        $this->types = $types;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function data(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function set(string $propertyPath, $data): ViewModelHydratorInterface
    {
        $this->ensureViewModel();
        $this->set[$propertyPath] = $data;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function propertyPath(?string $propertyPath = null): self
    {
        $this->propertyPath = $propertyPath;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function finish(): object
    {
        $this->retrieveData();

        // Hydrate
        $this->denormalizer->denormalize($this->done, get_class($this->viewModel), null, [
            AbstractNormalizer::OBJECT_TO_POPULATE => $this->viewModel,
            AbstractObjectNormalizer::DEEP_OBJECT_TO_POPULATE => true,
            AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
            /* @todo after the release of Symfony 5.4
            AbstractObjectNormalizer::SKIP_UNINITIALIZED_VALUES => true
            */
        ]);
        $this->done = [];

        // Set direct property paths
        if (!empty($this->set)) {
            $accessor = new PropertyAccessor();
            foreach($this->set as $propertyPath => $value) {
                $accessor->setValue($this->viewModel, $propertyPath, $value);
            }
        }

        return $this->viewModel;
    }

    /**
     * @return $this
     * @throws ViewModelNotInitializedException
     */
    private function ensureViewModel(): self
    {
        if (null !== $this->viewModel) return $this;
        throw new ViewModelNotInitializedException(
            'View model not initialized, please start the hydration process with one of the "start" or "startWith" methods.'
        );
    }

    /**
     * Generates the complete data of the current step in the hydration process and merge it with any existing data.
     *
     * @return $this;
     * @throws ViewModelNotInitializedException
     */
    private function retrieveData(): self
    {
        $this->ensureViewModel();
        $data = [];

        $skipData = false;
        if (null !== $this->query) {
            $queryData = $this->executeQuery();
            if (in_array($this->fetchMode, [self::FETCH_COLUMN, self::FETCH_COLLECTION])) {
                if (null === $this->propertyPath) {
                    throw new PropertyPathNotSetException(
                        'A property path is required when fetching a single column or collection.'
                    );
                }
                $data = $queryData;
                $skipData = true;
            } else {
                // Merge queried data into $data
                $data = array_merge($data, $queryData);
            }
        }

        if (!$skipData) {
            // Merge manually set data into $data
            $data = array_merge($data, $this->data);
        }

        // If a propertyPath is set, use it
        if (null !== $this->propertyPath) {
            $root = [];
            $current = &$root;
            $paths = explode('.', $this->propertyPath);
            foreach($paths as $path) {
                $current[$path] = [];
                $current = &$current[$path];
            }
            $current = $data;
            $data = $root;
            unset($current, $path, $paths, $root);
        }

        $this->done = array_merge_recursive($this->done, $data);
        return $this;
    }

    /**
     * Loads the query SQL and retrieves the result.
     *
     * @return mixed
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    protected function executeQuery()
    {
        $path = sprintf('%s/%s.sql', $this->sqlDirectory, $this->query);
        return $this->connection->execute($path, $this->params, $this->types, $this->fetchMode);
    }
}