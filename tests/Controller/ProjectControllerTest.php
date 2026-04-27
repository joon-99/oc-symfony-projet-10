<?php

namespace App\Tests\Controller;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ProjectControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $projectRepository;
    private string $path = '/project/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->projectRepository = $this->manager->getRepository(Project::class);

        foreach ($this->projectRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Project index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first()->text());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'project[title]' => 'Testing',
            'project[startDate]' => 'Testing',
            'project[deadline]' => 'Testing',
            'project[isArchived]' => 'Testing',
            'project[users]' => 'Testing',
            'project[tags]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->projectRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Project();
        $fixture->setTitle('My Title');
        $fixture->setStartDate('My Title');
        $fixture->setDeadline('My Title');
        $fixture->setIsArchived('My Title');
        $fixture->setUsers('My Title');
        $fixture->setTags('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Project');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Project();
        $fixture->setTitle('Value');
        $fixture->setStartDate('Value');
        $fixture->setDeadline('Value');
        $fixture->setIsArchived('Value');
        $fixture->setUsers('Value');
        $fixture->setTags('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'project[title]' => 'Something New',
            'project[startDate]' => 'Something New',
            'project[deadline]' => 'Something New',
            'project[isArchived]' => 'Something New',
            'project[users]' => 'Something New',
            'project[tags]' => 'Something New',
        ]);

        self::assertResponseRedirects('/project/');

        $fixture = $this->projectRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getTitle());
        self::assertSame('Something New', $fixture[0]->getStartDate());
        self::assertSame('Something New', $fixture[0]->getDeadline());
        self::assertSame('Something New', $fixture[0]->getIsArchived());
        self::assertSame('Something New', $fixture[0]->getUsers());
        self::assertSame('Something New', $fixture[0]->getTags());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Project();
        $fixture->setTitle('Value');
        $fixture->setStartDate('Value');
        $fixture->setDeadline('Value');
        $fixture->setIsArchived('Value');
        $fixture->setUsers('Value');
        $fixture->setTags('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/project/');
        self::assertSame(0, $this->projectRepository->count([]));
    }
}
