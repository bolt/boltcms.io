<?php


namespace App\VersionControl;


use App\Twig\VersionRequest;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FetchVersionCommand extends Command
{
    protected static $defaultName = 'app:get-latest-version';

    /* @var VersionRequest */
    private $version;

    public function __construct(VersionRequest $versionRequest)
    {
        parent::__construct();
        $this->version = $versionRequest;
    }

    protected function configure()
    {
        $this->setDescription("Checks and updates newest stable version of Bolt");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $this->version->saveVersion();
        $this->version->saveDescription();
        return Command::SUCCESS;
    }
}
