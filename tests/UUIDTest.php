<?php
/**
 * Written by Farshad Hassani
 */

namespace Modules\Core\Test;


use Modules\Core\Builders\UUID;
use PHPUnit\Framework\TestCase;

/**
 * UUIDTest class
 */
class UUIDTest extends TestCase
{
    /**
     * Check isValid method work true.
     *
     * @return void
     */
    public function testIsValidUUID()
    {
        $uuid_v3 = '7d282876-cf93-347f-96ac-ab7cca377fd5';
        $uuid_v4 = 'fab4c0ca-86d9-441c-aa70-77cc397648d5';
        $uuid_v5 = 'b45f7e23-7376-58c6-a8a4-eb70eecc5fee';
        
        $invalid_uuid = 'b45f7e04-86d9-58c6-58a7-eb70eecc5f5w';

        $this->assertTrue(UUID::isValid($uuid_v3));
        $this->assertTrue(UUID::isValid($uuid_v4));
        $this->assertTrue(UUID::isValid($uuid_v5));

        $this->assertFalse(UUID::isValid($invalid_uuid));
    }

    /**
     * Test to generate UUID v3
     *
     * @return void
     */
    public function testGenerateUUIDV3()
    {
        // uuid with default namespace
        $uuid_v3        = UUID::v3('Farshad Hassani');
        $expected_value = 'bfdfe4e0-2a8f-3f98-8e80-227b32bfbba0';

        $this->assertSame($expected_value, $uuid_v3);
        $this->assertTrue(UUID::isValid($uuid_v3));
        
        // uuid with custome namespace
        // we use from expected value in previous step as a custom namespace
        $uuid_v3        = UUID::v3('Farshad Hassani', $expected_value);
        $expected_value = '1be5b03e-039b-3677-a08d-ae647c1a2330';

        $this->assertSame($expected_value, $uuid_v3);
        $this->assertTrue(UUID::isValid($uuid_v3));
    }

    /**
     * Test to generate UUID v4
     *
     * @return void
     */
    public function testGenerateUUIDV4()
    {
        $uuid_v4 = UUID::v4();

        $this->assertTrue(UUID::isValid($uuid_v4));
    }

    /**
     * Test to generate UUID v5
     *
     * @return void
     */
    public function testGenerateUUIDV5()
    {
        // uuid with default namespace
        $uuid_v5        = UUID::v5('Farshad Hassani');
        $expected_value = '09523ed7-51fe-52ae-9207-a668019f31f6';

        $this->assertSame($expected_value, $uuid_v5);
        $this->assertTrue(UUID::isValid($uuid_v5));
        
        // uuid with custome namespace
        // we use from expected value in previous step as a custom namespace
        $uuid_v5        = UUID::v5('Farshad Hassani', $expected_value);
        $expected_value = '04086b20-278e-5c1a-93da-2f0ac544f179';

        $this->assertSame($expected_value, $uuid_v5);
        $this->assertTrue(UUID::isValid($uuid_v5));
    }
}