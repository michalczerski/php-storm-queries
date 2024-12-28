<?php

namespace integration\mapper;

use data\ConnectionProvider;
use data\models\mapperTypeTest\OrderWithoutProps;
use data\models\mapperTypeTest\OrderWithProps;
use data\models\mapperTypeTest\OrderWithTypedInitProps;
use data\models\mapperTypeTest\OrderWithTypedProps;
use data\models\mapperTypeTest\ProductWithoutProps;
use data\models\mapperTypeTest\ProductWithProps;
use data\models\mapperTypeTest\ProductWithTypedInitProps;
use data\models\mapperTypeTest\ProductWithTypedProps;
use data\models\mapperTypeTest\ShipperWithoutProps;
use data\models\mapperTypeTest\ShipperWithProps;
use data\models\mapperTypeTest\ShipperWithTypedInitProps;
use data\models\mapperTypeTest\ShipperWithTypedProps;
use DateTime;
use PHPUnit\Framework\TestCase;
use stdClass;
use Storm\Query\mapper\Map;
use Storm\Query\mapper\Mapper;

final class MapperTypesTest extends TestCase
{
    private static array $items;

    public function testMappingToStdClass(): void
    {
        $map = self::getMap(stdClass::class, stdClass::class, stdClass::class);

        $order = Mapper::map(self::$items, $map)[0];

        $this->assertInstanceOf(stdClass::class, $order);
        $this->assertInstanceOf(stdClass::class, $order->products[0]);
        $this->assertInstanceOf(stdClass::class, $order->shipper);
        $this->assertEquals(10389, $order->id);
        $this->assertEquals('1996-12-20', $order->date);
        $this->assertCount(4, $order->products);
        $this->assertEquals(10, $order->products[0]->id);
        $this->assertEquals('Ikura', $order->products[0]->name);
        $this->assertEquals(16, $order->products[0]->quantity);
        $this->assertEquals(2, $order->shipper->id);
        $this->assertEquals('United Package', $order->shipper->name);
    }

    public function testMappingToUserClassWithoutProperties(): void
    {
        $map = self::getMap(OrderWithoutProps::class, ProductWithoutProps::class, ShipperWithoutProps::class);

        $order = Mapper::map(self::$items, $map)[0];

        $this->assertInstanceOf(OrderWithoutProps::class, $order);
        $this->assertInstanceOf(ProductWithoutProps::class, $order->products[0]);
        $this->assertInstanceOf(ShipperWithoutProps::class, $order->shipper);
        $this->assertEquals(10389, $order->id);
        $this->assertEquals('1996-12-20', $order->date);
        $this->assertCount(4, $order->products);
        $this->assertEquals(10, $order->products[0]->id);
        $this->assertEquals('Ikura', $order->products[0]->name);
        $this->assertEquals(16, $order->products[0]->quantity);
        $this->assertEquals(2, $order->shipper->id);
        $this->assertEquals('United Package', $order->shipper->name);
    }

    public function testMappingToUserClassWithProperties(): void
    {
        $map = self::getMap(OrderWithProps::class, ProductWithProps::class, ShipperWithProps::class);

        $order = Mapper::map(self::$items, $map)[0];

        $this->assertInstanceOf(OrderWithProps::class, $order);
        $this->assertInstanceOf(ProductWithProps::class, $order->products[0]);
        $this->assertInstanceOf(ShipperWithProps::class, $order->shipper);
        $this->assertEquals(10389, $order->id);
        $this->assertEquals('1996-12-20', $order->date);
        $this->assertCount(4, $order->products);
        $this->assertEquals(10, $order->products[0]->id);
        $this->assertEquals('Ikura', $order->products[0]->name);
        $this->assertEquals(16, $order->products[0]->quantity);
        $this->assertEquals(2, $order->shipper->id);
        $this->assertEquals('United Package', $order->shipper->name);
    }

    public function testMappingToUserClassWithTypedProperties(): void
    {
        $map = self::getMap(OrderWithTypedProps::class, ProductWithTypedProps::class, ShipperWithTypedProps::class);

        $order = Mapper::map(self::$items, $map)[0];

        $this->assertInstanceOf(OrderWithTypedProps::class, $order);
        $this->assertInstanceOf(ProductWithTypedProps::class, $order->products[0]);
        $this->assertInstanceOf(ShipperWithTypedProps::class, $order->shipper);
        $this->assertEquals(10389, $order->id);
        $this->assertEquals(new DateTime('1996-12-20'), $order->date);
        $this->assertCount(4, $order->products);
        $this->assertEquals(10, $order->products[0]->id);
        $this->assertEquals('Ikura', $order->products[0]->name);
        $this->assertEquals(16, $order->products[0]->quantity);
        $this->assertEquals(2, $order->shipper->id);
        $this->assertEquals('United Package', $order->shipper->name);
    }

    public function testMappingToUserClassWithTypedAndInitializedProperties(): void
    {
        $map = self::getMap(OrderWithTypedInitProps::class, ProductWithTypedInitProps::class, ShipperWithTypedInitProps::class);

        $order = Mapper::map(self::$items, $map)[0];

        $this->assertInstanceOf(OrderWithTypedInitProps::class, $order);
        $this->assertInstanceOf(ProductWithTypedInitProps::class, $order->products[0]);
        $this->assertInstanceOf(ShipperWithTypedInitProps::class, $order->shipper);
        $this->assertEquals(10389, $order->id);
        $this->assertEquals(new DateTime('1996-12-20'), $order->date);
        $this->assertCount(4, $order->products);
        $this->assertEquals(10, $order->products[0]->id);
        $this->assertEquals('Ikura', $order->products[0]->name);
        $this->assertEquals(16, $order->products[0]->quantity);
        $this->assertEquals(2, $order->shipper->id);
        $this->assertEquals('United Package', $order->shipper->name);
    }

    private static function getMap(string $orderClassName, string $productClassName, string $shipperClassName): Map
    {
        return Map::create($orderClassName, 'order_id', [
            'order_id' => 'id',
            'order_date' => 'date',
        ])
        ->hasMany('products',
            Map::create($productClassName, 'product_id', [
                'product_id' => 'id',
                'product_name' => 'name',
                'quantity' => 'quantity'])
        )
        ->hasOne('shipper',
            Map::create($shipperClassName, 'shipper_id', [
                'shipper_id' => 'id',
                'shipper_name' => 'name'])
        );
    }

    public static function setUpBeforeClass(): void
    {
        self::$items = ConnectionProvider::getStormQueries()
            ->select(
                'o.employee_id', 'o.order_id', 'o.order_date',
                's.shipper_name', 's.shipper_id',
                'od.quantity', 'od.product_id',
                'p.product_name')
            ->from('orders o')
            ->leftJoin('shippers s', 's.shipper_id', 'o.shipper_id')
            ->leftJoin('order_details od', 'od.order_id', 'o.order_id')
            ->leftJoin('products p', 'p.product_id', 'od.product_id')
            ->where('o.order_id', '=', 10389)
            ->find();
    }
}