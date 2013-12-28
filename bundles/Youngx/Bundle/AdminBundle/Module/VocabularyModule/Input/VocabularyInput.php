<?php

namespace Youngx\Bundle\AdminBundle\Module\VocabularyModule\Input;

use Youngx\MVC\Html\SelectHtml;

class VocabularyInput extends SelectHtml
{
    protected $vocabulary;

    public function setVocabulary($vocabulary)
    {
        $this->vocabulary = $vocabulary;

        return $this;
    }

    protected function format()
    {
        $this->context->repository()->query('voc');

        parent::format();
    }
}