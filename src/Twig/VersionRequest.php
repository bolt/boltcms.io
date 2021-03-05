<?php


namespace App\Twig;


use Github\Client;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Yaml\Yaml;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
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
        $this->githubApi = $this->client->api('repo')->releases()->latest('bolt', 'core');
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_Version', [$this, 'getVersion']),
            new TwigFunction('get_VersionDescription', [$this, 'getVersionDescription']),
        ];
    }

    public function saveVersion()
    {
       $version = $this->githubApi['tag_name'];
        file_put_contents($this->projectDir . '/config/extensions/version' . '.text', $version);
    }

    public function saveDescription()
    {
        $description = $this->githubApi['body'];
        file_put_contents($this->projectDir . '/config/extensions/description' . '.text', $description);
    }

    public function getVersion()
    {
        return file_get_contents($this->projectDir . '/config/extensions/version.text');
    }

    public function getVersionDescription()
    {
        return file_get_contents($this->projectDir . '/config/extensions/description.text');
    }
}
