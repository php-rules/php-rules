<?php
namespace phprules\source;

/**
 * A memory source.
 *
 * @author Martin Rademacher <mano@radebatz.net>
 */
class MemorySource implements SourceInterface
{
    protected $data;

    /**
     * Create new instance.
     *
     * @param mixed data The
     */
    public function __construct($data)
    {
        if (is_array($data)) {
            $data = implode("\n", $data);
        }
        $this->data = $data;
    }

    /**
     * {@inheritDoc}
     */
    public function getData() {
        return $this->data;
    }

}
