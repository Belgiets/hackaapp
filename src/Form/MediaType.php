<?php

namespace App\Form;

use App\Entity\Media;
use App\Helper\LoggerTrait;
use App\Service\S3Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaType extends AbstractType
{
    use LoggerTrait;

    /**
     * @var S3Service
     */
    private $s3Service;

    /**
     * @var string
     */
    private $rootDir;

    /**
     * @var string
     */
    private $folderPerson;

    public function __construct(S3Service $s3Service, string $rootDir, string $folderPerson)
    {
        $this->s3Service = $s3Service;
        $this->rootDir = $rootDir;
        $this->folderPerson = $folderPerson;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, [
                'required' => !$options['required'],
                'label' => false
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($options) {
                /** @var Media $media */
                $media = $event->getData();

                if ($media && $media->getFile()) {
                    $uploadedFile = $media->getFile();
                    $filesDir = "{$this->rootDir}/../var/files";

                    $fileName = $uploadedFile->getFilename();
                    $fileName = Media::getPrefixName($fileName) . ".png";

                    $pathToFile = "$filesDir/$fileName";

                    if (!is_dir($filesDir)) {
                        mkdir($filesDir);
                    }

                    $uploadedFile->move(
                        $filesDir,
                        $fileName
                    );

                    try {
                        $result = $this
                            ->s3Service
                            ->putFile("{$this->folderPerson}/$fileName", $pathToFile, S3Service::ACL_PUBLIC_READ);

                        @unlink($pathToFile);

                        $media->setUrl($result['ObjectURL']);
                    } catch (\Exception $exception) {
                        $this->logger->error($exception->getMessage());
                    }
                }
            })
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Media::class,
            'edit' => false
        ]);
    }
}
