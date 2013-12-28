<?php

namespace Youngx\Bundle\AdminBundle\Module\DistrictModule\Form;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Form;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\Widget\FormWidget;

class ImportForm extends Form
{
    /**
     * @var UploadedFile
     */
    protected $file;

    public function id()
    {
        return 'district-import';
    }

    protected function render(RenderableResponse $response)
    {
        $response->setContent($this->context->widget('Form', array(
                    '#form' => $this,
                    '#uploadable' => true,
                    '#skin' => 'horizontal',
                )))->addVariable('#subtitle', '导入地区');
    }

    protected function submit(GetResponseEvent $event)
    {
        $handle = fopen($this->file->getRealPath(),"r");
        $district = $this->context->repository()->create('district');
        $ids = array();
        while ($info = fscanf($handle, "%d %s")) {
            $d = clone $district;
            list ($code, $label) = $info;
            $layer = $code % 10000 == 0 ? 1 : ($code % 100 == 0 ? 2 : 3);
            if ($layer == 1) {
                $parent = 0;
            } else if ($layer == 2) {
                $parentCode = (int) ((int)($code / 10000) * 10000);
                $parent = $ids[$parentCode];
            } else {
                $parentCode = (int) ((int)($code / 100) * 100);
                $parent = $ids[$parentCode];
            }

            $d->set(array(
                    'code' => $code,
                    'label' => $label,
                    'layer' => $layer,
                    'parent_id' => $parent
                ));
            $d->save();

            if ($layer < 3) {
                $ids[$code] = $d->identifier();
            }
        }

        fclose($handle);
    }

    public function renderFormWidget(FormWidget $widget)
    {
        $widget->addField('file')->label('文件')->file();
    }

    /**
     * @param mixed $file
     */
    public function setFile(UploadedFile $file)
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }
}