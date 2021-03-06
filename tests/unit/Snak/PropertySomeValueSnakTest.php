<?php

namespace Wikibase\DataModel\Tests\Snak;

use InvalidArgumentException;
use PHPUnit_Framework_TestCase;
use Wikibase\DataModel\Entity\ItemId;
use Wikibase\DataModel\Entity\PropertyId;
use Wikibase\DataModel\Snak\PropertyNoValueSnak;
use Wikibase\DataModel\Snak\PropertySomeValueSnak;
use Wikibase\DataModel\Snak\Snak;

/**
 * @covers \Wikibase\DataModel\Snak\PropertySomeValueSnak
 * @covers \Wikibase\DataModel\Snak\SnakObject
 *
 * @group Wikibase
 * @group WikibaseDataModel
 * @group WikibaseSnak
 *
 * @license GPL-2.0+
 * @author Thiemo Kreuz
 */
class PropertySomeValueSnakTest extends PHPUnit_Framework_TestCase {

	/**
	 * @dataProvider validConstructorArgumentsProvider
	 */
	public function testConstructor( $propertyId ) {
		$snak = new PropertySomeValueSnak( $propertyId );
		$this->assertInstanceOf( PropertySomeValueSnak::class, $snak );
	}

	public function validConstructorArgumentsProvider() {
		return [
			[ 1 ],
			[ new PropertyId( 'P1' ) ],
			[ new PropertyId( 'P9001' ) ],
		];
	}

	/**
	 * @dataProvider invalidConstructorArgumentsProvider
	 * @expectedException InvalidArgumentException
	 */
	public function testGivenInvalidConstructorArguments_constructorThrowsException( $propertyId ) {
		new PropertySomeValueSnak( $propertyId );
	}

	public function invalidConstructorArgumentsProvider() {
		return [
			[ null ],
			[ 0.1 ],
			[ 'Q1' ],
			[ new ItemId( 'Q1' ) ],
		];
	}

	public function testGetPropertyId() {
		$snak = new PropertySomeValueSnak( new PropertyId( 'P1' ) );
		$propertyId = $snak->getPropertyId();
		$this->assertInstanceOf( PropertyId::class, $propertyId );
	}

	public function testGetHash() {
		$snak = new PropertySomeValueSnak( new PropertyId( 'P1' ) );
		$hash = $snak->getHash();
		$this->assertInternalType( 'string', $hash );
		$this->assertEquals( 40, strlen( $hash ) );
	}

	/**
	 * This test is a safeguard to make sure hashes are not changed unintentionally.
	 */
	public function testHashStability() {
		$snak = new PropertySomeValueSnak( new PropertyId( 'P1' ) );
		$hash = $snak->getHash();

		$expected = sha1( 'C:45:"Wikibase\DataModel\Snak\PropertySomeValueSnak":2:{P1}' );
		$this->assertSame( $expected, $hash );
	}

	public function testEquals() {
		$snak1 = new PropertySomeValueSnak( new PropertyId( 'P1' ) );
		$snak2 = new PropertySomeValueSnak( new PropertyId( 'P1' ) );
		$this->assertTrue( $snak1->equals( $snak2 ) );
		$this->assertTrue( $snak2->equals( $snak1 ) );
	}

	/**
	 * @dataProvider notEqualsProvider
	 */
	public function testGivenDifferentSnaks_EqualsReturnsFalse( Snak $snak1, Snak $snak2 ) {
		$this->assertFalse( $snak1->equals( $snak2 ) );
		$this->assertFalse( $snak2->equals( $snak1 ) );
	}

	public function notEqualsProvider() {
		$p1 = new PropertyId( 'P1' );

		return [
			[
				new PropertySomeValueSnak( $p1 ),
				new PropertySomeValueSnak( new PropertyId( 'P2' ) )
			],
			[
				new PropertySomeValueSnak( $p1 ),
				new PropertyNoValueSnak( $p1 )
			],
		];
	}

	public function provideDataToSerialize() {
		$p2 = new PropertyId( 'P2' );
		$p2foo = new PropertyId( 'foo:P2' );

		return [
			'string' => [
				'P2',
				new PropertySomeValueSnak( $p2 ),
			],
			'foreign' => [
				'foo:P2',
				new PropertySomeValueSnak( $p2foo ),
			],
		];
	}

	/**
	 * @dataProvider provideDataToSerialize
	 */
	public function testSerialize( $expected, Snak $snak ) {
		$serialized = $snak->serialize();
		$this->assertSame( $expected, $serialized );

		$snak2 = new PropertySomeValueSnak( new PropertyId( 'P1' ) );
		$snak2->unserialize( $serialized );
		$this->assertTrue( $snak->equals( $snak2 ), 'round trip' );
	}

	public function provideDataToUnserialize() {
		$p2 = new PropertyId( 'P2' );
		$p2foo = new PropertyId( 'foo:P2' );

		return [
			'legacy' => [ new PropertySomeValueSnak( $p2 ), 'i:2;' ],
			'current' => [ new PropertySomeValueSnak( $p2 ), 'P2' ],
			'foreign' => [ new PropertySomeValueSnak( $p2foo ), 'foo:P2' ],
		];
	}

	/**
	 * @dataProvider provideDataToUnserialize
	 */
	public function testUnserialize( $expected, $serialized ) {
		$snak = new PropertySomeValueSnak( new PropertyId( 'P1' ) );
		$snak->unserialize( $serialized );
		$this->assertTrue( $snak->equals( $expected ) );
	}

}
