<?php

/**
 * This file contains tests for the class cApiActionlogCollection.
 *
 * @package    Testing
 * @subpackage Items
 * @author     marcus.gnass
 * @copyright  four for business AG <www.4fb.de>
 * @license    http://www.contenido.org/license/LIZENZ.txt
 * @link       http://www.4fb.de
 * @link       http://www.contenido.org
 */

/**
 * This class tests the methods of the class cApiActionlogCollection.
 *
 * Some methods have data providers to keep test cases concise.
 * They return a list of data sets for several testcases.
 * e.g. [
 *     'name of data set' => [<data to be used as input>, <data to be expected as output>],
 *     ...
 * ]
 *
 * @link   https://phpunit.readthedocs.io/en/8.4/annotations.html#dataprovider
 *
 * @author marcus.gnass
 */
class cActionLogCollectionTest extends cTestingTestCase
{
    public function dataCreate()
    {
        return [
            'zeros_default'    => [
                [0, 0, 0, 0, 0, ''],
                [
                    'user_id'      => 0,
                    'idclient'     => 0,
                    'idlang'       => 0,
                    'idaction'     => 0,
                    'idcatart'     => 0,
                    'logtimestamp' => (new DateTime())->format('Y-m-d H:i:s'),
                ],
            ],
            'zeros'            => [
                [0, 0, 0, 0, 0, '1971-06-01 12:34:56'],
                [
                    'user_id'      => 0,
                    'idclient'     => 0,
                    'idlang'       => 0,
                    'idaction'     => 0,
                    'idcatart'     => 0,
                    'logtimestamp' => '1971-06-01 12:34:56',
                ],
            ],
            'nonzeros_default' => [
                [1, 2, 3, 4, 5, ''],
                [
                    'user_id'      => 1,
                    'idclient'     => 2,
                    'idlang'       => 3,
                    'idaction'     => 4,
                    'idcatart'     => 5,
                    'logtimestamp' => (new DateTime())->format('Y-m-d H:i:s'),
                ],
            ],
            'nonzeros'         => [
                [1, 2, 3, 4, 5, '1971-06-01 12:34:56'],
                [
                    'user_id'      => 1,
                    'idclient'     => 2,
                    'idlang'       => 3,
                    'idaction'     => 4,
                    'idcatart'     => 5,
                    'logtimestamp' => '1971-06-01 12:34:56',
                ],
            ],
        ];
    }

    /**
     * @dataProvider dataCreate()
     *
     * @param array|null $input  data to be used as input
     * @param array|null $output data to be expected as output
     *
     * @throws cDbException
     * @throws cException
     * @throws cInvalidArgumentException
     */
    public function testCreate(array $input = null, array $output = null)
    {
        list($userId, $idclient, $idlang, $idaction, $idcatart, $logtimestamp) = $input;
        $coll = new cApiActionlogCollection();
        $act = $coll->create($userId, $idclient, $idlang, $idaction, $idcatart, $logtimestamp);
        $this->assertNotNull($act);
        $this->assertNotEquals(0, $act->getField('idlog'));
        foreach ($output as $key => $value) {
            $this->assertEquals($value, $act->getField($key));
        }
    }
}
