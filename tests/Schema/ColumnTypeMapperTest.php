<?php

namespace Rentgen\Tests\Schema\Postgres;

use Rentgen\Schema\ColumnTypeMapper;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class ColumnTypeMapperTest extends \PHPUnit_Framework_TestCase
{
    public function testGetNative()
    {
         $columnTypeMapper = new ColumnTypeMapper();

         $this->assertEquals('character varying', $columnTypeMapper->getNative('string'));
         $this->assertEquals('text', $columnTypeMapper->getNative('text'));
         $this->assertEquals('integer', $columnTypeMapper->getNative('integer'));
         $this->assertEquals('bigint', $columnTypeMapper->getNative('biginteger'));
         $this->assertEquals('smallint', $columnTypeMapper->getNative('smallinteger'));
         $this->assertEquals('real', $columnTypeMapper->getNative('float'));
         $this->assertEquals('decimal', $columnTypeMapper->getNative('decimal'));
         $this->assertEquals('timestamp', $columnTypeMapper->getNative('datetime'));
         $this->assertEquals('date', $columnTypeMapper->getNative('date'));
         $this->assertEquals('time', $columnTypeMapper->getNative('time'));
         $this->assertEquals('bytea', $columnTypeMapper->getNative('binary'));
    }

    public function testGetCommon()
    {
        $columnTypeMapper = new ColumnTypeMapper();

         $this->assertEquals('string', $columnTypeMapper->getCommon('character varying'));
         $this->assertEquals('text', $columnTypeMapper->getCommon('text'));
         $this->assertEquals('integer', $columnTypeMapper->getCommon('integer'));
         $this->assertEquals('biginteger', $columnTypeMapper->getCommon('bigint'));
         $this->assertEquals('smallinteger', $columnTypeMapper->getCommon('smallint'));
         $this->assertEquals('decimal', $columnTypeMapper->getCommon('decimal'));
         $this->assertEquals('float', $columnTypeMapper->getCommon('real'));
         $this->assertEquals('datetime', $columnTypeMapper->getCommon('timestamp'));
         $this->assertEquals('date', $columnTypeMapper->getCommon('date'));
         $this->assertEquals('time', $columnTypeMapper->getCommon('time'));
         $this->assertEquals('binary', $columnTypeMapper->getCommon('bytea'));
    }
}
