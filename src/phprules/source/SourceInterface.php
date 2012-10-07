<?php
namespace phprules\source;

/**
 * Rule or context source.
 *
 * @author Martin Rademacher <mano@radebatz.net>
 */
interface SourceInterface
{
    /**
     * Get the data from this source.
     *
     * @return String The data.
     */
    public function getData();

}
