<?php

namespace Youngx\Bundle\AdminBundle\Module\VocabularyModule\Controller;

use Youngx\Bundle\AdminBundle\Module\VocabularyModule\Entity\VocabularyEntity;
use Youngx\MVC\Context;

class AdminController
{
    public function indexAction(Context $context)
    {
        return $context->actionResponse('Table:VocabularyAdmin@Admin:Vocabulary');
    }

    public function addAction(Context $context)
    {
        return $context->actionResponse('Form:VocabularyAdmin@Admin:Vocabulary');
    }

    public function editAction(Context $context, VocabularyEntity $vocabulary)
    {
        return $context->actionResponse('Form:VocabularyAdmin@Admin:Vocabulary', array(
                'vocabulary' => $vocabulary
            ));
    }

    public function deleteAction(Context $context)
    {
        return $context->actionResponse('Action:VocabularyDelete@Admin:Vocabulary');
    }
}