<?php

namespace Youngx\Bundle\AdminBundle\Module\FileModule\Listener;

use Youngx\Bundle\AdminBundle\Module\FileModule\Entity\FileEntity;
use Youngx\Database\Entity;
use Youngx\EventHandler\Registration;
use Youngx\MVC\Context;
use Youngx\MVC\User\Identity;
use Youngx\Util\Directory;
use Youngx\Util\Image\Resize;

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

    public function getFiles()
    {
        $args = func_get_args();
        if ($args[0] instanceof Entity) {
            $entityType = $args[0]->type();
            $entityId = $args[0]->identifier();
        } else {
            list($entityType, $entityId) = $args;
        }

        return $this->context->repository()->query('file')->where('entity_type=:entity_type AND entity_id=:entity_id')->orderly()
            ->all(array(
                    ':entity_type' => $entityType,
                    ':entity_id' => $entityId
                ));
    }

    public function getImageUrl($uri, $width = 0, $height = 0)
    {
        $file = $this->context->locate($uri);
        if ($width || $height) {
            if (is_file($file)) {
                $p = strrpos($file, '.');
                $dir = substr($file, 0, $p);
                $ext = substr($file, $p+1);
                $thumbFile = "{$dir}/{$width}_{$height}.{$ext}";
                if (!is_file($thumbFile)) {
                    if (!is_dir($dir)) {
                        mkdir($dir);
                    }
                    if ($width && $height) {
                        Resize::fixedResize($file, $thumbFile, $width, $height);
                    } else if ($width) {
                        Resize::maxResize($file, $thumbFile, $width, 'width');
                    } else {
                        Resize::maxResize($file, $thumbFile, $height, 'height');
                    }
                }
                $uri = substr($uri, 0, strrpos($uri, '.')) . "/{$width}_{$height}.{$ext}";
            }
        }
        return $this->context->locateUrl($uri);
    }

    public function deleteFileEntity(FileEntity $entity)
    {
        $file = $this->context->locate($entity->getUri());
        if (is_file($file)) {
            unlink($file);
            $dir = substr($file, 0, strrpos($file, '.'));
            if (is_dir($dir)) {
                Directory::delDirAndFile($dir);
            }
        }
    }

    public static function registerListeners()
    {
        return array(
            'kernel.value#files' => 'getFiles',
            'kernel.value#image-url' => 'getImageUrl',
            'kernel.entity.delete#file' => 'deleteFileEntity',
            'kernel.templating.call.image_url' => 'getImageUrl'
        );
    }
}