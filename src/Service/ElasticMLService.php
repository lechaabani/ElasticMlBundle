<?php

namespace Hch\ElasticMLBundle\Service;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder;
use Psr\Log\LoggerInterface;

class ElasticMLService
{
    private $client;
    private $logger;

    public function __construct(string $client, LoggerInterface $logger = null)
    {
        $this->client = ClientBuilder::create()->setHosts([$client])->build();
        $this->logger = $logger;
    }

    /**
     * Creates a Machine Learning job in Elasticsearch.
     * @param array $params The necessary parameters for creating the job
     * @return array The result of the job creation
     * @throws \InvalidArgumentException If required parameters are missing
     * @throws \RuntimeException If the job creation fails
     */
    public function createJob(array $params): array
    {
        // Ensure required parameters are present
        if (!isset($params['job_id'], $params['analysis_config'], $params['data_description'], $params['datafeed_config'])) {
            throw new \InvalidArgumentException('Missing required parameters.');
        }

        // Build the request body following Elasticsearch's expected format
        $body = [
            'job_id' => $params['job_id'],
            'analysis_config' => [
                'bucket_span' => $params['analysis_config']['bucket_span'] ?? '5m',
                'detectors' => $params['analysis_config']['detectors'] ?? [
                        [
                            'function' => 'mean',
                            'field_name' => 'response_time'
                        ]
                    ],
            ],
            'data_description' => [
                'time_field' => $params['data_description']['time_field'] ?? '@timestamp'
            ],
            'datafeed_config' => [
                'indices' => $params['datafeed_config']['indices'] ?? ['default-index']
            ]
        ];

        try {
            // Create the job in Elasticsearch
            return $this->client->ml()->putJob([
                'job_id' => $params['job_id'],
                'body' => $body,
            ])->asArray();
        } catch (\Exception $e) {
            if ($this->logger) {
                $this->logger->error('Failed to create ML job: ' . $e->getMessage(), $params);
            }
            throw new \RuntimeException('Failed to create the Machine Learning job.');
        }
    }

    /**
     * Retrieves the results of the ML job.
     * @param string $jobId The ID of the job
     * @return array The job results
     * @throws \RuntimeException If the results could not be retrieved
     */
    public function getResults(string $jobId): array
    {
        try {
            return $this->client->ml()->getRecords(['job_id' => $jobId])->asArray();
        } catch (\Exception $e) {
            if ($this->logger) {
                $this->logger->error('Failed to retrieve ML job results for job_id: ' . $jobId, ['job_id' => $jobId]);
            }
            throw new \RuntimeException('Failed to retrieve the results of the Machine Learning job.');
        }
    }
}
