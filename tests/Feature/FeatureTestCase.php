<?php

declare(strict_types=1);

namespace App\Tests\Feature;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class FeatureTestCase extends WebTestCase
{
    protected function getApiClient(): HttpClientInterface
    {
        $baseUrl = getenv('API_BASE_URL');
        if (!$baseUrl) {
            throw new RuntimeException('Missing "API_BASE_URL" environment variable.');
        }

        return HttpClient::create([
            'base_uri' => $baseUrl,
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);
    }

    /**
     * @template T of object
     * @param class-string<T> $className
     */
    protected function find(string $className, mixed $id): ?object
    {
        $em = $this->getContainer()->get(EntityManagerInterface::class);
        return $em->find($className, $id);
    }

    protected function persist(object ...$entities): void
    {
        $em = $this->getContainer()->get(EntityManagerInterface::class);
        foreach ($entities as $entity) {
            $em->persist($entity);
            $em->flush();
        }
    }

    protected function remove(object $entity): void
    {
        $em = $this->getContainer()->get(EntityManagerInterface::class);
        $em->remove($entity);
        $em->flush();
    }

    /**
     * @template T of EntityRepository
     * @param class-string<T> $className
     * @return T
     */
    protected function repository(string $className): EntityRepository
    {
        return $this->getContainer()
            ->get(EntityManagerInterface::class)
            ->getRepository($className);
    }

    /**
     * @return string[]
     */
    private function tables(): array
    {
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

        $notMappedSuperClassNames = array_filter(
            $entityManager->getConfiguration()->getMetadataDriverImpl()->getAllClassNames(),
            fn (string $class): bool => false === $entityManager->getClassMetadata($class)->isMappedSuperclass,
        );

        return array_map(
            fn (string $class): string => $entityManager->getClassMetadata($class)->getTableName(),
            $notMappedSuperClassNames
        );
    }

    protected function clearDatabase(): void
    {
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);

        $connection = $entityManager->getConnection();
        foreach ($this->tables() as $table) {
            $connection->query(sprintf('TRUNCATE "%s" CASCADE;', $table));
        }

        $entityManager->clear();
    }
}
