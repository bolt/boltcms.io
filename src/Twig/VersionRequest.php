<?php


namespace App\Twig;


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

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_version', [$this, 'getVersion']),
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
        return file_get_contents($this->projectDir . '/config/extensions/version.text');
    }
}
