<?php

namespace Spatie\SlackAlerts;

use Closure;
use PHPUnit\Framework\Assert as PHPUnit;

class FakeSlackAlert extends SlackAlert
{
    protected array $sentMessages = [];

    public function message(string $text): void
    {
        $this->sentMessages[] = array_merge(
            $this->getBaseProperties(), ['text' => $text]
        );
    }

    public function blocks(array $blocks): void
    {
        $this->sentMessages[] = array_merge(
            $this->getBaseProperties(), ['blocks' => $blocks]
        );
    }

    public function expectNoMessagesSent(): void
    {
        PHPUnit::assertEquals(
            0,
            count($this->sentMessages),
            'Expected no messages to be sent, but found: ' . json_encode($this->sentMessages));
    }

    public function expectNumberOfMessagesSent(int $expectedCount): void
    {
        $actualCount = count($this->sentMessages);

        PHPUnit::assertEquals(
            $expectedCount,
            count($this->sentMessages),
            "Expected {$expectedCount} messages to be sent, but {$actualCount} messages: were actually sent");
    }

    public function expectMessagesSent(?Closure $callback = null)
    {
        $callback = $callback ?: fn() => true;

        $foundMessagesCount = collect($this->sentMessages)
            ->filter(fn($message) => $callback($message))
            ->count();

        $message = is_null($callback)
            ? 'Expected messages to be sent, but none were sent'
            : 'No messages were sent that matched the expected criteria.';;

        PHPUnit::assertGreaterThanOrEqual(
            1,
            $foundMessagesCount,
            $message,
        );
    }

    public function expectMessageSentContaining(string $expectedSubstring): void
    {
        $count = collect($this->sentMessages)
            ->filter(function ($message) use ($expectedSubstring) {
                if (isset($message['text'])) {
                    return str_contains($message['text'] ?? '', $expectedSubstring);
                }

                return $this->containsValueContaining($message['blocks'], $expectedSubstring);
            })
            ->count();

        PHPUnit::assertGreaterThanOrEqual(
            1,
            $count,
            "Expected at least one message to contain the substring '{$expectedSubstring}', but none were found."
        );
    }

    public function sentMessages(): array
    {
        return $this->sentMessages;
    }

    protected function containsValueContaining(array $array, string $value): bool
    {
        foreach ($array as $item) {
            if (is_array($item)) {
                if ($this->containsValueContaining($item, $value)) {
                    return true;
                }
            } elseif (str_contains($item, $value)) {
                return true;
            }
        }

        return false;
    }
}
