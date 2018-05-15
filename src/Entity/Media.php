<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MediaRepository")
 */
class Media
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

    /**
     * @var UploadedFile
     * @Assert\File(
     *      maxSize = "200M",
     *      mimeTypes = {"image/*"},
     *      maxSizeMessage = "The file is too large ({{ size }}).Allowed maximum size is {{ limit }}",
     *      mimeTypesMessage = "The mime type of the file is invalid ({{ type }}). Allowed mime types are {{ types }}",
     * )
     */
    private $file;

    public function getId()
    {
        return $this->id;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param UploadedFile $file
     *
     * @return $this
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @param $name
     * @return string
     * @throws \Exception
     */
    public static function getPrefixName($name)
    {
        return bin2hex(random_bytes(16)) . '-' . $name;
    }
}
