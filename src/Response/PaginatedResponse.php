<?php

namespace QurbanAli\PESCheck\Response;

class PaginatedResponse implements \IteratorAggregate, \Countable
{

    /**
     * PaginatedResponse constructor.
     *
     * @param int $count
     * @param string|null $next
     * @param string|null $previous
     * @param array $results
     */
    public function __construct(
        protected int $count,
        protected ?string $next,
        protected ?string $previous,
        protected array $results
    )
    {
    }

    /**
     * Get the total count.
     *
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * Get the next page URL.
     *
     * @return string|null
     */
    public function getNext(): ?string
    {
        return $this->next;
    }

    /**
     * Get the previous page URL.
     *
     * @return string|null
     */
    public function getPrevious(): ?string
    {
        return $this->previous;
    }

    /**
     * Get the results.
     *
     * @return array
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->results);
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return count($this->results);
    }
}
