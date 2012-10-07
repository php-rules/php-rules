<?php
namespace phprules\source;

use InvalidArgumentException;

/**
 * A file source.
 *
 * @author Martin Rademacher <mano@radebatz.net>
 */
class FileSource implements SourceInterface
{
    protected $filename;

    /**
     * Create new instance.
     *
     * @param string filename The source filename.
     */
    public function __construct($filename)
    {
        if (!file_exists($filename)) {
            throw new InvalidArgumentException(sprintf('file %s not found', $filename));
        }
        $this->filename = $filename;
    }

    /**
     * {@inheritDoc}
     */
    public function getData() {
        return file_get_contents($this->filename);
    }

}
