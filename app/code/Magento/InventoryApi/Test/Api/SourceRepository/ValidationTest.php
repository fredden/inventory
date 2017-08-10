<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\InventoryApi\Test\Api\SourceRepository;

use Magento\Framework\Webapi\Exception;
use Magento\Framework\Webapi\Rest\Request;
use Magento\InventoryApi\Api\Data\SourceInterface;
use Magento\TestFramework\TestCase\WebapiAbstract;

class ValidationTest extends WebapiAbstract
{
    /**#@+
     * Service constants
     */
    const RESOURCE_PATH = '/V1/inventory/source';
    const SERVICE_NAME = 'inventorySourceRepositoryV1';
    /**#@-*/

    /**
     * @param string $field
     * @param array $expectedErrorData
     * @dataProvider dataProviderRequiredFields
     */
    public function testCreateWithoutRequiredFields($field, array $expectedErrorData)
    {
        $data = [
            SourceInterface::NAME => 'source-name',
            SourceInterface::POSTCODE => 'source-postcode',
        ];
        unset($data[$field]);

        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH,
                'httpMethod' => Request::HTTP_METHOD_POST,
            ],
            'soap' => [
                'service' => self::SERVICE_NAME,
                'operation' => self::SERVICE_NAME . 'Save',
            ],
        ];

        try {
            $this->_webApiCall($serviceInfo, ['source' => $data], null, 'all');
            $this->fail('Expected throwing exception');
        } catch (\SoapFault $e) {
            self::assertContains($expectedErrorData['message'], $e->getMessage());
        } catch (\Exception $e) {
            $errorData = $this->processRestExceptionResult($e);
            self::assertEquals($expectedErrorData, $errorData);
            self::assertEquals(Exception::HTTP_BAD_REQUEST, $e->getCode());
        }
    }

    /**
     * @param string $field
     * @param array $expectedErrorData
     * @dataProvider dataProviderRequiredFields
     */
    public function testCreateWithEmptyRequiredFields($field, array $expectedErrorData)
    {
        $data = [
            SourceInterface::NAME => 'source-name',
            SourceInterface::POSTCODE => 'source-postcode',
        ];
        $data[$field] = null;

        $serviceInfo = [
            'rest' => [
                'resourcePath' => self::RESOURCE_PATH,
                'httpMethod' => Request::HTTP_METHOD_POST,
            ],
            'soap' => [
                'service' => self::SERVICE_NAME,
                'operation' => self::SERVICE_NAME . 'Save',
            ],
        ];

        try {
            $this->_webApiCall($serviceInfo, ['source' => $data], null, 'all');
            $this->fail('Expected throwing exception');
        } catch (\SoapFault $e) {
            self::assertContains($expectedErrorData['message'], $e->getMessage());
        } catch (\Exception $e) {
            $errorData = $this->processRestExceptionResult($e);
            self::assertEquals($expectedErrorData, $errorData);
            self::assertEquals(Exception::HTTP_BAD_REQUEST, $e->getCode());
        }
    }

    /**
     * @return array
     */
    public function dataProviderRequiredFields()
    {
        return[
            'without_' . SourceInterface::NAME => [
                SourceInterface::NAME,
                [
                    'message' => '"%1" can not be empty.',
                    'parameters' => [
                        SourceInterface::NAME,
                    ],
                ],
            ],
            'without_' . SourceInterface::POSTCODE => [
                SourceInterface::POSTCODE,
                [
                    'message' => '"%1" can not be empty.',
                    'parameters' => [
                        SourceInterface::POSTCODE,
                    ],
                ],
            ],
        ];
    }
}