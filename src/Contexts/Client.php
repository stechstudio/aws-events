<?php declare(strict_types=1);

namespace STS\AwsEvents\Contexts;

use Tightenco\Collect\Support\Collection;

class Client
{
    /** @var string */
    protected $installationId = '';

    /** @var string */
    protected $appTitle = '';

    /** @var string */
    protected $appVersionName = '';

    /** @var string */
    protected $appVersionCode = '';

    /** @var string */
    protected $appPackageName = '';

    /**
     * A collection of custom values set by the mobile client application.
     *
     * @var Collection
     */
    protected $custom;

    /**
     * A dict of environment information provided by the AWS SDK.
     *
     * @var Collection
     */
    protected $env;


    public function getInstallationId(): string
    {
        return $this->installationId;
    }


    public function setInstallationId(string $installationId): Client
    {
        $this->installationId = $installationId;
        return $this;
    }


    public function getAppTitle(): string
    {
        return $this->appTitle;
    }


    public function setAppTitle(string $appTitle): Client
    {
        $this->appTitle = $appTitle;
        return $this;
    }


    public function getAppVersionName(): string
    {
        return $this->appVersionName;
    }


    public function setAppVersionName(string $appVersionName): Client
    {
        $this->appVersionName = $appVersionName;
        return $this;
    }


    public function getAppVersionCode(): string
    {
        return $this->appVersionCode;
    }


    public function setAppVersionCode(string $appVersionCode): Client
    {
        $this->appVersionCode = $appVersionCode;
        return $this;
    }


    public function getAppPackageName(): string
    {
        return $this->appPackageName;
    }

    public function setAppPackageName(string $appPackageName): Client
    {
        $this->appPackageName = $appPackageName;
        return $this;
    }


    public function getCustom(): Collection
    {
        return $this->custom;
    }

    public function setCustom(Collection $custom): Client
    {
        $this->custom = $custom;
        return $this;
    }

    public function getEnv(): Collection
    {
        return $this->env;
    }

    public function setEnv(Collection $env): Client
    {
        $this->env = $env;
        return $this;
    }
}
