<?php

namespace Wikibase\DataModel\Tests\Statement;

use PHPUnit_Framework_TestCase;
use Wikibase\DataModel\Statement\StatementRank;

/**
 * @covers Wikibase\DataModel\Statement\StatementRank
 *
 * @group Wikibase
 * @group WikibaseDataModel
 * @group WikibaseStatement
 *
 * @licence GNU GPL v2+
 * @author Thiemo Mättig
 */
class StatementRankTest extends PHPUnit_Framework_TestCase {

	public function testConstants() {
		$this->assertSame( 0, StatementRank::DEPRECATED );
		$this->assertSame( 1, StatementRank::NORMAL );
		$this->assertSame( 2, StatementRank::PREFERRED );
	}

	public function testGetNames() {
		$this->assertSame( array(
			'deprecated',
			'normal',
			'preferred',
		), StatementRank::getNames() );
	}

	public function testGetAllRanks() {
		$this->assertSame( array(
			'deprecated' => 0,
			'normal' => 1,
			'preferred' => 2,
		), StatementRank::getAllRanks() );
	}

	/**
	 * @dataProvider notInIntegerRangeProvider
	 */
	public function testGivenInvalidRank_assertIsValidThrowsException( $rank ) {
		$this->setExpectedException( 'InvalidArgumentException' );
		StatementRank::assertIsValid( $rank );
	}

	/**
	 * @dataProvider integerRangeProvider
	 */
	public function testGivenValidRank_assertIsValidSucceeds( $rank ) {
		StatementRank::assertIsValid( $rank );
		$this->assertTrue( true );
	}

	/**
	 * @dataProvider notInIntegerRangeProvider
	 */
	public function testGivenInvalidRank_isValidFails( $rank ) {
		$this->assertFalse( StatementRank::isValid( $rank ) );
	}

	/**
	 * @dataProvider integerRangeProvider
	 */
	public function testGivenInvalidRank_isValidSucceeds( $rank ) {
		$this->assertTrue( StatementRank::isValid( $rank ) );
	}

	/**
	 * @dataProvider notInIntegerRangeProvider
	 */
	public function testGivenInvalidRank_isFalseThrowsException( $rank ) {
		$this->setExpectedException( 'InvalidArgumentException' );
		StatementRank::isFalse( $rank );
	}

	/**
	 * @dataProvider isFalseProvider
	 */
	public function testIsFalse( $rank, $expected ) {
		$this->assertSame( $expected, StatementRank::isFalse( $rank ) );
	}

	public function isFalseProvider() {
		return array(
			array( 0, true ),
			array( 1, false ),
			array( 2, false ),
		);
	}

	/**
	 * @dataProvider invalidComparisonPairProvider
	 */
	public function testGivenInvalidRank_isEqualThrowsException( $rank1, $rank2 ) {
		$this->setExpectedException( 'InvalidArgumentException' );
		StatementRank::isEqual( $rank1, $rank2 );
	}

	/**
	 * @dataProvider isEqualProvider
	 */
	public function testIsEqual( $rank1, $rank2, $expected ) {
		$this->assertSame( $expected, StatementRank::isEqual( $rank1, $rank2 ) );
	}

	public function isEqualProvider() {
		return array(
			array( null, null, true ),
			array( null, 0, false ),
			array( null, 1, false ),
			array( null, 2, false ),
			array( 0, null, false ),
			array( 0, 0, true ),
			array( 0, 1, false ),
			array( 0, 2, false ),
			array( 1, null, false ),
			array( 1, 0, false ),
			array( 1, 1, true ),
			array( 1, 2, false ),
			array( 2, null, false ),
			array( 2, 0, false ),
			array( 2, 1, false ),
			array( 2, 2, true ),
		);
	}

	/**
	 * @dataProvider invalidComparisonPairProvider
	 */
	public function testGivenInvalidRank_isLowerThrowsException( $rank1, $rank2 ) {
		$this->setExpectedException( 'InvalidArgumentException' );
		StatementRank::isLower( $rank1, $rank2 );
	}

	/**
	 * @dataProvider isLowerProvider
	 */
	public function testIsLower( $rank1, $rank2, $expected ) {
		$this->assertSame( $expected, StatementRank::isLower( $rank1, $rank2 ) );
	}

	public function isLowerProvider() {
		return array(
			array( null, null, false ),
			array( null, 0, true ),
			array( null, 1, true ),
			array( null, 2, true ),
			array( 0, null, false ),
			array( 0, 0, false ),
			array( 0, 1, true ),
			array( 0, 2, true ),
			array( 1, null, false ),
			array( 1, 0, false ),
			array( 1, 1, false ),
			array( 1, 2, true ),
			array( 2, null, false ),
			array( 2, 0, false ),
			array( 2, 1, false ),
			array( 2, 2, false ),
		);
	}

