<?php

namespace Youngx\Bundle\AdminBundle\Module\FileModule\Form;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Youngx\Bundle\AdminBundle\Module\FileModule\Entity\FileEntity;
use Youngx\Bundle\UserBundle\Entity\UserEntity;
use Youngx\Database\Entity;
use Youngx\MVC\Event\GetResponseEvent;
use Youngx\MVC\Form;
use Youngx\MVC\RenderableResponse;
use Youngx\MVC\Widget\FormWidget;

class FileForm extends Form
{
    /**
     * @var UploadedFile[]
     */
    protected $files = array();
    /**
     * @var Entity
     */
    protected $entity;

    protected $entityType;
    /**
     * @var UserEntity
     */
    protected $user;

    protected $multiple = true;

    protected $baseUri = 'public://';

    /**
     * @var FileEntity
     */
    protected $oldFile;

    /**
     * @var FileEntity[]
     */
    protected $fileEntities = array();

    protected $allowedExtensions = array();

    public function id()
    {
        return 'file';
    }

    protected function initRequest()
    {
        $entityType = $this->getEntityType();
        if (!$entityType) {
            throw new \Exception('文件上传未指定实体');
        }

        if (!$this->user) {
            $this->user = $this->context->identity()->getUserEntity();
        }
    }

    protected function validate(Form\FormErrorHandler $feh)
    {
        foreach ($this->files as $name => $file) {
            if (!in_array(strtolower($file->getClientOriginalExtension()), $this->allowedExtensions)) {
                $feh->add($name, '非法的文件格式：' . $file->getClientOriginalExtension());
            }
        }
    }

    protected function submit(GetResponseEvent $event)
    {
        if ($this->files && null != reset($this->files)) {
            $file = $this->context->repository()->create('file', array(
                    'uid' => $this->user->getUid(),
                    'entity_type' => $this->getEntityType(),
                    'entity_id' => $this->entity ? $this->entity->identifier() : 0,
                    'created_at' => Y_TIME
                ));
            $relativePath = date('Y/m/', Y_TIME);
            $basePath = $this->context->locate($this->baseUri) . '/'.$relativePath;
            foreach ($this->files as $key => $upload) {
                if ($upload->isValid()) {
                    $filename = md5(microtime(true) . $upload->getClientOriginalName()) . '.' . $upload->getClientOriginalExtension();
                    $upload->move($basePath, $filename);
                    $clone  = clone $file;
                    $clone->set(array(
                            'filename' => $filename,
                            'mime_type' => $upload->getClientMimeType(),
                            'uri' => $this->baseUri . $relativePath . $filename
                        ));

                    $this->onFileBeforeSave($key, $clone, $upload);
                    $clone->save();
                    $this->onFileBeforeSave($key, $clone, $upload);
                    $this->fileEntities[$key] = $clone;
                }
            }

            if ($this->oldFile) {
                $this->oldFile->delete();
            }

            //$this->context->flash()->add('success', '文件上传成功');
        } else {
            //$this->context->flash()->add('warning', '没有可上传的文件！');
        }
    }

    protected function onFileBeforeSave($key, FileEntity $file, UploadedFile $upload)
    {
    }

    protected function onFileSave($key, FileEntity $file, UploadedFile $upload)
    {
    }

    protected function render(RenderableResponse $response)
    {
        $response->setContent($this->context->widget('Form', array(
                    '#form' => $this,
                    '#skin' => 'vertical',
                    '#uploadable' => true
                )));
    }

    public function renderFormWidget(FormWidget $widget)
    {
        $widget->add('file', $this->context->input('file', array(
                    'multiple' => $this->multiple,
                    'name' => $this->multiple ? 'files' : 'file'
                )));
    }

    /**
     * @param Entity $entity
     */
    public function setEntity(Entity $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return Entity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param UploadedFile $file
     * @param null $key
     */
    public function setFile(UploadedFile $file, $key = null)
    {
        if (null !== $key) {
            $this->files[$key] = $file;
        } else {
            $this->files[] = $file;
        }
    }

    /**
     * @param UploadedFile[] $files
     */
    public function setFiles(array $files)
    {
        $this->files = $files;
    }

    /**
     * @return mixed
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param UserEntity $user
     */
    public function setUser(UserEntity $user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param boolean $multiple
     */
    public function setMultiple($multiple)
    {
        $this->multiple = $multiple;
    }

    /**
     * @return boolean
     */
    public function getMultiple()
    {
        return $this->multiple;
    }

    public function setBaseUri($baseUri)
    {
        $this->baseUri = $baseUri;

        return $this;
    }

    /**
     * @return FileEntity[]
     */
    public function getFileEntities()
    {
        return $this->fileEntities;
    }

    /**
     * @param mixed $entityType
     */
    public function setEntityType($entityType)
    {
        $this->entityType = $entityType;
    }

    /**
     * @return mixed
     */
    public function getEntityType()
    {
        return $this->entityType ?: ($this->entity ? $this->entity->type() : null);
    }

    /**
     * @param mixed $oldFile
     */
    public function setOldFile($oldFile)
    {
        $oldFile = intval($oldFile);
        if ($oldFile) {
            $this->oldFile = $this->context->repository()->load('file', $oldFile);
        }
    }
}