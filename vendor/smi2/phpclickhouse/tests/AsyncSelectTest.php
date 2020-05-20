<?php

namespace ClickHouseDB\Tests;

use ClickHouseDB\Exception\QueryException;
use PHPUnit\Framework\TestCase;

/**
 * Class AsyncSelectTest
 * @group AsyncSelect
 * @package ClickHouseDB\Tests
 */
final class AsyncSelectTest extends TestCase
{
    use WithClient;

    public function testselectAsyncFail()
    {
        $counter=rand(150,400);
        $list=[];
        for ($f=0;$f<$counter;$f++)
        {
            $list[$f]=$this->client->selectAsync('SELECT {num} as num',['num'=>$f]);
        }
        $this->client->executeAsync();
        for ($f=0;$f<$counter;$f++)
        {
            $ResultInt=0;
            try {
                $ResultInt=$list[$f]->fetchOne('num');
            } catch (\Exception $E)
            {

            }
            $this->assertEquals($f, $ResultInt);
        }


    }
}
