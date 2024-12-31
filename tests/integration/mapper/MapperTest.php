<?php

namespace integration\mapper;

use data\ConnectionProvider;
use data\models\Customer;
use data\models\Order;
use data\models\Product;
use data\models\Shipper;
use DateTime;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use stdClass;
use Storm\Query\Mapper\Map;
use Storm\Query\Mapper\Mapper;
use Storm\Query\StormQueries;

final class MapperTest extends TestCase
{
    private static array $items;
    private static StormQueries $queries;

    public function testExceptionWherePkNotValid()
    {
        $this->expectException(InvalidArgumentException::class);
        $map = Map::create(Customer::class, 'customer_i', [
            'customer_id' => 'customer_id',
            'customer_name' => 'customer_name'
        ]);

        Mapper::map(self::$items, $map);
    }

    public function testExceptionWhenClassDoesntExist()
    {
        $this->expectException(ReflectionException::class);
        $map =
            Map::create("data\models\InvalidClass", 'customer_id', [
                'customer_id' => 'customer_id',
                'customer_name' => 'customer_name'
            ]);

        Mapper::map(self::$items, $map);
    }

    public function testFindWithMap(): void
    {
        $map = Map::create(Customer::class, 'customer_id', [
            'customer_id' => 'id',
            'customer_name' => 'name']);
        $items = self::$queries
            ->select('c.customer_id', 'c.customer_name', 'c.address')
            ->from('customers c')
            ->find($map);

        $this->assertInstanceOf(Customer::class, $items[0]);
        $this->assertCount(91, $items);
    }

    public function testFindOneWithMap(): void
    {
        $map = Map::create(Customer::class, 'customer_id', [
            'customer_id' => 'id',
            'customer_name' => 'name']);
        $item = self::$queries
            ->select('c.customer_id', 'c.customer_name', 'c.address')
            ->from('customers c')
            ->findSingle($map);

        $this->assertInstanceOf(Customer::class, $item);
    }

    public function testFlatResults(): void
    {
        $items = self::$queries
            ->select('c.customer_id', 'c.customer_name', 'c.address')
            ->from('customers c')
            ->find();

        $map = Map::create(stdClass::class, 'customer_id', [
            'customer_id' => 'id',
            'customer_name' => 'name'
        ]);

        $customers = Mapper::map($items, $map);
        $customer = array_find($customers, function($customer) {
            return $customer->id == 11;
        });

        $this->assertEquals("B's Beverages", $customer->name);
        $this->assertCount(91, $customers);
    }

    public function testHierarchicalResult(): void
    {
        $map = Map::create(Customer::class, 'customer_id', [
            'customer_id' => 'id',
            'customer_name' => 'name',
            'address' => 'address',
            'city' => 'city',
            'postal_code' => 'postalCode',
            'country' => 'country'
        ])->hasMany('orders',
            Map::create(Order::class, 'order_id', [
                'order_id' => 'id',
                'order_date' => 'date',
            ])
            ->hasMany('products',
                Map::create(Product::class, 'product_id', [
                    'product_id' => 'id',
                    'product_name' => 'name',
                    'quantity' => 'quantity'])
            )
            ->hasOne('shipper',
                Map::create(Shipper::class, 'shipper_id', [
                    'shipper_id' => 'id',
                    'shipper_name' => 'name'])
            )
        );

        $customers = Mapper::map(self::$items, $map);

        $this->assertCount(91, $customers);

        $customer = array_find($customers, function($customer) {
            return $customer->id == 10;
        });
        $this->assertCustomer($customer);

        $this->assertCount(4, $customer->orders);
        $order = array_find($customer->orders, function($order) {
            return $order->id == 10389;
        });
        $this->assertOrder($order);

        $this->assertCount(4, $order->products);
        $product = array_find($order->products, function($product) {
           return $product->id == 10;
        });
        $this->assertProduct($product);
    }

    private function assertCustomer(Customer $customer): void
    {
        $this->assertEquals(10, $customer->id);
        $this->assertEquals("Bottom-Dollar Marketse", $customer->name);
        $this->assertEquals("23 Tsawassen Blvd.", $customer->address);
        $this->assertEquals("Tsawassen", $customer->city);
        $this->assertEquals("T2F 8M4", $customer->postalCode);
        $this->assertEquals("Canada", $customer->country);
    }

    private function assertOrder(Order $order): void
    {
        $this->assertEquals(new DateTime('1996-12-20'), $order->date);
        $this->assertEquals(2, $order->shipper->id);
        $this->assertEquals("United Package", $order->shipper->name);
    }

    private function assertProduct(Product $product): void
    {
        $this->assertEquals(16, $product->quantity);
        $this->assertEquals('Ikura', $product->name);
    }

    public static function setUpBeforeClass(): void
    {
        self::$queries = ConnectionProvider::getStormQueries();
        self::$items = self::$queries
            ->select(
                'c.customer_id', 'c.customer_name', 'c.address', 'c.city', 'c.postal_code', 'c.country',
                'o.employee_id', 'o.order_id', 'o.order_date',
                's.shipper_name', 's.shipper_id',
                'od.quantity', 'od.product_id',
                'p.product_name')
            ->from('customers c')
            ->leftJoin('orders o', 'o.customer_id', 'c.customer_id')
            ->leftJoin('shippers s', 's.shipper_id', 'o.shipper_id')
            ->leftJoin('order_details od', 'od.order_id', 'o.order_id')
            ->leftJoin('products p', 'p.product_id', 'od.product_id')
            ->orderByAsc('c.customer_id')
            ->find();
    }
}