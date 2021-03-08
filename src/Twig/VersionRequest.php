<?php


namespace App\Twig;

use Github\Client;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class VersionRequest extends AbstractExtension
{
    /* @var string */
    private $projectDir;

    /* @var Client */
    private $client;

    private $githubApi;


    public function __construct(string $projectDir, Client $client)
    {
        $this->projectDir = $projectDir;
        $this->client = $client;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_version', [$this, 'getVersion']),
            new TwigFunction('get_version_description', [$this, 'getVersionDescription']),
        ];
    }

    private function initGithubApi(): void
    {
        $this->githubApi = $this->client->repository()->releases()->latest('bolt', 'core');
    }

    public function getVersion(): string
    {
        $this->initGithubApi();
        return $this->githubApi['tag_name'];
    }

    public function getVersionDescription(): string
    {
        $this->initGithubApi();
        return $this->githubApi['body'];
    }
}
