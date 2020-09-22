<?php

namespace App\Twig;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ContributorsExtension extends AbstractExtension
{
    public const GH_ENDPOINT = 'https://api.github.com';
    public const GH_CONTRIBUTORS = 'repos/%s/%s/contributors';
    public const CACHE_DURATION = 14400; // 4 hours

    /** @var Client */
    private $client;

    /** @var CacheInterface */
    private $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->client = new Client();
        $this->cache = $cache;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('github_contributors', [$this, 'getGithubContributors']),
        ];
    }

    public function getGithubContributors(string $owner, string $repo): ?array
    {
        return $this->cache->get('gh_' . $owner . '_' . $repo, function (ItemInterface $item) use ($owner, $repo){
            $item->expiresAfter(self::CACHE_DURATION);
            return $this->fetchGhContributors($owner, $repo);
        });
    }

    private function fetchGhContributors(string $owner, string $repo): ?array
    {
        try {
            $response = $this->client->request('GET',self::getGhContributorsEndpoint($owner, $repo));
            $result = json_decode($response->getBody());
        } catch (GuzzleException $e) {
            $result = null;
        }

        return $result;
    }

    private static function getGhContributorsEndpoint(string $owner, string $repo): string
    {
        $relative = sprintf(self::GH_CONTRIBUTORS, $owner, $repo);
        return sprintf('%s/%s', self::GH_ENDPOINT, $relative);
    }
}