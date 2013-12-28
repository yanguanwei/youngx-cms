<?php

namespace Youngx\Bundle\AdminBundle\Module\VocabularyModule\Controller;

use Youngx\Bundle\AdminBundle\Module\VocabularyModule\Entity\TermEntity;
use Youngx\Bundle\AdminBundle\Module\VocabularyModule\Entity\VocabularyEntity;
use Youngx\MVC\Context;

class TermAdminController
{
    public function indexAction(Context $context, VocabularyEntity $vocabulary)
    {
        return $context->actionResponse('Table:TermAdmin@Admin:Vocabulary', array(
                'vocabulary' => $vocabulary
            ));
    }

    public function addAction(Context $context, VocabularyEntity $vocabulary)
    {
        return $context->actionResponse('Form:TermAdmin@Admin:Vocabulary', array(
                'vocabulary' => $vocabulary
            ));
    }

    public function editAction(Context $context, TermEntity $term)
    {
        return $context->actionResponse('Form:TermAdmin@Admin:Vocabulary', array(
                'term' => $term
            ));
    }

    public function deleteAction(Context $context)
    {
        return $context->actionResponse('Action:TermDelete@Admin:Vocabulary');
    }
}