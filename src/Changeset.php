<?php

/**
 * Changeset that represents Git staged area
 *
 * PHP version 5
 *
 * @category  DiffSniffer
 * @package   DiffSniffer
 * @author    Sergei Morozov <morozov@tut.by>
 * @copyright 2014 Sergei Morozov
 * @license   http://mit-license.org/ MIT Licence
 * @link      http://github.com/morozov/diff-sniffer-pre-commit
 */
namespace DiffSniffer\PreCommit;

use DiffSniffer\Changeset as ChangesetInterface;
use DiffSniffer\Exception\RuntimeException;

/**
 * Changeset that represents Git staged area
 *
 * PHP version 5
 *
 * @category  DiffSniffer
 * @package   DiffSniffer
 * @author    Sergei Morozov <morozov@tut.by>
 * @copyright 2017 Sergei Morozov
 * @license   http://mit-license.org/ MIT Licence
 * @link      http://github.com/morozov/diff-sniffer-pre-commit
 */
final class Changeset implements ChangesetInterface
{
    /**
     * CLI utilities
     *
     * @var Cli
     */
    private $cli;

    /**
     * Git working directory
     *
     * @var string
     */
    private $dir;

    /**
     * Constructor
     *
     * @param Cli $cli CLI utilities
     * @param string $cwd Current directory
     * @throws RuntimeException
     */
    public function __construct(Cli $cli, string $cwd)
    {
        $this->cli = $cli;

        $dir = $this->cli->exec(
            $this->cli->cmd('git', 'rev-parse', '--show-toplevel'),
            $cwd
        );

        $this->dir = rtrim($dir);
    }

    /**
     * {@inheritDoc}
     */
    public function getDiff() : string
    {
        return $this->cli->exec(
            $this->cli->pipe(
                $this->cli->cmd('git', 'diff', '--staged', '--numstat'),
                $this->cli->cmd('grep', '-vP', '^0\\t'),
                $this->cli->cmd('cut', '-f3'),
                $this->cli->cmd('xargs', 'git', 'diff', '--staged', '--')
            ),
            $this->dir
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getContents(string $path) : string
    {
        return $this->cli->exec(
            $this->cli->cmd('git', 'show', ':' . $path),
            $this->dir
        );
    }
}