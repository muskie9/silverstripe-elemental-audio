<?php

namespace Ishannz\Elements\Audio\Test\Elements;

use Ishannz\Elements\Audio\Elements\ElementAudio;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;

class ElementSlideshowTest extends SapphireTest
{
    /**
     * @var string
     */
    protected static $fixture_file = '../fixtures.yml';

    /**
     * Tests getCMSFields().
     */
    public function testGetCMSFields()
    {
        $object = $this->objFromFixture(ElementAudio::class, 'audio_upload');
        $this->assertInstanceOf(FieldList::class, $object->getCMSFields());
    }

    /**
     *
     */
    public function testGetElementEmbedCode()
    {
        $object = $this->objFromFixture(ElementAudio::class, 'audio_embed');
        $this->assertEquals($object->getAudioEmbedCode(), $object->EmbedCode);

        $object = $this->objFromFixture(ElementAudio::class, 'audio_upload');
        $this->assertNull($object->getAudioEmbedCode());
    }

    /**
     *
     */
    public function testGetType()
    {
        $object = $this->objFromFixture(ElementAudio::class, 'audio_upload');
        $this->assertEquals($object->getType(), 'Audio');
    }
}
