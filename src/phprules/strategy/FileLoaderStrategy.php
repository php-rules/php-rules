<?php
namespace phprules\strategy;

/**
 * Strategy pattern for loading a Rule from a text file.
 *
 * @author Greg Swindle <greg@swindle.net>
 * @package phprules.strategy
 */
class FileLoaderStrategy extends AbstractLoaderStrategy
{
    /**
     * Get statements from the given resource.
     *
     * @param  mixed $resource The resource to load from.
     * @return array List of tokens.
     */
    protected function getStatements($resource)
    {
        $lines = array();
        $ruleFile = fopen($resource, 'r');
        if ($ruleFile) {
            while (!feof($ruleFile)) {
                $line = trim(fgets($ruleFile));
                if (!empty($line) && !$this->isComment($line)) {
                    $tokens = preg_split('/[\s,]+/', $line);
                    $lines[] = $tokens;
              }
            }
        } else {
            throw new \Exception('Failed to open stream: ' . $resource . ' does not exist.');
        }
        fclose($ruleFile);

        return $lines;
    }

    /**
     * Check if a line is a comment.
     *
     * @return boolean <code>true</code> if the line is a comment.
     */
    private function isComment($line)
    {
        $line = trim($line);

        return (strstr($line, '#') == $line);
    }

}
