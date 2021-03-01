<?php


namespace App\Twig;


use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Yaml\Yaml;
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
            new TwigFunction('show_version', [$this, 'showVersion']),
        ];
    }

    public function showVersion()
    {
        $client = HttpClient::create();

        $client->request();

        $urlInit = curl_init();
        curl_setopt($urlInit, CURLOPT_URL, 'https://repo.packagist.org/p2/bolt/core.json');
        curl_setopt($urlInit, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($urlInit);
        $json = json_decode($data, true);

        $yaml = Yaml::dump($json, 6);
        file_put_contents($this->projectDir . '/config/extensions/version' . '.yaml', $yaml);
        curl_close($urlInit);
    }
}