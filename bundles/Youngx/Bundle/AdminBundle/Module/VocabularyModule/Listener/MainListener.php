<?php

namespace Youngx\Bundle\AdminBundle\Module\VocabularyModule\Listener;

use Youngx\Bundle\AdminBundle\Module\VocabularyModule\Entity\TermEntity;
use Youngx\EventHandler\Registration;
use Youngx\MVC\Context;

class MainListener implements Registration
{
    /**
     * @var Context
     */
    protected $context;

    public function setContext(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @param $vocabularyName
     * @return TermEntity[]
     */
    public function getTerms($vocabularyName)
    {
        $repository = $this->context->repository();
        $vocabulary = $repository->query('vocabulary')->where('name=:name')->one(array(':name' => $vocabularyName));
        if ($vocabulary) {
            return $vocabulary->getTerms();
        }
        return array();
    }

    public function vocabularyInput(array $attributes)
    {
        if (isset($attributes['#vocabulary'])) {
            $options = array();
            foreach ($this->getTerms($attributes['#vocabulary']) as $term) {
                $options[$term->getId()] = $term->getLabel();
            }
            $attributes['#options'] = $options;
            unset($attributes['#vocabulary']);
        }
        return $this->context->input('select', $attributes);
    }

    public static function registerListeners()
    {
        return array(
            'kernel.input#vocabulary' => 'vocabularyInput',
            'kernel.value#terms' => 'getTerms'
        );
    }
}