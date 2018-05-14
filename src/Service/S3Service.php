<?php


namespace App\Service;


use Aws\S3\S3Client;

class S3Service
{
    const ACL_PRIVATE = 'private';
    const ACL_PUBLIC_READ = 'public-read';

    /**
     * @var S3Client
     */
    private $s3client;

    /**
     * @var string
     */
    private $s3Bucket;

    /**
     * S3Service constructor.
     */
    public function __construct(S3Client $s3client, string $s3Bucket)
    {
        $this->s3client = $s3client;
        $this->s3Bucket = $s3Bucket;
    }

    public function putFile(string $key, string $pathToFile, string $acl = self::ACL_PRIVATE)
    {
        return $this->s3client->putObject([
            'ACL' => $acl,
            'Key' => $key,
            'Bucket' => $this->s3Bucket,
            'SourceFile' => $pathToFile,
        ]);
    }
}