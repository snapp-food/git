<?php declare(strict_types=1);

namespace DiffSniffer\Git\ContentSource;

use DiffSniffer\Git\ContentSource;
use DiffSniffer\Git\Cli;

/**
 * Commit content source
 */
class Commit implements ContentSource
{
    /**
     * @var Cli
     */
    private $cli;

    /**
     * @var string
     */
    private $dir;

    /**
     * @var string
     */
    private $commit;

    /**
     * Constructor
     *
     * @param Cli $cli
     * @param string $dir
     * @param string $commit
     */
    public function __construct(Cli $cli, string $dir, string $commit)
    {
        $this->cli = $cli;
        $this->dir = $dir;
        $this->commit = $commit;
    }

    /**
     * {@inheritDoc}
     */
    public function getContents(string $path) : string
    {
        return $this->cli->exec(
            $this->cli->cmd('git', 'show', $this->commit. ':' . $path),
            $this->dir
        );
    }
}
