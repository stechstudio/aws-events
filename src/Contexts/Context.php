<?php declare(strict_types=1);

namespace STS\AwsEvents\Contexts;

use Illuminate\Support\Collection;
use function json_decode;
use function strtolower;

class Context
{
    /**
     * The name of the Lambda function.
     *
     * @var string
     */
    protected $functionName = '';

    /**
     * The version of the function.
     *
     * @var string
     */
    protected $functionVersion = '';

    /**
     * The Amazon Resource Name (ARN) used to invoke the function.
     * Indicates if the invoker specified a version number or alias.
     *
     * @var string
     */
    protected $invokedFunctionArn = '';

    /**
     * The amount of memory configured on the function.
     *
     * @var string
     */
    protected $memoryLimitInMb = '';

    /**
     * The identifier of the invocation request.
     *
     * @var string
     */
    protected $awsRequestId = '';

    /**
     * The log group for the function.
     *
     * @var string
     */
    protected $logGroupName = '';

    /**
     * The log stream for the function instance.
     *
     * @var string
     */
    protected $logStreamName = '';

    /**
     * (mobile apps) Information about the Amazon Cognito identity that authorized the request.
     *
     * @var Identity
     */
    protected $identity;


    /**
     * (mobile apps) Client context provided to the Lambda invoker by the client application.
     *
     * @var Client
     */
    protected $client;

    /**
     * The date that the function times out in Unix time milliseconds.
     * For example, 1542409706888.
     *
     * @var int
     */
    protected $runtimeDeadlineMs = 0;
    /**
     * The AWS X-Ray tracing header.
     * For example, Root=1-5bef4de7-ad49b0e87f6ef6c87fc2e700;Parent=9a9197af755a6419;Sampled=1
     *
     * @var string
     */
    protected $xRayTraceId = '';

    public static function fromJson(string $jsonContext): Context
    {
        $contextCollection = new Collection(json_decode($jsonContext, true));
        $context = new static;

        $context->setFunctionName(env('AWS_LAMBDA_FUNCTION_NAME', ''));
        $context->setFunctionVersion(env('AWS_LAMBDA_FUNCTION_VERSION', ''));
        $context->setLogGroupName(env('AWS_LAMBDA_LOG_GROUP_NAME', ''));
        $context->setLogStreamName(env('AWS_LAMBDA_LOG_STREAM_NAME', ''));

        $context->setMemoryLimitInMb(env('AWS_LAMBDA_FUNCTION_MEMORY_SIZE', ''));
        $context->setInvokedFunctionArn($contextCollection->get(strtolower('Lambda-Runtime-Invoked-Function-Arn'), ''));
        $context->setAwsRequestId($contextCollection->get('lambda-runtime-aws-request-id', ''));
        $context->setRuntimeDeadlineMs((int) $contextCollection->get(strtolower('Lambda-Runtime-Deadline-Ms'), 0));
        $context->setXRayTraceId($contextCollection->get(strtolower('Lambda-Runtime-Trace-Id'), ''));

        return $context;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->getFunctionName(),
            'version' => $this->getFunctionVersion(),
            'log_group_name' => $this->getLogGroupName(),
            'log_stream_name' => $this->getLogStreamName(),
            'memory_limit_in_mb' => $this->getMemoryLimitInMb(),
            'invoked_function_arn' => $this->getInvokedFunctionArn(),
            'aws_request_id' => $this->getAwsRequestId(),
            'runtime_deadline_in_ms' => $this->getRuntimeDeadlineMs(),
            'xray_trace_id' => $this->getXRayTraceId(),
        ];
    }

    public function getFunctionName(): string
    {
        return $this->functionName;
    }

    public function setFunctionName(string $functionName): Context
    {
        $this->functionName = $functionName;
        return $this;
    }

    public function getFunctionVersion(): string
    {
        return $this->functionVersion;
    }

    public function setFunctionVersion(string $functionVersion): Context
    {
        $this->functionVersion = $functionVersion;
        return $this;
    }

    public function getLogGroupName(): string
    {
        return $this->logGroupName;
    }

    public function setLogGroupName(string $logGroupName): Context
    {
        $this->logGroupName = $logGroupName;
        return $this;
    }

    public function getLogStreamName(): string
    {
        return $this->logStreamName;
    }

    public function setLogStreamName(string $logStreamName): Context
    {
        $this->logStreamName = $logStreamName;
        return $this;
    }

    public function getMemoryLimitInMb(): string
    {
        return $this->memoryLimitInMb;
    }

    public function setMemoryLimitInMb(string $memoryLimitInMb): Context
    {
        $this->memoryLimitInMb = $memoryLimitInMb;
        return $this;
    }

    public function getInvokedFunctionArn(): string
    {
        return $this->invokedFunctionArn;
    }

    public function setInvokedFunctionArn(string $invokedFunctionArn): Context
    {
        $this->invokedFunctionArn = $invokedFunctionArn;
        return $this;
    }

    public function getAwsRequestId(): string
    {
        return $this->awsRequestId;
    }

    public function setAwsRequestId(string $awsRequestId): Context
    {
        $this->awsRequestId = $awsRequestId;
        return $this;
    }

    public function getRuntimeDeadlineMs(): int
    {
        return $this->runtimeDeadlineMs;
    }

    public function setRuntimeDeadlineMs(int $runtimeDeadlineMs): Context
    {
        $this->runtimeDeadlineMs = $runtimeDeadlineMs;
        return $this;
    }

    public function getXRayTraceId(): string
    {
        return $this->xRayTraceId;
    }

    public function setXRayTraceId(string $xRayTraceId): Context
    {
        $this->xRayTraceId = $xRayTraceId;
        return $this;
    }

    /**
     * Determine how much more time the function has to run
     * before being forcibly shut down due to timeout.
     */
    public function getTimeRemaining(): int
    {
        return $this->runtimeDeadlineMs - time();
    }

    public function getIdentity(): Identity
    {
        return $this->identity;
    }

    public function setIdentity(Identity $identity): Context
    {
        $this->identity = $identity;
        return $this;
    }

    public function getClient(): Client
    {
        return $this->client;
    }

    public function setClient(Client $client): Context
    {
        $this->client = $client;
        return $this;
    }
}
