<?php

namespace Mautic\CoreBundle\Helper;

use Mautic\CoreBundle\Exception\FilePathException;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FilePathResolver
{
    private \Symfony\Component\Filesystem\Filesystem $filesystem;

    private \Mautic\CoreBundle\Helper\InputHelper $inputHelper;

    public function __construct(Filesystem $filesystem, InputHelper $inputHelper)
    {
        $this->filesystem  = $filesystem;
        $this->inputHelper = $inputHelper;
    }

    /**
     * @param string $uploadDir
     *
     * @return string
     *
     * @throws FilePathException
     */
    public function getUniqueFileName($uploadDir, UploadedFile $file)
    {
        $inputHelper       = $this->inputHelper;
        $fullName          = $file->getClientOriginalName();
        $fullNameSanitized = $inputHelper::filename($fullName);
        $ext               = $this->getFileExtension($file);
        $baseFileName      = pathinfo($fullNameSanitized, PATHINFO_FILENAME);
        $name              = $baseFileName;
        $filePath          = $this->getFilePath($uploadDir, $baseFileName, $ext);
        $i                 = 1;

        while ($this->filesystem->exists($filePath)) {
            $name     = $baseFileName.'-'.$i;
            $filePath = $this->getFilePath($uploadDir, $name, $ext);
            ++$i;

            if ($i > 1000) {
                throw new FilePathException('Could not generate path');
            }
        }

        return $name.$ext;
    }

    /**
     * @param string $directory
     *
     * @throws FilePathException
     */
    public function createDirectory($directory)
    {
        if ($this->filesystem->exists($directory)) {
            return;
        }
        try {
            $this->filesystem->mkdir($directory);
        } catch (IOException $e) {
            throw new FilePathException('Could not create directory');
        }
    }

    /**
     * @param string $path
     */
    public function delete($path): void
    {
        if (!$this->filesystem->exists($path)) {
            return;
        }
        try {
            $this->filesystem->remove($path);
        } catch (IOException $e) {
        }
    }

    public function move(string $originPath, string $targetPath): void
    {
        $this->filesystem->rename($originPath, $targetPath);
    }

    /**
     * @param string $uploadDir
     * @param string $fileName
     * @param string $ext
     *
     * @return string
     */
    private function getFilePath($uploadDir, $fileName, $ext)
    {
        return $uploadDir.DIRECTORY_SEPARATOR.$fileName.$ext;
    }

    /**
     * @return string
     */
    private function getFileExtension(UploadedFile $file)
    {
        $ext = $file->getClientOriginalExtension();

        return ('' === $ext ? '' : '.').$ext;
    }
}
