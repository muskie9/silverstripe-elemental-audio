<?php

namespace Ishannz\Elements\Audio\Elements;

use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\File;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\OptionsetField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;
use SilverStripe\Core\Config\Config;

class ElementAudio extends BaseElement
{
    /**
     * @var string
     */
    private static $singular_name = 'Audio Element';

    /**
     * @var string
     */
    private static $plural_name = 'Audio Elements';

    /**
     * @var string
     */
    private static $description = 'Audio controller with audio transcript';

    /**
     * @var string
     */
    private static $table_name = 'ElementAudio';

    /**
     * @var array
     */
    private static $db = [
        'AudioType'       => 'Enum("Upload, Embed")',
        'Title'           => 'Varchar(255)',
        'AudioSummary'    => 'Varchar(255)',
        'TranscriptTitle' => 'Varchar(255)',
        'Transcript'      => 'HTMLText',
        'EmbedCode'       => 'Text',
    ];

    /**
     * @var array
     */
    private static $has_one = [
        'Audio' => File::class,
    ];

    /**
     * @var [type]
     */
    private static $defaults = [
        'AudioType' => 'Upload',
    ];

    /**
     * @var array
     */
    private static $casting = [
        'AudioEmbedCode' => 'HTMLText'
    ];

    /**
     * @var array
     */
    private static $allowed_extenstions = [
        'aif',
        'aifc',
        'aiff',
        'apl',
        'au',
        'avr',
        'cda',
        'm4a',
        'mid',
        'midi',
        'mp3',
        'ogg',
        'ra',
        'ram',
        'rm',
        'snd',
        'wav',
        'wma',
    ];

    /**
     * @var int
     */
    private static $audio_summary_max_length = 200;

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = FieldList::create(
            TextField::create(
                'Title',
                _t(__CLASS__ . '.Title','Audio block name')
            ),
            TextareaField::create(
                'AudioSummary',
                _t(__CLASS__ . '.AudioSummary','Audio summary')
            )
                ->setDescription(_t(__CLASS__ . '.AudioSummaryDescription','Short summary introducing the audio.
                        <strong>Up to 200 characters.</strong>'))
                ->setMaxLength(Config::inst()->get(ElementAudio::class, 'audio_summary_max_length')),
            OptionsetField::create(
                _t(__CLASS__ . '.AudioType','AudioType'),
                'Audio type',
                $this->dbObject('AudioType')->enumValues()
            ),

            /**
             * Only allowed the audio extensions which are allowed in the CMS.
             * See {@link File::app_categories}
             */
            $uploadField = UploadField::create(
                'Audio',
                _t(__CLASS__ . '.Audio', 'Audio file')
            )
                ->setAllowedExtensions(Config::inst()->get(ElementAudio::class, 'allowed_extenstions'))
                ->setDescription(_t(__CLASS__ . '.AudioDescription','Select an audio file from the Files section. 
                                Allow formats: aif, aifc, aiff, apl, au, avr, cda, m4a, 
                                mid, midi, mp3, ogg, ra, ram, rm, snd, wav, wma.'))
                ->setUploadEnabled(false),

            $embedCode = TextareaField::create(
                'EmbedCode',
                _t(__CLASS__ . '.EmbedCode', 'Audio embed code')
            ),

            TextField::create(
                'TranscriptTitle',
                _t(__CLASS__ . '.TranscriptTitle',
                    'Audio transcript heading')
            ),

            HTMLEditorField::create(
                'Transcript',
                _t(__CLASS__ . '.Transcript', 'Transcript content')
            )
        );

        $uploadField->displayIf('AudioType')->isEqualTo('Upload');
        $embedCode->displayIf('AudioType')->isEqualTo('Embed');

        return $fields;
    }

    /**
     * Check uploading valid audio file.
     *
     * @return \SilverStripe\ORM\ValidationResult
     */
    public function validate()
    {
        $result = parent::validate();

        if ($this->AudioType == 'Upload' && $this->Audio()->appCategory() != 'audio') {
            $result->addError('Invalid audio file.');
        }

        return $result;
    }

    /**
     * Convert text to html
     *
     * @return string
     */
    public function getAudioEmbedCode()
    {
        return $this->EmbedCode;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return _t(__CLASS__ . '.BlockType', 'Audio');
    }
}
