<?php

namespace Rubix\Tests\Kernels\Distance;

use Rubix\ML\Kernels\Distance\Hamming;
use Rubix\ML\Kernels\Distance\Distance;
use PHPUnit\Framework\TestCase;

class HammingTest extends TestCase
{
    protected $kernel;

    protected $a;

    protected $b;

    protected $c;

    public function setUp()
    {
        $this->a = ['x' => 2, 'y' => 1, 'z' => 4];
        $this->b = ['x' => 7, 'y' => 9, 'z' => 4];
        $this->c = ['x' => 2, 'y' => 2, 'z' => 3];

        $this->kernel = new Hamming();
    }

    public function test_build_distance_kernel()
    {
        $this->assertInstanceOf(Hamming::class, $this->kernel);
        $this->assertInstanceOf(Distance::class, $this->kernel);
    }

    public function test_compute_distance()
    {
        $distance1 = $this->kernel->compute($this->a, $this->b);
        $distance2 = $this->kernel->compute($this->a, $this->c);
        $distance3 = $this->kernel->compute($this->b, $this->c);

        $this->assertEquals(0.6666666666666666, $distance1, '', 1e-3);
        $this->assertEquals(0.6666666666666666, $distance2, '', 1e-3);
        $this->assertEquals(1.0, $distance3, '', 1e-3);
    }
}
