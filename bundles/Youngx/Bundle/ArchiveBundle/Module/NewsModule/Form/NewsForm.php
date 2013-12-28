<?php

namespace Youngx\Bundle\ArchiveBundle\Module\NewsModule\Form;

use Youngx\Bundle\ArchiveBundle\Entity\ArchiveEntity;
use Youngx\Bundle\ArchiveBundle\Form\ArchiveForm;
use Youngx\Bundle\ArchiveBundle\Module\NewsModule\Entity\NewsEntity;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\Widget\FormWidget;

class NewsForm extends ArchiveForm
{
    protected $content;
    /**
     * @var NewsEntity
     */
    protected $news;

    public function setNews(NewsEntity $news)
    {
        $this->news = $news;
        $this->set($news->toArray());
    }

    /**
     * @return NewsEntity
     */
    public function getNews()
    {
        return $this->news;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    protected function save(ArchiveEntity $archive, GetResponseEvent $event)
    {
        if (!$this->news) {
            $this->news = $this->context->repository()->create('news', array(
                    'id' => $archive->getId()
                ));
        }

        $this->news->setContent($this->content);
        $this->news->save();

        $this->context->flash()->add('success', sprintf(
                '资讯 <i>%s</i> 保存成功！', $archive->getTitle()
            ));

        $event->setResponse($this->context->redirectResponse(
                $this->context->generateUrl('news-admin')
            ));
    }

    protected function render(RenderableResponse $response)
    {
        $response->setContent($this->context->widget('Form', array(
                    '#form' => $this,
                    '#skin' => 'vertical',
                    'cancel' => $this->context->generateUrl('news-admin')
                )))
            ->addVariable('#subtitle',
                $this->news ? sprintf('编辑资讯 #<i>%s</i>', $this->news->getId())
                    : '添加资讯'
            );
    }

    public function renderFormWidget(FormWidget $widget)
    {
        $widget->addField('title')->label('标题')->text();
        $widget->addField('subtitle')->label('副标题')->text();
        $widget->addField('channel_id')->label('栏目')->select_channel(array(
                '#chosen' => true
            ));
        $widget->addField('cover')->label('图片')->ckfinder();
        $widget->addField('content')->label('内容')->ckeditor_full();
    }

    public function id()
    {
        return 'news-admin';
    }

    /**
     * @return string
     */
    protected function getType()
    {
        return 'news';
    }
}