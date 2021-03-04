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

    public function showVersion()
    {
        $listVersion = '';
        $client = HttpClient::create();
        $url = 'https://repo.packagist.org/p2/bolt/core.json';
        $version = '';
        try {
            $version = $client->request('GET', $url);
        } catch (TransportExceptionInterface $e) {
        }
        try {
            $listVersion = $version->getContent();
        } catch (ClientExceptionInterface | RedirectionExceptionInterface | ServerExceptionInterface | TransportExceptionInterface $e) {
        }
        $json = json_decode($listVersion, true);
        $yaml = Yaml::dump($json, 6);
        file_put_contents($this->projectDir . '/config/extensions/packagist' . '.yaml', $yaml);

        $versionYaml = Yaml::parse(file_get_contents($this->projectDir . '/config/extensions/packagist.yaml'));

        $showVersions = $versionYaml['packages']['bolt/core'];
        foreach ($showVersions as $showVersion) {
            if (!str_contains($showVersion['version'], 'beta')) {
                file_put_contents($this->projectDir . '/config/extensions/version' . '.text', $showVersion['version']);
                break;
            }
        }
    }

    public function getVersion()
    {
        return $this->githubApi['tag_name'];
    }

    public function getVersionDescription()
    {
        return $this->githubApi['body'];
    }
}
