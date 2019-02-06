<?php declare(strict_types=1);

namespace STS\AwsEvents\Contexts;

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

    /**
     * Determine how much more time the function has to run
     * before being forcibly shut down due to timeout.
     */
    public function getTimeRemaining(): int
    {
        return $this->runtimeDeadlineMs - time();
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

    public function getInvokedFunctionArn(): string
    {
        return $this->invokedFunctionArn;
    }

    public function setInvokedFunctionArn(string $invokedFunctionArn): Context
    {
        $this->invokedFunctionArn = $invokedFunctionArn;
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

    public function getAwsRequestId(): string
    {
        return $this->awsRequestId;
    }

    public function setAwsRequestId(string $awsRequestId): Context
    {
        $this->awsRequestId = $awsRequestId;
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
