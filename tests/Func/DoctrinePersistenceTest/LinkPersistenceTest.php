<?php

declare(strict_types=1);

namespace App\Tests\Func\DoctrinePersistenceTest;

use App\Model\Link;
use App\Model\MediaType\Image;
use App\Model\MediaType\Video;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class LinkPersistenceTest extends KernelTestCase
{
    private ?EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testSaveLinkForVideoMediaType()
    {
        // todo DRY : use class attribute and init on setup to avoid repeat
        $provider = 'bla bla';
        $author = 'foo';
        $title = 'bar';
        $height = 100;
        $width = 200;
        $duration = 222;
        $url = 'urlHello';
        $createdAt = new \DateTime('2021-11-02');
        $publishedAt = new \DateTime('2018-11-02');

        $video = new Video();
        $video->setHeight($height)
            ->setWidth($width)
            ->setDuration($duration);

        $link = new Link($url);
        $link->setCreatedAt($createdAt)
            ->setPublishedAt($publishedAt)
            ->setAuthor($author)
            ->setTitle($title)
            ->setProvider($provider)
            ->setVideo($video);

        $this->entityManager->persist($link);
        $this->entityManager->flush();

        $repository = $this->entityManager->getRepository(Link::class);

        /** @var Link $fetchedLink */
        $fetchedLink = $repository->findOneBy(
            [
                'url' => $url,
            ]
        );

        // todo DRY : extract method for assertion and use dataProvider
        $this->assertSame($provider, $fetchedLink->getProvider());
        $this->assertSame($title, $fetchedLink->getTitle());
        $this->assertSame($author, $fetchedLink->getAuthor());
        $this->assertSame($url, $fetchedLink->getUrl());
        $this->assertSame($createdAt->format('Y-m-d'), $fetchedLink->getCreatedAt()->format('Y-m-d'));
        $this->assertSame($publishedAt->format('Y-m-d'), $fetchedLink->getPublishedAt()->format('Y-m-d'));
        $this->assertSame($height, $fetchedLink->getVideo()->getHeight());
        $this->assertSame($width, $fetchedLink->getVideo()->getWidth());
        $this->assertSame($duration, $fetchedLink->getVideo()->getDuration());
        // we assert that image is null when video is setted
        $this->assertSame(null, $fetchedLink->getImage());
    }

    public function testSaveLinkForImageMediaType()
    {
        // todo DRY : use class attribute and init on setup to avoid repeat
        $provider = 'bla bla';
        $author = 'foo';
        $title = 'bar';
        $height = 100;
        $width = 200;
        $url = 'urlHello';

        $createdAt = new \DateTime('2021-11-02');
        $publishedAt = new \DateTime('2018-11-02');

        $image = new Image();
        $image->setHeight($height)
            ->setWidth($width);

        $link = new Link($url);
        $link->setCreatedAt($createdAt)
            ->setPublishedAt($publishedAt)
            ->setAuthor($author)
            ->setTitle($title)
            ->setProvider($provider)
            ->setImage($image);

        $this->entityManager->persist($link);
        $this->entityManager->flush();

        $repository = $this->entityManager->getRepository(Link::class);

        /** @var Link $fetchedLink */
        $fetchedLink = $repository->findOneBy(
            [
                'url' => $url,
            ]
        );

        // todo DRY : extract method for assertion and use dataProvider
        $this->assertSame($provider, $fetchedLink->getProvider());
        $this->assertSame($title, $fetchedLink->getTitle());
        $this->assertSame($author, $fetchedLink->getAuthor());
        $this->assertSame($url, $fetchedLink->getUrl());
        $this->assertSame($createdAt->format('Y-m-d'), $fetchedLink->getCreatedAt()->format('Y-m-d'));
        $this->assertSame($publishedAt->format('Y-m-d'), $fetchedLink->getPublishedAt()->format('Y-m-d'));
        $this->assertSame($height, $fetchedLink->getImage()->getHeight());
        $this->assertSame($width, $fetchedLink->getImage()->getWidth());
        // we assert that video is null when image is setted
        $this->assertSame(null, $fetchedLink->getVideo());
    }

    public function testRemoveLink()
    {
        // todo DRY : use class attribute and init on setup to avoid repeat
        $provider = 'bla bla';
        $author = 'foo';
        $title = 'bar';
        $height = 100;
        $width = 200;
        $url = 'urlHello';

        $createdAt = new \DateTime('2021-11-02');
        $publishedAt = new \DateTime('2018-11-02');

        $image = new Image();
        $image->setHeight($height)
            ->setWidth($width);

        $link = new Link($url);
        $link->setCreatedAt($createdAt)
            ->setPublishedAt($publishedAt)
            ->setAuthor($author)
            ->setTitle($title)
            ->setProvider($provider)
            ->setImage($image);

        $this->entityManager->persist($link);
        $this->entityManager->flush();

        $linkRepository = $this->entityManager->getRepository(Link::class);
        $imageRepository = $this->entityManager->getRepository(Image::class);

        /** @var Link $fetchedLink */
        $fetchedLink = $linkRepository->findOneBy(
            [
                'url' => $url,
            ]
        );

        $this->assertSame(1,
            $linkRepository->count(
                [
                    'url' => $url,
                ]
            )
        );

        $this->entityManager->remove($fetchedLink);
        $this->entityManager->flush();

        $this->assertSame(0,
            $linkRepository->count(
                [
                    'url' => $url,
                ]
            )
        );
        // we assert that image is removed by cascade
        $this->assertSame(0,
            $imageRepository->count(
                [
                    'width' => $width,
                ]
            )
        );
    }
}
