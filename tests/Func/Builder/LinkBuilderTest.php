<?php

namespace App\Tests\Func\Builder;

use App\Builder\BuildingStrategy\ImageStrategy;
use App\Builder\BuildingStrategy\VideoStrategy;
use App\Builder\LinkBuilder;
use App\Exception\ProviderNotSupportedException;
use App\Exception\MediaTypeNotSupportedException;
use App\Extractor\EmbedBasedExtractor;
use App\Model\Link;
use Embed\Embed;
use Embed\Http\NetworkException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Builder\LinkBuilder
 * todo add unit tests
 */
class LinkBuilderTest extends TestCase
{
    public function testBuildThrowAnExceptionWhenUrlIsEmpty()
    {
        $builder = new LinkBuilder(new EmbedBasedExtractor(new Embed()));
        $this->expectException(NetworkException::class);
        $builder->build(new Link(''), []);
    }

    public function testBuildThrowAnExceptionWhenUrlIsNotReachable()
    {
        $builder = new LinkBuilder(new EmbedBasedExtractor(new Embed()));
        $this->expectException(NetworkException::class);
        $this->expectExceptionMessage('Could not resolve host: bla');
        $builder->build(new Link('https://bla'), []);
    }

    public function testBuildThrowAnExceptionWhenProviderIsNotSupported()
    {
        $builder = new LinkBuilder(new EmbedBasedExtractor(new Embed()));
        $this->expectException(ProviderNotSupportedException::class);
        $this->expectExceptionMessage('The provider : Google is not supported.');
        // The provider google is not supported
        $builder->build(new Link('https://www.google.fr'), []);
    }

    public function testBuildThrowAnExceptionWhenProviderIsSupportedButMediaNotSupported()
    {
        $builder = new LinkBuilder(new EmbedBasedExtractor(new Embed()));
        $this->expectException(MediaTypeNotSupportedException::class);
        $this->expectExceptionMessage('The media type is not supported.');
        // we are on Vimeo provider but on homepage without media
        $builder->build(new Link('https://vimeo.com/fr'), []);
    }

    public function testBuildThrowAnExceptionWhenExtractorIsValidWithoutMediaBuildingStrategy()
    {
        $builder = new LinkBuilder(new EmbedBasedExtractor(new Embed()));
        $link = $builder->build(new Link('https://www.flickr.com/photos/adrianafuchter/10337055524/in/pool-1665021@N22/'), []);
        // todo complete other attributes assertions
        $this->assertSame('Flickr', $link->getProvider());
        // without strategy media type are null
        $this->assertNull( $link->getImage());
        $this->assertNull($link->getVideo());
    }

    public function testBuildThrowAnExceptionWhenExtractorIsValidWithMediaBuildingStrategy()
    {
        $builder = new LinkBuilder(new EmbedBasedExtractor(new Embed()));
        $link = $builder->build(
            new Link('https://www.flickr.com/photos/adrianafuchter/10337055524/in/pool-1665021@N22/'),
            [
                new ImageStrategy(),
                new VideoStrategy()
            ]
        );
        // todo complete other attributes assertions
        $this->assertSame('Flickr', $link->getProvider());
        // image media type is not null in this case
        $this->assertNotNull($link->getImage());
        // video is null
        $this->assertNull($link->getVideo());
    }
}