	/**
	 * @dataProvider invalidComparisonPairProvider
	 */
	public function testGivenInvalidRank_isHigherThrowsException( $rank1, $rank2 ) {
		$this->setExpectedException( 'InvalidArgumentException' );
		StatementRank::isHigher( $rank1, $rank2 );
	}

	/**
	 * @dataProvider isHigherProvider
	 */
	public function testIsHigher( $rank1, $rank2, $expected ) {
		$this->assertSame( $expected, StatementRank::isHigher( $rank1, $rank2 ) );
	}

	public function isHigherProvider() {
		return array(
			array( null, null, false ),
			array( null, 0, false ),
			array( null, 1, false ),
			array( null, 2, false ),
			array( 0, null, true ),
			array( 0, 0, false ),
			array( 0, 1, false ),
			array( 0, 2, false ),
			array( 1, null, true ),
			array( 1, 0, true ),
			array( 1, 1, false ),
			array( 1, 2, false ),
			array( 2, null, true ),
			array( 2, 0, true ),
			array( 2, 1, true ),
			array( 2, 2, false ),
		);
	}

	/**
	 * @dataProvider invalidComparisonPairProvider
	 */
	public function testGivenInvalidRank_compareThrowsException( $rank1, $rank2 ) {
		$this->setExpectedException( 'InvalidArgumentException' );
		StatementRank::compare( $rank1, $rank2 );
	}

	/**
	 * @dataProvider compareProvider
	 */
	public function testCompare( $rank1, $rank2, $expected ) {
		$this->assertSame( $expected, StatementRank::compare( $rank1, $rank2 ) );
	}

	public function compareProvider() {
		return array(
			array( null, null, 0 ),
			array( null, 0, -1 ),
			array( null, 1, -1 ),
			array( null, 2, -1 ),
			array( 0, null, 1 ),
			array( 0, 0, 0 ),
			array( 0, 1, -1 ),
			array( 0, 2, -1 ),
			array( 1, null, 1 ),
			array( 1, 0, 1 ),
			array( 1, 1, 0 ),
			array( 1, 2, -1 ),
			array( 2, null, 1 ),
			array( 2, 0, 1 ),
			array( 2, 1, 1 ),
			array( 2, 2, 0 ),
		);
	}

	/**
	 * @dataProvider neitherIntegerRangeNorNullProvider
	 */
	public function testGivenInvalidRank_findBestRankThrowsException( $rank ) {
		$this->setExpectedException( 'InvalidArgumentException' );
		StatementRank::findBestRank( $rank );
	}

	/**
	 * @dataProvider invalidComparisonPairProvider
	 */
	public function testGivenInvalidArray_findBestRankThrowsException( $rank1, $rank2 ) {
		$this->setExpectedException( 'InvalidArgumentException' );
		StatementRank::findBestRank( array( $rank1, $rank2 ) );
	}

	/**
	 * @dataProvider findBestRankProvider
	 */
	public function testFindBestRank( $ranks, $expected ) {
		$this->assertSame( $expected, StatementRank::findBestRank( $ranks ) );
	}

	public function findBestRankProvider() {
		return array(
			array( null, null ),
			array( 0, 0 ),
			array( 1, 1 ),
			array( array(), null ),
			array( array( null ), null ),
			array( array( 0 ), 0 ),
			array( array( 1 ), 1 ),
			array( array( null, 0 ), 0 ),
			array( array( 0, null ), 0 ),
			array( array( 0, 1 ), 1 ),
			array( array( null, 0, 1, 2 ), 2 ),
			array( array( 2, 1, 0, null ), 2 ),
		);
	}

	public function integerRangeProvider() {
		return array(
			array( 0 ),
			array( 1 ),
			array( 2 ),
		);
	}

	public function neitherIntegerRangeNorNullProvider() {
		return array(
			array( false ),
			array( true ),
			array( NAN ),
			array( INF ),
			array( '0' ),
			array( '1' ),
			array( 0.0 ),
			array( 1.0 ),
			array( -1 ),
			array( 3 ),
		);
	}

	public function notInIntegerRangeProvider() {
		$invalid = $this->neitherIntegerRangeNorNullProvider();
		$invalid[] = array( null );
		return $invalid;
	}

	public function invalidComparisonPairProvider() {
		$invalid = $this->neitherIntegerRangeNorNullProvider();
		$pairs = array();

		foreach ( $invalid as $args ) {
			$pairs[] = array( 1, $args[0] );
			$pairs[] = array( $args[0], 1 );
		}

		return $pairs;
	}

}
