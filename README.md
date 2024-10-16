# hch/elastic-ml-bundle

## Introduction

The **ElasticMlBundle** is a Symfony bundle that integrates **Elastic Machine Learning** (ML) features from Elasticsearch into your Symfony application. This bundle allows you to create and manage Machine Learning jobs via Elasticsearch's ML API.

## Features
- Create ML jobs in Elasticsearch
- Retrieve ML job results
- Flexible configuration for Elasticsearch hosts
- Example Symfony command to easily create jobs via the CLI

## Installation

1. **Install the bundle via Composer**:
   ```bash
   composer require hch/elastic-ml-bundle
   
2. **Enable the bundle in config/bundles.php**:

    ```bash
    return [
    	Hch\ElasticMLBundle\ElasticMLBundle::class => ['all' => true],
    ];


3. **Configure the Elasticsearch host in config/packages/elastic_ml.yaml**:

    ```bash
    elastic_ml:
        host: 'http://localhost:9200'

## Usage

Creating an ML Job via Symfony Command: 

	php bin/console elastic:ml:create-job --jobId=my-job-id --index=my-index
	
Example Output:
	
	Job created successfully!
	

- Handling Elasticsearch Exceptions: In case of errors such as misconfigured requests or Elasticsearch being down, you will receive detailed error messages from the API.

## Troubleshooting:

- 403 Forbidden: License non-compliant for ML: If you encounter this error, it means your Elasticsearch license does not support Machine Learning. You have the following options:

Activate a trial license:

	curl -X POST "http://localhost:9200/_license/start_trial?acknowledge=true"
	
. Upgrade your Elasticsearch license to a Gold or Platinum subscription to access ML features.

. No alive nodes: All the nodes seem to be down: This error indicates that Elasticsearch is either not running or unreachable.

Ensure Elasticsearch is running:

       sudo systemctl start elasticsearch
	
If using Docker, ensure the container is running:
	
	   docker start <elasticsearch-container-id>
	
- Invalid job request: analysis_config doesn't support START_ARRAY: This error occurs when the analysis_config in the request is incorrectly formatted. 
Ensure the detectors field in analysis_config is an array of objects. Here's a valid example:

	
	analysis_config:
	    bucket_span: "5m"
	    detectors:
		- function: "mean"
		  field_name: "response_time"
		  
## Configuration

- Ensure correct Elasticsearch configuration in config/packages/elastic_ml.yaml:


	elastic_ml:
	    host: 'http://localhost:9200'

## Development

Code Structure:

- Service: The service ElasticMLService provides the core functionalities to create ML jobs and retrieve results from Elasticsearch.
- The constructor takes the Elasticsearch host URL as a parameter.
- Command: The Symfony command CreateMLJobCommand uses the service to create ML jobs via the CLI.
	
	
- Example Service Usage: In your Symfony controllers or services, you can use the ElasticMLService like this:

	 
	 $mlService = $this->get(ElasticMLService::class);
	 $params = [
	     'job_id' => 'my-job-id',
	     'analysis_config' => [
		 'bucket_span' => '5m',
		 'detectors' => [
		     [
		         'function' => 'mean',
		         'field_name' => 'response_time'
		     ]
		 ]
	     ],
	     'data_description' => [
		 'time_field' => '@timestamp'
	     ],
	     'datafeed_config' => [
		 'indices' => ['my-index']
	     ]
	 ];
	 $mlService->createJob($params);
	 
## Testing
	
    php bin/phpunit
    
## Future Enhancements
- Allow users to pass dynamic job parameters via the Symfony command.
- Extend the bundle to support other machine learning engines, such as Google AI or AWS SageMaker.
- Automatically handle license upgrades or fallback gracefully when ML features are unavailable.
	
## License
	
This bundle is licensed under the MIT License. See LICENSE for more information.


