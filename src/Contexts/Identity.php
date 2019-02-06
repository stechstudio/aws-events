<?php declare(strict_types=1);

namespace STS\AwsEvents\Contexts;

class Identity
{
    /**
     * The authenticated Amazon Cognito identity.
     *
     * @var string
     */
    protected $cognitoIdentityId = '';

    /**
     * The Amazon Cognito identity pool that authorized the invocation.
     *
     * @var string
     */
    protected $cognitoIdentityPoolId = '';


    public function getCognitoIdentityId(): string
    {
        return $this->cognitoIdentityId;
    }


    public function setCognitoIdentityId(string $cognitoIdentityId): Identity
    {
        $this->cognitoIdentityId = $cognitoIdentityId;
        return $this;
    }


    public function getCognitoIdentityPoolId(): string
    {
        return $this->cognitoIdentityPoolId;
    }


    public function setCognitoIdentityPoolId(string $cognitoIdentityPoolId): Identity
    {
        $this->cognitoIdentityPoolId = $cognitoIdentityPoolId;
        return $this;
    }
}
