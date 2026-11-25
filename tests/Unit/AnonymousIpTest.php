<?php

namespace Tests\Unit;

use App\Support\AnonymousIp;
use PHPUnit\Framework\TestCase;

class AnonymousIpTest extends TestCase
{
    public function test_ipv4_loses_its_last_block(): void
    {
        $this->assertSame('81.2.69.0', AnonymousIp::of('81.2.69.142'));
        $this->assertSame('10.0.0.0', AnonymousIp::of('10.0.0.9'));
    }

    public function test_ipv6_is_cut_to_48_bits(): void
    {
        $this->assertSame('2a00:23c6:4177::', AnonymousIp::of('2a00:23c6:4177:a801:1e69:7aff:fe63:bd50'));
        $this->assertSame('::', AnonymousIp::of('::1'));
    }

    public function test_missing_or_invalid_input_gives_null(): void
    {
        $this->assertNull(AnonymousIp::of(null));
        $this->assertNull(AnonymousIp::of(''));
        $this->assertNull(AnonymousIp::of('not-an-ip'));
    }
}
