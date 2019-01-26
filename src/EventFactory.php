<?php

namespace STS\Lambda;

use STS\Lambda\Events\ApiGatewayProxyRequest;
use STS\Lambda\Events\CloudformationCreateRequest;
use STS\Lambda\Events\Cloudfront;
use STS\Lambda\Events\CloudwatchLogs;
use STS\Lambda\Events\CognitoSync;
use STS\Lambda\Events\Config;
use STS\Lambda\Events\DynamodbUpdate;
use STS\Lambda\Events\IotButton;
use STS\Lambda\Events\KinesisDataFirehouse;
use STS\Lambda\Events\KinesisDataStreams;
use STS\Lambda\Events\Lex;
use STS\Lambda\Events\S3Delete;
use STS\Lambda\Events\S3Put;
use STS\Lambda\Events\ScheduledEvent;
use STS\Lambda\Events\SesEmailReceiving;
use STS\Lambda\Events\Sns;
use STS\Lambda\Events\Sqs;
use STS\Lambda\Foundation\Event;


class EventFactory
{

    /**
     * Figures out how to make the proper event for us.
     *
     * @param string $rawEvent
     * @return Event
     * @throws \JsonException
     */
    public static function make(string $rawEvent): Event
    {
        $foundationEvent = new Event($rawEvent);
        if ($foundationEvent->contains('Records')) {
            return self::checkRecords($foundationEvent);
        } elseif ($foundationEvent->contains('awslogs')) {
            return new CloudwatchLogs($rawEvent);
        } elseif ($foundationEvent->contains('identityPoolId')) {
            return new CognitoSync($rawEvent);
        } elseif ($foundationEvent->contains('messageVersion')) {
            return new Lex($rawEvent);
        } elseif ($foundationEvent->contains('requestContext')) {
            return new ApiGatewayProxyRequest($rawEvent);
        } elseif ($foundationEvent->contains('StackId')) {
            return new CloudformationCreateRequest($rawEvent);
        } elseif ($foundationEvent->contains('configRuleName')) {
            return new Config($rawEvent);
        } elseif ($foundationEvent->contains('clickType')) {
            return new IotButton($rawEvent);
        } elseif ($foundationEvent->contains('invocationId')) {
            return new KinesisDataFirehouse($rawEvent);
        } elseif ($foundationEvent->contains('detail-type')) {
            return new ScheduledEvent($rawEvent);
        }
        return $foundationEvent;
    }

    /**
     * Dig into the Records.
     * @param Event $foundationEvent
     * @return Event
     * @throws \JsonException
     */
    protected static function checkRecords(Event $foundationEvent): Event
    {
        if ($foundationEvent->contains('Records')->contains('cf')) {
            return new Cloudfront($foundationEvent->toJson());
        } elseif ($foundationEvent->contains('Records')->contains('EventSource')) {
            return self::checkSns($foundationEvent);
        } elseif ($foundationEvent->contains('Records')->contains('eventsource')) {
            return self::checkEventSources($foundationEvent);
        }
        return $foundationEvent;
    }

    /**
     * Dig into the sns records
     * @param Event $foundationEvent
     * @return Event
     * @throws \JsonException
     */
    protected static function checkSns(Event $foundationEvent): Event
    {
        if ($foundationEvent->contains('Records')->contains('EventSource')->contains('aws:sns')) {
            return new Sns($foundationEvent->toJson());
        }
        return $foundationEvent;
    }

    /**
     * Dig into the lower case eventsource
     * @param Event $foundationEvent
     * @return Event
     * @throws \JsonException
     */
    protected static function checkEventSources(Event $foundationEvent): Event
    {
        if ($foundationEvent->contains('Records')->contains('eventsource')->contains('aws:s3')) {
            return self::checkSns($foundationEvent);
        } elseif ($foundationEvent->contains('Records')->contains('eventsource')->contains('aws:dynamodb')) {
            return new DynamodbUpdate($foundationEvent->toJson());
        } elseif ($foundationEvent->contains('Records')->contains('eventsource')->contains('aws:kinesis')) {
            return new KinesisDataStreams($foundationEvent->toJson());
        } elseif ($foundationEvent->contains('Records')->contains('eventsource')->contains('aws:ses')) {
            return new SesEmailReceiving($foundationEvent->toJson());
        } elseif ($foundationEvent->contains('Records')->contains('eventsource')->contains('aws:sqs')) {
            return new Sqs($foundationEvent->toJson());
        }
        return $foundationEvent;
    }

    /**
     * Dig into S3 records
     * @param Event $foundationEvent
     * @return Event
     * @throws \JsonException
     */
    protected static function checkS3(Event $foundationEvent): Event
    {
        if ($foundationEvent->contains('Records')->contains('eventsource')->contains('aws:s3')->contains('ObjectRemoved:Delete')) {
            return new S3Delete($foundationEvent->toJson());
        } elseif ($foundationEvent->contains('Records')->contains('eventsource')->contains('aws:s3')->contains('ObjectCreated:Put')) {
            return new S3Put($foundationEvent->toJson());
        }
        return $foundationEvent;
    }
}
