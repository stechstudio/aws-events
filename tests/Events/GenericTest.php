<?php
/**
 * Created by PhpStorm.
 * User: bubba
 * Date: 2019-01-25
 * Time: 13:13
 */
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use STS\AwsEvents\Events\Event;

final class GenericTest extends TestCase
{
    protected $eventSamples;

    public function setUp()
    {
        $this->eventSamples = dirname(__FILE__) . "/../../resources/event-samples/";
    }

    public function testCanBeCreatedFromApiGatewayProxyRequest(): Event
    {
        $event = Event::fromFile($this->eventSamples . 'api-gateway-proxy-request.json');
        $this->assertInstanceOf(Event::class, $event);
        return $event;
    }

    public function testInvalidJsonThrowsException(): void
    {
        $this->expectException(\JsonException::class);
        Event::fromString('not json');
    }

    /**
     * @param Event $event
     * @depends testCanBeCreatedFromApiGatewayProxyRequest
     */
    public function testCanGetAnEventItemByKey(Event $event): void
    {
        $this->assertEquals("/test/hello", $event->get('path'));
    }

    /**
     * @param Event $event
     * @depends testCanBeCreatedFromApiGatewayProxyRequest
     */
    public function testCanGetAnEventItemByAttribute(Event $event): void
    {
        $this->assertEquals("/test/hello", $event->path);
    }

    /**
     * @param Event $event
     * @depends testCanBeCreatedFromApiGatewayProxyRequest
     */
    public function testCanProxyIntoTheCollection(Event $event): void
    {
        $this->assertEquals("/{proxy+}", $event->last(function ($value, $key) {
            return $key == 'resource';
        }));
    }

    /**
     * @param Event $event
     * @depends testCanBeCreatedFromApiGatewayProxyRequest
     */
    public function testTransformationToArray(Event $event): void
    {
        $this->assertIsArray($event->toArray());
    }

    /**
     * @param Event $event
     * @depends testCanBeCreatedFromApiGatewayProxyRequest
     */
    public function testTransformationToCollection(Event $event): void
    {
        $this->assertInstanceOf(\Tightenco\Collect\Support\Collection::class, $event->toCollection());
    }

    /**
     * @param Event $event
     * @depends testCanBeCreatedFromApiGatewayProxyRequest
     */
    public function testCounting(Event $event): void
    {
        $this->assertEquals(8, $event->count());
    }

    /**
     * @param Event $event
     * @depends testCanBeCreatedFromApiGatewayProxyRequest
     */
    public function testConversionToJson(Event $event): void
    {
        json_decode($event->toJson());
        $this->assertEquals(JSON_ERROR_NONE, json_last_error());
    }

    /**
     * @param Event $event
     * @depends testCanBeCreatedFromApiGatewayProxyRequest
     */
    public function testCastingToString(Event $event): void
    {
        $this->assertTrue(is_string((string)$event));
    }

    /**
     * @param Event $event
     * @depends testCanBeCreatedFromApiGatewayProxyRequest
     */
    public function testJsonSerialization(Event $event): void
    {
        $this->assertIsArray($event->jsonSerialize());
    }

    /**
     * @param Event $event
     * @depends testCanBeCreatedFromApiGatewayProxyRequest
     */
    public function testIteratorWorks(Event $event): void
    {
        $this->assertInstanceOf(ArrayIterator::class, $event->getIterator());
    }

    /**
     * @dataProvider eventProvider
     * @param string $eventClassName
     * @param string $eventSample
     * @throws JsonException
     */
    public function testEventFactory(string $eventClassName, string $eventSample)
    {
        $this->assertEquals('STS\AwsEvents\Events\\' . $eventClassName,
            get_class(Event::fromFile($this->eventSamples . $eventSample)));
    }

    public function eventProvider()
    {
        return [
            [Sqs::class, 'sqs.json'],
            [Sns::class, 'sns.json'],
            [CloudwatchLogs::class, 'cloudwatch-logs.json'],
            [CognitoSync::class, 'cognito-sync.json'],
            [Lex::class, 'lex.json'],
            [ApiGatewayProxyRequest::class, 'api-gateway-proxy-request.json'],
            [CloudformationCreateRequest::class, 'cloudformation-create-request.json'],
            [Config::class, 'config.json'],
            [IotButton::class, 'iot-button.json'],
            [KinesisDataFirehouse::class, 'kinesis-data-firehose.json'],
            [ScheduledEvent::class, 'scheduled-event.json'],
            [Cloudfront::class, 'cloudfront.json'],
            [S3Delete::class, 's3-delete.json'],
            [S3Put::class, 's3-put.json'],
            [DynamodbUpdate::class, 'dynamodb-update.json'],
            [KinesisDataStreams::class, 'kinesis-data-streams.json'],
            [SesEmailReceiving::class, 'ses-email-receiving.json'],


        ];
    }

}
